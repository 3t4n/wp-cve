<?php

namespace WPT_Ultimate_Divi_Carousel\ImageCardCarouselItem;

use  ET_Builder_Module ;
class ImageCardCarouselItem extends ET_Builder_Module
{
    public  $slug = 'et_pb_wpdt_image_card_carousel_item' ;
    public  $vb_support = 'on' ;
    public  $type = 'child' ;
    protected  $container ;
    protected  $helper ;
    public function __construct( $container, $fullwidth = false )
    {
        $this->container = $container;
        parent::__construct();
        $this->fullwidth = $fullwidth;
    }
    
    protected  $module_credits = array(
        'module_uri' => 'https://wptools.app/wordpress-plugin/ultimate-divi-carousel-for-image-post-type-taxonomy-woocommerce/?utm_source=image-card-carousel-module&utm_medium=divi-module&utm_campaign=utc-f2p&utm_content=divi-module',
        'author'     => 'WP Tools (7-day FREE Trial)',
        'author_uri' => 'https://wptools.app/wordpress-plugin/ultimate-divi-carousel-for-image-post-type-taxonomy-woocommerce/?utm_source=image-card-carousel-module&utm_medium=divi-module&utm_campaign=utc-f2p&utm_content=divi-module',
    ) ;
    /**
     * init divi module *
     */
    public function init()
    {
        $this->name = esc_html__( 'Image Carousel Item', 'ultimate-carousel-for-divi' );
        $this->advanced_setting_title_text = esc_html__( 'Image Carousel Item', 'ultimate-carousel-for-divi' );
        $this->child_title_var = 'admin_label';
        $this->main_css_element = '.wpt-ultimate-carousel %%order_class%%';
        add_filter(
            'et_builder_processed_range_value',
            [ $this, 'process_range_value' ],
            10,
            3
        );
    }
    
    /**
     * Process the `et_builder_processed_range_value` and make adjustments.
     */
    public function process_range_value( $result, $range, $range_string )
    {
        if ( false !== strpos( $result, '0calc' ) ) {
            return $range;
        }
        return $result;
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
            'toggles' => [
            'title'        => [
            'title' => esc_html__( 'Title', 'ultimate-carousel-for-divi' ),
        ],
            'main_content' => [
            'title' => esc_html__( 'Description', 'ultimate-carousel-for-divi' ),
        ],
            'image'        => [
            'title' => esc_html__( 'Image', 'ultimate-carousel-for-divi' ),
        ],
            'button'       => [
            'title' => esc_html__( 'Button / Link', 'ultimate-carousel-for-divi' ),
        ],
            'admin_label'  => [
            'title'    => esc_html__( 'Admin Label', 'ultimate-carousel-for-divi' ),
            'priority' => 999,
        ],
        ],
        ],
            'advanced' => [
            'toggles' => [],
        ],
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
        $multi_view = et_pb_multi_view_options( $this );
        $button_text = esc_html__( $this->container['divi']->get_prop_value( $this, 'button_text' ), 'ultimate-carousel-for-divi' );
        $button_url_new_window = $this->container['divi']->get_prop_value( $this, 'button_url_new_window' );
        $image_alt = esc_html__( $this->container['divi']->get_prop_value( $this, 'image_alt' ), 'ultimate-carousel-for-divi' );
        $title_level = $this->container['divi']->get_prop_value( $this, 'title_level' );
        $processed_title_level = et_pb_process_header_level( $title_level, 'h4' );
        $processed_title_level = esc_html( $processed_title_level );
        $show_button = $this->container['divi']->get_prop_value( $this, 'show_button' );
        $open_url = $this->container['divi']->get_prop_value( $this, 'open_url' );
        $card_url = $this->container['divi']->get_prop_value( $this, 'card_url' );
        $card_url_new_window = $this->container['divi']->get_prop_value( $this, 'card_url_new_window' );
        $image = $multi_view->render_element( [
            'tag'      => 'img',
            'attrs'    => [
            'src'   => '{{image}}',
            'class' => 'wpt-image-card-image',
            'alt'   => $image_alt,
        ],
            'required' => 'image',
        ] );
        $title = $multi_view->render_element( [
            'tag'      => $processed_title_level,
            'content'  => '{{title}}',
            'attrs'    => [
            'class' => 'wpt-image-card-title',
        ],
            'required' => 'title',
        ] );
        $content = $multi_view->render_element( [
            'tag'      => 'div',
            'content'  => '{{content}}',
            'attrs'    => [
            'class' => 'wpt-image-card-content',
        ],
            'required' => 'content',
        ] );
        $button = $this->render_button( [
            'display_button'      => ( '' !== $this->props['button_url'] && 'off' !== $this->props['show_button'] ? true : false ),
            'button_text'         => $button_text,
            'button_text_escaped' => true,
            'has_wrapper'         => true,
            'button_url'          => esc_url( $this->props['button_url'] ),
            'url_new_window'      => esc_attr( $this->props['button_url_new_window'] ),
            'button_custom'       => ( isset( $this->props['custom_button'] ) ? esc_attr( $this->props['custom_button'] ) : 'off' ),
            'custom_icon'         => ( isset( $this->props['button_icon'] ) ? $this->props['button_icon'] : '' ),
            'button_rel'          => ( isset( $this->props['button_rel'] ) ? esc_attr( $this->props['button_rel'] ) : '' ),
        ] );
        // process the content background.
        $this->container['divi_background']->process_background( [
            'base_prop_name'    => 'content_bg',
            'props'             => $this->props,
            'function_name'     => $render_slug,
            'selector'          => '%%order_class%% .wpt-image-card-content-wrapper',
            'selector_hover'    => '%%order_class%% .wpt-image-card-content-wrapper:hover',
            'important'         => ' !important',
            'prop_name_aliases' => [
            "use_content_bg_color_gradient" => "content_bg_use_color_gradient",
            "content_bg"                    => "content_bg_color",
        ],
        ] );
        // process image card padding.
        $this->container['divi']->process_advanced_margin_padding_css(
            $this,
            'image_card',
            $render_slug,
            $this->margin_padding
        );
        $this->add_classname( [ $this->get_text_orientation_classname(), 'wpt-image-card-slide', 'swiper-slide' ] );
        ob_start();
        require $this->container['plugin_dir'] . '/resources/views/image-card-carousel-item.php';
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
     * Get the css selector
     */
    public function get_selector( $key )
    {
        return $this->helper()->get_selector( $key );
    }

}