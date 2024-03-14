<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Lightbox extends WPBakeryShortCode{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_lightbox', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
    	wp_register_script( 'jquery-magnific-popup', HTMEGAVC_LIBS_URI . '/magnific-popup/jquery.magnific-popup.min.js', '', '', '');
    	wp_enqueue_script( 'jquery-magnific-popup' );

    	wp_register_style( 'magnific-popup', HTMEGAVC_LIBS_URI . '/magnific-popup/magnific-popup.css' );
    	wp_enqueue_style( 'magnific-popup' );

    	wp_register_script( 'htmegavc-lightbox-active', plugins_url('js/lightbox-active.js', __FILE__), '', '', true);
    	wp_enqueue_script( 'htmegavc-lightbox-active' );

    	wp_register_style( 'htmegavc-lightbox', plugins_url('css/lightbox.css', __FILE__));
    	wp_enqueue_style( 'htmegavc-lightbox' );

    }


    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
           
            // Content
            'lightbox_type' => 'image', // image, video
            	'lightbox_image' => '',
            	'lightbox_image_size' => '',
            	'lightbox_video_url' => 'https://www.youtube.com/watch?v=G_G8SdXktHg',

            'lightbox_toggler_type' => 'image', // image, btn
            	'toggler_image' => '',
            	'toggler_imagesize' => '',
            	'toggler_image_icon' => 'fa fa-plus',

            	'toggler_button_text' => '',

            // Styling
            'toggler_image_overlay_color' => '',
            'toggler_image_margin' => '',
            'toggler_image_padding' => '',
            'toggler_image_icon_size' => '',
            'toggler_image_icon_color' => '',
            'toggler_image_icon_border_width' => '',
            'toggler_image_icon_border_style' => '',
            'toggler_image_icon_border_radius' => '',
            'toggler_image_icon_border_color' => '',

            'toggler_button_text_align' => '',
            'toggler_button_text_color' => '',
            'toggler_button_bg_color' => '',
            'toggler_button_margin' => '',
            'toggler_button_padding' => '',
            'toggler_button_text_use_google_font' => '',
            'toggler_button_text_google_font' => '',
            'toggler_button_text_typography' => '',

            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));


        // wrapper class
        $wrapper_class_arr = array();
        
        $unique_class = uniqid('htmegavc_lightbox_');
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_lightbox_wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        // add wrapper class
        $wrapper_class_arr[] =  'htmegavc-lightbox';
        $wrapper_class_arr[] =  'text-'. $toggler_button_text_align;
        $wrapper_class_arr[] =  'toggler-type-'. $lightbox_toggler_type;
        $wrapper_class_arr[] =  'lightbox-type-'. $lightbox_type;

        // join all wrapper class
        $wrapper_class = implode(' ', $wrapper_class_arr);

        // Styling
        $overlay_inline_style = $icon_inline_style = $button_inline_style = '';
        $overlay_inline_style .= "background-color:$toggler_image_overlay_color";

        $icon_inline_style .= "margin:$toggler_image_margin;";
        $icon_inline_style .= "padding:$toggler_image_padding;";
        $icon_inline_style .= "font-size:$toggler_image_icon_size;";
        $icon_inline_style .= "color:$toggler_image_icon_color;";
        $icon_inline_style .= "border-width:$toggler_image_icon_border_width;";
        $icon_inline_style .= "border-style:$toggler_image_icon_border_style;";
        $icon_inline_style .= "border-radius:$toggler_image_icon_border_radius;";
        $icon_inline_style .= "border-color:$toggler_image_icon_border_color;";

        $button_inline_style .= "text-align:$toggler_button_text_align;";
        $button_inline_style .= "color:$toggler_button_text_color;";
        $button_inline_style .= "background-color:$toggler_button_bg_color;";
        $button_inline_style .= "margin:$toggler_button_margin;";
        $button_inline_style .= "padding:$toggler_button_padding;";

        // Typography
        // Before Title Typography
        $google_font_data1 = htmegavc_build_google_font_data($toggler_button_text_google_font);
        if ( 'true' == $toggler_button_text_use_google_font && isset( $google_font_data1['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data1['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data1['values']['font_family'] );
        }

        // concate google font properties and other properties
        $toggler_button_text_google_font = htmegavc_build_google_font_style($google_font_data1);
        $button_inline_style .= htmegavc_combine_font_container($toggler_button_text_typography.';'.$toggler_button_text_google_font);


        $output = '';
        $output .= '<style>';
        $output .= "
			.$unique_class .htmegavc-lightbox-action::before{  $overlay_inline_style }
			.$unique_class .htmegavc-lightbox-action a i{  $icon_inline_style }
			.$unique_class a{  $button_inline_style }
        ";
        $output .= '</style>';

        // toggler image size
        if(strpos($toggler_imagesize, 'x')){
            $size_arr = explode('x', $toggler_imagesize);
            $toggler_imagesize = array($size_arr[0],$size_arr[1]);
        }

        // lightbox image size
        if(strpos($lightbox_image_size, 'x')){
            $size_arr = explode('x', $lightbox_image_size);
            $lightbox_image_size = array($size_arr[0],$size_arr[1]);
        }

        if($lightbox_type == 'image'){
        	$lightbox_url = wp_get_attachment_image_src($lightbox_image, $lightbox_image_size);
        	if(isset($lightbox_url[0])){
        		$lightbox_url = $lightbox_url[0];
        	}
        } elseif($lightbox_type == 'video'){
        	$lightbox_type = 'iframe';
        	$lightbox_url = $lightbox_video_url;
        }

        ob_start();
        ?>

        <div class="<?php echo esc_attr( $wrapper_class ); ?>" >

		    <?php if($lightbox_toggler_type != 'btn'): ?>
				<div class="htmegavc-lightboxthumb">
				    <?php echo wp_get_attachment_image($toggler_image, $toggler_imagesize); ?>
				</div>
				<div class="htmegavc-lightbox-action">
				    <a class="image-popup-vertical-fit" href="<?php echo $lightbox_url; ?>" data-popupoption='{"datatype":"<?php echo $lightbox_type ?>"}' ><i class="<?php echo esc_attr( $toggler_image_icon );?>"></i></a>
				</div>
		    <?php else: ?>
				<a class="image-popup-vertical-fit" href="<?php echo $lightbox_url; ?>" data-popupoption='{"datatype":"<?php echo $lightbox_type ?>"}'><?php echo esc_html( $toggler_button_text );?></a>
		    <?php endif; ?>

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
            "name" => __("HT Lightbox", 'htmegavc'),
            "description" => __("Add Lightbox to your page", 'htmegavc'),
            "base" => "htmegavc_lightbox",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegavc_lightbox_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('HT Mega Addons', 'htmegavc'),
            "params" => array(

            	// cotnent
            	array(
            	  "param_name" => "lightbox_type",
            	  "heading" => __("Lightbox Type", 'htmegavc'),
            	  "type" => "dropdown",
            	  "default_set" => 'image',
            	  'value' => [
            	      __( 'Image', 'htmegavc' )  =>  'image',
            	      __( 'Video', 'htmegavc' )  =>  'video',
            	  ],
            	),
            	array(
            	    'param_name' => 'lightbox_image',
            	    'heading' => __( 'Lightbox Image', 'htmegavc' ),
            	    'type' => 'attach_image',
            	    'dependency' =>[
            	        'element' => 'lightbox_type',
            	        'value' => array( 'image' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'lightbox_image_size',
            	    'heading' => __( 'Image Size', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'lightbox_type',
            	        'value' => array( 'image' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'lightbox_video_url',
            	    'heading' => __( 'Lightbox Video URL', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'value' => 'https://www.youtube.com/watch?v=G_G8SdXktHg',
            	    'dependency' =>[
            	        'element' => 'lightbox_type',
            	        'value' => array( 'video' ),
            	    ],
            	),

            	array(
            	  "param_name" => "lightbox_toggler_type",
            	  "heading" => __("Toggler Type", 'htmegavc'),
            	  "type" => "dropdown",
            	  "default_set" => 'image',
            	  'value' => [
            	      __( 'Image', 'htmegavc' )  =>  'image',
            	      __( 'Button', 'htmegavc' )  =>  'btn',
            	  ],
            	),
            	array(
            	    'param_name' => 'toggler_image',
            	    'heading' => __( 'Toggler Image', 'htmegavc' ),
            	    'type' => 'attach_image',
            	    'dependency' =>[
            	        'element' => 'lightbox_toggler_type',
            	        'value' => array( 'image' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'toggler_imagesize',
            	    'heading' => __( 'Toggler Image Size', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'toggler_type',
            	        'value' => array( 'image' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'toggler_image_icon',
            	    'heading' => __( 'Toggler Icon', 'htmegavc' ),
            	    'type' => 'iconpicker',
            	    'value' => 'fa fa-plus',
            	    'dependency' =>[
            	        'element' => 'toggler_type',
            	        'value' => array( 'image' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'toggler_button_text',
            	    'heading' => __( 'Toggler Button Text', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'dependency' =>[
            	        'element' => 'lightbox_toggler_type',
            	        'value' => array( 'btn' ),
            	    ],
            	),


            	// Styling
            	array(
            	    'param_name' => 'toggler_image_overlay_color',
            	    'heading' => __( 'Toggler Overlay Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'lightbox_toggler_type',
            	        'value' => array( 'image' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'toggler_image_margin',
            	    'heading' => __( 'Toggler Image Margin', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS margin. Example: 18px 0, which stand for margin-top and margin-bottom is 18px', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'lightbox_toggler_type',
            	        'value' => array( 'image' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'toggler_image_padding',
            	    'heading' => __( 'Toggler Image Padding', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS padding. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'lightbox_toggler_type',
            	        'value' => array( 'image' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'toggler_image_icon_size',
            	    'heading' => __( 'Toggler Hover Icon Border width', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS Border width. Example: 2px, which stand for border-width:2px;', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'lightbox_toggler_type',
            	        'value' => array( 'image' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'toggler_image_icon_color',
            	    'heading' => __( 'Toggler Hover Icon Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'lightbox_toggler_type',
            	        'value' => array( 'image' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'toggler_image_icon_border_width',
            	    'heading' => __( 'Toggler Hover Icon Border width', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS Border width. Example: 2px, which stand for border-width:2px;', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'lightbox_toggler_type',
            	        'value' => array( 'image' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'toggler_image_icon_border_style',
            	    'heading' => __( 'Toggler Hover Icon Border style', 'htmegavc' ),
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
            	    'dependency' =>[
            	        'element' => 'lightbox_toggler_type',
            	        'value' => array( 'image' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'toggler_image_icon_border_radius',
            	    'heading' => __( 'Toggler Hover Icon Border Radius', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS Border Radius. Example: 5px, which stand for border-radius:5px;', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'lightbox_toggler_type',
            	        'value' => array( 'image' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'toggler_image_icon_border_color',
            	    'heading' => __( 'Toggler Hover Icon Border color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'description' => __( 'The CSS Border color.', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'lightbox_toggler_type',
            	        'value' => array( 'image' ),
            	    ],
            	),


            	array(
            	    'param_name' => 'toggler_button_text_align',
            	    'heading' => __( 'Button Text Alignment', 'htmegavc' ),
            	    'type' => 'dropdown',
            	    "default_set" => 'center',
            	    'value' => [
            	        __( 'Left', 'htmegavc' )  =>  'left',
            	        __( 'Center', 'htmegavc' )  =>  'center',
            	        __( 'Right', 'htmegavc' )  =>  'right',
            	        __( 'Justify', 'htmegavc' )  =>  'justify',
            	    ],
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'lightbox_toggler_type',
            	        'value' => array( 'btn' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'toggler_button_text_color',
            	    'heading' => __( 'Button Text Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'lightbox_toggler_type',
            	        'value' => array( 'btn' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'toggler_button_bg_color',
            	    'heading' => __( 'Button BG Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'lightbox_toggler_type',
            	        'value' => array( 'btn' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'toggler_button_margin',
            	    'heading' => __( 'Button Margin', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS margin. Example: 18px 0, which stand for margin-top and margin-bottom is 18px', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'lightbox_toggler_type',
            	        'value' => array( 'btn' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'toggler_button_padding',
            	    'heading' => __( 'Button Padding', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS padding. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'lightbox_toggler_type',
            	        'value' => array( 'btn' ),
            	    ],
            	),

            	// Typography
            	array(
            	  'type' => 'checkbox',
            	  'heading' => __( 'Use google Font?', 'htmegavc' ),
            	  'param_name' => 'toggler_button_text_use_google_font',
            	  'description' => __( 'Use font family from google font.', 'htmegavc' ),
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'dependency' =>[
            	      'element' => 'lightbox_toggler_type',
            	      'value' => array( 'btn' ),
            	  ],
            	),
            	array(
            	  'type' => 'google_fonts',
            	  'param_name' => 'toggler_button_text_google_font',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
            	      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
            	    ),
            	  ),
            	  'dependency' =>[
            	      'element' => 'toggler_button_text_use_google_font',
            	      'value' => array( 'true' ),
            	  ],
            	),
            	array(
            	  'param_name' => 'toggler_button_text_typography',
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
            	  'dependency' =>[
            	      'element' => 'lightbox_toggler_type',
            	      'value' => array( 'btn' ),
            	  ],
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
new Htmegavc_Lightbox();