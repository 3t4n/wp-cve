<?php

namespace FedExVendor\WPDesk\FedexShippingService;

use FedExVendor\FedEx\RateService\ComplexType\RateReply;
use FedExVendor\Psr\Log\LoggerInterface;
use FedExVendor\WPDesk\AbstractShipping\Exception\UnitConversionException;
use FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanInsure;
use FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanPack;
use FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanTestSettings;
use FedExVendor\WPDesk\AbstractShipping\Shop\ShopSettings;
use FedExVendor\WPDesk\FedexShippingService\Exception\CurrencySwitcherException;
use FedExVendor\WPDesk\AbstractShipping\Exception\InvalidSettingsException;
use FedExVendor\WPDesk\AbstractShipping\Exception\RateException;
use FedExVendor\WPDesk\AbstractShipping\Rate\ShipmentRating;
use FedExVendor\WPDesk\AbstractShipping\Rate\ShipmentRatingImplementation;
use FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use FedExVendor\WPDesk\AbstractShipping\Shipment\Shipment;
use FedExVendor\WPDesk\AbstractShipping\ShippingService;
use FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRate;
use FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\HasSettings;
use FedExVendor\WPDesk\FedexShippingService\FedexApi\ConnectionChecker;
use FedExVendor\WPDesk\FedexShippingService\FedexApi\FedexRateCurrencyFilter;
use FedExVendor\WPDesk\FedexShippingService\FedexApi\FedexRateCustomServicesFilter;
use FedExVendor\WPDesk\FedexShippingService\FedexApi\FedexRateReplyInterpretation;
use FedExVendor\WPDesk\FedexShippingService\FedexApi\FedexRateRequestBuilder;
use FedExVendor\WPDesk\FedexShippingService\FedexApi\Sender;
use FedExVendor\WPDesk\FedexShippingService\FedexApi\FedexSender;
/**
 * FedEx main shipping class injected into WooCommerce shipping method.
 *
 * @package WPDesk\FedexShippingService
 */
class FedexShippingService extends \FedExVendor\WPDesk\AbstractShipping\ShippingService implements \FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\HasSettings, \FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRate, \FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanInsure, \FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanPack, \FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanTestSettings
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
    const UNIQUE_ID = 'flexible_shipping_fedex';
    /**
     * Sender.
     *
     * @var Sender
     */
    private $sender;
    /**
     * FedexShippingService constructor.
     *
     * @param LoggerInterface $logger Logger.
     * @param ShopSettings $helper Helper.
     */
    public function __construct(\FedExVendor\Psr\Log\LoggerInterface $logger, \FedExVendor\WPDesk\AbstractShipping\Shop\ShopSettings $helper)
    {
        $this->logger = $logger;
        $this->shop_settings = $helper;
    }
    public function is_rate_enabled(\FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings)
    {
        return \true;
    }
    /**
     * Set logger.
     *
     * @param LoggerInterface $logger Logger.
     */
    public function setLogger(\FedExVendor\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * Set sender.
     *
     * @param Sender $sender Sender.
     */
    public function set_sender(\FedExVendor\WPDesk\FedexShippingService\FedexApi\Sender $sender)
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
     * @param RateReply $rate_reply .
     * @param ShopSettings $shop_settings .
     * @param SettingsValues $settings .
     *
     * @return FedexRateReplyInterpretation
     */
    protected function create_reply_interpretation(\FedExVendor\FedEx\RateService\ComplexType\RateReply $rate_reply, $shop_settings, $settings)
    {
        return new \FedExVendor\WPDesk\FedexShippingService\FedexApi\FedexRateReplyInterpretation($rate_reply, $shop_settings->is_tax_enabled(), $settings->get_value(\FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FIELD_REQUEST_TYPE, \FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FIELD_REQUEST_TYPE_VALUE_ALL));
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
     */
    public function rate_shipment(\FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \FedExVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment)
    {
        if (!$this->get_settings_definition()->validate_settings($settings)) {
            throw new \FedExVendor\WPDesk\AbstractShipping\Exception\InvalidSettingsException();
        }
        $validate_shipment = new \FedExVendor\WPDesk\FedexShippingService\FedexValidateShipment($shipment, $this->logger);
        $this->verify_currency($this->shop_settings->get_default_currency(), $this->shop_settings->get_currency());
        if ($validate_shipment->is_weight_exceeded()) {
            return new \FedExVendor\WPDesk\AbstractShipping\Rate\ShipmentRatingImplementation([]);
        }
        $request_builder = $this->create_rate_request_builder($settings, $shipment, $this->shop_settings);
        $request = $request_builder->build_request();
        $this->set_sender(new \FedExVendor\WPDesk\FedexShippingService\FedexApi\FedexSender($this->logger, $this->is_testing($settings)));
        $response = $this->get_sender()->send($request);
        $reply = $this->create_reply_interpretation($response, $this->shop_settings, $settings);
        if ($reply::has_reply_warning($response)) {
            $this->logger->info($reply::get_reply_message($response));
        }
        return $this->create_filter_rates_by_currency(new \FedExVendor\WPDesk\FedexShippingService\FedexApi\FedexRateCustomServicesFilter($reply, $settings));
    }
    /**
     * Create rate request builder.
     *
     * @param SettingsValues $settings .
     * @param Shipment       $shipment .
     * @param ShopSettings   $shop_settings .
     *
     * @return FedexRateRequestBuilder
     */
    protected function create_rate_request_builder(\FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \FedExVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment, \FedExVendor\WPDesk\AbstractShipping\Shop\ShopSettings $shop_settings)
    {
        return new \FedExVendor\WPDesk\FedexShippingService\FedexApi\FedexRateRequestBuilder($settings, $shipment, $shop_settings);
    }
    /**
     * Creates rate filter by currency.
     *
     * @param ShipmentRating $rating .
     *
     * @return FedexRateCurrencyFilter .
     */
    protected function create_filter_rates_by_currency(\FedExVendor\WPDesk\AbstractShipping\Rate\ShipmentRating $rating)
    {
        return new \FedExVendor\WPDesk\FedexShippingService\FedexApi\FedexRateCurrencyFilter($rating, $this->shop_settings);
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
            throw new \FedExVendor\WPDesk\FedexShippingService\Exception\CurrencySwitcherException($this->shop_settings);
        }
    }
    /**
     * Should I use a test API?
     *
     * @param \WPDesk\AbstractShipping\Settings\SettingsValues $settings Settings.
     *
     * @return bool
     */
    public function is_testing(\FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings)
    {
        $testing = \false;
        if ($settings->has_value('testing') && $this->shop_settings->is_testing()) {
            $testing = 'yes' === $settings->get_value('testing') ? \true : \false;
        }
        return $testing;
    }
    /**
     * Get settings
     *
     * @return FedexSettingsDefinition
     */
    public function get_settings_definition()
    {
        return new \FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition($this->shop_settings);
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
        return \__('FedEx Live Rates', 'flexible-shipping-fedex');
    }
    /**
     * Get description.
     *
     * @return string
     */
    public function get_description()
    {
        $link = $this->shop_settings->get_locale() === 'pl_PL' ? 'https://octol.io/fedex-settings-docs-pl' : 'https://octol.io/fedex-setting-docs';
        return \sprintf(\__('Dynamically calculated FedEx live rates based on the established FedEx API connection. %1$sLearn more →%2$s', 'flexible-shipping-fedex'), '<a href="' . $link . '" target="_blank">', '</a>');
    }
    /**
     * Pings API.
     * Returns empty string on success or error message on failure.
     *
     * @param SettingsValues  $settings .
     * @param LoggerInterface $logger .
     * @return string
     */
    public function check_connection(\FedExVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \FedExVendor\Psr\Log\LoggerInterface $logger)
    {
        try {
            $connection_checker = new \FedExVendor\WPDesk\FedexShippingService\FedexApi\ConnectionChecker($settings, $logger, $this->is_testing($settings));
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
        return \FedExVendor\WPDesk\FedexShippingService\FedexSettingsDefinition::FIELD_API_PASSWORD;
    }
}
