<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class AWC_Api
 */
class AWC_Api
{
    /**
     * @var string
     */
    protected $gateway;

    /**
     * @var WC_Order
     */
    protected $order;

    /**
     * AWC_Api constructor.
     *
     * @param WC_Payment_Gateway $gateway Gateway instance.
     * @param $order
     */
    public function __construct( $gateway, $order = null )
    {
        $this->gateway = $gateway;
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function awc_get_api_url()
    {
        return AWC_URL_API_DOMAIN;
    }

    /**
     * @return mixed
     */
    public function awc_get_host_domain()
    {
        return AWC_HOST_DOMAIN;
    }

    /**
     * @param $suffix_url
     * @return string
     */
    private function awc_get_full_url( $suffix_url )
    {
        return $this->awc_get_api_url() . $suffix_url;
    }

    /**
     * @param $order_id
     * @return array|WP_Error
     */
    public function awc_get_order( $order_id )
    {
        return $this->awc_get( $this->awc_get_full_url( AWC_Suffix_Api::AWC_SUFFIX_ORDER . $order_id ) );
    }

    /**
     * @param $information
     * @return array|WP_Error
     */
    public function awc_calculate_installments( $information )
    {
        return $this->awc_post( $this->awc_get_full_url( AWC_Suffix_Api::AWC_SUFFIX_PAYMENT_INSTALLMENTS ), $information );
    }

    /**
     * @return array|WP_Error
     */
    public function awc_post_customer()
    {
        list( $street, $number ) = explode( ", ", $this->order->data['billing']['address_1'] );

        if (! $number) {
            $number = get_post_meta( $this->order->get_id(), '_billing_number', true );
        }

        $data = [
            "firstname" => $this->order->data['billing']['first_name'],
            "lastname" => $this->order->data['billing']['last_name'],
            "email" => $this->order->data['billing']['email'],
            "telephone" => $this->order->data['billing']['phone'],
            "postcode" => $this->order->data['billing']['postcode'],
            "address_street" => $street,
            "address_street_number" => $number,
            "address_street_complement" => $this->order->data['billing']['address_2'],
            "address_street_district" => get_post_meta( $this->order->get_id(), '_billing_neighborhood', true ),
            "address_city" => $this->order->data['billing']['city'],
            "address_state" => $this->order->data['billing']['state'],
            "ip" => AWC_Helper::awc_get_ip(),
            "tracking" => [
                "utm_source" => "",
                "utm_medium" => "",
                "utm_campaign" => "",
                "utm_term" => "",
                "utm_content" => ""
            ],
        ];

        return $this->awc_post( $this->awc_get_full_url( AWC_Suffix_Api::AWC_SUFFIX_CUSTOMER ), $data );
    }

    /**
     * @param $customer_id
     * @param $interest_total
     * @return array|WP_Error
     * @throws Exception
     */
    public function awc_post_order( $customer_id, $interest_total )
    {
        $data = [
            "customer_id" => $customer_id,
            "products" => $this->awc_get_products_cart( $interest_total ),
            "shipping" => $this->order->get_shipping_total(),
            "discount" => number_format( AWC_Helper::awc_get_discount_total(), 2, '.', ',' ),
            "freight_type" => $this->order->get_shipping_method(),
            "ip" => AWC_Helper::awc_get_ip(),
        ];

        return $this->awc_post( $this->awc_get_full_url( AWC_Suffix_Api::AWC_SUFFIX_ORDER ), $data );
    }

    /**
     * @param array $information
     * @return array|WP_Error
     */
    public function awc_post_payment( $information )
    {
        if ( $information['type_payment'] == AWC_Payment_Type::AWC_BILLET ) {
            return $this->awc_payment_billet( $information['order_id'], $information['customer_id'], $information['post_payment'] );
        }

        if ( $information['type_payment'] == AWC_Payment_Type::AWC_PIX ) {
            return $this->awc_payment_pix( $information['order_id'], $information['customer_id'], $information['post_payment'] );
        }

        return $this->awc_payment_credit_card( $information['order_id'], $information['customer_id'], $information['post_payment'] );
    }

    /**
     * @param $order_id
     * @param $customer_id
     * @param $post_payment
     * @return array|WP_Error
     */
    private function awc_payment_credit_card( $order_id, $customer_id, $post_payment )
    {
        $data = [
            "cart" => [
                "order_id" => $order_id,
            ],
            "customer" => [
                "customer_id" => $customer_id,
            ],
            "payment" => [
                "CreditCard" => [
                    "number" => $post_payment['card_number'],
                    "cvv" => $post_payment['card_security_code'],
                    "month" => $post_payment['card_month'],
                    "year" => $post_payment['card_year'],
                    "document_number" => $post_payment['card_cpf'],
                    "name" => $post_payment['card_name'],
                    "installments" => $post_payment['installments'],
                ],
            ],
        ];

        return $this->awc_post( $this->awc_get_full_url( AWC_Suffix_Api::AWC_SUFFIX_PAYMENT_CREDIT_CARD ), $data );
    }

    /**
     * @param $order_id
     * @param $customer_id
     * @param $post_payment
     * @return array|WP_Error
     */
    private function awc_payment_billet( $order_id, $customer_id, $post_payment )
    {
        $data = [
            "cart" => [
                "order_id" => $order_id,
            ],
            "customer" => [
                "customer_id" => $customer_id,
            ],
            "payment" => [
                "Boleto" => [
                    "document_number" => $post_payment['cpf_billet'],
                    "due_date" => $post_payment['due_date'],
                ],
            ],
        ];

        return $this->awc_post( $this->awc_get_full_url( AWC_Suffix_Api::AWC_SUFFIX_PAYMENT_BILLET ), $data );
    }

    /**
     * @param $order_id
     * @param $customer_id
     * @param $post_payment
     * @return array|WP_Error
     */
    private function awc_payment_pix( $order_id, $customer_id, $post_payment )
    {
        $data = [
            "cart" => [
                "order_id" => $order_id,
            ],
            "customer" => [
                "customer_id" => $customer_id,
            ],
            "payment" => [
                "pix" => [
                    "document_number" => $post_payment['cpf_pix'],
                ],
            ],
        ];

        return $this->awc_post( $this->awc_get_full_url( AWC_Suffix_Api::AWC_SUFFIX_PAYMENT_PIX ), $data );
    }

    /**
     * @return array
     */
    private function awc_get_header()
    {
        return array(
            'Content-Type' => 'application/json; charset=utf-8',
            'access-token' => $this->gateway->awc_api_key,
        );
    }

    /**
     * @param $url
     * @param array $data
     * @return array|WP_Error
     */
    private function awc_post( $url, array $data )
    {
        return wp_remote_post( $url, [
            "headers" => $this->awc_get_header(),
            "body" => AWC_Helper::awc_encode_object( $data ),
            "method" => "POST",
            "data_format" => "data"
        ] );
    }

    /**
     * @param $url
     * @return array|WP_Error
     */
    private function awc_get( $url )
    {
        return wp_remote_get( $url, [
            "headers" => $this->awc_get_header(),
            "method" => "GET",
        ] );
    }

    /**
     * @param $interest
     * @return array
     * @throws Exception
     */
    private function awc_get_products_cart( $interest )
    {
        $items = WC()->cart->get_cart();

        $array_products = [];

        foreach ($items as $values) {
            array_push( $array_products, $this->awc_get_current_product( $values ) );
        }

        $array_products = (new AWC_Interest())->awc_distribute_interest( $array_products, $interest );

        $tax_total = (float) AWC_Helper::awc_get_fee_total();

        if (! $tax_total) {
            return $array_products;
        }

        return (new AWC_Tax())->awc_distribute_tax( $array_products, $interest );
    }

    /**
     * @param $values
     * @return array
     * @throws Exception
     */
    private function awc_get_current_product( $values )
    {
        $product = wc_get_product( $values['product_id'] );

        if ( ! $product->get_sku() ) {
            throw new \Exception( "Produto do carrinho {$product->get_title()} não possui SKU registrado." );
        }

        if ( empty( $values['variation_id'] ) && count( $values['variation'] ) == 0 ) {
            return $this->awc_get_information_product( $product, $values );
        }

        return $this->awc_get_information_product_variation( $product->get_sku(), $values );
    }

    /**
     * @param WC_Product $product
     * @param $values
     * @return array
     */
    private function awc_get_information_product( WC_Product $product, $values )
    {
        $price = (float) $product->get_price();

        if ( ! empty( $values['rn_entry'] ) ) {
            $totals = $values['rn_entry']->Totals;

            $price = (float) $product->get_price() + $totals->OptionsTotal;
        }

        return [
            "sku" => $product->get_sku(),
            "price" => $price,
            "name" => $product->get_title(),
            "qty" => $values['quantity'],
            "description" => $product->get_description(),
            "image" => get_the_post_thumbnail_url( $values['product_id'] ),
        ];
    }

    /**
     * @param $skuParentProduct
     * @param $values
     * @return array
     */
    private function awc_get_information_product_variation( $skuParentProduct, $values )
    {
        $variation = wc_get_product( $values['variation_id'] );

        $price = (float) $variation->get_price();

        if ( ! empty( $values['rn_entry'] ) ) {
            $totals = $values['rn_entry']->Totals;

            $price = (float) $variation->get_price() + $totals->OptionsTotal;
        }

        return [
            "sku" => "{$skuParentProduct}__{$variation->get_sku()}",
            "price" => $price,
            "name" => $variation->get_name(),
            "qty" => $values['quantity'],
            "description" => $variation->get_description(),
            "image" => get_the_post_thumbnail_url( $values ),
        ];
    }

    /**
     * @param $order_id
     * @param $tracking_code
     * @return array|WP_Error
     */
    public function awc_post_tracking_code( $order_id, $tracking_code )
    {
        $data = $this->make_tracking_code_body( $order_id, $tracking_code );

        return $this->awc_post( $this->awc_get_full_url( AWC_Suffix_Api::AWS_SUFFIX_TRACKING_CODE ), $data );
    }

    /**
     * @param $order_id
     * @param $tracking_code
     * @return array
     */
    private function make_tracking_code_body( $order_id, $tracking_code )
    {
        return [
            'access-token' => $this->gateway->awc_api_key,
            'order_id' => $order_id,
            'delivery_tracking_code' => $tracking_code
        ];
    }
}