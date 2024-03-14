<?php

namespace ShopWP;

if (!defined('ABSPATH')) {
    exit();
}

class Templates
{
    public $Template_Loader;
    public $plugin_settings;

    public function __construct($Template_Loader, $plugin_settings)
    {
        $this->Template_Loader = $Template_Loader;
        $this->plugin_settings = $plugin_settings;
    }

    /*

	Template: components/pagination/end

	*/
    public function wps_products_pagination_end()
    {
        $data = [];

        ob_start();
        $this->Template_Loader
            ->set_template_data($data)
            ->get_template_part('components/pagination/end');
        $output = ob_get_clean();
        return $output;
    }

    public function wps_products_pagination_start()
    {
        $data = [];

        ob_start();
        $this->Template_Loader
            ->set_template_data($data)
            ->get_template_part('components/pagination/start');
        $output = ob_get_clean();
        return $output;
    }

    /*

	Template: components/pagination/breadcrumbs

	*/
    public function shopwp_breadcrumbs($shortcodeData = false)
    {

        if ($this->plugin_settings && 
            apply_filters(
                'shopwp_show_breadcrumbs',
                $this->plugin_settings['general']['show_breadcrumbs']
            )
        ) {
            $data = [];

            return $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('components/pagination/breadcrumbs');
        }
    }

    public function shopwp_root_elements()
    {
        echo '<div id="shopwp-root"></div>';
    }

    public function shopwp_single_template($template)
    {

        if (is_singular(SHOPWP_PRODUCTS_POST_TYPE_SLUG)) {

            if (!apply_filters('shopwp_use_products_single_template', true)) {
               return $template;
            }

            return $this->Template_Loader->get_template_part(
                'products',
                'single',
                false
            );
        }

        if (is_singular(SHOPWP_COLLECTIONS_POST_TYPE_SLUG)) {

            if (!apply_filters('shopwp_use_collections_single_template', true)) {
               return $template;
            }

            return $this->Template_Loader->get_template_part(
                'collections',
                'single',
                false
            );
        }

        return $template;
    }

    /*

	Main Template products-all

	*/
    public function shopwp_all_template($template)
    {

        if (is_post_type_archive(SHOPWP_PRODUCTS_POST_TYPE_SLUG)) {

            if (!apply_filters('shopwp_use_products_all_template', true)) {
               return $template;
            }

            return $this->Template_Loader->get_template_part(
                'products',
                'all',
                false
            );
        }

        if (is_post_type_archive(SHOPWP_COLLECTIONS_POST_TYPE_SLUG)) {

            if (!apply_filters('shopwp_use_collections_all_template', true)) {
               return $template;
            }

            return $this->Template_Loader->get_template_part(
                'collections',
                'all',
                false
            );
        }

        return $template;
    }

    public function shopwp_page_templates($template)
    {
        $enable_default_pages =
            $this->plugin_settings['general']['enable_default_pages'];

        if (!$enable_default_pages) {
            return $template;
        }

        $current_page_id = get_the_ID();
        $page_products_id = $this->plugin_settings['general']['page_products'];
        $page_collections_id =
            $this->plugin_settings['general']['page_collections'];

        if ($current_page_id === $page_products_id) {
            return $this->Template_Loader->get_template_part(
                'products',
                'all',
                false
            );
        }

        if ($current_page_id === $page_collections_id) {
            return $this->Template_Loader->get_template_part(
                'collctions',
                'all',
                false
            );
        }

        return $template;
    }

    public function shopwp_set_posts_per_page($query)
    {
        if (
            !is_admin() &&
            $query->is_main_query() &&
            is_post_type_archive('wps_products')
        ) {
            $query->set(
                'posts_per_page',
                $this->plugin_settings['general']['num_posts']
            );
        }

        return $query;
    }

    public function init()
    {
        add_action('shopwp_breadcrumbs', [$this, 'shopwp_breadcrumbs']);
        add_action('wp_footer', [$this, 'shopwp_root_elements']);

        add_filter('single_template', [$this, 'shopwp_single_template']);
        add_filter('archive_template', [$this, 'shopwp_all_template']);

        add_action('pre_get_posts', [$this, 'shopwp_set_posts_per_page']);

        add_filter('page_template', [$this, 'shopwp_page_templates']);

        add_filter('wps_products_pagination_start', [
            $this,
            'wps_products_pagination_start',
        ]);
        add_filter('wps_products_pagination_end', [
            $this,
            'wps_products_pagination_end',
        ]);
    }
}
