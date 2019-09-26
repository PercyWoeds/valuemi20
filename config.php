<?php


// $servername = "localhost";
// $username =  "admin";
// $password = "Ycrep2016";
// $dbname = "bdfactura";
// define('NUM_ITEMS_BY_PAGE', 20);


// $con = mysqli_connect($servername,$username,$password,$dbname) ;



error_reporting(0);
define('DB_NAME', 'bdfactura');
define('DB_USER', 'admin');
define('DB_PASSWORD', 'Ycrep2016');
define('DB_HOST', 'localhost');
 
// Create connection
$db     =   new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}




?>