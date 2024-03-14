<?php
namespace Shop_Ready\extension\elewidgets;

use Shop_Ready\base\elementor\Widget_Settings as Shop_Ready_Widget_Settings;
use Shop_Ready\base\elementor\style_controls\box\Widget_Box_Style;
use Shop_Ready\base\elementor\style_controls\common\Widget_Common_Style;
use Shop_Ready\base\elementor\style_controls\position\Widget_Style_Position;
use Automattic\Jetpack\Constants;

abstract class Widget_Base extends \Elementor\Widget_Base
{

    use Widget_Box_Style;
    use Widget_Common_Style;
    use Widget_Style_Position;

    public $config = false;
    public $wrapper_class = true;


    abstract protected function html();

    /**
     * Get current page URL with various filtering props supported by WC.
     *
     * @return string
     * @since  3.3.0
     */
    public function get_current_page_url()
    {
        global $woocommerce;

        if (Constants::is_defined('SHOP_IS_ON_FRONT')) {
            $link = home_url();
        } elseif (is_shop()) {
            $link = get_permalink(wc_get_page_id('shop'));
        } elseif (is_product_category()) {
            $link = get_term_link(get_query_var('product_cat'), 'product_cat');
        } elseif (is_product_tag()) {
            $link = get_term_link(get_query_var('product_tag'), 'product_tag');
        } else {

            $queried_object = get_queried_object();
            $link = isset($queried_object->slug) ? get_term_link($queried_object->slug, $queried_object->taxonomy) : '#';
        }

        // Min/Max.
        if (isset($_GET['min_price'])) {
            $link = add_query_arg('min_price', wc_clean(wp_unslash(sanitize_text_field($_GET['min_price']))), $link);
        }

        if (isset($_GET['max_price'])) {
            $link = add_query_arg('max_price', wc_clean(wp_unslash(sanitize_text_field($_GET['max_price']))), $link);
        }

        // Order by.
        if (isset($_GET['orderby'])) {
            $link = add_query_arg('orderby', wc_clean(wp_unslash(sanitize_text_field($_GET['orderby']))), $link);
        }

        /**
         * Search Arg.
         * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
         */
        if (get_search_query()) {
            $link = add_query_arg('s', rawurlencode(htmlspecialchars_decode(get_search_query())), $link);
        }

        // Post Type Arg.
        if (isset($_GET['post_type'])) {
            $link = add_query_arg('post_type', wc_clean(wp_unslash(sanitize_text_field($_GET['post_type']))), $link);

            // Prevent post type and page id when pretty permalinks are disabled.
            if (is_shop()) {
                $link = remove_query_arg('page_id', $link);
            }
        }

        // Min Rating Arg.
        if (isset($_GET['rating_filter'])) {
            $link = add_query_arg('rating_filter', wc_clean(sanitize_text_field($_GET['rating_filter'])), $link);
        }

        // All current filters.
        if ($_chosen_attributes = $woocommerce->query::get_layered_nav_chosen_attributes()) {
            foreach ($_chosen_attributes as $name => $data) {
                $filter_name = wc_attribute_taxonomy_slug($name);
                if (!empty($data['terms'])) {
                    $link = add_query_arg('filter_' . $filter_name, implode(',', $data['terms']), $link);
                }
                if ('or' === $data['query_type']) {
                    $link = add_query_arg('query_type_' . $filter_name, 'or', $link);
                }
            }
        }

        return $link;
    }

    public function get_categories()
    {

        $main_addon = ['shopready-elementor-addon'];

        if (method_exists($this, 'set_categories') && is_array($this->set_categories())) {

            return array_merge($main_addon, $this->set_categories());
        }

        if ($this->have_config_key('category')) {

            if (is_array($this->config['category'])) {

                return array_merge($main_addon, $this->config['category']);
            } elseif (is_string($this->config['category'])) {

                return array_push($main_addon, $this->config['category']);
            }

        }

        return $main_addon;
    }

    public function get_icon()
    {

        if ($this->have_config_key('icon')) {
            return $this->config['icon'];
        }

        return 'fa fa-plug';
    }

    public function show_in_panel()
    {

        if ($this->config) {
            return $this->config['show_in_panel'];
        }

        return true;
    }

    public function get_name()
    {

        $slug = $this->get_refined_slug();
        $this->set_shop_config($slug);

        return $slug;
    }

    /*
     * Set Config From config/widgets.php
     * since 1.0
     * return void
     */

    public function set_shop_config($real_slug)
    {

        $all_configs = shop_ready_elementor_component_config()->all();

        if (is_array($all_configs)) {
            $all_configs = array_change_key_case($all_configs, CASE_LOWER);
        }

        $this->config = array_key_exists($real_slug, $all_configs) ? $all_configs[$real_slug] : false;

        unset($all_configs);
    }

    public function get_refined_slug()
    {

        $slug = str_replace(['shop_ready\extension\elewidgets\widgets'], [''], strtolower(get_called_class()));
        return strtolower(trim(str_replace('\\', '_', $slug), '_'));
    }

    public function get_keywords()
    {

        if ($this->have_config_key('keywords')) {

            $keyword = $this->config['keywords'];
            return is_array($keyword) ? $keyword : [$keyword];

        }

        return [$this->get_title()];
    }

    public function have_config_key($key)
    {

        if (!is_array($this->config)) {

            return false;
        }

        if (!array_key_exists($key, $this->config)) {
            return false;
        }

        return true;
    }

    public function get_title()
    {

        $key = 'title';

        if ($this->have_config_key($key)) {
            return $this->config[$key];
        }

        return str_replace(['_', '-', '.'], [' '], $this->get_refined_slug());
    }

    public function get_script_depends()
    {

        $key = 'js';

        if ($this->have_config_key($key)) {

            $asset = $this->config[$key];
            return is_array($asset) ? $asset : [$asset];
        }

        return [];
    }

    public function get_style_depends()
    {

        $key = 'css';

        if ($this->have_config_key($key)) {

            $asset = $this->config[$key];
            return is_array($asset) ? $asset : [$asset];
        }

        return [];
    }

    public function render()
    {
        shop_ready_load_wc();

        try {

            if ($this->wrapper_class) {
                echo wp_kses_post(\sprintf('<div class="%s" >', esc_attr(apply_filters('shop_ready_ele_widget_container', 'woo-ready-ele-widget-container'))));
            }

            $this->html();

            if ($this->wrapper_class) {
                echo wp_kses_post("</div>");
            }

        } catch (\ErrorException $e) {

            return;
        }

    }

}