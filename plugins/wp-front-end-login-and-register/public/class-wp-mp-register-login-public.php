<?php
/**
 * The public-facing functionality of the plugin.
 *
 *
 * @package    Wp_Mp_Register_Login
 * @subpackage Wp_Mp_Register_Login/public
 * @author     Jenis Patel <jenis.patel@daffodilsw.com>
 */
require_once 'class-wp-mp-register-login-generic-public.php';

class Wp_Mp_Register_Login_Public extends Wp_Mp_Register_Login_Generic_Public
{

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
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wp-mp-register-login-public.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-formValidation', plugin_dir_url(__FILE__) . 'css/formValidation.min.css', array(), $this->version, 'all');
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wp-mp-register-login-public.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . '-bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . '-formValidation.min', plugin_dir_url(__FILE__) . 'js/validator/formValidation.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . '-bootstrap-validator', plugin_dir_url(__FILE__) . 'js/validator/bootstrap-validator.min.js', array('jquery'), $this->version, false);
        // localizing gloabl js objects
        wp_localize_script($this->plugin_name, 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    /**
     * Render the login form
     *
     * @since   1.0.0
     */
    public function wpmp_display_login_form()
    {
        ob_start();
        include_once 'partials/wpmp-login-form.php';
        return ob_get_clean();  
    }

    /**
     * Make the user login in the application
     *
     * @since   1.0.0
     */
    public function wpmp_user_login()
    {
        $wpmp_email_settings = get_option('wpmp_email_settings');
        $wpmp_messages_settings = get_option('wpmp_display_settings');
        $response = array();
        // checking post data
        if (isset($_POST) && $_POST) {

            $nonce = $_POST['wpmp_login_nonce'];
            // For security : verifying wordpress nonce
            if (!wp_verify_nonce($nonce, 'wpmp_login_action')) {
                _e('Failed security check !', $this->plugin_name);
                exit;
            }
            // preparing credentials array
            $credentials = array();
            $credentials['user_login'] = trim($_POST['wpmp_username']);
            $credentials['user_password'] = trim($_POST['wpmp_password']);

            // if email confirmation enabled
            if (isset($wpmp_email_settings['wpmp_user_email_confirmation']) && $wpmp_email_settings['wpmp_user_email_confirmation'] == '1') {
                // checking if user verified his email or not
                $this->wpmp_check_user_verified_email_or_not($credentials);
            }

            // auto login the user
            $user = wp_signon($credentials, false);
            // checking for authentication error
            if (is_wp_error($user)) {
                $response['error'] = __($wpmp_messages_settings['wpmp_login_error_message'], $this->plugin_name);
            } else {
                wp_set_auth_cookie($user->data->ID);
                // setting current logged in user
                wp_set_current_user($user->data->ID, $user->data->user_login);
                // Adding hook so that anyone can add action on user login
                do_action('set_current_user');
                $response['logged_in'] = true;
                $response['success'] = __($wpmp_messages_settings['wpmp_login_success_message'], $this->plugin_name);
                $response['redirection_url'] = $_POST['redirection_url'];
            }
            // sending back the response in right header
            wp_send_json($response);
        }
    }

    /**
     * Checking user has confirmed email address or not
     * 
     * @param array $credentials
     * @since   1.1.0
     * @author Jenis Patel
     */
    public function wpmp_check_user_verified_email_or_not($credentials)
    {
        //get message settings
        $wpmp_messages_settings = get_option('wpmp_display_settings');
        // getting user details
        $user = get_user_by('login', $credentials['user_login']);
        if (!$user->ID)  $user = get_user_by('email', $credentials['user_login']);
        if (!$user->ID) {
            $response['error'] = __('The username you have entered does not exist.', $this->plugin_name);
        } else {
            $stored_token = get_user_meta($user->ID, 'wpmp_email_verification_token', true);
            if (!$stored_token) {
                return true;
            } else {
                $response['error'] = __($wpmp_messages_settings['wpmp_account_notactivated_message'], $this->plugin_name);
            }
        }
        wp_send_json($response);
    }

    /**
     * Render the registration form and verify token if present
     *
     * @since   1.0.0
     */
    public function wpmp_display_register_form()
    {
        // verifing token if present
        ob_start();
        $token_verification = $this->wpmp_verify_token();
        include_once 'partials/wpmp-register-form.php';
        return ob_get_clean();  
    }

    /**
     * Send registration email notifications to users and admin
     * 
     * @param type array $userdata
     * @since 1.0.0
     * @author Neelkanth
     */
    public function wpmp_user_registration_mail($userdata)
    {
        $wpmp_email_settings = get_option('wpmp_email_settings');

        //prepare placeholder array

        $placeholders = array(
            '%USERNAME%' => $userdata['user_login'],
            '%BLOGNAME%' => get_option('blogname'),
            '%FIRSTNAME%' => $userdata['first_name'],
            '%LASTNAME%' => $userdata['last_name'],
            '%USEREMAIL%' => $userdata['user_email'],
            '%BLOGURL%' => "<a href='" . site_url() . "'>" . site_url() . "</a>",
            '%ACTIVATIONLINK' => ''
        );

        $to['admin'] = get_option('admin_email');
        $to['user'] = $userdata['user_email'];

        $subject['admin'] = get_option('blogname') . ' | New user registered';

        //Default subject
        $subject['user'] = 'Welcome to ' . get_option('blogname');

        //Subject when settngs are saved
        if (!empty($wpmp_email_settings['wpmp_notification_subject'])) {
            $subject['user'] = $wpmp_email_settings['wpmp_notification_subject'];
            //$subject['user'] = str_replace('%BLOGNAME%', get_option('blogname'), $subject['user']);
            $subject['user'] = Wp_Mp_Register_Login_Generic_Public::generic_placeholder_replacer($subject['user'], $placeholders);
        }

        // using content type html for emails
        $headers = array('Content-Type: text/html; charset=UTF-8');

        //Make body of admin message
        $userprofile = '<br><br><strong>' . __('First name : ') . '</strong>' . $userdata['first_name'];
        $userprofile.= '<br><strong>' . __('Last name : ') . '</strong>' . $userdata['last_name'];
        $userprofile.= '<br><strong>' . __('Username : ') . '</strong>' . $userdata['user_login'];
        $userprofile.= '<br><strong>' . __('Email : ') . '</strong>' . $userdata['user_email'];
        $userprofile.='<br><strong>' . __('Password :') . '</strong>' . __(' As choosen at time of registration');

        $message['admin'] = sprintf(__('A new user has registered on %s with following details:'), get_option('blogname'));

        //Email when settings are saved
        if (!empty($wpmp_email_settings['wpmp_notification_message'])) {
            $message['user'] = $wpmp_email_settings['wpmp_notification_message'];

            $message['user'] = Wp_Mp_Register_Login_Generic_Public::generic_placeholder_replacer($message['user'], $placeholders);
        }

        $footer['admin'] = '<br><br>' . __('Thanks.');

        $body['admin'] = $message['admin'] . $userprofile . $footer['admin'];

        $body['user'] = $message['user'];

        //sending email notification to admin
        if (!empty($wpmp_email_settings['wpmp_admin_email_notification'])) {
            wp_mail($to['admin'], $subject['admin'], $body['admin'], $headers);
        }
        //sending email notification to user
        $status = wp_mail($to['user'], $subject['user'], $body['user'], $headers);

        return $status;
    }

    /**
     * User registration
     * @return json
     * @since   1.0.0
     */
    public function wpmp_user_registration()
    {

        $wpmp_email_settings = get_option('wpmp_email_settings');
        $response = array();

        // checking post data
        if (isset($_POST) && $_POST) {

            $nonce = $_POST['wpmp_register_nonce'];
            // For security : verifying wordpress nonce
            if (!wp_verify_nonce($nonce, 'wpmp_register_action')) {
                _e('Failed security check !', $this->plugin_name);
                exit;
            }
            // preparing user array and added required filters
            $userdata = array(
                'user_login' => apply_filters('pre_user_login', trim($_POST['wpmp_username'])),
                'user_pass' => apply_filters('pre_user_pass', trim($_POST['wpmp_password'])),
                'user_email' => apply_filters('pre_user_email', trim($_POST['wpmp_email'])),
                'first_name' => apply_filters('pre_user_first_name', trim($_POST['wpmp_fname'])),
                'last_name' => apply_filters('pre_user_last_name', trim($_POST['wpmp_lname'])),
                'role' => get_option('default_role'),
                'user_registered' => date('Y-m-d H:i:s')
            );

            // creating new user
            $user_id = wp_insert_user($userdata);

            // checking for errors while user registration
            if (is_wp_error($user_id)) {
                $response['error'] = $user_id->get_error_message();
            } else {

                //adding current url in user data
                $userdata['current_url'] = $_POST['wpmp_current_url'];

                // Adding hook so that anyone can add action on user registration
                do_action('user_register', $user_id);

                $response['reg_status'] = true;
                //$response['success'] = __('Thanks for signing up. Please check your email for confirmation!', $this->plugin_name);
                //Sending user registration mails
                $email_confirmation_enabled = $wpmp_email_settings['wpmp_user_email_confirmation'];
                // check if admin opt for email confirmation
                if (isset($wpmp_email_settings['wpmp_user_email_confirmation']) && $wpmp_email_settings['wpmp_user_email_confirmation'] == '1') {
                    // creating and adding email verification token
                    $wpmp_email_verification_token = md5(wp_generate_password(26, true));
                    add_user_meta($user_id, 'wpmp_email_verification_token', $wpmp_email_verification_token);
                    $userdata['user_token'] = $wpmp_email_verification_token;

                    $mail_status = $this->wpmp_send_email_verification_token($userdata);
                    $response['success'] = __('Thanks for signing up. Please check your email for confirmation!', $this->plugin_name);
                } else {
                    $mail_status = $this->wpmp_user_registration_mail($userdata);
                    $response['success'] = __('You are successfully registered.', $this->plugin_name);
                }

                $response['redirection_url'] = $_POST['redirection_url'];
                $response['mail_status'] = $mail_status;
            }
            // sending back the response in right header
            wp_send_json($response);
        }
    }
    /*
     * update profile and add profile image
     * Load profile page html    
     * 
     */
    function wpmp_user_profile_page(){
        if ( is_user_logged_in() ) {
            ob_start();
            include_once 'partials/wpmp-profile.php';
            return ob_get_clean();  
        } else {
            wp_redirect(home_url());
            exit;
        }        
    }
    //update Profile
    public function updateProfile(){        
        // checking post data
        if (isset($_POST) && $_POST) {
            $nonce = $_POST['wpmp_profile_nonce'];
            // For security : verifying wordpress nonce
            if (!wp_verify_nonce($nonce, 'wpmp_profile_action')) {
                _e('Failed security check !', $this->plugin_name);
                exit;
            }
            // preparing user array and added required filters
            $userdata = array(
                'ID' => get_current_user_id(),               
                'user_email' => apply_filters('pre_user_email', trim($_POST['wpmp_email'])),
                'first_name' => apply_filters('pre_user_first_name', trim($_POST['wpmp_fname'])),
                'last_name' => apply_filters('pre_user_last_name', trim($_POST['wpmp_lname']))                
            );
            // creating new user
            $user_id = wp_update_user($userdata);
            //Upload profile Pic
            if($_FILES['wpmp_profile_pic']['size'] !=0 && $_FILES['wpmp_profile_pic']['error']==0){
                if ( ! function_exists( 'wp_handle_upload' ) ) {
                    require_once( ABSPATH . 'wp-admin/includes/file.php' );
                }
            $uploadedfile = $_FILES['wpmp_profile_pic'];
            $upload_overrides = array( 'test_form' => false );
            $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
                if ( $movefile && ! isset( $movefile['error'] ) ) {
        update_user_meta($user_id,'wpmp_profile_pic', $movefile['url']);
                }
            }            
            // checking for errors while user registration
            if (is_wp_error($user_id)) {
                $response['error'] = $user_id->get_error_message();
            } else {                
                $response['reg_status'] = true;
                $response['success'] = "Profile updated successfully";
            }
            // sending back the response in right header
            wp_send_json($response);
        }
    }
    /*
     * Send email confirmation link with token
     * @param array $userdata
     * @return bool
     * @since 1.1.0
     * @author Jenis Patel
     */

    public function wpmp_send_email_verification_token($userdata)
    {
        $wpmp_email_settings = get_option('wpmp_email_settings');
        // using content type html for emails
        $headers = array('Content-Type: text/html; charset=UTF-8');
        // configuring email options
        $to = $userdata['user_email'];
        $headers[] = 'From:' . get_option('blogname');

        $placeholders = array(
            '%USERNAME%' => $userdata['user_login'],
            '%BLOGNAME%' => get_option('blogname'),
            '%FIRSTNAME%' => $userdata['first_name'],
            '%LASTNAME%' => $userdata['last_name'],
            '%USEREMAIL%' => $userdata['user_email'],
            '%BLOGURL%' => "<a href='" . site_url() . "'>" . site_url() . "</a>",
            '%ACTIVATIONLINK%' => "<a target='_blank' href='" . esc_url(add_query_arg(array('email' => $userdata['user_email'], 'wpmp_email_verification_token' => $userdata['user_token']), $userdata['current_url'])) . "'>"
            . add_query_arg(array('email' => $userdata['user_email'], 'wpmp_email_verification_token' => $userdata['user_token']), $userdata['current_url']) . "</a>"
        );

        $subject = $wpmp_email_settings['wpmp_new_account_verification_email_subject'];
        $subject = Wp_Mp_Register_Login_Generic_Public::generic_placeholder_replacer($subject, $placeholders);

        $message = $wpmp_email_settings['wpmp_new_account_verification_email_message'];
        $message = Wp_Mp_Register_Login_Generic_Public::generic_placeholder_replacer($message, $placeholders);
        // sending confirmation email
        return wp_mail($to, $subject, $message, $headers);
    }
    /*
     * Verify user token
     * @param none
     * @since 1.1.0
     * @author Jenis Patel
     */

    public function wpmp_verify_token()
    {
        // checking email and token
        if (isset($_GET) && isset($_GET['wpmp_email_verification_token']) && $_GET['email']) {
            $user = get_user_by('email', $_GET['email']);

            if ($user->ID)
                $stored_token = get_user_meta($user->ID, 'wpmp_email_verification_token', true);
            if ($stored_token == $_GET['wpmp_email_verification_token']) {
                // preparing user data
                $userdata = array();
                $userdata['user_login'] = $user->data->user_login;
                $userdata['user_email'] = $user->data->user_email;
                $userdata['first_name'] = get_user_meta($user->ID, 'first_name', true);
                $userdata['last_name'] = get_user_meta($user->ID, 'last_name', true);
                // removing token on verification
                delete_user_meta($user->ID, 'wpmp_email_verification_token');
                // sending registration success email
                $this->wpmp_user_registration_mail($userdata);

                return true;
            }
        }

        return false;
    }
    /*
     * Render reset password form
     * @param none
     * @since 2.0.0
     * @author Neelkanth
     */

    public function wpmp_display_resetpassword_form()
    {
        ob_start();
        include_once 'partials/wpmp-resetpassword-form.php';
        return ob_get_clean();  
    }
    /*
     * Reset password
     * @param none
     * @since 2.0.0
     * @author Neelkanth
     */

    public function wpmp_resetpassword()
    {

        $nonce = $_POST['wpmp_resetpassword_nonce'];
        $response = array();
        // For security : verifying wordpress nonce
        if (!wp_verify_nonce($nonce, 'wpmp_resetpassword_action')) {
            _e('Failed security check !', $this->plugin_name);
            exit;
        }

        //read the custom messages settings
        $wpmp_messages = array();
        $wpmp_messages = get_option('wpmp_display_settings');

        // checking post data
        //user do not have a token
        if (isset($_POST) && empty($_POST['wpmp_reset_password_token'])) {

            //get user details from email            
            $user = get_user_by('email', $_POST['wpmp_rp_email']);
            //check user exists
            if ($user->ID > 0) {

                $wpmp_reset_password_token = '';
                //check if token already exists
                $token_exists = get_user_meta($user->ID, 'wpmp_reset_password_token', true);

                if ($token_exists) {
                    //send the old token
                    $wpmp_reset_password_token = $token_exists;
                } else {
                    //generate new token
                    $wpmp_reset_password_token = md5(wp_generate_password(26, true));
                    //add new token to usermeta
                    add_user_meta($user->ID, 'wpmp_reset_password_token', $wpmp_reset_password_token);
                }


                //create info to sent in the email
                $userdata = array();
                $userdata['current_url'] = $_POST['wpmp_current_url'];
                $userdata['user_email'] = $user->user_email;
                $userdata['wpmp_reset_password_token'] = $wpmp_reset_password_token;
                $userdata['user_login'] = $user->user_login;

                $mail_status = $this->wpmp_send_reset_password_token($userdata);

                if (true == $mail_status) {
                    $response['success'] = __($wpmp_messages['wpmp_password_reset_link_sent_message'], $this->plugin_name);
                    //create response
                    wp_send_json($response);
                } else {
                    $response['error'] = __($wpmp_messages['wpmp_password_reset_link_notsent_message'], $this->plugin_name);
                    wp_send_json($response);
                }
            } else {
                $response['error'] = __($wpmp_messages['wpmp_password_reset_invalid_email_message'], $this->plugin_name);

                wp_send_json($response);
            }
        }
        //the user already has the token
        else if (isset($_POST) && !empty($_POST['wpmp_reset_password_token'])) {

            $data = array();
            $data['wpmp_rp_email'] = $_POST['wpmp_rp_email'];
            $data['wpmp_reset_password_token'] = $_POST['wpmp_reset_password_token'];
            $data['wpmp_newpassword'] = $_POST['wpmp_newpassword'];

            $status = $this->wpmp_change_user_password($data);


            if ($status == true) {
                $response['success'] = __($wpmp_messages['wpmp_password_reset_success_message'], $this->plugin_name);
                wp_send_json($response);
            }
            if ($status == false) {
                $response['error'] = __($wpmp_messages['wpmp_invalid_password_reset_token_message'], $this->plugin_name);
                wp_send_json($response);
            }
        }
    }
    /*
     * Send password reset link with token
     * @param array $userdata
     * @return bool
     * @since 2.0.0
     * @author Neelkanth
     */

    public function wpmp_send_reset_password_token($userdata)
    {
        $wpmp_email_settings = get_option('wpmp_email_settings');
        // using content type html for emails
        $headers = array('Content-Type: text/html; charset=UTF-8');
        // configuring email options
        $to = $userdata['user_email'];
        $headers[] = 'From:' . get_option('blogname');

        //placeholders

        $placeholders = array(
            '%USERNAME%' => $userdata['user_login'],
            '%BLOGNAME%' => get_option('blogname'),
            '%USEREMAIL%' => $userdata['user_email'],
            '%BLOGURL%' => "<a href='" . site_url() . "'>" . site_url() . "</a>",
            '%RECOVERYLINK%' => "<a target='_blank' href='" . esc_url(add_query_arg(array('email' => $userdata['user_email'], 'wpmp_reset_password_token' => $userdata['wpmp_reset_password_token']), $userdata['current_url'])) . "'>"
            . add_query_arg(array('email' => $userdata['user_email'], 'wpmp_reset_password_token' => $userdata['wpmp_reset_password_token']), $userdata['current_url']) . "</a>"
        );

        //Default subject
        $subject = $wpmp_email_settings['wpmp_password_reset_email_subject'];
        $subject = Wp_Mp_Register_Login_Generic_Public::generic_placeholder_replacer($subject, $placeholders);
        $message = $wpmp_email_settings['wpmp_password_reset_email_message'];
        $message = Wp_Mp_Register_Login_Generic_Public::generic_placeholder_replacer($message, $placeholders);

        // sending confirmation email
        return wp_mail($to, $subject, $message, $headers);
    }
    /*
     * Verify user token, change the password and delete the usermeta
     * @param none
     * @since 2.0.0
     * @author Neelkanth
     */

    public function wpmp_change_user_password($data)
    {
        // checking email and token
        if (isset($data) && $data['wpmp_reset_password_token'] && $data['wpmp_rp_email']) {
            $user = get_user_by('email', $data['wpmp_rp_email']);

            if ($user->ID > 0) {
                $stored_token = get_user_meta($user->ID, 'wpmp_reset_password_token', true);
            }
            if ($stored_token == $data['wpmp_reset_password_token']) {
                // preparing user data
                $password = $data['wpmp_newpassword'];
                $password_reset = wp_set_password($password, $user->ID);

                // removing token on verification
                return delete_user_meta($user->ID, 'wpmp_reset_password_token');
            }
        }
        return false;
    }
}
