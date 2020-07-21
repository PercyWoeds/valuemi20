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
$sql = "CREATE TABLE invoices(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,

    td VARCHAR(2) NOT NULL,
    fecha DATE NOT NULL ,
    turno VARCHAR(2) NOT NULL,
    cod_emp VARCHAR(4) NOT NULL,
    caja VARCHAR(2) NOT NULL,
    serie VARCHAR(6) NOT NULL,
    numero  VARCHAR(10) NOT NULL,
    cod_cli VARCHAR(11) NOT NULL,
    ruc  VARCHAR(11) NOT NULL,
    placa VARCHAR(50) NOT NULL,
    odometro VARCHAR(50) NOT NULL,
    cod_prod VARCHAR(2) NOT NULL,
    cantidad DECIMAL(14,2)  DEFAULT 0,
	precio DECIMAL(14,2)  DEFAULT 0,
	importe DECIMAL(14,2)  DEFAULT 0,
	igv DECIMAL(14,2)  DEFAULT 0,
	fpago  VARCHAR(2) NOT NULL,
    implista DECIMAL(14,2)  DEFAULT 0,
    cod_tar VARCHAR(2) NOT NULL,
    km VARCHAR(2) NOT NULL,
    chofer  VARCHAR(50) NOT NULL,
	tk_devol  VARCHAR(50) NOT NULL,
	cod_sucu VARCHAR(2) NOT NULL,
	isla  VARCHAR(2) NOT NULL,
   	dni_cli   	VARCHAR(8) NOT NULL,
   	tipo  	VARCHAR(2) NOT NULL,
   	producto VARCHAR(250) NOT NULL,
   	razon varchar(250) NOT NULL, 
   	unidad varchar(3) NOT NULL 

)";

if ($conn->query($sql) === TRUE) {
    echo "Table invoices created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>