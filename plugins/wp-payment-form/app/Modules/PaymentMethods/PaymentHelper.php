<?php

namespace WPPayForm\App\Modules\PaymentMethods;

class PaymentHelper
{
    public static function encryptKey($value)
    {
        if(!$value) {
            return $value;
        }

        if ( ! extension_loaded( 'openssl' ) ) {
            return $value;
        }

        $salt = (defined( 'LOGGED_IN_SALT' ) && '' !== LOGGED_IN_SALT) ? LOGGED_IN_SALT : 'this-is-a-fallback-salt-but-not-secure';
        $key = ( defined( 'LOGGED_IN_KEY' ) && '' !== LOGGED_IN_KEY ) ? LOGGED_IN_KEY : 'this-is-a-fallback-key-but-not-secure';

        $method = 'aes-256-ctr';
        $ivlen  = openssl_cipher_iv_length( $method );
        $iv     = openssl_random_pseudo_bytes( $ivlen );

        $raw_value = openssl_encrypt( $value . $salt, $method, $key, 0, $iv );
        if ( ! $raw_value ) {
            return false;
        }

        return base64_encode( $iv . $raw_value ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
    }

    public static function decryptKey( $raw_value ) {

        if(!$raw_value) {
            return $raw_value;
        }

        if ( ! extension_loaded( 'openssl' ) ) {
            return $raw_value;
        }

        $raw_value = base64_decode( $raw_value, true ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode

        $method = 'aes-256-ctr';
        $ivlen  = openssl_cipher_iv_length( $method );
        $iv     = substr( $raw_value, 0, $ivlen );

        $raw_value = substr( $raw_value, $ivlen );

        $salt = (defined( 'LOGGED_IN_SALT' ) && '' !== LOGGED_IN_SALT) ? LOGGED_IN_SALT : 'this-is-a-fallback-salt-but-not-secure';
        $key = ( defined( 'LOGGED_IN_KEY' ) && '' !== LOGGED_IN_KEY ) ? LOGGED_IN_KEY : 'this-is-a-fallback-key-but-not-secure';

        $value = openssl_decrypt( $raw_value, $method, $key, 0, $iv );
        if ( ! $value || substr( $value, - strlen( $salt ) ) !== $salt ) {
            return false;
        }

        return substr( $value, 0, - strlen( $salt ) );
    }
}