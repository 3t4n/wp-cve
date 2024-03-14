<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/acewebx/#content-plugins
 * @since      1.0.0
 *
 * @package    Ace_Woo_Ajax_Cart_Count
 * @subpackage Ace_Woo_Ajax_Cart_Count/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ace_Woo_Ajax_Cart_Count
 * @subpackage Ace_Woo_Ajax_Cart_Count/public
 * @author     AceWebX Team <developer@acewebx.com>
 */
class Ace_Woo_Ajax_Cart_Count_Public
{

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
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function aceEnqueueStyles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ace_Woo_Ajax_Cart_Count_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ace_Woo_Ajax_Cart_Count_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ace-woo-ajax-cart-count-public.css', array(), $this->version, 'all');
		wp_enqueue_style('ace-font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function aceEnqueueScripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ace_Woo_Ajax_Cart_Count_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ace_Woo_Ajax_Cart_Count_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ace-woo-ajax-cart-count-public.js', array('jquery'), $this->version, false);
	}

	public function aceWoocommercAddToCart($fragments)
	{
		global $woocommerce;
		ob_start();
		include('partials/ace-woo-ajax-cart-count-public-display.php');
		$fragments['a.cart-customlocation'] = ob_get_clean();
		return $fragments;
	}

	public function shortCodeHooks()
	{
		add_shortcode('WooAjaxCartCount', [$this, 'aceWooAjaxCartCount']);
	}

	public function aceWooAjaxCartCount($cart_total)
	{
		global $woocommerce;
		ob_start();
		include('partials/ace-woo-ajax-cart-count-public-display.php');
		$cart_total = ob_get_clean();
		return $cart_total;
	}
}
