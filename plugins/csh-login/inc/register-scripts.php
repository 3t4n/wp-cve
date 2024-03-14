<?php

//-----------------------------------admin jquery-----------------------------------------
add_action('admin_enqueue_scripts', 'cshlg_enqueue_admin_script');

function cshlg_enqueue_admin_script() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('cshlg_admin_script', CSHLOGIN_PLUGIN_ASSETS_URL.'js/admin-script.js', array(), false, false);
}

//-----------------------------------admin style CSS-----------------------------------
add_action('admin_enqueue_scripts', 'cshlg_enqueue_admin_styles');

function cshlg_enqueue_admin_styles() {
    wp_register_style('cshlg_admin_style', CSHLOGIN_PLUGIN_ASSETS_URL.'css/admin.css');
    wp_enqueue_style('cshlg_admin_style');
}

//---------------------------------Frontend jquery---------------------------------------
add_action('wp_enqueue_scripts', 'cshlg_enqueue_widget_script');

function cshlg_enqueue_widget_script() {
    //global AJAX variable.
    //label or place holder.
    global $cshlg_options;
    $display_labels = 'Labels';
    if (isset($cshlg_options['display_labels'])) {
        $display_labels = $cshlg_options['display_labels'];
    }

    $type_modal = 'Dropdown';
    if (isset($cshlg_options['type_modal'])) {
        $type_modal = $cshlg_options['type_modal'];
    }

    // Login redirect.
    $get_login_redirect = 'Home Page';
    $login_redirect = "";
    if (isset($cshlg_options['direct_url'])) {
        $get_login_redirect = $cshlg_options['direct_url'];
    }

    if ($get_login_redirect == 'Home Page') {
        $login_redirect = home_url( '/' );
    }

    if ($get_login_redirect == 'Current Page') {
        $login_redirect = "";
    }

    if ($get_login_redirect == 'Custom URL') {
        if (!empty($cshlg_options['custom_redirect'])) {
            $login_redirect = $cshlg_options['custom_redirect'];
        }
    }

    // Register redirect.
    $register_redirect = "";
    if (!empty($cshlg_options['registration_direct'])) {
        $register_redirect = $cshlg_options['registration_direct'];
    } else {
        $register_redirect = "";
    }
    
    $generated_pass = '';
    if (isset($cshlg_options['generated_pass'])) {
        $generated_pass = $cshlg_options['generated_pass'];
    }

    wp_enqueue_script('jquery');
    
    wp_enqueue_script('cshlg_jquery_validate', 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js');
    
    wp_enqueue_script('cshlg_widget_script', CSHLOGIN_PLUGIN_ASSETS_URL.'js/widget-script.js', array(), false, false);
    
    // Pass Data to Js.
    wp_localize_script('cshlg_widget_script', 'jsPassData', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'display_labels' => $display_labels,
        'type_modal' => $type_modal,
        'get_login_redirect' => $get_login_redirect,
        'login_redirect' => $login_redirect,
        'register_redirect' => $register_redirect,
        'generated_pass' => $generated_pass
        ));
}

//--------------------------------Frontend stye CSS-----------------------------------
add_action('wp_enqueue_scripts', 'cshlg_enqueue_widget_style');

function cshlg_enqueue_widget_style() {
    $fb_icon = CSHLOGIN_PLUGIN_ASSETS_URL.'img/facebook.png';
    wp_enqueue_style('widget_style', CSHLOGIN_PLUGIN_ASSETS_URL.'css/default.css');  
    wp_enqueue_style('layout1_style', CSHLOGIN_PLUGIN_ASSETS_URL.'css/layout1.css');

    global $cshlg_options;

    $dynamic_css = "";
    // insert css from database.
    //background color
    if ( !empty($cshlg_options['background_color']) ) {
        $background_color = $cshlg_options['background_color'];
        $dynamic_css .= ".login_dialog { background: {$background_color}; }";
    }

    //background picture
    if ( !empty($cshlg_options['background_upload'])) {
        $background_url = $cshlg_options['background_upload'];
        $dynamic_css .= " .login_dialog { background-image: url({$background_url}); } ";
    }

    //button color
    if ( !empty($cshlg_options['button_color'])) {
        $button_color = $cshlg_options['button_color'];
        $dynamic_css .= " .login_dialog input[type=submit] { background: {$button_color} !important; } ";
    }

    //font color
    if ( !empty($cshlg_options['font_color'])) {
        $font_color = $cshlg_options['font_color'];
        $dynamic_css .= " .login_dialog label, h2 { color: {$font_color} !important; } ";
    }

    //link color
    if ( !empty($cshlg_options['link_color'])) {
        $link_color = $cshlg_options['link_color'];
        $dynamic_css .= " .pass_and_register a { color: {$link_color} !important; } ";
    }

    wp_add_inline_style('layout1_style', $dynamic_css);  
}
// Ajax.
// Login submit action.
add_action('wp_ajax_nopriv_login_submit', 'cshlg_login_submit');

function cshlg_login_submit() {
    $username   = sanitize_user( $_POST['user'] );
    $password   = sanitize_text_field( $_POST['password'] );
    $rememberme = sanitize_text_field( $_POST['rememberme'] );
    if ($rememberme == 'forever') {
        $rememberme = true;
    } else {
        $rememberme = false;
    }
    // login
    $login_data                  = array();
    $login_data['user_login']    = $username;
    $login_data['user_password'] = $password;
    $login_data['remember']      = $rememberme;
    $user_verify                 = wp_signon($login_data, false);
    
    if (!is_wp_error($user_verify)) {
        $login_status = array(
            'login_status' => 'OK'
            );
        wp_send_json($login_status);
        wp_set_current_user($user_verify->ID);
    }  
}

// register submit action.
add_action('wp_ajax_nopriv_register_submit', 'cshlg_register_submit');

function cshlg_register_submit() {
    $register_user     = sanitize_user( $_POST['register_user'] );
    $register_email    = sanitize_email( $_POST['register_email'] );
    $password_register = sanitize_text_field( $_POST['password_register'] );
    $random_password   = wp_generate_password(8);
    $user_id           = username_exists($register_user);

    if (!$user_id and email_exists($register_email) == false and is_email($register_email)) {
        global $cshlg_options;

        $generated_pass = '';
        if (isset($cshlg_options['generated_pass'])) {
            $generated_pass = $cshlg_options['generated_pass'];
        }

        if ($generated_pass != "on") {
            $password_register = $random_password;
        }

        //Sent Email.
        $to       = $register_email;
        $blogname = get_bloginfo('name');
        $subject  = sprintf(__('[%s] Register Success!'), $blogname);   
        $body  = "Thank you for signing up on our site." . "\r\n\r\n";
        $body .= "Remember Username and Password as follows you can login to our site right now." . "\r\n\r\n";
        $body .= "Your Username :" . $register_user . "\r\n\r\n";
        $body .= "Your Password :" . $password_register . "\r\n\r\n";
 
        $sent_status = wp_mail($to, $subject, $body);

        if ($generated_pass == "on") {
            $user_id = wp_create_user($register_user, $password_register, $register_email);
            $register_status = array(
                'register_status' => 'OK'
                );
            wp_send_json($register_status);
        }else {
            if ($sent_status) {
                // Create user.
                $user_id = wp_create_user($register_user, $password_register, $register_email);
                $register_status = array(
                    'register_status' => 'OK'
                    );
                wp_send_json($register_status);
            }else{
                $register_status = array(
                    'register_status' => 'ERRORMAIL',
                    );
                wp_send_json($register_status);
            }
        }
    }
}

// lost pwd submit action.
add_action('wp_ajax_nopriv_lost_pwd_submit', 'cshlg_lost_pwd_submit');

function cshlg_lost_pwd_submit() {
    $user_data = "";
    if (is_email($_REQUEST['lost_pwd_user_email'])) {
        $user_data = sanitize_email($_REQUEST['lost_pwd_user_email']);
    }else {
        $user_data = sanitize_user($_REQUEST['lost_pwd_user_email']);
    }

    if (email_exists($user_data) || username_exists($user_data)) {
        global $wpdb, $current_site, $wp_hasher;
        
        if (strpos($user_data, '@')) {
            $user_data = get_user_by('email', trim($user_data));
        }else {
            $login     = trim($user_data);
            $user_data = get_user_by('login', $login);
        }
        
        // redefining user_login ensures we return the right case in the email
        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;
        
        $key = wp_generate_password(20, false);
        
        // Now insert the key, hashed, into the DB.
        if (empty($wp_hasher)) {
            require_once ABSPATH . WPINC . '/class-phpass.php';
            $wp_hasher = new PasswordHash(8, true);
        }

        $hashed    = time() . ':' . $wp_hasher->HashPassword($key);
        $key_saved = $wpdb->update($wpdb->users, array(
            'user_activation_key' => $hashed
            ), array(
            'user_login' => $user_login
            ));

        if (false === $key_saved) {
            return new WP_Error('no_password_key_update', __('Could not save password reset key to database.'));
        }
        
        $message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
        $message .= network_home_url('/') . "\r\n\r\n";
        $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
        $message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
        $message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
        $message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";
        
        if (is_multisite()) {
            $blogname = $GLOBALS['current_site']->site_name;
        }else {
            // The blogname option is escaped with esc_html on the way into the database in sanitize_option
            // we want to reverse this for the plain text arena of emails.
            $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        }
        
        $title = sprintf(__('[%s] Password Reset'), $blogname);
        $sent_status = wp_mail($user_email, $title, $message);
        
        if ($sent_status) {
            $lost_pwd_status = array(
                'lost_pwd_status' => 'OK'
                );
            wp_send_json($lost_pwd_status);
        }else{
            $lost_pwd_status = array(
                'lost_pwd_status' => 'ERRORMAIL',
                );
            wp_send_json($lost_pwd_status);
        }
    }
}

?>