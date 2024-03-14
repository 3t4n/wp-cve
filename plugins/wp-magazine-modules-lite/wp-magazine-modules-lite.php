<?php
/**
 * Plugin Name:       WP Magazine Modules Lite
 * Plugin URI:		  https://wordpress.org/plugins/wp-magazine-modules-lite/
 * Description:       Ultimate plugin suitable for creating you own newspaper and magazine layouts using Gutenberg and Elementor page builder. Design magazine modules with ease and perfection!
 * Version:           1.1.0
 * Author:            CodeVibrant
 * Author URI:        http://codevibrant.com/
 * License:           GNU General Public License v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       wp-magazine-modules-lite
 * 
 * @since             1.0.0
 * @package           WP Magazine Modules Lite
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
if ( !defined( 'WPMAGAZINE_MODULES_LITE' ) ) {
	define( 'WPMAGAZINE_MODULES_LITE', 'WP Magazine Modules' );
}
define( 'WPMAGAZINE_MODULES_LITE_VERSION', '1.1.0' );
define( 'WPMAGAZINE_MODULES_LITE_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPMAGAZINE_MODULES_LITE_INCLUDES_PATH', plugin_dir_path( __FILE__ ). 'includes' );
define( 'WPMAGAZINE_MODULES_LITE_INCLUDES_URL', plugin_dir_url( __FILE__ ). 'includes' );

if ( !function_exists( 'wpmagazine_modules_lite_activation' ) ) :

	require plugin_dir_path( __FILE__ ) . 'includes/class-wpmagazine-modules-lite-activator.php';

	/**
	 * When plugin is activated.
	 *  - sets transients for firing get started notice and expires after given time.
	 */
	function wpmagazine_modules_lite_activation() {
		set_transient( 'wpmagazine-modules-lite-admin-notice', true, 5 );
		Wpmagazine_Modules_Lite_Activator::activate();
	}

	register_activation_hook( __FILE__, 'wpmagazine_modules_lite_activation' );

endif;

if ( !function_exists( 'wpmagazine_modules_lite_deactivation' ) ) :

	require plugin_dir_path( __FILE__ ) . 'includes/class-wpmagazine-modules-lite-deactivator.php';

	/**
	 * When plugin is deactivated.
	 */
	function wpmagazine_modules_lite_deactivation() {
		Wpmagazine_Modules_Lite_Deactivator::deactivate();
	}

	register_deactivation_hook( __FILE__, 'wpmagazine_modules_lite_deactivation' );

endif;

if ( !function_exists( 'wpmagazine_modules_lite_check_gutenberg' ) ) :

	/**
	 * check if gutenberg block editor is in use or not.
	 */
	function wpmagazine_modules_lite_check_gutenberg() {
		if ( function_exists( 'register_block_type' ) ) {
			define( 'WPMAGAZINE_MODULES_LITE_GUTENBERG', TRUE );
			return;
		}
		
		define( 'WPMAGAZINE_MODULES_LITE_GUTENBERG', FALSE );
		add_action( 'admin_notices', 'wpmagazine_modules_lite_gutenberg_admin_notice' );
	}

	add_action( 'plugins_loaded', 'wpmagazine_modules_lite_check_gutenberg', 99 );

endif;

if ( !function_exists( 'wpmagazine_modules_lite_gutenberg_admin_notice' ) ) :

	/**
	 * Displays the gutenberg incompatibility notices.
	 */
	function wpmagazine_modules_lite_gutenberg_admin_notice() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			esc_html__( '"%1$s" in your site. "%2$s" need be installed and activated for better compatibility.', 'wp-magazine-modules-lite' ),
			'<strong>' . esc_html__( 'Gutenberg is not installed.', 'wp-magazine-modules-lite' ) . '</strong>',
			'<strong>' . esc_html__( 'Gutenberg Blocks', 'wp-magazine-modules-lite' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

endif;

if ( !function_exists( 'wpmagazine_modules_lite_check_elementor' ) ) :

	/**
	 * check if elementor is active or not.
	 */
	function wpmagazine_modules_lite_check_elementor() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			define( 'WPMAGAZINE_MODULES_LITE_ELEMENTOR', FALSE );
			return;
		}
		define( 'WPMAGAZINE_MODULES_LITE_ELEMENTOR', TRUE );
	}

	add_action( 'plugins_loaded', 'wpmagazine_modules_lite_check_elementor', 99 );

endif;

if ( !function_exists( 'wpmagazine_modules_lite_admin_notice' ) ) :

	/**
	 * Admin notices to get started with plugin, called after plugin activation.
	 * 
	 */
	function wpmagazine_modules_lite_admin_notice() {
		if ( get_transient( 'wpmagazine-modules-lite-admin-notice' ) ) {
	?>
			<div id="wpmagazine-modules-lite-message" class="wpmagazine-modules-lite-message notice notice-info is-dismissible">
				<p><?php printf( esc_html__( 'Thank you for choosing %1$s! To fully take advantage of the best our plugin can offer please make sure you visit our %2$s dashboard page %3$s.', 'wp-magazine-modules-lite' ), esc_html( WPMAGAZINE_MODULES_LITE ), '<a href="'.esc_url( admin_url( 'admin.php?page=wpmagazine-modules-lite' ) ).'">', '</a>' ); ?></p>
				<p><a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=wpmagazine-modules-lite' ) ); ?>"><?php echo esc_html__( 'Get started', 'wp-magazine-modules-lite' ); ?></a></p>
			</div>
	<?php
			delete_transient( 'wpmagazine-modules-lite-admin-notice' );
		}
	}

endif;

if ( !function_exists( 'wpmagazine_modules_lite_run' ) ) :

	/**
	 * Execution of the plugin.
	 *
	 * @since    1.0.0
	 */
	function wpmagazine_modules_lite_run() {
		/**
		 * defines plugin functioning codes ( internationalization, admin-specific hooks, and public-facing site hooks )
		 */
		require plugin_dir_path( __FILE__ ) . 'includes/class-wpmagazine-modules-lite.php';
		require plugin_dir_path( __FILE__ ) . 'admin/class-wpmagazine-modules-lite-admin.php';
		add_action( 'admin_notices', 'wpmagazine_modules_lite_admin_notice' );
		$instance = Wpmagazine_Modules_Lite::instance();
	}

	add_action( 'plugins_loaded', 'wpmagazine_modules_lite_run' );
	
endif;