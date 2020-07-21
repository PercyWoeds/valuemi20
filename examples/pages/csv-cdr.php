 <?php 



$servername = "localhost";
$username =  "root";
$password = "root";
$dbname = "bdfactura";
define('NUM_ITEMS_BY_PAGE', 20);


$con = mysqli_connect($servername,$username,$password,$dbname) ;


  $q="SELECT * FROM invoices where fecha BETWEEN '2020-01-01' AND '2020-01-31';";

 $resultxls= mysqli_query($con, $q);

 if  (!empty($resultxls)) {
 
 $resultfile = array_to_csv_download($resultxls);


}else {

	echo "archivo invalida";

}


function array_to_csv_download($array, $filename = "Nobal1.csv", $delimiter=";") 
    {
    header( 'Content-Type: application/csv' );
    header( 'Content-Disposition: attachment; filename="' . $filename . '";' );

    // clean output buffer
    ob_end_clean();

  
    $data="";
    // use keys as column titles
    while($row = $array->fetch_array()) {
    	

  $data .= $row['fecha'].",".$row['serie'].",".$row['numero'].",".$row['importe'].",".$row['ESTADO'].",".$row['OBSERVA']."\n";
}

echo $data; 
   
    // use exit to get rid of unexpected output afterward
    exit();
}

    

  ?>

  