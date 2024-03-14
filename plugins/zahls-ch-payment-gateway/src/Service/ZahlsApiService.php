<?php

namespace ZahlsPaymentGateway\Service;

use Zahls\Communicator;
use Zahls\Models\Response\Gateway;
use Zahls\Models\Response\Transaction;

class ZahlsApiService
{
    private $instance;
    private $apiKey;
    private $platform;
    private $prefix;
    private $lookAndFeelId;

    /**
     * Constructor
     *
     * @param EntityRepository $customerRepository
     * @param LoggerInterface $logger
     */
    public function __construct($instance, $apiKey, $platform, $prefix, $lookAndFeelId)
    {
        $this->instance = $instance;
        $this->apiKey = $apiKey;
        $this->platform = $platform;
        $this->prefix = $prefix;
        $this->lookAndFeelId = $lookAndFeelId;
    }

    public function createZahlsGateway($order, $totalAmount, $basket, $purpose, $reference, $successRedirectUrl, $cancelRedirectUrl, $preAuthorization, $chargeOnAuth, $currency = 'USD', $customButtonText = null) {
        $zahls = $this->getInterface();
        $gateway = new \Zahls\Models\Request\Gateway();

        $gateway->setValidity(15);
        $gateway->setPsp([]);
        $gateway->setSkipResultPage(true);
		
		if ($customButtonText) {
            $gateway->setButtonText($customButtonText);
        }

        $gateway->setAmount($totalAmount * 100);
        $gateway->setCurrency($currency);
        $gateway->setPreAuthorization($preAuthorization);
        $gateway->setChargeOnAuthorization($chargeOnAuth);
        $gateway->setBasket($basket);
        $gateway->setPurpose($purpose);
        $gateway->setReferenceId($reference);

        $gateway->setLookAndFeelProfile($this->lookAndFeelId ?: null);

        $gateway->setSuccessRedirectUrl($successRedirectUrl);
        $gateway->setCancelRedirectUrl($cancelRedirectUrl);
        $gateway->setFailedRedirectUrl($cancelRedirectUrl);

        $billingAddress = $order->get_billing_address_1() . ' ' . $order->get_billing_address_2();
        $gateway->addField($type = 'title', $value = '');
        $gateway->addField($type = 'forename', $value = $order->get_billing_first_name());
        $gateway->addField($type = 'surname', $value = $order->get_billing_last_name());
        $gateway->addField($type = 'company', $value = $order->get_billing_company());
        $gateway->addField($type = 'street', $value = $billingAddress);
        $gateway->addField($type = 'postcode', $value = $order->get_billing_postcode());
        $gateway->addField($type = 'place', $value = $order->get_billing_city());
        $gateway->addField($type = 'country', $value = $order->get_billing_country());
        $gateway->addField($type = 'phone', $value = $order->get_billing_phone());
        $gateway->addField($type = 'email', $value = $order->get_billing_email());
        $gateway->addField($type = 'custom_field_1', $value = $order->get_id(), $name = 'WooCommerce Order ID');

        try {
            $response = $zahls->create($gateway);

            return $response;
        } catch (\Zahls\ZahlsException $e) {
            print $e->getMessage();
        }
    }

    /**
     * @return \Zahls\Zahls
     */
    public function getInterface(): \Zahls\Zahls
    {
        $this->registerAutoloader();
        $platform = !empty($this->platform) ? $this->platform : \Zahls\Communicator::API_URL_BASE_DOMAIN;
        return new \Zahls\Zahls($this->instance, $this->apiKey, '', $platform);
    }

    public function deleteGatewayById($gatewayId):bool {
        $zahls = $this->getInterface();

        $gateway = new \Zahls\Models\Request\Gateway();
        $gateway->setId($gatewayId);

        try {
            $zahls->delete($gateway);
        } catch (\Zahls\ZahlsException $e) {
            return false;
        }
        return true;
    }

    public function getZahlsTransaction(int $zahlsTransactionId): ?\Zahls\Models\Response\Transaction
    {
        $zahls = $this->getInterface();

        $zahlsTransaction = new \Zahls\Models\Request\Transaction();
        $zahlsTransaction->setId($zahlsTransactionId);

        try {
            $response = $zahls->getOne($zahlsTransaction);
            return $response;
        } catch (\Zahls\ZahlsException $e) {
            return null;
        }
    }

    public function chargeTransaction($transactionId, $amount) {
        $zahls = $this->getInterface();
        $transaction = new \Zahls\Models\Request\Transaction();
        $transaction->setId($transactionId);
        $transaction->setAmount(floatval($amount) * 100);
        try {
            $zahls->charge($transaction);
            return true;
        } catch (\Zahls\ZahlsException $e) {
        }
        return false;
    }
	

    public function getZahlsGateway($gatewayId) {
        $zahls = $this->getInterface();
        $gateway = new \Zahls\Models\Request\Gateway();
        $gateway->setId($gatewayId);
        try {
            $zahlsGateway = $zahls->getOne($gateway);
            return $zahlsGateway;
        } catch (\Zahls\ZahlsException $e) {
            throw new \Exception('No gateway found by ID: '. $gatewayId);
        }
    }


    private function registerAutoloader()
    {
        spl_autoload_register(function ($class) {
            $root = ZAHLS_PLUGIN_DIR . '/zahls-php-master';
            $classFile = $root . '/lib/' . str_replace('\\', '/', $class) . '.php';
            if (file_exists($classFile)) {
                require_once $classFile;
            }
        });
    }
}