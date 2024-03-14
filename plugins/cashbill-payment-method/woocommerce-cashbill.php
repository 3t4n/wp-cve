<?php
/*
Plugin Name: CashBill.pl - Płatności WooCommerce
Plugin URI: https://cashbill.pl
Description: Dedykowane rozwiązanie integrujące najpopularniejsze metody płatności. Dzięki tej wtyczce możesz w atrakcyjny sposób prezentować siatkę z logotypami banków i innych dostawców bez konieczności odsyłania klienta na stronę operatora.
Version: 3.0.0
Author: CashBill S.A.
Author URI: https://cashbill.pl
*/

if (!defined('ABSPATH')) {
    exit;
}

require_once 'helpers/CashBillHelpers.php';
require_once 'model/CashBillSettings.php';
require_once 'controller/CashBillSettings.php';
require_once 'callback/CashBillPaymentNotification.php';
require_once 'lib/cashbill_sdk/Shop.php';

function cashbill_payment_init()
{
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }
    add_filter('woocommerce_payment_gateways', 'cashbill_payment_load_class');
    add_action('woocommerce_blocks_loaded', 'cashbill_payment_woocommerce_blocks_support');

    require_once 'payment/CashBillPaymentAbstract.php';
    require_once 'payment/CashBillChannelPayment.php';

    require_once 'payment/CashBillBasicPayment.php';
    require_once 'payment/CashBillBlikPayment.php';
    require_once 'payment/CashBillPayPalPayment.php';
    require_once 'payment/CashBillPaysafecardPayment.php';
    require_once 'payment/CashBillInstallmentPayment.php';
    require_once 'payment/CashBillCCPayment.php';
    require_once 'payment/CashBillTwistoPayment.php';
    require_once 'payment/CashBillApplePayPayment.php';
    require_once 'payment/CashBillGooglePayPayment.php';

    function cashbill_payment_load_class($methods)
    {
        $methods[] = 'CashBillBasicPayment';
        $methods[] = 'CashBillBlikPayment';
        $methods[] = 'CashBillPayPalPayment';
        $methods[] = 'CashBillPaysafecardPayment';
        $methods[] = 'CashBillInstallmentPayment';
        $methods[] = 'CashBillCCPayment';
        $methods[] = 'CashBillTwistoPayment';
        $methods[] = 'CashBillApplePayPayment';
        $methods[] = 'CashBillGooglePayPayment';
        return $methods;
    }

    function cashbill_payment_woocommerce_blocks_support()
    {
        require_once 'payment/CashBillPaymentTypeAbstract.php';

        require_once 'payment/CashBillBasicPaymentType.php';
        require_once 'payment/CashBillApplePayPaymentType.php';
        require_once 'payment/CashBillGooglePayPaymentType.php';
        require_once 'payment/CashBillBlikPaymentType.php';
        require_once 'payment/CashBillInstallmentPaymentType.php';
        require_once 'payment/CashBillPayPalPaymentType.php';
        require_once 'payment/CashBillTwistoPaymentType.php';
        require_once 'payment/CashBillCCPaymentType.php';
        require_once 'payment/CashBillPaysafecardPaymentType.php';

        if (class_exists('Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType')) {
            add_action(
                'woocommerce_blocks_payment_method_type_registration',
                function (Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry) {
                    $payment_method_registry->register(new CashBillBasicPaymentType());
                    $payment_method_registry->register(new CashBillApplePayPaymentType());
                    $payment_method_registry->register(new CashBillGooglePayPaymentType());
                    $payment_method_registry->register(new CashBillBlikPaymentType());
                    $payment_method_registry->register(new CashBillInstallmentPaymentType());
                    $payment_method_registry->register(new CashBillPayPalPaymentType());
                    $payment_method_registry->register(new CashBillTwistoPaymentType());
                    $payment_method_registry->register(new CashBillCCPaymentType());
                    $payment_method_registry->register(new CashBillPaysafecardPaymentType());
                }
            );
        }
    }


}

function cashbill_settings_init()
{
    $settings = new CashBillSettingsController();
    $settings->init();
}

function cashbill_payment_links($links)
{
    $plugin_links = array(
        '<a href="' . admin_url('admin.php?page=cashbill-payments-settings') . '">' . __('Ustawienia', 'cashbill_payment') . '</a>',
    );

    return array_merge($plugin_links, $links);
}

function admin_style()
{
    wp_enqueue_style('cashbill_menu_styles', plugins_url('cashbill-payment-method/css/admin.css'));
}

add_action('admin_enqueue_scripts', 'admin_style');
add_action('plugins_loaded', 'cashbill_payment_init');
add_action('plugins_loaded', 'cashbill_settings_init');
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'cashbill_payment_links');
add_action('woocommerce_api_cashbill_payment', array(new CashBillPaymentNotification(), 'callback'));