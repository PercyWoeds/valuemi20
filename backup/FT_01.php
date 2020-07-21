
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
    	->setNumDoc('20501683109')
    	->setRznSocial('CONSORCIO KINZUKO  S.A.C');
    	
    	

		$date_string = '28-09-2019';
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 5112,654.55
		    ->setTipoDoc('01')
		    ->setSerie('FT01')
		    ->setCorrelativo('10' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('25.42')) //VALOR VENTA 
		    ->setMtoIGV(floatval('4.58')) //IGV
		    ->setTotalImpuestos(floatval('4.58')) //IGV 
		    ->setValorVenta(floatval('25.42'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('30.00')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('30.00') ;



		

		$item = (new SaleDetail())
		    ->setCodProducto('0000000120710') //CODIGO PRODUCTO
		    ->setUnidad('NIU')
		    ->setCantidad('2.0')
		    ->setDescripcion('AGUA SAN CARLOS 500ML')
		    ->setMtoBaseIgv(floatval('1.69'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('0.31'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('0.31'))
		    ->setMtoValorVenta(floatval('1.69'))
		    ->setMtoValorUnitario(floatval('0.85')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('1.00')); //PRECIO CON IGV 


	$desc = <<<XML
DES 1
DES 2
DES 3
XML;


$item2 = (new SaleDetail())
		    ->setCodProducto('0000077536495') //CODIGO PRODUCTO
		    ->setUnidad('NIU')
		    ->setCantidad('1.0')
		    ->setDescripcion('CUSQUEÑA TRIGO 330 ML')
		    ->setMtoBaseIgv(floatval('23.73'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('4.27'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('4.27'))
		    ->setMtoValorVenta(floatval('23.73'))
		    ->setMtoValorUnitario(floatval('23.73')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('28.00')); //PRECIO CON IGV



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

	