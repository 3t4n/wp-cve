<?php

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ShortCode {
	/**
	 * @var null
	 */
	public static $instance = null;

	/**
	 * @return null|ShortCode
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return string
	 */
	public function getString( \WP_REST_Request $request ) {
		$data   = $request->get_params();
		$string = '';

		if ( ! $data['code'] ) {
			return $string;
		}

		$string = do_shortcode( shortcode_unautop( $data['code'] ) );

		return html_entity_decode($string);
	}
}
