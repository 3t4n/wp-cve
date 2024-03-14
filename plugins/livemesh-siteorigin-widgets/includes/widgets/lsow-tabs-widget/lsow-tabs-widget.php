<?php

/*
Widget Name: Tabs
Description: Display tabbed content in variety of styles.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/
class LSOW_Tabs_Widget extends SiteOrigin_Widget
{
    function __construct()
    {
        parent::__construct(
            'lsow-tabs',
            __( 'Tabs', 'livemesh-so-widgets' ),
            array(
            'description' => __( 'Display tabbed content in variety of styles.', 'livemesh-so-widgets' ),
            'panels_icon' => 'dashicons dashicons-minus',
            'help'        => LSOW_PLUGIN_HELP_URL . '#tabs-accordions',
        ),
            array(),
            array(
            'title'        => array(
            'type'  => 'text',
            'label' => __( 'Title', 'livemesh-so-widgets' ),
        ),
            'style'        => array(
            'type'          => 'select',
            'label'         => __( 'Choose Tab Style', 'livemesh-so-widgets' ),
            'state_emitter' => array(
            'callback' => 'select',
            'args'     => array( 'style' ),
        ),
            'default'       => 'style1',
            'options'       => array(
            'style1'  => __( 'Tab Style 1', 'livemesh-so-widgets' ),
            'style2'  => __( 'Tab Style 2', 'livemesh-so-widgets' ),
            'style3'  => __( 'Tab Style 3', 'livemesh-so-widgets' ),
            'style4'  => __( 'Tab Style 4', 'livemesh-so-widgets' ),
            'style5'  => __( 'Tab Style 5', 'livemesh-so-widgets' ),
            'style6'  => __( 'Tab Style 6', 'livemesh-so-widgets' ),
            'style7'  => __( 'Vertical Tab Style 1', 'livemesh-so-widgets' ),
            'style8'  => __( 'Vertical Tab Style 2', 'livemesh-so-widgets' ),
            'style9'  => __( 'Vertical Tab Style 3', 'livemesh-so-widgets' ),
            'style10' => __( 'Vertical Tab Style 4', 'livemesh-so-widgets' ),
        ),
        ),
            'color'        => array(
            'type'          => 'color',
            'label'         => __( 'Tab highlight color', 'livemesh-so-widgets' ),
            'state_handler' => array(
            'style[style4,style6,style7,style8]' => array( 'show' ),
            '_else[style]'                       => array( 'hide' ),
        ),
            'default'       => '#f94213',
        ),
            'mobile_width' => array(
            'type'        => 'number',
            'label'       => __( 'Mobile Resolution', 'livemesh-so-widgets' ),
            'description' => __( 'The resolution to treat as a mobile resolution for invoking responsive tabs.', 'livemesh-so-widgets' ),
            'default'     => 767,
        ),
            'icon_type'    => array(
            'type'          => 'select',
            'label'         => __( 'Choose Icon Type', 'livemesh-so-widgets' ),
            'description'   => __( 'Some styles may ignore icons chosen.', 'livemesh-so-widgets' ),
            'default'       => 'none',
            'state_emitter' => array(
            'callback' => 'select',
            'args'     => array( 'icon_type' ),
        ),
            'state_handler' => array(
            'style[style2]' => array( 'hide' ),
            '_else[style]'  => array( 'show' ),
        ),
            'options'       => array(
            'none'       => __( 'None', 'livemesh-so-widgets' ),
            'icon'       => __( 'Icon', 'livemesh-so-widgets' ),
            'icon_image' => __( 'Icon Image', 'livemesh-so-widgets' ),
        ),
        ),
            'tabs'         => array(
            'type'       => 'repeater',
            'label'      => __( 'Tabs', 'livemesh-so-widgets' ),
            'item_name'  => __( 'Single Tab', 'livemesh-so-widgets' ),
            'item_label' => array(
            'selector'     => "[id*='tabs-title']",
            'update_event' => 'change',
            'value_method' => 'val',
        ),
            'fields'     => array(
            'title'       => array(
            'type'        => 'text',
            'label'       => __( 'Tab Title', 'livemesh-so-widgets' ),
            'description' => __( 'The title for the tab shown as name for tab navigation.', 'livemesh-so-widgets' ),
        ),
            'tab_id'      => array(
            'type'        => 'text',
            'label'       => __( 'Tab ID', 'livemesh-so-widgets' ),
            'description' => __( 'The Tab ID is required to link to a tab. It must be unique across the page, must begin with a letter and may be followed by any number of letters, digits, hyphens or underscores.', 'livemesh-so-widgets' ),
        ),
            'icon_image'  => array(
            'type'          => 'media',
            'label'         => __( 'Tab Image.', 'livemesh-so-widgets' ),
            'state_handler' => array(
            'icon_type[icon_image]' => array( 'show' ),
            'icon_type[icon]'       => array( 'hide' ),
            'icon_type[none]'       => array( 'hide' ),
        ),
        ),
            'icon'        => array(
            'type'          => 'icon',
            'label'         => __( 'Tab Icon.', 'livemesh-so-widgets' ),
            'state_handler' => array(
            'icon_type[icon]'       => array( 'show' ),
            'icon_type[icon_image]' => array( 'hide' ),
            'icon_type[none]'       => array( 'hide' ),
        ),
        ),
            'tab_content' => array(
            'type'        => 'tinymce',
            'label'       => __( 'Tab Content', 'livemesh-so-widgets' ),
            'description' => __( 'The content of the tab.', 'livemesh-so-widgets' ),
        ),
        ),
        ),
            'customize'    => array(
            'type'         => 'notify',
            'custom_label' => __( 'Customize this widget with the intuitive yet powerful visual styles editor - ', 'livemesh-so-widgets' ) . '<a target="_blank" href="https://wordpress.org/plugins/so-css/" title="SiteOrigin CSS">' . __( 'SiteOrigin CSS.', 'livemesh-so-widgets' ) . '</a>',
        ),
        )
        );
    }
    
    function initialize()
    {
        $this->register_frontend_scripts( array( array( 'lsow-tabs', plugin_dir_url( __FILE__ ) . 'js/tabs' . LSOW_JS_SUFFIX . '.js', array( 'jquery' ) ) ) );
        $this->register_frontend_styles( array( array(
            'lsow-icomoon',
            LSOW_PLUGIN_URL . 'assets/css/icomoon.css',
            array(),
            LSOW_VERSION
        ), array(
            'lsow-frontend',
            LSOW_PLUGIN_URL . 'assets/css/lsow-frontend.css',
            array(),
            LSOW_VERSION
        ), array( 'lsow-tabs', plugin_dir_url( __FILE__ ) . 'css/style.css' ) ) );
    }
    
    function get_less_variables( $instance )
    {
        return array(
            'color' => $instance['color'],
        );
    }
    
    function get_template_variables( $instance, $args )
    {
        $settings = array(
            'style'        => $instance['style'],
            'icon_type'    => $instance['icon_type'],
            'mobile_width' => intval( $instance['mobile_width'] ),
            'tabs'         => ( !empty($instance['tabs']) ? $instance['tabs'] : array() ),
        );
        return array(
            'settings' => $settings,
        );
    }

}
siteorigin_widget_register( 'lsow-tabs', __FILE__, 'LSOW_Tabs_Widget' );