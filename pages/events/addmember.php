<?php

if (!isset($_GET['id'])) {
    $msgBox = error($m_eventfailedfind);
    header('Location: ?p=events', true, 303);
    exit();
}

$users = $db->run(
    'select id, display_name from users where not id in (select user_id from events_members where event_id = ?) and id != ?', 
    $_GET['id'],
    $_SESSION['user_id']);

?>

<div class="wrapper">
    <h2>Add members</h2>
    <form action="?p=events/manager" method="post">  
        <div class="form-group">
            <label>Name</label>
            <select class="form-control" name="user_id">
                <?php foreach ($users as $user) {?>
                    <option value='<?= $user['id'] ?>'><?= $user['display_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <input name="event_id" type="text" value="<?= $_GET['id'] ?>" hidden>
            <button name="add_member" type="submit" class="btn btn-primary">Add</button>
            <a href="?p=events" class="btn btn-dark">Back</a>
        </div>
    </form>
</div>