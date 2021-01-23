<?php

if (!isset($_GET['id']) || !isset($_GET['type'])) {
    $msgBox = error($m_eventfailedfind);
    header('Location: ?p=events', true, 303);
    exit();
} 

$usersJoined = $db->run(
    'select u.id, u.display_name from events_members em, users u where em.user_id = u.id and em.event_id = ?', 
    $_GET['id']);

?>

<div class="wrapper">
    <h2>View Events</h2>
    <div class="d-flex flex-row-reverse">
        <a href="?p=events/viewexpense&id=<?= $_GET['id'] ?>&type=<?= !$_GET['type'] ?>" class="btn btn-secondary">
            <?php if ($_GET['type'] == 0) {
                echo 'See individual';
            } else {
                echo 'See total';
            } ?>
        </a>
    </div>
    <table data-toggle="table">
        <thead>
            <tr>
                <th>Members</th>
                <th>Amount spent</th>
                <th>Notes</th>
                <th>Operations</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $q =
                $_GET['type'] == 0
                    ? 'CALL `Get total expense`(?)'
                    : 'select eh.id, u.display_name, eh.user_id, eh.amount, eh.notes, e.creator_id from events_expense_history eh, users u, events e where e.id = eh.event_id and eh.event_id = ? and u.id = eh.user_id';
            if ($rows = $db->run($q, $_GET['id'])) {
                foreach ($rows as $row) { ?>
                <tr>
                    <td><?= $row['display_name'] ?></td>
                    <td><?= $row['amount'] ?></td>
                    <td><?= $row['notes'] ?></td>
                    <td>
                        <?php 
                            if ($_GET['type'] == 1) {
                                if ($row['creator_id'] == $_SESSION['user_id'] || $row['user_id'] == $_SESSION['user_id'])
                                    echo editButton($row) . '&nbsp' . deleteButton($row);
                            }
                        ?>
                    </td>
                </tr>
            <?php }
            }
            ?>
        </tbody>
    </table>
    <a href="?p=events" class="btn btn-dark">Back</a>
</div>

<div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action='?p=events/manager' method='post'>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit expense</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="event_name">Member name</label>
                        <select class="form-control" name="user_id">
                            <?php foreach ($usersJoined as $user) {?>
                            <option value='<?= $user['id'] ?>'><?= $user['display_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="amount">Amount</label>
                        <input class="form-control" placeholder="Amount" name="amount" type="number">
                    </div>
                    <div class="form-group mb-3">
                        <label for="notes">Notes</label>
                        <input class="form-control" placeholder="OPTIONAL" name="notes" type="text">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-warning">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Remove this expense?</h5>
            </div>
            <div class="modal-body">
                This action is not reversible!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button class="btn btn-danger btn-ok">Remove</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Bind click to OK button within popup
    $('#DeleteModal').on('click', '.btn-ok', function(e) {
        var $modalDiv = $(e.delegateTarget);

        var expenseId = $(this).data('expenseId');

        if (expenseId !== null && expenseId !== '') {
            $.post('?p=events/manager', {
                expenseId: expenseId,
                remove_expense: true
            }).then(function() {
                location.reload();
            });
        } else {
            $modalDiv.modal('hide');
        }

    });

    // Bind to modal opening to set necessary data properties to be used to make request
    $('#DeleteModal').on('show.bs.modal', function(e) {
        var data = $(e.relatedTarget).data('service');

        $('.btn-ok', this).data('expenseId', data.id);
        console.log(data);
    });

    $('#EditModal').on('show.bs.modal', function(e) {
        var data = $(e.relatedTarget).data('service');
        var modal = $(this)

        modal.find()
        console.log(data);
    });
});
</script>