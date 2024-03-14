<?php

use CKPL\Pay\Definition\Confirm\Builder\ConfirmPaymentBuilder;
use CKPL\Pay\Definition\Confirm\ConfirmPaymentInterface;
use CKPL\Pay\Exception\Definition\ConfirmPaymentException;
use CKPL\Pay\Exception\Exception;
use WC_Gateway_Conotoxia_Pay_Logger as Logger;

class WC_Gateway_Conotoxia_Pay_Blik extends WC_Payment_Gateway_Conotoxia
{
    public function __construct()
    {
        $this->id = Identifier::CONOTOXIA_PAY_BLIK;
        $this->title = __('Pay with BLIK', CONOTOXIA_PAY);
        $this->has_fields = true;
        $this->method_title = __('BLIK Level 0 at Conotoxia Pay', CONOTOXIA_PAY);
        $this->method_description = __(
            'Allow customers to pay with BLIK without leaving the online shop in the Conotoxia Pay payment gateway.',
            CONOTOXIA_PAY
        );
        $this->supports = ['products', 'refunds'];
        $this->init_payment_icon('images/blik.svg');
        if ($this->is_admin_panel()) {
            $this->init_form_fields();
        }
        $this->init_settings();

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
        add_action('woocommerce_after_checkout_validation', [$this, 'validate_blik_code'], 10, 2);
        add_action('woocommerce_after_checkout_validation', [$this, 'validate_user_data'], 10, 2);
        add_action('before_woocommerce_pay', [$this, 'display_payment_status']);

        add_filter('woocommerce_available_payment_gateways', [$this, 'resolve_gateway_availability']);
    }

    /**
     * @return void
     */
    public function init_form_fields(): void
    {
        $this->form_fields = $this->get_blik_form_fields();
    }

    /**
     * @param mixed $payment_gateways
     * @return mixed
     */
    public function resolve_gateway_availability($payment_gateways)
    {
        if (get_woocommerce_currency() !== 'PLN') {
            unset($payment_gateways[$this->id]);
        }
        return $payment_gateways;
    }

    /**
     * @return bool
     */
    public function process_admin_options(): bool
    {
        $this->init_settings();
        $post_data = $this->get_post_data();

        foreach ($this->get_form_fields() as $key => $field) {
            if (array_key_exists($key, $this->get_blik_form_fields())) {
                try {
                    $this->settings[$key] = $this->get_field_value($key, $field, $post_data);
                } catch (\Exception $exception) {
                    $this->add_error($exception->getMessage());
                }
            }
        }

        $option_key = $this->get_option_key();
        do_action('woocommerce_update_option', ['id' => $option_key]);
        $processed = update_option(
            $option_key,
            apply_filters('woocommerce_settings_api_sanitized_fields_' . $this->id, $this->settings),
            'yes'
        );

        if ($processed && !$this->get_primary_gateway()->is_configuration_completed()) {
            $this->show_notice(
                __(
                    'Enter the required data from the Conotoxia Pay payment gateway to complete the configuration.',
                    CONOTOXIA_PAY
                )
            );
        }
        return $processed;
    }

    /**
     * @return void
     */
    public function payment_fields(): void
    {
        parent::payment_fields();
        echo WC_Gateway_Conotoxia_Pay_Blik_Form_Template::get();
    }

    /**
     * @param mixed $data
     * @param mixed $errors
     * @return void
     */
    public function validate_blik_code($data, $errors): void
    {
        if (is_array($data) && array_key_exists('payment_method', $data) && $data['payment_method'] === $this->id) {
            $invalid_blik_code_message = __('The code should have 6 digits.', CONOTOXIA_PAY);
            if (!isset($_POST['cx-blik-code'])) {
                $errors->add('validation', $invalid_blik_code_message);
                return;
            }
            $blik_code = $this->resolve_blik_code(sanitize_text_field($_POST['cx-blik-code']));
            if (strlen($blik_code) !== 6) {
                $errors->add('validation', $invalid_blik_code_message);
            }
        }
    }

    /**
     * @param mixed $data
     * @param mixed $errors
     * @return void
     */
    public function validate_user_data($data, $errors): void
    {
        if (is_array($data) && array_key_exists('payment_method', $data) && $data['payment_method'] === $this->id) {
            if (empty($_POST['billing_first_name'])) {
                Logger::log('Billing first name is a required field.');
                $errors->add('validation', __('Billing first name is a required field.', CONOTOXIA_PAY));
                return;
            }

            if (empty($_POST['billing_last_name'])) {
                Logger::log('Billing last name is a required field.');
                $errors->add('validation', __('Billing last name is a required field.', CONOTOXIA_PAY));
                return;
            }

            if (empty($_POST['billing_email'])) {
                Logger::log('Billing email is a required field.');
                $errors->add('validation', __('Billing email is a required field.', CONOTOXIA_PAY));
            }
        }
    }

    /**
     * @param int $order_id
     * @return array
     */
    public function process_payment($order_id): array
    {
        $order = wc_get_order($order_id);
        if (!$order) {
            Logger::log('Could not find order when creating BLIK payment.');
            return $this->get_failed_payment_processing();
        }
        if (!$order->needs_payment()) {
            Logger::log(
                'Order with id \'%s\' has invalid state (status \'%s\' and total \'%s\') when creating BLIK payment.',
                $order->get_order_number(),
                $order->get_status(),
                $order->get_total()
            );
            return $this->get_failed_payment_processing();
        }
        try {
            $sdk = $this->initialize_conotoxia_pay();
            $payment = $this->create_payment($order);
            $created_payment_response = $sdk->payments()->makePayment($payment);
        } catch (Exception $exception) {
            Logger::log('Payment creation problem: %s', $exception->getMessage());
            return $this->get_failed_payment_processing();
        }

        $payment_id = $created_payment_response->getPaymentId();

        try{
            if (class_exists(\Automattic\WooCommerce\Utilities\OrderUtil::class)
                && \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled()) {
                $payment_message = $order->get_transaction_id() ?
                    __('Conotoxia Pay: Re-payment %s has been created. Waiting for customer confirmation.', CONOTOXIA_PAY)
                    : __('Conotoxia Pay: Payment %s has been created. Waiting for customer confirmation.', CONOTOXIA_PAY);
                $order->set_transaction_id($payment_id);
                $order->add_order_note(sprintf($payment_message, $payment_id));
                $order->save();
            }
            else {
                $this->save_transaction_id($order, $order_id, $payment_id);
            }
        } catch (WC_Data_Exception $e){
            Logger::log('Problem with saving transaction id: %s', $e->getMessage());
        }

        try {
            $confirm_payment = $this->create_confirm_payment(
                $order,
                $this->resolve_blik_code(sanitize_text_field($_POST['cx-blik-code'])),
                $created_payment_response->getToken(),
                sanitize_text_field($_POST['cx-user-screen-resolution']),
                sanitize_text_field($_POST['cx-user-agent'])
            );
            $confirm_payment_response = $sdk->payments()->confirmPayment($confirm_payment);
        } catch (Exception $exception) {
            Logger::log(
                'Problem with confirmation for payment with id %s: %s',
                $created_payment_response->getPaymentId(),
                $exception->getMessage()
            );
            return $this->get_failed_payment_processing();
        }
        if (!in_array(
            $confirm_payment_response->getPaymentStatus(),
            ['WAITING_FOR_NOTIFICATION', 'SUCCESS', 'INITIATED', 'AUTHORIZATION_REQUESTED']
        )) {
            Logger::log(
                'Invalid initial status for payment with id %s: %s',
                $created_payment_response->getPaymentId(),
                $confirm_payment_response->getPaymentStatus()
            );
            return $this->get_failed_payment_processing();
        }
        WC()->cart->empty_cart();
        return [
            'result' => 'success',
            'redirect' => $order->get_checkout_payment_url($order),
        ];
    }

    /**
     * @return void
     */
    public function display_payment_status(): void
    {
        $repayment = sanitize_text_field($_GET['pay_for_order']);
        if (filter_var($repayment, FILTER_VALIDATE_BOOLEAN)) {
            return;
        }
        global $wp;
        $order_id = sanitize_text_field($wp->query_vars['order-pay']);
        $order = wc_get_order($order_id);
        if (!$order || $order->get_payment_method() !== $this->id) {
            return;
        }
        $order_key = sanitize_text_field($_GET['key']);
        if ($order->get_order_key() !== $order_key) {
            return;
        }
        wp_enqueue_style('blik_status_style', plugins_url('styles/blik_status.css', __DIR__));
        $blik_status_script = 'blik_status_script';
        wp_enqueue_script($blik_status_script, plugins_url('scripts/blik_status.js', __DIR__), ['jquery']);
        wp_localize_script(
            $blik_status_script,
            'args',
            [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'orderId' => $order_id,
                'orderKey' => $order_key,
            ]
        );
        echo WC_Gateway_Conotoxia_Pay_Blik_Status_Template::get(
            $order->get_transaction_id(),
            $order->get_billing_email()
        );
    }

    /**
     * @param WC_Order $order
     * @param string $blik_code
     * @param string $payment_token
     * @param string $user_screen_resolution
     * @param string $user_agent
     * @return ConfirmPaymentInterface
     * @throws ConfirmPaymentException
     */
    private function create_confirm_payment(
        WC_Order $order,
        string   $blik_code,
        string   $payment_token,
        string   $user_screen_resolution,
        string   $user_agent
    ): ConfirmPaymentInterface
    {
        return (new ConfirmPaymentBuilder())
            ->setBlikCode($blik_code)
            ->setToken($payment_token)
            ->setEmail($order->get_billing_email())
            ->setType('BLIK')
            ->setFirstName($order->get_billing_first_name())
            ->setLastName($order->get_billing_last_name())
            ->setAcceptLanguage($this->get_accept_language())
            ->setUserScreenResolution($user_screen_resolution)
            ->setUserAgent($user_agent)
            ->setUserIpAddress(WC_Geolocation::get_ip_address())
            ->setUserPort($this->get_remote_port())
            ->setFingerprint($this->get_fingerprint())
            ->getConfirmPayment();
    }

    /**
     * @return array
     */
    private function get_failed_payment_processing(): array
    {
        wc_add_notice(
            __('There was a problem with creating payment. Please contact the store support.', CONOTOXIA_PAY),
            'error'
        );
        return [];
    }

    /**
     * @return array
     */
    private function get_blik_form_fields(): array
    {
        return [
            'enabled' => [
                'title' => __('Enable/Disable', CONOTOXIA_PAY),
                'label' => __('Enable BLIK Level 0 payment method', CONOTOXIA_PAY),
                'type' => 'checkbox',
                'default' => 'no',
            ],
            'icon' => [
                'title' => __('BLIK icon', CONOTOXIA_PAY),
                'description' => __('Show the BLIK icon on the payment method selection screen.', CONOTOXIA_PAY),
                'type' => 'checkbox',
                'default' => 'no',
            ],
            'asDefault' => [
                'title' => __('Default payment method', CONOTOXIA_PAY),
                'description' => __('Sets BLIK Level 0 as the default payment method.', CONOTOXIA_PAY),
                'type' => 'checkbox',
                'default' => 'no',
            ],
        ];
    }

    /**
     * @return string|null
     */
    private function get_remote_port(): ?string
    {
        if (!empty($_SERVER['REMOTE_PORT'])) {
            return sanitize_text_field($_SERVER['REMOTE_PORT']);
        }
        return null;
    }

    /**
     * @return string
     */
    private function get_fingerprint(): string
    {
        $fingerprint = isset($GLOBALS['_COOKIE']['PHPSESSID'])
            ? md5(sanitize_text_field($GLOBALS['_COOKIE']['PHPSESSID']))
            : '-';
        $domain = parse_url(get_site_url(), PHP_URL_HOST);
        return '{"' . $domain . '":"' . $fingerprint . '"}';
    }

    /**
     * @param string $value
     * @return string
     */
    private function resolve_blik_code(string $value): string
    {
        if (empty($value)) {
            return "";
        }
        $value_array = str_split($value);
        $value_array = array_filter($value_array, function ($element) {
            return is_numeric($element);
        });
        return implode($value_array);
    }
}
