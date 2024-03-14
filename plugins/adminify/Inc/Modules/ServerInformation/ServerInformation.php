<?php

namespace WPAdminify\Inc\Modules\ServerInformation;

use WPAdminify\Inc\Utils;
use WPAdminify\Inc\Modules\ServerInformation\ServerInfo_WP_Details;
use WPAdminify\Inc\Modules\ServerInformation\ServerInfo_Server_Details;
use WPAdminify\Inc\Modules\ServerInformation\ServerInfo_PHP_INI_Details;
use WPAdminify\Inc\Modules\ServerInformation\ServerInfo_Htaccess_Details;
use WPAdminify\Inc\Modules\ServerInformation\ServerInfo_Robots_Details;
use WPAdminify\Inc\Modules\ServerInformation\ServerInfo_Error_Logs_Details;

// no direct access allowed
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WPAdminify
 *
 * @package Server Information
 *
 * @author WP Adminify <support@wpadminify.com>
 */

class ServerInformation {

	private $url;
	public $prefix = '_wpadminify_server_info';

	public function __construct() {
		// if ( is_multisite() && ! is_network_admin() ) {
		// return; // only display to network admin if multisite is enbaled
		// }

		$this->url = WP_ADMINIFY_URL . 'Inc/Modules/ServerInformation';
		add_action( 'adminify_loaded', [ $this, 'jltwp_adminify_server_info_menu' ] );

		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', [ $this, 'jltwp_adminify_server_info_styles' ], 99999 );

			// Refresh Debug Log
			add_action( 'wp_ajax_jltwp_adminify_error_log_content_refresh', [ $this, 'jltwp_adminify_error_log_content_refresh' ] );
			add_action( 'wp_ajax_nopriv_jltwp_adminify_error_log_content_refresh', [ $this, 'jltwp_adminify_error_log_content_refresh' ] );

			// Clear Debug Log
			add_action( 'wp_ajax_jltwp_adminify_error_log_content_clear', [ $this, 'jltwp_adminify_error_log_content_clear' ] );
			add_action( 'wp_ajax_nopriv_jltwp_adminify_error_log_content_clear', [ $this, 'jltwp_adminify_error_log_content_clear' ] );
		}
	}



	/**
	 * Server Information Script/Styles
	 *
	 * @return void
	 */
	public function jltwp_adminify_server_info_styles() {
		global $pagenow;

		if ( ( 'admin.php' === $pagenow ) && ( 'adminify-server-info' === $_GET['page'] ) ) {
				echo '<style>.wp-adminify_page_adminify-server-info .adminify-header-inner{padding:0;}.wp-adminify_page_adminify-server-info .adminify-field-subheading{font-size:20px; padding-left:0;}.wp-adminify_page_adminify-server-info .adminify-nav,.wp-adminify_page_adminify-server-info .adminify-search,.wp-adminify_page_adminify-server-info .adminify-footer,.wp-adminify_page_adminify-server-info .adminify-reset-all,.wp-adminify_page_adminify-server-info .adminify-expand-all,.wp-adminify_page_adminify-server-info .adminify-header-left,.wp-adminify_page_adminify-server-info .adminify-reset-section,.wp-adminify_page_adminify-server-info .adminify-nav-background{display: none !important;}.wp-adminify_page_adminify-server-info .adminify-nav-normal + .adminify-content{margin-left: 0;}

                /* If needed for white top-bar */
                .wp-adminify_page_adminify-server-info .adminify-header-inner {
                    background-color: #fafafa !important;
                    border-bottom: 1px solid #f5f5f5;
                }
                .wp-adminify .adminify-server-info .adminify-buttons{
                    display: none !important;
                }
            </style>';

			wp_enqueue_script( 'wp-adminify-error-logs', $this->url . '/error-logs.js', [ 'jquery' ], null, true );
			wp_localize_script( 'wp-adminify-error-logs', 'WPAdminify_ErrorL', $this->adminify_error_logs_object() );

			wp_enqueue_style( 'adminify', \ADMINIFY_Setup::include_plugin_url( 'assets/css/style.min.css' ), [], WP_ADMINIFY_VER, 'all' );
			wp_enqueue_script( 'adminify', \ADMINIFY_Setup::include_plugin_url( 'assets/js/main.min.js' ), [ 'jquery' ], WP_ADMINIFY_VER, true );
		}
	}



	/**
	 * JS Object
	 *
	 * @return void
	 */
	public function adminify_error_logs_object() {
		return [
			'ajax_url'       => admin_url( 'admin-ajax.php' ),
			'security_nonce' => wp_create_nonce( 'adminify-error-logs-security-nonce' ),
			'label_update'   => esc_html__( 'Will be updated...', 'adminify' ),
			'label_clear'    => esc_html__( 'Will be cleared...', 'adminify' ),
			'label_done'     => esc_html__( 'Done!', 'adminify' ),
		];
	}

	public function jltwp_adminify_server_info_menu() {
		if ( ! class_exists( 'ADMINIFY' ) ) {
			return;
		}

		// WP Adminify Server Infos Settings
		\ADMINIFY::createOptions(
			$this->prefix,
			[

				// Framework Title
				'framework_title'         => __( 'WP Adminify Server Info <small>by Jewel Theme</small>', 'adminify' ),
				'framework_class'         => 'adminify-server-info',

				// menu settings
				'menu_title'              => 'Server Info',
				'menu_slug'               => 'adminify-server-info',
				'menu_type'               => 'submenu', // menu, submenu, options, theme, etc.
				'menu_capability'         => 'manage_options',
				'menu_icon'               => '',
				'menu_position'           => 59,
				'menu_hidden'             => false,
				'menu_parent'             => 'wp-adminify-settings',

				// Footer Credits
				'footer_text'             => ' ',
				'footer_after'            => ' ',
				'footer_credit'           => ' ',

				// menu extras
				'show_bar_menu'           => false,
				'show_sub_menu'           => false,
				'show_in_network'         => true,
				'show_in_customizer'      => false,

				'show_search'             => false,
				'show_reset_all'          => false,
				'show_reset_section'      => false,
				'show_footer'             => false,
				'show_all_options'        => false,
				'show_form_warning'       => false,
				'sticky_header'           => false,
				'save_defaults'           => false,
				'ajax_save'               => false,

				// admin bar menu settings
				'admin_bar_menu_icon'     => '',
				'admin_bar_menu_priority' => 45,

				// database model
				'database'                => 'network', // options, transient, theme_mod, network(multisite support)
				'transient_time'          => 0,

				// typography options
				'enqueue_webfont'         => false,
				'async_webfont'           => false,

				// others
				'output_css'              => false,

				// theme and wrapper classname
				'nav'                     => 'normal',
				'theme'                   => 'dark',
				'class'                   => 'wp-adminify_page_adminify-server-info',
			]
		);

		// Server Info Section
		\ADMINIFY::createSection(
			$this->prefix,
			[
				'title'  => 'Server Info',
				'icon'   => 'fas fa-rocket',
				'fields' => [

					[
						'id'    => 'server-info-tab',
						'type'  => 'tabbed',
						'title' => '',
						'tabs'  => [

							[
								'title'  => __( 'WordPress', 'adminify' ),
								'icon'   => 'fab fa-wordpress',
								'fields' => [
									[
										'id'       => 'wordpress',
										'type'     => 'callback',
										'function' => 'WPAdminify\Inc\Modules\ServerInformation\ServerInformation::jltwp_adminify_wordpress_details',
									],
								],
							],
							[
								'title'  => __( 'Server', 'adminify' ),
								'icon'   => 'fa fa-gear',
								'fields' => [
									[
										'id'       => 'server',
										'type'     => 'callback',
										'function' => 'WPAdminify\Inc\Modules\ServerInformation\ServerInformation::jltwp_adminify_server_details',
									],
								],
							],
							[
								'title'  => __( 'PHP Info', 'adminify' ),
								'icon'   => 'fa fa-gear',
								'fields' => [
									[
										'id'       => 'php_info',
										'type'     => 'callback',
										'function' => 'WPAdminify\Inc\Modules\ServerInformation\ServerInformation::jltwp_adminify_get_phpinfo',
									],
								],
							],
							[
								'title'  => __( 'MySQL', 'adminify' ),
								'icon'   => 'fa fa-gear',
								'fields' => [
									[
										'id'       => 'mysql_info',
										'type'     => 'callback',
										'function' => 'WPAdminify\Inc\Modules\ServerInformation\ServerInformation::jltwp_adminify_get_mysqlinfo',
									],
								],
							],
							[
								'title'  => __( 'Constants', 'adminify' ),
								'icon'   => 'fa fa-gear',
								'fields' => [
									[
										'id'       => 'constants',
										'type'     => 'callback',
										'function' => 'WPAdminify\Inc\Modules\ServerInformation\ServerInformation::jltwp_adminify_constant_details',
									],
								],
							],
							[
								'title'  => __( '.htaccess File', 'adminify' ),
								'icon'   => 'fa fa-gear',
								'fields' => [
									[
										'id'       => 'htaccess',
										'type'     => 'callback',
										'function' => 'WPAdminify\Inc\Modules\ServerInformation\ServerInformation::jltwp_adminify_htacces_details',
									],
								],
							],
							[
								'title'  => __( 'php.ini File', 'adminify' ),
								'icon'   => 'fa fa-gear',
								'fields' => [
									[
										'id'       => 'php_ini',
										'type'     => 'callback',
										'function' => 'WPAdminify\Inc\Modules\ServerInformation\ServerInformation::jltwp_adminify_php_ini_details',
									],
								],
							],
							[
								'title'  => __( 'Robots.txt File', 'adminify' ),
								'icon'   => 'fa fa-gear',
								'fields' => [
									[
										'id'       => 'robots_txt',
										'type'     => 'callback',
										'function' => 'WPAdminify\Inc\Modules\ServerInformation\ServerInformation::jltwp_adminify_robots_details',
									],
								],
							],
							[
								'title'  => __( 'Error Logs', 'adminify' ),
								'icon'   => 'fa fa-gear',
								'fields' => [
									[
										'id'       => 'error_logs',
										'type'     => 'callback',
										'function' => '\WPAdminify\Inc\Modules\ServerInformation\ServerInformation::jltwp_adminify_error_log_details',
									],
								],
							],

						],
					],

				],
			]
		);
	}


	/**
	 * Server Details
	 */
	public static function jltwp_adminify_server_details() {
		new ServerInfo_Server_Details();
	}


	/**
	 * WordPress Details
	 */
	public static function jltwp_adminify_wordpress_details() {
		new ServerInfo_WP_Details();
	}


	/**
	 * Constant Details
	 */
	public static function jltwp_adminify_constant_details() {
		new ServerInfo_Constant_Details();
	}

	/**
	 * .htaccess file Details
	 */
	public static function jltwp_adminify_htacces_details() {
		new ServerInfo_Htaccess_Details();
	}

	/**
	 * php.ini file Details
	 */
	public static function jltwp_adminify_php_ini_details() {
		new ServerInfo_PHP_INI_Details();
	}

	/**
	 * Robots file Details
	 */
	public static function jltwp_adminify_robots_details() {
		new ServerInfo_Robots_Details();
	}

	/**
	 * Error Logs Details
	 */
	public static function jltwp_adminify_error_log_details() {
		new ServerInfo_Error_Logs_Details();
	}

	/**
	 * Error Logs Details
	 */
	public static function jltwp_adminify_get_phpinfo() {
		if ( ! class_exists( 'DOMDocument' ) ) {
			echo '<div class="wrap" id="PHPinfo">';
			echo '<h2>' . esc_html__( 'PHP', 'adminify' ) . ' ' . esc_html( phpversion() ) . '</h2>';
			echo 'You need <a href="' . esc_url( 'http://php.net/manual/en/class.domdocument.php' ) . '" target="_blank">' . esc_html__('DOMDocument extension' , 'adminify') . '</a> to be enabled.';
			echo '</div>';
		} else {
			ob_start();
			phpinfo();
			$phpinfo = ob_get_contents();
			ob_end_clean();

			// Use DOMDocument to parse phpinfo()
			$html            = new \DOMDocument( '1.0', 'UTF-8' );
			$internal_errors = libxml_use_internal_errors( true );
			$html->loadHTML( $phpinfo );
			libxml_use_internal_errors( $internal_errors );

			// Style process
			$tables = $html->getElementsByTagName( 'table' );
			foreach ( $tables as $table ) {
				$table->setAttribute( 'class', 'widefat' );
			}

			// We only need the <body>
			$xpath = new \DOMXPath( $html );
			$body  = $xpath->query( '/html/body' );

			// Save HTML fragment
			$phpinfo_html = $html->saveXml( $body->item( 0 ) );

			echo '<div class="wrap" id="PHPinfo">';
			echo '<h2>' . esc_html__( 'PHP', 'adminify' ) . ' ' . esc_html( phpversion() ) . '</h2>';
			echo Utils::wp_kses_custom( $phpinfo_html );
			echo '</div>';
		}
	}


	/**
	 * Get MYSQL Information
	 *
	 * @return void
	 */
	public static function jltwp_adminify_get_mysqlinfo() {
		global $wpdb;
		$sqlversion = $wpdb->get_var( 'SELECT VERSION() AS version' );
		$mysqlinfo  = $wpdb->get_results( 'SHOW VARIABLES' );

		if ( is_rtl() ) { ?>
			<style type="text/css">
				#MYSQLinfo,
				#MYSQLinfo table,
				#MYSQLinfo th,
				#MYSQLinfo td {
					direction: ltr;
					text-align: left;
				}

				#MYSQLinfo h2 {
					padding: 0.5em 0 0;
				}
			</style>
			<?php
		}
		echo '<div class="wrap" id="MYSQLinfo" >' . "\n";
		echo '<h2>' . esc_html__( 'MYSQL', 'adminify' ) . ' ' . esc_html( $sqlversion ) . '</h2>';

		if ( $mysqlinfo ) {
			echo '<br class="clear" />' . "\n";
			echo '<table class="widefat" dir="ltr">' . "\n";
			echo '<thead><tr><th>' . esc_html__( 'Variable Name', 'adminify' ) . '</th><th>' . esc_html__( 'Value', 'adminify' ) . '</th></tr></thead><tbody>' . "\n";
			foreach ( $mysqlinfo as $info ) {
				echo '<tr class="" onmouseover="this.className=\'highlight\'" onmouseout="this.className=\'\'"><td>' . esc_html( $info->Variable_name ) . '</td><td>' . esc_html( htmlspecialchars( $info->Value ) ) . '</td></tr>' . "\n";
			}
			echo '</tbody></table>' . "\n";
		}
		echo '</div>' . "\n";
	}


	// Refresh Button Ajax
	public function jltwp_adminify_error_log_content_refresh() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && check_ajax_referer( 'adminify-error-logs-security-nonce', 'security' ) > 0 ) {
			if ( ! empty( $_POST['command'] ) ) {
				$action = sanitize_key( $_POST['command'] );

				$file_content = '';

				// Check for user refreshing request
				if ( $action == 'refresh_error_log' ) {

					// Get the wp "debug.log" file
					$file = ServerInfo_Error_Logs_Details::jltwp_adminify_error_log();

					// Get the wp "debug.log" file content
					$file_content = ServerInfo_Error_Logs_Details::jltwp_adminify_error_log_content( $file );
				}

				wp_send_json( [ 'file_content' => $file_content ] );
			}
		}

		die();
	}



	public function jltwp_adminify_error_log_content_clear() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && check_ajax_referer( 'adminify-error-logs-security-nonce', 'security' ) > 0 ) {
			if ( ! empty( $_POST['command'] ) ) {
				$file_content = '';
				$action       = sanitize_key( $_POST['command'] );
				// Check for user clearing request
				if ( $action == 'clear_error_log' ) {

					// Call wp file system
					global $wp_filesystem;
					WP_Filesystem();

					// Get the wp "debug.log" file
					$file = ServerInfo_Error_Logs_Details::jltwp_adminify_error_log();

					// Save no content to "debug.log" file the clear the file content
					$wp_filesystem->put_contents( $file, '', 0644 );

					// Get the wp "debug.log" file content
					$file_content = ServerInfo_Error_Logs_Details::jltwp_adminify_error_log_content( $file );
				}

				return wp_send_json( [ 'file_content' => $file_content ] );
			}
		}

		die();
	}
}
