<?php
/**
 * Theme Pro by Themeco
 * https://theme.co/pro
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class WFACP_Compatibility_With_Theme_PRO {
	private $shortcode_content = '';
	private $meta_key = '_cornerstone_data';

	public function __construct() {
		add_filter( 'wfacp_shortcode_exist', [ $this, 'is_shortcode_exists' ], 10, 2 );
		add_filter( 'wfacp_detect_shortcode', [ $this, 'send_brick_content' ] );
	}

	public function is_shortcode_exists( $status, $post ) {
		$content = $this->get_shortcode_content( $post );
		if ( false !== $content ) {
			$this->shortcode_content = $content;
			$status                  = true;
		}

		return $status;
	}

	public function get_shortcode_content( $post ) {
		if ( is_null( $post ) || ! $post instanceof WP_Post ) {
			return false;
		}
		$panels_data = get_post_meta( $post->ID );;
		if ( empty( $panels_data ) ) {
			return false;
		}
		$shortcodes = json_encode( $panels_data );

		$start_position = strpos( $shortcodes, '[wfacp_forms' );
		if ( false === $start_position ) {
			return false;
		}
		$shortcode_string = substr( $shortcodes, $start_position );
		$closing_position = strpos( $shortcode_string, ']', 1 );
		if ( false === $closing_position ) {
			return false;
		}

		$shortcode_string = substr( $shortcodes, $start_position, $closing_position + 1 );

		if ( strlen( $shortcode_string ) <= 0 ) {
			return false;
		}

		$shortcode_string = str_replace( '."\".', '', stripslashes( $shortcode_string ) );

		return preg_replace( '/\\\\/', '', $shortcode_string );

	}

	public function send_brick_content( $post_content ) {
		return ! empty( $this->shortcode_content ) ? $this->shortcode_content : $post_content;
	}

}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Theme_PRO(), 'theme-pro' );

