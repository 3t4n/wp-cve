<?php

namespace HQRentalsPlugin\HQRentalsTemplates;

class HQRentalsTemplateHandler
{
    public function __construct()
    {
        add_filter('template_include', array($this, 'addingTemplates'), 3);
        add_filter('theme_page_templates', array($this, 'addTemplateFileToWP'), 10, 4);
        add_filter('page_template', array($this, 'loadTemplateFiles'), 20, 5);
    }

    public function addingTemplates($defaultTemplate)
    {
        global $post;
        $theme = wp_get_theme();
        // add theme route
        if ($post->post_type === 'hqwp_veh_classes' and is_single() and $theme->stylesheet === 'grandcarrental') {
            $defaultTemplate = load_template(dirname(__FILE__) . '/gcar/single-hqwp_veh_classes.php');
        } elseif (is_page('quotes')) {
            load_template(__DIR__ . '/page-quotes.php');
        } elseif (is_page('payments')) {
            load_template(__DIR__ . '/page-payments.php');
        } else {
            return $defaultTemplate;
        }
        /*
         * else if(){
            load_template(dirname( __FILE__ ) . '/gcar/page.php');
        }
         * */
    }
    public function addTemplateFileToWP($templates)
    {
        $theme = wp_get_theme();
        if (
            $theme->stylesheet === 'aucapina' or
            $theme->stylesheet === 'aucapina-child' or
            $theme->stylesheet === 'aucapina_child'
        ) {
            $templates['page-aucapina-vehicle-class.php'] = __('Vehicle Class - HQ Auscapina', 'hq-wordpress');
        }
        $templates['page-vehicle-class-big-header-template.php'] = __('Vehicle Class Big Header Template', 'hq-wordpress');
        return $templates;
    }

    public function loadTemplateFiles($page_template)
    {

        if (get_page_template_slug() == 'page-aucapina-vehicle-class.php') {
            $page_template = plugin_dir_path(__FILE__) . 'aucapina/templates/page-aucapina-vehicle-class.php';
        }
        if (get_page_template_slug() == 'page-vehicle-class-big-header-template.php') {
            $page_template = plugin_dir_path(__FILE__) . 'generics/page-vehicle-class-big-header-template.php';
        }
        return $page_template;
    }
}
