<?php
/**
 * Plugin Name: WP Activity Log for Yoast SEO
 * Plugin URI: https://melapress.com/wordpress-activity-log/
 * Description: A WP Activity Log plugin extension for Yoast SEO
 * Text Domain: activity-log-wp-seo
 * Author: Melapress
 * Author URI: https://melapress.com/
 * Version: 1.3.1
 * License: GPL2
 * Network: true
 *
 * @package Wsal
 * @subpackage Wsal Custom Events Loader
 */

use WSAL\Helpers\Classes_Helper;

/*
	Copyright(c) 2023  Melapress  (email : info@melapress.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
	REQUIRED. Here we include and fire up the main core class. This will be needed regardless so be sure to leave line 37-39 in tact.
*/
require_once plugin_dir_path( __FILE__ ) . 'core/class-extension-core.php';
$wsal_extension = new WPWhiteSecurity\ActivityLog\Extensions\Common\Core( __FILE__, 'activity-log-wp-seo' );


/**
 * Adds new custom event objects for our plugin
 *
 * @method wsal_yoast_seo_extension_add_custom_event_objects
 * @since  1.0.0
 * @param  array $objects An array of default objects.
 * @return array
 */
function wsal_yoast_seo_extension_add_custom_event_objects( $objects ) {
	$new_objects = array(
		'yoast-seo'                   => esc_html__( 'Yoast SEO', 'wp-security-audit-log' ),
		'yoast-seo-metabox'           => esc_html__( 'Yoast SEO Meta Box', 'wp-security-audit-log' ),
		'yoast-seo-search-appearance' => esc_html__( 'Yoast SEO Search Appearance', 'wp-security-audit-log' ),
		'yoast-seo-redirects'         => esc_html__( 'Yoast SEO Redirects', 'wp-security-audit-log' ),
	);

	// combine the two arrays.
	$objects = array_merge( $objects, $new_objects );

	return $objects;
}

/**
 * Add specific events so we can use them for category titles.
 *
 * @param  array $sub_category_events - Current event list.
 * @return array $sub_category_events - Appended list.
 */
function wsal_yoast_seo_extension_togglealerts_sub_category_events( $sub_category_events ) {
	$new_events          = array( 8813, 8815, 8838 );
	$sub_category_events = array_merge( $sub_category_events, $new_events );
	return $sub_category_events;
}

/**
 * Add sub cateogry titles to ToggleView page in WSAL.
 *
 * @param  string $subcat_title - Original title.
 * @param  int    $alert_id - Alert ID.
 * @return string $subcat_title - New title.
 */
function wsal_yoast_seo_extension_togglealerts_sub_category_titles( $subcat_title, $alert_id ) {
	if ( 8815 === $alert_id ) {
		$subcat_title = esc_html_e( 'Features:', 'wp-security-audit-log' );
	} elseif ( 8813 === $alert_id ) {
		$subcat_title = esc_html_e( 'Search Appearance', 'wp-security-audit-log' );
	} elseif ( 8838 === $alert_id ) {
		$subcat_title = esc_html_e( 'Multisite network', 'wp-security-audit-log' );
	}
	return $subcat_title;
}

/**
 * If a user is running an older version of WSAL, they will see a "duplicate event" error.
 * This function checks and runs a filter to replace that notice. Its done via JS as we cant
 * currently give this notice a neat ID/class.
 */
function wsal_yoast_seo_extension_replace_duplicate_event_notice() {
	$wsal_version = get_site_option( 'wsal_version' );
	if ( version_compare( $wsal_version, '4.1.3.2', '<=' ) ) {
		add_action( 'admin_footer', 'wsal_yoast_seo_extension_replacement_duplicate_event_notice' );
	}
}

/**
 * Add obsolete events to the togglealerts view.
 *
 * @param  array $obsolete_events - Current events.
 * @return array $obsolete_events - Appended events.
 */
function wsal_yoast_seo_extension_togglealerts_obsolete_events( $obsolete_events ) {
	$new_events      = array( 8810, 8811 );
	$obsolete_events = array_merge( $obsolete_events, $new_events );
	return $obsolete_events;
}

/**
 * Replacement "duplicate event" notice text.
 */
function wsal_yoast_seo_extension_replacement_duplicate_event_notice() {
	$replacement_text = esc_html__( 'You are running an old version of WP Activity Log. Please update the plugin to run it alongside this extension: Yoast SEO', 'wp-security-audit-log' );
	?>
	<script type="text/javascript">
		if ( jQuery( '.notice.notice-error span[style="color:#dc3232; font-weight:bold;"]' ).length ) {
			jQuery( '.notice.notice-error span[style="color:#dc3232; font-weight:bold;"]' ).parent().text( '<?php echo esc_html( $replacement_text ); ?>' );
		}
	</script>
	<?php
}


/**
 * Add our filters.
 */
add_filter( 'wsal_event_objects', 'wsal_yoast_seo_extension_add_custom_event_objects' );
add_filter( 'wsal_togglealerts_sub_category_events', 'wsal_yoast_seo_extension_togglealerts_sub_category_events' );
add_filter( 'wsal_togglealerts_sub_category_titles', 'wsal_yoast_seo_extension_togglealerts_sub_category_titles', 10, 2 );
add_filter( 'admin_init', 'wsal_yoast_seo_extension_replace_duplicate_event_notice' );
add_filter( 'wsal_togglealerts_obsolete_events', 'wsal_yoast_seo_extension_togglealerts_obsolete_events' );

add_action(
	'wsal_sensors_manager_add',
	/**
	* Adds sensors classes to the Class Helper
	*
	* @return void
	*
	* @since latest
	*/
	function () {
		require_once __DIR__ . '/wp-security-audit-log/sensors/class-yoast-seo-sensor.php';

		Classes_Helper::add_to_class_map(
			array(
				'WSAL\\Plugin_Sensors\\Yoast_SEO_Sensor' => __DIR__ . '/wp-security-audit-log/sensors/class-yoast-seo-sensor.php',
			)
		);
	}
);

add_action(
	'wsal_custom_alerts_register',
	/**
	* Adds sensors classes to the Class Helper
	*
	* @return void
	*
	* @since latest
	*/
	function () {
		require_once __DIR__ . '/wp-security-audit-log/class-yoast-custom-alerts.php';

		Classes_Helper::add_to_class_map(
			array(
				'WSAL\\Custom_Alerts\\Yoast_Custom_Alerts' => __DIR__ . '/wp-security-audit-log/class-yoast-custom-alerts.php',
			)
		);
	}
);
