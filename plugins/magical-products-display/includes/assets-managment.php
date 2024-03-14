<?php

/**
 *  Magical portducts display plugin style and scripts
 * 
 * 
 */

class mpdAssetsManagement
{
    public static function init()
    {
        add_action('elementor/frontend/after_enqueue_styles', [__CLASS__, 'frontend_widget_styles']);
        add_action("elementor/frontend/after_enqueue_scripts", [__CLASS__, 'frontend_widget_scripts']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'admin_scripts']);
        add_action('elementor/editor/after_enqueue_styles', [__CLASS__, 'editor_widget_styles']);
    }
    public static function frontend_widget_styles()
    {

        wp_register_style('bootstrap-custom', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/bootstrap-custom.css', array(), '5.1.0', 'all');
        wp_register_style('bootstrap-grid',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/bootstrap-grid.min.css', array(), '5.2.0', 'all');
        //swiper style
        wp_register_style('mpd-swiper',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/swiper.min.css', array(), '1.0', 'all');
        //image hover card
        wp_register_style('mgproducts-hover-card',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/imagehover.min.css', array(), '1.0', 'all');
        //tab style
        wp_register_style('mgproducts-tab',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/widgets/mpd-tabs.css', array(), '1.0', 'all');
        //pricing style
        wp_register_style('mgproducts-pricing',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/widgets/mpd-pricing.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        //Accordion style
        wp_register_style('mgproducts-accordion',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/widgets/mpd-accordion.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        //main style
        wp_enqueue_style('mgproducts-style',  MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/mpd-display-style.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
    }
    public static function frontend_widget_scripts()
    {

        wp_register_script("bootstrap-bundle", MAGICAL_PRODUCTS_DISPLAY_ASSETS . "js/bootstrap.bundle.min.js",   array('jquery'), '5.1.0', true);

        wp_register_script("mpd-swiper", MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/swiper.min.js',   array('jquery'), '1.0.5', true);
        wp_register_script("mgproducts-script", MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/main-scripts.js',   array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script("mgproducts-slider", MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/widgets-active/products-slider-active.js',   array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script("mgproducts-carousel", MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/widgets-active/products-carousel-active.js',   array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_register_script("mgproducts-tcarousel", MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/widgets-active/testimonail-carousel-active.js',   array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
        wp_enqueue_script("mgproducts-main", MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'js/main-scripts.js',   array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
    }
    /**
     * Init admin js
     *
     * Include js files 
     *
     * @since 1.0.13
     *
     * @access public
     */
    public static function admin_scripts()
    {
        wp_register_style('admin-info-style', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/admin-info.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
        wp_enqueue_script('mgpd-admin-js', MAGICAL_PRODUCTS_DISPLAY_ASSETS . '/js/admin.js',   array('jquery'), MAGICAL_PRODUCTS_DISPLAY_VERSION, true);
    }

    public static function editor_widget_styles()
    {
        wp_enqueue_style('mpd-editor-style', MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/mpd-editor-style.css', array(), MAGICAL_PRODUCTS_DISPLAY_VERSION, 'all');
    }
}
mpdAssetsManagement::init();
