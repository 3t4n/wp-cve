<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class AWC_Process_Payment
 */
class AWC_Process_Payment
{
    /**
     * @var WC_Payment_Gateway
     */
    protected $gateway;

    /**
     * @param WC_Payment_Gateway $gateway Gateway instance.
     */
    public function __construct( $gateway )
    {
        $this->gateway = $gateway;
    }

    /**
     * @param $order_id
     * @return array
     * @throws Exception
     */
    public function awc_process_payment_credit_card( $order_id )
    {
        $order = wc_get_order( $order_id );

        $order->add_meta_data( '_appmax_type_payment', AWC_Payment_Type::AWC_CREDIT_CARD );

        if ( $this->awc_enable_debug() ) {
            $log_content = "";
            $log_content .= "============================================================" . PHP_EOL;
            $log_content .= sprintf( "* Appmax Credit Card - #%s - %s", $order->get_order_number(), AWC_Helper::awc_date_time_formatted( date( 'Y-m-d H:i:s' ) ) ) . PHP_EOL;
            $log_content .= sprintf( "* Meta Data \"_appmax_type_payment\": %s", AWC_Payment_Type::AWC_CREDIT_CARD ) . PHP_EOL;
            $this->awc_add_log( $log_content );
        }

        $awc_api = new AWC_Api( $this->gateway, $order );

        // Send post to endpoint customer
        $response_customer = $awc_api->awc_post_customer();

        $this->awc_verify_server( $response_customer );

        $response_customer_body = $this->awc_verify_post_customer( $response_customer );

        if ( $this->awc_enable_debug() ) {
            $log_content = "";
            $log_content .= "* Informações do endpoint \"/customer\"" . PHP_EOL;
            $log_content .= sprintf( "* Response Json: %s", AWC_Helper::awc_encode_object( $response_customer_body->data ) ) . PHP_EOL;
            $log_content .= PHP_EOL;
            $this->awc_add_log( $log_content );
        }

        $interest_total = (float) AWC_Calculate::awc_calculate_total_interest(
            AWC_Helper::awc_get_total_cart(),
            $_POST['installments'],
            $this->gateway->settings['awc_interest_credit_card']
        );

        // Send post to endpoint order
        $response_order = $awc_api->awc_post_order( $response_customer_body->data->id, $interest_total );

        $response_order_body = $this->awc_verify_post_order( $response_order );

        $order->add_order_note( sprintf( "Appmax Order ID: %s", $response_order_body->data->id ), true );

        $order->add_meta_data( '_appmax_order_id', $response_order_body->data->id );

        $order->add_meta_data( '_appmax_tracking_code','' );

        update_post_meta( $order->get_order_number(),'appmax_order_id', $response_order_body->data->id );

        update_post_meta( $order->get_order_number(),'appmax_tracking_code', '' );

        if ( $this->awc_enable_debug() ) {
            $log_content = "";
            $log_content .= "* Informações do endpoint \"/order\"" . PHP_EOL;
            $log_content .= sprintf( "* Response Json: %s", AWC_Helper::awc_encode_object( $response_order_body->data ) ) . PHP_EOL;
            $log_content .= PHP_EOL;
            $this->awc_add_log( $log_content );
        }

        // Send post to endpoint payment
        $response_payment = $awc_api->awc_post_payment( [
            'order_id' => $response_order_body->data->id,
            'customer_id' => $response_customer_body->data->id,
            'post_payment' => AWC_Process_Payment::awc_make_post_payment_credit_card(),
            'type_payment' => AWC_Payment_Type::AWC_CREDIT_CARD,
        ] );

        $response_payment_body = $this->awc_verify_post_payment( $response_payment, $order );

        $order->update_status( $this->gateway->settings['awc_status_order_created'] );

        $order->add_order_note( sprintf( "Pay Reference: %s", $response_payment_body->data->pay_reference ), true );

        $order->add_meta_data( '_appmax_pay_reference', $response_payment_body->data->pay_reference );

        $order->add_meta_data( '_appmax_tracking_code', '' );

        update_post_meta( $order->get_order_number(),'appmax_pay_reference', $response_payment_body->data->pay_reference );

        update_post_meta( $order->get_order_number(),'appmax_tracking_code', '' );

        if ( $this->awc_enable_debug() ) {
            $log_content = "";
            $log_content .= "* Informações do endpoint \"/payment/credit-card\"" . PHP_EOL;
            $log_content .= sprintf( "* Response Json: %s", AWC_Helper::awc_encode_object( $response_payment_body ) ) . PHP_EOL;
            $this->awc_add_log( $log_content );
        }

        $this->awc_save_order_meta_fields( $order->get_order_number(), [
            'type_payment' => AWC_Payment_Type::AWC_CREDIT_CARD,
            'post_payment' => array_merge(
                AWC_Process_Payment::awc_make_post_payment_credit_card(),
                [
                    'card_number' => substr_replace(AWC_Helper::awc_card_number_unformatted( AWC_Helper::awc_clear_input( $_POST['card_number'] ) ), "****", 6, -4),
                    'card_month' => "**",
                    'card_year' => "**",
                    'card_security_code' => "***",
                ]
            )
        ] );

        WC()->cart->empty_cart();

        return array(
            'result' => 'success',
            'redirect' => $this->gateway->get_return_url( $order ),
        );
    }

    /**
     * @param $order_id
     * @return array
     * @throws Exception
     */
    public function awc_process_payment_billet( $order_id )
    {
        $order = wc_get_order( $order_id );

        $order->add_meta_data( '_appmax_type_payment', AWC_Payment_Type::AWC_BILLET );

        if ( $this->awc_enable_debug() ) {
            $log_content = "";
            $log_content .= "============================================================" . PHP_EOL;
            $log_content .= sprintf( "* Appmax Billet - #%s - %s", $order->get_order_number(), AWC_Helper::awc_date_time_formatted( date( 'Y-m-d H:i:s' ) ) ) . PHP_EOL;
            $log_content .= sprintf( "* Meta Data \"_appmax_type_payment\": %s", AWC_Payment_Type::AWC_BILLET ) . PHP_EOL;
            $this->awc_add_log( $log_content );
        }

        $awc_api = new AWC_Api( $this->gateway, $order );

        // Send post to endpoint customer
        $response_customer = $awc_api->awc_post_customer();

        $this->awc_verify_server( $response_customer );

        $response_customer_body = $this->awc_verify_post_customer( $response_customer );

        if ( $this->awc_enable_debug() ) {
            $log_content = "";
            $log_content .= "* Informações do endpoint \"/customer\"" . PHP_EOL;
            $log_content .= sprintf( "* Response Json: %s", AWC_Helper::awc_encode_object( $response_customer_body->data ) ) . PHP_EOL;
            $log_content .= PHP_EOL;
            $this->awc_add_log( $log_content );
        }

        $interest_total = 0;

        // Send post to endpoint order
        $response_order = $awc_api->awc_post_order( $response_customer_body->data->id, $interest_total );

        $response_order_body = $this->awc_verify_post_order( $response_order );

        $order->add_order_note( sprintf( "Appmax Order ID: %s", $response_order_body->data->id ), true );

        $order->add_meta_data( '_appmax_order_id', $response_order_body->data->id );

        $order->add_meta_data( '_appmax_tracking_code','' );

        update_post_meta( $order->get_order_number(),'appmax_tracking_code', '' );

        update_post_meta( $order->get_order_number(),'appmax_order_id', $response_order_body->data->id );

        if ( $this->awc_enable_debug() ) {
            $log_content = "";
            $log_content .= "* Informações do endpoint \"/order\"" . PHP_EOL;
            $log_content .= sprintf( "* Response Json: %s", AWC_Helper::awc_encode_object( $response_order_body->data ) ) . PHP_EOL;
            $log_content .= PHP_EOL;
            $this->awc_add_log( $log_content );
        }

        $post_payment = AWC_Process_Payment::awc_make_post_payment_billet();
        $post_payment['due_date'] = AWC_Due_Date_Validator::awc_generate_due_date( $this->gateway->awc_due_days );

        $information = array(
            'order_id' => $response_order_body->data->id,
            'customer_id' => $response_customer_body->data->id,
            'post_payment' => $post_payment,
            'type_payment' => AWC_Payment_Type::AWC_BILLET,
        );

        // Send post to endpoint payment
        $response_payment = $awc_api->awc_post_payment( $information );

        $response_payment_body = $this->awc_verify_post_payment( $response_payment, $order );

        $order->update_status( AWC_Order_Status::AWC_PENDING );

        $order->add_order_note( sprintf( "Boleto: %s", $response_payment_body->data->pdf ), true );
        $order->add_order_note( sprintf( "Pay Reference: %s", $response_payment_body->data->pay_reference ), true );
        $order->add_order_note( sprintf( "Digitable Line: %s", $response_payment_body->data->digitable_line ), true );

        $order->add_meta_data( '_appmax_link_billet', $response_payment_body->data->pdf );
        $order->add_meta_data( '_appmax_pay_reference', $response_payment_body->data->pay_reference );
        $order->add_meta_data( '_appmax_digitable_line', $response_payment_body->data->digitable_line );

        update_post_meta( $order->get_order_number(),'appmax_link_billet', $response_payment_body->data->pdf );
        update_post_meta( $order->get_order_number(),'appmax_pay_reference', $response_payment_body->data->pay_reference );
        update_post_meta( $order->get_order_number(),'appmax_digitable_line', $response_payment_body->data->digitable_line );

        if ( $this->awc_enable_debug() ) {
            $log_content = "";
            $log_content .= "* Informações do endpoint \"/payment/billet\"" . PHP_EOL;
            $log_content .= sprintf( "* Response Json: %s", AWC_Helper::awc_encode_object( $response_payment_body ) ) . PHP_EOL;
            $this->awc_add_log( $log_content );
        }

        $post_payment['link_billet'] = $response_payment_body->data->pdf;
        $post_payment['pay_reference'] = $response_payment_body->data->pay_reference;
        $post_payment['digitable_line'] = $response_payment_body->data->digitable_line;

        $this->awc_save_order_meta_fields( $order->get_order_number(), [
            'type_payment' => AWC_Payment_Type::AWC_BILLET,
            'post_payment' => $post_payment,
        ] );

        WC()->cart->empty_cart();

        return array(
            'result' => 'success',
            'redirect' => $this->gateway->get_return_url( $order ),
        );
    }

    /**
     * @param $order_id
     * @return array
     * @throws Exception
     */
    public function awc_process_payment_pix( $order_id )
    {
        $order = wc_get_order( $order_id );

        $order->add_meta_data( '_appmax_type_payment', AWC_Payment_Type::AWC_PIX );

        if ( $this->awc_enable_debug() ) {
            $log_content = "";
            $log_content .= "============================================================" . PHP_EOL;
            $log_content .= sprintf( "* Appmax Pix - #%s - %s", $order->get_order_number(), AWC_Helper::awc_date_time_formatted( date( 'Y-m-d H:i:s' ) ) ) . PHP_EOL;
            $log_content .= sprintf( "* Meta Data \"_appmax_type_payment\": %s", AWC_Payment_Type::AWC_PIX ) . PHP_EOL;
            $this->awc_add_log( $log_content );
        }

        $awc_api = new AWC_Api( $this->gateway, $order );

        // Send post to endpoint customer
        $response_customer = $awc_api->awc_post_customer();

        $this->awc_verify_server( $response_customer );

        $response_customer_body = $this->awc_verify_post_customer( $response_customer );

        if ( $this->awc_enable_debug() ) {
            $log_content = "";
            $log_content .= "* Informações do endpoint \"/customer\"" . PHP_EOL;
            $log_content .= sprintf( "* Response Json: %s", AWC_Helper::awc_encode_object( $response_customer_body->data ) ) . PHP_EOL;
            $log_content .= PHP_EOL;
            $this->awc_add_log( $log_content );
        }

        $interest_total = 0;

        // Send post to endpoint order
        $response_order = $awc_api->awc_post_order( $response_customer_body->data->id, $interest_total );

        $response_order_body = $this->awc_verify_post_order( $response_order );

        $order->add_order_note( sprintf( "Appmax Order ID: %s", $response_order_body->data->id ), true );

        $order->add_meta_data( '_appmax_order_id', $response_order_body->data->id );

        $order->add_meta_data( '_appmax_tracking_code','' );

        update_post_meta( $order->get_order_number(),'appmax_tracking_code', '' );

        update_post_meta( $order->get_order_number(),'appmax_order_id', $response_order_body->data->id );

        if ( $this->awc_enable_debug() ) {
            $log_content = "";
            $log_content .= "* Informações do endpoint \"/order\"" . PHP_EOL;
            $log_content .= sprintf( "* Response Json: %s", AWC_Helper::awc_encode_object( $response_order_body->data ) ) . PHP_EOL;
            $log_content .= PHP_EOL;
            $this->awc_add_log( $log_content );
        }

        $post_payment = AWC_Process_Payment::awc_make_post_payment_pix();

        $information = array(
            'order_id' => $response_order_body->data->id,
            'customer_id' => $response_customer_body->data->id,
            'post_payment' => $post_payment,
            'type_payment' => AWC_Payment_Type::AWC_PIX,
        );

        // Send post to endpoint payment
        $response_payment = $awc_api->awc_post_payment( $information );

        $response_payment_body = $this->awc_verify_post_payment( $response_payment, $order );

        $order->update_status( AWC_Order_Status::AWC_PENDING );

        $order->add_order_note( sprintf( "Pay Reference: %s", $response_payment_body->data->pay_reference ), true );
        $order->add_meta_data( '_appmax_pay_reference', $response_payment_body->data->pay_reference );

        update_post_meta( $order->get_order_number(),'appmax_pay_reference', $response_payment_body->data->pay_reference );

        if ( $this->awc_enable_debug() ) {
            $log_content = "";
            $log_content .= "* Informações do endpoint \"/payment/pix\"" . PHP_EOL;
            $log_content .= sprintf( "* Response Json: %s", AWC_Helper::awc_encode_object( $response_payment_body ) ) . PHP_EOL;
            $this->awc_add_log( $log_content );
        }

        $post_payment['pay_reference'] = $response_payment_body->data->pay_reference;
        $post_payment['pix_qrcode'] = $response_payment_body->data->pix_qrcode;
        $post_payment['pix_emv'] = $response_payment_body->data->pix_emv;
        $post_payment['pix_expiration_date'] = $response_payment_body->data->pix_expiration_date;
        $post_payment['order_id'] = $response_order_body->data->id;

        $this->awc_save_order_meta_fields( $order->get_order_number(), [
            'type_payment' => AWC_Payment_Type::AWC_PIX,
            'post_payment' => $post_payment,
        ] );

        WC()->cart->empty_cart();

        return array(
            'result' => 'success',
            'redirect' => $this->gateway->get_return_url( $order ),
        );
    }

    /**
     * @param $response
     * @return bool
     * @throws Exception
     */
    private function awc_verify_server( $response )
    {
        $this->awc_verify_errors_curl( $response );

        $data = $response['headers']->getAll();

        $log_content = "";

        if (array_key_exists('cf-ray', $data) && $data['server'] == 'cloudflare' && $response['response']['code'] != 200) {
            $log_content .= sprintf( "%s", AWC_Errors_Api::AWC_MESSAGE_001 ) . PHP_EOL;
            $log_content .= sprintf( "%s - %s (Cloudflare)", $response['response']['code'], $response['response']['message'] ) . PHP_EOL;
            $this->awc_add_log( $log_content );

            $message_exception = sprintf( "%s", AWC_Errors_Api::AWC_MESSAGE_001 );
            throw new \Exception( $message_exception );
        }

        if ($data['server'] == 'nginx' && $response['response']['code'] != 200) {
            $log_content .= sprintf( "%s", AWC_Errors_Api::AWC_MESSAGE_002 ) . PHP_EOL;
            $log_content .= sprintf( "%s - %s (Nginx)", $response['response']['code'], $response['response']['message'] ) . PHP_EOL;
            $this->awc_add_log( $log_content );

            $message_exception = sprintf( "%s", AWC_Errors_Api::AWC_MESSAGE_002 );
            throw new \Exception( $message_exception );
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

            $log_content .= sprintf( "Motivo: %s", $response->get_error_message() ) . PHP_EOL;
            $this->awc_add_log( $log_content );

            $message_exception = sprintf( "%s", AWC_Errors_Api::AWC_MESSAGE_003 );
            throw new \Exception($message_exception);
        }

        return true;
    }

    /**
     * @param $response
     * @return array|mixed|object
     * @throws Exception
     */
    private function awc_verify_post_customer( $response )
    {
        $this->awc_verify_errors_curl( $response, AWC_Errors_Api::AWC_MESSAGE_ERROR_CUSTOMER );

        $response_body = AWC_Helper::awc_decode_object( wp_remote_retrieve_body( $response ) );

        $this->awc_verify_access_token( $response_body );

        if ( ! $response_body->success and $response_body->text == AWC_Errors_Api::AWC_VALIDATE_REQUEST ) {

            $message_exception = sprintf( "%s", AWC_Errors_Api::AWC_MESSAGE_ERROR_CUSTOMER );

            if ($response_body->data) {
                $message_exception .= "<ul>";
                foreach ($response_body->data as $item) {
                    $message_exception .= "<li>" . $item[0] . "</li>";
                }
                $message_exception .= "</ul>";
            }

            throw new \Exception( $message_exception );
        }

        return $response_body;
    }

    /**
     * @param $response
     * @return array|mixed|object
     * @throws Exception
     */
    private function awc_verify_post_order( $response )
    {
        $this->awc_verify_errors_curl( $response, AWC_Errors_Api::AWC_MESSAGE_ERROR_ORDER );

        $response_body = AWC_Helper::awc_decode_object( wp_remote_retrieve_body( $response ) );

        $this->awc_verify_access_token( $response_body );

        if ( ! $response_body->success and $response_body->text == AWC_Errors_Api::AWC_VALIDATE_REQUEST ) {
            $message_exception = sprintf( "%s", AWC_Errors_Api::AWC_MESSAGE_ERROR_ORDER );

            if ($response_body->data) {
                $message_exception .= "<ul>";
                foreach ($response_body->data as $item) {
                    $message_exception .= "<li>" . $item[0] . "</li>";
                }
                $message_exception .= "</ul>";
            }

            throw new \Exception( $message_exception );
        }

        return $response_body;
    }

    /**
     * @param $response
     * @param WC_Order $order
     * @return array|mixed|object
     * @throws Exception
     */
    private function awc_verify_post_payment($response, $order )
    {
        $log_content = "";

        if (is_wp_error($response) && $response instanceof WP_Error) {

            $order->update_status( AWC_Order_Status::AWC_FAILED, AWC_Errors_Api::AWC_MESSAGE_ERROR_PAYMENT );

            $log_content .= sprintf( "* Falha na transação %s ...", $order->get_order_number() ) . PHP_EOL;
            $log_content .= sprintf( "* Motivo do cancelamento: %s.", AWC_Errors_Api::AWC_MESSAGE_ERROR_PAYMENT ) . PHP_EOL;
            $log_content .= sprintf( "* Resposta de servidor: %s.", $response->get_error_message() ) . PHP_EOL;
            $this->awc_add_log( $log_content );

            $message_exception = sprintf( "%s", AWC_Errors_Api::AWC_MESSAGE_005 );
            throw new \Exception( $message_exception );
        }

        $response_body = AWC_Helper::awc_decode_object( wp_remote_retrieve_body( $response ) );

        $this->awc_verify_access_token( $response_body );

        if ( ! $response_body->success and $response_body->text == AWC_Errors_Api::AWC_VALIDATE_REQUEST ) {

            $message_exception = sprintf( "%s", AWC_Errors_Api::AWC_MESSAGE_005 );

            if ($response_body->data) {
                $message_exception .= "<ul>";
                foreach ($response_body->data as $item) {
                    $message_exception .= "<li>" . $item[0] . "</li>";
                }
                $message_exception .= "</ul>";
            }

            throw new \Exception( $message_exception );
        }

        if ( ! $response_body->success ) {

            if ($order->status == AWC_Order_Status::AWC_FAILED) {
                $order->add_order_note( $response_body->text );
            }

            if ($order->status != AWC_Order_Status::AWC_FAILED) {
                $order->update_status( AWC_Order_Status::AWC_FAILED, $response_body->text );
            }

            if ( $this->awc_enable_debug() ) {
                $log_content .= sprintf( "* Falha na transação %s ...", $order->get_order_number() ) . PHP_EOL;
                $log_content .= sprintf( "* Motivo do cancelamento: %s.", $response_body->text ) . PHP_EOL;
                $this->awc_add_log( $log_content );
            }

            $message_exception = sprintf( "%s", AWC_Errors_Api::AWC_MESSAGE_005 );
            throw new \Exception( $message_exception );
        }

        return $response_body;
    }

    /**
     * @param $order_id
     * @param $payment_data
     */
    private function awc_save_order_meta_fields( $order_id, $payment_data ) {

        $meta_data = array(
            '_appmax_woocommerce_transaction_data' => $payment_data,
            '_appmax_woocommerce_transaction_id'   => intval( $order_id ),
            '_transaction_id'                      => intval( $order_id ),
        );

        if ($this->awc_enable_debug()) {
            $log_content = sprintf( "Appmax WooCommerce - %s", AWC_Helper::awc_date_time_formatted( date( 'Y-m-d H:i:s' ) ) ) . PHP_EOL;
            $log_content .= sprintf( "* Meta Datas inseridas: %s", AWC_Helper::awc_encode_object( $meta_data ) ) . PHP_EOL;
            $log_content .= PHP_EOL;
            $log_content .= "============================================================";
            $this->awc_add_log( $log_content );
        }

        $order = wc_get_order( $order_id );

        if ( ! method_exists( $order, 'update_meta_data' ) ) {
            foreach ( $meta_data as $key => $value ) {
                update_post_meta( $order_id, $key, $value );
            }
        } else {
            foreach ( $meta_data as $key => $value ) {
                $order->update_meta_data( $key, $value );
            }

            $order->save();
        }
    }

    /**
     * @return array
     */
    private static function awc_make_post_payment_credit_card()
    {
        return array(
            'card_number' => AWC_Helper::awc_card_number_unformatted( AWC_Helper::awc_clear_input( $_POST['card_number'] ) ),
            'card_name' => AWC_Helper::awc_clear_input( $_POST['card_name'] ),
            'card_cpf' => AWC_Helper::awc_cpf_unformatted( AWC_Helper::awc_clear_input( $_POST['card_cpf'] ) ),
            'card_month' => AWC_Helper::awc_clear_input( $_POST['card_month'] ),
            'card_year' => AWC_Helper::awc_clear_input( $_POST['card_year'] ),
            'card_security_code' => AWC_Helper::awc_clear_input( $_POST['card_security_code'] ),
            'installments' => AWC_Helper::awc_clear_input( $_POST['installments'] ),
        );
    }

    /**
     * @return array
     */
    private static function awc_make_post_payment_billet()
    {
        return array(
            'cpf_billet' => AWC_Helper::awc_cpf_unformatted( AWC_Helper::awc_clear_input( $_POST['cpf_billet'] ) ),
        );
    }

    /**
     * @return array
     */
    private static function awc_make_post_payment_pix()
    {
        return array(
            'cpf_pix' => AWC_Helper::awc_cpf_unformatted( AWC_Helper::awc_clear_input( $_POST['cpf_pix'] ) ),
        );
    }

    /**
     * @return bool
     */
    private function awc_enable_debug()
    {
        return $this->gateway->debug === 'yes';
    }

    /**
     * @param $message
     */
    private function awc_add_log( $message )
    {
        $this->gateway->log->add( $this->gateway->id, $message );
    }

    /**
     * @param WC_Order $order
     * @return bool
     */
    private function awc_check_order_appmax( WC_Order $order )
    {
        $awc_api = new AWC_Api( $this->gateway, $order );

        $response_get_order = $awc_api->awc_get_order( $order->get_meta('_appmax_order_id') );

        $response_body = AWC_Helper::awc_decode_object( wp_remote_retrieve_body( $response_get_order ) );

        $order_note = sprintf(
            "Status atual do pedido #%d na plataforma Appmax: %s",
            $order->get_meta('_appmax_order_id'),
            AWC_Helper::awc_first_character_in_upper_case( $response_body->data->status )
        );

        $order->add_order_note( $order_note );

        $status = AWC_Order_Status::AWC_FAILED;

        if ( in_array( $response_body->data->status, AWC_Status_Appmax::approved() ) ) {
            $status = $this->gateway->settings['awc_status_order_created'];
        }

        if ( $this->awc_enable_debug() ) {
            $log_content = $order_note . PHP_EOL;
            $log_content .= sprintf(
                    "* Status do pedido #%d alterado para %s.",
                    $order->get_order_number(),
                    AWC_Helper::awc_first_character_in_upper_case( $status )
                ) . PHP_EOL;
            $this->awc_add_log( $log_content );
        }

        $order->update_status( $status );

        return true;
    }
}