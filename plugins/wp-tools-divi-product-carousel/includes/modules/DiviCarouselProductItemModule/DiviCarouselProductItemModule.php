<?php

namespace WPT_Divi_Product_Carousel_Modules\DiviCarouselProductItemModule;

use  ET_Builder_Module ;
use  ET_Builder_Element ;
/**
 * Full width divi module.
 */
class DiviCarouselProductItemModule extends ET_Builder_Module
{
    public  $child_title_fallback_var = 'alt' ;
    public  $child_title_var = 'admin_label' ;
    public  $name = 'Product Carousel Item' ;
    public  $slug = 'et_pb_wptools_carousel_product_item' ;
    public  $type = 'child' ;
    public  $vb_support = 'on' ;
    protected  $container ;
    protected  $module_credits = array(
        'module_uri' => 'https://wptools.app/wordpress-plugin/product-carousel-for-divi/?utm_source=divi-module&utm_medium=module&utm_campaign=divi-product-carousel&utm_content=module',
        'author'     => 'WP Tools → Get 7 day FREE Trial',
        'author_uri' => 'https://wptools.app/wordpress-plugin/product-carousel-for-divi/?utm_source=divi-module&utm_medium=module&utm_campaign=divi-product-carousel&utm_content=module',
    ) ;
    /**
     * Constructor
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
     * fields
     */
    public function get_fields()
    {
        $fields = [];
        $fields['blog_id'] = [
            'label'       => esc_html__( 'Select Product Item', 'et_builder' ),
            'type'        => 'select',
            'options'     => $this->container['post']->get_post_list(),
            'toggle_slug' => 'main_content',
            'description' => esc_html__( 'Select a product item from the dropdown', 'et_builder' ),
            'default'     => '0-p',
        ];
        $fields['admin_label'] = [
            'label'       => __( 'Admin Label', 'et_builder' ),
            'type'        => 'text',
            'toggle_slug' => 'main_content',
            'description' => 'This will change the label of the module in the builder for easy identification.',
            'default'     => 'Product Item',
        ];
        return $fields;
    }
    
    /**
     * modal toggles
     */
    public function get_settings_modal_toggles()
    {
        return [
            'general' => [
            'toggles' => [
            'main_content' => esc_html__( 'WooCommerce Product', 'et_builder' ),
        ],
        ],
        ];
    }
    
    /**
     * init
     */
    public function init()
    {
    }
    
    /**
     * Renderer
     */
    public function render( $unprocessed_props, $content = null, $render_slug )
    {
        $module_classes = $this->module_classname( $render_slug );
        $module_class = trim( ET_Builder_Element::add_module_order_class( '', $render_slug ) );
        $defaults = [
            'blog_id' => '0-p',
        ];
        $props = wp_parse_args( $this->props, $defaults );
        if ( !$props['blog_id'] || $props['blog_id'] == '0-p' ) {
            return '';
        }
        return $this->container['post']->render_post_item( $props['blog_id'], $props );
    }
    
    protected function _render_module_wrapper( $output = '', $render_slug = '' )
    {
        return $output;
    }

}