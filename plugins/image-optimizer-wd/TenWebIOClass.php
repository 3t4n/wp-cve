<?php
namespace TenWebPluginIO;

use Tenweb_Authorization\Helper;
use TenWebIO\Init;

class TenWebIOClass
{
    private static $instance = null;
    private static $version = '6.0.65';

    private function __construct()
    {
        if ( !TENWEB_IO_HOSTED_ON_10WEB ) {
            add_action('admin_menu', array($this, 'adminMenu'));
        }
        add_action('admin_notices', array($this, 'notices'));
        add_action('admin_init', array($this, 'pluginActivationRedirect'));
        if (!empty($_GET['nonce']) && wp_verify_nonce($_GET['nonce'], 'io_10web_connection')) {
            add_action('in_admin_header', array('\TenWebPluginIO\Connect', 'connectToTenweb'));
        } else if (!empty($_GET["iowd_disconnect"]) && wp_verify_nonce($_GET['iowd_disconnect_nonce'], 'iowd_disconnect_nonce')) {
            add_action('admin_init', array('\TenWebPluginIO\Connect', 'disconnectFromTenweb'));
        } else if (!empty($_GET['new_connection_flow']) && !empty($_GET['connection_error']) && empty($_GET['old_connection_flow'])) {
            wp_redirect(Connect::getConnectionLink('sign-up', ['old_connection_flow' => 1]));
        }

        add_action('wp_ajax_iowd_onboarding_ajax', array($this, 'onBoarding'));
        add_action('wp_ajax_onboarding_step_change', array($this, 'onBoardingStepChange'));
        add_action('wp_ajax_iowd_get_google_page_speed', array('\TenWebPluginIO\Utils', 'getPageScore'));
        add_action('wp_ajax_iowd_install_booster', array('\TenWebPluginIO\Utils', 'installBooster'));
        add_action('pre_current_active_plugins', array($this, 'pluginsPageDeactivatePopup'));

    }

    /**
     * @return TenWebIOClass|null
     */
    public static function getInstance()
    {
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * @return void
     */
    public function adminMenu()
    {
        wp_enqueue_style('iowd-wpglobal-style', TENWEBIO_URL . '/assets/css/wpglobal_style.css', array(), TENWEBIO_VERSION);
        add_menu_page("Image Optimizer", "Image Optimizer", 'manage_options', TENWEBIO_PREFIX . '_dashboard', array($this, 'admin'), TENWEBIO_URL_IMAGES . "/logo_icon.svg",11);
        add_submenu_page('', __('App', 'tenweb-image-optimizer'), __('App', 'tenweb-image-optimizer'), 'manage_options', 'onboarding_' . TENWEBIO_PREFIX, array($this, 'onBoarding'));
        //     add_submenu_page(TENWEBIO_PREFIX . '_dashboard', 'Image Optimizer', 'Dashboard', 'manage_options', TENWEBIO_PREFIX . '_dashboard', array($this, 'admin'));
    }

    public function admin()
    {
        if (Connect::ImageOptimizerConnected()) {
            new \TenWebPluginIO\MainView;
        } else {
            new \TenWebPluginIO\SignUpView;
        }
    }

    public function notices()
    {
        $page = isset($_GET["page"]) ? $_GET["page"] : '';
        if (strpos($page, TENWEBIO_PREFIX) === false) {
            return;
        }
        $whitelist = array(
            '127.0.0.1',
            '::1'
        );
        if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
            echo "<div class='error'><p>" . __("Image optimizing is disabled on Localhost. Please install the plugin on a live server to optimize images.", TENWEBIO_PREFIX) . "</p></div>";
        }
        if (!class_exists("WP_REST_Controller")) {
            echo "<div class='error'><p>" . __("Image Optimizer plugin requires WordPress 4.7 or higher.", TENWEBIO_PREFIX) . "</p></div>";
        }
    }

    public function phpNotice()
    {
        echo "<div class='error'><p>" . __("This version of the Image optimizer plugin requires PHP 5.6.0 or higher.", TENWEBIO_PREFIX) . "</p><p>" . __("We recommend you to update PHP or ask your hosting provider to do that.", TENWEBIO_PREFIX) . "</p></div>";
    }

    /**
     * @return void
     */
    public static function activate()
    {
        $version = get_site_option(TENWEBIO_PREFIX . "_version");

        if ($version && version_compare($version, self::$version, '<')) {
            delete_site_option(TENWEBIO_PREFIX . "_version");
        }
        update_site_option(TENWEBIO_PREFIX . "_version", self::$version);
        if ( !TENWEB_IO_HOSTED_ON_10WEB ) {
            add_option('iowd_activation_redirect', true);
        }
        Helper::check_site_state(true, null, null, ['image-optimizer-wd' => 1]);
    }

    public static function checkPHPVersion()
    {
        if (version_compare(phpversion(), "5.5", '<=')) {
            if (!function_exists('deactivate_plugins')) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            add_action('admin_notices', array('\TenWebPluginIO\TenWebIOClass', 'phpNotice'));
            deactivate_plugins(TENWEBIO_MAIN_FILE);
        }
    }

    /**
     * Redirects the user after plugin activation
     */
    public function pluginActivationRedirect()
    {
        if (get_option('iowd_activation_redirect', false)) {
            delete_option('iowd_activation_redirect');
            //            $onboarding_step = get_option( 'iowd_onboarding_step', false );
            //            if ( ( $onboarding_step != "skipped" || $onboarding_step != "done") && !is_plugin_active('tenweb-speed-optimizer/tenweb_speed_optimizer.php' ) ) {
            //                exit(wp_safe_redirect(admin_url('admin.php?page=onboarding_' . TENWEBIO_PREFIX)));
            //            }
            exit(wp_safe_redirect(admin_url('admin.php?page=' . TENWEBIO_PREFIX . '_dashboard')));
        }
    }

    /* OnBoarding page views and actions */
    public function onBoarding()
    {
        require_once TENWEBIO_DIR . "/onBoarding/OnBoarding.php";
        new \TenWebPluginIO\OnBoarding();
    }

    /* Change onBoarding step ajax action */
    public function onBoardingStepChange()
    {
        $nonce = isset($_POST['iowd_nonce']) ? sanitize_text_field($_POST['iowd_nonce']) : '';
        if (!wp_verify_nonce($nonce, 'iowd_nonce')) {
            die;
        }
        $onboarding_step = isset($_POST['onboarding_step']) ? sanitize_text_field($_POST['onboarding_step']) : '';
        update_option('iowd_onboarding_step', $onboarding_step, 1);
        if ($onboarding_step == 'done') {
            $connect_link = \TenWebOptimizer\OptimizerUtils::get_tenweb_connection_link();
            wp_send_json_success(array(
                'status'              => 'success',
                'booster_connect_url' => esc_url($connect_link)
            ));
        } else {
            $this->OnBoarding();
            die;
        }
    }

    public static function siteState()
    {
        Helper::check_site_state(true);
    }

    public static function ioDeactivate()
    {
        Init::deactivate();
        Helper::check_site_state(true, null, null, ['image-optimizer-wd' => 0]);
    }

    public function pluginsPageDeactivatePopup()
    {
        if (Connect::ImageOptimizerConnected()) {
            new \TenWebPluginIO\DeactivatePopups('');
        }
    }
}
