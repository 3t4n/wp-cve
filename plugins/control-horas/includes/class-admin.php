<?php
/**
 * Control de horas Admin.
 *
 * @since   0.0.0
 * @package Control_Horas
 */

/**
 * Control de horas Admin.
 *
 * @since 0.0.0
 */
class CH_Admin {
	/**
	 * Parent plugin class.
	 *
	 * @var   class
	 * @since 0.0.0
	 */
	protected $plugin = null;

	/**
	 * Plugin title
	 *
	 * @var    string
	 * @since  0.0.0
	 */
	protected $title = 'Control de horas';

	/**
	 * Plugin menu title
	 *
	 * @var    string
	 * @since  0.0.0
	 */
	protected $menu_title = 'Control de horas';

	/**
	 * This is the key
	 *
	 * @var    string
	 * @since  0.0.0
	 */
	protected $key = 'controlhoras';

	/**
	 * Constructor.
	 *
	 * @since  0.0.0
	 *
	 * @param  object $plugin Main plugin object.
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

		if ( ! is_admin() ) {
			return false;
		}

		// setup page.
		add_action( 'admin_post_control_horas_setup', array( $this, 'user_setup' ) );

		// edit shift.
		add_action( 'admin_post_control_horas_edit', array( $this, 'edit_shift' ) );

		// add menu page.
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
	}

	/**
	 * Add main menu
	 *
	 * @since  0.0.0
	 */
	public function add_menu_page() {

		add_menu_page(
			$this->title,
			$this->menu_title,
			'manage_options',
			$this->key,
			array( $this, 'admin_page_display' ),
			'dashicons-clock'
		);
	}

	/**
	 * Add admin user interface
	 *
	 * @since  0.0.0
	 */
	public function admin_page_display() {
		$this->plugin->ch_ui->options_ui();
	}

	/**
	 *
	 * Save settings from pages setup
	 *
	 * @since  0.0.0
	 */
	public function user_setup() {

		if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'control-horas' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'control-horas' ) );
		}

		// sanitized in update_setting function.
		$guardarip = isset( $_POST['guardar-ip'] ) ? 1 : 0;
		$this->plugin->ch_settings->update_setting( 'guardar-ip', $guardarip );

		// set message and redirect.
		$message = 'saved';
		if ( wp_redirect(
			add_query_arg(
				array(
					'page'    => 'controlhoras',
					'message' => $message,
					'tab'     => 'ajustes',
				),
				admin_url( 'admin.php' )
			)
		) ) {
			exit;
		}
	}

	/**
	 *
	 * Edit shift
	 *
	 * @since  0.0.0
	 */
	public function edit_shift() {

		if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'control-horas' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'control-horas' ) );
		}

		if ( ! isset( $_POST['id'] ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'control-horas' ) );
		}
		$id = sanitize_text_field( wp_unslash ( $_POST['id'] ) );

		$message = 'error';
		// nota field.
		if ( isset( $_POST['note'] ) ) {
			$note = sanitize_text_field( wp_unslash ( $_POST['note'] ) );
			$ch_db = $this->plugin->ch_db;
			if ( $ch_db->update_shift( $id, $note ) ) {
				$message = 'saved';
			}
		}

		// set message and redirect.
		if ( wp_redirect(
			add_query_arg(
				array(
					'page'    => 'controlhoras',
					'message' => $message,
					'tab'     => 'registros',
				),
				admin_url( 'admin.php' )
			)
		) ) {
			exit;
		}
	}

}
