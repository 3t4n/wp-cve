<?php

namespace Woo_MP\Controllers;

use Woo_MP\Woo_MP;
use Woo_MP\Payment_Gateways;
use Woo_MP\Woo_MP_Order;
use Woo_MP\Payment_Gateway\Payment_Gateway;
use Woo_MP\Payment_Gateway\Payment_Meta_Box_Helper;
use YeEasyAdminNotices\V1\AdminNotice;

defined( 'ABSPATH' ) || die;

/**
 * Controller for the payment meta box.
 */
class Payment_Meta_Box_Controller {

    /**
     * The active payment gateway.
     *
     * @var Payment_Gateway
     */
    private $gateway;

    /**
     * The gateway's payment meta box helper class.
     *
     * @var Payment_Meta_Box_Helper
     */
    private $gateway_helper;

    /**
     * The order.
     *
     * @var object
     */
    private $order;

    /**
     * The currency that payments will be made in.
     *
     * @var string
     */
    private $payment_currency;

    /**
     * The template directories that the gateway is providing.
     *
     * @var string[]
     */
    private $gateway_template_directories = [];

    /**
     * All template directories.
     *
     * @var string[]
     */
    private $template_directories = [];

    /**
     * Payments made for this order.
     *
     * @var array
     */
    private $charges;

    /**
     * Register the meta box.
     *
     * @return void
     */
    public static function add_meta_box() {
        $title = apply_filters( 'woo_mp_payments_meta_box_title', 'Payments' );

        add_meta_box( 'woo-mp', $title, [ new static(), 'display' ] );
    }

    /**
     * Do validation and allow for payment gateways to add their own validation.
     *
     * @return bool true if valid, false otherwise.
     */
    private function validation() {
        $validation = [];

        if ( $this->gateway_helper ) {
            if ( $this->payment_currency !== $this->order->get_currency() ) {
                $validation[] = [
                    'message' => "Transactions will be processed in $this->payment_currency.",
                    'type'    => 'info',
                    'valid'   => true,
                ];
            }

            $validation = array_merge(
                $validation,
                $this->validate_missing_gateway_settings(),
                $this->gateway_helper->validation()
            );
        } else {
            $validation[] = [
                'message' => sprintf(
                    'Please <a href="%s">select a payment gateway</a>. %s',
                    WOO_MP_SETTINGS_URL,
                    WOO_MP_CONFIG_HELP
                ),
                'type'    => 'info',
                'valid'   => false,
            ];
        }

        if ( $validation ) {
            $errors = array_values( array_filter(
                $validation,
                function ( $message ) {
                    return ! $message['valid'];
                }
            ) );

            if ( $errors ) {
                $validation = [ $errors[0] ];
            }

            foreach ( $validation as $message ) {
                $notice = AdminNotice::create();

                if ( $message['valid'] ) {
                    $notice->dismissible();
                }

                $notice
                    ->type( $message['type'] )
                    ->addClass( 'inline' )
                    ->html( wp_kses_post( $message['message'] ) )
                    ->outputNotice();
            }

            if ( $errors ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check for missing payment gateway settings.
     *
     * @return array[] Validation messages in the format specified {@see Payment_Meta_Box_Helper::validation() here}.
     */
    private function validate_missing_gateway_settings() {
        $validation = [];

        $gateway      = $this->gateway;
        $settings_url = WOO_MP_SETTINGS_URL . '&section=' . $gateway::ID;

        foreach ( $this->get_missing_gateway_settings() as $setting ) {
            $validation[] = [
                'message' => "Please <a href='$settings_url'>set your {$setting['title']}</a>. " . WOO_MP_CONFIG_HELP,
                'type'    => 'info',
                'valid'   => false,
            ];
        }

        return $validation;
    }

    /**
     * Get missing payment gateway settings.
     *
     * @return array[] A list of missing settings in the WooCommerce format.
     */
    private function get_missing_gateway_settings() {
        $missing_settings = [];

        foreach ( $this->gateway->get_settings_section()->get_settings() as $setting ) {
            if ( ! empty( $setting['required'] ) && ! get_option( $setting['id'] ) ) {
                $missing_settings[] = $setting;
            }
        }

        return $missing_settings;
    }

    /**
     * Enqueue assets and make some data available on the client side via a global 'wooMP' JavaScript object.
     *
     * @return void
     */
    private function enqueue_assets() {
        $suffix = SCRIPT_DEBUG ? '' : '.min';

        wp_enqueue_style( 'woo-mp-style', WOO_MP_URL . '/assets/css/style.css', [], WOO_MP_VERSION );

        if ( version_compare( $GLOBALS['wp_version'], '5.7-beta1', '<' ) ) {
            wp_enqueue_style( 'woo-mp-style-5-7', WOO_MP_URL . '/assets/css/wp-backward-compatibility/style-5-7.css', [], WOO_MP_VERSION );
        }

        if ( version_compare( $GLOBALS['wp_version'], '5.3-beta1', '<' ) ) {
            wp_enqueue_style( 'woo-mp-style-5-3', WOO_MP_URL . '/assets/css/wp-backward-compatibility/style-5-3.css', [], WOO_MP_VERSION );
        }

        wp_enqueue_script( 'jquery-payment', plugins_url( "assets/js/jquery-payment/jquery.payment$suffix.js", WC_PLUGIN_FILE ), [], WC_VERSION, true );
        wp_enqueue_script( 'woo-mp-script', WOO_MP_URL . '/assets/js/script.js', [], WOO_MP_VERSION, true );

        do_action( 'woo_mp_enqueued_assets' );

        $this->gateway_helper->enqueue_assets();

        $client_data = $this->gateway_helper->client_data() + [
            'AJAXURL'        => admin_url( 'admin-ajax.php' ),
            'nonces'         => [
                'woo_mp_process_transaction' => wp_create_nonce( 'woo_mp_process_transaction_' . $this->order->get_id() ),
            ],
            'gatewayID'      => Payment_Gateways::get_active_id(),
            'orderID'        => $this->order->get_id(),
            'editOrderURL'   => $this->order->get_edit_order_url(),
            'currency'       => $this->payment_currency,
            'currencySymbol' => get_woocommerce_currency_symbol( $this->payment_currency ),
        ];

        wp_localize_script( 'woo-mp-script', 'wooMP', $client_data );
    }

    /**
     * Initialize the template system.
     *
     * @return void
     */
    private function init_templates() {
        $directories = array_merge(
            $this->gateway_template_directories,
            [ WOO_MP_PATH . '/templates' ],
            Woo_MP::is_pro() ? [ WOO_MP_PRO_PATH . '/templates' ] : []
        );

        /**
         * Filters the list of directories used to find templates.
         *
         * Use `array_unshift()` to add your custom template directory.
         * This will ensure that your templates override the default ones.
         *
         * Warning: The templates are not covered by any backward-compatibility guarantee.
         *          Test each release before updating your production site.
         *
         * @param string[] $directories The absolute directory paths, ordered from higher priority to lower priority.
         */
        $this->template_directories = apply_filters( 'woo_mp_payments_meta_box_template_directories', $directories );
    }

    /**
     * Output a template.
     *
     * @param  string $name The name of the template.
     * @return void
     */
    private function template( $name ) {
        foreach ( $this->template_directories as $directory ) {
            $path = "$directory/$name.php";

            if ( is_readable( $path ) ) {
                require $path;

                break;
            }
        }
    }

    /**
     * Run validation, enqueue assets, and output the meta box content.
     *
     * @return void
     */
    public function display() {
        $this->gateway = Payment_Gateways::get_active();

        if ( $this->gateway ) {
            $this->gateway_helper               = $this->gateway->get_payment_meta_box_helper();
            $this->order                        = new Woo_MP_Order( wc_get_order() );
            $this->payment_currency             = $this->gateway_helper->get_currency( $this->order->get_currency() );
            $this->gateway_template_directories = $this->gateway_helper->get_template_directories();
            $this->charges                      = $this->order->get_woo_mp_payments();
        }

        if ( ! $this->validation() ) {
            return;
        }

        $this->enqueue_assets();

        $this->init_templates();

        $this->template( 'payments-meta-box' );
    }

}
