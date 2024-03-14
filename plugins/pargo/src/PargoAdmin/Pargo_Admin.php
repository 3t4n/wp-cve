<?php

namespace PargoWp\PargoAdmin;

use PargoWp\Includes\Analytics;
use PargoWp\Includes\Pargo_Wp_Shipping_Method;
use PargoWp\Includes\Pargo_Wp_Shipping_Method_Home_Delivery;
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       pargo.co.za
 * @since      1.0.0
 *
 * @package    Pargo
 * @subpackage Pargo/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pargo
 * @subpackage Pargo/admin
 * @author     Pargo <support@pargo.co.za>
 */
class Pargo_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pargo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pargo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pargo-admin.css', array(), $this->version, 'all' );
		// The Vue.js compiled CSS
		wp_enqueue_style( $this->plugin_name.'-alt', PARGO_PLUGIN_PATH . 'assets/vue/pargo_admin.css', array(), $this->version, 'all' );

	}

	public function add_module_script($tag, $handle, $src) {
		// if not your script, do nothing and return original $tag
		if ( $this->plugin_name.'-admin' !== $handle ) {
			return $tag;
		}
		// change the script tag by adding type="module" and return it.
		$tag = '<script src="' . esc_url( $src ) . '"></script>';
		return $tag;
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pargo_Loader as all the hooks are defined
		 * in that particular class.
		 *
		 * The Pargo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		// wp_enqueue_script( $this->plugin_name.'-runtime', PARGO_PLUGIN_PATH . 'assets/js/runtime.js', [], $this->version, true );
		// wp_enqueue_script( $this->plugin_name.'-vendors', PARGO_PLUGIN_PATH . 'assets/js/vendors.js', [], $this->version, true );

		wp_enqueue_script( $this->plugin_name.'-admin', PARGO_PLUGIN_PATH . 'assets/vue/admin.umd.js', [], $this->version, true );
		wp_localize_script(
			$this->plugin_name.'-admin',
			'OBJ',
			array(
				'asset_url' => PARGO_PLUGIN_PATH . 'assets',
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'api_url' => esc_url_raw( rest_url() ),
				'nonce' => wp_create_nonce( 'wp_rest' )
			)
		);
	}

	/**
	 * Add the menu item
	 *
	 */
	public function pargo_wp_init_menu() {

		add_menu_page( 'Pargo', 'Pargo', 'manage_options', 'pargo-wp', [$this, "pargo_admin"], 'none' );
		add_submenu_page( "pargo-wp", "Account", "Account", "manage_options", "pargo-wp", [$this, "pargo_admin"], null );
		add_submenu_page( "pargo-wp", "Pickup Point Settings", "Pickup Point Settings", "manage_options", "pargo-woocom-pup-setting", [$this, "pargo_woocom_shipping_settings"], null );
		add_submenu_page( "pargo-wp", "Home Delivery Settings", "Home Delivery Settings", "manage_options", "pargo-woocom-home-setting", [$this, "pargo_woocom_home_shipping_settings"], null );
		add_submenu_page( "pargo-wp", "Styling", "Pargo Styling", "manage_options", "pargo-wp-settings", [$this, "pargo_admin"], null );
	}

	/**
	 * The admin app
	 */
	public function pargo_admin() {
		echo '<div id="pargo-admin-app"></div>';
	}

	/**
	 * Redirect for the WooCommerce shipping settings for Pickup Points
	 */
	public function pargo_woocom_shipping_settings()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        $settings_link = admin_url('admin.php') . '?page=wc-settings&tab=shipping&section=wp_pargo';

		wp_redirect($settings_link, 301);
    }

	/**
	 * Redirect for the WooCommerce shipping settings for Home Delivery
	 */
	public function pargo_woocom_home_shipping_settings()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        $settings_link = admin_url('admin.php') . '?page=wc-settings&tab=shipping&section=wp_pargo_home';

		wp_redirect($settings_link, 301);
    }
	/**
	 * TODO, update this to be more specific to Pargo
	 */
	function pargo_shipping_method()
    {
        if (!class_exists('PargoWp\Includes\Pargo_Wp_Shipping_Method')) {

        }
    }

	/**
	 * Add the Pick up shipping method
	 * @param array $methods
	 *
	 * @returns array
	 */
	public function add_pargo_pick_up_shipping_method($methods) {
		// Filter will be called everytime the plug-in loads here.
//        Analytics::submit('client_admin', 'click', 'add_pargo_shipping_method');
		$wp_pargo = new Pargo_Wp_Shipping_Method(); // Create instance of Default Pickup Shipping Method
		$methods['wp_pargo']  = $wp_pargo;
		$wp_pargo_home = new Pargo_Wp_Shipping_Method_Home_Delivery(); // Create instance of Home Delivery Shipping Method
		$methods['wp_pargo_home'] = $wp_pargo_home;
		return $methods;
	}

	/**
	 * Display the custom Pargo fields on the admin view
	 *
	 * @param WC_Order $order
	 * @returns void
	 */
	public function pargo_checkout_field_display_admin_order_meta($order) {
		if (get_post_meta($order->get_id(), 'pargo_pc')) {
			echo '<p style="clear:both;"><b>' . __('Pargo Pick Up Address') . ':</b> ' . get_post_meta($order->get_id(), 'pargo_delivery_address', true) . '</p>';
            echo '<p style="clear:both;"><b>' . __('Pargo Pick Up Point Code') . ':</b> ' . get_post_meta($order->get_id(), 'pargo_pc', true) . '</p>';
		}
	}

	public function pargo_admin_billing_fields($fields) {
		$fields['suburb'] = array(
			'label' => __('Suburb', 'pargo'),
			'show' => false,
			'required' => true,
			'class' => 'form-row-wide',
			'clear' => true
		);
		return $fields;
	}

	public function pargo_admin_shipping_fields($fields) {
		$fields['suburb'] = array(
			'label' => __('Suburb', 'pargo'),
			'show' => false,
			'required' => true,
			'class' => 'form-row-wide',
			'clear' => true
		);
		return $fields;
	}

	/**
	 * Ajax Action which is called when shipping zones are saved.
	 * @return void
	 * @throws \JsonException
	 */
    public function save_shipping_zone_analytics_data() {
		if (isset($_REQUEST['data'])) {
			if (strpos(array_key_first($_REQUEST['data']), 'wp_pargo') !== false) {
				Analytics::submit( 'client_admin', 'click', 'save_shipping_zone' );
			}
		}
    }

	/**
	 * Handle a custom 'pargo_waybill' query var to get orders with the 'pargo_waybill' meta.
	 * @param array $query - Args for WP_Query.
	 * @param array $query_vars - Query vars from WC_Order_Query.
	 * @return array modified $query
	 */
	public function handle_order_pargo_waybill_query_var( $query, $query_vars ) {
		if ( ! empty( $query_vars['pargo_waybill'] ) ) {
			$query['meta_query'][] = array(
				'key' => 'pargo_waybill',
				'value' => esc_attr( $query_vars['pargo_waybill'] ),
				'compare' => 'IN'
			);
		}

		return $query;
	}

	/**
	 * @param $post_id
	 * @param $post
	 * @param $update
	 *
	 * @return void
	 * @throws \JsonException
	 */
	function update_post_action($post_id, $post, $update) {
		// make sure this is an admin user
		if (current_user_can( 'manage_options' )) {
			if ( $post->post_type === 'shop_order' ) {
				Analytics::submit('client_admin', 'click', 'order_update');
			}
		}
	}

	/**
	 * Action to create the assets/css folder if it does not exist
	 *
	 * @return void
	 */
	public function create_css_folder() {
		$assets_dir = plugin_dir_path( __FILE__ ) . "../../assets";
		if (file_exists($assets_dir . '/css') === false) {
			try {
				mkdir($assets_dir . '/css', 0755, true);
			} catch (Exception $e) {
				error_log($e);
				die(_e( 'Could not create assets css directory in Pargo plugin', 'pargo' ));
			}
		}
	}
}
