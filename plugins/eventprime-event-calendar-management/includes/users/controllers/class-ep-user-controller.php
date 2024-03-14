<?php
/**
 * User controller
 */

defined( 'ABSPATH' ) || exit;

class EventM_User_Controller {

    public function __construct() {
        wp_register_style(
			'ep-user-select2-css',
			EP_BASE_URL . '/includes/assets/css/select2.min.css',
			false, EVENTPRIME_VERSION
		);
	
        wp_register_style(
            'ep-user-views-custom-css',
            EP_BASE_URL . '/includes/users/assets/css/ep-user-views.css',
            false, EVENTPRIME_VERSION
        );

        wp_register_script(
            'ep-user-select2-js',
            EP_BASE_URL . '/includes/assets/js/select2.full.min.js',
            array( 'jquery' ), EVENTPRIME_VERSION
		);
        wp_register_script(
            'ep-user-views-js',
            EP_BASE_URL . '/includes/users/assets/js/ep-user-custom.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );
    }

    public function enqueue_style_script(){
        wp_enqueue_style( 'ep-user-select2-css' );
        wp_enqueue_style( 'ep-user-views-custom-css' );
        wp_enqueue_script( 'ep-user-select2-js' );
        wp_enqueue_script( 'ep-user-views-js' );

        wp_localize_script(
            'ep-user-views-js', 
            'ep_frontend', 
            array(
                '_nonce'                => wp_create_nonce( 'ep-frontend-nonce' ),
                'ajaxurl'               => admin_url( 'admin-ajax.php' ),
                'nonce_error'           => esc_html__( 'Please refresh the page and try again.', 'eventprime-event-calendar-management' ),
                'delete_event_confirm'  => esc_html__( 'Are you sure you want to delete this event?', 'eventprime-event-calendar-management' )
            )
        );
    }

    /**
     * Render template on the frontend
     */
    public function render_template( $atts = array() ) {
        global $wp;

        ob_start();

        $this->enqueue_style_script();
        
        $args = new stdClass();
        $args->show_register = 0;
        $args->redirect_url = ( ! empty( ep_get_global_settings( 'login_redirect_after_login' ) ) ) ? get_permalink( ep_get_global_settings( 'login_redirect_after_login' ) ) : get_permalink( ep_get_global_settings( 'profile_page' ) );
        
        if( isset( $_POST['ep_register'] ) ) {
            $args->show_register = 1;
        }

        $args = $this->get_login_options( $args );

        $args = $this->get_register_options( $args );

        if ( ! is_user_logged_in() ) {
            ep_get_template_part( 'users/login', null, $args );
        }else{
            $args->current_user = wp_get_current_user();
            $args->upcoming_bookings = ( ! empty( $args->current_user->ID ) ) ? EventM_Factory_Service::get_user_wise_upcoming_bookings( $args->current_user->ID ) : array();
            $args->all_bookings      = ( ! empty( $args->current_user->ID ) ) ? EventM_Factory_Service::get_user_all_bookings( $args->current_user->ID ) : array();
            $args->wishlisted_events = ( ! empty( $args->current_user->ID ) ) ? EventM_Factory_Service::get_user_wishlisted_events( $args->current_user->ID ) : array();
            $args->submitted_events  = ( ! empty( $args->current_user->ID ) ) ? EventM_Factory_Service::get_user_submitted_events( $args->current_user->ID ) : array();
            
            ep_get_template_part( 'users/profile', null, $args );
        }

		return ob_get_clean();
    }

    /**
     * Render login template on the frontend
     */
    public function render_login_template( $atts = array() ) {
        global $wp;

        ob_start();

        $this->enqueue_style_script();

        $args = new stdClass();
        $args->redirect_url = ( ! empty( ep_get_global_settings( 'login_redirect_after_login' ) ) ) ? get_permalink( ep_get_global_settings( 'login_redirect_after_login' ) ) : get_permalink( ep_get_global_settings( 'profile_page' ) );

        if(isset( $atts['redirect'] ) ) {
            if( $atts['redirect'] == 'off' ) {
                $args->redirect_url = '';
            }
            if( $atts['redirect'] == 'reload' ) {
                $args->redirect_url = 'reload';
            }
        }
        //  Login Block Attributes start
        if(! empty( $atts['block_login_custom_class'] ) ){
            $args->block_login_custom_class = $atts['block_login_custom_class'];
        }
        if( ! empty( $atts['block_login_title'] ) ){
            $args->block_login_title = $atts['block_login_title'];
        }
        if( ! empty( $atts['block_login_user_detail_label'] ) ){
            $args->block_login_user_detail_label = $atts['block_login_user_detail_label'];
        }
        if( ! empty( $atts['block_login_password_label'] ) ){
            $args->block_login_password_label = $atts['block_login_password_label'];   
        }
        if( ! empty( $atts['block_login_remember_me_label'] ) ){
            $args->block_login_remember_me_label = $atts['block_login_remember_me_label'];   
        }
        if( ! empty( $atts['block_login_forget_password_label'] ) ){
            $args->block_login_forget_password_label = $atts['block_login_forget_password_label'];
        }
        if( ! empty( $atts['block_login_click_here_label'] ) ){
            $args->block_login_click_here_label = $atts['block_login_click_here_label'];
        }
        if( ! empty( $atts['block_login_button']  ) ){
            $args->block_login_button_label = $atts['block_login_button'];
        }
        if( ! empty( $atts['block_login_dont_have_account_label'] ) ){
            $args->block_login_dont_have_account_label = $atts['block_login_dont_have_account_label'];
        }
        if( ! empty( $atts['block_login_register_link_label'] ) ){
            $args->block_login_register_link_label = $atts['block_login_register_link_label'];
        }
        if( ! empty( $atts['align'] ) ){
            $args->align = $atts['align'];
        }
        if( ! empty( $atts['backgroundColor'] ) ){
            $args->backgroundColor = $atts['backgroundColor'];
        }
        if( ! empty( $atts['textColor'] ) ){
            $args->textColor = $atts['textColor'];
        }
        //  Login Block Attributes end
        
        $args = $this->get_login_options( $args );

        $args->current_user = wp_get_current_user();
        
        ep_get_template_part( 'users/login', null, $args );
		
		return ob_get_clean();
    }
    
    /**
     * Render register template on the frontend
     */
    public function render_register_template( $atts = array() ) {
        global $wp;

        ob_start();
        $this->enqueue_style_script();
        $args = new stdClass();
        $args->show_register = 0;
        $args->redirect_url = ( ! empty( ep_get_global_settings( 'login_redirect_after_login' ) ) ) ? get_permalink( ep_get_global_settings( 'login_redirect_after_login' ) ) : get_permalink( ep_get_global_settings( 'profile_page' ) );

        if( isset( $_POST['ep_register'] ) ) {
            $args->show_register = 1;
        }
        //  Register Block Attributes start
        if(! empty( $atts['block_register_custom_class'] ) ){
            $args->block_register_custom_class = $atts['block_register_custom_class'];
        }
        if( ! empty( $atts['block_register_user_name_label'] ) ){
            $args->block_register_user_name_label = $atts['block_register_user_name_label'];
        }
        if( ! empty( $atts['block_register_user_email_label'] ) ){
            $args->block_register_user_email_label = $atts['block_register_user_email_label'];
        }
        if( ! empty( $atts['block_register_password_label'] ) ){
            $args->block_register_password_label = $atts['block_register_password_label'];   
        }
        if( ! empty( $atts['block_register_repeat_password_label'] ) ){
            $args->block_register_repeat_password_label = $atts['block_register_repeat_password_label'];   
        }
        if( ! empty( $atts['block_register_phone_label'] ) ){
            $args->block_register_phone_label = $atts['block_register_phone_label'];
        }
        if( ! empty( $atts['block_register_button'] ) ){
            $args->block_register_button_label = $atts['block_register_button'];
        }
        if( ! empty( $atts['block_register_already__account_label'] ) ){
            $args->block_register_already__account_label = $atts['block_register_already__account_label'];
        }
        if( ! empty( $atts['block_register_please__login_label'] ) ){
            $args->block_register_please__login_label = $atts['block_register_please__login_label'];
        }
        if( ! empty( $atts['align'] ) ){
            $args->align = $atts['align'];
        }
        if( ! empty( $atts['backgroundColor'] ) ){
            $args->backgroundColor = $atts['backgroundColor'];
        }
        if( ! empty( $atts['textColor'] ) ){
            $args->textColor = $atts['textColor'];
        }
        //  Register Block Attributes end

        $args->redirect_url = '';
        if(isset( $atts['redirect'] ) ) {
            if( $atts['redirect'] == 'reload' ) {
                $args->redirect_url = 'reload';
            }
        }

        $args = $this->get_register_options( $args );
        $args->current_user = wp_get_current_user();
            
        ep_get_template_part( 'users/register', null, $args );
		
		return ob_get_clean();
    }
    /**
     * Get login options from global settings
     * 
     * @param object $args Arguments
     * 
     * @return object $args
     */
    public function get_login_options( $args ) {  
        $args->login_heading_text = ( ! empty( ep_get_global_settings( 'login_heading_text' ) ) ? ep_get_global_settings( 'login_heading_text' ) : 'Login to Your Account' );

        // block login title update field
        if( ! empty( $args->block_login_title ) ){
            $args->login_heading_text = $args->block_login_title;
        }

        $args->login_subheading_text = ( ! empty( ep_get_global_settings( 'login_subheading_text' ) ) ? ep_get_global_settings( 'login_subheading_text' ) : '' );
        $args->login_username_label = esc_html__( 'Email/Username', 'eventprime-event-calendar-management' );
        $args->login_id_field = 'email_username';
        $login_id_field = ep_get_global_settings( 'login_id_field' );

        if( ! empty( $login_id_field ) ) {
            $login_username_label = ep_get_global_settings( 'login_id_field_label_setting' );
            if( empty( $login_username_label ) ) {
                if( $login_id_field == 'username' ) {
                    $args->login_username_label = esc_html__( 'Username', 'eventprime-event-calendar-management' );
                } elseif( $login_id_field == 'email' ) {
                    $args->login_username_label = esc_html__( 'Email', 'eventprime-event-calendar-management' );
                }
            } else{
                $args->login_username_label = $login_username_label;
            }
            $args->login_id_field = $login_id_field;
        }
        // Custom class for Login Block
        if(! empty( $args->block_login_custom_class ) ){
            $args->block_login_class = $args->block_login_custom_class;
        }
        // block user detail editable field
        if( ! empty( $args->block_login_user_detail_label ) ){
            $args->login_username_label = $args->block_login_user_detail_label;
        }

        $args->login_password_label = esc_html__( 'Password', 'eventprime-event-calendar-management' );
        $login_password_label = ep_get_global_settings( 'login_password_label' );

        if( !empty( $login_password_label ) ) {
            $args->login_password_label = $login_password_label;
        }
        // block login password editable field
        if( ! empty( $args->block_login_password_label ) ){
            $args->login_password_label = $args->block_login_password_label;
        }

        $args->login_show_rememberme_label = esc_html__( 'Remember Me', 'eventprime-event-calendar-management' );
        $login_show_rememberme_label = ep_get_global_settings( 'login_show_rememberme_label' );
        if( !empty( $login_show_rememberme_label ) ) {
            $args->login_show_rememberme_label = $login_show_rememberme_label;
        }
        // block login remember me editable field
        if( ! empty( $args->block_login_remember_me_label ) ){
            $args->login_show_rememberme_label = $args->block_login_remember_me_label;
        }
        
        $args->login_button_label = esc_html__( 'Log in', 'eventprime-event-calendar-management' );
        $login_button_label = ep_get_global_settings( 'login_button_label' );
        if( !empty( $login_button_label ) ) {
            $args->login_button_label = $login_button_label;
        }
        // block login button editable field
        if( ! empty( $args->block_login_button_label ) ){
            $args->login_button_label = $args->block_login_button_label;
        }
        $args->login_show_forgotpassword_label = esc_html__( 'Forgot Password?', 'eventprime-event-calendar-management' );
        if( ! empty( ep_get_global_settings( 'login_show_forgotpassword_label' ) ) ) {
            $args->login_show_forgotpassword_label = ep_get_global_settings( 'login_show_forgotpassword_label' );
        }
        // block login forget password editable feild
        if( ! empty( $args->block_login_forget_password_label ) ){
            $args->login_show_forgotpassword_label = $args->block_login_forget_password_label;
        }
        $args->login_click_here_label = esc_html__( ' Click Here', 'eventprime-event-calendar-management' );

        // block login Click Here label
        if( ! empty( $args->block_login_click_here_label ) ){
            $args->login_click_here_label = $args->block_login_click_here_label;
        }

        $args->login_google_recaptcha = ep_get_global_settings('login_google_recaptcha');
        $args->google_recaptcha_site_key = ep_get_global_settings('google_recaptcha_site_key');

        $args->dont_have_account_label = esc_html__( "Don't have an account.", 'eventprime-event-calendar-management' );
        
        // block login don't have account label
        if( ! empty( $args->block_login_dont_have_account_label ) ){
            $args->dont_have_account_label = $args->block_login_dont_have_account_label;
        }

        $args->register_text = '';

        return $args;
    }

    /**
     * Get register options from global settings
     *
     * @param object $args Arguments
     * 
     * @return object $args
     */
    public function get_register_options( $args ) {
        // username settings
        $register_username = ep_get_global_settings( 'register_username' );
        $args->register_username_show = 1;
        $args->register_username_label = esc_html__( 'Username', 'eventprime-event-calendar-management' );
        if( ! isset( $register_username['show'] ) || empty( $register_username['show'] ) ) {
            $args->register_username_show = 0;
        }
        
        if( isset( $register_username['label'] ) && ! empty( $register_username['label'] ) ) {
            $args->register_username_label = $register_username['label'];
        }
        // Register Block customer class
        if(! empty( $args->block_register_custom_class ) ){
            $args->block_register_class = $args->block_register_custom_class;
        }
        // Register block user name label update
        if( ! empty( $args->block_register_user_name_label ) ){
            $args->register_username_label = $args->block_register_user_name_label;
        }
        $args->register_username_mandatory = 1;
        if( ! isset( $register_username['mandatory'] ) || $register_username['mandatory'] == 0 ) {
            $args->register_username_mandatory = 0;
        }
        
        if(empty(ep_get_global_settings( 'login_registration_form' ))){
            $args->register_username_show = 1;
            $args->register_username_mandatory = 1;
        }
        // email settings
        $register_email = ep_get_global_settings( 'register_email' );
        $args->register_email_label = esc_html__( 'Email', 'eventprime-event-calendar-management' );
        if( isset( $register_email['label'] ) && ! empty( $register_email['label'] ) ) {
            $args->register_email_label = $register_email['label'];
        }
        // Register block user email label update
        if( ! empty( $args->block_register_user_email_label ) ){
            $args->register_email_label = $args->block_register_user_email_label;
        }
        // password settings
        $register_password = ep_get_global_settings( 'register_password' );
        $args->register_password_show = 1;
        $args->register_password_label = esc_html__( 'Password', 'eventprime-event-calendar-management' );
        if( ! isset( $register_password['show'] ) || $register_password['show'] == 0 ) {
            $args->register_password_show = 0;
            if( isset( $register_password['label'] ) && ! empty( $register_password['label'] ) ) {
                $args->register_password_label = $register_password['label'];
            }
        }
        $args->register_password_mandatory = 1;
        if( ! isset( $register_password['mandatory'] ) || $register_password['mandatory'] == 0 ) {
            $args->register_password_mandatory = 0;
        }
        
        if(empty(ep_get_global_settings( 'login_registration_form' ))){
            $args->register_password_show = 1;
        }
        // Register block password label update
        if( ! empty( $args->block_register_password_label ) ){
            $args->register_password_label = $args->block_register_password_label;
        }
        // repeat password settings
        $register_repeat_password = ep_get_global_settings( 'register_repeat_password' );
        $args->register_repeat_password_show = 1;
        $args->register_repeat_password_label = esc_html__( 'Repeat Password', 'eventprime-event-calendar-management' );
        if( ! isset( $register_repeat_password['show'] ) || $register_repeat_password['show'] == 0 ) {
            $args->register_repeat_password_show = 0;
            if( isset( $register_repeat_password['label'] ) && ! empty( $register_repeat_password['label'] ) ) {
                $args->register_repeat_password_label = $register_repeat_password['label'];
            }
        }
        $args->register_repeat_password_mandatory = 1;
        if( ! isset( $register_repeat_password['mandatory'] ) || $register_repeat_password['mandatory'] == 0 ) {
            $args->register_repeat_password_mandatory = 0;
        }
        // Register block repeat password label update
        if( ! empty( $args->block_register_repeat_password_label ) ){
            $args->register_repeat_password_label = $args->block_register_repeat_password_label;
        }
        // dob settings
        $register_dob = ep_get_global_settings( 'register_dob' );
        $args->register_dob_show = 1;
        $args->register_dob_label = esc_html__( 'Date of Birth', 'eventprime-event-calendar-management' );
        if( ! isset( $register_dob['show'] ) || $register_dob['show'] == 0 ) {
            $args->register_dob_show = 0;
            if( isset( $register_dob['label'] ) && ! empty( $register_dob['label'] ) ) {
                $args->register_dob_label = $register_dob['label'];
            }
        }
        $args->register_dob_mandatory = 1;
        if( ! isset( $register_dob['mandatory'] ) || $register_dob['mandatory'] == 0 ) {
            $args->register_dob_mandatory = 0;
        }

        // phone settings
        $register_phone = ep_get_global_settings( 'register_phone' );
        $args->register_phone_show = 1;
        $args->register_phone_label = esc_html__( 'Phone', 'eventprime-event-calendar-management' );
        if( ! isset( $register_phone['show'] ) || $register_phone['show'] == 0 ) {
            $args->register_phone_show = 0;
            if( isset( $register_phone['label'] ) && ! empty( $register_phone['label'] ) ) {
                $args->register_phone_label = $register_phone['label'];
            }
        }
        $args->register_phone_mandatory = 1;
        if( ! isset( $register_phone['mandatory'] ) || $register_phone['mandatory'] == 0 ) {
            $args->register_phone_mandatory = 0;
        }
        if(empty(ep_get_global_settings( 'login_registration_form' ))){
            $args->register_phone_show = 1;
        }
        // Register block phone label update
        if( ! empty( $args->block_register_phone_label ) ){
            $args->register_phone_label = $args->block_register_phone_label;
        }
        // timezone settings
        $register_timezone = ep_get_global_settings( 'register_timezone' );
        $args->register_timezone_show = 1;
        $args->register_timezone_label = esc_html__( 'Timezone', 'eventprime-event-calendar-management' );
        if( ! isset( $register_timezone['show'] ) || $register_timezone['show'] == 0 ) {
            $args->register_timezone_show = 0;
            if( isset( $register_timezone['label'] ) && ! empty( $register_timezone['label'] ) ) {
                $args->register_timezone_label = $register_timezone['label'];
            }
        }
        $args->register_button_label = 'Register';
        // Register block button label update
        if( ! empty( $args->block_register_button_label ) ){
            $args->register_button_label = $args->block_register_button_label;
        }
        $args->register_timezone_mandatory = 1;
        if( ! isset( $register_timezone['mandatory'] ) || $register_timezone['mandatory'] == 0 ) {
            $args->register_timezone_mandatory = 0;
        }
        $args->google_recaptcha_site_key = ep_get_global_settings('google_recaptcha_site_key');
        $args->register_google_recaptcha = 0;
        if( ep_get_global_settings( 'register_google_recaptcha' ) ){
            $args->register_google_recaptcha = 1;
        }

        $args->already_have_account_label = esc_html__( "Already have an account?", 'eventprime-event-calendar-management' );
        
        // block register don't have account label
        if( ! empty( $args->block_register_already__account_label ) ){
            $args->already_have_account_label = $args->block_register_already__account_label;
        }
        
        $args->login_button_label = esc_html__( 'Log in', 'eventprime-event-calendar-management' );
        $login_button_label = ep_get_global_settings( 'login_button_label' );
        
        if( !empty( $login_button_label ) ) {
            $args->login_button_label = $login_button_label;
        }
        $args->login_button_label = esc_html__( "Please Login", 'eventprime-event-calendar-management' );

         // block register please login label
         if( ! empty( $args->block_register_please__login_label ) ){
            $args->login_button_label = $args->block_register_please__login_label;
        }

        return $args;
    }
    
    public function ep_handle_login() {
        $result = array();
        $recaptcha = true;  
        if( ep_get_global_settings('login_google_recaptcha') == 1 ) {
            if( isset( $_POST['g-recaptcha-response'] ) && !empty( ep_get_global_settings( 'google_recaptcha_secret_key' ) ) ) {
            $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".ep_get_global_settings('google_recaptcha_secret_key')."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
                if(!$response['success']){
                    $recaptcha = false;
                    return $result = array(
                        'success' => 0,
                        'msg'     => esc_html__( 'Recaptcha validation failed.', 'eventprime-event-calendar-management' ),
                    );
                }
            }
            if(isset($_POST['g-recaptcha-response']) && empty(ep_get_global_settings('google_recaptcha_secret_key'))){
                $recaptcha = false;
                return $result = array(
                    'success' => 0,
                    'msg'     => esc_html__( 'Recaptcha validation failed.', 'eventprime-event-calendar-management' ),
                );
            }

        }
        if( $recaptcha && isset( $_REQUEST['ep-attendee-login-nonce'] ) && ! empty( $_REQUEST['ep-attendee-login-nonce'] ) ) {
            if ( isset( $_POST['user_name'], $_POST['password'] ) && wp_verify_nonce( $_REQUEST['ep-attendee-login-nonce'], 'ep-attendee-login' ) ) {
                try {
                    $login_data = array(
                        'user_login'    => trim( wp_unslash( $_POST['user_name'] ) ),
                        'user_password' => $_POST['password'],
                        'remember'      => isset( $_POST['rememberme'] )
                    );

                    $error = new WP_Error();$login_error = 0;
                    $error = apply_filters( 'ep_login_errors', $error, $login_data['user_login'], $login_data['user_password'] );
                   
                    if ( $error->get_error_code() ) {
                        $login_error = 1;
                        return $result = array(
                            'success' => 0,
                            'msg'     => $error->get_error_message(),
                        );
                    }
    
                    if ( empty( $login_data['user_login'] ) ) {
                        $login_error = 1;
                        return $result = array(
                            'success' => 0,
                            'msg'     => esc_html__( 'Username is required.', 'eventprime-event-calendar-management' ),
                        );
                    }

                    if ( empty( $login_data['user_password'] ) ) {
                        $login_error = 1;
                        return $result = array(
                            'success' => 0,
                            'msg'     => esc_html__( 'Password is required.', 'eventprime-event-calendar-management' ),
                        );
                    }

                    if( empty( $login_error ) ) {
                        // Login the user.
                        $user = wp_signon( apply_filters( 'ep_login_data', $login_data ), is_ssl() );
                        
                        if ( is_wp_error( $user ) ) {
                            return $result = array(
                                'success' => 0,
                                'msg'     => $user->get_error_message(),
                            );
                        } else {
                            if ( ! empty( $_POST['redirect'] ) ) {
                                $login_redirect = sanitize_text_field( $_POST['redirect'] );
                                if( 'no-redirect' == $login_redirect || 'off' == $login_redirect ) {
                                    $redirect = '';
                                } elseif( 'reload' == $login_redirect ) {
                                    $redirect = 'reload';
                                } else{
                                    $redirect = wp_unslash( $_POST['redirect'] );
                                }
                            } elseif ( wp_get_referer() ) {
                                $redirect = wp_get_referer();
                            } else {
                                $redirect = get_permalink( ep_get_global_settings( 'profile_page' ) );
                            }
                            return $result = array(
                                'success'  => 1,
                                'msg'      => esc_html__( 'Successfully logged in!', 'eventprime-event-calendar-management' ),
                                'user'     => $user,
                                'redirect' => $redirect,
                            );
                            //wp_redirect( wp_validate_redirect( apply_filters( 'ep_login_redirect', $redirect, $user ), get_permalink( ep_get_global_settings( 'profile_page' ) ) ) );
                            //exit;
                        }
                    }
                } catch (Exception $e) {
                    do_action( 'ep_login_process_failed' );
                    return $result = array(
                        'success' => 0,
                        'msg'     => esc_html__( 'Login failed.', 'eventprime-event-calendar-management' ),
                    );
                }
            }
        }
        
        return $result;
    }

    // Submit registration form
    public function ep_handle_registration() {
        $error = 1;
        $args = $this->get_register_options( new stdClass() );
        $recaptcha = true;  
        $result = array( 'success' => 0, 'msg' => 'Failed.' );
        if( ep_get_global_settings( 'register_google_recaptcha') == 1 ) {
            if( isset( $_POST['g-recaptcha-response'] ) && ! empty( ep_get_global_settings( 'google_recaptcha_secret_key' ) ) ) {
            $response = json_decode( file_get_contents( "https://www.google.com/recaptcha/api/siteverify?secret=".ep_get_global_settings( 'google_recaptcha_secret_key' )."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR'] ), true );
                if( ! $response['success'] ) {
                    $recaptcha = false;
                    $result = array(
                        'success' => 0,
                        'msg'     => esc_html__( 'Recaptcha validation failed.', 'eventprime-event-calendar-management' ),
                    );
                }
            }
            if( isset( $_POST['g-recaptcha-response'] ) && empty( ep_get_global_settings('google_recaptcha_secret_key') ) ) {
                $recaptcha = false;
                $result = array(
                    'success' => 0,
                    'msg'     => esc_html__( 'Recaptcha validation failed.', 'eventprime-event-calendar-management' ),
                );
            }
        }
        if( $recaptcha && isset( $_REQUEST['ep-attendee-register-nonce'] ) && ! empty( $_REQUEST['ep-attendee-register-nonce'] ) ) {
            if ( wp_verify_nonce( $_REQUEST['ep-attendee-register-nonce'], 'ep-attendee-register' ) ) {
                $username = isset($_POST['user_name']) ? sanitize_text_field($_POST['user_name']) : '';
                $email = sanitize_email( wp_unslash( $_POST['email'] ) );
                $password = isset($_POST['password']) ? sanitize_text_field($_POST['password']) : '';
                $re_password = isset($_POST['repeat_password']) ? sanitize_text_field($_POST['repeat_password']) : '';
                $dob = isset($_POST['dob']) ? sanitize_text_field($_POST['dob']) : '';
                $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
                $timezone = isset($_POST['timezone']) ? sanitize_text_field($_POST['timezone']) : '';
                
                // username validation
                if( ! empty( $args->register_username_show ) ) {
                    if( ! empty( $args->register_username_mandatory ) && empty( $username ) ) {
                        return $result = array(
                            'success' => 0,
                            'msg'     => esc_html__( 'Username is required!', 'eventprime-event-calendar-management' )
                        );
                    }
                    if ( self::ep_verify_user( $username ) ) {
                        return $result = array(
                            'success' => 0,
                            'msg'     => esc_html__( 'Username already exists!', 'eventprime-event-calendar-management' ),
                        );
                    }
                }
                // Email Validation
                if( ! is_email( $email ) ) {
                    return $result = array(
                        'success' => 0,
                        'msg'     => esc_html__( 'Email is not valid!', 'eventprime-event-calendar-management' )
                    );
                }
                if( empty( $email ) ) {
                    return $result = array(
                        'success' => 0,
                        'msg'     => esc_html__( 'Email is required!', 'eventprime-event-calendar-management' )
                    );
                }
                if ( self::ep_verify_user( $email ) ) {
                    return $result = array(
                        'success' => 0,
                        'msg' => esc_html__( 'Email is already exists!', 'eventprime-event-calendar-management' ),
                    );
                }
                
                // Password validation
                if( ! empty( $args->register_password_show ) ) {
                    if( ! empty( $args->register_password_mandatory ) && empty( $password ) ) {
                        return $result = array(
                            'success' => 0,
                            'msg'     => esc_html__( 'Password is required!', 'eventprime-event-calendar-management' )
                        );
                    }
                }
                // Repeat Password validation
                if( ! empty( $args->register_repeat_password_show ) ) {
                    if( ! empty( $args->register_password_mandatory ) && empty( $re_password ) ) {
                        return $result = array(
                            'success' => 0,
                            'msg'     => esc_html__( 'Repeat password is required!', 'eventprime-event-calendar-management' )
                        );
                    }
                    if( $password != $re_password ) {
                        return $result = array(
                            'success' => 0,
                            'msg'     => esc_html__( 'Repeat password does not match', 'eventprime-event-calendar-management' )
                        );
                    } 
                }
                
                //DOB validation
                if( ! empty( $args->register_dob_show ) ) {
                    if( ! empty( $args->register_dob_mandatory ) && empty( $dob ) ) {
                        return $result = array(
                            'success' => 0,
                            'msg'     => esc_html__( 'Date of birth is required!', 'eventprime-event-calendar-management' )
                        );
                    }
                }
                
                //Phone validation
                if( ! empty( $args->register_phone_show ) ) {
                    if( ! empty( $args->register_phone_mandatory ) && empty( $phone ) ) {
                        return $result = array(
                            'success' => 0,
                            'msg'     => esc_html__( 'Phone is required!', 'eventprime-event-calendar-management' )
                        );
                    }
                }
                
                //Timezone validation
                if( ! empty( $args->register_timezone_show ) ) {
                    if( ! empty( $args->register_timezone_mandatory ) && empty( $timezone ) ) {
                        return $result = array(
                            'success' => 0,
                            'msg'     => esc_html__( 'Timezone is required!', 'eventprime-event-calendar-management' )
                        );
                    }
                }

                $error = 0;
                try {
                    $pass_auto = 0;
                    $login_page = ep_get_global_settings( 'login_page' );
                    $form_link = '#';
                    if($login_page){
                        $form_link =  get_permalink($login_page);
                    }
                    $success_msg = $success_msg = __(sprintf( 'Successfully registered! For login <a href="%s" class=""> Click here</a>', $form_link ),'eventprime-event-calendar-management' );
                    
                    if( empty( $error ) ) {
                        if(empty($password)){
                            $success_msg = __(sprintf( 'Successfully registered! Please check your registered email id for login deatils. For login <a href="%s" class=""> Click here</a>', $form_link ),'eventprime-event-calendar-management' );
                            $password = wp_generate_password();
                            $pass_auto = 1;
                        }
                        $username = ( ! empty( $username ) ? $username : $email );
                        $new_customer = wp_create_user( $username, $password, $email );
                        if ( is_wp_error( $new_customer ) ) {
                            return $result = array(
                                'success' => 0,
                                'msg'     => esc_html( $new_customer->get_error_message() )
                            );
                        } else {
                            $user = get_user_by( 'ID', $new_customer );
                            if ($user) {
                                update_user_meta( $new_customer, 'dob', $dob );
                                update_user_meta( $new_customer, 'phone', $phone );
                                update_user_meta( $new_customer, 'ep_user_timezone_meta', $timezone );
                            }

                            do_action( 'ep_after_user_registration', $new_customer, $_POST );

                            /*$info['user_login'] = $user->user_login;
                            $info['user_password'] = $password;
                            $info['remember'] = true;
                            $user_signon = wp_signon( $info, false );
                            wp_set_current_user( $new_customer );
                            */
                            //Send Email notification on registration
                            $user_object = new stdClass();
                            $user_object->user_id = $new_customer;
                            $user_object->email = $email;
                            if($pass_auto){
                                $user_object->password = $password;
                            }
                            
                            EventM_Notification_Service::user_registration($user_object);
                            
                            if ( ! empty( $_POST['redirect'] ) ) {
                                $reg_redirect = sanitize_text_field( $_POST['redirect'] );
                                if( 'reload' == $reg_redirect ) {
                                    $redirect = 'reload';
                                } else{
                                    $redirect = wp_unslash( $_POST['redirect'] );
                                }
                            } else{
                                $redirect = '';
                            }

                            return $result = array(
                                'success' => 1,
                                'msg'     => $success_msg,
                                'redirect'=> $redirect
                            );
                            //wp_redirect( wp_validate_redirect( apply_filters( 'ep_registration_redirect', $redirect, $user ), get_permalink( ep_get_global_settings( 'profile_page' ) ) ) );
                            //exit;
                        }
                    }
                } catch (Exception $e) {
                    do_action( 'ep_registration_process_failed' );
                    return $result = array(
                        'success' => 0,
                        'msg'     => esc_html__( 'Registration Failed! ', 'eventprime-event-calendar-management' ),
                    );
                }
            }
        }
        return $result;
    }
    
    public static function ep_verify_user( $email ) {
        return ( username_exists( $email ) || email_exists( $email ) );
    }
    
    // handle user registration from checkout
    public function ep_checkout_registration($user_data){
        $pass_auto = 0;
        if(empty($user_data->password)){
            $password = wp_generate_password();
            $pass_auto = 1;
        }else{
            $password = $user_data->password;
        }
        $username = isset($user_data->username) && !empty($user_data->username) ? $user_data->username : $user_data->email;
        $new_customer = wp_create_user( $username, $password, $user_data->email );
        if ( is_wp_error( $new_customer ) ) {
            return 0;
                            
        } else {
            $user = get_user_by( 'ID', $new_customer );
            if ($user) {
                if(isset($user_data->fname) && !empty($user_data->fname)){
                    update_user_meta( $new_customer, 'first_name', $user_data->fname );
                }
                if(isset($user_data->lname) && !empty($user_data->lname)){
                    update_user_meta( $new_customer, 'last_name', $user_data->lname );
                }
            }
            $user_object = new stdClass();
            $user_object->user_id = $new_customer;
            $user_object->email = $user_data->email;
            if($pass_auto){
                $user_object->password = $password;
            }
            EventM_Notification_Service::user_registration($user_object);
            return $new_customer;              
        }
    }
}