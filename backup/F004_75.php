
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
    	->setRznSocial('NOBAL TRANSPORTES S.A.C');
    	
    	

		$date_string = '26-09-2019';
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 5112,654.55
		    ->setTipoDoc('01')
		    ->setSerie('F004')
		    ->setCorrelativo('75' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('425.01')) //VALOR VENTA 
		    ->setMtoIGV(floatval('76.50')) //IGV
		    ->setTotalImpuestos(floatval('76.50')) //IGV 
		    ->setValorVenta(floatval('425.01'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('501.51')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('501.51') ;


		$item = (new SaleDetail())
		    ->setCodProducto('05') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('40.67')
		    ->setDescripcion('DieselPro B5 S50 UV')
		    ->setMtoBaseIgv(floatval('408.06'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('73.45'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('73.45'))
		    ->setMtoValorVenta(floatval('408.06'))
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
		    ->setCantidad('1.54')
		    ->setDescripcion('Gasohol 90')
		    ->setMtoBaseIgv(floatval('16.95'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('3.05'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('3.05'))
		    ->setMtoValorVenta(floatval('16.95'))
		    ->setMtoValorUnitario(floatval('11.01')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('12.99')); //PRECIO CON IGV


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

	