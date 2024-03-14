<?php

/**
 * WordPress settings API demo class
 *
 * @author Tareq Hasan
 */
if ( !class_exists('themesCode_Settings_API_Test' ) ):
class themesCode_Settings_API_Test {

    private $settings_api;

    function __construct() {
        $this->settings_api = new themesCode_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        //add_action( 'admin_menu', array($this, 'admin_menu') );
        add_action( 'admin_menu', array($this, 'sub_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }



     function sub_menu()
    {
      add_submenu_page( 'edit.php?post_type=tctestimonial','Testimonial Settings','Testimonial Settings', 'manage_options','tct-settings',array($this, 'plugin_page'));
    }


    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'tct_basics',
                'title' => __( 'Basic Settings', 'TCODES' )
            ),
            array(
                'id' => 'tct_advanced',
                'title' => __( 'Advanced Settings', 'TCODES' )
            ),
            array(
                'id' => 'tct_styles',
                'title' => __( 'General Styling', 'TCODES' )
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
            'tct_basics' => array(

              array(
                  'name'    => 'hide-midborder',
                  'label'   => __( 'Hide Mid Border', 'TCODES' ),
                  'desc'    => __( 'Hide Mid Border under author details.', 'TCODES' ),
                  'type'    => 'select',
                  'default' => 'no',
                  'options' => array(
                      'yes' => 'Yes',
                      'no'  => 'No'
                  )
              ),
              array(
                  'name'    => 'auto-play',
                  'label'   => __( 'Auto Play', 'TCODES' ),
                  'desc'    => __( 'By default  Auto Play is active.', 'TCODES' ),
                  'type'    => 'select',
                  'default' => 'true',
                  'options' => array(
                      'true' => 'Yes',
                      'false'  => 'No'
                  )
              ),
              array(
                  'name'    => 'auto_play_timeout',
                  'label'   => __( 'Auto Play Timeout', 'TCODES' ),
                  'desc'    => __( 'Set autoplay Timeout', 'TCODES' ),
                  'type'              => 'text',
                  'default'           => 4000,
                  'sanitize_callback' => 'intval'
              ),
              array(
                  'name'    => 'stop-onhover',
                  'label'   => __( 'Stop On Hover', 'TCODES' ),
                  'desc'    => __( 'By default  Stop On Hover is active.', 'TCODES' ),
                  'type'    => 'select',
                  'default' => 'true',
                  'options' => array(
                      'true' => 'Yes',
                      'false'  => 'No'
                  )
              ),
              array(
                  'name'    => 'loop',
                  'label'   => __( 'Carousel Loop', 'TCODES' ),
                  'desc'    => __( 'By default Loop is active.', 'TCODES' ),
                  'type'    => 'select',
                  'default' => 'true',
                  'options' => array(
                      'true' => 'Yes',
                      'false'  => 'No'
                  )
              ),

              array(
                  'name'              => 'medium-desktops',
                  'label'             => __( 'Items Number ( Desktop )', 'TCODES' ),
                  'desc'              => __( '2 is recomended', 'TCODES' ),
                  'type'              => 'select',
                  'default'           => 1,
                  'options' => array(
                      '1' => '1',
                      '2'  => '2'
                  )
              ),

              array(
                  'name'              => 'items-tablet-val',
                  'label'             => __( 'Items Number ( Tablet )', 'TCODES' ),
                  'desc'              => __( '1 is recomended', 'TCODES' ),
                  'type'              => 'select',
                  'default'           => 1,
                  'options' => array(
                      '1' => '1',
                      '2'  => '2'
                  )
              )


            ),
            'tct_advanced' => array(

                array(
                    'name'    => 'nav-val',
                    'label'   => __( 'Navigation ', 'TCODES' ),
                    'desc'    => __( 'DroEnable/Disable Navigation', 'TCODES' ),
                    'type'    => 'select',
                    'default' => 'true',
                    'options' => array(
                        'true' => 'Yes',
                        'false'  => 'No'
                    )
                ),
                array(
                    'name'    => 'dots-val',
                    'label'   => __( 'Dots ', 'TCODES' ),
                    'desc'    => __( 'Enable/Disable Dots', 'TCODES' ),
                    'type'    => 'select',
                    'default' => 'true',
                    'options' => array(
                        'true' => 'Yes',
                        'false'  => 'No'
                    )
                ),
                array(
                    'name'    => 'autoheight',
                    'label'   => __( 'Auto Height', 'TCODES' ),
                    'desc'    => __( 'Enable/Disable Auto Height', 'TCODES' ),
                    'type'    => 'select',
                    'default' => 'false',
                    'options' => array(
                        'true' => 'Yes',
                        'false'  => 'No'
                    )
                ),
                array(
                    'name'    => 'auto-width',
                    'label'   => __( 'Auto Width', 'TCODES' ),
                    'desc'    => __( 'Image width will be automatic', 'TCODES' ),
                    'type'    => 'select',
                    'default' => 'false',
                    'options' => array(
                        'true' => 'Yes',
                        'false'  => 'No'
                    )
                ),
                array(
                    'name'    => 'rtl-val',
                    'label'   => __( 'Right To Left', 'TCODES' ),
                    'desc'    => __( 'Right To Left', 'TCODES' ),
                    'type'    => 'select',
                    'default' => 'false',
                    'options' => array(
                        'true' => 'Yes',
                        'false'  => 'No'
                    )
                ),

                array(
                    'name'              => 'stage-padding',
                    'label'             => __( 'Stage Padding', 'TCODES' ),
                    'desc'              => __( 'Any Numaric value. 2 is recomended', 'TCODES' ),
                    'type'              => 'text',
                    'default'           => 0,
                    'sanitize_callback' => 'intval'
                ),
                array(
                    'name'              => 'margin-val',
                    'label'             => __( 'Margin', 'TCODES' ),
                    'desc'              => __( 'Any Numaric value.', 'TCODES' ),
                    'type'              => 'text',
                    'default'           => 5,
                    'sanitize_callback' => 'intval'
                )

            ),
            'tct_styles' => array(

              array(
                  'name'    => 'navigation-color',
                  'label'   => __( 'Navigation Background Color', 'TCODES' ),
                  'desc'    => __( 'Navigation Button Background Color', 'TCODES' ),
                  'type'    => 'color',
                  'default' => '#fff'
              ),


              array(
                  'name'    => 'navigation-hover-color',
                  'label'   => __( 'Navigation Background Hover Color', 'TCODES' ),
                  'desc'    => __( 'Navigation Background Hover Color', 'TCODES' ),
                  'type'    => 'color',
                  'default' => '#FF6766'
              ),


              array(
                  'name'    => 'dots-color',
                  'label'   => __( 'Dots Color', 'TCODES' ),
                  'desc'    => __( 'Dots Button Color', 'TCODES' ),
                  'type'    => 'color',
                  'default' => '#34495E'
              ),
              array(
                  'name'    => 'dots-hover-color',
                  'label'   => __( 'Dots Hover Color', 'TCODES' ),
                  'desc'    => __( 'Dots Hover Color', 'TCODES' ),
                  'type'    => 'color',
                  'default' => '#FF6766'
              ),
              /*
              array(
                  'name'    => 'navigation-ac',
                  'label'   => __( 'Navigation Arrow Color', 'TCODES' ),
                  'desc'    => __( 'navigation Arrow Color', 'TCODES' ),
                  'type'    => 'color',
                  'default' => '#343434'
              ),
              */
              array(
                  'name'    => 'author-name',
                  'label'   => __( 'Author Name-details', 'TCODES' ),
                  'desc'    => __( 'Author Name and details Color', 'TCODES' ),
                  'type'    => 'color',
                  'default' => '#343434'
              ),
              array(
                  'name'    => 'author-image-border',
                  'label'   => __( 'Author Image border', 'TCODES' ),
                  'desc'    => __( 'Author Image border Color', 'TCODES' ),
                  'type'    => 'color',
                  'default' => '#343434'
              ),
              array(
                  'name'    => 'navigation-ac',
                  'label'   => __( 'Navigation Arrow Color', 'TCODES' ),
                  'desc'    => __( 'navigation Arrow Color', 'TCODES' ),
                  'type'    => 'color',
                  'default' => '#343434'
              ),
              array(
                  'name'    => 'tcpt-box-bg',
                  'label'   => __( 'Background Color', 'TCODES' ),
                  'desc'    => __( 'Testimonial Box Background Color', 'TCODES' ),
                  'type'    => 'color',
                  'default' => '#71BA51'
              ),

              array(
                  'name'    => 'tcpt-text',
                  'label'   => __( 'Text Color', 'TCODES' ),
                  'desc'    => __( 'Text Color', 'TCODES' ),
                  'type'    => 'color',
                  'default' => '#fff'
              ),

              array(
                  'name'    => 'tcpt-quote',
                  'label'   => __( 'Quote Icon Color', 'TCODES' ),
                  'desc'    => __( 'Quote Icon Color', 'TCODES' ),
                  'type'    => 'color',
                  'default' => '#FC575E'
              ),

              array(
                  'name'    => 'tcpt-mborder',
                  'label'   => __( 'Middle border Color', 'TCODES' ),
                  'desc'    => __( 'Middle border Color', 'TCODES' ),
                  'type'    => 'color',
                  'default' => '#FC575E'
              ),


            )

        );

        return $settings_fields;
    }
    function plugin_page() {
        echo '<div class="tcpc-wrap">';

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
