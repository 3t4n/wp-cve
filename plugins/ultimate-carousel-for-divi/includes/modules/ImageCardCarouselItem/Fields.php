<?php

namespace WPT_Ultimate_Divi_Carousel\ImageCardCarouselItem;

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
        return [];
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
            'open_url'            => 'off',
            'card_url'            => '#',
            'card_url_new_window' => 'off',
            'show_button'         => 'off',
        ];
        return $defaults;
    }
    
    /**
     * Get module fields
     */
    public function get_fields()
    {
        $et_accent_color = et_builder_accent_color();
        $fields = [
            'title'                 => [
            'label'       => esc_html__( 'Title', 'ultimate-carousel-for-divi' ),
            'type'        => 'text',
            'default'     => '',
            'tab_slug'    => 'general',
            'toggle_slug' => 'title',
            'description' => esc_html__( 'Input the title text.', 'ultimate-carousel-for-divi' ),
        ],
            'content'               => [
            'label'       => esc_html__( 'Content', 'ultimate-carousel-for-divi' ),
            'type'        => 'tiny_mce',
            'tab_slug'    => 'general',
            'toggle_slug' => 'main_content',
            'description' => esc_html__( 'Input the content text.', 'ultimate-carousel-for-divi' ),
        ],
            'image'                 => [
            'label'              => esc_html__( 'Image', 'ultimate-carousel-for-divi' ),
            'type'               => 'upload',
            'upload_button_text' => esc_attr__( 'Upload Image', 'ultimate-carousel-for-divi' ),
            'choose_text'        => esc_attr__( 'Choose Image', 'ultimate-carousel-for-divi' ),
            'update_text'        => esc_attr__( 'Set Image', 'ultimate-carousel-for-divi' ),
            'tab_slug'           => 'general',
            'toggle_slug'        => 'image',
            'description'        => esc_html__( 'Upload image for your image card.', 'ultimate-carousel-for-divi' ),
        ],
            'image_alt'             => [
            'label'       => esc_html__( 'Image Alt Text', 'ultimate-carousel-for-divi' ),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'image',
            'description' => esc_html__( 'Enter the "ALT" text for the image.', 'ultimate-carousel-for-divi' ),
        ],
            'show_button'           => [
            'label'       => esc_html__( 'Show Button', 'ultimate-carousel-for-divi' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'No', 'ultimate-carousel-for-divi' ),
            'on'  => esc_html__( 'Yes', 'ultimate-carousel-for-divi' ),
        ],
            'affects'     => [ 'custom_button' ],
            'default'     => 'off',
            'tab_slug'    => 'general',
            'toggle_slug' => 'button',
            'description' => esc_html__( 'Toggle switch to show/hide a button. Set "Yes" to show and "No" to hide.', 'ultimate-carousel-for-divi' ),
        ],
            'button_text'           => [
            'label'       => esc_html__( 'Button Text', 'ultimate-carousel-for-divi' ),
            'type'        => 'text',
            'show_if'     => [
            'show_button' => 'on',
        ],
            'default'     => esc_html__( 'Read More', 'ultimate-carousel-for-divi' ),
            'tab_slug'    => 'general',
            'toggle_slug' => 'button',
            'description' => esc_html__( 'Enter the button text.', 'ultimate-carousel-for-divi' ),
        ],
            'button_url'            => [
            'label'       => esc_html__( 'Url', 'ultimate-carousel-for-divi' ),
            'type'        => 'text',
            'default'     => '#',
            'show_if'     => [
            'show_button' => 'on',
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'button',
            'description' => esc_html__( 'Enter the URL to which the button should point to.', 'ultimate-carousel-for-divi' ),
        ],
            'button_url_new_window' => [
            'label'       => esc_html__( 'Link Target', 'ultimate-carousel-for-divi' ),
            'type'        => 'select',
            'options'     => [
            'off' => esc_html__( 'Same Window', 'ultimate-carousel-for-divi' ),
            'on'  => esc_html__( 'New Tab', 'ultimate-carousel-for-divi' ),
        ],
            'default'     => 'off',
            'show_if'     => [
            'show_button' => 'on',
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'button',
            'description' => esc_html__( 'Set the window on which you`d like the URL to open. Select "Same Window" to open link in same window and "New Tab" to open the link on a new browser tab.', 'ultimate-carousel-for-divi' ),
        ],
        ];
        $fields['open_url'] = [
            'label'       => esc_html__( 'Open URL On Item Click?', 'ultimate-carousel-for-divi' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'Off', 'ultimate-carousel-for-divi' ),
            'on'  => esc_html__( 'On', 'ultimate-carousel-for-divi' ),
        ],
            'show_if'     => [
            'show_button' => 'off',
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'button',
            'description' => esc_html__( 'Open an URL when the image card is clicked.', 'ultimate-carousel-for-divi' ),
            'default'     => $this->get_default( 'open_url' ),
        ];
        $fields['card_url'] = [
            'label'       => esc_html__( 'Url', 'ultimate-carousel-for-divi' ),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'button',
            'show_if'     => [
            'show_button' => 'off',
            'open_url'    => 'on',
        ],
            'description' => esc_html__( 'Enter the URL to which the card should point to.', 'ultimate-carousel-for-divi' ),
            'default'     => $this->get_default( 'card_url' ),
        ];
        $fields['card_url_new_window'] = [
            'label'       => esc_html__( 'Link Target', 'ultimate-carousel-for-divi' ),
            'type'        => 'select',
            'options'     => [
            'off' => esc_html__( 'Same Window', 'ultimate-carousel-for-divi' ),
            'on'  => esc_html__( 'New Tab', 'ultimate-carousel-for-divi' ),
        ],
            'default'     => $this->get_default( 'card_url_new_window' ),
            'show_if'     => [
            'show_button' => 'off',
            'open_url'    => 'on',
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'button',
            'description' => esc_html__( 'Set the window on which you`d like the URL to open. Select "Same Window" to open link in same window and "New Tab" to open the link on a new browser tab.', 'ultimate-carousel-for-divi' ),
        ];
        $fields['admin_label'] = [
            'label'       => __( 'Admin Label', 'ultimate-carousel-for-divi' ),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'admin_label',
            'description' => __( 'This will change the label of the module in the builder for easy identification.', 'ultimate-carousel-for-divi' ),
        ];
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