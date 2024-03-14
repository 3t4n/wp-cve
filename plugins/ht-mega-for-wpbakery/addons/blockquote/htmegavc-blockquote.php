<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Blockquote extends WPBakeryShortCode{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_blockquote', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
    	wp_register_style( 'htmegavc-blockquote', plugins_url('css/blockquote.css', __FILE__));
    	wp_enqueue_style( 'htmegavc-blockquote' );
    }
 
    public function integrateWithVC() {
 
        /*
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("HT Blockquote", 'htmevavc'),
            "description" => __("Add Blockquote to your page", 'htmevavc'),
            "base" => "htmegavc_blockquote",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegvc_blockquote_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('HT Mega Addons', 'htmevavc'),
            "params" => array(

            	// cotnent
                array(
                    'param_name' => 'custom_content',
                    'heading' => __( 'Blockquote Content', 'htmevavc' ),
                    'type' => 'textarea',
                ),
                array(
                    'param_name' => 'blockquote_by',
                    'heading' => __( 'Blockquote By', 'htmevavc' ),
                    'type' => 'textfield',
                    'value' => __( 'Jon Doy', 'htmevavc' ),
                ),
                array(
                  "param_name" => "blockquote_position",
                  "heading" => __("Blockquote position", 'htmevavc'),
                  "type" => "dropdown",
                  "default_set" => 'righttop',
                  'value' => [
                  	__( 'Left Top', 'htmevavc' )	=>	'lefttop'     ,
                  	__( 'Left Center', 'htmevavc' )	=>	'leftcenter'  ,
                  	__( 'Left Bottom', 'htmevavc' )	=>	'leftbottom'  ,
                  	__( 'Center Top', 'htmevavc' )	=>	'centertop'   ,
                  	__( 'Center Center', 'htmevavc' )	=>	'center'      ,
                  	__( 'Center Bottom', 'htmevavc' )	=>	'centerbottom',
                  	__( 'Right Top', 'htmevavc' )	=>	'righttop'    ,
                  	__( 'Right Center', 'htmevavc' )	=>	'rightcenter' ,
                  	__( 'Right Bottom', 'htmevavc' )	=>	'rightbottom' ,
                  ],
                ),
                array(
                  "param_name" => "blockquote_icon_type",
                  "heading" => __("Blockquote icon type", 'htmevavc'),
                  "type" => "dropdown",
                  "default_set" => 'righttop',
                  'value' => [
                  	__( 'Image icon', 'htmevavc' )	=>	'img_icon'     ,
                  	__( 'Font icon', 'htmevavc' )	=>	'font_icon'  ,
                  ],
                ),
                array(
                    'param_name' => 'blockquote_image',
                    'heading' => __( 'Image', 'htmevavc' ),
                    'type' => 'attach_image',
                    'description' => '',
                    'dependency' =>[
                        'element' => 'blockquote_icon_type',
                        'value' => array( 'img_icon' ),
                    ],
                ),
                array(
                    'param_name' => 'blockquote_icon',
                    'heading' => __( 'Icon', 'htmevavc' ),
                    'type' => 'iconpicker',
                    'description' => '',
                    'dependency' =>[
                        'element' => 'blockquote_icon_type',
                        'value' => array( 'font_icon' ),
                    ],
                ),


                // Styling
                array(
                    'param_name' => 'blockquot_text_color',
                    'heading' => __( 'Blockquote Text color', 'htmevavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                	'param_name' => 'htmega_blockquote_background',
                	'heading' => __( 'Blockquote Background', 'htmevavc' ),
                	'type' => 'colorpicker',
                	'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'htmega_blockquote_padding',
                    'heading' => __( 'Blockquote Padding', 'htmevavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS padding of blockquote. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmevavc' ),
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'htmega_blockquote_border_width',
                    'heading' => __( 'Blockquote Border width', 'htmevavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS Border width of blockquote. Example: 2px, which stand for border-width:2px;', 'htmevavc' ),
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'htmega_blockquote_border_style',
                    'heading' => __( 'Blockquote Border style', 'htmevavc' ),
                    'type' => 'dropdown',
                    "default_set" => '1',
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
                    'param_name' => 'htmega_blockquote_border_color',
                    'heading' => __( 'Blockquote Border color', 'htmevavc' ),
                    'type' => 'colorpicker',
                    'description' => __( 'The CSS Border color of blockquote.', 'htmevavc' ),
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),

                // quoted by
                array(
                    "type" => "htmegavc_param_heading",
                    "text" => __("Quoted By Styling","htmevavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'blockquoteby_color',
                    'heading' => __( 'Text color', 'htmevavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'blockquoteby_before_color',
                    'heading' => __( 'Separator color', 'htmevavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'blockquoteby_before_width',
                    'heading' => __( 'Separator width', 'htmevavc' ),
                    'type' => 'textfield',
                    'group'  => __( 'Styling', 'htmevavc' ),
                    'description'  => __( 'Eg: 20px', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'blockquoteby_before_height',
                    'heading' => __( 'Separator height', 'htmevavc' ),
                    'type' => 'textfield',
                    'description'  => __( 'Eg: 2px', 'htmevavc' ),
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),

                // quote icon
                array(
                    "type" => "htmegavc_param_heading",
                    "text" => __("Quote Icon Styling","htmevavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'blockquoteicon_fontsize',
                    'heading' => __( 'Icon Font size', 'htmevavc' ),
                    'type' => 'textfield',
                    'description' => __( 'Eg: 20px', 'htmevavc' ),
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'blockquoteicon_line_height',
                    'heading' => __( 'Icon Line Height', 'htmevavc' ),
                    'type' => 'textfield',
                    'description' => __( 'Eg: 20px', 'htmevavc' ),
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'blockquoteicon_width',
                    'heading' => __( 'Icon Width', 'htmevavc' ),
                    'type' => 'textfield',
                    'description' => __( 'Eg: 20px', 'htmevavc' ),
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'blockquoteicon_height',
                    'heading' => __( 'Icon Height', 'htmevavc' ),
                    'type' => 'textfield',
                    'description' => __( 'Eg: 20px', 'htmevavc' ),
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'blockquoteicon_background',
                    'heading' => __( 'Icon BG color', 'htmevavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'blockquoteicon_color',
                    'heading' => __( 'Icon color', 'htmevavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'blockquoteicon_padding',
                    'heading' => __( 'Icon Padding', 'htmevavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS padding of icon. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmevavc' ),
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'blockquoteicon_border_width',
                    'heading' => __( 'Blockquote Border width', 'htmevavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS Border width of blockquote. Example: 2px, which stand for border-width:2px;', 'htmevavc' ),
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'blockquoteicon_border_style',
                    'heading' => __( 'Blockquote Border style', 'htmevavc' ),
                    'type' => 'dropdown',
                    "default_set" => '1',
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
                    'param_name' => 'blockquoteicon_border_radius',
                    'heading' => __( 'Icon Border radius', 'htmevavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS Border color of blockquote.', 'htmevavc' ),
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),
                array(
                    'param_name' => 'blockquoteicon_border_color',
                    'heading' => __( 'Icon Border color', 'htmevavc' ),
                    'type' => 'colorpicker',
                    'description' => __( 'The CSS Border color of blockquote.', 'htmevavc' ),
                    'group'  => __( 'Styling', 'htmevavc' ),
                ),




                // typography
                array(
                    "type" => "htmegavc_param_heading",
                    "text" => __("Blockquote Typography","htmevavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Typography', 'htmevavc' ),
                ),
                array(
                  'type' => 'checkbox',
                  'heading' => __( 'Use google font?', 'htmevavc' ),
                  'param_name' => 'blockquote_text_use_google_font',
                  'description' => __( 'Use font family from google font.', 'htmevavc' ),
                  'group'  => __( 'Typography', 'htmevavc' ),
                ),
                array(
                  'type' => 'google_fonts',
                  'param_name' => 'blockquote_text_google_font',
                  'group'  => __( 'Typography', 'htmevavc' ),
                  'settings' => array(
                    'fields' => array(
                      'font_family_description' => __( 'Select font family.', 'htmevavc' ),
                      'font_style_description' => __( 'Select font styling.', 'htmevavc' ),
                    ),
                  ),
                  'dependency' =>[
                      'element' => 'blockquote_text_use_google_font',
                      'value' => array( 'true' ),
                  ],
                ),
                array(
                  'param_name' => 'blockquote_text_typography',
                  'type' => 'font_container',
                  'group'  => __( 'Typography', 'htmevavc' ),
                  'settings' => array(
                    'fields' => array(
                      'text_align',
                      'font_size',
                      'line_height',
                      'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmevavc' ),
                      'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmevavc' ),
                    ),
                  ),
                ),
                array(
                    "type" => "htmegavc_param_heading",
                    "text" => __("Quoted by Typography","htmevavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Typography', 'htmevavc' ),
                ),
                array(
                  'type' => 'checkbox',
                  'heading' => __( 'Use google font?', 'htmevavc' ),
                  'param_name' => 'quoted_by_text_use_google_font',
                  'description' => __( 'Use font family from google font.', 'htmevavc' ),
                  'group'  => __( 'Typography', 'htmevavc' ),
                ),
                array(
                  'type' => 'google_fonts',
                  'param_name' => 'quoted_by_text_google_font',
                  'group'  => __( 'Typography', 'htmevavc' ),
                  'settings' => array(
                    'fields' => array(
                      'font_family_description' => __( 'Select font family.', 'htmevavc' ),
                      'font_style_description' => __( 'Select font styling.', 'htmevavc' ),
                    ),
                  ),
                  'dependency' =>[
                      'element' => 'quoted_by_text_use_google_font',
                      'value' => array( 'true' ),
                  ],
                ),
                array(
                  'param_name' => 'quoted_by_text_typography',
                  'type' => 'font_container',
                  'group'  => __( 'Typography', 'htmevavc' ),
                  'settings' => array(
                    'fields' => array(
                      'text_align',
                      'font_size',
                      'line_height',
                      'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmevavc' ),
                      'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmevavc' ),
                    ),
                  ),
                ),



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



    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
            'custom_content' => '',
            'blockquote_by' => '',
            'blockquote_icon_type' => 'img_icon',
            'blockquote_image' => '',
            'blockquote_icon' => '',
            'blockquote_position' => 'lefttop',
            'blockquoteby_before_position' => '',

            // customization
            'blockquot_text_color' => '',
            'htmega_blockquote_padding' => '',
            'htmega_blockquote_background' => '',
            'htmega_blockquote_border_width' => '',
            'htmega_blockquote_border_style' => '',
            'htmega_blockquote_border_color' => '',

            'blockquoteby_color' => '',
            'blockquoteby_before_color' => '',
            'blockquoteby_before_width' => '',
            'blockquoteby_before_height' => '',

            'blockquoteicon_color' => '',
            'blockquoteicon_background' => '',
            'blockquoteicon_padding' => '',
            'blockquoteicon_border_width' => '',
            'blockquoteicon_border_color' => '',
            'blockquoteicon_border_style' => '',
            'blockquoteicon_border_radius' => '',
            'blockquoteicon_fontsize' => '',
            'blockquoteicon_line_height' => '',
            'blockquoteicon_width' => '',
            'blockquoteicon_height' => '',

            'blockquote_text_use_google_font' => '',
            'blockquote_text_google_font' => '',
            'blockquote_text_typography' => '',

            'quoted_by_text_use_google_font' => '',
            'quoted_by_text_google_font' => '',
            'quoted_by_text_typography' => '',

            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));


        // wrapper class
        $wrapper_class_arr = array();
        
        $unique_class = uniqid('htmegavc_blockquote_');
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_blockquote_wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        // add wrapper class
        $wrapper_class_arr[] =  'htmegavc-blockquote htmegavc-blockquote-position-'. $blockquote_position;
        $wrapper_class_arr[] =  'htmegavc-citeseparator-position-'. $blockquoteby_before_position;

        // join all wrapper class
        $wrapper_class = implode(' ', $wrapper_class_arr);

        // load google font
        $google_font_data2 = htmegavc_build_google_font_data($blockquote_text_google_font);
        if ( 'true' == $blockquote_text_use_google_font && isset( $google_font_data2['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data2['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data2['values']['font_family'] );
        }

        // concate google font properties and other properties
        $blockquote_text_google_font = htmegavc_build_google_font_style($google_font_data2);
        $blockquote_inline_style = htmegavc_combine_font_container($blockquote_text_typography.';'.$blockquote_text_google_font);

        // load google font
        $google_font_data1 = htmegavc_build_google_font_data($quoted_by_text_google_font);
        if ( 'true' == $quoted_by_text_use_google_font && isset( $google_font_data1['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data1['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data1['values']['font_family'] );
        }

        // concate google font properties and other properties
        $quoted_by_text_google_font = htmegavc_build_google_font_style($google_font_data1);
        $quote_by_inline_style = htmegavc_combine_font_container($quoted_by_text_typography.';'.$quoted_by_text_google_font);

        // blockquote inline style
        $blockquote_inline_style .= "color:$blockquot_text_color !important;";
        $blockquote_inline_style .= "padding:$htmega_blockquote_padding;";
        $blockquote_inline_style .= "background-color:$htmega_blockquote_background;";
        $blockquote_inline_style .= "border-width:$htmega_blockquote_border_width;";
        $blockquote_inline_style .= "border-style:$htmega_blockquote_border_style;";
        $blockquote_inline_style .= "border-color:$htmega_blockquote_border_color;";

        $quote_by_inline_style .= "color:$blockquoteby_color;";

        // blockquote_icon inline style
        $blockquote_icon_inline_style = "background-color:$blockquoteicon_background;";
        $blockquote_icon_inline_style .= "color:$blockquoteicon_color;";
        $blockquote_icon_inline_style .= "padding:$blockquoteicon_padding;";
        $blockquote_icon_inline_style .= "border-width:$blockquoteicon_border_width;";
        $blockquote_icon_inline_style .= "border-color:$blockquoteicon_border_color;";
        $blockquote_icon_inline_style .= "border-style:$blockquoteicon_border_style;";
        $blockquote_icon_inline_style .= "border-radius:$blockquoteicon_border_radius;";
        $blockquote_icon_inline_style .= "font-size:$blockquoteicon_fontsize;";
        $blockquote_icon_inline_style .= "line-height:$blockquoteicon_line_height;";
        $blockquote_icon_inline_style .= "width:$blockquoteicon_width;";
        $blockquote_icon_inline_style .= "height:$blockquoteicon_height;";

        $output = '';
        $output .= '<style>';;
        $output .= "
            .$unique_class blockquote cite::before{
				background-color:$blockquoteby_before_color;
				height:$blockquoteby_before_height;
				width:$blockquoteby_before_width;
            }";
        $output .= '</style>';

        ob_start(); ?>

         <div class="<?php echo esc_attr($wrapper_class); ?>">
             <blockquote  style="<?php echo esc_attr($blockquote_inline_style); ?>">
                 <?php 
                     if ( !empty( $custom_content ) ) {
                         echo '<div class="blockquote_content">'.wp_kses_post( $custom_content ).'</div>';
                     }
                     if( !empty( $blockquote_by ) ){
                         echo '<cite class="quote-by" style="'. $quote_by_inline_style .'"> '.esc_html( $blockquote_by ).' </cite>';
                     }
                     if( !empty( $blockquote_image ) && $blockquote_icon_type == 'img_icon' ){
                         echo wp_get_attachment_image($blockquote_image, 'large');
                     }else{
                         echo sprintf('<span class="blockquote_icon" style="'. $blockquote_icon_inline_style .'"><i class="%1$s"></i></span>',$blockquote_icon);
                     }
                 ?>
             </blockquote>
        </div>

        <?php 
        $output .= ob_get_clean();
        return $output;
  }

}

// Finally initialize code
new Htmegavc_Blockquote();