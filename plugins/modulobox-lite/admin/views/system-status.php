<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$base = new ModuloBox_Base();

$wp_version  = get_bloginfo( 'version' );
$php_version = $base::get_phpversion();

$environments = array(
	__( 'WordPress Environment', 'modulobox' ) => array(
		__( 'WP Version', 'modulobox' ) => array(
			'status' => 'v' . $wp_version,
			'state'  => version_compare( $wp_version, '4.5.0', '>=' ) ? 'yes' : 'no',
			'info'   => __( 'The version of WordPress installed on your site', 'modulobox' )
		),
		__( 'WP Multisite', 'modulobox' ) => array(
			'status' => is_multisite() ? __( 'Yes', 'modulobox' ) : __( 'No', 'modulobox' ),
			'state'  => 'none',
			'info'   => __( 'Whether or not you have WordPress Multisite enabled', 'modulobox' )
		),
		__( 'WP Active Plugins', 'modulobox' ) => array(
			'status' => count( $base::get_active_plugins() ),
			'state'  => 'none',
			'info'   => __( 'The number of plugins currently activated on your site', 'modulobox' )
		),
		__( 'WP Debug Mode', 'modulobox' ) => array(
			'status' => $base::get_debug_mode() ? __( 'Yes', 'modulobox' ) : __( 'No', 'modulobox' ),
			'state'  => 'none',
			'info'   => __( 'When activated, PHP errors, notices and warnings will be displayed on your site', 'modulobox' )
		),
		__( 'WP Memory Usage', 'modulobox' ) => array(
			'status' => $base::get_memory_usage() . ' / ' . $base::get_memory_limit(),
			'state'  => 'none',
			'info'   => __( 'Amount of PHP memory currently used in your admin dashboard', 'modulobox' )
		),
		__( 'WP Max Upload Size', 'modulobox' ) => array(
			'status' => $base::get_max_upload_size(),
			'state'  => 'none',
			'info'   => __( 'The largest file size that can be uploaded to your WordPress installation', 'modulobox' )
		)
	),
	__( 'Server Environment', 'modulobox' ) => array(
		__( 'Server Info', 'modulobox' ) => array(
			'status' => $base::get_server_software(),
			'state'  => 'none',
			'info'   => __( 'Information about the web server that is currently hosting your site', 'modulobox' )
		),
		__( 'PHP version', 'modulobox' ) => array(
			'status' => 'v' . $php_version,
			'state'  => version_compare( $php_version, '5.3.0', '>=' ) ? 'yes' : 'no',
			'info'   => __( 'The version of PHP installed on your hosting server', 'modulobox' )
		),
		__( 'PHP Post Max Size', 'modulobox' ) => array(
			'status' => $base::get_post_max_size(),
			'state'  => 'none',
			'info'   => __( 'Maximum size of post data that can be contained in a page', 'modulobox' )
		),
		__( 'PHP Execution Time', 'modulobox' ) => array(
			'status' => $base::get_max_execution_time(),
			'state'  => 'none',
			'info'   => __( 'Maximum time in seconds a script is allowed to run before it is terminated by the parser', 'modulobox' )
		),
		__( 'PHP Max Input Vars', 'modulobox' ) => array(
			'status' => $base::get_max_input_vars(),
			'state'  => 'none',
			'info'   => __( 'The maximum number of variables your server can use for a single function to avoid overloads', 'modulobox' )
		)
	)
);

echo '<div class="mobx-tab-content mobx-system-status-content">';

	echo '<h2>' . esc_html__( 'System Status', 'modulobox' ) . '</h2>';
	echo '<p>';
		esc_html_e( 'The ModuloBox System Status report can be useful for troubleshooting issues with your site.', 'modulobox' );
		echo '<br>';
		esc_html_e( 'ModuloBox requires WordPress 4.5.0 and PHP 5.3.0 at minium.', 'modulobox' );
	echo '</p>';

	echo '<h3>ModuloBox</h3>';
	do_settings_sections( 'debug' );

	foreach( $environments as $environment => $info ) {

		echo '<h3>' . esc_html( $environment ) . '</h3>';

		echo '<table class="form-table mobx-status-table">';

			echo '<tbody>';

				foreach( $info as $label => $val ) {

					echo '<tr>';

						echo '<th scope="row">' . esc_html( $label ) . '</th>';

						echo '<td>';
							echo '<span class="mobx-state-' . sanitize_html_class( $val['state'] ) . '">' . esc_html( $val['status'] ) . '</span>';
							echo '<span class="mobx-info-desc"></span>';
							echo '<p class="mobx-field-desc">';
								echo '<span>' . esc_html( $val['info'] ) . '</span>';
							echo '</p>';
						echo '</td>';

					echo '</tr>';
				}

			echo '</tbody>';

		echo '</table>';

	}

	include( MOBX_ADMIN_PATH . 'views/info-bar.php' );

echo '</div>';
