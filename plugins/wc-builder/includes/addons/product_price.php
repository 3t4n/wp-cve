<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class WPBForWPbakery_Product_Price{
    function __construct() {

        // creating shortcode addon
        add_shortcode( 'wpbforwpbakery_product_price', array( $this, 'render_shortcode' ) );

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );
    }

    public function render_shortcode( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'regular_price_color' => '', 
            'sale_price_color' => '',
            'el_class' => '', 
            'wrapper_css' => '', 
        ),$atts));

        global $product;
        $product = wc_get_product();
        if ( empty( $product ) ) { return; }

        ob_start();
        $unique_class = uniqid('wpbforwpbakery_product_price_');
        $output = '<style>';
        $output .= ".$unique_class {float:none !important;width:100% !important;margin:0 !important; }";
        $output .= ".$unique_class .price del{color: {$regular_price_color} !important }";
        $output .= ".$unique_class .price ins,.$unique_class .woocommerce-Price-amount{color: {$sale_price_color} !important }";
        $output .= '</style>';

        echo '<div class="summary entry-summary '. esc_attr($el_class . ' ' . $unique_class) .wpbforwpbakery_get_vc_custom_class($wrapper_css, ' ') .'">';
        woocommerce_template_single_price();
        echo '</div>';

        return $output.= ob_get_clean();
    }


    public function integrateWithVC() {
    
        /*
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("WCB: Product Price", 'wpbforwpbakery'),
            "base" => "wpbforwpbakery_product_price",
            "class" => "",
            "controls" => "full",
            "icon" => 'wpbforwpbakery_product_price_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('WC Builder', 'wpbforwpbakery'),
            "params" => array(
            	array(
            	    'param_name' => 'regular_price_color',
            	    'heading' => __( 'Regular Price Color', 'wpbforwpbakery' ),
            	    'type' => 'colorpicker',
            	),
            	array(
            	    'param_name' => 'sale_price_color',
            	    'heading' => __( 'Sale Price Color', 'wpbforwpbakery' ),
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
new WPBForWPbakery_Product_Price();