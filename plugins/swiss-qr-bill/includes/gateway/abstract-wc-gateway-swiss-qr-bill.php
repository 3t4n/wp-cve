<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * The Payment gateway class
 *
 * @since      1.0.0
 *
 * @package    WC_Swiss_Qr_Bill
 * @subpackage WC_Swiss_Qr_Bill/includes/gateway
 */
class WC_Gateway_Swiss_Qr_Base extends WC_Payment_Gateway {

    public $title;
    public $descriptions;
    public $instructions;
    public $validation_success_msg;


    protected $invoiceGenerate;

    /**
     * Constructor for the gateway.
     */
    public function __construct() {
        // Setup general properties.
        $this->setup_properties();

        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();

        // Get settings.
        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        $this->instructions = $this->get_option('instructions');

        // Initialize invoice generate class
        $this->invoiceGenerate = new WC_Swiss_Qr_Bill_Generate($this);

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

        add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
        add_action('woocommerce_thankyou_' . $this->id, array($this->invoiceGenerate, 'clean_pdf_invoice'), 20, 1);
        add_filter('woocommerce_payment_complete_order_status', array($this, 'change_payment_complete_order_status'), 10, 3);

        // Customer Emails.
        add_action('woocommerce_email_before_order_table', array($this, 'email_instructions'), 10, 4);

        // My account orders action
        add_filter('woocommerce_my_account_my_orders_actions', array($this, 'view_invoice'), 50, 2);

        // Order edit page
        add_action('woocommerce_before_resend_order_emails', array($this, 'generate_invoice'), 99, 2);
        add_action('woocommerce_after_resend_order_email', array($this, 'clean_pdf_invoice_after_order_action'), 99, 2);

    }

    /**
     * Setup general properties for the gateway.
     */
    protected function setup_properties() {
    }

    /**
     * Initialise Gateway Settings Form Fields.
     */
    public function init_form_fields() {
    }

    /**
     * Check if this gateway is enabled and available in the user's country.
     *
     * @return bool
     */
    public static function check_currency_validation() {
        return in_array(get_woocommerce_currency(), apply_filters('wc_swiss_qr_bill_supported_currencies', array('CHF', 'EUR')), true);
    }

    /**
     * Admin Panel Options.
     * - Options for bits like 'title' and availability on a country-by-country basis.
     *
     * @since 1.0.0
     */
    public function admin_options() {
//        if ( !self::check_currency_validation() ) {
//            $this->invalid_currency_notification();
//        }

        // Check the required validation
        if ($this->get_option('enabled') == 'yes') {
            $required_validations = $this->invoiceGenerate->gateway_field_empty_validation($this->id);
            if (count($required_validations) > 0) { ?>
                <div class="inline error">
                    <?php foreach ($required_validations as $error_message) { ?>
                        <p>
                            <?php echo $error_message; ?>
                        </p>
                        <?php
                    } ?>
                </div>
                <?php
            } else {
                $response = $this->invoiceGenerate->is_data_valid_for_qr_bill($this->id, true);
                if ($response === true) { ?>
                    <div class="inline updated">
                        <p>
                            <strong><?php echo $this->validation_success_msg; ?></strong>
                        </p>
                    </div>
                    <?php
                } else if ($response !== false) { ?>
                    <div class="inline error">
                        <p>
                            <?php echo $response; ?>
                        </p>
                    </div>
                    <?php
                }
            }
        }


        parent::admin_options();
    }

    /**
     * WooCommerce fallback notice.
     */
    public function invalid_currency_notification() {
        echo '<div class="error notice is-dismissible"><p>' . sprintf(esc_html__('Swiss QR Bill for WooCommerce works only with CHF and EUR.', 'swiss-qr-bill')) . '</p></div>';
    }

    /**
     * Check the availability of payment gateway in checkout page
     * @return bool
     */
    public function is_available() {
        return self::check_currency_validation() &&
            $this->is_valid_billing_country() &&
            empty($this->invoiceGenerate->gateway_field_empty_validation($this->id)) &&
            $this->get_option('enabled') === 'yes' &&
            $this->check_logged_in_restriction() &&
            $this->check_order_restriction() &&
            $this->check_product_cats_restriction() &&
            $this->invoiceGenerate->is_data_valid_for_qr_bill($this->id);
    }

    /**
     * Process the payment and return the result.
     *
     * @param int $order_id Order ID.
     * @return array
     */
    public function process_payment($order_id) {
        $order = wc_get_order($order_id);

        if ($order->get_total() > 0) {
            // Mark as processing or on-hold (payment won't be taken until delivery).
            do_action('invoice_generate', $order_id);
            $order->update_status(
                apply_filters(
                    'woocommerce_swiss_qr_process_payment_order_status', 'on-hold', $order),
                __('Expecting payment with Swiss QR bill.', 'swiss-qr-bill')
            );

        } else {
            $order->payment_complete();
        }

        // Remove cart.
        WC()->cart->empty_cart();

        // Return thank you redirect.
        return array(
            'result' => 'success',
            'redirect' => $this->get_return_url($order),
        );
    }

    /**
     * Output for the order received page.
     */
    public function thankyou_page() {
        if ($this->instructions) {
            echo wp_kses_post(wpautop(wptexturize($this->instructions)));
        }
    }

    /**
     * Change payment complete order status to completed for Swiss Qr Bill orders.
     *
     * @param string $status Current order status.
     * @param int $order_id Order ID.
     * @param WC_Order|false $order Order object.
     * @return string
     * @since  3.1.0
     */
    public function change_payment_complete_order_status($status, $order_id = 0, $order = false) {
        if ($order && 'swiss_qr' === $order->get_payment_method()) {
            $status = 'completed';
        }
        return apply_filters('woocommerce_swiss_qr_process_payment_order_status', $status);
    }

    /**
     * Add content to the WC emails.
     *
     * @param $order
     * @param $sent_to_admin
     * @param $plain_text
     * @param $email
     */
    public function email_instructions($order, $sent_to_admin, $plain_text, $email) {
        if ($this->instructions && !$sent_to_admin && $this->id === $order->get_payment_method() && $order->has_status('on-hold') && is_a($email, 'WC_Email_Customer_On_Hold_Order')) {
            echo wp_kses_post(wpautop(wptexturize($this->instructions)) . PHP_EOL);
        }
    }

    /**
     * Function to check for the logged in restriction
     * @return bool
     */
    protected function check_logged_in_restriction() {
        $login_restriction = $this->get_option('login_restriction');
        return $login_restriction !== 'yes' ||
            ($login_restriction === 'yes' && is_user_logged_in());
    }

    /**
     * Function to check the at least one completed order restriction
     * @return bool
     */
    protected function check_order_restriction() {
        $order_restriction = $this->get_option('order_restriction');
        if ($order_restriction !== 'yes') {
            return true;
        }
        // For not logged in customer, use the session data

        if (!is_user_logged_in()) {
            return false;
        }

        $args = array(
            'status' => 'completed',
            'customer_id' => get_current_user_id(),
        );

        return count(wc_get_orders($args)) > 0;

    }

    /**
     * Function to check the product category restriction
     * @return bool
     */
    protected function check_product_cats_restriction() {
        foreach (WC()->cart->get_cart_contents() as $cart_content):
            $cart_product = wc_get_product($cart_content['product_id']);

            foreach ($cart_product->get_category_ids() as $category_id):
                $is_gateway_enabled = get_term_meta($category_id, 'wsqb_activate_gateway', true) !== 'no';
                // Return false as soon as we find disabled category
                if (!$is_gateway_enabled) {
                    return false;
                    break;
                }
            endforeach;
        endforeach;

        return true;

    }


    /**
     * Check if the customer if checking out from valid country
     * @return bool
     */
    public function is_valid_billing_country() {
        return WC()->session && in_array(strtoupper(WC()->session->get('customer')['country']), array('CH', 'LI'));
    }

    /**
     * @param $actions
     * @param $order
     * @return mixed
     */
    public function view_invoice($actions, $order) {
        if ($order->get_payment_method() != $this->id) {
            return $actions;
        }
        $actions['view_invoice'] = array(
            'url' => wp_nonce_url(
                add_query_arg(
                    array(
                        'action' => 'view_invoice',
                        'order_id' => $order->get_order_number(),
                    ), wc_get_page_permalink('myaccount')
                ), 'generate_invoice'
            ),
            'name' => __('View Invoice', 'swiss-qr-bill'),
        );

        return $actions;
    }

    /**
     * @param $order
     * @param $order_type
     */
    public function generate_invoice($order, $order_type) {
        if ('customer_invoice' !== $order_type) {
            return false;
        }
        $payment_method = $order->get_payment_method();
        if (!in_array($payment_method, array('wc_swiss_qr_bill', 'wc_swiss_qr_bill_classic'))) {
            return false;
        }

        //Init the invoice generate class
        new WC_Swiss_Qr_Bill_Generate(WC()->payment_gateways()->payment_gateways()[$payment_method]);

        // Get payment gateway setting option for this particular order
        $gateway_options = get_post_meta($order->get_id(), '_wsqb_gateway_data', true);
        do_action('invoice_generate', sanitize_text_field($order->get_id()), unserialize($gateway_options));
    }

    public function clean_pdf_invoice_after_order_action($order, $type) {
        if (!is_a($order, 'WC_Order')) {
            return;
        }

        if ('customer_invoice' !== $type) {
            return;
        }

        $this->invoiceGenerate->clean_pdf_invoice($order->get_id());
    }

}
