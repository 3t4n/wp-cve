<?php
/*
 * Plugin Name: Clickcease - Click Fraud Protection
 * Description: Plugin adds the ClickCease click fraud protection code.
 * Version: 3.2.4
 * Requires at least: 5.6
 * Requires PHP: 5.6
 * Author: ClickCease
 * Author URI: https://www.ClickCease.com
 */

if (!defined('ABSPATH')) {
    die();
}
define('clickcease_plugin_VERSION', '3.2.4');
define('clickcease_plugin_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('clickcease_plugin_PLUGIN_URL', plugin_dir_url(__FILE__));

add_action('plugins_loaded', ['WP_clickcease_plugin', 'init']);
register_activation_hook(__FILE__, ['WP_clickcease_plugin', 'activate_plugin']);
register_deactivation_hook(__FILE__,  ['WP_clickcease_plugin', 'deactivate_plugin']);
register_uninstall_hook(__FILE__, ['WP_clickcease_plugin', 'uninstall_plugin']);

const CC_PLUGIN_PAGE = 'clickcease-plugin-options';

/**
 * Provide settings fields
 *
 * @package clickcease_plugin
 */
class WP_clickcease_plugin
{
    /**
     * Plugin constructor.
     */
    public function __construct()
    {
        $whitelist = get_option('clickcease_whitelist', []);
        if (!current_user_can('publish_posts')) {
            add_action('wp_enqueue_scripts', [$this, 'add_stats_script'], -998);
            $client_ip = Utils::get_the_user_ip();
            if (!$client_ip || !in_array($client_ip, $whitelist)) {
                add_action('send_headers', [$this, 'clickcease_server_validation'], -999);
                add_action('wp_enqueue_scripts', [$this, 'enqueue_custom_scripts'], -999);
                add_action('wp_body_open', [$this, 'add_noscript_tag'], -999);
                add_action('wp_ajax_validate_clickcease_response', [$this, 'check_with_clickcease'], -999);
                add_action('wp_ajax_nopriv_validate_clickcease_response', [$this, 'check_with_clickcease'], -999);
            }
            if (!$client_ip)
                LogService::log("Server", "", "", "", "", "", "", "", ErrorCodes::NO_CLIENT_IP);
        } else {
            $this->init_clickcease_field_setting();
            $fetch_monitoring_data = $this->send_interval('cc-monitoring-date', '5 minutes');
            if ($fetch_monitoring_data) {
                $this->update_monitoring_status();
            }
            if ($whitelist)
                $this->send_whitelist_usage($whitelist);

            $plugin_latests_version  = $this->plugin_latests_version();
            $this->send_plugin_latest_version($plugin_latests_version);

            $this->send_plugin_state();
        }
    }

    public static function activate_plugin()
    {
        (new RTI_Service())->update_user_status(DomainState::PLUGIN_ACTIVATED);
        $botzappingAuth = get_option('clickcease_bot_zapping_authenticated', '');
        if ($botzappingAuth) (new RTI_Service())->update_user_status(DomainState::BZ_PLUGIN_ACTIVATED);
    }

    public static function deactivate_plugin()
    {
        (new RTI_Service())->update_user_status(DomainState::PLUGIN_DEACTIVATED);
    }

    public static function uninstall_plugin()
    {

        (new RTI_Service())->update_user_status(DomainState::BZ_PLUGIN_UNINSTALLED);
    }
    public static function init()
    {
        $class = __CLASS__;
        new $class();
    }

    public function clickcease_server_validation()
    {
        $rtiService = new RTI_Service();
        if (isset($_GET["clickcease"]) && $_GET["clickcease"] == "valid") {
            return;
        }

        global $wp;

        $clickcease_api_key = get_option('clickcease_api_key', '');
        $current_page = home_url($wp->request);
        $clickcease_domain_key = get_option('clickcease_domain_key', '');
        $botzappingAuth = get_option('clickcease_bot_zapping_authenticated', '');
        $secret_key = get_option('clickcease_secret_key', '');
        $invalid_secret = get_option('cheq_invalid_secret', '');
        $is_monitoring = get_option('monitoring', false);

        $this->check_keys($rtiService, $secret_key, $clickcease_api_key, $clickcease_domain_key);
        if ($clickcease_api_key && $clickcease_domain_key && $botzappingAuth && $secret_key && !$invalid_secret) {
            $validated = $rtiService->auth_with_rti($clickcease_api_key, $current_page, 'page_load', $clickcease_domain_key);
            if (!$is_monitoring && (!$validated['is_valid'] || (isset($_GET["clickcease"]) && ($_GET["clickcease"] == "block" || $_GET["clickcease"] == "clearhtml")))) {
                LogService::log(
                    "Server",
                    'blockuser',
                    isset($validated['output']->version) ? $validated['output']->version : '',
                    isset($validated['output']->isInvalid) ? $validated['output']->isInvalid : '',
                    isset($validated['output']->threatTypeCode) ? $validated['output']->threatTypeCode : '',
                    isset($validated['output']->requestId) ? $validated['output']->requestId : '',
                    isset($validated['output']->riskScore) ? $validated['output']->riskScore : '',
                    isset($validated['output']->setCookie) ? $validated['output']->setCookie : ''
                );
                header('Status: 403 Forbidden', true, 403);
                header('HTTP/1.0 403 Forbidden');
                exit();
            }
        } else {
            $logMsg =
                "clickcease_api_key : " .
                $clickcease_api_key .
                " ,clickcease_domain_key: " .
                $clickcease_domain_key .
                ",useBotzapping: " .
                $botzappingAuth .
                ",secret_key:" .
                $secret_key .
                ",invalid_secret: " .
                $invalid_secret;
            LogService::log("Server", "", "", "", "", "", "", "", ErrorCodes::NO_KEYS, $logMsg);
        }
    }

    public function check_keys($rtiService, $secret_key, $clickcease_api_key, $clickcease_domain_key)
    {
        $new_version_updated = get_option('cc_version_updated', '');

        if ($secret_key && !$new_version_updated) {
            update_option('cc_version_updated', true);
            $rtiService->auth_with_botzapping($clickcease_api_key, $clickcease_domain_key, $secret_key);
        }
    }
    private function send_whitelist_usage($whitelist)
    {
        $send_log = $this->send_interval('cc_white_list_send_date', '1 days');
        if ($send_log) {
            $log_data_str = json_encode($whitelist);
            LogService::log("Plugin", "", "", "", "", "", "", "", ErrorCodes::WHITELIST_TRACK, $log_data_str);
        }
    }
    public function cc_redirect($cc_get_value = 'invalid', $cc_get_key = 'clickcease')
    {
        if (strpos(Utils::getServerVariable('REQUEST_URI'), '?') === false) {
            return Utils::getServerVariable('REQUEST_URI') . '?' . $cc_get_key . '=' . $cc_get_value;
        } else {
            return Utils::getServerVariable('REQUEST_URI') . '&' . $cc_get_key . '=' . $cc_get_value;
        }
    }

    public function add_noscript_tag()
    {
        $removeClickFraud = filter_var(get_option('clickcease_remove_tracking'), FILTER_VALIDATE_BOOLEAN);

        if (!$removeClickFraud) { ?>
            <noscript>
                <a href="<?= Urls::CLICKCEASE ?>" rel="nofollow"><img src="<?= Urls::CLICKCEASE_MONITORING ?>/stats.aspx" alt="Clickcease" /></a>
            </noscript>
        <?php }

        $botzappingAuth = get_option('clickcease_bot_zapping_authenticated', '');
        $api_key = get_option('clickcease_domain_key');
        $domain = $this->get_active_domain($api_key);

        $baseUrl = $domain . '/ns';
        if ($botzappingAuth && $api_key) { ?>
            <noscript>
                <iframe src='<?= $baseUrl ?>/<?= $api_key ?>.html?ch=""' width='0' height='0' style='display:none'></iframe>
            </noscript>
        <?php }
    }

    // Validate request on Ajax
    public function check_with_clickcease()
    {
        $rtiService = new RTI_Service();

        return $rtiService->validateRTIClient();
    }

    public function add_stats_script()
    {
        $removeClickFraud = filter_var(get_option('clickcease_remove_tracking'), FILTER_VALIDATE_BOOLEAN);

        if (!$removeClickFraud) { ?>
            <script async src='<?= Urls::CLICKCEASE ?>/monitor/stat.js'>
            </script>
        <?php }
    }

    public function enqueue_custom_scripts()
    {
        $api_key = get_option('clickcease_domain_key');
        $botzappingAuth = get_option('clickcease_bot_zapping_authenticated', '');
        $domain = $this->get_active_domain($api_key);
        if ($botzappingAuth && $api_key) {
            wp_enqueue_script('clickceaseFrontEnd', plugin_dir_url(__FILE__) . 'includes/assets/js/front-end.js', ['jquery'], "1.0");
            wp_localize_script('clickceaseFrontEnd', 'ajax_obj', [
                'cc_nonce' => wp_create_nonce('cc_ajax_nonce'),
                'ajax_url' => admin_url('admin-ajax.php'),
                'ajax_action' => 'validate_clickcease_response',
            ]);
            $baseUrl = $domain . '/i';
        ?>

            <script async src='<?= $baseUrl ?>/<?= $api_key ?>.js' class='ct_clicktrue'></script>
<?php
        } else {
            LogService::log("Server", "", "", "", "", "", "", "", $errorCode = ErrorCodes::SCRIPT_NOT_INSTALLED);
        }
    }

    /**
     * Init ClickCease Plugin.
     */
    public function init_clickcease_field_setting()
    {
        // Add new admin menu options page for AP Setting.
        add_action('admin_menu', [$this, 'create_clickcease_plugin_options_page']);
        // Register ClickCease Plugin settings.
        add_action('admin_init', [$this, 'clickcease_admin_init'], 99);
    }

    /**
     * Admin init action with lowest execution priority
     */
    public function clickcease_admin_init()
    {
        // Admin Scripts.
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        $this->check_plugin_state();
    }

    /**
     * Create the ClickCease Plugin options page
     */
    public function create_clickcease_plugin_options_page()
    {
        add_menu_page(
            'ClickCease Plugin',
            'ClickCease Plugin',
            'manage_options',
            'clickcease-plugin-options',
            [$this, 'clickcease_plugin_options_page_html'],
            'dashicons-menu',
            150
        );
    }

    /**
     * Create the AP Settings options page HTML
     */
    public function clickcease_plugin_options_page_html()
    {
        // check user capabilities.
        if (current_user_can('manage_options')) {
            echo '<div class="wrap"><div id="wp-cc-plugin"></div></div>';
        }
    }

    /**
     * Load Admin scripts
     */
    public function admin_enqueue_scripts($hook)
    {
        $screen = get_current_screen();
        //no need to inject admin page to any admin pages only to our page
        if (strpos($screen->id, CC_PLUGIN_PAGE) !== false) {
            wp_enqueue_script('wp-cc', clickcease_plugin_PLUGIN_URL . '/build/static/js/main.bfb99e87.js', ['jquery', 'wp-element'], wp_rand(), true);

            wp_enqueue_style('clickcease-setting-style', plugins_url('/build/static/css/main.72f98d40.css', __FILE__), [], clickcease_plugin_VERSION);

            wp_localize_script('wp-cc', 'ajax_obj', [
                'nonce' => wp_create_nonce('wp_rest'),
                "ajax_url" => admin_url('admin-ajax.php'),
            ]);
        }
    }

    private function send_interval($option_name, $fetch_interval)
    {
        $fetched_data = false;
        $last_send_date = get_option($option_name, '');
        if ($last_send_date) {
            $next_fetch_date = date("Y-m-d H:i:s", strtotime($last_send_date . ' + ' . $fetch_interval));
            $date_now = date("Y-m-d H:i:s");
            if ($date_now >= $next_fetch_date) {
                $fetched_data = true;
                update_option($option_name, date("Y-m-d H:i:s"));
            }
        } else {
            update_option($option_name, date("Y-m-d H:i:s"));
            $fetched_data = true;
        }
        return $fetched_data;
    }

    private function get_active_domain($api_key)
    {
        $domain =  $domain = Urls::HTTPS . Urls::CHEQ_TAG;

        $fetch_active_domain = $this->send_interval('cc-active-domain', '1 days');
        if ($fetch_active_domain) {
            $temp_domain =   (new RTI_Service())->get_active_domain($api_key);
            if ($temp_domain)
                $domain = $temp_domain;
        }
        return $domain;
    }
    private function update_monitoring_status()
    {
        $rtiService = new RTI_Service();
        $clickcease_api_key = get_option('clickcease_api_key', '');
        $clickcease_domain_key = get_option('clickcease_domain_key', '');
        $secret_key = get_option('clickcease_secret_key', '');
        if ($clickcease_api_key && $clickcease_domain_key && $secret_key) {
            $isMonitoring = $rtiService->is_monitoring_with_botzapping($clickcease_api_key, $clickcease_domain_key, $secret_key);
            update_option('monitoring', $isMonitoring);
        }
    }

    private function send_plugin_latest_version($plugin_latests_version)
    {
        $send = $this->send_interval('cc_send_plugin_latest_version', '10 minutes');
        if ($send) {
            $botzappingAuth = get_option('clickcease_bot_zapping_authenticated', '');
            $client_id = get_option('clickcease_client_id', '');
            if ($botzappingAuth && $client_id !== '') {
                $rtiService = new RTI_Service();
                $clickcease_api_key = get_option('clickcease_api_key', '');
                $rtiService->update_bz_domain($clickcease_api_key, $client_id, $plugin_latests_version);
            }
        }
    }

    private function send_plugin_state()
    {
        $cc_send_plugin_state = get_option('cc_send_plugin_state', '');
        if (!$cc_send_plugin_state) {
            $botzappingAuth = get_option('clickcease_bot_zapping_authenticated', '');
            if ($botzappingAuth) (new RTI_Service())->update_user_status(DomainState::BZ_PLUGIN_ACTIVATED);
            else (new RTI_Service())->update_user_status(DomainState::PLUGIN_ACTIVATED);
            update_option("cc_send_plugin_state", "true");
        }
    }
    private function plugin_latests_version()
    {
        $res = true;
        $plugin_name = $this->get_plugin_name();
        if (!function_exists('get_plugin_updates')) {
            require_once(ABSPATH . 'wp-admin/includes/update.php');
        }
        $domains_need_update = get_plugin_updates();
        foreach ($domains_need_update as $domain) {
            if ($domain->Name === $plugin_name) {
                $res = false;
            }
        }
        return $res;
    }
    private function get_plugin_name()
    {
        if (!function_exists('get_plugin_data')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }
        $plugin_data = get_plugin_data(__FILE__);
        $plugin_name = $plugin_data['Name'];
        return $plugin_name;
    }
    public function check_plugin_state()
    {
        $check_plugin_state = get_option('cc_check_plugin_state', '');
        if (!$check_plugin_state && is_plugin_active(clickcease_plugin_PLUGIN_PATH)) {
            (new RTI_Service())->update_user_status(DomainState::PLUGIN_ACTIVATED);
            update_option('cc_check_plugin_state', true);
        }
    }
}

set_error_handler("error_handler");

function error_handler($errno, $errstr, $errfile, $errline)
{
    if (strpos($errfile, clickcease_plugin_PLUGIN_PATH) !== false) {
        $error_msg = "errorno:" . $errno . ",errstr:" . $errstr . ",errfile:" . $errfile . ",errline:" . $errline;
        LogService::log("Plugin", "", "", "", "", "", "", "", ErrorCodes::ERROR, $error_msg);
    }
    return true;
}

register_shutdown_function(function () {
    $err = error_get_last();
    if (!is_null($err)) {
        error_handler("", $err['message'], $err['file'], $err['line']);
    }
});

require_once clickcease_plugin_PLUGIN_PATH . 'classes/rtiService.php';
require_once clickcease_plugin_PLUGIN_PATH . 'classes/routes.php';
require_once clickcease_plugin_PLUGIN_PATH . 'classes/formService.php';
require_once clickcease_plugin_PLUGIN_PATH . 'classes/logService.php';
require_once clickcease_plugin_PLUGIN_PATH . 'classes/enums.php';
