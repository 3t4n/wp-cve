<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Section_Title{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_section_title', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_register_style( 'htmegavc_section_title', plugins_url('css/section-title.css', __FILE__) );
      wp_enqueue_style( 'htmegavc_section_title' );
    }

    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
            'style'         => '1', // 1, 2, 3, 4, 5, 6, 7
            'title'         => __('Middle Align Heading', 'htmegavc'),
            'sub_title'     => __('Lorem ipsum dolor sit amet, consectetur adipisicin elit sed.','htmegavc'),
            'text_align'    => 'center', // left, right, center

            'section_icon_type'           => 'image',// icon, image,
            'section_icon_image'          => '',
            'section_icon_image_size'     => 'large',

            'section_icon_font'            => '',
            'section_icon_font_size'       => '',
            'section_icon_font_color'      => '',

            'title_bg_image'      => '',
            'title_bg_image_size'     => 'large',

            'section_bg_text'     => '',

            'title_border_color'      => '',
            'title_border_width'      => '',
            'title_border_height'     => '',

            'sub_title_border_color'      => '',
            'sub_title_border_width'      => '',
            'sub_title_border_height'     => '',
            'section_border_position'     => 'none', // none, left, right
            'section_border_color'     => '',
            'section_border_width'     => '',
            'section_border_height'    => '',

            'title_typography'      => '',
            'sub_title_typography'      => '',
            'section_bg_text_typography'      => '',

            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));


        // wrapper class
        $unique_class = uniqid('htmegavc_section_title_');
        $wrapper_class_arr = array();
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'heading_wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        $wrapper_class_arr[] = 'heading-style-'. $style;
        $wrapper_class_arr[] = 'text-'. $text_align;

        $wrapper_class = implode(' ', $wrapper_class_arr);

        // Typography
        $title_typography = htmegavc_combine_font_container($title_typography);
        $sub_title_typography = htmegavc_combine_font_container($sub_title_typography);
        $section_bg_text_typography = htmegavc_combine_font_container($section_bg_text_typography);


        if( $style == '7' ){
            $output = "";

            $output .= '<style>';
            $output .= ".$unique_class p.heading-border-bottom::before{background: $sub_title_border_color;width:$sub_title_border_width;height:$sub_title_border_height}";
            $output .= "
            .$unique_class.heading-border-left::before,
            .$unique_class.heading-border-right::before{
                background: $section_border_color;width:$section_border_width;height:$section_border_height
            }";
            $output .= '</style>';

            $wrapper_class .= " htmegavc-heading heading-border-$section_border_position";

            $output .= '<div class="'. $wrapper_class .'">';
                $output .= '<p class="subtitle text-italic heading-border-bottom" style="'. $sub_title_typography .'">'. $sub_title .'</p>';
                $output .= '<h2 class="heading-1" style="'. $title_typography .';">'. $title .'</h2>';
            $output .= '</div>';

        } elseif ( $style == '6' ){
            $output = "";

            $output .= '<style>';
            $output .= '.'. $unique_class . '.heading-style-'. $style .' .middle-align-border::before{
                width: '. $title_border_width .';
                height: '. $title_border_height .';
                background: '. $title_border_color .';
            }';
            $output .= '.'. $unique_class . '.heading-style-'. $style .' .middle-align-border::after{
                width: '. $title_border_width .';
                height: '. $title_border_height .';
                background: '. $title_border_color .';
            }';
            $output .= '</style>';

            $wrapper_class .= ' htmegavc-heading';
            $size = $title_bg_image_size;
            if(strpos($size, 'x')){
                $size = array();
                $size = array($title_bg_image_size);
            }
            $bg_image_url = wp_get_attachment_image_src($title_bg_image, $size);
            $bg_image_url = $bg_image_url ? $bg_image_url[0] : "";

            $output .= '<div class="'. $wrapper_class .'">';
                $output .= '<p class="subtitle" style="'. $sub_title_typography .'">'. $sub_title .'</p>';
                $output .= '<h2 class="heading-1 middle-align-border" style="'. $title_typography .'; background-image:url('. $bg_image_url .');">'. $title .'</h2>';
            $output .= '</div>';

        } elseif ( $style == '3' ){
            $wrapper_class .= ' htmegavc-heading';

            $size = $title_bg_image_size;
            if(strpos($size, 'x')){
                $size = array();
                $size = array($title_bg_image_size);
            }
            $bg_image_url = wp_get_attachment_image_src($title_bg_image, $size);
            $bg_image_url = $bg_image_url ? $bg_image_url[0] : "";

            $output = "";
            $output .= '<div class="'. $wrapper_class .'">';
                $output .= '<p class="subtitle-3" style="'. $sub_title_typography .'">'. $sub_title .'</p>';
                $output .= '<h2 class="heading-1 heading-bg-img" style="'. $title_typography .'; background-image:url('. $bg_image_url .');">'. $title .'</h2>';
            $output .= '</div>';

        } elseif( $style == '2' || $style == '4' || $style == '5'){
            $img_size = $section_icon_image_size;
            if(strpos($img_size, 'x')){
                $img_size = array();
                $img_size = array($section_icon_image_size);
            }

            $wrapper_class .= $style == '2' ? ' poss_relative '. $text_align .'-icon htmegavc-heading' : ' poss_relative htmegavc-heading';
            $output = "";

            $output .= '<style>';
            $output .= '.'. $unique_class . '.heading-style-'. $style .' i{font-size:'. $section_icon_font_size .'}';
            $output .= '.'. $unique_class . '.heading-style-'. $style .' i{color:'. $section_icon_font_color .'}';
            $output .= '</style>';

            $output .= '<div class="'. $wrapper_class .'">';
                if($style == '4' || $style == '5'){
                    $output .= '<p class="subtitle" style="'. $sub_title_typography .'">'. $sub_title .'</p>';
                }

                $output .= '<h2 class="heading-2" style="'. $title_typography .'">'. $title .'</h2>';

                if($style == '2'){
                    $output .= '<p class="subtitle" style="'. $sub_title_typography .'">'. $sub_title .'</p>';
                }

                if($style == '5'){
                    $output .= '<h1 style="'. $section_bg_text_typography .'" class="heading-bg-text">'. $section_bg_text .'</h1>';
                }

                if($section_icon_type == 'image'){
                    $output .= wp_get_attachment_image($section_icon_image, $img_size);
                }

                if($section_icon_type == 'icon'){
                    $output .= '<i class="'. $section_icon_font .'"><i>';
                }
            $output .= '</div>';    

        } else {
            $wrapper_class .= ' htmegavc-heading';

            $output = "";
            $output .= '<div class="'. $wrapper_class .'">';
                $output .= '<h2 class="heading-2" style="'. $title_typography .'">'. $title .'</h2>';
                $output .= '<p class="subtitle" style="'. $sub_title_typography .'">'. $sub_title .'</p>';
            $output .= '</div>';
        }
  ?>

  <?php
    return $output;
  }



  public function integrateWithVC() {
  
      /*
      Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

      More info: http://kb.wpbakery.com/index.php?title=Vc_map
      */
      vc_map( array(
          "name" => __("HT Section Title", 'htmegavc'),
          "description" => __("Add heading/section title to your page", 'htmegavc'),
          "base" => "htmegavc_section_title",
          "class" => "",
          "controls" => "full",
          "icon" => 'htmegvc_section_title_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
          "category" => __('HT Mega Addons', 'htmegavc'),
          "params" => array(
              array(
                  "param_name" => "style",
                  "heading" => __("Style", 'htmegavc'),
                  "type" => "dropdown",
                  "default_set" => '1',
                  'value' => [
                      __( 'Style 1', 'htmegavc' )  =>  '1',
                      __( 'Style 2', 'htmegavc' )  =>  '2',
                      __( 'Style 3', 'htmegavc' )  =>  '3',
                      __( 'Style 4', 'htmegavc' )  =>  '4',
                      __( 'Style 5', 'htmegavc' )  =>  '5',
                      __( 'Style 6', 'htmegavc' )  =>  '6',
                      __( 'Style 7', 'htmegavc' )  =>  '7',
                  ],
              ),
              array(
                  'param_name' => 'title',
                  'heading' => __( 'Title', 'htmegavc' ),
                  'value' => __( 'Middle Align Heading', 'htmegavc' ),
                  'type' => 'textfield',
              ),
              array(
                  'param_name' => 'sub_title',
                  'heading' => __( 'Sub title', 'htmegavc' ),
                  'value' => __( 'Lorem ipsum dolor sit amet, consectetur adipisicin elit sed.', 'htmegavc' ),
                  'type' => 'textfield',
              ),
              array(
                  "param_name" => "text_align",
                  "heading" => __("Text Align", 'htmegavc'),
                  "type" => "dropdown",
                  "default_set" => 'center',
                  'value' => [
                      __( 'Center', 'htmegavc' )  =>  'center',
                      __( 'Left', 'htmegavc' )  =>  'left',
                      __( 'Right', 'htmegavc' )  =>  'right',
                  ],
              ),
              array(
                  "param_name" => "section_icon_type",
                  "heading" => __("Icon Type", 'htmegavc'),
                  "type" => "dropdown",
                  "default_set" => '1',
                  'value' => [
                      __( 'Image Icon', 'htmegavc' )  =>  'image',
                      __( 'Font Icon', 'htmegavc' )  =>  'icon',
                  ],
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '2', '4' ),
                  ],
              ),
              array(
                  'param_name' => 'section_icon_image',
                  'heading' => __( 'Image Icon Upload', 'htmegavc' ),
                  'type' => 'attach_image',
                  'dependency' =>[
                      'element' => 'section_icon_type',
                      'value' => array( 'image', ),
                  ],
              ),
              array(
                  'param_name' => 'section_icon_image_size',
                  'heading' => __( 'Image Icon Size', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)). Default we use the original image.', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'section_icon_type',
                      'value' => array( 'image', ),
                  ],
              ),
              array(
                  'param_name' => 'section_icon_font',
                  'heading' => __( 'Icon Font', 'htmegavc' ),
                  'type' => 'iconpicker',
                  'dependency' =>[
                      'element' => 'section_icon_type',
                      'value' => array( 'icon', ),
                  ],
              ),
              array(
                  'param_name' => 'section_icon_font_size',
                  'heading' => __( 'Icon Font Size', 'htmegavc' ),
                  'type' => 'textfield',
                  'dependency' =>[
                      'element' => 'section_icon_type',
                      'value' => array( 'icon', ),
                  ],
              ),
              array(
                  'param_name' => 'section_icon_font_color',
                  'heading' => __( 'Icon Font Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'dependency' =>[
                      'element' => 'section_icon_type',
                      'value' => array( 'icon', ),
                  ],
              ),

              // style 3
              array(
                  'param_name' => 'title_bg_image',
                  'heading' => __( 'Tile BG Image', 'htmegavc' ),
                  'type' => 'attach_image',
                  'description' => __( 'Upload an image, it will be animated in background of the texts', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '3', ),
                  ],
              ),
              array(
                  'param_name' => 'title_bg_image_size',
                  'heading' => __( 'Image Icon Size', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)). Default we use the original image.', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '3', ),
                  ],
              ),

              // style 5
              array(
                  'param_name' => 'section_bg_text',
                  'heading' => __( 'Section bg text', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Write your text, it will show in background of the section_title', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '5', ),
                  ],
              ),

              // style 6
              array(
                  'param_name' => 'title_border_color',
                  'heading' => __( 'Title Border color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '6', ),
                  ],
              ),
              array(
                  'param_name' => 'title_border_width',
                  'heading' => __( 'Title Border Width', 'htmegavc' ),
                  'type' => 'textfield',
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '6', ),
                  ],
              ),
              array(
                  'param_name' => 'title_border_height',
                  'heading' => __( 'Title Border Height', 'htmegavc' ),
                  'type' => 'textfield',
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '6', ),
                  ],
              ),

              // style 7
              array(
                  'param_name' => 'sub_title_border_color',
                  'heading' => __( 'Sub Title Border color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '7', ),
                  ],
              ),
              array(
                  'param_name' => 'sub_title_border_width',
                  'heading' => __( 'Sub Title Border Width', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' =>  __( 'Eg: 100px', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '7', ),
                  ],
              ),
              array(
                  'param_name' => 'sub_title_border_height',
                  'heading' => __( 'Sub Title Border Height', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' =>  __( 'Eg: 5px', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '7', ),
                  ],
              ),
              array(
                  "param_name" => "section_border_position",
                  "heading" => __("Section Border Position", 'htmegavc'),
                  "type" => "dropdown",
                  "default_set" => 'none',
                  'value' => [
                      __( 'None', 'htmegavc' )  =>  'none',
                      __( 'Left', 'htmegavc' )  =>  'left',
                      __( 'Right', 'htmegavc' )  =>  'right',
                  ],
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '7', ),
                  ],
              ),
              array(
                  'param_name' => 'section_border_color',
                  'heading' => __( 'Section Border color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'dependency' =>[
                      'element' => 'section_border_position',
                      'value' => array( 'left','right' ),
                  ],
              ),
              array(
                  'param_name' => 'section_border_width',
                  'heading' => __( 'Section Border Width', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' =>  __( 'Eg: 100px', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'section_border_position',
                      'value' => array( 'left','right' ),
                  ],
              ),
              array(
                  'param_name' => 'section_border_height',
                  'heading' => __( 'Section Border Height', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' =>  __( 'Eg: 5px', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'section_border_position',
                      'value' => array( 'left','right' ),
                  ],
              ),


              //Typography Tab
              array(
                  "param_name" => "package_typograpy",
                  "type" => "htmegavc_param_heading",
                  "text" => __("Title Typography","htmevavc"),
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Typography', 'htmevavc' ),
              ),
              array(
                'param_name' => 'title_typography',
                'type' => 'font_container',
                'group'  => __( 'Typography', 'htmegavc' ),
                'settings' => array(
                  'fields' => array(
                    'font_family',
                    'font_size',
                    'line_height',
                    'color',
                    'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmegavc' ),
                    'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
                    'color_description' => __( 'Select heading color.', 'htmegavc' ),
                  ),
                ),
              ),

              array(
                  "param_name" => "package_typograpy",
                  "type" => "htmegavc_param_heading",
                  "text" => __("Sub Title Typography","htmevavc"),
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Typography', 'htmevavc' ),
              ),
              array(
                'param_name' => 'sub_title_typography',
                'type' => 'font_container',
                'group'  => __( 'Typography', 'htmegavc' ),
                'settings' => array(
                  'fields' => array(
                    'font_family',
                    'font_size',
                    'line_height',
                    'color',
                    // 'text_align',
                    'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmegavc' ),
                    'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
                    'color_description' => __( 'Select icon color.', 'htmegavc' ),
                  ),
                ),
              ),


              array(
                  "type" => "htmegavc_param_heading",
                  "text" => __("Section BGText Typography","htmegavc"),
                  "param_name" => "package_typograpy",
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Typography', 'htmegavc' ),
              ),
              array(
                'param_name' => 'section_bg_text_typography',
                'type' => 'font_container',
                'group'  => __( 'Typography', 'htmegavc' ),
                'settings' => array(
                  'fields' => array(
                    'font_family',
                    'font_size',
                    'line_height',
                    'color',
                    'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmegavc' ),
                    'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
                    'color_description' => __( 'Select icon color.', 'htmegavc' ),
                  ),
                ),
              ),


              array(
                  'param_name' => 'custom_class',
                  'heading' => __( 'Extra class name', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Style this element differently - add a class name and refer to it in custom CSS.', 'htmegavc' ),
              ),
              array(
                "param_name" => "wrapper_css",
                "heading" => __( "Wrapper Styling", "htmegavc" ),
                "type" => "css_editor",
                'group'  => __( 'Wrapper Styling', 'htmegavc' ),
            ),
          )
      ) );
  }


}

// Finally initialize code
new Htmegavc_Section_Title();