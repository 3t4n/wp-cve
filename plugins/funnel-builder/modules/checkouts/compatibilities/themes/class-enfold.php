<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Theme_Enfold {

	public function __construct() {

		/* checkout page */
		add_action( 'wfacp_checkout_page_found', [ $this, 'dequeue_actions' ] );

		add_action( 'wfacp_do_not_allow_shortcode_printing', [ $this, 'avia_do_not_allow_shortcode' ] );

		add_action( 'wfacp_none_checkout_pages', [ $this, 'force_execute_embed_shortcode' ], - 1 );
		add_filter( 'wfacp_internal_css', [ $this, 'add_internal_css' ] );

	}

	public function is_enabled() {
		if ( class_exists( 'AviaBuilder' ) ) {
			return true;
		}

		return false;
	}

	public function force_execute_embed_shortcode() {
		if ( class_exists( 'WFACP_Template_loader' ) && $this->is_enabled() ) {
			global $post;
			if ( is_null( $post ) ) {
				return;
			}
			$shortcodes                = $post->post_content;
			$_aviaLayoutBuilder_active = get_post_meta( $post->ID, '_aviaLayoutBuilder_active', true );

			$start_position = strpos( $shortcodes, '[wfacp_forms' );

			if ( false !== $start_position && $_aviaLayoutBuilder_active === 'active' ) {
				$shortcode_string = substr( $shortcodes, $start_position );
				$closing_position = strpos( $shortcode_string, ']', 1 );
				if ( false !== $closing_position ) {
					$shortcode_string                          = substr( $shortcodes, $start_position, $closing_position + 1 );
					WFACP_Core()->embed_forms->current_page_id = $post->ID;
					if ( strlen( $shortcode_string ) > 0 ) {
						do_shortcode( $shortcode_string );
					}
				}
			}
		}
	}

	public function avia_do_not_allow_shortcode( $status ) {

		if ( ! $this->is_enabled() ) {
			return $status;
		}

		if ( isset( $_REQUEST['avia-save-nonce'] ) && ! empty( $_REQUEST['avia-save-nonce'] ) ) {
			return true;
		}

		return $status;

	}

	public function dequeue_actions() {
		if ( class_exists( 'aviaAssetManager' ) ) {
			$instance = WFACP_Common::remove_actions( 'wp_enqueue_scripts', 'aviaAssetManager', 'try_minifying_scripts' );
			add_action( 'wp_enqueue_scripts', array( $instance, 'try_minifying_scripts' ), 11 );
		}
	}

	public function add_internal_css() {
		if ( ! $this->is_enabled() ) {
			return;
		}


		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}
		$bodyClass = "body ";

		$px = $instance->get_template_type_px() . "px";
		if ( 'pre_built' !== $instance->get_template_type() ) {

			$bodyClass = "body #wfacp-e-form ";
		}

		echo "<style>";
		echo '#top #wfacp-e-form form-row {padding: 0 7px;;margin: 0 0 15px;}';
		echo '#top label {font-weight: normal;}';
		echo '#top .wfacp_mini_cart_start_h .woocommerce-info {border: none !important;}';
		echo "</style>";

	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Theme_Enfold(), 'wfacp-enfold' );
