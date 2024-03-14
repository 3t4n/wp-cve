<?php

namespace WPT_Divi_Product_Carousel_Modules\DiviProductCarouselModule;

/**
 * .
 */
class Fields
{
    protected  $container ;
    /**
     * Constructor.
     */
    public function __construct( $container )
    {
        $this->container = $container;
    }
    
    public function get_blog_title_fields()
    {
        $fields = [];
        return $fields;
    }
    
    /**
     * Get carousel params
     */
    public function get_carousel_js_params( $props )
    {
        $params = [
            'initialSlide' => (int) $props['initialslide'],
            'zIndex'       => (int) $props['zindex'],
        ];
        $toggles = [
            'arrows',
            'dots',
            'autoplay',
            'infinite',
            'pauseOnFocus',
            'pauseOnHover'
        ];
        foreach ( $toggles as $key ) {
            if ( isset( $props[strtolower( $key )] ) ) {
                $params[$key] = ( $props[strtolower( $key )] == 'on' ? true : false );
            }
        }
        // responsive params
        $params['slidesToShow'] = (int) $props['slidestoshow'];
        $params['slidesToScroll'] = (int) $props['slidestoscroll'];
        return $params;
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
            'arrows'                 => 'on',
            'dots'                   => 'on',
            'autoplay'               => 'on',
            'autoplayspeed'          => 3000,
            'slidestoshow'           => 3,
            'slidestoscroll'         => 1,
            'infinite'               => 'off',
            'pauseonfocus'           => 'on',
            'pauseonhover'           => 'on',
            'zindex'                 => '1000',
            'arrows_background'      => '#000000',
            'dots_background'        => '#000000',
            'dots_active_background' => 'red',
            'vertical_alignment'     => 'center',
            'equal_height'           => 'off',
        ];
        return $defaults;
    }
    
    /**
     * Get module fields
     */
    public function get_fields()
    {
        $fields = [];
        $fields['initialslide'] = [
            'label'          => esc_html__( 'Slide to start on', 'et_builder' ),
            'type'           => 'range',
            'range_settings' => [
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ],
            'toggle_slug'    => 'main_content',
            'description'    => esc_html__( 'Slide to start on', 'et_builder' ),
            'validate_unit'  => false,
            'default_unit'   => '',
            'default'        => $this->get_default( 'initialslide' ),
        ];
        $fields['zindex'] = [
            'label'          => esc_html__( 'Z-Index', 'et_builder' ),
            'type'           => 'range',
            'range_settings' => [
            'min'  => -999,
            'max'  => 9999,
            'step' => 1,
        ],
            'tab_slug'       => 'general',
            'toggle_slug'    => 'main_content',
            'description'    => esc_html__( 'Set the zindex values for slides, useful for IE9 and lower', 'et_builder' ),
            'validate_unit'  => false,
            'default_unit'   => '',
            'default'        => $this->get_default( 'zindex' ),
        ];
        $fields['admin_label'] = [
            'label'       => __( 'Admin Label', 'et_builder' ),
            'type'        => 'text',
            'description' => 'This will change the label of the module in the builder for easy identification.',
        ];
        return $fields + $this->get_nav_fields() + $this->get_blog_title_fields() + $this->get_slide_fields() + $this->get_motion_fields() + $this->get_product_short_desc_fields() + $this->get_responsive_fields();
    }
    
    public function get_product_short_desc_fields()
    {
        $fields = [];
        return $fields;
    }
    
    /**
     * Autoplay fields
     */
    public function get_motion_fields()
    {
        $fields = [];
        return $fields;
    }
    
    /**
     * Navigation fields
     */
    public function get_nav_fields()
    {
        $fields = [];
        return $fields;
    }
    
    /**
     * Responsive slide count fields
     */
    public function get_responsive_fields()
    {
        $fields = [];
        $fields['slidestoshow'] = [
            'label'          => esc_html__( 'Number of visible items', 'et_builder' ),
            'type'           => 'range',
            'range_settings' => [
            'min'  => 1,
            'max'  => 100,
            'step' => 1,
        ],
            'toggle_slug'    => 'main_content',
            'description'    => esc_html__( 'Number of items to show on desktop. Premium version allows setting number of slides for tablets and mobile', 'et_builder' ),
            'validate_unit'  => false,
            'default'        => $this->get_default( 'slidestoshow' ),
            'default_unit'   => '',
        ];
        return $fields;
    }
    
    public function get_slide_fields()
    {
        $fields = [];
        return $fields;
    }

}