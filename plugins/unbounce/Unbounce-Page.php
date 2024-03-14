<?php
/*
Plugin Name: Unbounce Landing Pages
Plugin URI: http://unbounce.com
Description: Unbounce is the most powerful standalone landing page builder available.
Version: 1.1.2
Author: Unbounce
Author URI: http://unbounce.com
License: GPLv2
*/

require_once dirname(__FILE__) . '/UBCompatibility.php';
require_once dirname(__FILE__) . '/UBDiagnostics.php';
require_once dirname(__FILE__) . '/UBUtil.php';
require_once dirname(__FILE__) . '/UBConfig.php';
require_once dirname(__FILE__) . '/UBLogger.php';
require_once dirname(__FILE__) . '/UBHTTP.php';
require_once dirname(__FILE__) . '/UBIcon.php';
require_once dirname(__FILE__) . '/UBWPListTable.php';
require_once dirname(__FILE__) . '/UBPageTable.php';
require_once dirname(__FILE__) . '/UBTemplate.php';

register_activation_hook(__FILE__, function () {
    UBConfig::set_options_if_not_exist();
});

register_deactivation_hook(__FILE__, function () {
    foreach (UBConfig::ub_option_defaults() as $key => $value_not_needed) {
        delete_option($key);
    }
});

add_action('init', function () {
    UBLogger::setup_logger();

    $domain = UBConfig::domain();

    if (!UBConfig::is_authorized_domain($domain)) {
        UBLogger::info("Domain: $domain has not been authorized");
        return;
    }

    UBLogger::debug_var('domain', $domain);

    $start = microtime(true);

    // $current_* = request from client to WordPress
    // $target_*  = request from WordPress to Page Server

    $current_protocol = UBHTTP::get_current_protocol($_SERVER, is_ssl());
    $current_method = UBUtil::array_fetch($_SERVER, 'REQUEST_METHOD');
    $current_path = UBUtil::array_fetch($_SERVER, 'REQUEST_URI');
    $current_url  = $current_protocol . '://' . $domain . $current_path;
    $current_user_agent = UBUtil::array_fetch($_SERVER, 'HTTP_USER_AGENT');

    UBLogger::debug_var('current_protocol', $current_protocol);
    UBLogger::debug_var('current_method', $current_method);
    UBLogger::debug_var('current_path', $current_path);
    UBLogger::debug_var('current_url', $current_url);
    UBLogger::debug_var('current_user_agent', $current_user_agent);

    $target_protocol = UBHTTP::determine_protocol($_SERVER, is_ssl());
    $target_domain = UBConfig::page_server_domain();
    $target_url = $target_protocol . '://' . $target_domain . $current_path;

    UBLogger::debug_var('target_domain', $target_domain);
    UBLogger::debug_var('target_protocol', $target_protocol);
    UBLogger::debug_var('target_url', $target_url);

    $domain_info = UBConfig::read_unbounce_domain_info($domain, false);
    $proxyable_url_set = UBUtil::array_fetch($domain_info, 'proxyable_url_set', array());

    ////////////////////

    $url_purpose = UBHTTP::get_url_purpose(
        $proxyable_url_set,
        $current_method,
        $current_url
    );
    if ($url_purpose == null) {
        UBLogger::debug("ignoring request to URL " . $current_url);
    } elseif (is_user_logged_in() && UBUtil::is_wordpress_preview($_GET)) {
        UBLogger::debug("Serving Wordpress Preview instead of landing page on root");
    } elseif ($url_purpose == 'HealthCheck') {
        if (UBConfig::domain_with_port() !== UBUtil::array_fetch($_SERVER, 'HTTP_HOST')) {
            http_response_code(412);
        }

        header('Content-Type: application/json');
        $version = UBConfig::UB_VERSION;
        echo "{\"ub_wordpress\":{\"version\":\"$version\"}}";
        exit(0);
    } else {
        // Disable caching plugins. This should take care of:
        //   - W3 Total Cache
        //   - WP Super Cache
        //   - ZenCache (Previously QuickCache)
        if (!defined('DONOTCACHEPAGE')) {
            define('DONOTCACHEPAGE', true);
        }

        if (!defined('DONOTCDN')) {
            define('DONOTCDN', true);
        }

        if (!defined('DONOTCACHEDB')) {
            define('DONOTCACHEDB', true);
        }

        if (!defined('DONOTMINIFY')) {
            define('DONOTMINIFY', true);
        }

        if (!defined('DONOTCACHEOBJECT')) {
            define('DONOTCACHEOBJECT', true);
        }

        UBLogger::debug("perform ''" . $url_purpose . "'' on received URL " . $current_url);

        $current_headers = array_change_key_case(getallheaders(), CASE_LOWER);

        // Make sure we don't get cached by Wordpress hosts like WPEngine
        header('Cache-Control: max-age=0; private');

        list($success, $message) = UBHTTP::stream_request(
            $current_method,
            $target_url,
            $current_user_agent,
            $current_headers,
            $current_protocol,
            $domain
        );

        if ($success === false) {
              update_option(UBConfig::UB_PROXY_ERROR_MESSAGE_KEY, $message);
        }

        $end = microtime(true);
        $time_taken = ($end - $start) * 1000;

        UBLogger::debug_var('time_taken', $time_taken);
        UBLogger::debug("proxying for $current_url done successfully -- took $time_taken ms");

        exit(0);
    }
}, UBConfig::int_min());

add_action('admin_init', function () {
    $current_version = UBConfig::UB_VERSION;
    $saved_version = get_option(UBConfig::UB_PLUGIN_VERSION_KEY);

    if ($saved_version != $current_version) {
        UBConfig::set_options_if_not_exist();
        update_option(UBConfig::UB_PLUGIN_VERSION_KEY, $current_version);

        // When upgrading to 1.1.x from a 1.0.x version, override all previous ub-page-server-domain values to new default
        if (version_compare($saved_version, '1.1.0', '<')) {
            update_option(UBConfig::UB_PAGE_SERVER_DOMAIN_KEY, UBConfig::default_page_server_domain());
        }
    }

    UBUtil::clear_flash();

    // Disable incompatible scripts

    // WPML
    wp_dequeue_script('installer-admin');

    // Enqueue our own scripts

    // Main page
    wp_enqueue_script(
        'ub-rx',
        plugins_url('js/rx.lite.compat.min.js', __FILE__)
    );
    wp_enqueue_script(
        'set-unbounce-domains-js',
        plugins_url('js/set-unbounce-domains.js', __FILE__),
        array('jquery', 'ub-rx'),
        "1.1.1"
    );
    wp_enqueue_script(
        'unbounce-page-js',
        plugins_url('js/unbounce-page.js', __FILE__),
        array('jquery')
    );

    // Diagnostics page
    wp_enqueue_script(
        'ub-clipboard-js',
        plugins_url('js/clipboard.min.js', __FILE__)
    );
    wp_enqueue_script(
        'unbounce-diagnostics-js',
        plugins_url('js/unbounce-diagnostics.js', __FILE__),
        array('jquery', 'ub-clipboard-js')
    );
    // Re-enable incompatible scripts

    // WPML
    wp_enqueue_script('installer-admin');

    wp_enqueue_style(
        'unbounce-pages-css',
        plugins_url('css/unbounce-pages.css', __FILE__)
    );

    // Plugin settings
    register_setting(
        UBConfig::UB_ADMIN_PAGE_SETTINGS,
        UBConfig::UB_RESPONSE_HEADERS_FORWARDED_KEY,
        array('sanitize_callback' => function ($input_value) {
            return array_filter(array_map('trim', explode(PHP_EOL, strtolower($input_value))));
        })
    );

    add_settings_section('default', null, null, UBConfig::UB_ADMIN_PAGE_SETTINGS);

    add_settings_field(
        UBConfig::UB_RESPONSE_HEADERS_FORWARDED_KEY,
        'Response Headers Forwarded',
        function ($args) {
            $defaults = UBConfig::ub_option_defaults();
            echo UBTemplate::render('settings_response_headers_forwarded', array(
                'value' => get_option(UBConfig::UB_RESPONSE_HEADERS_FORWARDED_KEY),
                'default' => $defaults[UBConfig::UB_RESPONSE_HEADERS_FORWARDED_KEY]
            ));
        },
        UBConfig::UB_ADMIN_PAGE_SETTINGS,
        'default'
    );
}, 0);

add_action('admin_menu', function () {
    // Main admin page
    $print_admin_panel = function () {
        $domain = UBConfig::domain();
        $domain_info = UBConfig::read_unbounce_domain_info($domain, false);

        echo UBTemplate::render(
            'main',
            array('domain_info' => $domain_info,
            'domain' => $domain)
        );
    };

    add_menu_page(
        'Unbounce Pages',
        'Unbounce Pages',
        'manage_options',
        UBConfig::UB_ADMIN_PAGE_MAIN,
        $print_admin_panel,
        UBIcon::base64_encoded_svg()
    );

    // Settings page
    $print_settings_panel = function () {
        echo UBTemplate::render('settings');
    };

    // Diagnostics page
    $print_diagnostics_panel = function () {
        $domain = UBConfig::domain();
        $domain_info = UBConfig::read_unbounce_domain_info($domain, false);

        echo UBTemplate::render(
            'diagnostics',
            array(
                'checks' => UBDiagnostics::checks($domain, $domain_info),
                'details' => UBDiagnostics::details($domain, $domain_info),
                'domain' => $domain,
                'permalink_url' => admin_url('options-permalink.php'),
            'curl_error_message' => UBUtil::array_fetch(
                $domain_info,
                'failure_message'
            ))
        );
    };

    add_submenu_page(
        UBConfig::UB_ADMIN_PAGE_MAIN,
        'Unbounce Pages',
        'Pages',
        'manage_options',
        UBConfig::UB_ADMIN_PAGE_MAIN,
        $print_admin_panel
    );

    add_submenu_page(
        UBConfig::UB_ADMIN_PAGE_MAIN,
        'Unbounce Pages Settings',
        'Settings',
        'manage_options',
        UBConfig::UB_ADMIN_PAGE_SETTINGS,
        $print_settings_panel
    );

    add_submenu_page(
        UBConfig::UB_ADMIN_PAGE_MAIN,
        'Unbounce Pages Diagnostics',
        'Diagnostics',
        'manage_options',
        UBConfig::UB_ADMIN_PAGE_DIAGNOSTICS,
        $print_diagnostics_panel
    );
});

add_action('admin_post_set_unbounce_domains', function () {
    $domains_list = UBUtil::array_fetch($_POST, 'domains', '');
    $domains = array_filter(explode(',', $domains_list), function ($domain) {
        return $domain == UBConfig::domain();
    });

    if ($domains && is_array($domains)) {
        $authorization = 'success';
        $has_authorized = get_option(UBConfig::UB_HAS_AUTHORIZED_KEY, false);

        $data = array(
        'domain_name' => UBConfig::domain(),
        'first_authorization' => !$has_authorized,
        'user_id' => UBUtil::array_fetch($_POST, 'user_id', ''),
        'client_id' => UBUtil::array_fetch($_POST, 'client_id', ''),
        'domain_id' => UBUtil::array_fetch($_POST, 'domain_id', ''),
        'domain_uuid' => UBUtil::array_fetch($_POST, 'domain_uuid', ''),
        );

        UBConfig::update_authorization_options($domains, $data);
    } else {
        $authorization = 'failure';
    }

    UBUtil::set_flash('authorization', $authorization);

    status_header(301);
    $location = admin_url('admin.php?page='.UBConfig::UB_ADMIN_PAGE_MAIN);
    header("Location: $location");
});

add_action('admin_post_flush_unbounce_pages', function () {
    $domain = UBConfig::domain();
    // Expire cache and redirect
    $_domain_info = UBConfig::read_unbounce_domain_info($domain, true);
    status_header(301);
    $location = admin_url('admin.php?page='.UBConfig::UB_ADMIN_PAGE_MAIN);
    header("Location: $location");
});
