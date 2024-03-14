<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Mailchimp_For_WP{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_mailchimp_for_wp', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_register_style( 'htmegavc_mailchimp_for_wp', plugins_url('css/mailchimp-for-wp.css', __FILE__) );
      wp_enqueue_style( 'htmegavc_mailchimp_for_wp' );
    }


    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
            'style' => '1',
            'form_id' => '',

            // Typography
            'email_box_typography' => '',
            'button_typography' => '',

            /* Styling */
            'email_box_height' => '',
            'email_box_bg_color' => '',
            'email_box_border_color' => '',
            'email_box_border_radius' => '',
            'email_box_placeholder_color' => '',
            'email_box_margin' => '',
            'email_box_padding' => '',

            'button_height' => '',
            'button_bg_color' => '',
            'button_border_color' => '',
            'button_border_radius' => '',
            'button_margin' => '',
            'button_padding' => '',

            // hover
            'button_hover_bg_color' => '',
            'button_hover_text_color' => '',
            'button_hover_border_color' => '',

            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));

        $unique_class =  uniqid('htmegavc_cf7_');
        $style_class = ' htmegavc-newsletter-wrapper htmegavc-newsletter-style-'. esc_attr($style);
        $wrapper_css_class = ' '. apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_testimonial', $atts );
        $custom_class = ' ' . $custom_class;

        // Typography
        $email_box_typography = htmegavc_combine_font_container($email_box_typography);
        $button_typography = htmegavc_combine_font_container($button_typography);

        $output = '';
        $output .= '<div class="'. esc_attr($unique_class.$style_class.$wrapper_css_class.$custom_class ) .'">';

        $output .= '<style>';
        $output .= "
            .$unique_class .htmegavc-input-box input[type*='text'],
            .$unique_class .htmegavc-input-box input[type*='email']{ 
                $email_box_typography;
                height: $email_box_height;
                background-color: $email_box_bg_color;
                border-color: $email_box_border_color;
                border-radius: $email_box_border_radius;
                padding: $email_box_padding;
                margin: $email_box_margin;
            }

            /*placeholder*/
            .$unique_class input[type*='email']::placeholder{
                color: $email_box_placeholder_color;
            }

            /*button*/
            .$unique_class .htmegavc-input-box button{ 
                $button_typography;
                height: $button_height;
                background-color: $button_bg_color;
                border-color: $button_border_color;
                border-radius: $button_border_radius;
                padding: $button_padding;
                margin: $button_margin;
            }

            /*hover*/
            .$unique_class .htmegavc-input-box button:hover{
                background-color: $button_hover_bg_color;
                color: $button_hover_text_color;
                border-color: $button_hover_border_color;
            }


        ";
        $output .= '</style>';

        if( !empty($form_id) ){
            $output .= do_shortcode( '[mc4wp_form  id="'.$form_id.'"]' );
        }else{
           $output .= '<div class="htmegavc-form_no_select">' .__('Please Select newsletter form.','htmegavc'). '</div>';
        }
  ?>

  <?php
    $output .= '</div><!--/.form_wrapper-->';
    return $output;
  }



  public function integrateWithVC() {
  
      /*
      Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

      More info: http://kb.wpbakery.com/index.php?title=Vc_map
      */
      vc_map( array(
          "name" => __("Mailchimp for wp", 'htmegavc'),
          "description" => __("Add Subscribe form to your page", 'htmegavc'),
          "base" => "htmegavc_mailchimp_for_wp",
          "class" => "",
          "controls" => "full",
          "icon" => 'htmegvc_mailchimp_for_wp_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
          "category" => __('HT Mega Addons', 'htmegavc'),
          "params" => array(
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
                "param_name" => "form_id",
                "heading" => __("Mailchimp form id", 'htmegavc'),
                "type" => "textfield",
                "default_set" => '1',
                'description' => __( 'To see your id <a href="admin.php?page=mailchimp-for-wp-forms" target="_blank"> Click here </a>', 'htmegavc' ),
              ),


              //Typography Tab
              array(
                  "type" => "htmegavc_param_heading",
                  "text" => __("Email Box Typography","htmegavc"),
                  "param_name" => "package_typograpy",
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Typography', 'htmegavc' ),
              ),
              array(
                'param_name' => 'email_box_typography',
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
                  "text" => __("Button Typography","htmegavc"),
                  "param_name" => "package_typograpy",
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Typography', 'htmegavc' ),
              ),
              array(
                'param_name' => 'button_typography',
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

              // Styling Tab
              array(
                  "type" => "htmegavc_param_heading",
                  "text" => __("Email Box Styling","htmegavc"),
                  "param_name" => "package_typograpy",
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'email_box_height',
                  'heading' => __( 'Email box height', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Example: 60px', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'email_box_bg_color',
                  'heading' => __( 'Email Box BG color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'email_box_border_color',
                  'heading' => __( 'Email Box Border color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'email_box_placeholder_color',
                  'heading' => __( 'Email Box Placeholder Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'email_box_border_radius',
                  'heading' => __( 'Email Box Border radius', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Example: 5px', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'email_box_padding',
                  'heading' => __( 'Email Box Padding', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'The CSS padding of email box. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'email_box_margin',
                  'heading' => __( 'Email Box Margin', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'The CSS margin of email box. Example: 18px 0, which stand for margin-top and margin-bottom is 18px', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),


              array(
                  "type" => "htmegavc_param_heading",
                  "text" => __("Button Styling","htmegavc"),
                  "param_name" => "package_typograpy",
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'button_height',
                  'heading' => __( 'Button Height', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Example: 60px', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'button_bg_color',
                  'heading' => __( 'Button BG Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'button_border_color',
                  'heading' => __( 'Button Border Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'button_border_radius',
                  'heading' => __( 'Button Border Radious', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Example: 5px', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'button_padding',
                  'heading' => __( 'Button Padding', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'The CSS padding of button. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'button_margin',
                  'heading' => __( 'Button Margin', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'The CSS margin of button. Example: 18px 0, which stand for margin-top and margin-bottom is 18px', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),


              array(
                  "type" => "htmegavc_param_heading",
                  "text" => __("Hover Styling","htmegavc"),
                  "param_name" => "package_typograpy",
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'button_hover_bg_color',
                  'heading' => __( 'Button hover BG color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'button_hover_text_color',
                  'heading' => __( 'Button hover text color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'button_hover_border_color',
                  'heading' => __( 'Button hover border color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              // Styling tab end


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
new Htmegavc_Mailchimp_For_WP();