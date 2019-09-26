
<?php

use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/numeroletras.php';


$see = require __DIR__.'/configtp.php';



// Emisor
$address = new Address();
$address->setUbigueo('150101')
    ->setDepartamento('LIMA')
    ->setProvincia('LIMA')
    ->setDistrito('LIMA')
    ->setUrbanizacion('')
    ->setDireccion('JR. VICTOR REINEL NRO.187 VILLA DE LA LEGUA');

$company = new Company();
$company->setRuc('20424092941')
    ->setRazonSocial('TRANSPORTES PEREDA SRL.')
    ->setNombreComercial('TRANSPORTES PEREDA SRL.')
    ->setAddress($address);



$linea = 0;
//Abrimos nuestro archivo

	//*** Procesa datos      **//

	// Venta
   // Cliente
		$client = new Client();
		$client->setTipoDoc('6')
    	->setNumDoc('20546121250')
    	->setRznSocial('STRACON S.A.');
    	
    	

		$date_string = '18-09-2019';
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 5112,654.55
		    ->setTipoDoc('01')
		    ->setSerie('FF01')
		    ->setCorrelativo('4440' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('USD')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('750.00')) //VALOR VENTA 
		    ->setMtoIGV(floatval('135.00')) //IGV
		    ->setTotalImpuestos(floatval('135.00')) //IGV 
		    ->setValorVenta(floatval('750.00'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('885.00')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('885.00') ;

		

		$item = (new SaleDetail())
		    ->setCodProducto('01') //CODIGO PRODUCTO
		    ->setUnidad('NIU')
		    ->setCantidad('1.00')
		    ->setDescripcion('""SERVICIO DE GRUA
DENTRO DE LAS INSTALACIONES DE PEREDA
ORDEN DE SERVICIO NRO. 4200005064"
"
')
		    ->setMtoBaseIgv(floatval('750.00'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('135.00'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('135.00'))
		    ->setMtoValorVenta(floatval('750.00'))
		    ->setMtoValorUnitario(floatval('750.00')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('885.00')); //PRECIO CON IGV 


	$desc = <<<XML
DES 1
DES 2
DES 3
XML;



		$legend = (new Legend())
		    ->setCode('1000')
		    ->setValue($letras);

		$invoice->setDetails([ $item])
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

	