<?php
/**
 * Plugin Name: TrackShip for WooCommerce
 * Description: TrackShip for WooCommerce integrates TrackShip into your WooCommerce Store and auto-tracks your orders, automates your post-shipping workflow and allows you to provide a superior Post-Purchase experience to your customers.
 * Version: 1.7.6
 * Author: TrackShip
 * Author URI: https://trackship.com/
 * License: GPL-2.0+
 * License URI: 
 * Text Domain: trackship-for-woocommerce
 * Domain Path: /language/
 * WC tested up to: 8.2.1
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Trackship_For_Woocommerce {
	
	/**
	 * Trackship_For_Woocommerce version.
	 *
	 * @var string
	*/
	public $version = '1.7.6';
	public $plugin_path;
	public $ts_install;
	public $ts_actions;
	public $actions;
	public $front;
	public $admin;
	public $html;
	public $late_shipments;
	public $exception_shipments;
	public $on_hold_shipments;
	public $shipments;
	public $logs;
	public $analytics;
	public $trackship_admin_notice;
	public $wc_admin_notice;
	public $smswoo_admin;
	public $smswoo_init;
	public $wot_ts;
	public $kly_ts;

	/**
	 * Initialize the main plugin function
	*/
	public function __construct() {
		
		// WC & AST/ST are active
		if ( ! $this->is_wc_active() ) {
			add_action( 'admin_notices', array( $this, 'notice_activate_wc' ) );
			return;
		}

		if ( !$this->is_ast_active() && !$this->is_st_active() && !$this->is_active_woo_order_tracking() && !$this->is_active_yith_order_tracking() ) {
			add_action( 'admin_notices', array( $this, 'notice_activate_ast' ) );
		}

		// Include required files.
		$this->includes();

		// Init REST API.
		$this->init_rest_api();

		//start adding hooks
		$this->init();

		//admin class init
		$this->ts_actions->init();
		
		//admin class init
		$this->admin->init();
		
		//plugin install class init
		$this->ts_install->init();

		//plugin shipments class init
		$this->shipments->init();

		//plugin Logs class init
		$this->logs->init();
	}
	
	/**
	 * Check if WooCommerce is active
	 *
	 * @since  1.0.0
	 * @return bool
	*/
	private function is_wc_active() {
		
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$is_active = true;
		} else {
			$is_active = false;
		}
		return $is_active;
	}
	
	/**
	 * Display WC active notice
	 *
	 * @since  1.0.0
	*/
	public function notice_activate_wc() {
		?>
		<div class="error">
			<?php /* translators: %s: search for a tag */ ?>
			<p><?php printf( esc_html__( 'Please install and activate %1$sWooCommerce%2$s for TrackShip for WooCommerce!', 'trackship-for-woocommerce' ), '<a href="' . esc_url( admin_url( 'plugin-install.php?tab=search&s=WooCommerce&plugin-search-input=Search+Plugins' ) ) . '">', '</a>' ); ?></p>
		</div>
		<?php
	}
	
	/**
	 * Display AST active notice
	 *
	 * @since  1.0.0
	*/
	public function notice_activate_ast() {
		?>
		<div class="error">
			<?php /* translators: %s: search for a tag */ ?>
			<p><?php printf( esc_html__( 'You must have a %1$sShipment Tracking plugin%2$s installed to use TrackShip for WooCommerce.', 'trackship-for-woocommerce' ), '<a href="' . esc_url( admin_url( 'plugin-install.php?tab=search&s=AST&plugin-search-input=Search+Plugins' ) ) . '">', '</a>' ); ?></p>
		</div>
		<?php
	}
	
	/*
	* init when class loaded
	*/
	public function init() {
		
		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );
		
		add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'tsw_plugin_action_links' ) );

		add_filter( 'yith_wcbm_add_badge_tags_in_wp_kses_allowed_html', '__return_true' );
		add_filter( 'yith_wcbm_is_allowed_adding_badge_tags_in_wp_kses', '__return_true' );
	}
	
	/**
	 * Init trackship REST API.
	 *
	*/
	private function init_rest_api() {
		add_action( 'rest_api_init', array( $this, 'rest_api_register_routes' ) );
	}

	/**
	 * Gets the absolute plugin path without a trailing slash, e.g.
	 * /path/to/wp-content/plugins/plugin-directory.
	 *
	 * @return string plugin path
	 */
	public function get_plugin_path() {
		if ( isset( $this->plugin_path ) ) {
			return $this->plugin_path;
		}

		$this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );

		return $this->plugin_path;
	}
	
	/*
	* include files
	*/
	private function includes() {
	
		require_once $this->get_plugin_path() . '/includes/class-wc-trackship-install.php';
		$this->ts_install = WC_Trackship_Install::get_instance();

		$trackship_apikey = get_option( 'trackship_apikey' );
		if ( $trackship_apikey ) {
			require_once $this->get_plugin_path() . '/includes/class-wc-trackship-front.php';
			$this->front = WC_TrackShip_Front::get_instance();
			add_action( 'template_redirect', array( $this->front, 'preview_tracking_page' ) );
			add_action( 'template_redirect', array( $this->front, 'track_form_preview' ) );
		}

		require_once $this->get_plugin_path() . '/includes/class-wc-trackship-actions.php';
		$this->ts_actions	= WC_Trackship_Actions::get_instance();
		$this->actions		= WC_Trackship_Actions::get_instance();

		require_once $this->get_plugin_path() . '/includes/class-wc-trackship-admin.php';
		$this->admin = WC_Trackship_Admin::get_instance();

		require_once $this->get_plugin_path() . '/includes/html/class-wc-trackship-html.php';
		$this->html = WC_Trackship_Html::get_instance();

		require_once $this->get_plugin_path() . '/includes/class-wc-trackship-late-shipments.php';
		$this->late_shipments = WC_TrackShip_Late_Shipments::get_instance();

		require_once $this->get_plugin_path() . '/includes/class-wc-trackship-exception-shipments.php';
		$this->exception_shipments = WC_TrackShip_Exception_Shipments::get_instance();

		require_once $this->get_plugin_path() . '/includes/class-wc-trackship-on-hold-shipments.php';
		$this->on_hold_shipments = WC_TrackShip_On_Hold_Shipments::get_instance();

		require_once $this->get_plugin_path() . '/includes/class-wc-trackship-api-call.php';

		require_once $this->get_plugin_path() . '/includes/shipments/class-wc-trackship-shipments.php';
		$this->shipments = WC_Trackship_Shipments::get_instance();

		require_once $this->get_plugin_path() . '/includes/logs/class-wc-trackship-logs.php';
		$this->logs = WC_Trackship_Logs::get_instance();

		require_once $this->get_plugin_path() . '/includes/analytics/class-wc-trackship-analytics.php';
		$this->analytics = WC_Trackship_Analytics::get_instance();

		require_once $this->get_plugin_path() . '/includes/class-wc-trackship-notice.php';
		$this->trackship_admin_notice = WC_TrackShip_Admin_Notice::get_instance();

		require_once $this->get_plugin_path() . '/includes/class-wc-admin-notices.php';
		$this->wc_admin_notice = WC_TS4WC_Admin_Notices_Under_WC_Admin::get_instance();

		//SMSWOO
		require_once $this->get_plugin_path() . '/includes/smswoo/class-smswoo-init.php';
		$this->smswoo_init = TSWC_SMSWOO_Init::get_instance();

		if ( is_plugin_active( 'automatewoo/automatewoo.php' ) ) {
			require_once plugin_dir_path( __FILE__ ) . '/includes/integration/class-wc-automatewoo-integration.php';
		}

		if ( $this->is_active_woo_order_tracking() ) {
			require_once plugin_dir_path( __FILE__ ) . '/includes/integration/class-woo-order-tracking-integration.php';
			$this->wot_ts = WOO_Order_Tracking_TS4WC::get_instance();
		}

		if ( $this->is_active_klaviyo() ) {
			require_once plugin_dir_path( __FILE__ ) . '/includes/integration/class-klaviyo-integration.php';
			$this->kly_ts = WOO_Klaviyo_TS4WC::get_instance();
		}
	}
	
	/**
	 * Register shipment tracking routes.
	 *
	 */
	public function rest_api_register_routes() {
		if ( ! is_a( WC()->api, 'WC_API' ) ) {
			return;
		}
		require_once $this->get_plugin_path() . '/includes/api/class-trackship-rest-api-controller.php';

		$trackship_controller_v1 = new TrackShip_REST_API_Controller();
		$trackship_controller_v1->register_routes();

		$trackship_controller_v2 = new TrackShip_REST_API_Controller();
		$trackship_controller_v2->set_namespace( 'wc/v2' );
		$trackship_controller_v2->register_routes();

		$trackship_controller_v3 = new TrackShip_REST_API_Controller();
		$trackship_controller_v3->set_namespace( 'wc/v3' );
		$trackship_controller_v3->register_routes();
		
	}
	
	/*
	* include file on plugin load
	*/
	public function on_plugins_loaded() {
		$trackship_apikey = is_trackship_connected();

		//load customizer
		if ( $trackship_apikey ) {
			require_once $this->get_plugin_path() . '/includes/customizer/trackship-customizer.php';
			require_once $this->get_plugin_path() . '/includes/customizer/class-trackship-email-preview.php';
		}
		require_once $this->get_plugin_path() . '/includes/trackship-email-manager.php';
		
		//load plugin textdomain
		load_plugin_textdomain( 'trackship-for-woocommerce', false, dirname( plugin_basename(__FILE__) ) . '/language/' );
	}

	/*
	* return plugin directory URL
	*/
	public function plugin_dir_url() {
		return plugin_dir_url( __FILE__ );
	}
	
	/**
	* Add plugin action links.
	*
	* Add a link to the settings page on the plugins.php page.
	*
	* @since 1.0.0
	*
	* @param  array  $links List of existing plugin action links.
	* @return array         List of modified plugin action links.
	*/
	public function tsw_plugin_action_links( $links ) {
		$admin_url = is_trackship_connected() ? admin_url( '/admin.php?page=trackship-for-woocommerce' ) : admin_url( '/admin.php?page=trackship-dashboard' );
		$name = is_trackship_connected() ? __( 'Settings', 'trackship-for-woocommerce' ) : __( 'Connect a Store', 'trackship-for-woocommerce' );
		$links = array_merge( array(
			'<a href="' . esc_url( $admin_url ) . '">' . esc_html__( $name ) . '</a>',
			'<a href="https://docs.trackship.com/docs/trackship-for-woocommerce/">' . __( 'Docs' ) . '</a>',
			'<a href="https://wordpress.org/support/plugin/trackship-for-woocommerce/#new-topic-0">' . __( 'Support' ) . '</a>',
			'<a href="https://wordpress.org/support/plugin/trackship-for-woocommerce/reviews/#new-post">' . __( 'Review' ) . '</a>'
		), $links );
		return $links;
	}
	
	/**
	 * Check if Advanced Shipment Tracking for WooCommerce is active
	 *
	 * @since  1.0.0
	 * @return bool
	*/
	public function is_ast_active() {
		
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		if ( is_plugin_active( 'woo-advanced-shipment-tracking/woocommerce-advanced-shipment-tracking.php' ) || is_plugin_active( 'ast-pro/ast-pro.php' ) || is_plugin_active( 'advanced-shipment-tracking-pro/advanced-shipment-tracking-pro.php' ) ) {
			$is_active = true;
		} else {
			$is_active = false;
		}		

		return $is_active;
	}
	
	/**
	 * Check if Shipment Tracking is active
	 *
	 * @since  1.0.0
	 * @return bool
	*/
	public function is_st_active() {
		
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		if ( is_plugin_active( 'woocommerce-shipment-tracking/woocommerce-shipment-tracking.php' ) ) {
			$is_active = true;
		} else {
			$is_active = false;
		}
	
		return $is_active;
	}
	
	/**
	 * Check if Woo order Tracking is active
	 *
	 * @since  1.5.0
	 * @return bool
	*/
	public function is_active_woo_order_tracking() {
		
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		if ( is_plugin_active( 'woo-orders-tracking/woo-orders-tracking.php' ) || is_plugin_active( 'woocommerce-orders-tracking/woocommerce-orders-tracking.php' ) ) {
			$is_active = true;
		} else {
			$is_active = false;
		}

		return $is_active;
	}

	/**
	 * Check if Klaviyo is active
	 *
	 * @since  1.6.3
	 * @return bool
	*/
	public function is_active_klaviyo() {
		
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		if ( is_plugin_active( 'klaviyo/klaviyo.php' ) ) {
			$is_active = true;
		} else {
			$is_active = false;
		}

		return $is_active;
	}
	

	/**
	 * Check if Yith order Tracking is active
	 *
	 * @since  1.5.0
	 * @return bool
	*/
	public function is_active_yith_order_tracking() {
		
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		if ( is_plugin_active( 'yith-woocommerce-order-tracking/init.php' ) || is_plugin_active( 'yith-woocommerce-order-tracking-premium/init.php' ) ) {
			$is_active = true;
		} else {
			$is_active = false;
		}
	
		return $is_active;
	}

	public function get_tracking_items( $order_id ) {
		if ( function_exists( 'ast_get_tracking_items' ) ) {
			$tracking_items = ast_get_tracking_items( $order_id );
		} elseif ( class_exists( 'WC_Shipment_Tracking' ) ) {
			$tracking_items = WC_Shipment_Tracking()->actions->get_tracking_items( $order_id, true );
		} elseif ( class_exists( 'YITH_WooCommerce_Order_Tracking' ) ) {
			$order = wc_get_order( $order_id );
			if ( !$order || !$order->get_meta( 'ywot_tracking_code', true ) ) {
				return array();
			}
			$tracking_provider = $order->get_meta( 'ywot_carrier_name', true ) ? $order->get_meta( 'ywot_carrier_name', true ) : $order->get_meta( 'ywot_carrier_id', true );
			$tracking_items[0] = array(
				'formatted_tracking_provider'	=> trackship_for_woocommerce()->actions->get_provider_name( $tracking_provider ),
				'tracking_provider'				=> $tracking_provider,
				'formatted_tracking_link'		=> $order->get_meta( 'ywot_carrier_url', true ),
				'tracking_number'				=> $order->get_meta( 'ywot_tracking_code', true ),
				'tracking_id'					=> md5( "{$tracking_provider}-{$order->get_meta( 'ywot_tracking_code', true )}" . microtime() ),
				'tracking_page_link'			=> trackship_for_woocommerce()->actions->get_tracking_page_link( $order_id, $order->get_meta( 'ywot_tracking_code', true ) ),
				'date_shipped'					=> $order->get_meta( 'ywot_pick_up_date', true ),
			);
			$tracking_items = $tracking_items ? $tracking_items : array();
		} elseif ( $this->is_active_woo_order_tracking() ) {
			$tracking_items = $this->wot_ts->woo_orders_tracking_items( $order_id );
		} else {
			$order = wc_get_order( $order_id );
			$tracking_items = $order->get_meta( '_wc_shipment_tracking_items', true );
			$tracking_items = $tracking_items ? $tracking_items : array();
		}

		foreach ( $tracking_items as $key => $tracking_item ) {
			$tracking_items[$key]['tracking_page_link'] = trackship_for_woocommerce()->actions->get_tracking_page_link( $order_id, $tracking_item['tracking_number'] );
		}

		return $tracking_items;
	}
}

if ( ! function_exists( 'trackship_for_woocommerce' ) ) {

	/**
	 * Returns an instance of Trackship_For_Woocommerce.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return Trackship_For_Woocommerce
	*/	
	function trackship_for_woocommerce() {
		static $instance;
	
		if ( ! isset( $instance ) ) {
			$instance = new Trackship_For_Woocommerce();
		}
	
		return $instance;
	}


	/**
	 * Register this class globally.
	 *
	 * Backward compatibility.
	*/
	trackship_for_woocommerce();
}

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

/*
* check trackship is connected
*
* @since   1.0.0
*
* Return @void
*
*/
function is_trackship_connected() {
	
	$trackship_apikey = get_option( 'trackship_apikey' );
	
	if ( ! $trackship_apikey ) {
		return false;
	}
	
	return true;
}
	
/*
* get trackship key
*
* @since   1.0
*
* Return @void
*
*/
function get_trackship_key() {
	$trackship_apikey = get_option( 'trackship_apikey' );
	return $trackship_apikey;
}

function get_trackship_settings( $key, $default_value = '' ) {
	$data_array = get_option( 'trackship_settings', array() );
	$value = '';
	if ( isset( $data_array[$key] ) ) {
		$value = $data_array[$key];
	}
	
	if ( '' == $value ) {
		$value = $default_value;
	}
	return $value;
}

function update_trackship_settings( $key, $value ) {
	$data_array = get_option( 'trackship_settings', array() );
	$data_array[ $key ] = $value;
	update_option( 'trackship_settings', $data_array );
}

function delete_trackship_settings( $key ) {
	$data_array = get_option( 'trackship_settings', array() );
	unset($data_array[$key]);
	update_option( 'trackship_settings', $data_array );
}

if ( ! function_exists( 'zorem_tracking' ) ) {
	function zorem_tracking() {
		require_once dirname(__FILE__) . '/zorem-tracking/zorem-tracking.php';
		$plugin_name = 'TrackShip for WooCommerce';
		$plugin_slug = 'trackship-for-woocommerce';
		$user_id = '12';
		$setting_page_type = 'top-level';
		$setting_page_location = 'A custom top-level admin menu (admin.php)';
		$parent_menu_type = '';
		$menu_slug = 'trackship-for-woocommerce';
		$plugin_id = '12';
		$zorem_tracking = WC_Trackers::get_instance( $plugin_name, $plugin_slug, $user_id,
		$setting_page_type, $setting_page_location, $parent_menu_type, $menu_slug, $plugin_id );

		return $zorem_tracking;
	}
	zorem_tracking();
}
