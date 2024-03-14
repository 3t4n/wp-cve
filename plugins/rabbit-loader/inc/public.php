<?php

class RabbitLoader_21_Public
{

    static $skip_reason = '';
    const skip_reason_pm = "me-mode";
    const skip_reason_ep = "exclude-pattern";
    const skip_reason_us = "user-session";
    const no_optimization = "rl-no-optimization";
    static $add_action_called = false;
    static $is_origin = false;
    static $cache_served = false;
    static $cacheFile = null;

    public static function addActions()
    {
        if (self::$add_action_called) {
            return;
        }
        self::$add_action_called = true;
        //is_user_logged_in is a pluggable function and you could get a fatal error if you call it too early.
        add_action('init', 'RabbitLoader_21_Public::init', 10);
        add_action('shutdown', 'RabbitLoader_21_Public::shutdown', 10000);
        add_filter('wp_redirect', 'RabbitLoader_21_Public::wp_redirect', 10, 2);
        add_filter('redirect_canonical', 'RabbitLoader_21_Public::redirect_canonical', 10, 2);
        add_filter('pre_handle_404', 'RabbitLoader_21_Public::pre_handle_404', 10, 2);
        add_filter('paginate_links', 'RabbitLoader_21_Public::paginate_links', 10, 1);
        add_filter('nonce_life', 'RabbitLoader_21_Public::nonce_life');

        RabbitLoader_21_CanonicalUrl::init();
        RL21UtilWP::init();

        //Third party theme hooks
        add_action('kirki_output_inline_styles', function ($value) {
            #theme developed using kirki framework may generate lots of inline css referring to font files making the page load slow
            #https://github.com/kirki-framework/kirki/blob/3a5d5093b1a99d1c7b813608479fe8fc9348b6a3/packages/kirki-framework/module-css/src/CSS.php
            $value = false;
            return $value;
        }, -1);
    }

    public static function init()
    {
        if (current_user_can('manage_options')) {
            add_action('admin_bar_menu', 'RabbitLoader_21_Public::adminBarMenu', 50);
            add_action('wp_enqueue_scripts', 'RabbitLoader_21_Public::adminBarScript');
        }

        //few checks inside can_cache_request() can only be done when WordPress init hook is called.
        $rlSDK = RabbitLoader_21_Core::getSDK();
        if (!self::can_cache_request()) {
            $rlSDK->ignoreRequest(self::$skip_reason);
        }
    }

    public static function shutdown()
    {
    }

    private static function can_cache_request()
    {
        if (!empty(self::$skip_reason)) {
            return false;
        }
        if (RabbitLoader_21_Util_Core::get_request_type() != 'get') {
            //Not a GET request, return
            self::$skip_reason = 'method-' . RabbitLoader_21_Util_Core::get_request_type();
            return false;
        }

        if (RL21UtilWP::is_user_logged_in() || RL21UtilWP::is_login_page()) {
            //we are not serving cached content to logged in users
            self::$skip_reason = self::skip_reason_us;
            return false;
        }

        if (RL21UtilWP::is_ajax()) {
            self::$skip_reason = 'ajax';
            return false;
        }

        if (!empty($_COOKIE['woocommerce_items_in_cart'])) {
            //TBD- user has some items in cart, in future version we will check how to serve a cached page with cart
            self::$skip_reason = 'cart-items';
            return false;
        }

        if (empty($_SERVER["HTTP_HOST"]) || RL21UtilWP::is_cli() || !empty($_GET["mwprid"])) {
            self::$skip_reason = 'cli';
            return false;
        }

        global $wp_query;
        if (isset($wp_query) && $wp_query->is_404()) {
            self::$skip_reason = '404-not-found';
            return false;
        }

        if (RL21UtilWP::is_search()) {
            self::$skip_reason = 'search';
            return false;
        }

        if (RL21UtilWP::is_cart()) {
            self::$skip_reason = 'cart';
            return false;
        }

        if (RL21UtilWP::is_checkout()) {
            self::$skip_reason = 'checkout';
            return false;
        }

        RabbitLoader_21_Core::getWpUserOption($user_options);

        if (!empty($user_options['cart_uri']) && strcasecmp(RabbitLoader_21_Util_Core::serverURINoGet(), $user_options['cart_uri']) == 0) {
            self::$skip_reason = 'cart';
            return false;
        }

        if (!empty($user_options['checkout_uri']) && strcasecmp(RabbitLoader_21_Util_Core::serverURINoGet(), $user_options['checkout_uri']) == 0) {
            self::$skip_reason = 'checkout';
            return false;
        }

        if (!empty($user_options['exclude_patterns'])) {
            $exclude_patterns = explode("\n", $user_options['exclude_patterns']);
            $rlSDK = RabbitLoader_21_Core::getSDK();
            $rlSDK->skipForPaths($exclude_patterns);
        }

        if (!empty(self::$skip_reason)) {
            self::$skip_reason = self::$skip_reason;
            return false;
        }

        return true;
    }

    public static function getSkipReason()
    {
        return self::$skip_reason;
    }

    public static function process_incoming_request($mode = 'ac')
    {
        try {
            RabbitLoader\SDK\Util::sendHeader("x-rl-mode: $mode", false);
            $rlSDK = RabbitLoader_21_Core::getSDK();

            RabbitLoader_21_Core::getWpUserOption($user_options);
            if (!empty($user_options['ignore_params'])) {
                $ignore_params = explode("\n", $user_options['ignore_params']);
                if (!empty($ignore_params)) {
                    $rlSDK->ignoreParams($ignore_params);
                }
            }
            if (!empty($user_options['private_mode_val'])) {
                $rlSDK->setMeMode();
            }

            if (!self::can_cache_request()) {
                $rlSDK->ignoreRequest(self::$skip_reason);
            }
            $rlSDK->registerPurgeCallback(function ($url) {
                $tp_purge_count = 0;
                RabbitLoader_21_TP::purge_url($url, $tp_purge_count);
            });
            $rlSDK->process();
        } catch (Throwable $e) {
            RabbitLoader_21_Core::on_exception($e);
        }
    }


    public static function adminBarMenu($admin_bar)
    {
        $top_menu = array(
            'id' => 'rabbitloader_top_menu',
            'title' => 'RabbitLoader',
            'href' => admin_url('options-general.php?page=rabbitloader')
        );

        $list_menu = array(
            'parent' => 'rabbitloader_top_menu',
            'id'     => 'rabbitloader_purge_page',
            'title'  =>  'Purge cache for this page',
            'href'   =>  "#",
            'meta' => array(
                'class' => 'rabbitloader_purge_page',
            )
        );

        $admin_bar->add_node($top_menu);
        $admin_bar->add_node($list_menu);
    }

    public static function adminBarScript()
    {
        global $post;

        wp_enqueue_script('rabbitloader-index', RABBITLOADER_PLUG_URL . 'admin/js/index.js', ['jquery'], RABBITLOADER_PLUG_VERSION);
        wp_localize_script('rabbitloader-index', 'rabbitloader_local_vars', [
            'admin_ajax' => admin_url('admin-ajax.php'),
            'post_id' => empty($post) ? 0 : $post->ID,
            'rl_nonce' => wp_create_nonce('rl-ajax-nonce')
        ]);
    }

    public static function wp_redirect($location, $status)
    {
        if (!empty($location)) {
            self::$skip_reason = 'redirect-' . $status;
        }
        return $location;
    }

    public static function redirect_canonical($redirect_url, $requested_url)
    {
        if (!empty($redirect_url)) {
            self::$skip_reason = 'redirect-canonical';
        }
        return $redirect_url;
    }

    public static function pre_handle_404($preempt, $wp_query)
    {
        if ($preempt) {
            self::$skip_reason = '404';
        }
        return $preempt;
    }

    public static function paginate_links($link)
    {
        if (!empty($link)) {
            $unsets = ['rl-no-optimization', 'norl', 'rl-warmup', 'rltest', 'rl-rand', 'rl-only-after'];
            list($urlpart, $qspart) = array_pad(explode('?', $link), 2, '');
            parse_str($qspart, $qsvars);

            foreach ($unsets as $key) {
                if (isset($qsvars[$key])) {
                    unset($qsvars[$key]);
                }
            }

            $newqs = http_build_query($qsvars);
            $link = $urlpart . (empty($newqs) ?  '' : '?' . $newqs);
            return $link;
        }
    }

    public static function nonce_life($life)
    {
        $referer = empty($_SERVER["HTTP_REFERER"]) ? '' : $_SERVER["HTTP_REFERER"];
        $rlSDK = RabbitLoader_21_Core::getSDK();

        if (RL21UtilWP::is_user_logged_in()) {
            return $life;
        } elseif (intval($life) > RabbitLoader_21_Core::ORPHANED_LONG_AGE_SEC) {
            return $life;
        } else if ($rlSDK->isWarmUp()) {
            return RabbitLoader_21_Core::ORPHANED_LONG_AGE_SEC;
        } else if (RL21UtilWP::is_ajax() && (!empty($_COOKIE["rlCached"]) || stripos($referer, self::no_optimization) !== false)) {
            //for ajax, if cookie is set, the page was cached who called the ajax
            //or the referer page had no_optimization that matched the previous else condition
            return RabbitLoader_21_Core::ORPHANED_LONG_AGE_SEC;
        } else {
            //unhandled scenarios
        }
        return $life;
    }
}
