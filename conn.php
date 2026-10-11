<?php

$servername = "localhost";
$dbname = "itwqfbgt_ethnir";
$username = "itwqfbgt_ethnir";
$password = "Itsmejhan1";

$conn = mysqli_connect($servername,$username,$password,$dbname);

if(!$conn) {

die(" PROBLEM WITH CONNECTION : " . mysqli_connect_error());

}

$currentDomain = $_SERVER['SERVER_NAME'];
$BaseURL = "https://" . $currentDomain . "/";
  
?>