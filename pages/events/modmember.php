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
            <button name="add_member" type="submit" class="btn btn-primary" <?php if (count($usersAvailable) == 0) echo 'disabled'; ?>>Add</button>
            <a href="?p=events" class="btn btn-dark">Back</a>
        </div>
    </form>
</div>

<div class="wrapper">
    <h2>Remove members</h2>
    <div class="form-group">
        <label>Name</label>
        <select id="remove-selector" class="form-control" name="user_id">
            <?php foreach ($usersJoined as $user) {?>
            <option value='<?= $user['id'] ?>'><?= $user['display_name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group">
        <button name="remove_member" class="btn btn-danger" data-event-id="<?= $_GET['id'] ?>" data-toggle="modal"
            data-target="#DeleteModal" <?php if (count($usersJoined) == 0) echo 'disabled'; ?>>Remove</button>
        <a href="?p=events" class="btn btn-dark">Back</a>
    </div>
</div>

<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Remove this member?</h5>
            </div>
            <div class="modal-body">
                All expenses input by this member will be removed.
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

        var userId = $(this).data('userId');
        var eventId = $(this).data('eventId');

        if (userId !== null && userId !== '') {
            $.post('?p=events/manager', {
                event_id: eventId,
                user_id: userId,
                remove_member: true
            }).then(function() {
                location.reload();
            });
        } else {
            $modalDiv.modal('hide');
        }

    });

    // Bind to modal opening to set necessary data properties to be used to make request
    $('#DeleteModal').on('show.bs.modal', function(e) {
        var data = $(e.relatedTarget).data();
        var id = $('#remove-selector').val();
        $('.btn-ok', this).data('eventId', data.eventId);
        $('.btn-ok', this).data('userId', id);
    });
});
</script>