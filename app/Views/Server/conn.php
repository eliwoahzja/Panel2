<?php

$servername = "localhost";
$dbname = "jqdmcnvd_shaii";
$username = "jqdmcnvd_shaii";
$password = "Itsmejhan1";


$conn = mysqli_connect($servername,$username,$password,$dbname);

if(!$conn) {

die(" PROBLEM WITH CONNECTION : " . mysqli_connect_error());

}
?>