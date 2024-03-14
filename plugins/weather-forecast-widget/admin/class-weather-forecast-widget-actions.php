<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.bergtourentipp-tirol.at
 * @since      1.0.0
 *
 * @package    Weather_Forecast_Widget
 * @subpackage Weather_Forecast_Widget/admin
 */

/**
 * Process Widget Actions
 *
 * @package    Weather_Forecast_Widget
 * @subpackage Weather_Forecast_Widget/admin
 * @author     Dominik Luger <admin@bergtourentipp-tirol.at>
 */
 
define('CONVERT_FACTOR__M_S_KM_H', '3.6');

class Weather_Forecast_Widget_Actions {

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
		 * defined in Weather_Forecast_Widget_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Weather_Forecast_Widget_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

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
		 * defined in Weather_Forecast_Widget_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Weather_Forecast_Widget_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	}
	
	 /**
	 * Plugin Arbeit machen
	 *
	 * @since    1.0.0
	 */			
	public function wfw_get_weather_widget( $post_id, $template, 
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
													  $max_width ) {
		global $post;
		if (!$post){
			$ajax = true;
			$post = get_post( $post_id );
		} else {
			$ajax = false;
		}
		
		// INITIALIZE
		$lang_code = "";
		$cache_time = "";
		$apiKey = "";
		$apiCacheValue = "";
		$apiUrl = "";
		$apiResponse = "";
		$widget_title = "";
		$widget_image = "";
		
		//create id for collapse button with milliseconds (then mutliple shortcodes in one page are possible)
		$uniqid = '';
		$uniqid = uniqid();
		
		// get PLUGIN OPTIONS/SETTINGS
		$options = get_option( $this->plugin_name . '-settings' );
		
		//Check/set API KEY		
		if (!isset($options['openweathermap-apikey'])) {
			if ( current_user_can( 'administrator' ) ) {
				return $this->error_msg( __( 'ERROR MESSAGE FOR ADMIN => No Open Weather Map API Key provided in settings!', 'weather-forecast-widget' ) );
			} else {
				return $this->error_msg( __( 'Weather Forecast Info can´t be read at the moment!', 'weather-forecast-widget' ) );
			}
		} else {
			$apiKey = $options['openweathermap-apikey'];
		}
		
		//Get 2-DIGIT LANGUAGE CODE
		$lang_code = strtolower( substr( get_locale(), 0, 2 ) );
		if (!$lang_code) {
			if ( current_user_can( 'administrator' ) ) {
				return $this->error_msg( __( 'ERROR MESSAGE FOR ADMIN => Language code can´t be read from wordpress!', 'weather-forecast-widget' ) );
			} else {
				return $this->error_msg( __( 'Weather Forecast Info can´t be read at the moment!', 'weather-forecast-widget' ) );
			}
		}
		
		//Get CACHE TIME
		if (isset($options['cache-time']) && !empty($options['cache-time'])) {
			if ($options['cache-time'] != 'never') {
				$cache_time = $options['cache-time'];
			}
		}
		
		//Get WEATHER OPTIONS (units, values to show, other options)
		if (isset($options['weather-units-radiobuttons'])) {
			if ($options['weather-units-radiobuttons']){
				$weather_unit = $options['weather-units-radiobuttons'];
			}
		} else {
			$weather_unit = 'metric';
		}
		if (isset($options['weather-value-checkboxes'])) {
			if ($options['weather-value-checkboxes']){
				$weather_values = (array) $options['weather-value-checkboxes'];
			}
		} else {
			$weather_values = array();
		}
		if ($weather_unit == 'imperial') {
			$widget_options = array(
										'daily_forecast'		=> $daily_forecast,
										'hourly_forecast'		=> $hourly_forecast,
										'alerts'				=> $alerts,
										'show_daily_forecast'	=> $show_daily_forecast,
										'show_hourly_forecast'	=> $show_hourly_forecast,
										'show_alerts'			=> $show_alerts,
										'values' 				=> $weather_values,
										'unit' 	 				=> $weather_unit,
										'temp' 	 				=> '&#8457',
										'wind' 	 				=> 'mph',
									);
		} else {
			$widget_options = array(
										'daily_forecast'		=> $daily_forecast,
										'hourly_forecast'		=> $hourly_forecast,
										'alerts'				=> $alerts,
										'show_daily_forecast'	=> $show_daily_forecast,
										'show_hourly_forecast'	=> $show_hourly_forecast,
										'show_alerts'			=> $show_alerts,
										'values' 				=> $weather_values,
										'unit'   				=> $weather_unit,
										'temp'   				=> '&#8451',
										'wind'   				=> 'km/h',
									);
		}
		
		//Check/set API URL
		if ( $city ) {
			$apiUrl =	'https://api.openweathermap.org/data/2.5/weather?q=' . $city . '&units=' .$weather_unit .'&lang=' . $lang_code . '&appid=' . $apiKey;
			$apiCacheValue = 'wfw_' . $lang_code . '_' . $city;
			
			//API CALL for CITY => to get the COORDINATES
			$apiResponse = $this->wfw_call_api( $apiCacheValue, $cache_time, $apiUrl );
			$http_status = wp_remote_retrieve_response_code( $apiResponse );
			$body = wp_remote_retrieve_body( $apiResponse );
			$data = json_decode($body);
			
			//Extract coordinates
			if ( $http_status === 200 && !is_wp_error( $apiResponse ) ) {
				if ($data) {
					$lat = $data->coord->lat;
					$lon = $data->coord->lon;
				}
			}
			
			//Clear variables again
			$apiUrl = "";
			$apiResponse = "";
			$http_status = "";
			$body = "";
			$data = "";
		};
		if ( !$apiUrl && $lat && $lon ) {
			$apiUrl =	'https://api.openweathermap.org/data/2.5/onecall?lat=' . $lat . '&lon=' . $lon . '&exclude=minutely&units=' .$weather_unit .'&lang=' . $lang_code . '&appid=' . $apiKey;
			$apiCacheValue = 'wfw_' . $lang_code . '_' .$weather_unit . '_'. $lat . '_' . $lon;
		}
		if ( !$apiUrl ) {
			if ( current_user_can( 'administrator' ) ) {
				return $this->error_msg( __( 'ERROR MESSAGE FOR ADMIN => No Shortcode Attributes provided!', 'weather-forecast-widget' ) );
			} else {
				return $this->error_msg( __( 'Weather Forecast Info can´t be read at the moment!', 'weather-forecast-widget' ) );
			}
		}
		
		//Get necessary TITLE for widget
		if ( $city && $title_cityname === 'X' ) {
			$widget_title = $city;
		}
		
		if ( !$widget_title && $title_overwrite !== 'false' ) {
			$widget_title = $title_overwrite;
		}
		
		if ( !$widget_title ) {
			if (!isset($options['widget-title-radiobuttons'])) {
				if ( current_user_can( 'administrator' ) ) {
					return $this->error_msg( __( 'ERROR MESSAGE FOR ADMIN => No option for WIDGET TITLE set!', 'weather-forecast-widget' ) );
				} else {
					return $this->error_msg( __( 'Weather Forecast Info can´t be read at the moment!', 'weather-forecast-widget' ) );
				}
			} else {
				if ($options['widget-title-radiobuttons'] === 'post_title') {
					$widget_title = $post->post_title;
				}
				if ($options['widget-title-radiobuttons'] === 'post_meta') {
					if (!isset($options['widget-title-postmeta']) || empty($options['widget-title-postmeta']) ) {
						if ( current_user_can( 'administrator' ) ) {
							return $this->error_msg( __( 'ERROR MESSAGE FOR ADMIN => POST META FIELD for WIDGET TITLE hasn´t been set!', 'weather-forecast-widget' ) );
						} else {
							return $this->error_msg( __( 'Weather Forecast Info can´t be read at the moment!', 'weather-forecast-widget' ) );
						}
					} else {
						$widget_title = get_post_meta($post->ID, $options['widget-title-postmeta'], true);
					}
				}
			
			}
		}
		
		//BACKGROUND IMAGE
		$background_image = "";
		if (isset($options['widget-backgroundimg'])) {
			$thumb_option_src = $options['widget-backgroundimg'];
			if ( $thumb_option_src ) {
				$widget_image = $thumb_option_src;
			}
		}
		if (!$background_image) {
			$thumb_src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' );
			if ($thumb_src) {
				$thumb_src = $thumb_src[0];
				$widget_image = $thumb_src;
			}
		}

		//API CALL for COORDINATES
		$apiResponse = $this->wfw_call_api( $apiCacheValue, $cache_time, $apiUrl );
		$http_status = wp_remote_retrieve_response_code( $apiResponse );
		$body = wp_remote_retrieve_body( $apiResponse );
		$data = json_decode($body);
		
		$currentTime = time();

		if ( $http_status === 200 && !is_wp_error( $apiResponse ) ) {
			if ($data) {
				//CALL WIDGET TEMPLATE
				if ($template === "1") { $show_widget = $this->wfw_show_template1( $uniqid, $widget_image, $widget_title, $max_width, $data, $widget_options ); };
				if ($template === "2") { $timezone = new DateTimeZone($data->timezone); 
										 $show_widget = $this->wfw_show_template2( $uniqid, $widget_image, $widget_title, $max_width, $data, $widget_options, wp_date( "Ymd", current_time( 'timestamp', 0 ), $timezone) ); };
				if ($template === "3") { $show_widget = $this->wfw_show_template3( $uniqid, $widget_image, $widget_title, $max_width, $data, $widget_options ); };
				if ($template === "alert_1") { $show_widget = $this->wfw_show_alert1( $uniqid, $widget_title, $max_width, $data, $widget_options ); };
				return $show_widget;
			}
		} else {
			if ( current_user_can( 'administrator' ) ) {
				$error = 'Errorcode: ' . $http_status . ' (' . $data->message . ')';
				return $this->error_msg( sprintf(__('RECEIVED ERROR MESSAGE FOR ADMIN => %s', 'weather-forecast-widget' ), $error ) );
			} else {
				return $this->error_msg( __( 'Weather Forecast Info can´t be read at the moment!', 'weather-forecast-widget' ) );
			}
		}
	}
	private function error_msg( $text ) {
		return '<div class="d-flex flex-column align-items-center justify-content-center">
					<div class="alert alert-danger alert-dismissible fade show d-flex flex-column align-items-center justify-content-center" style="display:inline-block;" role="alert">'
						.$text
						. '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				</div>';
	}
	private function warning_msg( $text ) {
		return '<div class="d-flex flex-column justify-content-center">
					<div class="alert alert-warning alert-dismissible fade show d-flex flex-column align-items-center justify-content-center" style="display:inline-block;" role="alert">'
						.$text
						. '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				</div>';
	}
	private function information_msg( $text ) {
		return '<div class="d-flex flex-column align-items-center justify-content-center">
					<div class="alert alert-info fade show d-flex flex-column align-items-center justify-content-center" style="display:inline-block;" role="alert">'
						.$text
						. '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				</div>';
	}
	private function wfw_call_api( $cache_value, $cache_time, $url ) {
		//Get response data from CACHE
		$cache_data = "";
		$cache_data_http_status = "";
		if ( $cache_value && $cache_time ) {
			$cache_data = get_transient($cache_value);
			$cache_data_http_status = wp_remote_retrieve_response_code( $cache_data );
		}

		if ($cache_data && $cache_data_http_status === 200 ) {
			//Return cached response data
			return $cache_data;
		} else {
			//No cache => get response data from API
			$response = "";
			$response = wp_remote_get($url,
			array(
				'sslverify' => true,
				'headers' => array(
									'Accept' => 'application/json')
				)
			);
			$response_http_status = wp_remote_retrieve_response_code( $response );
			//Cache response data
			if ( $response_http_status === 200 && $cache_value && $cache_time ) {
				set_transient( $cache_value, $response, $cache_time * MINUTE_IN_SECONDS  );
			}
			//Return api response data
			return $response;
		}
	}
	private function wfw_get_icon( $name, $type ) {
		if (!$type) {
			//Get PLUGIN OPTIONS/SETTINGS
			$options = get_option( $this->plugin_name . '-settings' );
			
			//Check/set API KEY		
			if (isset($options['widget-icon-radiobuttons'])) {
				if( is_array($options) ) {
					$type = $options[ 'widget-icon-radiobuttons' ];
				}
			} else {
				$type = 'icon_animated_fill';
				//$type = 'icon_animated_line';
				//$type = 'icon_static';
			}
		}
		
		//Exception for converting name from static to animated
		if ($type == 'icon_animated_fill' || $type == 'icon_animated_line') {
			if ($name === "windy" ) { $name = "windsock"; };
			if ($name === "cloud" ) { $name = "cloudy"; };
			if ($name === "time-2" ) { $name = "compass"; };
			if ($name === "snowflake-cold" ) { $name = "snowflake"; };
		}
				
		switch ($type) {
			case 'icon_animated_fill':				
				$path_icon = PATH_ANIMATED_ICONS_FILLED_ALL . $name . '.svg';
				$html = '<img src="' . $path_icon . '" alt="Icon Filled SVG">';
				break;
			case 'icon_animated_line':
				$path_icon = PATH_ANIMATED_ICONS_NOT_FILLED_ALL . $name . '.svg';
				$html = '<img src="' . $path_icon . '" alt="Icon Not Filled SVG">';
				break;
			case 'icon_static':
				$html = '<i class="wi wi-' . $name . '"></i>';
				break;
		}
		
		return $html;
	}
	private function wfw_get_weather_icon( $weather, $type ) {
		if (!$type) {
			//Get PLUGIN OPTIONS/SETTINGS
			$options = get_option( $this->plugin_name . '-settings' );
			
			//Check/set API KEY		
			if (isset($options['widget-icon-radiobuttons'])) {
				if( is_array($options) ) {
					$type = $options[ 'widget-icon-radiobuttons' ];
				}
			} else {
				$type = 'icon_animated_fill';
				//$type = 'icon_animated_line';
				//$type = 'icon_static';
			}
		}
		
		switch ($type) {
			case 'icon_animated_fill':
				$path_icon = PATH_ANIMATED_ICONS_FILLED . $weather->icon . '.svg';
				$html = '<img src="' . $path_icon . '" alt="Weather Icon Filled SVG">';
				//<span class="span"><img src="http://localhost/Wordpress/wp-content/uploads/2021/11/snowy-1.svg" alt="SVG mit img Tag laden" width="100%" height="100%"></span>
				break;
			case 'icon_animated_line':
				$path_icon = PATH_ANIMATED_ICONS_NOT_FILLED . $weather->icon . '.svg';
				$html = '<img src="' . $path_icon . '" alt="Weather Icon Not Filled SVG">';
				break;
			case 'icon_static':
				$html = '<i class="wi wi-owm-' . $weather->id . '"></i>';
				break;
		}
		
		return $html;
	}
	private function wfw_show_template1( $uniqid, $thumb, $title, $max_width, $data, $widget_options ) {
		global $post;
		
		$weather_values = $widget_options['values'];
		if (!$weather_values) {
			$weather_values = array();
		}
		if ( $widget_options['unit'] === 'imperial' ) {
			$convert_wind = 1;
		} else {
			$convert_wind = CONVERT_FACTOR__M_S_KM_H;
		}

		if ($data) {
			//DATE CONVERSION
			$timezone    = new DateTimeZone($data->timezone);
			
			$currentDate_Formatted = wp_date( "D, " . get_option('date_format'),$data->current->dt, $timezone);
			$currentTime_Sunrise = wp_date( "H:i", $data->current->sunrise, $timezone);
			$currentTime_Sunset = wp_date( "H:i", $data->current->sunset, $timezone);
			$futureDate_1_Formatted = wp_date( "D, d.m", $data->daily[1]->dt, $timezone);
			$futureTime_1_Sunrise = wp_date( "H:i", $data->daily[1]->sunrise, $timezone);
			$futureTime_1_Sunset = wp_date( "H:i", $data->daily[1]->sunset, $timezone);
			$futureDate_2_Formatted = wp_date( "D, d.m", $data->daily[2]->dt, $timezone);
			$futureTime_2_Sunrise = wp_date( "H:i", $data->daily[2]->sunrise, $timezone);
			$futureTime_2_Sunset = wp_date( "H:i", $data->daily[2]->sunset, $timezone);
			$futureDate_3_Formatted = wp_date( "D, d.m", $data->daily[3]->dt, $timezone);
			$futureTime_3_Sunrise = wp_date( "H:i", $data->daily[3]->sunrise, $timezone);
			$futureTime_3_Sunset = wp_date( "H:i", $data->daily[3]->sunset, $timezone);
			
			$background_image = "";
			if ( $thumb ) {
				$background_image = 'background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(' . $thumb . ')';
			} else {
				$background_image = 'background-image: linear-gradient(rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.9)), url( )';
			}
			
			$currentRain = isset($data->current->rain->{'1h'}) ? number_format($data->current->rain->{'1h'}, 0, '.', ' ') : '0';
			$currentSnow = isset($data->current->snow->{'1h'}) ? number_format($data->current->snow->{'1h'}, 0, '.', ' ') : '0';
			$date1_Humidity = isset($data->daily[1]->humidity) ? number_format($data->daily[1]->humidity, 0, '.', ' ') : '0';					
			$date1_Rain = isset($data->daily[1]->rain) ? number_format($data->daily[1]->rain, 0, '.', ' ') : '0';
			$date1_Snow = isset($data->daily[1]->snow) ? number_format($data->daily[1]->snow, 0, '.', ' ') : '0';
			$date2_Humidity = isset($data->daily[2]->humidity) ? number_format($data->daily[2]->humidity, 0, '.', ' ') : '0';					
			$date2_Rain = isset($data->daily[2]->rain) ? number_format($data->daily[2]->rain, 0, '.', ' ') : '0';
			$date2_Snow = isset($data->daily[2]->snow) ? number_format($data->daily[2]->snow, 0, '.', ' ') : '0';
			$date3_Humidity = isset($data->daily[3]->humidity) ? number_format($data->daily[3]->humidity, 0, '.', ' ') : '0';					
			$date3_Rain = isset($data->daily[3]->rain) ? number_format($data->daily[3]->rain, 0, '.', ' ') : '0';
			$date3_Snow = isset($data->daily[3]->snow) ? number_format($data->daily[3]->snow, 0, '.', ' ') : '0';
			
			$html_output =
			'<div id="wfw_widget" class="container" style="max-width:' . $max_width . ';">
				<div class="row">
					<div class="">
						<div class="weather" data-hover="' . __( 'Last updated: ', 'weather-forecast-widget' ) . wp_date( "d.m.Y H:i:s", $data->current->dt, $timezone) . '">     									
							<div class="current" style="background: linear-gradient(180deg, rgba(2,0,36,1) 0%, rgba(33,71,83,1) 60%, rgba(0,212,255,1) 100%);background-size: cover;background-position: center;' . $background_image .';">
								<div class="info">
								<div class="row">
									<div class="title" style="margin-top: -15px; padding-bottom: 20px;">' . $title . '</div>
								</div>
								<div class="row" style="white-space:nowrap;">
									<div class="col-one">
										<div class="data"><small>' . $currentDate_Formatted . '</small></div>'
										. '<div class="data"><small>' . $this->wfw_get_icon( "sunrise", false ) . '  ' . $currentTime_Sunrise . '     |     ' . $this->wfw_get_icon( "sunset", false ) . '  ' . $currentTime_Sunset . '</small></div>';
										if ( in_array( 'temp', $weather_values) ) {
										$html_output = $html_output
										.'<div class="data">' .$this->wfw_get_icon( "thermometer", false ) .'<small><small><small> ' . __( 'TEMP:', 'weather-forecast-widget' ) . '</small></small> ' .number_format($data->current->temp, 0, '.', ' ') .' ' .$widget_options['temp'] .'</small></div>'; };
										if ( in_array( 'wind', $weather_values) ) {
										$html_output = $html_output
										.'<div class="data">' .$this->wfw_get_icon( "windy", false ) .'<small><small><small> ' . __( 'WIND:', 'weather-forecast-widget' ) . '</small></small> ' .number_format(($data->current->wind_speed * $convert_wind), 0, '.', ' ') . ' ' .$widget_options['wind'] .'</small></div>'; };
										if ( in_array( 'clouds', $weather_values) ) {
										$html_output = $html_output
										.'<div class="data">' .$this->wfw_get_icon( "cloud", false ) .'<small><small><small> ' . __( 'CLOUDS:', 'weather-forecast-widget' ) . '</small></small> ' .$data->current->clouds . ' &percnt;</small></div>'; };
										if ( in_array( 'humidity', $weather_values) ) {
										$html_output = $html_output
										.'<div class="data">' .$this->wfw_get_icon( "humidity", false ) .'<small><small><small> ' . __( 'HUMIDITY:', 'weather-forecast-widget' ) . '</small></small> ' .number_format($data->current->humidity, 0, '.', ' ') . ' &percnt;</small></div>'; };
										if ( in_array( 'rain', $weather_values) ) {
										$html_output = $html_output
										.'<div class="data">' .$this->wfw_get_icon( "raindrop", false ) .'<small><small><small> ' . __( 'RAIN:', 'weather-forecast-widget' ) . '</small></small> ' .$currentRain . ' mm</small></div>'; };
										if ( in_array( 'snow', $weather_values) ) {
										$html_output = $html_output
										.'<div class="data">' .$this->wfw_get_icon( "snowflake-cold", false ) .'<small><small><small> ' . __( 'SNOW:', 'weather-forecast-widget' ) . '</small></small> ' .$currentSnow . ' mm</small></div>'; };
									$html_output = $html_output
									. '
									</div>
									<div class="col-two justify-content-center">
										<div class="icon">
											<span class="span">'. $this->wfw_get_weather_icon( $data->current->weather[0], false ). '</span>
											<div class="desc">' . $data->current->weather[0]->description . '</div>
										</div>
									</div>
								</div>
								</div>
							</div>
							<div class="future" style="background-color:white;">
								<div class="day">
									<h4>' . $futureDate_1_Formatted . '</h4>'
									. '<div class="data" style="padding-top: 5px"><small>' . $this->wfw_get_icon( "sunrise", false ) . '  ' . $futureTime_1_Sunrise . '</small></div>
									<div class="data"><small>' . $this->wfw_get_icon( "sunset", false ) . '  ' . $futureTime_1_Sunset . '</small></div>'
									. '<span class="span">' . $this->wfw_get_weather_icon( $data->daily[1]->weather[0], false, "35px" ) . '</span>
									<div class="data" style="padding-bottom: 5px; padding-top: 10px;"><small><small><strong>' . $data->daily[1]->weather[0]->description . '</strong></small></small></div>';
									if ( in_array( 'temp', $weather_values) ) {
									$html_output = $html_output
									.'<div class="data"><small><small><small>' . __( 'TEMP:', 'weather-forecast-widget' ) . '</small> <strong>' .number_format($data->daily[1]->temp->day, 0, '.', ' ') .' ' .$widget_options['temp'] .'</strong></small></small></div>'; };
									if ( in_array( 'wind', $weather_values) ) {
									$html_output = $html_output
									.'<div class="data"><small><small><small>' . __( 'WIND:', 'weather-forecast-widget' ) . '</small> <strong>' .number_format(($data->daily[1]->wind_speed * $convert_wind), 0, '.', ' ') . ' ' .$widget_options['wind'] .'</strong></small></small></div>'; };
									if ( in_array( 'clouds', $weather_values) ) {
									$html_output = $html_output
									.'<div class="data"><small><small><small>' . __( 'CLOUDS:', 'weather-forecast-widget' ) . '</small> <strong>' .number_format($data->daily[1]->clouds, 0, '.', ' ') . ' &percnt;</strong></small></small></div>'; };
									if ( in_array( 'humidity', $weather_values) ) {
									$html_output = $html_output
									.'<div class="data"><small><small><small>' . __( 'HUMIDITY:', 'weather-forecast-widget' ) . '</small> <strong>' .$date1_Humidity . ' &percnt;</strong></small></small></div>'; };
									if ( in_array( 'rain', $weather_values) ) {
									$html_output = $html_output
									.'<div class="data"><small><small><small>' . __( 'RAIN:', 'weather-forecast-widget' ) . '</small> <strong>' .$date1_Rain . ' mm</strong></small></small></div>'; };
									if ( in_array( 'snow', $weather_values) ) {
									$html_output = $html_output
									.'<div class="data"><small><small><small>' . __( 'SNOW:', 'weather-forecast-widget' ) . '</small> <strong>' .$date1_Snow . ' mm</strong></small></small></div>'; };
								$html_output = $html_output
								.'</div>
								<div class="day">
									<h4>' . $futureDate_2_Formatted . '</h4>'
									. '<div class="data" style="padding-top: 5px"><small>' . $this->wfw_get_icon( "sunrise", false ) . '  ' . $futureTime_2_Sunrise . '</small></div>
									<div class="data"><small>' . $this->wfw_get_icon( "sunset", false ) . '  ' . $futureTime_2_Sunset . '</small></div>'													
									. '<span class="span">' . $this->wfw_get_weather_icon( $data->daily[2]->weather[0], false, "35px" ) . '</span>
									<div class="data" style="padding-bottom: 5px; padding-top: 10px;"><small><small><strong>' . $data->daily[2]->weather[0]->description . '</strong></small></small></div>';
									if ( in_array( 'temp', $weather_values) ) {
									$html_output = $html_output
									.'<div class="data"><small><small><small>' . __( 'TEMP:', 'weather-forecast-widget' ) . '</small> <strong>' . number_format($data->daily[2]->temp->day, 0, '.', ' ') .' ' .$widget_options['temp'] .'</strong></small></small></div>'; };
									if ( in_array( 'wind', $weather_values) ) {
									$html_output = $html_output
									.'<div class="data"><small><small><small>' . __( 'WIND:', 'weather-forecast-widget' ) . '</small> <strong>' . number_format(($data->daily[2]->wind_speed * $convert_wind), 0, '.', ' ') . ' ' .$widget_options['wind'] .'</strong></small></small></div>'; };
									if ( in_array( 'clouds', $weather_values) ) {
									$html_output = $html_output
									.'<div class="data"><small><small><small>' . __( 'CLOUDS:', 'weather-forecast-widget' ) . '</small> <strong>' . number_format($data->daily[2]->clouds, 0, '.', ' ') . ' &percnt;</strong></small></small></div>'; };
									if ( in_array( 'humidity', $weather_values) ) {
									$html_output = $html_output
									.'<div class="data"><small><small><small>' . __( 'HUMIDITY:', 'weather-forecast-widget' ) . '</small> <strong>' .$date2_Humidity . ' &percnt;</strong></small></small></div>'; };
									if ( in_array( 'rain', $weather_values) ) {
									$html_output = $html_output
									.'<div class="data"><small><small><small>' . __( 'RAIN:', 'weather-forecast-widget' ) . '</small> <strong>' .$date2_Rain . ' mm</strong></small></small></div>'; };
									if ( in_array( 'snow', $weather_values) ) {
									$html_output = $html_output
									.'<div class="data"><small><small><small>' . __( 'SNOW:', 'weather-forecast-widget' ) . '</small> <strong>' .$date2_Snow . ' mm</strong></small></small></div>'; };
								$html_output = $html_output
								.'</div>
								<div class="day">
									<h4>' . $futureDate_3_Formatted . '</h4>'
									. '<div class="data" style="padding-top: 5px"><small>' . $this->wfw_get_icon( "sunrise", false ) . '  ' . $futureTime_3_Sunrise . '</small></div>
									<div class="data"><small>' . $this->wfw_get_icon( "sunset", false ) . '  ' . $futureTime_3_Sunset . '</small></div>'													
									. '<span class="span">' . $this->wfw_get_weather_icon( $data->daily[3]->weather[0], false, "35px" ) . '</span>
									<div class="data" style="padding-bottom: 5px; padding-top: 10px;"><small><small><strong>' . $data->daily[3]->weather[0]->description . '</strong></small></small></div>';
									if ( in_array( 'temp', $weather_values) ) {
									$html_output = $html_output
									.'<div class="data"><small><small><small>' . __( 'TEMP:', 'weather-forecast-widget' ) . '</small> <strong>' . number_format($data->daily[3]->temp->day, 0, '.', ' ') .' ' .$widget_options['temp'] .'</strong></small></small></div>'; };
									if ( in_array( 'wind', $weather_values) ) {
									$html_output = $html_output
									.'<div class="data"><small><small><small>' . __( 'WIND:', 'weather-forecast-widget' ) . '</small> <strong>' . number_format(($data->daily[3]->wind_speed * $convert_wind), 0, '.', ' ') . ' ' .$widget_options['wind'] .'</strong></small></small></div>'; };
									if ( in_array( 'clouds', $weather_values) ) {
									$html_output = $html_output
									.'<div class="data"><small><small><small>' . __( 'CLOUDS:', 'weather-forecast-widget' ) . '</small> <strong>' . number_format($data->daily[3]->clouds, 0, '.', ' ') . ' &percnt;</strong></small></small></div>'; };
									if ( in_array( 'humidity', $weather_values) ) {
									$html_output = $html_output
									.'<div class="data"><small><small><small>' . __( 'HUMIDITY:', 'weather-forecast-widget' ) . '</small> <strong>' .$date3_Humidity . ' &percnt;</strong></small></small></div>'; };
									if ( in_array( 'rain', $weather_values) ) {
									$html_output = $html_output
									.'<div class="data"><small><small><small>' . __( 'RAIN:', 'weather-forecast-widget' ) . '</small> <strong>' .$date3_Rain . ' mm</strong></small></small></div>'; };
									if ( in_array( 'snow', $weather_values) ) {
									$html_output = $html_output
									.'<div class="data"><small><small><small>' . __( 'SNOW:', 'weather-forecast-widget' ) . '</small> <strong>' .$date3_Snow . ' mm</strong></small></small></div>'; };
								$html_output = $html_output
								.'</div>
							</div>
						</div>
					</div>
				</div>';
				
				//Show alerts (only if alerty are available)
				if ($widget_options['show_alerts'] === 'X') {
					$html_output = $html_output 
					.'<!-- ALERTS -->'
					.$this->wfw_get_alerts( $uniqid, $data, $widget_options );
				}
				//Show alerts (only if alerty are available and requested)
				/*
				if ($widget_options['show_alerts'] === 'X') {
					if (!empty($data->alerts)) {
						$html_output = $html_output .'<br><div class="row d-flex text-center justify-content-center align-items-center h-100">';
					}
					if (!empty($data->alerts)) {
						foreach( $data->alerts as $alert ) {
						
						$fromDate = wp_date( "D, " . get_option('date_format') ." H:i",  $alert->start, $timezone);
						$toDate = wp_date( "D, " . get_option('date_format') ." H:i",  $alert->end, $timezone);
						
						$alert_raw = '<strong>' .$fromDate .' - ' .$toDate .'</strong>';
						$alert_raw = $alert_raw .$alert->description .' <br>(' .$alert->sender_name .')';
						$alert_html = $this->warning_msg( $alert_raw );
						
							
						$html_output = $html_output .$alert_html;
					};
					};
					if (!empty($data->alerts)) {
						$html_output = $html_output .'</div>';
					}				
				}
				*/
				
			$html_output = $html_output
			.'</div>';
			
			return $html_output;
		} else {
			return null;
		}
	}
	private function wfw_show_template2( $uniqid, $thumb, $title, $max_width, $data, $widget_options, $mainDate ) {
		global $post;
		
		$weather_values = $widget_options['values'];
		if (!$weather_values) {
			$weather_values = array();
		}
		if ( $widget_options['unit'] === 'imperial' ) {
			$convert_wind = 1;
		} else {
			$convert_wind = CONVERT_FACTOR__M_S_KM_H;
		}
		
		if ($data) {
			//DATE CONVERSION
			$timezone    = new DateTimeZone($data->timezone);
			
			$currentHour = wp_date( "g", $data->current->dt, $timezone);
			$currentDate = wp_date( "Ymd", $data->current->dt, $timezone);
			
			$lastUpdated = __( 'Last updated: ', 'weather-forecast-widget' ) .wp_date( "d.m.Y H:i:s", $data->current->dt, $timezone);
	
			if ( $mainDate === $currentDate ){
				$date0_Formatted = wp_date( "D, " . get_option('date_format'), $data->current->dt, $timezone);
				
				$date0_Weather_icon = $this->wfw_get_weather_icon( $data->current->weather[0], false );
				$date0_Description = $data->current->weather[0]->description;
				
				$date0_Sunrise = wp_date( "H:i", $data->current->sunrise, $timezone);
				$date0_Sunset = wp_date( "H:i", $data->current->sunset, $timezone);
				
				$date0_TempMin = number_format($data->daily[0]->temp->min, 0, '.', ' ');
				$date0_TempDay = number_format($data->current->temp, 0, '.', ' ');
				$date0_TempMax = number_format($data->daily[0]->temp->max, 0, '.', ' ');
				
				$date0_Wind = number_format(($data->current->wind_speed * $convert_wind), 0, '.', ' ');
				$date0_Clouds = number_format($data->current->clouds, 0, '.', ' ');
				$date0_Humidity = number_format($data->current->humidity, 0, '.', ' ');
				$date0_Rain = isset($data->current->rain->{'1h'}) ? number_format($data->current->rain->{'1h'}, 0, '.', ' ') : '0';
				$date0_Snow = isset($data->current->snow->{'1h'}) ? number_format($data->current->snow->{'1h'}, 0, '.', ' ') : '0';
			} else {
				foreach( $data->daily as $day ) {				
					if ( $mainDate == wp_date( "Ymd", $day->dt, $timezone ) ) {								
						$date0_Formatted = wp_date( "D, " . get_option('date_format'), $day->dt, $timezone);
						
						$date0_Weather_icon = $this->wfw_get_weather_icon( $day->weather[0], false );
						$date0_Description = $day->weather[0]->description;
						
						$date0_Sunrise = wp_date( "H:i", $day->sunrise, $timezone);
						$date0_Sunset = wp_date( "H:i", $day->sunset, $timezone);
						
						if ( isset($day->temp->day) ){ 
							$date0_TempMin = number_format($day->temp->min, 0, '.', ' ');
							$date0_TempDay = number_format($day->temp->day, 0, '.', ' ');
							$date0_TempMax = number_format($day->temp->max, 0, '.', ' ');					
						} else {
							$date0_TempMin = '';
							$date0_TempDay = '';
							$date0_TempMax = '';
						}
						
						$date0_Wind = isset($day->wind_speed) ? number_format(($day->wind_speed * $convert_wind), 0, '.', ' ') : '0';
						$date0_Clouds = isset($day->clouds) ? number_format($day->clouds, 0, '.', ' ') : '0';
						$date0_Humidity = isset($day->humidity) ? number_format($day->humidity, 0, '.', ' ') : '0';					
						$date0_Rain = isset($day->rain) ? number_format($day->rain, 0, '.', ' ') : '0';
						$date0_Snow = isset($day->snow) ? number_format($day->snow, 0, '.', ' ') : '0';
						break;
					} else {
						continue;
					}
				};
			};
			
			$index = 0;
			$date0_daily_index = 0;
			foreach( $data->daily as $day ) {
				if ( $mainDate == wp_date( "Ymd", $day->dt, $timezone ) ) {
					$date0_daily_index = $index;
					break;
				} else {
					$index++;
					continue;
				}
			};
			
			$background_image = "";
			if ( $thumb ) {
				$background_image = 'background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url(' . $thumb . ')';
			} else {
				//$background_image = 'background-image: linear-gradient(rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.9)), url(' .PATH_ANIMATED_ICONS_PUBLIC_PARTIALS .'WeatherForecastWidgetLogo.png' . ')';
				$background_image = 'background-image: linear-gradient(rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.9)), url( )';
			}			
			
			$html_output = 
			'<!DOCTYPE html>
			<html lang="en">
			<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			</head>
			<section class="w-100">
			  <div id="wfw_widget" class="container py-3 w-100 h-50" style="max-width:' .$max_width .';">
				<div class="row d-flex justify-content-center align-items-center h-100">'
				  //<div class="col-sm-12 col-md-9 col-lg-7 col-xl-5">
				  .'<div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">

					<div id="wfw_t2_content" class="card text-white bg-image shadow-4-strong"
					  style="border-radius: 25px; background: linear-gradient(180deg, rgba(2,0,36,1) 0%, rgba(33,71,83,1) 60%, rgba(0,212,255,1) 100%);background-size: cover;background-position: center;' . $background_image .';">
					  
					  <!-- CURRENT DATA -->
					  <div id="wfw_t2_curr_date" class="card-header border-bottom">
						  <div id="row" class="row align-items-center">
							<div class="col-2 text-begin">
							</div>
							<div class="col-8 text-center">
							  <p class="mb-1" id="date"><strong>' .$date0_Formatted .'</strong></p>
							</div>
							<div class="col-2 text-end text-white">
							  <a data-bs-toggle="collapse" href="#collapseLastUpdate_' .$uniqid .'" role="button" aria-expanded="false" aria-controls="collapseLastUpdate">
								<i class="fas fa-question-circle" style="color:white;"></i>
							  </a>
							</div>
						  </div>
						  <div id="row" class="row align-items-center text-center">
							<div class="collapse" id="collapseLastUpdate_' .$uniqid .'">
							  <p class="text-light">' .$lastUpdated .'</p>
							</div>	
						  </div>
					  </div>
					  <div id="wfw_t2_curr_header" class="card-header border-0">
						<div class="text-center mb-2">
						  <p class="h4 mb-2" id="title">' . $title . '</p>
						  <span id="weather_icon">' .$date0_Weather_icon .'</span>
						  <p class="mb-2" id="description"><strong>' .$date0_Description . '</strong></p>
							<p class="mb-0">
								<span class="sunrise">' . $this->wfw_get_icon( "sunrise", false ) .'  <strong>' .$date0_Sunrise .'</strong></span>
								<span class="mx-1">|</span>
								<span class="sunset">' . $this->wfw_get_icon( "sunset", false ) .'  <strong>' .$date0_Sunset .'</strong></span>
								<br>';
								if ( in_array( 'temp', $weather_values) ) {
								$html_output = $html_output
								.'<span class="temp">' .$this->wfw_get_icon( "thermometer", false ) .' ' .__( 'Temp', 'weather-forecast-widget' ) .': <span>' .$date0_TempMin .' / <strong>'
																																							.$date0_TempDay .'</strong> / '
																																							.$date0_TempMax
																																							. '<strong> ' .$widget_options['temp'] .'</strong></span></span>
								';
								};
								if ( in_array( 'wind', $weather_values) ) {
								$html_output = $html_output
								.'
								<span class="mx-1">|</span>
								<span class="wind">' .$this->wfw_get_icon( "windy", false ) .' '. __( 'Wind', 'weather-forecast-widget' ) .': <span><strong>' .$date0_Wind .' ' .$widget_options['wind'] .'</strong></span></span>
								';
								};
								if ( in_array( 'clouds', $weather_values) ) {
								$html_output = $html_output
								.'
								<span class="mx-1">|</span>
								<span class="clouds">' .$this->wfw_get_icon( "cloud", false ) .' '.__( 'Clouds', 'weather-forecast-widget' ) .': <span><strong>' .$date0_Clouds .' &percnt;</strong></span></span>
								';
								};
								if ( in_array( 'humidity', $weather_values) ) {
								$html_output = $html_output
								.'
								<span class="mx-1">|</span>
								<span class="humidity">' .$this->wfw_get_icon( "humidity", false ) .' '.__( 'Humidity', 'weather-forecast-widget' ) .': <span><strong>' .$date0_Humidity .' &percnt;</strong></span></span>
								';
								};
								if ( in_array( 'rain', $weather_values) ) {
								$html_output = $html_output
								.'
								<span class="mx-1">|</span>
								<span class="rain">' .$this->wfw_get_icon( "raindrop", false ) .' '.__( 'Rain', 'weather-forecast-widget' ) .': <span><strong>' .$date0_Rain .' mm</strong></span></span>
								';
								};
								if ( in_array( 'snow', $weather_values) ) {
								$html_output = $html_output
								.'
								<span class="mx-1">|</span>
								<span class="snow">' .$this->wfw_get_icon( "snowflake-cold", false ) .' '.__( 'Snow', 'weather-forecast-widget' ) .': <span><strong>' .$date0_Snow .' mm</strong></span></span>
								';
								};
								$html_output = $html_output .'
							</p>
						</div>
					  </div>';

						if ($widget_options['show_hourly_forecast'] === 'X') {
							$html_output = $html_output 
							.'<!-- HOURLY FORECAST -->'
							.$this->wfw_hourly_forecast ( $uniqid, $currentDate, $currentHour, $date0_Weather_icon, $mainDate, $data, $widget_options );
						}

						if ($widget_options['show_daily_forecast'] === 'X') {
							$html_output = $html_output 
							.'<!-- DAILY FORECAST -->'
							.$this->wfw_daily_forecast( $uniqid, $date0_daily_index, $data, $widget_options );
						}
					
					$html_output = $html_output 
					.'</div>
				  </div>
				</div>';
				
				//Show alerts (only if alerty are available)
				if ($widget_options['show_alerts'] === 'X') {
					$html_output = $html_output 
					.'<!-- ALERTS -->'
					.$this->wfw_get_alerts( $uniqid, $data, $widget_options );
				}

			  $html_output = $html_output 
			  .'</div>
			</section>';	
		};
		
		return $html_output;
	}
	private function wfw_get_alerts ( $uniqid, $data, $widget_options ) {
		$timezone    = new DateTimeZone($data->timezone);
		
		if (!empty($data->alerts)) {
			$html = '<div id="wfw_t2_alerts" class="row d-flex text-center justify-content-center align-items-center mt-3">
						<div class="text-center">
							<a href="#collapseAlerts_' .$uniqid .'" class="text-white" id="collapseAlertsButton" data-bs-toggle="collapse" aria-expanded="true" aria-controls="collapseAlerts">
							  <button type="button" class="btn btn-outline-light bg-danger text-white mb-3"><i class="fas fa-exclamation-triangle"></i></i> ' .__( 'Weather Alerts', 'weather-forecast-widget' ) .' <i class="fas fa-exclamation-triangle"></i></button>
							</a>
						</div>
						<div id="collapseAlerts_' .$uniqid .'" class="collapse ' .$widget_options['alerts'] .'" aria-labelledby="headingAlerts" data-bs-parent="#collapseAlerts" style="max-height:35vh; overflow-y:auto;">
					';
		}
			
		if (!empty($data->alerts)) {
			foreach( $data->alerts as $alert ) {
				$fromDate = wp_date( "D, " . get_option('date_format') ." H:i",  $alert->start, $timezone);
				$toDate = wp_date( "D, " . get_option('date_format') ." H:i",  $alert->end, $timezone);
				
				$alert_raw = '<strong>' .$fromDate .' - ' .$toDate .'</strong>';
				$alert_raw = $alert_raw .'<br>' .$alert->description .' <br>(' .$alert->sender_name .')';
				$alert_html = '<small>' .$this->warning_msg( $alert_raw ) .'</small>';
				
					
				$html = $html .$alert_html;
			};
		};
		
		if (!empty($data->alerts)) {
			$html = $html 
			.'</div>
			</div>';
		}	
		
		return $html;
	}
	private function wfw_hourly_forecast ( $uniqid, $currentDate, $currentHour, $date0_Weather_icon, $mainDate, $data, $widget_options ) {
		$timezone    = new DateTimeZone($data->timezone);
		
		$weather_values = $widget_options['values'];
		if (!$weather_values) {
			$weather_values = array();
		}
		if ( $widget_options['unit'] === 'imperial' ) {
			$convert_wind = 1;
		} else {
			$convert_wind = CONVERT_FACTOR__M_S_KM_H;
		}
		
		$html =
		'
		<div id="wfw_t2_curr_hourly" class="card-body p-3 border-top mb-2">
			<div class="text-center">
				<a href="#collapseHourlyForecast_' .$uniqid .'" class="text-white" id="collapseHourlyForecastButton" data-bs-toggle="collapse" aria-expanded="true" aria-controls="collapseHourlyForecast">
				  <button type="button" class="btn btn-outline-light"><i class="fas fa-chevron-circle-down"></i> ' .__( 'Hourly Forecast', 'weather-forecast-widget' ) .' <i class="fas fa-chevron-circle-down"></i></button>
				</a>
			</div>
			<div id="collapseHourlyForecast_' .$uniqid .'" class="collapse ' .$widget_options['hourly_forecast'] .' mt-2" aria-labelledby="headingHourlyForecast" data-bs-parent="#collapseHourlyForecast">
			<div id="columns" class="container-fluid" style="overflow: auto; overflow-x: auto; overflow-y: hidden;">
				<div class="row flex-nowrap align-items-center" style="white-space: nowrap;">
						
					<div id="column" class="col-2 text-left d-none d-sm-block">
						<span id="hour" class="d-block mb-0" style="opacity: 0;">' .$currentHour .'</span>
						<span id="weather_icon" class="d-block p-1" style="opacity: 0;">' .$date0_Weather_icon .'</span>';
						if ( in_array( 'temp', $weather_values) ) {
						$html = $html .'<span id="temp" class="d-block"><small>' .__( 'Temp', 'weather-forecast-widget' ) .' </small></span>'; };
						if ( in_array( 'wind', $weather_values) ) {
						$html = $html .'<span id="wind" class="d-block"><small>' . __( 'Wind', 'weather-forecast-widget' ) . ' </small></span>'; };
						if ( in_array( 'clouds', $weather_values) ) {
						$html = $html .'<span id="clouds" class="d-block"><small>' .__( 'Clouds', 'weather-forecast-widget' ) .' </small></span>'; };
						if ( in_array( 'humidity', $weather_values) ) {
						$html = $html .'<span id="humidity" class="d-block"><small>' .__( 'Humidity', 'weather-forecast-widget' ) .' </small></span>'; };
						if ( in_array( 'rain', $weather_values) ) {
						$html = $html .'<span id="rain" class="d-block"><small>' .__( 'Rain', 'weather-forecast-widget' ) .' </small></span>'; };
						if ( in_array( 'snow', $weather_values) ) {
						$html = $html .'<span id="snow" class="d-block"><small>' .__( 'Snow', 'weather-forecast-widget' ) .' </small></span>'; };
					$html = $html .'
					</div>
					<div id="column" class="col-3 text-left d-sm-none">
						<span id="hour" class="d-block mb-0" style="opacity: 0;">' .$currentHour .'</span>
						<span id="weather_icon" class="d-block p-1" style="opacity: 0;">' .$date0_Weather_icon .'</span>';
						if ( in_array( 'temp', $weather_values) ) {
						$html = $html .'<span id="temp" class="d-block"><small>' .__( 'Temp', 'weather-forecast-widget' ) .' </small></span>'; };
						if ( in_array( 'wind', $weather_values) ) {
						$html = $html .'<span id="wind" class="d-block"><small>' . __( 'Wind', 'weather-forecast-widget' ) . ' </small></span>'; };
						if ( in_array( 'clouds', $weather_values) ) {
						$html = $html .'<span id="clouds" class="d-block"><small>' .__( 'Clouds', 'weather-forecast-widget' ) .' </small></span>'; };
						if ( in_array( 'humidity', $weather_values) ) {
						$html = $html .'<span id="humidity" class="d-block"><small>' .__( 'Humidity', 'weather-forecast-widget' ) .' </small></span>'; };
						if ( in_array( 'rain', $weather_values) ) {
						$html = $html .'<span id="rain" class="d-block"><small>' .__( 'Rain', 'weather-forecast-widget' ) .' </small></span>'; };
						if ( in_array( 'snow', $weather_values) ) {
						$html = $html .'<span id="snow" class="d-block"><small>' .__( 'Snow', 'weather-forecast-widget' ) .' </small></span>'; };
					$html = $html .'
					</div>';						
		
				//Problem with overflow of hourly forecast
				$count = '';
				$count_print = '';
				$mobile_print = '';
				foreach( $data->hourly as $hourly ) {						
					if ( $mainDate === wp_date( "Ymd", $hourly->dt, $timezone ) ) {
						$count++;
					} else {
						continue;
					}
				}
				if ($count > '0'){ $count = $count - 1; }
				if ( $count > '5' ){
					$count_print = round( $count / 3, 0, PHP_ROUND_HALF_ODD );
					$mobile_print_1 = $count_print;
					$mobile_print_2 = $mobile_print_1 + $count_print;
					$mobile_print_3 = $mobile_print_2 + $count_print;
				} else {
					$mobile_print_1 = $mobile_print_2 = $mobile_print_3 = '1';
				}
		
				$index = '';
				$count = '';
				$html_hours = '';
				foreach( $data->hourly as $hourly ) {						
					if ( $mainDate == wp_date( "Ymd", $hourly->dt, $timezone ) ) {
						if ( $mainDate === $currentDate && $currentHour === wp_date( "g", $hourly->dt, $timezone ) ){
							$class_add = "border rounded-circle current_hour";
						} else {
							$class_add = "";
						}
						$index = '0';
					} else {
						$class_add = "";
						
						if ( $index == '' ){
							continue;
						} else {
							if ($index >= '0' && $index <= '7') {
								$index++;
							} else {
								break;
							}
						}
					}
					
					$hourlyRain = isset($hourly->rain->{'1h'}) ? number_format($hourly->rain->{'1h'}, 0, '.', ' ') : '0';
					$hourlySnow = isset($hourly->snow->{'1h'}) ? number_format($hourly->snow->{'1h'}, 0, '.', ' ') : '0';
						
					$html_hours = $html_hours
					.'<div id="column" class="col-2 text-center d-none d-sm-block">
						<span id="hour" class="d-block mb-0 ' .$class_add .'">' .wp_date( "H", $hourly->dt, $timezone) .'</span>
						<span id="weather_icon" class="d-block p-1">' .$this->wfw_get_weather_icon( $hourly->weather[0], false ) .'</span>';
						if ( in_array( 'temp', $weather_values) ) {
						$html_hours = $html_hours .'<span id="temp" class="d-block"><strong>' . number_format($hourly->temp, 0, '.', ' ') . '&deg;</strong></span>'; };
						if ( in_array( 'wind', $weather_values) ) {
						$html_hours = $html_hours .'<span id="wind" class="d-block"><strong>' . number_format(($hourly->wind_speed * $convert_wind), 0, '.', ' ') . '</strong></span>'; };
						if ( in_array( 'clouds', $weather_values) ) {
						$html_hours = $html_hours .'<span id="clouds" class="d-block"><strong>' . number_format($hourly->clouds, 0, '.', ' ') . '</strong></span>'; };
						if ( in_array( 'humidity', $weather_values) ) {
						$html_hours = $html_hours .'<span id="humidity" class="d-block"><strong>' . number_format($hourly->humidity, 0, '.', ' ') . '</strong></span>'; };
						if ( in_array( 'rain', $weather_values) ) {
						$html_hours = $html_hours .'<span id="rain" class="d-block"><strong>' .$hourlyRain . '</strong></span>'; };
						if ( in_array( 'snow', $weather_values) ) {
						$html_hours = $html_hours .'<span id="snow" class="d-block"><strong>' .$hourlySnow . '</strong></span>'; };
					$html_hours = $html_hours .'
					</div>';
					
					if ( $index == '0' ) {	
						$count++;
						
						if ($count == $mobile_print_1 || $count == $mobile_print_2 || $count == $mobile_print_3){
						//ANDRUCKEN			
							$html_hours = $html_hours
							.'<div id="column" class="col-3 text-center d-sm-none">
								<span id="hour" class="d-block mb-0 ' .$class_add .'">' .wp_date( "H", $hourly->dt, $timezone) .'</span>
								<span id="weather_icon" class="d-block p-1">' .$this->wfw_get_weather_icon( $hourly->weather[0], false ) .'</span>';
								if ( in_array( 'temp', $weather_values) ) {
								$html_hours = $html_hours .'<span id="temp" class="d-block"><strong>' . number_format($hourly->temp, 0, '.', ' ') . '&deg;</strong></span>'; };
								if ( in_array( 'wind', $weather_values) ) {
								$html_hours = $html_hours .'<span id="wind" class="d-block"><strong>' . number_format(($hourly->wind_speed * $convert_wind), 0, '.', ' ') . '</strong></span>'; };
								if ( in_array( 'clouds', $weather_values) ) {
								$html_hours = $html_hours .'<span id="clouds" class="d-block"><strong>' . number_format($hourly->clouds, 0, '.', ' ') . '</strong></span>'; };
								if ( in_array( 'humidity', $weather_values) ) {
								$html_hours = $html_hours .'<span id="humidity" class="d-block"><strong>' . number_format($hourly->humidity, 0, '.', ' ') . '</strong></span>'; };
								if ( in_array( 'rain', $weather_values) ) {
								$html_hours = $html_hours .'<span id="rain" class="d-block"><strong>' .$hourlyRain . '</strong></span>'; };
								if ( in_array( 'snow', $weather_values) ) {
								$html_hours = $html_hours .'<span id="snow" class="d-block"><strong>' .$hourlySnow . '</strong></span>'; };
							$html_hours = $html_hours .'
							</div>';
						}
					}
				};
				
				if (!$html_hours) {
					return '';
				} else {
					$html = $html .$html_hours;
				}
				
				$html = $html
				.'</div>
			</div>
			</div>
		</div>
		';
		
		return $html;
	}
	private function wfw_daily_forecast ( $uniqid, $date0_daily_index, $data, $widget_options ) {
		$timezone    = new DateTimeZone($data->timezone);
	
		$html = '<div id="wfw_t2_fc_daily" class="card-body border-top px-3">';
		$html = $html .'
		<div class="text-center">
			<a href="#collapseDailyForecast_' .$uniqid .'" class="text-white" id="collapseDailyForecastButton" data-bs-toggle="collapse" aria-expanded="true" aria-controls="collapseDailyForecast">
			  <button type="button" class="btn btn-outline-light"><i class="fas fa-chevron-circle-down"></i> ' .__( 'Daily Forecast', 'weather-forecast-widget' ) .' <i class="fas fa-chevron-circle-down"></i></button>
			</a>
		</div>
		<div id="collapseDailyForecast_' .$uniqid .'" class="collapse ' .$widget_options['daily_forecast'] .' mt-2" aria-labelledby="headingDailyForecast" data-bs-parent="#collapseDailyForecast">';
		
		$html_forecast = '';
		if ($date0_daily_index+1 <= 7){
			$html_forecast = $html_forecast
			.'<div id="row" class="row align-items-center mb-1">
			  <div class="col-5 text-start d-none d-sm-block">
				<strong>' .wp_date( "l", $data->daily[$date0_daily_index+1]->dt, $timezone) .'</strong>
			  </div>
			  <div class="col-2 text-center d-none d-sm-block">
				<div id="weather_icon">' .$this->wfw_get_weather_icon( $data->daily[$date0_daily_index+1]->weather[0], false ) .'</div>
			  </div>
			  <div class="col-5 text-end d-none d-sm-block">
				<span id="in_1_day">' . number_format($data->daily[$date0_daily_index+1]->temp->min, 0, '.', ' ') .' / <strong>'
									  . number_format($data->daily[$date0_daily_index+1]->temp->day, 0, '.', ' ') .'</strong> / '
									  . number_format($data->daily[$date0_daily_index+1]->temp->max, 0, '.', ' ') .'</span> <strong>' .$widget_options['temp'] .'</strong>
			  </div>
			  
			  <div class="col-4 text-start d-sm-none">
				<strong>' .wp_date( "l", $data->daily[$date0_daily_index+1]->dt, $timezone) .'</strong>
			  </div>
			  <div class="col-4 text-center d-sm-none">
				<div id="weather_icon">' .$this->wfw_get_weather_icon( $data->daily[$date0_daily_index+1]->weather[0], false ) .'</div>
			  </div>
			  <div class="col-4 text-end d-sm-none">
				<span id="in_1_day"><strong>' . number_format($data->daily[$date0_daily_index+1]->temp->day, 0, '.', ' ') .' ' .$widget_options['temp'] .'</strong></span>
			  </div>
			</div>';
		};
		
		if ($date0_daily_index+2 <= 7){
			$html_forecast = $html_forecast 
			.'<div id="row" class="row align-items-center">
			  <div class="col-5 text-start d-none d-sm-block">
				<strong>' .wp_date( "l", $data->daily[$date0_daily_index+2]->dt, $timezone) .'</strong>
			  </div>
			  <div class="col-2 text-center d-none d-sm-block">
				<div id="weather_icon">' .$this->wfw_get_weather_icon( $data->daily[$date0_daily_index+2]->weather[0], false ) .'</div>
			  </div>
			  <div class="col-5 text-end d-none d-sm-block">
				<span id="in_2_days">' . number_format($data->daily[$date0_daily_index+2]->temp->min, 0, '.', ' ') .' / <strong>'
									   . number_format($data->daily[$date0_daily_index+2]->temp->day, 0, '.', ' ') .'</strong> / '
									   . number_format($data->daily[$date0_daily_index+2]->temp->max, 0, '.', ' ') .'</span> <strong>' .$widget_options['temp'] .'</strong>
			  </div>
			  
			  <div class="col-4 text-start d-sm-none">
				<strong>' .wp_date( "l", $data->daily[$date0_daily_index+2]->dt, $timezone) .'</strong>
			  </div>
			  <div class="col-4 text-center d-sm-none">
				<div id="weather_icon">' .$this->wfw_get_weather_icon( $data->daily[$date0_daily_index+2]->weather[0], false ) .'</div>
			  </div>
			  <div class="col-4 text-end d-sm-none">
				<span id="in_2_days"><strong>' . number_format($data->daily[$date0_daily_index+2]->temp->day, 0, '.', ' ') .' ' .$widget_options['temp'] .'</strong></span>
			  </div>
			</div>';
		};
			
		if ($date0_daily_index+3 <= 7){
			$html_forecast = $html_forecast
			.'<div id="row" class="row align-items-center">
			  <div class="col-5 text-start d-none d-sm-block">
				<strong>' .wp_date( "l", $data->daily[$date0_daily_index+3]->dt, $timezone) .'</strong>
			  </div>

			  <div class="col-2 text-center d-none d-sm-block">
				<div id="weather_icon">' .$this->wfw_get_weather_icon( $data->daily[$date0_daily_index+3]->weather[0], false ) .'</div>
			  </div>
			  <div class="col-5 text-end d-none d-sm-block">
				<span id="in_3_days">' . number_format($data->daily[$date0_daily_index+3]->temp->min, 0, '.', ' ') .' / <strong>'
									   . number_format($data->daily[$date0_daily_index+3]->temp->day, 0, '.', ' ') .'</strong> / '
									   . number_format($data->daily[$date0_daily_index+3]->temp->max, 0, '.', ' ') .'</span> <strong>' .$widget_options['temp'] .'</strong>
			  </div>
			  
			  <div class="col-4 text-start d-sm-none">
				<strong>' .wp_date( "l", $data->daily[$date0_daily_index+3]->dt, $timezone) .'</strong>
			  </div>
			  <div class="col-4 text-center d-sm-none">
				<div id="weather_icon">' .$this->wfw_get_weather_icon( $data->daily[$date0_daily_index+3]->weather[0], false ) .'</div>
			  </div>
			  <div class="col-4 text-end d-sm-none">
				<span id="in_3_days"><strong>' . number_format($data->daily[$date0_daily_index+3]->temp->day, 0, '.', ' ') .' ' .$widget_options['temp'] .'</strong></span>
			  </div>
			</div>';
		};
		
		if (!$html_forecast) {
			return '';
		} else {
			$html = $html .$html_forecast;
		}
		
		$html = $html .'
		</div>
		</div>';
		
		return $html;
	}
	
	private function wfw_show_template3( $uniqid, $thumb, $title, $max_width, $data, $widget_options ) {
		global $post;
		
		if ($data) {
			//DATE CONVERSION
			$timezone    = new DateTimeZone($data->timezone);
			$currentTimestamp = current_time( 'timestamp', 0 );
			$currentDate = wp_date( "Ymd", $currentTimestamp, $timezone);
			
			$html_output =
			'
			<!--Carousel Wrapper-->
			<div id="wfw_t3_carousel_' .$uniqid .'" class="carousel slide carousel-fade" data-bs-interval="false" data-bs-ride="carousel" data-bs-wrap="false">

			  <!--Controls-->
			  <div class="controls-top text-center">';
				$html_output = $html_output 
				.'<a class="btn btn-outline-secondary" role="button" title="' .__( 'Previous Day', 'weather-forecast-widget' ) .'" data-bs-target="#wfw_t3_carousel_' .$uniqid .'" data-bs-slide="prev"><i class="fas fa-arrow-circle-left"></i>  ' .__( 'Previous Day', 'weather-forecast-widget' ) .'</a>
				  <a class="btn btn-outline-secondary" role="button" title="' .__( 'Next Day', 'weather-forecast-widget' ) .'" data-bs-target="#wfw_t3_carousel_' .$uniqid .'" data-bs-slide="next">' .__( 'Next Day', 'weather-forecast-widget' ) .' <i class="fas fa-arrow-circle-right"></i></a>
			  </div>
			  <!--/.Controls-->

			  <!--Indicators-->
			  <!--/.Indicators-->

			  <!--Slides-->
			  <div id="wfw_t3_carousel_inner" class="carousel-inner">

				<!--Current Day (= First Slide)-->
				<div id="wfw_t3_carousel_slide" class="carousel-item active">'
				.$this->wfw_show_template2( $uniqid, $thumb, $title, $max_width, $data, $widget_options, $currentDate ) .'
				</div>
				<!--/.Current Day-->

				<!--Next Days (= Next Slides)-->
				<div id="wfw_t3_carousel_slide" class="carousel-item">' .$this->wfw_show_template2( $uniqid, $thumb, $title, $max_width, $data, $widget_options, wp_date( "Ymd", strtotime('+1 day', $currentTimestamp), $timezone) ) .'</div>
				<div id="wfw_t3_carousel_slide" class="carousel-item">' .$this->wfw_show_template2( $uniqid, $thumb, $title, $max_width, $data, $widget_options, wp_date( "Ymd", strtotime('+2 day', $currentTimestamp), $timezone) ) .'</div>
				<div id="wfw_t3_carousel_slide" class="carousel-item">' .$this->wfw_show_template2( $uniqid, $thumb, $title, $max_width, $data, $widget_options, wp_date( "Ymd", strtotime('+3 day', $currentTimestamp), $timezone) ) .'</div>
				<div id="wfw_t3_carousel_slide" class="carousel-item">' .$this->wfw_show_template2( $uniqid, $thumb, $title, $max_width, $data, $widget_options, wp_date( "Ymd", strtotime('+4 day', $currentTimestamp), $timezone) ) .'</div>
				<div id="wfw_t3_carousel_slide" class="carousel-item">' .$this->wfw_show_template2( $uniqid, $thumb, $title, $max_width, $data, $widget_options, wp_date( "Ymd", strtotime('+5 day', $currentTimestamp), $timezone) ) .'</div>
				<div id="wfw_t3_carousel_slide" class="carousel-item">' .$this->wfw_show_template2( $uniqid, $thumb, $title, $max_width, $data, $widget_options, wp_date( "Ymd", strtotime('+6 day', $currentTimestamp), $timezone) ) .'</div>
				<div id="wfw_t3_carousel_slide" class="carousel-item">' .$this->wfw_show_template2( $uniqid, $thumb, $title, $max_width, $data, $widget_options, wp_date( "Ymd", strtotime('+7 day', $currentTimestamp), $timezone) ) .'</div>
				<!--/.Next Days-->

			  </div>
			  <!--/.Slides-->

			</div>
			<!--/.Carousel Wrapper-->
			';
		};
		
		return $html_output;
	}
	
	private function wfw_show_alert1( $uniqid, $widget_title, $max_width, $data, $widget_options ) {
		$timezone    = new DateTimeZone($data->timezone);
		
		if (isset($data->alerts) && !empty($data->alerts)) {
			$alert_count = count($data->alerts);
			$alert_btn = 'btn-danger';
			$alert_icon = 'fas fa-exclamation-triangle';
		} else {
			$alert_count = 0;
			$alert_btn = 'btn-success';
			$alert_icon = 'fas fa-info-circle';
		}
			
		$html = '<div id="wfw_widget" class="container" style="max-width:' . $max_width . ';">
					<div id="wfw_a1_alerts" class="row d-flex /*text-center justify-content-center align-items-center*/ mt-3">
						<div class="text-center">
							<a href="#collapseAlerts_' .$uniqid .'" class="/*text-white*/" id="collapseAlertsButton" data-bs-toggle="collapse" aria-expanded="true" aria-controls="collapseAlerts">
							  <button type="button" class="btn ' .$alert_btn .' text-white mb-3">
							  <p style="margin-bottom: 0.5em !important;">' .$widget_title .'</p>
							  <i class="' .$alert_icon .'"></i>   <strong>' .$alert_count .' ' .__( 'active weather alerts', 'weather-forecast-widget' ) .'</strong>   <i class="' .$alert_icon .'"></i>
							  </button>
							</a>
						</div>
						<div id="collapseAlerts_' .$uniqid .'" class="collapse ' .$widget_options['alerts'] .'" aria-labelledby="headingAlerts" data-bs-parent="#collapseAlerts" style="max-height:50vh; overflow-y:auto;">
				';
			
		if (!empty($data->alerts)) {
			foreach( $data->alerts as $alert ) {
				$fromDate = wp_date( "D, " . get_option('date_format') ." H:i",  $alert->start, $timezone);
				$toDate = wp_date( "D, " . get_option('date_format') ." H:i",  $alert->end, $timezone);
				
				$alert_time = '<strong>' .$fromDate .' - ' .$toDate .'</strong>';
				$alert_company = $alert->sender_name;
				$alert_event = '<strong>' .$alert->event .'</strong>';
				if (isset($alert->tags) && !empty($alert->tags)) {
					$alert_tags = implode(", ", $alert->tags);
				} else {
					$alert_tags = '';
				}
				$alert_message = $alert->description;
				$alert_html = '<div class="d-flex flex-column">
									<div class="alert bg-danger bg-gradient bg-opacity-85 text-white border alert-dismissible fade show d-flex flex-column" role="alert">'
										.'<div class="row">
											<div class="col-1"><i class="fas fa-address-card"></i></div>
											<div class="col-11"><small>' .$alert_company .'</small></div>
										  </div>';
										
										if ($alert_event) { $alert_html = $alert_html
										.'<div class="row">
											<div class="col-1"><i class="fas fa-tag"></i></div>
											<div class="col-11"><small>' .$alert_event .'</small></div>
										</div>'; }
										
										$alert_html = $alert_html
										.'<div class="row">
											<div class="col-1"><i class="far fa-clock"></i></div>
											<div class="col-11">' .$alert_time .'</div>
										  </div>';
										
										$alert_html = $alert_html
										.'<hr class="dashed">';
										
										if ($alert_tags) { $alert_html = $alert_html
										.'<div class="row">
											<div class="col-1"><i class="fas fa-hashtag"></i></div>
											<div class="col-11"><small>' .$alert_tags .'</small></div>
										  </div>';
										}
										
										$alert_html = $alert_html
										.'<div class="row">
											<div class="col-1"><i class="far fa-comment-dots"></i></div>
											<div class="col-11">' .$alert_message .'</div>
										  </div>'
										.'<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
									</div>
								</div>';
					
				$html = $html .$alert_html;
			};
		};
		
		if (!empty($html)) {
			$html = $html 
			.'</div>
			</div>
			</div>';
		}	
		
		return $html;
	}
}
