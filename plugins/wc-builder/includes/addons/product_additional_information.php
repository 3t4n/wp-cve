<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');
class WPBForWPbakery_Product_Additional_Information{
    function __construct() {

        // creating shortcode addon
        add_shortcode( 'wpbforwpbakery_product_additional_information', array( $this, 'render_shortcode' ) );

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );
    }

    public function render_shortcode( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'heading_color' => '', 
            'heading_font_size' => '', 
            'heading_line_height' => '', 
            'content_color' => '', 
            'content_font_size' => '', 
            'content_line_height' => '', 
            'el_class' => '', 
            'wrapper_css' => '', 
        ),$atts));

        $unique_class = uniqid('wpbforwpbakery_product_additional_information');
        global $product;
        $product = wc_get_product();
        ob_start();

        $output = '<style>';
        $output .= ".woocommerce .$unique_class h2{color:{$heading_color}; font-size:{$heading_font_size}; line-height: {$heading_line_height}; }";
        $output .= ".woocommerce .$unique_class .shop_attributes{color:{$content_color}; font-size:{$content_font_size}; line-height: {$content_line_height}; }";
        $output .= '</style>';

        if ( empty( $product ) ) { return; }
        echo '<div class="'. esc_attr($el_class . ' ' . $unique_class) .wpbforwpbakery_get_vc_custom_class($wrapper_css, ' ') .'">';
            wc_get_template( 'single-product/tabs/additional-information.php' );
        echo '</div>';

        return $output .= ob_get_clean();
    }

    public function integrateWithVC() {
    
        /*
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("WCB: Product Additional Information", 'wpbforwpbakery'),
            "base" => "wpbforwpbakery_product_additional_information",
            "class" => "",
            "controls" => "full",
            "icon" => 'wpbforwpbakery_product_additional_information_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('WC Builder', 'wpbforwpbakery'),
            "params" => array(
            	array(
            	    'param_name' => 'heading_color',
            	    'heading' => __( 'Heading Color', 'wpbforwpbakery' ),
            	    'type' => 'colorpicker',
            	),
            	array(
            	    'param_name' => 'heading_font_size',
            	    'heading' => __( 'Heading Font Size', 'wpbforwpbakery' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Ex: 23px', 'wpbforwpbakery' ),
            	),
            	array(
            	    'param_name' => 'heading_line_height',
            	    'heading' => __( 'Heading Line Height', 'wpbforwpbakery' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Ex: 25px', 'wpbforwpbakery' ),
            	),
            	array(
            	    'param_name' => 'content_color',
            	    'heading' => __( 'Content Color', 'wpbforwpbakery' ),
            	    'type' => 'colorpicker',
            	),
            	array(
            	    'param_name' => 'content_font_size',
            	    'heading' => __( 'Content Font Size', 'wpbforwpbakery' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Ex: 23px', 'wpbforwpbakery' ),
            	),
            	array(
            	    'param_name' => 'content_line_height',
            	    'heading' => __( 'Content Line Height', 'wpbforwpbakery' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Ex: 25px', 'wpbforwpbakery' ),
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

new WPBForWPbakery_Product_Additional_Information();