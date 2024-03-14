<?php
/**
 * The user log in, log out, registration, profile, username, and reset password link shortcode.
 *
 * @since      3.2
 *
 * @category   WordPress\Plugin
 * @package    Connections_Directory\Shortcode
 * @subpackage Connections_Directory\Shortcode\User_Link
 * @author     Steven A. Zahm
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2023, Steven A. Zahm
 * @link       https://connections-pro.com/
 */

declare( strict_types=1 );

namespace Connections_Directory\Shortcode;

use Connections_Directory\Shortcode;
use Connections_Directory\Utility\_array;

/**
 * Class User_Link
 *
 * @package Connections_Directory\Shortcode
 */
final class User_Link extends Shortcode {

	use Do_Shortcode;
	use Get_HTML;

	/**
	 * The shortcode tag.
	 *
	 * @since 3.2
	 */
	const TAG = 'link_';

	/**
	 * User_Link constructor.
	 *
	 * @param array  $untrusted The shortcode arguments.
	 * @param string $content   The shortcode content.
	 * @param string $tag       The shortcode tag.
	 */
	public function __construct( array $untrusted, string $content = '', string $tag = self::TAG ) {

		$this->tag = $tag;

		$defaults  = $this->getDefaultAttributes();
		$untrusted = $this->setDefaultText( $untrusted );
		$untrusted = shortcode_atts( $defaults, $untrusted, $tag );

		$this->attributes = $this->prepareAttributes( $untrusted );
		$this->content    = $content;
		$this->html       = $this->generateHTML();
	}

	/**
	 * The shortcode attribute defaults.
	 *
	 * @since 3.2
	 *
	 * @return array
	 */
	protected function getDefaultAttributes(): array {

		$request = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		return array(
			'action'   => 'log_in|out',
			'redirect' => $request,
			'text'     => '', // Intentionally left blank as this will be dynamically set.
		);
	}

	/**
	 * Set the default `text` shortcode property value based on the link `action` property.
	 *
	 * @since 3.2
	 *
	 * @param array $untrusted The user supplied shortcode properties.
	 *
	 * @return array
	 */
	private function setDefaultText( array $untrusted ): array {

		if ( array_key_exists( 'text', $untrusted ) ) {

			return $untrusted;
		}

		$action = _array::get( $untrusted, 'action', 'log_in|out' );

		switch ( $action ) {

			case 'profile':
				$text = __( 'Edit Profile', 'connections_login' );
				break;

			case 'registration':
				$text = __( 'Register', 'connections_login' );
				break;

			case 'reset_password':
				$text = __( 'Reset Password', 'connections_login' );
				break;

			default:
				$text = __( 'Log In', 'connections_login' ) . '|' . __( 'Log Out', 'connections_login' );
		}

		$untrusted['text'] = $text;

		return $untrusted;
	}

	/**
	 * Parse and prepare the shortcode attributes.
	 *
	 * @since 3.2
	 *
	 * @param array $attributes The shortcode arguments.
	 *
	 * @return array
	 */
	protected function prepareAttributes( array $attributes ): array {

		$actions = array(
			'log_in',
			'log_out',
			'profile',
			'registration',
			'reset_password',
			'log_in|out',
		);

		if ( ! in_array( $attributes['action'], $actions, true ) ) {

			$attributes['action'] = 'log_in/out';
		}

		$attributes['redirect'] = wp_validate_redirect( $attributes['redirect'] );

		return $attributes;
	}

	/**
	 * Generate the shortcode HTML.
	 *
	 * @since 3.2
	 *
	 * @return string
	 */
	protected function generateHTML(): string {

		$action = $this->attributes['action'];
		$html   = '';

		switch ( $action ) {

			case 'log_in':
				if ( ! is_user_logged_in() ) {

					$html = $this->logInOutLink();
				}
				break;

			case 'log_out':
				if ( is_user_logged_in() ) {

					$html = $this->logInOutLink();
				}
				break;

			case 'profile':
				$html = $this->profileLink();
				break;

			case 'registration':
				$html = $this->registrationLink();
				break;

			case 'reset_password':
				$html = $this->resetPasswordLink();
				break;

			case 'log_in|out':
			default:
				$html = $this->logInOutLink();
		}

		return $html;
	}

	/**
	 * Generate the HTML for either the login or log out link.
	 *
	 * @since 3.2
	 *
	 * @return string
	 */
	private function logInOutLink(): string {

		$redirect = $this->attributes['redirect'];
		$text     = $this->attributes['text'];

		// The "|" char is used to split titles.
		if ( false !== strstr( $text, '|' ) ) {

			$strings = explode( '|', $text );

		} else {

			// Both the login and logout string should be supplied and separated by a pipe,
			// but if both were not supplied, use text for both.
			$strings = array( $text, $text );
		}

		if ( is_user_logged_in() ) {

			$text = $strings[1];
			$url  = wp_logout_url( $redirect );

		} else {

			$text = $strings[0];
			$url  = wp_login_url( $redirect );
		}

		return '<a href="' . esc_url( $url ) . '" title="' . esc_attr( $text ) . '">' . esc_html( $text ) . '</a>';
	}

	/**
	 * Generate the HTML link for the new user registration link.
	 *
	 * @since 3.2
	 *
	 * @return string
	 */
	private function registrationLink(): string {

		if ( is_user_logged_in() ) {
			return '';
		}

		$text = $this->attributes['text'];
		$url  = wp_registration_url();

		return '<a href="' . esc_url( $url ) . '">' . esc_html( $text ) . '</a>';
	}

	/**
	 * Generate the HTML link for the reset password link.
	 *
	 * @since 3.2
	 *
	 * @return string
	 */
	private function resetPasswordLink(): string {

		if ( is_user_logged_in() ) {
			return '';
		}

		$redirect = $this->attributes['redirect'];
		$text     = $this->attributes['text'];
		$url      = wp_lostpassword_url( $redirect );

		return '<a href="' . esc_url( $url ) . '">' . esc_html( $text ) . '</a>';
	}

	/**
	 * Generate the HTML link to the user's edit profile page.
	 *
	 * @since 3.2
	 *
	 * @return string
	 */
	private function profileLink(): string {

		if ( ! is_user_logged_in() ) {
			return '';
		}

		$text = $this->attributes['text'];
		$url  = $this->profileURL();

		return '<a href="' . esc_url( $url ) . '">' . esc_html( $text ) . '</a>';
	}

	/**
	 * Returns the URL for the user edit profile page.
	 *
	 * This method is compatible with both bbPress and WooCommerce,
	 * it will return the URL for their user profile page instead
	 * of the core WordPress user profile page.
	 *
	 * @since 3.2
	 *
	 * @return string
	 */
	private function profileURL(): string {

		if ( function_exists( 'bp_core_get_user_domain' ) ) {
			$url = bp_core_get_user_domain( get_current_user_id() );
		} elseif ( function_exists( 'bbp_get_user_profile_url' ) ) {
			$url = bbp_get_user_profile_url( get_current_user_id() );
		} elseif ( class_exists( 'WooCommerce' ) ) {
			$url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		} else {
			$url = get_edit_user_link();
		}

		return apply_filters(
			'Connections_Directory/Login/Shortcode/User_Link/Profile/URL',
			$url
		);
	}
}
