<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Contact_Form_Seven{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_contact_form_seven', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_register_style( 'htmegavc_contact_form_seven', plugins_url('css/contact-form-seven.css', __FILE__) );
      wp_enqueue_style( 'htmegavc_contact_form_seven' );
    }
 
    public function integrateWithVC() {
 
        /*
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("HT Contact Form Seven", 'htmegavc'),
            "description" => __("Add Contact Form Seven to your page", 'htmegavc'),
            "base" => "htmegavc_contact_form_seven",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegvc_contact_form_seven_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
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
                      __( 'Style Six', 'htmegavc' )  =>  '6',
                      __( 'Style Seven', 'htmegavc' )  =>  '7',
                  ],
                ),
                array(
                  "param_name" => "form_id",
                  "heading" => __("Contact Form", 'htmegavc'),
                  "type" => "dropdown",
                  "default_set" => '1',
                  'value' => htmegavc_contact_form_seven(),
                ),
                array(
                    'param_name' => 'icon_before_title',
                    'heading' => __( 'Icon Before Title', 'htmegavc' ),
                    'type' => 'attach_image',
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '6' ),
                    ],
                ),
                array(
                    'param_name' => 'title',
                    'heading' => __( 'Section Title', 'htmegavc' ),
                    'type' => 'textfield',
                    'value' => __('Get In Touch', 'htmegavc'),
                ),
                array(
                    'param_name' => 'subtitle',
                    'heading' => __( 'Sub Title', 'htmegavc' ),
                    'type' => 'textfield',
                    'value' => __('Lorem ipsum dolor sit amet, consectetur adipisicing elit sed do.', 'htmegavc'),
                ),


                //Typography Tab
                array(
                    "type" => "htmegavc_param_heading",
                    "text" => __("Title Typography","htmegavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Typography', 'htmegavc' ),
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
                      'text_align',
                      'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmegavc' ),
                      'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
                      'color_description' => __( 'Select heading color.', 'htmegavc' ),
                    ),
                  ),
                ),
                array(
                    "type" => "htmegavc_param_heading",
                    "text" => __("Sub Title Typography","htmegavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Typography', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'subtitle_typography',
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
                    "text" => __("Input box Typography","htmegavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Typography', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'input_box_typography',
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
                    "type" => "htmegavc_param_heading",
                    "text" => __("Textarea box Typography","htmegavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Typography', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'textarea_box_typography',
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
                    "type" => "htmegavc_param_heading",
                    "text" => __("Label Typography","htmegavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Typography', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'label_typography',
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
                    "type" => "htmegavc_param_heading",
                    "text" => __("Submit Typography","htmegavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Typography', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'submit_typography',
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

                // Styling Tab
                array(
                    "type" => "htmegavc_param_heading",
                    "text" => __("Input Box Styling","htmegavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'input_box_height',
                    'heading' => __( 'Input Box Height', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'Example: 60px', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'input_box_bg_color',
                    'heading' => __( 'Input Box BG Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'input_box_border_color',
                    'heading' => __( 'Input Box Border Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'input_box_focus_border_color',
                    'heading' => __( 'Input Box Focus Border Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'input_box_placeholder_color',
                    'heading' => __( 'Input Box Placeholder Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'input_box_border_radius',
                    'heading' => __( 'Input Box Border Radious', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'Example: 5px', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'input_box_padding',
                    'heading' => __( 'Input Box Padding', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS padding of each input box. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'input_box_margin',
                    'heading' => __( 'Textarea Box Margin', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS margin of each input box. Example: 18px 0, which stand for margin-top and margin-bottom is 18px', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),


                array(
                    "type" => "htmegavc_param_heading",
                    "text" => __("Textarea Box Styling","htmegavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'textarea_box_height',
                    'heading' => __( 'Textarea Box Height', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'Example: 60px', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'textarea_box_bg_color',
                    'heading' => __( 'Textarea Box BG color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'textarea_box_border_color',
                    'heading' => __( 'Textarea Box Border color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'textarea_box_focus_border_color',
                    'heading' => __( 'Textarea Box Foucs Border Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'textarea_box_border_radius',
                    'heading' => __( 'Textarea Box Border Radius', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'Example: 5px', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'textarea_box_padding',
                    'heading' => __( 'Textarea Box Padding', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS padding of each input box. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'textarea_box_margin',
                    'heading' => __( 'Textarea Box Margin', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS margin of each input box. Example: 18px 0, which stand for margin-top and margin-bottom is 18px', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),


                array(
                    "type" => "htmegavc_param_heading",
                    "text" => __("Submit Button Styling","htmegavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'submit_height',
                    'heading' => __( 'Submit Button Height', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'Example: 60px', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'submit_bg_color',
                    'heading' => __( 'Submit Button BG Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'submit_border_color',
                    'heading' => __( 'Submit Button Border Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'submit_border_radius',
                    'heading' => __( 'Submit Button Border Radious', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'Example: 5px', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'submit_box_shadow',
                  'heading' => __( 'Button Box Shadow', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Example value: 0 0 10px rgba(0, 0, 0, 0.1) <a target="_blank" href="https://www.w3schools.com/cssref/css3_pr_box-shadow.asp">Learn More</a>', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'submit_padding',
                    'heading' => __( 'Submit Button Padding', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS padding of each input box. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'submit_margin',
                    'heading' => __( 'Submit Button Margin', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'The CSS margin of each input box. Example: 18px 0, which stand for margin-top and margin-bottom is 18px', 'htmegavc' ),
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
                    'param_name' => 'submit_hover_bg_color',
                    'heading' => __( 'Submit Button Hover BG Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'submit_hover_text_color',
                    'heading' => __( 'Submit Button Hover Text Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'submit_hover_border_color',
                    'heading' => __( 'Submit Button Hover Border Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                // Styling tab end


                array(
                    'param_name' => 'custom_class',
                    'heading' => __( 'Extra Class Name', 'htmegavc' ),
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


    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
            'style' => '1',
            'form_id' => '',
            'icon_before_title' => '',
            'title' => __('Get In Touch', 'htmegavc'),
            'subtitle' => __('Lorem ipsum dolor sit amet, consectetur adipisicing elit sed do.', 'htmegavc'),

            // Typography
            'title_typography' => '',
            'subtitle_typography' => '',
            'input_box_typography' => '',
            'textarea_box_typography' => '',
            'label_typography' => '',
            'submit_typography' => '',

            /* Styling */
            'input_box_height' => '',
            'input_box_bg_color' => '',
            'input_box_border_color' => '',
            'input_box_focus_border_color' => '',
            'input_box_placeholder_color' => '',
            'input_box_border_radius' => '',
            'input_box_padding' => '',
            'input_box_margin' => '',

            'textarea_box_height' => '',
            'textarea_box_bg_color' => '',
            'textarea_box_border_color' => '',
            'textarea_box_focus_border_color' => '',
            'textarea_box_border_radius' => '',
            'textarea_box_padding' => '',
            'textarea_box_margin' => '',


            'submit_height' => '',
            'submit_bg_color' => '',
            'submit_border_color' => '',
            'submit_border_radius' => '',
            'submit_box_shadow' => '',
            'submit_padding' => '',
            'submit_margin' => '',

            // hover
            'submit_hover_bg_color' => '',
            'submit_hover_text_color' => '',
            'submit_hover_border_color' => '',

            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));

        $unique_class =  uniqid('htmegavc_cf7_');
        $style_class = ' htmegavc-form_wrapper htmegavc-form-style-'. esc_attr($style);
        $wrapper_css_class = ' '. apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_testimonial', $atts );
        $custom_class = ' ' . $custom_class;

        // Typography
        $title_typography = htmegavc_combine_font_container($title_typography);
        $subtitle_typography = htmegavc_combine_font_container($subtitle_typography);
        $input_box_typography = htmegavc_combine_font_container($input_box_typography);
        $textarea_box_typography = htmegavc_combine_font_container($textarea_box_typography);
        $label_typography = htmegavc_combine_font_container($label_typography);
        $submit_typography = htmegavc_combine_font_container($submit_typography);

        $output = '';
        $output .= '<div class="'. esc_attr($unique_class.$style_class.$wrapper_css_class.$custom_class ) .'">';

        $output .= '<style>';
        $output .= "
            .$unique_class .wpcf7-form .wpcf7-form-control-wrap input[type*='text'],
            .$unique_class .wpcf7-form .wpcf7-form-control-wrap input[type*='email'],
            .$unique_class .wpcf7-form .wpcf7-form-control-wrap input[type*='url'],
            .$unique_class .wpcf7-form .wpcf7-form-control-wrap input[type*='number'],
            .$unique_class .wpcf7-form .wpcf7-form-control-wrap input[type*='tel'],
            .$unique_class .wpcf7-form .wpcf7-form-control-wrap input[type*='date'],
            .$unique_class .wpcf7-form .wpcf7-form-control-wrap .wpcf7-select{ 
                $input_box_typography;
                height: $input_box_height;
                background-color: $input_box_bg_color;
                border-color: $input_box_border_color;
                border-radius: $input_box_border_radius;
                padding: $input_box_padding;
                margin: $input_box_margin;
            }
            .$unique_class .wpcf7-form .wpcf7-form-control-wrap textarea{ 
                $textarea_box_typography;
                height: $textarea_box_height;
                background-color: $textarea_box_bg_color;
                border-color: $textarea_box_border_color;
                border-radius: $textarea_box_border_radius;
                padding: $textarea_box_padding;
                margin: $textarea_box_margin;
            }
            
            /*label*/
            .$unique_class .wpcf7-form label{ $label_typography }

            /*submit*/
            .$unique_class .wpcf7-form .wpcf7-submit{ 
                $submit_typography;
                height: $submit_height;
                background-color: $submit_bg_color;
                border-color: $submit_border_color;
                border-radius: $submit_border_radius;
                box-shadow: $submit_box_shadow;
                padding: $submit_padding;
                margin: $submit_margin;
            }

            /*hover*/
            .$unique_class .wpcf7-form .wpcf7-submit:hover{
                background-color: $submit_hover_bg_color;
                color: $submit_hover_text_color;
                border-color: $submit_hover_border_color;
            }

            /*focus*/
            .$unique_class .wpcf7-form .wpcf7-form-control-wrap input:focus{
                border-color: $input_box_focus_border_color;
            }
            .$unique_class .wpcf7-form .wpcf7-form-control-wrap textarea:focus{
                border-color: $textarea_box_focus_border_color;
            }

            /*placeholder*/
            .$unique_class .wpcf7-form .wpcf7-form-control-wrap input::placeholder,
            .$unique_class .wpcf7-form .wpcf7-form-control-wrap textarea::placeholder{
                color: $input_box_placeholder_color;
            }
        ";

        if(!$icon_before_title){
        	$output .= "
				.$unique_class .htmegavc-ct-title {
					padding-left: 15px;
				}
        	";
        }

        $output .= '</style>';

        if ($title || $subtitle){
            $output .= '<div class="htmegavc-ct-title text-center">';
                if($style == '6'){
                    $output .= wp_get_attachment_image( $icon_before_title, 'large');
                }
                $output .= $title ? '<h2 style='. $title_typography .'>'. $title .'</h2>' : '';
                $output .= $subtitle ? '<p style='. $subtitle_typography .'>'. $subtitle .'</p>' : '';
            $output .= '</div>';
        }


        if( !empty($form_id) ){
            $output .= do_shortcode( '[contact-form-7  id="'.$form_id.'"]' ); 
        }else{
           $output .= '<div class="htmegavc-form_no_select">' .__('Please Select contact form.','htmegavc'). '</div>';
        }
  ?>

  <?php
    $output .= '</div><!--/.form_wrapper-->';
    return $output;
  }

}

// Finally initialize code
new Htmegavc_Contact_Form_Seven();