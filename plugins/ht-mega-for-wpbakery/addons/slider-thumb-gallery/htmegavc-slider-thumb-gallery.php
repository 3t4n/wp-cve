<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Slider_Thumb_Gallery extends WPBakeryShortCode{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_thumb_gallery', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_register_script( 'slick', HTMEGAVC_LIBS_URI . '/slick-slider/slick.min.js', '', '', '');
      wp_enqueue_script( 'slick' );

      wp_register_style( 'slick', HTMEGAVC_LIBS_URI . '/slick-slider/slick.min.css');
      wp_enqueue_style( 'slick' );

      wp_register_script( 'thumbgallery-active', plugins_url('js/thumbgallery-active.js', __FILE__), '', '', true);
      wp_enqueue_script( 'thumbgallery-active' );

      wp_register_style( 'thumbgallery', plugins_url('css/thumbgallery.css', __FILE__));
      wp_enqueue_style( 'thumbgallery');
    }


    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
           // Content
           'sliderthumbnails_style' => '1',
           'slider_list' => '',
            'slider_title' => __('Location Name Here.', 'htmegavc'),
            'slider_image' => '',
           'slider_imagesize' => '',

           // Slider Setting
           'slitems' => '1',
           'slarrows' => 'yes',
            'slprevicon' => 'fa fa-angle-left',
            'slnexticon' => 'fa fa-angle-right',
           // 'sldots' => 'no',
           'slpause_on_hover' => 'yes',
           'slcentermode' => '',
            'slcenterpadding' => '50',
           'slautolay' => '',
            'slautoplay_speed' => '3000',
            'slanimation_speed' => '300',
           'slscroll_columns' => '1',

           // Tablet Options
           'sltablet_display_columns' => '1',
           'sltablet_scroll_columns' => '1',
           'sltablet_width' => '750',

           // Mobile Options
           'slmobile_display_columns' => '1',
           'slmobile_scroll_columns' => '1',
           'slmobile_width' => '480',

           // Slider Nav Option Setting
           'slider_thumbnails_imagesize' => 'thumbnail',
           'slnavitems' => '4',
           'slnavarrows' => 'yes',
            'slnavprevicon' => 'fa fa-angle-left',
            'slnavnexticon' => 'fa fa-angle-right',
           // 'slnavdots' => '',
           'slnavvertical' => 'yes',
           'slnavpause_on_hover' => 'yes',
           'slnavcentermode' => '',
            'slnavcenterpadding' => '',
           'slnavautolay' => '',
            'slnavautoplay_speed' => '3000',
            'slnavanimation_speed' => '300',
           'slnavscroll_columns' => '1',

           // Tablet Options
           'slnavtablet_display_columns' => '1',
           'slnavtablet_scroll_columns' => '1',
           'slnavtablet_width' => '750',
           // Mobile Options
           'slnavmobile_display_columns' => '1',
           'slnavmobile_scroll_columns' => '1',
           'slnavmobile_width' => '480',

           // Styling
           // Title Styling
           'slider_title_color' => '',
           'slider_title_margin' => '',
           'slider_title_padding' => '',
           'slider_title_background' => '',

           // Arrow Styling
           'thumbnails_arrow_fontsize' => '',
           'thumbnails_arrow_color' => '',
           'thumbnails_arrow_background' => '',
           'thumbnails_arrow_border_style' => '',
           'thumbnails_arrow_border_width' => '',
           'thumbnails_arrow_border_radius' => '',
           'thumbnails_arrow_border_color' => '',
           'thumbnails_arrow_height' => '',
           'thumbnails_arrow_width' => '',
           'thumbnails_arrow_padding' => '',

           // Hover Styling
           'thumbnails_arrow_hover_color' => '',
           'thumbnails_arrow_hover_background' => '',
           'thumbnails_arrow_hover_border_style' => '',
           'thumbnails_arrow_hover_border_width' => '',
           'thumbnails_arrow_hover_border_radius' => '',
           'thumbnails_arrow_hover_border_color' => '',

           // Pagination Styling
           'thumbnails_dots_background' => '',
           'thumbnails_dots_border_style' => '',
           'thumbnails_dots_border_width' => '',
           'thumbnails_dots_border_color' => '',
           'thumbnails_dots_border_radius' => '',
           'thumbnails_dots_border_color' => '',
           'thumbnails_dots_height' => '',
           'thumbnails_dots_width' => '',

           // Active Dots Styling
           'thumbnails_dots_hover_background' => '',
           'thumbnails_dots_hover_border_style' => '',
           'thumbnails_dots_hover_border_width' => '',
           'thumbnails_dots_hover_border_radius' => '',
           'thumbnails_dots_hover_border_color' => '',

           // Typography
           'slider_title_use_google_font' => '',
           'slider_title_google_font' => '',
           'slider_title_typography' => '',

            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));


        // wrapper class
        $wrapper_class_arr = array();
        
        $unique_class = uniqid('htmegavc_thumb_gallery_');
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_thumb_gallery_wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        // add wrapper class
        $wrapper_class_arr[] =  'htmegavc-sliderarea htmegavc-thumbnails-style-'.$sliderthumbnails_style;

        // join all wrapper class
        $wrapper_class = implode(' ', $wrapper_class_arr);

        // Styling
        // Title Styling
        $title_inline_style = "color:$slider_title_color;";
        $title_inline_style .= "background-color:$slider_title_background;";
        $title_inline_style .= "margin:$slider_title_margin;";
        $title_inline_style .= "padding:$slider_title_padding;";

        // Arrow Styling
        $arrow_inline_style = "font-size:$thumbnails_arrow_fontsize;";
        $arrow_inline_style .= "color:$thumbnails_arrow_color;";
        $arrow_inline_style .= "background-color:$thumbnails_arrow_background;";
        $arrow_inline_style .= "border-width:$thumbnails_arrow_border_width;";
        $arrow_inline_style .= "border-style:$thumbnails_arrow_border_style;";
        $arrow_inline_style .= "border-color:$thumbnails_arrow_border_color;";
        $arrow_inline_style .= "border-radius:$thumbnails_arrow_border_radius;";
        $arrow_inline_style .= "height:$thumbnails_arrow_height;";
        $arrow_inline_style .= "width:$thumbnails_arrow_width;";
        $arrow_inline_style .= "padding:$slider_title_padding;";

        // Arrow Hover Styling
        $arrow_hover_inline_style = "color:$thumbnails_arrow_hover_color;";
        $arrow_hover_inline_style .= "background-color:$thumbnails_arrow_hover_background;";
        $arrow_hover_inline_style .= "border-color:$thumbnails_arrow_hover_border_color;";

        // Typography
        // Title Typography
        $google_font_data1 = htmegavc_build_google_font_data($slider_title_google_font);
        if ( 'true' == $slider_title_use_google_font && isset( $google_font_data1['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data1['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data1['values']['font_family'] );
        }

        // concate google font properties and other properties
        $slider_title_google_font = htmegavc_build_google_font_style($google_font_data1);
        $title_inline_style .= htmegavc_combine_font_container($slider_title_typography.';'.$slider_title_google_font);


        $output = '';
        $output .= '<style>';
        $output .= "
      .$unique_class .htmegavc-thumbgallery-for .slick-arrow{ $arrow_inline_style }
      .$unique_class .htmegavc-thumbgallery-for .slick-arrow{ $arrow_inline_style }
      .$unique_class .htmegavc-thumbgallery-for .slick-arrow:hover{ $arrow_hover_inline_style }
        ";
        $output .= '</style>';

        ob_start();

        $slider_settings = [];
        $slider_settings = [
            'arrows' => ('yes' === $slarrows),
            'arrow_prev_txt' => $slprevicon,
            'arrow_next_txt' => $slnexticon,
            'autoplay' => ('yes' === $slautolay),
            'autoplay_speed' => absint($slautoplay_speed),
            'animation_speed' => absint($slanimation_speed),
            'pause_on_hover' => ('yes' === $slpause_on_hover),
            'center_mode' => ( 'yes' === $slcentermode),
            'center_padding' => absint($slcenterpadding),
        ];

        $slider_responsive_settings = [];
        $slider_responsive_settings = [
            'display_columns' => $slitems,
            'scroll_columns' => $slscroll_columns,
            'tablet_width' => $sltablet_width,
            'tablet_display_columns' => $sltablet_display_columns,
            'tablet_scroll_columns' => $sltablet_scroll_columns,
            'mobile_width' => $slmobile_width,
            'mobile_display_columns' => $slmobile_display_columns,
            'mobile_scroll_columns' => $slmobile_scroll_columns,

        ];
        $slider_settings = array_merge( $slider_settings, $slider_responsive_settings );

        $nav_slider_settings = [];
        $nav_slider_settings = [
            'navarrows' => ('yes' === $slnavarrows),
            'navarrow_prev_txt' => $slnavprevicon, 
            'navarrow_next_txt' => $slnavnexticon,
            // 'navdots' => ('yes' === $slnavdots),
            'navvertical' => ('yes' === $slnavvertical),
            'navautoplay' => ('yes' === $slnavautolay),
            'navautoplay_speed' => absint($slnavautoplay_speed),
            'navanimation_speed' => absint($slnavanimation_speed),
            'navpause_on_hover' => ('yes' === $slnavpause_on_hover),
            'navcenter_mode' => ( 'yes' === $slnavcentermode),
            'navcenter_padding' => absint($slnavcenterpadding),
        ];

        $nav_slider_responsive_settings = [];
        $nav_slider_responsive_settings = [
            'navdisplay_columns' => $slnavitems,
            'navscroll_columns' => $slnavscroll_columns,
            'navtablet_width' => $sltablet_width,
            'navtablet_display_columns' => $slnavtablet_display_columns,
            'navtablet_scroll_columns' => $slnavtablet_scroll_columns,
            'navmobile_width' => $slnavmobile_width,
            'navmobile_display_columns' => $slnavmobile_display_columns,
            'navmobile_scroll_columns' => $slnavmobile_scroll_columns,

        ];
        $nav_slider_settings = array_merge( $nav_slider_settings, $nav_slider_responsive_settings );

        $slider_image_size = $slider_image;
        if(strpos($slider_image_size, 'x')){
            $slider_image_size = array($slider_image);
        }

        $thumbnail_image_size = $slider_thumbnails_imagesize;
        if(strpos($thumbnail_image_size, 'x') == true){
          $size_arr = explode('x', $thumbnail_image_size);
            $thumbnail_image_size = array($size_arr[0],$size_arr[1]);
        }

        $slider_list = isset($atts['slider_list']) ? vc_param_group_parse_atts($atts['slider_list']) : array();
        ?>

        <div class="<?php echo esc_attr( $wrapper_class ); ?>" >
    
      <div class="htb-row row--5 align-items-center mt--40">

          <?php if( $sliderthumbnails_style == 3 ): ?>
              <div class="htb-col-lg-2 htb-col-md-2 htb-col-sm-2 htb-col-2">
                  <div class="<?php echo esc_attr('htmegavc-thumbgallery-nav'); ?>" data-navsettings='<?php echo wp_json_encode( $nav_slider_settings ); ?>'>
                      <?php foreach ( $slider_list as $slideritem ) :?>
                          <div class="small-thumb">
                              <?php
                                  echo wp_get_attachment_image($slideritem['slider_image'], $thumbnail_image_size);
                              ?>
                          </div>
                      <?php endforeach;?>
                  </div>
              </div>
              <div class="htb-col-lg-10 htb-col-md-10 htb-col-sm-10 htb-col-10">
                  <div class="htmegavc-thumb-gallery">
              <ul class="<?php echo esc_attr('htmegavc-thumbgallery-for htmegavc-arrow-'.$sliderthumbnails_style); ?>" data-settings='<?php echo esc_attr( wp_json_encode( $slider_settings ) ); ?>'>
                          <?php foreach ( $slider_list as $slideritem ) :?>
                              <li>
                                  <?php
                                      echo wp_get_attachment_image($slideritem['slider_image'], $slider_imagesize);
                                      if( !empty( $slider_title ) ){
                                          echo '<div class="content right-bottom"><h2>'.esc_html__( $slider_title, 'htmegavc').'</h2></div>';
                                      }
                                  ?>
                              </li>
                          <?php endforeach;?>
                      </ul>

                  </div>
              </div>

          <?php elseif( $sliderthumbnails_style == 4 ): ?>
              <div class="htb-col-lg-12">
                  <div class="<?php echo esc_attr('htmegavc-thumbgallery-nav'); ?>" data-navsettings='<?php echo wp_json_encode( $nav_slider_settings ); ?>'>
                      <?php foreach ( $slider_list as $slideritem ) :?>
                          <div class="small-thumb">
                              <?php
                                  echo wp_get_attachment_image($slideritem['slider_image'], $thumbnail_image_size);
                              ?>
                          </div>
                      <?php endforeach;?>
                  </div>
              </div>
              <div class="htb-col-lg-12">
                  <div class="htmegavc-thumb-gallery">
                <ul class="<?php echo esc_attr('htmegavc-thumbgallery-for htmegavc-arrow-'.$sliderthumbnails_style); ?>" data-settings='<?php echo wp_json_encode( $slider_settings ); ?>'  >
                  <?php foreach ( $slider_list as $slideritem ) :?>
                              <li>
                                  <?php
                                      echo wp_get_attachment_image($slideritem['slider_image'], $slider_imagesize);
                                      if( !empty( $slider_title ) ){
                                          echo '<div class="content"><h2>'.esc_html__( $slider_title, 'htmegavc').'</h2></div>';
                                      }
                                  ?>
                              </li>
                          <?php endforeach;?>
                      </ul>

                  </div>
              </div>

          <?php else:?>
              <div class="<?php if( $sliderthumbnails_style == 2 ){ echo 'htb-col-lg-12'; }else{ echo 'htb-col-lg-10 htb-col-md-10 htb-col-sm-10 htb-col-10'; }?>">
                  <div class="htmegavc-thumb-gallery">
                <ul class="<?php echo esc_attr('htmegavc-thumbgallery-for htmegavc-arrow-'.$sliderthumbnails_style); ?>" data-settings='<?php echo wp_json_encode( $slider_settings ); ?>'  >
                  <?php foreach ( $slider_list as $slideritem ) :?>
                              <li>
                                  <?php
                                       echo wp_get_attachment_image($slideritem['slider_image'], $slider_imagesize);
                                      if( !empty( $slider_title ) ){
                                          echo '<div class="content"><h2>'.esc_html__( $slider_title, 'htmegavc').'</h2></div>';
                                      }
                                  ?>
                              </li>
                          <?php endforeach;?>
                      </ul>

                  </div>
              </div>

              <div class="<?php if( $sliderthumbnails_style == 2 ){ echo 'htb-col-lg-12';}else{ echo 'htb-col-lg-2 htb-col-md-2 htb-col-sm-2 htb-col-2'; }?>">
                  <div class="<?php echo esc_attr('htmegavc-thumbgallery-nav'); ?>" data-navsettings='<?php echo wp_json_encode( $nav_slider_settings ); ?>'>
                      <?php foreach ( $slider_list as $slideritem ) :?>
                          <div class="small-thumb">
                              <?php
                                   echo wp_get_attachment_image($slideritem['slider_image'], $thumbnail_image_size);
                              ?>
                          </div>
                      <?php endforeach;?>
                  </div>
              </div>
          <?php endif;?>

          <!-- End Thumb Gallery -->
      </div>

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
            "name" => __("HT Slider Thumb Gallery", 'htmegavc'),
            "description" => __("Add Slider Thumb Gallery to your page", 'htmegavc'),
            "base" => "htmegavc_thumb_gallery",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegavc_thumb_gallery_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('HT Mega Addons', 'htmegavc'),
            "params" => array(

              // cotnent
              array(
                "param_name" => "sliderthumbnails_style",
                "heading" => __("Style", 'htmegavc'),
                "type" => "dropdown",
                "default_set" => '1',
                'value' => [
                    __( 'Right', 'htmegavc' ) => '1',
                    __( 'Bottom', 'htmegavc' )  => '2',
                    __( 'Left', 'htmegavc' )  => '3',
                    __( 'Top', 'htmegavc' ) => '4',
                ],
              ),
              array(
                  'param_name' => 'slider_list',
                  "heading" => __("Slider List", 'text_domainn'),
                  'type' => 'param_group',
                  'params' => array(
                     array(
                         'param_name' => 'slider_title',
                         'heading' => __( 'Slider Title', 'htmegavc' ),
                         'type' => 'textfield',
                         'value' => __('Location Name Here.', 'htmegavc'),
                     ),
                     array(
                         'param_name' => 'slider_image',
                         'heading' => __( 'Slider Image', 'htmegavc' ),
                         'type' => 'attach_image',
                     ),
                  )
              ),
              array(
                  'param_name' => 'slider_imagesize',
                  'heading' => __( 'Slider Image Size', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'slider_thumbnails_imagesize',
                  'heading' => __( 'Slider Thumbnails Image  Size', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'htmegavc' ),
              ),

              // Slider Options
              array(
                  'param_name' => 'slitems',
                  'heading' => __( 'Slider Items', 'htmegavc' ),
                  'type' => 'textfield',
                  'value' => '1',
                  'description' => __( 'Example: 2', 'htmegavc' ),
                  'group' => __('Slider Options', 'htmegavc'),
              ),
              array(
                "param_name" => "slarrows",
                "heading" => __("Slider Arrow", 'htmegavc'),
                "type" => "dropdown",
                "default_set" => 'yes',
                'value' => [
                    __( 'Yes', 'htmegavc' ) => 'yes',
                    __( 'No', 'htmegavc' )  => 'no',
                ],
                'group' => __('Slider Options', 'htmegavc'),
              ),
              array(
                  'param_name' => 'slprevicon',
                  'heading' => __( 'Previous Arrow Icon', 'htmegavc' ),
                  'type' => 'iconpicker',
                  'dependency' =>[
                      'element' => 'slarrows',
                      'value' => array( 'yes' ),
                  ],
                  'group' => __('Slider Options', 'htmegavc'),
              ),
              array(
                  'param_name' => 'slnexticon',
                  'heading' => __( 'Next Arrow Icon', 'htmegavc' ),
                  'type' => 'iconpicker',
                  'dependency' =>[
                      'element' => 'slarrows',
                      'value' => array( 'yes' ),
                  ],
                  'group' => __('Slider Options', 'htmegavc'),
              ),
              // array(
              //   "param_name" => "sldots",
              //   "heading" => __("Slider Dots", 'htmegavc'),
              //   "type" => "dropdown",
              //   "default_set" => 'no',
              //   'value' => [
              //       __( 'No', 'htmegavc' ) => 'no',
              //       __( 'Yes', 'htmegavc' )  => 'yes',
              //   ],
              //   'group' => __('Slider Options', 'htmegavc'),
              // ),
              array(
                "param_name" => "slcentermode",
                "heading" => __("Center Mode", 'htmegavc'),
                "type" => "dropdown",
                "default_set" => 'no',
                'value' => [
                    __( 'Yes', 'htmegavc' ) => 'yes',
                    __( 'No', 'htmegavc' )  => 'no',
                ],
                'group' => __('Slider Options', 'htmegavc'),
              ),
              array(
                  'param_name' => 'slcenterpadding',
                  'heading' => __( 'Center padding', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'The CSS padding. Example: 18px, which stand for padding:18px', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'slcentermode',
                      'value' => array( 'yes' ),
                  ],
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                "param_name" => "slautolay",
                "heading" => __("Slider Auto Play", 'htmegavc'),
                "type" => "dropdown",
                "default_set" => 'no',
                'value' => [
                    __( 'No', 'htmegavc' )  => 'no',
                    __( 'Yes', 'htmegavc' ) => 'yes',
                ],
                'group' => __('Slider Options', 'htmegavc'),
              ),
              array(
                  'param_name' => 'autoplay_speed',
                  'heading' => __( 'Autoplay Speed', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Autoplay Speed in milliseconds. Example: 3000', 'htmegavc' ),
                  'group'  => __( 'Slider Options', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'slautolay',
                      'value' => array( 'yes' ),
                  ],
              ),
              array(
                  'param_name' => 'slanimation_speed',
                  'heading' => __( 'Autoplay Animation Speed', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Autoplay animation speed.', 'htmegavc' ),
                  'group'  => __( 'Slider Options', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'slautolay',
                      'value' => array( 'yes' ),
                  ],
              ),
              array(
                "param_name" => "slpause_on_hover",
                "heading" => __("Pause On Hover?", 'htmegavc'),
                "type" => "dropdown",
                "default_set" => 'yes',
                'value' => [
                    __( 'Yes', 'htmegavc' ) => 'yes',
                    __( 'No', 'htmegavc' )  => 'no',
                ],
                'dependency' =>[
                    'element' => 'slautolay',
                    'value' => array( 'yes' ),
                ],
                'group' => __('Slider Options', 'htmegavc'),
              ),
              array(
                  'param_name' => 'slscroll_columns',
                  'heading' => __( 'Slider Item To Scroll', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Example: 2', 'htmegavc' ),
                  'group'  => __( 'Slider Options', 'htmegavc' ),
              ),

              // Slider Nav Options
              array(
                  'param_name' => 'slnavvertical',
                  'heading' => __( 'Nav Vertical', 'htmegavc' ),
                  "type" => "dropdown",
                  "default_set" => 'yes',
                  'value' => [
                      __( 'Yes', 'htmegavc' ) => 'yes',
                      __( 'No', 'htmegavc' )  => 'no',
                  ],
                  'group' => __('Nav Options', 'htmegavc'),
              ),
              array(
                  'param_name' => 'slnavitems',
                  'heading' => __( 'Thumbnails Items', 'htmegavc' ),
                  'type' => 'textfield',
                  'value' => '1',
                  'description' => __( 'Example: 2', 'htmegavc' ),
                  'group' => __('Nav Options', 'htmegavc'),
              ),
              array(
                "param_name" => "slnavarrows",
                "heading" => __("Thumbnails Arrow", 'htmegavc'),
                "type" => "dropdown",
                "default_set" => 'yes',
                'value' => [
                    __( 'Yes', 'htmegavc' ) => 'yes',
                    __( 'No', 'htmegavc' )  => 'no',
                ],
                'group' => __('Nav Options', 'htmegavc'),
              ),
              array(
                  'param_name' => 'slnavprevicon',
                  'heading' => __( 'Previous Arrow Icon', 'htmegavc' ),
                  'type' => 'iconpicker',
                  'dependency' =>[
                      'element' => 'slarrows',
                      'value' => array( 'yes' ),
                  ],
                  'group' => __('Nav Options', 'htmegavc'),
              ),
              array(
                  'param_name' => 'slnavnexticon',
                  'heading' => __( 'Next Arrow Icon', 'htmegavc' ),
                  'type' => 'iconpicker',
                  'dependency' =>[
                      'element' => 'slarrows',
                      'value' => array( 'yes' ),
                  ],
                  'group' => __('Nav Options', 'htmegavc'),
              ),
              array(
                "param_name" => "slnavpause_on_hover",
                "heading" => __("Pause On Hover?", 'htmegavc'),
                "type" => "dropdown",
                "default_set" => 'yes',
                'value' => [
                    __( 'Yes', 'htmegavc' ) => 'yes',
                    __( 'No', 'htmegavc' )  => 'no',
                ],
                'group' => __('Nav Options', 'htmegavc'),
              ),
              array(
                "param_name" => "slnavcentermode",
                "heading" => __("Center Mode", 'htmegavc'),
                "type" => "dropdown",
                "default_set" => 'no',
                'value' => [
                    __( 'No', 'htmegavc' )  => 'no',
                    __( 'Yes', 'htmegavc' ) => 'yes',
                ],
                'group' => __('Nav Options', 'htmegavc'),
              ),
              array(
                  'param_name' => 'slnavcenterpadding',
                  'heading' => __( 'Center padding', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'The CSS padding. Example: 18px, which stand for padding:18px', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'slcentermode',
                      'value' => array( 'yes' ),
                  ],
                  'group'  => __('Nav Options', 'htmegavc' ),
              ),
              array(
                "param_name" => "slnavautolay",
                "heading" => __("Thumbnails Auto Play", 'htmegavc'),
                "type" => "dropdown",
                "default_set" => 'no',
                'value' => [
                    __( 'Yes', 'htmegavc' ) => 'yes',
                    __( 'No', 'htmegavc' )  => 'no',
                ],
                'group' => __('Nav Options', 'htmegavc'),
              ),
              array(
                  'param_name' => 'aunavtoplay_speed',
                  'heading' => __( 'Autoplay Speed', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Autoplay Speed in milliseconds. Example: 3000', 'htmegavc' ),
                  'group'  => __('Nav Options', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'slautolay',
                      'value' => array( 'yes' ),
                  ],
              ),
              array(
                  'param_name' => 'slnavanimation_speed',
                  'heading' => __( 'Autoplay animation speed', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Autoplay animation speed.', 'htmegavc' ),
                  'group'  => __('Nav Options', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'slautolay',
                      'value' => array( 'yes' ),
                  ],
              ),
              array(
                  'param_name' => 'slnavscroll_columns',
                  'heading' => __( 'Thumbnails Item To Scroll', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Example: 2', 'htmegavc' ),
                  'group'  => __('Nav Options', 'htmegavc' ),
              ),


              // Styling
              // Title Styling
              array(
                  "param_name" => "custom_heading",
                  "type" => "htmegavc_param_heading",
                  "text" => __("Title Styling","htmegavc"),
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'slider_title_color',
                  'heading' => __( 'Title Text Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'slider_title_background',
                  'heading' => __( 'Title Text BG  Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'slider_title_margin',
                  'heading' => __( 'Title Margin', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'The CSS margin. Example: 18px 0, which stand for margin-top and margin-bottom is 18px', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'slider_title_padding',
                  'heading' => __( 'Title Padding', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'The CSS padding. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),

              // Arrow Styling
              array(
                  "param_name" => "custom_heading",
                  "type" => "htmegavc_param_heading",
                  "text" => __("Arrow Styling","htmegavc"),
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'thumbnails_arrow_fontsize',
                  'heading' => __( 'Arrow Icon Size', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'The CSS font-size. Example: 18px, which stand for font-size:18px', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'thumbnails_arrow_color',
                  'heading' => __( 'Arrow Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'thumbnails_arrow_background',
                  'heading' => __( 'Arrow BG  Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'thumbnails_arrow_border_width',
                  'heading' => __( 'Arrow Border width', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'The CSS Border width. Example: 2px, which stand for border-width:2px;', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'thumbnails_arrow_border_style',
                  'heading' => __( 'Arrow Border style', 'htmegavc' ),
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
                  'param_name' => 'thumbnails_arrow_border_radius',
                  'heading' => __( 'Arrow Border Radius', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'The CSS Border Radius. Example: 5px, which stand for border-radius:5px;', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'thumbnails_arrow_border_color',
                  'heading' => __( 'Arrow Border color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'description' => __( 'The CSS Border color of blockquote.', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'thumbnails_arrow_height',
                  'heading' => __( 'Arrow Height', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'The CSS height. Example: 18px, which stand for height:18px', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'thumbnails_arrow_width',
                  'heading' => __( 'Arrow Width', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'The CSS width. Example: 18px, which stand for width:18px', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'thumbnails_arrow_padding',
                  'heading' => __( 'Arrow Padding', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'The CSS padding. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),

              // Arrow Hover Styling
              array(
                  "param_name" => "custom_heading",
                  "type" => "htmegavc_param_heading",
                  "text" => __("Arrow Hover Styling","htmegavc"),
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'thumbnails_arrow_hover_color',
                  'heading' => __( 'Arrow Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'thumbnails_arrow_hover_background',
                  'heading' => __( 'Arrow BG  Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'thumbnails_arrow_border_color',
                  'heading' => __( 'Arrow Border color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'description' => __( 'The CSS Border color of blockquote.', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),


              // Typography
              // Before Title Typography
              array(
                  "param_name" => "package_typograpy",
                  "type" => "htmegavc_param_heading",
                  "text" => __("Slider Title Typography","htmegavc"),
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Typography', 'htmegavc' ),
              ),
              array(
                'type' => 'checkbox',
                'heading' => __( 'Use google Font?', 'htmegavc' ),
                'param_name' => 'slider_title_use_google_font',
                'description' => __( 'Use font family from google font.', 'htmegavc' ),
                'group'  => __( 'Typography', 'htmegavc' ),
              ),
              array(
                'type' => 'google_fonts',
                'param_name' => 'slider_title_google_font',
                'group'  => __( 'Typography', 'htmegavc' ),
                'settings' => array(
                  'fields' => array(
                    'font_family_description' => __( 'Select font family.', 'htmegavc' ),
                    'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
                  ),
                ),
                'dependency' =>[
                    'element' => 'slider_title_use_google_font',
                    'value' => array( 'true' ),
                ],
              ),
              array(
                'param_name' => 'slider_title_typography',
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
              ),

                // extra class
                array(
                    'param_name' => 'custom_class',
                    'heading' => __( 'Extra Class Name', 'htmegavc' ),
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
new Htmegavc_Slider_Thumb_Gallery();