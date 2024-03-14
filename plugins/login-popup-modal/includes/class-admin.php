<?php
/**
 * Login modal box Admin.
 *
 * @since   0.0.0
 * @package Login_Modal_Box
 */

/**
 * Login modal box Admin.
 *
 * @since 0.0.0
 */
class LMB_Admin {
	/**
	 * Parent plugin class.
	 *
	 * @since 0.0.0
	 *
	 * @var   Login_Modal_Box
	 */
	protected $plugin = null;

	/**
	 * Plugin title
	 *
	 * @var    string
	 * @since  0.0.0
	 */
	protected $title = 'All Login Form';

	/**
	 * Plugin menu title
	 *
	 * @var    string
	 * @since  0.0.0
	 */
	protected $menu_title = 'All Login Form';

	/**
	 * Constructor.
	 *
	 * @since  0.0.0
	 *
	 * @param  Login_Modal_Box $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}


	/**
	 * Initiate our hooks.
	 *
	 * @since  0.0.0
	 */
	public function hooks() {

		if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		// add menu page.
		add_action( 'admin_menu', array( $this, 'add_control_menu' ) );

		// action to save settings.
		add_action( 'admin_post_save_settings', array( $this, 'save_settings' ) );

		// add settings link to plugin menu.
		add_filter( 'plugin_action_links_' . $this->plugin->basename, array( $this, 'plugin_add_settings_link' ) );
	}

	/**
	 * Save settings.
	 *
	 * @since  0.0.0
	 */
	public function save_settings() {

		if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'login-modal-box' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'login-modal-box' ) );
		}

		// Header title and settings.
		$this->plugin->settings->update_setting( 'header-title', sanitize_text_field( $_POST['header-title'] ) );
		$this->plugin->settings->update_setting( 'login-id', is_numeric( $_POST['login-id'] ) ? sanitize_text_field( $_POST['login-id'] ) : 0 );
		$this->plugin->settings->update_setting( 'logout-id', is_numeric( $_POST['logout-id'] ) ? sanitize_text_field( $_POST['logout-id'] ) : 0 );

		// Menu location.
		$this->plugin->settings->update_setting( 'menu-location', ( $_POST['menu-location'] != '0') ? sanitize_text_field( $_POST['menu-location'] ) : null );

		// set message and redirect.
		$message = 'saved';
		if ( wp_redirect(
			add_query_arg(
				array(
					'page'      => 'login-modal-box',
					'tab'       => 'configurar',
					'message'   => $message,
				),
				admin_url( 'admin.php' )
			)
		) ) {
			exit;
		}
	}

	/**
	 * Add main menu
	 *
	 * @since  0.0.0
	 */
	public function add_control_menu() {
		add_theme_page(
			$this->title,
			$this->menu_title,
			'manage_options',
			'login-modal-box',
			array( $this, 'admin_page_display' )
		);
	}

	/**
	 * Add admin user interface
	 *
	 * @since  0.0.0
	 */
	public function admin_page_display() {
		$this->plugin->ui->options_ui();
	}

	/**
	 * Add admin user interface
	 *
	 * @since  0.0.0
	 * @param  array $links Links
	 */
	public function plugin_add_settings_link( $links ) {
		$settings = sprintf( '<a href="themes.php?page=login-modal-box">%s</a>', esc_html__( 'Settings', 'login-modal-box' ) );

		if ( ! empty( $links ) ) {
			array_unshift( $links, $settings );
		} else {
			$links = array( $settings );
		}
		return $links;
	}

}
