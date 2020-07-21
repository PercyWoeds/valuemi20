
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
    	
    	

		$date_string = '03-09-2019';
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 5112,654.55
		    ->setTipoDoc('01')
		    ->setSerie('FF01')
		    ->setCorrelativo('4340' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('386962.00')) //VALOR VENTA 
		    ->setMtoIGV(floatval('69653.16')) //IGV
		    ->setTotalImpuestos(floatval('69653.16')) //IGV 
		    ->setValorVenta(floatval('386962.00'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('456615.16')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('456615.16') ;


		$item = (new SaleDetail())
		    ->setCodProducto('01') //CODIGO PRODUCTO
		    ->setUnidad('NIU')
		    ->setCantidad('1')
		    ->setDescripcion("SERVICIO DE TRANSPORTE  DE LIMA A MINA SAN RAFAEL PLACA: AWW-790/AKR-981 VALOR REFERENCIAL:S/.7762.20 ORDEN DE SERVICIO NRO.4200004304 ")
		    ->setMtoBaseIgv(floatval('386962.00'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('69653.16'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('69653.16'))
		    ->setMtoValorVenta(floatval('386962.00'))
		    ->setMtoValorUnitario(floatval('386962.00')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('456615.16')); //PRECIO CON IGV 


	$desc = <<<XML
DES 1
DES 2
DES 3
XML;


		$legend = (new Legend())
		    ->setCode('1000')
		    ->setValue($letras);

		$invoice->setDetails([ $item  ])
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

	