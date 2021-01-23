<?php

if (!isset($_GET['id']) || !isset($_GET['type'])) {
    $msgBox = error($m_eventfailedfind);
    header('Location: ?p=events', true, 303);
    exit();
} ?>

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
                    : 'select eh.id, u.display_name, eh.amount, eh.notes from events_expense_history eh, users u where eh.event_id = ? and u.id = eh.user_id';
            if ($rows = $db->run($q, $_GET['id'])) {
                foreach ($rows as $row) { ?>
                <tr>
                    <td><?= $row['display_name'] ?></td>
                    <td><?= $row['amount'] ?></td>
                    <td><?= $row['notes'] ?></td>
                    <td>
                        
                    </td>
                </tr>
            <?php }
            }
            ?>
        </tbody>
    </table>
    <a href="?p=events" class="btn btn-dark">Back</a>
</div>