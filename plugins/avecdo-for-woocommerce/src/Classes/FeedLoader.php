<?php

    namespace Avecdo\Woocommerce\Classes;

    use Avecdo\Woocommerce\Models\Model;

    if (!defined('ABSPATH')) exit;

    class FeedLoader
    {
        public static $instance;

        public static function make()
        {
            if (is_null(static::$instance)) {
                static::$instance = new static();
            }

            return static::$instance;
        }

        private $model;

        public function __construct()
        {
            $this->model = Model::make($GLOBALS['wpdb']);
        }


        public function init()
        {
            global $wp;

            /*
             * Allow WP to accept URL's like index.php?avecdo-api&foo=bar
             */
            $wp->add_query_var('avecdo-api');
        }


        public function apiInit()
        {
            if (!isset($GLOBALS['wp']->query_vars['avecdo-api'])) {
                return;
            }

            Plugin::make()->catchApiRequest();
        }


        public function clearCache()
        {
            add_filter('wp_feed_cache_transient_lifetime', array($this, 'setFeedCache'));
        }


        /**
         * Hack to "clear" the feed cache.
         */
        public function setFeedCache($seconds)
        {
            return 1;
        }


        /**
         * Detects if WooCommerce is found within the activated plugins.
         * @return bool
         */
        public function isWoocommerceActive()
        {
            if( is_plugin_active_for_network( 'woocommerce/woocommerce.php') || is_plugin_active( 'woocommerce/woocommerce.php')){
                return true;
            }

            $active_plugins = array();

            $active_plugins = is_multisite() ?
                array_keys(get_site_option('active_sitewide_plugins', array())) :
                apply_filters('active_plugins', get_option('active_plugins', array()));

            foreach ($active_plugins as $active_plugin) {
                $active_plugin = explode('/', $active_plugin);
                if (isset($active_plugin[1]) && $active_plugin[1] === 'woocommerce.php') {
                    return true;
                }
            }

            return false;
        }
    }
