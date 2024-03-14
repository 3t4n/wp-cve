<?php

spl_autoload_register(function ($class) {
    $file = str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";
    $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . $file;
    if (file_exists($path)) {
        require_once $path;
    }
});
class RabbitLoader_21_Core
{

    private static $rl_wp_options = [];
    private static $user_options = [];

    /**
     * max time a cache can live
     */
    const ORPHANED_LONG_AGE_SEC = 30 * 24 * 3600;
    const LOCAL_CONFIG_FILE = "rl_config";

    private static function addKeys(&$args, &$rabbitloader_field_domain)
    {
        if (empty($args)) {
            $args = [];
        }

        if (empty($args['headers'])) {
            $args['headers'] = [];
        }

        if (RabbitLoader_21_Util_Core::isDev()) {
            $args['sslverify'] = false;
        }
        $args['timeout'] = 30;

        $api_token = RabbitLoader_21_Core::getWpOptVal('api_token');
        if (!empty($api_token)) {
            $args['headers'] += [
                'AUTHORIZATION' => 'Bearer ' . $api_token
            ];
            $rabbitloader_field_domain = RabbitLoader_21_Core::getWpOptVal('domain');
        } else {
            return false;
        }

        return true;
    }

    public static function update_api_tokens($api_token, $domain, $did, $comments)
    {
        RabbitLoader_21_Core::getWpOption($rl_wp_options);
        $rl_wp_options['api_token'] = $api_token;
        $rl_wp_options['domain'] = $domain;
        $rl_wp_options['did'] = $did;
        $rl_wp_options['comments'] = $comments;
        $rl_wp_options['token_update_ts'] = time();
        RabbitLoader_21_Core::updateWpOption($rl_wp_options);
    }

    public static function getRLDomain()
    {
        return RabbitLoader_21_Util_Core::isDev() ? 'https://rabbitloader.local/' : 'https://rabbitloader.com/';
    }
    public static function getRLDomainV2()
    {
        return RabbitLoader_21_Util_Core::isDev() ? 'https://api-v2.rabbitloader.local/' : 'https://api-v2.rabbitloader.com/';
    }

    private static function isTemporaryError($apiMessage)
    {
        $temp_errors = ['timed out', 'Could not resolve host', 'error setting certificate', 'Connection reset', 'OpenSSL', 'getaddrinfo() thread', 'SSL connection timeout', 'Unknown SSL', 'SSL_ERROR_SYSCALL', 'Failed connect to', 'cURL error 77'];
        $found = false;
        foreach ($temp_errors as $msg) {
            if (stripos($apiMessage, $msg) !== false) {
                $found = true;
                break;
            }
        }
        return $found;
    }

    public static function &callGETAPI($endpoint, &$apiError, &$apiMessage)
    {
        $http = [];
        $args = [];
        $apiError = true;
        if (!RabbitLoader_21_Core::addKeys($args, $rabbitloader_field_domain)) {
            $apiError = 'Keys could not be added';
            return $http;
        }
        $url = RabbitLoader_21_Core::getRLDomain() . 'api/v1/';
        if (strpos($endpoint, '?')) {
            $endpoint .= '&';
        } else {
            $endpoint .= '?';
        }

        $endpoint .= 'domain=' . $rabbitloader_field_domain . '&plugin_cms=wp&plugin_v=' . RABBITLOADER_PLUG_VERSION . '&cms_v=' . get_bloginfo('version');

        $args['method'] = 'GET';

        try {
            $http = wp_remote_get($url . $endpoint, $args);

            if (is_wp_error($http)) {
                $apiError = true;
                $apiMessage = $http->get_error_message();
                if (empty($apiMessage)) {
                    $apiMessage = '';
                }
                if (self::isTemporaryError($apiMessage)) {
                    //chill, it happens
                } else {
                    RabbitLoader_21_Core::on_exception($http);
                }
                $http = [];
            }

            if (!empty($http['response']['code']) && in_array($http['response']['code'], [200, 401])) {
                $http['body'] = json_decode($http['body'], true);
                if (!empty($http['body']['message'])) {
                    $message = $http['body']['message'];
                    if (!strcmp($message, 'AUTH_REQUIRED') || !strcmp($message, 'INVALID_DOMAIN')) {
                        RabbitLoader_21_Core::update_api_tokens('', '', '', "$message when $endpoint was called");
                    }
                }
                $apiError = empty($http['body']['result']);
                $apiMessage = empty($http['body']['message']) ? '' : $http['body']['message'];
            }
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
            $apiError = true;
            $apiMessage = $e->getMessage();
        }
        return $http;
    }

    public static function &callGETAPIV2($endpoint, &$apiError, &$apiMessage)
    {
        $http = [];
        $args = [];
        $apiError = true;
        if (!RabbitLoader_21_Core::addKeys($args, $rabbitloader_field_domain)) {
            $apiError = 'Keys could not be added';
            return $http;
        }
        $url = RabbitLoader_21_Core::getRLDomainV2();
        $args['method'] = 'GET';

        try {
            if (stripos($endpoint, '{domain_id}')) {
                $did = RabbitLoader_21_Core::getWpOptVal('did');
                if (empty($did)) {
                    $apiError = 'Please disconnect the plugin and connect again.';
                    return $http;
                }
                $endpoint = str_ireplace('{domain_id}', $did, $endpoint);
            }
            $http = wp_remote_get($url . $endpoint, $args);
            $code = wp_remote_retrieve_response_code($http);
            if (is_wp_error($http)) {
                $apiError = true;
                $apiMessage = $http->get_error_message();
                if (empty($apiMessage)) {
                    $apiMessage = '';
                }
                if (self::isTemporaryError($apiMessage)) {
                    //chill, it happens
                } else {
                    RabbitLoader_21_Core::on_exception($http);
                }
            }

            if (in_array($code, [401, 403])) {
                $apiError = true;
                $apiMessage = "Unauthorized access. Please disconnect and Login again.";
                RabbitLoader_21_Core::update_api_tokens('', '', '', "$code when $endpoint was called");
            }
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
            $apiError = true;
            $apiMessage = $e->getMessage();
        }
        $http['body'] = json_decode(wp_remote_retrieve_body($http), true);
        return $http;
    }

    public static function &callPostApi($endpoint, $body, &$apiError, &$apiMessage)
    {
        $http = [];
        $args = [];
        $apiError = true;

        if (!RabbitLoader_21_Core::addKeys($args, $rabbitloader_field_domain)) {
            $apiError = 'Keys could not be added';
            return $http;
        }
        $url = RabbitLoader_21_Core::getRLDomain() . 'api/v1/';

        $body['domain'] = $rabbitloader_field_domain;
        $body['plugin_cms'] = 'wp';
        $body['plugin_v'] = RABBITLOADER_PLUG_VERSION;
        $body['cms_v'] = get_bloginfo('version');

        $args['method'] = 'POST';
        $args['body'] = $body;

        try {
            $http = wp_remote_post($url . $endpoint, $args);

            if (is_wp_error($http)) {
                $apiError = true;
                $apiMessage = $http->get_error_message();
                if (empty($apiMessage)) {
                    $apiMessage = '';
                }
                if (self::isTemporaryError($apiMessage)) {
                    //chill, it happens
                } else {
                    RabbitLoader_21_Core::on_exception($http->get_error_message() . $url . $endpoint);
                }
                $http = [];
            }

            if (!empty($http['response']['code']) && in_array($http['response']['code'], [200, 401])) {
                $http['body'] = json_decode($http['body'], true);
                if (!empty($http['body']['message'])) {
                    $message = $http['body']['message'];
                    if (!strcmp($message, 'AUTH_REQUIRED') || !strcmp($message, 'INVALID_DOMAIN')) {
                        RabbitLoader_21_Core::update_api_tokens('', '', '', "$message when $endpoint was called");
                    }
                }
                $apiError = empty($http['body']['result']);
                $apiMessage = empty($http['body']['message']) ? '' : $http['body']['message'];
            }
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
            $apiError = true;
            $apiMessage = $e->getMessage();
        }
        return $http;
    }

    public static function getWpUserOption(&$user_options)
    {
        if (!empty(self::$user_options)) {
            $user_options = self::$user_options;
            return;
        }
        if (function_exists('get_option')) {
            $user_options = get_option('rabbit_loader_user_options');
        } else {
            RabbitLoader_21_Core::get_log_file('rl_user_options', $rl_user_options);
            if (file_exists($rl_user_options)) {
                $user_options = json_decode(file_get_contents($rl_user_options), true);
            }
        }
        if (empty($user_options) || !is_array($user_options)) {
            $user_options = [];
        }
        $default_values = [
            'purge_on_change' => false,
            'exclude_patterns' => '',
            'ignore_params' => '',
            'private_mode_val' => false,
        ];
        foreach ($default_values as $k => $v) {
            if (!isset($user_options[$k])) {
                $user_options[$k] = $v;
            }
        }
        self::$user_options = $user_options;
    }
    public static function updateUserOption(&$user_options)
    {
        self::$user_options = $user_options;
        update_option('rabbit_loader_user_options', $user_options, true);
        try {
            RabbitLoader_21_Core::get_log_file('rl_user_options', $rl_user_options);
            $rl_json = json_encode($user_options, JSON_INVALID_UTF8_IGNORE);
            RabbitLoader_21_Util_Core::fpc($rl_user_options, $rl_json, WP_DEBUG);
        } catch (\Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }
    }


    public static function getWpOption(&$rl_wp_options)
    {
        if (!empty(self::$rl_wp_options)) {
            $rl_wp_options = self::$rl_wp_options;
            return;
        }
        if (function_exists('get_option')) {
            $rl_wp_options = get_option('rabbit_loader_wp_options');
        } else {
            RabbitLoader_21_Core::get_log_file(self::LOCAL_CONFIG_FILE, $rl_config);
            if (file_exists($rl_config)) {
                $rl_wp_options = json_decode(file_get_contents($rl_config), true);
            }
        }
        if (empty($rl_wp_options)) {
            $rl_wp_options = [];
        }
        self::$rl_wp_options = $rl_wp_options;
    }
    /**
     * Get value of single config option
     */
    public static function getWpOptVal($key)
    {
        RabbitLoader_21_Core::getWpOption($rl_wp_options);
        return isset($rl_wp_options[$key]) ? $rl_wp_options[$key] : '';
    }

    public static function updateWpOption(&$rl_wp_options)
    {
        self::$rl_wp_options = $rl_wp_options;
        try {
            update_option('rabbit_loader_wp_options', $rl_wp_options, true);
        } catch (\Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }
        try {
            RabbitLoader_21_Core::get_log_file(self::LOCAL_CONFIG_FILE, $rl_config);
            $rl_json = json_encode($rl_wp_options, JSON_INVALID_UTF8_IGNORE);
            RabbitLoader_21_Util_Core::fpc($rl_config, $rl_json, WP_DEBUG);
        } catch (\Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }
    }

    public static function purge_all(&$purge_count, $purge_source, &$tp_purge_count)
    {
        try {
            //RL purges
            $purge_count = 0;
            $purge_count += RabbitLoader_21_Core::cleanAllCachedFiles();

            //other common platforms purges
            RabbitLoader_21_TP::purge_all($tp_purge_count);
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }
    }

    /**
     * @param string $fn file name
     * @param string $fp file path
     */
    public static function get_log_file($fn, &$fp)
    {
        $fp = RL21UtilWP::get_cache_dir() . DIRECTORY_SEPARATOR . $fn . ".log";
    }

    public static function delete_log_file($fn)
    {
        $fp = '';
        self::get_log_file($fn, $fp);
        if ($fp && file_exists($fp)) {
            @unlink($fp);
        }
    }

    public static function on_exception($exception, $limit = 8)
    {
        try {
            $rlSDK = self::getSDK();
            $rlSDK->excCatch($exception, ['src' => 'wp'], $limit);
        } catch (Throwable $e) {
            if (WP_DEBUG) {
                echo $e->getMessage();
            }
        }
    }

    public static function sendJsonResponse(&$response)
    {
        header("Content-Type: application/json");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, s-max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $encoded_str = json_encode($response, JSON_INVALID_UTF8_IGNORE);
        if ($encoded_str === false) {
            echo '{"time":"1", "failed":"1"}';
        } else {
            echo $encoded_str;
        }
        exit;
    }

    public static function get_common_cache_urls(&$urls_to_purge)
    {
        if (empty($urls_to_purge)) {
            $urls_to_purge = [];
        }

        $urls_to_purge[] = get_home_url(); //always purge home page if any other page is modified
        $urls_to_purge[] = get_home_url() . "/"; //always purge home page if any other page is modified
        $urls_to_purge[] = home_url('/'); //always purge home page if any other page is modified
        $urls_to_purge[] = site_url('/'); //always purge home page if any other page is modified

        //clean pagination urls
        try {
            if (!empty(get_option('page_for_posts'))) {
                $page_for_posts = get_permalink(get_option('page_for_posts'));
                if (is_string($page_for_posts) && !empty($page_for_posts) && get_option('show_on_front') == 'page') {
                    $urls_to_purge[] = $page_for_posts;
                }
            }

            $posts_per_page = get_option('posts_per_page');
            $published_posts = RabbitLoader_21_Core::get_published_count();
            $page_number_max = min(3, ceil($published_posts / $posts_per_page));
            for ($pn = 1; $pn < $page_number_max; $pn++) {
                $urls_to_purge[] = home_url(sprintf('/page/%s/', $pn));
            }
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }
    }

    public static function get_published_count()
    {
        //$published_count = wp_count_posts()->publish + wp_count_posts('page')->publish;
        $published_count = 0;
        $post_types = get_post_types(['public' => true], 'names', 'and');
        $ex = ['attachment', 'elementor_library'];
        foreach ($post_types  as $post_type) {
            if (in_array($post_type, $ex)) {
                continue;
            }
            $published_count += wp_count_posts($post_type)->publish;
        }
        return $published_count + 1; //1 for home page
    }

    private static function &checkHostingName()
    {
        $hosting_name = 'NA';
        if (!empty($_SERVER['cw_allowed_ip'])) {
            $hosting_name  = $_SERVER['cw_allowed_ip'];
        } else if (class_exists('WpeCommon') && method_exists('WpeCommon', 'purge_memcached')) {
            $hosting_name = 'wpengine';
        } else if (defined("KINSTAMU_VERSION")) {
            $hosting_name = 'Kinsta';
        } else if (RL21UtilWP::is_flywheel()) {
            $hosting_name = 'flywheel';
        } else if (preg_match("/^dp-.+/", gethostname())) {
            $hosting_name = 'dreamhost';
        } else if (defined("CLOSTE_APP_ID")) {
            $hosting_name = 'closte';
        } else if (function_exists('sg_cachepress_purge_cache')) {
            $hosting_name = 'siteground';
        } else if (class_exists('LiteSpeed_Cache_API') && method_exists('LiteSpeed_Cache_API', 'purge_all')) {
            $hosting_name = 'litespeed';
        } else if (class_exists('PagelyCachePurge') && method_exists('PagelyCachePurge', 'purgeAll')) {
            $hosting_name = 'pagely';
        } else if (class_exists('comet_cache') && method_exists('comet_cache', 'clear')) {
            $hosting_name = 'comet';
        } else if (defined('IS_PRESSABLE')) {
            $hosting_name = 'pressable';
        }
        return $hosting_name;
    }

    public static function &getSDK()
    {
        if (empty($GLOBALS['rlSDK'])) {
            $rlSDK = new RabbitLoader\SDK\RabbitLoader(RabbitLoader_21_Core::getWpOptVal('api_token'), RL21UtilWP::get_cache_dir());
            $GLOBALS['rlSDK'] = &$rlSDK;
            $rlSDK->setDebug(WP_DEBUG);

            $rlSDK->registerPurgeCallback(function ($url) {
                $tp_purge_count = 0;
                RabbitLoader_21_TP::purge_url($url, $tp_purge_count);
            });

            $rlSDK->setPlatform([
                'plugin_cms' => 'wp',
                'plugin_v' => RL21UtilWP::getRLPlugVersion(),
                'cms_v' => function_exists('get_bloginfo') ? get_bloginfo('version') : ''
            ]);
        }
        return $GLOBALS['rlSDK'];
    }

    public static function cleanAllCachedFiles()
    {
        $rlSDK = self::getSDK();
        $deleted_count = $rlSDK->deleteAll();

        if (function_exists("opcache_reset")) {
            opcache_reset();
        }
        if (function_exists('delete_transient')) {
            delete_transient('rabbitloader_trans_overview_data');
        }
        return $deleted_count;
    }

    public static function getCacheCount()
    {
        $rlSDK = self::getSDK();
        return $rlSDK->getCacheCount();
    }
}
