<?php
namespace Phppot;
use Greenter\Ws\Services\ConsultCdrService;
use Greenter\Ws\Services\SoapClient;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/vendor/autoload.php';


$servername = "localhost";
$username =  "root";
$password = "root";
$dbname = "bdfactura";
define('NUM_ITEMS_BY_PAGE', 20);


$con = mysqli_connect($servername,$username,$password,$dbname) ;

//importar informacion



?>


<!DOCTYPE html>
<html>

<head>
    <title>Factura Electronica </title>
   
    <script type="text/javascript" src="/bootstrap/js/jquery.min.js"></script>
    <script type="text/javascript" src="/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/bootstrap/css/bootstrap.min.css">
    
    <h3 class=”text-primary mt-5"> Factura electronica </h3>

</head>





<body>
   

<div class=”col-lg-10 offset-lg-2 content-wrapper”>
<div class=”row”>
<div class=”col-lg-12">
<div class=”card”>
<div class=”card-block”>

<table class=”table table-hover”>

                <thead>
                    <tr class=”text-primary”>
                        <th>Nro</th>
                        <th>Td</th>
                        <th>Fecha</th>
                         <th>Turno</th>
                        <th>Serie</th>
                        <th>Numero</th>
                        <th>Ruc</th>
                        <th>Placa</th>
                        <th>Importe</th>


                        <th>Estado </th>
                        <th>Observacion </th>
                        <th> </th>
                        <th> </th>

                       
                    </tr>

                </thead>

                    <?php 
                        $count=0;

                        if(isset($_GET['page_count']))
                        {
                            $count=1;
                            $page_count=$_GET['page_count']-1;
                            $count=$page_count*20;
                        }

                        $q="SELECT * from invoices LIMIT $count,20";

                        $result = mysqli_query($con, $q);

                        if  (!empty($result)) {
                        $j=0;

                        while($data=$result->fetch_array())

                        { $j=$j+1;
                    ?>
                    <tbody>


                    <tr>
                        <tr>
                            <th scope=”row”>
                             </th>   

                        <td><?php echo $j ?></td>
                    
                        <td><?php  echo $data['td']; ?></td>
                        <td><?php  echo $data['fecha']; ?></td>

                        <td><?php  echo $data['turno']; ?></td>
                        
                        <td><?php  echo $data['serie']; ?></td>
                        <td><?php  echo $data['numero']; ?></td>
                        <td><?php  echo $data['ruc']; ?></td>
                        
                        <td><?php  echo $data['importe']; ?></td>
                        
                          
                        <td><?php  echo $data['ESTADO']; ?></td>
                        
                         
                        <td><?php  echo $data['OBSERVA']; ?></td>
                        
                      

            
                    </tr>
                    </tbody>
                

                    <?php } }?>


              </table>

        </div>
</div>
</div>
</div>

              <ul class="pagination">
                    <?php 

                        $q="SELECT count(id)  from invoices ";

                        $result = mysqli_query($con, $q);

                             if  (!empty($result)) {
                        
                        $data=$result->fetch_array();

                        $total=$data[0];
                        $total_page=ceil($total/20);

                        if($total_page>1)
                        {
                            for($i=1;$i<=$total_page;$i++)
                            {
                    ?>

        <li class="active"><a href="view.php?page_count=<?php echo $i ;?>" name="page_count" id="page_count"><?php echo $i; ?></a></li>
                    <?php
                            }
                        }  
                        } 
                    ?>
                </ul>
            



 <h2>Importa informacion </h2>
  
        <div class="row">

            <form class="form-horizontal" action="" method="post"
                name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
                <div class="input-row">
                    <label class="col-md-4 control-label">Archivo CSV
                        File</label> 
                        <input type="file" name="filecsv"  id="filecsv" accept=".csv">
                    <button type="submit" id="submit" name="import"
                        class="btn-submit">Importar</button>
                    <br />

                </div>

            </form>

        </div>


        <div class="row">


            <a href="examples/pages/buscar-cdr.php">Consulta CDR <i class="fa fa-external-link-alt"></i></a>

            <a href="examples/pages/csv-cdr.php">Descarga excel  <i class="fa fa-external-link-alt"></i></a>

            
        </div>




<?php

if (isset($_POST["import"])) {

  echo 'file name ';
    
    $fileName = $_FILES["filecsv"]["tmp_name"];

    echo "datos ";
 
    
    if ($_FILES["filecsv"]["size"] > 0) {
      
        
        $filecsv = fopen($fileName, "r");

        echo  "impotadoan arcjiovo ";

        $sqldelete = " DELETE  FROM invoices ";
       // $resultd = mysqli_query($con, $sqldelete);
            

        while (($column = fgetcsv($filecsv, 10000, ",")) !== FALSE) {

          $csvDate = $column[1] ;
          $csvDate = strtotime($csvDate);
      $newCSVDate = date("Y-m-d",$csvDate);
     

            $sqlInsert = "INSERT into invoices (td,fecha,turno,cod_emp,caja,serie,numero, cod_cli,ruc,placa,odometro,cod_prod,cantidad,precio,importe,igv,fpago,  implista,cod_tar,km,chofer,tk_devol,cod_sucu,isla,dni_cli,tipo,producto,razon,unidad,ESTADO,OBSERVA)
                   values ('" . $column[0] . "',
                   '" . $newCSVDate  . "',
                   '" . $column[2] . "',
                   '" . $column[3] . "',
                   '" . $column[4] . "',
                   '" . $column[5] . "',
                   '" . $column[6] . "',
                   '" . $column[7] . "',
                   '" . $column[8] . "',
                   '" . $column[9] . "',
                   '" . $column[10] . "',
                   '" . $column[11] . "',
                   '" . $column[12] . "',
                   '" . $column[13] . "',
                   '" . $column[14] . "',
                   '" . $column[15] . "',
                   '" . $column[16] . "',
                   '" . $column[17] . "',
                   '" . $column[18] . "',
                   '" . $column[19] . "',
                   '" . $column[20] . "',
                   '" . $column[21] . "',
                   '" . $column[22] . "',
                   '" . $column[23] . "',
                   '" . $column[24] . "',
                   '" . $column[25] . "',
                   '" . $column[26] . "',
                   '" . $column[27] . "',
                   '" . $column[28] . "',
                   '" . $column[29] . "',
                   '" . $column[30] . "' )";


            $result = mysqli_query($con, $sqlInsert);
            
            if (! empty($result)) {
                $type = "success";
                $message = "CSV Data Imported into the Database";
            } else {
                $type = "error";
                $message =  mysqli_error( $con ) ;

            }
        }
    }
}

?>


<script type="text/javascript">
$(document).ready(function() {
    $("#frmCSVImport").on("submit", function () {

        $("#response").attr("class", "");
        $("#response").html("");
        var fileType = ".csv";
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + fileType + ")$");
        if (!regex.test($("#filecsv").val().toLowerCase())) {
                $("#response").addClass("error");
                $("#response").addClass("display-block");
            $("#response").html("Invalid File. Upload : <b>" + fileType + "</b> Files.");
            return false;
        }
        return true;
    });
});
</script>
</body> 

</html>