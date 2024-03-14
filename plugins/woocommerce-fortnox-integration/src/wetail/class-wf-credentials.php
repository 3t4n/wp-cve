<?php

namespace src\wetail;

if ( !defined( 'ABSPATH' ) ) die();

/**
 * Class WF_Credentials
 * used for checking plugin license, check "Plugin-specific constants" region before deploying.
 *
 * @package src\wetail
 */
final class WF_Credentials extends License_checker {
    //region  Plugin-specific constants
    /**
     * @var string Application secret key, optional, leave empty if none.
     */
    protected const SECRET_KEY = 'ak4763';
    /**
     * @var string Cache transient, required not empty
     */
    protected const CACHE_TRANSIENT = 'woocommerce-fortnox_integration_license';
    /**
     * @var string wp_options license key key, required not empty
     */
    protected const KEY_OPTION_NAME = 'fortnox_license_key';
    /**
     * @var int License check cache timeout, optional but better be about 300
     */
    protected const LICENSE_CACHE_TIMEOUT = 300;
    /**
     * @var bool if true check() method will call error_log on license response, license cache will be disabled
     */
    protected const DEBUG = false;
    //protected const DEBUG = true;
    //endregion


    /**
     * method wrapper for back capability
     * @return bool
     */
    public static function check() {
        
        $license_key = get_option( static::KEY_OPTION_NAME );

        if ( empty( $license_key ) ) {
            $license_key = get_option( 'fortnox_api_key' ); //IF UPGRADE FAILS
            if ( empty( $license_key ) ) {
                return false;
            }

        }

        return static::is_active( $license_key );
    }
}

if ( class_exists( __NAMESPACE__ . '\License_checker' ) ) return;

/**
 * Class License_checker
 * Provides methods to check license with Wetail WHMCS server for wordpress plugins.
 * Could not be called directly, should be inherited.
 * Check "Plugin-specific constants, to be overridden by inheritor" region below for details before use.
 *
 * @version 1.0.1
 */
abstract class License_checker {
    //region  Plugin-specific constants, to be overridden by inheritor
    /**
     * @var string Application secret key, optional, leave empty if none.
     */
    protected const SECRET_KEY = ''; //YOUR_SECRET_HERE, for example ak1234, see whmcs side for details.
    /**
     * @var string Cache transient, required not empty
     */
    protected const CACHE_TRANSIENT = 'YOUR_PLUGIN_SLUG_HERE_license';
    /**
     * @var string wp_options license key key, required not empty
     */
    protected const KEY_OPTION_NAME = 'YOUR_';
    /**
     * @var int License check cache timeout, optional but better be about 300
     */
    protected const LICENSE_CACHE_TIMEOUT = 300;
    /**
     * @var bool if true check() method will call error_log on license response, license cache will be disabled
     */
    protected const DEBUG = false;
    //protected const DEBUG = true;
    //endregion

    /**
     * @var string License check endpoint
     */
    //protected const CHECK_URL = 'https://whmcs.onlineforce.net/modules/servers/licensing/verify-stage.php';
    protected const CHECK_URL = 'https://whmcs.onlineforce.net/modules/servers/licensing/verify.php';

    /**
     * @var array License invalid response preset, internal use
     */
    protected const STATUS_INVALID = [
        'status' => 'Invalid',
    ];

    /**
     * @var array Remote error response preset, internal use
     */
    protected const STATUS_REMOTE_ERROR = [
        'status' => 'Failed to contact licensing servers',
    ];

    /**
     * Check if the license key is active.
     *
     * @param string $license_key The license key to check.
     *
     * @return bool
     * @throws \Exception if was called incorrectly.
     */
    final public static function is_active( string $license_key ) {
        if ( __CLASS__ == static::class ) {
            throw new \Exception( __CLASS__ . '::' . __FUNCTION__ . ' should not be called directly.' );
            //how did you even called abstract class wtf dude
        }

        $license = static::fetch_license( $license_key );

        if ( true === static::DEBUG ) {
            error_log( __FUNCTION__ . ' response: ' . var_export( $license, 1 ) );
        }
        if ( isset( $license['status'] ) && 'Active' === $license['status'] ) {
            return true;
        }

        return false;
    }


    /**
     * Return array containing information and status of the license key.
     *
     * @param string $license_key The license key to check.
     *
     * @return array
     */
    private static function fetch_license( string $license_key ) {
        if ( null === $license_key || empty( $license_key ) ) {
            return static::STATUS_INVALID;
        }

        $cached_license = static::fetch_license_from_cache();

        if ( false === static::DEBUG ) {
            if ( $cached_license && $license_key === $cached_license['license_key'] ) {
                return $cached_license;
            }
        } else {
            error_log( __FUNCTION__ . ' warning: license is not saved to cache, debug mode enabled.' );
        }

        $token                   = static::generate_license_token( $license_key );
        $remote_license_response = static::fetch_remote_license( $license_key, $token );

        if ( is_wp_error( $remote_license_response ) ){
            return [
                'status' => 'Active'
            ];
        }

        if ( 200 !== wp_remote_retrieve_response_code( $remote_license_response ) ) {
            return array_merge( static::STATUS_REMOTE_ERROR,
                [ 'details' => 'Unexpected response code: ' . wp_remote_retrieve_response_code( $remote_license_response ) ] );
        }

        $parsed_response = static::parse_response_body(
            wp_remote_retrieve_body( $remote_license_response ),
            $token
        );

        static::cache_license( $parsed_response, $license_key );

        return $parsed_response;
    }


    /**
     * Fetch license from WP transient cache.
     *
     * @return array
     */
    private static function fetch_license_from_cache() {
        return get_transient( static::CACHE_TRANSIENT );
    }

    /**
     * Send a request to the remote license server and return a response array.
     *
     * @param string $license_key The license key to fetch status for.
     * @param string $token Security token
     *
     * @return array
     */
    private static function fetch_remote_license( string $license_key, string $token ) {

        $post_args = http_build_query(
            [
                'licensekey'  => $license_key,
                'domain'      => static::get_domain_name(),
                'ip'          => static::get_ip_address(),
                'dir'         => strtolower( dirname( __FILE__ ) ),
                'check_token' => $token,
            ]
        );

        return wp_remote_post( static::CHECK_URL, [ 'sslverify' => false, 'body' => $post_args ] );
    }


    /**
     * Get the domain name of the current site.
     *
     * @return string
     */
    private static function get_domain_name() {
        if ( ! isset( $_SERVER['SERVER_NAME'] ) ) {
            return 'unknown';
        }

        return wp_unslash( $_SERVER['SERVER_NAME'] );
    }

    /**
     * Get the IP address of the current site.
     *
     * @return string
     */
    private static function get_ip_address() {
        if ( ! isset( $_SERVER['SERVER_ADDR'] ) ) {
            return '0.0.0.0';
        }

        return (string) filter_var(
            wp_unslash( $_SERVER['SERVER_ADDR'] ),
            FILTER_SANITIZE_NUMBER_FLOAT,
            FILTER_FLAG_ALLOW_FRACTION
        );
    }

    /**
     * Generate a check token that WHMCS requires.
     *
     * @param string $license_key The license key the token is representing.
     *
     * @return string
     */
    private static function generate_license_token( string $license_key ) {
        return time() . md5( mt_rand( 1000000000, 9999999999 ) . $license_key );
    }

    /**
     * Parse a response body in to a license status array.
     *
     * @param string $body The response body to parse.
     * @param string $token Security token
     *
     * @return array
     */
    private static function parse_response_body( string $body, string $token = '' ) {
        $license_status = [];
        preg_match_all( '/<(.*?)>([^<]+)<\/\\1>/i', $body, $matches );

        foreach ( $matches[1] as $k => $v ) {
            $license_status[ $v ] = $matches[2][ $k ];
        }

        if ( ! is_array( $license_status ) ) {
            die( 'Invalid License Server Response' );
        }

        if ( ! empty( $token ) /* && ! empty( $license_status['md5hash'] )*/ && ! empty( static::SECRET_KEY ) ) {
            if ( empty( $license_status['md5hash'] ) ) {
                if ( true === static::DEBUG ) {
                    error_log( __FUNCTION__ . ': I have all parameters to check signature but no signature arrived.' );
                }

                return array_merge( static::STATUS_REMOTE_ERROR,
                    [ 'details' => 'No response signature' ] );
            }
            if ( ! empty( $license_status['md5hash'] ) ) {
                if ( $license_status['md5hash'] != md5( static::SECRET_KEY . $token ) ) {
                    if ( true === static::DEBUG ) {
                        error_log( __FUNCTION__ . ': I have all parameters to check signature but got signature mismatch.' );
                    }

                    return array_merge( static::STATUS_REMOTE_ERROR,
                        [ 'details' => 'Hash mismatch: "' . $license_status['md5hash'] . '" != "' . md5( static::SECRET_KEY . $token ) . '"' ] );
                }
            }
        }

        return $license_status;
    }


    /**
     * Cache license key data as a WP transient.
     *
     * @param array $license The license to cache.
     * @param string $license_key The license key to associate with the transient.
     *
     * @return void
     */
    private static function cache_license( array $license, string $license_key ) {
        if ( true === static::DEBUG ) {
            error_log( __FUNCTION__ . ' warning: license is not cached, debug mode enabled.' );
        }
        $license['license_key'] = $license_key;
        set_transient( static::CACHE_TRANSIENT, $license, static::LICENSE_CACHE_TIMEOUT ); // 5 minutes.
    }
}
