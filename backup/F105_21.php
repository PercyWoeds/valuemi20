
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
    	->setRznSocial('ENCOFRADOS ALSINA DEL PERU S.A.C');
    	
    	

		$date_string = '30-09-2019';
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 5112,654.55
		    ->setTipoDoc('01')
		    ->setSerie('F105')
		    ->setCorrelativo('21' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('1572.03')) //VALOR VENTA 
		    ->setMtoIGV(floatval('282.96')) //IGV
		    ->setTotalImpuestos(floatval('282.96')) //IGV 
		    ->setValorVenta(floatval('1572.03'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('1854.99')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('1854.99') ;

		

		$item = (new SaleDetail())
		    ->setCodProducto('07') //CODIGO PRODUCTO
		    ->setUnidad('LTR')
		    ->setCantidad('316.7')
		    ->setDescripcion('GLP')
		    ->setMtoBaseIgv(floatval('319.38'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('57.49'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('57.49'))
		    ->setMtoValorVenta(floatval('319.38'))
		    ->setMtoValorUnitario(floatval('1.01')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('1.19')); //PRECIO CON IGV 


	$desc = <<<XML
DES 1
DES 2
DES 3
XML;


$item2 = (new SaleDetail())
		    ->setCodProducto('08') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('51.03')
		    ->setDescripcion('GASOHOL 90')
		    ->setMtoBaseIgv(floatval('580.31'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('104.45'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('104.45'))
		    ->setMtoValorVenta(floatval('580.31'))
		    ->setMtoValorUnitario(floatval('11.37')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('13.42')); //PRECIO CON IGV

$item3 = (new SaleDetail())
		    ->setCodProducto('07') //CODIGO PRODUCTO
		    ->setUnidad('LT')
		    ->setCantidad('695.9')
		    ->setDescripcion('GLP')
		    ->setMtoBaseIgv(floatval('672.34'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('121.02'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('121.02'))
		    ->setMtoValorVenta(floatval('672.34'))
		    ->setMtoValorUnitario(floatval('0.97')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('1.14')); //PRECIO CON IGV 



		$legend = (new Legend())
		    ->setCode('1000')
		    ->setValue($letras);

		$invoice->setDetails([ $item ,$item2 ,$item3 ])
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

	