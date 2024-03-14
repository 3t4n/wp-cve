<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Popover extends WPBakeryShortCode{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_popover', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
    	wp_register_style( 'htmegavc-popover', plugins_url('css/popover.css', __FILE__));
    	wp_enqueue_style( 'htmegavc-popover' );

    	wp_register_script( 'htmegavc-popover-active', plugins_url('js/popover-active.js', __FILE__), '', '', true);
    	wp_enqueue_script( 'htmegavc-popover-active' );
    }


    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
           // content
           'popover_button_txt' => __('Popover', 'htmegavc'),
           'show_link' => '',
           	'button_link' => '',
           'popover_text' => __('Popover Text', 'htmegavc'),
           'popover_header_text' => __('Popover Header Here', 'htmegavc'),
           'popover_dir' => 'top',
           'show_popover' => 'no',
           'popover_space' => '',

           // customization

           // Popover Styling
           // Popover Area
           'hover_popover_area_width' => '',
           'hover_popover_area_background' => '',
           'hover_popover_area_box_shadow' => '',
           'hover_popover_area_border_width' => '',
           'hover_popover_area_border_style' => '',
           'hover_popover_area_border_color' => '',
           'hover_popover_area_border_radius' => '',

           // Popover Header
           'hover_popover_header_color' => '',
           'hover_popover_header_background' => '',
           'hover_popover_header_padding' => '',

           // Popover Content
           'hover_popover_content_color' => '',
           'hover_popover_content_background' => '',
           'hover_popover_content_padding' => '',

           // Button Styling
           'button_alignment' => 'left',
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

           // Popover header Typography
           'hover_popover_header_use_google_font' => '',
           'hover_popover_header_google_font' => '',
           'hover_popover_header_typography' => '',

           // Popover content Typography
           'hover_popover_content_use_google_font' => '',
           'hover_popover_content_google_font' => '',
           'hover_popover_content_typography' => '',

            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));


        // wrapper class
        $wrapper_class_arr = array();
        
        $unique_class = uniqid('htmegavc_popover_');
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_popover_wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        // add wrapper class
        $wrapper_class_arr[] =  'htmegavc-popover';
        $wrapper_class_arr[] =  'text-'. $button_alignment;

        // join all wrapper class
        $wrapper_class = implode(' ', $wrapper_class_arr);

        // .popover inline style
        $popover_inline_style = "min-width: $hover_popover_area_width;";
        $popover_inline_style .= "background-color: $hover_popover_area_background;";
        $popover_inline_style .= "box-shadow: $hover_popover_area_box_shadow;";
        $popover_inline_style .= "border-width:$hover_popover_area_border_width;";
        $popover_inline_style .= "border-style:$hover_popover_area_border_style;";
        $popover_inline_style .= "border-radius:$hover_popover_area_border_radius;";
        $popover_inline_style .= "border-color:$hover_popover_area_border_color;";

        // .htb-popover-header inline style
        $popover_header_inline_style = "color: $hover_popover_header_color;";
        $popover_header_inline_style .= "background-color: $hover_popover_header_background;";
        $popover_header_inline_style .= "padding: $hover_popover_header_padding;";

        // .htmegavc-popover span inline style
        $wrapper_span_inline_style = "border-width:$button_border_width;";
        $wrapper_span_inline_style .= "border-style:$button_border_style;";
        $wrapper_span_inline_style .= "border-radius:$button_border_radius;";
        $wrapper_span_inline_style .= "border-color:$button_border_color;";
        $wrapper_span_inline_style .= "margin:$button_margin;";
        $wrapper_span_inline_style .= "padding:$button_padding;";

        // .htb-popover-body inline style
        $popover_body_inline_style = "color: $hover_popover_content_color;";
        $popover_body_inline_style .= "background-color: $hover_popover_content_background;";
        $popover_body_inline_style .= "padding: $hover_popover_content_padding;";

        // typography and google font
        $google_font_data1 = htmegavc_build_google_font_data($button_google_font);
        if ( 'true' == $button_use_google_font && isset( $google_font_data1['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data1['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data1['values']['font_family'] );
        }

        // concate google font properties and other properties
        $button_google_font = htmegavc_build_google_font_style($google_font_data1);
        $wrapper_span_inline_style .= htmegavc_combine_font_container($button_typography.';'.$button_google_font);

        // Popover header Typography
        $google_font_data2 = htmegavc_build_google_font_data($hover_popover_header_google_font);
        if ( 'true' == $hover_popover_header_use_google_font && isset( $google_font_data2['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data2['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data2['values']['font_family'] );
        }

        // concate google font properties and other properties
        $hover_popover_header_google_font = htmegavc_build_google_font_style($google_font_data2);
        $popover_header_inline_style .= htmegavc_combine_font_container($hover_popover_header_typography.';'.$hover_popover_header_google_font);

        // Popover content Typography
        $google_font_data2 = htmegavc_build_google_font_data($hover_popover_content_google_font);
        if ( 'true' == $hover_popover_content_use_google_font && isset( $google_font_data2['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data2['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data2['values']['font_family'] );
        }

        // concate google font properties and other properties
        $hover_popover_content_google_font = htmegavc_build_google_font_style($google_font_data2);
        $popover_body_inline_style .= htmegavc_combine_font_container($hover_popover_content_typography.';'.$hover_popover_content_google_font);


        $output = '';
        $output .= '<style>';
        $output .= "
			.$unique_class .htbbs-popover-auto[x-placement^=top]{ top: -$popover_space !important}
			.$unique_class .htbbs-popover-top{ top: -$popover_space !important}
			.$unique_class .htbbs-popover-auto[x-placement^=bottom]{ top: $popover_space !important}
			.$unique_class .htbbs-popover-bottom{ top: $popover_space !important}
			.$unique_class .htbbs-popover-auto[x-placement^=right]{ left: $popover_space !important}
			.$unique_class .htbbs-popover-right{ left: $popover_space !important}
			.$unique_class .htbbs-popover-auto[x-placement^=left]{ left: $popover_space !important}
			.$unique_class .htbbs-popover-left{left: -$popover_space !important}

			.$unique_class .popover{ $popover_inline_style }

			.$unique_class .htbbs-popover-auto[x-placement^=top] .arrow::after{ border-top-color: $hover_popover_area_background !important; }
			.$unique_class .htbbs-popover-top .arrow::after{ border-top-color: $hover_popover_area_background !important; }
			.$unique_class .htbbs-popover-auto[x-placement^=bottom] .arrow::after{ border-bottom-color: $hover_popover_area_background !important; }
			.$unique_class .htbbs-popover-bottom .arrow::after{ border-bottom-color: $hover_popover_area_background !important; }
			.$unique_class .htbbs-popover-auto[x-placement^=left] .arrow::after{ border-left-color: $hover_popover_area_background !important; }
			.$unique_class .htbbs-popover-left .arrow::after{ border-left-color: $hover_popover_area_background !important; }
			.$unique_class .htbbs-popover-auto[x-placement^=right] .arrow::after{ border-right-color: $hover_popover_area_background !important; }
			.$unique_class .htbbs-popover-right .arrow::after{ border-right-color: $hover_popover_area_background !important; }


			.$unique_class .htb-popover-header{ $popover_header_inline_style }
			.$unique_class .htb-popover-body{ $popover_body_inline_style }

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
             	    $popover_button_txt = sprintf( '<a href="%1$s" target="%3$s">%2$s</a>', $link_url, $popover_button_txt,$link_target );
             	  }
             	}

             	 $active_class = '';
             	if( $show_popover == 'yes' ){
             	    $active_class = 'show';
             	}

             	echo sprintf('
             		<span class="%1$s" data-container=".%2$s" data-toggle="popover" data-placement="%3$s" data-content="%4$s" title="%5$s" style="%7$s">%6$s</span>',
             		 $active_class,
             		 $unique_class,
             		 $popover_dir,
             		 $popover_text,
             		 $popover_header_text,
             		 $popover_button_txt,
             		 $wrapper_span_inline_style
             	);
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
            "name" => __("HT Popover", 'htmegavc'),
            "description" => __("Add Popover to your page", 'htmegavc'),
            "base" => "htmegavc_popover",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegvc_popover_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('HT Mega Addons', 'htmegavc'),
            "params" => array(

            	// cotnent
            	array(
            	    'param_name' => 'popover_button_txt',
            	    'heading' => __( 'Button Text', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'value' => 'Button Popover',
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
            	    'param_name' => 'popover_text',
            	    'heading' => __( 'Popover Text', 'htmegavc' ),
            	    'type' => 'textarea',
            	    'value' => esc_html__( 'Popover content', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'popover_header_text',
            	    'heading' => __( 'Popover Header Text', 'htmegavc' ),
            	    'type' => 'textarea',
            	    'value' => esc_html__( 'Popover Header content', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'popover_dir',
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
            	array(
            	  "param_name" => "show_popover",
            	  "heading" => __("Show Popover by default", 'htmegavc'),
            	  "type" => "dropdown",
            	  "default_set" => '0',
            	  'value' => [
            	      __( 'Hide', 'htmegavc' )  =>  '0',
            	      __( 'Show', 'htmegavc' )  =>  'yes',
            	  ],
            	),

                // customization
                // Popover Styling
                array(
                    "param_name" => "custom_heading",
                    "type" => "htmegavc_param_heading",
                    "text" => __("Popover Styling","htmegavc"),
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
                    'param_name' => 'popover_space',
                    'heading' => __( 'Space With Button', 'htmegavc' ),
                    'type' => 'textfield',
                    'value' => '10px',
                    'description' => __( 'Eg: 10px', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'hover_popover_area_width',
                    'heading' => __( 'Popover area width', 'htmegavc' ),
                    'type' => 'textfield',
                    'value' => '100px',
                    'description' => __( 'Eg: 100px', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'hover_popover_area_background',
                    'heading' => __( 'Popover area background', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'hover_popover_area_box_shadow',
                  'heading' => __( 'Popover area Box Shadow', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Example value: 0 0 10px rgba(0, 0, 0, 0.1) <a target="_blank" href="https://www.w3schools.com/cssref/css3_pr_box-shadow.asp">Learn More</a>', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'hover_popover_area_border_width',
                    'heading' => __( 'Popover area Border width', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS Border width. Example: 2px, which stand for border-width:2px;', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'hover_popover_area_border_style',
                    'heading' => __( 'Popover area Border style', 'htmegavc' ),
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
                    'param_name' => 'hover_popover_area_border_radius',
                    'heading' => __( 'Popover area Border Radius', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS Border Radius. Example: 5px, which stand for border-radius:5px;', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'hover_popover_area_border_color',
                    'heading' => __( 'Popover area Border color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'description' => __( 'The CSS Border color.', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),

                // Popover Header
                array(
                    "param_name" => "custom_heading",
                    "type" => "htmegavc_param_heading",
                    "text" => __("Popover Header Styling","htmegavc"),
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'hover_popover_header_color',
                    'heading' => __( 'Popover header text color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'hover_popover_header_background',
                    'heading' => __( 'Popover header BG color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'hover_popover_header_padding',
                    'heading' => __( 'Popover Header Padding', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS padding. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),

                // Popover Content
                array(
                    "param_name" => "custom_heading",
                    "type" => "htmegavc_param_heading",
                    "text" => __("Popover Content Styling","htmegavc"),
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'hover_popover_content_color',
                    'heading' => __( 'Content text color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'hover_popover_content_background',
                    'heading' => __( 'Content BG color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'hover_popover_content_padding',
                    'heading' => __( 'Content Padding', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS padding. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
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
                

                //Popover header Typography
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
                  'param_name' => 'hover_popover_header_use_google_font',
                  'description' => __( 'Use font family from google font.', 'htmegavc' ),
                  'group'  => __( 'Typography', 'htmegavc' ),
                ),
                array(
                  'type' => 'google_fonts',
                  'param_name' => 'hover_popover_header_google_font',
                  'group'  => __( 'Typography', 'htmegavc' ),
                  'settings' => array(
                    'fields' => array(
                      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
                      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
                    ),
                  ),
                  'dependency' =>[
                      'element' => 'hover_popover_header_use_google_font',
                      'value' => array( 'true' ),
                  ],
                ),
                array(
                  'param_name' => 'hover_popover_header_typography',
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

                // Popover content Typography
                array(
                    "param_name" => "package_typograpy",
                    "type" => "htmegavc_param_heading",
                    "text" => __("Popover content Typography","htmegavc"),
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Typography', 'htmegavc' ),
                ),
                array(
                  'type' => 'checkbox',
                  'heading' => __( 'Use google font?', 'htmegavc' ),
                  'param_name' => 'hover_popover_content_use_google_font',
                  'description' => __( 'Use font family from google font.', 'htmegavc' ),
                  'group'  => __( 'Typography', 'htmegavc' ),
                ),
                array(
                  'type' => 'google_fonts',
                  'param_name' => 'hover_popover_content_google_font',
                  'group'  => __( 'Typography', 'htmegavc' ),
                  'settings' => array(
                    'fields' => array(
                      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
                      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
                    ),
                  ),
                  'dependency' =>[
                      'element' => 'hover_popover_content_use_google_font',
                      'value' => array( 'true' ),
                  ],
                ),
                array(
                  'param_name' => 'hover_popover_content_typography',
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
new Htmegavc_Popover();