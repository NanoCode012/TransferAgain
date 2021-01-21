<?php
$filename = dirname(__FILE__, 4).'/secret/transferagain-dbconfig.php';

if (file_exists($filename)) {
    require_once $filename;
} else {
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = 'root';
    $dbname = 'transferagain';
}

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try {
    $db = \ParagonIE\EasyDB\Factory::fromArray([
        'mysql:host=' . $dbhost . ';dbname=' . $dbname,
        $dbuser,
        $dbpass,
    ]);
} catch (Exception $e) {
    exit('Caught exception when trying to connect to db ' . $e->getMessage());
}

?>
