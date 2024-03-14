<?php

namespace WPT_Divi_Carousel_Images_Modules\DiviCarouselModule;

use  ET_Builder_Module ;
use  ET_Builder_Element ;
/**
 * DiviCarouselModule.
 */
class DiviCarouselModule extends ET_Builder_Module
{
    public  $main_css_element = 'section%%order_class%%' ;
    public  $name = 'Image Carousel' ;
    public  $slug = 'et_pb_wptools_image_carousel' ;
    public  $vb_support = 'on' ;
    protected  $container ;
    protected  $module_credits = array(
        'module_uri' => 'https://wptools.app/wordpress-plugin/image-carousel-for-divi/?utm_source=website&utm_medium=divi-module&utm_campaign=divi-img-carousel&utm_content=module',
        'author'     => 'WP Tools → Get 7 day FREE Trial',
        'author_uri' => 'https://wptools.app/wordpress-plugin/image-carousel-for-divi/?utm_source=website&utm_medium=divi-module&utm_campaign=divi-img-carousel&utm_content=module',
    ) ;
    /**
     * Constructor.
     */
    public function __construct( $container )
    {
        $this->container = $container;
        parent::__construct();
    }
    
    /**
     * Advanced fields.
     */
    public function get_advanced_fields_config()
    {
        return [
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
        ];
    }
    
    /**
     * Custom css fields.
     */
    public function get_custom_css_fields_config()
    {
        return [];
    }
    
    /**
     * Divi module fields.
     *
     * @return [type] [description]
     */
    public function get_fields()
    {
        return $this->container['carousel_module_fields']->get_fields();
    }
    
    /**
     * Settings modal toggle
     *
     * @return [type] [description]
     */
    public function get_settings_modal_toggles()
    {
        $toggles = $this->get_settings_modal_toggles__free();
        return $toggles;
    }
    
    /**
     * Freely available options.
     */
    public function get_settings_modal_toggles__free()
    {
        return [
            'general' => [
            'toggles' => [
            'main_content' => esc_html__( 'Carousel General Settings', 'et_builder' ),
        ],
        ],
        ];
    }
    
    /**
     * Init
     *
     * @return [type] [description]
     */
    public function init()
    {
        $this->child_item_text = esc_html__( 'Image', 'et_builder' );
        $this->child_slug = 'et_pb_wptools_carousel_image_item';
    }
    
    /**
     * Render function
     *
     * @param  [type] $unprocessed_props [description]
     * @param  [type] $content           [description]
     * @param  [type] $render_slug       [description]
     * @return [type] [description]
     */
    public function render( $unprocessed_props, $content = null, $render_slug )
    {
        // wp_die(var_dump($this->props, $unprocessed_props));
        $module_classes = $this->module_classname( $render_slug );
        $module_class = trim( ET_Builder_Element::add_module_order_class( '', $render_slug ) );
        $defaults = wp_parse_args( $unprocessed_props, $this->container['carousel_module_fields']->get_defaults() );
        foreach ( $defaults as $key => $value ) {
            if ( isset( $this->props[$key] ) and empty($this->props[$key]) ) {
                $this->props[$key] = $value;
            }
        }
        $props = wp_parse_args( $this->props, $defaults );
        $this->container[$render_slug] = $props;
        $this->container['divi']->enqueue_carousel_image_module_assets();
        $main_selector = 'section.' . $module_class;
        
        if ( isset(
            $_POST['object'],
            $_POST['object'][0],
            $_POST['object'][0]['attrs'],
            $_POST['object'][0]['attrs']['content']
        ) and is_array( $_POST['object'][0]['attrs']['content'] ) ) {
            $module_class = '.' . $render_slug . '_' . $_POST['et_fb_module_index'];
            $children_html = '';
            foreach ( $_POST['object'][0]['attrs']['content'] as $child_item ) {
                if ( !isset( $child_item['props']['attrs']['src'] ) ) {
                    $child_item['props']['attrs']['src'] = '';
                }
                if ( !isset( $child_item['props']['attrs']['alt'] ) ) {
                    $child_item['props']['attrs']['alt'] = '';
                }
                $children_html .= sprintf( '<img src="%s" alt="%s"/>', $child_item['props']['attrs']['src'], $child_item['props']['attrs']['alt'] );
            }
            $content = $children_html;
        } else {
            $content = $this->content;
        }
        
        //in-line style
        ET_Builder_Element::set_style( $render_slug, [
            'selector'    => "{$main_selector} .slick-arrow:before",
            'declaration' => sprintf( 'color:%s;', $props['arrows_background'] ),
        ] );
        ET_Builder_Element::set_style( $render_slug, [
            'selector'    => "{$main_selector} .slick-arrow",
            'declaration' => 'cursor:pointer !important;',
        ] );
        ET_Builder_Element::set_style( $render_slug, [
            'selector'    => "{$main_selector} .slick-dots li button:before",
            'declaration' => sprintf( 'opacity: 1!important;color:%s;', $props['dots_background'] ),
        ] );
        ET_Builder_Element::set_style( $render_slug, [
            'selector'    => "{$main_selector} .slick-dots li.slick-active button:before",
            'declaration' => sprintf( 'opacity: 1!important;color:%s;', $props['dots_active_background'] ),
        ] );
        ET_Builder_Element::set_style( $render_slug, [
            'selector'    => "{$main_selector}.slick-initialized .slick-track",
            'declaration' => sprintf( 'display: flex;align-items: %s;', $props['vertical_alignment'] ),
        ] );
        ob_start();
        require $this->container['dir'] . '/resources/views/wptools-divi-carousel-images.php';
        return ob_get_clean();
    }

}