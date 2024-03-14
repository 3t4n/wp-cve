<?php

namespace ShopWP;

use ShopWP\Options;
use ShopWP\Utils;
use ShopWP\Utils\Data;

if (!defined('ABSPATH')) {
    exit();
}

class Data_Bridge
{
    public $plugin_settings;
    public $Render_Products_Defaults;
    public $Render_Cart_Defaults;

    public function __construct($plugin_settings, $Render_Cart_Defaults, $Render_Collections_Defaults, $Render_Products_Defaults, $Render_Search_Defaults, $Render_Storefront_Defaults, $Render_Translator_Defaults, $Render_Reviews_Defaults)
    {
        $this->plugin_settings              = $plugin_settings;

        $this->Render_Cart_Defaults         = $Render_Cart_Defaults;
        $this->Render_Collections_Defaults  = $Render_Collections_Defaults;
        $this->Render_Products_Defaults     = $Render_Products_Defaults;
        $this->Render_Search_Defaults       = $Render_Search_Defaults;
        $this->Render_Storefront_Defaults   = $Render_Storefront_Defaults;
        $this->Render_Translator_Defaults   = $Render_Translator_Defaults;
        $this->Render_Reviews_Defaults      = $Render_Reviews_Defaults;

    }

    public function replace_lang_in_rest_url($found_lang_code, $rest_url) {
        return str_replace("/" . $found_lang_code, "", $rest_url);
    }

    public function sanitize_rest_url_for_translate_press($rest_url, $TRP_LANGUAGE) {

        if ($TRP_LANGUAGE === 'en_US') {
            return $rest_url;
        }
        
        $trp = \TRP_Translate_Press::get_trp_instance();
        $url_converter = $trp->get_component('url_converter');
        $found_lang_code = $url_converter->get_url_slug($TRP_LANGUAGE);

        return $this->replace_lang_in_rest_url($found_lang_code, $rest_url);
    }

    public function sanitize_rest_url_for_wpml($rest_url) {

        $current_lang = apply_filters( 'wpml_current_language', NULL );

        return $this->replace_lang_in_rest_url($current_lang, $rest_url);
    }

    public function replace_rest_protocol()
    {
        $rest_url = \get_rest_url();

        global $TRP_LANGUAGE;

        if (!empty($TRP_LANGUAGE)) {
            $rest_url = $this->sanitize_rest_url_for_translate_press($rest_url, $TRP_LANGUAGE);

        } else if (defined('WPML_PLUGIN_BASENAME')) {
            $rest_url = $this->sanitize_rest_url_for_wpml($rest_url);
        }

        if (\is_ssl()) {
            return str_replace("http://", "https://", $rest_url);
        }

        return $rest_url;
    }

    public function starts_with($haystack, $needle) {
        $length = strlen( $needle );
        return substr( $haystack, 0, $length ) === $needle;
    }
    
    public function ends_with($haystack, $needle) {
        
        $length = strlen( $needle );
        
        if (!$length) {
        return true;
        }
    
        return substr( $haystack, -$length ) === $needle;
    
    }
    
    public function get_available_pages()
    {
        $pages = \get_pages();
    
        $final_pages = array_map(function ($page) {
            return [
                'id' => $page->ID,
                'post_title' => $page->post_title,
                'permalink' => get_permalink($page->ID)
            ];
        }, $pages);

        return $final_pages;
    }    

    public function get_has_connection($connection)
    {
        if (empty($connection)) {
            return false;
        }

        if (
            empty($connection['api_password']) ||
            empty($connection['domain'])
        ) {
            return false;
        }

        return true;
    }

    public function get_settings($is_admin)
    {
        global $post;

        $active_metafields = maybe_unserialize(Options::get('shopwp_active_metafields'));

        if (empty($active_metafields)) {
            $active_metafields = false;
        }

        $general = Data::sanitize_settings($this->plugin_settings['general']);
        
        return [
            'cart' => $this->Render_Cart_Defaults->all_attrs(),
            'collections' => $this->Render_Collections_Defaults->all_attrs(),
            'products' => $this->Render_Products_Defaults->all_attrs(),
            'search' => $this->Render_Search_Defaults->all_attrs(),
            'storefront' => $this->Render_Storefront_Defaults->all_attrs(),
            'translator' => $this->Render_Translator_Defaults->all_attrs(),
            'reviews' => $this->Render_Reviews_Defaults->all_attrs(),
            'syncing' => [
                'reconnectingWebhooks' => false,
                'hasConnection' => $this->get_has_connection(
                    $this->plugin_settings['connection']
                ),
                'isSyncing' => false,
                'manuallyCanceled' => false,
                'isClearing' => false,
                'isDisconnecting' => false,
                'isConnecting' => false,
            ],
            'general' => $general,
            'connection' => [
                'masked' => Data::mask_api_values($this->plugin_settings['connection']),
                'storefront' => [
                    'domain' =>
                        $this->plugin_settings['connection']['domain'],
                    'storefrontAccessToken' =>
                        $this->plugin_settings['connection'][
                            'storefront_access_token'
                        ],
                ]
            ],
            'notices' => $this->plugin_settings['notices'],
            'api' => [
                'namespace' => SHOPWP_SHOPIFY_API_NAMESPACE,
                'restUrl' => $this->replace_rest_protocol(),
                'nonce' => \wp_create_nonce('wp_rest'),
            ],
            'misc' => [
                'availablePages' => $is_admin ? $this->get_available_pages() : false,
                'postID' => $post ? $post->ID : false,
                'postURL' => $is_admin && $post ? get_permalink($post->ID) : false,
                'isMobile' => \wp_is_mobile(),
                'isSSL' => \is_ssl(),
                'pluginsDirURL' => \plugin_dir_url(dirname(__FILE__)),
                'pluginsDistURL' => plugin_dir_url(dirname(__FILE__)) . 'dist/',
                'adminURL' => \get_admin_url(),
                'siteUrl' => \site_url(),
                'isSingularProducts' => \is_singular('wps_products'),
                'isSingularCollections' => \is_singular('wps_collections'),
                'siteDomain' => parse_url(site_url())['host'],
                'isAdmin' => $is_admin,
                'latestVersion' => SHOPWP_NEW_PLUGIN_VERSION,
                'isPro' => false,
                'hasTranslator' => defined('SHOPWP_DOWNLOAD_ID_TRANSLATOR_EXTENSION') ? true : false,
                'hasRecharge' => defined('SHOPWP_DOWNLOAD_ID_RECHARGE_EXTENSION') ? true : false,
                'hasElementor' => defined('SHOPWP_DOWNLOAD_ID_ELEMENTOR_EXTENSION') ? true : false,
                'hasYotpo' => defined('SHOPWP_DOWNLOAD_ID_YOTPO_REVIEWS_EXTENSION') ? true : false,
                'timers' => [
                    'syncing' => false,
                ]
            ],
            'collectionsData' => $is_admin ? maybe_unserialize(Transients::get('shopwp_all_collections')) : false,
            'metafields' => $is_admin ? $active_metafields : false
        ];
    }

    public function stringify_settings($settings)
    {
        $settings_encoded_js_string = wp_json_encode(
            Utils::convert_underscore_to_camel_array($settings)
        );

        return "const shopwp = Object.freeze(" . $settings_encoded_js_string . ")";
    }

    public function add_settings_script($script_dep, $is_admin)
    {
        $string_settings = $this->stringify_settings($this->get_settings($is_admin));

        wp_add_inline_script($script_dep, $string_settings, 'before');
    }
}
