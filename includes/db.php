<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'db_nt3101';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die('Connection Failed!'.mysqli_connect_error());
}
