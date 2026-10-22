<?php

$servername = "localhost";
$dbname = "jqdmcnvd_shaii";
$username = "jqdmcnvd_shaii";
$password = "Itsmejhan1";

mysqli_report(MYSQLI_REPORT_OFF);

$conn = @mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    $conn = @mysqli_connect('127.0.0.1', $username, $password, $dbname, 3306);
}

if (!$conn) {
    $conn = false;
}
