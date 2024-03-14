<?php

namespace CODNetwork\Services;

use CODNetwork\Enums\LoggerEnum;
use CODNetwork\Models\CODN_Settings;
use CODNetwork\Services\Exception\CODN_Application_Token_Null_Exception;
use CODNetwork\Services\Exception\CODN_Order_Bad_Request_Exception;
use CODNetwork\Services\Exception\CODN_WP_Error_Exception;
use WC_Order;
use WP_Error;
use WP_Http;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('CODN_Order_Service')) {
    class CODN_Order_Service
    {
        const ORDER_ENDPOINT = '/api/woocommerce/orders';

        private static $instance;

        /** @var CODN_Settings */
        private $settings;

        /** @var CODN_Logger_Service */
        private $logger;

        public function __construct()
        {
            $this->settings = CODN_Settings::get_instance();
            $this->logger = new CODN_Logger_Service();
        }

        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Pushing a order to Cod.network Platform
         * throws CODN_Application_Token_Null_Exception|Exception|CODN_Order_Bad_Request_Exception
         */
        public function push_new_order(int $orderId): array
        {
            $token = $this->settings->select_token();
            if (is_null($token)) {
                throw new CODN_Application_Token_Null_Exception('token is empty', 500);
            }

            $order = new WC_Order($orderId);
            $items = $order->get_items();
            $productIds = [];
            $url = sprintf("%s/%s", codn_get_domain(), self::ORDER_ENDPOINT);

            foreach ($items as $item) {
                $productIds[] = $item['product_id'];
            }

            $response = $this->requestOrder($url, $token, $productIds, $orderId);
            $responseCode = (int) wp_remote_retrieve_response_code($response);
            $responseMessage = trim(wp_remote_retrieve_response_message($response));

            if (!in_array($responseCode, [WP_Http::OK, WP_Http::CREATED])) {
                $this->logger->error(
                    'cod network respond by invalid status while pushing new order',
                    [
                        LoggerEnum::ORDER_ID      => $orderId,
                        LoggerEnum::EXTRA_MESSAGE => $responseMessage,
                        LoggerEnum::RESPONSE_CODE => $responseCode,
                        LoggerEnum::TRACE         => __FILE__,
                    ]
                );

                throw new CODN_Order_Bad_Request_Exception(
                    $responseMessage,
                    $responseCode
                );
            }

            $this->logger->info(
                'pushing new order has been completed',
                [
                    'order.id' => $orderId
                ]
            );

            return json_decode($response['body'], true);
        }

        public function requestOrder(string $url, string $token, array $productIds, int $orderId): array
        {
            $data = [
                'store' => get_site_url(),
                'hmac' => $token,
                'product_ids' => $productIds,
                'id' => $orderId
            ];
            $encodedData = json_encode($data);
            $args = [
                'method' => 'POST',
                'body' => $encodedData,
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ];

            $response = wp_remote_post($url, $args);
            if (is_wp_error($response)) {
                $responseCode = $response->get_error_code();
                $responseMessage = $response->get_error_message();
                $this->logger->alert(
                    'cod network respond by invalid status while pushing new order',
                    [
                        'order.id' => $orderId,
                        'extra.message' => $responseMessage,
                        'response.code' => $responseCode,
                        'trace' => __FILE__
                    ]
                );

                throw new CODN_WP_Error_Exception(
                    $responseMessage,
                    $responseCode
                );
            }

            return $response;
        }
    }
}

