<?php
    $display_name = $db->cell('select display_name from users where id=?', $_SESSION['user_id']);
?>

<div class="wrapper">
    <h2>Welcome, <b><?= $display_name ?></b>!</h2>

    <p>
        <a href="?p=view" class="btn btn-info">View</a>
        <a href="?p=reset" class="btn btn-warning">Reset</a>
        <a href="?p=logout" class="btn btn-danger">Sign Out</a>
    </p>
</div>