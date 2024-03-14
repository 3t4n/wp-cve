<?php

/**
 * This class handles authorization issues
 *
 * @package   WooCommerce_iZettle_Integration
 * @author    BjornTech <info@bjorntech.com>
 * @license   GPL-3.0
 * @link      http://bjorntech.com
 * @copyright 2017-2019 BjornTech - BjornTech AB
 */

defined('ABSPATH') || exit;

if (!class_exists('WC_iZettle_Service_Object', false)) {
    class WC_iZettle_Service_Object extends IZ_Integration_API_Transaction_V2
    {
        const ADM_URL = 'bjorntech.net/v2';

        private static $instance = null;
        private static $access_token = null;
        private static $webhook_signing_key = null;
        private static $webhook_status = null;
        private static $expires_in = null;
        private static $organization_uuid = null;
        private static $valid_to = null;
        private static $last_synced = null;
        private static $is_trial = null;

        public function get_webhook_status()
        {
            if (self::$webhook_status === null) {
                self::$webhook_status = get_option('izettle_webhook_status');
            }
            return self::$webhook_status;
        }

        public function set_webhook_status($webhook_status)
        {
            self::$webhook_status = $webhook_status;
            update_option('izettle_webhook_status', self::$webhook_status);
        }

        public function get_webhook_signing_key()
        {
            if (self::$webhook_signing_key === null) {
                self::$webhook_signing_key = get_option('izettle_webhook_signing_key');
            }
            return self::$webhook_signing_key;
        }

        public function set_webhook_signing_key($webhook_signing_key)
        {
            self::$webhook_signing_key = $webhook_signing_key;
            update_option('izettle_webhook_signing_key', self::$webhook_signing_key);
        }

        public function get_access_token()
        {
            if (self::$access_token === null) {
                self::$access_token = get_option('izettle_access_token');
            }
            return self::$access_token;
        }

        public function set_access_token($access_token)
        {
            self::$access_token = $access_token;
            update_option('izettle_access_token', self::$access_token);
        }

        public function get_expires_in()
        {
            if (self::$expires_in === null) {
                self::$expires_in = get_option('izettle_expires_in');
            }
            return self::$expires_in;
        }

        public function set_expires_in($expires_in)
        {
            self::$expires_in = intval($expires_in / 1000);
            update_option('izettle_expires_in', self::$expires_in);
        }

        public function get_organization_uuid()
        {
            if (self::$organization_uuid === null) {
                self::$organization_uuid = get_option('izettle_organization_uuid');
            }
            return self::$organization_uuid;
        }

        public function set_organization_uuid($organization_uuid)
        {
            self::$organization_uuid = $organization_uuid;
            update_option('izettle_organization_uuid', self::$organization_uuid);
        }

        public function get_valid_to()
        {
            if (self::$valid_to === null) {
                self::$valid_to = get_option('izettle_valid_to');
            }
            return self::$valid_to;
        }

        public function set_valid_to($valid_to)
        {
            self::$valid_to = intval($valid_to / 1000);
            update_option('izettle_valid_to', self::$valid_to);
        }

        public function get_last_synced()
        {
            if (self::$last_synced === null) {
                self::$last_synced = get_option('izettle_last_synced');
            }
            return self::$last_synced;
        }

        public function set_last_synced($last_synced)
        {
            self::$last_synced = intval($last_synced / 1000);
            update_option('izettle_last_synced', self::$last_synced);
        }

        public function get_is_trial()
        {
            if (self::$is_trial === null) {
                self::$is_trial = get_option('izettle_is_trial');
            }
            return self::$is_trial;
        }

        public function set_is_trial($is_trial)
        {
            self::$is_trial = $is_trial;
            update_option('izettle_is_trial', self::$is_trial);
        }

        public function get_adm_url()
        {
            return ($adm_url = get_option('izettle_alternate_service_url')) != '' ? $adm_url : self::ADM_URL;
        }

        public static function instance()
        {
            if (null === self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

    }
}

function izettle_api()
{
    return WC_iZettle_Service_Object::instance();
}