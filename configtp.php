
<?php
use Greenter\Ws\Services\SunatEndpoints;

$see = new \Greenter\See();
$see->setService(SunatEndpoints::FE_BETA);
$see->setCertificate(file_get_contents(__DIR__.'/certificatetp.pem'));
$see->setCredentials('20424092941PEREDAC4', '20424092941');

return $see;
