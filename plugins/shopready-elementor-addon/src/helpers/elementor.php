<?php
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Core\Files\File_Types\Svg;

/**************************** ***********
 * Shop Ready Elementor Plugin
 *
 * Elementor related function 
 * @since 1.0
 * @author Quomodosoft
 *
 ************** **************************/

if (!function_exists('shop_ready_get_elementor_saved_templates')) {
    /**
     * optional parameter
     * Category name
     * return array element templates
     * @since 1.0
     */
    function shop_ready_get_elementor_saved_templates($category = false)
    {

        static $_template_kits = null;

        if (is_null($_template_kits)) {

            $args = array(
                'numberposts' => -1,
                'post_type' => 'elementor_library',
                'post_status' => 'publish',
                'orderby' => 'title',
                'order' => 'ASC',
            );

            if ($category) {

                $args['tax_query'][] = array(
                    'taxonomy' => 'elementor_library_category',
                    'field' => 'slug',
                    'terms' => $category
                );

            }

            $product_args = array(
                'numberposts' => -1,
                'post_type' => 'product',
                'post_status' => ['publish'],
                'orderby' => 'title',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'shop_ready_template',
                        'value' => 1,
                    ),
                ),
            );

            // custom product add 

            $product_kits = get_posts($product_args);
            $_template_kits = get_posts($args);
            $_template_kits = array_merge($product_kits, $_template_kits);
        }

        return $_template_kits;
    }
}



if (!function_exists('shop_ready_get_elementor_templates_arr')) {

    /**
     * use in elementor widget
     * return array
     * @author quomodsoft.com
     */
    function shop_ready_get_elementor_templates_arr()
    {

        static $_template_kits = null;

        if (is_null($_template_kits)) {
            $_template_kits[''] = esc_html__('Select Template', 'shopready-elementor-addon');
            $temp = shop_ready_get_elementor_saved_templates();

            if (is_array($temp)) {
                foreach ($temp as $item) {
                    $_template_kits[$item->ID] = $item->post_name . ' - ' . $item->ID;
                }
            }

        }

        return $_template_kits;
    }

}



if (!function_exists('shop_ready_gl_get_setting')) {
    /**
     * Helper function to return a setting.
     *
     * Saves 2 lines to get kit, then get setting. Also caches the kit and setting.
     * @since 1.0
     * @author quomodsoft.com
     * @param  string $setting_id
     * Plugin::$instance->kits_manager->get_active_kit_for_frontend()->get_settings_for_display('wr_login_redirect');
     * @return string|array same as the Elementor internal function does.
     */
    function shop_ready_gl_get_setting($setting_id, $default = '')
    {

        if (!did_action('elementor/loaded')) {
            return;
        }

        global $woo_ready_el_global_settings;

        $return = $default;

        if (!isset($woo_ready_el_global_settings['kit_settings'])) {
            $kit = \Elementor\Plugin::$instance->documents->get(\Elementor\Plugin::$instance->kits_manager->get_active_id(), false);
            $woo_ready_el_global_settings['kit_settings'] = method_exists($kit, 'get_settings') ? $kit->get_settings() : '';
        }

        if (isset($woo_ready_el_global_settings['kit_settings'][$setting_id])) {
            $return = $woo_ready_el_global_settings['kit_settings'][$setting_id];
        }

        return apply_filters('shop_ready_el_global_' . $setting_id, $return);
    }

}


if (!function_exists('shop_ready_show_or_hide')) {

    /**
     * Helper function to show/hide elements
     *
     * This works with switches control, if the setting ID that has been passed is toggled on, we'll return show, otherwise we'll return hide
     *
     * @param  string $setting_id
     * @return string|array same as the Elementor internal function does.
     */
    function shop_ready_show_or_hide($setting_id)
    {
        return ('yes' === shop_ready_gl_get_setting($setting_id) ? 'wr-show' : 'wr-hide');
    }
}

if (!function_exists('shop_ready_get_page_meta')) {

    /**
     * shop_ready_get_page_meta
     * @return string
     * @since 1.0
     * @param meta_key
     * @param page_id
     */
    function shop_ready_get_page_meta($key, $page_id = null)
    {

        try {

            $id = get_the_ID();
            if (is_numeric($page_id)) {
                $id = $page_id;
            }

            $current_doc = \Elementor\Plugin::instance()->documents->get($id);
            if ($current_doc) {
                return $current_doc->get_settings($key);
            }


        } catch (\Exception $e) {
            return false;
        }

        return false;
    }

}

if (!function_exists('shop_ready_is_elementor_mode')) {
    /**
     * Elementor Editor And Preview Mode Check
     * @since 1.0
     */
    function shop_ready_is_elementor_mode()
    {

        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            return true;
        }

        if (isset($_GET['preview_id']) && isset($_GET['preview']) && $_GET['preview_nonce']) {
            return true;
        }
    }
}


if (!function_exists('shop_ready_render_icons')) {

    function shop_ready_render_icons($content = array(), $class = '')
    {

        if (!is_array($content)) {
            return false;
        }
        //elementor-icons-fa-

        if (is_array($content['value'])) {
            $svg_icon = $content['value']['url'];
        } else {
            $font_icon = $content['value'];
        }

        if (!is_array($content['value']) && $font_icon) {

            wp_enqueue_style('elementor-icons-' . $content['library']);

            if ($class) {
                return '<i class="' . $class . ' ' . esc_attr($font_icon) . '"></i>';
            } else {
                return '<i class="' . esc_attr($font_icon) . '"></i>';
            }
        }


        if ($content['library'] == 'svg' && isset($content['value']['id'])) {

            return Svg::get_inline_svg($content['value']['id']);

        }
    }
}

if (!function_exists('shop_ready_load_wc')) {

    function shop_ready_load_wc()
    {
        include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
        include_once WC_ABSPATH . 'includes/class-wc-cart.php';
        if (is_null(WC()->cart)) {
            wc_load_cart();
        }
    }
}

if (!function_exists('shop_ready_get_active_breakpoint')) {

    function shop_ready_get_active_breakpoint()
    {

        $breakpoints = \Elementor\Plugin::$instance->breakpoints->get_breakpoints();
        static $active_braekpoint = [];

        if (empty($active_braekpoint)) {

            foreach ($breakpoints as $key => $brk) {

                if ($brk->is_enabled()) {
                    $active_braekpoint[$key] = $brk;
                }

            }
        }

        return $active_braekpoint;

    }
}

if (!function_exists('shop_ready_widgets_class_dir_list')):

    function shop_ready_widgets_class_dir_list($dir)
    {

        $classes = [];
        $classes_dir = [];

        $finder = new \Symfony\Component\Finder\Finder();
        $finder->directories()->in($dir)->depth('== 0');
        $found_dir = [];

        foreach ($finder as $_dir) {

            $finder_file = new \Symfony\Component\Finder\Finder();
            $finder_file->files()->in($dir . '/' . basename($_dir->getRealPath()))->contains('namespace');

            foreach ($finder_file as $__file) {

                $filePath = $__file->getRealPath();
                $classes_dir[basename($_dir->getRealPath())][] = strtok(basename($filePath), '.');
            }

        }

        return $classes_dir;
    }

endif;

if (!function_exists('shop_ready_widgets_class_list')):

    function shop_ready_widgets_class_list($dir)
    {

        $classes = [];

        $finder = new \Symfony\Component\Finder\Finder();
        $finder->directories()->in($dir)->depth('== 1');
        $finder->files()->in($dir);
        $finder->files()->contains('namespace');

        foreach ($finder as $file) {

            $absoluteFilePath = $file->getRealPath();
            if (!is_null(basename($absoluteFilePath))) {
                $classes[] = strtok(basename($absoluteFilePath), '.');
            }

        }

        return $classes;

    }

endif;

if (!function_exists('shop_ready_get_template_by_document')):

    function shop_ready_get_template_by_document($post_id)
    {

        $config = shop_ready_templates_config()->all();

        $return_data = array_filter($config, function ($var) use ($post_id) {
            return $var['id'] == $post_id;
        });

        if (empty($return_data)) {
            return false;
        }

        $keys = array_keys($return_data);

        if (isset($keys[0]) && is_string($keys[0])) {
            return [
                'sr_tpl' => 'shop_ready_dashboard',
                'tpl_type' => $keys[0]
            ];
        }

        return false;

    }

endif;

if (!function_exists('shop_ready_find_template_by_name')):

    function shop_ready_find_template_by_name($name)
    {
        $config = shop_ready_templates_config()->all();

        $template = isset($config[$name]) ? $config[$name] : false;
        unset($config);

        if (isset($template['active']) && isset($template['id']) && is_numeric($template['id']) && ($template['active'] == 1 || $template['active'])) {
            return $template;
        }

        return false;
    }

endif;