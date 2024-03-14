<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class WPBForWPbakery_Product_Stock{
    function __construct() {

        // creating shortcode addon
        add_shortcode( 'wpbforwpbakery_product_stock', array( $this, 'render_shortcode' ) );

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );
    }

    public function render_shortcode( $atts, $content = null ) {
        extract(shortcode_atts(array(
        	'in_stock_text_color' => '', 
        	'out_of_stock_text_color' => '',
            'el_class' => '', 
            'wrapper_css' => '', 
        ),$atts));


        global $product;
        $product = wc_get_product();
        if ( empty( $product ) ) { return; }

        $el_class = 'wpbforwpbakery_product_stock ' . $el_class;

        ob_start();
        $unique_class = uniqid('wpbforwpbakery_product_stock_');
        $output = '<style>';
        $output .= ".$unique_class .stock.in-stock{color: {$in_stock_text_color} !important;}";
        $output .= ".$unique_class .stock.out-of-stock{color: {$out_of_stock_text_color} !important;}";
        $output .= '</style>';
        
        echo '<div class="'. esc_attr($el_class . ' ' . $unique_class) .wpbforwpbakery_get_vc_custom_class($wrapper_css, ' ') .'">';

        echo wc_get_stock_html( $product );

        echo '</div>';

        return $output.= ob_get_clean();
    }


    public function integrateWithVC() {
    
        /*
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("WCB: Product Stock", 'wpbforwpbakery'),
            "base" => "wpbforwpbakery_product_stock",
            "class" => "",
            "controls" => "full",
            "icon" => 'wpbforwpbakery_product_stock_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('WC Builder', 'wpbforwpbakery'),
            "params" => array(
            	array(
            	    'param_name' => 'in_stock_text_color',
            	    'heading' => __( 'In Stock Text Color', 'wpbforwpbakery' ),
            	    'type' => 'colorpicker',
            	),
            	array(
            	    'param_name' => 'out_of_stock_text_color',
            	    'heading' => __( 'Out Of Stock Text Color', 'wpbforwpbakery' ),
            	    'type' => 'colorpicker',
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

new WPBForWPbakery_Product_Stock();