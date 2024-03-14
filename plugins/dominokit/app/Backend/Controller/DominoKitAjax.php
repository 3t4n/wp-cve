<?php

namespace DominoKitApp\Backend\Controller;

defined('ABSPATH') || exit;

class DominoKitAjax
{
    /**
     * @var null
     */
    private static $instance = null;

    public function __construct()
    {
        add_action('wp_ajax_dominokit_option_admin_action', array($this, 'dominokit_option_admin_action_callback'));
        add_action('wp_ajax_nopriv_dominokit_option_admin_action', array($this, 'dominokit_option_admin_action_callback'));

        add_action('wp_ajax_dominokit_toggleWooCart_remove_action', array($this, 'dominokit_toggleWooCart_remove_action_callback'));
        add_action('wp_ajax_nopriv_dominokit_toggleWooCart_remove_action', array($this, 'dominokit_toggleWooCart_remove_action_callback'));

        add_action('wp_ajax_dominokit_shamsi_enabled_action', array($this, 'dominokit_shamsi_enabled_action_callback'));
        add_action('wp_ajax_nopriv_dominokit_shamsi_enabled_action', array($this, 'dominokit_shamsi_enabled_action_callback'));

        add_action('wp_ajax_dominokit_toggleWooCart_admin_action', array($this, 'dominokit_toggleWooCart_admin_action_callback'));
        add_action('wp_ajax_nopriv_dominokit_toggleWooCart_admin_action', array($this, 'dominokit_toggleWooCart_admin_action_callback'));

        add_action('wp_ajax_dominokit_datepicker_enabled_action', array($this, 'dominokit_datepicker_enabled_action_callback'));
        add_action('wp_ajax_nopriv_dominokit_datepicker_enabled_action', array($this, 'dominokit_datepicker_enabled_action_callback'));

        add_action('wp_ajax_dominokit_toggleWooSingleUrl_admin_action', array($this, 'dominokit_toggleWooSingleUrl_admin_action_callback'));
        add_action('wp_ajax_nopriv_dominokit_toggleWooSingleUrl_admin_action', array($this, 'dominokit_toggleWooSingleUrl_admin_action_callback'));

        add_action('wp_ajax_dominokit_toggleWooCartUrl_remove_action', array($this, 'dominokit_toggleWooCartUrl_remove_action_callback'));
        add_action('wp_ajax_nopriv_dominokit_toggleWooCartUrl_remove_action', array($this, 'dominokit_toggleWooCartUrl_remove_action_callback'));

        add_action('wp_ajax_dominokit_price_hide_action', array($this, 'dominokit_price_hide_action_callback'));
        add_action('wp_ajax_nopriv_dominokit_price_hide_action', array($this, 'dominokit_price_hide_action_callback'));

        add_action('wp_ajax_dominokit_price_hide_text_action', array($this, 'dominokit_price_hide_text_action_callback'));
        add_action('wp_ajax_nopriv_dominokit_price_hide_text_action', array($this, 'dominokit_price_hide_text_action_callback'));

        add_action('wp_ajax_dominokit_toggleWooPriceUrl_admin_action', array($this, 'dominokit_toggleWooPriceUrl_admin_action_callback'));
        add_action('wp_ajax_nopriv_dominokit_toggleWooPriceUrl_admin_action', array($this, 'dominokit_toggleWooPriceUrl_admin_action_callback'));

        add_action('wp_ajax_dominokit_price_hide_remove_action', array($this, 'dominokit_price_hide_remove_action_callback'));
        add_action('wp_ajax_nopriv_dominokit_price_hide_remove_action', array($this, 'dominokit_price_hide_remove_action_callback'));

        add_action('wp_ajax_dominokit_replace_text_zero_action', array($this, 'dominokit_replace_text_zero_action_callback'));
        add_action('wp_ajax_nopriv_dominokit_replace_text_zero_action', array($this, 'dominokit_replace_text_zero_action_callback'));

        add_action('wp_ajax_dominokit_remove_replace_text_zero_action', array($this, 'dominokit_remove_replace_text_zero_action_callback'));
        add_action('wp_ajax_nopriv_dominokit_remove_replace_text_zero_action', array($this, 'dominokit_remove_replace_text_zero_action_callback'));
    }

    public function dominokit_datepicker_enabled_action_callback()
    {
        global $dominokit_datepicker_enable;

        if (isset($_POST['opt_wooDatepicker']) && !empty($_POST['opt_wooDatepicker'])) {
            $dominokit_datepicker_enable = sanitize_text_field($_POST['opt_wooDatepicker']);
        }

        $upd_toggleWooDatepicker = update_option('dominokit_option_wooDatepicker', $dominokit_datepicker_enable);

        wp_send_json([
            'result' => $upd_toggleWooDatepicker,
            'message' => __('Saved successfully', 'dominokit')
        ]);
    }

    public function dominokit_shamsi_enabled_action_callback()
    {
        global $dominokit_shamsi_enable;

        $dominokit_shamsi_enable = sanitize_text_field($_POST['opt_wooShamsi']);

        $upd_toggleWooShamsi = update_option('dominokit_option_wooShamsi', $dominokit_shamsi_enable);

        wp_send_json([
            'result' => $upd_toggleWooShamsi,
            'message' => __('Saved successfully', 'dominokit')
        ]);
    }

    public function dominokit_remove_replace_text_zero_action_callback()
    {
        $remove_toggleWooReplaceTxtRemove = delete_option('dominokit_replace_text_zero');

        wp_send_json([
            'result' => $remove_toggleWooReplaceTxtRemove,
            'message' => __('The text was deleted', 'dominokit')
        ]);
    }

    public function dominokit_replace_text_zero_action_callback()
    {
        global $dominokit_replace_text;

        $dominokit_replace_text = sanitize_text_field($_POST['price_replace_text']);

        $upd_toggleReplacePrice = update_option('dominokit_replace_text_zero', $dominokit_replace_text);

        wp_send_json([
            'result' => $upd_toggleReplacePrice,
            'message' => __('Saved successfully', 'dominokit')
        ]);
    }

    public function dominokit_price_hide_remove_action_callback()
    {
        $remove_toggleWooPriceHide = delete_option('dominokit_price_hide_enabled');
        $remove_toggleWooPriceHideText = delete_option('dominokit_price_hide_text');
        $remove_toggleWooPriceHideUrl = delete_option('dominokit_price_hide_url');

        wp_send_json([
            'result' => $remove_toggleWooPriceHideUrl,
            'message' => __('The text was deleted', 'dominokit')
        ]);
    }

    public function dominokit_toggleWooPriceUrl_admin_action_callback()
    {
        global $dominokit_btn_hide_price_url;

        $dominokit_btn_hide_price_url = sanitize_text_field($_POST['btn_hide_price_url']);

        $upd_togglePriceHideUrl = update_option('dominokit_price_hide_url', $dominokit_btn_hide_price_url);

        wp_send_json([
            'result' => $upd_togglePriceHideUrl,
            'message' => __('Saved successfully', 'dominokit')
        ]);
    }

    public function dominokit_price_hide_text_action_callback()
    {
        global $dominokit_btn_hide_price_txt;

        $dominokit_btn_hide_price_txt = sanitize_text_field($_POST['btn_hide_price_txt']);

        $upd_togglePriceHideTxt = update_option('dominokit_price_hide_text', $dominokit_btn_hide_price_txt);

        wp_send_json([
            'result' => $upd_togglePriceHideTxt,
            'message' => __('Saved successfully', 'dominokit')
        ]);
    }

    public function dominokit_price_hide_action_callback()
    {
        global $dominokit_togglePriceHide;

        $dominokit_togglePriceHide = sanitize_text_field($_POST['price_hide_enabled']);

        $upd_togglePriceHide = update_option('dominokit_price_hide_enabled', $dominokit_togglePriceHide);

        wp_send_json([
            'result' => $upd_togglePriceHide,
            'message' => __('Saved successfully', 'dominokit')
        ]);
    }

    public function dominokit_toggleWooCartShopUrl_remove_action_callback()
    {
        $remove_toggleWooShopUrl = delete_option('dominokit_cart_button_shop_url');

        wp_send_json([
            'result' => $remove_toggleWooShopUrl,
            'message' => __('The text was deleted', 'dominokit')
        ]);
    }

    public function dominokit_toggleWooCartUrl_remove_action_callback()
    {
        $remove_toggleWooUrl = delete_option('dominokit_cart_button_product_url');

        wp_send_json([
            'result' => $remove_toggleWooUrl,
            'message' => __('The text was deleted', 'dominokit')
        ]);
    }

    public function dominokit_toggleWooSingleUrl_admin_action_callback()
    {
        global $dominokit_toggleWooUrl;

        $dominokit_toggleWooUrl = sanitize_text_field($_POST['cart_button_product_url']);

        $upd_toggleWooUrl = update_option('dominokit_cart_button_product_url', $dominokit_toggleWooUrl);

        wp_send_json([
            'result' => $upd_toggleWooUrl,
            'error' => __('could not be saved', 'dominokit'),
            'message' => __('Saved successfully', 'dominokit')
        ]);
    }

    public function dominokit_toggleWooCart_remove_action_callback()
    {
        $remove_toggleWooCart = delete_option('dominokit_cart_button_product_txt');

        wp_send_json([
            'result' => $remove_toggleWooCart,
            'message' => __('The text was deleted', 'dominokit')
        ]);
    }

    public function dominokit_toggleWooCart_admin_action_callback()
    {
        global $dominokit_toggleWooCart, $dominokit_toggleWooCart_enabled;

        $dominokit_toggleWooCart = sanitize_text_field($_POST['cart_button_product_txt']);

        $dominokit_toggleWooCart_enabled = sanitize_text_field($_POST['cart_button_product_enabled']);

        $upd_toggleWooCart = update_option('dominokit_cart_button_product_txt', $dominokit_toggleWooCart);

        wp_send_json([
            'result' => $upd_toggleWooCart,
            'message' => __('Saved successfully', 'dominokit')
        ]);
    }

    public function dominokit_option_admin_action_callback()
    {
        global $unavailable_products;

        $unavailable_products = sanitize_text_field($_POST['unavailable_products']);

        $upd_unavailable = update_option('woo_unavailable_products', $unavailable_products);

        wp_send_json([
            'result' => $upd_unavailable,
            'message' => __('Saved successfully', 'dominokit')
        ]);
    }

    /**
     * @return DominoKitAjax|null
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
