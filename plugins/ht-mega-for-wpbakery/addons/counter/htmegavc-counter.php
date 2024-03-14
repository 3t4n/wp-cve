<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Counter extends WPBakeryShortCode{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_counter', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
    	wp_register_script( 'waypoints', HTMEGAVC_LIBS_URI . '/waypoints/waypoints.min.js', '', '', '');
        wp_enqueue_script( 'waypoints' );

        wp_register_script( 'jquery-counterup', HTMEGAVC_LIBS_URI . '/counterup/jquery.counterup.js', '', '', '');
    	wp_enqueue_script( 'jquery-counterup' );

    	wp_register_script( 'htmegavc-counterup-active', plugins_url('js/counterup-active.js', __FILE__), '', '', true);
    	wp_enqueue_script( 'htmegavc-counterup-active' );

    	wp_register_style( 'htmegavc-counter', plugins_url('css/counter.css', __FILE__));
    	wp_enqueue_style( 'htmegavc-counter' );
    }


    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
            // Content
            'counter_layout_style'		=> 	'1',
            'counter_icon_type'		=> 	'',
            	'counter_icon'		=> 	'',
            	'counter_image'		=> 	'',
            	'counter_image_ize'		=> 	'',
            'counter_title'		=> 	__('Happy Clients', 'htmevavc'),
            'terget_number'		=> 	'120',
            'counter_number_prefix'		=> 	'',
            'counter_number_suffix'		=> 	'',

            // Styling
            'counter_area_background_overlay'		=> 	'',
            'counter_area_align'		=> 	'',

            // Number Styling
            'counter_number_color'		=> 	'',

            // Title Styling
            'counter_title_color'		=> 	'',
            'counter_title_after_border_color'		=> 	'',

            // Icon Styling
            'counter_icon_color'		=> 	'',
            'counter_icon_size'		=> 	'',

            // Prefix And Suffix Styling
            'counter_prefix_color'		=> 	'',
            'counter_suffix_color'		=> 	'',

            // Number Typography
            'counter_number_use_google_font'		=> 	'',
            'counter_number_google_font'		=> 	'',
            'counter_number_typogrphy'		=> 	'',

            // Title Typography
            'counter_title_use_google_font'		=> 	'',
            'counter_title_google_font'		=> 	'',
            'counter_title_typography'		=> 	'',

            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));

        // wrapper class
        $wrapper_class_arr = array();
        
        $unique_class = uniqid('htmegavc_counter_');
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_counter_wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        // add wrapper class
        $wrapper_class_arr[] =  'htmegavc-counter';
        $wrapper_class_arr[] .=  'htmegavc-counter-area htmegavc-counter-style-'.$counter_layout_style;
        $wrapper_class_arr[] .=  'text-'.$counter_area_align;

        // join all wrapper class
        $wrapper_class = implode(' ', $wrapper_class_arr);

        // Styling
        // Wrapper Styling
        $wrapper_inline_style = "background-color:$counter_area_background_overlay;";

        // Number Styling
        $number_inline_style = "color:$counter_number_color;";

        // Title Styling
        $title_inline_style = "color:$counter_title_color";
        $title_after_inline_style = "background-color:$counter_title_after_border_color;";

        // Icon Styling
        $icon_inline_style = '';
        if($counter_icon_type == 'icon'){
        	$icon_inline_style .= "color:$counter_icon_color;";
        	$icon_inline_style .= "font-size:$counter_icon_size;";
        }

        // Prefix and Suffix Styling
        $prefix_inline_style = "color:$counter_prefix_color;";
        $suffix_inline_style = "color:$counter_suffix_color;";
        


        // Typography
        // Number Typography
        $google_font_data1 = htmegavc_build_google_font_data($counter_number_google_font);
        if ( 'true' == $counter_number_use_google_font && isset( $google_font_data1['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data1['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data1['values']['font_family'] );
        }

        // concate google font properties and other properties
        $counter_number_google_font = htmegavc_build_google_font_style($google_font_data1);
        $number_inline_style .= htmegavc_combine_font_container($counter_number_typogrphy.';'.$counter_number_google_font);

        // Number Typography
        $google_font_data2 = htmegavc_build_google_font_data($counter_title_google_font);
        if ( 'true' == $counter_title_use_google_font && isset( $google_font_data2['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data2['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data2['values']['font_family'] );
        }

        // concate google font properties and other properties
        $counter_title_google_font = htmegavc_build_google_font_style($google_font_data2);
        $title_inline_style .= htmegavc_combine_font_container($counter_title_typography.';'.$counter_title_google_font);


        $output = '';
        $output .= '<style>';
        $output .= "
			.$unique_class.htmegavc-counter{ $wrapper_inline_style }
			.$unique_class.htmegavc-counter .htmegavc-counter-number{ $number_inline_style }
			.$unique_class.htmegavc-counter .htmegavc-counter-content h2{ $title_inline_style }

			.$unique_class.htmegavc-counter .htmegavc-counter-icon i{ $icon_inline_style }

			.$unique_class.htmegavc-counter .htmegavc-counter-content h2::before{ $title_after_inline_style }

			.$unique_class.htmegavc-counter .htmegavc-prefix{ $prefix_inline_style }
			.$unique_class.htmegavc-counter .htmegavc-suffix{ $suffix_inline_style }
        ";
        $output .= '</style>';

        ob_start();

        // image size
        if(strpos($counter_image_ize, 'x')){
            $size_arr = explode('x', $counter_image_ize);
            $counter_image_ize = array($size_arr[0],$size_arr[1]);
        }

        $prefix = $suffix = '';
        if( $counter_number_prefix ){
            $prefix = '<span class="htmegavc-prefix">'.$counter_number_prefix.'</span>';
        }
        if( $counter_number_suffix){ 
            $suffix = '<span class="htmegavc-suffix">'.$counter_number_suffix.'</span>';
        }
        ?>

        <div class="<?php echo esc_attr( $wrapper_class ); ?>" >
		
			<?php
			    if( $counter_layout_style == 6 ){
			        echo '<div class="htmegavc-counter-icon">';
			            if( $counter_icon ){
			                echo '<span><i class=" '.esc_attr( $counter_icon ).' "></i></span>';
			            }
			            if( $counter_image ){
			                echo wp_get_attachment_image($counter_image, $counter_image_ize);
			            }
			            if( $terget_number ){
			                echo $prefix.'<span class="htmegavc-counter-number">'.esc_html( $terget_number ).'</span>'.$suffix;
			            }
			        echo '</div>';
			    }else{
			        if( $counter_icon){
			            echo '<div class="htmegavc-counter-icon"><span><i class=" '.esc_attr( $counter_icon ).' "></i></span></div>';
			        }
			        if( $counter_image ){
			            echo '<div class="htmegavc-counter-img">'. wp_get_attachment_image($counter_image, $counter_image_ize) .'</div>';
			        }
			    }                    
			?>
			<div class="htmegavc-counter-content">
			    <?php
			        if($counter_layout_style == 4 ){
			            if(  $counter_title ){
			                echo '<h2 class="htmegavc-counter-title">'.esc_html($counter_title,'htmegavc-addons').'</h2>';
			            }
			            if( $terget_number ){
			                echo $prefix.'<span class="htmegavc-counter-number">'.esc_html( $terget_number ).'</span>'.$suffix;
			            }
			        }elseif($counter_layout_style == 6 ){
			            if( $counter_title ){
			                echo '<h2 class="htmegavc-counter-title">'.esc_html($counter_title).'</h2>';
			            }
			        }else{
			            if( $terget_number ){
			                echo $prefix.'<span class="htmegavc-counter-number">'.esc_html( $terget_number ).'</span>'.$suffix;
			            }
			            if( $counter_title ){
			                echo '<h2 class="htmegavc-counter-title">'.esc_html($counter_title).'</h2>';
			            }
			        }
			    ?>
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
            "name" => __("HT Counter", 'htmegavc'),
            "description" => __("Add Counter to your page", 'htmegavc'),
            "base" => "htmegavc_counter",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegavc_counter_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('HT Mega Addons', 'htmegavc'),
            "params" => array(

            	// cotnent
            	array(
            	  "param_name" => "counter_layout_style",
            	  "heading" => __("Style", 'htmegavc'),
            	  "type" => "dropdown",
            	  'value' => [
            	      __( 'Style One', 'htmegavc' )  =>  '1',
            	      __( 'Style Two', 'htmegavc' )  =>  '2',
            	      __( 'Style Three', 'htmegavc' )  =>  '3',
            	      __( 'Style Four', 'htmegavc' )  =>  '4',
            	      __( 'Style Five', 'htmegavc' )  =>  '5',
            	      __( 'Style Six', 'htmegavc' )  =>  '6',
            	  ],
            	),
            	array(
            	  "param_name" => "counter_icon_type",
            	  "heading" => __("Icon Type", 'htmegavc'),
            	  "type" => "dropdown",
            	  'value' => [
            	      __( 'Icon', 'htmegavc' )  =>  'icon',
            	      __( 'Image', 'htmegavc' )  =>  'image',
            	  ],
            	),
            	array(
            	    'param_name' => 'counter_icon',
            	    'heading' => __( 'Counter Icon', 'htmegavc' ),
            	    'type' => 'iconpicker',
            	    'value' => 'fa fa-team',
            	    'dependency' =>[
            	        'element' => 'counter_icon_type',
            	        'value' => array( 'icon' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'counter_image',
            	    'heading' => __( 'Counter Image Icon', 'htmegavc' ),
            	    'type' => 'attach_image',
            	    'dependency' =>[
            	        'element' => 'counter_icon_type',
            	        'value' => array( 'image' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'counter_image_ize',
            	    'heading' => __( 'Image Size', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'counter_icon_type',
            	        'value' => array( 'image' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'counter_title',
            	    'heading' => __( 'Counter Title', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'value' => __( 'Happy Clients', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'terget_number',
            	    'heading' => __( 'Target Number', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'value' => '150',
            	    'description' => __( 'Example: 150', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'counter_number_prefix',
            	    'heading' => __( 'Counter Number Prefix', 'htmegavc' ),
            	    'type' => 'textfield',
            	),
            	array(
            	    'param_name' => 'counter_number_suffix',
            	    'heading' => __( 'Counter Number Suffix', 'htmegavc' ),
            	    'type' => 'textfield',
            	),


            	// Styling
            	array(
            	    'param_name' => 'counter_area_background_overlay',
            	    'heading' => __( 'Counter Box Background Overlay', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	  "param_name" => "counter_area_align",
            	  "heading" => __("Content Align", 'htmegavc'),
            	  "type" => "dropdown",
            	  'value' => [
            	      __( 'Center', 'htmegavc' )  =>  'center',
            	      __( 'Left', 'htmegavc' )  =>  'left',
            	      __( 'Right', 'htmegavc' )  =>  'right',
            	      __( 'Justify', 'htmegavc' )  =>  'justify',
            	  ],
            	),

            	// Number Styling
            	array(
            	    'param_name' => 'counter_number_color',
            	    'heading' => __( 'Counter Number Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),

            	// Title Styling
            	array(
            	    'param_name' => 'counter_title_color',
            	    'heading' => __( 'Counter Title Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'counter_title_after_border_color',
            	    'heading' => __( 'Count Title After Border Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),

            	// Icon Styling
            	array(
            	    'param_name' => 'counter_icon_color',
            	    'heading' => __( 'Counter Icon Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'counter_icon_type',
            	        'value' => array( 'icon' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'counter_icon_size',
            	    'heading' => __( 'Counter Icon Size', 'htmegavc' ),
            	    'type' => 'textfield',
            	  	'description' => __( 'Example: 20px', 'htmegavc' ),
            	  	'group'  => __( 'Styling', 'htmegavc' ),
            	  	'dependency' =>[
            	  	    'element' => 'counter_icon_type',
            	  	    'value' => array( 'icon' ),
            	  	],
            	),

            	// Prefix And Suffix Styling
            	array(
            	    'param_name' => 'counter_prefix_color',
            	    'heading' => __( 'Counter Number Prefix Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'counter_suffix_color',
            	    'heading' => __( 'Counter Number Suffix Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),


            	// Typography
            	// Title Typography
            	array(
            	    "param_name" => "package_typograpy",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Title Typography","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'checkbox',
            	  'heading' => __( 'Use google Font?', 'htmegavc' ),
            	  'param_name' => 'counter_number_use_google_font',
            	  'description' => __( 'Use font family from google font.', 'htmegavc' ),
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'google_fonts',
            	  'param_name' => 'counter_number_google_font',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
            	      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
            	    ),
            	  ),
            	  'dependency' =>[
            	      'element' => 'counter_number_use_google_font',
            	      'value' => array( 'true' ),
            	  ],
            	),
            	array(
            	  'param_name' => 'counter_number_typogrphy',
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

            	// Number Typography
            	array(
            	    "param_name" => "package_typograpy",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Number Typography","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'checkbox',
            	  'heading' => __( 'Use google Font?', 'htmegavc' ),
            	  'param_name' => 'counter_title_use_google_font',
            	  'description' => __( 'Use font family from google font.', 'htmegavc' ),
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'google_fonts',
            	  'param_name' => 'counter_title_google_font',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
            	      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
            	    ),
            	  ),
            	  'dependency' =>[
            	      'element' => 'counter_title_use_google_font',
            	      'value' => array( 'true' ),
            	  ],
            	),
            	array(
            	  'param_name' => 'counter_title_typography',
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
new Htmegavc_Counter();