<?php

use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/numeroletras.php';


$see = require __DIR__.'/config2.php';

// Cliente
$client = new Client();
$client->setTipoDoc('1')
    ->setNumDoc('00000000')
    ->setRznSocial('CLIENTE GENERICO');

// Emisor
$address = new Address();
$address->setUbigueo('150140')
    ->setDepartamento('LIMA')
    ->setProvincia('LIMA')
    ->setDistrito('SANTIADO DE SURCO')
    ->setUrbanizacion('')
    ->setDireccion('JR. MONTE FICUS NRO. 151 PROLONGACION BENAVIDES (ALT CDRA 32 DE AV.CAMINOS DEL INCA)');

$company = new Company();
$company->setRuc('20517308367')
    ->setRazonSocial('INVERSIONES NOBAL S.A.C.')
    ->setNombreComercial('INVERSIONES NOBAL S.A.C.')
    ->setAddress($address);



$linea = 0;
//Abrimos nuestro archivo
$archivo = fopen("boletas-2020-05n.csv", "r");
//Lo recorremos
while (($datos = fgetcsv($archivo, ",")) == true) 
{
  $num = count($datos);
  
  //Recorremos las columnas de esa linea
  
   
   echo "Fecha :" . $datos[1]  . "\n";
   echo "Serie : " . $datos[5]  . "\n";
   echo "Numero : ". $datos[6]  . "\n";
   echo "Codigo :"   . $datos[11]  . "\n";
   echo "Descrip :" . $datos[12]  . "\n";
   echo "Uni.Medida :" . $datos[25]  . "\n";
   
   echo "Cantidad: " . $datos[13]  . "\n";
   echo "Precio S.Igv : " . $datos[14]  . "\n";
   echo "Precio : " . $datos[15]  . "\n";
   echo "V.Venta :" . $datos[16]  . "\n";
   echo "IGV :" . $datos[17]  . "\n";
   echo "Importe : " . $datos[18]  . "\n";
   $letras =  numtoletras($datos[18]) ;

	
	//*** Procesa datos      **//

	// Venta

		$date_string = $datos[1];
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 51
		    ->setTipoDoc('03')
		    ->setSerie($datos[5] )
		    ->setCorrelativo($datos[6] )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval($datos[16]))
		    ->setMtoIGV(floatval($datos[17]))
		    ->setTotalImpuestos(floatval($datos[17]))
		    ->setValorVenta(floatval($datos[16]))
		    ->setMtoImpVenta(floatval($datos[18]))
		    
		    ->setCompany($company);

		$item = (new SaleDetail())
		    ->setCodProducto($datos[11])
		    ->setUnidad($datos[25])
		    ->setCantidad($datos[13])
		    ->setDescripcion($datos[12])
		    ->setMtoBaseIgv(floatval($datos[16]))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval($datos[17]))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval($datos[17]))
		    ->setMtoValorVenta(floatval($datos[16]))
		    ->setMtoValorUnitario(floatval($datos[14]))
		    ->setMtoPrecioUnitario(floatval($datos[15]));

		$legend = (new Legend())
		    ->setCode('1000')
		    ->setValue($letras);

		$invoice->setDetails([$item])
		        ->setLegends([$legend]);

		$result = $see->send($invoice);

		if ($result->isSuccess()) {
		    echo $result->getCdrResponse()->getDescription();
		} else {
		    var_dump($result->getError());
		}


	//*** fin procesa datos  **//



}
//Cerramos el archivo
fclose($archivo);
?>

