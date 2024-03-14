<?php
defined('ABSPATH') || exit;

$inet_wk_options = get_option('inet_wk');

if (!empty($inet_wk_options['inet-webkit-remove-xml-rpc'])) {
    add_filter('xmlrpc_enabled', '__return_false');
}

if (!empty($inet_wk_options['inet-webkit-disable-copy-content'])) {
    add_action('wp_enqueue_scripts', 'disable_copy_content');
    function disable_copy_content()
    {
        wp_enqueue_script('inet-webkit-disable-copy', INET_WK_URL . 'assets/js/frontend/disable-copy.js', array('jquery'), '', true);
    }
}
if (!empty($inet_wk_options['inet-webkit-delete-link-head'])) {
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'start_post_rel_link', 10, 0);
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head, 10, 0');
}

//if(!empty($inet_wk_options['inet-webkit-recaptcha']['inet-webkit-recaptcha-site-key']) && !empty($inet_wk_options['inet-webkit-recaptcha']['inet-webkit-recaptcha-secret-key'])){
//    add_action('login_form', 'inetwk_display_login_captcha_form');
//    function inetwk_display_login_captcha_form(){
//        $inet_wk_options = get_option( 'inet_wk' );
//        $site_key = $inet_wk_options['inet-webkit-recaptcha']['inet-webkit-recaptcha-site-key'];
//        echo '<div class="g-recaptcha" data-badge="inline" data-sitekey="'.$site_key.'"></div>';
//    }
//    add_filter('wp_authenticate_user', 'inetwk_verify_login_captcha', 10, 2);
//    function inetwk_verify_login_captcha($user, $password){
//        $inet_wk_options = get_option( 'inet_wk' );
//        $secret_key = $inet_wk_options['inet-webkit-recaptcha']['inet-webkit-recaptcha-secret-key'];
//
//        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["g-recaptcha-response"])) {
//            $recaptcha_secret = $secret_key;
//            $response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=". $recaptcha_secret ."&response=". $_POST['g-recaptcha-response']);
//            $response = json_decode($response["body"], true);
//            if (true == $response["success"]) {
//                return $user;
//            } else {
//                return new WP_Error("Captcha Invalid", __("<strong>ERROR</strong>: Mã Captcha không đúng"));
//            }
//        } else {
//            return new WP_Error("Captcha Invalid", __("<strong>ERROR</strong>: Mã Captcha không đúng"));
//        }
//    }
//}
//
//if(!empty($inet_wk_options['inet-webkit-recaptcha']['inet-webkit-recaptcha-site-key']) && !empty($inet_wk_options['inet-webkit-recaptcha']['inet-webkit-recaptcha-secret-key'])){
//
//    add_action('login_enqueue_scripts', 'inetwk_login_recaptcha_script');
//    function inetwk_login_recaptcha_script(){
//        wp_register_script("recaptcha_api", "https://www.google.com/recaptcha/api.js?hl=" . get_locale());
//        wp_enqueue_script("recaptcha_api");
//
//    }
//
//}

if (!empty($inet_wk_options['inet-webkit-switcher-hide-wp-version'])) {
    function remove_version_info()
    {
        return '';
    }

    add_filter('the_generator', 'remove_version_info');
    function change_footer_admin()
    {
        return ' ';
    }

    add_filter('admin_footer_text', 'change_footer_admin', 9999);
    function change_footer_version()
    {
        return ' ';
    }

    add_filter('update_footer', 'change_footer_version', 9999);

}

if (!empty($inet_wk_options['inet-webkit-switcher-hide-menu-theme-plugin'])) {
    if (is_admin()) {
        add_filter('auto_update_core', '__return_false');
        add_filter('auto_update_translation', '__return_false');
        add_action('admin_menu', 'inet_wk_remove_menu_pages_admin');
        function inet_wk_remove_menu_pages_admin()
        {
            remove_menu_page('theme-editor.php');
            remove_menu_page('plugins.php');
        }

        if (!defined('DISALLOW_FILE_EDIT'))
            define('DISALLOW_FILE_EDIT', true);
        if (!defined('DISALLOW_FILE_MODS'))
            define('DISALLOW_FILE_MODS', true);
    }
}

if (!empty($inet_wk_options['inet-webkit-login-url']['inet-webkit-switcher-change-url-login'])) {

    if (!class_exists('inetwk_Security_Load_Settings')) {
        class inetwk_Security_Load_Settings
        {

            private $secureOptions;

            public function __construct()
            {

                $this->setup_vars();

                add_action('login_init', array($this, 'inetwk_hide_login_head'), 1);
                add_action('login_form', array($this, 'inetwk_hide_login_hidden_field'));
                add_action('template_redirect', array($this, 'inetwk_hide_login_init'));
                add_action('init', array($this, 'inetwk_hide_login_init'));
                add_filter('lostpassword_url', array($this, 'inetwk_hide_login_lostpassword'), 10, 0);
                add_action('lostpassword_form', array($this, 'inetwk_hide_login_hidden_field'));
                add_filter('lostpassword_redirect', array($this, 'inetwk_hide_login_lostpassword_redirect'), 100, 1);
            }

            function setup_vars()
            {
                $this->secureOptions = get_option('inet_wk');
            }

            function inetwk_hide_login_head()
            {
                $inetwk_slug = $this->secureOptions['inet-webkit-login-url']['inet-webkit-login-new-url'];
                if (isset($_POST['redirect_slug']) && $_POST['redirect_slug'] == $inetwk_slug) {
                    return false;
                }
                if (strpos($_SERVER['REQUEST_URI'], 'action=logout') !== false) {
                    check_admin_referer('log-out');
                    $user = wp_get_current_user();
                    wp_logout();
                    wp_safe_redirect(home_url(), 302);
                    die;
                }
                if ((strpos($_SERVER['REQUEST_URI'], $inetwk_slug) === false) &&
                    (strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false)) {
                    wp_safe_redirect(home_url('404'), 302);
                    exit();
                }
            }

            function inetwk_hide_login_hidden_field()
            {
                $inetwk_slug = $this->secureOptions['inet-webkit-login-url']['inet-webkit-login-new-url'];
                ?>
                <input type="hidden" name="redirect_slug" value="<?php echo esc_attr($inetwk_slug); ?>"/>
                <?php
            }

            function inetwk_hide_login_init()
            {
                $inetwk_slug = $this->secureOptions['inet-webkit-login-url']['inet-webkit-login-new-url'];

                if ('/' . $inetwk_slug == $_SERVER['REQUEST_URI']) {
                    wp_safe_redirect(home_url('wp-login.php?' . $inetwk_slug . '&redirect=false'));
                    exit();
                }
            }

            function inetwk_hide_login_lostpassword()
            {
                $inetwk_slug = $this->secureOptions['inet-webkit-login-url']['inet-webkit-login-new-url'];
                return site_url('wp-login.php?action=lostpassword&' . $inetwk_slug . '&redirect=false');
            }

            function inetwk_hide_login_lostpassword_redirect($lostpassword_redirect)
            {
                $inetwk_slug = $this->secureOptions['inet-webkit-login-url']['inet-webkit-login-new-url'];
                return 'wp-login.php?checkemail=confirm&redirect=false&' . $inetwk_slug;
            }

        }

        new inetwk_Security_Load_Settings;
    }
}

