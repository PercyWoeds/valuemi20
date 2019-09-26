<?php


$servername = "localhost";
$username =  "admin";
$password = "Ycrep2016";
$dbname = "bdfactura";

$con = mysqli_connect($servername,$username,$password,$dbname) ;
    if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	die();
	}

	?>
