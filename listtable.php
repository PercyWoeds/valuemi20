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
$sql = " SELECT * FROM notadet ";

if ($conn->query($sql) === TRUE) {

 echo "<table>";

 while($row = mysqli_fetch_array($sql))
          {
          echo "<tr><td>" . $row['serie'] . "</td><td> " . $row['numero'] . "</td></tr>"; //these are the fields that you have stored in your database table employee
          }
 echo "</table>";


} else {
    echo "Error : " . $conn->error;
}

$conn->close();
?>