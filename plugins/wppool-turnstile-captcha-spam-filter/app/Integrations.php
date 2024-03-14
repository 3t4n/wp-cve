<?php
/**
 * Main plugin class.
 *
 * @since   1.0.0
 * @package EasyCloudflareTurnstile
 */

namespace EasyCloudflareTurnstile;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Manages ContactForm7 turnstile integration.
 *
 * @since 1.0.1
 */
class Integrations {

	/**
	 * Contains common integrations.
	 *
	 * @var \EasyCloudflareTurnstile\Common;
	 */
	public $common;

	/**
	 * Contains contact form 7 integrations.
	 *
	 * @var \EasyCloudflareTurnstile\ContactForm7;
	 */
	public $cf7;

	/**
	 * Contains wpforms integrations.
	 *
	 * @var \EasyCloudflareTurnstile\WPForms;
	 */
	public $wpf;

	/**
	 * Contains elementor integrations.
	 *
	 * @var \EasyCloudflareTurnstile\Elementor;
	 */
	public $elementor;
	/**
	 * Contains gravityforms integrations.
	 *
	 * @var \EasyCloudflareTurnstile\GravityForm;
	 */
	public $gravityforms;

	/**
	 * Contains formidable integrations.
	 *
	 * @var \EasyCloudflareTurnstile\Formidable;
	 */
	public $formidable;

	/**
	 * Contains mc4wp integrations.
	 *
	 * @var \EasyCloudflareTurnstile\MailChimp;
	 */
	public $mailchimp;

	/**
	 * Contains forminator integrations.
	 *
	 * @var \EasyCloudflareTurnstile\Forminator;
	 */
	public $forminator;

	/**
	 * Contains wpdiscuz integrations.
	 *
	 * @var \EasyCloudflareTurnstile\WpDiscuz;
	 */
	public $wpdiscuz;

	/**
	 * Contains happyforms integrations.
	 *
	 * @var \EasyCloudflareTurnstile\HappyForms;
	 */
	public $happyforms;

	/**
	 * Contains wpuf integrations.
	 *
	 * @var \EasyCloudflareTurnstile\WPUF;
	 */
	public $wpuf;


	/**
	 * Get store.
	 *
	 * @param string $key The key to get data.
	 * @return array
	 */
	public function get_store( $key = '' )
	{
		$store = get_option( 'ect_store' );
		if ( $store ) {
			$store = json_decode( $store, true );

			if ( $key && isset( $store[ $key ] ) ) {
				return $store[ $key ];
			}
		}

		return $store;
	}

	/**
	 * Get store setting by key.
	 *
	 * @param  mixed $key The setting key.
	 * @return mixed
	 */
	public function get( $key = '' )
	{
		$store = $this->get_store( 'integrations' );

		return isset( $store[ $key ] ) ? wp_validate_boolean( $store[ $key ] ) : false;
	}

	/**
	 * Get store integration value.
	 *
	 * @param  mixed $value The setting key.
	 * @return string
	 */
	public function get_name( $value = '' )
	{
		$store = $this->get_store( 'integrations' );
		if ( $value && ( isset( $store ) && wp_validate_boolean( $store ) ) ) {
			$name = in_array( $value, $store ) ? $value : '';
			return isset( $name ) ? $name : '';
		}
			return '';
	}

	/**
	 * Retrieve integration fields value by the key.
	 *
	 * @param string $key The settings key.
	 *
	 * @since  1.0.0
	 * @return mixed
	 */
	public function field( $key )
	{
		$fields = $this->get_store( 'fields' );
		return isset( $fields [ $key ] ) ? $fields[ $key ] : false;
	}
}