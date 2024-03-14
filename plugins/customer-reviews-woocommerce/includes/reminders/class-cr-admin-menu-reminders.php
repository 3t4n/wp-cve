<?php

if ( ! defined( 'ABSPATH' ) ) {
		exit;
}

if ( ! class_exists( 'CR_Reminders_Admin_Menu' ) ):

/**
 * Reminders admin menu class
 *
 * @since 3.5
 */
class CR_Reminders_Admin_Menu {

	/**
		 * @var string The slug identifying this menu
		 */
		protected $menu_slug;

		/**
		 * Constructor
		 *
		 * @since 3.5
		 */
		public function __construct() {
		$this->menu_slug = 'cr-reviews-reminders';

		add_action( 'admin_menu', array( $this, 'register_reminders_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'include_scripts' ) );
		add_filter( 'set-screen-option', array( $this, 'save_screen_options' ), 10, 3 );
		}

		/**
		 * Register the reminders submenu
		 *
		 * @since 3.5
		 */
		public function register_reminders_menu() {
			$capability = 'manage_options';
			if (
				! current_user_can( 'manage_options' ) &&
				current_user_can( 'manage_woocommerce' )
			) {
				$capability = 'manage_woocommerce';
			}
			$submenu = add_submenu_page(
				'cr-reviews',
				__( 'Reminders', 'customer-reviews-woocommerce' ),
				__( 'Reminders', 'customer-reviews-woocommerce' ),
				$capability,
				$this->menu_slug,
				array( $this, 'display_reminders_admin_page' )
			);
			if ( $submenu ) {
				add_action( "load-$submenu", array( $this, 'display_screen_options' ) );
			}
	}

	/**
	 * Handles bulk and per-reminder actions.
	 *
	 * @since 3.5
	 *
	 * @param string $action The action to process
	 */
	protected function process_actions( $list_table ) {
		$action = $list_table->current_action();

		$orders = array();
		$reminders = array();

		switch ( $action ) {
			case 'cancel':
			case 'send':
				// Bulk actions
				check_admin_referer( 'bulk-reminders' );

				$orders = ( isset( $_GET['orders'] ) && is_array( $_GET['orders'] ) ) ? $_GET['orders'] : array();
				$orders = array_map( 'intval', $orders );
				break;
			case 'delete':
				// Bulk actions
				check_admin_referer( 'bulk-reminders' );

				$reminders = ( isset( $_GET['reminders'] ) && is_array( $_GET['reminders'] ) ) ? $_GET['reminders'] : array();
				$reminders = array_map( 'intval', $reminders );
				break;
			case 'cancelreminder':
			case 'sendreminder':
				// Single-reminder actions
				check_admin_referer( 'manage-reminders' );

				$order_id = ( isset( $_GET['order_id'] ) ) ? intval( $_GET['order_id'] ): 0;

				if ( $order_id ) {
					$orders[] = $order_id;
				}
		}

		$cancelled = 0;
		$sent = 0;
		foreach ( $orders as $order_id ) {
			switch ( $action ) {
				case 'cancel':
				case 'cancelreminder':
					wp_clear_scheduled_hook( 'ivole_send_reminder', array( $order_id ) );
					$cancelled++;
					break;
				case 'send':
				case 'sendreminder':
					wp_clear_scheduled_hook( 'ivole_send_reminder', array( $order_id ) );
					wp_schedule_single_event( 1, 'ivole_send_reminder', array( $order_id ) );
					$sent++;
			}
		}

		if ( 0 < count( $reminders ) ) {
			$log = new CR_Reminders_Log();
			$log->delete( $reminders );
		}

		if ( $sent ) {
			wp_cron();
		}

		$redirect_to = remove_query_arg( array( 'reminder' ), wp_get_referer() );
		$redirect_to = add_query_arg( 'paged', $list_table->get_pagenum(), $redirect_to );

		if ( $cancelled ) {
			$redirect_to = add_query_arg( 'cancelled', $cancelled, $redirect_to );
		}

		if ( $sent ) {
			$redirect_to = add_query_arg( 'sent', $sent, $redirect_to );
		}

		wp_safe_redirect( $redirect_to );
		exit;
	}

	/**
	 * Render the scheduled reminders page
	 *
	 * @since 3.5
	 */
	public function display_reminders_admin_page() {
		if ( isset( $_GET['tab'] ) ) {
			$current_tab = $_GET['tab'];
		} else {
			$current_tab = 'scheduled';
		}
		if ( 'sent' === $current_tab ) {
			$list_table = new CR_Reminders_Log_Table( ['screen' => get_current_screen()] );
		} else {
			$list_table = new CR_Reminders_List_Table( ['screen' => get_current_screen()] );
		}
		$pagenum  = $list_table->get_pagenum();
		$doaction = $list_table->current_action();

		if ( $list_table->current_action() ) {
			$this->process_actions( $list_table );
		} elseif ( ! empty( $_GET['_wp_http_referer'] ) ) {
			wp_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
			exit;
		}

		$list_table->prepare_items();

		include plugin_dir_path( dirname( dirname( __FILE__ ) ) ) . 'templates/reminders-admin-page.php';
	}

	public function include_scripts() {
		if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === $this->menu_slug ) {
			wp_enqueue_script( 'jquery' );
		}
	}

	public function display_screen_options() {
		$args = array(
			'label' => 'Reminders per page',
			'default' => 20,
			'option' => 'reminders_per_page'
		);
		add_screen_option( 'per_page', $args );
	}

	public function save_screen_options( $screen_option, $option, $value ) {
		if ( 'reminders_per_page' === $option ) {
			if ( $value < 1 || $value > 999 ) {
				return false;
			}
		}
		return $value;
	}

}

endif;
