
<?php

use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/numeroletras.php';


$see = require __DIR__.'/configcm.php';



// Emisor
$address = new Address();
$address->setUbigueo('150142')
    ->setDepartamento('LIMA')
    ->setProvincia('LIMA')
    ->setDistrito('VILLA EL SALVADOR')
    ->setUrbanizacion('')
    ->setDireccion('URB.PACHACAMAC MZA. C2 LOTE. 12 BAR.2 SEC. 1 IV ETA.');

$company = new Company();
$company->setRuc('20545339006')
    ->setRazonSocial('CODISCOM S.A.C.')
    ->setNombreComercial('CODISCOM S.A.C.')
    ->setAddress($address);





$linea = 0;
//Abrimos nuestro archivo

	//*** Procesa datos      **//

	// Venta
   // Cliente
		$client = new Client();
		$client->setTipoDoc('6')
    	->setNumDoc('20100111838')
    	->setRznSocial('GRIFOS ESPINOZA S.A.');
    	
    	

		$date_string = '21-09-2019';
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 5112,654.55
		    ->setTipoDoc('01')
		    ->setSerie('FF03')
		    ->setCorrelativo('30' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('152.54')) //VALOR VENTA 
		    ->setMtoIGV(floatval('27.46')) //IGV
		    ->setTotalImpuestos(floatval('27.46')) //IGV 
		    ->setValorVenta(floatval('152.54'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('180.00')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('180.00') ;


		$item = (new SaleDetail())
		    ->setCodProducto('02') //CODIGO PRODUCTO
		    ->setUnidad('NIU')
		    ->setCantidad('1.0')
		    ->setDescripcion('SERVICIO DE TRANSPORTE DE FLETE DE 1000 GLNS')
		    ->setMtoBaseIgv(floatval('152.54'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('27.46'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('27.46'))
		    ->setMtoValorVenta(floatval('152.54'))
		    ->setMtoValorUnitario(floatval('152.54')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('180.00')); //PRECIO CON IGV 


	$desc = <<<XML
DES 1
DES 2
DES 3
XML;



		$legend = (new Legend())
		    ->setCode('1000')
		    ->setValue($letras);

		$invoice->setDetails([ $item   ])
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

	