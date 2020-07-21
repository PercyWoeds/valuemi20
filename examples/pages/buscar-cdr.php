<?php
use Greenter\Ws\Services\ConsultCdrService;
use Greenter\Ws\Services\SoapClient;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../../vendor/autoload.php';

$servername = "localhost";
$username =  "root";
$password = "root";
$dbname = "bdfactura";
define('NUM_ITEMS_BY_PAGE', 20);

$errorMsg = null;
$filename = null;


function validateFields(array $items)
{
   

    global $errorMsg;
    $validateFiels = ['$rucSol', 'userSol', 'passSol', 'ruc', 'tipo', 'serie', 'numero'];
    foreach ($items as $key => $value) {
        if (in_array($key, $validateFiels) && empty($value)) {
            $errorMsg = 'El campo '.$key.', es requerido';
            return false;
        }
    }

    return true;
}

function getCdrStatusService($user, $password)
{
    $ws = new SoapClient(SunatEndpoints::FE_CONSULTA_CDR.'?wsdl');
    $ws->setCredentials($user, $password);

    $service = new ConsultCdrService();
    $service->setClient($ws);

    return $service;
}

function savedFile($filename, $content)
{
    $pathZip = __DIR__.'/../../files/'.$filename;
    file_put_contents($pathZip, $content);
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <?php include '../../views/head.php'; ?>
    <style>
        .mb-100 {
            margin-bottom: 100px;
        }
        .mb-20 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<?php include '../../views/top.php'; ?>


<div class="container mb-100">
    <div class="row">
        <?php if (isset($resultcdr)): ?>
            <div class="col-md-12">
                <div class="card mb-20">
                    <div class="card-header bg-success text-white">Resultado</div>
                    <div class="card-block">
                        <div class="card bg-light text-dark">
                            <div class="card-body">
                                <?php if ($result->isSuccess()): ?>
                                    <strong>Codigo: </strong> <?=$result->getCode()?> <br>
                                    <strong>Mensaje: </strong> <?=$result->getMessage()?> <br>
                                    <?php if (!is_null($result->getCdrResponse())):?>
                                        <strong>Estado Comprobante: </strong> <?=$result->getCdrResponse()->getDescription()?>
                                        <br>
                                        <strong>Observaciones: </strong> <?=implode('<br>', $result->getCdrResponse()->getNotes())?>
                                        <br>
                                        <?php if (!is_null($filename)): ?>
                                            <strong>CDR: </strong><br>
                                            <a href="/examples/pages/file-download.php?name=<?=$filename?>"><i class="fa fa-file-archive"></i>&nbsp;<?=$filename?></a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="alert alert-danger">
                                        <?=$result->getError()->getMessage()?>
                                    </div>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<?php endif; ?> 


<?php 


$con = mysqli_connect($servername,$username,$password,$dbname) ;


                     $rucSol = "20517308367";
                      $userSol ="FACTURA2" ;
                     $passSol = "NORMA2018";
                
            
            $estadooriginal = "-";
            $tipodocumento = "B";
            $lcSeriecdr = "B002";

            $sqlSelect = "SELECT * FROM  invoices   ";
            $sqlSelect.= "where ESTADO = '$estadooriginal' and td = '$tipodocumento' ";
         $sqlSelect.= "and serie = '$lcSeriecdr'";
          
           // $sqlSelect.= "where  td = '$tipodocumento' order by serie, fecha ";


            
                   
            $result = mysqli_query($con, $sqlSelect);
      
            
            if (! empty($result)) {
                echo "resuslto";
                    $type = "success";
                    $message = "CSV Data Imported into the Database";
                    $data_array = [];
                  
                if($result->num_rows > 0){ 


                     while($row = $result->fetch_array())
                    { 

                            $lcSerie = $row['serie'] ;
                            $lcNumero =  $row['numero'];
                            $lcEstado = "-";
                            $lcObserva = "-";

                            if ($row['td'] == 'B') {
                                $tipo = "03";
                            }


                             if ( $row['td'] =='F') {
                                $tipo = "01";
                            }

            

                            $service = getCdrStatusService($rucSol.$userSol, $passSol);

                            $arguments = [
                                $rucSol,
                                $tipo,
                                $row['serie'],
                                intval($row['numero'])
                            ];
                        
                        
                            $resultcdr =  $service->getStatus(...$arguments);

                       
                       if ($resultcdr->isSuccess()): ?>
                                    <br> 

                                     <?php 
                                     echo $row['fecha'];
                                     echo $tipo;
                                     echo $lcSerie;
                                     echo $lcNumero;
                                     ?>

                                     <br>

                                    <strong>Codigo: </strong> <?=$resultcdr->getCode()?> <br>
                                    <strong>Mensaje: </strong> <?=$resultcdr->getMessage()?> <br>

                                    <?php 
                                     $lcEstado  = $resultcdr->getCode();
                                     $lcObserva = $resultcdr->getMessage() ;
                                     ?>

                                    <?php if (!is_null($resultcdr->getCdrResponse())):?>

                                        <strong>Estado Comprobante: </strong> <?=$resultcdr->getCdrResponse()->getDescription()?>
                                        <br>
                                        <strong>Observaciones: </strong> <?=implode('<br>', $resultcdr->getCdrResponse()->getNotes())?>
                                        <br>

                                        <?php 
                                        $lcEstado  = $resultcdr->getCdrResponse()->getDescription();
                                        $lcObserva = $resultcdr->getCdrResponse()->getNotes() ;
                                         endif; ?>

                                     <?php else: ?>
                                    <div class="alert alert-danger">
                                        <?=$resultcdr->getError()->getMessage()?>

                                    </div>

                                   
                                    <?php endif; ?> 

                                     <?php      

                                
                                     
                                    
                                     $sql = "UPDATE invoices ";
                                     $sql .= "SET  ESTADO = '$lcEstado', OBSERVA = '$lcObserva' ";
                                     $sql .=  "WHERE serie ='$lcSerie' and numero ='$lcNumero'  ";

                                    
                                       if (mysqli_query($con, $sql)) {
                                          //echo "Record updated successfully";
                                       } else {
                                          echo "Error updating record: " . mysqli_error($con);
                                          echo $sql ;
                                       }
                        }

                    echo "** FINALIZO ** "; 

                    }  

                } else {
                $type = "error";
                $message =  mysqli_error( $con ) ;
                echo $message;

            }
    

?>

</div>
</div>
<?php include '../../views/footer.php'; ?>
</body>
</html>


