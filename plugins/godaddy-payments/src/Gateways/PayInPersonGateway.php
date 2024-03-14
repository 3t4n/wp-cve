<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Gateways;

use Exception;
use GoDaddy\WooCommerce\Poynt\Blocks\PayInPersonCheckoutBlockIntegration;
use GoDaddy\WooCommerce\Poynt\Handlers\PaymentTokensHandler;
use GoDaddy\WooCommerce\Poynt\Helpers\ArrayHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\PoyntHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\WCHelper;
use GoDaddy\WooCommerce\Poynt\Plugin;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;
use WC_Data_Store;
use WC_Order;
use WC_Order_Item_Product;
use WC_Shipping_Method;
use WC_Shipping_Zone;
use WC_Shipping_Zone_Data_Store;

defined('ABSPATH') or exit;

/**
 * Pay in Person gateway handler.
 *
 * @since 1.3.1
 *
 * @method Plugin get_plugin()
 */
#[\AllowDynamicProperties]
class PayInPersonGateway extends Framework\SV_WC_Payment_Gateway_Direct
{
    /** @var string[] default shipping methods for GoDaddy Payments - Selling in Person */
    protected $defaultEnableForMethods = ['local_pickup', 'gdp_local_delivery', 'local_pickup_plus'];

    /** @var bool shipping methods validation status. */
    protected $isShippingMethodInvalid;

    /** @var string businessId. */
    protected $businessId = '';

    /** @var int terminal original price */
    const TERMINAL_ORIGINAL_PRICE = 499;

    /** @var int terminal sale price */
    const TERMINAL_SALE_PRICE = 399;

    /** @var int terminal original price cad */
    const TERMINAL_ORIGINAL_PRICE_CAD = 599;

    /** @var int terminal sale price cad */
    const TERMINAL_SALE_PRICE_CAD = 399;

    /** @var string payments hub URL */
    const HUB_URL = 'https://payments.godaddy.com/';

    /** @var PayInPersonCheckoutBlockIntegration|null */
    protected ?PayInPersonCheckoutBlockIntegration $payInPersonCheckoutBlockIntegration = null;

    /**
     * PayInPerson gateway constructor.
     *
     * @since 1.3.1
     */
    public function __construct()
    {
        $plugin = poynt_for_woocommerce();

        parent::__construct(
            Plugin::PAYINPERSON_GATEWAY_ID,
            $plugin,
            [
                'method_title'       => __('GoDaddy Payments – Selling in Person', 'godaddy-payments'),
                'method_description' => esc_html__('Customers can buy online and pay in person with orders synced to your Smart Terminal.', 'godaddy-payments'),
                'payment_type'       => Plugin::PAYINPERSON_GATEWAY_ID,
                'supports'           => [
                    self::FEATURE_PRODUCTS,
                    self::FEATURE_REFUNDS,
                    self::FEATURE_CREDIT_CARD_CAPTURE,
                ],
                'countries'  => Plugin::getSupportedCountries(),
                'currencies' => Plugin::getSupportedCurrencies(),
            ]
        );

        $this->isShippingMethodInvalid = false;

        if (! $this->title) {
            $this->title = $this->get_default_title();
        }

        $this->addHooks();

        if (is_admin()) {
            $this->businessId = PoyntHelper::getBusinessId();
        }
    }

    /**
     * Setup gateway hooks.
     *
     * @since 1.3.1
     */
    private function addHooks()
    {
        // add styles for gateway
        add_action('admin_enqueue_scripts', [$this, 'addGatewayStyles']);

        // Add instructions to WC order emails sent to the customers.
        add_action('woocommerce_email_before_order_table', [$this, 'instructionsEmail'], 20, 2);
    }

    /**
     * Adds the admin gateway setup page stylesheet.
     *
     * @since 1.3.1
     *
     * @internal
     *
     * @return void
     */
    public function addGatewayStyles()
    {
        if (! WCHelper::isAccessingSettings(Plugin::PAYINPERSON_GATEWAY_ID)) {
            return;
        }

        wp_enqueue_style(Plugin::PAYINPERSON_GATEWAY_ID, $this->get_plugin()->get_plugin_url().'/assets/css/pay-in-person.css', [], $this->get_plugin()->get_version());
    }

    /**
     * Enqueues the gateway assets.
     *
     * @since 1.7.0
     *
     * @return void
     */
    protected function enqueue_gateway_assets()
    {
        if (is_order_received_page() || (is_account_page() && ! is_add_payment_method_page())) {
            return;
        }

        // @TODO we are currently registering Poynt Collect only because the block integration shares code with the credit card gateway, but ideally this should be removed {unfulvio 2024-01-09}
        if ($environment = $this->get_plugin()->get_gateway(Plugin::CREDIT_CARD_GATEWAY_ID)->get_environment()) {
            $this->get_plugin()->registerPoyntCollect($environment);
        }
    }

    /**
     * Process a transaction.
     *
     * @since 1.3.1
     *
     * @param WC_Order $order the order object
     * @return WC_Order
     */
    protected function do_transaction($order)
    {
        return $order;
    }

    /**
     * Render custom order received text on the thank you page.
     *
     * @since 1.3.1
     *
     * @param string $text order received text
     * @param WC_Order|null $order order object
     * @return string
     */
    public function maybe_render_held_order_received_text($text, $order)
    {
        // only show if the user checkout using pay in person payment method
        if ($order instanceof WC_Order && Plugin::PAYINPERSON_GATEWAY_ID === $order->get_payment_method()) {
            $text = $this->get_option('instructions', $this->getDefaultInstructions());
        }

        return wp_kses_post(wpautop(wptexturize($text)));
    }

    /**
     * Builds the capture handler instance.
     * Assigns it to default GoDaddy Payments - Credit Card gateway to handle capture.
     *
     * @since 1.3.0
     */
    public function init_capture_handler()
    {
        $this->capture_handler = new GDPCapture(poynt_for_woocommerce()->get_gateway(Plugin::CREDIT_CARD_GATEWAY_ID));
    }

    /**
     * Return the Payment Tokens Handler class instance.
     *
     * @since 1.3.2
     *
     * @return PaymentTokensHandler
     */
    protected function build_payment_tokens_handler()
    {
        return new PaymentTokensHandler($this);
    }

    /**
     * Adds instructions to WC order emails sent to the customers.
     *
     * @since 1.3.0
     *
     * @internal
     *
     * @param WC_Order $order Order object
     * @param bool $sentToAdmin Sent to admin
     * @return void
     */
    public function instructionsEmail($order, $sentToAdmin)
    {
        if (! $sentToAdmin && Plugin::PAYINPERSON_GATEWAY_ID === $order->get_payment_method()) {
            echo wp_kses_post(wpautop(wptexturize($this->get_option('instructions', $this->getDefaultInstructions()))).PHP_EOL);
        }
    }

    /**
     * Toggle between settings page and Poynt Terminal CTA based on terminal activation status.
     *
     * @see \WC_Settings_API::admin_options()
     *
     * @since 1.3.1
     *
     * @return void
     * @throws Exception
     */
    public function admin_options()
    {
        echo '<h2>'.esc_html($this->get_method_title());
        wc_back_link(__('Return to payments', 'godaddy-payments'), admin_url('admin.php?page=wc-settings&tab=checkout'));
        echo '</h2>';
        echo wp_kses_post(wpautop($this->get_method_description()));

        if (PoyntHelper::hasPoyntSmartTerminalActivated()):?>
            <p class="pay-in-person-settings-description">
                <?php printf(
                    /* translators: Placeholders: %1$s - opening HTML tag, %2$s - closing HTML tag */
                    esc_html__('%1$sShop Smart Terminal%2$s', 'godaddy-payments'),
                    '<a href="'.esc_url($this->getSmartTerminalProductPageUrl()).'" target="_blank">',
                    ' <span class="dashicons dashicons-external"></span></a>'
                ); ?>
                &nbsp;|&nbsp;
                <?php printf(
                    /* translators: Placeholders: %1$s - opening HTML tag, %2$s - closing HTML tag */
                    esc_html__('%1$sDevices%2$s', 'godaddy-payments'),
                    '<a href="'.esc_url(add_query_arg('businessId', $this->businessId, self::HUB_URL.'in-person/devices')).'" target="_blank">',
                    ' <span class="dashicons dashicons-external"></span></a>'
                ); ?>
                &nbsp;|&nbsp;
                <?php printf(
                    /* translators: Placeholders: %1$s - opening HTML tag, %2$s - closing HTML tag */
                    esc_html__('%1$sCatalogs%2$s', 'godaddy-payments'),
                    '<a href="'.esc_url(add_query_arg('businessId', $this->businessId, self::HUB_URL.'in-person/catalog')).'" target="_blank">',
                    ' <span class="dashicons dashicons-external"></span></a>'
                ); ?>
                &nbsp;|&nbsp;
                <?php printf(
                    /* translators: Placeholders: %1$s - opening HTML tag, %2$s - closing HTML tag */
                    esc_html__('%1$sCustomize Terminal%2$s', 'godaddy-payments'),
                    '<a href="'.esc_url(add_query_arg('businessId', $this->businessId, self::HUB_URL.'in-person/customization')).'" target="_blank">',
                    ' <span class="dashicons dashicons-external"></span></a>'
                ); ?>
            </p>
            <table class="form-table">
                <?php echo $this->generate_settings_html($this->get_form_fields(), false); ?>
            </table>
            <?php $this->display_errors(); ?>
        <?php else:
            $GLOBALS['hide_save_button'] = true; ?>
            <div class="pay-in-person-settings-no-order">
                <div class="pay-in-person-settings-no-order-upper">
                    <h4> <?php echo __('Smart Terminal', 'godaddy-payments'); ?></h4>
                    <h2> <?php echo __('Dual screens for smoother selling.', 'godaddy-payments'); ?></h2>
                    <p>  <?php echo __('Our dual screens make check out a breeze. Plus, our all-in-one terminal includes a built-in payment processor, scanner, printer, security and more.', 'godaddy-payments'); ?></p>
                </div>
                <div class="pay-in-person-settings-no-order-lower">
                    <div class="pay-in-person-settings-no-order-lower-inner">
                        <div class="pay-in-person-settings-no-order-price">
                            <span class="pay-in-person-settings-no-order-price-sale">$<?php echo $this->get_plugin()->getCountry() === 'CA' ? static::TERMINAL_SALE_PRICE_CAD : static::TERMINAL_SALE_PRICE; ?></span>
                            <span class="pay-in-person-settings-no-order-price-linethrough">$<?php echo $this->get_plugin()->getCountry() === 'CA' ? static::TERMINAL_ORIGINAL_PRICE_CAD : static::TERMINAL_ORIGINAL_PRICE; ?></span>
                        </div>
                        <div class="pay-in-person-settings-no-order-badges">
                            <span class="pay-in-person-settings-no-order-free"> <?php echo __('Free', 'godaddy-payments'); ?></span>
                            <span class="pay-in-person-settings-no-order-shipping"> <?php echo __('2-day shipping.', 'godaddy-payments'); ?></span>
                        </div>
                        <div class="pay-in-person-settings-no-order-btn">
                            <a target="_blank" href="<?php echo esc_url($this->getSmartTerminalProductPageUrl()); ?>"> <?php echo __('Learn More', 'godaddy-payments'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif;
    }

    /**
     * Gets the Smart Terminal product page URL.
     *
     * @since 1.3.0
     *
     * @return string
     * @throws Exception
     */
    public function getSmartTerminalProductPageUrl() : string
    {
        return sprintf(
            'https://payments.godaddy.com/in-person/shop/96b9694e-1d63-47b0-85e6-3c773beaa004%s',
            ! empty($this->businessId) ? '?businessId='.$this->businessId : ''
        );
    }

    /**
     * Gets the Pay in Person form fields.
     *
     * @since 1.3.1
     *
     * @return array
     * @throws Exception
     */
    protected function get_method_form_fields() : array
    {
        // hide debug mode
        unset($this->form_fields['debug_mode']);

        return [
            'title' => [
                'title'    => esc_html__('Title', 'godaddy-payments'),
                'type'     => 'text',
                'desc_tip' => esc_html__('Payment method title that the customer will see during checkout.', 'godaddy-payments'),
                'default'  => $this->get_default_title(),
            ],
            'instructions' => [
                'title'    => esc_html__('Order received instructions', 'godaddy-payments'),
                'type'     => 'textarea',
                'default'  => $this->getDefaultInstructions(),
                'desc_tip' => esc_html__('Message that the customer will see on the order received page and in the processing order email after checkout.', 'godaddy-payments'),
            ],
            'enable_for_methods' => [
                'title'             => __('Enable for Shipping Methods', 'godaddy-payments'),
                'type'              => 'multiselect',
                'class'             => 'wc-enhanced-select',
                'css'               => 'width: 400px;',
                'default'           => $this->defaultEnableForMethods,
                'options'           => $this->loadShippingMethodOptions(),
                'desc_tip'          => esc_html__('Select the shipping methods that will show this payment method for the customer during checkout.', 'godaddy-payments'),
                'custom_attributes' => [
                    'data-placeholder' => __('Select Shipping Methods', 'godaddy-payments'),
                ],
            ],
        ];
    }

    /**
     * Gets the checkout block integration instance.
     *
     * @since 1.7.0
     *
     * @return PayInPersonCheckoutBlockIntegration
     */
    public function get_checkout_block_integration_instance() : ?Framework\Payment_Gateway\Blocks\Gateway_Checkout_Block_Integration
    {
        if (null === $this->payInPersonCheckoutBlockIntegration) {
            require_once $this->get_plugin()->get_plugin_path().'/src/Blocks/PayInPersonCheckoutBlockIntegration.php';

            $this->payInPersonCheckoutBlockIntegration = new PayInPersonCheckoutBlockIntegration($this->get_plugin(), $this);
        }

        return $this->payInPersonCheckoutBlockIntegration;
    }

    /**
     * Determines whether the gateway is configured.
     *
     * @since 1.3.1
     *
     * @return bool
     * @throws Exception
     */
    public function is_configured() : bool
    {
        return PoyntHelper::hasPoyntSmartTerminalActivated();
    }

    /**
     * Determines if the gateway is available at checkout.
     *
     * Note: this method is called by WooCommerce, so it needs to remain snake_case.
     *
     * @since 1.3.0
     *
     * @return bool
     * @throws Exception
     */
    public function is_available()
    {
        return parent::is_available()
            && $this->isChosenShippingMethodAccepted(ArrayHelper::wrap($this->get_option('enable_for_methods')))
            && $this->orderNeedsShipping();
    }

    /**
     * Validates MultiSelect Field to determine whether the user has selected any Shipping Method.
     *
     * Note: this method is called by WooCommerce, so it needs to remain snake_case.
     *
     * @since 1.3.0
     *
     * @param string|int $key
     * @param mixed $value
     * @return array|mixed|null
     */
    public function validate_multiselect_field($key, $value)
    {
        if ('enable_for_methods' === $key && empty($value)) {
            $this->isShippingMethodInvalid = true;
            $this->add_error(__('At least one shipping method is required to enable Selling in Person.', 'godaddy-payments'));
        }

        return $value;
    }

    /**
     * Loads all the shipping method options for the enable_for_methods field.
     *
     * @since 1.3.0
     *
     * @return array<string, string>
     * @throws Exception
     */
    protected function loadShippingMethodOptions() : array
    {
        $regions = ['US', 'CA'];
        $options = [];
        $shipping = WC()->shipping();

        if (! $shipping || ! WCHelper::isAccessingSettings(Plugin::PAYINPERSON_GATEWAY_ID)) {
            return [];
        }

        foreach ($shipping->get_shipping_methods() as $shippingMethod) {
            $options[$shippingMethod->get_method_title()][$shippingMethod->id] = $this->getShippingMethodOptionsText($shippingMethod);

            foreach ($this->getAvailableShippingZones($regions) as $shippingZone) {
                foreach ($shippingZone->get_shipping_methods() as $shippingMethodId => $zoneShippingMethod) {
                    if ($zoneShippingMethod->id !== $shippingMethod->id) {
                        continue;
                    }

                    $optionName = sprintf(
                        /* translators: Placeholders: %1$s - shipping zone name, %2$s - shipping method name */
                        esc_html__('%1$s &ndash; %2$s', 'godaddy-payments'),
                        $shippingZone->get_id() ? $shippingZone->get_zone_name() : esc_html__('Other locations', 'godaddy-payments'),
                        /* translators: Placeholders: %1$s - shipping method title, %2$s - shipping method id */
                        sprintf(esc_html__('%1$s (#%2$s)', 'godaddy-payments'), $zoneShippingMethod->get_title(), $shippingMethodId)
                    );

                    $options[$shippingMethod->get_method_title()][$zoneShippingMethod->get_rate_id()] = $optionName;
                }
            }
        }

        return $options;
    }

    /**
     * Gets the method shipping options text.
     *
     * @since 1.3.0
     *
     * @param WC_Shipping_Method $method
     * @return string
     */
    protected function getShippingMethodOptionsText(WC_Shipping_Method $method) : string
    {
        return 'local_pickup_plus' === $method->id
            ? __('Local Pickup Plus method', 'godaddy-payments')
            /* translators: Placeholder: %s - Shipping method name */
            : sprintf(__('Any "%s" method', 'godaddy-payments'), $method->get_method_title());
    }

    /**
     * Can the order be refunded via this gateway?
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder Order object.
     * @return bool If false, the automatic refund button is hidden in the UI.
     */
    public function can_refund_order($wcOrder)
    {
        if (! $wcOrder->get_meta('_wc_poynt_credit_card_trans_id')) {
            return false;
        }

        return parent::can_refund_order($wcOrder);
    }

    /**
     * Adds the standard refund transaction data to the order.
     *
     * Overrides the refund meta_key
     *
     * @since 1.3.0
     *
     * @param WC_Order $order the order object
     * @param Framework\SV_WC_Payment_Gateway_API_Response $response transaction response
     */
    protected function add_refund_data(WC_Order $order, $response)
    {
        // indicate the order was refunded along with the refund amount
        $this->add_order_meta($order, 'refund_amount', $order->refund->amount);

        // add refund transaction ID
        if ($response && $response->get_transaction_id()) {
            $this->add_order_meta($order, 'refund_remoteId', $response->get_transaction_id());
        }
    }

    /**
     * Return the methods shipping options text.
     *
     * @since 1.3.0
     *
     * @param string[] $availableRegions
     * @return array
     * @throws Exception
     */
    protected function getAvailableShippingZones(array $availableRegions) : array
    {
        $availableZones = [];

        /** @var WC_Shipping_Zone_Data_Store $dataStore */
        $dataStore = WC_Data_Store::load('shipping-zone');
        $shippingZones = $dataStore->get_zones();

        // add only zones within accepted regions (e.g. US, CA)
        foreach ($shippingZones as $shippingZone) {
            $shippingZone = new WC_Shipping_Zone($shippingZone);
            $zoneLocations = is_array($shippingZone->get_zone_locations()) ? $shippingZone->get_zone_locations() : [];

            $locationsFiltered = array_values(
                array_filter($zoneLocations,
                    function ($location) use ($availableRegions) {
                        return empty($availableRegions) || in_array(current(explode(':', $location->code)), $availableRegions);
                    }, ARRAY_FILTER_USE_BOTH
                )
            );

            if (! empty($locationsFiltered)) {
                $availableZones[] = $shippingZone;
            }
        }

        return array_unique($availableZones);
    }

    /**
     * Check is chosen on checkout shipping method accepted by current payment gateway.
     *
     * @since 1.3.0
     *
     * @param string[] $enabledForMethods
     * @return bool
     * @throws Exception
     */
    private function isChosenShippingMethodAccepted(array $enabledForMethods = []) : bool
    {
        if (! empty($enabledForMethods) && WC()->session && function_exists('wc_get_chosen_shipping_method_ids')) {
            $chosenShippingMethods = ArrayHelper::wrap(wc_get_chosen_shipping_method_ids());

            foreach ($chosenShippingMethods as $chosenShippingMethod) {
                if (! is_string($chosenShippingMethod)) {
                    continue;
                }

                // bail if there's at least one chosen shipping method that does not match an eligible method
                if (! in_array($chosenShippingMethod, $enabledForMethods, true)) {
                    return false;
                }
            }
        }

        // enabled for any chosen shipping method
        return true;
    }

    /**
     * Determines whether the cart / order needs shipping.
     *
     * @since 1.3.0
     *
     * @return bool
     * @throws Exception
     */
    public function orderNeedsShipping() : bool
    {
        if (WC()->cart && WC()->cart->needs_shipping()) {
            return apply_filters('woocommerce_cart_needs_shipping', true);
        }

        if (0 < get_query_var('order-pay') && is_page(wc_get_page_id('checkout'))) {
            $orderId = absint(get_query_var('order-pay'));

            if ($order = wc_get_order($orderId)) {
                return apply_filters('woocommerce_cart_needs_shipping', ! $this->orderIsVirtual($order));
            }
        }

        return apply_filters('woocommerce_cart_needs_shipping', false);
    }

    /**
     * Determines whether a WooCommerce order is virtual.
     *
     * @since 1.3.0
     *
     * @param WC_Order $order
     * @return bool
     */
    protected function orderIsVirtual(WC_Order $order) : bool
    {
        $isVirtual = true;

        /** @var WC_Order_Item_Product $item */
        foreach ($order->get_items() as $item) {
            $product = $item->get_product();

            // once we've found one non-virtual product we know we're done, break out of the loop
            if ($product && ! $product->is_virtual()) {
                $isVirtual = false;
                break;
            }
        }

        return $isVirtual;
    }

    /**
     * Get the default payment method title, which is configurable within the admin and displayed on checkout.
     *
     * @since 1.3.1
     *
     * @return string payment method title to show on checkout
     */
    protected function get_default_title() : string
    {
        return __('Pay in Person', 'godaddy-payments');
    }

    /**
     * Get the default payment method description, which is configurable within the admin and displayed on checkout.
     *
     * @since 1.3.1
     *
     * @return string payment method description to show on checkout
     */
    protected function get_default_description() : string
    {
        return __('Pay for your order in-person at pickup or delivery.', 'godaddy-payments');
    }

    /**
     * Gets the default instructions.
     *
     * This is used to get default instructions for the "Thank you" order page and email.
     *
     * @since 1.3.1
     *
     * @return string
     */
    private function getDefaultInstructions() : string
    {
        return esc_html__('We accept major credit/debit cards and cash.', 'godaddy-payments');
    }
}
