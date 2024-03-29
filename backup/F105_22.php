
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
    	->setNumDoc('20552238401')
    	->setRznSocial('OPTIMUS LOGISTIC S.A.C.');
    	
    	

		$date_string = '02-10-2019';
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 5112,654.55
		    ->setTipoDoc('01')
		    ->setSerie('F105')
		    ->setCorrelativo('22' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('3765.76')) //VALOR VENTA 
		    ->setMtoIGV(floatval('677.84')) //IGV
		    ->setTotalImpuestos(floatval('677.84')) //IGV 
		    ->setValorVenta(floatval('3765.76'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('4443.60')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('4443.60') ;

		

		$item = (new SaleDetail())
		    ->setCodProducto('05') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('371.3')
		    ->setDescripcion('DIESEL PRO B5')
		    ->setMtoBaseIgv(floatval('3740.85'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('673.35'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('673.35'))
		    ->setMtoValorVenta(floatval('3740.85'))
		    ->setMtoValorUnitario(floatval('10.08')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('11.89')); //PRECIO CON IGV 


	$desc = <<<XML
DES 1
DES 2
DES 3
XML;


$item2 = (new SaleDetail())
		    ->setCodProducto('08') //CODIGO PRODUCTO
		    ->setUnidad('LTR')
		    ->setCantidad('25.99')
		    ->setDescripcion('GLP')
		    ->setMtoBaseIgv(floatval('24.92'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('4.48'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('4.48'))
		    ->setMtoValorVenta(floatval('24.92'))
		    ->setMtoValorUnitario(floatval('0.96')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('1.13')); //PRECIO CON IGV



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

	