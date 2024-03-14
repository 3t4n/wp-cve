<?php

namespace TABS_RES_PLUGINS\Extension\WooCommerce\Product;

/**
 *
 * @author biplo
 */
trait Data
{

    public function product_tabs_content($key, $tab)
    {
        $content = '';
        $content = apply_filters('responsive_woo_tab_content_filter', $tab['content']);
        $sub = get_option('responsive_tabs_woo_sub_title');
        if ($sub) :
            $tab_title_html = '<h2 class="responsive_woo_tab-title responsive_woo_tab-tab-title-' . urldecode(sanitize_title($tab['title'])) . '">' . $tab['title'] . '</h2>';
            echo apply_filters('responsive_woo_tab_product_tabs_heading', $tab_title_html, $tab);
        endif;

        echo apply_filters('responsive_woo_tab_product_tabs_content', $content, $tab);
    }

    public function add_custom_product_tabs($tabs)
    {
        global $product;
        $product_id = method_exists($product, 'get_id') === true ? $product->get_id() : $product->ID;
        $product_tabs = maybe_unserialize(get_post_meta($product_id, '_responsive_tabs_woo_data', true));
        if (is_array($product_tabs) && !empty($product_tabs)) {
            $priority = 25;
            foreach ($product_tabs as $key => $tab) {
                if (empty($tab['title'])) {
                    continue;
                }
                $default = [
                    'priority' => $priority++,
                    'callback' => ''
                ];
                $tab = array_merge($default, $tab);
                $keys = urldecode(sanitize_title($tab['title']));
                if (array_key_exists($keys, $tabs)) :
                    $k = 100;
                    for ($i = 0; $i < $k; $i++) {
                        $new = $keys . '-' . $i;
                        if (array_key_exists($new, $tabs) == false) :
                            $keys = $new;
                            break;
                        endif;
                    }
                endif;
                if ($tab['callback'] == '') :
                    $tab['callback'] = [$this, 'product_tabs_content'];
                endif;

                $tabs[$keys] = array(
                    'title' => $tab['title'],
                    'icon' => isset($tab['icon']) ? $tab['icon'] : '',
                    'priority' => $tab['priority'],
                    'callback' => $tab['callback'],
                    'content' => $tab['content']
                );
            }
        }
        return $tabs;
    }

    public function woo_template($template, $template_name, $template_path)
    {
        global $woocommerce;
        $_Parent_Template = $template;
        if (!$template_path) :
            $template_path = $woocommerce->template_url;
        endif;

        $plugin_path = untrailingslashit(wpshopmart_tabs_r_directory_path) . '/Extension/WooCommerce/Template/';

        if (file_exists($plugin_path . $template_name)) :
            $template = $plugin_path . $template_name;
        endif;

        if (!$template) :
            $template = locate_template(
                array(
                    $template_path . $template_name,
                    $template_name
                )
            );

        endif;

        if (!$template) :
            $template = $_Parent_Template;
        endif;

        return $template;
    }

    /**
     * Check if we should use the filter
     */
    public function use_the_content_filter()
    {
        return get_option('responsive_tabs_use_the_content') == 'yes' ? true : false;
    }

    public function content_filter($content)
    {
        $content = function_exists('capital_P_dangit') ? capital_P_dangit($content) : $content;
        $content = function_exists('wptexturize') ? wptexturize($content) : $content;
        $content = function_exists('convert_smilies') ? convert_smilies($content) : $content;
        $content = function_exists('wpautop') ? wpautop($content) : $content;
        $content = function_exists('shortcode_unautop') ? shortcode_unautop($content) : $content;
        $content = function_exists('prepend_attachment') ? prepend_attachment($content) : $content;
        $content = function_exists('wp_filter_content_tags') ? wp_filter_content_tags($content) : $content;
        $content = function_exists('do_shortcode') ? do_shortcode($content) : $content;

        if (class_exists('WP_Embed')) {
            $embed = new \WP_Embed;
            $content = method_exists($embed, 'autoembed') ? $embed->autoembed($content) : $content;
        }

        return $content;
    }

    public function responsive_remove_product_tabs($tabs)
    {
        $customize = $this->customize;
        if (isset($customize['unset'])) :
            foreach ($customize['unset'] as $k => $value) {
                if (isset($tabs[$k])) :
                    unset($tabs[$k]);
                endif;
            }
        endif;
        if (isset($customize['title'])) :
            foreach ($customize['title'] as $k => $value) {
                if (isset($tabs[$k])) :
                    $tabs[$k]['title'] = $value;
                endif;
            }
        endif;
        if (isset($customize['icon'])) :
            foreach ($customize['icon'] as $k => $value) {
                if (isset($tabs[$k])) :
                    $tabs[$k]['custom_icon'] = $value;
                endif;
            }
        endif;
        if (isset($customize['priority'])) :
            foreach ($customize['priority'] as $k => $value) {
                if (isset($tabs[$k])) :
                    $tabs[$k]['priority'] = $value;
                endif;
            }
        endif;
        if (isset($customize['callback'])) :
            foreach ($value as $k => $value) {
                if (isset($tabs[$k])) :
                    $tabs[$k]['callback'] = $value;
                endif;
            }

        endif;
        return $tabs;
    }

    public function reorder_default_tabs()
    {
        $check_customization = json_decode(stripslashes(get_option('responsive_tabs_woocommerce_customize_default_tabs')), true);

        if (is_array($check_customization) && count($check_customization) > 1) :
            $customize = [];
            foreach ($check_customization as $key => $value) {
                if (isset($value['title']) && $value['title'] != '') :
                    $customize['title'][$key] = $value['title'];
                endif;
                if (isset($value['icon']) && $value['icon'] != '') :
                    $customize['icon'][$key] = $value['icon'];
                endif;
                if (isset($value['priority']) && $value['priority'] != '') :
                    $customize['priority'][$key] = $value['priority'];
                endif;
                if (isset($value['callback']) && $value['callback'] != '') :
                    $customize['callback'][$key] = $value['callback'];
                endif;
                if (isset($value['unset']) && $value['unset'] == 'on') :
                    $customize['unset'][$key] = $value['unset'];
                endif;
            }
            if (count($customize) > 0) :
                $this->customize = $customize;
                add_filter('woocommerce_product_tabs', [$this, 'responsive_remove_product_tabs'], 10);
            endif;
        endif;
    }

    public function add_product_tabs($tabs)
    {

        global $product;
        $arg = [
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'asc',
            'post_status' => 'publish',
            'post_type' => RESPONSIVE_TABS_WOOCOMMERCE_POST_TYPE,
            'meta_query' => [
                [
                    'key' => 'responsive_woo_tabs_activation',
                    'value' => 'yes',
                    'compare' => '=',
                ],
            ],
        ];

        $product_tabs = get_posts($arg);

        if (empty($product_tabs)) {
            return $tabs;
        }


        if (!empty($product_tabs)) {
            foreach ($product_tabs as $key => $value) {
                $post_id = $value->ID;
                $render = false;
                $condition = get_post_meta($post_id, 'responsive_woo_tabs_condition', true);
                if ($condition == 'entire_site') :
                    $render = true;
                elseif ($condition == 'singular') :
                    $singular = get_post_meta($post_id, 'responsive_woo_tabs_singular_id', true);
                    if (count($arg) > 0) :
                        if (array_intersect($singular, [$product->get_id()])) {
                            $render = true;
                        }
                    endif;
                elseif ($condition == 'archive') :
                    $archive = get_post_meta($post_id, 'responsive_woo_tabs_archive', true);
                    if ($archive == 'products_cat') :

                        $products_cat = get_post_meta($post_id, 'responsive_woo_tabs_products_cat', true);

                        if (!empty($products_cat) && count($products_cat) > 0) :
                            $cat_list = wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'ids'));
                            if (array_intersect($cat_list, $products_cat)) {
                                $render = true;
                            }
                        endif;
                    elseif ($archive == 'products_tags') :
                        $products_tag = get_post_meta($post_id, 'responsive_woo_tabs_products_tags', true);

                        if (!empty($products_tag) && count($products_tag) > 0) :
                            $tag_list = wp_get_post_terms($product->get_id(), 'product_tag', array('fields' => 'ids'));
                            if (array_intersect($tag_list, $products_tag)) {
                                $render = true;
                            }
                        endif;
                    endif;

                endif;

                if ($render) :
                    $title = $value->post_title;
                    $keys = urldecode(sanitize_title($title));
                    $callback = get_post_meta($post_id, 'responsive_woo_tabs_callback', true);
                    $priority = get_post_meta($post_id, 'responsive_woo_tabs_priority', true);

                    $tabs[$keys] = array(
                        'title' => $title,
                        'icon' => get_post_meta($post_id, 'responsive_woo_tabs_icon', true),
                        'priority' => 20,
                        'callback' => $callback,
                        'content' => $value->post_content
                    );
                    if ($priority > 0) :
                        $tabs[$keys]['priority'] = $priority;
                    endif;
                    if ($callback == '') :
                        $tabs[$keys]['callback'] = [$this, 'product_tabs_content'];
                    endif;
                endif;
            }
        }
        return $tabs;
    }
}
