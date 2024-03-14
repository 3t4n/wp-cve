<?php

class Advanced_Settings {

    public static $key = 'emma_advanced_settings';

    public static $settings = array();

    function __construct() {

        add_action( 'admin_init', array( &$this, 'register_settings' ) );

        self::$settings = $this->get_settings_options();
    }

    public static function get_settings_options() {

        // load the settings from the database
        $settings_options = (array) get_option( self::$key );

        // merge with defaults
        $settings_options = array_merge( self::get_settings_defaults(), $settings_options );

        return $settings_options;

    }

    public static function get_settings_defaults() {
        $defaults = array(
            'successTrackingPixel' => '',
        );
        return $defaults;
    }

    function register_settings() {
        register_setting( self::$key, self::$key, array( &$this, 'sanitize_advanced_settings' ) );

        add_settings_section( 'section_adv_settings', 'Emma Advanced Settings', array( &$this, 'section_adv_desc' ), self::$key );
        add_settings_field( 'successTrackingPixel', 'Tracking Pixel', array( &$this, 'field_success_tracking_pixel' ), self::$key, 'section_adv_settings' );
        
        add_settings_section( 'section_adv_settings_recaptcha', 'reCAPTCHA', array( &$this, 'section_recaptcha_desc'), self::$key );
        add_settings_field( 'useRecaptcha', 'Use reCAPTCHA', array( &$this, 'field_use_recaptcha' ), self::$key, 'section_adv_settings_recaptcha' );
        add_settings_field( 'recaptchaSiteKey', 'Site Key', array( &$this, 'field_recaptcha_site_key' ), self::$key, 'section_adv_settings_recaptcha' );
        add_settings_field( 'recaptchaSecretKey', 'Secret Key', array( &$this, 'field_recaptcha_secret_key' ), self::$key, 'section_adv_settings_recaptcha' );
    }
    
    function section_adv_desc() { 
		echo '<p><strong>For advanced users only!</strong><br />Any code inserted in this area will be applied upon a successful form submission. This area is commonly can used for tracking pixels for Facebook or tracking for Google Analytics for pay per click campaigns.</p>';
	}
    function field_success_tracking_pixel() { 
	    $successTrackingPixel = '';
	    if ( isset(self::$settings['successTrackingPixel']) ) {
		    $successTrackingPixel = esc_attr( self::$settings['successTrackingPixel'] );
	    }
    ?>
        <textarea id="emma_success_tracking_pixel" style="width:400px;max-width: 100%;" rows="10" name="<?php echo self::$key; ?>[successTrackingPixel]"><?php echo $successTrackingPixel; ?></textarea>
    <?php }
	
	function section_recaptcha_desc() {
		
		$opt_val = '';
		if ( isset( self::$settings['useRecaptcha'] ) && '1' == self::$settings['useRecaptcha'] ) {
			$opt_val = 'show-recaptcha-settings';
		}
		
		echo '<p style="text-align: left;">Set up your reCAPTCHA settings, is a free service that protects your site from spam and abuse. It uses advanced risk analysis techniques to tell humans and bots apart.</p><p><strong>Please note</strong>, these settings are required only if you decide to use the reCAPTCHA field. <a href="http://www.google.com/recaptcha/" target="_blank">Sign up for reCAPTCHA</a>.</p>';
		echo '<span id="emma-check-recaptcha" class="' . $opt_val . '" style="display:none"></span>';
	}
	
	function field_use_recaptcha() {
		
		$opt_val = '';
		if ( isset(self::$settings['useRecaptcha']) ) {
			$opt_val = self::$settings['useRecaptcha'];
		}
		
		echo '<input type="checkbox" id="emma_use_recaptcha" name="' . self::$key . '[useRecaptcha]" value="1" ' . checked( '1', $opt_val, false ) . ' />';
		
	}
	
	function field_recaptcha_site_key() {
		
		$opt_val = '';
		if ( isset(self::$settings['recaptchaSiteKey']) ) {
			$opt_val = self::$settings['recaptchaSiteKey'];
		}
		
		echo '<input id="emma_recaptcha_site_key" name="' . self::$key . '[recaptchaSiteKey]" value="' . $opt_val . '" placeholder="reCAPTCHA Site Key" style="width:300px;max-width:100%" />';
		
	}
	
	function field_recaptcha_secret_key() {
		
		$opt_val = '';
		if ( isset(self::$settings['recaptchaSecretKey']) ) {
			$opt_val = self::$settings['recaptchaSecretKey'];
		}
		
		echo '<input id="emma_recaptcha_secret_key" name="' . self::$key . '[recaptchaSecretKey]" value="' . $opt_val . '" placeholder="reCAPTCHA Secret Key" style="width:300px;max-width:100%" />';
		
		// Maybe show example reCAPTCHA here
		if ( isset( self::$settings['recaptchaSiteKey'] ) && '' !== self::$settings['recaptchaSiteKey'] ) { ?>
			<div class="emma-settings-recaptcha">
				<p><b><i>reCAPTCHA Preview</i></b></h4>
				<script src='https://www.google.com/recaptcha/api.js'></script>
				<div class="g-recaptcha" data-sitekey="<?php echo self::$settings['recaptchaSiteKey']; ?>"></div>
			</div>
		<?php }
		
	}
	
    function sanitize_advanced_settings( $input ) {
		
        // get the current options
        // $valid_input = self::$settings;
        $valid_input = array();

        // check which button was clicked, submit or reset,
        $submit = ( ! empty( $input['submit'] ) ? true : false );
        $reset = ( ! empty( $input['reset'])  ? true : false );

        // if the submit or refresh button was clicked
        if ( $submit || $refresh ) {

            /**
             * validate advanced settings, and add error messages
             * add_settings_error( $setting, $code, $message, $type )
             * $setting here refers to the $id of add_settings_field
             * add_settings_field( $id, $title, $callback, $page, $section, $args );
             */
			
			// success tracking pixel
			$valid_input['successTrackingPixel'] = $input['successTrackingPixel'];
			
			// use ReCAPTCHA
			$valid_input['useRecaptcha'] = intval( $input['useRecaptcha'] );
			
			// reCAPTCHA Site Key
			$valid_input['recaptchaSiteKey'] = $input['recaptchaSiteKey'];
			
			// reCAPTCHA Secret Key
			$valid_input['recaptchaSecretKey'] = $input['recaptchaSecretKey'];

        } elseif ( $reset ) {

            // get defaults
            $default_input = $this->get_settings_defaults();
            // assign to valid input
            $valid_input = $default_input;

        }

        return $valid_input;

    } // end sanitize_advanced_settings

}
