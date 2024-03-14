<?php

namespace Shop_Ready\extension\elewidgets\assets;

use Shop_Ready\system\base\assets\Assets as Shop_Ready_Resource;

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

        add_action('wp_enqueue_scripts', [$this, 'enqueue_public_js'], 18);

        /*--------------------------------
            ENQUEUE FRONTEND SCRIPTS
        ---------------------------------*/
        add_action('elementor/frontend/after_enqueue_scripts', [$this, 'enqueue_public_js']);
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'enqueue_public_css']);
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_editor_scripts']);

    }

    public function enqueue_editor_scripts()
    {

        wp_enqueue_script(
            'woo-ready-editor-global-settings',
            SHOP_READY_URL . 'assets/admin/js/editor-global-settings.js',
            ['jquery', 'elementor-editor'],
            time(),
            true
        );
    }

    /*
     * enqueue css
     */
    public function enqueue_public_css($hook)
    {

        $public_css = [];

        if (shop_ready_sysytem_module_options_is_active('fontawesome')) {

            $public_css[] = 'fontawesome';
        }

        foreach ($public_css as $handle) {
            wp_enqueue_style(str_replace(['_'], ['-'], $handle));
        }

        unset($public_css);
    }



    /*
     * enqueue css and js
     */
    public function enqueue_css($hook)
    {


        $admin_css = [
            'shop-ready-admin-base'
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
            'shop-ready-admin-base'
        ];

        foreach ($admin_js as $handle) {
            wp_enqueue_script(str_replace(['_'], ['-'], $handle));
        }

        unset($admin_js);

    }

    public function enqueue_public_js($hook)
    {

        $public_js = [
            'shop-ready-elementor-base',
        ];

        foreach ($public_js as $handle) {

            wp_enqueue_script(str_replace(['_'], ['-'], $handle));
        }

        unset($public_js);

        // Check Astra
        if ('astra' == strtolower(wp_get_theme())) {

            wp_enqueue_style('shop-ready-astra', SHOP_READY_URL . 'src/extension/elewidgets/assets/css/astra.css');
        }
        // Check OcceanWP
        if ('oceanwp' == strtolower(wp_get_theme())) {

            wp_enqueue_style('shop-ready-oceanwp', SHOP_READY_URL . 'src/extension/elewidgets/assets/css/oceanwp.css');
        }

    }


}