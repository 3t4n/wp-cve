<?php

namespace ShopWP;

use ShopWP\Options;
use ShopWP\Utils;

defined('ABSPATH') ?: exit();

class Shortcodes
{
    public $Render_Products;
    public $Render_Cart;
    public $Render_Search;
    public $Render_Storefront;
    public $Render_Collections;
    public $Render_Translator;
    public $Render_Reviews;
    public $plugin_settings;

    public function __construct(
        $Render_Products,
        $Render_Cart,
        $Render_Search,
        $Render_Storefront,
        $Render_Collections,
        $Render_Translator,
        $Render_Reviews,
        $plugin_settings
    ) {
        $this->Render_Products = $Render_Products;
        $this->Render_Cart = $Render_Cart;
        $this->Render_Search = $Render_Search;
        $this->Render_Storefront = $Render_Storefront;
        $this->Render_Collections = $Render_Collections;
        $this->Render_Translator = $Render_Translator;
        $this->Render_Reviews = $Render_Reviews;
        $this->plugin_settings = $plugin_settings;
    }

    public function shortcode($fn)
    {
        \ob_start();

        $fn();

        $content = \ob_get_contents();
        \ob_end_clean();

        return $content;
    }

    public function shortcode_wps_products_title($shortcode_atts = [])
    {

        if (empty($shortcode_atts)) {
            $shortcode_atts = [];
        }

        return $this->shortcode(function () use ($shortcode_atts) {
            $this->Render_Products->title($shortcode_atts);
        });
    }

    public function shortcode_wps_products_description($shortcode_atts = [])
    {

        if (empty($shortcode_atts)) {
            $shortcode_atts = [];
        }

        return $this->shortcode(function () use ($shortcode_atts) {
            $this->Render_Products->description($shortcode_atts);
        });
    }

    public function shortcode_wps_products_pricing($shortcode_atts = [])
    {

        if (empty($shortcode_atts)) {
            $shortcode_atts = [];
        }

        return $this->shortcode(function () use ($shortcode_atts) {
            $this->Render_Products->pricing($shortcode_atts);
        });
    }

    public function shortcode_wps_products_buy_button($shortcode_atts = [])
    {
        if (empty($shortcode_atts)) {
            $shortcode_atts = [];
        }

        return $this->shortcode(function () use ($shortcode_atts) {
            $this->Render_Products->buy_button($shortcode_atts);
        });
    }

    public function shortcode_wps_products($shortcode_atts = [])
    {

        if (empty($shortcode_atts)) {
            $shortcode_atts = [];
        }

        return $this->shortcode(function () use ($shortcode_atts) {
            $this->Render_Products->products($shortcode_atts);
        });
    }

    public function shortcode_wps_product_gallery($shortcode_atts = [])
    {

        if (empty($shortcode_atts)) {
            $shortcode_atts = [];
        }

        return $this->shortcode(function () use ($shortcode_atts) {
            $this->Render_Products->gallery($shortcode_atts);
        });
    }

    public function shortcode_wps_cart_icon($shortcode_atts = [])
    {

        if (empty($shortcode_atts)) {
            $shortcode_atts = [];
        }

        return $this->shortcode(function () use ($shortcode_atts) {
            $this->Render_Cart->cart_icon($shortcode_atts);
        });
    }

    public function shortcode_wps_collections($shortcode_atts = [])
    {

        if (empty($shortcode_atts)) {
            $shortcode_atts = [];
        }        

        return $this->shortcode(function () use ($shortcode_atts) {
            $this->Render_Collections->collections($shortcode_atts);
        });
    }

    public function wps_cart()
    {
        $this->Render_Cart->cart();
    }


    public function init()
    {
        \add_shortcode('wps_products', [$this, 'shortcode_wps_products']);
        \add_shortcode('wps_products_title', [
            $this,
            'shortcode_wps_products_title',
        ]);
        \add_shortcode('wps_products_description', [
            $this,
            'shortcode_wps_products_description',
        ]);
        \add_shortcode('wps_products_pricing', [
            $this,
            'shortcode_wps_products_pricing',
        ]);
        \add_shortcode('wps_products_buy_button', [
            $this,
            'shortcode_wps_products_buy_button',
        ]);
        \add_shortcode('wps_products_gallery', [
            $this,
            'shortcode_wps_product_gallery',
        ]);
        \add_shortcode('wps_collections', [$this, 'shortcode_wps_collections']);


        \add_shortcode('wps_cart_icon', [$this, 'shortcode_wps_cart_icon']);

        if (!is_admin() && $this->plugin_settings['general']['cart_loaded']) {
            \add_action('wp_footer', [$this, 'wps_cart']);
        }
    }
}
