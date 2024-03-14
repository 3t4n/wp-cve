<?php

/**
 * Plugin Settings Config
 * Author : WpBean
 */


if ( !class_exists('WPB_WCS_Settings_Config' ) ):
class WPB_WCS_Settings_Config {
    private $settings_api;
    function __construct() {
        $this->settings_api = new WeDevs_Settings_API;
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
        add_submenu_page(
	        WPB_WCS_TEXTDOMAIN . '-about',
	        esc_html__( 'Settings', WPB_WCS_TEXTDOMAIN ),
	        esc_html__( 'Settings', WPB_WCS_TEXTDOMAIN ),
	        apply_filters( 'wpb_wcs_settings_user_capability', 'manage_options' ),
	        WPB_WCS_TEXTDOMAIN . '-settings',
	        array( $this, 'plugin_page' )
	    );
    }
    function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'general_settings',
                'title' => esc_html__( 'General Settings', WPB_WCS_TEXTDOMAIN )
            ),
            array(
                'id'    => 'style_settings',
                'title' => esc_html__( 'Style Settings', WPB_WCS_TEXTDOMAIN )
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
            'general_settings' => array(
                array(
                    'name'    => 'wpb_wcs_order_by',
                    'label'   => __( 'Order By', WPB_WCS_TEXTDOMAIN ),
                    'desc'    => __( 'Select OrderBy for Categories', WPB_WCS_TEXTDOMAIN ),
                    'type'    => 'select',
                    'default' => 'name',
                    'options' => array(
                        'id'    => __( 'ID', WPB_WCS_TEXTDOMAIN ),
                        'count' => __( 'Count', WPB_WCS_TEXTDOMAIN ),
                        'name'  => __( 'Name', WPB_WCS_TEXTDOMAIN ),
                        'slug'  => __( 'Slug', WPB_WCS_TEXTDOMAIN ),
                        'none'  => __( 'None', WPB_WCS_TEXTDOMAIN ),
                    )
                ),
                array(
                    'name'    => 'wpb_wcs_order',
                    'label'   => __( 'Order', WPB_WCS_TEXTDOMAIN ),
                    'desc'    => __( 'Select Order for Categories', WPB_WCS_TEXTDOMAIN ),
                    'type'    => 'select',
                    'default' => 'ASC',
                    'options' => array(
                        'ASC'    => __( 'ASC', WPB_WCS_TEXTDOMAIN ),
                        'DESC'   => __( 'DESC', WPB_WCS_TEXTDOMAIN ),
                    )
                ),
                array(
                    'name'  => 'wpb_wcs_hide_empty',
                    'label' => __( 'Hide Empty Categories', WPB_WCS_TEXTDOMAIN ),
                    'desc'  => __( 'Yes', WPB_WCS_TEXTDOMAIN ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'              => 'wpb_wcs_exclude',
                    'label'             => __( 'Exclude Categories', WPB_WCS_TEXTDOMAIN ),
                    'desc'              => __( 'Comma separated categories id to exclude. Either use exclude or include, don\'t use both together.', WPB_WCS_TEXTDOMAIN ),
                    'placeholder'       => __( '53,55,76,96', WPB_WCS_TEXTDOMAIN ),
                    'type'              => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'wpb_wcs_include',
                    'label'             => __( 'Include Categories', WPB_WCS_TEXTDOMAIN ),
                    'desc'              => __( 'Comma separated categories id to include', WPB_WCS_TEXTDOMAIN ),
                    'placeholder'       => __( '53,55,76,96', WPB_WCS_TEXTDOMAIN ),
                    'type'              => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'wpb_wcs_number',
                    'label'             => __( 'Number of Categories', WPB_WCS_TEXTDOMAIN ),
                    'desc'              => __( 'The maximum number of terms to return. By default it returns all of them.', WPB_WCS_TEXTDOMAIN ),
                    'type'              => 'number',
                ),
            ),
            'style_settings' => array(
                array(
                    'name'      => 'text_color',
                    'label'     => __( 'Text Color', WPB_WCS_TEXTDOMAIN ),
                    'desc'      => __( 'Used for slider text color. Default use theme body color.', WPB_WCS_TEXTDOMAIN ),
                    'type'      => 'color',
                    'default'   => ''
                ),
                array(
                    'name'      => 'primary_color',
                    'label'     => __( 'Primary Color', WPB_WCS_TEXTDOMAIN ),
                    'desc'      => __( 'Used for slider button, navigation and links hover background.', WPB_WCS_TEXTDOMAIN ),
                    'type'      => 'color',
                    'default'   => '#39a1f4'
                ),
                array(
                    'name'      => 'secondary_color',
                    'label'     => __( 'Secondary Color', WPB_WCS_TEXTDOMAIN ),
                    'desc'      => __( 'Used for slider button and navigation background.', WPB_WCS_TEXTDOMAIN ),
                    'type'      => 'color',
                    'default'   => '#2196F3'
                ),
                array(
                    'name'      => 'slider_bg_color',
                    'label'     => __( 'Slider Background', WPB_WCS_TEXTDOMAIN ),
                    'desc'      => __( 'Slider item background color.', WPB_WCS_TEXTDOMAIN ),
                    'type'      => 'color',
                    'default'   => '#ededed'
                ),
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

new WPB_WCS_Settings_Config();