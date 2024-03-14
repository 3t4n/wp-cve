<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class WPBForWPbakery_Product_Reviews{
    function __construct() {

        // creating shortcode addon
        add_shortcode( 'wpbforwpbakery_product_reviews', array( $this, 'render_shortcode' ) );

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );
    }

    public function render_shortcode( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'el_class' => '', 
            'wrapper_css' => '', 
        ),$atts));

        $unique_class = uniqid('wpbforwpbakery_product_reviews');
        global $product;
        $product = wc_get_product();
        ob_start();

        if ( empty( $product ) ) { return; }
        add_filter( 'comments_template', array( 'WC_Template_Loader', 'comments_template_loader' ) );
        echo '<div class="'. esc_attr($el_class . ' ' . $unique_class) .wpbforwpbakery_get_vc_custom_class($wrapper_css, ' ') .'">';
            comments_template();
        echo '</div>';

        return ob_get_clean();
    }

    public function integrateWithVC() {
    
        /*
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("WCB: Product Reviews", 'wpbforwpbakery'),
            "base" => "wpbforwpbakery_product_reviews",
            "class" => "",
            "controls" => "full",
            "icon" => 'wpbforwpbakery_product_reviews_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('WC Builder', 'wpbforwpbakery'),
            "params" => array(
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

new WPBForWPbakery_Product_Reviews();