<?php

/**
 * Author: Alin Marcu
 * Author URI: https://deconf.com
 * Copyright 2013 Alin Marcu
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Modified by Joomunited
 */

/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');

if (!class_exists('WpmsGaTools')) {

    /**
     * Class WpmsGaTools
     */
    class WpmsGaTools
    {

        /**
         * Init google analytics client
         *
         * @param string $clientId     Google client ID
         * @param string $clientSecret Google client secret
         *
         * @return mixed
         */
        public static function initClient($clientId, $clientSecret)
        {
            require_once WPMETASEO_PLUGIN_DIR . 'inc/google_analytics/wpmsgapi.php';
            require_once WPMETASEO_PLUGIN_DIR . 'inc/lib/google-api/vendor/autoload.php';

            $client = new WPMSGoogle\Client();
            $client->setScopes(array(
                'https://www.googleapis.com/auth/analytics.readonly'
            ));
            $client->setAccessType('offline');
            $client->setApprovalPrompt('force');
            $client->setApplicationName('WP Meta SEO');
            // $client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
            $redirectUrl = admin_url('admin.php?page=metaseo_google_analytics&view=wpms_gg_service_data&task=wpms_ga');
            $client->setRedirectUri($redirectUrl);

            $client->setClientId($clientId);
            $client->setClientSecret($clientSecret);

            return $client;
        }

        /**
         * Get google analytics client
         *
         * @param object $client         Google analytics client
         * @param array  $access         Access info to connect
         * @param array  $access_default Access default info to connect
         *
         * @return mixed
         */
        public static function setClient($client, $access, $access_default)
        {
            if (!empty($access['wpmsga_dash_clientid']) && !empty($access['wpmsga_dash_clientsecret'])) {
                $client->setClientId($access['wpmsga_dash_clientid']);
                $client->setClientSecret($access['wpmsga_dash_clientsecret']);
            } else {
                $client->setClientId($access_default[0]);
                $client->setClientSecret($access_default[1]);
            }

            return $client;
        }

        /**
         * Check token expired
         *
         * @param array $token Token
         *
         * @return boolean
         */
        public static function isTokenExpired($token)
        {
            $current = time();
            if ($token['created'] + $token['expires_in'] < $current) {
                return true;
            }
            return false;
        }

        /**
         * Get access token from refresh token
         *
         * @param string $clientId     Client Id
         * @param string $clientSecret Client secret
         * @param array  $token        Token
         *
         * @return array
         */
        public static function getAccessTokenFromRefresh($clientId, $clientSecret, $token)
        {
            if (isset($token['refresh_token'])) {
                $curl_args = array(
                    'user-agent' => null,
                    'timeout' => 90,
                    'sslverify' => false,
                    'method' => 'POST',
                    'cookies' => array(),
                    'body' => array(
                        'client_id' => $clientId,
                        'client_secret' => $clientSecret,
                        'refresh_token' => $token['refresh_token'],
                        'grant_type' => 'refresh_token'
                    )
                );
                $response = wp_remote_request('https://accounts.google.com/o/oauth2/token', $curl_args);

                if (!is_wp_error($response)) {
                    $body = wp_remote_retrieve_body($response);
                    $body = json_decode($body);

                    if (!empty($body->access_token)) {
                        $token['access_token'] = $body->access_token;
                        if (isset($body->refresh_token)) {
                            $token['refresh_token'] = $body->refresh_token;
                        }
                        $token['expires_in'] = $body->expires_in;
                        $token['created'] = time();

                        $google_analytics = get_option('wpms_google_alanytics');
                        $google_analytics['googleCredentials'] = $token;
                        update_option('wpms_google_alanytics', $google_analytics);
                    }
                }
            }

            return $token;
        }

        /**
         * Retrieves all Google Analytics Views with details
         *
         * @param object $service       Google service
         * @param string $access_token  Access token
         * @param object $error_timeout Error timeout
         *
         * @return array
         */
        public static function refreshProfiles($service, $access_token, $error_timeout)
        {
            try {
                $ga_dash_profile_list = array();
                $startindex = 1;
                $totalresults = 65535; // use something big
                while ($startindex < $totalresults) {
                    // Get profile for google analytics 4 properties (GA4)
                    $accounts = $service->management_accounts->listManagementAccounts();
                    $accounts_id = array();
                    $ga4_profile_list = array();
                    $ua_profile_list = array();
                    if (count($accounts) > 0) {
                        foreach ($accounts as $account) {
                            $accounts_id[] = $account->id;
                        }

                        $ga4_properties = array();
                        foreach ($accounts_id as $account_id) {
                            $ga4_properties = array_merge($ga4_properties, self::get_ga4_properties($account_id, $access_token));
                        }

                        if (!empty($ga4_properties)) {
                            $webDataStreams = array();
                            foreach ($ga4_properties as $ga4_property) {
                                $streamList = self::webDataStreamsList(str_replace('properties/', '', $ga4_property->name), $access_token);
                                if (!empty($streamList) && is_array($streamList)) {
                                    $webDataStreams = array_merge($webDataStreams, $streamList);
                                }
                            }

                            foreach ($webDataStreams as $profile) {
                                $profile_name = $profile->name;
                                $profile_name_arr = explode('/', $profile_name);
                                $property_id = $profile_name_arr[1];
                                $webDataStream_id = $profile_name_arr[3];

                                $ga4_profile_list[] = array(
                                    $profile->displayName,
                                    $webDataStream_id, // todo:: check this value
                                    $property_id,
                                    $profile->webStreamData->defaultUri,
                                    'GA4',
                                    '',
                                    '',
                                    '',
                                    $profile->webStreamData->measurementId
                                );
                            }
                        }
                    }

                    // Get profile for google universal analytics (UA)
                    $profiles = $service->management_profiles->listManagementProfiles(
                        '~all',
                        '~all',
                        array(
                            'start-index' => $startindex
                        )
                    );
                    $items = $profiles->getItems();
                    $totalresults = $profiles->getTotalResults();

                    if ($totalresults > 0) {
                        foreach ($items as $profile) {
                            $timetz = new DateTimeZone($profile->getTimezone());
                            $localtime = new DateTime('now', $timetz);
                            $timeshift = strtotime($localtime->format('Y-m-d H:i:s')) - time();
                            $ua_profile_list[] = array(
                                $profile->getName(),
                                $profile->getId(),
                                $profile->getwebPropertyId(),
                                $profile->getwebsiteUrl(),
                                'UA',
                                $timeshift,
                                $profile->getTimezone(),
                                $profile->getDefaultPage(),
                                ''
                            );
                            $startindex++;
                        }
                    }

                    $ga_dash_profile_list = array_merge($ga4_profile_list, $ua_profile_list);
                }

                if (empty($ga_dash_profile_list)) {
                    self::setCache(
                        'last_error',
                        date('Y-m-d H:i:s') . ': No properties were found in this account!',
                        $error_timeout
                    );
                } else {
                    self::deleteCache('last_error');
                }
                return $ga_dash_profile_list;
            } catch (WPMSGoogle\Service\Exception $e) {
                self::setCache(
                    'last_error',
                    date('Y-m-d H:i:s') . ': ' . esc_html('(' . $e->getCode() . ') ' . $e->getMessage()),
                    $error_timeout
                );
                self::setCache(
                    'gapi_errors',
                    $e->getCode(),
                    $error_timeout
                );
                return $ga_dash_profile_list;
            } catch (Exception $e) {
                self::setCache(
                    'last_error',
                    date('Y-m-d H:i:s') . ': ' . esc_html($e),
                    $error_timeout
                );
                return $ga_dash_profile_list;
            }
        }


        /**
         * Get Google analytics 4 properties
         *
         * @param string $account_id   GA4 account ID
         * @param string $access_token Access token
         *
         * @return array
         */
        public static function get_ga4_properties($account_id, $access_token)
        {
            $query_url = 'https://analyticsadmin.googleapis.com/v1alpha/properties';
            $query_url = add_query_arg('access_token', $access_token, $query_url);
            $properties = array();
            $pageToken = null;
            do {
                try {
                    $additional = array();
                    $additional['filter'] = 'parent:accounts/' . $account_id;
                    $additional['pageSize'] = 40;
                    if ($pageToken) {
                        $additional['pageToken'] = $pageToken;
                    }

                    $call_args = array();
                    $call_args['method'] = 'GET';
                    $call_args['body'] = $additional;
                    $response = wp_remote_request($query_url, $call_args);
                    if (!is_wp_error($response)) {
                        $body = wp_remote_retrieve_body($response);
                        $body = json_decode($body);
                    }

                    $items = isset($body->properties) ? $body->properties : array();
                    $properties = array_merge($properties, $items);
                    if (isset($body->nextPageToken)) {
                        $pageToken = $body->nextPageToken;
                    } else {
                        $pageToken = null;
                    }
                } catch (Exception $e) {
                    $pageToken = null;
                }
            } while ($pageToken);

            return $properties;
        }

        /**
         * Retrieve all data stream google analytics 4 property
         *
         * @param string $property_id  GA4 property ID
         * @param string $access_token Access token
         *
         * @return array
         */
        public static function webDataStreamsList($property_id, $access_token)
        {
            $query_url = 'https://analyticsadmin.googleapis.com/v1alpha/properties/' . $property_id . '/dataStreams';
            $query_url = add_query_arg('access_token', $access_token, $query_url);
            $webDataStreams = array();
            $pageToken = null;
            do {
                try {
                    $additional = array();
                    $additional['pageSize'] = 40;
                    if ($pageToken) {
                        $additional['pageToken'] = $pageToken;
                    }

                    $call_args = array();
                    $call_args['method'] = 'GET';
                    $call_args['body'] = $additional;

                    $response = wp_remote_request($query_url, $call_args);
                    if (!is_wp_error($response)) {
                        $body = wp_remote_retrieve_body($response);
                        $body = json_decode($body);
                    }

                    $items = isset($body->dataStreams) ? $body->dataStreams : array();
                    $webDataStreams = array_merge($webDataStreams, $items);
                    if (isset($body->nextPageToken)) {
                        $pageToken = $body->nextPageToken;
                    } else {
                        $pageToken = null;
                    }
                } catch (Exception $e) {
                    $pageToken = null;
                }
            } while ($pageToken);

            return $webDataStreams;
        }

        /**
         * Get selected profile
         *
         * @param array  $profiles List profiles
         * @param string $profile  Selected profile
         *
         * @return boolean
         */
        public static function getSelectedProfile($profiles, $profile)
        {
            if (!empty($profiles)) {
                foreach ($profiles as $item) {
                    if ($item[1] === $profile) {
                        return $item;
                    }
                }
            }
            return false;
        }

        /**
         * Get color
         *
         * @param string $colour Color
         * @param float  $per    Percent
         *
         * @return string
         */
        public static function colourVariator($colour, $per)
        {
            $colour = substr($colour, 1);
            $rgb    = '';
            $per    = $per / 100 * 255;
            if ($per < 0) {
                // Darker
                $per = abs($per);
                for ($x = 0; $x < 3; $x ++) {
                    $c   = hexdec(substr($colour, (2 * $x), 2)) - $per;
                    $c   = ($c < 0) ? 0 : dechex($c);
                    $rgb .= (strlen($c) < 2) ? '0' . $c : $c;
                }
            } else {
                // Lighter
                for ($x = 0; $x < 3; $x ++) {
                    $c   = hexdec(substr($colour, (2 * $x), 2)) + $per;
                    $c   = ($c > 255) ? 'ff' : dechex($c);
                    $rgb .= (strlen($c) < 2) ? '0' . $c : $c;
                }
            }
            return '#' . $rgb;
        }

        /**
         * Variations
         *
         * @param string $base String
         *
         * @return array
         */
        public static function variations($base)
        {
            $variations[] = $base;
            $variations[] = self::colourVariator($base, - 10);
            $variations[] = self::colourVariator($base, + 10);
            $variations[] = self::colourVariator($base, + 20);
            $variations[] = self::colourVariator($base, - 20);
            $variations[] = self::colourVariator($base, + 30);
            $variations[] = self::colourVariator($base, - 30);
            return $variations;
        }

        /**
         * Check roles
         *
         * @param array   $access_level Access level
         * @param boolean $tracking     Tracking
         *
         * @return boolean
         */
        public static function checkRoles($access_level, $tracking = false)
        {
            if (is_user_logged_in() && isset($access_level)) {
                $current_user = wp_get_current_user();
                $roles        = (array) $current_user->roles;
                if ((current_user_can('manage_options')) && !$tracking) {
                    return true;
                }
                if (count(array_intersect($roles, $access_level)) > 0) {
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        }

        /**
         * Set cache
         *
         * @param string  $name       Option cache name
         * @param string  $value      Option cache value
         * @param integer $expiration Expiration
         *
         * @return void
         */
        public static function setCache($name, $value, $expiration = 0)
        {
            $option = array('value' => $value, 'expires' => time() + (int) $expiration);
            update_option('wpmsga_cache_' . $name, $option);
        }

        /**
         * Remove cache
         *
         * @param string $name Option cache name
         *
         * @return void
         */
        public static function deleteCache($name)
        {
            delete_option('wpmsga_cache_' . $name);
        }

        /**
         * Get cache
         *
         * @param string $name Option cache name
         *
         * @return boolean
         */
        public static function getCache($name)
        {
            $option = get_option('wpmsga_cache_' . $name);

            if (false === $option || !isset($option['value']) || !isset($option['expires'])) {
                return false;
            }

            if ($option['expires'] < time()) {
                delete_option('wpmsga_cache_' . $name);
                return false;
            } else {
                return $option['value'];
            }
        }

        /**
         * Clear cache
         *
         * @return void
         */
        public static function clearCache()
        {
            global $wpdb;
            $wpdb->query('DELETE FROM '. $wpdb->options . ' WHERE option_name LIKE "wpmsga_cache_qr%%"');
        }

        /**
         * Get root domain
         *
         * @param string $domain Site domain
         *
         * @return array
         */
        public static function getRootDomain($domain)
        {
            $root = explode('/', $domain);
            preg_match(
                '/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i',
                str_ireplace('www', '', isset($root[2]) ? $root[2] : $domain),
                $root
            );
            return $root;
        }

        /**
         * Strip protocol
         *
         * @param string $domain Site domain
         *
         * @return mixed
         */
        public static function stripProtocol($domain)
        {
            return str_replace(array('https://', 'http://', ' '), '', $domain);
        }
    }

}
