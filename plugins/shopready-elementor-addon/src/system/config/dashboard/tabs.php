<?php
if (!defined('ABSPATH')) {
    exit;
}

return [

    'dashboard_tab' => [

        [
            'menu_id' => 'shop-ready-dashboard',
            'attr_class' => 'woo-ready-home',
            'menu_title' => esc_html__('Dashboard', 'shopready-elementor-addon'),
            'content_view_path' => SHOP_READY_DIR_PATH . 'src/system/base/dashboard/views/tabs/content/home.php',
            'active' => true

        ],

        [
            'menu_id' => 'shop_ready_components',
            'attr_class' => 'woo-ready-dash-widgets',
            'menu_title' => esc_html__('Widgets', 'shopready-elementor-addon'),
            'content_view_path' => SHOP_READY_DIR_PATH . 'src/system/base/dashboard/views/tabs/content/widgets.php',
        ],

        [
            'menu_id' => 'shop_ready_modules',
            'attr_class' => 'woo-ready-dash-modules',
            'menu_title' => esc_html__('Modules', 'shopready-elementor-addon'),
            'content_view_path' => SHOP_READY_DIR_PATH . 'src/system/base/dashboard/views/tabs/content/modules.php',
        ],

        [
            'menu_id' => 'shop_ready_templates',
            'attr_class' => 'woo-ready-dash-templates',
            'menu_title' => esc_html__('Templates', 'shopready-elementor-addon'),
            'content_view_path' => SHOP_READY_DIR_PATH . 'src/system/base/dashboard/views/tabs/content/templates.php',
        ],

        [
            'menu_id' => 'shop_ready_data_api',
            'attr_class' => 'woo-ready-dash-data-api',
            'menu_title' => esc_html__('Api', 'shopready-elementor-addon'),
            'content_view_path' => SHOP_READY_DIR_PATH . 'src/system/base/dashboard/views/tabs/content/api.php',
        ]

    ],

];