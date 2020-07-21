
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

	//*** Procesa datos      **//

	// Venta
   // Cliente
		$client = new Client();
		$client->setTipoDoc('6')
    	->setNumDoc('20538697266')
    	->setRznSocial('TRANS RICH SAC');
    	
    	

		$date_string = '28-09-2019';
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 5112,654.55
		    ->setTipoDoc('01')
		    ->setSerie('FT01')
		    ->setCorrelativo('13' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('68.14')) //VALOR VENTA 
		    ->setMtoIGV(floatval('12.26')) //IGV
		    ->setTotalImpuestos(floatval('12.26')) //IGV 
		    ->setValorVenta(floatval('68.14'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('80.40')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('80.40') ;



		

		$item = (new SaleDetail())
		    ->setCodProducto('0000000000045') //CODIGO PRODUCTO
		    ->setUnidad('NIU')
		    ->setCantidad('2.0')
		    ->setDescripcion('COCA COLA 3 LT')
		    ->setMtoBaseIgv(floatval('20.68'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('3.72'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('3.72'))
		    ->setMtoValorVenta(floatval('20.68'))
		    ->setMtoValorUnitario(floatval('10.34')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('12.2')); //PRECIO CON IGV 


	$desc = <<<XML
DES 1
DES 2
DES 3
XML;


$item2 = (new SaleDetail())
		    ->setCodProducto('0000000105053') //CODIGO PRODUCTO
		    ->setUnidad('NIU')
		    ->setCantidad('2.0')
		    ->setDescripcion('RON CARTAVIO BLACK X 1LT')
		    ->setMtoBaseIgv(floatval('47.46'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('8.54'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('8.54'))
		    ->setMtoValorVenta(floatval('47.46'))
		    ->setMtoValorUnitario(floatval('23.73	')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('28.0')); //PRECIO CON IGV



		$legend = (new Legend())
		    ->setCode('1000')
		    ->setValue($letras);

		$invoice->setDetails([ $item ,$item2  ])
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

	