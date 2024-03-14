<?php

namespace Shop_Ready\base\assets;

use Shop_Ready\system\base\assets\Assets as Shop_Ready_Resource;
use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;

/*
 * Register all widgets related js and css
 * @since 1.0 
 * $pagenow (string) used in wp-admin See also get_current_screen() for the WordPress Admin Screen API
 * $post_type (string) used in wp-admin
 * $allowedposttags (array)
 * $allowedtags (array)
 * $menu (array)
 */

class Assets extends Shop_Ready_Resource
{

    public function register()
    {

        add_action('wp_enqueue_scripts', [$this, 'enqueue_public_css'], 100);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_public_js'], 100);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_js'], 10);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_css'], 10);
        add_action('elementor/editor/after_enqueue_styles', [$this, 'shop_ready_editor_styles']);

    }

    /*
     * Editor enqueue css
     */
    public function shop_ready_editor_styles($hook)
    {


        $public_css = [
            [
                'handle_name' => 'shop-ready-editor-style',
                'src' => SHOP_READY_URL . 'assets/admin/css/editor.css',
                'file' => SHOP_READY_DIR_PATH . 'assets/admin/css/editor.css',
                'minimize' => false,
                'editor' => true,
                'media' => 'all',
                'deps' => [

                ]
            ]
        ];

        foreach ($public_css as $css) {

            if (file_exists($css['file']) && $css['editor']) {

                $media = isset($css['media']) ? $css['media'] : 'all';
                wp_enqueue_style(str_replace(['_'], ['-'], $css['handle_name']), $css['src'], $css['deps'], filemtime($css['file']), $media);

            }
        }

        unset($public_css);
    }

    /* 
     * enqueue css
     */
    public function enqueue_public_css($hook)
    {

        $public_css = [
            //'shop-ready-public-base',
            'shop-ready-public-default'
        ];

        foreach ($public_css as $handle) {
            wp_enqueue_style($handle);
        }

        unset($public_css);
    }

    /*
     * enqueue css and js
     */
    public function enqueue_css($hook)
    {


        $admin_css = [
            'shop-ready-admin-notice',
        ];

        foreach ($admin_css as $handle) {
            wp_enqueue_style(str_replace(['_'], ['-'], $handle));
        }

        unset($admin_css);
    }
    /*
     * push all admin enqueue 
     */
    public function enqueue_js($hook)
    {

        $admin_js = [
            'shop-ready-admin-base',

        ];

        foreach ($admin_js as $handle) {
            wp_enqueue_script(str_replace(['_'], ['-'], $handle));
        }

        unset($admin_js);

    }

    public function enqueue_public_js($hook)
    {

        $public_js = [
            'shop-ready-public-base',
            'shop-ready-public-base-custom'
        ];

        foreach ($public_js as $handle) {
            wp_enqueue_script(str_replace(['_'], ['-'], $handle));
        }

        $add_to_cart_animation = WReady_Helper::get_global_setting('shop_ready_pro_mini_cart_add_to_cart_animation', 'no');
        $animation_widget_area = WReady_Helper::get_global_setting('shop_ready_pro_mini_cart_add_to_cart_animation_widget_area', 'woo-ready-floating-cart');
        $animation_custom_area = WReady_Helper::get_global_setting('shop_ready_pro_mini_cart_add_to_cart_animation_custom_area', 'no');
        $cart_custom_area_cls = WReady_Helper::get_global_setting('shop_ready_pro_mini_cart_add_to_cart_custom_area_cls', 'woo-ready-floating-cart');
        $animation_opacity = WReady_Helper::get_global_setting('shop_ready_pro_add_t_ani_animation_opacity', '0.3');
        $animation_icon = shop_ready_render_icons(WReady_Helper::get_global_setting('shop_ready_pro_mini_cart_add_to_cart_animation_icon', ''));
        $custom_animation_time = intval(WReady_Helper::get_global_setting('shop_ready_pro_mini_cart_add_to_cart_flying_custom_area_time', '2000'));

        if (!shop_ready_sysytem_module_options_is_active('flying_cart_animation')) {
            $add_to_cart_animation = 'no';
        }

        if ($custom_animation_time < 10) {
            $custom_animation_time = 2000;
        }

        $animation_opacity_size = 0.3;

        if (isset($animation_opacity['size'])) {
            $animation_opacity_size = $animation_opacity['size'];
        }

        wp_localize_script(
            'shop-ready-public-base',
            'shop_ready_obj',
            array(
                'ajaxurl' => esc_url(admin_url('admin-ajax.php')),
                'add_to_cart_animation' => esc_attr($add_to_cart_animation),
                'animation_widget_area' => esc_attr($animation_widget_area),
                'animation_custom_area' => esc_attr($animation_custom_area),
                'cart_custom_area_cls' => esc_attr($cart_custom_area_cls),
                'animation_icon_content' => wp_kses_post($animation_icon),
                'custom_animation_time' => esc_attr($custom_animation_time),
                'animation_opacity' => esc_attr($animation_opacity_size),

            )
        );

        wp_localize_script(
            'shop-ready-public-base',
            'sr_woo_obj',
            array(
                'ajax_add_to_cart_enable' => get_option('woocommerce_enable_ajax_add_to_cart'),
            )
        );

        unset($public_js);
    }


}