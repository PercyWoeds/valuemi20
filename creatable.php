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
$sql = "CREATE TABLE notacab(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fecha DATE NOT NULL ,
    td VARCHAR(2) NOT NULL,
    serie VARCHAR(6) NOT NULL,
    numero  VARCHAR(10) NOT NULL,
	importe DECIMAL(14,2)  DEFAULT 0
)";

if ($conn->query($sql) === TRUE) {
    echo "Table employees created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>