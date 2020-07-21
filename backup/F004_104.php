
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
    	
    	

		$date_string = '24-10-2019';
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 5112,654.55
		    ->setTipoDoc('01')
		    ->setSerie('F004')
		    ->setCorrelativo('104' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('583.81')) //VALOR VENTA 
		    ->setMtoIGV(floatval('105.08')) //IGV
		    ->setTotalImpuestos(floatval('105.08')) //IGV 
		    ->setValorVenta(floatval('583.81'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('688.89')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('688.89') ;

		

		$item = (new SaleDetail())
		    ->setCodProducto('05') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('25.43')
		    ->setDescripcion('DieselPro B5 S50 UV')
		    ->setMtoBaseIgv(floatval('255.14'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('45.93'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('45.93'))
		    ->setMtoValorVenta(floatval('255.14'))
		    ->setMtoValorUnitario(floatval('10.03')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('11.84')); //PRECIO CON IGV 


	$desc = <<<XML
DES 1
DES 2
DES 3
XML;





$item2 = (new SaleDetail())
		    ->setCodProducto('02') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('5.36')
		    ->setDescripcion('Gasohol 90')
		    ->setMtoBaseIgv(floatval('57.64'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('10.37'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('10.37'))
		    ->setMtoValorVenta(floatval('57.64'))
		    ->setMtoValorUnitario(floatval('10.75')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('12.69')); //PRECIO CON IGV



$item3 = (new SaleDetail())
		    ->setCodProducto('07') //CODIGO PRODUCTO
		    ->setUnidad('LTR')
		    ->setCantidad('140.8')
		    ->setDescripcion('GLP')
		    ->setMtoBaseIgv(floatval('144.35'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('25.98'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('25.98'))
		    ->setMtoValorVenta(floatval('144.35'))
		    ->setMtoValorUnitario(floatval('1.03')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('1.21')); //PRECIO CON IGV



$item4 = (new SaleDetail())
		    ->setCodProducto('04') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('1.17')
		    ->setDescripcion('Gasohol 98')
		    ->setMtoBaseIgv(floatval('126.68'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('22.80'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('22.80'))
		    ->setMtoValorVenta(floatval('126.68'))
		    ->setMtoValorUnitario(floatval('13.13')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('15.49')); //PRECIO CON IGV



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

	