<?php session_start();

// If logged in
if (isset($_SESSION['user_id'])) {
    if (!isset($_GET['p'])) {
        $page = 'welcome';
    } else {
        $page = $_GET['p'];
    }
}
// If not logged in
else {
    if (
        isset($_GET['p']) &&
        in_array($_GET['p'], ['login', 'logout', 'register', 'authmanager'])
    ) {
        $page = $_GET['p'];
    } else {
        $page = 'login';
    }
}

$servertitle = 'TransferAgain' . ' | ' . ucwords($page);

?>
