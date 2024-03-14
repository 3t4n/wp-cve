<?php

namespace WPT_Ultimate_Divi_Carousel\TaxonomyCarousel;

/**
 * .
 */
class Fields
{
    protected  $container ;
    protected  $module ;
    /**
     * Constructor.
     */
    public function __construct( $container )
    {
        $this->container = $container;
    }
    
    /**
     * Set the module instance.
     */
    public function set_module( $module )
    {
        $this->module = $module;
    }
    
    /**
     * Get selector
     */
    public function get_selector( $key )
    {
        $selectors = $this->get_selectors();
        return $selectors[$key]['selector'];
    }
    
    /**
     * List of selectors
     */
    public function get_selectors()
    {
        $selectors = [];
        $selectors = $selectors + $this->container['swiper_divi']->get_selectors( $this->module );
        return $selectors;
    }
    
    /**
     * Get default for given keys
     */
    public function get_default( $key )
    {
        $defaults = $this->get_defaults();
        return ( isset( $defaults[$key] ) ? $defaults[$key] : '' );
    }
    
    /**
     * Get defaults
     */
    public function get_defaults()
    {
        $defaults = [
            'post_type'           => '',
            'show_image'          => 'on',
            'show_title'          => 'on',
            'show_content'        => 'on',
            'content_type'        => 'post_excerpt',
            'show_button'         => 'on',
            'button_text'         => __( 'View All', 'ultimate-carousel-for-divi' ),
            'open_url'            => 'on',
            'card_url_new_window' => 'off',
        ];
        $defaults += $this->container['swiper_divi']->get_defaults();
        return $defaults;
    }
    
    /**
     * Get module fields
     */
    public function get_fields()
    {
        $fields = $this->container['swiper_divi']->get_fields( $this->module );
        $fields += $this->get_taxonomy_module_fields();
        $fields['admin_label'] = [
            'label'       => __( 'Admin Label', 'ultimate-carousel-for-divi' ),
            'type'        => 'text',
            'description' => __( 'This will change the label of the module in the builder for easy identification.', 'ultimate-carousel-for-divi' ),
        ];
        return $fields;
    }
    
    /**
     * Get fields related to taxonomy
     */
    public function get_taxonomy_module_fields()
    {
        $fields = [];
        $fields += $this->container['divi_taxonomy_query_builder']->get_fields(
            'taxonomy_builder',
            __( 'Post Type', 'ultimate-carousel-for-divi' ),
            'general',
            'taxonomy_toggle',
            __( 'Select a "Post Type" from the list.', 'ultimate-carousel-for-divi' )
        );
        return $fields;
    }
    
    public function get_css_fields()
    {
        $selectors = [];
        foreach ( $selectors as $key => $selector ) {
            $selectors[$key]['selector'] = "html body div#page-container " . $selector['selector'];
        }
        return $selectors;
    }
    
    public function set_advanced_toggles( &$toggles )
    {
    }
    
    /**
     * Advanced font definition
     */
    public function get_advanced_font_definition( $key )
    {
        return [
            'css' => [
            'main'      => $this->get_selector( $key ),
            'important' => 'all',
        ],
        ];
    }
    
    public function set_advanced_font_definition( &$config, $key )
    {
        $config['fonts'][$key] = $this->get_advanced_font_definition( $key );
    }

}