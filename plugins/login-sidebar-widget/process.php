<?php

function login_validate() {
    if (isset($_POST['option']) and $_POST['option'] == "ap_user_login") {

        $nonce_check = (get_option('nonce_check_on_login') == 'Yes' ? true : false);
        if ($nonce_check) {
            if (!isset($_POST['login_widget_field']) || !wp_verify_nonce($_POST['login_widget_field'], 'login_widget_action')) {
                wp_die(__('Sorry, your nonce did not verify.', 'login-sidebar-widget'));
                exit;
            }
        }

        global $aperror;
        $lla = new Login_Log_Adds;
        $aperror = new WP_Error;

        if ($_POST['userusername'] != "" and $_POST['userpassword'] != "") {
            $creds = array();
            $creds['user_login'] = sanitize_text_field($_POST['userusername']);
            $creds['user_password'] = $_POST['userpassword'];

            if (isset($_POST['remember']) and $_POST['remember'] == "Yes") {
                $remember = true;
            } else {
                $remember = false;
            }
            $creds['remember'] = $remember;
            $user = wp_signon($creds, true);
            if (isset($user->ID) and $user->ID != '') {
                wp_set_auth_cookie($user->ID, $remember);
                $lla->log_add(apply_filters('lwws_log_ip', $_SERVER['REMOTE_ADDR']), 'Login success', date("Y-m-d H:i:s"), 'success');
                wp_redirect(apply_filters('lwws_login_redirect', sanitize_text_field($_POST['redirect']), $user->ID));
                exit;
            } else {
                $aperror->add("msg_class", "error_wid_login");
                $aperror->add("msg", __(get_login_error_message_text($user), 'login-sidebar-widget'));
                do_action('ap_login_log_front', $user);
            }
        } else {
            $aperror->add("msg_class", "error_wid_login");
            $aperror->add("msg", __('Username or password is empty!', 'login-sidebar-widget'));
            $lla->log_add(apply_filters('lwws_log_ip', $_SERVER['REMOTE_ADDR']), 'Username or password is empty', date("Y-m-d H:i:s"), 'failed');
        }
    }
}

function forgot_pass_validate() {

    if (isset($_GET['key']) && isset($_GET['action']) && sanitize_text_field($_GET['action']) == "reset_pwd") {
        global $wpdb;
        $reset_key = sanitize_text_field($_GET['key']);
        $user_login = sanitize_text_field($_GET['login']);
        $user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));

        $login_sidebar_widget_from_email = get_option('login_sidebar_widget_from_email');
        if ($login_sidebar_widget_from_email == '') {
            $login_sidebar_widget_from_email = 'no-reply@wordpress.com';
        }

        if (!empty($reset_key) && !empty($user_data)) {
            $user_login = $user_data->user_login;
            $user_email = $user_data->user_email;

            $new_password = wp_generate_password(7, false);
            wp_set_password($new_password, $user_data->ID);
            //mailing reset details to the user
            $headers = 'From: ' . get_bloginfo('name') . ' <' . $login_sidebar_widget_from_email . '>' . "\r\n";
            $message = nl2br(get_option('new_password_mail_body'));
            $message = str_replace(array('#site_url#', '#user_name#', '#user_password#'), array(site_url(), $user_login, $new_password), $message);
            $message = stripslashes(html_entity_decode($message));
            add_filter('wp_mail_content_type', 'lsw_set_html_content_type');
            if ($message && !wp_mail($user_email, stripslashes(get_option('new_password_mail_subject')), $message, $headers)) {
                wp_die(__('Email failed to send for some unknown reason.', 'login-sidebar-widget'));
                exit;
            } else {
                wp_die(__('New Password successfully sent to your email address.', 'login-sidebar-widget'));
                exit;
            }
            remove_filter('wp_mail_content_type', 'lsw_set_html_content_type');
        } else {
            wp_die(__('Not a valid key.', 'login-sidebar-widget'));
            exit;
        }
    }

    if (isset($_POST['option']) and sanitize_text_field($_POST['option']) == "ap_forgot_pass") {

        if (!isset($_POST['login_widget_field']) || !wp_verify_nonce($_POST['login_widget_field'], 'login_widget_action')) {
            wp_die(__('Sorry, your nonce did not verify.', 'login-sidebar-widget'));
            exit;
        }

        global $aperror;
        global $wpdb;
        $aperror = new WP_Error;

        $user_login = '';
        $user_email = '';
        $msg = '';

        if (empty($_POST['userusername'])) {
            $msg .= __('Email is empty!', 'login-sidebar-widget');
        }

        $user_username = esc_sql(trim(sanitize_text_field($_POST['userusername'])));

        $user_data = get_user_by('email', $user_username);
        if (empty($user_data)) {
            $msg .= __('Invalid E-mail address!', 'login-sidebar-widget');
        }

        if (isset($user_data->data->user_login) and isset($user_data->data->user_email)) {
            $user_login = $user_data->data->user_login;
            $user_email = $user_data->data->user_email;
        }

        if ($user_email) {
            $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
            if (empty($key)) {
                $key = wp_generate_password(10, false);
                $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
            }

            $login_sidebar_widget_from_email = get_option('login_sidebar_widget_from_email');
            if ($login_sidebar_widget_from_email == '') {
                $login_sidebar_widget_from_email = 'no-reply@wordpress.com';
            }

            //mailing reset details to the user
            $headers = 'From: ' . get_bloginfo('name') . ' <' . $login_sidebar_widget_from_email . '>' . "\r\n";
            $resetlink = site_url() . "?action=reset_pwd&key=$key&login=" . rawurlencode($user_login);
            $message = nl2br(get_option('forgot_password_link_mail_body'));
            $message = str_replace(array('#site_url#', '#user_name#', '#resetlink#'), array(site_url(), $user_login, $resetlink), $message);
            $message = stripslashes(html_entity_decode($message));
            add_filter('wp_mail_content_type', 'lsw_set_html_content_type');

            if (!wp_mail($user_email, stripslashes(get_option('forgot_password_link_mail_subject')), $message, $headers)) {
                $aperror->add("reg_msg_class", "error_wid_login");
                $aperror->add("reg_error_msg", __('Email failed to send for some unknown reason.', 'login-sidebar-widget'));
            } else {
                $aperror->add("reg_msg_class", "error_wid_login");
                $aperror->add("reg_error_msg", __('We have just sent you an email with Password reset instructions.', 'login-sidebar-widget'));
            }
            remove_filter('wp_mail_content_type', 'lsw_set_html_content_type');
        } else {
            $aperror->add("reg_msg_class", "error_wid_login");
            $aperror->add("reg_error_msg", $msg);
        }
    }
}