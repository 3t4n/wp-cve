<?php
namespace Ponhiro_Blocks;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 設定のリセット
 */
add_action( 'wp_ajax_pb_reset_settings', __NAMESPACE__ . '\pb_reset_settings' );
function pb_reset_settings() {
	if ( !isset( $_POST['nonce'] ) ) return false;
	$nonce = $_POST['nonce'];
	if ( wp_verify_nonce( $nonce, 'pb-ajax-nonce' ) ) {
		
		delete_option( \Ponhiro_Blocks::DB_NAME['settings'] );
		wp_die( __( 'Succeeded.', 'useful-blocks' ) );
	}

	wp_die( __( 'Failed.', 'useful-blocks' ) );
}
