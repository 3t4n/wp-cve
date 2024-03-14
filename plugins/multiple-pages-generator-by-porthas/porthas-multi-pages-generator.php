<?php

/**
 * Plugin Name: Multiple Pages Generator by Themeisle
 * Plugin URI: https://themeisle.com/plugins/multi-pages-generator/
 * Description: Plugin for generation of multiple frontend pages from CSV data file.
 * WordPress Available:  yes 
 *
 * Author: Themeisle
 * Author URI: https://themeisle.com
 * Version: 3.4.1
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

defined( 'MPG_BASENAME' ) || define( 'MPG_BASENAME', __FILE__ );
defined( 'MPG_MAIN_DIR' ) || define( 'MPG_MAIN_DIR', dirname( __FILE__ ) );
defined( 'MPG_UPLOADS_DIR' ) || define( 'MPG_UPLOADS_DIR', WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'mpg-uploads' . DIRECTORY_SEPARATOR );
defined( 'MPG_CACHE_DIR' ) || define( 'MPG_CACHE_DIR', WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'mpg-cache' . DIRECTORY_SEPARATOR );
defined( 'MPG_CACHE_URL' ) || define( 'MPG_CACHE_URL', WP_CONTENT_URL . DIRECTORY_SEPARATOR . 'mpg-uploads' . DIRECTORY_SEPARATOR );
defined( 'MPG_NAME' ) || define( 'MPG_NAME', 'Multiple Pages Generator' );

// to redirect all themeisle_log_event to error log.
if ( ! defined( 'MPG_LOCAL_DEBUG' ) ) {
	define( 'MPG_LOCAL_DEBUG', false );
}

if ( ! defined( 'MPG_JSON_OPTIONS' ) ) {
	if ( defined( 'JSON_INVALID_UTF8_IGNORE' ) ) {
		define( 'MPG_JSON_OPTIONS', JSON_INVALID_UTF8_IGNORE );
	} else {
		define( 'MPG_JSON_OPTIONS', 0 );
	}
}

add_action( 'admin_init', function () {
	if ( is_plugin_active( 'multi-pages-plugin/porthas-multi-pages-generator.php' )
	     && is_plugin_active( 'multi-pages-plugin-premium/porthas-multi-pages-generator.php' ) ) {
		deactivate_plugins( [ 'multi-pages-plugin/porthas-multi-pages-generator.php' ] );
		add_action( 'admin_notices', function () {
			printf(
				'<div class="notice notice-warning"><p><strong>%s</strong><br>%s</p><p></p></div>',
				sprintf(
				/* translators: %s: Name of deactivated plugin */
					__( '%s plugin deactivated.' ),
					'Multiple Pages Generator(Free)'
				),
				'Using the Premium version of Multiple Pages Generator is not requiring using the Free version anymore.'
			);
		} );
	}
} );

if ( ! function_exists( 'mpg_run' ) ) {
	function mpg_run() {
		static $has_run = false;
		if ( $has_run ) {
			return;
		}
		// ... Your plugin's main file logic ...
		require_once 'controllers/CoreController.php';
		require_once 'controllers/HookController.php';
		require_once 'controllers/MenuController.php';
		require_once 'controllers/SearchController.php';
		// Запуск базового функционала подмены данных
		MPG_HookController::init_replacement();
		// Запуск всяких actions, hooks, filters
		MPG_HookController::init_base();
		// Запуск хуков для ajax. Связываем роуты и функции
		MPG_HookController::init_ajax();
		// Инициализация бокового меню в WordPress
		MPG_MenuController::init();

		add_filter( 'themeisle_sdk_products', function ( $products ) {
			$products[] = __FILE__;

			return $products;
		} );
		add_filter( 'themeisle_sdk_hide_dashboard_widget', '__return_false' );
		add_filter(
			'multiple_pages_generator_by_porthas_about_us_metadata',
			function() {
				return array(
					'logo'     => plugin_dir_url( __FILE__ ) . 'frontend/images/icon-256x256.png',
					'location' => 'mpg-dataset-library',
				);
			}
		);

		// Filter screen option value.
		add_filter(
			'set-screen-option',
			function( $status, $option, $value ) {
				if ( 'mpg_projects_per_page' === $option ) {
					return $value;
				}
			},
			99,
			3
		);
		$vendor_file = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'vendor/autoload.php';
		if ( is_readable( $vendor_file ) ) {
			require_once $vendor_file;
		}
		if ( ! $has_run ) {
			$has_run = true;
		}
	}
}

if ( MPG_LOCAL_DEBUG ) {
	add_action( 'themeisle_log_event', 'mpg_themeisle_log_event', 10, 5 );

	/**
	 * Redirect themeisle_log_event to error log.
	 */
	function mpg_themeisle_log_event( $name, $msg, $type, $file, $line ) {
		if ( MPG_NAME === $name ) {
			error_log( sprintf( '%s (%s:%d): %s', $type, $file, $line, $msg ) );
		}
	}
}

require_once 'helpers/Themeisle.php';
