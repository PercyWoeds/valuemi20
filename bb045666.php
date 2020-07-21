
<?php

use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/numeroletras.php';


$see = require __DIR__.'/configgeye.php';



// Cliente
$client = new Client();
$client->setTipoDoc('1')
    ->setNumDoc('00000000')
    ->setRznSocial('CLIENTE GENERICO');

// Emisor
$address = new Address();
$address->setUbigueo('150106')
    ->setDepartamento('LIMA')
    ->setProvincia('LIMA')
    ->setDistrito('CARABAYLLO')
    ->setUrbanizacion('PUNCHAUCA')
    ->setDireccion('AV. TUPAC AMARU KM. 22.5 LOTE. 7 URB.');

$company = new Company();
$company->setRuc('20514739065')
    ->setRazonSocial('GRUPO E & E S.A.C.')
    ->setNombreComercial('GRUPO E & E S.A.C.')
    ->setAddress($address);



$linea = 0;
//Abrimos nuestro archivo

//  $num = count($datos);
  
  //Recorremos las columnas de esa linea
  
   
//   echo "Fecha :" . $datos[1]  . "\n";
 //  echo "Serie : " . $datos[5]  . "\n";
  // echo "Numero : ". $datos[6]  . "\n";
  // echo "Razon Social : ". $datos[24]  . "\n";   
   //echo "Codigo :"   . $datos[11]  . "\n";
   //echo "Descrip :" . $datos[12]  . "\n";
   //echo "Cantidad: " . $datos[13]  . "\n";
  // echo "Precio S.Igv : " . $datos[14]  . "\n";
   //echo "Precio : " . $datos[15]  . "\n";
   //echo "V.Venta :" . $datos[16]  . "\n";
   //echo "IGV :" . $datos[17]  . "\n";
   //echo "Importe : " . $datos[18]  . "\n";
  
	//*** Procesa datos      **//

	// Venta
   // Cliente
		

		$date_string = '30-09-2019';
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 5112,654.55
		    ->setTipoDoc('03')
		    ->setSerie('BT02')
		    ->setCorrelativo('1' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('208.34 ')) //VALOR VENTA 
		    ->setMtoIGV(floatval('37.5')) //IGV
		    ->setTotalImpuestos(floatval('37.5')) //IGV 
		    ->setValorVenta(floatval('208.34 '))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('245.84 ')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('245.84') ;

	

		$item = (new SaleDetail())
		    ->setCodProducto('02') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('14.93')
		    ->setDescripcion('GAS 90')
		    ->setMtoBaseIgv(floatval('136.50'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('24.57'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('24.57'))
		    ->setMtoValorVenta(floatval('136.50'))
		    ->setMtoValorUnitario(floatval('9.14')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('10.79')); //PRECIO CON IGV 


	$desc = <<<XML
DES 1
DES 2
DES 3
XML;

$item2 = (new SaleDetail())
		    ->setCodProducto('08') //CODIGO PRODUCTO
		    ->setUnidad('LT')
		    ->setCantidad('66.19')
		    ->setDescripcion('GLP')
		    ->setMtoBaseIgv(floatval('71.84')) //VALOR VENTA ITEM
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('12.93'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('12.93'))
		    ->setMtoValorVenta(floatval('71.84')) //VALOR VENTA
		    ->setMtoValorUnitario(floatval('1.09')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('1.28')); //PRECIO CON IGV 



		$legend = (new Legend())
		    ->setCode('1000')
		    ->setValue($letras);

		$invoice->setDetails([$item,$item2])
		        ->setLegends([$legend]);

		$result = $see->send($invoice);

		if ($result->isSuccess()) {
		    echo $result->getCdrResponse()->getDescription();
		} else {
		    var_dump($result->getError());
		}


	//*** fin procesa datos  **//




//Cerramos el archivo

?>

	
