<?php
$servername = "localhost";
$username =  "admin";
$password = "Ycrep2016";
$dbname = "bdfactura";
// Create connection


// checking connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = mysqli_query($conn,"SHOW TABLES FROM bdfactura");

while($table = mysql_fetch_array($sql)) { // go through each row that was returned in $result
    echo($table[0] . "<br>");    // print the table that was returned on that row.
}



$conn->close();
?>