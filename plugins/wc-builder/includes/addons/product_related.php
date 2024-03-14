<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class WPBForWPbakery_Product_Related{
    function __construct() {

        // creating shortcode addon
        add_shortcode( 'wpbforwpbakery_product_related', array( $this, 'render_shortcode' ) );

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );
    }

    public function render_shortcode( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'posts_per_page'              => !empty($atts['posts_per_page']) ? $atts['posts_per_page'] : 4, 
            'columns'                     => !empty($atts['columns']) ? $atts['columns'] : 4, 
            'orderby'                     => '', 
            'order'                       => '', 
            'show_heading'                => '',
            'related_heading_color'       => '',
            'related_heading_font_size'   => '',
            'related_heading_line_height' => '',
            'related_heading_align'       => '',
            'el_class'                    => '', 
            'wrapper_css'                 => '', 
        ),$atts));

        $unique_class = uniqid('wpbforwpbakery_product_related');
        global $product;
        $product = wc_get_product();
        $output = '';

        ob_start();
        if ( ! $product ) { return; }
        $args = array(
            'posts_per_page' => $posts_per_page,
            'limit'          => $posts_per_page,
            'columns'        => $columns,
            'orderby'        => $orderby,
            'order'          => $order,
        );

        $output = '<style>';
        $output .= ".related > h2:first-child{display:{$show_heading}; }";
        $output .= ".related > h2:first-child{ color: {$related_heading_color}; font-size:{$related_heading_font_size};line-height:{$related_heading_line_height}; text-align:{$related_heading_align} }";
        $output .= '</style>';

        echo '<div class="columns-'. esc_attr($args['columns'] .' '. $el_class .' '. $unique_class) .wpbforwpbakery_get_vc_custom_class($wrapper_css, ' ') .'">';

        woocommerce_related_products($args);

        echo '</div>';

        return $output.= ob_get_clean();
    }

    public function integrateWithVC() {
    
        /*
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("WCB: Related Products", 'wpbforwpbakery'),
            "base" => "wpbforwpbakery_product_related",
            "class" => "",
            "controls" => "full",
            "icon" => 'wpbforwpbakery_product_related_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('WC Builder', 'wpbforwpbakery'),
            "params" => array(
            	array(
            	    'param_name' => 'posts_per_page',
            	    'heading' => __( 'Products Per Page', 'wpbforwpbakery' ),
            	    'type' => 'textfield',
            	    "std" => '4',
            	),
            	array(
            	    'param_name' => 'columns',
            	    'heading' => __( 'Columns', 'wpbforwpbakery' ),
            	    "type" => "dropdown",
            	    "std" => '4',
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
            	    "std" => 'date',
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
            	    'param_name' => 'show_heading',
            	    'heading' => __( 'Show Heading', 'wpbforwpbakery' ),
            	    "type" => "dropdown",
            	    "std" => 'yes',
            	    'value' => [
            	        __( 'Yes', 'wpbforwpbakery' )  =>  'block',
            	        __( 'No', 'wpbforwpbakery' )  =>  'none',
            	    ],
            	),
            	array(
            	    'param_name' => 'related_heading_color',
            	    'heading' => __( 'Related Heading Color', 'wpbforwpbakery' ),
            	    'type' => 'colorpicker',
            	),
            	array(
            	    'param_name' => 'related_heading_font_size',
            	    'heading' => __( 'Related Heading Font Size', 'wpbforwpbakery' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Ex: 23px', 'wpbforwpbakery' ),
            	),
            	array(
            	    'param_name' => 'related_heading_line_height',
            	    'heading' => __( 'Related Heading Line Height', 'wpbforwpbakery' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Ex: 23px', 'wpbforwpbakery' ),
            	),
            	array(
            	    'param_name' => 'related_heading_align',
            	    'heading' => __( 'Related Heading Text Align', 'wpbforwpbakery' ),
            	    "type" => "dropdown",
            	    "std" => 'h2',
            	    'value' => [
            	        __( 'Left', 'wpbforwpbakery' )  =>  'left',
            	        __( 'Right', 'wpbforwpbakery' )  =>  'right',
            	        __( 'Center', 'wpbforwpbakery' )  =>  'center',
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

new WPBForWPbakery_Product_Related();