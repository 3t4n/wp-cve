<?php

namespace UpsFreeVendor\WPDesk\UpsShippingService;

use UpsFreeVendor\Octolize\Ups\RestApi\RestApiClient;
use UpsFreeVendor\Octolize\Ups\RestApi\UpsRestApiSender;
use UpsFreeVendor\Psr\Log\LoggerInterface;
use UpsFreeVendor\Ups\Entity\RateRequest;
use UpsFreeVendor\Ups\Entity\RateResponse;
use UpsFreeVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint;
use UpsFreeVendor\WPDesk\AbstractShipping\Exception\InvalidSettingsException;
use UpsFreeVendor\WPDesk\AbstractShipping\Exception\RateException;
use UpsFreeVendor\WPDesk\AbstractShipping\Exception\UnitConversionException;
use UpsFreeVendor\WPDesk\AbstractShipping\Rate\ShipmentRating;
use UpsFreeVendor\WPDesk\AbstractShipping\Rate\SingleRate;
use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment;
use UpsFreeVendor\WPDesk\AbstractShipping\ShippingService;
use UpsFreeVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRate;
use UpsFreeVendor\WPDesk\AbstractShipping\ShippingServiceCapability\HasSettings;
use UpsFreeVendor\WPDesk\UpsShippingService\CurrencyVerify\UpsCurrencyVerifyRatesFilter;
use UpsFreeVendor\WPDesk\UpsShippingService\Exception\CurrencySwitcherException;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\ConnectionChecker;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\Sender;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\UpsRateReplyInterpretation;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\UpsRateRequestBuilder;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\UpsSender;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShopSettings;
/**
 * Ups main shipping class injected into WooCommerce shipping method.
 */
abstract class AbstractUpsShippingService extends \UpsFreeVendor\WPDesk\AbstractShipping\ShippingService implements \UpsFreeVendor\WPDesk\AbstractShipping\ShippingServiceCapability\HasSettings, \UpsFreeVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRate
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
    /**
     * Origin country.
     *
     * @var string
     */
    private $origin_country;
    /**
     * @var RestApiClient
     */
    protected $rest_api_client;
    const UNIQUE_ID = 'flexible_shipping_ups';
    /**
     * UpsShippingService constructor.
     *
     * @param LoggerInterface    $logger Logger.
     * @param ShopSettings       $shop_settings Helper.
     * @param string             $origin_country Origin country.
     * @param RestApiClient|null $rest_api_client Client.
     */
    public function __construct(\UpsFreeVendor\Psr\Log\LoggerInterface $logger, \UpsFreeVendor\WPDesk\WooCommerceShipping\ShopSettings $shop_settings, string $origin_country, \UpsFreeVendor\Octolize\Ups\RestApi\RestApiClient $rest_api_client)
    {
        $this->logger = $logger;
        $this->shop_settings = $shop_settings;
        $this->origin_country = $origin_country;
        $this->rest_api_client = $rest_api_client;
    }
    /**
     * Set logger.
     *
     * @param LoggerInterface $logger Logger.
     */
    public function setLogger(\UpsFreeVendor\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * .
     *
     * @return LoggerInterface
     */
    public function get_logger()
    {
        return $this->logger;
    }
    /**
     * .
     *
     * @return ShopSettings
     */
    public function get_shop_settings()
    {
        return $this->shop_settings;
    }
    /**
     * Create sender.
     *
     * @param SettingsValues $settings Settings Values.
     *
     * @return UpsSender
     */
    protected function create_sender(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings)
    {
        if ($settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::API_TYPE, \UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::API_TYPE_XML) === \UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::API_TYPE_REST) {
            return new \UpsFreeVendor\Octolize\Ups\RestApi\UpsRestApiSender($this->rest_api_client, $this->logger, $this->is_testing($settings), $this->shop_settings->is_tax_enabled());
        } else {
            return new \UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\UpsSender($settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::ACCESS_KEY), $settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::USER_ID), $settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::PASSWORD), $this->logger, $this->is_testing($settings), $this->shop_settings->is_tax_enabled());
        }
    }
    /**
     * Create reply interpretation.
     *
     * @param RateResponse   $response .
     * @param ShopSettings   $shop_settings .
     * @param SettingsValues $settings .
     *
     * @return UpsRateReplyInterpretation
     */
    protected function create_reply_interpretation(\UpsFreeVendor\Ups\Entity\RateResponse $response, $shop_settings, $settings)
    {
        return new \UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\UpsRateReplyInterpretation($response, $shop_settings->is_tax_enabled());
    }
    /**
     * Create shipment rating implementation.
     *
     * @param SingleRate[]   $rates .
     * @param bool           $is_access_point_rating .
     * @param SettingsValues $settings .
     *
     * @return ShipmentRating
     */
    protected function create_shipment_rating_implementation(array $rates, $is_access_point_rating, $settings)
    {
        return new \UpsFreeVendor\WPDesk\UpsShippingService\UpsShipmentRatingImplementation($rates, $is_access_point_rating);
    }
    /**
     * Rate shipment.
     *
     * @param SettingsValues       $settings Settings Values.
     * @param Shipment             $shipment Shipment.
     * @param array                $services Services.
     * @param Sender               $sender Sender.
     * @param CollectionPoint|null $collection_point Collection point.
     *
     * @return ShipmentRating
     * @throws InvalidSettingsException InvalidSettingsException.
     * @throws RateException RateException.
     * @throws UnitConversionException Weight exception.
     */
    protected function rate_shipment_for_ups(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment, array $services, \UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\Sender $sender, \UpsFreeVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint $collection_point = null)
    {
        if (!$this->get_settings_definition()->validate_settings($settings)) {
            throw new \UpsFreeVendor\WPDesk\AbstractShipping\Exception\InvalidSettingsException();
        }
        $this->verify_currency($this->shop_settings->get_default_currency(), $this->shop_settings->get_currency());
        $request_builder = $this->create_rate_request_builder($settings, $shipment, $this->shop_settings);
        $request_builder->build_request();
        if ($collection_point) {
            $request_builder->set_collection_point($collection_point);
        }
        $request = $request_builder->get_build_request();
        try {
            $response = $sender->send($request);
            $reply = $this->create_reply_interpretation($response, $this->shop_settings, $settings);
            if ($reply->has_reply_warning()) {
                $this->logger->info($reply->get_reply_message());
            }
            $ups_rates = $reply->get_rates();
            $rates = $this->create_shipment_rating_implementation($this->filter_service_rates($services, $ups_rates, $this->is_custom_services_enabled($settings)), null !== $collection_point, $settings);
            $rates = new \UpsFreeVendor\WPDesk\UpsShippingService\CurrencyVerify\UpsCurrencyVerifyRatesFilter($rates, $this->shop_settings, $this->logger);
        } catch (\UpsFreeVendor\WPDesk\AbstractShipping\Exception\RateException $e) {
            $this->logger->info('UPS response', $e->get_context());
            throw $e;
        }
        return $rates;
    }
    /**
     * Create rate request builder.
     *
     * @param SettingsValues $settings .
     * @param Shipment       $shipment .
     * @param ShopSettings   $shop_settings .
     *
     * @return UpsRateRequestBuilder
     */
    protected function create_rate_request_builder(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment, \UpsFreeVendor\WPDesk\WooCommerceShipping\ShopSettings $shop_settings)
    {
        return new \UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\UpsRateRequestBuilder($settings, $shipment, $shop_settings);
    }
    /**
     * Is standard rate enabled?
     *
     * @param SettingsValues $settings .
     *
     * @return bool
     */
    public function is_rate_enabled(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings)
    {
        return \UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::ADD_ONLY_ACCESS_POINTS_TO_RATES !== $settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::ACCESS_POINT, \UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::DO_NOT_ADD_ACCESS_POINTS_TO_RATES);
    }
    /**
     * @param SettingsValues $settings
     *
     * @return array
     */
    protected function get_services(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings)
    {
        return $this->is_custom_services_enabled($settings) ? $settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::FIELD_SERVICES_TABLE) : $this->convert_services_to_settings_services(\UpsFreeVendor\WPDesk\UpsShippingService\UpsServices::get_services_for_country($this->origin_country));
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
            throw new \UpsFreeVendor\WPDesk\UpsShippingService\Exception\CurrencySwitcherException();
        }
    }
    /**
     * @param Shipment $shipment .
     *
     * @return bool
     */
    private function is_us_domestic_shipment(\UpsFreeVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment)
    {
        return 'US' === $shipment->ship_from->address->country_code && 'US' === $shipment->ship_to->address->country_code;
    }
    /**
     * Log request once.
     *
     * @param RateRequest $request Request.
     */
    private function log_request_once(\UpsFreeVendor\Ups\Entity\RateRequest $request)
    {
        static $already_logged;
        if (!$already_logged) {
            $this->logger->info('UPS request', ['request' => $request->getShipment()]);
            $already_logged = \true;
        }
    }
    /**
     * Should I use a test API?
     *
     * @param SettingsValues $settings Settings.
     *
     * @return bool
     */
    public function is_testing(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings)
    {
        $testing = \false;
        if ($settings->has_value('testing') && $this->shop_settings->is_testing()) {
            $testing = 'yes' === $settings->get_value('testing') ? \true : \false;
        }
        return $testing;
    }
    /**
     * Filter&change rates according to settings.
     *
     * @param array         $services Services.
     * @param SingleRate[]  $ups_rates Response.
     * @param bool          $sort_services .
     *
     * @return SingleRate[]
     */
    private function filter_service_rates(array $services, array $ups_rates, $sort_services = \false)
    {
        $rates = [];
        if (!empty($ups_rates)) {
            foreach ($ups_rates as $service_id => $service) {
                if (isset($service->service_type) && isset($services[$service->service_type]) && !empty($services[$service->service_type]['enabled'])) {
                    $service->service_name = $services[$service->service_type]['name'];
                    $rates[$service->service_type] = $service;
                }
            }
            if ($sort_services) {
                $rates = $this->sort_services($rates, $services);
            }
        }
        return $rates;
    }
    /**
     * @param array $services
     *
     * @return array
     */
    private function convert_services_to_settings_services(array $services)
    {
        $settings_services = [];
        foreach ($services as $service_id => $service) {
            $settings_services[$service_id] = ['name' => $service, 'enabled' => $service_id];
        }
        return $settings_services;
    }
    /**
     * Sort rates according to order set in admin settings.
     *
     * @param SingleRate[] $rates           Rates.
     * @param array        $option_services Saved services to settings.
     *
     * @return SingleRate[]
     */
    private function sort_services($rates, $option_services)
    {
        if (!empty($option_services)) {
            $services = [];
            foreach ($option_services as $service_code => $service_name) {
                if (isset($rates[$service_code])) {
                    $services[] = $rates[$service_code];
                }
            }
            return $services;
        }
        return $rates;
    }
    /**
     * Are customs service settings enabled.
     *
     * @param SettingsValues $settings Values.
     *
     * @return bool
     */
    private function is_custom_services_enabled(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings)
    {
        return $settings->has_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::CUSTOM_SERVICES) && 'yes' === $settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::CUSTOM_SERVICES);
    }
    /**
     * Get settings
     *
     * @return UpsSettingsDefinition
     */
    public function get_settings_definition()
    {
        return new \UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition($this->shop_settings);
    }
    /**
     * Pings API.
     * Returns empty string on success or error message on failure.
     *
     * @param SettingsValues  $settings .
     * @param LoggerInterface $logger .
     * @return string
     */
    public function check_connection(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \UpsFreeVendor\Psr\Log\LoggerInterface $logger)
    {
        try {
            $connection_checker = new \UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\ConnectionChecker($this, $this->create_sender($settings), $settings, $logger);
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
        return \UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::DEBUG_MODE;
    }
}
