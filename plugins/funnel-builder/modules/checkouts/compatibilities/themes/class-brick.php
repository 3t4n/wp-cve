<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Theme_Brick {
	private $shortcode_content = '';

	public function __construct() {
		/* checkout page */

		add_filter( 'wfacp_shortcode_exist', [ $this, 'is_shortcode_exists' ], 10, 2 );
		add_filter( 'wfacp_detect_shortcode', [ $this, 'send_brick_content' ] );
		add_action( 'wp_ajax_bricks_get_element_html', [ $this, 'remove_our_shortcode' ], 9 );
		add_action( 'wfacp_update_page_design', array( $this, 'update_page_template' ), 99, 1 );
		add_filter( 'rest_dispatch_request', [ $this, 'remove_our_shortcode' ], 20 );

		add_filter( 'wfacp_do_not_execute_shortcode', [ $this, 'do_not_execute_shortcode' ], 20 );
		add_filter( 'wfacp_do_not_allow_shortcode_printing', [ $this, 'do_not_execute_shortcode' ], 20 );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

	}

	public function is_enabled() {
		return function_exists( 'bricks_is_builder' );
	}


	/**
	 * Do Not execute shortcode when bricks builder is open( sometime Session expired message displayed)
	 *
	 * @param $status
	 *
	 * @return false|mixed
	 */
	public function do_not_execute_shortcode( $status ) {

		if ( isset( $_GET['bricks'] ) && 'run' == $_GET['bricks'] ) {
			$status = true;
		}

		return $status;
	}

	public function is_shortcode_exists( $status, $post ) {
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

	public function send_brick_content( $post_content ) {
		return ! empty( $this->shortcode_content ) ? $this->shortcode_content : $post_content;
	}

	public function get_shortcode_content( $post ) {


		if ( is_null( $post ) || ! $post instanceof WP_Post ) {
			return false;
		}

		$panels_data = get_post_meta( $post->ID, BRICKS_DB_PAGE_CONTENT, true );;


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


	public function remove_our_shortcode( $request ) {
		if ( ! function_exists( 'bricks_is_rest_call' ) || ! function_exists( 'bricks_is_ajax_call' ) ) {
			return $request;
		}
		//echo wp_debug_backtrace_summary();
		if ( bricks_is_rest_call() || bricks_is_ajax_call() ) {

			remove_shortcode( 'wfacp_forms' );
			remove_shortcode( 'wfacp_mini_cart' );
		}

		return $request;
	}

	/**
	 * Set default template when bricks theme activated
	 *
	 * @param $page_id
	 *
	 * @return void
	 */
	public function update_page_template( $page_id ) {
		if ( true === $this->is_enabled() && 'bricks' === get_template() ) {
			update_post_meta( $page_id, '_wp_page_template', '' );
		}
	}

	public function internal_css() {

		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}

		$bodyClass = "body ";
		$px        = $instance->get_template_type_px() . "px";
		if ( 'pre_built' !== $instance->get_template_type() ) {
			$bodyClass = "body #wfacp-e-form ";
			$px        = "7px";
		}


		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . ".woocommerce-checkout #payment{padding: 0;}";
		$cssHtml .= "</style>";
		echo $cssHtml;

	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Theme_Brick(), 'wfacp-brick' );
