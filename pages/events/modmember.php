<?php

if (!isset($_GET['id'])) {
    $msgBox = error($m_eventfailedfind);
    header('Location: ?p=events', true, 303);
    exit();
}

$usersAvailable = $db->run(
    'select id, display_name from users where not id in (select user_id from events_members where event_id = ?) and id != ?', 
    $_GET['id'],
    $_SESSION['user_id']);

$usersJoined = $db->run(
    'select u.id, u.display_name from events_members em, users u where em.user_id = u.id and em.event_id = ? and u.id != ?', 
    $_GET['id'],
    $_SESSION['user_id']);

?>

<div class="wrapper">
    <h2>Add members</h2>
    <form action="?p=events/manager" method="post">  
        <div class="form-group">
            <label>Name</label>
            <select class="form-control" name="user_id">
                <?php foreach ($usersAvailable as $user) {?>
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

<div class="wrapper">
    <h2>Remove members</h2>
    <form action="?p=events/manager" method="post">  
        <div class="form-group">
            <label>Name</label>
            <select class="form-control" name="user_id">
                <?php foreach ($usersJoined as $user) {?>
                    <option value='<?= $user['id'] ?>'><?= $user['display_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <input name="event_id" type="text" value="<?= $_GET['id'] ?>" hidden>
            <button name="remove_member" type="submit" class="btn btn-danger">Remove</button>
            <a href="?p=events" class="btn btn-dark">Back</a>
        </div>
    </form>
</div>