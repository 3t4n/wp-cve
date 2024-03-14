<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

class ShareASale_WC_Tracker {
	/**
	* @var ShareASale_WC_Tracker_Pixel $pixel object that controls tracking sale conversions
	* @var ShareASale_WC_Tracker_Analytics $analytics object that controls advanced analytics (add-to-cart, coupon code, etc.) setup
	* @var ShareASale_WC_Tracker_Loader $loader Loader object that coordinates actions and filters between core plugin and admin classes
	* @var string $plugin_slug WordPress Slug for this plugin
	* @var string $version Plugin version
	*/
	private $pixel, $analytics, $loader, $plugin_slug, $version;

	public function __construct( $version ) {

		$this->plugin_slug = 'shareasale-wc-tracker-slug';
		$this->version     = $version;

		$this->load_dependencies();

		$this->define_frontend_hooks();
		$this->define_admin_hooks();
		$this->define_woocommerce_hooks();
		$this->define_installer_hooks();
		$this->define_uninstaller_hooks();
		$this->define_backend_hooks();
	}

	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-shareasale-wc-tracker-admin.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-shareasale-wc-tracker-pixel.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-shareasale-wc-tracker-reconciler.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-shareasale-wc-tracker-mastertag.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-shareasale-wc-tracker-autovoid.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-shareasale-wc-tracker-analytics.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-shareasale-wc-tracker-loader.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-shareasale-wc-tracker-installer.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-shareasale-wc-tracker-uninstaller.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-shareasale-wc-tracker-rest.php';

		$this->loader    = new ShareASale_WC_Tracker_Loader();
		//both define_frontend_hooks() and define_woocommerce_hooks() rely on $pixel and $analytics objects so instantiate them here instead
		$this->pixel     = new ShareASale_WC_Tracker_Pixel( $this->version );
		$this->analytics = new ShareASale_WC_Tracker_Analytics( $this->version );
	}

	private function define_frontend_hooks() {
		$mastertag = new ShareASale_WC_Tracker_Mastertag( $this->version );
		$this->loader->add_action( 'wp_enqueue_scripts', $mastertag, 'enqueue_scripts' );

		$autovoid = new ShareASale_WC_Tracker_Autovoid( $this->version );
		$this->loader->add_action( 'wp_enqueue_scripts', $autovoid, 'enqueue_scripts' );

		$this->loader->add_action( 'wp_head', $this->analytics, 'wp_head',
			array(
				'priority' => 10,
				'args' => 0,
			)
		);
		$this->loader->add_action( 'wp_enqueue_scripts', $this->analytics, 'enqueue_scripts' );
		//advanced analytics ajax cart page based changes, not using WooCommerce hooks
		//restored cart items both kicks off a WC_Form_Handler ajax request AND then redirects the page...
		//$this->loader->add_action( 'wp_ajax_shareasale_wc_tracker_cart_item_restored',   $this->analytics, 'wp_ajax_shareasale_wc_tracker_cart_item_restored' );
		$this->loader->add_action(
			'wp_ajax_nopriv_shareasale_wc_tracker_update_cart_action_cart_updated',
			$this->analytics,
			'wp_ajax_nopriv_shareasale_wc_tracker_update_cart_action_cart_updated',
			array(
				'priority' => 10,
				'args' => 0,
			)
		);
		//only included the non-nopriv version in case a Merchant is testing analytics themselves while logged in...
		$this->loader->add_action(
			'wp_ajax_shareasale_wc_tracker_update_cart_action_cart_updated',
			$this->analytics,
			'wp_ajax_shareasale_wc_tracker_update_cart_action_cart_updated',
			array(
				'priority' => 10,
				'args' => 0,
			)
		);

		$this->loader->add_action(
			'wp_ajax_nopriv_shareasale_wc_tracker_triggered',
			$this->pixel,
			'wp_ajax_nopriv_shareasale_wc_tracker_triggered',
			array(
				'priority' => 10,
				'args' => 0,
			)
		);
		//only included the non-nopriv version in case a Merchant is revisiting the receipt page themselves while logged in...
		$this->loader->add_action(
			'wp_ajax_shareasale_wc_tracker_triggered',
			$this->pixel,
			'wp_ajax_shareasale_wc_tracker_triggered',
			array(
				'priority' => 10,
				'args' => 0,
			)
		);

		//filters
		$this->loader->add_filter( 'script_loader_tag',  $this->pixel, 'script_loader_tag',
			array(
				'priority' => 10,
				'args' => 3,
			)
		);
	}

	private function define_admin_hooks() {
		$admin = new ShareASale_WC_Tracker_Admin( $this->version );
		$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_init',            $admin, 'admin_init' );
		$this->loader->add_action( 'admin_init',            $admin, 'plugin_upgrade' );
		$this->loader->add_action( 'admin_menu',            $admin, 'admin_menu' );
		$this->loader->add_action( 'shareasale_wc_tracker_generate_scheduled_datafeed', $admin, 'shareasale_wc_tracker_generate_scheduled_datafeed' );
		$this->loader->add_action( 'wp_ajax_shareasale_wc_tracker_generate_datafeed',   $admin, 'wp_ajax_shareasale_wc_tracker_generate_datafeed' );
		$this->loader->add_action( 'wp_ajax_shareasale_wc_tracker_ftp_failed_dismiss_notice',   $admin, 'wp_ajax_shareasale_wc_tracker_ftp_failed_dismiss_notice' );
		//for adding and saving custom post meta (ShareASale category/subactegory number values) to the WC products page general section
		$this->loader->add_action( 'woocommerce_product_options_general_product_data', $admin, 'woocommerce_product_options_general_product_data' );
		$this->loader->add_action( 'woocommerce_process_product_meta',                 $admin, 'woocommerce_process_product_meta' );
		//for adding and saving custom post meta ("upload to ShareASale?" checkbox) to the WC coupons page general section
		$this->loader->add_action( 'woocommerce_coupon_options', 	  $admin, 'woocommerce_coupon_options' );
		$this->loader->add_action( 'woocommerce_coupon_options_save', $admin, 'woocommerce_coupon_options_save',
			array(
				'priority' => 10,
				'args' => 2,
			)
		);
		$this->loader->add_action( 'admin_notices', $admin, 'admin_notices' );

		//admin filters
		$this->loader->add_filter( 'plugin_action_links_' . SHAREASALE_WC_TRACKER_PLUGIN_FILENAME, $admin, 'render_settings_shortcut' );
	}

	private function define_woocommerce_hooks() {
		//conversion tracking pixel
		$this->loader->add_action( 'woocommerce_thankyou', $this->pixel, 'woocommerce_thankyou' );
		//automatic reconciliation
		$reconciler = new ShareASale_WC_Tracker_Reconciler( $this->version );
		$this->loader->add_action( 'woocommerce_order_partially_refunded', $reconciler, 'woocommerce_order_partially_refunded',
			array(
				'priority' => 10,
				'args' => 2,
			)
		);
		$this->loader->add_action( 'woocommerce_order_fully_refunded', $reconciler, 'woocommerce_order_fully_refunded',
			array(
				'priority' => 10,
				'args' => 2,
			)
		);
		//advanced analytics
		//the ShareASale_WC_Tracker_Analytics methods hooked to add_to_cart/ajax_added_to_cart must stay priority number lower than WC_Cart::calculate_totals' priority 20, since it's also hooked to those events. Using PHP_INT_MAX to ensure last place execution
		$this->loader->add_action( 'woocommerce_add_to_cart',          $this->analytics, 'woocommerce_add_to_cart',
			array(
				'priority' => PHP_INT_MAX,
				'args' => 0,
			)
		);
		$this->loader->add_action( 'woocommerce_ajax_added_to_cart',   $this->analytics, 'woocommerce_ajax_added_to_cart',
			array(
				'priority' => PHP_INT_MAX,
				'args' => 0,
			)
		);
		$this->loader->add_action( 'woocommerce_before_checkout_form', $this->analytics, 'woocommerce_before_checkout_form',
			array(
				'priority' => 10,
				'args' => 0,
			)
		);
		$this->loader->add_action( 'woocommerce_applied_coupon', $this->analytics, 'woocommerce_applied_coupon' );
		$this->loader->add_action( 'woocommerce_thankyou',       $this->analytics, 'woocommerce_thankyou' );
	}

	private function define_installer_hooks() {
	    register_activation_hook( SHAREASALE_WC_TRACKER_PLUGIN_FILENAME, array( 'ShareASale_WC_Tracker_Installer', 'install' ) );
	}

	private function define_uninstaller_hooks() {
		register_deactivation_hook( SHAREASALE_WC_TRACKER_PLUGIN_FILENAME, array( 'ShareASale_WC_Tracker_Uninstaller', 'disable' ) );
	    register_uninstall_hook( SHAREASALE_WC_TRACKER_PLUGIN_FILENAME, array( 'ShareASale_WC_Tracker_Uninstaller', 'uninstall' ) );
	}

	private function define_backend_hooks() {
		$rest = new ShareASale_WC_Tracker_Rest();
		$this->loader->add_action( 'rest_api_init', $rest, 'register_routes' );

	}

	public function run() {
		$this->loader->run();
	}
}
