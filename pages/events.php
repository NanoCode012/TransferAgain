<div class="wrapper">
    <h2>My Events</h2>
    <div class="d-flex flex-row-reverse">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Modal" data-type="create">
            Add events
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
              'select e.id, e.event_name, e.event_status from events e, events_members em where em.user_id = ? and e.id = em.event_id';
          if ($rows = $db->run($q, $_SESSION['user_id'])) {
              foreach ($rows as $row) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['event_name'] ?></td>
                <td><?= $events_status[$row['event_status']] ?></td>
                <td>
                    <a type="button" href="?p=events/addexpense&id=<?= $row['id'] ?>">
                        <span style="color: Blue;">
                            <i class="fas fa-plus-square fa-2x"></i>
                        </span>
                    </a>

                    <a type="button" href="?p=events/modmember&id=<?= $row['id'] ?>">
                        <span style="color: Brown;">
                            <i class="fas fa-user-circle fa-2x"></i>
                        </span>
                    </a>

                    <a type="button" href="?p=events/viewexpense&id=<?= $row['id'] ?>&type=0">
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

<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
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