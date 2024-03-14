<?php
/**
 * Shortcode
 *
 * Create a Shortcode in WPBakery
 *
 * @category   Wordpress
 * @since      Class available since Release 1.0.0
 */


if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly.
}
if ( is_plugin_active( 'js_composer/js_composer.php' ) && ! class_exists( 'BlogsqodeShortcode' )) {

    class BlogsqodeShortcode {

        function __construct() {
            add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
            add_shortcode( 'blogsqode_blog_shortcode', array( $this, 'render_shortcode' ) );

        }        

        public function create_shortcode() {
            // Stop all if VC is not enabled
            if ( !defined( 'WPB_VC_VERSION' ) ) {
                return;
            }        

            // Map blockquote with vc_map()
            vc_map( array(
                'name'          => esc_html__('Blogsqode Blog Shortcode', 'blogsqode'),
                'base'          => 'blogsqode_blog_shortcode',
                'description'   => esc_html__( '', 'blogsqode' ),
                'category'      => esc_html__( 'Blogsqode Blog', 'blogsqode'),                
                'params' => array(
    
                    array(
                        'type'          => 'textfield',
                        'heading'       => esc_html__( 'Shortcode', 'blogsqode' ),
                        'param_name'    => 'blogsqode_shortcode',
                        'value'         => esc_html__( 'blogsqode_blog_list', 'blogsqode' ),
                        'description'   => esc_html__( 'Add shortcode', 'blogsqode' ),
                    ),

                    array(
                        'type'          => 'textfield',
                        'heading'       => esc_html__( 'Element ID', 'blogsqode' ),
                        'param_name'    => 'element_id',
                        'value'             => esc_html__( '', 'blogsqode' ),
                        'description'   => esc_html__( 'Enter element ID (Note: make sure it is unique and valid).', 'blogsqode' ),
                        'group'         => esc_html__( 'Extra', 'blogsqode'),
                    ),

                    array(
                        'type'          => 'textfield',
                        'heading'       => esc_html__( 'Extra class name', 'blogsqode' ),
                        'param_name'    => 'extra_class',
                        'value'             => esc_html__( '', 'blogsqode' ),
                        'description'   => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'blogsqode' ),
                        'group'         => esc_html__( 'Extra', 'blogsqode'),
                    ),               
                ),
            ));             

        }

        public function render_shortcode( $atts, $content, $tag ) {
            $atts = (shortcode_atts(array(
                'blogsqode_shortcode'  => '',
                'extra_class'       => '',
                'element_id'        => ''
            ), $atts));

            //Class and Id
            $extra_class        = esc_attr($atts['extra_class']);
            $element_id         = esc_attr($atts['element_id']);
            
            $output = '';
            $output .= '<div class="blogsqode_shortcode ' . esc_attr($extra_class) . '" id="' . esc_attr($element_id) . '" >';
            $output .= do_shortcode('[blogsqode_blog_list]');
            $output .= '</div>';

            return $output;                  

        }

    }

    new BlogsqodeShortcode();

}