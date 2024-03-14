<?php

namespace PargoWp\Includes;

use Exception;
use JsonException;

class Analytics
{

	/**
	 * @param $journey
	 * @param $event_type
	 * @param $event_name
	 *
	 * @return array|void|\WP_Error
	 * @throws JsonException
	 */
    public static function submit($journey, $event_type, $event_name)
    {
        $pargo_shipping_method = new Pargo_Wp_Shipping_Method();
        $enabled = $pargo_shipping_method->get_option('pargo_usage_tracking_enabled');

        if ($enabled === 'false') {
            return;
        }

        $endpoint_url = 'https://analytics.monitoring.pargo.co/api/v1/analytics/ingest';

        // Get the session token or generate a unique session token if none is provided
        $session_token = wp_get_session_token();
        if (empty($session_token)) {
            if (!session_id()) {
                session_start();
            }
            $session_token = 'session_' . session_id(); // Unique identifier for the current session
        }

        $request_params = array(
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode(array(
                'app' => 'woocommerce',
                'session' => $session_token,
                'event' => array(
                    'env' => strpos($pargo_shipping_method->get_option('pargo_url_endpoint', 'staging'), 'staging') !== false ? 'staging' : 'production',
                    'store' => get_bloginfo('name'),
                    'agent' => $_SERVER['HTTP_USER_AGENT'],
                    'host' => $_SERVER['HTTP_HOST'],
                    'journey' => $journey,
                    'event_type' => $event_type,
                    'name' => $event_name,
                    'plugin_version' => PARGO_VERSION,
                    'wordpress_theme' => wp_get_theme()->get('Name'),
                    'wordpress_version' => get_bloginfo('version'),
                    'country' => WC()->countries->get_base_country()
                ),
            ), JSON_THROW_ON_ERROR),
        );
        try {
            return wp_remote_post($endpoint_url, $request_params);
        } catch (Exception $e) {
            error_log($e);
        }
	}
}
