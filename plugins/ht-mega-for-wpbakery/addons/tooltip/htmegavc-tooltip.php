<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Tooltip extends WPBakeryShortCode{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_tooltip', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
    	wp_register_style( 'htmegavc-tooltip', plugins_url('css/tooltip.css', __FILE__));
    	wp_enqueue_style( 'htmegavc-tooltip' );

    	wp_register_script( 'htmegavc-tooltip-active', plugins_url('js/tooltip-active.js', __FILE__), '', '', true);
    	wp_enqueue_script( 'htmegavc-tooltip-active' );
    }


    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
           // content
           'tooltip_button_txt' => __('Button Tooltip', 'htmegavc'),
           'show_link' => '',
           	'button_link' => '',
           'tooltip_text' => __('Tooltip Text', 'htmegavc'),
           'tooltip_dir' => 'top',

           // customization

           // Tooltip Styling
           'tooltip_space' => '',
           'hover_tooltip_content_color' => '',
           'hover_tooltip_content_background' => '',
           'hover_tooltip_content_padding' => '',
           'hover_tooltip_content_border_radius' => '',

           // Button Styling
           'button_alignment' => 'center',
           'button_color' => '',
           'button_background' => '',
           'button_border_width' => '',
           'button_border_style' => '',
           'button_border_radius' => '',
           'button_border_color' => '',
           'button_margin' => '',
           'button_padding' => '',

           // Button Hover Styling
           'button_hover_color' => '',
           'button_hover_background' => '',
           'button_hover_border' => '',

           // Typography
           // Button Typography
           'button_use_google_font' => '',
           'button_google_font' => '',
           'button_typography' => '',

           // Tooltip content Typography
           'hover_tooltip_content_use_google_font' => '',
           'hover_tooltip_content_google_font' => '',
           'hover_tooltip_content_typography' => '',


            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));


        // wrapper class
        $wrapper_class_arr = array();
        
        $unique_class = uniqid('htmegavc_tooltip_');
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_tooltip_wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        // add wrapper class
        $wrapper_class_arr[] =  'htmegavc-tooltip';
        $wrapper_class_arr[] =  'text-'. $button_alignment;

        // join all wrapper class
        $wrapper_class = implode(' ', $wrapper_class_arr);

        // typography and google font
        $google_font_data1 = htmegavc_build_google_font_data($button_google_font);
        if ( 'true' == $button_google_font && isset( $google_font_data1['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data1['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data1['values']['font_family'] );
        }

        // concate google font properties and other properties
        $button_google_font = htmegavc_build_google_font_style($google_font_data1);
        $wrapper_span_inline_style = htmegavc_combine_font_container($button_typography.';'.$button_google_font);

        $wrapper_span_inline_style .= "border-width:$button_border_width;$wrapper_span_inline_style";
        $wrapper_span_inline_style .= "border-style:$button_border_style;";
        $wrapper_span_inline_style .= "border-radius:$button_border_radius;";
        $wrapper_span_inline_style .= "border-color:$button_border_color;";
        $wrapper_span_inline_style .= "margin:$button_margin;";
        $wrapper_span_inline_style .= "padding:$button_padding;";

        $output = '';
        $output .= '<style>';
        $output .= "
			.$unique_class .bs-tooltip-auto[x-placement^=top]{ top: -$tooltip_space !important}
			.$unique_class .bs-tooltip-top{ top: -$tooltip_space !important}
			.$unique_class .bs-tooltip-auto[x-placement^=bottom]{ top: $tooltip_space !important}
			.$unique_class .bs-tooltip-bottom{ top: $tooltip_space !important}
			.$unique_class .bs-tooltip-auto[x-placement^=right]{ left: $tooltip_space !important}
			.$unique_class .bs-tooltip-right{ left: $tooltip_space !important}
			.$unique_class .bs-tooltip-auto[x-placement^=left]{ left: $tooltip_space !important}
			.$unique_class .bs-tooltip-left{left: -$tooltip_space !important}

			.$unique_class .tooltip-inner{background-color:$hover_tooltip_content_background;color:$hover_tooltip_content_color;padding:$hover_tooltip_content_padding;border-radius:$hover_tooltip_content_border_radius;}


			.$unique_class .bs-tooltip-auto[x-placement^=top] .arrow::before{ border-top-color: $hover_tooltip_content_background !important}
			.$unique_class .bs-tooltip-top .arrow::before{ border-top-color: $hover_tooltip_content_background !important}
			.$unique_class .bs-tooltip-auto[x-placement^=bottom] .arrow::before{ border-bottom-color: $hover_tooltip_content_background !important}
			.$unique_class .bs-tooltip-bottom .arrow::before{ border-bottom-color: $hover_tooltip_content_background !important}
			.$unique_class .bs-tooltip-auto[x-placement^=left] .arrow::before{ border-left-color: $hover_tooltip_content_background !important}
			.$unique_class .bs-tooltip-left .arrow::before{ border-left-color: $hover_tooltip_content_background !important}
			.$unique_class .bs-tooltip-auto[x-placement^=right] .arrow::before{ border-right-color: $hover_tooltip_content_background !important}
			.$unique_class .bs-tooltip-right .arrow::before{ border-right-color: $hover_tooltip_content_background !important}

			.$unique_class span,.$unique_class span a{background-color:$button_background;color:$button_color;}
			.$unique_class span:hover,.$unique_class span:hover a{background-color:$button_hover_background;color:$button_hover_color;border-color:$button_hover_border !important;}
            ";
        $output .= '</style>';

        ob_start(); ?>

         <div class="<?php echo esc_attr($wrapper_class); ?>">
             <?php

             	// button generate
             	$link_arr = explode('|', $button_link);
             	if(count($link_arr) > 1){
             	  $link_url  =  urldecode(str_replace('url:', '', $link_arr[0]));
             	  $link_target  =  urldecode(str_replace('target:', '', $link_arr[2]));

             	  if($link_url){
             	    $tooltip_button_txt = sprintf( '<a href="%1$s" target="%3$s">%2$s</a>', $link_url, $tooltip_button_txt,$link_target );
             	  }
             	}

             	echo sprintf('<span style="%5$s" data-toggle="tooltip" data-container=".%4$s" data-placement="%1$s" title="%2$s">%3$s</span>', $tooltip_dir, $tooltip_text, $tooltip_button_txt, $unique_class, $wrapper_span_inline_style );
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
            "name" => __("HT Tooltip", 'htmegavc'),
            "description" => __("Add Tooltip to your page", 'htmegavc'),
            "base" => "htmegavc_tooltip",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegvc_tooltip_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('HT Mega Addons', 'htmegavc'),
            "params" => array(

            	// cotnent
            	array(
            	    'param_name' => 'tooltip_button_txt',
            	    'heading' => __( 'Button Text', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'value' => 'Button Tooltip',
            	),
            	array(
            	  "param_name" => "show_link",
            	  "heading" => __("Show Link", 'htmegavc'),
            	  "type" => "dropdown",
            	  "default_set" => '0',
            	  'value' => [
            	      __( 'Hide', 'htmegavc' )  =>  '0',
            	      __( 'Show', 'htmegavc' )  =>  'yes',
            	  ],
            	),
            	array(
            	    'param_name' => 'button_link',
            	    'heading' => __( 'Link ', 'htmegavc' ),
            	    'type' => 'vc_link',
            	    'value' => 'url:#',
            	    'dependency' =>[
            	        'element' => 'show_link',
            	        'value' => array( 'yes' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'tooltip_text',
            	    'heading' => __( 'Tooltip Text', 'htmegavc' ),
            	    'type' => 'textarea',
            	    'value' => esc_html__( 'Tooltip content', 'htmevavc' ),
            	),
            	array(
            	    'param_name' => 'tooltip_dir',
            	    'heading' => __( 'Direction', 'htmegavc' ),
            	    "type" => "dropdown",
            	    "default_set" => 'top',
            	    'value' => [
            	        __( 'Top', 'htmegavc' )  =>  'top',
            	        __( 'Left', 'htmegavc' )  =>  'left',
            	        __( 'Right', 'htmegavc' )  =>  'right',
            	        __( 'Bottom', 'htmegavc' )  =>  'bottom',
            	    ],
            	),

                // customization
                // Tooltip Styling
                array(
                    "param_name" => "custom_heading",
                    "type" => "htmegavc_param_heading",
                    "text" => __("Tooltip Styling","htmegavc"),
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'tooltip_space',
                    'heading' => __( 'Space With Button', 'htmegavc' ),
                    'type' => 'textfield',
                    'value' => '10px',
                    'description' => __( 'Eg: 10px', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'hover_tooltip_content_color',
                    'heading' => __( 'Tooltip Content color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'hover_tooltip_content_background',
                    'heading' => __( 'Tooltip Content BG color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'hover_tooltip_content_padding',
                    'heading' => __( 'Tooltip Content Padding', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS padding of Tooltip Content. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'hover_tooltip_content_border_radius',
                    'heading' => __( 'Tooltip Content Border Radius', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS Border Radius of Tooltip Content. Example: 5px, which stand for border-radius:5px;', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),

                // Button Styling
                array(
                    "param_name" => "custom_heading",
                    "type" => "htmegavc_param_heading",
                    "text" => __("Button Styling","htmegavc"),
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'button_alignment',
                    'heading' => __( 'Button Alignment', 'htmegavc' ),
                    'type' => 'dropdown',
                    "default_set" => 'center',
                    'value' => [
                        __( 'Left', 'htmegavc' )  =>  'left',
                        __( 'Center', 'htmegavc' )  =>  'center',
                        __( 'Right', 'htmegavc' )  =>  'right',
                        __( 'Justify', 'htmegavc' )  =>  'justify',
                    ],
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'button_color',
                    'heading' => __( 'Text Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'button_background',
                    'heading' => __( 'Button BG Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'button_border_width',
                    'heading' => __( 'Button Border width', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS Border width of blockquote. Example: 2px, which stand for border-width:2px;', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'button_border_style',
                    'heading' => __( 'Button Border style', 'htmegavc' ),
                    'type' => 'dropdown',
                    "default_set" => '1',
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
                    'param_name' => 'button_border_radius',
                    'heading' => __( 'Button Border Radius', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS Border Radius of button. Example: 5px, which stand for border-radius:5px;', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'button_border_color',
                    'heading' => __( 'Button Border color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'description' => __( 'The CSS Border color of blockquote.', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'button_margin',
                    'heading' => __( 'Button Margin', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS margin of Button. Example: 18px 0, which stand for margin-top and margin-bottom is 18px', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'button_padding',
                    'heading' => __( 'Button Padding', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS padding of Button. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),

                // Button Hover Styling
                array(
                    "param_name" => "custom_heading",
                    "type" => "htmegavc_param_heading",
                    "text" => __("Button Hover Styling","htmegavc"),
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'button_hover_color',
                    'heading' => __( 'Button Hover Text Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'button_hover_background',
                    'heading' => __( 'Button Hover BG Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'button_hover_border',
                    'heading' => __( 'Button Hover Border color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'description' => __( 'The CSS Border color of Button.', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),

                // Typography
                // Button Typography
                array(
                    "param_name" => "package_typograpy",
                    "type" => "htmegavc_param_heading",
                    "text" => __("Button Typography","htmegavc"),
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Typography', 'htmegavc' ),
                ),
                array(
                  'type' => 'checkbox',
                  'heading' => __( 'Use google font?', 'htmegavc' ),
                  'param_name' => 'button_use_google_font',
                  'description' => __( 'Use font family from google font.', 'htmegavc' ),
                  'group'  => __( 'Typography', 'htmegavc' ),
                ),
                array(
                  'type' => 'google_fonts',
                  'param_name' => 'button_google_font',
                  'group'  => __( 'Typography', 'htmegavc' ),
                  'settings' => array(
                    'fields' => array(
                      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
                      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
                    ),
                  ),
                  'dependency' =>[
                      'element' => 'button_use_google_font',
                      'value' => array( 'true' ),
                  ],
                ),
                array(
                  'param_name' => 'button_typography',
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
                // Tooltip content Typography
                array(
                    "param_name" => "package_typograpy",
                    "type" => "htmegavc_param_heading",
                    "text" => __("Tooltip content Typography","htmegavc"),
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Typography', 'htmegavc' ),
                ),
                array(
                  'type' => 'checkbox',
                  'heading' => __( 'Use google font?', 'htmegavc' ),
                  'param_name' => 'hover_tooltip_content_use_google_font',
                  'description' => __( 'Use font family from google font.', 'htmegavc' ),
                  'group'  => __( 'Typography', 'htmegavc' ),
                ),
                array(
                  'type' => 'google_fonts',
                  'param_name' => 'hover_tooltip_content_google_font',
                  'group'  => __( 'Typography', 'htmegavc' ),
                  'settings' => array(
                    'fields' => array(
                      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
                      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
                    ),
                  ),
                  'dependency' =>[
                      'element' => 'hover_tooltip_content_use_google_font',
                      'value' => array( 'true' ),
                  ],
                ),
                array(
                  'param_name' => 'hover_tooltip_content_typography',
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
new Htmegavc_Tooltip();