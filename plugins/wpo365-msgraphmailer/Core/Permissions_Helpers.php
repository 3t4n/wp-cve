<?php

namespace Wpo\Core;

use \Wpo\Core\WordPress_Helpers;
use \Wpo\Services\Log_Service;
use \Wpo\Services\Options_Service;
use \Wpo\Services\User_Service;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Core\Permissions_Helpers')) {

    class Permissions_Helpers
    {

        /**
         * @since 7.12
         */
        public static function user_is_admin($user)
        {

            if ($user instanceof \WP_User) {
                return \in_array('administrator', $user->roles) || is_super_admin($user->ID);
            }

            return false;
        }

        /**
         * Returns true when a user is allowed to change the password
         *
         * @since   1.0
         * @return  void
         * 
         * @return boolean true when a user is allowed to change the password otherwise false
         */
        public static function show_password_fields($show, $user)
        {

            return !self::block_password_update($user->ID);
        }

        /**
         * Returns true when a user is allowed to change the password
         * 
         * @since 1.5
         * 
         * @param boolean  $allow whether allowed or not
         * @param int      $user_id id of the user for which the action is triggered
         * 
         * @return boolean true when a user is allowed to change the password otherwise false
         */
        public static function allow_password_reset($allow, $user_id)
        {
            return !self::block_password_update($user_id);
        }

        /**
         * Helper method to determin whether a user is allowed to change the password
         * 
         * @since 1.5
         * 
         * @param int   $user_id id of the user for which the action is triggered
         * 
         * @return boolean true when a user is not allowed to change the password otherwise false
         */
        private static function block_password_update($user_id)
        {
            $block_password_change = Options_Service::get_global_boolean_var('block_password_change');

            if (!$block_password_change) {
                Log_Service::write_log('DEBUG', __METHOD__ . ' -> Not blocking password update');
                return false;
            }

            $use_customers_tenants = Options_Service::get_global_boolean_var('use_b2c') || Options_Service::get_global_boolean_var('use_ciam');
            $wp_usr = get_user_by('ID', intval($user_id));

            // Limit the blocking of password update only for O365 users
            return ($use_customers_tenants || User_Service::user_is_o365_user($user_id) === User_Service::IS_O365_USER) && !self::user_is_admin($wp_usr) ? true : false;
        }

        /**
         * Prevents users who cannot create new users to change their email address
         *
         * @since   1.0
         * @param   array   errors => Existing errors ( from Wordpress )
         * @param   bool    update => true when updating an existing user otherwise false
         * @param   WPUser  usr_new => Updated user
         * @return  void
         */
        public static function prevent_email_change($user_id)
        {

            // Don't block as per global settings configuration
            if (false === Options_Service::get_global_boolean_var('block_email_change')) {
                return;
            }

            $use_customers_tenants = Options_Service::get_global_boolean_var('use_b2c') || Options_Service::get_global_boolean_var('use_ciam');

            if (!$use_customers_tenants && User_Service::user_is_o365_user($user_id) !== User_Service::IS_O365_USER) {
                return;
            }

            $usr_old = get_user_by('ID', intval($user_id));

            if ($usr_old === false) {
                return;
            }

            // At this point the user is an O365 user and email change should be blocked as per config
            if (isset($_POST['email']) && $_POST['email'] != $usr_old->user_email) {

                // Prevent update
                $_POST['email'] = $usr_old->user_email;

                add_action('user_profile_update_errors', function ($errors) {
                    $errors->add('email_update_error', __('Updating your email address is currently not allowed', 'wpo365-login'));
                });
            }
        }

        /**
         * Quick check whether the requested scope e.g. api.yammer.com requires delegated access.
         * 
         * @since   17.0
         * 
         * @param   string      $scope  The scope the requested access must be valid for.
         * @return  boolean             True if delegated access is required for the scope provide.
         */
        public static function must_use_delegate_access_for_scope($scope)
        {
            return (false !== WordPress_Helpers::stripos($scope, 'api.yammer.com') ||
                false !== WordPress_Helpers::stripos($scope, '.sharepoint.com') ||
                (false === WordPress_Helpers::stripos($scope, 'user.read.all') && false !== WordPress_Helpers::stripos($scope, 'user.read'))
            );
        }
    }
}
