<?php
$username = 'root';
$password = '';
$database = 'wwt-linker';

$mysql = mysqli_connect(_DATABASE_SERVER, _DATABASE_USERNAME, _DATABASE_PASSWORD, _DATABASE_NAME);

if (!$mysql) {
    die("Error: " . mysqli_connect_error());
}
?>