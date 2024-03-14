<?php
/**
 * Class for handle frontend forms.
 */

defined( 'ABSPATH' ) || exit;

class EventM_Form_Handler_Service {

    /**
     * Init Hooks
     */
    public static function init() {
        //add_action( 'wp_loaded', array( __CLASS__, 'ep_handle_login' ), 20 );
        //add_action( 'wp_loaded', array( __CLASS__, 'ep_handle_registration' ), 20 );
    }

    /**
     * Handle login form submission
     * 
     * @throws Exception on the login error 
     */
    public static function ep_handle_login() {
        $recaptcha = true;  
        if(ep_get_global_settings('login_google_recaptcha') ==1){
            if(isset($_POST['g-recaptcha-response']) && !empty(ep_get_global_settings('google_recaptcha_secret_key'))){
            $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".ep_get_global_settings('google_recaptcha_secret_key')."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
                if(!$response['success']){
                    $recaptcha = false;
                    EventM_Front_Notices_Service::ep_add_front_notice( 'error', esc_html__( 'Recaptcha validation failed.', 'eventprime-event-calendar-management' ) ) ;
                }
            }
            if(isset($_POST['g-recaptcha-response']) && empty(ep_get_global_settings('google_recaptcha_secret_key'))){
                $recaptcha = false;
                EventM_Front_Notices_Service::ep_add_front_notice( 'error', esc_html__( 'Recaptcha secret key missing.', 'eventprime-event-calendar-management' ) ) ;
            }

        }
        if( $recaptcha && isset( $_REQUEST['ep-attendee-login-nonce'] ) && ! empty( $_REQUEST['ep-attendee-login-nonce'] ) ) {
            if ( isset( $_POST['ep_login'], $_POST['user_name'], $_POST['password'] ) && wp_verify_nonce( $_REQUEST['ep-attendee-login-nonce'], 'ep-attendee-login' ) ) {
                try {
                    $login_data = array(
                        'user_login'    => trim( wp_unslash( $_POST['user_name'] ) ),
                        'user_password' => $_POST['password'],
                        'remember'      => isset( $_POST['rememberme'] )
                    );

                    $error = new WP_Error();$login_error = 0;
				    $error = apply_filters( 'ep_login_errors', $error, $login_data['user_login'], $login_data['user_password'] );
                    if ( $error->get_error_code() ) {
                        EventM_Front_Notices_Service::ep_add_front_notice( 'error', $error->get_error_message() ) ;
                        $login_error = 1;
                    }
    
                    if ( empty( $login_data['user_login'] ) ) {
                        EventM_Front_Notices_Service::ep_add_front_notice( 'error', esc_html__( 'Username is required.', 'eventprime-event-calendar-management' ) ) ;
                        $login_error = 1;
                    }

                    if ( empty( $login_data['user_password'] ) ) {
                        EventM_Front_Notices_Service::ep_add_front_notice( 'error', esc_html__( 'Password is required.', 'eventprime-event-calendar-management' ) ) ;
                        $login_error = 1;
                    }

                    if( empty( $login_error ) ) {
                        // Login the user.
                        $user = wp_signon( apply_filters( 'ep_login_data', $login_data ), is_ssl() );
                        
                        if ( is_wp_error( $user ) ) {
                            EventM_Front_Notices_Service::ep_add_front_notice( 'error', $user->get_error_message() );
                        } else {
                            if ( ! empty( $_POST['redirect'] ) ) {
                                $redirect = wp_unslash( $_POST['redirect'] );
                            } elseif ( wp_get_referer() ) {
                                $redirect = wp_get_referer();
                            } else {
                                $redirect = get_permalink( ep_get_global_settings( 'profile_page' ) );
                            }
        
                            wp_redirect( wp_validate_redirect( apply_filters( 'ep_login_redirect', $redirect, $user ), get_permalink( ep_get_global_settings( 'profile_page' ) ) ) );
                            exit;
                        }
                    }
                    
                } catch (Exception $e) {
                    do_action( 'ep_login_process_failed' );
                }
            }
        }
    }

    /**
     * Handle registration form submission
     * 
     * @throws Exception on the registration error 
     */
    public static function ep_handle_registration() {
        $recaptcha = true;  
        if(ep_get_global_settings('register_google_recaptcha') ==1){
            if(isset($_POST['g-recaptcha-response']) && !empty(ep_get_global_settings('google_recaptcha_secret_key'))){
            $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".ep_get_global_settings('google_recaptcha_secret_key')."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
                if(!$response['success']){
                    $recaptcha = false;
                    EventM_Front_Notices_Service::ep_add_front_notice( 'error', esc_html__( 'Recaptcha validation failed.', 'eventprime-event-calendar-management' ) ) ;
                }
            }
            if(isset($_POST['g-recaptcha-response']) && empty(ep_get_global_settings('google_recaptcha_secret_key'))){
                $recaptcha = false;
                EventM_Front_Notices_Service::ep_add_front_notice( 'error', esc_html__( 'Recaptcha secret key missing.', 'eventprime-event-calendar-management' ) ) ;
            }

        }
        if( $recaptcha && isset( $_REQUEST['ep-attendee-register-nonce'] ) && ! empty( $_REQUEST['ep-attendee-register-nonce'] ) ) {
            if ( isset( $_POST['ep_register'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['password'], $_POST['repeat_password'] ) && wp_verify_nonce( $_REQUEST['ep-attendee-register-nonce'], 'ep-attendee-register' ) ) {
                $email = sanitize_email( wp_unslash( $_POST['email'] ) );
                $password = $_POST['password'];
                try {
                    $error = 0;
                    if ( self::ep_verify_user( $email ) ) {
                        EventM_Front_Notices_Service::ep_add_front_notice( 'error', esc_html__( 'Username/Email is already exist!', 'eventprime-event-calendar-management' ) );
                        $error = 1;
                    }

                    if( $_POST['password'] !== $_POST['repeat_password'] ) {
                        EventM_Front_Notices_Service::ep_add_front_notice( 'error', esc_html__( 'Password and Repeat Password are not same!', 'eventprime-event-calendar-management' ) );
                        $error = 1;
                    }

                    if( empty( $error ) ) {
                        $new_customer = wp_create_user( $email, $password, $email );
                        if ( is_wp_error( $new_customer ) ) {
                            EventM_Front_Notices_Service::ep_add_front_notice( 'error', $new_customer->get_error_message() );
                        } else {
                            $user = get_user_by( 'ID', $new_customer );
                            if ($user) {
                                update_user_meta( $new_customer, 'first_name', sanitize_text_field( $_POST['first_name'] ) );
                                update_user_meta( $new_customer, 'last_name', sanitize_text_field( $_POST['last_name'] ) );
                                //update_user_meta( $new_customer, 'phone', $_POST['phone'] );
                            }

                            do_action('ep_after_user_registration', $new_customer);

                            $info['user_login'] = $user->user_login;
                            $info['user_password'] = $password;
                            $info['remember'] = true;
                            $user_signon = wp_signon( $info, false );
                            wp_set_current_user( $new_customer );

                            if ( ! empty( $_POST['redirect'] ) ) {
                                $redirect = wp_unslash( $_POST['redirect'] );
                            } elseif ( wp_get_referer() ) {
                                $redirect = wp_get_referer();
                            } else {
                                $redirect = get_permalink( ep_get_global_settings( 'profile_page' ) );
                            }
        
                            wp_redirect( wp_validate_redirect( apply_filters( 'ep_registration_redirect', $redirect, $user ), get_permalink( ep_get_global_settings( 'profile_page' ) ) ) );
                            exit;
                        }
                    }
                } catch (Exception $e) {
                    do_action( 'ep_registration_process_failed' );
                }
            }
        }
    }

    /**
     * Verify username
     */
    public static function ep_verify_user( $email ) {
        return ( username_exists( $email ) || email_exists( $email ) );
    }
}

EventM_Form_Handler_Service::init();