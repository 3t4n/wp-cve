<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class WPBForWPbakery_Product_Data_Tab{
    function __construct() {

        // creating shortcode addon
        add_shortcode( 'wpbforwpbakery_product_data_tab', array( $this, 'render_shortcode' ) );

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );
    }

    public function render_shortcode( $atts, $content = null ) {
        extract(shortcode_atts(array(
        	'tab_title_color' => '', 
        	'tab_title_font_size' => '', 
        	'tab_title_line_height' => '', 
        	'active_tab_title_color' => '', 
            'tab_heading_color' => '', 
            'tab_heading_font_size' => '', 
            'tab_heading_line_height' => '', 
            'el_class' => '', 
            'wrapper_css' => '', 
        ),$atts));

        global $product;
        $product = wc_get_product();
        if ( empty( $product ) ) { return; }

        ob_start();
        $unique_class = uniqid('wpbforwpbakery_product_data_tab');
        $output = '<style>';
        $output .= ".woocommerce .$unique_class ul li a{ color: {$tab_title_color}; font-size:{$tab_title_font_size} !important; line-height:{$tab_title_line_height} !important; }";
        $output .= ".woocommerce .$unique_class ul li.active a{ color: {$active_tab_title_color} !important; }";
        $output .= ".woocommerce .$unique_class .woocommerce-Tabs-panel h2{ color: {$tab_heading_color} !important; font-size:{$tab_heading_font_size} !important; line-height:{$tab_heading_line_height} !important; }";
        $output .= '</style>';


        echo '<div class="'. esc_attr($el_class . ' ' . $unique_class) .wpbforwpbakery_get_vc_custom_class($wrapper_css, ' ') .'">';
        	setup_postdata( $product->get_id() );
            wc_get_template( 'single-product/tabs/tabs.php' );
        echo '</div>';

        return $output.= ob_get_clean();
    }

    public function integrateWithVC() {
    
        /*
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("WCB: Product Data Tab", 'wpbforwpbakery'),
            "base" => "wpbforwpbakery_product_data_tab",
            "class" => "",
            "controls" => "full",
            "icon" => 'wpbforwpbakery_product_data_tab_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('WC Builder', 'wpbforwpbakery'),
            "params" => array(
            	array(
            	    'param_name' => 'tab_title_color',
            	    'heading' => __( 'Tab Title Color', 'wpbforwpbakery' ),
            	    'type' => 'colorpicker',
            	),
            	array(
            	    'param_name' => 'tab_title_font_size',
            	    'heading' => __( 'Tab Title Font Size', 'wpbforwpbakery' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Ex: 23px', 'wpbforwpbakery' ),
            	),
            	array(
            	    'param_name' => 'tab_title_line_height',
            	    'heading' => __( 'Tab Title Line Height', 'wpbforwpbakery' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Ex: 23px', 'wpbforwpbakery' ),
            	),
            	array(
            	    'param_name' => 'active_tab_title_color',
            	    'heading' => __( 'Active Tab Title Color', 'wpbforwpbakery' ),
            	    'type' => 'colorpicker',
            	),
            	array(
            	    'param_name' => 'tab_heading_color',
            	    'heading' => __( 'Tab Heading Color', 'wpbforwpbakery' ),
            	    'type' => 'colorpicker',
            	),
            	array(
            	    'param_name' => 'tab_heading_font_size',
            	    'heading' => __( 'Tab Heading Font Size', 'wpbforwpbakery' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Ex: 23px', 'wpbforwpbakery' ),
            	),
            	array(
            	    'param_name' => 'tab_heading_line_height',
            	    'heading' => __( 'Tab Heading Line Height', 'wpbforwpbakery' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Ex: 23px', 'wpbforwpbakery' ),
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

new WPBForWPbakery_Product_Data_Tab();