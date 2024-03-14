<?php
/**
 * Plugin Name: Instantio - Instant Checkout for WooCommerce
 * Plugin URI: https://themefic.com/instantio/
 * Description: WooCommerce Quick Checkout through Side Cart, Floating Cart, Popup Cart & Direct Checkout Button. The whole checkout process would take only 15-25 seconds. Less Cart Abandonment and Better Sales Rate.
 * Author: Themefic
 * Text Domain: instantio
 * Domain Path: /lang/
 * Author URI: https://themefic.com
 * Tags: woocommerce, direct checkout, floating cart, side cart, ajax cart, cart popup, ajax add to cart, one page checkout, single page checkout, fly cart, mini cart, quick buy, instant checkout, quick checkout, same page checkout, sidebar cart, sticky cart, woocommerce ajax, one click checkout, woocommerce one page checkout, direct checkout woocommerce, woocommerce one click checkout, woocommerce quick checkout, woocommerce express checkout, woocommerce simple checkout, skip cart page woocommerce, woocommerce cart popup, edit woocommerce checkout page, woocommerce direct checkout
 
 * Version: 3.2.3
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * WC tested up to: 8.6
**/

// don't load directly
defined( 'ABSPATH' ) || exit;

class INSTANTIO {

	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
		$this->ins_public_hooks();
	}

	/**
	 * Define constants
	 */
	private function define_constants() {
		if ( ! defined( 'INSTANTIO_VERSION' ) ) {

			define( 'INSTANTIO_VERSION', '3.2.3' );


		}
		define( 'INS_URL', plugin_dir_url( __FILE__ ) );
		define( 'INS_INC_URL', INS_URL . 'includes' );
		define( 'INS_LAYOUTS_URL', INS_URL . 'includes/layouts' );
		define( 'INS_ASSETS_URL', INS_URL . 'assets' );
		define( 'INS_ADMIN_URL', INS_URL . 'admin' );

		define( 'INS_PATH', plugin_dir_path( __FILE__ ) );
		define( 'INS_INC_PATH', INS_PATH . 'includes' );
		define( 'INS_ADMIN_PATH', INS_PATH . 'admin' );
		define( 'INS_CONTROLLER_PATH', INS_INC_PATH . '/controller' );
		define( 'INS_BASE_LOCATION', plugin_basename( __FILE__ ) );
		define( 'INS_TEMPLATES_PATH', INS_INC_PATH . '/templates' );

		/**
		 * Ajax install & activate WooCommerce
		 *
		 * @since 3.0
		 * @link https://developer.wordpress.org/reference/functions/wp_ajax_install_plugin/
		 */
		add_action( "wp_ajax_ins_ajax_install_woocommerce", "wp_ajax_install_plugin" );

	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {
		require_once __DIR__ . '/vendor/autoload.php';
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		require_once( 'functions.php' );

		// Ins Quick Setup wizard & Ins_checkout_Editor
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			require_once INS_INC_PATH . '/controller/class-setup-wizard.php';

			// Ins_checkout_Editor
			require_once INS_INC_PATH . '/controller/checkout_editor.php';
		}


		// ins Promo Banner
		if ( file_exists( INS_INC_PATH . '/controller/class-promo-notice.php' ) ) {
			require_once INS_INC_PATH . '/controller/class-promo-notice.php';
		}
	}

	/**
	 * Init Instantio when WordPress Initialises.
	 */
	private function init_hooks() {
		add_action( 'plugins_loaded', array( $this, 'init' ), 0 );
	}

	/**
	 *  init Instantio when WordPress Initialises.
	 *
	 * @since 1.0
	 */
	public function init() {
		add_action( 'init', array( $this, 'tf_plugin_loaded_action' ) );

		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			new INS\Controller\Assets();
		}

		if ( is_admin() && ! wp_doing_ajax() ) {
			new INS\Controller\Admin();

			// Appsero
			$this->ins_appsero_init_tracker_instantio();



		} else {
			new INS\Controller\App();

			// ins Variation product Quick Views 
			add_action( 'wp_ajax_ins_variable_product_quick_view', array( $this, 'ins_ajax_quickview_variable_products' ) );
			add_action( 'wp_ajax_nopriv_ins_variable_product_quick_view', array( $this, 'ins_ajax_quickview_variable_products' ) );
		}

	}

	/**
	 * Plugins Loaded Actions
	 *
	 * Including Option Panel
	 *
	 * Including Options
	 */
	public function tf_plugin_loaded_action() {

		if ( file_exists( INS_PATH . 'admin/tf-options/Ins_TF_Options.php' ) ) {
			require_once INS_PATH . 'admin/tf-options/Ins_TF_Options.php';
		}

	}

	/**
	 *	Ajax variable products quick view
	 */

	public function ins_ajax_quickview_variable_products() {
		global $post, $product, $woocommerce;

		// return 1;
		check_ajax_referer( 'ins_ajax_nonce', 'security' );

		add_action( 'wcqv_product_data', 'woocommerce_template_single_add_to_cart' );

		$product_id = $_POST['product_id'];

		$wiqv_loop = new WP_Query(
			array(
				'post_type' => 'product',
				'p' => $product_id,
			)
		);
		ob_start();
		if ( $wiqv_loop->have_posts() ) :
			while ( $wiqv_loop->have_posts() ) :
				$wiqv_loop->the_post(); ?>
				<?php wc_get_template( 'single-product/add-to-cart/variation.php' ); ?>
				<script>
					jQuery.getScript("<?php echo $woocommerce->plugin_url(); ?>/assets/js/frontend/add-to-cart-variation.min.js");
				</script>
				<?php
				do_action( 'wcqv_product_data' );
			endwhile;
		endif;

		echo ob_get_clean();

		wp_die();
	}

	/**
	 * Appsero
	 *
	 * Including Options
	 */
	public function ins_appsero_init_tracker_instantio() {

		if ( ! class_exists( 'Appsero\Client' ) ) {
			require_once( INS_INC_PATH . '/app/src/Client.php' );
		}


		$client = new Appsero\Client( '29e55a76-0819-490f-b692-8368956cbf12', 'instantio', __FILE__ );

		// Change notice text
		$notice = sprintf( $client->__trans( 'Want to help make <strong>%1$s</strong> even more awesome? Allow %1$s to collect non-sensitive diagnostic data and usage information. I agree to get Important Product Updates & Discount related information on my email from  %1$s (I can unsubscribe anytime).' ), $client->name );

		$client->insights()->notice( $notice );

		// Active insights
		$client->insights()->init();

	}

	private function ins_public_hooks() {
		add_action( 'after_setup_theme', [ $this, 'ins_check_editor' ] );
	}

	public function ins_check_editor() {
		$ins_billing_fields = apply_filters( 'ins_billing_fields_priority', 1000 );
		$ins_shipping_fields = apply_filters( 'ins_shipping_fields_priority', 1000 );

		add_filter( 'woocommerce_default_address_fields', 'ins_over_checkout_billing_address', $ins_billing_fields, 2 );
		add_filter( 'woocommerce_checkout_fields', 'ins_over_checkout_billing_fields', $ins_billing_fields, 2 );
		add_filter( 'woocommerce_checkout_fields', 'ins_over_checkout_shipping_fields', $ins_shipping_fields, 2 );
		// add_filter('woocommerce_default_address_fields', 'ins_over_checkout_shiping_address');
	}

}


new INSTANTIO();

add_action( 'admin_enqueue_scripts', 'ins_admin_enqueue_scripts' );
add_action( 'before_woocommerce_init', 'ins_before_woocommerce_init' );

function ins_before_woocommerce_init() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
}


function ins_admin_enqueue_scripts() {
	wp_enqueue_style( 'ins-admin', INS_ASSETS_URL . '/admin/css/instantio-admin-style.css', array(), INSTANTIO_VERSION );
	wp_enqueue_script( 'ins-admin-script', INS_ASSETS_URL . '/admin/js/instantio-admin-script.js', array( 'jquery' ), INSTANTIO_VERSION, true );

	wp_localize_script( 'ins-admin-script', 'tf_admin_params',
		array(
			'ins_nonce' => wp_create_nonce( 'updates' ),
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		)
	);
}

