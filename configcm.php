<?php
use Greenter\Ws\Services\SunatEndpoints;

$see = new \Greenter\See();
$see->setService(SunatEndpoints::FE_PRODUCCION);

$see->setCertificate(file_get_contents(__DIR__.'/certificatecm.pem'));
$see->setCredentials('20545339006FACTURA2', '20545339006');

return $see;
