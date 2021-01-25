<?php

if (!isset($_GET['id'])) {
    $msgBox = error($m_eventfailedfind);
    header('Location: ?p=events', true, 303);
    exit();
} 

$count = $db->cell('select count(id) from transaction where event_id = ? and email_sent = 0', $_GET['id']);
?>

<div class="wrapper">
    <h2>View Transactions</h2>
    <div class="d-flex flex-row-reverse">
        <?php if ($count > 0) { ?>
        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#MailAllModal">
                Email all
        </button>
        <?php } ?>
    </div>
    <table data-toggle="table">
        <thead>
            <tr>
                <th data-field="name" data-sortable="true">Members</th>
                <th data-field="amount" data-sortable="true">Amount owed</th>
                <th data-field="status">Status</th>
                <th>Operations</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $q = 'select t.*, u.display_name, e.creator_id from transaction t, users u, events e where t.event_id = e.id and t.user_id = u.id and event_id = ?';
            if ($rows = $db->run($q, $_GET['id'])) {
                foreach ($rows as $row) { ?>
                <tr>
                    <td><?= $row['display_name'] ?></td>
                    <td><?= $row['owe_amount'] ?></td>
                    <td>
                        <?php if ($row['creator_id'] == $_SESSION['user_id']) { ?>
                            <input type="checkbox" value="<?= $row['id'] ?>" <?php if ($row['transaction_status']) echo 'checked'; ?>>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($row['email_sent'] == 0 && $row['transaction_status'] == 0) { ?>
                        <a href="" data-toggle="modal" data-target="#MailModal" data-id="<?= $row['id'] ?>">
                            <span style="color: Magenta;">
                                <i class="fas fa-envelope fa-2x"></i>
                            </span>
                        </a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
    <a href="?p=events" class="btn btn-dark">Back</a>
</div>

<div class="modal fade" id="MailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mail this person?</h5>
            </div>
            <div class="modal-body">
                You can only mail one person once.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button class="btn btn-info btn-ok">Send</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="MailAllModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mail everyone?</h5>
            </div>
            <div class="modal-body">
                This will take a while. Please wait.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button class="btn btn-info btn-ok" data-transaction_id="-1">Send</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('table').on('change', ':checkbox', function() {
        var transaction_id = $(this).val();
        var status = (this.checked) ? 1 : 0;
        $.post('?p=events/manager', {
            transaction_id: transaction_id,
            transaction_status: status,
            event_id: <?= $_GET['id'] ?>,
            set_transaction: true
        }).then(function() {
            location.reload();
        });
    });

    $('#MailModal,#MailAllModal').on('click', '.btn-ok', function(e) {
        var $modalDiv = $(e.delegateTarget);
        var transaction_id = $(this).data('transaction_id');
        if (transaction_id !== null && transaction_id !== '') {
            $('.btn-ok').prop('disabled', true);
            $.post('?p=events/manager', {
                transaction_id: transaction_id,
                event_id: <?= $_GET['id'] ?>,
                send_email: true
            }).then(function() {
                location.reload();
            });
        } else {
            $modalDiv.modal('hide');
        }

    });

    // Bind to modal opening to set necessary data properties to be used to make request
    $('#MailModal').on('show.bs.modal', function(e) {
        var data = $(e.relatedTarget).data();
        $('.btn-ok', this).data('transaction_id', data.id);
    });
});

</script>