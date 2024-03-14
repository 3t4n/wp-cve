<?php

class Form_Setup {

    public static $key = 'emma_form_setup';

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
            'include_firstname_lastname' => 'both',
            'form_size' => '',
            'email_placeholder' => '', // 'Email address',
            'firstname_placeholder' => 'First name',
            'lastname_placeholder' => 'Last name',
            'submit_txt' => 'Subscribe',
            'confirmation_msg' => 'Thanks for subscribing! You should receive a confirmation email shortly.',
            'powered_by' => 'no',
            'send_confirmation_email' => '1',
            'confirmation_email_subject' => 'You&apos;re recent subscription to Email List',
            'confirmation_email_msg' => 'Thanks for subscribing! This email confirms that you now have an active subscription to our newsletter!',
            'email_validation_status_txt' => 'Please enter a valid email address.',
			'member_failed_status_txt' => 'We couldn\'t add your address. Please try again later.',
			'confirmation_email_success_status_txt' => 'A Confirmation email has been sent.',
			'confirmation_email_failed_status_txt' => 'A Confirmation email could not be sent to that address.'
        );
        return $defaults;
    }

    function register_settings() {

        register_setting( self::$key, self::$key, array( &$this, 'sanitize_form_setup_settings' ) );
        
        add_settings_section( 'section_form_field_includes', 'Form Fields', array( &$this, 'section_form_field_includes_desc' ), self::$key );
        add_settings_field( 'include_firstname_lastname', 'Choose Which Fields To Include:', array( &$this, 'field_include_firstname_lastname' ), self::$key, 'section_form_field_includes' );

        add_settings_section( 'section_form_size', 'Set Form Width', array( &$this, 'section_form_size_desc' ), self::$key );
        add_settings_field( 'form_size', 'Form Width', array( &$this, 'field_form_size' ), self::$key, 'section_form_size' );

        add_settings_section( 'section_form_placeholders', 'Form Placeholders', array( &$this, 'section_form_placeholders_desc' ), self::$key );
        add_settings_field( 'email_placeholder', 'Email Placeholder', array( &$this, 'field_email_placeholder' ), self::$key, 'section_form_placeholders' );
        add_settings_field( 'firstname_placeholder', 'First Name Placeholder', array( &$this, 'field_firstname_placeholder' ), self::$key, 'section_form_placeholders' );
        add_settings_field( 'lastname_placeholder', 'Last Name Placeholder', array( &$this, 'field_lastname_placeholder' ), self::$key, 'section_form_placeholders' );
        add_settings_field( 'submit_button_text', 'Submit Button Text', array( &$this, 'field_submit_txt' ), self::$key, 'section_form_placeholders' );
        
        add_settings_section( 'section_status_messages', 'Confirmation Messages', array( &$this, 'section_status_messages_desc' ), self::$key );
        add_settings_field( 'confirmation_msg', 'Confirmation Message', array( &$this, 'field_confirmation_msg' ), self::$key, 'section_status_messages' );
        add_settings_field( 'email_validation_status_txt', '"Email Validation Error" Message', array( &$this, 'field_email_validation_status_txt' ), self::$key, 'section_status_messages' );
        add_settings_field( 'member_failed_status_txt', 'General Error Message', array( &$this, 'field_member_failed_status_txt' ), self::$key, 'section_status_messages' );

    }

    function section_form_field_includes_desc() {  }
    function field_include_firstname_lastname() { ?>
    	<input id="include_firstname_lastname_first"
           type="radio"
           name="<?php echo self::$key; ?>[include_firstname_lastname]"
           value="first" <?php checked( 'first', ( self::$settings['include_firstname_lastname'] ) ); ?>
        /><label for="include_firstname_lastname_first">First name + email</label><br />
        <input id="include_firstname_lastname_both"
           type="radio"
           name="<?php echo self::$key; ?>[include_firstname_lastname]"
           value="both" <?php checked( 'both', ( self::$settings['include_firstname_lastname'] ) ); ?>
        /><label for="include_firstname_lastname_both">First and last name + email</label><br />
        <input id="include_firstname_lastname_none"
           type="radio"
           name="<?php echo self::$key; ?>[include_firstname_lastname]"
           value="none" <?php checked( 'none', ( self::$settings['include_firstname_lastname'] ) ); ?>
        /><label for="include_firstname_lastname_none">Email only</label>
    <?php }

    function section_form_size_desc() {  }
    function field_form_size() { ?>
    	<input id="field_form_size"
           type="text"
           size="40"
           name="<?php echo self::$key; ?>[form_size]"
           value="<?php echo esc_attr( self::$settings['form_size'] ); ?>"
           placeholder="100%"
        /><br />
        <p>Please enter form width as: '400px' or '100%'</p>
    
   <?php
    }

    function section_form_placeholders_desc() {  }
    function field_email_placeholder() { ?>
        <input id="emma_email_placeholder"
           type="text"
           size="40"
           name="<?php echo self::$key; ?>[email_placeholder]"
           value="<?php echo esc_attr( self::$settings['email_placeholder'] ); ?>"
        />
    <?php }

    function field_firstname_placeholder() {?>
        <input id="emma_firstname_placeholder"
           type="text"
           size="40"
           name="<?php echo self::$key; ?>[firstname_placeholder]"
           value="<?php echo esc_attr( self::$settings['firstname_placeholder'] ); ?>"
        />
    <?php }

    function field_lastname_placeholder() { ?>
        <input id="emma_lastname_placeholder"
           type="text"
           size="40"
           name="<?php echo self::$key; ?>[lastname_placeholder]"
           value="<?php echo esc_attr( self::$settings['lastname_placeholder'] ); ?>"
        />
    <?php }

    function field_submit_txt() { ?>
        <input id="emma_submit_txt"
           type="text"
           size="40"
           name="<?php echo self::$key; ?>[submit_txt]"
           value="<?php echo esc_attr( self::$settings['submit_txt'] ); ?>"
        />
    <?php }
	
	function section_status_messages_desc() { ?>
		
	<?php }

    function field_confirmation_msg() { ?>
        <textarea id="emma_confirmation_msg"
              name="<?php echo self::$key; ?>[confirmation_msg]"
              rows="6"
              cols="40" ><?php
        // avoid undefined index by checking for the value 1st, then assigning it nothing if it has not been set.
        $confirmation_msg = isset( self::$settings['confirmation_msg'] ) ? esc_attr( self::$settings['confirmation_msg'] ) : '';
        echo $confirmation_msg;
        ?></textarea>
    <?php
    }
    
    function field_email_validation_status_txt() { 
    ?>
	    <textarea id="emma_email_validation_status_txt"
              name="<?php echo self::$key; ?>[email_validation_status_txt]"
              rows="6"
              cols="40" ><?php
        // avoid undefined index by checking for the value 1st, then assigning it nothing if it has not been set.
        $status_txt = isset( self::$settings['email_validation_status_txt'] ) ? esc_attr( self::$settings['email_validation_status_txt'] ) : '';
        echo $status_txt;
        ?></textarea>
	<?php
    }
	
	function field_member_failed_status_txt() { ?>
	    <textarea id="emma_member_failed_status_txt"
              name="<?php echo self::$key; ?>[member_failed_status_txt]"
              rows="6"
              cols="40" ><?php
        // avoid undefined index by checking for the value 1st, then assigning it nothing if it has not been set.
        $status_txt = isset( self::$settings['member_failed_status_txt'] ) ? esc_attr( self::$settings['member_failed_status_txt'] ) : '';
        echo $status_txt;
        ?></textarea>
        <span class="emma-description-text">This is what will show in the rare event that Emma can't make a connection.</span>
	<?php
	}

    // Form preview section
    // for version 2.0,
    function field_form_preview() {
        echo '<div style="position: fixed; top: 130px; right: 50px;">';
        $preview_form = new Form( self::$settings );
        echo $preview_form->output();
        echo '</div>';
    }

    function sanitize_form_setup_settings( $input ) {

        $valid_input = array();

        // check which button was clicked, submit or reset,
        $submit = ( ! empty( $input['submit'] ) ? true : false );
        $reset = ( ! empty( $input['reset'])  ? true : false );

        if ( $submit ) {

            // text inputs
            $valid_input['form_signup_id'] 						  = (is_numeric($input['form_signup_id']) ? $input['form_signup_id'] : $valid_input['form_signup_id']);
            $valid_input['include_firstname_lastname']  		  = $input['include_firstname_lastname'];
            $valid_input['form_size']                   		  = $input['form_size'];
            $valid_input['email_placeholder']           		  = wp_kses( $input['email_placeholder'], '' );
            $valid_input['firstname_placeholder']       		  = wp_kses( $input['firstname_placeholder'], '' );
            $valid_input['lastname_placeholder']        		  = wp_kses( $input['lastname_placeholder'], '' );
            $valid_input['submit_txt']                  		  = wp_kses( $input['submit_txt'], '' );
            $valid_input['confirmation_msg']            		  = wp_kses( $input['confirmation_msg'], '' );
            $valid_input['email_validation_status_txt']			  = wp_kses( $input['email_validation_status_txt'], '' );
			$valid_input['member_already_exists_status_txt']	  = wp_kses( $input['member_already_exists_status_txt'], '' );
			$valid_input['member_failed_status_txt']			  = wp_kses( $input['member_failed_status_txt'], '' );
			$valid_input['confirmation_email_success_status_txt'] = wp_kses( $input['confirmation_email_success_status_txt'], '' );
			$valid_input['confirmation_email_failed_status_txt']  = wp_kses( $input['confirmation_email_failed_status_txt'], '' );
            $valid_input['powered_by']                  		  = $input['powered_by'];
            $valid_input['send_confirmation_email']     		  = $input['send_confirmation_email'];
            $valid_input['confirmation_email_subject']  		  = wp_kses( $input['confirmation_email_subject'], '' );
            $valid_input['confirmation_email_msg']      		  = wp_kses( $input['confirmation_email_msg'], '' );

        } elseif ( $reset ) {

            // get defaults
            $default_input = $this->get_settings_defaults();
            // assign to valid input
            $valid_input = $default_input;
        }
        return $valid_input;
    }
}