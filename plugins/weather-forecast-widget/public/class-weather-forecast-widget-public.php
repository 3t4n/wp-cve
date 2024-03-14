<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.bergtourentipp-tirol.at
 * @since      1.0.0
 *
 * @package    Weather_Forecast_Widget
 * @subpackage Weather_Forecast_Widget/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Weather_Forecast_Widget
 * @subpackage Weather_Forecast_Widget/public
 * @author     Dominik Luger <admin@bergtourentipp-tirol.at>
 */
class Weather_Forecast_Widget_Public {

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
		 * defined in Weather_Forecast_Widget_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Weather_Forecast_Widget_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/weather-forecast-widget-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wfw_template_1_css', plugin_dir_url( __FILE__ ) . 'css/weather-forecast-widget-template_1.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wfw_template_2_css', plugin_dir_url( __FILE__ ) . 'css/weather-forecast-widget-template_2.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'weather-icons', plugin_dir_url( __FILE__ ) . 'css/weather-icons/weather-icons.min.css', array (), $this->version, 'all' );
		
		//wp_enqueue_style( 'bootstrap_css', '//cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', array(), '5.1.3', 'all' );	//change register od bootstrap-theme, because of incompability with wp_enqueue_style
		wp_register_style( 'bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' );
		array_unshift( wp_styles()->queue, 'bootstrap_css' );
		wp_enqueue_style( 'fontawesome_5_css', '//use.fontawesome.com/releases/v5.3.0/css/all.css', array(), '5.3.0', 'all' );

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
		 * defined in Weather_Forecast_Widget_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Weather_Forecast_Widget_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/weather-forecast-widget-public.js', array( 'jquery' ), $this->version, false );
		
		// wp_enqueue_script( 'bootstrap_popper', '//cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js', array(), '2.10.2', true );	
		// wp_enqueue_script( 'bootstrap_javascript', '//cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js', array(), '5.1.3', true );
		
		//Check if bootstrap is already there
		global $wp_scripts;
		$bootstrap_enqueued = FALSE;
		//var_dump($wp_scripts->registered);
		foreach( $wp_scripts->registered as $script ) {
			if ((stristr($script->src, 'bootstrap.min.js') !== FALSE or
				 stristr($script->src, 'bootstrap.js') != FALSE) and
				wp_script_is($script->handle, $list = 'enqueued')) {	
				//echo '<script>alert("WFW Message: ' .$script->src .'")</script>';

				$bootstrap_enqueued = TRUE;
				break;
			}
		}
		if (!$bootstrap_enqueued) {
			wp_enqueue_script( 'bootstrap_popper', '//cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js', array(), '2.10.2', true );	
			wp_enqueue_script( 'bootstrap_javascript', '//cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js', array(), '5.1.3', true );
		}
		
	}
}
