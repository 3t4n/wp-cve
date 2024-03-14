<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AWC_Tracking_Code
{
    /**
     * @var string
     */
    private $awc_api_key;

    /**
     * @var AWC_Gateway_Credit_Card
     */
    private $gateway;

    public function __construct()
    {
        $this->gateway = new AWC_Gateway_Credit_Card();
        $this->awc_api_key = $this->gateway->get_option( 'awc_api_key' );
    }

    /**
     *  send tracking code to appmax
     * @throws Exception
     */
    public function awc_send_to_appmax()
    {
        if (! isset($_POST['post_id']) && ! isset($_POST['meta'] )) {
            return;
        }

        $order = wc_get_order( $_POST['post_id'] );

        $tracking_code = $this->getTrackingCode($_POST['meta']);

        if (empty($tracking_code)) {
            return;
        }

        $appmax_order_id = $order->get_meta('appmax_order_id');

        if (empty($appmax_order_id)) {
            return;
        }

        $awc_api = new AWC_Api( $this->gateway, $order );

        $response = $awc_api->awc_post_tracking_code($appmax_order_id, $tracking_code);

        $response_tracking_body = $this->validateResponse($response);

        if ( $this->awc_enable_debug() ) {
            $log_content = "";
            $log_content .= "* Informações do endpoint \"/order/delivery-tracking-code\"" . PHP_EOL;
            $log_content .= sprintf( "* Response Json: %s", AWC_Helper::awc_encode_object( $response_tracking_body->data ) ) . PHP_EOL;
            $log_content .= PHP_EOL;
            $this->awc_add_log( $log_content );
        }

        if ($response_tracking_body) {
            $order->add_order_note( sprintf( "Adicionado o código de rastreamento: %s", $tracking_code ), true );
        }
    }

    /**
     *  send woocommerce correios tracking code to appmax
     * @throws Exception
     */
    public function awc_send_correios_to_appmax()
    {
        if (! $this->validateCorreiosTrackingCode($_POST)) {
            return;
        }

        $order = wc_get_order( $_POST['order_id'] );

        $appmax_order_id = $order->get_meta('appmax_order_id');

        if (empty($appmax_order_id)) {
            return;
        }

        update_post_meta( $order->get_order_number(),'appmax_tracking_code', $_POST['tracking_code'] );

        $awc_api = new AWC_Api( $this->gateway, $order );

        $response = $awc_api->awc_post_tracking_code($appmax_order_id, $_POST['tracking_code']);

        $response_tracking_body = $this->validateResponse($response);

        if ( $this->awc_enable_debug() ) {
            $log_content = "";
            $log_content .= "* Informações do endpoint \"/order/delivery-tracking-code\"" . PHP_EOL;
            $log_content .= sprintf( "* Response Json: %s", AWC_Helper::awc_encode_object( $response_tracking_body->data ) ) . PHP_EOL;
            $log_content .= PHP_EOL;
            $this->awc_add_log( $log_content );
        }
    }

    private function getTrackingCode($metas)
    {
        foreach ($metas as $meta) {
            if ($meta['key'] == "appmax_tracking_code") {
                return $meta['value'];
            }
        }

        return null;
    }

    private function validateCorreiosTrackingCode($request)
    {
        if (! isset($request['action'])) {
            return false;
        }

        if ($request['action'] != 'woocommerce_correios_add_tracking_code') {
            return false;
        }

        return isset($request['tracking_code']);
    }

    private function validateResponse($response)
    {
        $this->awc_verify_errors_curl( $response, AWC_Errors_Api::AWC_MESSAGE_ERROR_TRACKING );

        $response_body = AWC_Helper::awc_decode_object( wp_remote_retrieve_body( $response ) );

        $this->awc_verify_access_token( $response_body );

        if ($response_body->success and $response_body->text == "Store delivery tracking code" ) {
            return $response_body;
        }

        $message_exception = sprintf( "%s", AWC_Errors_Api::AWC_MESSAGE_ERROR_TRACKING );

        if ($response_body->data) {
            foreach ($response_body->data as $item) {
                $message_exception .= $item[0];
            }
        }

        throw new \Exception( $message_exception );
    }

    /**
     * @return bool
     */
    private function awc_enable_debug()
    {
        return $this->gateway->debug === 'no';
    }

    /**
     * @param $message
     */
    private function awc_add_log( $message )
    {
        $this->gateway->log->add( $this->gateway->id, $message );
    }

    /**
     * @param $response
     * @param $type
     * @return bool
     * @throws Exception
     */
    private function awc_verify_errors_curl($response, $type = null)
    {
        if (is_wp_error($response) && $response instanceof WP_Error) {

            $log_content = "";

            if (! $type) {
                $log_content .= sprintf( "%s", AWC_Errors_Api::AWC_MESSAGE_003 ) . PHP_EOL;
            }

            if ($type) {
                $log_content .= sprintf( "%s - %s", $type, AWC_Errors_Api::AWC_MESSAGE_003 ) . PHP_EOL;
            }

            $log_content .= sprintf( "Motivo: %s", $response->get_error_message() );
            $this->awc_add_log( $log_content );

            $message_exception = sprintf( "%s", AWC_Errors_Api::AWC_MESSAGE_003 );
            throw new \Exception($message_exception);
        }

        return true;
    }

    /**
     * @param $response_body
     * @return bool
     * @throws Exception
     */
    private function awc_verify_access_token( $response_body )
    {
        if ( ! $response_body->success and $response_body->text == AWC_Errors_Api::AWC_INVALID_ACCESS_TOKEN ) {
            $log_content = sprintf( "%s", AWC_Errors_Api::AWC_MESSAGE_004 ) . PHP_EOL;
            $this->awc_add_log( $log_content );

            $message_exception = sprintf( "%s", AWC_Errors_Api::AWC_MESSAGE_004 );
            throw new \Exception( $message_exception );
        }

        return true;
    }

}