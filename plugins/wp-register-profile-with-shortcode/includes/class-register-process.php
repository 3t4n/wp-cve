<?php

class Register_Process
{

    public function __construct()
    {
        add_action('init', array($this, 'register_validate'));
    }

    public function is_field_enabled($value)
    {
        $data = get_option($value);
        if ($data === 'Yes') {
            return true;
        } else {
            return false;
        }
    }

    public function is_field_required($value)
    {
        $data = get_option($value);
        if ($data === 'Yes') {
            return 'required="required"';
        } else {
            return '';
        }
    }

    public static function curPageURL()
    {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
        if (isset($_SERVER["SERVER_PORT"]) and $_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    public function create_user($data = array())
    {
        start_session_if_not_started();
        global $wprw_mail_to_user_subject, $wprw_mail_to_admin_subject;
        $wprw_admin_email = get_option('wprw_admin_email');
        $wprw_from_email = get_option('wprw_from_email');
        $wprw_success_msg = Register_Settings::removeslashes(get_option('wprw_success_msg'));

        if (empty($wprw_success_msg)) {
            $success_msg = __(Register_Settings::$wprw_success_msg, 'wp-register-profile-with-shortcode');
        } else {
            $success_msg = $wprw_success_msg;
        }

        if ($wprw_from_email == '') {
            $wprw_from_email = 'no-reply@wordpress.com';
        }

        $userdata = $data['userdata'];

        // insert new user in db //
        $user_id = wp_insert_user($userdata);
        // insert new user in db //

        // after insert action //
        do_action('wprp_after_insert_user', $user_id);
        // after insert action //

        // subscription action //
        do_action('cfws_subscription', $user_id, $data);
        // subscription action //

        $headers[] = 'From: <' . $wprw_from_email . '>';

        // send mail to user //
        $subject = Register_Settings::removeslashes(get_option('new_user_register_mail_subject'));
        $body = $this->new_user_data_to_user_mail($userdata);
        $body = html_entity_decode($body);

        $to_array = array($userdata['user_email']);
        add_filter('wp_mail_content_type', 'wprw_set_html_content_type');
        wp_mail($to_array, $subject, $body, $headers);
        remove_filter('wp_mail_content_type', 'wprw_set_html_content_type');
        // send mail to user //

        // send mail to admin //
        if (!empty($wprw_admin_email)) {
            $subject1 = __($wprw_mail_to_admin_subject, 'wp-register-profile-with-shortcode');
            $body1 = $this->new_user_data_to_admin_mail($userdata);
            $body1 = html_entity_decode($body1);
            add_filter('wp_mail_content_type', 'wprw_set_html_content_type');
            wp_mail($wprw_admin_email, $subject1, $body1, $headers);
            remove_filter('wp_mail_content_type', 'wprw_set_html_content_type');
        }
        // send mail to admin //

        if (!$this->is_field_enabled('force_login_after_registration') and $user_id) {
            $_SESSION['reg_error_msg'] = $success_msg;
            $_SESSION['reg_msg_class'] = 'reg_success';
        }

        unset($_SESSION['wp_register_temp_data']);
        return $user_id;
    }

    public function new_user_data_to_user_mail($userdata = array())
    {
        $wprw_mail_to_user_body = Register_Settings::removeslashes(get_option('new_user_register_mail_body'));

        $wprw_mail_to_user_body = str_replace(array("#site_name#", "#user_name#", "#user_password#", "#site_url#"), array(get_bloginfo('name'), $userdata['user_login'], $userdata['user_pass'], site_url()), $wprw_mail_to_user_body);
        return $wprw_mail_to_user_body;
    }

    public function new_user_data_to_admin_mail($userdata = array())
    {
        global $wprw_mail_to_admin_body;
        $data = '';

        if (!empty($userdata['user_login'])) {
            $data .= '<strong>' . __('User Name', 'wp-register-profile-with-shortcode') . ':</strong> ' . $userdata['user_login'];
            $data .= '<br>';
        }
        if (!empty($userdata['user_email'])) {
            $data .= '<strong>' . __('User Email', 'wp-register-profile-with-shortcode') . ':</strong> ' . $userdata['user_email'];
            $data .= '<br>';
        }
        if (!empty($userdata['first_name'])) {
            $data .= '<strong>' . __('First Name', 'wp-register-profile-with-shortcode') . ':</strong> ' . $userdata['first_name'];
            $data .= '<br>';
        }
        if (!empty($userdata['last_name'])) {
            $data .= '<strong>' . __('Last Name', 'wp-register-profile-with-shortcode') . ':</strong> ' . $userdata['last_name'];
            $data .= '<br>';
        }
        if (!empty($userdata['display_name'])) {
            $data .= '<strong>' . __('Display Name', 'wp-register-profile-with-shortcode') . ':</strong> ' . $userdata['display_name'];
            $data .= '<br>';
        }
        if (!empty($userdata['description'])) {
            $data .= '<strong>' . __('About User', 'wp-register-profile-with-shortcode') . ':</strong> ' . $userdata['description'];
            $data .= '<br>';
        }
        if (!empty($userdata['user_url'])) {
            $data .= '<strong>' . __('User URL', 'wp-register-profile-with-shortcode') . ':</strong> ' . $userdata['user_url'];
            $data .= '<br>';
        }

        $wprw_mail_to_admin_body = str_replace(array("#site_name#", "#new_user_data#", '#user_name#'), array(get_bloginfo('name'), $data, $userdata['user_login']), $wprw_mail_to_admin_body);

        return $wprw_mail_to_admin_body;
    }

    public function register_validate()
    {
        if (isset($_POST['option']) and sanitize_text_field($_POST['option']) == "wprp_user_register") {

            if (!wp_verify_nonce($_POST['wprp_5q5rt78'], 'wprp_action')) {
                wp_die('Error');
            }

            start_session_if_not_started();
            global $post;
            $error = false;
            $comp_errors = array();
            $msg = '';
            $_SESSION['wp_register_temp_data'] = $_POST;

            // validation compatibility filter //
            $default_registration_form_hooks = get_option('default_registration_form_hooks');
            if ($default_registration_form_hooks == 'Yes') {
                $comp_validation = apply_filters('registration_errors', $comp_errors, sanitize_text_field(@$_POST['user_login']), sanitize_text_field(@$_POST['user_email']));
                if (is_wp_error($comp_validation)) {
                    $msg .= __($comp_validation->get_error_message(), 'wp-register-profile-with-shortcode');
                    $msg .= '</br>';
                    $error = true;
                }
            }
            // validation compatibility filter //

            if ($this->is_field_enabled('captcha_in_registration')) {
                if (sanitize_text_field($_POST['user_captcha']) != $_SESSION['wprp_captcha_code']) {
                    $msg .= __('Security code do not match!', 'wp-register-profile-with-shortcode');
                    $msg .= '</br>';
                    $error = true;
                }
            }

            if (!$this->is_field_enabled('username_in_registration') and empty($_POST['user_login'])) {
                $_POST['user_login'] = sanitize_text_field($_POST['user_email']);
            }

            if (username_exists(sanitize_text_field($_POST['user_login']))) {
                $msg .= __('Username already exists. Please use a different one!', 'wp-register-profile-with-shortcode');
                $msg .= '</br>';
                $error = true;
            }

            if (email_exists(sanitize_text_field($_POST['user_email']))) {
                $msg .= __('Email already exists. Please use a different one!', 'wp-register-profile-with-shortcode');
                $msg .= '</br>';
                $error = true;
            }

            if ($this->is_field_enabled('password_in_registration')) {
                if ($_POST['new_user_password'] != $_POST['re_user_password']) {
                    $msg .= __('Password and Retype password do not match!', 'wp-register-profile-with-shortcode');
                    $msg .= '</br>';
                    $error = true;
                }
            }

            $userdata = array();

            if ($this->is_field_enabled('firstname_in_registration')) {
                if ($this->is_field_enabled('is_firstname_required') and sanitize_text_field($_POST['first_name']) == '') {
                    $msg .= __('Please enter first name', 'wp-register-profile-with-shortcode');
                    $msg .= '</br>';
                    $error = true;
                } else {
                    $userdata['first_name'] = sanitize_text_field($_POST['first_name']);
                }
            }

            if ($this->is_field_enabled('lastname_in_registration')) {
                if ($this->is_field_enabled('is_lastname_required') and sanitize_text_field($_POST['last_name']) == '') {
                    $msg .= __('Please enter last name', 'wp-register-profile-with-shortcode');
                    $msg .= '</br>';
                    $error = true;
                } else {
                    $userdata['last_name'] = sanitize_text_field($_POST['last_name']);
                }
            }

            if ($this->is_field_enabled('displayname_in_registration')) {
                if ($this->is_field_enabled('is_displayname_required') and sanitize_text_field($_POST['display_name']) == '') {
                    $msg .= __('Please enter display name', 'wp-register-profile-with-shortcode');
                    $msg .= '</br>';
                    $error = true;
                } else {
                    $userdata['display_name'] = sanitize_text_field($_POST['display_name']);
                }
            }

            if ($this->is_field_enabled('userdescription_in_registration')) {
                if ($this->is_field_enabled('is_userdescription_required') and sanitize_text_field($_POST['description']) == '') {
                    $msg .= __('Please enter description', 'wp-register-profile-with-shortcode');
                    $msg .= '</br>';
                    $error = true;
                } else {
                    $userdata['description'] = sanitize_text_field($_POST['description']);
                }
            }

            if ($this->is_field_enabled('userurl_in_registration')) {
                if ($this->is_field_enabled('is_userurl_required') and sanitize_text_field($_POST['user_url']) == '') {
                    $msg .= __('Please enter description', 'wp-register-profile-with-shortcode');
                    $msg .= '</br>';
                    $error = true;
                } else {
                    $userdata['user_url'] = sanitize_text_field($_POST['user_url']);
                }
            }

            if (!$error) {
                $userdata['user_login'] = sanitize_text_field($_POST['user_login']);
                $userdata['user_email'] = sanitize_text_field($_POST['user_email']);

                if ($this->is_field_enabled('password_in_registration') and $_POST['new_user_password'] != '') {
                    $new_pass = $_POST['new_user_password'];
                    $userdata['user_pass'] = $new_pass;
                } else {
                    $new_pass = wp_generate_password();
                    $userdata['user_pass'] = $new_pass;
                }

                $enable_cfws_newsletter_subscription = get_option('enable_cfws_newsletter_subscription');
                if ($enable_cfws_newsletter_subscription == 'Yes') {
                    $userdata['cf_subscribe_newsletter'] = sanitize_text_field($_POST['cf_subscribe_newsletter']);
                }

                if (get_option('enable_subscription') == 'Yes') {
                    $_SESSION['wp_register_subscription']['userdata'] = $userdata;
                    $_SESSION['wp_register_subscription']['sub_type'] = sanitize_text_field($_REQUEST['sub_type']);
                    $redirect_page = get_permalink(get_option('subscription_page'));
                    wp_redirect($redirect_page);
                    exit;
                } else {
                    $create_user_data['userdata'] = $userdata;
                    $user_id = $this->create_user($create_user_data);

                    if ($this->is_field_enabled('force_login_after_registration') and $user_id) {
                        $nuser = get_user_by('id', $user_id);
                        if ($nuser) {
                            wp_set_current_user($user_id, $nuser->user_login);
                            wp_set_auth_cookie($user_id);
                            do_action('wp_login', $nuser->user_login, $nuser);
                        }
                    }

                    $redirect_page = get_option('thank_you_page_after_registration_url');
                    if ($redirect_page) {
                        $redirect = get_permalink($redirect_page);
                    } else {
                        $redirect = sanitize_text_field($_REQUEST['redirect']);
                    }

                    wp_redirect($redirect);
                    exit;
                }
            } else {
                $_SESSION['reg_error_msg'] = $msg;
                $_SESSION['reg_msg_class'] = 'reg_error';
            }
        }
    }
}
