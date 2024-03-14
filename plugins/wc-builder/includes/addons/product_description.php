<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class WPBForWPbakery_Product_Description{
    function __construct() {
        // creating shortcode addon
        add_shortcode( 'wpbforwpbakery_product_description', array( $this, 'render_shortcode' ) );

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );
    }

    public function render_shortcode( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'text_align' => '', 
            'text_color' => '', 
            'font_size' => '', 
            'line_height' => '', 
            'use_google_font' => '', 
            'google_font' => 'font_family:Abril Fatface|font_style:400 regular', 
            'el_class' => '', 
            'wrapper_css' => '', 
        ),$atts));

        ob_start();
        global $product, $post;
        $product = wc_get_product();

        $style = '';
        $styles = array();

        if( $text_align != "" ){
        	$styles[] = 'text-align:'. $text_align .'';
        }

        if( $text_color != "" ){
        	$styles[] = 'color:'. $text_color .'';
        }

        if( $font_size != "" ){
        	$styles[] = 'font-size:'. $font_size .'';
        }

        if( $line_height != "" ){
        	$styles[] = 'line-height:'. $line_height .'';
        }

        $font_inline_style = '';
        if($use_google_font == 'true'){
        	$font_data = wpbforwpbakery_get_fonts_data( $google_font );
        	wpbforwpbakery_enqueue_google_font( $font_data );
        	$font_inline_style = wpbforwpbakery_get_font_inline_style( $font_data );
        }

        // concate styles
        if ( ! empty( $styles ) ) {
        	$style = 'style="' . esc_attr( implode( ';', $styles ) . ';' . $font_inline_style ) . '"';
        }

        printf('<div class="woocommerce_product_description %1$s" %3$s>%2$s</div>',
        	esc_attr($el_class) . wpbforwpbakery_get_vc_custom_class($wrapper_css, ' '),
        	$post->post_content,
        	$style
    	);

        return ob_get_clean();
    }

    public function integrateWithVC() {
        /*
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("WCB: Product Description", 'wpbforwpbakery'),
            "base" => "wpbforwpbakery_product_description",
            "class" => "",
            "controls" => "full",
            "icon" => 'wpbforwpbakery_product_description_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('WC Builder', 'wpbforwpbakery'),
            "params" => array(
                array(
                  "param_name" => "text_align",
                  "heading" => __("Text Align", 'wpbforwpbakery'),
                  "type" => "dropdown",
                  "default_set" => 'left',
                  'value' => wpbforwpbakery_text_align_lists(),
                ),
                array(
                    'param_name' => 'text_color',
                    'heading' => __( 'Text color', 'wpbforwpbakery' ),
                    'type' => 'colorpicker',
                ),
                array(
                    'param_name' => 'font_size',
                    'heading' => __( 'Font Size', 'wpbforwpbakery' ),
                    'type' => 'textfield',
                    'description' => __( 'Ex: 23px', 'wpbforwpbakery' ),
                ),
                array(
                    'param_name' => 'line_height',
                    'heading' => __( 'Line Height', 'wpbforwpbakery' ),
                    'type' => 'textfield',
                    'description' => __( 'Ex: 25px', 'wpbforwpbakery' ),
                ),
                array(
                  'type' => 'checkbox',
                  'heading' => __( 'Use google font?', 'wpbforwpbakery' ),
                  'param_name' => 'use_google_font',
                  'description' => __( 'Use font family from google font.', 'wpbforwpbakery' ),
                ),
                array(
                  'type' => 'google_fonts',
                  'param_name' => 'google_font',
                  'settings' => array(
                    'fields' => array(
                      'font_family_description' => __( 'Select font family.', 'wpbforwpbakery' ),
                      'font_style_description' => __( 'Select font styling.', 'wpbforwpbakery' ),
                    ),
                  ),
                  'dependency' =>[
                      'element' => 'use_google_font',
                      'value' => array( 'true' ),
                  ],
                ),

                array(
                    'param_name' => 'el_class',
                    'heading' => __( 'Extra class name', 'wpbforwpbakery' ),
                    'type' => 'textfield',
                    'description' => __( 'Style this element differently - add a class name and refer to it in custom CSS.', 'wpbforwpbakery' ),
                ),
                array(
                  "param_name" => "wrapper_css",
                  "heading" => __( "Wrapper Styling", "wpbforwpbakery" ),
                  "type" => "css_editor",
                  'group'  => __( 'Wrapper Styling', 'wpbforwpbakery' ),
              ),
            )
        ) );
    }
}

new WPBForWPbakery_Product_Description();