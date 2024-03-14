<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class TBLight_Admin {

	const PLUGIN_INFO_JSON_URL = 'https://kanev.com/media/downloads/taxibooking-wordpress/wptblight-free-info.json';

	const PLUGIN_SLUG = 'wptblight-free';

	// car WP_List_Table object
	public $cars_obj;

	// payment method WP_List_Table object
	public $paymentmethods_obj;

	// payment method WP_List_Table object
	public $configs_obj;

	// order WP_List_Table object
	public $orders_obj;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', array( __CLASS__, 'set_screen' ), 10, 3 );
		add_action( 'admin_menu', array( $this, 'tblight_plugin_menu' ) );
		add_action( 'admin_print_styles', array( $this, 'add_tblight_stylesheet' ) );
		add_action( 'admin_print_scripts', array( $this, 'add_tblight_scripts' ) );

		add_action( 'init', 'tblight_output_buffer' );
	}

	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function add_tblight_stylesheet() {
		wp_enqueue_style( 'jquery-ui-datepicker-style', plugins_url( '/admin/css/jquery-ui.min.css', __FILE__ ), array(), filemtime( TBLIGHT_PATH . '/admin/css/jquery-ui.min.css' ) );
		wp_enqueue_style( 'tblight-style', plugins_url( '/admin/css/tblight_style.min.css', __FILE__ ), array(), filemtime( TBLIGHT_PATH . '/admin/css/tblight_style.min.css' ) );
	}

	public function add_tblight_scripts() {
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_media();
		wp_enqueue_script( 'custom-uploader-js', plugins_url( '/admin/js/uploader.js', __FILE__ ), array(), filemtime( TBLIGHT_PATH . '/admin/js/uploader.js' ), true );
		wp_enqueue_script( 'jquery-ui-tooltip' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'lord-icons', plugins_url( '/admin/js/lord-icon.js', __FILE__ ), array(), filemtime( TBLIGHT_PATH . '/admin/js/lord-icon.js' ), true );

		$elsettings   = BookingHelper::config();
		$gmap_api_key = $elsettings->api_key;

		if ( ! empty( $_GET['page'] ) && $_GET['page'] == 'configs' && ! empty( $_GET['action'] ) && $_GET['action'] == 'edit' && ! empty( $_GET['id'] ) && $_GET['id'] == 4 ) {
			if ( $gmap_api_key != '' ) {
				wp_enqueue_script( 'maps-googleapis', 'https://maps.googleapis.com/maps/api/js?v=3&key=' . $gmap_api_key, array(), 3, true );
			} else {
				wp_enqueue_script( 'maps-googleapis', 'https://maps.googleapis.com/maps/api/js?v=3', array(), 3, true );
			}
			wp_enqueue_script( 'google-maps-getbounds-js', plugins_url( 'assets/js/google.maps.Polygon.getBounds.js', __FILE__ ), array(), filemtime( TBLIGHT_PATH . '/assets/js/google.maps.Polygon.getBounds.js' ), true );
			wp_enqueue_script( 'google-maps-moveto-js', plugins_url( 'assets/js/google.maps.Polygon.moveTo.js', __FILE__ ), array(), filemtime( TBLIGHT_PATH . '/assets/js/google.maps.Polygon.moveTo.js' ), true );

		} elseif ( ! empty( $_GET['page'] ) && $_GET['page'] == 'orders' && ! empty( $_GET['action'] ) && $_GET['action'] == 'edit' ) {
			$gmap_api_key = $elsettings->api_key;

			if ( $gmap_api_key != '' ) {
				wp_enqueue_script( 'maps-googleapis', 'https://maps.googleapis.com/maps/api/js?v=3&language=en&libraries=geometry,places&key=' . $gmap_api_key, array(), 3, true );
			} else {
				wp_enqueue_script( 'maps-googleapis', 'https://maps.googleapis.com/maps/api/js?v=3', array(), 3, true );
			}
		}
	}

	public function tblight_plugin_menu() {

		add_menu_page(
			'Taxi Booking',
			'Taxi Booking',
			'manage_options',
			'dashboard',
			array( $this, 'tblight_dashboard_page' ),
			'dashicons-businessperson',
			20
		);

		// Orders menu item
		require_once TBLIGHT_PLUGIN_PATH . 'admin/classes/class-orders-list.php';

		$orders_hook = add_submenu_page(
			'dashboard',
			'TaxiBooking - Orders',
			'Orders',
			'manage_options',
			'orders',
			array( $this, 'tblight_orders_page' )
		);
		add_action( "load-$orders_hook", array( $this, 'screen_option' ) );
		// Orders menu item

		// Cars menu item
		require_once TBLIGHT_PLUGIN_PATH . 'admin/classes/class-cars-list.php';

		$hook = add_submenu_page(
			'dashboard',
			'TaxiBooking - Cars',
			'Vehicles',
			'manage_options',
			'cars',
			array( $this, 'tblight_cars_page' )
		);
		add_action( "load-$hook", array( $this, 'screen_option' ) );
		// Cars menu item

		// PaymentMethods menu item
		require_once TBLIGHT_PLUGIN_PATH . 'admin/classes/class-paymentmethods-list.php';

		$paymentmethods_hook = add_submenu_page(
			'dashboard',
			'TaxiBooking - Payment Methods',
			'Payment Methods',
			'manage_options',
			'paymentmethods',
			array( $this, 'tblight_paymentmethods_page' )
		);
		add_action( "load-$paymentmethods_hook", array( $this, 'screen_option' ) );
		// PaymentMethods menu item

		// Configs menu item
		require_once TBLIGHT_PLUGIN_PATH . 'admin/classes/class-configs-list.php';

		$configs_hook = add_submenu_page(
			'dashboard',
			'TaxiBooking - Settings',
			'Settings',
			'manage_options',
			'configs',
			array( $this, 'tblight_configs_page' )
		);
		add_action( "load-$configs_hook", array( $this, 'screen_option' ) );
		// Configs menu item
	}

	public function tblight_dashboard_page() {
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$plugin_data = get_plugin_data( TBLIGHT_FILE );

		require_once TBLIGHT_PLUGIN_PATH . 'admin/views/dashboard.php';
	}

	public function tblight_cars_page() {
		require_once TBLIGHT_PLUGIN_PATH . 'admin/controllers/car.php';
	}

	public function tblight_paymentmethods_page() {
		 require_once TBLIGHT_PLUGIN_PATH . 'admin/controllers/paymentmethod.php';
	}

	public function tblight_configs_page() {
		require_once TBLIGHT_PLUGIN_PATH . 'admin/controllers/config.php';
	}

	public function tblight_orders_page() {
		 require_once TBLIGHT_PLUGIN_PATH . 'admin/controllers/order.php';
	}

	/**
	 * Screen options
	 */
	public function screen_option() {

		$option = 'per_page';
		$args   = array(
			'label'   => 'Number of items per page:',
			'default' => 20,
			'option'  => 'items_per_page',
		);

		if ( $_REQUEST['page'] == 'cars' ) {
			add_screen_option( $option, $args );
			$this->cars_obj = new Cars_List();
		}
		if ( $_REQUEST['page'] == 'paymentmethods' ) {
			add_screen_option( $option, $args );
			$this->paymentmethods_obj = new Paymentmethods_List();
		}
		if ( $_REQUEST['page'] == 'configs' ) {
			$this->configs_obj = new Configs_List();
		}
		if ( $_REQUEST['page'] == 'orders' ) {
			add_screen_option( $option, $args );
			$this->orders_obj = new Orders_List();
		}
	}

	/**
	 * Initializes a single instance
	 */
	public static function get_instance() {
		 static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}
}
