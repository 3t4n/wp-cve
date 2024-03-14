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
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Weather_Forecast_Widget
 * @subpackage Weather_Forecast_Widget/admin
 * @author     Dominik Luger <admin@bergtourentipp-tirol.at>
 */

class Weather_Forecast_Widget_Admin {

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

    public $shortcode_atts;

    /**
     * @return array
     */
    public function getAtts() {
        return $this->shortcode_atts;
    }

    /**
     * @param $atts
     */
    public function setAtts( $atts ) {
        $this->shortcode_atts = $atts;
    }
	
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
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/weather-forecast-widget-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'weather-icons', WEATHER_FORECAST_WIDGET_BASE_URL . 'public/css/weather-icons/weather-icons.min.css', array (), $this->version, 'all' );		//needed for displaying static icons on settings screen
		
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/weather-forecast-widget-admin.js', array( 'jquery' ), $this->version, false );
		
		wp_enqueue_media();
    	wp_register_script('media-uploader', plugin_dir_url( __FILE__ ) . 'js/weather-forecast-widget-media-uploader.js', array( 'jquery' ), $this->version, false );
    	wp_enqueue_script('media-uploader');
		
	}


	/**
	 * Admin Seite erstellen
	 *
	 * @since    1.0.0
	 */
	public function wfw_admin_menu() {

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

		add_menu_page(
			__( 'Description', 'weather-forecast-widget' ),
			//'Description', // Title of the page
			'Weather Forecast Widget', // Text to show on the menu link
			'manage_options', // Capability requirement to see the link
			'wfw_menu_page', // The 'slug' - file to display when clicking the link
			array( $this, 'wfw_display_menu_page' )
		);
		add_submenu_page(
			"wfw_menu_page", 
			__( 'Shortcodes', 'weather-forecast-widget' ), 
			__( 'Shortcodes', 'weather-forecast-widget' ), 
			'manage_options', 
			"wfw_shortcodes_page", 
			array( $this, 'wfw_display_shortcodes_page' )
		);
		add_submenu_page(
			"wfw_menu_page", 
			__( 'Settings', 'weather-forecast-widget' ), 
			__( 'Settings', 'weather-forecast-widget' ), 
			'manage_options', 
			"wfw_settings_page", 
			array( $this, 'wfw_display_settings_page' )
		);
	}
	public function wfw_display_menu_page() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/weather-forecast-widget-admin-menu.php';
	}
	public function wfw_display_shortcodes_page() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/weather-forecast-widget-admin-menu-shortcodes.php';
	}
	public function wfw_display_settings_page() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/weather-forecast-widget-admin-menu-settings.php';
	}
	public function wfw_register_setting( $input ) {
		$new_input = array();

		if ( isset( $input ) ) {
			//Loop trough each input and sanitize the value if the input id isn't openweathermap-apikey + widget-backgroundimg
			foreach ( $input as $key => $value ) {
				if ( $key == 'openweathermap-apikey' || $key == 'widget-backgroundimg' || $key == 'weather-value-checkboxes' ) {
					$new_input[ $key ] = $value;
				} else {
					$new_input[ $key ] = sanitize_text_field( $value );
				}
			}
		}
		
		return $new_input;
	}
	public function wfw_add_settings_section() {
		return;
	}
	public function wfw_add_settings_field_input_text( $args ) {

		$field_id = $args['label_for'];
		$field_default = $args['default'];
		$field_placeholder = $args['placeholder'];

		$options = get_option( $this->plugin_name . '-settings' );
		$option = $field_default;

		if ( ! empty( $options[ $field_id ] ) ) {
			$option = $options[ $field_id ];
		}
		
		if ( $field_id == 'openweathermap-apikey' && esc_attr( $option ) ) {
			$apiResponse = $this->wfw_test_apikey();
			$http_status = wp_remote_retrieve_response_code( $apiResponse );
			$body = wp_remote_retrieve_body( $apiResponse );
			$test_apikey = json_decode($body);
			
			if ( $http_status === 200 ) {
				$msg = sprintf(__('<p style="color: green">API KEY <strong>%s</strong> is VALID!</p><br />', 'weather-forecast-widget' ), esc_attr( $option ) );
				echo $msg;
			} else {
				$msg = sprintf(__('<p style="color: red">API KEY <strong>%1$s</strong> is NOT VALID: %2$s | %3$s</p><br />', 'weather-forecast-widget' ), esc_attr( $option ), $test_apikey->cod, $test_apikey->message );
				echo $msg;
			}
		}	
		
		?>
			<input type="text" name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" id="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" placeholder="<?php echo esc_attr( $field_placeholder ); ?>" value="<?php echo esc_attr( $option ); ?>" class="regular-text" />
		<?php
	}	
	public function wfw_add_settings_media_uploader( $args ) {

		$field_id = $args['label_for'];
		$field_default = $args['default'];
		$field_placeholder = $args['placeholder'];

		$options = get_option( $this->plugin_name . '-settings' );
		$option = $field_default;

		if ( ! empty( $options[ $field_id ] ) ) {
			$option = $options[ $field_id ];
		}
		
		if ( $field_id == 'widget-backgroundimg' && esc_attr( $option ) ) {
			$image = '<img src="' . esc_attr( $option ) . '" alt="Chosen Image" width="auto" height="150"/><br /><br />';
			echo $image;
		}
		?>
			<p><?php echo __( 'If you <strong>choose an image from your media</strong> here, it will be used as the background image.', 'weather-forecast-widget' ) ?></p>
			<p><?php echo __( 'If you <strong>don´t choose an image from your media</strong> here, the thumbnail of the page or of the post will be used as the background image.', 'weather-forecast-widget' ) ?></p>
			<br />
			<input id="background_image" type="text" name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" placeholder="<?php echo esc_attr( $field_placeholder ); ?>" style="width: 50%;" value="<?php echo esc_attr( $option ); ?>" />
			<input id="upload_image_button" type="button" class="button-primary" value="<?php echo __( 'Select Background Image', 'weather-forecast-widget' ) ?>" />
		<?php
	}	
	public function wfw_add_settings_title_radiobuttons( $args ) {
		
		$field_id = $args['label_for'];
		$field_default = $args['default'];
		$field_placeholder = $args['placeholder'];

		$options = get_option( $this->plugin_name . '-settings' );
		$option = $field_default;

		if ( ! empty( $options[ $field_id ] ) ) {
			$option = $options[ $field_id ];
		}
		
		?>
		<p><?php echo __( 'This setting will be used as <strong>a default if nothing else will be passed in the shortcode</strong>. If you´ll pass <strong>title_cityname</strong> or <strong>title_overwrite</strong> as a shortcode attribute, the attributes will be processed in the widget output.', 'weather-forecast-widget' ) ?></p><br>
		<input type="radio" name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" value="post_title" <?php checked('post_title', esc_attr( $option )); ?> ><label for="post_title"><strong><?php echo __( 'Page/Post Title', 'weather-forecast-widget' ) ?></strong></label><br>
		<input type="radio" name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" value="post_meta" <?php checked('post_meta', esc_attr( $option )); ?> ><label for="post_meta"><?php echo __( '<strong>Post Meta Field</strong> (choose one of the dropdown below)', 'weather-forecast-widget' ) ?></label><br>
		<?php
	}
	public function wfw_add_settings_weather_units_radiobuttons( $args ) {
		
		$field_id = $args['label_for'];
		$field_default = $args['default'];
		$field_placeholder = $args['placeholder'];

		$options = get_option( $this->plugin_name . '-settings' );
		$option = $field_default;

		if ( ! empty( $options[ $field_id ] ) ) {
			$option = $options[ $field_id ];
		}
		
		?>
		<p><?php echo __( 'This setting will be used to read the weather data with the desired <strong>units</strong>. If no unit is selected here, "<strong>Metric"</strong> is used by default.', 'weather-forecast-widget' ) ?></p><br>
		<input type="radio" name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" value="metric" <?php checked('metric', esc_attr( $option )); ?> ><label for="metric"><?php echo __( '<strong>Metric</strong> (Temperature: celsius | Wind speed: kilometres/hour )', 'weather-forecast-widget' ) ?></label><br>
		<input type="radio" name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" value="imperial" <?php checked('imperial', esc_attr( $option )); ?> ><label for="imperial"><?php echo __( '<strong>Imperial</strong> (Temperature: fahrenheit | Wind speed: miles/hour)', 'weather-forecast-widget' ) ?></label><br>
		<?php
	}
	public function wfw_add_settings_icon_radiobuttons( $args ) {
		
		$field_id = $args['label_for'];
		$field_default = $args['default'];
		$field_placeholder = $args['placeholder'];

		$options = get_option( $this->plugin_name . '-settings' );
		$option = $field_default;

		if ( ! empty( $options[ $field_id ] ) ) {
			$option = $options[ $field_id ];
		}
		
		?>
		<p><?php echo __( 'This setting will be used as <strong>a default for displaying the icons</strong>. If no icon type is selected here, the "<strong>animated icons filled</strong>" will be displayed by default.', 'weather-forecast-widget' ) ?></p>
		<br />
		<input type="radio" name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" value="icon_animated_fill" <?php checked('icon_animated_fill', esc_attr( $option )); ?> ><label for="icon_animated_fill"><?php echo __( 'Animated Icons filled', 'weather-forecast-widget' ) ?></label>
		<br />
		<?php echo '<img src="' . PATH_ANIMATED_ICONS_FILLED . '01d.svg" alt="Icon Filled SVG" width="5%" height="auto">' ?>
		<?php echo '<img src="' . PATH_ANIMATED_ICONS_FILLED . '11d.svg" alt="Icon Filled SVG" width="5%" height="auto">' ?>
		<?php echo '<img src="' . PATH_ANIMATED_ICONS_FILLED_ALL . 'sunrise.svg" alt="Icon Filled SVG" width="5%" height="auto">' ?>
		<br />
		<br />
		<input type="radio" name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" value="icon_animated_line" <?php checked('icon_animated_line', esc_attr( $option )); ?> ><label for="icon_animated_line"><?php echo __( 'Animated Icons not filled', 'weather-forecast-widget' ) ?></label>
		<br />
		<?php echo '<img src="' . PATH_ANIMATED_ICONS_NOT_FILLED . '01d.svg" alt="Icon Filled SVG" width="5%" height="auto">' ?>
		<?php echo '<img src="' . PATH_ANIMATED_ICONS_NOT_FILLED . '11d.svg" alt="Icon Filled SVG" width="5%" height="auto">' ?>
		<?php echo '<img src="' . PATH_ANIMATED_ICONS_NOT_FILLED_ALL . 'sunrise.svg" alt="Icon Filled SVG" width="5%" height="auto">' ?>
		<br />
		<br />
		<input type="radio" name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" value="icon_static" <?php checked('icon_static', esc_attr( $option )); ?> ><label for="icon_static"><?php echo __( 'Static Icons', 'weather-forecast-widget' ) ?></label>
		<br />
		<br />
		<i class="wi wi-day-sunny" style="font-size: 50px; padding-left: 5px; padding-right: 25px;"></i><i class="wi wi-thunderstorm" style="font-size: 50px; padding-right: 25px;"></i><?php echo '    ' ?><i class="wi wi-sunrise" style="font-size: 50px"></i>
		<br />
		<?php
	}
	public function wfw_add_settings_post_meta_field( $args ) {
		
		$field_id = $args['label_for'];
		$field_default = $args['default'];
		$field_placeholder = $args['placeholder'];

		$options = get_option( $this->plugin_name . '-settings' );
		$option = $field_default;

		if ( ! empty( $options[ $field_id ] ) ) {
			$option = $options[ $field_id ];
		}
		
		if ( $field_id == 'widget-title-postmeta' ) {
			$option_title_radiobuttons = "";
			if (!isset($options['widget-title-radiobuttons'])) {
				if( is_array($options) ) {
					$option_title_radiobuttons = $options[ 'widget-title-radiobuttons' ];
				}
			}
			if ( $option_title_radiobuttons === 'post_meta' && empty(esc_attr( $option )) ) {
				$msg = __('<p style="color: red"><strong>Post Meta Field is blank but mandatory!</strong></p><br />', 'weather-forecast-widget');
				echo $msg;
			}
			if ( $option_title_radiobuttons === 'post_title' && !empty(esc_attr( $option )) ) {
				$msg = sprintf(__('<p style="color: orange">Post Meta Field is filled (<strong>%s</strong>) , but is not used because the Post Title is used!</p><br />', 'weather-forecast-widget' ), esc_attr( $option ) );
				echo $msg;
			}
		}
		
		?>
		<select name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>">
			<option value="0"></option>
		<?php foreach ( $this->meta_keys as $value => $label ) : ?>
			<option value="<?php echo esc_attr( $label ); ?>"<?php selected( esc_attr( $option ), $label); ?>><?php echo esc_html( $label ); ?></option>
		<?php endforeach; ?>
		</select>
		<?php
	}
	public function wfw_add_settings_cache_time_dropdown( $args ) {
		
		$field_id = $args['label_for'];
		$field_default = $args['default'];
		$field_placeholder = $args['placeholder'];

		$options = get_option( $this->plugin_name . '-settings' );
		$option = $field_default;

		if ( ! empty( $options[ $field_id ] ) ) {
			$option = $options[ $field_id ];
		}
		
		?>
		<select name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>">
			<option value="never"<?php selected( esc_attr( $option ), 'never'); ?>><?php echo __( 'Never cache', 'weather-forecast-widget' ) ?></option>
			<option value="5"<?php selected( esc_attr( $option ), 5); ?>><?php echo __( '5 Minutes', 'weather-forecast-widget' ) ?></option>
			<option value="10"<?php selected( esc_attr( $option ), 10); ?>><?php echo __( '10 Minutes', 'weather-forecast-widget' ) ?></option>
			<option value="15"<?php selected( esc_attr( $option ), 15); ?>><?php echo __( '15 Minutes', 'weather-forecast-widget' ) ?></option>
			<option value="20"<?php selected( esc_attr( $option ), 20); ?>><?php echo __( '20 Minutes', 'weather-forecast-widget' ) ?></option>
			<option value="25"<?php selected( esc_attr( $option ), 25); ?>><?php echo __( '25 Minutes', 'weather-forecast-widget' ) ?></option>
			<option value="30"<?php selected( esc_attr( $option ), 30); ?>><?php echo __( '30 Minutes', 'weather-forecast-widget' ) ?></option>
			<option value="35"<?php selected( esc_attr( $option ), 35); ?>><?php echo __( '35 Minutes', 'weather-forecast-widget' ) ?></option>
			<option value="40"<?php selected( esc_attr( $option ), 40); ?>><?php echo __( '40 Minutes', 'weather-forecast-widget' ) ?></option>
			<option value="45"<?php selected( esc_attr( $option ), 45); ?>><?php echo __( '45 Minutes', 'weather-forecast-widget' ) ?></option>
			<option value="50"<?php selected( esc_attr( $option ), 50); ?>><?php echo __( '50 Minutes', 'weather-forecast-widget' ) ?></option>
			<option value="55"<?php selected( esc_attr( $option ), 55); ?>><?php echo __( '55 Minutes', 'weather-forecast-widget' ) ?></option>
			<option value="60"<?php selected( esc_attr( $option ), 60); ?>><?php echo __( '60 Minutes', 'weather-forecast-widget' ) ?></option>
		</select>
		<?php
	}
	public function wfw_add_settings_weather_value_checkboxes( $args ) {
		
		$field_id = $args['label_for'];
		$setting = $this->plugin_name .'-settings';
		$options = get_option( $setting );
		
		$weather_values = isset( $options[ $field_id ] ) ? (array) $options[ $field_id ] : [];
		//var_dump($weather_values)
		
		$field_default = $args['default'];
		$option = $field_default;
		
		?>
		<p><?php echo __( 'This setting will be used to display the chosen <strong>weather values</strong> in the widget. If no weather value is selected here, no value will be displayed in the widget by default.', 'weather-forecast-widget' ) ?></p><br>
		 <!-- Here we are comparing stored value with 1. Stored value is 1 if user checks the checkbox otherwise empty string. -->
        <input type='checkbox' id="checkbox-temp" name='<?php echo $setting . '[' . $field_id . '][]'; ?>' <?php checked( in_array( 'temp', $weather_values ), 1); ?> value='temp'><label for="checkbox-temp"><?php echo __( 'Temperature', 'weather-forecast-widget' ) ?></label><br>
		<input type='checkbox' id="checkbox-wind" name='<?php echo $setting . '[' . $field_id . '][]'; ?>' <?php checked( in_array( 'wind', $weather_values ), 1); ?> value='wind'><label for="checkbox-wind"><?php echo __( 'Wind', 'weather-forecast-widget' ) ?></label><br>
        <input type='checkbox' id="checkbox-clouds" name='<?php echo $setting . '[' . $field_id . '][]'; ?>' <?php checked( in_array( 'clouds', $weather_values ), 1); ?> value='clouds'><label for="checkbox-clouds"><?php echo __( 'Clouds', 'weather-forecast-widget' ) ?></label><br>
		<input type='checkbox' id="checkbox-humidity" name='<?php echo $setting . '[' . $field_id . '][]'; ?>' <?php checked( in_array( 'humidity', $weather_values ), 1); ?> value='humidity'><label for="checkbox-humidity"><?php echo __( 'Humidity', 'weather-forecast-widget' ) ?></label><br>
        <input type='checkbox' id="checkbox-rain" name='<?php echo $setting . '[' . $field_id . '][]'; ?>' <?php checked( in_array( 'rain', $weather_values ), 1); ?> value='rain'><label for="checkbox-rain"><?php echo __( 'Rain', 'weather-forecast-widget' ) ?></label><br>
		<input type='checkbox' id="checkbox-snow" name='<?php echo $setting . '[' . $field_id . '][]'; ?>' <?php checked( in_array( 'snow', $weather_values ), 1); ?> value='snow'><label for="checkbox-snow"><?php echo __( 'Snow', 'weather-forecast-widget' ) ?></label><br>
		<?php
	}	
	
	public function wfw_register_settings() {
		// Here we are going to register our setting.
		register_setting(
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings',
			array( $this, 'wfw_register_setting' )
		);

		// Here we are going to add a section for our setting.
		add_settings_section(
			$this->plugin_name . '-settings-section',
			__( 'Settings', 'weather-forecast-widget' ),
			array( $this, 'wfw_add_settings_section' ),
			$this->plugin_name . '-settings'
		);

		add_settings_field(
			'openweathermap-apikey',
			__( 'Open Weather Map API Key', 'weather-forecast-widget' ),
			array( $this, 'wfw_add_settings_field_input_text' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => 'openweathermap-apikey',
				'default' => '',
				'placeholder' => __( 'Type in your API KEY', 'weather-forecast-widget' ),
			)
		);
		
		add_settings_field(
			'cache-time',
			__( 'Cache Time (in Minutes)', 'weather-forecast-widget' ),
			array( $this, 'wfw_add_settings_cache_time_dropdown' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => 'cache-time',
				'default' => '',
				'placeholder' => ''
			)
		);
		
		add_settings_field(
			'widget-backgroundimg',
			__( 'Widget Background Image', 'weather-forecast-widget' ),
			array( $this, 'wfw_add_settings_media_uploader' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => 'widget-backgroundimg',
				'default' => '',
				'placeholder' => __( 'Choose an image from your media library via button "Select Background Image"', 'weather-forecast-widget' ),
			)
		);
		
		add_settings_field(
			'widget-title-radiobuttons',
			__( 'Widget Title Text', 'weather-forecast-widget' ),
			array( $this, 'wfw_add_settings_title_radiobuttons' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => 'widget-title-radiobuttons',
				'default' => '',
				'placeholder' => ''
			)
		);
		
		add_settings_field(
			'widget-title-postmeta',
			__( 'Widget Title Post Meta Field', 'weather-forecast-widget' ),
			array( $this, 'wfw_add_settings_post_meta_field' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => 'widget-title-postmeta',
				'default' => '',
				'placeholder' => __( 'Choose a post meta field', 'weather-forecast-widget' ),
			)
		);
		
		add_settings_field(
			'widget-weather-value-checkboxes',
			__( 'Widget Weather Values', 'weather-forecast-widget' ),
			array( $this, 'wfw_add_settings_weather_value_checkboxes' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => 'weather-value-checkboxes',
				'default' => '',
				'placeholder' => '',
			)
		);		
		add_settings_field(
			'widget-weather-units-radiobuttons',
			__( 'Widget Weather Units', 'weather-forecast-widget' ),
			array( $this, 'wfw_add_settings_weather_units_radiobuttons' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => 'weather-units-radiobuttons',
				'default' => '',
				'placeholder' => ''
			)
		);
		
		add_settings_field(
			'widget-icon-radiobuttons',
			__( 'Widget Icons', 'weather-forecast-widget' ),
			array( $this, 'wfw_add_settings_icon_radiobuttons' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => 'widget-icon-radiobuttons',
				'default' => '',
				'placeholder' => ''
			)
		);
	}
	
	/**
	 * DB Abfragen
	 *
	 * @since    1.0.0
	 */
	public function wfw_load_db_selects() {
		global $wpdb;
		
		$query = "
        SELECT DISTINCT($wpdb->postmeta.meta_key) 
        FROM $wpdb->posts 
        LEFT JOIN $wpdb->postmeta 
        ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
        WHERE $wpdb->posts.post_type = '%s'
		  AND $wpdb->postmeta.meta_key != ''
		";
		
		$this->meta_keys = $wpdb->get_col($wpdb->prepare($query, 'post'));
	}
	
	
	 /**
	 * Test API KEY for settings page
	 *
	 * @since    1.0.0
	 */
	public function wfw_test_apikey() {
				
		$options = get_option( $this->plugin_name . '-settings' );
		if (!isset($options['openweathermap-apikey'])) {
			return __( 'No Open Weather Map API Key provided in settings!', 'weather-forecast-widget' );
		} else {
			$apikey = $options['openweathermap-apikey'];
		}

		$apiUrl = 'http://api.openweathermap.org/data/2.5/forecast?id=524901&appid=' . $apikey;

		$response = "";
		$response = wp_remote_get($apiUrl,
		array(
			'sslverify' => true,
			'headers' => array(
								'Accept' => 'application/json')
			)
		);

		return $response;
	}
}
