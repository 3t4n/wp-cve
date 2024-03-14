<?php
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if ( ! class_exists( '_WP_Editors' ) ) :
  require( ABSPATH . WPINC . '/class-wp-editor.php' );
endif;

if ( ! function_exists( 'plz_tinymce_plugin_translation' ) ) :
	function plz_tinymce_plugin_translation() {
		$strings = array( 'plugin_title' => __( 'Plezi form', 'plezi-for-wordpress' ) );
		$locale = _WP_Editors::$mce_locale;
		$options = array(
			'body'            => array(
				'_wpnonce'      => wp_create_nonce( 'wp_rest' ),
				'args' 					=> 'sort_by=created_at&sort_dir=desc&page=1&per_page=20',
				'filters' 			=> array('sort_by' => 'created_at', 'sort_dir' => 'desc', 'page' => '1', 'per_page' => '20' )
			),
			'headers'         => array(
				'Cache-Control' => 'no-cache',
			),
			'cookies'         => plz_get_user_cookies()
		);

		$result = wp_remote_post( get_rest_url( null, 'plz/v2/configuration/get-forms-list' ), $options );
		$forms = json_decode( wp_remote_retrieve_body( $result ) );
		$options = array();

		if ( $forms && ! isset( $forms->error ) && isset( $forms->list ) ) :
			foreach ( $forms->list as $form ) :
				$options[ $form->id ] = $form->attributes->custom_title;
			endforeach;
		endif;

		$strings['plugin_options'] = $options;
		$translated = 'tinyMCE.addI18n("' . $locale . '.plz_tinymce_plugin", ' . wp_json_encode( $strings ) . ");\n";

		return $translated;
	}
endif;

$strings = plz_tinymce_plugin_translation();
