<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Button{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_button', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_register_style( 'htmegavc_button', plugins_url('css/button.css', __FILE__) );
      wp_enqueue_style( 'htmegavc_button' );
    }

    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
            // Content
            'button_text' => __('Click Me', 'htmegavc'),
            'button_link' => '',
            'style' => 'normal', // normal,outline,button_with_icon
                'button_icon' => '',
                'button_icon_alignment' => '', // left,right
            'button_size' => 'medium', // m,md,lg,xl,xs
            'button_alignment' => 'center',
            'button_shadow_style' => '', // m,md,lg,xl,xs

            // Typography
            'button_text_typography' => '',
            'button_icon_typography' => '',

            // Styling
            'button_bg_type' => '', // normal, gradient
            'button_text_color' => '',
            'button_bg_color' => '',
            'button_bg_color1' => '',
            'button_bg_color2' => '',

            'button_padding' => '',

            'button_border_width' => '',
            'button_border_style' => '',
            'button_border_radius' => '',
            'button_border_color' => '',

            'button_icon_bg_color' => '',
            'button_icon_border_width' => '',
            'button_icon_border_type' => '',
            'button_icon_border_radius' => '',
            'button_icon_border_color' => '',

            'button_icon_margin' => '',
            'button_icon_padding' => '',

            // Hover
            'button_hover_bg_color' => '',
            'button_hover_text_color' => '',
            'button_hover_border_color' => '',
            'button_hover_effect' => '', // 0    -  14
            'button_icon_hover_bg_color' => '',
            'button_icon_hover_icon_color' => '',

            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));

        $unique_class =  uniqid('htmegavc_button_');
        $style_class = ' htmegavc-button-wrapper htmegavc-button-style-'. esc_attr($style);
        $wrapper_css_class = ' '. apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_button', $atts );
        $custom_class = ' ' . $custom_class;

        // shadow class
        $shadow_class = '';
        if($button_shadow_style != ''){
            $shadow_class = 'button-shadow button-shadow-'.$button_shadow_style;
        }

        // Typography
        $button_text_typography = htmegavc_combine_font_container($button_text_typography);
        $button_icon_typography = htmegavc_combine_font_container($button_icon_typography);


        if( $style == 'button_with_icon' ){
            $output = '';
            $output .= '<style>';
            $output .= "
                .$unique_class.htmegavc-btn--2{
                    $button_text_typography;
                    background-color:$button_bg_color;
                    color:$button_text_color;
                    border-width:$button_border_width;
                    border-style:$button_border_style;
                    border-radius:$button_border_radius;
                    border-color:$button_border_color;
                }
                .$unique_class.htmegavc-btn--2 span.button-arrow{
                    $button_icon_typography;
                    background-color:$button_icon_bg_color;
                    border-width:$button_icon_border_width;
                    border-radius:$button_icon_border_radius;
                    border-color:$button_icon_border_color;
                }
            ";
            $output .= '</style>';


            $output .= "<a class='htmegavc-btn--2 $unique_class $wrapper_css_class' href='#'>";
                $output .= "<span class='button-with-icon'>$button_text</span>";
                $output .= "<span class='button-arrow'>";
                    $output .= "<i class='$button_icon'></i>";
                $output .= " </span>";
            $output .= "</a>";                             
        } else {
            $output = '';
            $output .= '<style>';
            $output .= "
                .$unique_class.htmegavc-btn{
                    $button_text_typography;
                    background-color:$button_bg_color;
                    color:$button_text_color;
                    padding:$button_padding;
                    border-width:$button_border_width;
                    border-style:$button_border_style;
                    border-radius:$button_border_radius;
                    border-color:$button_border_color;
                }

                /* hover */
                .$unique_class.htmegavc-btn:hover{background-color:$button_hover_bg_color;}
                .$unique_class.htmegavc-btn:hover{color:$button_hover_text_color;}
                .$unique_class.htmegavc-btn:hover{border-color:$button_hover_border_color;}
                
                .$unique_class.button-effect--3::after{background-color:$button_bg_color;}
                .$unique_class.button-effect--3.htmegavc-btn:hover{background-color:transparent;}
                .$unique_class.button-effect--3::before{border-color:$button_hover_bg_color;}
                .$unique_class.button-effect--3:hover::after{background-color:$button_hover_bg_color;}

                .$unique_class.button-effect--10::before,
                .$unique_class.button-effect--11::before{background-color:$button_bg_color;}

                .$unique_class.button-effect--11:hvoer::before{border-color:$button_bg_color;}


                /* outline */
                .$unique_class.htmegavc-button-style-outline,
                .$unique_class.htmegavc-button-style-outline:hover{
                    background-color:transparent;
                }
                .$unique_class.htmegavc-button-style-outline::before{
                    border-color: $button_bg_color;
                }
                .$unique_class.htmegavc-button-style-outline::after{
                    background-color: $button_bg_color;
                }
                .$unique_class.htmegavc-button-style-outline:hover::before{
                    border-color: $button_hover_bg_color;
                }
                .$unique_class.htmegavc-button-style-outline:hover::after{
                    background-color: $button_hover_bg_color;
                }
            ";

            if($button_bg_type == 'gradient'){
                $output .= "
                    .$unique_class.background-type-gradient{
                    background-image: -webkit-linear-gradient(0, $button_bg_color1, $button_bg_color2);
                    background-image: -o-linear-gradient(0, $button_bg_color1, $button_bg_color2);
                    background-image: linear-gradient(0, $button_bg_color1, $button_bg_color2);
                }
                .$unique_class.background-type-gradient::before{
                    background-image: -webkit-linear-gradient(0, $button_bg_color2, $button_bg_color1);
                    background-image: -o-linear-gradient(0, $button_bg_color2, $button_bg_color1);
                    background-image: linear-gradient(0, $button_bg_color2, $button_bg_color1);
                }
                ";
            }


            $output .= '</style>';

            $output .= "<div class='htmegavc-btn-wrapper text-$button_alignment $wrapper_css_class'><a class='htmegavc-btn button--$button_size button-effect--$button_hover_effect $unique_class $style_class $custom_class background-type-$button_bg_type $shadow_class' href='#'>";
            $output .= "<span>$button_text</span>";
            $output .= "</a></div>";
        }

    	return $output;
  }

  public function integrateWithVC() {
  
      /*
      Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

      More info: http://kb.wpbakery.com/index.php?title=Vc_map
      */
      vc_map( array(
          "name" => __("HT Button", 'htmegavc'),
          "description" => __("Add Button to your page", 'htmegavc'),
          "base" => "htmegavc_button",
          "class" => "",
          "controls" => "full",
          "icon" => 'htmegvc_button_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
          "category" => __('HT Mega Addons', 'htmegavc'),
          "params" => array(
              array(
                  'param_name' => 'button_text',
                  'heading' => __( 'Button Text', 'htmegavc' ),
                  'type' => 'textfield',
              ),
              array(
                  'param_name' => 'button_link',
                  'heading' => __( 'Link to button ', 'htmegavc' ),
                  'type' => 'vc_link',
                  'value' => 'url:#',
              ),
              array(
                "param_name" => "style",
                "heading" => __("Style", 'htmegavc'),
                "type" => "dropdown",
                "default_set" => 'normal',
                'value' => [
                    __( 'Normal', 'htmegavc' )  =>  'normal',
                    __( 'Outline', 'htmegavc' )  =>  'outline',
                    __( 'Button with icon', 'htmegavc' )  =>  'button_with_icon',
                ],
              ),
              array(
                  'param_name' => 'button_icon',
                  'heading' => __( 'Button Icon', 'htmegavc' ),
                  'type' => 'iconpicker',
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'button_with_icon' ),
                  ],
              ),
              array(
                "param_name" => "button_icon_alignment",
                "heading" => __("Button Icon alignment", 'htmegavc'),
                "type" => "dropdown",
                "default_set" => 'right',
                'value' => [
                    __( 'Left', 'htmegavc' )  =>  'left',
                    __( 'Right', 'htmegavc' )  =>  'right',
                ],
                'dependency' =>[
                    'element' => 'style',
                    'value' => array( 'button_with_icon' ),
                ],
              ),
              array(
                "param_name" => "button_size",
                "heading" => __("Button Size", 'htmegavc'),
                "type" => "dropdown",
                "default_set" => 'md',
                'value' => [
                  __( 'None', 'htmegavc' )    => 'none',
                  __( 'Medium', 'htmegavc' )    => 'medium',
                  __( 'Standard', 'htmegavc' )    => 'standard',
                  __( 'Large', 'htmegavc' )    => 'large',
                  __( 'Small', 'htmegavc' )    => 'small',
                  __( 'Extra Small', 'htmegavc' )    => 'extra-small',
                  __( 'Extra Large', 'htmegavc' )    => 'extra-large',
                ],
              ),
              array(
                  'param_name' => 'button_alignment',
                  'heading' => __( 'Button Alignment', 'my_text_domain' ),
                  'type' => 'dropdown',
                  'value' => [
                      __( 'Center', 'my_text_domain' )  =>  'center',
                      __( 'Left', 'my_text_domain' )  =>  'left',
                      __( 'Right', 'my_text_domain' )  =>  'right',
                      __( 'Justify', 'my_text_domain' )  =>  'justify',
                  ],
              ),
              array(
                "param_name" => "button_shadow_style",
                "heading" => __("Button Shadow Style", 'htmegavc'),
                "type" => "dropdown",
                "default_set" => 'md',
                'value' => [
                  __( 'None', 'htmegavc' )    => 'none',
                  __( 'One', 'htmegavc' )    => '1',
                  __( 'Two', 'htmegavc' )    => '2',
                  __( 'Three', 'htmegavc' )    => '3',
                  __( 'Four', 'htmegavc' )    => '4',
                  __( 'Five', 'htmegavc' )    => '5',
                  __( 'Six', 'htmegavc' )    => '6',
                ],
              ),

              //Typography Tab
              array(
                  "type" => "htmegavc_param_heading",
                  "text" => __("Button Text Typography","htmegavc"),
                  "param_name" => "package_typograpy",
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Typography', 'htmegavc' ),
              ),
              array(
                'param_name' => 'button_text_typography',
                'type' => 'font_container',
                'group'  => __( 'Typography', 'htmegavc' ),
                'settings' => array(
                  'fields' => array(
                    'font_family',
                    'font_size',
                    'line_height',
                    'color',
                    'text_align',
                    'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmegavc' ),
                    'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
                    'color_description' => __( 'Select heading color.', 'htmegavc' ),
                  ),
                ),
              ),
              array(
                  "type" => "htmegavc_param_heading",
                  "text" => __("Button Icon Typography","htmegavc"),
                  "param_name" => "package_typograpy",
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Typography', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'button_with_icon' ),
                  ],
              ),
              array(
                'param_name' => 'button_icon_typography',
                'type' => 'font_container',
                'group'  => __( 'Typography', 'htmegavc' ),
                'settings' => array(
                  'fields' => array(
                    'font_family',
                    'font_size',
                    'line_height',
                    'color',
                    'text_align',
                    'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmegavc' ),
                    'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
                    'color_description' => __( 'Select icon color.', 'htmegavc' ),
                  ),
                ),
                'dependency' =>[
                    'element' => 'style',
                    'value' => array( 'button_with_icon' ),
                ],
              ),

              // Styling Tab
              array(
                "param_name" => "button_bg_type",
                "heading" => __("Button BG Type", 'htmegavc'),
                "type" => "dropdown",
                'group'  => __( 'Styling', 'htmegavc' ),
                "default_set" => 'normal',
                'value' => [
                    __( 'Normal', 'htmegavc' )  =>  'normal',
                    __( 'Gradient', 'htmegavc' )  =>  'gradient',
                ],
              ),
              array(
                  'param_name' => 'button_bg_color',
                  'heading' => __( 'Button BG color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'button_bg_type',
                      'value' => array( 'normal' ),
                  ],
              ),
              array(
                  'param_name' => 'button_text_color',
                  'heading' => __( 'Button Text color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'button_bg_type',
                      'value' => array( 'normal' ),
                  ],
              ),
              array(
                  'param_name' => 'button_bg_color1',
                  'heading' => __( 'Gradient BG color 1', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'button_bg_type',
                      'value' => array( 'gradient' ),
                  ],
                  'edit_field_class' => 'vc_column vc_col-sm-6',
              ),
              array(
                  'param_name' => 'button_bg_color2',
                  'heading' => __( 'Gradient BG color 2', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'button_bg_type',
                      'value' => array( 'gradient' ),
                  ],
                  'edit_field_class' => 'vc_column vc_col-sm-6',
              ),
              array(
                  'param_name' => 'button_icon_bg_color',
                  'heading' => __( 'Button icon BG color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'button_with_icon' ),
                  ],
              ),
              array(
                  'param_name' => 'button_padding',
                  'heading' => __( 'Button Padding', 'my_text_domain' ),
                  'type' => 'textfield',
                  'description' => __( 'The CSS padding. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'my_text_domain' ),
                  'group'  => __( 'Styling', 'my_text_domain' ),
              ),
              array(
                "param_name" => "button_border_width",
                "heading" => __("Border Width", 'htmegavc'),
                "type" => "dropdown",
                'group'  => __( 'Styling', 'htmegavc' ),
                "default_set" => '',
                'value' => [
                  __('None', 'htmegavc') => 'none',
                  __('0px', 'htmegavc') => '0px',
                  __('1px', 'htmegavc') => '1px',
                  __('2px', 'htmegavc') => '2px',
                  __('3px', 'htmegavc') => '3px',
                  __('4px', 'htmegavc') => '4px',
                  __('5px', 'htmegavc') => '5px',
                ],
                'dependency' =>[
                    'element' => 'style',
                    'value' => array( 'normal', 'button_with_icon' ),
                ],
              ),
              array(
                "param_name" => "button_border_style",
                "heading" => __("Border Style", 'htmegavc'),
                "type" => "dropdown",
                'group'  => __( 'Styling', 'htmegavc' ),
                "default_set" => '',
                'value' => [
                  __('None', 'htmegavc') => '',
                  __('Solid', 'htmegavc') => 'solid',
                  __('dotted', 'htmegavc') => 'dotted',
                  __('dashed', 'htmegavc') => 'dashed',
                  __('none', 'htmegavc') => 'none',
                  __('hidden', 'htmegavc') => 'hidden',
                  __('double', 'htmegavc') => 'double',
                  __('groove', 'htmegavc') => 'groove',
                  __('ridge', 'htmegavc') => 'ridge',
                  __('inset', 'htmegavc') => 'inset',
                  __('outset', 'htmegavc') => 'outset',
                  __('initial', 'htmegavc') => 'initial',
                  __('inherit', 'htmegavc') => 'inherit',
                ],
                'dependency' =>[
                    'element' => 'style',
                    'value' => array( 'normal', 'button_with_icon' ),
                ],
              ),
              array(
                  'param_name' => 'button_border_radius',
                  'heading' => __( 'Border radius css', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Example: 5px', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'normal', 'button_with_icon' ),
                  ],
              ),
              array(
                  'param_name' => 'button_border_color',
                  'heading' => __( 'Border Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'normal', 'button_with_icon' ),
                  ],
              ),
              array(
                "param_name" => "button_icon_border_width",
                "heading" => __("Border Width", 'htmegavc'),
                "type" => "dropdown",
                'group'  => __( 'Styling', 'htmegavc' ),
                "default_set" => '',
                'value' => [
                  __('None', 'htmegavc') => 'none',
                  __('0px', 'htmegavc') => '0px',
                  __('1px', 'htmegavc') => '1px',
                  __('2px', 'htmegavc') => '2px',
                  __('3px', 'htmegavc') => '3px',
                  __('4px', 'htmegavc') => '4px',
                  __('5px', 'htmegavc') => '5px',
                ],
                'dependency' =>[
                    'element' => 'style',
                    'value' => array( 'button_with_icon' ),
                ],
              ),
              array(
                "param_name" => "button_icon_border_type",
                "heading" => __("Border Style", 'htmegavc'),
                "type" => "dropdown",
                'group'  => __( 'Styling', 'htmegavc' ),
                "default_set" => '',
                'value' => [
                  __('', 'htmegavc') => '',
                  __('Solid', 'htmegavc') => 'solid',
                  __('dotted', 'htmegavc') => 'dotted',
                  __('dashed', 'htmegavc') => 'dashed',
                  __('none', 'htmegavc') => 'none',
                  __('hidden', 'htmegavc') => 'hidden',
                  __('double', 'htmegavc') => 'double',
                  __('groove', 'htmegavc') => 'groove',
                  __('ridge', 'htmegavc') => 'ridge',
                  __('inset', 'htmegavc') => 'inset',
                  __('outset', 'htmegavc') => 'outset',
                  __('initial', 'htmegavc') => 'initial',
                  __('inherit', 'htmegavc') => 'inherit',
                ],
                'dependency' =>[
                    'element' => 'style',
                    'value' => array( 'button_with_icon' ),
                ],
              ),
              array(
                  'param_name' => 'button_icon_border_radius',
                  'heading' => __( 'Border radius css', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Example: 5px', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'button_with_icon' ),
                  ],
              ),
              array(
                  'param_name' => 'button_icon_border_color',
                  'heading' => __( 'Border Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'button_with_icon' ),
                  ],
              ),
              // styling tab end


              // hvoer tab
              array(
                  'param_name' => 'button_hover_bg_color',
                  'heading' => __( 'Button Hover Bg Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Hover', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'normal', 'outline' ),
                  ],
              ),
              array(
                  'param_name' => 'button_hover_text_color',
                  'heading' => __( 'Button Hover Text Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Hover', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'button_hover_border_color',
                  'heading' => __( 'Button Hover Border Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Hover', 'htmegavc' ),
              ),
              array(
                "param_name" => "button_hover_effect",
                "heading" => __("Hover Effect", 'htmegavc'),
                "type" => "dropdown",
                'group'  => __( 'Hover', 'htmegavc' ),
                "default_set" => '',
                'value' => [
                      __( 'None', 'htmegavc' ) =>  '0',
                      __( 'Effect 1', 'htmegavc' ) =>  '1',
                      __( 'Effect 2', 'htmegavc' ) =>  '2',
                      __( 'Effect 3', 'htmegavc' ) =>  '3',
                      __( 'Effect 4', 'htmegavc' ) =>  '4',
                      __( 'Effect 5', 'htmegavc' ) =>  '5',
                      __( 'Effect 6', 'htmegavc' ) =>  '6',
                      __( 'Effect 7', 'htmegavc' ) =>  '7',
                      __( 'Effect 8', 'htmegavc' ) =>  '8',
                      __( 'Effect 9', 'htmegavc' ) =>  '9',
                      __( 'Effect 10', 'htmegavc' )  => '10',
                      __( 'Effect 11', 'htmegavc' )  => '11',
                      __( 'Effect 12', 'htmegavc' )  => '12',
                      __( 'Effect 13', 'htmegavc' )  => '13',
                      __( 'Effect 14', 'htmegavc' )  => '14',
                      __( 'Effect 15', 'htmegavc' )  => '15',
                      __( 'Effect 16', 'htmegavc' )  => '16',
                      __( 'Effect 17', 'htmegavc' )  => '17',
                ],
              ),
              array(
                  'param_name' => 'button_icon_hover_bg_color',
                  'heading' => __( 'Hover Icon Bg Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Hover', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'button_with_icon' ),
                  ],
              ),
              array(
                  'param_name' => 'button_icon_hover_icon_color',
                  'heading' => __( 'Hover Icon Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Hover', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'button_with_icon' ),
                  ],
              ),
              // hvoer tab end


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
new Htmegavc_Button();