<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Accordion{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_accordion', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );

        add_image_size('htmegavc_size_700x950');
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_enqueue_style( 'vc_font_awesome_5_shims-css' );
      wp_enqueue_style( 'vc_font_awesome_5' );

      wp_register_style( 'htmegavc_accordion', plugins_url('css/accordion.css', __FILE__) );
      wp_enqueue_style( 'htmegavc_accordion' );

      wp_enqueue_script("jquery-ui-core");

      wp_register_script( 'htmegavc_suzzle', HTMEGAVC_LIBS_URI .'/suzzle/suzzle.js', '', '');
      wp_register_script( 'jquery-easing', HTMEGAVC_LIBS_URI .'/easing/jquery.easing.1.3.js', '', '');
      wp_register_script( 'jquery-mousewheel', HTMEGAVC_LIBS_URI .'/mousewheel/jquery.mousewheel.js', '', '');
      wp_register_script( 'jquery-vaccordion', HTMEGAVC_LIBS_URI .'/vaccordion/jquery.vaccordion.js', '', '');
      wp_register_script( 'htmegavc-vaccordion-active', plugins_url('js/accordion-active.js', __FILE__), '', '', true );
      
      wp_enqueue_script( 'jquery-easing' );
      wp_enqueue_script( 'jquery-mousewheel' );
      wp_enqueue_script( 'jquery-vaccordion' );
      wp_enqueue_script( 'htmegavc-vaccordion-active' );
    }

    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
            'style' => 'normal',
            'theme' => '1',
            'icon_position' => 'left',
            'accordion_open_icon' => 'fa fa-minus',
            'accordion_close_icon' => 'fa fa-plus',
            'v_theme' => '1',
            'icon_shape' => 'none',
            'accordion_list' => '',
            'v_accordion_list' => '',
            'v_image_size' => '',
            'h_accordion_list' => '',

            'title_bg_color' => '',
            'desc_bg_color' => '',
            'title_color' => '',
            'desc_color' => '',
            'icon_shape' => '',
            'icon_color' => '',
            'icon_bg_color' => '',
            'border_color' => '',
            'item_width' => '',
            'item_width_expand' => '',
            'h_wrapper_height' => '',
            'h_item_expand_height' => '',
            'h_items_to_show' => '',

            'title_typography' => '', 
            'desc_typography' => '', 
            
            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));


        // wrapper class
        $wrapper_class_arr = array();
        
        $unique_class = uniqid('htmegavc_accordion_');
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'accordion_wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        // Typography
        $title_typography = htmegavc_combine_font_container($title_typography);
        $desc_typography = htmegavc_combine_font_container($desc_typography);



        $output = '';

        if($style == 'horizontal'){
            // accordion options
            $accordion_options = array();
            $accordion_options['h_wrapper_height'] = $h_wrapper_height;
            $accordion_options['h_item_expand_height'] = $h_item_expand_height;
            $accordion_options['h_items_to_show'] = $h_items_to_show;
            $accordion_options = wp_json_encode($accordion_options);

            $accordion_list = isset($atts['h_accordion_list']) ? vc_param_group_parse_atts($atts['h_accordion_list']) : array();

            ob_start(); ?>

             <div id="htmegavc-va-accordion" class="htmegavc-accordion--5" data-htmegavc-accordion='<?php echo esc_attr($accordion_options); ?>'>
                <div class="accor_wrapper">

                    <?php foreach($accordion_list as $key => $item):
                        $img_src = wp_get_attachment_image_src($item['attachement_id'], 'large');
                        ?>

                    <div class="single_accordion" <?php if( !empty($img_src) ){ echo 'style="background-image:url('. $img_src[0] .')"'; } ?>>
                        <h3 class="va-title" style="<?php echo esc_attr($title_typography); ?>"><?php echo esc_html( $item['title'] ); ?></h3>
                        <div class="va-content" style="<?php echo esc_attr($desc_typography); ?>">
                            <p><?php echo wp_kses_post( $item['desc'] ); ?></p>
                        </div>
                    </div>

                    <?php endforeach; ?>

                </div>
            </div>

            <?php $output .= ob_get_clean();

        }else if($style == 'vertical' && $v_theme == '2'){
            // accordion options
            $accordion_options = array();
            $accordion_options['item_width'] = $item_width;
            $accordion_options['item_width_expand'] = $item_width_expand;

            $accordion_options = wp_json_encode($accordion_options);

            // wrapper class
            $wrapper_class_arr[] = 'htmegavc-accordion--4 htmegavc_accordion_'. $style;
            $wrapper_class_arr[] = 'theme_'. $v_theme;
            $wrapper_class = esc_attr(implode(' ', $wrapper_class_arr));

            $output .= '<ul class="'. esc_attr($wrapper_class) .'" id="htmegavc-accordion-4"  data-htmegavc-accordion='.$accordion_options.'>';

            // custom style
            $output .= '<style>';
            $output .= "ul.$unique_class li{width:$item_width;}";
            $output .= "ul.$unique_class li .horizontal_accor_heading{background-color:$title_bg_color;}";
            $output .= "ul.$unique_class li .horizontal_accor_heading,ul.$unique_class li .horizontal_accor_description h2{color:$title_color;}";
            $output .= "ul.$unique_class li .horizontal_accor_description p{background-color:$desc_bg_color;}";
            $output .= "ul.$unique_class li .horizontal_accor_description p{color:$desc_color;}";
            $output .= '</style>';

            $accordion_list = isset($atts['v_accordion_list']) ? vc_param_group_parse_atts($atts['v_accordion_list']) : array();

            ob_start();
            foreach($accordion_list as $key => $item){
                $key = $key + 1;
                $img_src = wp_get_attachment_image_src($item['attachement_id'], 'large');

            ?>

                <li <?php if( !empty($img_src) ){ echo 'style="background-image:url('. $img_src[0] .')"'; } ?>>
                    <div class="horizontal_accor_heading"><?php echo esc_html( $item['title'] ); ?></div>
                    <div class="horizontal_accor_bgDescription"></div>
                    <div class="horizontal_accor_description">
                        <h2  style="<?php echo esc_attr($title_typography); ?>"><?php echo esc_html( $item['title'] ); ?></h2>
                        <p><?php echo esc_html( $item['desc'] ); ?></p>
                    </div>
                </li>

            <?php
            }

            $output .= ob_get_clean();
            $output .= '</ul><!-- /.htmegavc-accordion--4 -->';
        }else if($style == 'vertical' && $v_theme == '1'){
            // wrapper class
            $wrapper_class_arr[] = 'gallery-wrap htmegavc_accordion_'. $style;
            $wrapper_class_arr[] = 'theme_'. $theme;
            $wrapper_class = esc_attr(implode(' ', $wrapper_class_arr));

            // iamge size
            if(strpos($v_image_size, 'x')){
                $size_arr = explode('x', $v_image_size);
                $v_image_size = array($size_arr[0],$size_arr[1]);
            }

            $output .= "<div class='{$wrapper_class}'>";

            $accordion_list = isset($atts['v_accordion_list']) ? vc_param_group_parse_atts($atts['v_accordion_list']) : array();
            foreach($accordion_list as $key => $item){
                $key = $key + 1;
                $img_src = wp_get_attachment_image_src($item['attachement_id'], $v_image_size);

                ob_start();
            ?>

                <div class="item item-<?php echo esc_attr($key) ?>"  <?php if( !empty($img_src) ){ echo 'style="background-image:url('. $img_src[0] .')"'; } ?>></div>

            <?php
                $output .= ob_get_clean();
            }

            $output .= '</div><!-- /.gallery-wrap -->';

        } else {
            $wrapper_class_arr[] = 'panel-group htmegavc_accordion htmegavc_accordion_'. $style;
            $wrapper_class_arr[] = 'theme_'. $theme;
            $wrapper_class_arr[] = 'icon_'. $icon_shape;
            $wrapper_class_arr[] = 'icon_'. $icon_position;
            $wrapper_class = esc_attr(implode(' ', $wrapper_class_arr));

            $output .= "<div class='{$wrapper_class}' id='$unique_class' role='tablist' aria-multiselectable='true'>";
            $output .= '<style>';
            $output .= ".panel-group.$unique_class .htmegavc-panel-heading{background-color:$title_bg_color;}";
            $output .= ".panel-group.$unique_class .htmegavc-panel-heading h4 a{color:$title_color;}";
            $output .= ".panel-group.$unique_class .htmegavc-panel-collapse{background-color:$desc_bg_color;}";
            $output .= ".panel-group.$unique_class .htmegavc-panel-collapse .htmegavc-panel-body{color:$desc_color;}";

            
            $output .= ".panel-group.$unique_class .htmegavc-panel-heading i{ background: $icon_bg_color; color: $icon_color;}";
            $output .= ".$unique_class.theme_5 .htmegavc-panel-heading,.$unique_class.theme_5 .htmegavc-panel-heading h4 a::after{ border-color: $border_color;}";
            $output .= '</style>';

            $accordion_list = isset($atts['accordion_list']) ? vc_param_group_parse_atts($atts['accordion_list']) : array();
            foreach($accordion_list as $key => $item){
                ob_start(); ?>
                
                <div class="htmegavc-panel htmegavc-panel-default">
                    <div class="htmegavc-panel-heading" role="tab" id="accOne">
                        <h4 class="htmegavc-panel-title">
                            <a style="<?php echo esc_attr($title_typography); ?>" class="htb-collapsed" role="button" data-toggle="htbcollapse" data-parent="#<?php echo esc_attr($unique_class); ?>" href="#acc-collaps_<?php echo esc_attr($key.$unique_class); ?>" aria-expanded="true"
                                aria-controls="acc-collaps_<?php echo esc_attr($key.$unique_class); ?>">
                               <i class="accourdion-icon closeed-accourdion <?php echo esc_attr( $accordion_close_icon ) ?>"></i>
                               <i class="accourdion-icon opened-accourdion <?php echo esc_attr( $accordion_open_icon ) ?>"></i>
                               <?php echo esc_html($item['title']); ?>
                            </a>
                        </h4>
                    </div>
                    <div id="acc-collaps_<?php echo esc_attr($key.$unique_class); ?>" class="htmegavc-panel-collapse htb-collapse in" role="tabpanel" aria-labelledby="accOne">
                        <div style="<?php echo esc_attr($desc_typography); ?>" class="htmegavc-panel-body"><?php echo wp_kses_post($item['desc']); ?></div>
                    </div>
                </div>

                <?php $output .= ob_get_clean();
            }

            $output .= '</div>';
        }


        return $output;
  }



  
  public function integrateWithVC() {
  
      /*
      Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

      More info: http://kb.wpbakery.com/index.php?title=Vc_map
      */
      vc_map( array(
          "name" => __("HT Accordion", 'htmegavc'),
          "description" => __("Add Accordion to your page", 'htmegavc'),
          "base" => "htmegavc_accordion",
          "class" => "",
          "controls" => "full",
          "icon" => 'htmegvc_accordion_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
          "category" => __('HT Mega Addons', 'htmegavc'),
          "params" => array(
              array(
                  "param_name" => "style",
                  "heading" => __("Style", 'htmegavc'),
                  "type" => "dropdown",
                  "default_set" => 'normal',
                  'value' => [
                      __( 'Normal', 'htmegavc' )  =>  'normal',
                      __( 'Vertical', 'htmegavc' )  =>  'vertical',
                      __( 'Horizontal', 'htmegavc' )  =>  'horizontal',
                  ],
              ),
              array(
                  "param_name" => "theme",
                  "heading" => __("Theme", 'htmegavc'),
                  "type" => "dropdown",
                  "default_set" => '1',
                  'value' => [
                      __( 'Theme 1 (Gray)', 'htmegavc' )  =>  '1',
                      __( 'Theme 2 (White)', 'htmegavc' )  =>  '2',
                      __( 'Theme 3 (White Round)', 'htmegavc' )  =>  '3',
                      __( 'Theme 4 (White- Border)', 'htmegavc' )  =>  '4',
                      __( 'Theme 5 (White - BoxShadow)', 'htmegavc' )  =>  '5',
                  ],
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'normal' ),
                  ],
              ),
              array(
                  "param_name" => "icon_position",
                  "heading" => __("Icon Position", 'htmegavc'),
                  "type" => "dropdown",
                  "default_set" => '1',
                  'value' => [
                      __( 'Left', 'htmegavc' )  =>  'left',
                      __( 'Right', 'htmegavc' )  =>  'right',
                  ],
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'normal' ),
                  ],
              ),
              array(
                  'param_name' => 'accordion_close_icon',
                  'heading' => __( 'Close Icon', 'htmegavc' ),
                  'type' => 'iconpicker',
                  'value' => 'fa fa-plus',
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'normal' ),
                  ],
              ),
              array(
                  'param_name' => 'accordion_open_icon',
                  'heading' => __( 'Open Icon', 'htmegavc' ),
                  'type' => 'iconpicker',
                  'value' => 'fa fa-minus',
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'normal' ),
                  ],
              ),
              array(
                  "param_name" => "v_theme",
                  "heading" => __("Theme", 'htmegavc'),
                  "type" => "dropdown",
                  "default_set" => '1',
                  'value' => [
                      __( 'Theme 1', 'htmegavc' )  =>  '1',
                      __( 'Theme 2', 'htmegavc' )  =>  '2',
                  ],
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'vertical' ),
                  ],
              ),
              array(
                  'param_name' => 'v_image_size',
                  'heading' => __( 'Image Size', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)). Default we use the htmegavc_size_700x950', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'v_theme',
                      'value' => array( '1', ),
                  ],
              ),
              
              // accordion repeater
              array(
                  'param_name' => 'accordion_list',
                  "heading" => __("Accordion Items", 'htmegavc'),
                  'type' => 'param_group',
                  'value' => urlencode( json_encode (array(
                      array(
                          'title'         => __('Accordion #1','htmegavc'),
                          'desc'          => __('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incid idunt ut labore et dolore magna aliqua. Ut enim ad minimol veniam qui nostrud exercitation ullamco.
                                      ','htmegavc'),
                      ),
                      array(
                          'title'         => __('Accordion #2','htmegavc'),
                          'desc'          =>  __('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incid idunt ut labore et dolore magna aliqua. Ut enim ad minimol veniam qui nostrud exercitation ullamco.
                                      ','htmegavc'),
                      ),
                      array(
                          'title'         => __('Accordion #3','htmegavc'),
                          'desc'          =>  __('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incid idunt ut labore et dolore magna aliqua. Ut enim ad minimol veniam qui nostrud exercitation ullamco.
                                      ','htmegavc'),
                      ),
                   ))),
                  'params' => array(
                     array(
                         'param_name' => 'title',
                         'heading' => __( 'Title', 'htmegavc' ),
                         'type' => 'textfield',
                         'dependency' =>[
                             'element' => 'style',
                             'value' => array( 'vertical' ),
                         ],
                     ),
                     array(
                         'param_name' => 'desc',
                         'heading' => __( 'Description', 'htmegavc' ),
                         'type' => 'textarea',
                     ),
                  ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'normal' ),
                  ],
              ),

              // style vertical
              array(
                  'param_name' => 'v_accordion_list',
                  "heading" => __("Accordion Items", 'text_domainn'),
                  'type' => 'param_group',
                  'value' => urlencode( json_encode (array(
                      array(
                          'title'         => __('Accordion #1','htmegavc'),
                          'desc'          => __('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incid idunt ut labore et dolore magna aliqua. Ut enim ad minimol veniam qui nostrud exercitation ullamco.
                                      ','htmegavc'),
                      ),
                      array(
                          'title'         => __('Accordion #2','htmegavc'),
                          'desc'          =>  __('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incid idunt ut labore et dolore magna aliqua. Ut enim ad minimol veniam qui nostrud exercitation ullamco.
                                      ','htmegavc'),
                      ),
                      array(
                          'title'         => __('Accordion #3','htmegavc'),
                          'desc'          =>  __('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incid idunt ut labore et dolore magna aliqua. Ut enim ad minimol veniam qui nostrud exercitation ullamco.
                                      ','htmegavc'),
                      ),
                      array(
                          'title'         => __('Accordion #4','htmegavc'),
                          'desc'          => __('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incid idunt ut labore et dolore magna aliqua. Ut enim ad minimol veniam qui nostrud exercitation ullamco.
                                      ','htmegavc'),
                      ),
                      array(
                          'title'         => __('Accordion #5','htmegavc'),
                          'desc'          =>  __('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incid idunt ut labore et dolore magna aliqua. Ut enim ad minimol veniam qui nostrud exercitation ullamco.
                                      ','htmegavc'),
                      ),
                      array(
                          'title'         => __('Accordion #6','htmegavc'),
                          'desc'          =>  __('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incid idunt ut labore et dolore magna aliqua. Ut enim ad minimol veniam qui nostrud exercitation ullamco.
                                      ','htmegavc'),
                      ),
                   ))),
                  'params' => array(
                     array(
                         'param_name' => 'title',
                         'heading' => __( 'Title', 'htmegavc' ),
                         'type' => 'textfield',
                         'dependency' =>[
                             'element' => 'style',
                             'value' => array( 'vertical' ),
                         ],
                     ),
                     array(
                         'param_name' => 'desc',
                         'heading' => __( 'Description', 'htmegavc' ),
                         'type' => 'textarea',
                     ),
                     array(
                         'param_name' => 'attachement_id',
                         'heading' => __( 'Image', 'htmegavc' ),
                         'type' => 'attach_image',
                         'description' => __( 'For theme 1: Recomended image size is 700x950. For theme 1: Recomended image size is ', 'htmegavc' ),
                     ),
                  ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'vertical' ),
                  ],
              ),


              // style horizontal
              array(
                  'param_name' => 'h_accordion_list',
                  "heading" => __("Accordion Items", 'text_domainn'),
                  'type' => 'param_group',
                  'value' => urlencode( json_encode (array(
                      array(
                          'title'         => __('Accordion #1','htmegavc'),
                          'desc'          => __('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incid idunt ut labore et dolore magna aliqua. Ut enim ad minimol veniam qui nostrud exercitation ullamco.
                                      ','htmegavc'),
                      ),
                      array(
                          'title'         => __('Accordion #2','htmegavc'),
                          'desc'          =>  __('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incid idunt ut labore et dolore magna aliqua. Ut enim ad minimol veniam qui nostrud exercitation ullamco.
                                      ','htmegavc'),
                      ),
                      array(
                          'title'         => __('Accordion #3','htmegavc'),
                          'desc'          =>  __('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incid idunt ut labore et dolore magna aliqua. Ut enim ad minimol veniam qui nostrud exercitation ullamco.
                                      ','htmegavc'),
                      ),
                      array(
                          'title'         => __('Accordion #4','htmegavc'),
                          'desc'          => __('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incid idunt ut labore et dolore magna aliqua. Ut enim ad minimol veniam qui nostrud exercitation ullamco.
                                      ','htmegavc'),
                      ),
                      array(
                          'title'         => __('Accordion #5','htmegavc'),
                          'desc'          =>  __('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incid idunt ut labore et dolore magna aliqua. Ut enim ad minimol veniam qui nostrud exercitation ullamco.
                                      ','htmegavc'),
                      ),
                      array(
                          'title'         => __('Accordion #6','htmegavc'),
                          'desc'          =>  __('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incid idunt ut labore et dolore magna aliqua. Ut enim ad minimol veniam qui nostrud exercitation ullamco.
                                      ','htmegavc'),
                      ),
                   ))),
                  'params' => array(
                     array(
                         'param_name' => 'title',
                         'heading' => __( 'Title', 'htmegavc' ),
                         'type' => 'textfield',
                     ),
                     array(
                         'param_name' => 'desc',
                         'heading' => __( 'Description', 'htmegavc' ),
                         'type' => 'textarea',
                     ),
                     array(
                         'param_name' => 'attachement_id',
                         'heading' => __( 'Image', 'htmegavc' ),
                         'type' => 'attach_image',
                         'description' => __( 'For theme 1: Recomended image size is 700x950. For theme 1: Recomended image size is ', 'htmegavc' ),
                     ),
                  ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'horizontal' ),
                  ],
              ),


              // customizations
              array(
                  'param_name' => 'title_bg_color',
                  'heading' => __( 'Title Bg Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'normal', 'vertical', 'horizontal' ),
                  ],
              ),
              array(
                  'param_name' => 'desc_bg_color',
                  'heading' => __( 'Description Bg Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'normal', 'vertical', 'horizontal' ),
                  ],
              ),
              array(
                  'param_name' => 'title_color',
                  'heading' => __( 'Title Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'normal', 'vertical', 'horizontal' ),
                  ],
              ),
              array(
                  'param_name' => 'desc_color',
                  'heading' => __( 'Description Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'normal', 'vertical', 'horizontal' ),
                  ],
              ),
              array(
                  'param_name' => 'icon_color',
                  'heading' => __( 'Icon Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'normal' ),
                  ],
              ),
              array(
                  'param_name' => 'icon_bg_color',
                  'heading' => __( 'Icon Bg Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
              ),
              array(
                  'param_name' => 'border_color',
                  'heading' => __( 'Border Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'theme',
                      'value' => array( '5' ),
                  ],
              ),


              array(
                  'param_name' => 'item_width',
                  'heading' => __( 'Item Width', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Width of each item. Eg: 130px', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'v_theme',
                      'value' => array( '2' ),
                  ],
              ),
              array(
                  'param_name' => 'item_width_expand',
                  'heading' => __( 'Item Expand Width', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Width of expanded item. Eg: 430px', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'v_theme',
                      'value' => array( '2' ),
                  ],
              ),
              array(
                  'param_name' => 'h_wrapper_height',
                  'heading' => __( 'Wrapper height', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Height of the items area. Eg: 430', 'htmegavc' ),
                  'value' => '450',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'horizontal' ),
                  ],
              ),
              array(
                  'param_name' => 'h_item_expand_height',
                  'heading' => __( 'Item Exapand height', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'The height of a opened slice, should not be more than wrapper height. Eg: 430', 'htmegavc' ),
                  'value' => '450',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'horizontal' ),
                  ],
              ),
              array(
                  'param_name' => 'h_items_to_show',
                  'heading' => __( 'Items to show', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Number of visible slices. Eg: 2', 'htmegavc' ),
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'horizontal' ),
                  ],
              ),

              // typography
              array(
                  "type" => "htmegavc_param_heading",
                  "text" => __("Title Typography","htmegavc"),
                  "param_name" => "package_typograpy",
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Typography', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'normal' ),
                  ],
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
                    'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmegavc' ),
                    'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
                  ),
                ),
                'dependency' =>[
                    'element' => 'style',
                    'value' => array( 'normal' ),
                ],
              ),
              array(
                  "type" => "htmegavc_param_heading",
                  "text" => __("Description Typography","htmegavc"),
                  "param_name" => "package_typograpy",
                  "class" => "htmegavc-param-heading",
                  'edit_field_class' => 'vc_column vc_col-sm-12',
                  'group'  => __( 'Typography', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( 'normal' ),
                  ],
              ),
              array(
                'param_name' => 'desc_typography',
                'type' => 'font_container',
                'group'  => __( 'Typography', 'htmegavc' ),
                'settings' => array(
                  'fields' => array(
                    'font_family',
                    'font_size',
                    'line_height',
                    'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmegavc' ),
                    'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
                  ),
                ),
                'dependency' =>[
                    'element' => 'style',
                    'value' => array( 'normal' ),
                ],
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
new Htmegavc_Accordion();