<?php

/**
 * Scroll Bar With Scroll Back To Top 
 *
 * @author Shafiqul Islam
 */
if ( !class_exists('gcz_Scroll_Setting' ) ):
class gcz_Scroll_Setting {

    private $settings_api;

    function __construct() {
        $this->settings_api = new gcz_Scroll_Setting_api;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_options_page( 'Scroll Settings', 'Scroll Settings', 'delete_posts', 'scroll_settings', array($this, 'plugin_page') );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'scroll_bar',
                'title' => __( 'Scroll Bar', 'wedevs' )
            ),
            array(
                'id' => 'scroll_top',
                'title' => __( 'Scroll Back To Top', 'wedevs' )
            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'scroll_bar' => array(
			    array(
                    'name'    => 'scroll_bar_active',
                    'label'   => __( 'Scroll Bar Active', 'gcz_scroll' ),
                    'type'    => 'select',
                    'default' => 'yes',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
				array(
                    'name'    => 'scroll_bar_color',
                    'label'   => __( 'Scroll Bar Color', 'gcz_scroll' ),
                    'type'    => 'color',
                    'default' => '#36c6f4'
                ),

                array(
                    'name'              => 'scroll_bar_width',
                    'label'             => __( 'Scroll Bar Width', 'gcz_scroll' ),
                    'type'              => 'text',
                    'default'           => '10',
                    'sanitize_callback' => 'intval'
                ),                
				array(
                    'name'              => 'scroll_bar_speed',
                    'label'             => __( 'Scroll Bar Speed', 'gcz_scroll' ),
                    'type'              => 'text',
                    'default'           => '100',
                    'sanitize_callback' => 'intval'
                ),				
				array(
                    'name'              => 'scroll_bar_opacity',
                    'label'             => __( 'Scroll Bar Opacity', 'gcz_scroll' ),
                    'type'              => 'text',
                    'default'           => '0.3'
                ),				
				array(
                    'name'              => 'scroll_bar_mousescrollstep',
                    'label'             => __( 'Scroll Bar Mouse Scroll Step', 'gcz_scroll' ),
                    'type'              => 'text',
                    'default'           => '45',
                    'sanitize_callback' => 'intval'
                ),				
				array(
                    'name'              => 'scroll_bar_borderradius',
                    'label'             => __( 'Scroll Bar Border Radius', 'gcz_scroll' ),
                    'type'              => 'text',
                    'default'           => '10',
                    'sanitize_callback' => 'intval'
                ),					
				array(
                    'name'              => 'scroll_bar_border',
                    'label'             => __( 'Scroll Bar Border', 'gcz_scroll' ),
                    'type'              => 'text',
                    'default'           => '0px solid #000'
                ),				
				array(
                    'name'              => 'scroll_bar_hidecursordelay',
                    'label'             => __( 'Scroll Bar Hide Cursor Delay', 'gcz_scroll' ),
                    'type'              => 'text',
                    'default'           => '150',
                    'sanitize_callback' => 'intval'
                ),
				array(
                    'name'    => 'smooth_scroll',
                    'label'   => __( 'Smooth Scroll', 'gcz_scroll' ),
                    'type'    => 'select',
                    'default' => 'true',
                    'options' => array(
                        'true' => 'Yes',
                        'false'  => 'No'
                    )
                )
            ),
            'scroll_top' => array(
                array(
                    'name'    => 'scroll_top_active',
                    'label'   => __( 'Scroll Top Active', 'gcz_scroll' ),
                    'type'    => 'select',
                    'default' => 'yes',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
				array(
                    'name'    => 'scroll_top_color',
                    'label'   => __( 'Scroll Top Background Color', 'gcz_scroll' ),
                    'type'    => 'color',
                    'default' => '#36c6f4'
                ),
				array(
                    'name'    => 'scroll_top_icon_font_size',
                    'label'   => __( 'Scroll Top Font Size', 'gcz_scroll' ),
                    'type'    => 'text',
                    'default' => '20px'
                ),				
				array(
                    'name'    => 'scroll_top_icon_color',
                    'label'   => __( 'Scroll Top Icon Color', 'gcz_scroll' ),
                    'type'    => 'color',
                    'default' => '#ffffff'
                ),				
				array(
                    'name'    => 'scroll_top_border_radius',
                    'label'   => __( 'Scroll Top Border Radius', 'gcz_scroll' ),
                    'type'    => 'text',
                    'default' => '3px'
                ),
                array(
                    'name'    => 'scroll_top_icon',
                    'label'   => __( 'Scroll Top Icon Select', 'gcz_scroll' ),
                    'type'    => 'select',
                    'default' => 'icon-up-open',
                    'options' => array(
                        'icon-up-open' => 'Icon Up Open',
                        'icon-up-big'  => 'Icon Up Big',
                        'icon-up-circled'  => 'Icon Up Circled',
                        'icon-up-circled2'  => 'Icon Up Circled 2',
                        'icon-angle-double-up'  => 'Icon Angle Double Up',
                        'icon-angle-circled-up'  => 'Icon Angle Circled Up',
                        'icon-up-dir'  => 'Icon Up Dir',
                        'icon-angle-up'  => 'Icon Angle Up'
                    )
                ),
                array(
                    'name'              => 'scroll_top_smooth',
                    'label'             => __( 'Scroll Top Smooth', 'gcz_scroll' ),
                    'type'              => 'text',
                    'default'           => '500',
                    'sanitize_callback' => 'intval'
                ),                
				array(
                    'name'              => 'scroll_top_show_time',
                    'label'             => __( 'Scroll Show', 'gcz_scroll' ),
                    'type'              => 'text',
                    'default'           => '100',
                    'sanitize_callback' => 'intval'
                )	
				
				
            ),
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;