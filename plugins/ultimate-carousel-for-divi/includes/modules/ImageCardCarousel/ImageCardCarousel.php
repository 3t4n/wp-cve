<?php

namespace WPT_Ultimate_Divi_Carousel\ImageCardCarousel;

use  ET_Builder_Module ;
class ImageCardCarousel extends ET_Builder_Module
{
    public  $slug = 'et_pb_wpdt_image_card_carousel' ;
    public  $child_slug = 'et_pb_wpdt_image_card_carousel_item' ;
    public  $vb_support = 'on' ;
    protected  $container ;
    protected  $helper ;
    protected  $module_credits = array(
        'module_uri' => 'https://wptools.app/wordpress-plugin/ultimate-divi-carousel-for-image-post-type-taxonomy-woocommerce/?utm_source=image-card-carousel-module&utm_medium=divi-module&utm_campaign=utc-f2p&utm_content=divi-module',
        'author'     => 'WP Tools (7-day FREE Trial)',
        'author_uri' => 'https://wptools.app/wordpress-plugin/ultimate-divi-carousel-for-image-post-type-taxonomy-woocommerce/?utm_source=image-card-carousel-module&utm_medium=divi-module&utm_campaign=utc-f2p&utm_content=divi-module',
    ) ;
    public function __construct( $container, $fullwidth = false )
    {
        $this->container = $container;
        parent::__construct();
        $this->fullwidth = $fullwidth;
    }
    
    /**
     * init divi module *
     */
    public function init()
    {
        $this->name = esc_html__( 'Image Card Carousel', '' );
        $this->main_css_element = '%%order_class%%';
        $this->icon_path = $this->container['plugin_dir'] . '/images/divi-ultimate-carousel-logo-blue.svg';
    }
    
    /**
     * get the fields helper class *
     */
    public function helper()
    {
        
        if ( !$this->helper ) {
            $this->helper = new Fields( $this->container );
            $this->helper->set_module( $this );
        }
        
        return $this->helper;
    }
    
    /**
     * get the module toggles *
     */
    public function get_settings_modal_toggles()
    {
        $toggles = [
            'general'  => [
            'toggles' => [],
        ],
            'advanced' => [],
        ];
        $this->container['swiper_divi']->set_settings_modal_toggles( $toggles );
        return $toggles;
    }
    
    /**
     * get the css fields for advanced divi module settings *
     */
    public function get_custom_css_fields_config()
    {
        return $this->helper()->get_css_fields();
    }
    
    /**
     * get the advanced field for divi module settings *
     */
    public function get_advanced_fields_config()
    {
        $config = [
            'border'                => false,
            'borders'               => false,
            'text'                  => false,
            'box_shadow'            => false,
            'filters'               => false,
            'animation'             => false,
            'text_shadow'           => false,
            'max_width'             => false,
            'margin_padding'        => false,
            'custom_margin_padding' => false,
            'background'            => false,
            'fonts'                 => false,
            'link_options'          => false,
            'transform'             => false,
        ];
        return $config;
    }
    
    /**
     * get the divi module fields *
     */
    public function get_fields()
    {
        return $this->helper()->get_fields();
    }
    
    /**
     * Render the divi module *
     */
    public function render( $attrs, $content = null, $render_slug )
    {
        $this->container['divi']->add_free_plan_class( $this );
        $effect = $this->container['divi']->get_prop_value( $this, 'effect' );
        $slides_per_view = $this->container['divi']->get_prop_value( $this, 'slides_per_view' );
        $enable_coverflow_slide_shadow = $this->container['divi']->get_prop_value( $this, 'enable_coverflow_slide_shadow' );
        $coverflow_shadow_color = $this->container['divi']->get_prop_value( $this, 'coverflow_shadow_color' );
        $coverflow_rotate = $this->container['divi']->get_prop_value( $this, 'coverflow_rotate' );
        $coverflow_depth = $this->container['divi']->get_prop_value( $this, 'coverflow_depth' );
        $slider_loop = $this->container['divi']->get_prop_value( $this, 'slider_loop' );
        $autoplay = $this->container['divi']->get_prop_value( $this, 'autoplay' );
        $autoplay_speed = $this->container['divi']->get_prop_value( $this, 'autoplay_speed' );
        $pause_on_hover = $this->container['divi']->get_prop_value( $this, 'pause_on_hover' );
        $show_arrow = $this->container['divi']->get_prop_value( $this, 'show_arrow' );
        $show_arrow_on_hover = $this->container['divi']->get_prop_value( $this, 'show_arrow_on_hover' );
        $show_control_dot = $this->container['divi']->get_prop_value( $this, 'show_control_dot' );
        $coverflow_shadow_color = $this->container['divi']->get_prop_value( $this, 'coverflow_shadow_color' );
        $control_dot_active_color = $this->container['divi']->get_prop_value( $this, 'control_dot_active_color' );
        $control_dot_inactive_color = $this->container['divi']->get_prop_value( $this, 'control_dot_inactive_color' );
        $slide_transition_duration = $this->container['divi']->get_prop_value( $this, 'slide_transition_duration' );
        $arrow_font_size = $this->container['divi']->get_prop_value( $this, 'arrow_font_size' );
        $arrow_color = $this->container['divi']->get_prop_value( $this, 'arrow_color' );
        $arrow_position = $this->container['divi']->get_prop_value( $this, 'arrow_position' );
        $pagination_position = $this->container['divi']->get_prop_value( $this, 'pagination_position' );
        $this->props['pagination_bullets_custom_margin'] = $this->container['divi']->get_prop_value( $this, 'pagination_bullets_custom_margin' );
        $initial_slide = $this->container['divi']->get_prop_value( $this, 'initial_slide' );
        $centered_slides = $this->container['divi']->get_prop_value( $this, 'centered_slides' );
        $space_between_desktop = et_pb_responsive_options()->get_desktop_value( 'space_between', $this->props, $this->get_default( 'space_between' ) );
        $space_between_tablet = et_pb_responsive_options()->get_tablet_value( 'space_between', $this->props, $space_between_desktop );
        $space_between_phone = et_pb_responsive_options()->get_phone_value( 'space_between', $this->props, $space_between_tablet );
        $slides_per_view_values = $this->container['divi']->get_responsive_values( 'slides_per_view', $this->props, $this->get_default( 'slides_per_view' ) );
        list( $arrow_vertical_position, $arrow_horizontal_position ) = explode( '-', $arrow_position );
        $this->add_classname( [ $this->get_text_orientation_classname() ] );
        $module_classes = $this->module_classname( $render_slug );
        $module_class = trim( \ET_Builder_Element::add_module_order_class( '', $render_slug ) );
        if ( $this->container['divi']->is_ajax() && isset( $attrs['children'] ) ) {
            $this->content = $this->container['divi']->get_child_modules_content( $attrs['children'] );
        }
        
        if ( $this->content && '' !== $this->content ) {
            $this->container['swiper_divi']->enqueue_assets();
            $this->container['swiper_divi']->set_styles( $this, $render_slug, $this->props );
        }
        
        ob_start();
        require $this->container['plugin_dir'] . '/resources/views/image-card-carousel.php';
        return ob_get_clean();
    }
    
    /**
     * Get the default value for the field
     */
    public function get_default( $key )
    {
        return $this->helper()->get_default( $key );
    }
    
    /**
     * Get the css selector *
     */
    public function get_selector( $key )
    {
        return $this->helper()->get_selector( $key );
    }

}