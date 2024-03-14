<?php
/*
* Plugin Name: Country Based Restrictions for WooCommerce
* Plugin URI:  https://www.zorem.com/shop/woocommerce-product-country-based-restrictions/
* Description: Restrict WooCommerce products in specific countries
* Author: zorem
* Author URI: https://www.zorem.com/
* Version: 3.6.1
* Text Domain: woo-product-country-base-restrictions
* WC requires at least: 4.0
* WC tested up to: 8.5.2
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class ZH_Product_Country_Restrictions {
	
	public $user_country = '';	
	
	/**
	 * Country Based Restrictions for WooCommerce
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $version = '3.6.1';
	public $toolbar;
	public $restriction;
	public $plugin_path;
	public $settings;
	public $notice;
	public $product;
	
	/*
	* construct function
	*
	* @since 1.0.0
	*/
	public function __construct() {
		
		if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
			return;
		}
		
		if ( ! $this->is_cbr_pro_active() ) {
			if ( $this->is_wc_active() ) {		
				$this->includes();
				$this->settings->init();
				add_action( 'plugins_loaded', array( $this, 'plugin_init' ) );
			} else {
				add_action( 'admin_notices', array( $this, 'admin_error_notice' ) );	
			}
		}
		
		
	}
	
	/**
	 * Include plugin file.
	 *
	 * @since 1.0.0
	 *
	 */	
	public function includes() {
		
		require_once $this->get_plugin_path() . '/include/admin-settings.php';
		$this->settings = CBR_Admin_Settings::get_instance();
		
		require_once $this->get_plugin_path() . '/include/admin-notice.php';
		$this->notice = CBR_Admin_Notice::get_instance();
		
		require_once $this->get_plugin_path() . '/include/single-product.php';
		$this->product = CBR_Single_Product::get_instance();
		
		require_once $this->get_plugin_path() . '/include/admin-toolbar.php';
		$this->toolbar = CBR_Admin_Toolbar::get_instance();
		
		require_once $this->get_plugin_path() . '/include/products-restriction.php';
		$this->restriction = CBR_Product_Restriction::get_instance();
		
	}
	
	/**
	 * Plugin file dir hooks
	 *
	 * @since 1.0.0
	 *
	 */
	public function plugin_dir_url() {
		return plugin_dir_url( __FILE__ );
	}
	
	/**
	 * Gets the absolute plugin path without a trailing slash, e.g.
	 * /path/to/wp-content/plugins/plugin-directory.
	 *
	 * @since 1.0.0
	 * @return string plugin path
	 */
	public function get_plugin_path() {
		if ( isset( $this->plugin_path ) ) {
			return $this->plugin_path;
		}

		$this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );

		return $this->plugin_path;
	}
	
	/**
	 * Init hooks
	 *
	 * @since 1.0.0
	 *
	 */
	public function plugin_init() {
		
		$i18n_dir = basename( dirname( __FILE__ ) ) . '/lang/';         
		load_plugin_textdomain( 'woo-product-country-base-restrictions', false, $i18n_dir );
		
		//hooks in admin plugin page	
		add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this , 'my_plugin_action_links' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this , 'my_plugin_action_PRO_links' ));
		
		//hooks for frontend
		add_action( 'wp_head', array( $this, 'wc_cbr_frontend_enqueue' ), 999 );
		
		//load javascript in admin
		add_action('admin_enqueue_scripts', array( $this, 'wc_esrc_enqueue' ) );
		
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
	public function my_plugin_action_links( $links ) {
		$links = array_merge( 
			array( '<a href="' . esc_url( admin_url( '/admin.php?page=woocommerce-product-country-base-restrictions' ) ) . '">' . esc_html( 'Settings', 'woocommerce' ) . '</a>' ),
			array( '<a href="' . esc_url( 'https://www.zorem.com/docs/country-based-restrictions-for-woocommerce/' ) . '" target="_blank">' . esc_html( 'Docs', 'woocommerce' ) . '</a>' ),
			array( '<a href="' . esc_url( 'https://wordpress.org/support/plugin/woo-product-country-base-restrictions/reviews/#new-post' ) . '" target="_blank">' . esc_html( 'Review', 'woocommerce' ) . '</a>' ), 
			$links );
		return $links;
	}
	
	/**
	 * Add plugin action links.is_wc_active
	 *
	 * Add a link to the pro product page on the plugins.php page.
	 *
	 * @since 1.0.0
	 *
	 * @param  array  $links List of existing plugin action links.
	 * @return array         List of modified plugin action links.
	 */
	public function my_plugin_action_PRO_links( $links ) {
		
		if ( class_exists( 'Country_Based_Restrictions_PRO_Add_on' ) ) {
			return $links;
		}
		
		$links = array_merge( $links, array(
			'<a target="_blank" style="color: #45b450; font-weight: bold;" href="' . esc_url( 'https://www.zorem.com/products/country-based-restriction-pro/') . '">' . __( 'Go Pro', 'woocommerce' ) . '</a>'
		) );
		
		return $links;
	}
	
	/**
	 * Check if WC is active
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

		// Do the WC active check
		if ( false === $is_active ) {
			add_action( 'admin_notices', array( $this, 'notice_activate_wc' ) );
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
			<p><?php printf( esc_html( 'Please install and activate %1$sWooCommerce%2$s for Country Based Restrictions for WooCommerce!', 'woo-product-country-base-restrictions' ), '<a href="' . esc_url(admin_url( 'plugin-install.php?tab=search&s=WooCommerce&plugin-search-input=Search+Plugins' ) . '">', '</a>' ) ); ?></p>
		</div>
		<?php
	}
	
	/**
	 * Check if CBR PRO is active
	 *
	 * @since  1.0.0
	 * @return bool
	*/
	private function is_cbr_pro_active() {
		
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		if ( is_plugin_active( 'country-base-restrictions-pro-addon/country-base-restrictions-pro-addon.php' ) ) {
			$is_active = true;
		} else {
			$is_active = false;
		}

			
		return $is_active;
	}
	
	/**
	 * WOOCOMMERCE_VERSION admin notice
	 *
	 * @since 1.0.0
	 */
	public function admin_error_notice() {
		$message = __('Product Country Restrictions requires WooCommerce 3.0 or newer', 'woo-product-country-base-restrictions');
		echo esc_html("<div class='error'><p>$message</p></div>");
	}
	

	/*
	* Add admin javascript
	*
	* @since 1.0.0
	*/	
	public function wc_esrc_enqueue() {
		
		$page = isset( $_GET['page'] ) ? sanitize_text_field($_GET['page']) : '';
		
		// Add condition for css & js include for admin page  
		if ( 'woocommerce-product-country-base-restrictions' != $page ) {
			return;
		}
			
		// Add the WP Media 
		wp_enqueue_media();
		
		// Add tiptip js and css file
		wp_enqueue_script( 'cbr-admin-js', plugin_dir_url(__FILE__) . 'assets/js/admin.js', array('jquery', 'wp-util', 'wp-color-picker'), $this->version, true );
		wp_enqueue_script( 'cbr-material-min-js', plugin_dir_url(__FILE__) . 'assets/js/material.min.js', array(), $this->version );
		wp_enqueue_style( 'cbr-admin-css', plugin_dir_url(__FILE__) . 'assets/css/admin.css', array(), $this->version );
		wp_enqueue_style( 'cbr-material-css', plugin_dir_url(__FILE__) . 'assets/css/material.css', array(), $this->version );
			
		wp_enqueue_style('select2-cbr', plugins_url('assets/css/select2.min.css', __FILE__ ), array(), $this->version );
		wp_enqueue_script('select2-cbr', plugins_url('assets/js/select2.min.js', __FILE__), array(), $this->version);
		
		wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
		wp_enqueue_style( 'woocommerce_admin_styles' );
	
		wp_register_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip.min.js', array( 'jquery' ), WC_VERSION, true );
		wp_enqueue_script( 'jquery-tiptip' );
		
	}
	
	/*
	* Add frontend css
	*
	* @since 1.0.0
	*/	
	public function wc_cbr_frontend_enqueue() {

		wp_enqueue_script( 'cbr-pro-front-js', plugin_dir_url(__FILE__) . 'assets/js/front.js', array('jquery'), $this->version, true );
		wp_localize_script( 'cbr-pro-front-js', 'cbr_ajax_object', array( 'cbr_ajax_url' => admin_url( 'admin-ajax.php' ) ) );

		if ( '1' != get_option('wpcbr_hide_restricted_product_variation') && !is_product() ) {
			return;
		}
		wp_enqueue_style( 'cbr-fronend-css', plugin_dir_url(__FILE__) . 'assets/css/frontend.css', array(), $this->version );
	}
	
}
$fzpcr = new ZH_Product_Country_Restrictions();

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );
