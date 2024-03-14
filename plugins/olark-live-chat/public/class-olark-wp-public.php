<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.olark.com
 * @since      1.0.0
 *
 * @package    Olark_Wp
 * @subpackage Olark_Wp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Olark_Wp
 * @subpackage Olark_Wp/public
 * @author     Olark <platform@olark.com>
 */
class Olark_Wp_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->olark_options = get_option($this->plugin_name);
	}

	private function _format_price( $price ) {
		$negative = $price < 0;
		$price_formatted = apply_filters('raw_woocommerce_price', floatval($negative ? $price * -1 : $price ));
		$price_formatted = html_entity_decode(get_woocommerce_currency_symbol()) . apply_filters('formatted_woocommerce_price', number_format($price_formatted, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator()), $price, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator());
		return $price_formatted;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Olark_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Olark_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/olark-wp-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Olark_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Olark_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		include_once(ABSPATH . 'wp-admin/includes/plugin.php');
		$uses_woocommerce = class_exists('WooCommerce');
		$woocommerce_version = null;
		if ($uses_woocommerce) {
			$woocommerce_version = WC()->version;
		}

		if(!empty($this->olark_options['enable_olark'])){
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/olark-wp-public.js', array( 'jquery' ), $this->version, false );

			$dataToBePassed = array(
				'site_ID'           => $this->olark_options['olark_site_ID'],
				'expand' 			=> $this->olark_options['start_expanded'],
				'float' 			=> $this->olark_options['detached_chat'],
				'override_lang' => $this->olark_options['override_lang'],
				'lang'				=> $this->olark_options['olark_lang'],
				'api'				=> $this->olark_options['olark_api'],
				'mobile'			=> $this->olark_options['olark_mobile'],
				'woocommerce'			=> $uses_woocommerce,
				'woocommerce_version'		=> $woocommerce_version,
				'enable_cartsaver'	=>	"0"
			);


			if (!empty($this->olark_options['enable_cartsaver']) && $uses_woocommerce) {
				$dataToBePassed['enable_cartsaver'] = '1';
				$items = WC()->cart->get_cart();
				$cart_total_formatted = $this->_format_price(WC()->cart->cart_contents_total);

				$cart_items = array();
				foreach($items as $item => $values) {
					$product = $values['data'];
					$cart_items[] = array(
						'name' => $product->get_title(),
						'sku' => $product->get_sku(),
						'quantity'  => $values['quantity'],
						'price' => $this->_format_price(get_post_meta($values['product_id'] , '_price', true)),
						'magento' => array(
							'formatted_price' => $this->_format_price(get_post_meta($values['product_id'] , '_price', true))
						)
					);
				}
				/* TODO customer information */
				$customer = array();
				$magento = array(
					'total' => WC()->cart->cart_contents_total,
					'formatted_total' => $cart_total_formatted,
					'extra_items' => [],
					'recent_events' => array(
					)
				);
				$dataToBePassed['cart_info'] = array(
					'items' => $cart_items,
					'customer' => $customer,
					'magento' => $magento
				);
			}

			wp_localize_script( $this->plugin_name, 'olark_vars', $dataToBePassed );
		}
	}
}
