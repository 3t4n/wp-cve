<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Dropcaps extends WPBakeryShortCode{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_dropcaps', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
    	wp_register_style( 'htmegavc-dropcaps', plugins_url('css/dropcaps.css', __FILE__));
    	wp_enqueue_style( 'htmegavc-dropcaps' );
    }


    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
           // content
           'dropcaps_style' => '',
           'dropcaps_text' => '',

           // customization
           // Dropcap Styling
           'content_dropcaps_color' => '',
           'content_dropcaps_background' => '',
           'content_dropcaps_padding' => '',
           'content_dropcaps_border_width' => '',
           'content_dropcaps_border_style' => '',
           'content_dropcaps_border_color' => '',
           'content_dropcaps_border_radius' => '',

           // Content Styling
           'content_color' => '',
           'content_typography' => '',
           'content_background' => '',
           'content_padding' => '',

           // Typography
           // Dropcap Typography
           'content_dropcaps_use_google_font' => '',
           'content_dropcaps_google_font' => '',
           'content_dropcaps_typography' => '',

           // Content Typography
           'content_use_google_font' => '',
           'content_google_font' => '',
           'content_typography' => '',

            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));


        // wrapper class
        $wrapper_class_arr = array();
        
        $unique_class = uniqid('htmegavc_dropcaps_');
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_dropcaps_wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        // add wrapper class
        $wrapper_class_arr[] =  'htmegavc-dropcaps-area';
        $wrapper_class_arr[] =  'htmegavc-dropcaps-style-'. $dropcaps_style;

        // join all wrapper class
        $wrapper_class = implode(' ', $wrapper_class_arr);

        // .htmegavc-dropcaps-inner p inline style
        $dropcaps_letter_inline_style = "color: $content_dropcaps_color;";
        $dropcaps_letter_inline_style .= "background-color: $content_dropcaps_background;";
        $dropcaps_letter_inline_style .= "padding: $content_dropcaps_padding;";
        $dropcaps_letter_inline_style .= "border-width:$content_dropcaps_border_width;";
        $dropcaps_letter_inline_style .= "border-style:$content_dropcaps_border_style;";
        $dropcaps_letter_inline_style .= "border-color:$content_dropcaps_border_color;";
        $dropcaps_letter_inline_style .= "border-radius:$content_dropcaps_border_radius;";

        // .htmegavc-dropcaps-inner p inline style
        $wrapper_p_inline_style = "color: $content_color;";
        $wrapper_p_inline_style .= "background-color: $content_background;";
        $wrapper_p_inline_style .= "padding: $content_padding;";

        // typography and google font
        // Dropcap Typography
        $google_font_data1 = htmegavc_build_google_font_data($content_dropcaps_google_font);
        if ( 'true' == $content_dropcaps_use_google_font && isset( $google_font_data1['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data1['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data1['values']['font_family'] );
        }

        // concate google font properties and other properties
        $content_dropcaps_google_font = htmegavc_build_google_font_style($google_font_data1);
        $dropcaps_letter_inline_style .= htmegavc_combine_font_container($content_dropcaps_typography.';'.$content_dropcaps_google_font);

        // Popover header Typography
        $google_font_data2 = htmegavc_build_google_font_data($content_google_font);
        if ( 'true' == $content_use_google_font && isset( $google_font_data2['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data2['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data2['values']['font_family'] );
        }

        // concate google font properties and other properties
        $content_google_font = htmegavc_build_google_font_style($google_font_data2);
        $wrapper_p_inline_style .= htmegavc_combine_font_container($content_typography.';'.$content_google_font);


        $output = '';
        $output .= '<style>';
        $output .= "
			.$unique_class .htmegavc-dropcaps-inner p{ $wrapper_p_inline_style }
			.$unique_class .htmegavc-dropcaps-inner p:first-of-type:first-letter,
			.$unique_class .htmegavc-dropcaps-inner:first-of-type:first-letter{ $dropcaps_letter_inline_style }
        ";
        $output .= '</style>';

        ob_start(); ?>

         <div class="<?php echo esc_attr($wrapper_class); ?>">
             <?php
                 if( !empty( $dropcaps_text ) ){
                     echo '<div class="htmegavc-dropcaps-inner">'.wpautop( $dropcaps_text ).'</div>';
                 }
             ?>
        </div>

        <?php 
        $output .= ob_get_clean();
        return $output;
  }
 
    public function integrateWithVC() {
 
        /*
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("HT Dropcaps", 'htmevavc'),
            "description" => __("Add Dropcaps to your page", 'htmevavc'),
            "base" => "htmegavc_dropcaps",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegvc_dropcaps_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('HT Mega Addons', 'htmevavc'),
            "params" => array(

            	// cotnent
            	array(
            	  "param_name" => "dropcaps_style",
            	  "heading" => __("Dropcaps Style", 'htmevavc'),
            	  "type" => "dropdown",
            	  "default_set" => '1',
            	  'value' => [
            	      __( 'Style One', 'htmegavc' )	=>	'1',
            	      __( 'Style Two', 'htmegavc' )	=>	'2',
            	      __( 'Style Three', 'htmegavc' )	=>	'3',
            	      __( 'Style Four', 'htmegavc' )	=>	'4',
            	      __( 'Style Five', 'htmegavc' )	=>	'5',
            	  ],
            	),
            	array(
            	    'param_name' => 'dropcaps_text',
            	    'heading' => __( 'Content', 'htmevavc' ),
            	    'type' => 'textarea',
            	    'value' => __( 'Lorem ipsum dolor sit amet, consec adipisicing elit, sed do eiusmod tempor incidid ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip exl Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incidid ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.', 'htmegavc' ),
            	),

                // Customization
                // Dropcap Styling
                array(
                    "param_name" => "custom_heading",
                    "type" => "htmegavc_param_heading",
                    "text" => __("Dropcap Styling","htmevavc"),
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'content_dropcaps_color',
                    'heading' => __( 'Dropcaps Text Color', 'htmevavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'content_dropcaps_background',
                    'heading' => __( 'Dropcaps Text BG Color', 'htmevavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'content_dropcaps_padding',
                    'heading' => __( 'Padding Around Dropcap Text', 'htmevavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS padding. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmevavc' ),
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'content_dropcaps_border_width',
                    'heading' => __( 'CSS Border width', 'htmevavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS Border width of blockquote. Example: 2px, which stand for border-width:2px;', 'htmevavc' ),
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'content_dropcaps_border_style',
                    'heading' => __( 'CSS Border style', 'htmevavc' ),
                    'type' => 'dropdown',
                    'value' => [
                        __( 'None', 'htmevavc' )  =>  'none',
                        __( 'Solid', 'htmevavc' )  =>  'solid',
                        __( 'Double', 'htmevavc' )  =>  'double',
                        __( 'Dotted', 'htmevavc' )  =>  'dotted',
                        __( 'Dashed', 'htmevavc' )  =>  'dashed',
                        __( 'Groove', 'htmevavc' )  =>  'groove',
                    ],
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'content_dropcaps_border_radius',
                    'heading' => __( 'Button Border Radius', 'htmevavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS Border Radius of button. Example: 5px, which stand for border-radius:5px;', 'htmevavc' ),
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'content_dropcaps_border_color',
                    'heading' => __( 'Border color', 'htmevavc' ),
                    'type' => 'colorpicker',
                    'description' => __( 'The CSS Border color.', 'htmevavc' ),
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),


                // Content Styling
                array(
                    "param_name" => "custom_heading",
                    "type" => "htmegavc_param_heading",
                    "text" => __("Content Styling","htmevavc"),
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'content_color',
                    'heading' => __( 'Content Color', 'htmevavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'content_background',
                    'heading' => __( 'Content BG Color', 'htmevavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'content_padding',
                    'heading' => __( 'Content Padding', 'htmevavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS padding. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmevavc' ),
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),

                // Typography
                // Dropcap Typography
                array(
                    "param_name" => "package_typograpy",
                    "type" => "htmegavc_param_heading",
                    "text" => __("Dropcaps Typography","htmevavc"),
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Typography', 'htmevavc' ),
                ),

                array(
                  'type' => 'checkbox',
                  'heading' => __( 'Use google font?', 'htmevavc' ),
                  'param_name' => 'content_dropcaps_use_google_font',
                  'description' => __( 'Use font family from google font.', 'htmevavc' ),
                  'group'  => __( 'Typography', 'htmevavc' ),
                ),
                array(
                  'type' => 'google_fonts',
                  'param_name' => 'content_dropcaps_google_font',
                  'group'  => __( 'Typography', 'htmevavc' ),
                  'settings' => array(
                    'fields' => array(
                      'font_family_description' => __( 'Select font family.', 'htmevavc' ),
                      'font_style_description' => __( 'Select font styling.', 'htmevavc' ),
                    ),
                  ),
                  'dependency' =>[
                      'element' => 'content_dropcaps_use_google_font',
                      'value' => array( 'true' ),
                  ],
                ),
                array(
                  'param_name' => 'content_dropcaps_typography',
                  'type' => 'font_container',
                  'group'  => __( 'Typography', 'htmevavc' ),
                  'settings' => array(
                    'fields' => array(
                      'font_size',
                      'line_height',
                      'text-align',
                      'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmevavc' ),
                      'color_description' => __( 'Select heading color.', 'htmevavc' ),
                    ),
                  ),
                ),
                

                //Content Typography
                array(
                    "param_name" => "package_typograpy",
                    "type" => "htmegavc_param_heading",
                    "text" => __("Content Typography","htmevavc"),
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Typography', 'htmevavc' ),
                ),
                array(
                  'type' => 'checkbox',
                  'heading' => __( 'Use google font?', 'htmevavc' ),
                  'param_name' => 'content_use_google_font',
                  'description' => __( 'Use font family from google font.', 'htmevavc' ),
                  'group'  => __( 'Typography', 'htmevavc' ),
                ),
                array(
                  'type' => 'google_fonts',
                  'param_name' => 'content_google_font',
                  'group'  => __( 'Typography', 'htmevavc' ),
                  'settings' => array(
                    'fields' => array(
                      'font_family_description' => __( 'Select font family.', 'htmevavc' ),
                      'font_style_description' => __( 'Select font styling.', 'htmevavc' ),
                    ),
                  ),
                  'dependency' =>[
                      'element' => 'content_use_google_font',
                      'value' => array( 'true' ),
                  ],
                ),
                array(
                  'param_name' => 'content_typography',
                  'type' => 'font_container',
                  'group'  => __( 'Typography', 'htmevavc' ),
                  'settings' => array(
                    'fields' => array(
                      'font_size',
                      'line_height',
                      'text-align',
                      'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmevavc' ),
                      'color_description' => __( 'Select heading color.', 'htmevavc' ),
                    ),
                  ),
                ),


                // extra class
                array(
                    'param_name' => 'custom_class',
                    'heading' => __( 'Extra class name', 'htmevavc' ),
                    'type' => 'textfield',
                    'description' => __( 'Style this element differently - add a class name and refer to it in custom CSS.', 'htmevavc' ),
                ),
                array(
                  "param_name" => "wrapper_css",
                  "heading" => __( "Wrapper Styling", "htmevavc" ),
                  "type" => "css_editor",
                  'group'  => __( 'Wrapper Styling', 'htmevavc' ),
              ),
            )
        ) );
    }

}

// Finally initialize code
new Htmegavc_Dropcaps();