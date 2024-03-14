<?php

/**
 * Connection checker.
 *
 * @package WPDesk\FedexShippingService\FedexApi
 */
namespace FedExVendor\WPDesk\FedexShippingService\FedexApi;

use FedExVendor\FedEx\RateService\ComplexType\RateRequest;
use FedExVendor\FedEx\RateService\ComplexType\RequestedPackageLineItem;
use FedExVendor\FedEx\RateService\Request;
use FedExVendor\FedEx\RateService\SimpleType\LinearUnits;
use FedExVendor\FedEx\RateService\SimpleType\PaymentType;
use FedExVendor\FedEx\RateService\SimpleType\RateRequestType;
use FedExVendor\FedEx\RateService\SimpleType\WeightUnits;
use FedExVendor\Psr\Log\LoggerInterface;
use FedExVendor\WPDesk\AbstractShipping\Exception\ApiConnectionCheckerException;
use FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
/**
 * Can check connection.
 */
class ConnectionChecker
{
    /**
     * Settings.
     *
     * @var SettingsValues
     */
    private $settings;
    /**
     * Logger.
     *
     * @var LoggerInterface
     */
    private $logger;
    /** @var bool */
    private $is_testing;
    /**
     * ConnectionChecker constructor.
     *
     * @param SettingsValues  $settings .
     * @param LoggerInterface $logger .
     * @param bool $is_testing .
     */
    public function __construct(\FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \FedExVendor\Psr\Log\LoggerInterface $logger, $is_testing)
    {
        $this->settings = $settings;
        $this->logger = $logger;
        $this->is_testing = $is_testing;
    }
    private function create_rate_request()
    {
        $rateRequest = new \FedExVendor\FedEx\RateService\ComplexType\RateRequest();
        //authentication & client details
        $rateRequest->WebAuthenticationDetail->UserCredential->Key = $this->settings->get_value('api_key');
        $rateRequest->WebAuthenticationDetail->UserCredential->Password = $this->settings->get_value('api_password');
        $rateRequest->ClientDetail->AccountNumber = $this->settings->get_value('account_number');
        $rateRequest->ClientDetail->MeterNumber = $this->settings->get_value('meter_number');
        $rateRequest->TransactionDetail->CustomerTransactionId = \rand(1, 1000000) . ' on ' . \site_url();
        //version
        $rateRequest->Version->ServiceId = 'crs';
        $rateRequest->Version->Major = 31;
        $rateRequest->Version->Minor = 0;
        $rateRequest->Version->Intermediate = 0;
        $rateRequest->ReturnTransitAndCommit = \true;
        //shipper
        $rateRequest->RequestedShipment->PreferredCurrency = 'USD';
        $rateRequest->RequestedShipment->Shipper->Address->StreetLines = ['228 Park Ave S'];
        $rateRequest->RequestedShipment->Shipper->Address->City = 'New York';
        $rateRequest->RequestedShipment->Shipper->Address->StateOrProvinceCode = 'TN';
        $rateRequest->RequestedShipment->Shipper->Address->PostalCode = '10003';
        $rateRequest->RequestedShipment->Shipper->Address->CountryCode = 'US';
        //recipient
        $rateRequest->RequestedShipment->Recipient->Address->StreetLines = [\rand(1, 10000) . ' Farmcrest Ct'];
        $rateRequest->RequestedShipment->Recipient->Address->City = 'Herndon';
        $rateRequest->RequestedShipment->Recipient->Address->StateOrProvinceCode = 'VA';
        $rateRequest->RequestedShipment->Recipient->Address->PostalCode = 20171;
        $rateRequest->RequestedShipment->Recipient->Address->CountryCode = 'US';
        //shipping charges payment
        $rateRequest->RequestedShipment->ShippingChargesPayment->PaymentType = \FedExVendor\FedEx\RateService\SimpleType\PaymentType::_SENDER;
        //rate request types
        $rateRequest->RequestedShipment->RateRequestTypes = [\FedExVendor\FedEx\RateService\SimpleType\RateRequestType::_LIST];
        $rateRequest->RequestedShipment->PackageCount = 1;
        //create package line items
        $rateRequest->RequestedShipment->RequestedPackageLineItems = [new \FedExVendor\FedEx\RateService\ComplexType\RequestedPackageLineItem()];
        //package 1
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Weight->Value = \rand(1, 10);
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Weight->Units = \FedExVendor\FedEx\RateService\SimpleType\WeightUnits::_LB;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Length = \rand(1, 10);
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Width = \rand(1, 10);
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Height = \rand(1, 10);
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->Dimensions->Units = \FedExVendor\FedEx\RateService\SimpleType\LinearUnits::_IN;
        $rateRequest->RequestedShipment->RequestedPackageLineItems[0]->setGroupPackageCount(1);
        return $rateRequest;
    }
    /**
     * Pings API.
     * Throws exception on failure.
     *
     * @return void
     * @throws \Exception .
     */
    public function check_connection()
    {
        $rate_service_request = new \FedExVendor\FedEx\RateService\Request();
        $rate_service_request->getSoapClient()->__setLocation($this->is_testing ? \FedExVendor\FedEx\RateService\Request::TESTING_URL : \FedExVendor\FedEx\RateService\Request::PRODUCTION_URL);
        $rateReply = $rate_service_request->getGetRatesReply($this->create_rate_request());
        if ($rateReply->HighestSeverity === 'ERROR') {
            foreach ($rateReply->Notifications as $key => $notification) {
                $this->logger->warning('FedEx connection checker error - notification ' . $key, ['code' => $notification->Code, 'message' => $notification->Message]);
            }
            throw new \FedExVendor\WPDesk\AbstractShipping\Exception\ApiConnectionCheckerException($rateReply->Notifications[0]->Message);
        }
    }
}
