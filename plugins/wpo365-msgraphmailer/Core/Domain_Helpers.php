<?php

namespace Wpo\Core;

use \Wpo\Services\Log_Service;
use \Wpo\Services\Options_Service;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Core\Domain_Helpers')) {

    class Domain_Helpers
    {

        /**
         * Gets the domain (host) part of an email address.
         * 
         * @since 3.1
         * 
         * @param   string  $email_address  email address to analyze
         * @return  string  Returns the email address' host part or an empty string if
         *                  the email address appears to be invalid
         */
        public static function get_smtp_domain_from_email_address($email_address)
        {
            $smpt_domain = '';

            if (filter_var(trim($email_address), FILTER_VALIDATE_EMAIL) !== false) {
                $smpt_domain = strtolower(trim(substr($email_address, strrpos($email_address, '@')  + 1)));
            }

            return $smpt_domain;
        }

        /**
         * Checks a user's smtp domain against the configured custom and default domains
         * 
         * @since 4.0
         * 
         * @return boolean true if a match is found otherwise false
         */
        public static function is_tenant_domain($email_domain)
        {
            $custom_domain = array_change_key_case(array_flip(Options_Service::get_global_list_var('custom_domain')));
            $default_domain = Options_Service::get_global_string_var('default_domain');

            if (array_key_exists($email_domain, $custom_domain) || strtolower(trim($default_domain)) == $email_domain) {
                return true;
            }

            return false;
        }
    }
}
