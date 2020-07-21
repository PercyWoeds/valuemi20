
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
    	->setNumDoc('20504499716')
    	->setRznSocial('KANTAR WORLDPANEL PERU S.A');
    	
    	

		$date_string = '27-09-2019';
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 5112,654.55
		    ->setTipoDoc('01')
		    ->setSerie('F104')
		    ->setCorrelativo('101' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('196.40')) //VALOR VENTA 
		    ->setMtoIGV(floatval('29.96')) //IGV
		    ->setTotalImpuestos(floatval('29.96')) //IGV 
		    ->setValorVenta(floatval('196.40'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('196.40')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('196.40') ;


		$item = (new SaleDetail())
		    ->setCodProducto('7753') //CODIGO PRODUCTO
		    ->setUnidad('NIU')
		    ->setCantidad('12.00')
		    ->setDescripcion('CUSQUEÑA TRIGO LATA')
		    ->setMtoBaseIgv(floatval('40.68'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('3.66'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('3.66'))
		    ->setMtoValorVenta(floatval('40.68'))
		    ->setMtoValorUnitario(floatval('3.39')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('4.0')); //PRECIO CON IGV 


	$desc = <<<XML
DES 1
DES 2
DES 3
XML;


$item2 = (new SaleDetail())
		    ->setCodProducto('7753') //CODIGO PRODUCTO
		    ->setUnidad('NIU')
		    ->setCantidad('12.00')
		    ->setDescripcion('CRISTAL LATA 355ML')
		    ->setMtoBaseIgv(floatval('32.54'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('5.86'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('5.86'))
		    ->setMtoValorVenta(floatval('32.54'))
		    ->setMtoValorUnitario(floatval('2.71')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('3.20')); //PRECIO CON IGV



$item3 = (new SaleDetail())
		    ->setCodProducto('7750') //CODIGO PRODUCTO
		    ->setUnidad('NIU')
		    ->setCantidad('1.00')
		    ->setDescripcion('SAN LUIS S/GAS 7LT')
		    ->setMtoBaseIgv(floatval('7.80'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('1.40'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('1.40'))
		    ->setMtoValorVenta(floatval('7.80'))
		    ->setMtoValorUnitario(floatval('7.80')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('9.20')); //PRECIO CON IGV




$item4 = (new SaleDetail())
		    ->setCodProducto('7753') //CODIGO PRODUCTO
		    ->setUnidad('NIU')
		    ->setCantidad('24.00')
		    ->setDescripcion('PILSEN LATA 355ML')
		    ->setMtoBaseIgv(floatval('85.42'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('15.38'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('15.38'))
		    ->setMtoValorVenta(floatval('85.42'))
		    ->setMtoValorUnitario(floatval('3.56')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('4.20')); //PRECIO CON IGV


		$legend = (new Legend())
		    ->setCode('1000')
		    ->setValue($letras);

		$invoice->setDetails([ $item ,$item2,$item3,$item4  ])
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

	