
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
    	->setNumDoc('20600010311')
    	->setRznSocial('LEITECORP S.A.C');
   

		$date_string = '09-12-2019';
		$date = date_create_from_format('d-m-Y', $date_string); 

		$invoice = (new Invoice())
		    ->setUblVersion('2.1')
		    ->setTipoOperacion('0101') // Catalog. 5112,654.55
		    ->setTipoDoc('01')
		    ->setSerie('FF01')  
		    ->setCorrelativo('1' )
		    ->setFechaEmision($date)
		    ->setTipoMoneda('PEN')
		    ->setClient($client)		    
		    ->setMtoOperGravadas(floatval('2417.88')) //VALOR VENTA 
		    ->setMtoIGV(floatval('435.22')) //IGV
		    ->setTotalImpuestos(floatval('435.22')) //IGV 
		    ->setValorVenta(floatval('2417.88'))  // VALOR VENTA
		    ->setMtoImpVenta(floatval('2853.10')) // IMPORTE TOTAL
		    ->setCompany($company);

		     $letras =  numtoletras('2853.10') ;



		

		$item = (new SaleDetail())
		    ->setCodProducto('02') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('2.387')
		    ->setDescripcion('GASOHOL 90')
		    ->setMtoBaseIgv(floatval('25.42'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('4.58'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('4.58'))
		    ->setMtoValorVenta(floatval('25.42'))
		    ->setMtoValorUnitario(floatval('10.65')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('12.57')); //PRECIO CON IGV 


	$desc = <<<XML
DES 1
DES 2
DES 3
XML;


$item2 = (new SaleDetail())
		    ->setCodProducto('02') //CODIGO PRODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('13.552')
		    ->setDescripcion('GASOHOL 90')
		    ->setMtoBaseIgv(floatval('149.19'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('26.85'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('26.85'))
		    ->setMtoValorVenta(floatval('149.19'))
		    ->setMtoValorUnitario(floatval('11.01')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('12.99')); //PRECIO CON IGV




$item3 = (new SaleDetail())
		    ->setCodProducto('05') //CODIGO DIODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('108.368')
		    ->setDescripcion('DIESEL B5 S50')
		    ->setMtoBaseIgv(floatval('1091.95'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('196.55'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('196.55'))
		    ->setMtoValorVenta(floatval('1091.95'))
		    ->setMtoValorUnitario(floatval('10.08')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('11.89')); //PRECIO CON IGV



$item4 = (new SaleDetail())
		    ->setCodProducto('05') //CODIGO DIODUCTO
		    ->setUnidad('GLL')
		    ->setCantidad('86.436')
		    ->setDescripcion('DIESEL B5 S50')
		    ->setMtoBaseIgv(floatval('878.28'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('158.09'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('158.09'))
		    ->setMtoValorVenta(floatval('878.28'))
		    ->setMtoValorUnitario(floatval('10.16')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('11.99')); //PRECIO CON IGV



$item5 = (new SaleDetail())
		    ->setCodProducto('07') //CODIGO DIODUCTO
		    ->setUnidad('LTR')
		    ->setCantidad('65.667')
		    ->setDescripcion('GLP')
		    ->setMtoBaseIgv(floatval('63.44'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('11.42'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('11.42'))
		    ->setMtoValorVenta(floatval('63.44'))
		    ->setMtoValorUnitario(floatval('0.97')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('1.19')); //PRECIO CON IGV



$item6 = (new SaleDetail())
		    ->setCodProducto('07') //CODIGO DIODUCTO
		    ->setUnidad('LTR')
		    ->setCantidad('170.815')
		    ->setDescripcion('GLP')
		    ->setMtoBaseIgv(floatval('172.26'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('31.01'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('31.01'))
		    ->setMtoValorVenta(floatval('172.26'))
		    ->setMtoValorUnitario(floatval('1.01')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('1.19')); //PRECIO CON IGV



$item7 = (new SaleDetail())
		    ->setCodProducto('07') //CODIGO DIODUCTO
		    ->setUnidad('LTR')
		    ->setCantidad('35.821')
		    ->setDescripcion('GLP')
		    ->setMtoBaseIgv(floatval('37.34'))
		    ->setPorcentajeIgv(18.00) // 18%
		    ->setIgv(floatval('6.72'))
		    ->setTipAfeIgv('10')
		    ->setTotalImpuestos(floatval('6.72'))
		    ->setMtoValorVenta(floatval('37.34'))
		    ->setMtoValorUnitario(floatval('1.04')) //PRECIO SIN IGV
		    ->setMtoPrecioUnitario(floatval('1.23')); //PRECIO CON IGV



		$legend = (new Legend())
		    ->setCode('1000')
		    ->setValue($letras);

		$invoice->setDetails([ $item ,$item2,$item3,$item4,$item5,$item6,$item7 ])
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

	