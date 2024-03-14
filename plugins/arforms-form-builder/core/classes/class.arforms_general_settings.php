<?php

class arforms_general_settings{

    function __construct(){

        add_action('wp_ajax_arf_save_setting_data', array($this,'arf_save_setting_data_func') );

        /* Gmail API auth */
        add_action( 'wp',array($this,'arf_add_gmail_api'),10);
        add_action('wp_ajax_arf_gmail_remove_auth', array($this, 'arf_gmail_remove_auth_func'));
        add_action('wp_ajax_arf_send_test_gmail', array($this, 'arf_send_test_gmail'));

        /* SMTP send email notification */
        add_action( 'wp_ajax_arf_send_test_mail', array( $this, 'arf_send_test_mail' ) );
    }

    function arf_send_test_mail() {
		global $arflitenotifymodel, $arformsmain;

		if ( !isset( $_POST['_wpnonce_arflite'] ) || (isset( $_POST['_wpnonce_arflite'] ) && '' != $_POST['_wpnonce_arflite'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arflite'] ), 'arflite_wp_nonce' )) ) {
			echo esc_attr( 'security_error' );
			die;
		}

		$reply_to = ( isset( $_POST['reply_to'] ) && ! empty( $_POST['reply_to'] ) ) ? sanitize_email( $_POST['reply_to'] ) : '';
		$send_to  = ( isset( $_POST['send_to'] ) && ! empty( $_POST['send_to'] ) ) ? sanitize_email( $_POST['send_to'] ) : '';

		$subject       = ( isset( $_POST['subject'] ) && ! empty( $_POST['subject'] ) ) ? sanitize_text_field( $_POST['subject'] ) : __( 'SMTP Test E-Mail', 'arforms-form-builder' );
		$message       = ( isset( $_POST['message'] ) && ! empty( $_POST['message'] ) ) ? sanitize_textarea_field( $_POST['message'] ) : '';
		$reply_to_name = ( isset( $_POST['reply_to_name'] ) && ! empty( $_POST['reply_to_name'] ) ) ? sanitize_text_field( $_POST['reply_to_name'] ) : '';

		if ( empty( $send_to ) || empty( $reply_to ) || empty( $message ) || empty( $subject ) ) {
			return;
		}

        if( $arformsmain->arforms_is_pro_active() ){
            global $arnotifymodel;
            echo $arnotifymodel->send_notification_email_user($send_to, $subject, $message, $reply_to, $reply_to_name, '', array(), true, true, true, true); //phpcs:ignore
        } else {
            echo $arflitenotifymodel->arflite_send_notification_email_user($send_to, $subject, $message, $reply_to, $reply_to_name, '', array(), true, true, true, true); //phpcs:ignore
        }

		die();
	}

    function arf_send_test_gmail(){
        global $arflitenotifymodel, $arformsmain;
		if ( !isset( $_POST['_wpnonce_arfnonce'] ) || ( isset( $_POST['_wpnonce_arfnonce'] ) && '' != $_POST['_wpnonce_arfnonce'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arfnonce'] ), 'arflite_wp_nonce' ) ) ) {
            echo esc_attr( 'security_error' );
            die;
		}
        $from_to = (isset($_POST['from_to'])) && !empty($_POST['from_to']) ? sanitize_email($_POST['from_to']) : '';
        $send_to = (isset($_POST['send_to'])) && !empty($_POST['send_to']) ? sanitize_email($_POST['send_to']) : '';
        $subject = (isset($_POST['subject']) && !empty($_POST['subject'])) ? sanitize_text_field($_POST['subject']) : addslashes(esc_html__('GMAIL Test E-Mail', 'arforms-form-builder'));
        $message = (isset($_POST['message']) && !empty($_POST['message'])) ? sanitize_text_field($_POST['message']) : '';
        $reply_to_name = (isset($_POST['reply_to_name']) && !empty($_POST['reply_to_name'])) ? sanitize_text_field($_POST['reply_to_name']) : '';
        if (empty($send_to) || empty($from_to) || empty($message) || empty($subject)) {
            return;
        }
        if( $arformsmain->arforms_is_pro_active() ){
            global $arnotifymodel; 
            echo $arnotifymodel->send_notification_email_user($send_to, $subject, $message, $from_to, $reply_to_name, '', array(), true, true, true, true); //phpcs:ignore
        } else {
            echo $arflitenotifymodel->arflite_send_notification_email_user($send_to, $subject, $message, $from_to, $reply_to_name, '', array(), true, true, true, true); //phpcs:ignore

        }
        die();
    }
    function arf_gmail_remove_auth_func(){
		global $arformsmain;
		if ( !isset( $_POST['_wpnonce_arflite'] ) || ( isset( $_POST['_wpnonce_arflite'] ) && '' != $_POST['_wpnonce_arflite'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arflite'] ), 'arflite_wp_nonce' ) ) ) {
            echo esc_attr( 'security_error' );
            die;
		}
        $auth_token = !empty( $_POST['auth_token'] ) ? sanitize_text_field( $_POST['auth_token'] ) : '' ; //phpcs:ignore WordPress.Security.NonceVerification
        $auth_email = !empty( $_POST['connected_email']) ? sanitize_text_field( $_POST['connected_email']) : ''; //phpcs:ignore WordPress.Security.NonceVerification
        $auth_response = !empty( $_POST['access_token_data']) ? $_POST['access_token_data'] : ''; //phpcs:ignore
        if( !empty( $auth_response)){
			$arformsmain->arforms_update_settings( 'arf_gmail_api_response_data', '', 'general_settings' );
        }
        if( !empty( $auth_token)){
			$arformsmain->arforms_update_settings( 'arf_gmail_api_access_token', '', 'general_settings' );
        }
        if( !empty( $auth_email )){
			$arformsmain->arforms_update_settings( 'arf_gmail_api_connected_email', '', 'general_settings' );
        }
        $response['variant'] = 'success';
        $response['title']   = esc_html__( 'Success', 'arforms-form-builder' );
        $response['msg']     = esc_html__( 'Sign out successfully.', 'arforms-form-builder' );
        echo wp_json_encode( $response );
        die();
    }
    function arf_add_gmail_api(){
		global $arformsmain;
        if( isset( $_GET['page'] ) && 'ARForms-settings' == $_GET['page'] ){
            if( empty( $_GET['state'] ) ){
                echo "<script type='text/javascript' data-cfasync='false'>";
                echo "let url = document.URL;";
                echo "if( /\#state/.test( url ) ){";
                    echo "url = url.replace( /\#state/, '&state' );";
                    echo "window.location.href= url;";
                echo "} else {";
                    echo "window.location.href='" . get_home_url() . "';"; //phpcs:ignore
                echo "}";
                echo "</script>";
            } else {
                global $wpdb, $arformsmain, $arflitemaincontroller;
                $gmail_api_clientID = $arformsmain->arforms_get_settings('gmail_api_clientid','general_settings');
                $gmail_api_client_secret = $arformsmain->arforms_get_settings('gmail_api_clientsecret','general_settings');
                $gmail_api_clientID = !empty( $gmail_api_clientID ) ? $gmail_api_clientID : '';
                $gmail_api_client_secret = !empty( $gmail_api_client_secret ) ? $gmail_api_client_secret : '';
				//$arflitemaincontroller->arfliteafterinstall();
                $state = base64_decode( $_GET['state'] ); //phpcs:ignore
                if( preg_match( '/(gmail_oauth)/', $state ) ){
                    require_once ARFLITE_FORMPATH . '/core/gmail/vendor/autoload.php';
                    $code = !empty( $_GET['code']) ? urldecode( $_GET['code'] ) : ''; //phpcs:ignore
                    $arformslite_client_id =  $gmail_api_clientID;
                    $arformslite_client_secret = $gmail_api_client_secret;
                    $arformslite_redirect_url = get_home_url(). '?page=ARForms-settings';
                    $client = new Google_Client();
                    $client->setClientId($arformslite_client_id);
                    $client->setClientSecret( $arformslite_client_secret );
                    $client->setRedirectUri( $arformslite_redirect_url);
                    $client->setAccessType( 'offline' );
                    $response_data  = $client->authenticate( $code );
                    if( !empty($response_data)){
                        if( isset($response_data['access_token']) && $response_data['access_token'] != '' ){
                            $access_token = $response_data['access_token'];
                        }
                    }
                    $client->setAccessToken( $response_data );
                    $service = new Google\Service\Gmail( $client );  
                    try {
                        $email = $service->users->getProfile( 'me' )->getEmailAddress();
                    } catch ( \Exception $e ) {
                        $email = '';
                    }
                        $email = json_encode( $email );
                        $response_data = json_encode( $response_data );
                        $access_token_db = json_encode( $access_token );
                        if( !empty($email)){
							$arformsmain->arforms_update_settings( 'arf_gmail_api_connected_email', $email, 'general_settings' );
                        }
                        if( !empty($response_data)){
							$arformsmain->arforms_update_settings( 'arf_gmail_api_response_data', $response_data, 'general_settings' );
                        }
                        if( !empty($access_token)){
							$arformsmain->arforms_update_settings( 'arf_gmail_api_access_token', $access_token_db, 'general_settings' );
                        }
                    ?>
                    <script>
                        load_function();
                        function load_function() {
                            window.opener.document.getElementById('frm_gmail_api_accesstoken').value = '<?php echo $access_token;  //phpcs:ignore ?>';
                            window.opener.document.getElementById('arflite_google_api_auth_link_remove').style.display = "inline-block";
                            window.opener.document.getElementById('arflite_google_api_auth_link').style.display = "none";
                            window.close();
                        }
                    </script>
                    <?php
                }
            }
            die;
        }
    }

    function arf_save_setting_data_func(){

        global $arformsmain, $tbl_arf_settings;

        $response = array();

        if ( !isset( $_POST['_wpnonce_arfnonce'] ) || ( isset( $_POST['_wpnonce_arfnonce'] ) && '' != $_POST['_wpnonce_arfnonce'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arfnonce'] ), 'arflite_wp_nonce' ) ) ) {
            $response['variant'] = 'error';
            $response['title'] = esc_html__( 'Error', 'arforms-form-builder');
            $response['msg'] = esc_html__( 'Sorry, your request could not be processed due to security reason.', 'arforms-form-builder' );

            wp_send_json( $response );
            die;
		}

        if( !current_user_can( 'arfchangesettings' ) ){
            $response['variant'] = 'error';
            $response['title'] = esc_html__( 'Error', 'arforms-form-builder');
            $response['msg'] = esc_html__( 'Sorry, you do not have permission to perform this action', 'arforms-form-builder' );;
            wp_send_json( $response );
            die;
        }

        $response['variant'] = 'error';
        $response['title']   = esc_html__('Error', 'arforms-form-builder');
        $response['msg']     = esc_html__('Something Went wrong while updating settings...', 'arforms-form-builder');
        
        $arf_setting_filterd_form = isset( $_POST['setting_form_data'] ) ?  stripslashes_deep( $_POST['setting_form_data'] ) : array(); //phpcs:ignore

		$settings_data = json_decode( $arf_setting_filterd_form, true );
        
        if( !empty( $settings_data )){

            $arforms_default_opts = $arformsmain->arflite_default_options();
            $arforms_default_opts_key = $arformsmain->arflite_default_options_keys();

            if( $arformsmain->arforms_is_pro_active() && class_exists('arforms_pro_settings') && method_exists('arforms_pro_settings', 'arforms_fetch_pro_default_options') ){
                $arforms_pro_default_opts = arforms_pro_settings::arforms_fetch_pro_default_options();
                if( !empty( $arforms_pro_default_opts ) ){
                    $arforms_default_opts = array_merge( $arforms_default_opts, $arforms_pro_default_opts );
                }
            }

            foreach( $arforms_default_opts as $option_name => $option_val ){
    
                if( isset( $settings_data[ $option_name ] ) ){
                    $opt_val = $settings_data[ $option_name ];
                } else if( isset( $settings_data['frm_' . $option_name ] ) ){
                    $opt_val = $settings_data['frm_' . $option_name ];
                } else {
                    $opt_val = $option_val;
                }
                
                if( !in_array( $option_name, $arformsmain->arforms_skip_sanitization_keys() ) ){
                    $opt_val = is_array( $opt_val ) ? array_map( array( $arformsmain, 'arforms_sanitize_values' ), $opt_val ) : $arformsmain->arforms_sanitize_single_value( $opt_val );
                }
        
                if( is_array( $opt_val ) ){
                    $opt_val = json_encode( $opt_val );
                }
        
                $arformsmain->arforms_update_settings( $option_name, $opt_val, 'general_settings' );
                if( !empty( $arforms_default_opts_key[ $option_name ] ) ){
                    $arformsmain->arforms_update_settings( $arforms_default_opts_key[ $option_name ], $opt_val, 'general_settings' );
                }

            }

            $params = $settings_data;

            $opt_data_from_outside = array();
			$opt_data_from_outside = apply_filters('arf_update_global_setting_outside',$opt_data_from_outside,$params);  

			if(is_array($opt_data_from_outside) && !empty($opt_data_from_outside) && count($opt_data_from_outside) > 0) {
				foreach ($opt_data_from_outside as $key => $optdata) {
					$this->$key = $optdata;
				}
			}
            
            if( isset($params['anonymous_data']) && true == $params['anonymous_data']){
                $this->arforms_set_anonymus_data_cron();
            }
            update_option('arforms_current_tab', 'general_settings' );

            
            $response['variant'] = 'success';
            $response['title']   = esc_html__('Success', 'arforms-form-builder');
            $response['msg']     = esc_html__('General setting saved successfully.', 'arforms-form-builder');
        }
        
        wp_cache_delete( 'arforms_all_general_settings' );
        
        echo json_encode($response);
        die;
    }

    function arforms_include_pro_files(  $filename = '', $type = 'view' ){
        global $arformsmain;
        if( $arformsmain->arforms_is_pro_active() && !empty( $filename ) && defined( 'FORMPATH' ) ){

            if( 'view' == $type && file_exists( FORMPATH . '/core/views/' . $filename ) ){
                require_once FORMPATH . '/core/views/' . $filename;
            }
        }
    }

    function arforms_render_pro_settings( $setting_key ){
        global $arformsmain;

        if( class_exists( 'arforms_pro_settings' ) && method_exists( 'arforms_pro_settings', 'arforms_render_pro_settings_ui' ) ){
            arforms_pro_settings::arforms_render_pro_settings_ui( $setting_key );
        }

    }

    /**
     * Set anonymous data cron
     *
     * @return void
     */
    function arforms_set_anonymus_data_cron() {
        global $arflitemaincontroller;
        wp_get_schedules();
        if ( ! wp_next_scheduled('arforms_send_anonymous_data') ) {                
            wp_schedule_event( time(), 'weekly', 'arforms_send_anonymous_data');
        }
    }
}

global $arforms_general_settings;
$arforms_general_settings = new arforms_general_settings();