<?php
/**
 * Blockquote
 *
 * Create a Blockquote in WPBakery
 *
 * @category   Wordpress
 * @since      Class available since Release 1.0.0
 */


if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly.
}
if ( is_plugin_active( 'js_composer/js_composer.php' ) && !class_exists( 'BlogsqodeBlockquote' )) {

        class BlogsqodeBlockquote {

            function __construct() {
                add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
                add_shortcode( 'blogsqode_blockquote', array( $this, 'render_shortcode' ) );

            }        

            public function create_shortcode() {
            // Stop all if VC is not enabled
                if ( !defined( 'WPB_VC_VERSION' ) ) {
                    return;
                }        

            // Map blockquote with vc_map()
                vc_map( array(
                    'name'          => esc_html__('Blogsqode Blockquote', 'blogsqode'),
                    'base'          => 'blogsqode_blockquote',
                    'description'   => esc_html__( '', 'blogsqode' ),
                    'category'      => esc_html__( 'Blogsqode Blog', 'blogsqode'),                
                    'params' => array(

                        array(
                            "type" => "textarea_html",
                            "holder" => "div",
                            "class" => "",                     
                            "heading" => esc_html__( "Blockquote Content", 'blogsqode' ),
                        "param_name" => "content", // Important: Only one textarea_html param per content element allowed and it should have "content" as a "param_name"
                        "value" => esc_html__( "<p>I am test text block. Click edit button to change this text.</p>", 'blogsqode' ),
                        "description" => esc_html__( "Enter content.", 'blogsqode' )
                    ),    

                        array(
                            'type'          => 'textfield',
                            'holder'        => 'div',
                            'heading'       => esc_html__( 'Author Quote', 'blogsqode' ),
                            'param_name'    => 'quote_author',
                            'value'         => esc_html__( '', 'blogsqode' ),
                            'description'   => esc_html__( 'Add Author Quote.', 'blogsqode' ),
                        ),


                        array(
                            "type" => "vc_link",
                            "class" => "",
                            "heading" => esc_html__( "Blockquote Cite", 'blogsqode' ),
                            "param_name" => "blockquote_cite",
                            "description" => esc_html__( "Add Citiation Link and Source Name", 'blogsqode' ),                                                
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
                    'blockquote_cite'   => '',
                    'quote_author'      => '',
                    'extra_class'       => '',
                    'element_id'        => ''
                ), $atts));


            //Content 
                $content            = esc_html(wpb_js_remove_wpautop($content, true));
                $quote_author       = esc_html($atts['quote_author']);

            //Cite Link
                $blockquote_source  = vc_build_link( $atts['blockquote_cite'] );
                $blockquote_title   = esc_html($blockquote_source["title"]);
                $blockquote_url     = esc_url( $blockquote_source['url'] );

            //Class and Id
                $extra_class        = esc_attr($atts['extra_class']);
                $element_id         = esc_attr($atts['element_id']);



                $output = '';
                $output .= '<div class="blockquote ' . esc_attr($extra_class) . '" id="' . esc_attr($element_id) . '" >';
                $output .= '<blockquote cite="' . esc_url($blockquote_url) . '">';
                $output .= esc_html($content);
                $output .= '</blockquote>';
                $output .= '</div>';

                return $output;                  

            }

        }

        new BlogsqodeBlockquote();

    }
