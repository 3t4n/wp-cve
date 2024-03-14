<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Business_Hours extends WPBakeryShortCode{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_business_hours', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_register_style( 'htmegavc_business_hours', plugins_url('css/business-hours.css', __FILE__) );
      wp_enqueue_style( 'htmegavc_business_hours' );
    }

    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
            'theme' => 'bg_shape',
            'item_list' => '',

            'title_color' => '',
            'content_color' => '',
            'offday_title_color' => '',
            'offday_content_color' => '',
            'offday_item_bg_color' => '',
            'item_odd_bg_color' => '',
            'item_even_bg_color' => '',
            'item_odd_text_color' => '',
            'item_even_text_color' => '',
            'item_border_color' => '',
            'item_border_shape_img' => '',
            'gradient_color_1' => '',
            'gradient_color_2' => '',

            'title_use_google_font' => '', 
            'title_google_font' => '', 
            'title_typography' => '', 

            'content_use_google_font' => '', 
            'content_google_font' => '', 
            'content_typography' => '',

            'offday_title_typography' => '', 
            'offday_content_typography' => '', 
            
            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));

        // wrapper class
        $wrapper_class_arr = array();
        
        $unique_class = uniqid('htmegavc_business_hours_');
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'business_hours_wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        // Typography
        // load google font
        $title_google_font_data = htmegavc_build_google_font_data($title_google_font);
        if ( 'true' == $title_use_google_font && isset( $title_google_font_data['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $title_google_font_data['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $title_google_font_data['values']['font_family']);
        }
        $title_google_font = htmegavc_build_google_font_style($title_google_font_data);
        $title_typography = htmegavc_combine_font_container($title_typography.';'.$title_google_font);

        $content_google_font_data = htmegavc_build_google_font_data($content_google_font);
        if ( 'true' == $content_use_google_font && isset( $content_google_font_data['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $content_google_font_data['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $content_google_font_data['values']['font_family']);
        }
        $content_google_font = htmegavc_build_google_font_style($content_google_font_data);
        $content_typography = htmegavc_combine_font_container($content_typography.';'.$content_google_font);

        $offday_title_typography = htmegavc_combine_font_container($offday_title_typography);
        $offday_content_typography = htmegavc_combine_font_container($offday_content_typography);

        // add wrapper class
        $wrapper_class_arr[] =  'htmegavc-business-hours';
        $wrapper_inner_class = '';
         if($theme == 'bg_gradient'){

            $wrapper_class_arr[] =  'business-hrs-5';

        }else if($theme == 'bg_img'){

            $wrapper_class_arr[] =  'business-hrs-4';

        }else if($theme == 'offday_highlight'){

            $wrapper_class_arr[] =  'business-hrs-3';

        } else if($theme == 'diff_color'){

            $wrapper_class_arr[] =  'business-hrs-2';

        } else {

            $wrapper_class_arr[] =  'business-hrs-1';
            $wrapper_inner_class =  'shapebg_image--1';

        }

        // join all wrapper class
        $wrapper_class = esc_attr(implode(' ', $wrapper_class_arr));

        // item list
        $item_list = isset($atts['item_list']) ? vc_param_group_parse_atts($atts['item_list']) : array();
        $item_border_shape_img = wp_get_attachment_image_src($item_border_shape_img, 'large');
        $item_border_shape_img = isset($item_border_shape_img[0]) ? $item_border_shape_img[0] : '';

        $output = '';

        // custom style
        $output .= '<style>';
        $output .= ".$unique_class.htmegavc-business-hours .business-hrs-inner .single-hrs span.day{color:$title_color;}";
        $output .= ".$unique_class.htmegavc-business-hours .business-hrs-inner .single-hrs span.time{color:$content_color;}";

        $output .= ".$unique_class.htmegavc-business-hours .business-hrs-inner .single-hrs:nth-child(odd){background-color:$item_odd_bg_color;}";
        $output .= ".$unique_class.htmegavc-business-hours .business-hrs-inner .single-hrs:nth-child(even){background-color:$item_even_bg_color;}";
        $output .= ".$unique_class.htmegavc-business-hours .business-hrs-inner .single-hrs:nth-child(odd) span.day{color:$item_odd_text_color;}";
        $output .= ".$unique_class.htmegavc-business-hours .business-hrs-inner .single-hrs:nth-child(odd) span.time{color:$item_odd_text_color;}";
        $output .= ".$unique_class.htmegavc-business-hours .business-hrs-inner .single-hrs:nth-child(even) span.day{color:$item_even_text_color;}";
        $output .= ".$unique_class.htmegavc-business-hours .business-hrs-inner .single-hrs:nth-child(even) span.time{color:$item_even_text_color;}";

        $output .= ".$unique_class.htmegavc-business-hours .business-hrs-inner .single-hrs.closed-day{background-color:$offday_item_bg_color;}";
        $output .= ".$unique_class.htmegavc-business-hours .business-hrs-inner .single-hrs.closed-day span.day{color:$offday_title_color;}";
        $output .= ".$unique_class.htmegavc-business-hours .business-hrs-inner .single-hrs.closed-day span.time{color:$offday_content_color;}";

        if($theme == 'diff_color' || $theme == 'bg_img' || $theme == 'bg_gradient'){
           $output .= ".$unique_class.htmegavc-business-hours .business-hrs-inner .single-hrs{border-color:$item_border_color;}";

           if($item_border_shape_img){
            $output .= ".$unique_class.htmegavc-business-hours .business-hrs-inner .single-hrs::before{background-image: url($item_border_shape_img);}";
           }
        }
        if($theme == 'bg_gradient' && $gradient_color_1 && $gradient_color_2){
            $output .= ".$unique_class.htmegavc-business-hours .business-hrs-inner::before{
                background-image: linear-gradient($gradient_color_1, $gradient_color_2);
                background-image: -webkit-gradient($gradient_color_1, $gradient_color_2);
                background-image: -webkit-linear-gradient($gradient_color_1, $gradient_color_2);
                background-image: -moz-linear-gradient($gradient_color_1, $gradient_color_2);
                background-image: -ms-linear-gradient($gradient_color_1, $gradient_color_2);
                background-image: -o-linear-gradient($gradient_color_1, $gradient_color_2);
            }";
        }

        $output .= '</style>';

        ob_start(); ?>

        <!-- Start Business Hours -->
        <div class="<?php echo esc_attr($wrapper_class); ?>">
          <div class="business-hrs-inner <?php echo esc_attr($wrapper_inner_class); ?>">
            
            <?php foreach($item_list as $item): ?>
              <div class="single-hrs <?php echo esc_attr(isset($item['offday']) && $item['offday'] == 'true' ? 'closed-day' : ''); ?>">

                <?php if($item['title']): ?>
                  <span class="day" style="<?php echo esc_attr($title_typography); ?>"><?php echo esc_html( $item['title'] ) ?></span>
                <?php endif; ?>

                <?php if($item['content']): ?>
                  <span class="time" style="<?php echo esc_attr($content_typography); ?>"><?php echo esc_html( $item['content'] ) ?></span>
                <?php endif; ?>

              </div>
            <?php endforeach; ?>

          </div>
        </div>
        <!-- Start Business Hours -->

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
          "name" => __("HT Business Hours", 'htmegavc'),
          "description" => __("Add Business Hours to your page", 'htmegavc'),
          "base" => "htmegavc_business_hours",
          "class" => "",
          "controls" => "full",
          "icon" => 'htmegvc_business_hours_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
          "category" => __('HT Mega Addons', 'htmegavc'),
          "params" => array(
              array(
                  "param_name" => "theme",
                  "heading" => __("Style", 'htmegavc'),
                  "type" => "dropdown",
                  "default_set" => 'bg_shape',
                  'value' => [
                      __( 'Theme 1', 'htmegavc' )  =>  'bg_shape',
                      __( 'Theme 2', 'htmegavc' )  =>  'diff_color',
                      __( 'Theme 3', 'htmegavc' )  =>  'offday_highlight',
                      __( 'Theme 4', 'htmegavc' )  =>  'bg_img',
                      __( 'Theme 5', 'htmegavc' )  =>  'bg_gradient',
                  ],
              ),
              
              // accordion repeater
              array(
                  'param_name' => 'item_list',
                  "heading" => __("Items", 'text_domainn'),
                  'type' => 'param_group',
                  'value' => urlencode( json_encode (array(
                      array(
                          'title'         => __('Monday','htmegavc'),
                          'content'          => __('8:00am to 5:00pm','htmegavc'),
                      ),
                      array(
                          'title'         => __('Tuesday','htmegavc'),
                          'content'          => __('8:00am to 5:00pm','htmegavc'),
                      ),
                      array(
                          'title'         => __('Wednesday','htmegavc'),
                          'content'          => __('8:00am to 5:00pm','htmegavc'),
                      ),
                      array(
                          'title'         => __('Thursday','htmegavc'),
                          'content'          => __('8:00am to 5:00pm','htmegavc'),
                      ),
                      array(
                          'title'         => __('Friday','htmegavc'),
                          'content'          => __('8:00am to 5:00pm','htmegavc'),
                      ),
                      array(
                          'title'         => __('Saturday','htmegavc'),
                          'content'          => __('8:00am to 5:00pm','htmegavc'),
                      ),
                      array(
                          'title'         => __('Sunday','htmegavc'),
                          'content'          => __('8:00am to 5:00pm','htmegavc'),
                      ),
                   ))),
                  'params' => array(
                     array(
                         'param_name' => 'title',
                         'heading' => __( 'Title', 'htmegavc' ),
                         'type' => 'textfield',
                     ),
                     array(
                         'param_name' => 'content',
                         'heading' => __( 'Content', 'htmegavc' ),
                         'type' => 'textarea',
                     ),
                     array(
                       'param_name' => 'offday',
                       'heading' => __( 'Is this day offday?', 'htmegavc' ),
                       'type' => 'checkbox',
                     ),
                  ),
              ),


              // customizations
              array(
                  'param_name' => 'title_color',
                  'heading' => __( 'Title Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'content_color',
                  'heading' => __( 'Content Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'offday_title_color',
                  'heading' => __( 'Offday Title Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'offday_content_color',
                  'heading' => __( 'Offday Content Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'offday_item_bg_color',
                  'heading' => __( 'Offday Item Bg Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'item_odd_bg_color',
                  'heading' => __( 'Item Odd Bg Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'item_even_bg_color',
                  'heading' => __( 'Item Even Bg Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'item_odd_text_color',
                  'heading' => __( 'Item Odd Text Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'item_even_text_color',
                  'heading' => __( 'Item Even Text Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),


              array(
                  'param_name' => 'item_border_color',
                  'heading' => __( 'Item Border Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'theme',
                      'value' => array( 'diff_color' ),
                  ],
              ),
              array(
                  'param_name' => 'item_border_shape_img',
                  'heading' => __( 'Border shape image', 'htmegavc' ),
                  'type' => 'attach_image',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'theme',
                      'value' => array( 'bg_img','diff_color', 'bg_gradient' ),
                  ],
              ),
              array(
                  'param_name' => 'gradient_color_1',
                  'heading' => __( 'Background Gradient Color 1', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'theme',
                      'value' => array( 'bg_gradient' ),
                  ],
              ),
              array(
                  'param_name' => 'gradient_color_2',
                  'heading' => __( 'Background Gradient Color 2', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'theme',
                      'value' => array( 'bg_gradient' ),
                  ],
              ),


              // typography

              // title
              array(
                  "type" => "htmegavc_param_heading",
                  "text" => __("Title Typography","htmegavc"),
                  "param_name" => "package_typograpy",
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Typography', 'htmegavc' ),
              ),
              array(
                'type' => 'checkbox',
                'heading' => __( 'Use google font?', 'htmegavc' ),
                'param_name' => 'title_use_google_font',
                'description' => __( 'Use font family from google font.', 'htmegavc' ),
                'group'  => __( 'Typography', 'htmegavc' ),
              ),
              array(
                'type' => 'google_fonts',
                'param_name' => 'title_google_font',
                'group'  => __( 'Typography', 'htmegavc' ),
                'settings' => array(
                  'fields' => array(
                    'font_family_description' => __( 'Select font family.', 'htmegavc' ),
                    'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
                  ),
                ),
                'dependency' =>[
                    'element' => 'title_use_google_font',
                    'value' => array( 'true' ),
                ],
              ),
              array(
                'param_name' => 'title_typography',
                'type' => 'font_container',
                'group'  => __( 'Typography', 'htmegavc' ),
                'settings' => array(
                  'fields' => array(
                    'font_size',
                    'line_height',
                    'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmegavc' ),
                    'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
                  ),
                ),
              ),

              // cotnent
              array(
                  "type" => "htmegavc_param_heading",
                  "text" => __("Content Typography","htmegavc"),
                  "param_name" => "package_typograpy",
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Typography', 'htmegavc' ),
              ),
              array(
                'type' => 'checkbox',
                'heading' => __( 'Use google font?', 'htmegavc' ),
                'param_name' => 'content_use_google_font',
                'description' => __( 'Use font family from google font.', 'htmegavc' ),
                'group'  => __( 'Typography', 'htmegavc' ),
              ),
              array(
                'type' => 'google_fonts',
                'param_name' => 'content_google_font',
                'group'  => __( 'Typography', 'htmegavc' ),
                'settings' => array(
                  'fields' => array(
                    'font_family_description' => __( 'Select font family.', 'htmegavc' ),
                    'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
                  ),
                ),
                'dependency' =>[
                    'element' => 'content_use_google_font',
                    'value' => array( 'true' ),
                ],
              ),
              array(
                'param_name' => 'content_typography',
                'type' => 'font_container',
                'group'  => __( 'Typography', 'htmegavc' ),
                'settings' => array(
                  'fields' => array(
                    'font_size',
                    'line_height',
                    'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmegavc' ),
                    'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
                  ),
                ),
              ),

              // offday
              array(
                  "type" => "htmegavc_param_heading",
                  "text" => __("Offday Title Typography","htmegavc"),
                  "param_name" => "package_typograpy",
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Typography', 'htmegavc' ),
              ),
              array(
                'param_name' => 'offday_title_typography',
                'type' => 'font_container',
                'group'  => __( 'Typography', 'htmegavc' ),
                'settings' => array(
                  'fields' => array(
                    'font_size',
                    'line_height',
                    'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmegavc' ),
                    'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
                  ),
                ),
              ),
              array(
                  "type" => "htmegavc_param_heading",
                  "text" => __("Offday Content Typography","htmegavc"),
                  "param_name" => "package_typograpy",
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Typography', 'htmegavc' ),
              ),
              array(
                'param_name' => 'offday_content_typography',
                'type' => 'font_container',
                'group'  => __( 'Typography', 'htmegavc' ),
                'settings' => array(
                  'fields' => array(
                    'font_size',
                    'line_height',
                    'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmegavc' ),
                    'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
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
new Htmegavc_Business_Hours();