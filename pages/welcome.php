<?php
    $username = $db->cell('select username from users where id=?', $_SESSION['user_id']);
?>

<div class="wrapper">
    <h1>Hi, <b><?= $username ?></b>. Welcome to our site.</h1>

    <p>
        <a href="" class="btn btn-info">View</a>
        <a href="?p=reset" class="btn btn-warning">Reset</a>
        <a href="?p=logout" class="btn btn-danger">Sign Out</a>
    </p>
</div>