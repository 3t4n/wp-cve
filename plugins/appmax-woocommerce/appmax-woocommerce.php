<?php
/**
 * Plugin Name: AppMax WooCommerce
 * Description: Gateway de pagamento AppMax para WooCommerce.
 * Version: 2.0.46
 * License: GPLv2 or later
 * Author: AppMax Plataforma de Vendas Ltda
 * Text Domain: appmax-woocommerce
 *
 * @package Appmax_WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'AppMax_WC' ) ) :

    /**
     * Main AppMax_WC Class.
     *
     * @class AppMax_WC
     */
    class AppMax_WC
    {
        const VERSION = '2.0.46';

        /**
         * @var null
         */
        protected static $_instance = null;

        /**
         * @return AppMax_WC|null
         */
        public static function instance()
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        /**
         * AppMax_WC constructor.
         */
        public function __construct()
        {
            if ( class_exists( 'WC_Payment_Gateway' ) ) {

                $this->awc_define_constants();
                $this->awc_includes();

                add_filter( 'woocommerce_payment_gateways', array( $this, 'awc_register_gateway' ) );
                add_filter( 'woocommerce_checkout_fields', array( $this, 'awc_remove_checkout_fields' ) );

                add_action( 'wp_ajax_add-meta',  array( $this, 'awc_send_to_appmax' ), 1);
                add_action( 'wp_ajax_woocommerce_correios_add_tracking_code',  array( $this, 'awc_send_correios_to_appmax' ), 1);

                add_action( 'wp_ajax_update_order_status', array($this, 'awc_update_order_status'));
                add_action( 'wp_ajax_nopriv_update_order_status', array($this, 'awc_update_order_status'));

                add_action( 'woocommerce_order_details_after_order_table', array( $this, 'awc_show_link_billet' ) );
                add_action( 'woocommerce_order_details_after_order_table', array( $this, 'awc_show_pix_qrcode' ) );
            } else {
                add_action( 'admin_notices', array( $this, 'awc_woocommerce_not_installed' ) );
            }
        }

        public function awc_update_order_status(){

            if ( isset($_POST['order_id']) && $_POST['order_id'] > 0 ) {
                $order = wc_get_order($_POST['order_id']);
                $order->update_status( AWC_Order_Status::AWC_COMPLETED );
                die();
            }
        }

        public function awc_send_to_appmax()
        {
            $awc_tracking_code = new AWC_Tracking_Code();
            $awc_tracking_code->awc_send_to_appmax();
        }

        public function awc_send_correios_to_appmax()
        {
            $awc_tracking_code = new AWC_Tracking_Code();
            $awc_tracking_code->awc_send_correios_to_appmax();
        }

        /**
         * Add the gateway to WooCommerce.
         *
         * @param array $methods WooCommerce payment methods.
         * @return array
         */
        public function awc_register_gateway( $methods )
        {
            $methods[] = 'AWC_Gateway_Credit_Card';
            $methods[] = 'AWC_Gateway_Billet';
            $methods[] = 'AWC_Gateway_Pix';
            return $methods;
        }

        /**
         * Remove some fields
         *
         * @param $fields
         * @return mixed
         */
        public function awc_remove_checkout_fields( $fields )
        {
            unset( $fields['billing']['billing_company'] );
            unset( $fields['shipping']['shipping_company'] );
            unset( $fields['order']['order_comments'] );
            return $fields;
        }

        /**
         * Add the billet link when the payment is billet
         *
         * @param $order
         */
        public function awc_show_link_billet( $order )
        {
            if ( $order->get_meta( '_appmax_type_payment' ) == AWC_Payment_Type::AWC_BILLET ) {
                $html = "";
                $html .= "<a href='%s' target='_blank' class='button-view-boleto button-test'>Exibir Boleto</a>";

                if ($order->get_meta('_appmax_digitable_line')) {
                    $html .= "<p><strong>Linha digitável: </strong> %s</p>";
                }

                echo sprintf( $html, $order->get_meta( 'appmax_link_billet' ), $order->get_meta('_appmax_digitable_line') );
            }
        }

        /**
         * Add pix template when the payment is by pix
         *
         * @param $order
         */
        public function awc_show_pix_qrcode( $order )
        {
            if ( $order->get_meta( '_appmax_type_payment' ) == AWC_Payment_Type::AWC_PIX && $order->get_meta('_appmax_woocommerce_transaction_data') ) {
                
                $pix_template = dirname( __FILE__ ) . '/templates/views/checkout/pix/pix-payment.php';
    
                if(file_exists($pix_template)){
                    include $pix_template;
                }
            }
        }

        /**
         * Include woocommerce-not-installed.php
         */
        public function awc_woocommerce_not_installed()
        {
            include dirname( __FILE__ ) . '/templates/views/admin/woocommerce-not-installed.php';
        }

        /**
         * Get templates path.
         *
         * @return string
         */
        public static function awc_get_templates_path()
        {
            return plugin_dir_path( __FILE__ ) . 'templates/';
        }

        /**
         * Define Constants of Gateway
         */
        private function awc_define_constants()
        {
            $this->awc_define( 'AWC_ABSPATH', dirname( __FILE__ ) );
            $this->awc_define( 'AWC_PLUGIN_ROOT_PATH', basename( dirname( __FILE__ ) ) );

            $this->awc_define( 'AWC_GATEWAY_ID_BILLET', 'appmax-billet' );
            $this->awc_define( 'AWC_GATEWAY_ID_CREDIT_CARD', 'appmax-credit-card' );
            $this->awc_define( 'AWC_GATEWAY_ID_PIX', 'appmax-pix' );
            $this->awc_define( 'AWC_APPMAX_WEB_HOOK', 'appmax-webhook' );
            $this->awc_define( 'AWC_URL_API_DOMAIN', 'https://admin.appmax.com.br/api/v3/' );
            $this->awc_define( 'AWC_HOST_DOMAIN', '' );
            $this->awc_define( 'AWC_DUE_DAYS', 3 );
        }

        /**
         * Includes
         */
        private function awc_includes()
        {
            include_once AWC_ABSPATH . '/includes/domain/class-awc-day-week.php';
            include_once AWC_ABSPATH . '/includes/domain/class-awc-errors-api.php';
            include_once AWC_ABSPATH . '/includes/domain/class-awc-events.php';
            include_once AWC_ABSPATH . '/includes/domain/class-awc-order-status.php';
            include_once AWC_ABSPATH . '/includes/domain/class-awc-origin-order.php';
            include_once AWC_ABSPATH . '/includes/domain/class-awc-payment-type.php';
            include_once AWC_ABSPATH . '/includes/domain/class-awc-status-appmax.php';
            include_once AWC_ABSPATH . '/includes/domain/class-awc-suffix-api.php';
            include_once AWC_ABSPATH . '/includes/domain/class-awc-validation-type.php';
            include_once AWC_ABSPATH . '/includes/class-awc-api.php';
            include_once AWC_ABSPATH . '/includes/class-awc-calculate.php';
            include_once AWC_ABSPATH . '/includes/class-awc-due-date-validator.php';
            include_once AWC_ABSPATH . '/includes/class-awc-form-payment.php';
            include_once AWC_ABSPATH . '/includes/class-awc-gateway-billet.php';
            include_once AWC_ABSPATH . '/includes/class-awc-gateway-credit-card.php';
            include_once AWC_ABSPATH . '/includes/class-awc-gateway-pix.php';
            include_once AWC_ABSPATH . '/includes/class-awc-helper.php';
            include_once AWC_ABSPATH . '/includes/class-awc-interest.php';
            include_once AWC_ABSPATH . '/includes/class-awc-post-payment.php';
            include_once AWC_ABSPATH . '/includes/class-awc-process-payment.php';
            include_once AWC_ABSPATH . '/includes/class-awc-search-gateway.php';
            include_once AWC_ABSPATH . '/includes/class-awc-tax.php';
            include_once AWC_ABSPATH . '/includes/class-awc-tracking-code.php';
            include_once AWC_ABSPATH . '/includes/class-awc-validation.php';
            include_once AWC_ABSPATH . '/includes/class-awc-webhook.php';
            include_once AWC_ABSPATH . '/includes/class-awc-webhook-post.php';
        }

        /**
         * Define constants
         *
         * @param $name
         * @param $value
         */
        private function awc_define( $name, $value )
        {
            if ( ! defined( $name ) ) {
                define( $name, $value );
            }
        }
    }

    add_action( 'plugins_loaded', array( 'AppMax_WC', 'instance' ) );

endif;
