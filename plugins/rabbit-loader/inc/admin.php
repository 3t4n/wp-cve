<?php

class RabbitLoader_21_Admin
{

    public static $rabbitloader_cache_warnings = false;
    public static $admin_notice_shown = false;

    const PURGE_POST_CHANGE = "PURGE_POST";
    const PURGE_THEME_CHANGE = "PURGE_THEME";
    const PURGE_PLUG_CHANGE = "PURGE_PLUGIN";
    const PURGE_MANUAL_USER = "PURGE_USER";
    const SURVEY_DIS_PERMA = 999;

    public static function addActions()
    {
        add_action('admin_notices', 'RabbitLoader_21_Admin::admin_notices');
        add_action('admin_init', 'RabbitLoader_21_Admin::admin_init');
        add_action('network_admin_notices', 'RabbitLoader_21_Admin::admin_notices');
        add_action('admin_menu', 'RabbitLoader_21_Admin::leftMenuOption');
        add_action('enqueue_block_editor_assets', 'RabbitLoader_21_Admin::postSubmitButton');
        add_action('admin_enqueue_scripts', function () {
            wp_enqueue_script('rabbitloader-index', RABBITLOADER_PLUG_URL . 'admin/js/index.js', ['jquery'], RABBITLOADER_PLUG_VERSION);
            wp_localize_script('rabbitloader-index', 'rabbitloader_local_vars', [
                'admin_ajax' => admin_url('admin-ajax.php'),
                'hostname' => get_option('rabbitloader_field_domain'),
                'rl_nonce' => wp_create_nonce('rl-ajax-nonce')
            ]);
        });

        add_action('wp_ajax_rabbitloader_ajax_purge', function () {
            RL21UtilWP::verifyAjaxNonce();
            $response = [
                'result' => false,
                'lpc' => 0 //local purge count
            ];
            if (!empty($_POST['post_id'])) {
                $post_id = RabbitLoader_21_Util_Core::get_param('post_id', true);
                RL21UtilWP::onPostChange($post_id);
                $response['result'] = true;
            } else {
                RL21UtilWP::onPostChange(RL21UtilWP::POST_ID_ALL);
                $response['result'] = true;
            }
            RL21UtilWP::execute_purge($response['lpc']);
            delete_transient('rabbitloader_trans_overview_data');
            RabbitLoader_21_Core::sendJsonResponse($response);
        });

        add_action('wp_ajax_rabbitloader_mode_change', function () {
            RL21UtilWP::verifyAjaxNonce();

            if (!current_user_can('manage_options')) {
                #the use is not authorized to manage options
                wp_send_json_error(null, 403);
                return;
            }

            $response = [
                'result' => true
            ];

            $private_mode = !empty($_POST['private_mode']);
            RabbitLoader_21_Core::getWpUserOption($user_options);
            $user_options['private_mode_val'] = $private_mode;
            $user_options['private_mode_ts'] = date('c');
            RabbitLoader_21_Core::updateUserOption($user_options);

            try {
                //remove public pages cache, main purpose is to purge TPV
                RabbitLoader_21_TP::purge_all($tp_purge_count);
            } catch (\Throwable $e) {
                RabbitLoader_21_Core::on_exception($e);
            }

            RabbitLoader_21_Core::sendJsonResponse($response);
        });
        add_action('wp_ajax_rabbitloader_ajax_cron', function () {
            //self::deferred_exe();
            RL21UtilWP::verifyAjaxNonce();
        });
        // add_action('wp_ajax_rabbitloader_ajax_survey_dismissed', function () {
        //     RL21UtilWP::verifyAjaxNonce();
        //     self::survey_dismissed(self::SURVEY_DIS_PERMA);
        // });
        add_action('rl_site_connected', function () {
            self::rl_site_connected();
        });
        add_action('plugins_loaded', 'RabbitLoader_AD_AD::on_plugins_loaded');
        //listeners for taxonomy changes
    }

    public static function init()
    {
    }

    public static function leftMenuOption()
    {
        self::get_warnings($notification_count, false);
        add_menu_page(
            'RabbitLoader',
            $notification_count ? sprintf('RabbitLoader <span class="awaiting-mod">%d</span>', $notification_count) : 'RabbitLoader',
            'manage_options',
            'rabbitloader',
            'RabbitLoader_21_Tab_Init::echoPluginPage',
            dirname(plugin_dir_url(__FILE__)) . '/images/icon_16.png',
            //'',
            20
        );

        $page = RabbitLoader_21_Util_Core::get_param('page');

        if (strcmp($page, 'rabbitloader') === 0) {
            add_action('admin_head', 'admin_styles', 10, 1);
            function admin_styles($a)
            {
                echo '<link rel="stylesheet" href="' . RABBITLOADER_PLUG_URL . 'admin/css/bootstrap.v5.1.3.min.css' . '" type="text/css" media="all" />
                <link rel="stylesheet" href="' . RABBITLOADER_PLUG_URL . 'admin/css/style.css?v=' . RABBITLOADER_PLUG_VERSION . '" type="text/css" media="all" />';
            }
        }
        add_action('admin_head', function () {
            echo '<link rel="stylesheet" href="' . RABBITLOADER_PLUG_URL . 'admin/css/style-common.css?v=' . RABBITLOADER_PLUG_VERSION . '" type="text/css" media="all" />';
        }, 10, 1);
    }


    protected static function isPluginActivated()
    {

        return !empty(RabbitLoader_21_Core::getWpOptVal('api_token'));
    }

    public static function admin_notices()
    {

        //self::survey();

        try {
            $page = RabbitLoader_21_Util_Core::get_param('page');

            if (self::$admin_notice_shown || ($page == 'rabbitloader')) {
                return;
            }

            self::$admin_notice_shown = true;

            $plug_url = admin_url("admin.php?page=rabbitloader");


            if (!self::isPluginActivated()) {
                echo '
                <div class="notice notice-error is-dismissible"><p>';
                printf(RL21UtilWP::__('RabbitLoader is disconnected. Your pages are not optimized. <a href="%s">Click here to connect</a>'), $plug_url);
                echo '</p></div>';
            } else {
                self::get_warnings($notification_count, false);

                if ($notification_count > 0) {
                    echo '<div class="notice notice-error is-dismissible"><p>';
                    printf(RL21UtilWP::_n('RabbitLoader has %d warning which is affecting your website\'s optimizations. <a href="%s">check details</a>', 'RabbitLoader has %d warnings which may affect your website\'s optimizations. <a href="%s">check details</a>', $notification_count), $notification_count, $plug_url);
                    echo '</p></div>';
                }
            }
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        } catch (Exception $e) {
            RabbitLoader_21_Core::on_exception($e);
        }
    }

    private static function survey()
    {
        $showSurvey = false;
        $integration_start_time = RabbitLoader_21_Core::getWpOptVal('token_update_ts');
        if (!empty($integration_start_time)) {
            $maxTimeSec = 1 * 3600; //after 1hour
            $elapsedTimeSec = time() - $integration_start_time;
            $showSurvey =  $elapsedTimeSec > $maxTimeSec;
        }

        if (!$showSurvey) {
            return;
        }

        $user_id = get_current_user_id();
        if (empty($user_id)) {
            return;
        }
        //delete_user_meta($user_id, 'rl_survey_dismissed');
        $dismiss_time = intval(get_user_meta($user_id, 'rl_survey_dismissed', true));
        if (self::SURVEY_DIS_PERMA === $dismiss_time) {
            //permanently dismissed
            return;
        } else if (!empty($dismiss_time) && ($dismiss_time > strtotime('-7 days'))) {
            //dismiss within last 7 days
            return;
        }

        $remindLaterURL = add_query_arg('rl_survey_dismissed', time());
        $remindNeverURL = add_query_arg('rl_survey_dismissed', self::SURVEY_DIS_PERMA);
        echo '<div class="notice notice-info is-dismissible rl_survey_notice" style="background: #f4f4f4; color: #1d2327; border-width: 1px; border-style: solid; border-color: #1d2327; padding: 1rem 1rem; border-radius: 5px;"><div style="float:left; padding-right:1rem;"><img src="' . RABBITLOADER_PLUG_URL . '/assets/icon-dark.svg" width="100" /></div>';
        echo '<p class="p1">';
        RL21UtilWP::_e("Enjoying RabbitLoader? 🚀");
        echo '</p>';
        echo '<p>';
        RL21UtilWP::_e('Share your thoughts in a quick 10-second anonymous survey. Your feedback helps us hop forward! 🐇');
        echo '</p>';
        echo '<p class="p" style="margin-top: 1.5rem;"><button id="rl_show_survey" class="rl-btn rl-btn-primary mt-2 mb-sm-0">';
        RL21UtilWP::_e('Yes, Continue');
        echo '</button> <a href="' . $remindLaterURL  . '" class="rl-btn" style="color:#6b71fb;">';
        RL21UtilWP::_e('Ask me later');
        echo '</a><a href="' . $remindNeverURL . '" class="rl-btn" style="color:#6b71fb;">';
        RL21UtilWP::_e('I already did');
        echo '</a></p>';
        echo '</div>';
    }

    // private static function survey_dismissed($forceTime)
    // {
    //     $user_id = get_current_user_id();
    //     if (empty($user_id)) {
    //         wp_send_json_error(null, 403);
    //     }
    //     if (isset($_GET['rl_survey_dismissed'])) {
    //         delete_user_meta($user_id, 'rl_survey_dismissed');
    //         update_user_meta($user_id, 'rl_survey_dismissed', intval($_GET['rl_survey_dismissed']), false);
    //     }
    //     if ($forceTime) {
    //         delete_user_meta($user_id, 'rl_survey_dismissed');
    //         update_user_meta($user_id, 'rl_survey_dismissed', $forceTime, false);
    //     }
    // }
    public static function admin_init()
    {
        // if (isset($_GET['rl_survey_dismissed'])) {
        //     self::survey_dismissed(0);
        // }
    }

    protected static function get_warnings(&$count, $print)
    {

        if (self::$rabbitloader_cache_warnings === false) {

            self::$rabbitloader_cache_warnings = [];

            $otherConflictPluginMessages = RabbitLoader_21_Conflicts::getMessages();
            foreach ($otherConflictPluginMessages as $plugMessage) {
                self::$rabbitloader_cache_warnings[] = $plugMessage;
            }

            $adv_cache_msg = RL21UtilWP::__("The file /wp-content/advanced-cache.php is not writable. Please make sure that the PHP script has write access to the /wp-content/ directory and refresh this page to make RabbitLoader work efficiently.");

            if (!defined('RABBITLOADER_AC_ACTIVE') || (RABBITLOADER_PLUG_VERSION != RABBITLOADER_AC_PLUG_VERSION) || (LOGGED_IN_COOKIE != RABBITLOADER_AC_LOGGED_IN_COOKIE)) {
                $aac_code = self::activate_advanced_cache();
                if ($aac_code === 4) {
                    self::$rabbitloader_cache_warnings[] = $adv_cache_msg;
                }
            }

            if ((!defined("WP_CACHE") || !WP_CACHE)) {
                if (RL21UtilWP::is_flywheel()) {
                    self::$rabbitloader_cache_warnings[] = sprintf(RL21UtilWP::__('Please enable WP_CACHE from the Flywheel settings <a href="%s">check details</a>'), "https://rabbitloader.com/kb/settings-for-flywheel/");
                } else if (!self::update_wp_config_const('WP_CACHE', 'true')) {
                    self::$rabbitloader_cache_warnings[] = RL21UtilWP::__("The file /wp-config.php is not writable. Please make sure the file is writable or set WP_CACHE value to true to make RabbitLoader work efficiently.");
                }
            }

            RabbitLoader_21_Core::getWpOption($rl_wp_options);
            if (!empty($rl_wp_options['rl_hb_messages'])) {
                foreach ($rl_wp_options['rl_hb_messages'] as $message) {
                    if (!empty($message['fd'])) {
                        self::$rabbitloader_cache_warnings[] = RL21UtilWP::__($message['fd']);
                    }
                }
            }
            if (!empty($rl_wp_options['rl_latest_plugin_v'])) {
                if (version_compare(RABBITLOADER_PLUG_VERSION, $rl_wp_options['rl_latest_plugin_v']) == -1) {
                    self::$rabbitloader_cache_warnings[] = RL21UtilWP::__("You are using an outdated version of RabbitLoader plugin. Please update it for a better experience.");
                }
            }
        }

        $count = count(self::$rabbitloader_cache_warnings);

        if ($print) {
            foreach (self::$rabbitloader_cache_warnings as $message) {
                // echo '<div class="alert alert-danger" role="alert">';
                // _e($message, RABBITLOADER_TEXT_DOMAIN);
                // echo '</div>';
                echo '<div class="notice notice-error"><p><b>';
                RL21UtilWP::_e('Warning');
                echo ': </b>';
                _e($message, RABBITLOADER_TEXT_DOMAIN);
                echo '</div>';
            }
        }
    }

    public static function activate_advanced_cache()
    {

        try {
            if (!empty(RabbitLoader_21_Conflicts::getMessages())) {
                return 1;
            }

            // if (!RabbitLoader_21_Core::htaccessExists()) {
            //     return 2;
            // }

            $adv_cache_sample = RABBITLOADER_PLUG_DIR . "advanced-cache.php";
            $file_updated = false;
            if (file_exists($adv_cache_sample)) {
                $adv_cache_contents = file_get_contents($adv_cache_sample);
                $adv_cache_contents = str_replace("%%RABBITLOADER_AC_ABSPATH%%", ABSPATH, $adv_cache_contents);
                $adv_cache_contents = str_replace("%%RABBITLOADER_AC_PLUG_DIR%%", RABBITLOADER_PLUG_DIR, $adv_cache_contents);
                $adv_cache_contents = str_replace("%%RABBITLOADER_AC_LOGGED_IN_COOKIE%%", LOGGED_IN_COOKIE, $adv_cache_contents);
                $adv_cache_contents = str_replace("%%RABBITLOADER_AC_CACHE_DIR%%", RABBITLOADER_CACHE_DIR, $adv_cache_contents);
                $adv_cache_contents = str_replace("%%RABBITLOADER_AC_PLUG_VERSION%%", RABBITLOADER_PLUG_VERSION, $adv_cache_contents);
                $adv_cache_contents = str_replace("%%RABBITLOADER_AC_PLUG_ENV%%", RABBITLOADER_PLUG_ENV, $adv_cache_contents);

                $advanced_cache_file = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'advanced-cache.php';
                $file_updated = RabbitLoader_21_Util_Core::fpc($advanced_cache_file, $adv_cache_contents, WP_DEBUG);
            }

            if ($file_updated) {
                self::update_wp_config_const('WP_CACHE', 'true');
                return 3;
            } else {
                return 4;
            }
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }
    }

    private static function update_wp_config_const($const_name, $const_val)
    {
        $wp_config_path = RL21UtilWP::get_wp_config();
        //check if config file is writable
        if (empty($wp_config_path) || !is_writable($wp_config_path)) {
            //echo 'rl_not_writable__';
            return;
        }

        $lines = file($wp_config_path);
        $last_line = count($lines) - 1;

        $new_file = array();
        $const_added  = false;
        foreach ($lines as $current_line => $line_content) {
            //check if constant is already defined
            if (preg_match("/define\(\s*'{$const_name}'/i", $line_content)) {
                $const_added = true;
                $new_file[] = "if (!defined('{$const_name}')) { define( '{$const_name}', {$const_val} );}\n\n";
                continue; //dont't break here, its a complete file rewrite
            }

            $thatsAllLine = (preg_match("/\/\* That's all, stop editing!.*/i", $line_content)); //constants should be before this line.
            $isLast = ($thatsAllLine && !defined($const_name)) || ($last_line == $current_line);

            // If we reach the end and no define - add it.
            if (empty($const_added) && $isLast) {
                $const_added = true;
                $new_file[] = "if (!defined('{$const_name}')) { define( '{$const_name}', {$const_val} );}\n\n";
            }

            $new_file[] = $line_content;
        }
        $file_contents = implode("", $new_file);
        return RabbitLoader_21_Util_Core::fpc($wp_config_path, $file_contents, WP_DEBUG);
    }

    public static function plugin_deactivate()
    {
        try {
            self::update_wp_config_const('WP_CACHE', 'false');
            $advanced_cache_file = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'advanced-cache.php';
            if (file_exists($advanced_cache_file)) { //during uninstall RABBITLOADER_AC_ACTIVE will not be there
                $adv_cache_contents = "";
                $file_updated = RabbitLoader_21_Util_Core::fpc($advanced_cache_file, $adv_cache_contents, WP_DEBUG);
            }
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }
    }

    public static function plugin_uninstall()
    {
        self::plugin_deactivate();

        $post_data['uninstall'] = 1;
        $http = RabbitLoader_21_Core::callPOSTAPI('domain/heartbeat', $post_data, $apiError, $apiMessage);

        try {
            RabbitLoader_21_Core::cleanAllCachedFiles();
            RabbitLoader_21_Core::delete_log_file(RabbitLoader_21_Core::LOCAL_CONFIG_FILE);
        } catch (Exception $e) {
        }
        $ourOptions = array('rabbitloader_field_apikey', 'rabbitloader_field_apisecret', 'rabbitloader_field_domain', 'rl_optimizer_engine_version', 'rabbit_loader_wp_options', 'rabbit_loader_user_options', 'rabbitloader_cdn_prefix');

        foreach ($ourOptions as $optionName) {
            delete_option($optionName);
        }
    }

    public static function postSubmitButton()
    {
        wp_enqueue_script('rabbitloader-editor', RABBITLOADER_PLUG_URL . 'admin/js/editor.js', ['wp-edit-post', 'wp-plugins', 'wp-i18n', 'wp-element'], RABBITLOADER_PLUG_VERSION);
    }

    private static function rl_site_connected()
    {
        try {
            RabbitLoader_21_TP::purge_all($tp_purge_count);
        } catch (\Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }
    }

    public static function settings_link($links)
    {
        $url_settings = esc_url(add_query_arg(
            'page',
            'rabbitloader',
            get_admin_url() . 'admin.php'
        ));
        $link = "<a href='$url_settings'>" . __('Settings') . '</a>';
        array_push($links, $link);
        $url_kb = esc_url('https://rabbitloader.com/kb/');
        $link = "<a target='_blank' href='$url_kb'>" . __('Knowledge Base') . '</a>';
        array_push($links, $link);
        return $links;
    }
}
