<?php

namespace DhlVendor\WPDesk\DhlExpressShippingService;

use DhlVendor\DHL\Entity\AM\GetQuoteResponse;
use DhlVendor\Psr\Log\LoggerInterface;
use DhlVendor\WPDesk\AbstractShipping\Exception\InvalidSettingsException;
use DhlVendor\WPDesk\AbstractShipping\Exception\RateException;
use DhlVendor\WPDesk\AbstractShipping\Exception\UnitConversionException;
use DhlVendor\WPDesk\AbstractShipping\Rate\ShipmentRating;
use DhlVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use DhlVendor\WPDesk\AbstractShipping\Settings\SettingsValuesAsArray;
use DhlVendor\WPDesk\AbstractShipping\Shipment\Shipment;
use DhlVendor\WPDesk\AbstractShipping\ShippingService;
use DhlVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanInsure;
use DhlVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanPack;
use DhlVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRate;
use DhlVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanTestSettings;
use DhlVendor\WPDesk\AbstractShipping\ShippingServiceCapability\HasSettings;
use DhlVendor\WPDesk\AbstractShipping\Shop\ShopSettings;
use DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\DhlRateCurrencyFilter;
use DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\DhlRateCustomServicesFilter;
use DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\RestApi\RestApiDhlRateReplyInterpretation;
use DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\RestApi\RestApiDhlSender;
use DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\RestApi\RestApiConnectionChecker;
use DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\RestApi\RestApiDhlRateRequestBuilder;
use DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\Sender;
use DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\XmlApi\XmlApiConnectionChecker;
use DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\XmlApi\XmlApiDhlRateReplyInterpretation;
use DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\XmlApi\XmlApiDhlRateRequestBuilder;
use DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\XmlApi\XmlApiDhlSender;
use DhlVendor\WPDesk\DhlExpressShippingService\Exception\CurrencySwitcherException;
/**
 * DHL main shipping class injected into WooCommerce shipping method.
 *
 * @package WPDesk\DhlShippingService
 */
class DhlShippingService extends \DhlVendor\WPDesk\AbstractShipping\ShippingService implements \DhlVendor\WPDesk\AbstractShipping\ShippingServiceCapability\HasSettings, \DhlVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRate, \DhlVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanInsure, \DhlVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanPack, \DhlVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanTestSettings
{
    /** Logger.
     *
     * @var LoggerInterface
     */
    private $logger;
    /** Shipping method helper.
     *
     * @var ShopSettings
     */
    private $shop_settings;
    /** DHL settings.
     *
     * @var SettingsValuesAsArray|null
     */
    private $dhl_settings;
    const UNIQUE_ID = 'flexible_shipping_dhl_express';
    /**
     * Sender.
     *
     * @var Sender
     */
    private $sender;
    /**
     * DhlShippingService constructor.
     *
     * @param LoggerInterface $logger Logger.
     * @param ShopSettings $helper Helper.
     */
    public function __construct(\DhlVendor\Psr\Log\LoggerInterface $logger, \DhlVendor\WPDesk\AbstractShipping\Shop\ShopSettings $helper, ?\DhlVendor\WPDesk\AbstractShipping\Settings\SettingsValuesAsArray $dhl_settings = null)
    {
        $this->logger = $logger;
        $this->shop_settings = $helper;
        $this->dhl_settings = $dhl_settings;
    }
    public function is_rate_enabled(\DhlVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings)
    {
        return \true;
    }
    /**
     * Set logger.
     *
     * @param LoggerInterface $logger Logger.
     */
    public function setLogger(\DhlVendor\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * Set sender.
     *
     * @param Sender $sender Sender.
     */
    public function set_sender(\DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\Sender $sender)
    {
        $this->sender = $sender;
    }
    /**
     * Get sender.
     *
     * @return Sender
     */
    public function get_sender()
    {
        return $this->sender;
    }
    /**
     * Create reply interpretation.
     *
     * @param GetQuoteResponse|array $rate_reply .
     * @param ShopSettings $shop_settings .
     * @param SettingsValues $settings .
     *
     * @return ShipmentRating
     */
    protected function create_reply_interpretation($rate_reply, $shop_settings, $settings)
    {
        if ($this->get_api_type() === \DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::API_TYPE_XML) {
            return new \DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\XmlApi\XmlApiDhlRateReplyInterpretation($rate_reply, $shop_settings->is_tax_enabled(), $shop_settings->get_default_currency());
        } else {
            return new \DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\RestApi\RestApiDhlRateReplyInterpretation($rate_reply, $shop_settings->is_tax_enabled(), $shop_settings->get_default_currency());
        }
    }
    /**
     * Rate shipment.
     *
     * @param SettingsValues $settings Settings Values.
     * @param Shipment $shipment Shipment.
     *
     * @return ShipmentRating
     * @throws InvalidSettingsException InvalidSettingsException.
     * @throws RateException RateException.
     * @throws UnitConversionException Weight exception.
     * @throws \Exception
     */
    public function rate_shipment(\DhlVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \DhlVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment)
    {
        if (!$this->get_settings_definition()->validate_settings($settings)) {
            throw new \DhlVendor\WPDesk\AbstractShipping\Exception\InvalidSettingsException();
        }
        $this->verify_currency($this->shop_settings->get_default_currency(), $this->shop_settings->get_currency());
        $request_builder = $this->create_rate_request_builder($settings, $shipment, $this->shop_settings);
        $request = $request_builder->build_request();
        if ($this->get_api_type() === \DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::API_TYPE_XML) {
            $this->set_sender(new \DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\XmlApi\XmlApiDhlSender($this->logger, $this->is_testing($settings)));
        } else {
            $this->set_sender(new \DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\RestApi\RestApiDhlSender($this->logger, $this->dhl_settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::FIELD_API_KEY), $this->dhl_settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::FIELD_API_SECRET), $this->is_testing($settings)));
        }
        $response = $this->get_sender()->send($request);
        $reply = $this->create_reply_interpretation($response, $this->shop_settings, $settings);
        return $this->create_filter_rates_by_currency(new \DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\DhlRateCustomServicesFilter($reply, $settings));
    }
    /**
     * Create rate request builder.
     *
     * @param SettingsValues $settings .
     * @param Shipment $shipment .
     * @param ShopSettings $shop_settings .
     *
     * @return RestApiDhlRateRequestBuilder
     */
    protected function create_rate_request_builder(\DhlVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \DhlVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment, \DhlVendor\WPDesk\AbstractShipping\Shop\ShopSettings $shop_settings)
    {
        if ($this->get_api_type() === \DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::API_TYPE_XML) {
            return new \DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\XmlApi\XmlApiDhlRateRequestBuilder($settings, $shipment, $shop_settings);
        } else {
            return new \DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\RestApi\RestApiDhlRateRequestBuilder($settings, $shipment, $shop_settings);
        }
    }
    /**
     * Creates rate filter by currency.
     *
     * @param ShipmentRating $rating .
     *
     * @return DhlRateCurrencyFilter .
     */
    protected function create_filter_rates_by_currency(\DhlVendor\WPDesk\AbstractShipping\Rate\ShipmentRating $rating)
    {
        return new \DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\DhlRateCurrencyFilter($rating, $this->shop_settings);
    }
    /**
     * Verify currency.
     *
     * @param string $default_shop_currency Shop currency.
     * @param string $checkout_currency Checkout currency.
     *
     * @return void
     * @throws CurrencySwitcherException .
     */
    protected function verify_currency($default_shop_currency, $checkout_currency)
    {
        if ($default_shop_currency !== $checkout_currency) {
            throw new \DhlVendor\WPDesk\DhlExpressShippingService\Exception\CurrencySwitcherException($this->shop_settings);
        }
    }
    /**
     * Should I use a test API?
     *
     * @param \WPDesk\AbstractShipping\Settings\SettingsValues $settings Settings.
     *
     * @return bool
     */
    public function is_testing(\DhlVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings)
    {
        $testing = \false;
        if ($settings->has_value('testing')) {
            $testing = 'yes' === $settings->get_value('testing') ? \true : \false;
        }
        return $testing;
    }
    /**
     * Get settings
     *
     * @return DhlSettingsDefinition
     */
    public function get_settings_definition()
    {
        return new \DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition($this->shop_settings, $this->dhl_settings);
    }
    /**
     * Get unique ID.
     *
     * @return string
     */
    public function get_unique_id()
    {
        return self::UNIQUE_ID;
    }
    /**
     * Get name.
     *
     * @return string
     */
    public function get_name()
    {
        return \__('DHL Express Live Rates', 'flexible-shipping-dhl-express');
    }
    /**
     * Get description.
     *
     * @return string
     */
    public function get_description()
    {
        return \sprintf(\__('Dynamically calculated DHL Express live rates based on the established DHL Express API connection. %1$sLearn more →%2$s', 'flexible-shipping-dhl-express'), '<a href="https://octol.io/dhlexpress-settings-docs" target="_blank">', '</a>');
    }
    /**
     * Pings API.
     * Returns empty string on success or error message on failure.
     *
     * @param SettingsValues $settings .
     * @param LoggerInterface $logger .
     *
     * @return string
     */
    public function check_connection(\DhlVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \DhlVendor\Psr\Log\LoggerInterface $logger)
    {
        try {
            if ($settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::FIELD_API_TYPE, \DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::API_TYPE_REST) === \DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::API_TYPE_REST) {
                $connection_checker = new \DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\RestApi\RestApiConnectionChecker($settings, $logger, $this->is_testing($settings));
            } else {
                $connection_checker = new \DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\XmlApi\XmlApiConnectionChecker($settings, $logger, $this->is_testing($settings));
            }
            $connection_checker->check_connection();
            return '';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    /**
     * Returns field ID after which API Status field should be added.
     *
     * @return string
     */
    public function get_field_before_api_status_field()
    {
        return \DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::FIELD_API_SECRET;
    }
    private function get_api_type() : string
    {
        return $this->dhl_settings->get_value(\DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::FIELD_API_TYPE, \DhlVendor\WPDesk\DhlExpressShippingService\DhlSettingsDefinition::API_TYPE_XML);
    }
}
