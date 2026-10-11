<?php

$servername = "localhost";
$dbname = "itwqfbgt_ethnir";
$username = "itwqfbgt_ethnir";
$password = "Itsmejhan1";

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    $conn = false; // controllers/views already handle failed queries
}

$currentDomain = $_SERVER['SERVER_NAME'] ?? '';
$BaseURL = ($currentDomain !== '' ? "https://" . $currentDomain . "/" : "/");
