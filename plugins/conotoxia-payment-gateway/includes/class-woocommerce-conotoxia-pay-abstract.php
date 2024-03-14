<?php

use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Configuration\Factory\ConfigurationFactory;
use CKPL\Pay\Definition\Payment\Builder\AmountBuilder;
use CKPL\Pay\Definition\Payment\Builder\PaymentBuilder;
use CKPL\Pay\Definition\Payment\PaymentInterface;
use CKPL\Pay\Definition\StoreCustomer\StoreCustomer;
use CKPL\Pay\Exception\ConfigurationException;
use CKPL\Pay\Exception\Definition\AmountException;
use CKPL\Pay\Exception\Definition\PaymentException;
use CKPL\Pay\Exception\Exception;
use CKPL\Pay\Exception\Http\HttpConflictException;
use CKPL\Pay\Exception\Http\HttpNotFoundException;
use CKPL\Pay\Exception\Api\ValidationErrorException;
use CKPL\Pay\Pay;
use WC_Gateway_Conotoxia_Pay_Logger as Logger;

abstract class WC_Payment_Gateway_Conotoxia extends WC_Payment_Gateway
{

    /**
     * @type string
     */
    private const PAYMENTS_HOST = 'https://partner.cinkciarz.pl';

    /**
     * @type string
     */
    private const SANDBOX_PAYMENTS_HOST = 'https://pay-api.ckpl.us';

    /**
     * @type string
     */
    private const OIDC_HOST = 'https://login.cinkciarz.pl';

    /**
     * @type string
     */
    private const SANDBOX_OIDC_HOST = 'https://login.ckpl.io';

    /**
     * @param int $order_id
     * @param float|null $amount
     * @param string $reason
     * @return bool|WP_Error
     */
    public function process_refund($order_id, $amount = null, $reason = '')
    {
        $order = wc_get_order($order_id);
        if (empty($order->get_transaction_id())) {
            return false;
        }

        if (!is_numeric($amount)) {
            Logger::log('Invalid refund amount format: ' . $amount);
            return new WP_Error(CONOTOXIA_PAY, __('Invalid refund amount format.', CONOTOXIA_PAY));
        }


        try {
            $sdk = $this->initialize_conotoxia_pay();
            $refund = $sdk->refunds()->createRefundBuilder()
                ->setReason(empty($reason) ? __('Refund reason', CONOTOXIA_PAY) : $reason)
                ->setValue(number_format(floatval($amount), 2, '.', ''))
                ->setCurrency($order->get_currency())
                ->setExternalRefundId($order->get_order_number())
                ->setPaymentId($order->get_transaction_id())
                ->setIntegrationPlatform($this->get_shop_version())
                ->setAcceptLanguage($this->get_accept_language())
                ->setNotificationUrlParameters(['wcOrderKey' => $order->get_order_key()])
                ->getRefund();
            $created_refund = $sdk->refunds()->makeRefund($refund);
            $order->add_meta_data(
                '_refund',
                ['id' => $created_refund->getId(), 'amount' => number_format($amount, 2, '.', '')]
            );
        } catch (HttpConflictException|HttpNotFoundException $exception) {
            $locale_iso_code = substr(get_bloginfo("language"), 0, 2);
            $exception = $exception->getTranslatedMessage($locale_iso_code) ?? $this->get_refund_creation_problem();
            wc_add_notice($exception, 'error');
            return new WP_Error(CONOTOXIA_PAY, $exception);
        } catch (ValidationErrorException $exception) {
            $languageCode = substr(get_bloginfo("language"), 0, 2);

            Logger::log($exception->getLogMessage());
            return new WP_Error(CONOTOXIA_PAY, $exception->getLocalizedMessage($languageCode));
        } catch (Exception $exception) {
            wc_add_notice(__('Refund creation problem.', CONOTOXIA_PAY), 'error');
            return $this->get_refund_creation_problem();
        }
        return true;
    }

    /**
     * @param WC_Gateway_Conotoxia_Pay|null $primary_gateway
     * @return Pay
     * @throws ConfigurationException
     */
    public function initialize_conotoxia_pay(WC_Gateway_Conotoxia_Pay $primary_gateway = null): Pay
    {
        if ($primary_gateway == null) {
            $primary_gateway = $this->get_primary_gateway();
        }
        return new Pay(ConfigurationFactory::fromArray([
            ConfigurationInterface::HOST => $this->get_payment_host($primary_gateway),
            ConfigurationInterface::OIDC => $this->get_oidc_host($primary_gateway),
            ConfigurationInterface::CLIENT_ID => $primary_gateway->get_option('client_id'),
            ConfigurationInterface::CLIENT_SECRET => $primary_gateway->get_option('client_secret'),
            ConfigurationInterface::POINT_OF_SALE => $primary_gateway->get_option('point_of_sale'),
            ConfigurationInterface::PRIVATE_KEY => $primary_gateway->get_option('private_key'),
            ConfigurationInterface::PUBLIC_KEY => $primary_gateway->get_option('public_key'),
            ConfigurationInterface::STORAGE => new WC_Gateway_Conotoxia_Pay_Storage(),
        ]));
    }

    /**
     * @param WC_Order $order
     * @return PaymentInterface
     * @throws AmountException
     * @throws PaymentException
     */
    protected function create_payment(WC_Order $order): PaymentInterface
    {
        $return_url = $this->get_return_url($order);
        return (new PaymentBuilder())
            ->setExternalPaymentId($order->get_order_number())
            ->setAmount((new AmountBuilder())
                ->setValue(number_format($order->get_total(), 2, '.', ''))
                ->setCurrency(get_woocommerce_currency())
                ->getAmount()
            )
            ->setStoreCustomer((new StoreCustomer())
                ->setFirstName($order->get_billing_first_name())
                ->setLastName($order->get_billing_last_name())
                ->setEmail($order->get_billing_email())
            )
            ->setDescription($this->get_payment_description($order->get_order_number()))
            ->setReturnUrl($return_url)
            ->setErrorUrl($return_url)
            ->setIntegrationPlatform($this->get_shop_version())
            ->setAcceptLanguage($this->get_accept_language())
            ->setNotificationUrlParameters(['wcOrderKey' => $order->get_order_key()])
            ->denyPayLater()
            ->getPayment();
    }

    /**
     * @param string $icon_path
     * @return void
     */
    protected function init_payment_icon(string $icon_path): void
    {
        if ($this->is_payment_icon_enabled()) {
            $this->icon = apply_filters(
                CONOTOXIA_PAY . '_icon',
                plugins_url($icon_path, __DIR__)
            );
        }
    }

    /**
     * @param string $message
     * @param string $level
     * @return void
     */
    protected function show_notice(string $message, string $level = 'warning'): void
    {
        $allowed_html = [
            'a' => [
                'href' => [],
                'target' => [],
                'rel' => [],
            ]
        ];
        echo '<div class="notice notice-' . esc_html($level) . '"><p><strong>' . wp_kses($message, $allowed_html) . '</strong></p></div>';
    }

    /**
     * @return bool
     */
    protected function is_admin_panel(): bool
    {
        return (
            $this->get_requested_parameter('page') === 'wc-settings'
            &&
            $this->get_requested_parameter('section') == $this->id
        );
    }

    /**
     * @return string
     */
    protected function get_shop_version(): string
    {
        global $wp_version;
        return 'WORDPRESS=' . $wp_version . ';WOO=' . WC()->version . ';PLUGIN=' . CONOTOXIA_PAY_VERSION;
    }

    /**
     * @return string|null
     */
    protected function get_accept_language(): ?string
    {
        return isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? sanitize_text_field($_SERVER['HTTP_ACCEPT_LANGUAGE']) : null;
    }

    /**
     * @param string $order_number
     * @return string
     */
    protected function get_payment_description(string $order_number): string
    {
        return get_bloginfo('name') . ' ' . __('order') . ' #' . $order_number;
    }

    /**
     * @return WC_Gateway_Conotoxia_Pay
     */
    protected function get_primary_gateway(): WC_Gateway_Conotoxia_Pay
    {
        $payment_gateways = WC()->payment_gateways()->payment_gateways();
        if (isset($payment_gateways[Identifier::CONOTOXIA_PAY])) {
            return $payment_gateways[Identifier::CONOTOXIA_PAY];
        }
        Logger::log('Unable to access primary gateway - creating new instance instead');
        return new WC_Gateway_Conotoxia_Pay();
    }

    /**
     * @param string $key
     * @param string $default
     * @return string
     */
    private function get_requested_parameter(string $key, string $default = ''): string
    {
        return isset($_GET[$key]) ? sanitize_text_field($_GET[$key]) : $default;
    }

    /**
     * @return bool
     */
    private function is_payment_icon_enabled(): bool
    {
        return $this->get_option('icon') === 'yes';
    }

    /**
     * @param WC_Gateway_Conotoxia_Pay $primary_gateway
     * @return string
     */
    private function get_payment_host(WC_Gateway_Conotoxia_Pay $primary_gateway): string
    {
        if ($primary_gateway->is_sandbox_mode_enabled()) {
            return self::SANDBOX_PAYMENTS_HOST;
        }
        return self::PAYMENTS_HOST;
    }

    /**
     * @param WC_Gateway_Conotoxia_Pay $primary_gateway
     * @return string
     */
    private function get_oidc_host(WC_Gateway_Conotoxia_Pay $primary_gateway): string
    {
        if ($primary_gateway->is_sandbox_mode_enabled()) {
            return self::SANDBOX_OIDC_HOST;
        }
        return self::OIDC_HOST;
    }

    /**
     * @return WP_Error
     */
    private function get_refund_creation_problem(): WP_Error
    {
        return new WP_Error(CONOTOXIA_PAY, __('Refund creation problem.', CONOTOXIA_PAY));
    }

    /**
     * @param bool|WC_Order|WC_Order_Refund $order
     * @param int $order_id
     * @param null|string $payment_id
     * @deprecated Used only for backward compatibility
     */
    protected function save_transaction_id($order, $order_id, $payment_id) {
        if (get_post_meta($order_id, '_transaction_id', true)) {
            $payment_message = __('Conotoxia Pay: Re-payment %s has been created. Waiting for customer confirmation.', CONOTOXIA_PAY);
            update_post_meta($order_id, '_transaction_id', $payment_id);
        } else {
            $payment_message = __('Conotoxia Pay: Payment %s has been created. Waiting for customer confirmation.', CONOTOXIA_PAY);
            add_post_meta($order_id, '_transaction_id', $payment_id, true);
        }
        $order->add_order_note(sprintf($payment_message, $payment_id));
    }
}
