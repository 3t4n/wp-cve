<?php
/**
 * The user data shortcode.
 *
 * @since      3.1
 *
 * @category   WordPress\Plugin
 * @package    Connections_Directory\Shortcode
 * @subpackage Connections_Directory\Shortcode\User_Property
 * @author     Steven A. Zahm
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2023, Steven A. Zahm
 * @link       https://connections-pro.com/
 */

declare( strict_types=1 );

namespace Connections_Directory\Shortcode;

use Connections_Directory\Shortcode;
use Connections_Directory\Utility\_array;
use WP_User;

/**
 * Class User_Property
 *
 * @package Connections_Directory\Shortcode
 */
final class User_Property extends Shortcode {

	use Do_Shortcode;
	use Get_HTML;

	/**
	 * The shortcode tag.
	 *
	 * @since 3.1
	 */
	const TAG = 'user_';

	/**
	 * User_Property constructor.
	 *
	 * @param array  $untrusted The shortcode arguments.
	 * @param string $content   The shortcode content.
	 * @param string $tag       The shortcode tag.
	 */
	public function __construct( array $untrusted, string $content = '', string $tag = self::TAG ) {

		$this->tag = $tag;

		$defaults  = $this->getDefaultAttributes();
		$untrusted = shortcode_atts( $defaults, $untrusted, $tag );

		$this->attributes = $this->prepareAttributes( $untrusted );
		$this->content    = $content;
		$this->html       = is_user_logged_in() || 0 < $this->attributes['id'] ? $this->generateHTML() : '';
	}

	/**
	 * The shortcode attribute defaults.
	 *
	 * @since 3.1
	 *
	 * @return array
	 */
	protected function getDefaultAttributes(): array {

		return array(
			'id'   => get_current_user_id(),
			'prop' => 'id',
			'key'  => '',
			'size' => 96,
		);
	}

	/**
	 * Parse and prepare the shortcode attributes.
	 *
	 * @since 3.1
	 *
	 * @param array $attributes The shortcode arguments.
	 *
	 * @return array
	 */
	protected function prepareAttributes( array $attributes ): array {

		$properties = $this->getProperties();

		$attributes['id']   = absint( $attributes['id'] );
		$attributes['prop'] = array_key_exists( $attributes['prop'], $properties ) ? strtolower( $attributes['prop'] ) : 'id';

		switch ( $attributes['prop'] ) {

			case 'avatar':
			case 'avatar_url':
				$attributes['size'] = absint( $attributes['size'] );
				unset( $attributes['key'] );
				break;

			case 'meta':
				unset( $attributes['size'] );
				break;

			default:
				unset( $attributes['key'], $attributes['size'] );
		}

		return $attributes;
	}

	/**
	 * The valid user properties.
	 *
	 * @since 3.1
	 *
	 * @return string[]
	 */
	private function getProperties(): array {

		return array(
			'avatar'       => 'avatar',
			'avatar_url'   => 'avatar_url',
			'bio'          => 'user_description',
			'display_name' => 'display_name',
			'email'        => 'user_email',
			'first_name'   => 'user_firstname',
			'id'           => 'ID',
			'last_name'    => 'user_lastname',
			'login'        => 'user_login',      // The user login to the site.
			'nice_name'    => 'user_nicename',   // The user author permalink slug.
			'meta'         => 'meta',
			'registered'   => 'user_registered',
			'website'      => 'user_url',
		);
	}

	/**
	 * Get the property value by property key.
	 *
	 * @since 3.1
	 *
	 * @param string $key The property key.
	 *
	 * @return string
	 */
	private function getProperty( string $key ): string {

		return _array::get( $this->getProperties(), $key, 'id' );
	}

	/**
	 * Generate the shortcode HTML.
	 *
	 * @since 3.1
	 *
	 * @return string
	 */
	protected function generateHTML(): string {

		$html     = '';
		$user     = get_user_by( 'id', $this->attributes['id'] );
		$content  = $this->content;
		$property = $this->getProperty( $this->attributes['prop'] );

		if ( $user instanceof WP_User && $user->has_prop( $property ) ) {

			$value = $user->{$property};

			switch ( $property ) {

				case 'ID':
					$html = (string) $value;
					break;

				case 'user_description':
					$html = wp_kses_post( $value );
					break;

				case 'display_name':
				case 'user_email':
				case 'user_firstname':
				case 'user_lastname':
				case 'user_login':
				case 'user_nicename':
					$html = wp_kses_data( $value );
					break;

				case 'user_registered':
					$html = esc_html( $value );
					break;

				case 'user_url':
					$html = esc_url( $value );
					break;
			}

		} elseif ( $user instanceof WP_User && in_array( $property, array( 'avatar', 'avatar_url' ), true ) ) {

			switch ( $property ) {

				case 'avatar':
					$avatar = get_avatar( $user, $this->attributes['size'] );
					break;

				case 'avatar_url':
					$avatar = get_avatar_url( $user, array( 'size' => $this->attributes['size'] ) );
					break;

				default:
					$avatar = '';
			}

			if ( is_string( $avatar ) ) {
				$html = $avatar;
			}

		} elseif ( $user instanceof WP_User && 'meta' === $property ) {

			$meta = get_user_meta( $user->ID, $this->attributes['key'], true );
			$html = is_string( $meta ) ? $meta : json_encode( $meta );
		}

		if ( 0 < strlen( $html )
			 && 0 < strlen( $content )
			 && str_contains( $content, '%s' )
		) {

			// $html = trim( preg_replace( '/' . preg_quote( '%s', '/' ) . '+/u', $html, $content ), '%s' );
			$html = str_replace( '%s', $html, $content );
		}

		/**
		 * Filter the generated HTML.
		 *
		 * @since 3.1
		 *
		 * @param string $html     The escaped output of the shortcode.
		 * @param int    $id       The user ID.
		 * @param string $property The user property.
		 */
		return apply_filters(
			'Connections_Directory/Login/Shortcode/User_Property/HTML',
			$html,
			$this->attributes['id'],
			$property
		);
	}
}
