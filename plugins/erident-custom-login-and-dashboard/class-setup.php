<?php
/**
 * Setup Custom Login Dashboard plugin.
 *
 * @package Custom_Login_Dashboard
 */

namespace CustomLoginDashboard;

/**
 * Setup Better Admin Bar.
 */
class Setup {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Get instance of the class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Init the class setup.
	 */
	public static function init() {
		self::$instance = new self();

		add_action( 'plugins_loaded', array( self::$instance, 'setup' ) );
	}

	/**
	 * Setup action & filter hooks.
	 */
	public function __construct() {}

	/**
	 * Setup action & filters.
	 */
	public function setup() {

		add_action( 'init', array( $this, 'setup_text_domain' ) );
		add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 10, 4 );
		add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ), 20 );

		// Process export-import.
		add_action( 'admin_init', array( $this, 'process_export' ) );
		add_action( 'admin_init', array( $this, 'process_import' ) );

		// Migration stuff.
		add_action( 'admin_enqueue_scripts', array( $this, 'migration_notice_scripts' ) );
		add_action( 'admin_notices', array( $this, 'migration_notice' ) );

		// Ajax handlers.
		new Ajax\Save_Settings();
		new Ajax\Reset_Settings();
		new Ajax\Load_Default_Settings();
		new Ajax\Migration();

	}

	/**
	 * Setup textdomain.
	 */
	public function setup_text_domain() {

		load_plugin_textdomain( 'erident-custom-login-and-dashboard', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

	}

	/**
	 * Add settings link to plugin list page.
	 *
	 * @param array  $actions     An array of plugin action links.
	 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
	 * @param array  $plugin_data An array of plugin data. See `get_plugin_data()`.
	 * @param string $context     The plugin context. By default this can include 'all', 'active', 'inactive',
	 *                            'recently_activated', 'upgrade', 'mustuse', 'dropins', and 'search'.
	 *
	 * @return array The modified plugin action links.
	 */
	public function add_settings_link( $actions, $plugin_file, $plugin_data, $context ) {

		if ( CUSTOM_LOGIN_DASHBOARD_PLUGIN_BASENAME === $plugin_file ) {
			$support_link = '<a href="https://wordpress.org/support/plugin/erident-custom-login-and-dashboard/" target="_blank">' . __( 'Support', 'erident-custom-login-and-dashboard' ) . '</a>';

			array_unshift( $actions, $support_link );

			$settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=erident-custom-login-and-dashboard' ) ) . '">' . __( 'Settings', 'erident-custom-login-and-dashboard' ) . '</a>';

			array_unshift( $actions, $settings_link );
		}

		return $actions;

	}

	/**
	 * Add submenu under "Settings" menu item.
	 */
	public function add_submenu_page() {

		add_options_page( __( 'Custom Login & Dashboard', 'erident-custom-login-and-dashboard' ), __( 'Custom Login & Dashboard', 'erident-custom-login-and-dashboard' ), 'administrator', 'erident-custom-login-and-dashboard', [ $this, 'page_output' ] );

	}

	/**
	 * Better Admin Bar page output.
	 */
	public function page_output() {

		$output = require __DIR__ . '/templates/settings-template.php';
		$output();

	}

	/**
	 * Enqueue admin styles & scripts.
	 */
	public function admin_scripts() {

		$current_screen = get_current_screen();

		if ( 'settings_page_erident-custom-login-and-dashboard' !== $current_screen->id ) {
			return;
		}

		if ( function_exists( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		} else {
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_script( 'thickbox' );
		}

		// CSS dependencies.

		// WP Color picker dependency.
		wp_enqueue_style( 'wp-color-picker' );

		// Settings page styling.
		wp_enqueue_style( 'heatbox', CUSTOM_LOGIN_DASHBOARD_PLUGIN_URL . '/assets/css/heatbox.css', array(), CUSTOM_LOGIN_DASHBOARD_PLUGIN_VERSION );

		// Custom Login Dashboard admin styling.
		wp_enqueue_style( 'custom-login-dashboard', CUSTOM_LOGIN_DASHBOARD_PLUGIN_URL . '/assets/css/admin.css', array(), CUSTOM_LOGIN_DASHBOARD_PLUGIN_VERSION );

		// JS dependencies.

		// Color picker alpha.
		wp_enqueue_script( 'wp-color-picker-alpha', CUSTOM_LOGIN_DASHBOARD_PLUGIN_URL . '/assets/js/wp-color-picker-alpha.js', array( 'jquery', 'wp-color-picker', 'wp-i18n' ), '2.1.3', true );

		// Settings page scripts.
		wp_enqueue_script( 'custom-login-dashboard', CUSTOM_LOGIN_DASHBOARD_PLUGIN_URL . '/assets/js/settings-page.js', array( 'wp-color-picker' ), CUSTOM_LOGIN_DASHBOARD_PLUGIN_VERSION, true );

		wp_localize_script(
			'custom-login-dashboard',
			'CustomLoginDashboard',
			array(
				'nonces'  => array(
					'saveSettings'        => wp_create_nonce( 'cldashboard_nonce_save_settings' ),
					'resetSettings'       => wp_create_nonce( 'cldashboard_nonce_reset_settings' ),
					'loadDefaultSettings' => wp_create_nonce( 'cldashboard_nonce_load_default_settings' ),
				),
				'dialogs' => array(
					'resetSettingsConfirmation'       => __( 'Are you sure you want to delete all settings?', 'erident-custom-login-and-dashboard' ),
					'loadDefaultSettingsConfirmation' => __( 'Are you sure you want to reset all settings?', 'erident-custom-login-and-dashboard' ),
				),
			)
		);

		// This handle enqueue already from v3.5.9, let's keep it just in case someone is using it.
		wp_enqueue_script( 'wp_erident_dashboard-script2' );
		wp_enqueue_script( 'wp_erident_dashboard-script' );

	}

	/**
	 * Modify admin body class.
	 *
	 * @param string $classes The class names.
	 */
	public function admin_body_class( $classes ) {

		$current_user = wp_get_current_user();
		$classes     .= ' custom-login-dashboard-user-' . $current_user->user_nicename;

		$roles = $current_user->roles;
		$roles = $roles ? $roles : array();

		foreach ( $roles as $role ) {
			$classes .= ' custom-login-dashboard-role-' . $role;
		}

		$screens = array(
			'settings_page_erident-custom-login-and-dashboard',
		);

		$screen = get_current_screen();

		if ( ! in_array( $screen->id, $screens, true ) ) {
			return $classes;
		}

		$classes .= ' heatbox-admin has-header';

		return $classes;

	}

	/**
	 * Process widget export.
	 */
	public function process_export() {

		if ( empty( $_POST['er_action'] ) || 'export_settings' != $_POST['er_action'] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['er_export_nonce'], 'er_export_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$exporter = new Helpers\Export();

		$exporter->export();

	}

	/**
	 * Process widget import.
	 */
	public function process_import() {

		if ( empty( $_POST['er_action'] ) || 'import_settings' != $_POST['er_action'] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['er_import_nonce'], 'er_import_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$importer = new Helpers\Import();

		$importer->import();

	}

	/**
	 * Enqueue the migration scripts.
	 */
	public function migration_notice_scripts() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		wp_enqueue_script('updates');

		wp_enqueue_style( 'cldashboard-migration', CUSTOM_LOGIN_DASHBOARD_PLUGIN_URL . '/assets/css/migration.css', array(), CUSTOM_LOGIN_DASHBOARD_PLUGIN_VERSION );

		wp_enqueue_script( 'cldashboard-migration', CUSTOM_LOGIN_DASHBOARD_PLUGIN_URL . '/assets/js/migration.js', array( 'jquery' ), CUSTOM_LOGIN_DASHBOARD_PLUGIN_VERSION, true );

		$old_plugin_slug     = 'erident-custom-login-and-dashboard';
		$old_plugin_basename = $old_plugin_slug . '/er-custom-login.php';

		$new_plugin_slug     = 'ultimate-dashboard';
		$new_plugin_basename = $new_plugin_slug . '/' . $new_plugin_slug . '.php';

		$activation_url = add_query_arg(
			array(
				'action'        => 'activate',
				'plugin'        => rawurlencode( $new_plugin_basename ),
				'plugin_status' => 'all',
				'paged'         => '1',
				'_wpnonce'      => wp_create_nonce( 'activate-plugin_' . $new_plugin_basename ),
			),
			esc_url( network_admin_url( 'plugins.php' ) )
		);

		$js_objects = array(
			'redirectUrl' => admin_url( 'edit.php?post_type=udb_widgets&page=udb_plugin_onboarding' ),
			'oldPlugin'   => [
				'slug'     => $old_plugin_slug,
				'basename' => $old_plugin_basename,
			],
			'newPlugin'   => [
				'slug'          => $new_plugin_slug,
				'basename'      => $new_plugin_basename,
				'activationUrl' => $activation_url,
			],
			'nonces'      => array(
				'migration' => wp_create_nonce( 'cldashboard_nonce_migration' ),
			),
		);

		wp_localize_script(
			'cldashboard-migration',
			'CldashboardMigration',
			$js_objects
		);

	}

	/**
	 * Notice about migration to "Ultimate Dashboard".
	 */
	public function migration_notice() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		?>

		<div class="notice notice-error cldashboard-migration-notice is-dismissible" style="border: 1px solid #e5e5e5;">

			<div class="notice-body">
				<div class="notice-icon">
					<img src="<?php echo esc_url( CUSTOM_LOGIN_DASHBOARD_PLUGIN_URL ); ?>/assets/images/erident-logo.png">
				</div>
				<div class="notice-content">
					<h2 style="font-size: 24px; font-weight: 400;">Erident is now Ultimate Dashboard!</h2>

					<p style="font-size: 16px; opacity: .7;">
						Migrate to Ultimate Dashboard today & unlock more powerful features (for free)!
					</p>

					<hr style="border-color: #eee; border-top: none;">

					<img style="max-width: 900px; width:  100%; margin-bottom: 10px;" src="<?php echo esc_url( CUSTOM_LOGIN_DASHBOARD_PLUGIN_URL ); ?>/assets/images/before-after.png">

					<p style="margin-bottom: -3px;">
						<strong style="color: #1d2327; font-size: 16px;">What does this mean for me?</strong>
					</p>

					<p>
						It means a much better (live editing) experience when customizing your WordPress login page!<br> Not only that, there will be even more options available for you to fully customize your login screen.
					</p>

					<p style="margin-bottom: -3px;">
						<strong style="color: #1d2327; font-size: 16px;">What happens to my existing customizations?</strong>
					</p>

					<p>
						Don't worry! Your existing settings will stay in place.<br> All Erident settings will be migrated over to Ultimate Dashboard.
					</p>

					<p style="margin-bottom: -3px;">
						<strong style="color: #1d2327; font-size: 16px;">Why NOT just keep Erident?</strong>
					</p>

					<p style="color: tomato; font-weight: 700; opacity: .8;">
						Erident Custom Login & Dashboard is no longer actively supported.<br> To keep getting feature-updates & security fixes please upgrade to Ultimate Dashboard.
					</p>

					<p>
						Please click the button below to safely migrate to Ultimate Dashboard.
					</p>

					<p>
						<a href="" style="padding: 10px 40px;" class="button button-primary cldashboard-button cldashboard-migration-button">
							Start One-Click Migration
						</a>
						<a style="margin: 10px;" href="https://ultimatedashboard.io/blog/erident-custom-login-dashboard-is-now-ultimate-dashboard/?utm_source=erident&utm_medium=admin-notice&utm_campaign=udb" target="_blank">Read the announcement post</a>
					</p>

					<div class="cldashboard-migration-statuses">
						<div class="cldashboard-migration-status migration-failed">
							<i class="dashicons dashicons-no"></i>
							<span>Migration failed:</span> <span class="error-message"></span>
						</div>
						<div class="cldashboard-migration-status cldashboard-uninstalled">
							<span class="loader"></span>
							<i class="dashicons dashicons-yes"></i>
							<span class="process-message">Old Swift Control is uninstalled.</span>
						</div>
						<div class="cldashboard-migration-status ultimate-dashboard-installed">
							<span class="loader"></span>
							<i class="dashicons dashicons-yes"></i>
							<span class="process-message">New Better Admin Bar is installed.</span>
						</div>
						<div class="cldashboard-migration-status ultimate-dashboard-activated">
							<span class="loader"></span>
							<i class="dashicons dashicons-yes"></i>
							<span class="process-message">New Better Admin Bar is activated.</span>
						</div>
					</div>
				</div>
			</div>

		</div>

		<?php

	}

}
