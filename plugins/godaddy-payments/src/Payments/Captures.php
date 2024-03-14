<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Payments;

use Exception;
use GoDaddy\WooCommerce\Poynt\Gateways\GDPCapture;
use GoDaddy\WooCommerce\Poynt\Helpers\ArrayHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\StringHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\WCHelper;
use GoDaddy\WooCommerce\Poynt\Plugin;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;
use WC_Order;

/**
 * Captures handler.
 *
 * @since 1.3.0
 */
class Captures
{
    /** @var string action capture order. */
    const ACTION_CAPTURE_ORDER = 'godaddy_payments_capture_order';

    /**
     * Captures constructor.
     *
     * @since 1.3.0
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->registerHooks();
    }

    /**
     * Add hooks.
     *
     * @since 1.3.0
     *
     * @return void
     */
    protected function registerHooks()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
        add_action('wp_ajax_'.static::ACTION_CAPTURE_ORDER, [$this, 'ajaxCaptureOrder']);
        add_action('woocommerce_order_item_add_action_buttons', [$this, 'maybeAddCaptureButton']);
    }

    /**
     * Enqueues the scripts.
     *
     * @internal callback
     * @see Captures::registerHooks()
     *
     * @param string $hookSuffix
     * @throws Exception
     */
    public function enqueueScripts($hookSuffix)
    {
        if (! Framework\SV_WC_Order_Compatibility::is_order_edit_screen()) {
            return;
        }

        wp_enqueue_script('godaddy-payments-captures', poynt_for_woocommerce()->get_plugin_url().'/assets/js/payments/captures.js', ['jquery'], poynt_for_woocommerce()->get_version());
    }

    /**
     * May add a capture button to order.
     *
     * @since 1.3.0
     *
     * @internal callback
     * @see Captures::registerHooks()
     *
     * @param null|WC_Order $wcOrder
     * @return void
     * @throws Exception
     */
    public function maybeAddCaptureButton($wcOrder)
    {
        if (! Framework\SV_WC_Order_Compatibility::is_order($wcOrder) && 'poynt' !== $wcOrder->get_meta('_wc_poynt_provider_name')) {
            return;
        }

        // bail if payment gateway is no third party
        if (in_array($wcOrder->get_payment_method(), WCHelper::corePaymentMethods())) {
            return;
        }

        $this->renderCaptureButton($wcOrder);
    }

    /**
     * Renders capture payment button for order.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @return void
     * @throws Exception
     */
    protected function renderCaptureButton(WC_Order $wcOrder)
    {
        if (! WCHelper::hasOpenAuthorization($wcOrder)) {
            return;
        }

        $tooltip = '';
        $buttonClasses = ['button', 'godaddy-payments-capture'];

        if (WCHelper::hasCapturedOrder($wcOrder)) {
            $buttonClasses = ArrayHelper::combine($buttonClasses, ['tips', 'disabled']);
            $tooltip = __('This charge has been fully captured', 'godaddy-payments');
        } else {
            $buttonClasses[] = 'button-primary';
        } ?>
        <button
            type="button"
            class="<?php echo esc_attr(implode(' ', $buttonClasses)); ?> <?php echo $tooltip ? 'data-tip="'.esc_attr($tooltip).'"' : ''; ?>"
        >
            <?php esc_html_e('Capture Charge', 'godaddy-payments'); ?>
        </button>
        <?php

        wc_enqueue_js(sprintf('window.gd_payments_captures_handler = new GDPaymentsCaptureHandler(%s)', ArrayHelper::jsonEncode([
            'action'  => static::ACTION_CAPTURE_ORDER,
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce(static::ACTION_CAPTURE_ORDER),
            'orderId' => $wcOrder->get_ID(),
            'i18n'    => [
                'ays'          => __('Are you sure you wish to process this capture? The action cannot be undone.', 'godaddy-payments'),
                'errorMessage' => __('Something went wrong, and the capture could not be completed. Please try again.', 'godaddy-payments'),
            ],
        ])));
    }

    /**
     * Handles capture order ajax requests.
     *
     * @since 1.3.0
     *
     * @internal
     *
     * @return void
     */
    public function ajaxCaptureOrder()
    {
        try {
            $nonce = StringHelper::sanitize((string) ArrayHelper::get($_POST, 'nonce'));

            if (! wp_verify_nonce($nonce, static::ACTION_CAPTURE_ORDER)) {
                throw new Exception('Invalid permission.');
            }

            $wcOrder = wc_get_order((int) ArrayHelper::get($_POST, 'orderId'));

            if (! $wcOrder instanceof WC_Order) {
                throw new Exception('Order not found.');
            }

            $result = $this->captureOrder($wcOrder);

            if ($result['success']) {
                wp_send_json_success($result);
            } else {
                wp_send_json_error($result);
            }
        } catch (Exception $exception) {
            wp_send_json_error([
                'message' => 'Order could not be captured. '.$exception->getMessage(),
            ]);
        }
    }

    /**
     * Captures a WooCommerce order.
     *
     * @since 1.3.0
     *
     * @param WC_Order $wcOrder
     * @return array
     * @throws Exception
     */
    protected function captureOrder(WC_Order $wcOrder)
    {
        if (! WCHelper::hasOpenAuthorization($wcOrder)) {
            throw new Exception(__('Order not authorized for capture', 'godaddy-payments'));
        }

        if (WCHelper::hasCapturedOrder($wcOrder)) {
            throw new Exception(__('Order  already captured', 'godaddy-payments'));
        }

        $capture_handler = new GDPCapture(poynt_for_woocommerce()->get_gateway(Plugin::CREDIT_CARD_GATEWAY_ID));

        if (! $capture_handler->order_can_be_captured($wcOrder)) {
            wp_send_json_error(['message' => __('Transaction cannot be captured', 'godaddy-payments')]);
        }

        return $capture_handler->perform_capture($wcOrder);
    }
}
