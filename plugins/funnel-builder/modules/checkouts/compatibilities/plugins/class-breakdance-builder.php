<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * plugin Name: Breakdance by Breakdance (1.1)
 *
 */


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Breakdance {

	public function __construct() {
		add_filter( 'wfacp_shortcode_exist', [ $this, 'action' ], 10, 2 );
		add_filter( 'wfacp_detect_shortcode', [ $this, 'send_builder_content' ] );

		add_action( 'wp_head', [ $this, 'breakdance_buffer_output' ], 9 );
	}

	public function breakdance_buffer_output() {

		if ( ! function_exists( 'wfacp_template' ) ) {
			return;
		}
		$instance = wfacp_template();

		if ( ! $instance instanceof WFACP_Template_Common || $instance->get_template_type() !== 'pre_built' ) {
			return;
		}

		$wp_head_array = WFACP_Common::get_list_of_attach_actions( 'wp_head' );
		if ( ! is_array( $wp_head_array ) || count( $wp_head_array ) == 0 ) {
			return;
		}

		global $wp_filter;

		foreach ( $wp_head_array as $key => $value ) {
			if ( ! isset( $value['function_path'] ) ) {
				continue;
			}
			if ( ( false !== strpos( $value['function_path'], 'plugins/breakdance/plugin/render' ) || false !== strpos( $value['function_path'], 'plugins\breakdance\plugin\render' ) ) && isset( $wp_filter['wp_head']->callbacks[ $value['priority'] ][ $value['index'] ] ) ) {
				unset( $wp_filter['wp_head']->callbacks[ $value['priority'] ][ $value['index'] ] );

			}
		}


	}


	public function action( $status, $post ) {

		if ( true == $status ) {
			return $status;
		}

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

		$panels_data = get_post_meta( $post->ID, 'breakdance_data', true );;


		if ( empty( $panels_data ) ) {
			return false;
		}
		$shortcodes     = json_encode( $panels_data );
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

		return $shortcode_string;

	}

	public function send_builder_content( $post_content ) {
		return ! empty( $this->shortcode_content ) ? $this->shortcode_content : $post_content;
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Breakdance(), 'wfacp-breakdance-builder' );


