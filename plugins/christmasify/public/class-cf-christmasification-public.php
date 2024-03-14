<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://cyberfoxdigital.co.uk
 * @since      1.0.0
 *
 * @package    Cf_Christmasification
 * @subpackage Cf_Christmasification/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cf_Christmasification
 * @subpackage Cf_Christmasification/public
 * @author     Cyber Fox <info@cyberfoxdigital.co.uk>
 */
class Cf_Christmasification_Public {

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
		 * defined in Cf_Christmasification_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cf_Christmasification_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//Hide on WooCommerce checkout pages
		if ( function_exists('is_woocommerce') && ( is_cart() || is_checkout() ) ) {
			return TRUE;
		}

		$homepage_only	 = get_option('cf_christmasify_homepage_only');

		if(!empty($homepage_only) && !is_front_page()){
			return TRUE;
		}

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cf-christmasification-public.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */

	private function CheckDate($date) {
	  $tempDate = explode('-', $date);
	  // checkdate(month, day, year)
	  return checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
	}

	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cf_Christmasification_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cf_Christmasification_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		//Hide on WooCommerce checkout pages
		if ( function_exists('is_woocommerce') && ( is_cart() || is_checkout() ) ) {
			return TRUE;
		}

		$homepage_only	 = get_option('cf_christmasify_homepage_only');
		$date_from	 		 = get_option('cf_christmasify_date_from');
		$date_to 				 = get_option('cf_christmasify_date_to'); 

		if(!empty($date_from) && $this->CheckDate($date_from) && date_i18n('U') < strtotime($date_from)){
			return false;
		}

		if(!empty($date_to) && $this->CheckDate($date_to) && date_i18n('U') >= strtotime($date_to)){
			return false;
		}
		
		if(!empty($homepage_only) && !is_front_page()){
			return TRUE;
		}

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cf-christmasification-public.min.js', array( 'jquery' ), $this->version, false );
		add_action( 'wp_footer', function(){
			include('partials/cf-christmasification-public-display.php');
		}, 1005);
	}

}
