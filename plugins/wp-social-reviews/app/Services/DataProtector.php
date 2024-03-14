<?php
/**
 * Class WPSocialReviews\App\Services\DataEncryption
 *
 * @copyright 2021 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://sitekit.withgoogle.com
 */

namespace WPSocialReviews\App\Services;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class responsible for encrypting and decrypting data.
 *
 * @since 3.9.1
 * @access private
 * @ignore
 */
class DataProtector {

    /**
     * Key to use for encryption.
     *
     * @since 3.9.1
     * @var string
     */
    private $key;

    /**
     * Salt to use for encryption.
     *
     * @since 3.9.1
     * @var string
     */
    private $salt;

    /**
     * Constructor.
     *
     * @since 3.9.1
     */
    public function __construct() {
        $this->key  = $this->get_default_key();
        $this->salt = $this->get_default_salt();
    }

    /**
     * Encrypts a value.
     *
     * If a user-based key is set, that key is used. Otherwise, the default key is used.
     *
     * @since 3.9.1
     *
     * @param string $value Value to encrypt.
     * @return string|bool Encrypted value, or false on failure.
     */
    public function encrypt( $value ) {
        if(!$value) {
            return $value;
        }

        if ( ! extension_loaded( 'openssl' ) ) {
            return $value;
        }

        $method = 'aes-256-ctr';
        $ivlen  = openssl_cipher_iv_length( $method );
        $iv     = openssl_random_pseudo_bytes( $ivlen );

        $raw_value = openssl_encrypt( $value . $this->salt, $method, $this->key, 0, $iv );
        if ( ! $raw_value ) {
            return false;
        }

        return base64_encode( $iv . $raw_value ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
    }

    /**
     * Decrypts a value.
     *
     * If a user-based key is set, that key is used. Otherwise, the default key is used.
     *
     * @since 3.9.1
     *
     * @param string $raw_value Value to decrypt.
     * @return string|bool Decrypted value, or false on failure.
     */
    public function decrypt( $raw_value ) {

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

        $value = openssl_decrypt( $raw_value, $method, $this->key, 0, $iv );
        if ( ! $value || substr( $value, - strlen( $this->salt ) ) !== $this->salt ) {
            return false;
        }

        return substr( $value, 0, - strlen( $this->salt ) );
    }

    public function maybe_encrypt( $raw_value ) {
        $maybe_decrypted = $this->decrypt( $raw_value );

        if ( $maybe_decrypted ) {
            return $this->encrypt( $maybe_decrypted );
        }

        return $this->encrypt( $raw_value );
    }

    public function maybe_decrypt( $value ) {
        if ( ! is_string( $value ) ) {
            return $value;
        }

        if ( strpos( $value, '{' ) === 0 ) {
            return $value;
        }

        $decrypted = $this->decrypt( $value );

        if ( ! $decrypted ) {
            return $value;
        }

        return $decrypted;
    }


    /**
     * Gets the default encryption key to use.
     *
     * @since 3.9.1
     *
     * @return string Default (not user-based) encryption key.
     */
    private function get_default_key() {
        if ( defined( 'WPSR_ENCRYPTION_KEY' ) && '' !== WPSR_ENCRYPTION_KEY ) {
            return WPSR_ENCRYPTION_KEY;
        }

        if ( defined( 'LOGGED_IN_KEY' ) && '' !== LOGGED_IN_KEY ) {
            return LOGGED_IN_KEY;
        }

        // If this is reached, you're either not on a live site or have a serious security issue.
        return 'das-ist-kein-geheimer-schluessel';
    }

    /**
     * Gets the default encryption salt to use.
     *
     * @since 3.9.1
     *
     * @return string Encryption salt.
     */
    private function get_default_salt() {
        if ( defined( 'WPSR_ENCRYPTION_SALT' ) && '' !== WPSR_ENCRYPTION_SALT ) {
            return WPSR_ENCRYPTION_SALT;
        }

        if ( defined( 'LOGGED_IN_SALT' ) && '' !== LOGGED_IN_SALT ) {
            return LOGGED_IN_SALT;
        }

        // If this is reached, you're either not on a live site or have a serious security issue.
        return 'das-ist-kein-geheimes-salz';
    }
}
