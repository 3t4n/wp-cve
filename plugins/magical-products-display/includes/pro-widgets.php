<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class PP_Config.
 */
class mgpProWidgets
{
    function __construct()
    {

        add_filter('elementor/editor/localize_settings', [$this, 'get_promotion_widgets']);
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'editor_scripts']);
    }

    function editor_scripts()
    {
        wp_enqueue_script("mpdadmin-el-editor", MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/el-editor.js', array('jquery'), '1.0.7', true);
    }

    public function get_promotion_widgets($config)
    {

        $promotion_widgets = [];

        if (isset($config['promotionWidgets'])) {
            $promotion_widgets = $config['promotionWidgets'];
        }

        $pro_widgets = $this::get_pro_widgets();

        $combine_array = array_merge($promotion_widgets, $pro_widgets);

        $config['promotionWidgets'] = $combine_array;

        return $config;
    }



    /**
     * Get Widget List.
     *
     * @since 1.2.9.4
     *
     * @return array The Widget List.
     */
    public static function get_pro_widgets()
    {
        $pro_widgets = [
            [
                'name'       => 'mgppro_compare',
                'title'      => __('Compare Table', 'magical-products-display'),
                'categories' => '["mpd-productwoo"]',
                'keywords'   => ['compare', 'table', 'price', 'pricing', 'product'],
                'icon'       => 'eicon-price-table',
            ],
            [
                'name'       => 'mgppro_pdetails',
                'title'      => __('Product Pro Details', 'magical-products-display'),
                'categories' => '["mpd-productwoo"]',
                'keywords'   => ['product', 'details', 'countdown', 'display'],
                'icon'       => 'eicon-product-info',
            ],
            [
                'name'       => 'mgppro_countdown',
                'title'      => __('Advance Countdown', 'magical-products-display'),
                'categories' => '["mpd-productwoo"]',
                'keywords'   => ['countdown', 'offer', 'product', 'banner'],
                'icon'       => 'eicon-banner',
            ],
            [
                'name'       => 'mgppro_hotspot',
                'title'      => __('Product Hotspots', 'magical-products-display'),
                'categories' => '["mpd-productwoo"]',
                'keywords'   => ['hotspot', 'image', 'product', 'Hotspots', 'marker'],
                'icon'       => 'eicon-image-hotspot',
            ],
            [
                'name'       => 'mgppro_ticker',
                'title'      => __('Product Ticker', 'magical-products-display'),
                'categories' => '["mpd-productwoo"]',
                'keywords'   => ['ticker', 'latest', 'product', 'woo', 'animation'],
                'icon'       => 'eicon-posts-ticker',
            ],


        ];



        return $pro_widgets;
    }
}
$mgadmin_notices = new mgpProWidgets();
