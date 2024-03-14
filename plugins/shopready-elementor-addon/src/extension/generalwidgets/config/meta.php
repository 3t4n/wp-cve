<?php
if (!defined('ABSPATH')) {
    exit;
}
/************************** ***************** 
 * 
 * all Widgets Meta and category settings 
 * since 1.0
 * Registerd Widget Category for Widget Config
 *
 ***********************************************/
return [

    //  Extension Config
    'meta' => [
        'name' => esc_html__('Shop Ready General Widgets', 'shopready-elementor-addon'),
        'description' => esc_html__('ELementor Widget Extension to use basic meta and category config', 'shopready-elementor-addon'),
        'author' => 'quomodosoft'
    ],

    'categories' => [

        'wgenerel' => [
            'name' => esc_html__('Shop Ready Elements', 'shopready-elementor-addon'),
            'icon' => 'fa fa-plug'
        ],

    ],


];