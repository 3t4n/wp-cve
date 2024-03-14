<?php

class RL21UtilWP
{

    const POST_ID_ALL = "all";

    private static $isSearch = false;
    private static $isPageAccount = false;
    private static $purge_queue = [];

    public static function init()
    {
        add_action('template_redirect', function () {
            self::$isSearch = (is_search() || !empty($_GET["s"]));
            self::$isPageAccount = (function_exists("is_page") && is_page("account"));
        });
    }

    public static function is_cli()
    {
        return (defined("WP_CLI") && WP_CLI);
    }

    public static function is_user_logged_in()
    {

        if (function_exists('is_user_logged_in')) {
            //if WP is not initialized, we may not get the function, so we have to do our own checks as well
            return is_user_logged_in();
        }

        $cookies_keys = [];

        if (defined('RABBITLOADER_AC_LOGGED_IN_COOKIE')) {
            $cookies_keys[] = RABBITLOADER_AC_LOGGED_IN_COOKIE;
        }

        if (defined('LOGGED_IN_COOKIE')) {
            $cookies_keys[] = LOGGED_IN_COOKIE;
        }

        foreach ($cookies_keys as $key) {
            if (!empty($_COOKIE[$key])) {
                return true;
            }
        }

        return false;
    }

    public static function is_login_page()
    {
        $is_login = function_exists('is_login') && function_exists('wp_login_url') && is_login();

        $incl_path = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, ABSPATH);

        return $is_login || (in_array($incl_path . 'wp-login.php', get_included_files())
            || in_array($incl_path . 'wp-register.php', get_included_files()))
            || (isset($_GLOBALS['pagenow']) && $GLOBALS['pagenow'] === 'wp-login.php')
            || $_SERVER['PHP_SELF'] == '/wp-login.php' || self::$isPageAccount;
    }

    public static function is_ajax()
    {

        $incl_path = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, ABSPATH);

        return (function_exists("wp_doing_ajax") && wp_doing_ajax()) ||
            (defined('DOING_AJAX') && DOING_AJAX) ||
            (!empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest") ||
            (function_exists('get_included_files') && in_array($incl_path . 'wp-cron.php', get_included_files())) ||
            (function_exists('get_included_files') && in_array($incl_path . 'admin-ajax.php', get_included_files()));
    }

    public static function is_cart()
    {
        $isCart = false;
        try {
            $isCart = (function_exists("is_cart") && is_cart());
            if ($isCart) {
                RabbitLoader_21_Core::getWpUserOption($user_options);
                $user_options['cart_uri'] = RabbitLoader_21_Util_Core::serverURINoGet();
                RabbitLoader_21_Core::updateUserOption($user_options);
            }
        } catch (Throwable $e) {
            //is_cart may have dependency on wc_get_page_id()
            RabbitLoader_21_Core::on_exception($e);
        }
        return $isCart;
    }


    public static function is_checkout()
    {
        $isCheckout = (function_exists("is_checkout") && is_checkout());
        if ($isCheckout) {
            RabbitLoader_21_Core::getWpUserOption($user_options);
            $user_options['checkout_uri'] = RabbitLoader_21_Util_Core::serverURINoGet();
            RabbitLoader_21_Core::updateUserOption($user_options);
        }
        return $isCheckout;
    }

    public static function is_search()
    {
        return self::$isSearch || (function_exists("is_search") && is_search()) || isset($_GET["s"]) || (stripos(RabbitLoader_21_Util_Core::serverURINoGet(), '/search/') === 0);
    }

    public static function is_flywheel()
    {
        return defined("FLYWHEEL_PLUGIN_DIR");
    }

    public static function get_wp_config()
    {
        $wp_config_path = '';
        if (file_exists(ABSPATH . 'wp-config.php')) {
            $wp_config_path = ABSPATH . 'wp-config.php';
        } elseif (@file_exists(dirname(ABSPATH) . '/wp-config.php') && !@file_exists(dirname(ABSPATH) . '/wp-settings.php')) {
            // config file is not part of another installation
            $wp_config_path = dirname(ABSPATH) . '/wp-config.php';
        } else {
            $wp_config_path = false;
        }
        return $wp_config_path;
    }

    public static function getRLPlugVersion()
    {
        return defined('RABBITLOADER_PLUG_VERSION') ? RABBITLOADER_PLUG_VERSION : (defined('RABBITLOADER_AC_PLUG_VERSION') ? RABBITLOADER_AC_PLUG_VERSION : '');
    }

    public static function _e($txt)
    {
        echo RL21UtilWP::__($txt);
    }
    public static function __($txt)
    {
        return __($txt, RABBITLOADER_TEXT_DOMAIN);
    }
    public static function _n($txt_singular, $txt_plural, $count)
    {
        return _n($txt_singular, $txt_plural, $count, RABBITLOADER_TEXT_DOMAIN);
    }

    /**
     * @return string directory path (without trailing slash) where cache files are stored
     */
    public static function &get_cache_dir($cache_ttl = '')
    {
        $cache_dir = '';

        if (defined('RABBITLOADER_AC_CACHE_DIR')) {
            $cache_dir = RABBITLOADER_AC_CACHE_DIR;
        } else if (defined('RABBITLOADER_CACHE_DIR')) {
            $cache_dir = RABBITLOADER_CACHE_DIR;
        } else {
            $cache_dir = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . "rabbitloader";
        }
        if (!empty($cache_ttl)) {
            $cache_dir = $cache_dir . DIRECTORY_SEPARATOR . $cache_ttl;
        }
        return $cache_dir;
    }

    public static function onPostChange($post_id)
    {
        if (strcmp($post_id, RL21UtilWP::POST_ID_ALL) === 0) {
            self::$purge_queue[RL21UtilWP::POST_ID_ALL] = true;
        } else {
            try {
                if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
                    //no need to purge cache for these posts as they are never displayed on website
                    return;
                }
            } catch (\Throwable $e) {
                RabbitLoader_21_Core::on_exception($e);
            }

            if (empty(self::$purge_queue['post_ids'])) {
                self::$purge_queue['post_ids'] = [];
            }
            self::$purge_queue['post_ids'][$post_id] = true;

            $post_ancestors = get_post_ancestors($post_id);
            if (!empty($post_ancestors)) {
                foreach ($post_ancestors as $parent_id) {
                    self::$purge_queue['post_ids'][$parent_id] = true;
                }
            }

            $wpml_master_post_id = apply_filters('wpml_master_post_from_duplicate', $post_id);
            $wpml_master_post_id = empty($wpml_master_post_id) ? $post_id : $wpml_master_post_id;
            $wpml_posts = apply_filters('wpml_post_duplicates', $wpml_master_post_id);
            if (!empty($wpml_posts) && is_array($wpml_posts)) {
                foreach ($wpml_posts as $lang => $lang_post_id) {
                    self::$purge_queue['post_ids'][$lang_post_id] = true;
                }
                RabbitLoader\SDK\Util::sendHeader("x-rl-wpml_posts: " . json_encode($wpml_posts), false);
            }
            RabbitLoader\SDK\Util::sendHeader("x-rl-posts:" . json_encode(self::$purge_queue['post_ids']), false);
        }
    }

    public static function execute_purge(&$local_purge_count)
    {
        RabbitLoader_21_Core::getWpUserOption($user_options);

        $purge_source = empty(self::$purge_queue['purge_source']) ? '' : self::$purge_queue['purge_source'];
        $clean_cache = !empty($user_options['purge_on_change']);

        if (!empty(self::$purge_queue[RL21UtilWP::POST_ID_ALL])) {
            RabbitLoader_21_Core::purge_all($local_purge_count, $purge_source, $tp_purge_count);
        } else if (!empty(self::$purge_queue['post_ids'])) {
            $urls_to_purge = [];
            RabbitLoader_21_Core::get_common_cache_urls($urls_to_purge);
            foreach (self::$purge_queue['post_ids'] as $post_ID => $val) {
                $post_obj = get_post(intval($post_ID));
                if (!$post_obj) {
                    continue;
                }
                $post_canonical_url = wp_get_canonical_url($post_ID);
                $urls_to_purge[] = $post_canonical_url;
                self::get_all_taxonomies($post_ID, $post_obj->post_type, $tax_ids, $urls_to_purge);
                if ($clean_cache) {
                    RabbitLoader_21_TP::purge_post_id($post_ID, $tp_purge_count);
                }
            }

            $urls_to_purge = array_filter($urls_to_purge);
            $urls_to_purge = array_unique($urls_to_purge);
            $rlSDK = RabbitLoader_21_Core::getSDK();
            foreach ($urls_to_purge as $url) {
                if ($clean_cache) {
                    if ($rlSDK->delete($url)) {
                        $local_purge_count++;
                    }
                    RabbitLoader_21_TP::purge_url($url, $tp_purge_count);
                } else {
                    $rlSDK->onContentChange($url);
                    $local_purge_count++;
                }
                RabbitLoader\SDK\Util::sendHeader("x-rl-url: $url", false);
            }
            do_action('rl_purge_request_complete', $urls_to_purge);
        }
        self::$purge_queue = [];
    }

    private static function get_all_taxonomies($post_ID, $post_type, &$tax_ids, &$tax_urls)
    {
        $tax_ids = array();
        $taxonomies = get_object_taxonomies($post_type);
        foreach ($taxonomies as $taxonomy) {
            $taxonomy_data = get_taxonomy($taxonomy);
            if ($taxonomy_data instanceof WP_Taxonomy && $taxonomy_data->public === false) {
                continue;
            }
            $terms = get_the_terms($post_ID, $taxonomy);

            if (empty($terms) || is_wp_error($terms)) {
                continue;
            } else {
                foreach ($terms as $term) {
                    if (!empty($term)) {
                        $tax_ids[] = $term->term_taxonomy_id;
                        $tax_url = get_term_link($term);
                        if (!is_wp_error($tax_url) && !empty($tax_url)) {
                            $tax_urls[] = $tax_url;
                        }
                    }
                }
            }
        }

        $other_urls_to_merge = [];
        try {
            $other_urls_to_merge[] = get_author_posts_url(get_post_field('post_author', $post_ID));
            $other_urls_to_merge[] = get_author_feed_link(get_post_field('post_author', $post_ID));
            $other_urls_to_merge[] = get_post_type_archive_link($post_type);
            $other_urls_to_merge[] = get_post_type_archive_feed_link($post_type);
            $other_urls_to_merge[] = get_permalink($post_ID);
            $other_urls_to_merge[] = get_post_comments_feed_link($post_ID);
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }
        if (!empty($other_urls_to_merge)) {
            foreach ($other_urls_to_merge as $other_url) {
                if (!empty($other_url) && !is_wp_error($other_url)) {
                    $tax_urls[] = $other_url;
                }
            }
        }
    }

    public static function verifyAjaxNonce()
    {
        if (empty($_POST['rl_nonce']) || !wp_verify_nonce($_POST['rl_nonce'], 'rl-ajax-nonce')) {
            wp_send_json_error(null, 403);
        }
    }
}
