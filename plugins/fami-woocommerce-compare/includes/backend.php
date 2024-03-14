<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function fami_wccp_save_all_settings_via_ajax() {
	
	$response = array(
		'message' => array(),
		'html'    => '',
		'err'     => 'no'
	);
	
	$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
	
	if ( ! current_user_can( 'manage_options' ) ) {
		$response['message'][] = esc_html__( 'Cheating!? Huh?', 'fami-woocommerce-compare' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	if ( ! wp_verify_nonce( $nonce, 'fami_wccp_backend_nonce' ) ) {
		$response['message'][] = esc_html__( 'Security check error!', 'fami-woocommerce-compare' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$all_settings = isset( $_POST['all_settings'] ) ? Fami_Woocompare_Helper::clean( $_POST['all_settings'] ) : array();
	// $response['all_settings'] = $all_settings;
	
	update_option( 'fami_wccp_all_settings', $all_settings );
	
	$response['message'][] = esc_html__( 'All settings saved', 'fami-woocommerce-compare' );
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_fami_wccp_save_all_settings_via_ajax', 'fami_wccp_save_all_settings_via_ajax' );


/**
 * Compare page
 *
 * @param array   $post_states An array of post display states.
 * @param WP_Post $post        The current post object.
 */
function fami_wccp_add_display_post_states( $post_states, $post ) {
	$page_for_compare = Fami_Woocompare_Helper::get_page( 'compare' );
	if ( $page_for_compare === $post->ID ) {
		$post_states['_fami_wccp_page_for_compare'] = __( 'Page For Compare', 'fami-woocommerce-compare' );
	}
	
	return $post_states;
}

add_filter( 'display_post_states', 'fami_wccp_add_display_post_states', 10, 2 );

/**
 * @return string
 */
function fami_wccp_export_settings_link() {
	$nonce = wp_create_nonce( 'fami-wccp-export-settings' );
	$url   = add_query_arg(
		array(
			'action' => 'fami_wccp_export_all_settings',
			'nonce'  => $nonce
		),
		admin_url( 'admin-ajax.php' )
	);
	
	return esc_url( $url );
}

function fami_wccp_import_settings_action_link() {
	$nonce = wp_create_nonce( 'fami-wccp-import-settings' );
	$url   = add_query_arg(
		array(
			'action' => 'fami_wccp_import_all_settings',
			'nonce'  => $nonce
		),
		admin_url( 'admin-ajax.php' )
	);
	
	return esc_url( $url );
}

/**
 * Export all settings via ajax
 */
function fami_wccp_export_all_settings() {
	$all_setings = Fami_Woocompare_Helper::get_all_settings();
	
	header( 'Content-disposition: attachment; filename=fami_wccp.json' );
	header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
	
	$security     = isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '';
	$nonce_action = 'fami-wccp-export-settings';
	if ( ! wp_verify_nonce( $security, $nonce_action ) ) {
		die( esc_html__( 'Security check error', 'fami-woocommerce-compare' ) );
	}
	
	if ( ! current_user_can( 'manage_options' ) ) {
		die( esc_html__( 'Cheating!? Huh?', 'fami-woocommerce-compare' ) );
	}
	
	die( wp_json_encode( $all_setings ) );
}

add_action( 'wp_ajax_fami_wccp_export_all_settings', 'fami_wccp_export_all_settings' );

function fami_wccp_import_all_settings() {
	$response = array(
		'message' => array(),
		'html'    => '',
		'err'     => 'no'
	);
	
	$security     = isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '';
	$nonce_action = 'fami-wccp-import-settings';
	if ( ! wp_verify_nonce( $security, $nonce_action ) ) {
		$response['message'][] = esc_html__( 'Security check error!!!', 'fami-woocommerce-compare' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	if ( ! current_user_can( 'manage_options' ) ) {
		$response['message'][] = esc_html__( 'Cheating!? Huh?', 'fami-woocommerce-compare' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$response['files'] = $_FILES;
	
	if ( ! isset( $_FILES['fami_wccp_import_file']['error'] ) || is_array( $_FILES['fami_wccp_import_file']['error'] ) ) {
		$response['message'][] = esc_html__( 'Invalid parameters.', 'fami-woocommerce-compare' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	switch ( $_FILES['fami_wccp_import_file']['error'] ) {
		case UPLOAD_ERR_OK:
			break;
		case UPLOAD_ERR_NO_FILE:
			$response['message'][] = esc_html__( 'No file sent.', 'fami-woocommerce-compare' );
			$response['err']       = 'yes';
			wp_send_json( $response );
			break;
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			$response['message'][] = esc_html__( 'Exceeded filesize limit.', 'fami-woocommerce-compare' );
			$response['err']       = 'yes';
			wp_send_json( $response );
			break;
		default:
			$response['message'][] = esc_html__( 'Unknown errors.', 'fami-woocommerce-compare' );
			$response['err']       = 'yes';
			wp_send_json( $response );
			break;
	}
	
	$imported_file_name = isset( $_FILES['fami_wccp_import_file']['name'] ) ? Fami_Woocompare_Helper::clean( $_FILES['fami_wccp_import_file']['name'] ) : null;
	
	// Check file size
	if ( $_FILES['fami_wccp_import_file']['size'] > 500000 ) {
		$response['message'][] = esc_html__( 'Sorry, uploaded file is too large!!!', 'fami-woocommerce-compare' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	// Check file type
	$file_ext = fami_wccp_get_file_ext_if_allowed( $imported_file_name );
	if ( $file_ext !== 'json' ) {
		$response['message'][] = esc_html__( 'Wrong file extension!!!', 'fami-woocommerce-compare' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	$upload_dir  = wp_upload_dir();
	$target_file = $upload_dir . basename( $imported_file_name );
	if ( move_uploaded_file( $_FILES['fami_wccp_import_file']['tmp_name'], $target_file ) ) {
		if ( file_exists( $target_file ) ) {
			WP_Filesystem();
			global $wp_filesystem;
			$file_content = $wp_filesystem->get_contents( $target_file );
			
			$response['file_content'] = $file_content;
			// Remove file after read
			$wp_filesystem->delete( $target_file );
			
			// Check JSON format
			$all_settings = Fami_Woocompare_Helper::clean( json_decode( $file_content, true ) );
			if ( ! $all_settings ) {
				$response['message'][] = esc_html__( 'Wrong file format!!!', 'fami-woocommerce-compare' );
				$response['err']       = 'yes';
				wp_send_json( $response );
			}
			
			// EVERYTHING IS OK FOR IMPORT SETTINGS
			update_option( 'fami_wccp_all_settings', $all_settings );
			$response['message'][] = esc_html__( 'All settings imported. Reloading the page...', 'fami-woocommerce-compare' );
			wp_send_json( $response );
			
		} else {
			$response['message'][] = esc_html__( 'Can\'t find moved file!!!', 'fami-woocommerce-compare' );
			$response['err']       = 'yes';
			wp_send_json( $response );
		}
	} else {
		$response['message'][] = esc_html__( 'Can\'t move file!!!', 'fami-woocommerce-compare' );
		$response['err']       = 'yes';
		wp_send_json( $response );
	}
	
	wp_send_json( $response );
	die();
}

add_action( 'wp_ajax_fami_wccp_import_all_settings', 'fami_wccp_import_all_settings' );

/**
 * @param      $filename
 * @param null $mimes
 *
 * @return array
 */
function fami_wccp_check_filetype( $filename, $mimes = null ) {
	if ( empty( $mimes ) ) {
		$mimes = array(
			'json' => 'application/json'
		);
	}
	
	$type = false;
	$ext  = false;
	
	foreach ( $mimes as $ext_preg => $mime_match ) {
		$ext_preg = '!\.(' . $ext_preg . ')$!i';
		if ( preg_match( $ext_preg, $filename, $ext_matches ) ) {
			$type = $mime_match;
			$ext  = $ext_matches[1];
			break;
		}
	}
	
	return compact( 'ext', 'type' );
}

function fami_wccp_get_file_ext_if_allowed( $filename, $mimes = null ) {
	$filetype = fami_wccp_check_filetype( $filename, $mimes );
	if ( isset( $filetype['ext'] ) ) {
		return $filetype['ext'];
	}
	
	return '';
}