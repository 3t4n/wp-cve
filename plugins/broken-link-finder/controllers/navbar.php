<?php
/**
 * This is navbar file.
 *
 * @package broken-link-finder/controllers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $moblc_utility, $moblc_dir_path;

$debug_url    = add_query_arg( array( 'page' => 'moblc_troubleshooting' ), ( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : null ) );
$manual_url   = add_query_arg( array( 'page' => 'moblc_manual' ), ( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : null ) );
$settings_url = add_query_arg( array( 'page' => 'moblc_settings' ), ( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : null ) );
$report_url   = add_query_arg( array( 'page' => 'moblc_report' ), ( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : null ) );

$active_tab = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verifcation is not required here.
require_once $moblc_dir_path . 'views' . DIRECTORY_SEPARATOR . 'navbar.php';
