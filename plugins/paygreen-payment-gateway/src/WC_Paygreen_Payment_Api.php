<?php

namespace Paygreen\Module;

use Paygreen\Module\Exception\WC_Paygreen_Payment_Exception;
use Paygreen\Module\Exception\WC_Paygreen_Payment_Forbidden_Access_Exception;
use Paygreen\Sdk\Payment\V3\Client;
use Paygreen\Sdk\Payment\V3\Environment;

if (!defined('ABSPATH')) {
    exit;
}

class WC_Paygreen_Payment_Api
{
    /**
     * @return Client
     * @throws WC_Paygreen_Payment_Exception
     */
    public static function get_paygreen_client($curl_options = array(), $refresh_token = false)
    {
        $settings = get_option('woocommerce_paygreen_payment_settings');

        $curl_client =  new \Http\Client\Curl\Client(null, null, $curl_options);
        $composer_json = json_decode(file_get_contents(__DIR__ . '/../composer.json'));

        $environment = new Environment($settings['shop_id'], $settings['secret_key'], $settings['environment']);
        $environment->setApplicationName('wordpress-payment');
        $environment->setApplicationVersion($composer_json->version);
        $environment->setCmsName('wordpress-woocommerce');
        $environment->setCmsVersion($GLOBALS['wp_version'] . '-' . WC_VERSION);

        $client = new Client($curl_client, $environment);
        $bearer_token = self::get_bearer_token($client, $refresh_token);

        if ($bearer_token === null) {
            throw new WC_Paygreen_Payment_Exception('missing-bearer-token', __('There was a problem connecting to the PayGreen API endpoint.', 'paygreen-payment-gateway'));
        }

        $client->setBearer($bearer_token);

        return $client;
    }

    /**
     * Get and save a new bearer token.
     *
     * @param Client $client
     * @return string
     * @throws WC_Paygreen_Payment_Exception
     */
    public static function authenticate($client)
    {
        $settings = get_option('woocommerce_paygreen_payment_settings');
        $bearer_token = null;

        try {
            $response = $client->authenticate();
        }  catch (\Exception $e) {
            throw new WC_Paygreen_Payment_Exception($e->getMessage(), __('Failed to authenticate through the PayGreen API endpoint.', 'paygreen-payment-gateway'));
        }

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents())->data;
            $decoded = \Paygreen\Sdk\Payment\V3\Utils::decodeJWT($data->token);

            $settings['token'] = $data->token;
            $settings['token_expire_at'] = $decoded->exp;
            $bearer_token = $data->token;
        } elseif ($response->getStatusCode() === 403 && $settings['environment'] === Environment::ENVIRONMENT_RECETTE) {
            throw new WC_Paygreen_Payment_Forbidden_Access_Exception('missing-vpn-connection', __('Authentication failed. Please check your vpn connection.', 'paygreen-payment-gateway'));
        } else {
            $settings['token'] = 0;
            $settings['token_expire_at'] = 0;
        }
        update_option('woocommerce_paygreen_payment_settings', $settings);

        return $bearer_token;
    }

    /**
     * @return bool
     */
    public static function is_authenticated()
    {
        $settings = get_option('woocommerce_paygreen_payment_settings');

        $timestamp = (new \DateTime)->getTimestamp();

        if (isset($settings['token'])
            && isset($settings['token_expire_at'])
            && $settings['token'] !== 0
            && $timestamp < $settings['token_expire_at'])
        {
            return true;
        }

        return false;
    }

    /**
     * @param int $status_code
     * @return bool
     */
    public static function is_valid_response($status_code)
    {
        return $status_code >= 200 && $status_code <= 299;
    }

    /**
     * @return bool
     * @throws WC_Paygreen_Payment_Exception
     */
    public static function has_active_payment_methods()
    {
        $settings = get_option('woocommerce_paygreen_payment_settings');
        $paygreen_shop_id = $settings['shop_id'];

        $client = self::get_paygreen_client();
        $response = $client->listPaymentConfig($paygreen_shop_id);

        $has_one_enabled_payment_methods = false;

        if ($response->getStatusCode() === 200) {
            $paymentConfigs = json_decode($response->getBody()->getContents(), true)['data'];

            foreach ($paymentConfigs as $paymentConfig) {
                if ($paymentConfig['status'] === 'payment_config.enabled') {
                    WC_Paygreen_Payment_Logger::debug('WC_Paygreen_Payment_Api::has_active_payment_methods - ' . $paygreen_shop_id . ' - Active payment method found : ' . $paymentConfig['platform']);

                    $has_one_enabled_payment_methods = true;
                }
            }
        }

        $settings['has_active_payment_methods'] = $has_one_enabled_payment_methods;
        update_option('woocommerce_paygreen_payment_settings', $settings);

        if (!$has_one_enabled_payment_methods) {
            WC_Paygreen_Payment_Logger::debug('WC_Paygreen_Payment_Api::has_active_payment_methods - ' . $paygreen_shop_id . ' - No active payment methods found');
        }

        return $has_one_enabled_payment_methods;
    }

    /**
     * @param Client $client
     * @param bool $refresh_token
     * @return string
     * @throws WC_Paygreen_Payment_Exception
     */
    private static function get_bearer_token($client, $refresh_token = false)
    {
        $settings = get_option('woocommerce_paygreen_payment_settings');

        if (!$refresh_token && self::is_authenticated()) {
            $bearer_token = $settings['token'];
        } else {
            $bearer_token = self::authenticate($client);
        }

        return $bearer_token;
    }
}