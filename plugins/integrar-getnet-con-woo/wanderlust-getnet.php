<?php
/**
 * Plugin Name: Getnet Argentina para Woocommerce
 * Plugin URI: https://globalgetnet.com.ar/
 * Description: Integrar Getnet con WooCommerce
 * Author: Getnet Argentina
 * Author URI: https://globalgetnet.com.ar/
 * Version: 0.1.1
 * WC tested up to: 8.5.0
 * Text Domain: wc-gateway-getnet
 * Domain Path: /i18n/languages/
 *
 * Copyright: (c) 2010-2024 Wanderlust Web Design
 *
 *
 * @package   WC-Gateway-Getnet
 * @author    Wanderlust Web Design
 * @category  Admin
 * @copyright Copyright (c) 2010-2024, Wanderlust Web Design
 *
 */


add_action( 'before_woocommerce_init', function() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );

add_filter("woocommerce_payment_gateways", "getnet_add_gateway_class");
add_action("plugins_loaded", "getnet_init_gateway_class");

function getnet_add_gateway_class($gateways)
{
    $gateways[] = "WC_Getnet_Gateway";
    return $gateways;
}

function getnet_init_gateway_class()
{
    class WC_Getnet_Gateway extends WC_Payment_Gateway
    {
        public function __construct()
        {
            $this->id = "getnet_gateway";
            $this->icon = apply_filters(
                "woocommerce_getnet_icon",
                plugins_url(
                    "integrar-getnet-con-woo/img/logos-tarjetas.png",
                    plugin_dir_path(__FILE__)
                )
            );
            $this->has_fields = false;
            $this->method_title = "Getnet Argentina";
            $this->method_description =
                "Te permite cobrar con tarjetas de crédito y débito. Es necesario tener una cuenta en Getnet Argentina para activar este medio de pago.";
            $this->supports = ["products"];
            $this->init_form_fields();
            $this->init_settings();
            $this->title = $this->get_option("title");
            $this->description = $this->get_option("description");
            $this->enabled = $this->get_option("enabled");
            $this->client_id = $this->get_option("client_id");
            $this->client_secret_id = $this->get_option("client_secret_id");

            add_action(
                "woocommerce_update_options_payment_gateways_" . $this->id,
                [$this, "process_admin_options"]
            );
            add_action("woocommerce_api_getnet", [$this, "webhook"]);
        }

        public function init_form_fields()
        {
            $this->form_fields = [
                "enabled" => [
                    "title" => "Enable/Disable",
                    "label" => "Enable Getnet Gateway",
                    "type" => "checkbox",
                    "description" => "",
                    "default" => "no",
                ],
                "title" => [
                    "title" => "Título",
                    "type" => "text",
                    "description" =>"",
                    "default" => "Pagar con tarjeta de crédito o débito",
                    "desc_tip" => true,
                ],
                "description" => [
                    "title" => "Descripción",
                    "type" => "textarea",
                    "description" => "Esta descripción se mostrará en el checkout",
                    "default" =>
                        "Servicio provisto por Getnet Argentina.",
                ],
                "client_id" => [
                    "title" => "Client ID",
                    "type" => "text",
                ],
                "client_secret_id" => [
                    "title" => "Client Secret",
                    "type" => "text",
                ],
            ];
        }

        public function payment_fields()
        {
            if ($this->description) {
                echo wpautop(wp_kses_post($this->description));
            }
        }

        public function process_payment($order_id)
        {
            global $woocommerce;
            $order = wc_get_order($order_id);
            $shipping_data = $order->get_items("shipping");
            $nombre = "";
            $productos = [];
            $items = $order->get_items();
            $descuentos = $order->get_discount_total();
            $site_url = get_site_url();
            $notificaciones = $site_url . "/?wc-api=getnet";
            $impuestos = $order->get_total_tax();
 
            if( $impuestos ){
 
                    if($impuestos > 1){

                        $recargos = wc_format_decimal($impuestos, 2);
                        $recargos = str_replace(".", "", $recargos);
                         $productos[] = [
                            "id" => 2,
                            "name" => 'IMPUESTOS',
                            "unitPrice" => [
                                "currency" => "032",
                                "amount" => $recargos,
                            ],
                            "quantity" => 1,
                        ];

                    }
 
            }

 
            if ($descuentos > 1) {
                $descuentos = wc_format_decimal($descuentos, 2);
                $descuentos = str_replace(".", "", $descuentos);

                $productos[] = [
                    "id" => 1,
                    "name" => "Descuento",
                    "unitPrice" => [
                        "currency" => "032",
                        "amount" => -$descuentos,
                    ],
                    "quantity" => 1,
                ];
            }

            foreach ($items as $item) {
                if ($item["product_id"] > 0) {
                    $product = wc_get_product($item["product_id"]);
                    if($item["variation_id"]){
                        $product = wc_get_product($item["variation_id"]);
                    } else {
                        $product = wc_get_product($item["product_id"]);
                    }
                    if (empty($nombre)) {
                        $nombre = $product->get_name();
                    } else {
                        $nombre = $nombre . " - " . $product->get_name();
                    }
                    $precio = wc_format_decimal($product->get_price(), 2);
                    $precio_ok = str_replace(".", "", $precio);

                    $productos[] = [
                        "id" => 1,
                        "name" => $nombre,
                        "unitPrice" => [
                            "currency" => "032",
                            "amount" => $precio_ok,
                        ],
                        "quantity" => $item["quantity"],
                    ];
                }
            }

            if (is_array($shipping_data)) {
                foreach ($shipping_data as $k => $sm) {
                    if ($sm["total"] > 1) {
                        $shipping[] = [
                            "shipping" => [
                                "name" => strtoupper($sm["method_title"]),
                                "price" => [
                                    "currency" => "032",
                                    "amount" => "",
                                ],
                            ],
                        ];
                    }
                }
            }

            $token = "https://auth.geopagos.com/oauth/token";

            $body = [
                "client_id" => $this->client_id,
                "client_secret" => $this->client_secret_id,
                "grant_type" => "client_credentials",
                "scope" => "*",
            ];

            $body = wp_json_encode($body);
            $options = [
                "body" => $body,
                "headers" => [
                    "Content-Type" => "application/json",
                ],
                "timeout" => 60,
                "redirection" => 5,
                "blocking" => true,
                "httpversion" => "1.0",
                "sslverify" => false,
                "data_format" => "body",
            ];

            $respuesta = wp_remote_post($token, $options);

            if (!is_wp_error($respuesta)) {
                $response = json_decode($respuesta["body"]);
                $token = $response->access_token;
            }

            $checkout = "https://api.globalgetnet.com.ar/api/v2/orders";

            if ($sm["total"] > 1) {
                $precio = wc_format_decimal($sm["total"], 2);
                $precio_ok = str_replace(".", "", $precio);

                $pload = [
                    "data" => [
                        "attributes" => [
                            "items" => $productos,
                            "shipping" => [
                                "name" => strtoupper($sm["method_title"]),
                                "price" => [
                                    "currency" => "032",
                                    "amount" => $precio_ok,
                                ],
                            ],
                            "redirect_urls" => [
                                "success" => $order->get_checkout_order_received_url(),
                                "failed" => $order->get_cancel_order_url(),
                            ],
                            "webhookUrl" => $notificaciones,
                            "expireLimitMinutes" => 30
                        ],
                    ],
                ];
            } else {
                $pload = [
                    "data" => [
                        "attributes" => [
                            "items" => $productos,
                            "redirect_urls" => [
                                "success" => $order->get_checkout_order_received_url(),
                                "failed" => $order->get_cancel_order_url(),
                            ],
                            "webhookUrl" => $notificaciones,
                            "expireLimitMinutes" => 30,
                        ],
                    ],
                ];
            }

            $body = wp_json_encode($pload);

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.globalgetnet.com.ar/api/v2/orders",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/vnd.api+json",
                    "Accept: application/vnd.api+json",
                    "Authorization: Bearer " . $token,
                ],
            ]);

            $response = curl_exec($curl);

            curl_close($curl);

            $response_ok = json_decode($response);

            update_post_meta($order_id, "checkout", $response);
            update_post_meta(
                $order_id,
                "refNumber",
                $response_ok->data->attributes->payments[0]->reference_number
            );

            if ($response_ok->data->links[0]->checkout) {
                $order->reduce_order_stock();
                WC()->cart->empty_cart();
                return [
                    "result" => "success",
                    "redirect" => $response_ok->data->links[0]->checkout,
                ];
            }
        }

        public function webhook()
        {
            global $wpdb;
            $log = new WC_Logger();
            header("HTTP/1.1 200 OK");
            $postBody = file_get_contents("php://input");

            $log->add("GetNet log", "return " . $postBody);

            $token = "https://auth.geopagos.com/oauth/token";

            $body = [
                "client_id" => $this->client_id,
                "client_secret" => $this->client_secret_id,
                "grant_type" => "client_credentials",
                "scope" => "*",
            ];

            $body = wp_json_encode($body);
            $options = [
                "body" => $body,
                "headers" => [
                    "Content-Type" => "application/json",
                ],
                "timeout" => 60,
                "redirection" => 5,
                "blocking" => true,
                "httpversion" => "1.0",
                "sslverify" => false,
                "data_format" => "body",
            ];

            $respuesta = wp_remote_post($token, $options);

            if (!is_wp_error($respuesta)) {
                $response = json_decode($respuesta["body"]);
                $token = $response->access_token;
            }

            $responseipn = json_decode($postBody);
            if ($responseipn->data) {
                $terms = $responseipn->data->order->uuid;
                $products = $wpdb->get_results( 
                    $wpdb->prepare( "SELECT ID, post_parent FROM {$wpdb->posts} LEFT JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id WHERE meta_key IN ( 'checkout' ) AND meta_value LIKE %s;", '%' . $wpdb->esc_like( wc_clean( $terms ) ) . '%' ) 
                );   

                if ($products[0]->ID) {
  
                    $response = wp_remote_get( 'https://api.globalgetnet.com.ar/api/v2/orders/'. $terms, array(
                        'headers' => array(
                            'Accept' => 'application/json',
                            'Content-Type: application/vnd.api+json',
                            'Authorization: Bearer ' . $token
                        )
                    ) );
                    if ( ( !is_wp_error($response)) && (200 === wp_remote_retrieve_response_code( $response ) ) ) {
                        $order_getnet = json_decode($response['body']);
                        if( json_last_error() === JSON_ERROR_NONE ) {
                            
 
                            $order_id = $products[0]->ID;
                            $order = wc_get_order($order_id);

                            if ($order_getnet->status == "APPROVED") {
                                update_post_meta(
                                    $order_id,
                                    "_getnet_response",
                                    $postBody
                                );
                                $order->add_order_note(
                                    "GetNet: " .
                                        __("Pago Aprobado.", "wc-gateway-getnet")
                                );
                                $order->payment_complete();
                            } elseif ( $order_getnet->status == "PROCESSED" ) {
                                update_post_meta(
                                    $order_id,
                                    "_getnet_response",
                                    $postBody
                                );
                                $order->add_order_note(
                                    "GetNet: " .
                                        __("Pago Aprobado.", "wc-gateway-getnet")
                                );
                                $order->payment_complete();

                            } elseif ( $order_getnet->status == "SUCCESS" ) {
                                update_post_meta(
                                    $order_id,
                                    "_getnet_response",
                                    $postBody
                                );
                                $order->add_order_note(
                                    "GetNet: " .
                                        __("Pago Aprobado.", "wc-gateway-getnet")
                                );
                                $order->payment_complete();

                                
                            } else {
                                $order->add_order_note(
                                    "GetNet: " .
                                        __("Pago Fallido.", "wc-gateway-getnet")
                                );
                                update_post_meta(
                                    $order_id,
                                    "_getnet_response",
                                    $postBody
                                );
                            }                            
                                }
                            }
                
                }
            }
        }
    }
}
