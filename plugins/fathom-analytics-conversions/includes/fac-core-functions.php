<?php
/**
 * The Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @package Fathom_Analytics_Conversions\Functions
 * @version 1.0.9
 */

defined( 'ABSPATH' ) || exit;

global $fac4wp_options, $fac4wp_default_options;

$fac4wp_options = [];

$fac4wp_default_options = [
	FAC4WP_OPTION_API_KEY_CODE           => '',
	FAC_OPTION_INSTALLED_TC              => '',
	FAC4WP_OPTION_INTEGRATE_WPCF7        => FALSE,
	FAC4WP_OPTION_INTEGRATE_WPFORMS      => FALSE,
	FAC4WP_OPTION_INTEGRATE_GRAVIRYFORMS => FALSE,
	FAC4WP_OPTION_INTEGRATE_FLUENTFORMS  => FALSE,
	FAC4WP_OPTION_INTEGRATE_NINJAFORMS   => FALSE,
	FAC4WP_OPTION_INTEGRATE_WOOCOMMERCE  => FALSE,
	'integrate-wp-login'                 => FALSE,
	'integrate-wp-registration'          => FALSE,
	'integrate-wp-lost-password'         => FALSE,
];
apply_filters( 'fac4wp_global_default_options', $fac4wp_default_options );

function fac4wp_reload_options() {
	global $fac4wp_default_options;

	$stored_options = (array) get_option( FAC4WP_OPTIONS );
	if ( ! is_array( $fac4wp_default_options ) ) {
		$fac4wp_default_options = [];
	}

	$return_options = array_merge( $fac4wp_default_options, $stored_options );

	// fathom analytics options.
	$fac_fathom_options = [
		FAC_FATHOM_TRACK_ADMIN           => fac_fathom_get_admin_tracking(),
		FAC_OPTION_SITE_ID               => fac_fathom_get_site_id(),
		'fac_fathom_analytics_is_active' => fac_fathom_analytics_is_active(),
	];
	$return_options     = array_merge( $return_options, $fac_fathom_options );

	return apply_filters( 'fac4wp_global_reload_options', $return_options );
}

$fac4wp_options = fac4wp_reload_options();

// get admin tracking from Fathom Analytics.
function fac_fathom_get_admin_tracking() {
	//if(!defined('FATHOM_ADMIN_TRACKING_OPTION_NAME')) define('FATHOM_ADMIN_TRACKING_OPTION_NAME', 'fathom_track_admin');

	//return get_option(FATHOM_ADMIN_TRACKING_OPTION_NAME, '');
	return get_option( 'fathom_track_admin', '' );
}

// get Site ID from Fathom Analytics.
function fac_fathom_get_site_id() {
	$fac_options = (array) get_option( FAC4WP_OPTIONS );
	if ( ! empty( $fac_options[ FAC_OPTION_INSTALLED_TC ] ) ) {
		return $fac_options[ FAC_OPTION_SITE_ID ];
	} else { // If not 'installed tracking code elsewhere', get site id from FA plugin.
		return get_option( 'fathom_site_id', '' );
	}
}

// is Fathom Analytics active.
function fac_fathom_analytics_is_active() {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	return is_plugin_active( 'fathom-analytics/fathom-analytics.php' );
}

// check API key.
function fac_api_key() {
	global $fac4wp_options;
	$_site_id = $fac4wp_options[ FAC_OPTION_SITE_ID ];
	if ( empty( $_site_id ) ) {
		return '';
	}
	$url    = 'https://api.usefathom.com/v1/sites/' . $_site_id;
	$result = fac_fathom_api( $url );

	return $result;
}

// array_map recursive.
if ( ! function_exists( 'fac_array_map_recursive' ) ) {
	function fac_array_map_recursive( $callback, $array ) {
		$func = function ( $item ) use ( &$func, &$callback ) {
			return is_array( $item ) ? array_map( $func, $item ) : call_user_func( $callback, $item );
		};

		return array_map( $func, $array );
	}
}

// get Fathom events.
function fac_get_fathom_events() {
	global $fac4wp_options;
	$_site_id = $fac4wp_options[ FAC_OPTION_SITE_ID ];
	if ( empty( $_site_id ) ) {
		return '';
	}
	$url    = 'https://api.usefathom.com/v1/sites/' . $_site_id . '/events';
	$result = fac_fathom_api( $url );

	return $result;
}

// get new Fathom event.
function fac_get_fathom_event( $id ) {
	global $fac4wp_options;
	$_site_id = $fac4wp_options[ FAC_OPTION_SITE_ID ];
	$return   = [];
	if ( empty( $_site_id ) ) {
		return [];
	}
	$url    = 'https://api.usefathom.com/v1/sites/' . $_site_id . '/events/' . $id;
	$method = 'POST';
	//$body = ['id' => $id];
	$return = fac_fathom_api( $url );

	return $return;
}

// create new Fathom event.
function fac_create_fathom_event( $name ) {
	global $fac4wp_options;
	$_site_id = $fac4wp_options[ FAC_OPTION_SITE_ID ];
	$return   = [];
	if ( empty( $_site_id ) ) {
		return [];
	}
	$url    = 'https://api.usefathom.com/v1/sites/' . $_site_id . '/events';
	$method = 'POST';
	$body   = [ 'name' => $name ];
	$return = fac_save_fathom_api( $url, $body );

	return $return;
}

/**
 * Add new fathom event
 *
 * @param string $name Event name.
 */
function fac_add_new_fathom_event( $name ) {
	$event_id = '';
	if ( empty( $name ) ) {
		return $event_id;
	}
	$new_event = fac_create_fathom_event( $name );
	if ( isset( $new_event['error'] ) && empty( $new_event['error'] ) ) {
		$event_body = $new_event['body'];
		if ( fac_is_json( $event_body ) ) {
			$event_body = json_decode( $event_body );
			$event_id   = isset( $event_body->id ) ? $event_body->id : '';
		}
	}

	return $event_id;
}


// update Fathom event name.
function fac_update_fathom_event( $event_id, $name ) {
	global $fac4wp_options;
	$_site_id = $fac4wp_options[ FAC_OPTION_SITE_ID ];
	$return   = [];
	if ( empty( $_site_id ) || empty( $event_id ) || empty( $name ) ) {
		return [];
	}
	$url    = 'https://api.usefathom.com/v1/sites/' . $_site_id . '/events/' . $event_id;
	$method = 'POST';
	$body   = [ 'name' => $name ];
	$return = fac_save_fathom_api( $url, $body );

	return $return;
}

// get Fathom API.
function fac_fathom_api( $url = '' ) {
	global $fac4wp_options;
	$return   = [];
	$_api_key = $fac4wp_options[ FAC4WP_OPTION_API_KEY_CODE ];
	$_site_id = $fac4wp_options[ FAC_OPTION_SITE_ID ];
	if ( empty( $url ) || empty( $_site_id ) || empty( $_api_key ) ) {
		return $return;
	}
	$wp_request_headers = [
		'Authorization' => 'Bearer ' . $_api_key,
	];
	$request_args       = [
		'headers' => $wp_request_headers,
	];
	$response           = wp_remote_get( $url, $request_args );
	if ( ! is_wp_error( $response ) ) {
		$response_code     = wp_remote_retrieve_response_code( $response );
		$response_message  = wp_remote_retrieve_response_message( $response );
		$result            = wp_remote_retrieve_body( $response );
		$return['code']    = $response_code;
		$return['message'] = $response_message;
		$return['body']    = $result;
		$error_msg         = '';
		if ( $response_code !== 200 ) {
			if ( ! empty( $result ) ) {
				$result = json_decode( $result, TRUE );
				if ( isset( $result['error'] ) && ! empty( $result['error'] ) ) {
					$error_msg = $result['error'];
				}
			}
		} else {
			if ( strpos( $result, '<!DOCTYPE ' ) !== FALSE ) {
				$error_msg      = __( 'ERROR: The API Key you have entered does not have access to this site.', 'fathom-analytics-conversions' );
				$return['body'] = 'html';
			}
		}
		$return['error'] = $error_msg;
	}

	return $return;
}

// get Fathom API.
function fac_save_fathom_api( $url = '', $body = '' ) {
	global $fac4wp_options;
	$return   = [];
	$_api_key = $fac4wp_options[ FAC4WP_OPTION_API_KEY_CODE ];
	$_site_id = $fac4wp_options[ FAC_OPTION_SITE_ID ];
	if ( empty( $url ) || empty( $_site_id ) || empty( $_api_key ) ) {
		return $return;
	}
	$wp_request_headers = [
		'Authorization' => 'Bearer ' . $_api_key,
	];
	$request_args       = [
		'headers' => $wp_request_headers,
		'body'    => $body,
	];
	$response           = wp_remote_post( $url, $request_args );
	if ( ! is_wp_error( $response ) ) {
		$response_code     = wp_remote_retrieve_response_code( $response );
		$response_message  = wp_remote_retrieve_response_message( $response );
		$result            = wp_remote_retrieve_body( $response );
		$return['code']    = $response_code;
		$return['message'] = $response_message;
		$return['body']    = $result;
		$error_msg         = '';
		if ( $response_code !== 200 ) {
			if ( ! empty( $result ) ) {
				$result = json_decode( $result, TRUE );
				if ( isset( $result['error'] ) && ! empty( $result['error'] ) ) {
					$error_msg = $result['error'];
				}
			}
		} else {
			if ( strpos( $result, '<!DOCTYPE ' ) !== FALSE ) {
				$error_msg      = __( 'ERROR: The API Key you have entered does not have access to this site.', 'fathom-analytics-conversions' );
				$return['body'] = 'html';
			}
		}
		$return['error'] = $error_msg;
		//echo '<pre>';print_r($result);echo '</pre>';
	}

	return $return;
}

// check if a string is JSON format.
function fac_is_json( $string ) {
	json_decode( $string );

	return json_last_error() === JSON_ERROR_NONE;
}

// check all contact form 7 forms.
function fac_check_cf7_forms() {
	global $fac4wp_options;
	//echo '<pre>';print_r($fac4wp_options);echo '</pre>';
	if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_WPCF7 ] && ( $fac4wp_options['fac_fathom_analytics_is_active'] || ! empty( $fac4wp_options[ FAC_OPTION_INSTALLED_TC ] ) ) ) {
		$args      = [
			'post_type'   => 'wpcf7_contact_form',
			'post_status' => 'publish',
			'numberposts' => - 1,
		];
		$cf7_forms = get_posts( $args );
		//echo '<pre>';print_r($cf7_forms);echo '</pre>';
		if ( $cf7_forms ) {
			foreach ( $cf7_forms as $form ) {
				//echo '<pre>';print_r($form);echo '</pre>';
				$form_id          = $form->ID;
				$fac_cf7          = get_option( 'fac_cf7_' . $form_id, [] );
				$fac_cf7_event_id = is_array( $fac_cf7 ) && isset( $fac_cf7['event_id'] ) ? $fac_cf7['event_id'] : '';
				$title            = $form->post_title;
				if ( empty( $fac_cf7_event_id ) ) {
					fa_add_event_id_to_cf7( $form_id, $title );
				} else {
					fac_update_fathom_event( $fac_cf7_event_id, $title );
				}
			}
			wp_reset_postdata();
		}
	}
}

// add event id to cf7 form.
function fa_add_event_id_to_cf7( $form_id = 0, $title = '' ) {
	if ( ! $form_id || empty( $title ) ) {
		return;
	}
	$new_event = fac_create_fathom_event( $title );
	if ( isset( $new_event['error'] ) && empty( $new_event['error'] ) ) {
		$event_body = $new_event['body'];
		if ( fac_is_json( $event_body ) ) {
			$event_body = json_decode( $event_body );
			$event_id   = isset( $event_body->id ) ? $event_body->id : '';
			if ( ! empty( $event_id ) ) {
				$fac_cf7 = get_option( 'fac_cf7_' . $form_id, [] );
				if ( is_array( $fac_cf7 ) ) {
					$fac_cf7['event_id'] = $event_id;
					update_option( 'fac_cf7_' . $form_id, $fac_cf7 );
				}
			}
		}
		//echo '<pre>';print_r($event_body);echo '</pre>';
	}
}

// add event id to cf7 form.
function fa_add_event_id_to_wpforms( $form_id = 0, $title = '' ) {
	if ( ! $form_id || empty( $title ) ) {
		return;
	}
	$new_event = fac_create_fathom_event( $title );
	if ( isset( $new_event['error'] ) && empty( $new_event['error'] ) ) {
		$event_body = $new_event['body'];
		if ( fac_is_json( $event_body ) ) {
			$event_body = json_decode( $event_body );
			$event_id   = isset( $event_body->id ) ? $event_body->id : '';
			if ( ! empty( $event_id ) ) {
				$form = get_post( absint( $form_id ) );
				if ( $form ) {
					$form_content = $form->post_content;
					// convert to array
					if ( ! $form_content || empty( $form_content ) ) {
						$form_data = FALSE;
					} else {
						$form_data = wp_unslash( json_decode( $form_content, TRUE ) );
					}
					// assign event id
					$form_data['settings']['fac_wpforms_event_id'] = $event_id;
					// json encode
					$form_data = wp_slash( wp_json_encode( $form_data ) );
					// save event id to form
					wp_update_post(
						[
							'ID'           => $form_id,
							'post_content' => ( $form_data ),
						]
					);
				}
			}
		}
		//echo '<pre>';print_r($event_body);echo '</pre>';
	}
}

/**
 * Add event id to gravity form.
 */
function fa_add_event_id_to_gf( $form_id = 0, $title = '' ) {
	if ( ! $form_id || empty( $title ) ) {
		return;
	}
	$new_event = fac_create_fathom_event( $title );
	if ( isset( $new_event['error'] ) && empty( $new_event['error'] ) ) {
		$event_body = $new_event['body'];
		if ( fac_is_json( $event_body ) ) {
			$event_body = json_decode( $event_body );
			$event_id   = isset( $event_body->id ) ? $event_body->id : '';
			if ( ! empty( $event_id ) ) {
				$fac_gf = get_option( 'gforms_fac_' . $form_id, [] );
				if ( is_array( $fac_gf ) ) {
					$fac_gf['event_id'] = $event_id;
					update_option( 'gforms_fac_' . $form_id, $fac_gf );
				}
			}
		}
		//echo '<pre>';print_r($event_body);echo '</pre>';
	}
}

// check all wpforms forms.
function fac_check_wpforms_forms() {
	global $fac4wp_options;
	//echo '<pre>';print_r($fac4wp_options);echo '</pre>';
	if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_WPFORMS ] && ( $fac4wp_options['fac_fathom_analytics_is_active'] || ! empty( $fac4wp_options[ FAC_OPTION_INSTALLED_TC ] ) ) ) {
		$args  = [
			'post_type'   => 'wpforms',
			'post_status' => 'publish',
			'numberposts' => - 1,
		];
		$forms = get_posts( $args );
		//echo '<pre>';print_r($forms);echo '</pre>';
		if ( $forms ) {
			foreach ( $forms as $form ) {
				//echo '<pre>';print_r($form);echo '</pre>';
				$form_id      = $form->ID;
				$form_content = $form->post_content;

				if ( ! $form_content || empty( $form_content ) ) {
					$form_data = FALSE;
				} else {
					$form_data = wp_unslash( json_decode( $form_content, TRUE ) );
				}
				//echo '<pre>';print_r($form_data);echo '</pre>';
				$form_settings    = $form_data['settings'];
				$wpforms_event_id = isset( $form_settings['fac_wpforms_event_id'] ) ? $form_settings['fac_wpforms_event_id'] : '';
				$title            = $form->post_title;
				if ( empty( $wpforms_event_id ) ) {
					fa_add_event_id_to_wpforms( $form_id, $title );
				} else {
					fac_update_fathom_event( $wpforms_event_id, $title );
				}
			}
			wp_reset_postdata();
		}
	}
}

/**
 * Check all GravityForm forms
 */
function fac_check_gf_forms() {
	global $fac4wp_options;
	//echo '<pre>';print_r($fac4wp_options);echo '</pre>';
	if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_GRAVIRYFORMS ] && ( $fac4wp_options['fac_fathom_analytics_is_active'] || ! empty( $fac4wp_options[ FAC_OPTION_INSTALLED_TC ] ) ) && class_exists( 'GFAPI' ) ) {
		$gf_forms = GFAPI::get_forms( TRUE, FALSE ); // get all gforms.

		if ( $gf_forms ) {
			foreach ( $gf_forms as $form ) {
				$form_id         = $form['id'];
				$fac_gf          = get_option( 'gforms_fac_' . $form_id, [] );
				$fac_gf_event_id = is_array( $fac_gf ) && isset( $fac_gf['event_id'] ) ? $fac_gf['event_id'] : '';
				$title           = $form['title'];
				if ( empty( $fac_gf_event_id ) ) {
					fa_add_event_id_to_gf( $form_id, $title );
				} else {
					fac_update_fathom_event( $fac_gf_event_id, $title );
				}
			}
		}
	}
}

/**
 * Check all FluentForm forms.
 */
function fac_check_ff_forms() {
	global $fac4wp_options, $wpdb;
	if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_FLUENTFORMS ] && ( $fac4wp_options['fac_fathom_analytics_is_active'] || ! empty( $fac4wp_options[ FAC_OPTION_INSTALLED_TC ] ) ) ) {
		$formsTable = $wpdb->prefix . 'fluentform_forms';
		$fForms     = $wpdb->get_results( "SELECT * FROM " . $formsTable, ARRAY_A );
		//echo '<pre>';print_r( $firstForm );echo '</pre>';
		if ( $fForms ) {
			foreach ( $fForms as $form ) {
				$form_id = $form['id'];
				$title   = $form['title'];
				fac_update_event_id_to_ff( $form_id, $title );
			}
		}
	}
}

/**
 * Add/update event id to FluentForm.
 *
 * @param int $form_id The form id.
 * @param string $title The form title.
 */
function fac_update_event_id_to_ff( $form_id, $title ) {
	$fac_ff          = get_option( 'fac_fform_' . $form_id, [] );
	$fac_ff_event_id = is_array( $fac_ff ) && isset( $fac_ff['event_id'] ) ? $fac_ff['event_id'] : '';
	if ( empty( $fac_ff_event_id ) ) {
		$new_event_id = fac_add_new_fathom_event( $title );
		if ( ! empty( $new_event_id ) ) {
			if ( is_array( $fac_ff ) ) {
				$fac_ff['event_id'] = $new_event_id;
				update_option( 'fac_fform_' . $form_id, $fac_ff );
			}
		}
	} else {
		// Check if event id exist.
		$event = fac_get_fathom_event( $fac_ff_event_id );
		if ( $event['code'] !== 200 ) { // Not exist, then add a new one.
			$new_event_id = fac_add_new_fathom_event( $title );
			if ( ! empty( $new_event_id ) ) {
				if ( is_array( $fac_ff ) ) {
					$fac_ff['event_id'] = $new_event_id;
					update_option( 'fac_fform_' . $form_id, $fac_ff );
				}
			}
		} else {
			// Update event title if not match.
			$body        = isset( $event['body'] ) ? json_decode( $event['body'], TRUE ) : [];
			$body_object = isset( $body['object'] ) ? $body['object'] : '';
			$body_name   = isset( $body['name'] ) ? $body['name'] : '';
			if ( $body_object === 'event' && $body_name !== $title ) {
				fac_update_fathom_event( $fac_ff_event_id, $title ); // Update Fathom event with the current title.
			}
		}
	}
}

/**
 * Add/update event id to NinjaForm.
 *
 * @param int $form_id The form id.
 * @param string $title The form title.
 */
function fac_update_event_id_to_nj( $form_id, $title = '' ) {
	if ( class_exists( 'Ninja_Forms' ) ) {
		$form       = Ninja_Forms()->form( $form_id )->get();
		$f_settings = $form->get_settings();
		$event_id   = '';
		if ( is_array( $f_settings ) ) {
			$title    = $f_settings['title'];
			$event_id = isset( $f_settings['fathom_analytics'] ) ? $f_settings['fathom_analytics'] : '';
		}

		if ( empty( $event_id ) ) {
			$new_event_id = fac_add_new_fathom_event( $title );
			if ( ! empty( $new_event_id ) ) {
				$form->update_setting( 'fathom_analytics', $new_event_id );
				$form->save();
				// Update nf cache.
				if ( class_exists( 'WPN_Helper' ) ) {
					$form_cache = WPN_Helper::get_nf_cache( $form_id );
					$form_data  = $form_cache;
					if ( $form_data ) {
						if ( isset( $form_data['settings'] ) ) {
							$form_data['settings']['fathom_analytics'] = $new_event_id;
						}
					}
					WPN_Helper::update_nf_cache( $form_id, $form_data );
				}
			}
		} else {
			// Check if event id exist.
			$event = fac_get_fathom_event( $event_id );
			if ( $event['code'] !== 200 ) { // Not exist, then add a new one.
				$new_event_id = fac_add_new_fathom_event( $title );
				if ( ! empty( $new_event_id ) ) {
					$form->update_setting( 'fathom_analytics', $new_event_id );
					$form->save();
					// Update nf cache.
					if ( class_exists( 'WPN_Helper' ) ) {
						$form_cache = WPN_Helper::get_nf_cache( $form_id );
						$form_data  = $form_cache;
						if ( $form_data ) {
							if ( isset( $form_data['settings'] ) ) {
								$form_data['settings']['fathom_analytics'] = $new_event_id;
							}
						}
						WPN_Helper::update_nf_cache( $form_id, $form_data );
					}
				}
			} else {
				// Update event title if not match.
				$body        = isset( $event['body'] ) ? json_decode( $event['body'], TRUE ) : [];
				$body_object = isset( $body['object'] ) ? $body['object'] : '';
				$body_name   = isset( $body['name'] ) ? $body['name'] : '';
				if ( $body_object === 'event' && $body_name !== $title ) {
					fac_update_fathom_event( $event_id, $title ); // Update Fathom event with the current title.
				}
			}
		}
	}
}

/**
 * Check all NinjaForms forms.
 */
if ( ! function_exists( 'fac_check_nj_forms' ) ) {
	function fac_check_nj_forms() {
		global $fac4wp_options, $wpdb;
		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_NINJAFORMS ] && ( $fac4wp_options['fac_fathom_analytics_is_active'] || ! empty( $fac4wp_options[ FAC_OPTION_INSTALLED_TC ] ) ) ) {
			$formsTable = $wpdb->prefix . 'nf3_forms';
			$fForms     = $wpdb->get_results( "SELECT * FROM " . $formsTable, ARRAY_A );
			//echo '<pre>';print_r( $firstForm );echo '</pre>';
			if ( $fForms ) {
				foreach ( $fForms as $form ) {
					$form_id = $form['id'];
					$title   = $form['title'];
					fac_update_event_id_to_nj( $form_id, $title );
				}
			}
		}
	}
}

/**
 * Check if Fathom Analytics is active.
 */
if ( ! function_exists( 'is_fac_fathom_analytic_active' ) ) {
	function is_fac_fathom_analytic_active() {
		global $fac4wp_options;
		if ( $fac4wp_options['fac_fathom_analytics_is_active'] || ! empty( $fac4wp_options[ FAC_OPTION_INSTALLED_TC ] ) ) {
			return TRUE;
		}

		return FALSE;
	}
}
