<?php

class RabbitLoader_21_TP
{

    const PURGE_ALL = 1;
    const PURGE_ID = 2;
    const PURGE_URL = 3;
    private static $rl_wp_options = [];

    public static function purge_all(&$success_count)
    {
        self::call_tpvs(self::PURGE_ALL, '', $success_count);
    }

    public static function purge_url($url, &$success_count)
    {
        self::call_tpvs(self::PURGE_URL, $url, $success_count);
    }

    public static function purge_post_id($post_id, &$success_count)
    {
        self::call_tpvs(self::PURGE_ID, $post_id, $success_count);
    }

    private static function call_tpvs($purge_type,  $url_id, &$success_count)
    {
        RabbitLoader_21_Core::getWpOption(RabbitLoader_21_TP::$rl_wp_options);
        $class_methods = get_class_methods('RabbitLoader_21_TP');
        foreach ($class_methods as $method_name) {
            if (substr($method_name, 0, 3) == 'tpv') {
                try {
                    if (self::$method_name($purge_type,  $url_id)) {
                        ++$success_count;
                    }
                } catch (\Throwable $e) {
                    RabbitLoader_21_Core::on_exception($e);
                }
            }
        }
    }

    private static function tpv_wp_core($purge_type, $url_id)
    {
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }

        if (function_exists('opcache_reset')) {
            opcache_reset();
        }

        if (function_exists('apc_clear_cache')) {
            apc_clear_cache();
        }
    }

    private static function tpv_litespeed($purge_type, $url_id)
    {
        $isLS = !empty($_SERVER["X-LSCACHE"]) || (!empty($_SERVER["SERVER_SOFTWARE"]) && "litespeed" == strtolower($_SERVER["SERVER_SOFTWARE"])) || RabbitLoader_21_Util_Core::isDev();
        if (!$isLS) {
            return false;
        }
        if ($purge_type == self::PURGE_ALL) {
            RabbitLoader\SDK\Util::sendHeader("X-LiteSpeed-Purge: *", false);
        } elseif ($purge_type == self::PURGE_URL) {
            $path = parse_url($url_id, PHP_URL_PATH);
            if (empty($path)) {
                $path = '/';
            }
            RabbitLoader\SDK\Util::sendHeader("X-LiteSpeed-Purge: " . $path, false);
        }
        return true;
    }

    /**
     * https://github.com/pantheon-systems/pantheon-advanced-page-cache/blob/master/pantheon-advanced-page-cache.php
     */
    private static function tpv_pantheon($purge_type, $url_id)
    {
        if (!function_exists('pantheon_wp_clear_edge_all')) {
            return false;
        }
        if ($purge_type == self::PURGE_ALL) {
            pantheon_wp_clear_edge_all();
        } elseif ($purge_type == self::PURGE_ID) {
            do_action('clean_post_cache', $url_id);
        }
        return true;
    }

    private static function tpv_nginx($purge_type, $url_id)
    {
        global $nginx_purger;
        if (empty($nginx_purger)) {
            return false;
        }
        if ($purge_type == self::PURGE_ALL) {
            $nginx_purger->purge_all();
        } elseif ($purge_type == self::PURGE_URL) {
            $nginx_purger->purge_url($url_id);
        }
        return true;
    }

    /**
     * https://github.com/Savvii/warpdrive/
     */
    private static function tpv_savii($purge_type, $url_id)
    {
        if (!defined('\Savvii\CacheFlusherPlugin::NAME_DOMAINFLUSH_NOW')) {
            return false;
        }
        do_action('warpdrive_domain_flush');
        return true;
    }

    private static function tpv_ninukis($purge_type, $url_id)
    {
        if (!defined('WP_NINUKIS_WP_NAME') || !class_exists('Ninukis_Plugin')) {
            return false;
        }
        if ($purge_type == self::PURGE_ALL) {
            $purge_pressidum = Ninukis_Plugin::get_instance();
            $purge_pressidum->purgeAllCaches();
            return true;
        } elseif ($purge_type == self::PURGE_ID) {
        }
    }

    private static function tpv_pagely_cache($purge_type, $url_id)
    {
        if (!class_exists('\PagelyCachePurge')) {
            return false;
        }
        if ($purge_type == self::PURGE_ALL) {
            $purge_pagely = new PagelyCachePurge();
            $purge_pagely->purgeAll();
        } elseif ($purge_type == self::PURGE_URL) {
            $purge_pagely = new PagelyCachePurge();
            $purge_pagely->purgePath(parse_url($url_id, PHP_URL_PATH) . "(.*)");
        }
        return true;
    }

    //wpengine - https://wpengine.com/support/cache/
    private static function tpv_wpengine($purge_type, $url_id)
    {
        if (!class_exists("\WpeCommon")) {
            return false;
        }
        if ($purge_type == self::PURGE_ALL) {
            \WpeCommon::purge_memcached();
            \WpeCommon::purge_varnish_cache();
        } elseif ($purge_type == self::PURGE_ID) {
            \WpeCommon::purge_varnish_cache($url_id);
        }
        return true;
    }

    //godaddy
    private static function tpv_wpass($purge_type, $url_id)
    {
        if (!class_exists('WPaaS\Plugin')) {
            return false;
        }
        if ($purge_type == self::PURGE_ALL) {
            if (method_exists('WPass\Plugin', 'vip')) {
                //godaddy
                $method = 'BAN';
                $url = home_url();
                $host = wpraiser_get_domain();
                $url  = set_url_scheme(str_replace($host, WPaas\Plugin::vip(), $url), 'http');
                update_option('gd_system_last_cache_flush', time(), 'no'); # purge apc
                wp_remote_request(esc_url_raw($url), array('method' => $method, 'blocking' => false, 'headers' => array('Host' => $host)));
            }
            return true;
        } elseif ($purge_type == self::PURGE_ID) {
        }
    }

    //https://github.com/cloudflare/Cloudflare-WordPress/blob/74acc2df4938df1fcfa4347e971d73e2fc6e4edc/src/WordPress/Hooks.php
    private static function tpv_cloudflare($purge_type, $url_id)
    {
        if (!class_exists('\CF\WordPress\Hooks')) {
            return false;
        }
        if ($purge_type == self::PURGE_ALL) {
            $cfHooks = new \CF\WordPress\Hooks();
            $cfHooks->purgeCacheEverything();
        } elseif ($purge_type == self::PURGE_ID) {
            $cfHooks = new \CF\WordPress\Hooks();
            $cfHooks->purgeCacheByRelevantURLs($url_id);
        }
        return true;
    }

    private static function tpv_kinsta($purge_type, $url_id)
    {
        if (!defined("KINSTAMU_VERSION")) {
            return false;
        }
        if ($purge_type == self::PURGE_ALL) {
            $response = wp_remote_get('https://localhost/kinsta-clear-cache-all', [
                'sslverify' => false,
                'timeout'   => 5
            ]);
        } elseif ($purge_type == self::PURGE_URL) {
            $response = wp_remote_post('https://localhost/kinsta-clear-cache/v2/immediate', [
                'sslverify' => false,
                'timeout'   => 5,
                'body'      => [
                    'single' => preg_replace('@^https?://@', '', $url_id)
                ]
            ]);
        }
        return true;
    }

    //SiteGround - https://wordpress.org/plugins/sg-cachepress/
    private static function tpv_siteground_cachepress($purge_type, $url_id)
    {
        if (!function_exists('sg_cachepress_purge_cache')) {
            return false;
        }
        if ($purge_type == self::PURGE_ALL) {
            sg_cachepress_purge_cache();
        } elseif ($purge_type == self::PURGE_URL) {
            sg_cachepress_purge_cache($url_id);
        }
        return true;
    }

    private static function tpv_siteground($purge_type, $url_id)
    {
        $wp_config_path = RL21UtilWP::get_wp_config();
        if (empty($wp_config_path) || strpos(file_get_contents($wp_config_path), 'Added by SiteGround') === false) {
            return false;
        }
        if ($purge_type == self::PURGE_ALL) {
            $url_id = home_url() . '/(.*)';
        } elseif ($purge_type == self::PURGE_URL) {
            //$url_id is already set
        } else if ($purge_type == self::PURGE_ID) {
            return false;
        }
        $url_id = preg_replace("/^www\./", "", $url_id);
        $url_id = str_ireplace("https://", "http://", $url_id);

        return self::purge_varnish($url_id, "PURGE");
    }

    /**
     * Purge Varnish cache:
     * CloudWays: HTTP_X_VARNISH would be set if Varnish is on
     */
    private static function tpv_varnish($purge_type, $url_id)
    {
        $is_cloudways = isset($_SERVER['cw_allowed_ip']);
        $uses_varnish = $is_cloudways && isset($_SERVER['HTTP_X_VARNISH']);
        if (!$uses_varnish && !empty(RabbitLoader_21_TP::$rl_wp_options['rl_varnish'])) {
            $uses_varnish = intval(RabbitLoader_21_TP::$rl_wp_options['rl_varnish']) === 1;
        }
        if (!$uses_varnish) {
            return false;
        }
        $method = 'PURGE';
        if ($purge_type == self::PURGE_ALL) {
            $url_id = home_url() . '/.*';
        } elseif ($purge_type == self::PURGE_URL) {
            //$url_id is already set for single URL
            if ($is_cloudways) {
                $method = 'URLPURGE';
            }
        } else if ($purge_type == self::PURGE_ID) {
            return false;
        }
        return self::purge_varnish($url_id, $method);
    }

    private static function tpv_wp_rocket_residue($purge_type, $url_id)
    {
        if (!defined('WP_ROCKET_CACHE_PATH')) {
            return;
        }
        #https://docs.wp-rocket.me/article/133-manually-clear-wp-rocket-cache
        $wp_rocket_cache_path = WP_ROCKET_CACHE_PATH; #/wp-content/cache/wp-rocket/
        $host = parse_url(get_site_url(), PHP_URL_HOST);
        $wp_rocket_cache_dir = $wp_rocket_cache_path . $host;
        if (!file_exists($wp_rocket_cache_dir)) {
            $wp_rocket_cache_dir = $wp_rocket_cache_path . "www." . $host;
        }
        if (file_exists($wp_rocket_cache_dir)) {
            $special_files = ['.htaccess', '.', '..', ''];
            $files = glob($wp_rocket_cache_dir . '/*');
            foreach ($files as $file) {
                if (empty($file) || in_array(basename($file), $special_files)) {
                    continue;
                }
                if (is_file($file)) {
                    @unlink($file); // delete file
                } elseif (is_dir($file)) { //category, tags etc
                    $cat_files = glob($file . DIRECTORY_SEPARATOR . '/*');
                    foreach ($cat_files as $file) {
                        @unlink($file); // delete file
                    }
                }
            }
        } else {
            $files = glob($wp_rocket_cache_path . '*');
            if (!empty($files) && count($files) == 1 && stripos($files[0], 'cache/wp-rocket/index.html') !== false) {
                //no dir exists
            } else {
                RabbitLoader_21_Core::on_exception('WP_ROCKET_CACHE_PATH is true but dir not found ' . $wp_rocket_cache_dir . ' files ' . print_r($files, true), 1);
            }
        }
    }

    /**
     * Doc - Hostgator and https://github.com/bluehost/endurance-page-cache/blob/master/endurance-page-cache.php
     */
    private static function tpv_endurance($purge_type, $url_id)
    {
        if (!class_exists('Endurance_Page_Cache')) {
            return;
        }
        if ($purge_type == self::PURGE_ALL) {
            do_action('epc_purge');
        } elseif ($purge_type == self::PURGE_URL) {
            do_action('epc_purge_request', $url_id);
        }
    }

    private static function tpv_sample($purge_type, $url_id)
    {
        if ($purge_type == self::PURGE_ALL) {
        } elseif ($purge_type == self::PURGE_ID) {
        } elseif ($purge_type == self::PURGE_URL) {
        }
    }

    /**
     * @param string url_pattern - Single URL or a URL with wildcard
     * @param string method - PURGE, URLPURGE
     */
    private static function purge_varnish($url_pattern, $method)
    {
        $url_parts = parse_url($url_pattern);
        $port = (empty($url_parts['scheme']) || $url_parts['scheme'] == 'https') ? '443' : '80';
        $ch = curl_init($url_pattern);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RESOLVE, array($url_parts['host'] . ":$port:127.0.0.1"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_exec($ch);
        $curl_error = curl_error($ch);
        $httpcode = intval(curl_getinfo($ch, CURLINFO_HTTP_CODE));
        curl_close($ch);
        /*
        if($curl_error){
            RabbitLoader_21_Core::on_exception("Varnish curl error $curl_error for URL $url_id");
        }else if(!in_array($httpcode,[200, 301, 302, 403, 404])){
            RabbitLoader_21_Core::on_exception("Varnish unexpected response $httpcode URL $url_id");
        }*/
        return $httpcode;
    }
}
