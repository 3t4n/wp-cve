<?php

namespace DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\RestApi;

use DhlVendor\Octolize\DhlExpress\RestApi\MyDHL;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Account;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Package;
use DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\RateAddress;
use DhlVendor\Psr\Log\LoggerInterface;
use DhlVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\ApiConnectionChecker;
use DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition;
class RestApiConnectionChecker implements \DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\ApiConnectionChecker
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
     * @param SettingsValues $settings .
     * @param LoggerInterface $logger .
     * @param bool $is_testing .
     */
    public function __construct(\DhlVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \DhlVendor\Psr\Log\LoggerInterface $logger, $is_testing)
    {
        $this->settings = $settings;
        $this->logger = $logger;
        $this->is_testing = $is_testing;
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
        $myDhl = new \DhlVendor\Octolize\DhlExpress\RestApi\MyDHL($this->settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::FIELD_API_KEY, ''), $this->settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::FIELD_API_SECRET, ''), $this->is_testing);
        $rateService = $myDhl->getRateService();
        $originAddress = new \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\RateAddress('DE', '10117', 'Berlin');
        $destinationAddress = new \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\RateAddress('DE', '20099', 'Hamburg');
        $package = new \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Package(
            10,
            // kg
            20,
            // cm
            10,
            // cm
            30
        );
        $shippingDate = new \DateTimeImmutable('now');
        try {
            $rates = $rateService->addAccount(new \DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects\Account('shipper', $this->settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::FIELD_ACCOUNT_NUMBER, '')))->setOriginAddress($originAddress)->setDestinationAddress($destinationAddress)->setPlannedShippingDate($shippingDate)->addPackage($package)->setNextBusinessDay(\true)->setCustomsDeclarable(\false)->setPayerCountryCode('DE')->getRates();
        } catch (\Exception $e) {
            $this->logger->error('Connection check', ['exception' => $e]);
            throw $e;
        }
    }
}
