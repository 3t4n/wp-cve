<?php
class Register_Settings
{

    public static $wprw_success_msg = 'You are successfully registered to the site. Please check your email for login details.';

    public function __construct()
    {
        $this->load_settings();
    }

    public function register_widget_save_settings()
    {
        if (isset($_POST['option']) and sanitize_text_field($_POST['option']) == "register_widget_save_settings") {

            if (!wp_verify_nonce($_POST['wprp_58irt78'], 'wprp_admin_action')) {
                wp_die('Error');
            }

            start_session_if_not_started();
            global $wprp_default_options_data;

            if (!isset($_POST['register_widget_save_action_field']) || !wp_verify_nonce($_POST['register_widget_save_action_field'], 'register_widget_save_action')) {
                wp_die('Sorry, your nonce did not verify.');
            }

            if (is_array($wprp_default_options_data)) {
                foreach ($wprp_default_options_data as $key => $value) {
                    if (!empty($_REQUEST[$key])) {

                        if ($value['sanitization'] == 'sanitize_text_field') {
                            update_option($key, sanitize_text_field($_REQUEST[$key]));
                        } elseif ($value['sanitization'] == 'esc_html') {
                            update_option($key, esc_html($_REQUEST[$key]));
                        } elseif ($value['sanitization'] == 'esc_textarea') {
                            update_option($key, esc_textarea($_REQUEST[$key]));
                        } elseif ($value['sanitization'] == 'sanitize_text_field_array') {
                            $v = array_filter($_REQUEST[$key], 'sanitize_text_field');
                            update_option($key, $v);
                        } else {
                            update_option($key, sanitize_text_field($_REQUEST[$key]));
                        }
                    } else {
                        delete_option($key);
                    }
                }
            }

            do_action('wprp_save_settings');

            $_SESSION['msg'] = __('Plugin data updated successfully.', 'wp-register-profile-with-shortcode');
            $_SESSION['msg_class'] = 'updated';
        }
    }

    private function error_message()
    {
        start_session_if_not_started();
        if (isset($_SESSION['msg']) and $_SESSION['msg']) {
            echo '<div class="' . esc_attr($_SESSION['msg_class']) . '"><p>' . esc_html($_SESSION['msg']) . '</p></div>';
            unset($_SESSION['msg']);
            unset($_SESSION['msg_class']);
        }
    }

    public static function removeslashes($string)
    {
        $string = implode("", explode("\\", $string));
        return stripslashes(trim($string));
    }

    public function register_widget_options()
    {
        global $wpdb;

        $thank_you_page_after_registration_url = get_option('thank_you_page_after_registration_url');

        $username_in_registration = esc_attr(get_option('username_in_registration'));

        $password_in_registration = esc_attr(get_option('password_in_registration'));

        $firstname_in_registration = esc_attr(get_option('firstname_in_registration'));
        $firstname_in_profile = esc_attr(get_option('firstname_in_profile'));
        $is_firstname_required = esc_attr(get_option('is_firstname_required'));

        $lastname_in_registration = esc_attr(get_option('lastname_in_registration'));
        $lastname_in_profile = esc_attr(get_option('lastname_in_profile'));
        $is_lastname_required = esc_attr(get_option('is_lastname_required'));

        $displayname_in_registration = esc_attr(get_option('displayname_in_registration'));
        $displayname_in_profile = esc_attr(get_option('displayname_in_profile'));
        $is_displayname_required = esc_attr(get_option('is_displayname_required'));

        $userdescription_in_registration = esc_attr(get_option('userdescription_in_registration'));
        $userdescription_in_profile = esc_attr(get_option('userdescription_in_profile'));
        $is_userdescription_required = esc_attr(get_option('is_userdescription_required'));

        $userurl_in_registration = esc_attr(get_option('userurl_in_registration'));
        $userurl_in_profile = esc_attr(get_option('userurl_in_profile'));
        $is_userurl_required = esc_attr(get_option('is_userurl_required'));

        $wprw_success_msg = $this->removeslashes(esc_attr(get_option('wprw_success_msg')));

        $wprw_admin_email = esc_attr(get_option('wprw_admin_email'));
        $wprw_from_email = esc_attr(get_option('wprw_from_email'));
        $new_user_register_mail_subject = $this->removeslashes(esc_attr(get_option('new_user_register_mail_subject')));
        $new_user_register_mail_body = $this->removeslashes(esc_attr(get_option('new_user_register_mail_body')));

        $captcha_in_registration = esc_attr(get_option('captcha_in_registration'));
        $captcha_in_wordpress_default_registration = esc_attr(get_option('captcha_in_wordpress_default_registration'));
        $force_login_after_registration = esc_attr(get_option('force_login_after_registration'));

        $default_registration_form_hooks = esc_attr(get_option('default_registration_form_hooks'));

        $enable_cfws_newsletter_subscription = esc_attr(get_option('enable_cfws_newsletter_subscription'));

        $this->wrap_start();

        $this->error_message();

        $this->help_support();
        $this->login_sidebar_widget_add();

        include WPRPWS_DIR_PATH . '/view/admin/settings.php';

        $this->wp_register_pro_add();

        $this->donate();

        $this->wrap_end();
    }

    public static function wrap_start()
    {
        echo '<div class="wrap">';
    }

    public static function wrap_end()
    {
        echo '</div>';
    }

    public function register_widget_menu()
    {
        add_menu_page('Register Widget', 'WP Register Settings', 'activate_plugins', 'register_widget', array($this, 'register_widget_options'));
    }

    public function load_settings()
    {
        add_action('admin_menu', array($this, 'register_widget_menu'));
        add_action('admin_init', array($this, 'register_widget_save_settings'));
    }

    public function help_support()
    {
        include WPRPWS_DIR_PATH . '/view/admin/help-support.php';
    }

    public function login_sidebar_widget_add()
    {
        include WPRPWS_DIR_PATH . '/view/admin/login-add.php';
    }

    public function wp_register_pro_add()
    {
        include WPRPWS_DIR_PATH . '/view/admin/register-add.php';
    }

    public function donate()
    {
        include WPRPWS_DIR_PATH . '/view/admin/donate.php';
    }
}
