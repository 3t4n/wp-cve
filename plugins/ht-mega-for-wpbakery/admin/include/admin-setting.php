<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

if ( ! function_exists('is_plugin_active')){ include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); }

class HTmegavc_Admin_Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new HTmegavc_Settings_API;

        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 220 );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->htmegavc_admin_get_settings_sections() );
        $this->settings_api->set_fields( $this->htmegavc_admin_fields_settings() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    // Plugins menu Register
    function admin_menu() {
        add_submenu_page(
            'vc-general',
            '',
            esc_html__( 'HTMega Addons', 'htmegavc' ),
            'manage_options',
            'htmegavc_addons_options',
            array ( $this, 'plugin_page' )
        );
    }

    // Options page Section register
    function htmegavc_admin_get_settings_sections() {
        $sections = array(
            
            array(
                'id'    => 'htmegavc_general_tabs',
                'title' => esc_html__( 'General', 'htmegavc' )
            ),

            array(
                'id'    => 'htmegavc_element_tabs',
                'title' => esc_html__( 'Element', 'htmegavc' )
            ),

            array(
                'id'    => 'htmegavc_thirdparty_element_tabs',
                'title' => esc_html__( 'Third Party', 'htmegavc' )
            ),

        );
        return $sections;
    }

    // Options page field register
    protected function htmegavc_admin_fields_settings() {

        $settings_fields = array(

            'htmegavc_general_tabs'=>array(

                array(
                    'name'  => 'google_map_api_key',
                    'label' => __( 'Google Map Api Key', 'htmegavc' ),
                    'desc'  => __( 'Go to <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">https://developers.google.com</a> and generate the API key.', 'htmegavc' ),
                    'placeholder' => __( 'Google Map Api key', 'htmegavc' ),
                    'type' => 'text',
                    'sanitize_callback' => 'sanitize_text_field'
                ),

            ),

            'htmegavc_element_tabs'=>array(

                array(
                    'name'  => 'accordion',
                    'label'  => __( 'Accordion', 'htmegavc' ),
                    'desc'  => __( 'Accordion', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'animatesectiontitle',
                    'label'  => __( 'Animate Heading', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'blockquote',
                    'label'  => __( 'Blockquote', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'brandlogo',
                    'label'  => __( 'Brands', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),
                
                array(
                    'name'  => 'businesshours',
                    'label'  => __( 'Business Hours', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'button',
                    'label'  => __( 'Button', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'calltoaction',
                    'label'  => __( 'Call To Action', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'countdown',
                    'label'  => __( 'Countdown', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'counter',
                    'label'  => __( 'Counter', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),
                
                array(
                    'name'  => 'dropcaps',
                    'label'  => __( 'Dropcaps', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'googlemap',
                    'label'  => __( 'Google Map', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'imagecomparison',
                    'label'  => __( 'Image Comparison', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'imagegrid',
                    'label'  => __( 'Image Grid', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),
                
                array(
                    'name'  => 'galleryjustify',
                    'label'  => __( 'Image Gallery Justify', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'imagemagnifier',
                    'label'  => __( 'Image Magnifier', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),
                
                array(
                    'name'  => 'imagemasonry',
                    'label'  => __( 'Image Masonry', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'lightbox',
                    'label'  => __( 'Light Box', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'popover',
                    'label'  => __( 'Popover', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'pricingtable',
                    'label'  => __( 'Pricing Table', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'progressbar',
                    'label'  => __( 'Progress Bar', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'sectiontitle',
                    'label'  => __( 'Section Title', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),
                
                array(
                    'name'  => 'thumbgallery',
                    'label'  => __( 'Slider Thumbnail Gallery', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'teammember',
                    'label'  => __( 'Team Member', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'testimonial',
                    'label'  => __( 'Testimonial', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'tooltip',
                    'label'  => __( 'Tooltip', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'verticletimeline',
                    'label'  => __( 'Verticle Timeline', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

                array(
                    'name'  => 'videoplayer',
                    'label'  => __( 'Video Player', 'htmegavc' ),
                    'type'  => 'checkbox',
                    'default'=>'on',
                    'class'=>'htmegavc_table_row',
                ),

            ),
        );
	
		// Third Party Addons
		$third_party_element = array();
		if( is_plugin_active('contact-form-7/wp-contact-form-7.php') ) {
		    $third_party_element['htmegavc_thirdparty_element_tabs'][] = [
		        'name'    => 'contactform',
		        'label'    => __( 'Contact form 7', 'htmegavc' ),
		        'type'    => 'checkbox',
		        'default' => "on",
		        'class'=>'htmega_table_row',
		    ];
		}

		if( is_plugin_active('mailchimp-for-wp/mailchimp-for-wp.php') ) {
		    $third_party_element['htmegavc_thirdparty_element_tabs'][] = [
		        'name'    => 'mailchimpwp',
		        'label'    => __( 'Mailchimp for wp', 'htmegavc' ),
		        'type'    => 'checkbox',
		        'default' => "on",
		        'class'=>'htmega_table_row',
		    ];
		}

        return array_merge($settings_fields, $third_party_element);
    }


    function plugin_page() {

        echo '<div class="wrap">';
            echo '<h2>'.esc_html__( 'HTMega Addons Settings','htmegavc' ).'</h2>';
            $this->save_message();
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
        echo '</div>';

    }

    function save_message() {
        if( isset($_GET['settings-updated']) ) { ?>
            <div class="updated notice is-dismissible"> 
                <p><strong><?php esc_html_e('Successfully Settings Saved.', 'htmegavc') ?></strong></p>
            </div>
            
            <?php
        }
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = [];
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }
        return $pages_options;
    }

}

new HTmegavc_Admin_Settings();