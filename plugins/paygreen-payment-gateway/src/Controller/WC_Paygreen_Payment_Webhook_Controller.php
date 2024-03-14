<?php

namespace Paygreen\Module\Controller;

use Paygreen\Module\Helper\WC_Paygreen_Payment_Notification_Helper;
use Paygreen\Module\Helper\WC_Paygreen_Payment_Order_Helper;
use Paygreen\Module\Helper\WC_Paygreen_Payment_Payment_Order_Helper;
use Paygreen\Module\WC_Paygreen_Payment_Api;
use Paygreen\Module\WC_Paygreen_Payment_Logger;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * WC_Paygreen_Payment_Webhook_Controller class.
 *
 * Handles webhooks from Paygreen on orders that are not immediately chargeable.
 */
class WC_Paygreen_Payment_Webhook_Controller {
    const VALIDATION_SUCCEEDED = 'validation_succeeded';
    const VALIDATION_FAILED_EMPTY_HEADERS = 'empty_headers';
    const VALIDATION_FAILED_EMPTY_BODY = 'empty_body';
    const VALIDATION_FAILED_SIGNATURE_MISMATCH = 'signature_mismatch';

    private $settings;

    public function __construct() {
        $this->settings = get_option('woocommerce_paygreen_payment_settings');
        add_action('woocommerce_api_wc_paygreen_payment_webhook_controller', [$this, 'process']);
    }

    /**
     * Process incoming requests for Paygreen Webhook data and process them.
     *
     * @since 0.0.0
     */
    public function process() {
        try {
            if (!isset($_SERVER['REQUEST_METHOD'])
                || ('POST' !== $_SERVER['REQUEST_METHOD'])
                || !isset($_GET['wc-api'])
                || ('wc_paygreen_payment_webhook_controller' !== $_GET['wc-api'])
            ) {
                WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Webhook_Controller::process - Invalid data');

                status_header(200);
                exit;
            }

            $request_body = file_get_contents('php://input');
            $request_headers = array_change_key_case($this->get_request_headers(), CASE_UPPER);

            WC_Paygreen_Payment_Logger::info('WC_Paygreen_Payment_Webhook_Controller::process - Paygreen incoming webhook : ' . $request_body);

            // Validate it to make sure it is legit.
            $validation_result = $this->check_webhook_authenticity($request_headers, $request_body);

            if (self::VALIDATION_SUCCEEDED === $validation_result) {
                $result = $this->process_webhook($request_body);
                status_header($result);
            } else {
                WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Webhook_Controller::process - Invalid webhook detected : ' . $validation_result);
                status_header(400);
            }
        } catch (\Exception $exception) {
            WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Webhook_Controller:process - Exception - ' . preg_replace("/\n/", '<br>', (string) $exception->getMessage() . '<br>' . $exception->getTraceAsString()));
            status_header(400);
        }

        exit;
    }

    /**
     * Get the incoming request headers. Some servers are not using
     * Apache and "getallheaders()" will not work so we may need to
     * build our own headers.
     *
     * @since 0.0.0
     */
    private function get_request_headers() {
        if (!function_exists('getallheaders')) {
            $headers = [];

            foreach ($_SERVER as $name => $value) {
                if ('HTTP_' === substr($name, 0, 5)) {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }

            return $headers;
        } else {
            return getallheaders();
        }
    }

    /**
     * Verify the incoming webhook notification to make sure it is legit.
     *
     * @since 0.0.0
     * @param array $request_headers The request headers from Paygreen.
     * @param string $request_body    The request body from Paygreen.
     * @return string The validation result (e.g. self::VALIDATION_SUCCEEDED )
     */
    private function check_webhook_authenticity($request_headers, $request_body) {
        if (empty($request_headers)) {
            return self::VALIDATION_FAILED_EMPTY_HEADERS;
        }
        if (empty($request_body)) {
            return self::VALIDATION_FAILED_EMPTY_BODY;
        }

        // Generate the expected signature.
        $expected_signature = base64_encode(hash_hmac('sha256', $request_body, $this->settings['listener_hmac_key'], true));

        // Check if the expected signature is present.
        if ($expected_signature !== $request_headers['SIGNATURE']) {
            return self::VALIDATION_FAILED_SIGNATURE_MISMATCH;
        }

        return self::VALIDATION_SUCCEEDED;
    }

    /**
     * Process the incoming webhook.
     *
     * @since 0.0.0
     * @param string $request_body
     * @return int
     */
    private function process_webhook($request_body) {
        $notification = json_decode($request_body, true);

        if (array_key_exists('id', $notification) && !empty($notification['id'])) {
            try {
                $result = WC_Paygreen_Payment_Notification_Helper::process($notification);
            } catch (\Exception $exception) {
                WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Webhook_Controller::process_webhook - Exception - ' . preg_replace("/\n/", '<br>', (string) $exception->getMessage() . '<br>' . $exception->getTraceAsString()));

                return 400;
            }

        } else {
            WC_Paygreen_Payment_Logger::error('WC_Paygreen_Payment_Webhook_Controller::process_webhook - Missing notification id');

            return 200;
        }

        return $result;
    }
}