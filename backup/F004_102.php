
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
    	->setNumDoc('20509350605')
    	->setRznSocial('NOBAL TRANSPORTES SAC');
    	
    	

		$date_string = '22-10-2019';
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 5112,654.55
		    ->setTipoDoc('01')
		    ->setSerie('F004')
		    ->setCorrelativo('102' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('535.03')) //VALOR VENTA 
		    ->setMtoIGV(floatval('96.30')) //IGV
		    ->setTotalImpuestos(floatval('96.30')) //IGV 
		    ->setValorVenta(floatval('535.03'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('631.33')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('631.33') ;

		

		$item = (new SaleDetail())
		    ->setCodProducto('05') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('35.90')
		    ->setDescripcion('DieselPro B5 S50 UV')
		    ->setMtoBaseIgv(floatval('360.17'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('64.83'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('64.83'))
		    ->setMtoValorVenta(floatval('360.17'))
		    ->setMtoValorUnitario(floatval('10.03')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('11.84')); //PRECIO CON IGV 


	$desc = <<<XML
DES 1
DES 2
DES 3
XML;


$item2 = (new SaleDetail())
		    ->setCodProducto('02') //CODIGO PRODUCTO
		    ->setUnidad('LTR')
		    ->setCantidad('77.52')
		    ->setDescripcion('GLP')
		    ->setMtoBaseIgv(floatval('79.49'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('14.31'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('14.31'))
		    ->setMtoValorVenta(floatval('79.49'))
		    ->setMtoValorUnitario(floatval('1.03')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('1.21')); //PRECIO CON IGV



$item3 = (new SaleDetail())
		    ->setCodProducto('05') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('4.87')
		    ->setDescripcion('Gasohol 98')
		    ->setMtoBaseIgv(floatval('63.97'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('11.52'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('11.52'))
		    ->setMtoValorVenta(floatval('63.97'))
		    ->setMtoValorUnitario(floatval('13.13')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('15.49')); //PRECIO CON IGV




$item4 = (new SaleDetail())
		    ->setCodProducto('05') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('2.92')
		    ->setDescripcion('Gasohol 90')
		    ->setMtoBaseIgv(floatval('31.39'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('5.65'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('5.65'))
		    ->setMtoValorVenta(floatval('31.39'))
		    ->setMtoValorUnitario(floatval('10.75')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('12.69')); //PRECIO CON IGV




		$legend = (new Legend())
		    ->setCode('1000')
		    ->setValue($letras);

		$invoice->setDetails([ $item ,$item2 ,$item3,$item4 ])
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

	