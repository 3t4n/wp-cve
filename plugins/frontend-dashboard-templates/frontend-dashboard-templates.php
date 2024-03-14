<?php
/**
 * Plugin Name: Frontend Dashboard Templates
 * Plugin URI:
 * Description: Frontend Dashboard Pages is a plugin to show pages inside the Frontend Dashboard menu. The assigning page may contain content, images and even shortcodes
 * Version: 1.8
 * Author: vinoth06
 * Author URI: http://buffercode.com/
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: frontend-dashboard-templates
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$fed_check = get_option( 'fed_plugin_version' );

require_once ABSPATH . 'wp-admin/includes/plugin.php';
if ( $fed_check && is_plugin_active( 'frontend-dashboard/frontend-dashboard.php' ) ) {

	/**
	 * Version Number
	 */
	define( 'FED_TEMPLATES_PLUGIN_VERSION', '1.8' );

	/**
	 * App Name
	 */
	define( 'FED_TEMPLATES_APP_NAME', 'Frontend Dashboard Templates' );

	/**
	 * Root Path
	 */
	define( 'FED_TEMPLATES_PLUGIN', __FILE__ );
	/**
	 * Plugin Base Name
	 */
	define( 'FED_TEMPLATES_PLUGIN_BASENAME', plugin_basename( FED_TEMPLATES_PLUGIN ) );
	/**
	 * Plugin Name
	 */
	define( 'FED_TEMPLATES_PLUGIN_NAME', trim( dirname( FED_TEMPLATES_PLUGIN_BASENAME ), '/' ) );
	/**
	 * Plugin Directory
	 */
	define( 'FED_TEMPLATES_PLUGIN_DIR', untrailingslashit( dirname( FED_TEMPLATES_PLUGIN ) ) );


	require_once FED_TEMPLATES_PLUGIN_DIR . '/FEDT_Hooks.php';
	require_once FED_TEMPLATES_PLUGIN_DIR . '/FEDT_Page_Loader.php';
	require_once FED_TEMPLATES_PLUGIN_DIR . '/function.php';
} else {
	function fed_global_admin_notification_template() {
		?>
		<div class="notice notice-warning">
			<p>
				<b>
					<?php
					_e( 'Please install <a href="https://buffercode.com/plugin/frontend-dashboard">Frontend Dashboard</a> to use this plugin [Frontend Dashboard Template]', 'frontend-dashboard-templates' );
					?>
				</b>
			</p>
		</div>
		<?php
	}

	add_action( 'admin_notices', 'fed_global_admin_notification_template' );
	?>
	<?php
}
