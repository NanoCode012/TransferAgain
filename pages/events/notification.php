<?php

if (!isset($_GET['id'])) {
    $msgBox = error($m_eventfailedfind);
    header('Location: ?p=events', true, 303);
    exit();
} 

?>

<div class="wrapper">
    <h2>Notification</h2>
    
    <h4>
    <?php 
    $q = 'select t.*, u.display_name AS creator_name from transaction t, events e, users u where t.event_id = e.id and e.creator_id = u.id and t.user_id = ? and t.event_id = ?';
    $result = $db->row($q, $_SESSION['user_id'], $_GET['id']);
    
    if ($result['owe_amount'] > 0) {
        echo 'You owe ' . $result['creator_name'] . ' <b>' . $result['owe_amount'] . '</b>';
    } else {
        echo $result['creator_name'] . ' owes you <b>' . $result['owe_amount']*(-1) . '</b>';
    }
    ?>
    </h4>
    <a href="?p=events" class="btn btn-dark">Back</a>
</div>
