<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WPML {

	public function __construct() {

		/* checkout page */
		add_filter( 'wfacp_wpml_checkout_page_id', [ $this, 'wfacp_wpml_checkout_page_id_function' ], 10, 1 );
		add_action( 'admin_head', [ $this, 'add_admin_css' ] );
		add_filter( 'wfacp_disabled_elementor_duplicate_template', '__return_true' );
		add_action( 'wfacp_disabled_elementor_duplicate_template_placeholder', [ $this, 'duplicate_template' ], 10, 2 );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'remove_action' ], 9 );

	}

	public function wfacp_wpml_checkout_page_id_function( $override_checkout_page_id ) {

		if ( ! class_exists( 'WPML_TM_Records' ) ) {
			return $override_checkout_page_id;
		}

		global $wpdb, $wpml_post_translations, $wpml_term_translations;
		$tm_records = new WPML_TM_Records( $wpdb, $wpml_post_translations, $wpml_term_translations );

		try {
			$translations = $tm_records->icl_translations_by_element_id_and_type_prefix( $override_checkout_page_id, 'post_wfacp_checkout' );
			if ( $translations->language_code() !== ICL_LANGUAGE_CODE ) {
				$element_id                = $tm_records->icl_translations_by_trid_and_lang( $translations->trid(), ICL_LANGUAGE_CODE )->element_id();
				$override_checkout_page_id = empty( $element_id ) ? $override_checkout_page_id : $element_id;
			}
		} catch ( Exception $e ) {
			//echo $e->getMessage();
		}


		return $override_checkout_page_id;
	}

	public function duplicate_template( $new_post_id, $post_id ) {
		WFACP_Common::copy_meta( $post_id, $new_post_id );
	}

	public function add_admin_css() {

		echo "<style>";
		echo "body.woofunnels_page_wfacp{position: initial;}";
		echo "</style>";

	}

	public function remove_action() {
		if ( ! class_exists( 'WPML_Elementor_Adjust_Global_Widget_ID' ) || ! WFACP_Common::is_theme_builder() || ! isset( $_GET['post'] ) ) {
			return;
		}
		$elementor_data = get_post_meta( $_GET['post'], '_elementor_data', true );
		if ( is_array( $elementor_data ) ) {
			WFACP_Common::remove_actions( 'elementor/editor/before_enqueue_scripts', 'WPML_Elementor_Adjust_Global_Widget_ID', 'adjust_ids' );
		}
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WPML(), 'wfacp_wpml' );
