<?php
/**
 * IZ_Integration_API_Transaction
 *
 * @class           IZ_Integration_API_Transaction
 * @since           1.0.0
 * @package         WC_iZettle_Integration
 * @category        Class
 * @author          bjorntech
 */

defined('ABSPATH') || exit;

if (!class_exists('IZ_Integration_API_Transaction', false)) {

    class IZ_Integration_API_Transaction extends IZ_Integration_API
    {

        public function connect_to_service()
        {
            $now = time();
            if ($this->get_organization_uuid()) {
                if ($now >= $this->get_expires_in()) {
                    try {

                        $service_payload = array(
                            'client_id' => WC_IZ()->client_id,
                            'user_email' => get_option('izettle_username'),
                            'user_website' => ($alternate_url = get_option('bjorntech_alternate_webhook_url')) ? $alternate_url : get_site_url(),
                            'client_version' => WC_IZ()->version,
                            'organization_uuid' => $this->get_organization_uuid(),
                            'purchases_sync' => get_option('izettle_purchase_sync_model'),
                            'connection_status' => apply_filters('izettle_connection_status', ''),
                            'barcode_status' => get_option('izettle_product_update_barcode'),
                            'php_version' => phpversion(),
                            'wc_version' => WC_Zettle_Helper::wc_version(),
                            'purchase_sync_function' => wc_string_to_bool(get_option('zettle_enable_purchase_processing')) ? 'purchases' : '',
                            'when_changed_in_izettle' => get_option('izettle_when_changed_in_izettle'),
                            'webhook_destination' => ($alternate_url = get_option('bjorntech_alternate_webhook_url')) ? $this->construct_alternate_webhook_url($alternate_url, 'izettle/webhook') : get_rest_url(null, 'izettle/webhook'),
                        );

                        $response = $this->post(
                            'token',
                            $service_payload,
                            false,
                            array('Content-Type' => 'application/x-www-form-urlencoded'),
                            'zettle'
                        );

                        if (isset($response->access_token)) {
                            $local_expiry = ($response->expires_in_seconds + $now) * 1000;
                            $this->set_expires_in($local_expiry);
                            $this->set_valid_to($response->valid_to);
                            $this->set_last_synced($response->last_synced);
                            $this->set_is_trial($response->is_trial);
                            $this->set_access_token($response->access_token);
                            $this->set_webhook_signing_key($response->webhook_signing_key);
                            $this->set_webhook_status($response->webhook_status);
                            WC_IZ()->logger->add(sprintf('Webhook status %s', $response->webhook_status));
                            WC_IZ()->logger->add(sprintf('Successfully got access token %s', $response->access_token));
                            do_action('izettle_connection_success');
                        }

                    } catch (IZ_Integration_API_Exception $e) {
                        $error_code = $e->getCode();
                        $message = 'Error when retreiving access token';
                        if ($error_code == 403) {
                            $message = 'Client account not allowed to connect';
                        } elseif ($error_code == 401) {
                            $message = 'Client not authorized';
                        }
                        do_action('izettle_connection_fail', 'Got error ' . $error_code);
                        throw new IZ_Integration_API_Exception($message, $error_code, $e);
                    }

                }
            } else {
                throw new IZ_Integration_API_Exception('Trying to sync without authorization');
            }
        }

        public function construct_alternate_webhook_url($alternate_webhook_url, $rest_path)
        {
            $url = $alternate_webhook_url . '/';

            if (!is_null($rest_path)) {
                $url .= 'wp-json/' . $rest_path;
            }

            return $url;

        }

        //FIX

        public function start_tracking_inventory($product_uuid)
        {
            $this->connect_to_service();
            $response = $this->post(
                'organizations/' . $this->get_organization_uuid() . '/inventory',
                array(
                    'productUuid' => $product_uuid,
                ),
                true,
                true,
                'inventory'
            );
            return $response;
        }

        public function get_inventory($location_uuid, $product_uuid)
        {
            $this->connect_to_service();
            $response = $this->get(
                'organizations/' . $this->get_organization_uuid() . '/inventory/locations/' . $location_uuid . '/products/' . $product_uuid,
                true,
                'inventory'
            );
            return $response;
        }

        public function get_locations()
        {
            $this->connect_to_service();
            $response = $this->get(
                'organizations/' . $this->get_organization_uuid() . '/locations',
                true,
                'inventory'
            );
            return $response;
        }

        public function get_location_content($location, $product_uuid = '')
        {
            $this->connect_to_service();
            $response = $this->get(
                'organizations/' . $this->get_organization_uuid() . '/inventory/locations',
                true,
                'inventory',
                array(
                    'type' => $location,
                )
            );
            return $response;
        }

        public function set_inventory($change)
        {
            $this->connect_to_service();
            $response = $this->put(
                'organizations/self/inventory',
                $change,
                true,
                true,
                'inventory'
            );
            return $response;
        }

        //FIX

        public function get_products($product_uuid = "")
        {
            $this->connect_to_service();
            $response = $this->get(
                'organizations/' . $this->get_organization_uuid() . '/products/' . $product_uuid,
                true,
                'products'
            );
            return $response;
        }

        public function set_subscription($subscription)
        {
            $this->connect_to_service();
            $response = $this->post(
                'organizations/' . $this->get_organization_uuid() . '/subscriptions',
                $subscription,
                true,
                true,
                'pusher'
            );
            return $response;
        }

        public function get_subscription()
        {
            $this->connect_to_service();
            $response = $this->get(
                'organizations/' . $this->get_organization_uuid() . '/subscriptions',
                true,
                'pusher'
            );
            return $response;
        }

        public function remove_subscription($uuid)
        {
            $this->connect_to_service();
            $response = $this->delete(
                'organizations/' . $this->get_organization_uuid() . '/subscriptions/uuid/' . $uuid,
                array(),
                true,
                'pusher'
            );
            return $response;
        }

        public function get_purchases($params = array())
        {
            $this->connect_to_service();
            $response = $this->get(
                'purchases/v2',
                true,
                'purchase',
                $params
            );
            return $response;
        }

        public function get_purchase($uuid = '')
        {
            $this->connect_to_service();
            $response = $this->get(
                'purchase/v2/' . $uuid,
                true,
                'purchase'
            );
            return $response;
        }

        public function get_liquid_transactions($params = array())
        {
            $this->connect_to_service();
            $response = $this->get(
                'organizations/us/accounts/LIQUID/transactions',
                true,
                'finance',
                $params
            );
            return $response;
        }

        /**
         * Get liquid transactions from Zettle
         * @param mixed $params
         * @return object
         */
        public function get_liquid_transactions_v2($params = array())
        {
            $this->connect_to_service();
            $response = $this->get(
                'v2/accounts/LIQUID/transactions',
                true,
                'finance',
                $params
            );
            return $response;
        }

        public function get_payout_info($params = array())
        {
            $this->connect_to_service();
            $response = $this->get(
                'organizations/us/payout-info',
                true,
                'finance',
                $params
            );
            return $response;
        }

        /**
         * Get info on payout transactions from Zettle
         * @param mixed $params
         * @return object
         */
        public function get_payout_info_v2($params = array())
        {
            $this->connect_to_service();
            $response = $this->get(
                'v2/payout-info',
                true,
                'finance',
                $params
            );
            return $response;
        }

        public function get_categories()
        {
            $this->connect_to_service();
            $response = $this->get(
                'organizations/' . $this->get_organization_uuid() . '/categories/v2',
                true,
                'products'
            );
            return $response;
        }

        public function create_category($category)
        {
            $this->connect_to_service();
            $response = $this->post(
                'organizations/' . $this->get_organization_uuid() . '/categories/v2',
                $category,
                true,
                true,
                'products'
            );

            return $response;
        }

        public function create_product($product)
        {
            $this->connect_to_service();

            $response = $this->post(
                'organizations/self/products',
                $product,
                true,
                true,
                'products'
            );

            return $response;
        }

        public function update_product($product, $uuid, $etag)
        {
            $this->connect_to_service();
            $response = $this->put(
                'organizations/self/products/v2/' . $uuid,
                $product,
                true,
                array(
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->get_access_token(),
                    'If-Match' => '"' . $etag . '"',
                ),
                'products'
            );
            return $response;
        }

        public function delete_product($uuid)
        {
            $this->connect_to_service();
            $response = $this->delete(
                'organizations/' . $this->get_organization_uuid() . '/products/' . $uuid,
                array(),
                true,
                'products'
            );
            return $response;
        }

        // Image format is one of BMP, GIF, JPEG, PNG, TIFF
        public function create_image($imageurl, $imagedata = null, $image_format = 'JPEG')
        {
            $payload = array(
                'imageFormat' => $image_format,
                'imageData' => $imagedata,
                'imageUrl' => $imageurl,
            );

            $this->connect_to_service();
            $response = $this->post(
                'v2/images/organizations/' . $this->get_organization_uuid() . '/products',
                $payload,
                true,
                true,
                'image'
            );
            return $response;
        }

        public function get_tax_rates($uuid = "")
        {
            $this->connect_to_service();
            $response = $this->get(
                'v1/taxes/' . $uuid,
                true,
                'products'
            );
            return $response;
        }

        public function get_tax_settings()
        {
            $this->connect_to_service();
            $response = $this->get(
                'v1/taxes/settings',
                true,
                'products'
            );
            return $response;
        }

        public function create_tax_rates($taxrates)
        {
            $this->connect_to_service();
            $response = $this->post(
                'v1/taxes/',
                $taxrates,
                true,
                true,
                'products'
            );
            return $response;
        }

    }
}
