<?php

namespace StaxWoocommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Utils
 * @package StaxWoocommerce
 */
class Utils {

	/**
	 * @var null
	 */
	public static $instance;

	/**
	 * @return Utils|null
	 */
	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Replace curly with span
	 *
	 * @param $raw
	 *
	 * @return mixed
	 */
	public static function curly( $raw ) {
		return str_replace( [ '{{', '}}' ], [ '<span class="stx-highlight">', '</span>' ], self::kses( $raw ) );
	}

	/**
	 * Kses
	 *
	 * @param $raw
	 *
	 * @return string
	 */
	public static function kses( $raw ) {
		$allowed_tags = [
			'a'                             => [
				'class' => [],
				'href'  => [],
				'rel'   => [],
				'title' => [],
			],
			'abbr'                          => [
				'title' => [],
			],
			'b'                             => [],
			'blockquote'                    => [
				'cite' => [],
			],
			'cite'                          => [
				'title' => [],
			],
			'code'                          => [],
			'del'                           => [
				'datetime' => [],
				'title'    => [],
			],
			'dd'                            => [],
			'div'                           => [
				'class' => [],
				'title' => [],
				'style' => [],
			],
			'dl'                            => [],
			'dt'                            => [],
			'em'                            => [],
			'h1'                            => [
				'class' => [],
			],
			'h2'                            => [
				'class' => [],
			],
			'h3'                            => [
				'class' => [],
			],
			'h4'                            => [
				'class' => [],
			],
			'h5'                            => [
				'class' => [],
			],
			'h6'                            => [
				'class' => [],
			],
			'i'                             => [
				'class' => [],
			],
			'img'                           => [
				'alt'    => [],
				'class'  => [],
				'height' => [],
				'src'    => [],
				'width'  => [],
			],
			'li'                            => [
				'class' => [],
			],
			'ol'                            => [
				'class' => [],
			],
			'p'                             => [
				'class' => [],
			],
			'q'                             => [
				'cite'  => [],
				'title' => [],
			],
			'span'                          => [
				'class' => [],
				'title' => [],
				'style' => [],
			],
			'iframe'                        => [
				'width'       => [],
				'height'      => [],
				'scrolling'   => [],
				'frameborder' => [],
				'allow'       => [],
				'src'         => [],
			],
			'strike'                        => [],
			'br'                            => [],
			'strong'                        => [],
			'data-wow-duration'             => [],
			'data-wow-delay'                => [],
			'data-wallpaper-options'        => [],
			'data-stellar-background-ratio' => [],
			'ul'                            => [
				'class' => [],
			],
		];

		if ( function_exists( 'wp_kses' ) ) {
			return wp_kses( $raw, $allowed_tags );
		}

		return $raw;
	}

	/**
	 * Load template
	 *
	 * @param $name
	 * @param array $args
	 * @param bool $echo
	 *
	 * @return false|string|void
	 */
	public static function load_template( $name, $args = [], $echo = true ) {
		if ( ! $name ) {
			return;
		}

		extract( $args );

		ob_start();
		include( STAX_WOO_PATH . trim( $name ) . '.php' );

		if ( $echo ) {
			echo ob_get_clean();
		} else {
			return ob_get_clean();
		}
	}

	/**
	 * Check if WooCommerce is installed and active
	 *
	 * @return false|int
	 */
	public static function woocommerce_is_active() {
		$all_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

		return (bool) stripos( implode( $all_plugins ), 'woocommerce.php' );
	}
}

Utils::instance();
