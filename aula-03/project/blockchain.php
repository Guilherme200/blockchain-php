<?php

require  'vendor/autoload.php';

$blockchain = new \Blockchain\Blockchain();
$blockchain->setServiceUrl( 'http://127.0.0.2');

$wallet = $blockchain->Create->create('12345678', $email=null, $label=null);
echo 'link da carteira: ' . json_decode($wallet->link);
