<?php
/**
 * Authentication helper.
 *
 * @version     2018-02-26 10:08 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful\Helpers;

use Watchful\Exception;

/**
 * Class Watchful Authentication
 */
class Authentification {

	/**
	 * The private key.
	 *
	 * @var string
	 */
	private $private_key;

	/**
	 * The verify key.
	 *
	 * @var string
	 */
	private $verify_key;

	/**
	 * Public content.
	 *
	 * @var string
	 */
	private $public_content;

	/**
	 * The time stamp.
	 *
	 * @var int
	 */
	private $time_stamp;

	/**
	 * Authentication class constructor.
	 *
	 * @param string $private_key    The private key.
	 * @param string $verify_key     The verify key.
	 * @param int    $time_stamp     The time stamp.
	 * @param string $public_content Public content.
	 */
	public function __construct( $private_key, $verify_key, $time_stamp, $public_content ) {
		$this->private_key    = $private_key;
		$this->verify_key     = $verify_key;
		$this->time_stamp     = $time_stamp;
		$this->public_content = $public_content;
	}

	/**
	 * Check verify_key, hash_mac and timestamp.
	 *
	 * @return bool
	 *
	 * @throws Exception If no verification key or bad ke or bad timestamp.
	 */
	public function check() {
		if ( ! $this->verify_key ) {
			throw new Exception( 'no-verification-key', 403 );
		}

		if ( ! $this->check_key() ) {
			throw new Exception( 'bad-authentification', 403 );
		}

		if ( ! $this->validate_timestamp() ) {
			throw new Exception( 'bad-timestamp', 403 );
		}

		return true;
	}

	/**
	 * Get authentication arguments.
	 */
	public static function get_arguments() {
		return array(
			'time_stamp' => array(
				'default'           => 0,
				'sanitize_callback' => 'absint',
			),
			'key'        => array(
				'default'           => 1,
				'sanitize_callback' => 'sanitize_text_field',
			),
		);
	}

	/**
	 * Validate timestamp. The meaning of this check is to enhance security by
	 * making sure any token can only be used in a short period of time.
	 *
	 * @return bool True if timestamp is correct or if check is disabled in
	 *              component options
	 *
	 * @internal param int $timestamp
	 */
	private function validate_timestamp() {
		$settings = get_option( 'watchfulSettings' );
		if ( !empty($settings['watchful_disable_timestamp']) ) {
			return true;
		}

		if ( ( $this->time_stamp > time() - 360 ) && ( $this->time_stamp < time() + 360 ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check that the provided key is valid.
	 *
	 * @return bool
	 */
	private function check_key() {
		$key         = $this->verify_key;
		$control_key = hash_hmac( 'sha256', $this->public_content, $this->private_key );
		$status      = 0;
        if ( ! is_string( $key ) || ! is_string( $control_key ) ) {
            return false;
		}

		$len = strlen( $key );
		if ( strlen( $control_key ) !== $len ) {
            return false;
		}

		for ( $i = 0; $i < $len; $i++ ) {
			$status |= ord( $key[ $i ] ) ^ ord( $control_key[ $i ] );
		}

        return 0 === $status;
	}
}
