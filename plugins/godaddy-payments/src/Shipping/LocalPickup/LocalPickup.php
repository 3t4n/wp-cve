<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Shipping\LocalPickup;

use Exception;
use WC_Email_Customer_Processing_Order;
use WC_Order;
use WC_Shipping_Rate;

/**
 * Enhancement class for the core Local Pickup shipping method.
 *
 * @since 1.3.0
 */
#[\AllowDynamicProperties]
class LocalPickup
{
    /** @var string[] emails that should show pickup instructions */
    protected $emailsToIncludePickupInstructions = [
        ReadyForPickupEmail::class,
        WC_Email_Customer_Processing_Order::class,
    ];

    /**
     * LocalPickup constructor.
     *
     * @since 1.3.0
     */
    public function __construct()
    {
        $this->addHooks();
    }

    /**
     * Adds the hooks.
     *
     * @since 1.3.0
     */
    protected function addHooks()
    {
        add_filter('woocommerce_shipping_instance_form_fields_local_pickup', [$this, 'addLocalPickupInstructionFields']);
        add_action('woocommerce_after_shipping_rate', [$this, 'maybeAddCheckoutDescription']);
        add_action('woocommerce_email_customer_details', [$this, 'maybeAddPickupInstructionsToEmails'], 30, 4);
        add_action('woocommerce_thankyou', [$this, 'maybeAddPickupInstructionsToThankYouPage'], 1);
    }

    /**
     * Adds local pickup instruction fields to shipping rate instances.
     *
     * @internal
     *
     * @since 1.3.0
     *
     * @param array|null $instanceFields
     * @return array
     */
    public function addLocalPickupInstructionFields(array $instanceFields = null) : array
    {
        $this->addEmails();
        $instanceFields['checkout_description'] = [
            'title'       => __('Checkout description', 'godaddy-payments'),
            'type'        => 'textarea',
            'description' => __('Shipping method description that the customer will see during checkout.', 'godaddy-payments'),
            'default'     => '',
            'desc_tip'    => true,
        ];

        $instanceFields['pickup_instructions'] = [
            'title'       => __('Pickup instructions', 'godaddy-payments'),
            'type'        => 'textarea',
            'description' => __('Message that the customer will see on the order received page as well as in the processing order and ready for pickup emails.', 'godaddy-payments'),
            'default'     => '',
            'desc_tip'    => true,
        ];

        return $instanceFields;
    }

    /**
     * Retrieves shipping method instance settings.
     *
     * @since 1.3.0
     *
     * @param object $method the active shipping method
     * @return string
     */
    protected function getShippingMethodInstanceSettings($method)
    {
        return get_option(sprintf('woocommerce_%s_%d_settings', $method->get_method_id(), $method->get_instance_id()));
    }

    /**
     * Conditionally adds checkout description to checkout and cart.
     *
     * @internal
     *
     * @since 1.3.0
     *
     * @param WC_Shipping_Rate $method Shipping rate object
     * @return void
     */
    public function maybeAddCheckoutDescription(WC_Shipping_Rate $method)
    {
        if ('local_pickup' !== $method->get_method_id()) {
            return;
        }

        $localPickupSettings = $this->getShippingMethodInstanceSettings($method);
        if (is_array($localPickupSettings) && ! empty($checkoutDescription = $localPickupSettings['checkout_description'])) {
            echo '<p class="gdp-local-pickup-desc">'.wp_kses_post($checkoutDescription).'</p>';
        }
    }

    /**
     * Retrieves pickup instructions from shipping method instance on WC_Order object.
     *
     * @since 1.3.0
     *
     * @param WC_Order $order the WooCommerce Order object
     * @return string
     */
    public function getPickupInstructionsFromOrder(WC_Order $order) : string
    {
        $shippingMethods = $order->get_shipping_methods();

        if (! empty($shippingMethods)) {
            // @TODO: What happens here if the shipping method child is empty or not an object?  Not sure I trust Woo to always deliver {sshadid: 2021-12-04}
            $primaryShippingMethod = array_pop($shippingMethods);
            $shippingSettings = $this->getShippingMethodInstanceSettings($primaryShippingMethod);

            return  $shippingSettings['pickup_instructions'] ?? '';
        }

        return '';
    }

    /**
     * Conditionally adds pickup instructions to order received page, processing order email, and ready for pickup email.
     *
     * @internal
     *
     * @since 1.3.0
     *
     * @param WC_Order $order the WooCommerce Order object
     * @param bool $sentToAdmin
     * @param bool $plainText
     * @param object $email
     * @return void
     */
    public function maybeAddPickupInstructionsToEmails($order, $sentToAdmin, $plainText, $email)
    {
        $emailsToIncludePickupInstructions = array_flip($this->emailsToIncludePickupInstructions);

        if (! isset($emailsToIncludePickupInstructions[get_class($email)])) {
            return;
        }

        $pickupInstructions = $this->getPickupInstructionsFromOrder($order);

        if (! empty($pickupInstructions)) {
            $this->renderPickupInstructions($pickupInstructions, $plainText);
        }
    }

    /**
     * Renders the pickup instructions to order received page, processing order email, and ready for pickup email.
     *
     * @since 1.3.0
     *
     * @param string $pickupInstructions The pickup instructions text
     * @param bool $plainText Should the information be rendered in plain text
     * @return void
     */
    protected function renderPickupInstructions(string $pickupInstructions, bool $plainText = false)
    {
        if ($plainText) {
            echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
            _e('Pickup Instructions', 'godaddy-payments');
            echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";
            echo "\n\n----------------------------------------\n\n";
            echo esc_html(wp_strip_all_tags(wptexturize($pickupInstructions)));
            echo "\n\n----------------------------------------\n\n";

            return;
        }

        echo '<h2>'.__('Pickup Instructions', 'godaddy-payments').'</h2>';
        echo '<p>'.wp_kses_post($pickupInstructions).'</p>';
    }

    /**
     * Adds pickup instructions to thank you page.
     *
     * @internal
     *
     * @since 1.3.0
     *
     * @param int $orderId order ID for WC_Order
     * @throws Exception
     */
    public function maybeAddPickupInstructionsToThankYouPage($orderId)
    {
        $wcOrder = wc_get_order($orderId);

        if (! is_a($wcOrder, 'WC_Order')) {
            return;
        }

        $pickupInstructions = $this->getPickupInstructionsFromOrder($wcOrder);

        if (! empty($pickupInstructions)) {
            // @TODO: Really should just use the render above, but didn't want to mess with the class injection at the moment -- update later {JO: 2021-09-09}
            echo '<h2 class="woocommerce-column__title">'.__('Pickup Instructions', 'godaddy-payments').'</h2>';
            echo '<p>'.wp_kses_post($pickupInstructions).'</p>';
        }
    }

    /**
     * Add emails for local pickup.
     *
     * @since 1.3.0
     *
     * @return void
     */
    protected function addEmails()
    {
        new Emails();
    }
}
