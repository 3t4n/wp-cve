<?php

namespace Wpo\Core;

use WP_Site_Query;
use \Wpo\Core\WordPress_Helpers;

use \Wpo\Services\Options_Service;
use \Wpo\Services\Log_Service;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Core\Wpmu_Helpers')) {

    class Wpmu_Helpers
    {

        /**
         * Helper to get the global or local transient based on the
         * WPMU configuration.
         * 
         * @since 9.2
         * 
         * @return mixed Returns the value of transient or false if not found
         */
        public static function mu_get_transient($name)
        {

            if (!is_multisite() || (Options_Service::mu_use_subsite_options() && !self::mu_is_network_admin())) {
                return get_transient($name);
            }

            return get_site_transient($name);
        }

        /**
         * Helper to set the global or local transient based on the
         * WPMU configuration.
         * 
         * @since 9.2
         * 
         * @param $name string Name of transient
         * @param $value mixed Value of transient
         * @param $duration int Time transient should be cached in seconds
         * 
         * @return void
         */
        public static function mu_set_transient($name, $value, $duration = 0)
        {

            if (!is_multisite() || (Options_Service::mu_use_subsite_options() && !self::mu_is_network_admin())) {
                set_transient($name, $value, $duration);
            } else {
                set_site_transient($name, $value, $duration);
            }
        }

        /**
         * Helper to delete the global or local transient based on the
         * WPMU configuration.
         * 
         * @since 10.9
         * 
         * @param $name string Name of transient
         * 
         * @return void
         */
        public static function mu_delete_transient($name)
        {

            if (!is_multisite() || (Options_Service::mu_use_subsite_options() && !self::mu_is_network_admin())) {
                delete_transient($name);
            } else {
                delete_site_transient($name);
            }
        }

        /**
         * Helper to check if the current request is for a network admin page and it includes a simple 
         * check if the request is made from an AJAX call.
         * 
         * @since   11.18
         * 
         * @return  boolean  True if the request is for a network admin page other false.
         */
        public static function mu_is_network_admin()
        {
            return (is_network_admin() || true === $GLOBALS['WPO_CONFIG']['ina']);
        }

        /**
         * Helper to switch the current blog from the main site to a subsite in case
         * of a multisite installation (shared scenario) when the user is redirected 
         * back to the main site whereas the state URL indicates that the target is
         * a subsite.
         * 
         * @since   11.0
         * 
         * @param   $state_url  string  The (Relay) state URL
         * 
         * @return  void
         */
        public static function switch_blog($state_url)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            if (is_multisite() && !empty($state_url)) {
                $redirect_url = Options_Service::get_aad_option('redirect_url');
                $redirect_host = parse_url($redirect_url, PHP_URL_HOST);
                $state_host = parse_url($state_url, PHP_URL_HOST);
                $state_path = '/';
                $redirect_path = '/';

                if (!is_subdomain_install()) {
                    $redirect_path = parse_url($redirect_url, PHP_URL_PATH);
                    $state_path = parse_url($state_url, PHP_URL_PATH);
                }

                $state_blog_id = self::get_blog_id_from_host_and_path($state_host, $state_path);
                $redirect_blog_id = self::get_blog_id_from_host_and_path($redirect_host, $redirect_path);

                Log_Service::write_log('DEBUG', __METHOD__ . " -> Detected WPMU with state context (path: $state_path - ID: $state_blog_id) and AAD redirect context (path: $redirect_path - ID: $redirect_blog_id)");

                if ($state_blog_id !== $redirect_blog_id) {
                    switch_to_blog($state_blog_id);
                    $GLOBALS['WPO_CONFIG']['url_info']['wp_site_url'] = get_option('home');
                }
            }
        }

        /**
         * Helper to try and search for a matching blog by itteratively removing the last segment from the path.
         * 
         * @since   16.0
         * 
         * @param   string  $host   The domain e.g. www.your-site.com
         * @param   string  $path   The path starting with a slash
         * 
         * @return  int     The blog ID or 0 if not found
         */
        public static function get_blog_id_from_host_and_path($host, $path)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            $blog_id = get_blog_id_from_url($host, $path);

            if (!empty($blog_id)) {
                return $blog_id;
            }

            $path = WordPress_Helpers::rtrim($path, '/');
            $path = WordPress_Helpers::ltrim($path, '/');
            $segments = explode('/', $path);
            $segments[] = 'placeholder'; // Add empty string to start with full URL when popping elements from the end

            while (NULL != ($last_element = array_pop($segments))) {
                $path = '/' . implode('/', $segments);

                if (strlen($path) > 1) {
                    $path = $path . '/';
                }

                $blog_id = get_blog_id_from_url($host, $path);

                if ($blog_id > 0) {
                    return $blog_id;
                }
            }

            return 0;
        }
    }
}
