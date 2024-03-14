<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Active_OxygenBuilder {

	protected static $instance = null;

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		/* checkout page */
		add_filter( 'wfacp_skip_add_to_cart', [ $this, 'wfacp_skip_add_to_cart' ] );
		add_action( 'wfacp_checkout_page_found', [ $this, 'remove_wp_head' ] );
		add_action( 'wfacp_run_shortcode_before', [ $this, 'oxygen_builder_shortcode' ] );
		add_filter( 'wfacp_skip_form_printing', [ $this, 'show_embed_form' ], 11 );
		add_filter( 'wfacp_template_localize_data', [ $this, 'is_edit_mode_open' ] );
		add_action( 'wp_head', [ $this, 'add_html_height' ] );

		add_action( 'wfacp_checkout_page_found', [ $this, 'remove_oxygen_compatibility_function' ], 0 );


	}

	public function wfacp_skip_add_to_cart( $status ) {
		if ( $this->is_xlink_open() ) {
			$status = true;
		}

		return $status;
	}

	public function remove_wp_head() {

		if ( wfacp_is_elementor() ) {
			remove_action( 'wp_head', 'ct_footer_styles_hook' );
			remove_action( 'wp_head', 'oxy_print_cached_css', 999999 );
			remove_action( 'wp_head', 'oxygen_vsb_iframe_styles' );
			remove_action( 'wp_head', 'add_web_font', 0 );

			return;
		}

		$page_design = WFACP_Common::get_page_design( WFACP_Common::get_id() );
		if ( 'embed_forms' == $page_design['selected_type'] ) {
			if ( ! WFACP_Common::is_customizer() ) {

				add_filter( 'wfacp_allow_printing_shortcode_direct', '__return_true' );

				return;
			}
		}
		remove_action( 'ct_builder_start', 'ct_templates_buffer_start' );
		remove_action( 'ct_builder_end', 'ct_templates_buffer_end' );
		if ( $this->is_xlink_open() ) {
			$template_loader = WFACP_Core()->template_loader;
			remove_action( 'template_redirect', [ $template_loader, 'setup_preview' ], 99 );
		}

	}


	function oxygen_builder_shortcode( $shortcode_exist ) {
		if ( $this->is_xlink_open() ) {
			return $shortcode_exist;
		}
		if ( isset( $_REQUEST['ct_builder'] ) ) {
			return $shortcode_exist;
		}
		//return $shortcode_exist;
		if ( true === $shortcode_exist ) {
			return $shortcode_exist;
		}
		if ( ! function_exists( 'ct_template_shortcodes' ) ) {
			return $shortcode_exist;
		}
		global $post;
		$shortcodes     = get_post_meta( $post->ID, "ct_builder_shortcodes", true );
		$start_position = strpos( $shortcodes, '[wfacp_forms' );
		if ( false !== $start_position ) {
			$shortcode_string = substr( $shortcodes, $start_position );
			$closing_position = strpos( $shortcode_string, ']', 1 );
			if ( false !== $closing_position ) {
				$shortcode_string = substr( $shortcodes, $start_position, $closing_position + 1 );
				if ( strlen( $shortcode_string ) > 0 ) {
					do_shortcode( $shortcode_string );
				}
			}
		}


		return $shortcode_exist;
	}

	public function is_xlink_open() {
		if ( isset( $_REQUEST['xlink'] ) || isset( $_REQUEST['nouniversal'] ) ) {
			return true;
		}

		return false;
	}

	public function show_embed_form( $status ) {
		$template = wfacp_template();
		if ( $template instanceof WFACP_Template_Common ) {
			$data = $template->get_selected_register_template();
			global $post;
			if ( 'embed_forms' == $data['template_type'] && ! is_null( $post ) && $post->ID == WFACP_Common::get_id() ) {
				$status = false;
			}
		}

		return $status;
	}

	public function is_edit_mode_open( $data ) {
		if ( isset( $_REQUEST['ct_builder'] ) ) {
			$data['edit_mode'] = 'yes';
		}

		return $data;
	}

	public function add_html_height() {
		global $post;
		if ( isset( $_REQUEST['ct_builder'] ) && ! is_null( $post ) && $post->post_type == WFACP_Common::get_post_type_slug() ) {
			echo "<style>html {height: 100%;}</style>";
		}
	}
	public function remove_oxygen_compatibility_function() {
		$page_design = WFACP_Common::get_page_design( WFACP_Common::get_id() );
		if ( 'oxy' === $page_design['selected_type'] ) {
			add_filter( "option_oxygen_vsb_enable_signature_validation", '__return_false', 99 );
			remove_action( 'wfacp_checkout_page_found', [ $this, 'remove_wp_head' ] );
		}
	}
}

WFACP_Compatibility_With_Active_OxygenBuilder::get_instance();
