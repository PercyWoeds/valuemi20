
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
		    ->setCorrelativo('101' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('510.86')) //VALOR VENTA 
		    ->setMtoIGV(floatval('91.96')) //IGV
		    ->setTotalImpuestos(floatval('91.96')) //IGV 
		    ->setValorVenta(floatval('510.86'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('602.82')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('602.82') ;

		

		$item = (new SaleDetail())
		    ->setCodProducto('05') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('8.87')
		    ->setDescripcion('DieselPro B5 S50 UV')
		    ->setMtoBaseIgv(floatval('88.98'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('16.02'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('16.02'))
		    ->setMtoValorVenta(floatval('88.98'))
		    ->setMtoValorUnitario(floatval('10.03')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('11.84')); //PRECIO CON IGV 


	$desc = <<<XML
DES 1
DES 2
DES 3
XML;


$item2 = (new SaleDetail())
		    ->setCodProducto('07') //CODIGO PRODUCTO
		    ->setUnidad('LTR')
		    ->setCantidad('360.6')
		    ->setDescripcion('GLP')
		    ->setMtoBaseIgv(floatval('369.76'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('66.56'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('66.56'))
		    ->setMtoValorVenta(floatval('369.76'))
		    ->setMtoValorUnitario(floatval('1.03')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('1.21')); //PRECIO CON IGV



$item3 = (new SaleDetail())
		    ->setCodProducto('02') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('4.85')
		    ->setDescripcion('Gasohol 90')
		    ->setMtoBaseIgv(floatval('52.12'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('9.38'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('9.38'))
		    ->setMtoValorVenta(floatval('52.12'))
		    ->setMtoValorUnitario(floatval('10.75')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('12.69')); //PRECIO CON IGV





		$legend = (new Legend())
		    ->setCode('1000')
		    ->setValue($letras);

		$invoice->setDetails([ $item ,$item2 ,$item3  ])
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

	