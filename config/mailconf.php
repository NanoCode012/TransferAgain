<?php
$filename = dirname(__FILE__, 4).'/secret/sendgrid-api.php';

if (file_exists($filename)) {
    require_once $filename;
} else {
    exit('Cannot find sendgrid api file!');
}

?>