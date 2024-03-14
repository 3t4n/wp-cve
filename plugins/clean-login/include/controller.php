<?php

class CleanLogin_Controller{
    function load(){
        add_action( 'template_redirect', array( $this, 'prevent_cache_login_form' ) );
        add_action( 'template_redirect', array( $this, 'controller' ) );
        add_action( 'cleanlogin_before_login_edit_form_container', array( $this, 'maybe_show_email_change_pending_notification' ) );
    }

    function prevent_cache_login_form(){
        if( is_user_logged_in() )
            return;

        if( !get_option('cl_enable_hash_in_login_page', false) )
            return;

        if( isset( $_GET['nocache_login'] ) && !empty( $_GET['nocache_login'] ) )
            return;

        if( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'login' )
            return;

        if( !CleanLogin_Shortcode::is_login_page() )
            return;

        global $wp;

        $redirect_url = trailingslashit( home_url( $wp->request ) );

        if ( ! empty( $_SERVER['QUERY_STRING'] ) ) { // WPCS: Input var ok.
            $redirect_url = add_query_arg( wp_unslash( $_SERVER['QUERY_STRING'] ), '', $redirect_url ); // WPCS: sanitization ok, Input var ok.
        }

        if ( ! get_option( 'permalink_structure' ) ) {
            $redirect_url = add_query_arg( $wp->query_string, '', $redirect_url );
        }

        $redirect_url = add_query_arg( 'nocache_login', time(), remove_query_arg( 'nocache_login', $redirect_url ) );

        wp_safe_redirect( $redirect_url, 307 );
    }

    function controller(){
        global $wp_query;
        global $wpdb;
        $cleanlogin_has_verified_nonce = false;
    
        if( isset( $_POST["clean_login_wpnonce"] ) ){
            $cleanlogin_has_verified_nonce = wp_verify_nonce( isset( $_POST["clean_login_wpnonce"] ) ? $_POST["clean_login_wpnonce"] : "", 'clean_login_wpnonce' );
        }
        
        if( !is_singular() )
            return;
        
        $post = $wp_query->get_queried_object();
        if( !$post || strpos( $post->post_content, 'clean-login' ) === false )
            return;
        
        $url = $this->url_cleaner( wp_get_referer() );

        // LOGIN
        if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'login' ) {
            $enable_gcaptcha = get_option( 'cl_gcaptcha' );
            if( $enable_gcaptcha && !$this->valid_gcaptcha() ){
                $url = add_query_arg( 'authentication', 'wrongcaptcha', $url );
            }
            else{
                $user = ( $cleanlogin_has_verified_nonce ) ? wp_signon() : new WP_Error( 'invalid_nonce', __( 'Invalid NONCE, please try again.', 'clean-login' ) );

                if ( is_wp_error( $user ) )
                    $url = add_query_arg( 'authentication', 'failed', $url );
                else {
                    // if the user is disabled
                    if( empty($user->roles) ) {
                        wp_logout();
                        $url = add_query_arg( 'authentication', 'disabled', $url );
                    }
                    else {
                        $url = get_option( 'cl_login_redirect', false) ? esc_url( apply_filters('cl_login_redirect_url', CleanLogin_Controller::get_translated_option_page('cl_login_redirect_url'), $user)): esc_url( add_query_arg( 'authentication', 'success', $url ) );

                        if( !empty( $_REQUEST['clean_login_redirect'] ) )
                            $url = $_REQUEST['clean_login_redirect'];

                        $url = apply_filters( 'login_redirect', $url, '', $user );
                    }
                }
            }
            
            wp_safe_redirect( $url );
        // LOGOUT
        } else if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'logout' ) {
            wp_logout();
            $url = esc_url( add_query_arg( 'authentication', 'logout', $url ) );
            
            wp_safe_redirect( $url );

        // EDIT profile
        } else if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' ) {
            $url = esc_url( add_query_arg( 'updated', 'success', $url ) );

            $current_user = wp_get_current_user();
            $userdata = array( 'ID' => $current_user->ID );

            $first_name = isset( $_POST['first_name'] ) ? sanitize_text_field( $_POST['first_name'] ) : '';
            $last_name = isset( $_POST['last_name'] ) ? sanitize_text_field( $_POST['last_name'] ) : '';
            $userdata['first_name'] = $first_name;
            $userdata['last_name'] = $last_name;
        
            $email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
            if ( ! $email || empty ( $email ) ) {
                $url = esc_url( add_query_arg( 'updated', 'wrongmail', $url ) );
            } elseif ( ! is_email( $email ) ) {
                $url = esc_url( add_query_arg( 'updated', 'wrongmail', $url ) );
            } elseif ( ( $email != $current_user->user_email ) && email_exists( $email ) ) {
                $url = esc_url( add_query_arg( 'updated', 'wrongmail', $url ) );
            } elseif( $email != $current_user->user_email ) {
                $this->send_confirmation_email( $email );
            }

            // check if password complexity is checked
            $enable_passcomplex = get_option( 'cl_passcomplex' );

            // password checker
            if ( isset( $_POST['pass1'] ) && ! empty( $_POST['pass1'] ) ) {
                if ( ! isset( $_POST['pass2'] ) || ( isset( $_POST['pass2'] ) && $_POST['pass2'] != $_POST['pass1'] ) ) {
                    $url = esc_url( add_query_arg( 'updated', 'wrongpass', $url ) );
                }
                else {
                    if( $enable_passcomplex && !$this->is_password_complex($_POST['pass1']) )
                        $url = esc_url( add_query_arg( 'updated', 'passcomplex', $url ) );
                    else
                        $userdata['user_pass'] = $_POST['pass1'];
                }
            }

            $user_id = ( $cleanlogin_has_verified_nonce ) ? wp_update_user( $userdata ) : new WP_Error( 'invalid_nonce', __( 'Invalid NONCE, please try again.', 'clean-login' ) );
            
            if ( is_wp_error( $user_id ) ) {
                $url = esc_url( add_query_arg( 'updated', 'failed', $url ) );
            }

            wp_safe_redirect( $url );

        // REGISTER a new user
        } else if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'register' ) {
            $user = 0;
            $enable_captcha = get_option( 'cl_antispam' );
            $enable_gcaptcha = get_option( 'cl_gcaptcha' );
            $create_standby_role = get_option( 'cl_standby' );
            $enable_passcomplex = get_option( 'cl_passcomplex' );
            $create_customrole = get_option( 'cl_chooserole' );
            $newuserroles = get_option ( 'cl_newuserroles' );
            $emailnotification = get_option ( 'cl_emailnotification' );
            $emailnotificationcontent = get_option ( 'cl_emailnotificationcontent' );
            $termsconditions = get_option( 'cl_termsconditions' );
            $emailusername = !get_option('cl_email_username');
            $singlepassword = !get_option('cl_single_password');
            $automaticlogin = get_option('cl_automatic_login', false);
            $nameandsurname = get_option('cl_nameandsurname', false);
            $emailvalidation = get_option('cl_emailvalidation', false);

            $successful_registration = false;

            $url = esc_url( add_query_arg( 'created', 'success', $url ) );

            //if nameandsurname is checked then get them
            if( $nameandsurname ) {
                $first_name = isset( $_POST['first_name'] ) ? sanitize_text_field( $_POST['first_name'] ) : '';
                $last_name = isset( $_POST['last_name'] ) ? sanitize_text_field( $_POST['last_name'] ) : '';
            }

            //if email as username is checked then use email as username
            if ( $emailusername )
                $username = isset( $_POST['username'] ) ? sanitize_user( $_POST['username'] ) : '';
            else 
                $username = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
            
            $email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
            $pass1 = isset( $_POST['pass1'] ) ? $_POST['pass1'] : '';
            
            if( $singlepassword )
                $pass2 = isset( $_POST['pass2'] ) ? $_POST['pass2'] : '';
            else
                $pass2 = isset( $_POST['pass1'] ) ? $_POST['pass1'] : '';

            $website = isset( $_POST['website'] ) ? sanitize_text_field( $_POST['website'] ) : '';
            $captcha = isset( $_POST['captcha'] ) ? sanitize_text_field( $_POST['captcha'] ) : '';

            if( !session_id() ) 
                session_start();
            
            if( !empty( $_SESSION['cleanlogin-captcha'] ) ) {
                $captcha_session = $_SESSION['cleanlogin-captcha'];
                unset($_SESSION['cleanlogin-captcha']);
            }
            else {
                $captcha_session = '';
            }

            $role = isset( $_POST['role'] ) ? sanitize_text_field( $_POST['role'] ) : '';
            $terms = isset( $_POST['termsconditions'] ) && !empty( $_POST['termsconditions'] );
            
            if( $termsconditions && !$terms )
                $url = esc_url( add_query_arg( 'created', 'terms', $url ) );
            else if( $enable_passcomplex && !$this->is_password_complex( $pass1 ) )
                $url = esc_url( add_query_arg( 'created', 'passcomplex', $url ) );
            else if ( $create_customrole && !in_array( $role, $newuserroles ) )
                $url = esc_url( add_query_arg( 'created', 'failed', $url ) );
            else if( ( $enable_captcha && $captcha != $captcha_session ) || ( $enable_gcaptcha && !$this->valid_gcaptcha() ) )
                $url = esc_url( add_query_arg( 'created', 'wrongcaptcha', $url ) );
            else if( $website != '.' )
                $url = esc_url( add_query_arg( 'created', 'created', $url ) );
            else if( $nameandsurname && $first_name == '' )
                $url = esc_url( add_query_arg( 'created', 'wrongname', $url ) );
            else if( $nameandsurname && $last_name == '' )
                $url = esc_url( add_query_arg( 'created', 'wrongsurname', $url ) );
            else if( $username == '' || username_exists( $username ) )
                $url = esc_url( add_query_arg( 'created', 'wronguser', $url ) );
            else if( $email == '' || !is_email( $email ) || apply_filters( 'clean_login_valid_email', false, $email ) )
                $url = esc_url( add_query_arg( 'created', 'wrongmail', $url ) );
            else if ( email_exists( $email ) )
                $url = esc_url( add_query_arg( 'created', 'emailexists', $url ) );
            else if ( $pass1 == '' || $pass1 != $pass2)
                $url = esc_url( add_query_arg( 'created', 'wrongpass', $url ) );
            else{
                $user_id = ( $cleanlogin_has_verified_nonce ) ? wp_create_user( $username, $pass1, $email ) : new WP_Error( 'invalid_nonce', __( 'Invalid NONCE, please try again.', 'clean-login' ) );
                
                if ( is_wp_error( $user_id ) )
                    $url = esc_url( add_query_arg( 'created', 'failed', $url ) );
                else {
                    $successful_registration = true;
                    $user = new WP_User( $user_id );

                    // email validation
                    if( $emailvalidation ) {
                        $user->set_role( '' );
                        // Send auth email
                        $url_msg = get_permalink();
                        $url_msg = esc_url( add_query_arg( array( 
                            'activate' => $user->ID,
                            'security' => wp_create_nonce( 'codection-security' ),
                        ), $url_msg ) );
                        
                        $blog_title = get_bloginfo();
                        $message = sprintf( __( "Use the following link to activate your account: <a href='%s'>activate your account</a>.<br/><br/>%s<br/>", 'clean-login' ), $url_msg, $blog_title );

                        $subject = "[$blog_title] " . __( 'Activate your account', 'clean-login' );

                        $email = apply_filters( "clean_login_email_validation_email", $email );
                        $message = apply_filters( "clean_login_email_validation_content", $message );
                        $subject = apply_filters( "clean_login_email_validation_subject", $subject );

                        if( apply_filters( "clean_login_send_email_validation", true ) ){
                            if( !wp_mail( $email, $subject , $message, array( 'Content-Type: text/html; charset=UTF-8' ) ) )
                                $url = esc_url( add_query_arg( 'created', 'failed', $url ) );    
                        }
                    
                        $url = esc_url( add_query_arg( 'created', 'success-link', $url ) );
                    }
                    else if( $create_customrole ){
                        $user->set_role( $role );
                        do_action( 'user_register', $user_id );
                    }
                    else if ( $create_standby_role ){
                        $user->set_role( 'standby' );
                    }
                    
                    if( $nameandsurname ) {
                        $userdata = array( 'ID' => $user_id );
                        $userdata['first_name'] = $first_name;
                        $userdata['last_name'] = $last_name;
                        wp_update_user( $userdata );
                    }

                    $adminemail = get_bloginfo( 'admin_email' );
                    $blog_title = get_bloginfo();

                    if ( $create_standby_role && !$emailvalidation )
                        $message = sprintf( __( "New user registered: %s <br/><br/>Please change the role from 'Stand By' to 'Subscriber' or higher to allow full site access", 'clean-login' ), $username );
                    else
                        $message = sprintf( __( "New user registered: %s <br/>", 'clean-login' ), $username );
                    
                    $subject = "[$blog_title] " . __( 'New user', 'clean-login' );
                    
                    $adminemail = apply_filters( "clean_login_admin_email_notification_email", $adminemail);
                    $message = apply_filters( "clean_login_admin_email_notification_content", $message);
                    $subject = apply_filters( "clean_login_admin_email_notification_subject", $subject);
                    
                    if( apply_filters( "clean_login_send_admin_email_notification", true ) ){
                        if( !wp_mail( $adminemail, $subject, $message, array( 'Content-Type: text/html; charset=UTF-8' ) ) ){
                            $url = esc_url( add_query_arg( 'sent', 'failed', $url ) );
                        }
                    }
                    
                    if( $emailnotification ) {
                        $emailnotificationcontent = str_replace("{username}", $username, $emailnotificationcontent);
                        $emailnotificationcontent = str_replace("{password}", $pass1, $emailnotificationcontent);
                        $emailnotificationcontent = str_replace("{email}", $email, $emailnotificationcontent);
                        $emailnotificationcontent = htmlspecialchars_decode($emailnotificationcontent);
                        
                        $emailnotificationcontent = apply_filters( "clean_login_email_notification_content", $emailnotificationcontent);
                        
                        if( !wp_mail( $email, $subject , $emailnotificationcontent, array( 'Content-Type: text/html; charset=UTF-8' ) ) ){
                            $url = esc_url( add_query_arg( 'sent', 'failed', $url ) );
                        }
                    }

                    do_action( 'cleanlogin_after_successful_registration', $user_id );
                }
            }

            // if automatic login is enabled then log the user in and redirect them, checking if it was successful or not,
            //  is not compatible with email validation feature. This had no meaning!
            if( $automaticlogin && $successful_registration && !$emailvalidation ) {
                $url = esc_url( CleanLogin_Controller::get_translated_option_page( 'cl_url_redirect' ) );
                wp_signon( array('user_login' => $username, 'user_password' => $pass1 ), false );
            }
            
            do_action( 'clean_login_register', $user);
            
            $register_redirect_url = self::get_url_register_redirect();
            if( $register_redirect_url )
                $url = $register_redirect_url;

            wp_redirect( $url );
            exit();

        // When a user click the activation link goes here to activate his/her account
        } else if ( isset( $_GET['activate'] ) ) {
            if ( !wp_verify_nonce( $_GET['security'], 'codection-security' ) )
                die( 'Failed security check, expired Activation Link due to duplication or date.' );

            $url = CleanLogin_Controller::get_login_url();
            $user = get_user_by( 'id', intval( $_GET['activate'] ) );
            
            if ( !$user ) {
                $url = esc_url( add_query_arg( 'authentication', 'failed-activation', $url ) );
            } else {
                $user->set_role( get_option('default_role') );
                $url = esc_url( add_query_arg( 'authentication', 'success-activation', $url ) );
            }
            
            wp_safe_redirect( $url );

        // RESTORE a password by sending an email with the activation link
        } else if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'restore' ) {
            $url = esc_url( add_query_arg( 'sent', 'success', $url ) );

            $username = isset( $_POST['username'] ) ? sanitize_user( $_POST['username'] ) : '';
            $website = isset( $_POST['website'] ) ? sanitize_text_field( $_POST['website'] ) : '';

            // Since 1.1 (get username from email if so)
            if ( is_email( $username ) ) {
                $userFromMail = get_user_by( 'email', $username );
                if ( $userFromMail == false )
                    $username = '';
                else
                    $username = $userFromMail->user_login;
            }

            // honeypot detection
            if( $website != '.' )
                $url = esc_url( add_query_arg( 'sent', 'sent', $url ) );
            else if( $username == '' || !username_exists( $username ) )
                $url = esc_url( add_query_arg( 'sent', 'wronguser', $url ) );
            else {
                $user = get_user_by( 'login', $username );

                $url_msg = get_permalink();
                $url_msg = esc_url( add_query_arg( 'restore', $user->ID, $url_msg ) );
                $url_msg = wp_nonce_url( $url_msg, $user->ID );

                $email = $user->user_email;
                $blog_title = get_bloginfo();
                $message = sprintf( __( "Use the following link to restore your password: <a href='%s'>restore your password</a> <br/><br/>%s<br/>", 'clean-login' ), $url_msg, $blog_title );

                $subject = "[$blog_title] " . __( 'Restore your password', 'clean-login' );
                
                $message = apply_filters( "clean_login_email_restoration_content", $message);
                $subject = apply_filters( "clean_login_email_restoration_subject", $subject);
                
                if( !wp_mail( $email, $subject , $message, array( 'Content-Type: text/html; charset=UTF-8' ) ) )
                    $url = esc_url( add_query_arg( 'sent', 'failed', $url ) );
            }

            wp_safe_redirect( $url );

        // When a user click the activation link goes here to RESTORE his/her password
        } else if ( isset( $_REQUEST['restore'] ) ) {
            $user_id = $_REQUEST['restore'];
            $retrieved_nonce = $_REQUEST['_wpnonce'];
            if ( !wp_verify_nonce($retrieved_nonce, $user_id ) )
                die( 'Failed security check, expired Activation Link due to duplication or date.' );

            $edit_url = CleanLogin_Controller::get_edit_url();
            // If edit profile page exists the user will be redirected there
            if( $edit_url != '' && !apply_filters( 'cl_force_generate_notify_new_password', false ) ) {
                wp_clear_auth_cookie();
                wp_set_current_user ( $user_id );
                wp_set_auth_cookie  ( $user_id );
                $url = $edit_url;
            // If not, a new password will be generated and notified
            } else {
                $url = CleanLogin_Controller::get_restore_password_url();
                $new_password = wp_generate_password();
                $user_id = wp_update_user( array( 'ID' => $user_id, 'user_pass' => $new_password ) );

                if ( is_wp_error( $user_id ) ) {
                    $url = esc_url( add_query_arg( 'sent', 'wronguser', $url ) );
                } else {
                    set_transient( 'cl_temporary_pass_' . $user_id, $new_password );
                    $url = add_query_arg( array( 'pass_changed' => 'true', 'user_id' => $user_id ), $url );
                }
            }

            wp_safe_redirect( $url );
        }
        // CONFIRM EMAIL CHANGE
        else if( isset( $_GET['newuseremail'] ) && !empty( $_GET['newuseremail'] ) ){
            $current_user = wp_get_current_user();
            $new_email = get_user_meta( $current_user->ID, '_new_email', true );
            if ( $new_email && hash_equals( $new_email['hash'], $_GET['newuseremail'] ) ) {
                $user             = new stdClass;
                $user->ID         = $current_user->ID;
                $user->user_email = esc_html( trim( $new_email['newemail'] ) );
                if ( is_multisite() && $wpdb->get_var( $wpdb->prepare( "SELECT user_login FROM {$wpdb->signups} WHERE user_login = %s", $current_user->user_login ) ) ) {
                    $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->signups} SET user_email = %s WHERE user_login = %s", $user->user_email, $current_user->user_login ) );
                }
                wp_update_user( $user );
                delete_user_meta( $current_user->ID, '_new_email' );
                wp_redirect( esc_url( add_query_arg( 'updated', 'emailchangedsuccess', CleanLogin_Controller::get_edit_url() ) ) );
            } else {
                wp_redirect( esc_url( add_query_arg( 'updated', 'failed', CleanLogin_Controller::get_edit_url() ) ) );
            }
        }
    }

    function url_cleaner( $url ) {
        $query_args = array( 'authentication', 'updated', 'created', 'sent', 'restore' );
        return esc_url( remove_query_arg( $query_args, $url ) );
    }

    function is_password_complex($candidate) {
        // The third parameter for preg_match_all became optional from PHP 5.4.0. but before it's mandatory
        $dummy = array();
        if (!preg_match_all('$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $candidate, $dummy))
            return false;
        return true;
    
        /* Explaining $\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$
        $ = beginning of string
        \S* = any set of characters
        (?=\S{8,}) = of at least length 8
        (?=\S*[a-z]) = containing at least one lowercase letter
        (?=\S*[A-Z]) = and at least one uppercase letter
        (?=\S*[\d]) = and at least one number
        (?=\S*[\W]) = and at least a special character (non-word characters)
        $ = end of the string */
    }

    function valid_gcaptcha() {
        $gcaptcha_par = isset( $_POST['g-recaptcha-response'] ) ? sanitize_text_field( $_POST['g-recaptcha-response'] ) : '';
        $remote_ip = $_SERVER["REMOTE_ADDR"];
        $secret_key_gcaptcha = get_option( 'cl_gcaptcha_secretkey' );
      
        if ($gcaptcha_par != '') {
          $request_gcaptcha = wp_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key_gcaptcha . '&response=' . $gcaptcha_par . '&remoteip=' . $remote_ip );
          $response_body_gcaptcha = wp_remote_retrieve_body( $request_gcaptcha );
          $result_gcaptcha = json_decode( $response_body_gcaptcha, true );
          return $result_gcaptcha['success'];
        }

        return false;
    }

    static function get_translated_option_page( $page, $param = false) {
        $url = get_option( $page, $param );
     
        if( !function_exists( 'icl_object_id' )) {
            return $url;
        } else {
            $pid = url_to_postid( $url );
            return get_permalink( icl_object_id( $pid, 'page', true, ICL_LANGUAGE_CODE ) );
        }
    }

    static function get_login_url(){
        $login_id = get_option( 'cl_login_id' );

        if( empty( $login_id ) ){
            $login_id = url_to_postid( get_option( 'cl_login_url' ) );
            
            if( !empty( $login_id ) )
                update_option( 'cl_login_url', $login_id );
        }

        if( function_exists('icl_object_id') ) {
            $login_id = apply_filters( 'wpml_object_id', $login_id, 'page', FALSE, ICL_LANGUAGE_CODE );
        }        

        return empty( $login_id ) ? '' : get_permalink( $login_id );
    }

    static function get_edit_url(){
        $edit_id = get_option( 'cl_edit_id' );

        if( empty( $edit_id ) ){
            $edit_id = url_to_postid( get_option( 'cl_edit_id' ) );
            
            if( !empty( $edit_id ) )
                update_option( 'cl_edit_id', $edit_id );
        }

        if( function_exists('cl_edit_id') ) {
            $edit_id = apply_filters( 'wpml_object_id', $edit_id, 'page', FALSE, ICL_LANGUAGE_CODE );
        }
        
        return empty( $edit_id ) ? '' : get_permalink( $edit_id );
    }

    static function get_restore_password_url(){
        $restore_id = get_option( 'cl_restore_id' );

        if( empty( $restore_id ) ){
            $restore_id = url_to_postid( get_option( 'cl_restore_url' ) );
            
            if( !empty( $restore_id ) )
                update_option( 'cl_restore_id', $restore_id );
        }

        if( function_exists('icl_object_id') ) {
            $restore_id = apply_filters( 'wpml_object_id', $restore_id, 'page', FALSE, ICL_LANGUAGE_CODE );
        }        

        return empty( $restore_id ) ? '' : get_permalink( $restore_id );
    }

    static function get_register_url(){
        $register_id = get_option( 'cl_register_id' );

        if( empty( $register_id ) ){
            $register_id = url_to_postid( get_option( 'cl_register_url' ) );
            
            if( !empty( $restore_id ) )
                update_option( 'cl_register_id', $restore_id );
        }
        
        if( function_exists('icl_object_id') ) {
            $register_id = apply_filters( 'wpml_object_id', $register_id, 'page', FALSE, ICL_LANGUAGE_CODE );
        }        

        return empty( $register_id ) ? '' : get_permalink( $register_id );
    }

    static function get_url_register_redirect(){
		if( get_option('cl_register_redirect', false) == '' )
			return false;
	
		return get_option('cl_register_redirect_url', false) ? esc_url( apply_filters( 'cl_register_redirect_url', CleanLogin_Controller::get_translated_option_page('cl_register_redirect_url' ) ) ): false;
	}

    function send_confirmation_email( $email ){
	    $current_user = wp_get_current_user();
        $hash = md5( $email . time() . wp_rand() );
        $new_user_email = array( 'hash' => $hash, 'newemail' => $email );
        update_user_meta( $current_user->ID, '_new_email', $new_user_email );

        $sitename = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

        $email_text = __(
            'Howdy ###USERNAME###,

You recently requested to have the email address on your account changed.

If this is correct, please click on the following link to change it:
###ADMIN_URL###

You can safely ignore and delete this email if you do not want to
take this action.

This email has been sent to ###EMAIL###

Regards,
All at ###SITENAME###
###SITEURL###'
        );
        
        $content = apply_filters( 'new_user_email_content', $email_text, $new_user_email );

        $content = str_replace( '###USERNAME###', $current_user->user_login, $content );
        $content = str_replace( '###ADMIN_URL###', add_query_arg( array( 'newuseremail' => $hash ), self::get_edit_url() ), $content );
        $content = str_replace( '###EMAIL###', $_POST['email'], $content );
        $content = str_replace( '###SITENAME###', $sitename, $content );
        $content = str_replace( '###SITEURL###', home_url(), $content );

        wp_mail( $email, sprintf( __( '[%s] Email Change Request' ), $sitename ), $content );
    }

    function maybe_show_email_change_pending_notification(){
        $pending_email_change = get_user_meta( get_current_user_id(), '_new_email', true );

        if( empty( $pending_email_change ) )
            return;
        
        echo "<div class='cleanlogin-notification no-disappear error'><p>". sprintf(__( 'Email change to new email %1$s is pending to confirm', 'clean-login' ), $pending_email_change['newemail'] ) ."</p></div>";
    }
}