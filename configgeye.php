<?php
use Greenter\Ws\Services\SunatEndpoints;

$see = new \Greenter\See();
$see->setService(SunatEndpoints::FE_PRODUCCION);

$see->setCertificate(file_get_contents(__DIR__.'/certificategeye.pem'));
$see->setCredentials('20514739065GEYE2018', '20514739065');

return $see;
