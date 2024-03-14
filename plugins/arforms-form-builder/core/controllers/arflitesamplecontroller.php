<?php

class arflitesamplecontroller {

	function __construct() {

	}

	function arflite_samples_list( $load_list_into_new_form_popup = false ) {

		global $arflitesamplecontroller, $arfliteversion, $ARFLiteMdlDb, $arflitenotifymodel, $arfliteform, $arfliterecordmeta;

		$bloginformation = array();
		$str             = $ARFLiteMdlDb->arflite_get_rand_alphanumeric( 10 );

		if ( is_multisite() ) {
			$multisiteenv = 'Multi Site';
		} else {
			$multisiteenv = 'Single Site';
		}

		$addon_listing    = 1;
		$sample_list_data = get_transient( 'arflite_sample_listing_page' );
		if ( false == $sample_list_data ) {

			$bloginformation[] = $arflitenotifymodel->arflite_sitename();
			$bloginformation[] = $arfliteform->arflite_sitedesc();
			$bloginformation[] = home_url();
			$bloginformation[] = get_bloginfo( 'admin_email' );
			$bloginformation[] = $arfliterecordmeta->arflitewpversioninfo();
			$bloginformation[] = $arfliterecordmeta->arflitegetlanguage();
			$bloginformation[] = $arfliteversion;
			$bloginformation[] = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';
			$bloginformation[] = $str;
			$bloginformation[] = $multisiteenv;
			$bloginformation[] = $addon_listing;

			$valstring  = implode( '||', $bloginformation );
			$encodedval = base64_encode( $valstring );

			$urltosample = 'https://www.arformsplugin.com/download_samples/arf_sample_list.php';

			$raw_response = wp_remote_post(
				$urltosample,
				array(
					'method'      => 'POST',
					'timeout'     => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(),
					'body'        => array(
						'wpversion'  => $encodedval,
						'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '',
					),
					'cookies'     => array(),
				)
			);

			if ( is_wp_error( $raw_response ) || $raw_response['response']['code'] != 200 ) {

				echo "<div class='error_message sample_list_error-msg'>" . esc_html__( 'Forms listing is currently unavailable. Please try again later.', 'arforms-form-builder' ) . '</div>';
			} else {

				set_transient( 'arflite_sample_listing_page', $raw_response['body'], DAY_IN_SECONDS );
				echo $arflitesamplecontroller->arflite_sample_display_forms( $raw_response['body'], $load_list_into_new_form_popup ); //phpcs:ignore
			}
		} else {
			echo $arflitesamplecontroller->arflite_sample_display_forms( $sample_list_data, $load_list_into_new_form_popup ); //phpcs:ignore
		}
	}

	function arflite_sample_display_forms( $arf_samples, $load_list_into_new_form_popup ) {
		require ARFLITE_VIEWS_PATH . '/arflite_view_samples.php';
	}
}
