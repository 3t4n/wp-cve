<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Image_Comparison extends WPBakeryShortCode{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_image_comparison', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
    	wp_register_script( 'jquery-beerslider', HTMEGAVC_LIBS_URI . '/beerslider/jquery-beerslider-min.js', '', '', '');
    	wp_enqueue_script( 'jquery-beerslider' );

    	wp_register_style( 'beerslider', HTMEGAVC_LIBS_URI . '/beerslider/BeerSlider.css');
    	wp_enqueue_style( 'beerslider' );

    	wp_register_style( 'htmegavc-image-comparison', plugins_url('css/image-comparison.css', __FILE__));
    	wp_enqueue_style( 'htmegavc-image-comparison' );

    	wp_register_script( 'htmegavc-beerslider-active', plugins_url('js/beerslider-active.js', __FILE__), '', '', true);
    	wp_enqueue_script( 'htmegavc-beerslider-active' );
    }


    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
           // Content
           'style' => '1',
           'before_image' => '',
           'after_image' => '',
           'image_size' => '',
           'before_title' => __('Before', 'htmegavc'),
           'after_title' => __('After', 'htmegavc'),
           'start_amount' => '50',
           'imagecomparison_laben_pos' => 'top',

           // Styling
           // Before Title Styling
           'before_title_color' => '',
           'before_background' => '',

           // After Title Styling
           'after_title_color' => '',
           'after_background' => '',

           // Handler Styling
           'handler_color' => '',
           'handler_border' => '',
           'handler_border_width' => '',
           'handler_border_style' => '',
           'handler_border_radius' => '',
           'handler_border_color' => '',
           'handler_background' => '',
           'handler_width' => '',
           'handler_height' => '',

           // Image Before Styling
           'image_before_background' => '',

           // Image After Styling
           'image_after_background' => '',
           'image_after_border' => '',
           'image_after_border_width' => '',
           'image_after_border_style' => '',
           'image_after_border_color' => '',

           // Typography
           // Before Title Typography
           'before_title_use_google_font' => '',
           'before_title_google_font' => '',
           'before_title_typography' => '',

           // After Title Typography
           'after_title_use_google_font' => '',
           'after_title_google_font' => '',
           'after_title_typography' => '',

            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));


        // wrapper class
        $wrapper_class_arr = array();
        
        $unique_class = uniqid('htmegavc_image_comparison_');
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_image_comparison_wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        // add wrapper class
        $wrapper_class_arr[] =  'htmegavc-imagecomparison htmegavc-label-pos-'.$imagecomparison_laben_pos;

        // join all wrapper class
        $wrapper_class = implode(' ', $wrapper_class_arr);

        // Styling
        // Before Title Styling
        $before_title_inline_style = "color:$before_title_color;";
        $before_title_inline_style .= "background-color:$before_background;";

        // Before Title Styling
        $after_title_inline_style = "color:$after_title_color;";
        $after_title_inline_style .= "background-color:$after_background;";

        // Handler Styling
        $handler_inline_style = "color:$handler_color;";
        $handler_inline_style .= "background-color:$handler_background;";
        $handler_inline_style .= "border-width:$handler_border_width;";
        $handler_inline_style .= "border-style:$handler_border_style;";
        $handler_inline_style .= "border-radius:$handler_border_radius;";
        $handler_inline_style .= "border-color:$handler_border_color;";
        $handler_inline_style .= "width:$handler_width;";
        $handler_inline_style .= "height:$handler_height;";

        // Image:Before/after Styling
        $image_before_inline_style = "background-color:$image_before_background;;";

        // Image separator border styling
        $image_separator_inline_style = "border-right-width:$image_after_border_width;";
        $image_separator_inline_style .= "border-style:$image_after_border_style;";
        $image_separator_inline_style .= "border-color:$image_after_border_color;";

        // Typography
        // Before Title Typography
        $google_font_data1 = htmegavc_build_google_font_data($before_title_google_font);
        if ( 'true' == $before_title_use_google_font && isset( $google_font_data1['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data1['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data1['values']['font_family'] );
        }

        // concate google font properties and other properties
        $before_title_google_font = htmegavc_build_google_font_style($google_font_data1);
        $before_title_inline_style .= htmegavc_combine_font_container($before_title_typography.';'.$before_title_google_font);

        // After Title Typography
        $google_font_data2 = htmegavc_build_google_font_data($after_title_google_font);
        if ( 'true' == $after_title_use_google_font && isset( $google_font_data2['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data2['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data2['values']['font_family'] );
        }

        // concate google font properties and other properties
        $after_title_google_font = htmegavc_build_google_font_style($google_font_data2);
        $after_title_inline_style .= htmegavc_combine_font_container($after_title_typography.';'.$after_title_google_font);


        $output = '';
        $output .= '<style>';
        $output .= "
			.$unique_class .beer-slider[data-beer-label]::after{ $before_title_inline_style }
			.$unique_class .beer-reveal[data-beer-label]::after{ $after_title_inline_style }
			.$unique_class .beer-handle{ $handler_inline_style }
			.$unique_class .beer-slider::before{ $image_before_inline_style }
			.$unique_class .beer-reveal{ $image_separator_inline_style }
        ";
        $output .= '</style>';

        ob_start();
        ?>

        <div class="<?php echo esc_attr( $wrapper_class ); ?>" >

            <div class="beer-slider htmegavc-ber-slider-<?php echo esc_attr($style); ?>" data-start="<?php echo esc_attr($start_amount); ?>" data-beer-label="<?php echo esc_attr($before_title); ?>" >
                <?php
                	$size = $image_size;
                	if(strpos($size, 'x')){
                	    $size = array();
                	    $size = array($image_size);
                	}
                    echo wp_get_attachment_image($before_image,$size);
                ?>
                <div class="beer-reveal" data-beer-label="<?php echo esc_attr($after_title); ?>">
                    <?php
                        echo wp_get_attachment_image($after_image,$size);
                    ?>
                </div>
            </div>

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
            "name" => __("HT Image Comparison", 'htmegavc'),
            "description" => __("Add Image Comparison to your page", 'htmegavc'),
            "base" => "htmegavc_image_comparison",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegavc_image_comparison_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('HT Mega Addons', 'htmegavc'),
            "params" => array(

            	// cotnent
            	array(
            	  "param_name" => "style",
            	  "heading" => __("Style", 'htmegavc'),
            	  "type" => "dropdown",
            	  "default_set" => '1',
            	  'value' => [
            	      __( 'Style One', 'htmegavc' )  =>  '1',
            	      __( 'Style Two', 'htmegavc' )  =>  '2',
            	      __( 'Style Three', 'htmegavc' )  =>  '3',
            	      __( 'Style Four', 'htmegavc' )  =>  '4',
            	      __( 'Style Five', 'htmegavc' )  =>  '5',
            	  ],
            	),
            	array(
            	    'param_name' => 'before_image',
            	    'heading' => __( 'Before Image', 'htmegavc' ),
            	    'type' => 'attach_image',
            	    'edit_field_class' => 'vc_column vc_col-sm-6',
            	),
            	array(
            	    'param_name' => 'after_image',
            	    'heading' => __( 'After Image', 'htmegavc' ),
            	    'type' => 'attach_image',
            	    'edit_field_class' => 'vc_column vc_col-sm-6',
            	),
            	array(
            	    'param_name' => 'image_size',
            	    'heading' => __( 'Image Size', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'before_title',
            	    'heading' => __( 'Before Title', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'value' => __( 'Before', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'after_title',
            	    'heading' => __( 'After Title', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'value' => __( 'After', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'start_amount',
            	    'heading' => __( 'Before Start Amount', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'value' => 25,
            	    'description' => __( 'Example: 50', 'htmegavc' ),
            	),
            	array(
            	  "param_name" => "imagecomparison_laben_pos",
            	  "heading" => __("Label Position", 'htmegavc'),
            	  "type" => "dropdown",
            	  "default_set" => 'top',
            	  'value' => [
            	      __( 'Top', 'htmegavc' )	=> 'top',
            	      __( 'Center', 'htmegavc' )	=> 'center',
            	      __( 'Bottom', 'htmegavc' )	=> 'bottom',
            	  ],
            	),


            	// Styling
            	// Before Title Styling
            	array(
            	    "param_name" => "custom_heading",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Before Title Styling","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'before_title_color',
            	    'heading' => __( 'Text Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'before_background',
            	    'heading' => __( 'BG Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),

            	// After Title Styling
            	array(
            	    "param_name" => "custom_heading",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("After Title Styling","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'after_title_color',
            	    'heading' => __( 'Text Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'after_background',
            	    'heading' => __( 'BG Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),

            	// Handler Styling
            	array(
            	    "param_name" => "custom_heading",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Handler Styling","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'handler_color',
            	    'heading' => __( 'Arrow Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'handler_background',
            	    'heading' => __( 'BG Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'handler_width',
            	    'heading' => __( 'Handler Width', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Example: 100px, which stand for width:100px;', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'handler_height',
            	    'heading' => __( 'Handler Height', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Example: 100px, which stand for height:100px;', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'handler_border_width',
            	    'heading' => __( 'Handler Border width', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS Border width. Example: 2px, which stand for border-width:2px;', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'handler_border_style',
            	    'heading' => __( 'Handler Border style', 'htmegavc' ),
            	    'type' => 'dropdown',
            	    "default_set" => 'none',
            	    'value' => [
            	        __( 'None', 'htmegavc' )  =>  'none',
            	        __( 'Solid', 'htmegavc' )  =>  'solid',
            	        __( 'Double', 'htmegavc' )  =>  'double',
            	        __( 'Dotted', 'htmegavc' )  =>  'dotted',
            	        __( 'Dashed', 'htmegavc' )  =>  'dashed',
            	        __( 'Groove', 'htmegavc' )  =>  'groove',
            	    ],
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'handler_border_radius',
            	    'heading' => __( 'Handler Border Radius', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS Border Radius. Example: 5px, which stand for border-radius:5px;', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'handler_border_color',
            	    'heading' => __( 'Handler Border color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'description' => __( 'The CSS Border', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),


            	// Image Before/After Styling
            	array(
            	    "param_name" => "custom_heading",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Image Before/After Styling","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'image_before_background',
            	    'heading' => __( 'Image Before BG Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),


            	// Image separator Styling
            	array(
            	    "param_name" => "custom_heading",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Image separator Styling","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'image_after_border_width',
            	    'heading' => __( 'Separator Border width', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS Border width. Example: 2px, which stand for border-width:2px;', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'image_after_border_style',
            	    'heading' => __( 'Separator Border style', 'htmegavc' ),
            	    'type' => 'dropdown',
            	    "default_set" => 'none',
            	    'value' => [
            	        __( 'None', 'htmegavc' )  =>  'none',
            	        __( 'Solid', 'htmegavc' )  =>  'solid',
            	        __( 'Double', 'htmegavc' )  =>  'double',
            	        __( 'Dotted', 'htmegavc' )  =>  'dotted',
            	        __( 'Dashed', 'htmegavc' )  =>  'dashed',
            	        __( 'Groove', 'htmegavc' )  =>  'groove',
            	    ],
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'image_after_border_color',
            	    'heading' => __( 'Separator Border Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'description' => __( 'The CSS Border', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),


            	// Typography
            	// Before Title Typography
            	array(
            	    "param_name" => "package_typograpy",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Before Title Typography","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'checkbox',
            	  'heading' => __( 'Use google Font?', 'htmegavc' ),
            	  'param_name' => 'before_title_use_google_font',
            	  'description' => __( 'Use font family from google font.', 'htmegavc' ),
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'google_fonts',
            	  'param_name' => 'before_title_google_font',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
            	      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
            	    ),
            	  ),
            	  'dependency' =>[
            	      'element' => 'before_title_use_google_font',
            	      'value' => array( 'true' ),
            	  ],
            	),
            	array(
            	  'param_name' => 'before_title_typography',
            	  'type' => 'font_container',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_size',
            	      'line_height',
            	      'text-align',
            	      'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
            	      'color_description' => __( 'Select Heading Color.', 'htmegavc' ),
            	    ),
            	  ),
            	),


            	// After Title Typography
            	array(
            	    "param_name" => "package_typograpy",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Description Typography","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'checkbox',
            	  'heading' => __( 'Use google font?', 'htmegavc' ),
            	  'param_name' => 'after_title_use_google_font',
            	  'description' => __( 'Use font family from google font.', 'htmegavc' ),
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'google_fonts',
            	  'param_name' => 'after_title_google_font',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
            	      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
            	    ),
            	  ),
            	  'dependency' =>[
            	      'element' => 'after_title_use_google_font',
            	      'value' => array( 'true' ),
            	  ],
            	),
            	array(
            	  'param_name' => 'after_title_typography',
            	  'type' => 'font_container',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_size',
            	      'line_height',
            	      'text-align',
            	      'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
            	      'color_description' => __( 'Select heading color.', 'htmegavc' ),
            	    ),
            	  ),
            	),


                // extra class
                array(
                    'param_name' => 'custom_class',
                    'heading' => __( 'Extra class name', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'Style this element differently - add a class name and refer to it in custom CSS.', 'htmegavc' ),
                ),
                array(
                  "param_name" => "wrapper_css",
                  "heading" => __( "Wrapper Styling", "htmevavc" ),
                  "type" => "css_editor",
                  'group'  => __( 'Wrapper Styling', 'htmegavc' ),
              ),
            )
        ) );
    }

}

// Finally initialize code
new Htmegavc_Image_Comparison();