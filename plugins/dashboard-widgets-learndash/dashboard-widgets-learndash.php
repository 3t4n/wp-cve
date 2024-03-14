<?php

/**
 * Plugin Name:       Dashboard Widgets for LearnDash
 * Description:       Simple, informative, beautifully-designed Dashboard widgets for your LearnDash-powered site. Quick stats at a glance &amp; helpful links for faster admin navigation.
 * Version:           1.3
 * Author:            Escape Creative
 * Author URI:        https://escapecreative.com/
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       dashboard-widgets-learndash
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * LearnDash Dependency Check
 * Must have LearnDash active. Otherwise, deactivate plugin.
 * @link https://wordpress.stackexchange.com/questions/127818/how-to-make-a-plugin-require-another-plugin
 */
add_action( 'admin_init', 'dwfl_learndash_check' );

function dwfl_learndash_check() {

	if ( is_admin() && current_user_can( 'activate_plugins' ) && ! class_exists( 'SFWD_LMS' ) ) {

		add_action( 'admin_notices', 'dwfl_activate_plugin_notice' );

		deactivate_plugins( plugin_basename( __FILE__ ) );

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	} // end if is_plugin_active
} // end dwfl_learndash_check()

function dwfl_activate_plugin_notice() { ?>
	<div class="notice notice-error is-dismissible">
		<p><strong>Error:</strong> Please install &amp; activate the LearnDash plugin before you can use Dashboard Widgets for LearnDash.</p>
	</div>
<?php }


/**
 * Current plugin version.
 * Start at version 1.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DWFL_DASHBOARD_WIDGETS_LEARNDASH_VERSION', '1.3' );


/**
 * Define Constants
 */
define( 'DWFL_DASHBOARD_WIDGETS_LEARNDASH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );


/**
 * Add Plugin Action Link to Dashboard to view widgets.
 *
 * @since 1.0
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
 */
add_filter( 'plugin_action_links', 'dwfl_add_plugin_action_links', 10, 5 );

function dwfl_add_plugin_action_links( $actions, $plugin_file ) {
	static $plugin;

	if (!isset($plugin))
		$plugin = plugin_basename(__FILE__);

	if ($plugin == $plugin_file) {

		$open_dashboard = array('customizer' => '<a href="' . esc_url( admin_url() ) . '">' . __( 'View Widgets', 'dashboard-widgets-learndash' ) . '</a>');

		$actions = array_merge($open_dashboard, $actions);

	}

	return $actions;
}


/**
 * Load CSS
 */
add_action( 'admin_enqueue_scripts', 'dwfl_enqueue_scripts' );

function dwfl_enqueue_scripts( $hook ) {

	$screen = get_current_screen();

	if ( 'dashboard' === $screen->id ) {

		wp_enqueue_style( 'dwfl_admin_css', plugin_dir_url( __FILE__ ) . 'assets/css/dashboard-widgets.css', array(), '1.3' );

	}

}


/**
 * Include Dashboard Widgets
 */
add_action( 'init', 'dwfl_load_widgets' );

function dwfl_load_widgets() {

	/**
	 * LearnDash Links
	 * Check User Capabilities
	 * Only show widget for admin role
	 * @TODO - change this once LD fixes their roles/capabilities mapping
	 */
	if ( current_user_can( 'edit_dashboard' ) ) :

		include( plugin_dir_path( __FILE__ ) . 'inc/widgets/learndash-links.php' );

	endif;

	/**
	 * LearnDash Overivew
	 * Check User Capabilities
	 * Only show widget for admin role
	 * @TODO - change this once LD fixes their roles/capabilities mapping
	 */
	if ( current_user_can( 'edit_dashboard' ) ) :

		include( plugin_dir_path( __FILE__ ) . 'inc/widgets/learndash-overview.php' );

	endif;

	/**
	 * LearnDash Courses
	 * Check User Capabilities
	 * Only show widget for admin role
	 * @TODO - change this once LD fixes their roles/capabilities mapping
	 */
	if ( current_user_can( 'edit_dashboard' ) ) :

		include( plugin_dir_path( __FILE__ ) . 'inc/widgets/learndash-courses.php' );

	endif;

	/**
	 * LearnDash Recently Updated
	 * Check User Capabilities
	 * Only show widget for admin role
	 * @TODO - change this once LD fixes their roles/capabilities mapping
	 */
	if ( current_user_can( 'edit_dashboard' ) && current_user_can( 'edit_courses' ) ) :

		include( plugin_dir_path( __FILE__ ) . 'inc/widgets/learndash-recently-updated.php' );

	endif;

}