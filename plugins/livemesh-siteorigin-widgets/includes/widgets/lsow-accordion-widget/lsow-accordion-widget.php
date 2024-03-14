<?php

/*
Widget Name: Accordion
Description: Displays collapsible content panels to help display information when space is limited.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/
class LSOW_Accordion_Widget extends SiteOrigin_Widget
{
    function __construct()
    {
        parent::__construct(
            'lsow-accordion',
            __( 'Accordion', 'livemesh-so-widgets' ),
            array(
            'description' => __( 'Displays collapsible content panels to help display information when space is limited.', 'livemesh-so-widgets' ),
            'panels_icon' => 'dashicons dashicons-minus',
            'help'        => LSOW_PLUGIN_HELP_URL . '#tabs-accordions',
        ),
            array(),
            array(
            'title'     => array(
            'type'  => 'text',
            'label' => __( 'Title', 'livemesh-so-widgets' ),
        ),
            'style'     => array(
            'type'          => 'select',
            'label'         => __( 'Choose Accordion Style', 'livemesh-so-widgets' ),
            'state_emitter' => array(
            'callback' => 'select',
            'args'     => array( 'style' ),
        ),
            'default'       => 'style1',
            'options'       => array(
            'style1' => __( 'Style 1', 'livemesh-so-widgets' ),
            'style2' => __( 'Style 2', 'livemesh-so-widgets' ),
            'style3' => __( 'Style 3', 'livemesh-so-widgets' ),
        ),
        ),
            'toggle'    => array(
            'type'        => 'checkbox',
            'label'       => __( 'Allow to function like toggle?', 'livemesh-so-widgets' ),
            'description' => __( 'Check if multiple elements can be open at the same time.', 'livemesh-so-widgets' ),
        ),
            'expanded'  => array(
            'type'        => 'checkbox',
            'label'       => __( 'Start expanded?', 'livemesh-so-widgets' ),
            'description' => __( 'Check if you need all elements to be expanded initially. Works only if toggle flag above is checked and hence multiple accordion elements can be open at the same time.', 'livemesh-so-widgets' ),
        ),
            'accordion' => array(
            'type'       => 'repeater',
            'label'      => __( 'Accordion', 'livemesh-so-widgets' ),
            'item_name'  => __( 'Panel', 'livemesh-so-widgets' ),
            'item_label' => array(
            'selector'     => "[id*='accordion-title']",
            'update_event' => 'change',
            'value_method' => 'val',
        ),
            'fields'     => array(
            'title'         => array(
            'type'        => 'text',
            'label'       => __( 'Panel Title', 'livemesh-so-widgets' ),
            'description' => __( 'The title for the panel.', 'livemesh-so-widgets' ),
        ),
            'panel_id'      => array(
            'type'        => 'text',
            'label'       => __( 'Panel ID', 'livemesh-so-widgets' ),
            'description' => __( 'The Panel ID is required to link to a panel. It must be unique across the page, must begin with a letter and may be followed by any number of letters, digits, hyphens or underscores.', 'livemesh-so-widgets' ),
        ),
            'panel_content' => array(
            'type'        => 'tinymce',
            'label'       => __( 'Panel Content', 'livemesh-so-widgets' ),
            'description' => __( 'The collapsible content of the panel in the accordion.', 'livemesh-so-widgets' ),
        ),
        ),
        ),
            'customize' => array(
            'type'         => 'notify',
            'custom_label' => __( 'Customize this widget with the intuitive yet powerful visual styles editor - ', 'livemesh-so-widgets' ) . '<a target="_blank" href="https://wordpress.org/plugins/so-css/" title="SiteOrigin CSS">' . __( 'SiteOrigin CSS.', 'livemesh-so-widgets' ) . '</a>',
        ),
        )
        );
    }
    
    function initialize()
    {
        $this->register_frontend_scripts( array( array( 'lsow-accordion', plugin_dir_url( __FILE__ ) . 'js/accordion' . LSOW_JS_SUFFIX . '.js', array( 'jquery' ) ) ) );
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
        ) ) );
        $this->register_frontend_styles( array( array( 'lsow-accordion', plugin_dir_url( __FILE__ ) . 'css/style.css' ) ) );
    }
    
    function get_template_variables( $instance, $args )
    {
        $settings = array(
            'style'     => $instance['style'],
            'toggle'    => $instance['toggle'],
            'expanded'  => $instance['expanded'],
            'accordion' => ( !empty($instance['accordion']) ? $instance['accordion'] : array() ),
        );
        return array(
            'settings' => $settings,
        );
    }

}
siteorigin_widget_register( 'lsow-accordion', __FILE__, 'LSOW_Accordion_Widget' );