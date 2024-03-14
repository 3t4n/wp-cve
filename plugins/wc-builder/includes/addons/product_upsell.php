<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class WPBForWPbakery_Product_Upsell{
    function __construct() {

        // creating shortcode addon
        add_shortcode( 'wpbforwpbakery_product_upsell', array( $this, 'render_shortcode' ) );

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );
    }

    public function render_shortcode( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'el_class' => '', 
            'wrapper_css' => '', 
        ),$atts));

        $unique_class = uniqid('wpbforwpbakery_product_upsell');
        $product_per_page   = '-1';
        $columns            = 4;
        $orderby            = 'rand';
        $order              = 'desc';
        if ( ! empty( $columns ) ) {
            $columns = $columns;
        }
        if ( ! empty( $orderby ) ) {
            $orderby = $orderby;
        }
        if ( ! empty( $order ) ) {
            $order = $order;
        }

        ob_start();
        echo '<div class="'. esc_attr($el_class . ' ' . $unique_class) .wpbforwpbakery_get_vc_custom_class($wrapper_css, ' ') .'">';
            woocommerce_upsell_display( $product_per_page, $columns, $orderby, $order );
        echo '</div>';

        return ob_get_clean();
    }

    public function integrateWithVC() {
    
        /*
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("WCB: Product Upsell", 'wpbforwpbakery'),
            "base" => "wpbforwpbakery_product_upsell",
            "class" => "",
            "controls" => "full",
            "icon" => 'wpbforwpbakery_product_upsell_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('WC Builder', 'wpbforwpbakery'),
            "params" => array(
            	array(
            	    'param_name' => 'columns',
            	    'heading' => __( 'Columns', 'wpbforwpbakery' ),
            	    "type" => "dropdown",
            	    "default_set" => '4',
            	    'value' => [
            	        __( '1', 'wpbforwpbakery' )  =>  '1',
            	        __( '2', 'wpbforwpbakery' )  =>  '2',
            	        __( '3', 'wpbforwpbakery' )  =>  '3',
            	        __( '4', 'wpbforwpbakery' )  =>  '4',
            	        __( '5', 'wpbforwpbakery' )  =>  '5',
            	    ],
            	),
            	array(
            	    'param_name' => 'orderby',
            	    'heading' => __( 'Order By', 'wpbforwpbakery' ),
            	    "type" => "dropdown",
            	    "default_set" => 'date',
            	    'value' => [
            	        __( 'Date', 'wpbforwpbakery' )  =>  'date',
            	        __( 'Title', 'wpbforwpbakery' )  =>  'title',
            	        __( 'Price', 'wpbforwpbakery' )  =>  'price',
            	        __( 'Popularity', 'wpbforwpbakery' )  =>  'popularity',
            	        __( 'Rating', 'wpbforwpbakery' )  =>  'rating',
            	        __( 'Rand', 'wpbforwpbakery' )  =>  'rand',
            	        __( 'Menu Order', 'wpbforwpbakery' )  =>  'menu_order',
            	    ],
            	),
            	array(
            	    'param_name' => 'order',
            	    'heading' => __( 'Order', 'wpbforwpbakery' ),
            	    "type" => "dropdown",
            	    "default_set" => 'DESC',
            	    'value' => [
            	        __( 'ASC', 'wpbforwpbakery' )  =>  'ASC',
            	        __( 'DESC', 'wpbforwpbakery' )  =>  'DESC',
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

new WPBForWPbakery_Product_Upsell();