<?php

use Zahls\Models\Request\PaymentMethod;
use Zahls\Zahls;
use Zahls\ZahlsException;

spl_autoload_register(function ($class) {
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

$zahls = new Zahls($instanceName, $secret);

$paymentMethod = new PaymentMethod();
$paymentMethod->setId('visa');
// $paymentMethod->setFilterCurrency('CHF');
// $paymentMethod->setFilterPaymentType('one-time');
// $paymentMethod->setFilterPsp(36);

try {
    $response = $zahls->getOne($paymentMethod);
    echo '<pre>';
    var_dump($response);
    echo '</pre>';
    exit();
} catch (ZahlsException $e) {
    print $e->getMessage();
}
