<?php
$servername = "localhost";
$username =  "root";
$password = "root";
$dbname = "bdfactura";
// Create connection


// checking connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Attempt create table query execution
$sql= "ALTER TABLE `invoices` ADD OBSERVA VARCHAR(250)   NOT NULL AFTER `ESTADO`";

if ($conn->query($sql) === TRUE) {
    echo "Table employees created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>