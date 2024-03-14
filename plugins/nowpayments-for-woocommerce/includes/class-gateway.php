<?php

class NPWC_Gateway extends WC_Payment_Gateway {

    /**
     * NPWC_Gateway constructor.
     *
     * @since 1.0
     * @version 1.0
     */
    public function __construct() {

        $this->id = 'nowpayments';
        $this->title = $this->get_option( 'title' );
        $this->icon =  apply_filters( 'wcnp_icon', NPWC_PLUGIN_URL . '/assets/images/icon.png' );
        $this->has_fields = false;
        $this->method_title = 'NOWPayments';
        $this->description = $this->get_option( 'description' );
        $this->has_fields = false;
        $this->method_description = 'Allows customer to checkout with 150+ crypto currencies.';
        $this->init_form_fields();
        $this->init_settings();

        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        add_action( 'woocommerce_api_npwc_gateway', array( $this, 'ipn_callback' ) );

    }

    /**
     * Admin form fields
     *
     * @since 1.0
     * @version 1.0
     */
    public function init_form_fields() {

        $this->form_fields = array(
            'enabled'   =>  array(
                'title'     =>  'Enabled/ Disabled',
                'type'      =>  'checkbox',
                'label'     =>  'Enable NOWPayments',
                'default'   =>  'no'
            ),
            'sandbox'    =>  array(
                'title'     =>  'Enable/ Disable',
                'type'      =>  'checkbox',
                'label'     =>  'Enable SandBox',
                'default'   =>  'no'
            ),
            'single_product_icon'    =>  array(
	            'title'     =>  'Enable/ Disable',
	            'type'      =>  'checkbox',
	            'label'     =>  'Show Crypto Icons and Individual Pricing on Product Page (Pro)',
	            'default'   =>  'no'
            ),
            'products_icons'    =>  array(
	            'title'     =>  'Enable/ Disable',
	            'type'      =>  'checkbox',
	            'label'     =>  'Show Crypto Icons and Individual Pricing on Shop Page (Pro)',
	            'default'   =>  'no'
            ),
            'title' =>  array(
                'title'         =>  'Title',
                'type'          =>  'text',
                'default'       =>  'NOWPayments',
                'desc_tip'      =>  true,
                'description'   =>  'Title for NOWPayments',
            ),
            'description'   =>  array(
                'title'         =>  'Pay with NOWPayments',
                'type'          =>  'textarea',
                'default'       =>  'Pay with NOWPayments',
                'desc_tip'      =>  true,
                'description'   =>  'Add a new description for NOWPayments Gateway, Customers will se at checkout.',
            ),
            'live_api_key' => array(
                'title'       => 'Live API Key',
                'type'        => 'password',
                'description' => sprintf(
                        'Get your API: %s',
                        esc_url( 'https://account.nowpayments.io/store-settings' )
                ),
            ),
            'live_ipn_key' => array(
                'title'       => 'Live IPN Secret Key',
                'type'        => 'text',
                'description' => sprintf(
                    'Get your IPN Secret Key: %s',
                    esc_url( 'https://account.nowpayments.io/store-settings' )
                ),
            ),
            'sandbox_api_key' => array(
                'title'       => 'SandBox API Key',
                'type'        => 'password',
                'description' => sprintf(
                    'Get your API: %s',
                    esc_url( 'https://account-sandbox.nowpayments.io/store-settings' )
                ),
            ),
            'sandbox_ipn_key' => array(
                'title'       => 'SandBox IPN Secret Key',
                'type'        => 'text',
                'description' => sprintf(
                    'Get your IPN Secret Key: %s',
                    esc_url( 'https://account-sandbox.nowpayments.io/store-settings' )
                ),
            ),
            'webhook_url' => array(
                'title'             =>  'Webhook URL',
                'type'              =>  'text',
                'default'           =>  add_query_arg( 'wc-api', 'NPWC_Gateway', home_url( '/' ) ),
                'custom_attributes' =>  array( 'readonly' => 'readonly' )
            )
        );

    }

    /**
     * Process Admin Settings | Validate
     *
     * @return bool|void
     * @since 1.0
     * @version 1.0
     */
    public function process_admin_options() {

        parent::process_admin_options();

        if ( empty( $_POST['woocommerce_nowpayments_live_api_key'] ) ) {
            WC_Admin_Settings::add_error( 'Error: Live API Key is required.' );
            return false;
        }

        if( isset( $_POST['woocommerce_nowpayments_sandbox'] ) && empty( $_POST['woocommerce_nowpayments_sandbox_api_key'] ) ) {
            WC_Admin_Settings::add_error( 'Error: SandBox API Key is required.' );
            return false;
        }

    }

    /**
     * Let's Process the Payment xD
     *
     * @param int $order_id
     * @return array|void
     * @since 1.0
     * @version 1.0
     */
    public function process_payment( $order_id ) {

        $order = wc_get_order( $order_id );
        $is_live = ( !empty( $this->get_option( 'sandbox' ) ) && $this->get_option( 'sandbox' ) == 'yes' ) ? false : true;
        $api_key = '';

        if( $is_live ) {
            $api_key = $this->get_option( 'live_api_key' );
        }
        else {
            $api_key = $this->get_option( 'sandbox_api_key' );
        }

        return $this->off_site_checkout( $is_live, $api_key, $order );

    }

    /**
     * Off-Site checkout
     *
     * @param $is_live
     * @param $api_key
     * @param $order
     * @return array
     * @since 1.0
     * @version 1.0
     */
    public function off_site_checkout( $is_live, $api_key, $order ) {

        $order_id = $order->id;

        $parameters = array(
            'dataSource'        => 'woocommerce',
            'ipnURL'            => $this->get_option( 'webhook_url' ),
            'paymentCurrency'   => $order->get_currency(),
            'successURL'        => $this->get_return_url( $order ),
            'cancelURL'         => esc_url_raw( $order->get_cancel_order_url_raw() ),
            'orderID'           => $order_id,
            'customerName'      => $order->billing_first_name,
            'customerEmail'     => $order->billing_email,
            'paymentAmount'     => number_format( $order->get_total(), 8, '.', '' ),
        );

        $order_items = $order->get_items();
        $items = array();

        foreach ( $order_items as $item_id => $item ) {
            $items[] = $item->get_data();
        }

        $parameters["products"] = $items;
        $parameters = apply_filters( 'wcnp_checkout_parameters', $parameters );

        $nowpayments = new NPEC_API( $is_live, $api_key );
        $redirect_url = $nowpayments->off_page_checkout( $parameters );

        return array(
            'result'    =>  'success',
            'redirect'  =>  $redirect_url
        );

    }

    /**
     * Webhook Catcher | action_hook callback
     *
     * @since 1.0
     * @version 1.0
     */
    public function ipn_callback() {

        $request = file_get_contents( 'php://input' );
        $request = json_decode( $request, true );

        //Invalid Call, Order ID doesn't exist.
        if( !array_key_exists( 'order_id', $request ) ) {
            die( 'Invalid Call' );
        }

        $order = wc_get_order( $request['order_id'] );

        //finished - the funds have reached your personal address and the payment is finished.
        if( $request['payment_status'] == 'finished' ) {
            $order->update_status( 'completed', 'NOWPayments finished IPN Call.' );
        }

        //refunded - the funds were refunded back to the user.
        if( $request['payment_status'] == 'refunded' ) {
            $order->update_status( 'refunded', 'NOWPayments refunded IPN Call.' );
        }

        //failed - the payment wasn't completed due to the error of some kind.
        if( $request['payment_status'] == 'failed' ) {
            $order->update_status( 'failed', 'NOWPayments failed IPN Call.' );
        }

    }

}

/**
 * Adds Gateway into WooCommerce
 *
 * @param $gateways
 * @return mixed
 * @since 1.0
 * @version 1.0
 */
if ( !function_exists( 'add_nowpayments_to_wc' ) ):
    function add_nowpayments_to_wc( $gateways ) {
        $gateways[] = 'NPWC_Gateway';
        return $gateways;
    }
endif;

add_filter( 'woocommerce_payment_gateways', 'add_nowpayments_to_wc' );
