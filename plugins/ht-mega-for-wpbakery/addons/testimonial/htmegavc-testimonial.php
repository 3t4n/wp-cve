<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Testimonial{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_testimonial', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {

      wp_register_script( 'slick', HTMEGAVC_LIBS_URI . '/slick-slider/slick.min.js', '', '', '');
      wp_enqueue_script( 'slick');

      wp_register_style( 'slick',  HTMEGAVC_LIBS_URI . '/slick-slider/slick.min.css' );
      wp_enqueue_style( 'slick' );

      wp_register_script( 'htmegavc-testimonial-active', plugins_url('js/testimonial-active.js', __FILE__), array('jquery'), '', true );
      wp_enqueue_script( 'htmegavc-testimonial-active');
      
      wp_register_style( 'htmegavc_testimonial', plugins_url('css/testimonial.css', __FILE__) );
      wp_enqueue_style( 'htmegavc_testimonial' );


    }
 
    public function integrateWithVC() {
 
        /*
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("HT Testimonial", 'htmegavc'),
            "description" => __("Add Testimonial to your page", 'htmegavc'),
            "base" => "htmegavc_testimonial",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegvc_testimonial_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
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
                      __( 'Style Eight', 'htmegavc' )  =>  '8',
                      __( 'Style Nine', 'htmegavc' )  =>  '9',
                  ],
                ),
                array(
                    'param_name' => 'testimonial_list',
                    "heading" => __("Testimonial List", 'text_domainn'),
                    'type' => 'param_group',
                    'value' => urlencode( json_encode (array(
                        array(
                            'name'                => 'Peter Rose',
                            'designation'         => 'Marketer',
                            'description'         => 'Lorem ipsum dolor sit amet, conse cteturlol adipisicing elit, sed do eiusmod tem porlop incididunt ut labore et dolore. ',
                        ),
                        array(
                          'name'                => 'Json Rose',
                          'designation'         => 'Marketer',
                          'description'         => 'Lorem ipsum dolor sit amet, conse cteturlol adipisicing elit, sed do eiusmod tem porlop incididunt ut labore et dolore. ',
                        ),
                        array(
                          'name'                => 'Api Rose',
                          'designation'         => 'Marketer',
                          'description'         => 'Lorem ipsum dolor sit amet, conse cteturlol adipisicing elit, sed do eiusmod tem porlop incididunt ut labore et dolore. ',
                        ),
                     ))),
                    'params' => array(
                       array(
                           'param_name' => 'attachement_id',
                           'heading' => __( 'Client Image', 'htmegavc' ),
                           'type' => 'attach_image',
                       ),
                       array(
                           'param_name' => 'name',
                           'heading' => __( 'Name', 'htmegavc' ),
                           'type' => 'textfield',
                       ),
                       array(
                           'param_name' => 'designation',
                           'heading' => __( 'Designation', 'htmegavc' ),
                           'type' => 'textfield',
                       ),
                       array(
                           'param_name' => 'description',
                           'heading' => __( 'Description', 'htmegavc' ),
                           'type' => 'textarea',
                       ),
                    )
                ),



                // Styling Tab
                array(
                    'param_name' => 'shape_image',
                    'heading' => __( 'Shape Image', 'htmegavc' ),
                    'type' => 'attach_image',
                    'description' => '',
                    'group'  => __( 'Styling', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '1', '5' ),
                    ],
                ),
                array(
                    'param_name' => 'item_bg_color',
                    'heading' => __( 'Item Bg Colog', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '9' ),
                    ],
                ),
                array(
                    'param_name' => 'border_color',
                    'heading' => __( 'Border color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '2', '3', '5' ),
                    ],
                ),
                array(
                    'param_name' => 'line_color',
                    'heading' => __( 'Line color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '3', '4' ),
                    ],
                ),
                array(
                    'param_name' => 'number_color',
                    'heading' => __( 'Number color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '4' ),
                    ],
                ),
                array(
                    'param_name' => 'active_number_color',
                    'heading' => __( 'Active number color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '4' ),
                    ],
                ),
                array(
                    'param_name' => 'active_line_color',
                    'heading' => __( 'Active line color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '4' ),
                    ],
                ),
                array(
                    'param_name' => 'active_border_color',
                    'heading' => __( 'Active Border color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '2', '5' ),
                    ],
                ),
                array(
                    'param_name' => 'dots_color',
                    'heading' => __( 'Dots Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '2', '6', '7', '8'),
                    ],
                ),
                array(
                    'param_name' => 'active_dots_color',
                    'heading' => __( 'Active Dots Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '2', '6',  '7', '8'),
                    ],
                ),
                array(
                    'param_name' => 'nav_bg_color',
                    'heading' => __( 'Nav Bg Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '2', '6'),
                    ],
                ),
                array(
                    'param_name' => 'nav_color',
                    'heading' => __( 'Nav Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '2','6', '7', '8'),
                    ],
                ),
                array(
                    'param_name' => 'nav_hover_bg_color',
                    'heading' => __( 'Nav Hover Bg Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '2','6'),
                    ],
                ),
                array(
                    'param_name' => 'nav_hover_color',
                    'heading' => __( 'Nav Hover Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '2','6', '7', '8'),
                    ],
                ),


                // typography
                array(
                    "type" => "htmegavc_param_heading",
                    "text" => __("Description Typography","htmegavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'name_typography',
                  'type' => 'font_container',
                  'group'  => __( 'Styling', 'htmegavc' ),
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
                    "text" => __("Designation Typography","htmegavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'designation_typography',
                  'type' => 'font_container',
                  'group'  => __( 'Styling', 'htmegavc' ),
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
                    "text" => __("Description Typography","htmegavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'description_typography',
                  'type' => 'font_container',
                  'group'  => __( 'Styling', 'htmegavc' ),
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
                // Styling tab end
                
                array(
                  "param_name" => "columns_on_desktop",
                  "heading" => __("Columns on desktop", 'htmegavc'),
                  "type" => "dropdown",
                  'group'  => __( 'Setting', 'htmegavc' ),
                  "default_set" => '1',
                  'value' => [
                      __( 'One Column', 'htmegavc' )  =>  '1',
                      __( 'Two Column', 'htmegavc' )  =>  '2',
                      __( 'Three Column', 'htmegavc' )  =>  '3',
                      __( 'Four Column', 'htmegavc' )  =>  '4',
                      __( 'Five Column', 'htmegavc' )  =>  '5',
                  ],
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '2', '9' ),
                  ],
                ),
                array(
                  "param_name" => "columns_on_mobile",
                  "heading" => __("Columns on Mobile", 'htmegavc'),
                  "type" => "dropdown",
                  'group'  => __( 'Setting', 'htmegavc' ),
                  "default_set" => '1',
                  'value' => [
                      __( 'One Column', 'htmegavc' )  =>  '1',
                      __( 'Two Column', 'htmegavc' )  =>  '2',
                      __( 'Three Column', 'htmegavc' )  =>  '3',
                      __( 'Four Column', 'htmegavc' )  =>  '4',
                  ],
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '2', '9' ),
                  ],
                ),
                array(
                  "param_name" => "columns_on_tablet",
                  "heading" => __("Columns on Tablet", 'htmegavc'),
                  "type" => "dropdown",
                  'group'  => __( 'Setting', 'htmegavc' ),
                  "default_set" => '1',
                  'value' => [
                      __( 'One Column', 'htmegavc' )  =>  '1',
                      __( 'Two Column', 'htmegavc' )  =>  '2',
                      __( 'Three Column', 'htmegavc' )  =>  '3',
                      __( 'Four Column', 'htmegavc' )  =>  '4',
                  ],
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '2', '9' ),
                  ],
                ),


                array(
                  'param_name' => 'autoplay',
                  'heading' => __( 'Enable Autoplay', 'htmegavc' ),
                  'type' => 'checkbox',
                  'group'  => __( 'Setting', 'htmegavc' ),
                  'description' => __( 'The carousel automatically plays when site loaded.', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '1', '2', '4', '5', '6', '7', '8',),
                  ],
                ),
                array(
                    'param_name' => 'autoplay_speed',
                    'heading' => __( 'Autoplay Speed', 'htmegavc' ),
                    'type' => 'textfield',
                    'group'  => __( 'Setting', 'htmegavc' ),
                    'description' => __( 'Autoplay interval timeout.', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'autoplay',
                        'value' => array( 'true' ),
                    ],
                ),
                array(
                  'param_name' => 'loop',
                  'heading' => __( 'Enable Loop', 'htmegavc' ),
                  'type' => 'checkbox',
                  'description' => __( 'Infinity loop. Duplicate last and first items to get loop illusion', 'htmegavc' ),
                  'group'  => __( 'Setting', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '1', '2', '4', '5', '6', '7', '8',),
                  ],
                ),

                array(
                  'param_name' => 'dots',
                  'heading' => __( 'Enable Dots?', 'htmegavc' ),
                  'type' => 'checkbox',
                  'group'  => __( 'Setting', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '1', '2', '4', '6', '7', '8',),
                  ],
                  'edit_field_class' => 'vc_column vc_col-sm-6',
                ),
                array(
                  'param_name' => 'nav',
                  'heading' => __( 'Enable Nav?', 'htmegavc' ),
                  'type' => 'checkbox',
                  'group'  => __( 'Setting', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '1', '2', '6', '7', '8',),
                  ],
                  'edit_field_class' => 'vc_column vc_col-sm-6',
                ),
                array(
                  "param_name" => "nav_type",
                  "heading" => __("Nav Type", 'htmegavc'),
                  "type" => "dropdown",
                  'group'  => __( 'Setting', 'htmegavc' ),
                  "default_set" => '1',
                  'value' => [
                      __( 'Text', 'htmegavc' )  =>  'nav_type_text',
                      __( 'Icon', 'htmegavc' )  =>  'nav_type_icon',
                  ],
                  'dependency' =>[
                      'element' => 'nav',
                      'value' => array( 'true',),
                  ],
                ),
                array(
                    'param_name' => 'prev_text',
                    'heading' => __( 'Previous arrow text', 'htmegavc' ),
                    'type' => 'textfield',
                    'group'  => __( 'Setting', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'nav_type',
                        'value' => array( 'nav_type_text' ),
                    ],
                ),
                array(
                    'param_name' => 'next_text',
                    'heading' => __( 'Next arrow text', 'htmegavc' ),
                    'type' => 'textfield',
                    'group'  => __( 'Setting', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'nav_type',
                        'value' => array( 'nav_type_text' ),
                    ],
                ),
                array(
                    'param_name' => 'prev_icon',
                    'heading' => __( 'Previous arrow icon', 'htmegavc' ),
                    'type' => 'iconpicker',
                    'group'  => __( 'Setting', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'nav_type',
                        'value' => array( 'nav_type_icon' ),
                    ],
                ),
                array(
                    'param_name' => 'next_icon',
                    'heading' => __( 'Next arrow text/icon', 'htmegavc' ),
                    'type' => 'iconpicker',
                    'group'  => __( 'Setting', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'nav_type',
                        'value' => array( 'nav_type_icon' ),
                    ],
                ),
                // Setting end


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
    
    /*
    Shortcode logic how it should be rendered
    */

    public function htmegavc_combine_font_container($arr){
        $htmegavc_member_name_style = '';
        if($arr){
          foreach(explode('|', $arr) as $item){
            if($item == 'font_family:Use%20From%20Theme'){
              continue;
            }
            $htmegavc_member_name_style .= $item . ';';
          }
          $htmegavc_member_name_style = preg_replace(array('/_/', '/%23/', '/%20/'), array('-', '#', ' '), $htmegavc_member_name_style);
        }

        return $htmegavc_member_name_style;
    }


    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
            'style' => '1',
            'shape_image' => '',
            'border_color' => '',
            'active_border_color' => '',
            'dots_color' => '',
            'active_dots_color' => '',
            'nav_bg_color' => '',
            'nav_hover_bg_color' => '',
            'nav_color' => '',
            'nav_hover_color' => '',
            'line_color' => '',
            'number_color' => '',
            'active_number_color' => '',
            'active_line_color' => '',
            'item_bg_color' => '',

            'name_typography' => '',
            'designation_typography' => '',
            'description_typography' => '',


            'testimonial_list' => '',
            'autoplay' => '',
            'autoplay_speed' => '',
            'loop' => '',
            'dots' => '',
            'nav' => '',
            'nav_type' => '',
                'nav_type_text' => '',
                    'prev_text' => '',
                    'next_text' => '',
                'nav_type_icon' => '',
                    'prev_icon' => '',
                    'next_icon' => '',
            'columns_on_desktop' => '',
            'columns_on_tablet' => '',
            'columns_on_mobile' => '',
            'columns_on_mobile' => '',

            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));

        $unique_class =  uniqid('htmegavc_testimonial_');
        $style_class = ' htmegavc-testimonial-wrapper htmegavc-testimonial-style-'. esc_attr($style);
        $wrapper_css_class = ' '. apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_testimonial', $atts );
        $custom_class = ' ' . $custom_class;

        $testimonial_list = isset($atts['testimonial_list']) ? vc_param_group_parse_atts($atts['testimonial_list']) : array();

        // slick options
        $slick_options = array();
        $slick_options['style'] = $style;
        $slick_options['autoplay'] = $autoplay;
        $slick_options['autoplay_speed'] = $autoplay_speed;
        $slick_options['loop'] = $loop;
        $slick_options['dots'] = $dots;
        $slick_options['nav'] = $nav;
        $slick_options['nav_type'] = $nav_type;
        $slick_options['prev_icon'] = $prev_icon;
        $slick_options['next_icon'] = $next_icon;
        $slick_options['prev_text'] = $prev_text;
        $slick_options['next_text'] = $next_text;
        $slick_options['columns_on_desktop'] = $columns_on_desktop;
        $slick_options['columns_on_tablet'] = $columns_on_tablet;
        $slick_options['columns_on_mobile'] = $columns_on_mobile;

        $slick_options = json_encode($slick_options);

        // styling
        $name_typography = $this->htmegavc_combine_font_container($name_typography);
        $designation_typography = $this->htmegavc_combine_font_container($designation_typography);
        $description_typography = $this->htmegavc_combine_font_container($description_typography);


        // column support
        $xs_item    = $columns_on_mobile ? floor(12 / $columns_on_mobile) : 1;
        $sm_item    = $columns_on_tablet ? floor(12 / $columns_on_tablet) : 2;
        $md_item    = $columns_on_desktop ? floor(12 / $columns_on_desktop) : 3;
        if($columns_on_desktop == '5'){
            $lg_item = 'five';
        } else {
            $lg_item    = $columns_on_desktop ? floor(12 / $columns_on_desktop) : 3;
            
        }

        $column_classes = array();
        $column_classes[] = 'htb-col-xs-'. $xs_item;
        $column_classes[] = 'htb-col-sm-'. $sm_item;
        $column_classes[] = 'htb-col-md-'. $md_item;
        $column_classes[] = 'htb-col-lg-'. $lg_item;

        ob_start();

        

        if($style == '9'){
            $output = '';

            $output .= '<style>';
            $output .= '.'. $unique_class . ' .htmegavc-testimonal img{border-color:'. $border_color .'}';
            $output .= '.'. $unique_class . ' .htmegavc-testimonal .htmegavc-content .htmegavc-clint-info::before{background-color:'. $line_color .'}';
            $output .= '</style>';

            $output .= '<div class="'. esc_attr($unique_class.$style_class.$wrapper_css_class.$custom_class ) .'" data-htmegavc-testimonial=\''.$slick_options.'\'>';
                $output .= '<div class="htb-row">';

                foreach ($testimonial_list as $key => $item) {
                    $output .= '<div class="'. implode(' ', $column_classes) .'">';
                    $output .= '<div class="htmegavc-testimonal" style="background-color:'. $item_bg_color .'">';

                        $output .= '<div class="htmegavc-content">';
                            if(isset($item['attachement_id'])){
                                $output .= wp_get_attachment_image($item['attachement_id'], 'large');
                            } else {
                                $output .= '<img src="'. plugins_url('images/tm-1.png', __FILE__) .'">';
                            }
                            $output .= '<div class="htmegavc-clint-info">';
                                $output .= isset($item['name']) ? '<h4 style="'. $name_typography .'">'. $item['name'] .'</h4>' : '';
                                $output .= isset($item['designation']) ? '<span style="'. $designation_typography .'">'. $item['designation'] .'</span>' : '';
                            $output .= '</div>';

                        $output .= '</div>';
                        $output .= isset($item['description']) ? '<p style="'. $description_typography .'">'. $item['description'] .'</p>' : '';
                    $output .= '</div><!--/.testimonal-->';
                    $output .= '</div>';
                }
                $output .= '</div>';

            $output .= '</div>';
        }else if($style == '8'){

            $output = '';

            $output .= '<style>';
            $output .= '.'. $unique_class . ' .slick-dots li button{background-color:'. $dots_color .'}';
            $output .= '.'. $unique_class . ' .slick-dots li.slick-active button{background-color:'. $active_dots_color .'}';
            $output .= '.'. $unique_class . ' .slick-arrow{color:'. $nav_color .'}';
            $output .= '.'. $unique_class . ' .slick-arrow:hover{color:'. $nav_hover_color .'}';
            $output .= '</style>';

            $output .= '<div class="slick-slider '. esc_attr($unique_class.$style_class.$wrapper_css_class.$custom_class ) .'" data-htmegavc-testimonial=\''.$slick_options.'\'>';

                foreach ($testimonial_list as $key => $item) {
                    $output .= '<div class="htmegavc-testimonal">';

                        $output .= '<div class="htmegavc-content">';
                            if(isset($item['attachement_id'])){
                                $output .= wp_get_attachment_image($item['attachement_id'], 'large');
                            } else {
                                $output .= '<img src="'. plugins_url('images/tm-1.png', __FILE__) .'">';
                            }

                            $output .= '<div class="htmegavc-clint-info">';
                            
                                $output .= isset($item['name']) ? '<h4 style="'. $name_typography .'">'. $item['name'] .'</h4>' : '';
                                $output .= isset($item['designation']) ? '<span style="'. $designation_typography .'">'. $item['designation'] .'</span>' : '';
                            $output .= '</div>';

                        $output .= '</div><!-- /.htmegavc-content -->';

                        $output .= isset($item['description']) ? '<p style="'. $description_typography .'">'. $item['description'] .'</p>' : '';

                    $output .= '</div><!--/.testimonal-->';
                }

            $output .= '</div>';

        } elseif($style == '7'){

            $output = '';

            $output .= '<style>';
            $output .= '.'. $unique_class . ' .slick-dots li button{background-color:'. $dots_color .'}';
            $output .= '.'. $unique_class . ' .slick-dots li.slick-active button{background-color:'. $active_dots_color .'}';
            $output .= '.'. $unique_class . ' .slick-arrow{color:'. $nav_color .'}';
            $output .= '.'. $unique_class . ' .slick-arrow:hover{color:'. $nav_hover_color .'}';
            $output .= '</style>';

            $output .= '<div class="slick-slider '. esc_attr($unique_class.$style_class.$wrapper_css_class.$custom_class ) .'" data-htmegavc-testimonial=\''.$slick_options.'\'>';

                foreach ($testimonial_list as $key => $item) {
                    $output .= '<div class="htmegavc-testimonal">';

                            if(isset($item['attachement_id'])){
                                $output .= wp_get_attachment_image($item['attachement_id'], 'large');
                            } else {
                                $output .= '<img src="'. plugins_url('images/tm-1.png', __FILE__) .'">';
                            }
                            $output .= isset($item['description']) ? '<p style="'. $description_typography .'">'. $item['description'] .'</p>' : '';

                            $output .= '<div class="htmegavc-clint-info">';
                                $output .= isset($item['name']) ? '<h4 style="'. $name_typography .'">'. $item['name'] .'</h4>' : '';
                                $output .= isset($item['designation']) ? '<span style="'. $designation_typography .'">'. $item['designation'] .'</span>' : '';
                            $output .= '</div>';

                    $output .= '</div><!--/.testimonal-->';
                }

            $output .= '</div>';

        }else if($style == '6'){

            $output = '';

            $output .= '<style>';
            $output .= '.'. $unique_class . ' .slick-dots li button{background-color:'. $dots_color .'}';
            $output .= '.'. $unique_class . ' .slick-dots li.slick-active button{background-color:'. $active_dots_color .'}';
            $output .= '.'. $unique_class . ' .slick-arrow{background-color:'. $nav_bg_color .'}';
            $output .= '.'. $unique_class . ' .slick-arrow{color:'. $nav_color .'}';
            $output .= '.'. $unique_class . ' .slick-arrow:hover{background-color:'. $nav_hover_bg_color .'}';
            $output .= '.'. $unique_class . ' .slick-arrow:hover{color:'. $nav_hover_color .'}';
            $output .= '</style>';

            $output .= '<div class="slick-slider '. esc_attr($unique_class.$style_class.$wrapper_css_class.$custom_class ) .'" data-htmegavc-testimonial=\''.$slick_options.'\'>';

                foreach ($testimonial_list as $key => $item) {
                    $output .= '<div class="htmegavc-testimonal">';

                        $output .= '<div class="htmegavc-content">';
                            $output .= isset($item['description']) ? '<p style="'. $description_typography .'">'. $item['description'] .'</p>' : '';
                            $output .= '<div class="htmegavc-triangle"></div>';
                        $output .= '</div><!-- /.htmegavc-content -->';

                            $output .= '<div class="htmegavc-clint-info">';
                                if(isset($item['attachement_id'])){
                                    $output .= wp_get_attachment_image($item['attachement_id'], 'large');
                                } else {
                                    $output .= '<img src="'. plugins_url('images/tm-1.png', __FILE__) .'">';
                                }
                                $output .= isset($item['name']) ? '<h4 style="'. $name_typography .'">'. $item['name'] .'</h4>' : '';
                                $output .= isset($item['designation']) ? '<span style="'. $designation_typography .'">'. $item['designation'] .'</span>' : '';
                            $output .= '</div>';

                    $output .= '</div><!--/.testimonal-->';
                }

            $output .= '</div>';

        }else if($style == '5'){
            $output = '';

            $output .= '<style>';
            $output .= '.'. $unique_class . ' .slick-slide.slick-active .htmegavc-testimonal-img img{border-color:'. $border_color .'}';
            $output .= '.'. $unique_class . ' .slick-slide.slick-active.slick-center .htmegavc-testimonal-img img{border-color:'. $active_border_color .'}';
            $output .= '</style>';

            $output .= '<div class="slick-slider  '. esc_attr($unique_class.$style_class.$wrapper_css_class.$custom_class ) .'" data-htmegavc-testimonial=\''.$slick_options.'\'>';

                // testimonial for
                $output .= '<div class="htmegavc-testimonial-for">';
                foreach ($testimonial_list as $key => $item) {
                    if(isset($item['description'])){
                        $output .= '<div class="htmegavc-testimonial-desc">';
                            $output .= '<p style="'. $description_typography .'">'. $item['description'] .'</p>';
                        $output .= '</div>';
                    }
                }
                $output .= '</div>';

                // testimonial nav
                $output .= '<div class="htmegavc-testimonal-nav">';
                foreach ($testimonial_list as $key => $item) {
                    $output .= '<div class="htmegavc-testimonal-img">';

                    if(isset($item['description'])){

                        if(isset($item['attachement_id'])){
                            $output .= wp_get_attachment_image($item['attachement_id'], 'large');
                        } else {
                            $output .= '<img src="'. plugins_url('images/tm-1.png', __FILE__) .'">';
                        }

                    }

                    $output .= '<div class="htmegavc-content">';

                        $output .= isset($item['name']) ? '<h4 style="'. $name_typography .'">'. $item['name'] .'</h4>' : '';
                        $output .= isset($item['designation']) ? '<span style="'. $designation_typography .'">'. $item['designation'] .'</span>' : '';

                    $output .= '</div>';

                    $output .= '</div>';
                }
                $output .= '</div><!-- /.htmegavc-testimonal-nav -->';

                $output .= '<div class="htmegavc-testimonial-shape">';
                    if($shape_image){
                        $output .= wp_get_attachment_image($shape_image, 'large');
                    } else{
                        $output .= '<img src="'. plugins_url('images/clint-shape.png', __FILE__) .'">';
                    }
                $output .= '</div><!-- /.htmegavc-shape -->';


            $output .= '</div><!-- /.slick-slider -->';
        } elseif($style == '4'){
            $output = '';

            $output .= '<style>';
            $output .= '.'. $unique_class . ' .htmegavc-testimonal .htmegavc-thumb::before{background-color:'. $line_color .'}';
            $output .= '.'. $unique_class . ' .htmegavc-testimonal .htmegavc-thumb::after{background-color:'. $line_color .'}';
            $output .= ' ul.testi-pagination-dots li button{color:'. $number_color .'}';
            $output .= ' ul.testi-pagination-dots li::after{background-color:'. $active_number_color .'}';
            $output .= ' ul.testi-pagination-dots li.slick-active button{color:'. $active_number_color .'}';
            $output .= '</style>';

            $output .= '<div class="slick-slider  '. esc_attr($unique_class.$style_class.$wrapper_css_class.$custom_class ) .'" data-htmegavc-testimonial=\''.$slick_options.'\'>';

                foreach ($testimonial_list as $key => $item) {
                    $output .= '<div class="htmegavc-testimonal">';
                        $output .= '<div class="htmegavc-thumb">';
                            if(isset($item['attachement_id'])){
                                $output .= wp_get_attachment_image($item['attachement_id'], 'large');
                            } else {
                                $output .= '<img src="'. plugins_url('images/tm-1.png', __FILE__) .'">';
                            }
                        $output .= '</div>';

                        $output .= '<div class="htmegavc-content">';

                            $output .= isset($item['description']) ? '<p style="'. $description_typography .'">'. $item['description'] .'</p>' : '';
                            $output .= '<div class="htmegavc-clint-info">';
                                $output .= isset($item['name']) ? '<h4 style="'. $name_typography .'">'. $item['name'] .'</h4>' : '';
                                $output .= isset($item['designation']) ? '<span style="'. $designation_typography .'">'. $item['designation'] .'</span>' : '';
                            $output .= '</div>';

                        $output .= '</div>';
                    $output .= '</div>';
                }
            $output .= '</div>';
            $output .= '<div class="testimonial-pagination"></div>';

        }elseif($style == '3'){
            $output = '';

            $output .= '<style>';
            $output .= '.'. $unique_class . ' .htmegavc-testimonal img{border-color:'. $border_color .'}';
            $output .= '.'. $unique_class . ' .htmegavc-testimonal .htmegavc-content .htmegavc-clint-info::before{background-color:'. $line_color .'}';
            $output .= '</style>';

            $output .= '<div class="'. esc_attr($unique_class.$style_class.$wrapper_css_class.$custom_class ) .'" data-htmegavc-testimonial=\''.$slick_options.'\'>';
                $output .= '<div class="htb-row">';

                foreach ($testimonial_list as $key => $item) {
                    $output .= '<div class="htb-col-lg-6 htb-col-xl-6 htb-col-sm-12 htb-col-12">';
                    $output .= '<div class="htmegavc-testimonal">';

                        if(isset($item['attachement_id'])){
                            $output .= wp_get_attachment_image($item['attachement_id'], 'large');
                        } else {
                            $output .= '<img src="'. plugins_url('images/tm-1.png', __FILE__) .'">';
                        }

                        $output .= '<div class="htmegavc-content">';

                            $output .= isset($item['description']) ? '<p style="'. $description_typography .'">'. $item['description'] .'</p>' : '';
                            $output .= '<div class="htmegavc-clint-info">';
                                $output .= isset($item['name']) ? '<h4 style="'. $name_typography .'">'. $item['name'] .'</h4>' : '';
                                $output .= isset($item['designation']) ? '<span style="'. $designation_typography .'">'. $item['designation'] .'</span>' : '';
                            $output .= '</div>';

                        $output .= '</div>';
                    $output .= '</div><!--/.testimonal-->';
                    $output .= '</div>';
                }
                $output .= '</div>';

            $output .= '</div>';
        } elseif($style == '2'){

            $output = '';

            $output .= '<style>';
            $output .= '.'. $unique_class . ' .htmegavc-testimonal img{border-color:'. $border_color .'}';
            $output .= '.'. $unique_class . ' .slick-center .htmegavc-testimonal img{border-color:'. $active_border_color .'}';
            $output .= '.'. $unique_class . ' .slick-center .htmegavc-testimonal img{border-color:'. $active_border_color .'}';
            $output .= '.'. $unique_class . ' .slick-dots li button{background-color:'. $dots_color .'}';
            $output .= '.'. $unique_class . ' .slick-dots li.slick-active button{background-color:'. $active_dots_color .'}';
            $output .= '.'. $unique_class . ' .slick-arrow{background-color:'. $nav_bg_color .'}';
            $output .= '.'. $unique_class . ' .slick-arrow{color:'. $nav_color .'}';
            $output .= '.'. $unique_class . ' .slick-arrow:hover{background-color:'. $nav_hover_bg_color .'}';
            $output .= '.'. $unique_class . ' .slick-arrow:hover{color:'. $nav_hover_color .'}';
            $output .= '</style>';

            $output .= '<div class="slick-slider '. esc_attr($unique_class.$style_class.$wrapper_css_class.$custom_class ) .'" data-htmegavc-testimonial=\''.$slick_options.'\'>';

                foreach ($testimonial_list as $key => $item) {
                    $output .= '<div class="htmegavc-testimonal">';
                    $output .= '<div class="htmegavc-testimonal-inner">';

                        if(isset($item['attachement_id'])){
                            $output .= wp_get_attachment_image($item['attachement_id'], 'large');
                        } else {
                            $output .= '<img src="'. plugins_url('images/tm-1.png', __FILE__) .'">';
                        }
                        $output .= '<div class="htmegavc-content">';
                            $output .= isset($item['description']) ? '<p style="'. $description_typography .'">'. $item['description'] .'</p>' : '';
                            $output .= isset($item['name']) ? '<h4 style="'. $name_typography .'">'. $item['name'] .'</h4>' : '';
                            $output .= isset($item['designation']) ? '<span style="'. $designation_typography .'">'. $item['designation'] .'</span>' : '';
                        $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div><!--/.testimonal-->';
                }

            $output .= '</div>';

        } else {
            $output = '';
            $output .= '<div class="slick-slider '. esc_attr($unique_class.$style_class.$wrapper_css_class.$custom_class ) .'" data-htmegavc-testimonial=\''.$slick_options.'\'>';

                foreach ($testimonial_list as $key => $item) {
                    $output .= '<div class="htmegavc-testimonal">';

                        if(isset($item['attachement_id'])){
                            $output .= wp_get_attachment_image($item['attachement_id'], 'large');
                        } else {
                            $output .= '<img src="'. plugins_url('images/tm-1.png', __FILE__) .'">';
                        }
                        $output .= '<div class="htmegavc-shape">';
                            if($shape_image){
                                $output .= wp_get_attachment_image($shape_image, 'large');
                            } else{
                                $output .= '<img src="'. plugins_url('images/clint-shape.png', __FILE__) .'">';
                            }
                        $output .= '</div>';
                        $output .= '<div class="htmegavc-content">';
                            $output .= isset($item['description']) ? '<p style="'. $description_typography .'">'. $item['description'] .'</p>' : '';
                            $output .= isset($item['name']) ? '<h4 style="'. $name_typography .'">'. $item['name'] .'</h4>' : '';
                            $output .= isset($item['designation']) ? '<span style="'. $designation_typography .'">'. $item['designation'] .'</span>' : '';
                        $output .= '</div>';
                    $output .= '</div><!--/.testimonal-->';
                }

            $output .= '</div>';
        }


        echo $output;
?>

<?php
  return ob_get_clean();
}

}

// Finally initialize code
new Htmegavc_Testimonial();