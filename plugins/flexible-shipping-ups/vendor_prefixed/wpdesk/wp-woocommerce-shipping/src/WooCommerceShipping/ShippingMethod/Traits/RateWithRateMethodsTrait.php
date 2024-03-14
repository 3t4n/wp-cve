<?php

namespace UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\Traits;

use UpsFreeVendor\Psr\Log\LoggerInterface;
use WC_Cart;
use UpsFreeVendor\WPDesk\AbstractShipping\ShippingService;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition;
use UpsFreeVendor\WPDesk\WooCommerceShipping\CustomOrigin\CustomOriginFields;
use UpsFreeVendor\WPDesk\WooCommerceShipping\CustomOrigin\InstanceCustomOriginFields;
use UpsFreeVendor\WPDesk\WooCommerceShipping\FreeShipping\FreeShipping;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingBuilder\AddressProvider;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingBuilder\CustomOriginAddressSender;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceAddressReceiver;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceAddressSender;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingBuilder;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingMetaDataBuilder;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasCustomOrigin;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasInstanceCustomOrigin;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasEstimatedDeliveryDates;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasHandlingFees;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasFreeShipping;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\ErrorLogCatcher;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\RateMethod;
/**
 * Facilitates RateMethods usage in ShippingMethod.
 *
 * @package WPDesk\WooCommerceShipping\ShippingMethod\Traits
 */
trait RateWithRateMethodsTrait
{
    /** @var RateMethod[] */
    private $rate_methods = [];
    /** @var WooCommerceShippingBuilder */
    protected $shipping_builder;
    /**
     * @return CustomOriginAddressSender .
     */
    private function create_sender_address_from_custom_origin()
    {
        $origin_country = \explode(':', $this->get_option(\UpsFreeVendor\WPDesk\WooCommerceShipping\CustomOrigin\CustomOriginFields::ORIGIN_COUNTRY, ''));
        return new \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingBuilder\CustomOriginAddressSender($this->get_option(\UpsFreeVendor\WPDesk\WooCommerceShipping\CustomOrigin\CustomOriginFields::ORIGIN_ADDRESS, ''), '', $this->get_option(\UpsFreeVendor\WPDesk\WooCommerceShipping\CustomOrigin\CustomOriginFields::ORIGIN_CITY, ''), $this->get_option(\UpsFreeVendor\WPDesk\WooCommerceShipping\CustomOrigin\CustomOriginFields::ORIGIN_POSTCODE, ''), isset($origin_country[0]) ? $origin_country[0] : '', isset($origin_country[1]) ? $origin_country[1] : '');
    }
    /**
     * @return CustomOriginAddressSender .
     */
    private function create_sender_address_from_custom_origin_from_instance_options()
    {
        $origin_country = \explode(':', $this->get_instance_option(\UpsFreeVendor\WPDesk\WooCommerceShipping\CustomOrigin\InstanceCustomOriginFields::ORIGIN_COUNTRY, ''));
        return new \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingBuilder\CustomOriginAddressSender($this->get_instance_option(\UpsFreeVendor\WPDesk\WooCommerceShipping\CustomOrigin\InstanceCustomOriginFields::ORIGIN_ADDRESS, ''), '', $this->get_instance_option(\UpsFreeVendor\WPDesk\WooCommerceShipping\CustomOrigin\InstanceCustomOriginFields::ORIGIN_CITY, ''), $this->get_instance_option(\UpsFreeVendor\WPDesk\WooCommerceShipping\CustomOrigin\InstanceCustomOriginFields::ORIGIN_POSTCODE, ''), isset($origin_country[0]) ? $origin_country[0] : '', isset($origin_country[1]) ? $origin_country[1] : '');
    }
    /**
     * Is custom origin?
     *
     * @return bool
     */
    public function is_custom_origin()
    {
        return $this instanceof \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasCustomOrigin && 'yes' === $this->get_option(\UpsFreeVendor\WPDesk\WooCommerceShipping\CustomOrigin\CustomOriginFields::CUSTOM_ORIGIN, 'no');
    }
    /**
     * Is custom origin in shipping zone?
     *
     * @return bool
     */
    public function is_custom_origin_for_instance()
    {
        return $this instanceof \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasInstanceCustomOrigin && 'yes' === $this->get_instance_option(\UpsFreeVendor\WPDesk\WooCommerceShipping\CustomOrigin\InstanceCustomOriginFields::CUSTOM_ORIGIN, 'no');
    }
    /**
     * Get origin country code.
     *
     * @return string
     */
    public function get_origin_country_state()
    {
        if ($this->is_custom_origin()) {
            return $this->get_option(\UpsFreeVendor\WPDesk\WooCommerceShipping\CustomOrigin\CustomOriginFields::ORIGIN_COUNTRY, '');
        } else {
            return \get_option('woocommerce_default_country', '');
        }
    }
    /**
     * Get origin country code.
     *
     * @return string
     */
    public function get_origin_country_code()
    {
        $country_state_code = \explode(':', $this->get_origin_country_state());
        return $country_state_code[0];
    }
    /**
     * @return AddressProvider
     */
    protected function create_sender_address()
    {
        if ($this instanceof \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasInstanceCustomOrigin && $this->is_custom_origin_for_instance()) {
            return $this->create_sender_address_from_custom_origin_from_instance_options();
        }
        if ($this instanceof \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasCustomOrigin && $this->is_custom_origin()) {
            return $this->create_sender_address_from_custom_origin();
        }
        return new \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceAddressSender();
    }
    /**
     * Always creates shipping builder. Can be overwritten to change factory.
     *
     * @param array $package WooCommerce Package to ship.
     *
     * @return WooCommerceShippingBuilder
     */
    protected function create_shipping_builder(array $package)
    {
        if ($this->shipping_builder === null) {
            $this->shipping_builder = new \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingBuilder();
        }
        $this->shipping_builder->set_weight_unit(\get_option('woocommerce_weight_unit'));
        $this->shipping_builder->set_dimension_unit(\get_option('woocommerce_dimension_unit'));
        $this->shipping_builder->set_rounding_precision(\wc_get_rounding_precision());
        $this->shipping_builder->set_currency(\get_woocommerce_currency());
        $this->shipping_builder->set_sender_address($this->create_sender_address());
        $this->shipping_builder->set_woocommerce_package($package);
        $this->shipping_builder->set_receiver_address(new \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceAddressReceiver($package));
        return $this->shipping_builder;
    }
    /**
     * Add method that can generate rates.
     *
     * @param RateMethod $method
     */
    private function add_rate_method(\UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\RateMethod $method)
    {
        $this->rate_methods[] = $method;
    }
    /**
     * Add rate methods settings to shipment service settings
     *
     * @param array $settings Settings from \WC_Shipping_Method
     *
     * @return array Settings with rate settings
     */
    private function add_rate_methods_settings(array $settings)
    {
        foreach ($this->rate_methods as $method) {
            $settings = $method->add_to_settings($settings);
        }
        return $settings;
    }
    /**
     * Handle rating using rate methods.
     *
     * @param LoggerInterface                    $logger  Logger that was used in service
     * @param ShippingService                    $service Shipping service
     * @param array                              $package WooCommerce Package to rate
     * @param WooCommerceShippingMetaDataBuilder $metadata_builder
     */
    private function handle_rating_using_methods(\UpsFreeVendor\Psr\Log\LoggerInterface $logger, \UpsFreeVendor\WPDesk\AbstractShipping\ShippingService $service, array $package, \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingMetaDataBuilder $metadata_builder)
    {
        $loggerWithErrorCatching = new \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\ErrorLogCatcher($logger);
        $service->setLogger($loggerWithErrorCatching);
        $shipping_builder = $this->create_shipping_builder($package);
        foreach ($this->rate_methods as $method) {
            /** @var ShippingMethod $this */
            $method->handle_rates($this, $loggerWithErrorCatching, $metadata_builder, $shipping_builder);
        }
    }
    /**
     * Add rate.
     *
     * @param array $args Args.
     */
    public function add_rate($args = array())
    {
        $cart = \WC()->cart;
        $logger = $this->inject_logger_into($this->get_shipping_service($this));
        if ($this instanceof \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasEstimatedDeliveryDates) {
            if ($this->should_exclude_rate_with_maximum_transit_time($this, $args['meta_data'])) {
                return;
            }
        }
        if ($this instanceof \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasHandlingFees) {
            $args['cost'] = $this->apply_handling_fees_if_enabled($args['cost']);
        }
        if ($cart instanceof \WC_Cart && $this instanceof \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasFreeShipping) {
            $free_shipping = new \UpsFreeVendor\WPDesk\WooCommerceShipping\FreeShipping\FreeShipping($this, $logger);
            if ($free_shipping->is_enabled()) {
                $subtotal = (float) $cart->get_displayed_subtotal();
                $can_apply = $free_shipping->can_apply($subtotal);
                $free_shipping->debug($can_apply, $subtotal);
                if ($can_apply) {
                    $args['cost'] = 0;
                }
            }
        }
        parent::add_rate($args);
    }
}
