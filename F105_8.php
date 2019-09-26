
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
    	
    	

		$date_string = '30-05-2019';
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 5112,654.55
		    ->setTipoDoc('01')
		    ->setSerie('F105')
		    ->setCorrelativo('8' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('1398.32')) //VALOR VENTA 
		    ->setMtoIGV(floatval('251.70')) //IGV
		    ->setTotalImpuestos(floatval('251.70')) //IGV 
		    ->setValorVenta(floatval('1398.32'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('1650.02')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('1650.02') ;



		

		$item = (new SaleDetail())
		    ->setCodProducto('07') //CODIGO PRODUCTO
		    ->setUnidad('LT')
		    ->setCantidad('705.00')
		    ->setDescripcion('GLP')
		    ->setMtoBaseIgv(floatval('788.67'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('141.96'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('141.96'))
		    ->setMtoValorVenta(floatval('788.67'))
		    ->setMtoValorUnitario(floatval('1.12')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('1.32')); //PRECIO CON IGV 


	$desc = <<<XML
DES 1
DES 2
DES 3
XML;


$item2 = (new SaleDetail())
		    ->setCodProducto('03') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('1')
		    ->setDescripcion('GASOHOL 95')
		    ->setMtoBaseIgv(floatval('609.65'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('109.74'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('109.74'))
		    ->setMtoValorVenta(floatval('609.65'))
		    ->setMtoValorUnitario(floatval('11.47')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('13.54')); //PRECIO CON IGV





		$legend = (new Legend())
		    ->setCode('1000')
		    ->setValue($letras);

		$invoice->setDetails([ $item ,$item2   ])
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

	