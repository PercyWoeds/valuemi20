
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
    	
    	

		$date_string = '14-09-2019';
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 5112,654.55
		    ->setTipoDoc('01')
		    ->setSerie('F004')
		    ->setCorrelativo('69' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('444.64')) //VALOR VENTA 
		    ->setMtoIGV(floatval('80.03')) //IGV
		    ->setTotalImpuestos(floatval('80.03')) //IGV 
		    ->setValorVenta(floatval('444.64'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('524.67')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('524.67') ;



		

		$item = (new SaleDetail())
		    ->setCodProducto('05') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('42.2')
		    ->setDescripcion('DieselPro B5 S50 UV')
		    ->setMtoBaseIgv(floatval('423.45'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('76.22'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('76.22'))
		    ->setMtoValorVenta(floatval('423.45'))
		    ->setMtoValorUnitario(floatval('10.03')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('11.84')); //PRECIO CON IGV 


	$desc = <<<XML
DES 1
DES 2
DES 3
XML;


$item2 = (new SaleDetail())
		    ->setCodProducto('04') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('0.97')
		    ->setDescripcion('GASOHOL 98')
		    ->setMtoBaseIgv(floatval('12.71'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('2.29'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('2.29'))
		    ->setMtoValorVenta(floatval('12.71'))
		    ->setMtoValorUnitario(floatval('13.14')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('15.50')); //PRECIO CON IGV


$item3 = (new SaleDetail())
		    ->setCodProducto('02') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('0.77')
		    ->setDescripcion('GASOHOL 90')
		    ->setMtoBaseIgv(floatval('8.47'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('1.53'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('1.53'))
		    ->setMtoValorVenta(floatval('8.47'))
		    ->setMtoValorUnitario(floatval('11.01')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('12.99')); //PRECIO CON IGV




		$legend = (new Legend())
		    ->setCode('1000')
		    ->setValue($letras);

		$invoice->setDetails([ $item ,$item2 ,$item3   ])
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

	