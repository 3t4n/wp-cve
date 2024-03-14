<?php

namespace Wpo\Services;

use WP_Error;
use \Wpo\Core\WordPress_Helpers;
use \Wpo\Services\Log_Service;
use \Wpo\Services\Options_Service;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Services\Jwt_Token_Service')) {

    class Jwt_Token_Service
    {
        /**
         * Allow the current timestamp to be specified.
         * Useful for fixing a value within unit testing.
         *
         * Will default to PHP time() value if null.
         */
        public static $timestamp = null;

        /**
         * @param string $token
         * @return WP_Error|object   WP_Error when an error occurred, otherwise the token's claims.
         */
        public static function validate_signature($token, $retry = false)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            if (empty($token)) {
                return new WP_Error(
                    'ArgumentException',
                    __METHOD__ . ' -> JWT token not found.'
                );
            }

            $token_arr = explode('.', $token);

            // Token should explored in three segments header, body, signature
            if (sizeof($token_arr) != 3) {
                return new WP_Error(
                    'ArgumentException',
                    __METHOD__ . ' -> JWT token does not contain the expected 3 segments header, body and signature.'
                );
            }

            // Header
            $headers_enc = $token_arr[0];
            $header = \json_decode(WordPress_Helpers::base64_url_decode($headers_enc));

            if (\json_last_error() !== JSON_ERROR_NONE || !\property_exists($header, 'kid')) {
                return new WP_Error(
                    'ArgumentException',
                    __METHOD__ . ' -> Failed to retrieve expected JWT token header and corresponding kid property.'
                );
            }

            // Payload (claims)
            $claims_enc = $token_arr[1];
            $claims = \json_decode(WordPress_Helpers::base64_url_decode($claims_enc));

            // Signature
            $sig_enc = $token_arr[2];
            $sig = WordPress_Helpers::base64_url_decode($sig_enc);

            // Try get the public keys
            $key = self::get_key_from_set($header->kid);

            if (empty($key)) {
                return new WP_Error(
                    'WebKeySetNotFoundException',
                    __METHOD__ . ' -> Could not retrieve a tenant and application specific JSON Web Key Set and thus the JWT token cannot be verified successfully.'
                );
            }

            /** @var \phpseclib3\Crypt\RSA $rsa */

            $rsa = \phpseclib3\Crypt\PublicKeyLoader::load([
                'n' => new \phpseclib3\Math\BigInteger(WordPress_Helpers::base64_url_decode($key->n), 256),
                'e' => new \phpseclib3\Math\BigInteger(WordPress_Helpers::base64_url_decode($key->e), 256),
            ]);

            $rsa = $rsa->withHash('sha256');
            $rsa = $rsa->withPadding(\phpseclib3\Crypt\RSA::SIGNATURE_PKCS1);

            /** @var \phpseclib3\Crypt\Common\PublicKey $rsa */

            try {
                $verified = $rsa->verify($headers_enc . '.' . $claims_enc, $sig);
            } catch (\Exception $e) {
                $verified = false;
            }

            if (!$verified) {
                delete_option('wpo365_msft_key');

                if (!$retry) {
                    Log_Service::write_log('WARN', __METHOD__ . ' -> Verification of the signature of the JWT token failed a first time. Cached tokens have been deleted and a 2nd attempt will be made.');
                    return self::validate_signature($token, true);
                }

                return new WP_Error(
                    'SignatureValidationException',
                    __METHOD__ . ' -> Verification of the signature of the JWT token failed a second time. No more attempts will be made.'
                );
            }

            // Check nbf, iat and exp
            $timestamp = is_null(static::$timestamp) ? time() : static::$timestamp;

            $leeway = Options_Service::get_global_numeric_var('leeway');
            $leeway = empty($leeway) ? 300 : $leeway;

            // Check if the nbf if it is defined. This is the time that the
            // token can actually be used. If it's not yet that time, abort.
            if (isset($claims->nbf) && $claims->nbf > ($timestamp + $leeway)) {
                return new WP_Error(
                    'SignatureValidationException',
                    __METHOD__ . ' -> Cannot handle JWT token prior to ' . date(\DateTime::ISO8601, $claims->nbf) . '. Please check the system clock of your WordPress server.'
                );
            }

            // Check that this token has been created before 'now'. This prevents
            // using tokens that have been created for later use (and haven't
            // correctly used the nbf claim).
            if (isset($claims->iat) && $claims->iat > ($timestamp + $leeway)) {
                return new WP_Error(
                    'SignatureValidationException',
                    __METHOD__ . ' -> Cannot handle JWT token prior to ' . date(\DateTime::ISO8601, $claims->iat) . '. Please check the system clock of your WordPress server.'
                );
            }

            // Check if this token has expired.
            if (isset($claims->exp) && ($timestamp - $leeway) >= $claims->exp) {
                return new WP_Error(
                    'SignatureValidationException',
                    __METHOD__ . ' -> Cannot handle expired JWT token after ' . date(\DateTime::ISO8601, $claims->exp) . '. Please check the system clock of your WordPress server.'
                );
            }

            Log_Service::write_log('DEBUG', __METHOD__ . ' -> Verification of signature of the JWT token was successful');

            return $claims;
        }

        /**
         * Tries to retrieve the JSON Web Key Set issued for the current tenant and application either from cache or
         * by loading the JWKS from the jwks_uri specified in the open-id configuration for the current tenant.
         * 
         * @since 14.0
         * 
         * @return stdClass JSON Web Key Set to be used to verify a JWT token issued for the current tenant and registered application as a typical PHP stdClass.
         */
        private static function get_key_from_set($kid)
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            /**
             * Try get the key from cache.
             */

            $cached_key = get_site_option('wpo365_msft_key');

            if (!empty($cached_key)) {

                if (\is_object($cached_key) &&  \property_exists($cached_key, 'kid') && $cached_key->kid == $kid && \property_exists($cached_key, 'e') && \property_exists($cached_key, 'n')) {
                    Log_Service::write_log('DEBUG', __METHOD__ . ' -> Found cached JSON Web Key Set to verify the JWT token signature');
                    return $cached_key;
                }

                Log_Service::write_log('DEBUG', __METHOD__ . ' -> Deleted cached JSON Web Key Set to verify the JWT token signature');
                delete_option('wpo365_msft_key');
            }

            /**
             * Get the JSON Web Key Sets
             */

            $jwks_uri = self::get_json_web_key_sets_uri();

            $skip_ssl_verify = !Options_Service::get_global_boolean_var('skip_host_verification');

            $response = wp_remote_get(
                $jwks_uri,
                array(
                    'method' => 'GET',
                    'timeout' => 15,
                    'sslverify' => $skip_ssl_verify,
                )
            );

            if (is_wp_error($response)) {
                $warning = 'Error occured whilst trying to retrieve JSON Web Key Sets: ' . $response->get_error_message();
                Log_Service::write_log('ERROR', __METHOD__ . " -> $warning");
                return null;
            }

            $body = wp_remote_retrieve_body($response);
            $keys = \json_decode($body);

            if (\json_last_error() !== JSON_ERROR_NONE) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> Error when trying to decode the JSON Web Key Sets  [ ' . \json_last_error_msg() . ' ]');
                Log_Service::write_log('DEBUG',  $keys);
                return null;
            }

            if (\is_object($keys) && \property_exists($keys, 'keys')) {
                $keys = $keys->keys;
            }

            $keys_arr = \is_array($keys) ? $keys : array($keys);

            foreach ($keys_arr as $key) {

                if (\property_exists($key, 'kid') && $key->kid == $kid && \property_exists($key, 'n') && \property_exists($key, 'e')) {
                    update_site_option('wpo365_msft_key', $key);
                    return $key;
                }
            }

            return null;
        }

        /**
         * Get the JSON Web Key Sets URI for the given tenant and app. In case of multi-tenancy enabled the generic keys will be returned.
         * 
         * @since 14.1
         */
        private static function get_json_web_key_sets_uri()
        {
            Log_Service::write_log('DEBUG', '##### -> ' . __METHOD__);

            if (Options_Service::get_global_boolean_var('multi_tenanted')) {
                $jwks_uri = 'https://login.microsoftonline.com/common/discovery/v2.0/keys';
                Log_Service::write_log('DEBUG', __METHOD__ . " -> Trying to retrieve the Open ID configuration for the designated tenant and application $jwks_uri");
                return $jwks_uri;
            }

            /**
             * Get the JSON Web Key Sets URI (jwks_uri) from the openid configuration.
             */

            $request_service = Request_Service::get_instance();
            $request = $request_service->get_request($GLOBALS['WPO_CONFIG']['request_id']);

            $mode = $request->get_item('mode');
            $use_mail_config = $mode == 'mailAuthorize';

            $directory_id = $use_mail_config ? Options_Service::get_aad_option('mail_tenant_id') : Options_Service::get_aad_option('tenant_id');
            $application_id = $use_mail_config ? Options_Service::get_aad_option('mail_application_id') : Options_Service::get_aad_option('application_id');

            if (!$use_mail_config && Options_Service::get_global_boolean_var('use_b2c') &&  \class_exists('\Wpo\Services\Id_Token_Service_B2c')) {
                $b2c_domain_name = Options_Service::get_global_string_var('b2c_domain_name');
                $b2c_policy_name = Options_Service::get_global_string_var('b2c_policy_name');

                /**
                 * @since   20.x    Support for custom b2c login domain e.g. login.contoso.com
                 */

                $b2c_domain = Options_Service::get_global_string_var('b2c_custom_domain');

                if (empty($b2c_domain)) {
                    $b2c_domain = sprintf('https://%s.b2clogin.com/', $b2c_domain_name);
                } else {
                    $b2c_domain = sprintf('https://%s', trailingslashit($b2c_domain));
                }

                $open_id_config_url = "$b2c_domain$directory_id/$b2c_policy_name/v2.0/.well-known/openid-configuration";
            } else if (!$use_mail_config && Options_Service::get_global_boolean_var('use_ciam')) {
                $b2c_domain_name = Options_Service::get_global_string_var('b2c_domain_name');

                /**
                 * @since   20.x    Support for custom b2c login domain e.g. login.contoso.com
                 */

                $b2c_domain = Options_Service::get_global_string_var('b2c_custom_domain');

                if (empty($b2c_domain)) {
                    $b2c_domain = sprintf('https://%s.ciamlogin.com/', $b2c_domain_name);
                } else {
                    $b2c_domain = sprintf('https://%s', trailingslashit($b2c_domain));
                }

                $open_id_config_url = "$b2c_domain$directory_id/v2.0/.well-known/openid-configuration";
            } else {
                $open_id_config_url = "https://login.microsoftonline.com/$directory_id/v2.0/.well-known/openid-configuration?appid=$application_id";
            }

            Log_Service::write_log('DEBUG', __METHOD__ . " -> Trying to retrieve the Open ID configuration for the designated tenant and application $open_id_config_url");

            $skip_ssl_verify = !Options_Service::get_global_boolean_var('skip_host_verification');

            $response = wp_remote_get(
                $open_id_config_url,
                array(
                    'method' => 'GET',
                    'timeout' => 15,
                    'sslverify' => $skip_ssl_verify,
                )
            );

            if (is_wp_error($response)) {
                $warning = 'Error occured whilst getting JSON Web Key Sets URI: ' . $response->get_error_message();
                Log_Service::write_log('ERROR', __METHOD__ . " -> $warning");
                return null;
            }

            $body = wp_remote_retrieve_body($response);
            $open_id_config = json_decode($body);

            if (\json_last_error() !== JSON_ERROR_NONE || !isset($open_id_config->jwks_uri)) {
                Log_Service::write_log('ERROR', __METHOD__ . ' -> jwks_uri property not found [ ' . \json_last_error_msg() . ' ]');
                Log_Service::write_log('DEBUG', $open_id_config);
                return null;
            }

            return $open_id_config->jwks_uri;
        }
    }
}
