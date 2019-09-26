
<?php

use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/numeroletras.php';


$see = require __DIR__.'/configcm.php';



// Emisor
$address = new Address();
$address->setUbigueo('150142')
    ->setDepartamento('LIMA')
    ->setProvincia('LIMA')
    ->setDistrito('VILLA EL SALVADOR')
    ->setUrbanizacion('')
    ->setDireccion('URB.PACHACAMAC MZA. C2 LOTE. 12 BAR.2 SEC. 1 IV ETA.');

$company = new Company();
$company->setRuc('20545339006')
    ->setRazonSocial('CODISCOM S.A.C.')
    ->setNombreComercial('CODISCOM S.A.C.')
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
    	
    	

		$date_string = '16-09-2019';
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 5112,654.55
		    ->setTipoDoc('01')
		    ->setSerie('F004')
		    ->setCorrelativo('71' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('475.08')) //VALOR VENTA 
		    ->setMtoIGV(floatval('85.52')) //IGV
		    ->setTotalImpuestos(floatval('85.52')) //IGV 
		    ->setValorVenta(floatval('475.08'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('560.60')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('560.60') ;


		$item = (new SaleDetail())
		    ->setCodProducto('02') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('1.39')
		    ->setDescripcion('Gasohol 90')
		    ->setMtoBaseIgv(floatval('15.25'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('2.75'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('2.75'))
		    ->setMtoValorVenta(floatval('15.25'))
		    ->setMtoValorUnitario(floatval('11.01')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('12.99')); //PRECIO CON IGV 


	$desc = <<<XML
DES 1
DES 2
DES 3
XML;


$item2 = (new SaleDetail())
		    ->setCodProducto('05') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('45.83')
		    ->setDescripcion('DieselPro B5 S50 UV')
		    ->setMtoBaseIgv(floatval('459.83'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('82.77'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('82.77'))
		    ->setMtoValorVenta(floatval('459.83'))
		    ->setMtoValorUnitario(floatval('10.03')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('11.84')); //PRECIO CON IGV


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

	