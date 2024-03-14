<?php

/**
 * Class of shipping method launched by WooCommerce
 *
 * @package WPDesk\WooCommerceShipping
 */
namespace FedExVendor\WPDesk\WooCommerceShipping;

use FedExVendor\WPDesk\AbstractShipping\ShippingService;
use FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRate;
use FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRateToCollectionPoint;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingMetaDataBuilder;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasCollectionPointFlatRate;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\CollectionPoint\CollectionPointRateMethod;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\Fallback\FallbackRateMethod;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\FlatRateRateMethod\CollectionPointFlatRateRateMethod;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\Standard\StandardServiceRateMethod;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\Traits\DeliveryDatesTrait;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\Traits\MetaDataTrait;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\Traits\RateWithRateMethodsTrait;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\Traits\LoggerTrait;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\Traits\SettingsTrait;
use FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\Traits\ShippingServiceTrait;
/**
 * Class ShippingMethod
 */
class ShippingMethod extends \WC_Shipping_Method
{
    use LoggerTrait;
    use RateWithRateMethodsTrait;
    use ShippingServiceTrait;
    use SettingsTrait;
    use MetaDataTrait;
    use DeliveryDatesTrait;
    /** @var PluginShippingDecisions */
    protected static $plugin_shipping_decisions;
    /**
     * Is method enabled.
     *
     * @var bool
     */
    private $is_method_enabled = \false;
    /**
     * ShipmentMethod constructor.
     *
     * @param int $instance_id Instance ID.
     */
    public function __construct($instance_id = 0)
    {
        parent::__construct($instance_id);
        $shipping_service = $this->get_shipping_service($this);
        $this->id = $shipping_service->get_unique_id();
        $this->title = $shipping_service->get_name();
        $this->method_title = $shipping_service->get_name();
        $this->method_description = $shipping_service->get_description();
        $this->form_fields = $this->get_form_fields_from_shipping_service($this->get_plugin_shipping_decisions());
        $this->tax_status = 'taxable';
        $this->init();
        if ($this->instance_id) {
            $this->title = $this->get_option('title', $this->title);
        }
    }
    /**
     * Set shipping service.
     *
     * @param PluginShippingDecisions $plugin_shipping_decisions .
     */
    public static function set_plugin_shipping_decisions(\FedExVendor\WPDesk\WooCommerceShipping\PluginShippingDecisions $plugin_shipping_decisions)
    {
        self::$plugin_shipping_decisions = $plugin_shipping_decisions;
    }
    /**
     * @return PluginShippingDecisions .
     */
    public function get_plugin_shipping_decisions()
    {
        return static::$plugin_shipping_decisions;
    }
    /**
     * Init method.
     */
    protected function init()
    {
        $this->metadata_builder = $this->create_metadata_builder();
        $this->build_form_fields();
        $this->init_settings();
        if ($this->instance_id && empty($this->instance_settings)) {
            $this->init_instance_settings();
        }
        $this->enable_shipping_method_if_not_exists($this->settings);
        $this->is_method_enabled = 'yes' === $this->get_option('enable_shipping_method', 'yes');
        \add_action('woocommerce_update_options_shipping_' . $this->id, [$this, 'process_admin_options']);
    }
    /**
     * Create meta data builder.
     *
     * @return WooCommerceShippingMetaDataBuilder
     */
    protected function create_metadata_builder()
    {
        return new \FedExVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceShippingMetaDataBuilder($this);
    }
    /**
     * Init form fields.
     */
    public function build_form_fields()
    {
        $this->form_fields = $this->add_rate_methods_settings($this->form_fields);
    }
    /**
     * Enable shipping method by default.
     *
     * @param array $settings Settings.
     */
    private function enable_shipping_method_if_not_exists($settings)
    {
        if (empty($settings['enable_shipping_method'])) {
            $this->settings['enable_shipping_method'] = 'yes';
        }
    }
    /**
     * Called to calculate shipping rates for this method. Rates can be added using the add_rate() method.
     *
     * @param array $package Package array.
     */
    public function calculate_shipping($package = [])
    {
        if ($this->should_calculate_shipping()) {
            if ($this instanceof \FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasCollectionPointFlatRate && $this->is_flat_rate_enabled($this)) {
                $this->add_rate_method(new \FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\FlatRateRateMethod\CollectionPointFlatRateRateMethod($this->get_flat_rate_cost($this), $this->get_flat_rate_shipping_rate_suffix($this)));
            } else {
                $service = $this->get_shipping_service($this);
                $sender_address = $this->create_sender_address();
                if ($service instanceof \FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRate) {
                    $this->add_rate_method(new \FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\Standard\StandardServiceRateMethod($service));
                }
                if ($service instanceof \FedExVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRateToCollectionPoint) {
                    $this->add_rate_method(new \FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\CollectionPoint\CollectionPointRateMethod($service));
                }
                $this->add_rate_method(new \FedExVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\Fallback\FallbackRateMethod('yes' === $this->get_option('debug_mode', 'no')));
            }
            $logger = $this->inject_logger_into($this->get_shipping_service($this));
            $this->handle_rating_using_methods($logger, $this->get_shipping_service($this), $package, $this->metadata_builder);
        }
    }
    /**
     * @return bool
     */
    protected function should_calculate_shipping()
    {
        return $this->is_method_enabled;
    }
    public function get_method_description()
    {
        $description = parent::get_method_description();
        if ($this->instance_id === 0) {
            $description = $this->remove_links_from_description($description);
        }
        return $description;
    }
    private function remove_links_from_description($description)
    {
        return \preg_replace("/(<a .*>.*?<\\/a>)/is", '', $description);
    }
}
