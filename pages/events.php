<div class="wrapper">
    <h2>My Events</h2>
    <div class="d-flex flex-row-reverse">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
            Add events
        </button>
        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#JoinModal">
            Join events
        </button>
    </div>
    <table data-toggle="table">
        <thead>
            <tr>
                <th>Event code</th>
                <th>Event name</th>
                <th>Status</th>
                <th>Operations</th>
            </tr>
        </thead>
        <tbody>
            <?php
          $q =
              'select e.id, e.event_name, e.event_status, e.creator_id from events e, events_members em where em.user_id = ? and e.id = em.event_id';
          if ($rows = $db->run($q, $_SESSION['user_id'])) {
              foreach ($rows as $row) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['event_name'] ?></td>
                <td>
                    <?php if ($row['event_status'] == 1) { ?>
                        <a href="" data-toggle="modal" data-target="#OpenModal" data-id="<?= $row['id'] ?>">
                            <span style="color: Red;">
                                <i class="fas fa-lock fa-2x"></i>
                            </span>
                        </a>
                    <?php } else { ?>
                        <a href="" data-toggle="modal" data-target="#CloseModal" data-id="<?= $row['id'] ?>">
                            <span style="color: Green;">
                                <i class="fas fa-lock-open fa-2x"></i>
                            </span>
                        </a>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($row['event_status'] == 0) { ?>
                        <a href="?p=events/addexpense&id=<?= $row['id'] ?>">
                            <span style="color: Blue;">
                                <i class="fas fa-plus-square fa-2x"></i>
                            </span>
                        </a>
                    <?php } ?>

                    <?php if ($row['creator_id'] == $_SESSION['user_id']) { ?>
                    <a href="?p=events/modmember&id=<?= $row['id'] ?>">
                        <span style="color: Brown;">
                            <i class="fas fa-user-circle fa-2x"></i>
                        </span>
                    </a>
                    <?php } ?>

                    <a href="?p=events/viewexpense&id=<?= $row['id'] ?>&type=0">
                        <span style="color: Darkgreen;">
                            <i class="fas fa-search fa-2x"></i>
                        </span>
                    </a>
                </td>
            </tr>
            <?php }
          }
          ?>
        </tbody>
    </table>
    <!-- <a href="?p=event/info" class="btn btn-primary">My Event</a>
    <a href="join-event.php" class="btn btn-secondary">Join Event</a>
    <a href="create-event.php" class="btn btn-success">Create Event</a>
    <a href="pending-transaction.php" class="btn btn-info">Pending Transaction</a> -->
    <a href="?p=welcome" class="btn btn-dark">Back</a>
</div>

<div class="modal fade" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action='?p=events/manager' method='post'>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="event_name">Event name</label>
                        <input class="form-control" placeholder="Name" name="event_name" type="text">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button name='create_event' type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="JoinModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action='?p=events/manager' method='post'>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Join event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="event_id">Event code</label>
                        <input class="form-control" placeholder="Code" name="event_id" type="number">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button name='join_event' type="submit" class="btn btn-primary">Join</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="CloseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Close this event?</h5>
            </div>
            <div class="modal-body">
                No expenses will be allowed to be added after the event is closed. <br><br>
                You would need to reopen if you would like to add more expenses.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button class="btn btn-danger btn-ok" data-type="1">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="OpenModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Re-open this event?</h5>
            </div>
            <div class="modal-body">
                You will be able to add new expenses.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button class="btn btn-warning btn-ok" data-type="0">Open</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Bind click to OK button within popup
    $('#CloseModal,#OpenModal').on('click', '.btn-ok', function(e) {
        var $modalDiv = $(e.delegateTarget);
        var eventId = $(this).data('eventId');
        var type = $(this).data('type');

        if (eventId !== null && eventId !== '') {
            $.post('?p=events/manager', {
                event_id: eventId,
                event_status: type,
                mod_event: true
            }).then(function() {
                location.reload();
            });
        } else {
            $modalDiv.modal('hide');
        }

    });

    // Bind to modal opening to set necessary data properties to be used to make request
    $('#CloseModal,#OpenModal').on('show.bs.modal', function(e) {
        var data = $(e.relatedTarget).data();
        $('.btn-ok', this).data('eventId', data.id);
    });
});

</script>