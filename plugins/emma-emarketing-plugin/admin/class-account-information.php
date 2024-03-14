<?php

class Account_Information {

    public static $key = 'emma_account_information';

    public static $settings = array();

    function __construct() {

        add_action( 'admin_init', array( &$this, 'register_settings' ) );
        
        add_action( 'wp_ajax_emma_add_group', array( &$this, 'emma_add_group_callback' ) );

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
            'plugin_version' => '1.2.0',
            'account_id' => '',
            'publicAPIkey' => '',
            'privateAPIkey' => '',
            'form_signup_id' => '',
            'send_confirmation' => 1,
            'logged_in' => 'false',
            'groups' => array(),
            'group_active' => '',
        );
        return $defaults;
    }

    function register_settings() {
        // register_setting( $option_group, $option_name, $sanitize_callback );
        register_setting( self::$key, self::$key, array( &$this, 'sanitize_account_information_settings' ) );

        add_settings_section( 'section_login', 'Emma Account Login Information', array( &$this, 'section_login_desc' ), self::$key );

        add_settings_field( 'publicAPIkey', 'Public API Key', array( &$this, 'field_public_api_key' ), self::$key, 'section_login' );
        add_settings_field( 'privateAPIkey', 'Private API Key', array( &$this, 'field_private_api_key' ), self::$key, 'section_login' );
        add_settings_field( 'account_id',     'Account ID', array( &$this, 'field_account_id' ),     self::$key, 'section_login' );
        add_settings_field( 'form_signup_id', 'Signup ID (optional)',  array( &$this, 'field_form_signup_id' ), self::$key, 'section_login' );
        add_settings_field( 'send_confirmation', 'Send Confirmation Email', array( &$this, 'field_send_confirmation' ), self::$key, 'section_login' );

        add_settings_section( 'section_groups', 'Add New Members to Group', array( &$this, 'section_groups_desc' ), self::$key );

        add_settings_field( 'logged_in', '', array( &$this, 'field_logged_in' ), self::$key, 'section_groups' );

        // check to see if logged in to emma
        if ( self::$settings['logged_in'] == 'true' ) {
            add_settings_field( 'groups', 'Select Group', array( &$this, 'field_groups' ), self::$key, 'section_groups' );
        }
    }
    function section_login_desc() { 
	    if (self::$settings['account_id'] == '' || self::$settings['publicAPIkey'] == '' || self::$settings['privateAPIkey'] == '' ) {
			echo "<hr><h4>Don't have an Emma account yet? Try it free for 30 days by signing up <a href='http://myemma.com/partners/get-started?utm_source=Wor50549&utm_medium=integrationpartner&utm_campaign=partner-trial' title='Email Marketing Services - Email Marketing Software - Email Marketing | Emma, Inc.' target='_blank'>here</a>!</h4><hr>";
		}
		
		echo '<p>You must have an Emma Account with a API key. If you&apos;re unsure if your account is on the new API, contact Emma</p>';
	}
    function field_account_id() { ?>
        <input id="emma_account_id"
           type="text"
           size="20"
           name="<?php echo self::$key; ?>[account_id]"
           value="<?php echo esc_attr( self::$settings['account_id'] ); ?>"
        />
    <?php }
    function field_public_api_key() { ?>
        <input id="emma_publicAPIkey"
           type="text"
           size="20"
           name="<?php echo self::$key; ?>[publicAPIkey]"
           value="<?php echo esc_attr( self::$settings['publicAPIkey'] ); ?>"
        />
    <?php }
    function field_private_api_key() { ?>
        <input id="emma_privateapikey"
           type="text"
           size="20"
           name="<?php echo self::$key ?>[privateAPIkey]"
           value="<?php echo esc_attr( self::$settings['privateAPIkey'] ); ?>"
        />
    <?php }
	function field_form_signup_id() { ?>
        <input id="emma_form_signup_id"
           type="text"
           size="20"
           name="<?php echo self::$key; ?>[form_signup_id]"
           value="<?php echo esc_attr( self::$settings['form_signup_id'] ); ?>"
        />
    <?php }
	function field_send_confirmation() { ?>
		<input id="emma_send_confirmation_email"
		   type="checkbox"
		   name="<?php echo self::$key; ?>[send_confirmation]"
		   value="1"
		   <?php checked( esc_attr( self::$settings['send_confirmation'] ) ); ?>
		/> Yes
	<?php }
		
	function emma_add_group_callback() {
		$groups[] = array('group_name' => $_POST['groupName']);
        
        $group_data = array(
	        'groups' => $groups,
        );
	    
	    // instantiate a new Emma API class, pass login / auth data to it
        $emma_api = new Emma_API( self::$settings['account_id'], self::$settings['publicAPIkey'], self::$settings['privateAPIkey'] );
        
        $response = $emma_api->groupsAdd($group_data);
        $response_obj = $response[0];
        
        if (is_object($response_obj) && $response_obj->member_group_id !== '') {
	        $ajax_response = array(
	        	'status' => 'success',
	        	'response' => $response,
	        	'member_group_id' => $response_obj->member_group_id,
	        	'group_name' => $response_obj->group_name,
	        	);
        } else {
			$ajax_response = array(
		        'status' => 'failure',
		        'response' => $response,
	        );
        }
		
	    echo json_encode($ajax_response);
		wp_die();
	}
    function section_groups_desc() {

        if ( self::$settings['logged_in'] == 'true' ) {
            echo 'Assign members to groups ( optional )';
            
            echo '<div id="add-new-group-wrap"><h4>Need to add a new group? Enter a new group name below and click "Add Group."</h4>';
	        echo '<input type="text" name="emma_add_new_group" />';
	        echo '<a style="margin-left: 20px;" id="add-group" class="button-secondary emma-add-group-button">Add Group</a><span class="add-group-response"></span></div>';
            
        } else {
            echo 'Once you&apos;ve entered your account information and saved the changes, then you can choose from the available groups to assign new members to';
        }
    }

    function field_logged_in() { ?>
        <input id="emma_logged_in"
               type="hidden"
               name="<?php echo self::$key ?>[logged_in]"
               value="<?php echo esc_attr( self::$settings['logged_in'] ); ?>"
        />
    <?php }

    function field_groups() {

        $groups = self::$settings['groups'];

        // groups dropdown
        echo '<select id="emma_groups" name="' . self::$key . '[group_active]">';
        echo '<option value="000"> - select a group - </option>';

        foreach ( $groups as $group_key => $group_value ) {
            echo '<option value="' . $group_key . '"';
            if ( self::$settings['group_active'] == $group_key ) { echo "selected"; }
            echo '>' . $group_value . '</option>';
        }

        echo '</select>';

        // refresh button
        echo '<input style="margin-left: 20px;" type="submit" name="emma_account_information[refresh]" id="refresh" class="button-secondary" value="Refresh Groups" />';
    }

    function sanitize_account_information_settings( $input ) {
		
        // get the current options
        // $valid_input = self::$settings;
        $valid_input = array();

        // check which button was clicked, submit or reset,
        $submit = ( ! empty( $input['submit'] ) ? true : false );
        $reset = ( ! empty( $input['reset'])  ? true : false );
        $refresh = ( ! empty( $input['refresh']) ? true : false );

        // if the submit or refresh button was clicked
        if ( $submit || $refresh ) {

            /**
             * validate the account information settings, and add error messages
             * add_settings_error( $setting, $code, $message, $type )
             * $setting here refers to the $id of add_settings_field
             * add_settings_field( $id, $title, $callback, $page, $section, $args );
             */

            // account number

            // check if it's a number
            $valid_input['account_id'] = ( is_numeric($input['account_id']) ? $input['account_id'] : $valid_input['account_id'] );
            if ( $valid_input['account_id'] != $input['account_id'] ) {
                add_settings_error(
                    'account_id',
                    'emma_error',
                    'The Account Number can only contain numbers, no letters or alpha-numeric characters',
                    'error'
                );
            };
            
            // public API key
            if ( ( strlen($input['publicAPIkey']) == 20 ) && ( ctype_alnum($input['publicAPIkey']) ) ) {
                $valid_input['publicAPIkey'] = $input['publicAPIkey'];
            } else {
                add_settings_error(
                    'public_api_key',
                    'emma_error',
                    'The Public API Key can only contain letters and numbers, and should be 20 characters long',
                    'error'
                );
            }

            // private API key
            // make sure it's only 20 characters and contains only upper / lowercase letters and numbers
//            if ( preg_match('/[0-9a-zA-Z]{20}/', $input['privateAPIkey']) ) {
            if ( ( strlen($input['privateAPIkey']) == 20 ) && ( ctype_alnum($input['privateAPIkey']) ) ) {
                $valid_input['privateAPIkey'] = $input['privateAPIkey'];
            } else {
                add_settings_error(
                    'private_api_key',
                    'emma_error',
                    'The Private API Key can only contain letters and numbers, and should be 20 characters long',
                    'error'
                );
            }
            
            // form sign up ID :: check if it's a number		
            $valid_input['form_signup_id'] = ( is_numeric($input['form_signup_id']) ? $input['form_signup_id'] : $valid_input['form_signup_id'] );		
            if ( $valid_input['form_signup_id'] != $input['form_signup_id'] ) {		
                add_settings_error(		
                    'form_signup_id',		
                    'emma_error',		
                    'The Signup ID can only contain numbers, no letters or alpha-numeric characters',		
                    'error'		
                );		
            };
            
            // Send Confirmation Email
            $valid_input['send_confirmation'] = $input['send_confirmation'];

            // get group data

            // instantiate a new Emma API class, pass login / auth data to it
            $emma_api = new Emma_API( $valid_input['account_id'], $valid_input['publicAPIkey'], $valid_input['privateAPIkey'] );

            // get the groups for this account
            $groups = $emma_api->list_groups();

            // check if groups returned an error, or an answer
            if ( is_array($groups) && !empty($groups) ) {

                // if it returns an array, it's got groups back from hooking up w/ emma
                $valid_input['logged_in'] = 'true';

                // pass the array of groups into the settings
                $valid_input['groups'] = $groups;

                // if there is an active group selected, pass it through
                $valid_input['group_active'] = $input['group_active'];

            } else {

                // not logged in...
                $valid_input['logged_in'] = 'false';

                // pass thru previous info
                $valid_input['groups'] = self::$settings['groups'];
                $valid_input['group_active'] = $input['group_active'];
                
                $error_out = $groups;

                // the method returns a string / error message otherwise
                add_settings_error(
                    'account_id',
                    'emma_error',
                    $error_out,
                    'error'
                );


            }

        } elseif ( $reset ) {

            // get defaults
            $default_input = $this->get_settings_defaults();
            // assign to valid input
            $valid_input = $default_input;

        }

        return $valid_input;

    } // end sanitize_account_information_settings


}
