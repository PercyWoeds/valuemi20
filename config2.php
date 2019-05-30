
<?php
use Greenter\Ws\Services\SunatEndpoints;

$see = new \Greenter\See();
$see->setService(SunatEndpoints::FE_PRODUCCION);
$see->setCertificate(file_get_contents(__DIR__.'/certificate2.pem'));
$see->setCredentials('20517308367FACTURA2', 'NORMA2018');

return $see;
