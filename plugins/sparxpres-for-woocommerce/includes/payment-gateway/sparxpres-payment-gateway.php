<?php
defined('ABSPATH') || exit;

/**
 * This action hook registers our PHP class as a WooCommerce payment gateway
 */
add_filter('woocommerce_payment_gateways', 'sparxpres_add_gateway_class');
function sparxpres_add_gateway_class($gateways)
{
    $gateways[] = 'WC_Sparxpres_Gateway';
    return $gateways;
}

/**
 * The class itself, note that it is inside plugins_loaded action hook
 */
add_action('plugins_loaded', 'sparxpres_init_gateway_class');
function sparxpres_init_gateway_class()
{

    class WC_Sparxpres_Gateway extends WC_Payment_Gateway
    {

        /**
         * Class constructor
         */
        public function __construct()
        {
            $this->id = 'sparxpres'; // payment gateway plugin ID
            $this->icon = plugins_url('img/sparxpres-payment-logo.svg', __FILE__);
            $this->has_fields = false; // in case a custom credit card form is needed
            $this->method_title = __('Sparxpres part payment', 'sparxpres');
            $this->method_description = __('Receive payment with Sparxpres partial payment.', 'sparxpres');

            $this->init_form_fields();
            $this->init_settings();

            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            $this->enabled = $this->get_option('enabled');
            $this->testmode = false;
            $this->private_key = '';
            $this->publishable_key = '';

            // This action hook saves the settings (on parent)
            add_action('woocommerce_update_options_payment_gateways_' . $this->id,
                array($this, 'process_admin_options'));
            // Action hook for the thank you page
            add_action('woocommerce_thankyou_' . $this->id, array($this, 'process_thank_you_page'));
        }

        /**
         * Check if gateway is available (from currency, amount etc)
         */
        public function is_available()
        {
            if (is_checkout() && parent::is_available() && WC()->cart && get_woocommerce_currency() === 'DKK') {
                $orderTotal = ceil(parent::get_order_total());
                $loanInfo = SparxpresUtils::get_loan_information(SparxpresUtils::get_link_id());
                return isset($loanInfo) && SparxpresUtils::is_finance_enabled($loanInfo, $orderTotal);
            }
            return false;
        }

        /**
         * Plugin options
         */
        public function init_form_fields()
        {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'sparxpres'),
                    'label' => __('Enable Sparxpres part payment', 'sparxpres'),
                    'type' => 'checkbox',
                    'description' => '',
                    'default' => 'yes'
                ),
                'title' => array(
                    'title' => __('Title', 'sparxpres'),
                    'type' => 'text',
                    'description' => __('Payment title displayed during checkout', 'sparxpres'),
                    'default' => 'Sparxpres delbetaling',
                    'desc_tip' => true,
                ),
                'description' => array(
                    'title' => __('Description', 'sparxpres'),
                    'type' => 'textarea',
                    'description' => __('Payment description displayed during checkout', 'sparxpres'),
                    'default' => 'Din ordre afsendes først efter godkendelse hos Sparxpres.' . PHP_EOL .
                        'Følg anvisningerne på næste side.',
                    'desc_tip' => true,
                )
            );
        }

        /**
         * We're processing the payments here
         * @param $orderId
         * @return array
         */
        public function process_payment($orderId)
        {
            $order = wc_get_order($orderId);

            $linkId = SparxpresUtils::get_link_id();
            $amountCents = ceil($order->get_total() * 100);
            $returnUrl = $this->get_return_url($order);
            $cancelUrl = $order->get_cancel_order_url();

            $order->add_order_note('Sparxpres afventer anmodningen.');

            return array(
                'result' => 'success',
                'redirect' => 'https://sparxpres.dk/ansoegning/'
                    . '?linkId=' . $linkId
                    . '&transactionId=' . $orderId
                    . '&amountCents=' . $amountCents
                    . '&returnurl=' . urlencode($returnUrl)
                    . '&cancelurl=' . urlencode($cancelUrl)
            );
        }

        /**
         * Thank you page
         * @param $orderId
         */
        public function process_thank_you_page($orderId)
        {
            if (empty($orderId)) {
                return;
            }

            // Get an instance of the WC_Order object
            $order = wc_get_order($orderId);
            if (!empty($order)) {
                if ($order->get_status() === 'pending') {
                    // Awaiting payment – stock is reduced
                    $order->update_status('on-hold', __('The request has been sent to Sparxpres.', 'sparxpres'));
                } else {
                    $order->add_order_note(__('The request has been sent to Sparxpres.', 'sparxpres'));
                }
            }
        }
    }
}
