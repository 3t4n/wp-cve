<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Elex_Save_Settings_Tab_Fields {

	public function __construct() {
		add_action( 'wp_ajax_elex_gpf_save_settings_tab_field', array( $this, 'elex_gpf_save_settings_tab_field_callback' ) );
	}

	public function elex_gpf_save_settings_tab_field_callback() {
		check_ajax_referer( 'ajax-elex-gpf-nonce', '_ajax_elex_gpf_nonce' );
		$save_setting_tab_fields = array();
		if ( isset( $_POST['custom_meta'] ) ) {
			$custom_meta = array_map( 'sanitize_text_field', wp_unslash( $_POST['custom_meta'] ) );
			foreach ( $custom_meta as $key => $value ) {
				if ( is_null( $value ) || '' == $value ) {
					unset( $custom_meta[ $key ] );
				}
			}
			$save_setting_tab_fields['custom_meta'] = $custom_meta;
		}
		
		if ( isset( $_POST['cat_language'] ) && '' != $_POST['cat_language'] ) {
			$save_setting_tab_fields['cat_language'] = sanitize_text_field( $_POST['cat_language'] );
		}
		if ( isset( $_POST['wpml_language'] ) && '' != $_POST['wpml_language'] ) {
			$save_setting_tab_fields['wpml_language'] = sanitize_text_field( $_POST['wpml_language'] );
		}
		update_option( 'elex_settings_tab_fields_data', $save_setting_tab_fields );
	}
}
new Elex_Save_Settings_Tab_Fields();
