<?php 
/**
 * Dashboard actions.
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Dashboard class.
 */
class REVIVESO_Dashboard extends REVIVESO_BaseController
{
	use REVIVESO_Fields;
	use REVIVESO_Hooker;
	use REVIVESO_Admin_Settings;
	/**
	 * Settings.
	 */
	public $settings;

	/**
	 * Callbacks.
	 */
	public $callbacks;

	/**
	 * Callback Managers.
	 */
	public $callbacks_manager;

	/**
	 * Settings pages.
	 *
	 * @var array
	 */
	public $pages = array();

	/**
	 * Register functions.
	 */
	public function register() {
		$this->settings = new REVIVESO_SettingsApi();

		$this->action( 'admin_init', 'setSettings' );

		$this->setPages();

		$this->settings->addPages( $this->pages )->withSubPage( __( 'Dashboard', 'revive-so' ) )->register();
	}

	/**
	 * Register plugin pages.
	 */
	public function setPages() {
		$manage_options_cap = apply_filters( 'reviveso_manage_options_capability', 'manage_options' );
		$this->pages = array(
			array(
				'page_title' => 'Revive.so', 
				'menu_title' => __( 'Revive.so', 'revive-so' ),
				'capability' => $manage_options_cap,
				'menu_slug'  => 'reviveso', 
				'callback'   => array( $this, 'adminDashboard' ), 
				'icon_url'   => 'dashicons-update-alt', 
				'position'   => 100,
			),
		);
	}

	public function adminDashboard() {
		$options = get_option( 'reviveso_plugin_settings' );
		$last = get_option( 'reviveso_last_global_cron_run' );
        $format = get_option( 'date_format' ) . ' @ ' . get_option( 'time_format' );
		$class_name = $this->do_filter( 'plguin_settings_class_name', '' );
		$head_tag = apply_filters( 'reviveso_dashboard_header_tag', $this->tag . '' . $this->version );
		return require_once( REVIVESO_PATH. 'templates/admin.php' );
	}

}