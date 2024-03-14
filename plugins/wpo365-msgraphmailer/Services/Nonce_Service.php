<?php

namespace Wpo\Services;

use Wpo\Core\Wpmu_Helpers;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Services\Nonce_Service')) {

    class Nonce_Service
    {
        /**
         * Creates a nonce to ensure the request for an Azure AD token 
         * originates from the current server.
         * 
         * @since   21.6
         * 
         * @return string 
         */
        public static function create_nonce()
        {
            $nonce_stack = Wpmu_Helpers::mu_get_transient('wpo365_nonces');

            if (empty($nonce_stack)) {
                $nonce_stack = array();
            }

            $nonce = uniqid();
            $nonce_stack[] = $nonce;

            // When the stack grows to 200 it's downsized to 150
            if (sizeof($nonce_stack) > 200) {
                array_splice($nonce_stack, 0, 50);
            }

            Wpmu_Helpers::mu_set_transient('wpo365_nonces', $nonce_stack, 300);

            return $nonce;
        }

        /**
         * Verifies the nonce that Microsoft returns together with the requested token.
         * 
         * @param mixed $nonce 
         * @return bool 
         */
        public static function verify_nonce($nonce)
        {
            $nonce_stack = Wpmu_Helpers::mu_get_transient('wpo365_nonces');

            if (empty($nonce_stack)) {
                Log_Service::write_log('WARN', sprintf('%s -> Empty nonce stack', __METHOD__));
                return false;
            }

            $index = array_search($nonce, $nonce_stack);

            if (false === $index) {
                Log_Service::write_log('WARN', sprintf('%s -> Nonce %s not found %s', __METHOD__, $nonce, json_encode($nonce_stack)));
                return false;
            }

            array_splice($nonce_stack, $index, 1);
            Wpmu_Helpers::mu_set_transient('wpo365_nonces', $nonce_stack, 300);

            return true;
        }
    }
}
