
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

	//*** Procesa datos      **//

	// Venta
   // Cliente
		$client = new Client();
		$client->setTipoDoc('6')
    	->setNumDoc('20517089037')
    	->setRznSocial('PSW SA.');
    	
    	

		$date_string = '13-03-2020';
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 5112,654.55
		    ->setTipoDoc('01')
		    ->setSerie('F104')
		    ->setCorrelativo('170' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('15.42')) //VALOR VENTA 
		    ->setMtoIGV(floatval('2.78')) //IGV
		    ->setTotalImpuestos(floatval('2.78')) //IGV 
		    ->setValorVenta(floatval('15.42'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('18.20')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('18.20') ;


		$item = (new SaleDetail())
		    ->setCodProducto('7752') //CODIGO PRODUCTO
		    ->setUnidad('NIU')
		    ->setCantidad('1.00')
		    ->setDescripcion('KARINTO MANI PICANTE 100 GR')
		    ->setMtoBaseIgv(floatval('2.37'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('0.43'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('0.43'))
		    ->setMtoValorVenta(floatval('2.37'))
		    ->setMtoValorUnitario(floatval('2.37')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('2.80')); //PRECIO CON IGV 


	$desc = <<<XML
DES 1
DES 2
DES 3
XML;


$item2 = (new SaleDetail())
		    ->setCodProducto('7622') //CODIGO PRODUCTO
		    ->setUnidad('NIU')
		    ->setCantidad('1.00')
		    ->setDescripcion('RITZ 22GR')
		    ->setMtoBaseIgv(floatval('0.59'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('0.11'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('0.11'))
		    ->setMtoValorVenta(floatval('0.59'))
		    ->setMtoValorUnitario(floatval('0.59')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('0.70')); //PRECIO CON IGV



$item3 = (new SaleDetail())
		    ->setCodProducto('7752') //CODIGO PRODUCTO
		    ->setUnidad('NIU')
		    ->setCantidad('1.00')
		    ->setDescripcion('KARINTO MAMI SALADO 100GRS')
		    ->setMtoBaseIgv(floatval('2.37'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('0.43'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('0.43'))
		    ->setMtoValorVenta(floatval('2.37'))
		    ->setMtoValorUnitario(floatval('2.37')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('2.80')); //PRECIO CON IGV




$item4 = (new SaleDetail())
		    ->setCodProducto('7441') //CODIGO PRODUCTO
		    ->setUnidad('NIU')
		    ->setCantidad('1.00')
		    ->setDescripcion('GALLETA PRINCIPE CHOCOLATE')
		    ->setMtoBaseIgv(floatval('1.69'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('0.31'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('0.31'))
		    ->setMtoValorVenta(floatval('1.69'))
		    ->setMtoValorUnitario(floatval('1.69')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('2.00')); //PRECIO CON IGV




$item5 = (new SaleDetail())
		    ->setCodProducto('7550') //CODIGO PRODUCTO
		    ->setUnidad('NIU')
		    ->setCantidad('1.00')
		    ->setDescripcion('COCA COLA 1.5LT')
		    ->setMtoBaseIgv(floatval('5.93'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('1.07'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('1.07'))
		    ->setMtoValorVenta(floatval('5.93'))
		    ->setMtoValorUnitario(floatval('5.93')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('7.00')); //PRECIO CON IGV





$item6 = (new SaleDetail())
		    ->setCodProducto('7550') //CODIGO PRODUCTO
		    ->setUnidad('NIU')
		    ->setCantidad('1.00')
		    ->setDescripcion('COCA COLA ZERO 500ML')
		    ->setMtoBaseIgv(floatval('2.46'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('0.44'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('0.44'))
		    ->setMtoValorVenta(floatval('2.46'))
		    ->setMtoValorUnitario(floatval('2.46')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('2.90')); //PRECIO CON IGV

		$legend = (new Legend())
		    ->setCode('1000')
		    ->setValue($letras);

		$invoice->setDetails([ $item ,$item2,$item3,$item4,$item5,$item6  ])
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

	