<?php

if (!isset($_GET['id'])) {
    $msgBox = error($m_eventfailedfind);
    header('Location: ?p=events', true, 303);
    exit();
}

?>

<div class="wrapper">
    <h2>Add expense</h2>
    <form action="?p=events/manager" method="post">  
        <div class="form-group">
            <label>Amount</label>
            <input type="number" name="item_amount" class="form-control">
        </div>
        <div class="form-group">
            <label>Notes</label>
            <input type="text" name="item_notes" class="form-control" placeholder="OPTIONAL">
        </div>
        <div class="form-group">
            <input name="event_id" type="text" value="<?= $_GET['id'] ?>" hidden>
            <button name="add_expense" type="submit" class="btn btn-primary">Add</button>
            <a href="?p=events" class="btn btn-dark">Back</a>
        </div>
    </form>
</div>