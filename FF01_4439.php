
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
		    ->setCorrelativo('4439' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('USD')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('199.69')) //VALOR VENTA 
		    ->setMtoIGV(floatval('35.9442')) //IGV
		    ->setTotalImpuestos(floatval('35.9442')) //IGV 
		    ->setValorVenta(floatval('199.69'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('235.63')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('235.63') ;



		

		$item = (new SaleDetail())
		    ->setCodProducto('01') //CODIGO PRODUCTO
		    ->setUnidad('NIU')
		    ->setCantidad('1.00')
		    ->setDescripcion('"SERVICIO DE TRANSPORTE
DE LA VICTORIA A CALLAO
PLACA: D8A-902
VALOR REFERENCIAL: NO APLICA
ORDEN DE COMPRA NRO. 4200004774"
')
		    ->setMtoBaseIgv(floatval('199.69'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('35.9442'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('35.9442'))
		    ->setMtoValorVenta(floatval('199.69'))
		    ->setMtoValorUnitario(floatval('199.69')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('235.63')); //PRECIO CON IGV 


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

	