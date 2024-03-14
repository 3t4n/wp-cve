<?php

spl_autoload_register(function($class) {
    $root = dirname(__DIR__);
    $classFile = $root . '/lib/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($classFile)) {
        require_once $classFile;
    }
});

// $instanceName is a part of the url where you access your zahls installation.
// https://{$instanceName}.zahls.ch
$instanceName = 'YOUR_INSTANCE_NAME';

// $secret is the zahls secret for the communication between the applications
// if you think someone got your secret, just regenerate it in the zahls administration
$secret = 'YOUR_SECRET';

$zahls = new \Zahls\Zahls($instanceName, $secret);

$transaction = new \Zahls\Models\Request\Transaction();

// amount multiplied by 100
$transaction->setAmount(89.25 * 100);

// VAT rate percentage (nullable)
$transaction->setVatRate(7.70);

// currency ISO code
$transaction->setCurrency('CHF');

// optional: add contact information which should be stored along with payment
$transaction->addField($type = 'forename', $value = 'Max');
$transaction->addField($type = 'surname', $value = 'Mustermann');
$transaction->addField($type = 'email', $value = 'max.muster@zahls.ch');

try {
    $response = $zahls->create($transaction);
    var_dump($response);
} catch (\Zahls\ZahlsException $e) {
    print $e->getMessage();
}
