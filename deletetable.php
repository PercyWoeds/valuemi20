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

// Attempt create table query execution
$sql = " DELETE  FROM invoices ";

if ($conn->query($sql) === TRUE) {
    echo "Table  delete successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>