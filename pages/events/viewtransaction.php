<?php

if (!isset($_GET['id'])) {
    $msgBox = error($m_eventfailedfind);
    header('Location: ?p=events', true, 303);
    exit();
} 

?>

<div class="wrapper">
    <h2>View Transactions</h2>
    <table data-toggle="table">
        <thead>
            <tr>
                <th>Members</th>
                <th>Amount owed</th>
                <th>Status</th>
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
                    <td><?= $transaction_status[$row['transaction_status']] ?></td>
                    <td>
                        <?php if ($row['creator_id'] == $_SESSION['user_id']) { ?>
                        <input type="checkbox" value="<?= $row['id'] ?>" <?php if ($row['transaction_status'] == 1) echo 'checked'; ?>>
                        <?php } ?>
                    </td>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
    <a href="?p=events" class="btn btn-dark">Back</a>
</div>

<script>
$(document).ready(function() {
    $('table').on('change', ':checkbox', function() {
        var transaction_id = $(this).val();
        var status = (this.checked) ? 1 : 0;
        console.log(status);
        $.post('?p=events/manager', {
            transaction_id: transaction_id,
            transaction_status: status,
            event_id: <?= $_GET['id'] ?>,
            set_transaction: true
        }).then(function() {
            location.reload();
        });
    });
});

</script>