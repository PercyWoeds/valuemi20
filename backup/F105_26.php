
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
    	->setNumDoc('20536690264')
    	->setRznSocial('ENCOFRADOS ALSINA DEL PERÚ S.A.C.');
    	
    	

		$date_string = '29-10-2019';
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 5112,654.55
		    ->setTipoDoc('01')
		    ->setSerie('F105')
		    ->setCorrelativo('26' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('967.80')) //VALOR VENTA 
		    ->setMtoIGV(floatval('174.20')) //IGV
		    ->setTotalImpuestos(floatval('174.20')) //IGV 
		    ->setValorVenta(floatval('967.80'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('1142.00')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('1142.00') ;

		



$item   = (new SaleDetail())
		    ->setCodProducto('07') //CODIGO PRODUCTO
		    ->setUnidad('LTR')
		    ->setCantidad('317.8')
		    ->setDescripcion('GLP')
		    ->setMtoBaseIgv(floatval('307.01'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('55.26'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('55.26'))
		    ->setMtoValorVenta(floatval('307.01'))
		    ->setMtoValorUnitario(floatval('0.97')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('1.14')); //PRECIO CON IGV



	$desc = <<<XML
DES 1
DES 2
DES 3
XML;

		$item2 = (new SaleDetail())
		    ->setCodProducto('03') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('9.47')
		    ->setDescripcion('GASOHOL 95')
		    ->setMtoBaseIgv(floatval('109.32'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('19.68'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('19.68'))
		    ->setMtoValorVenta(floatval('109.32'))
		    ->setMtoValorUnitario(floatval('11.54')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('13.62')); //PRECIO CON IGV 



		$item3 = (new SaleDetail())
		    ->setCodProducto('03') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('20.49')
		    ->setDescripcion('GASOHOL 95')
		    ->setMtoBaseIgv(floatval('232.98'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('41.94'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('41.94'))
		    ->setMtoValorVenta(floatval('232.98'))
		    ->setMtoValorUnitario(floatval('11.37')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('13.42')); //PRECIO CON IGV 



$item4   = (new SaleDetail())
		    ->setCodProducto('07') //CODIGO PRODUCTO
		    ->setUnidad('LTR')
		    ->setCantidad('332.6')
		    ->setDescripcion('GLP')
		    ->setMtoBaseIgv(floatval('318.48'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('57.33'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('57.33'))
		    ->setMtoValorVenta(floatval('318.48'))
		    ->setMtoValorUnitario(floatval('0.96')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('1.13')); //PRECIO CON IGV




		$legend = (new Legend())
		    ->setCode('1000')
		    ->setValue($letras);

		$invoice->setDetails([ $item ,$item2,$item3 ,$item4  ])
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

	