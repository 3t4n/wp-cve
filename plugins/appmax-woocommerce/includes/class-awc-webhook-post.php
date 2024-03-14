<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AWC_WebHook_Post
{
    /**
     * @var $order
     */
    protected $order;

    /**
     * @var $data;
     */
    protected $data;

    /**
     * @var WC_Logger
     */
    protected $log;

    /**
     * AWC_WebHook_Post constructor.
     * @param $data
     */
    public function __construct( $data )
    {
        $this->data = $data;
        $this->log = new WC_Logger();
        $this->order = $this->awc_get_order();
    }

    public function awc_order_integrated()
    {
        $this->awc_generate_log_web_hook();

        if ( in_array( $this->data['origin'], AWC_Origin_Order::callCenter() ) and $this->order ) {
            $order_note = sprintf( "Status atual do pedido #%d na plataforma Appmax: %s", $this->data['id'], AWC_Helper::awc_first_character_in_upper_case( $this->data['status'] ) );

            $this->order->add_order_note( $order_note );

            $log_content = sprintf( "Webhook Appmax - %s", AWC_Helper::awc_date_time_formatted( date( 'Y-m-d H:i:s' ) ) ) . PHP_EOL;
            $log_content .= $order_note . PHP_EOL;
            $this->awc_add_log( $log_content );

            if ($this->data['status'] == AWC_Status_Appmax::AWC_INTEGRATED) {
                $status = AWC_Order_Status::AWC_PROCESSING;
                $this->order->update_status( $status );
                $order_note = sprintf(  "Status do pedido #%d alterado para %s.", $this->order->get_order_number(), AWC_Helper::awc_get_translate_status( $status ) );
                $this->order->add_order_note( $order_note );
            }

            if ($this->data['status'] != AWC_Status_Appmax::AWC_INTEGRATED) {
                $order_note = sprintf(  "O pedido #%d permanecerá com o status atual (%s).", $this->order->get_order_number(), AWC_Helper::awc_get_translate_status( $this->order->get_status() ) );
                $this->order->add_order_note( $order_note );
            }

            $log_content = sprintf( "Webhook Appmax - %s", AWC_Helper::awc_date_time_formatted( date( 'Y-m-d H:i:s' ) ) ) . PHP_EOL;
            $log_content .= $order_note .  PHP_EOL;

            $this->awc_add_log( $log_content );
        }

        if ( in_array( $this->data['origin'], AWC_Origin_Order::callCenter() ) and
            ! $this->order and
            $this->awc_get_gateway()['awc_order_call_center'] == AWC_Events::AWC_ORDER_INTEGRATED
        ) {
            $this->awc_create_order_woo_commerce();
        }

        if ( $this->data['origin'] == AWC_Origin_Order::AWC_API and $this->order ) {
            $order_note = sprintf( "Status atual do pedido #%d na plataforma Appmax: %s", $this->data['id'], AWC_Helper::awc_first_character_in_upper_case( $this->data['status'] ) );

            $this->order->add_order_note( $order_note );

            $log_content = sprintf( "Webhook Appmax - %s", AWC_Helper::awc_date_time_formatted( date( 'Y-m-d H:i:s' ) ) ) . PHP_EOL;
            $log_content .= $order_note . PHP_EOL;
            $this->awc_add_log( $log_content );

            if ($this->data['status'] == AWC_Status_Appmax::AWC_INTEGRATED) {
                $status = AWC_Order_Status::AWC_PROCESSING;
                $this->order->update_status( $status );
                $order_note = sprintf(  "Status do pedido #%d alterado para %s.", $this->order->get_order_number(), AWC_Helper::awc_get_translate_status( $status ) );
                $this->order->add_order_note( $order_note );
            }

            if ($this->data['status'] != AWC_Status_Appmax::AWC_INTEGRATED) {
                $order_note = sprintf(  "O pedido #%d permanecerá com o status atual (%s).", $this->order->get_order_number(), AWC_Helper::awc_get_translate_status( $this->order->get_status() ) );
                $this->order->add_order_note( $order_note );
            }

            $log_content = sprintf( "Webhook Appmax - %s", AWC_Helper::awc_date_time_formatted( date( 'Y-m-d H:i:s' ) ) ) . PHP_EOL;
            $log_content .= $order_note .  PHP_EOL;

            $this->awc_add_log( $log_content );
        }

        return;
    }

    public function awc_order_paid()
    {
        $this->awc_generate_log_web_hook();

        if ( $this->data['origin'] == AWC_Origin_Order::AWC_API and $this->order ) {
            $order_note = sprintf( "Status atual do pedido #%d na plataforma Appmax: %s", $this->data['id'], AWC_Helper::awc_first_character_in_upper_case( $this->data['status'] ) );

            $this->order->add_order_note( $order_note );

            $log_content = sprintf( "Webhook Appmax - %s", AWC_Helper::awc_date_time_formatted( date( 'Y-m-d H:i:s' ) ) ) . PHP_EOL;
            $log_content .= $order_note . PHP_EOL;

            $this->awc_add_log( $log_content );

            $order_note = sprintf(  "O pedido #%d permanecerá com o status atual (%s).", $this->order->get_order_number(), AWC_Helper::awc_get_translate_status( $this->order->get_status() ) );

            $status = AWC_Order_Status::AWC_PROCESSING;

            if ($this->data['status'] == AWC_Status_Appmax::AWC_AUTHORIZED) {

                if ($this->awc_get_gateway()['awc_order_authorized'] == AWC_Order_Status::AWC_ON_HOLD) {
                    $status = AWC_Order_Status::AWC_ON_HOLD;
                }

                $this->order->update_status( $status );
                $order_note = sprintf(  "Status do pedido #%d alterado para %s.", $this->order->get_order_number(), AWC_Helper::awc_get_translate_status( $status ) );
            }

            if ($this->data['status'] == AWC_Status_Appmax::AWC_APPROVED) {
                $this->order->update_status( $status );
                $order_note = sprintf(  "Status do pedido #%d alterado para %s.", $this->order->get_order_number(), AWC_Helper::awc_get_translate_status( $status ) );
            }

            $log_content = sprintf( "Webhook Appmax - %s", AWC_Helper::awc_date_time_formatted( date( 'Y-m-d H:i:s' ) ) ) . PHP_EOL;
            $log_content .= $order_note . PHP_EOL;

            $this->awc_add_log( $log_content );
        }

        if ( in_array( $this->data['origin'], AWC_Origin_Order::callCenter() ) and
            ! $this->order and
            $this->awc_get_gateway()['awc_order_call_center'] == AWC_Events::AWC_ORDER_PAID
        ) {
            $this->awc_create_order_woo_commerce();
        }

        return;
    }

    public function awc_order_refund()
    {
        $this->awc_generate_log_web_hook();

        $this->order->update_status( AWC_Order_Status::AWC_REFUNDED );

        $order_note = sprintf( "Status atual do pedido #%d na plataforma Appmax: %s", $this->data['id'], AWC_Helper::awc_first_character_in_upper_case( $this->data['status'] ) );

        $this->order->add_order_note( $order_note );

        $log_content = sprintf( "Webhook Appmax - %s", AWC_Helper::awc_date_time_formatted( date( 'Y-m-d H:i:s' ) ) ) . PHP_EOL;
        $log_content .= $order_note . PHP_EOL;

        $this->awc_add_log( $log_content );

        $log_content = sprintf( "Webhook Appmax - %s", AWC_Helper::awc_date_time_formatted( date( 'Y-m-d H:i:s' ) ) ) . PHP_EOL;
        $log_content .= sprintf( "* Status do pedido #%d alterado para %s.", $this->order->get_order_number(), AWC_Helper::awc_get_translate_status( AWC_Order_Status::AWC_REFUNDED ) ) . PHP_EOL;
        $log_content .= PHP_EOL;

        $this->awc_add_log( $log_content );
    }

    public function awc_payment_not_authorized()
    {
        $this->awc_generate_log_web_hook();

        $this->order->update_status( AWC_Order_Status::AWC_CANCELLED );

        $log_content = sprintf( "Webhook Appmax - %s", AWC_Helper::awc_date_time_formatted( date( 'Y-m-d H:i:s' ) ) ) . PHP_EOL;
        $log_content .= sprintf( "* Status do pedido #%d alterado para %s.", $this->order->get_order_number(), AWC_Helper::awc_get_translate_status( AWC_Order_Status::AWC_CANCELLED ) ) . PHP_EOL;

        $this->awc_add_log( $log_content );
    }

    public function awc_order_billet_overdue()
    {
        $this->awc_generate_log_web_hook();

        $this->order->update_status( AWC_Order_Status::AWC_PENDING );

        $order_note = sprintf( "Status atual do pedido #%d na plataforma Appmax: %s", $this->data['id'], AWC_Helper::awc_first_character_in_upper_case( $this->data['status'] ) );
        $order_note .= sprintf( "Data de vencimento: %s", AWC_Helper::awc_date_time_formatted( $this->data['billet_date_overdue'] ) ) . PHP_EOL;

        $this->order->add_order_note( $order_note );

        $log_content = sprintf( "Webhook Appmax - %s", AWC_Helper::awc_date_time_formatted( date( 'Y-m-d H:i:s' ) ) ) . PHP_EOL;
        $log_content .= $order_note . PHP_EOL;

        $this->awc_add_log( $log_content );

        $log_content = sprintf( "Webhook Appmax - %s", AWC_Helper::awc_date_time_formatted( date( 'Y-m-d H:i:s' ) ) ) . PHP_EOL;
        $log_content .= sprintf( "* Status do pedido #%d alterado para %s.", $this->order->get_order_number(), AWC_Helper::awc_get_translate_status( AWC_Order_Status::AWC_PENDING ) ) . PHP_EOL;

        $this->awc_add_log( $log_content );
    }

    private function awc_add_log( $message )
    {
        $this->log->add( AWC_APPMAX_WEB_HOOK, $message );
    }

    private function awc_generate_log_web_hook()
    {
        $log_content = sprintf( "Webhook Appmax - disparado em %s", AWC_Helper::awc_date_time_formatted( date( 'Y-m-d H:i:s' ) ) ) . PHP_EOL;
        $log_content .= sprintf( "* IP: %s", AWC_Helper::awc_get_ip() ) . PHP_EOL;

        if (! $this->data['upsell_order_id'] ) {
            $log_content .= sprintf( "* Order ID: %d", $this->data['id'] ) . PHP_EOL;
        }

        if ( $this->data['upsell_order_id'] ) {
            $log_content .= sprintf( "* Order Upsell ID: %d", $this->data['id'] ) . PHP_EOL;
        }

        $log_content .= sprintf( "* Payment Method: %s", $this->data['payment_type'] ) . PHP_EOL;
        $log_content .= sprintf( "* Order Origin: %s", $this->data['origin'] ) . PHP_EOL;
        $log_content .= sprintf( "* Order Status: %s", $this->data['status'] ) . PHP_EOL;
        $log_content .= sprintf( "* Total Products: %s", AWC_Helper::awc_monetary_format( $this->data['total_products'] ) ) . PHP_EOL;
        $log_content .= sprintf( "* Freight Value: %s", AWC_Helper::awc_monetary_format( $this->data['freight_value'] ) ) . PHP_EOL;
        $log_content .= sprintf( "* Discount: %s", AWC_Helper::awc_monetary_format( $this->data['discount'] ) ) . PHP_EOL;
        $log_content .= sprintf( "* Interest: %s", AWC_Helper::awc_monetary_format( $this->data['interest'] ) ) . PHP_EOL;
        $log_content .= sprintf( "* Order Total: %s", AWC_Helper::awc_monetary_format( $this->data['total'] ) ) . PHP_EOL;
        $log_content .= sprintf( "* Created At: %s", AWC_Helper::awc_date_time_formatted( $this->data['created_at'] ) ) . PHP_EOL;

        if ( $this->data['paid_at'] ) {
            $log_content .= sprintf( "* Paid At: %s", AWC_Helper::awc_date_time_formatted( $this->data['paid_at'] ) ) . PHP_EOL;
        }

        if ( $this->data['integrated_at'] ) {
            $log_content .= sprintf( "* Integrated At: %s", AWC_Helper::awc_date_time_formatted( $this->data['integrated_at'] ) ) . PHP_EOL;
        }

        if ( $this->data['refunded_at'] ) {
            $log_content .= sprintf( "* Refunded At: %s", AWC_Helper::awc_date_time_formatted( $this->data['refunded_at'] ) ) . PHP_EOL;
        }

        $log_content .= sprintf( "* Response WebHook: %s", AWC_Helper::awc_encode_object( $this->data ) ) . PHP_EOL;

        $this->awc_add_log($log_content);
    }

    private function awc_create_order_woo_commerce()
    {
        $address_street = $this->data['customer']['address_street'];
        $address_street_number = $this->data['customer']['address_street_number'];
        $address_street_district = $this->data['customer']['address_street_district'];

        $address = array(
            "first_name" => $this->data['customer']['firstname'],
            "last_name" => $this->data['customer']['lastname'],
            "email" => $this->data['customer']['email'],
            "phone" => AWC_Helper::awc_phone_formatted( $this->data['customer']['telephone'] ),
            "address_1" => sprintf( "%s, %s - %s", $address_street, $address_street_number, $address_street_district ),
            "address_2" => $this->data['customer']['address_street_complement'],
            "city" => $this->data['customer']['address_city'],
            "state" => $this->data['customer']['address_state'],
            "postcode" => AWC_Helper::awc_cep_formatted( $this->data['customer']['postcode'] ),
            "country" => "BR",
        );

        $order = wc_create_order();

        $order_note = sprintf( "Processado por Appmax" ) . PHP_EOL;

        if (! $this->data['upsell_order_id'] ) {
            $order_note .= sprintf( "Pedido #%d", $this->data['id'] ) . PHP_EOL;
        }

        if ( $this->data['upsell_order_id'] ) {
            $order_note .= sprintf( "Pedido de Upsell #%d para o pedido #%d", $this->data['id'], $this->data['upsell_order_id'] ) . PHP_EOL;
        }

        $order->add_order_note( $order_note );

        $order->set_address( $address, "billing" );
        $order->set_address( $address, "shipping" );

        $order->update_meta_data( "_billing_cpf", AWC_Helper::awc_cpf_formatted( $this->data['customer']['document_number'] ) );

        $order->set_total( AWC_Helper::awc_number_format( $this->data['total'] ) );
        $order->set_billing_phone( AWC_Helper::awc_phone_formatted( $this->data['customer']['telephone'] ) );

        $status = AWC_Order_Status::AWC_PROCESSING;

        if ($this->awc_get_gateway()['awc_status_order_created'] == AWC_Order_Status::AWC_PENDING) {
            $status = AWC_Order_Status::AWC_PENDING;
        }

        if ($this->data['status'] == AWC_Status_Appmax::AWC_AUTHORIZED) {
            if ($this->awc_get_gateway()['awc_order_authorized'] == AWC_Order_Status::AWC_PROCESSING) {
                $status = AWC_Order_Status::AWC_PROCESSING;
            }

            if ($this->awc_get_gateway()['awc_order_authorized'] == AWC_Order_Status::AWC_ON_HOLD) {
                $status = AWC_Order_Status::AWC_ON_HOLD;
            }
        }

        $order_note = sprintf( "Total de produtos: %s", AWC_Helper::awc_monetary_format( $this->data['total_products'] ) ) . PHP_EOL;
        $order_note .= sprintf( "Valor de frete: %s", AWC_Helper::awc_monetary_format( $this->data['freight_value'] ) ) . PHP_EOL;
        $order_note .= sprintf( "Desconto: %s", AWC_Helper::awc_monetary_format( $this->data['discount'] ) ) . PHP_EOL;
        $order_note .= sprintf( "Juros: %s", AWC_Helper::awc_monetary_format( $this->data['interest'] ) ) . PHP_EOL;
        $order_note .= sprintf( "Total do pedido: %s", AWC_Helper::awc_monetary_format( $this->data['total'] ) ) . PHP_EOL;

        $order->add_order_note( $order_note );

        $order->update_status( $status );

        $log_content = sprintf( "* Adicionando produtos ao pedido #%d", $order->get_id() ) . PHP_EOL;

        foreach ($this->data['bundles'] as $bundle) {

            $log_content .= sprintf( "* Produtos do pacote %s", $bundle['name'] ) . PHP_EOL;

            foreach ($bundle['products'] as $product) {

                $product_woo_commerce = $this->awc_get_product_by_sku( $this->awc_verify_sku_variation( $product['sku'] ) );

                if ( $product_woo_commerce ) {
                    $order->add_product( $product_woo_commerce, $product['quantity'] );
                    $log_content .= sprintf( "* %d x \"%s\" adicionado ao pedido #%d" , $product['quantity'], $product['name'], $order->get_id() ) . PHP_EOL;
                }

                if (! $product_woo_commerce ) {
                    $log_content .= sprintf( "* ATENÇÃO! Produto %s não foi encontrado.", $product['name'] ) . PHP_EOL;
                    $log_content .= sprintf( "* Cadastrando o produto %s no WooCommerce.", $product['name'] ) . PHP_EOL;

                    $new_product = new WC_Product();
                    $new_product->set_sku( $product['sku'] );
                    $new_product->set_name( $product['name'] );
                    $new_product->set_description( $product['description'] );
                    $new_product->set_short_description( $product['description'] );
                    $new_product->set_price( $product['price'] );
                    $new_product->set_regular_price( $product['price'] );
                    $new_product->set_status( 'pending' );
                    $new_product->save();

                    $log_content .= sprintf( "* Produto %s salvo com sucesso.", $product['name'] ) . PHP_EOL;

                    $order->add_product( $new_product, $product['quantity'] );

                    $log_content .= sprintf( "* %d x \"%s\" adicionado ao pedido #%d" , $product['quantity'], $product['name'], $order->get_id() ) . PHP_EOL;
                }
            }
        }

        $log_content .= sprintf( "* Pedido #%d salvo com sucesso.", $order->get_id() ) . PHP_EOL;

        $this->awc_add_log( $log_content );

        // Pega o ID do pedido criado
        $order_raw_data = wc_get_order( $order );
        $order_data = json_decode( $order_raw_data, true );
        $order_id = $order_data['id'];

        // Salva alguns campos personalizados para facilitar a busca por pedidos Appmax, caso necessário no futuro
        update_post_meta( $order_id,'appmax_order_id', $this->data['id'] );
        update_post_meta( $order_id,'appmax_upsell_parent_id', $this->data['upsell_order_id'] );
    }

    private function awc_verify_sku_variation( $sku )
    {
        if (preg_match("/__/i", $sku)) {
            list($parent, $children) = explode("__", $sku);
            return $children;
        }

        return $sku;
    }

    private function awc_get_order()
    {
        global $wpdb;

        $sql = 'select * from %s where meta_key = \'appmax_order_id\' and meta_value = \'%s\' limit 1';
        $stmt = sprintf( $sql, $wpdb->postmeta, $this->data['id'] );
        $result = $wpdb->get_row( $stmt, OBJECT );

        return wc_get_order( $result->post_id );
    }

    private function awc_get_product_by_sku( $sku )
    {
        global $wpdb;

        $sql = 'select * from %s where meta_key = \'_sku\' and meta_value = \'%s\' limit 1';
        $stmt = sprintf( $sql, $wpdb->postmeta, $sku);
        $result = $wpdb->get_row( $stmt, OBJECT );

        return wc_get_product( $result->post_id );
    }

    private function awc_get_gateway()
    {
        if ( $this->data['payment_type'] == AWC_Payment_Type::AWC_CREDIT_CARD ) {
            return AWC_Search_Gateway::awc_get_gateway_credit_card();
        }

        return AWC_Search_Gateway::awc_get_gateway_billet();
    }
}
