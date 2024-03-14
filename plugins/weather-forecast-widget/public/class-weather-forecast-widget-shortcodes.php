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
 * Shortcodes
 *
 * @package    Weather_Forecast_Widget
 * @subpackage Weather_Forecast_Widget/public
 * @author     Dominik Luger <admin@bergtourentipp-tirol.at>
 */
class Weather_Forecast_Widget_Shortcodes {

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
		
		# Register AJAX JS
		wp_enqueue_script( 'ajax-process-widget', plugin_dir_url( __FILE__ ) . 'js/weather-forecast-widget-ajax-calls.js', array( 'jquery' ), $this->version, false );
        # Here we send PHP values to JS
        wp_localize_script( 
			'ajax-process-widget', 
			'wp_transfer_to_ajax',
             array( 
                 'ajaxurl'    => admin_url( 'admin-ajax.php' ),
				 'ajaxaction' => 'wfw_process_ajax',
                 'ajaxnonce'  => wp_create_nonce( 'ajax_post_validation' ),
                 'post_id'    => get_the_ID()
            ) 
        );

	}

	public function wfw_register_shortcodes() {
		//Shortcode hinzufügen, damit das Wetter Widget zurückgegeben wird
		add_shortcode( 'weather_forecast_widget', array($this, 'wfw_process_shortcode') );
		//add_shortcode( 'weather_forecast_widget_ajax', array($this, 'wfw_process_ajax_shortcode') );
        add_action( 'wp_ajax_wfw_process_ajax', array( $this, 'wfw_process_ajax' ) );
        add_action( 'wp_ajax_nopriv_wfw_process_ajax', array( $this, 'wfw_process_ajax' ) );
	}
	public function wfw_process_shortcode( $atts ) {
		global $post;
			
		$atts = extract( shortcode_atts( array(
			'template'				=> '1',
			'hourly_forecast'		=> 'hide',	//show or hide
			'daily_forecast'		=> 'hide',	//show or hide
			'alerts'				=> 'hide',	//show or hide
			'show_hourly_forecast'	=> 'X',
			'show_daily_forecast'	=> 'X',
			'show_alerts'			=> 'X',
			'lazy_loading' 			=> '',
			'city' 					=> '',
			'lat'  					=> '',
			'lon' 					=> '',
			'title_cityname' 		=> '',
			'title_overwrite' 		=> false,
			'max_width' 			=> '500px',
		), $atts ) );
		
		//create div id with milliseconds (then mutliple shortcodes in one page are possible)
		$id = '';
		$id = uniqid();
		$div_loading = '';
		$div_loading = 'wfw_widget_loading_' .$id; 
		$div_show = '';
		$div_show = 'wfw_widget_show_' .$id; 
		
		$sc_atts = array(
			//'div_show'			=> $div_id,
			'template'				=> $template,
			'hourly_forecast'		=> $hourly_forecast,
			'daily_forecast'		=> $daily_forecast,
			'alerts'				=> $alerts,
			'show_hourly_forecast'	=> $show_hourly_forecast,
			'show_daily_forecast'	=> $show_daily_forecast,
			'show_alerts'			=> $show_alerts,
			'lazy_loading' 			=> $lazy_loading,
            'city'      			=> $city,
            'lat'       			=> $lat,
            'lon'       			=> $lon,
			'title_cityname' 		=> $title_cityname,
			'title_overwrite' 		=> $title_overwrite,
			'max_width' 			=> $max_width
        );
		$sc_atts_encoded = json_encode( $sc_atts );								//convert array to a JSON string
		$sc_atts_encoded = htmlspecialchars( $sc_atts_encoded, ENT_QUOTES );	//convert any quotes into HTML entities so JSON string behaves as a proper HTML attribute.
		
		if ( $lazy_loading != 'X' ){
			// Call WITHOUT Lazy Loading/Ajay
			$plugin_actions = new Weather_Forecast_Widget_Actions( $this->plugin_name, $this->version );
			$html = $plugin_actions->wfw_get_weather_widget( get_the_ID(), $template, 
																		   $hourly_forecast, 
																		   $daily_forecast,
																		   $alerts,
																		   $show_hourly_forecast, 
																		   $show_daily_forecast, 
																		   $show_alerts, 
																		   $lazy_loading, 
																		   $city, 
																		   $lat, 
																		   $lon, 
																		   $title_cityname, 
																		   $title_overwrite, 
																		   $max_width );
			return $html;
		} else {
			//Choose Loading Text
			if(strpos($template,'alert') !== false) {
				$text_loading = __( 'Weather alerts are retrieved', 'weather-forecast-widget' );
			} else{
				$text_loading = __( 'Weather information is retrieved', 'weather-forecast-widget' );
			}
			
			//Call WITH Lazy Loading/Ajay
			return '
					<div class="wfw_widget_container" id="wfw_widget_container" data-id="' .$id .'" data-sc_atts="' .$sc_atts_encoded .'">
						<div id="' .$div_loading .'">
							<div class="d-flex flex-column align-items-center justify-content-center mt-1 mb-1">
								<div class="row">
									<div class="spinner-grow text-info" style="width: 3rem; height: 3rem;" role="status">
									   <span class="sr-only">' .$text_loading .'</span>
									</div>
								</div>
								<br>
								<div class="row">
									<strong>' .$text_loading .'</strong>
								</div>
							</div>
						</div>
						<div id="' .$div_show .'">
						</div>
					</div>';
		}
	}
	public function wfw_process_ajax( ) {
		 $sc_atts_from_ajax = $_REQUEST["sc_atts"];
		 
		 $plugin_actions = new Weather_Forecast_Widget_Actions( $this->plugin_name, $this->version );
		 $html = $plugin_actions->wfw_get_weather_widget( 	$_REQUEST["post_id"], 
															$sc_atts_from_ajax['template'], 
															$sc_atts_from_ajax['hourly_forecast'], 
															$sc_atts_from_ajax['daily_forecast'],
															$sc_atts_from_ajax['alerts'], 
															$sc_atts_from_ajax['show_hourly_forecast'], 
															$sc_atts_from_ajax['show_daily_forecast'],
															$sc_atts_from_ajax['show_alerts'], 
															$sc_atts_from_ajax['lazy_loading'], 
															$sc_atts_from_ajax['city'],  
															$sc_atts_from_ajax['lat'],  
															$sc_atts_from_ajax['lon'],  
															$sc_atts_from_ajax['title_cityname'],  
															$sc_atts_from_ajax['title_overwrite'],  
															$sc_atts_from_ajax['max_width'] );
		
		 wp_send_json_success( $html );
		 
		 die();
	}
}
