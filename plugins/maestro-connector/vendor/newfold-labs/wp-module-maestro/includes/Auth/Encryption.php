<?php

namespace NewfoldLabs\WP\Module\Maestro\Auth;

/**
 * Simple encryption/decryption class for storing tokens
 */
class Encryption {
	/**
	 * Key to use for encrypting/decrypting
	 *
	 * @since 0.0.1
	 * @var string
	 */
	private $key;

	/**
	 * Salt to use prior to encryption
	 *
	 * @since 0.0.1
	 * @var string
	 */
	private $salt;

	/**
	 * Method to be used for encrypting
	 *
	 * @since 0.0.1
	 * @var string
	 */
	private $method;

	/**
	 * The initialization vector length based on the method
	 *
	 * @since 0.0.1
	 * @var string
	 */
	private $ivlength;

	/**
	 * Construct
	 *
	 * @since 0.0.1
	 */
	public function __construct() {
		$this->key      = $this->get_key();
		$this->salt     = $this->get_salt();
		$this->method   = 'aes-256-ctr';
		$this->ivlength = openssl_cipher_iv_length( $this->method );
	}

	/**
	 * Returns the encryption key to use
	 *
	 * @since 0.0.1
	 *
	 * @return string The encryption key
	 */
	private function get_key() {
		if ( defined( 'LOGGED_IN_KEY' ) && '' !== LOGGED_IN_KEY ) {
			return LOGGED_IN_KEY;
		}

		// It should never make it here because we check for key/salts on the plugin admin page,
		// both on activation and when adding a maestro, but it's here as a fallback.
		return '0bee113e839d8d63499ab55c863b162f';
	}

	/**
	 * Returns the salt to use before encryption
	 *
	 * @since 0.0.1
	 *
	 * @return string The salt
	 */
	private function get_salt() {
		if ( defined( 'LOGGED_IN_SALT' ) && '' !== LOGGED_IN_SALT ) {
			return LOGGED_IN_SALT;
		}

		// It should never make it here because we check for key/salts on the plugin admin page,
		// both on activation and when adding a maestro, but it's here as a fallback.
		return '1352ba9be1b89dfa4a81cb759cbaee5e';
	}


	/**
	 * Encrypts a value
	 *
	 * @since 0.0.1
	 *
	 * @param string $value The string to be encrypted
	 *
	 * @return string|False The encrypted value, or false on failure
	 */
	public function encrypt( $value ) {

		// If we don't have openssl for some reason, we'll just bail and return the value
		if ( ! extension_loaded( 'openssl' ) ) {
			return $value;
		}

		$salty_value = $value . $this->salt;
		$iv          = openssl_random_pseudo_bytes( $this->ivlength );

		$cipher = openssl_encrypt( $salty_value, $this->method, $this->key, 0, $iv );

		// If encryption failed
		if ( ! $cipher ) {
			return false;
		}

		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		return base64_encode( $iv . $cipher );
	}

	/**
	 * Decrypts a value
	 *
	 * @since 0.0.1
	 *
	 * @param string $cipher The value to be decrypted
	 *
	 * @return string|False The decrypted value or false on failure
	 */
	public function decrypt( $cipher ) {

		// If we don't have openssl for some reason, we'll just bail and return the value
		if ( ! extension_loaded( 'openssl' ) ) {
			return $cipher;
		}

		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
		$cipher = base64_decode( $cipher, true );

		// Grab the IV from the front of the passed encrypted string
		$iv = substr( $cipher, 0, $this->ivlength );

		// Get the encrypted value from the second half
		$value = substr( $cipher, $this->ivlength );

		// Decrypt!
		$decrypted_value = openssl_decrypt( $value, $this->method, $this->key, 0, $iv );

		// Decription failed, or the salt doesn't match the end of the decrypted string
		if ( ! $value || substr( $decrypted_value, - strlen( $this->salt ) ) !== $this->salt ) {
			return false;
		}

		// Remove the salt from the end and return the decypted value
		return substr( $decrypted_value, 0, - strlen( $this->salt ) );
	}
}
