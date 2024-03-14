<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmega_Vc_Brands{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_brands', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }


    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_register_style( 'htmegavc_brands', plugins_url('css/brands.css', __FILE__) );
      wp_enqueue_style( 'htmegavc_brands' );
    }


    public function render_shortcode( $atts, $content = null ) {

    	extract(shortcode_atts(array(
            'style' => '1',
            'attachement_id'  => '',
            'link_to_image'  => '',
            'border_color'  => '',
            'border_type'  => '',
            'border_width'  => '',
            'border_color'  => '',
            'border_type'  => '',
            'border_width'  => '',
            'border_color'  => '',
            'border_type'  => '',
            'border_width'  => '',
            'box_shadow'  => '',
            'img_bg_color'  => '',

            'custom_class' => '', 
            'wrapper_css' => '', 
    	),$atts));

      $wrapper_css_class = ' '. apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_brands', $atts );
      $unique_class =  uniqid('htmega_brands_');
      $custom_class = ' ' . $custom_class;

      $image_list = isset($atts['image_list']) ? vc_param_group_parse_atts($atts['image_list']) : array();

    	ob_start();
?>

<div class="htmegavc-brand-style-<?php echo esc_attr($style); ?> <?php echo esc_attr($unique_class.$wrapper_css_class.$custom_class ); ?>" >

<?php
  if($style == '5' || $style == '7'){

    $image =  $attachement_id ? wp_get_attachment_image($attachement_id, 'large') : '<img src="https://placeholdit.co//i/203x178">';


    $link_arr = explode('|', $link_to_image);
    if(count($link_arr) > 1){
      $link_url  =  urldecode(str_replace('url:', '', $link_arr[0]));
      $link_target  =  urldecode(str_replace('target:', '', $link_arr[2]));

      if($link_url){
        $image = '<a  href="'. esc_url($link_url) .'" target="'. esc_attr($link_target) .'" >'. $image .'</a>';
      }
    }

    $output = '';

    $output .= '<div class="htmegavc-client-wrapper">';
      $output .= $image;
    $output .= '</div>';

    echo wp_kses_post($output);

  } else if($style == '4'){


    $output = '';
    $output .= '<div class="htmegavc-client-wrapper">';
    foreach($image_list as $item){
      $image =  isset($item['attachement_id']) && $item['attachement_id'] ? wp_get_attachment_image($item['attachement_id'], 'large') : '<img src="https://placeholdit.co//i/203x178">';


      $link_arr = explode('|', $item['link_to_image']);
      if(count($link_arr) > 1){
        $link_url  =  urldecode(str_replace('url:', '', $link_arr[0]));
        $link_target  =  urldecode(str_replace('target:', '', $link_arr[2]));

        if($link_url){
          $image = '<a  href="'. esc_url($link_url) .'" target="'. esc_attr($link_target) .'" >'. $image .'</a>';
        }
      }

      $output .= '<div class="htmegavc-single-client">';
      $output .= $image;
      $output .= '</div>';
    }
    $output .= '</div><!-- /.client-wrapper -->';

    echo wp_kses_post($output);


  } else if($style == '2' || $style == '3' || $style == '6'){
    $output = '';
    $output .= '<ul class="htmegavc-brand-list">';
    foreach($image_list as $item){
      $output .= '<li style="border-color:'. $border_color .'; border-width:'. $border_width .';">';

      $image = isset($item['attachement_id']) && $item['attachement_id'] ? wp_get_attachment_image($item['attachement_id'], 'large') : '<img src="https://placeholdit.co//i/250x150">';

      //link
      $link_arr = explode('|', $item['link_to_image']);
      if(count($link_arr) > 1){
        $link_url  =  urldecode(str_replace('url:', '', $link_arr[0]));
        $link_target  =  urldecode(str_replace('target:', '', $link_arr[2]));

        if($link_url){
          $image = '<a  href="'. esc_url($link_url) .'" target="'. esc_attr($link_target) .'" >'. $image .'</a>';
        }
      }

      $output .= $image;
      $output .= '</li>';
    } // end foreach

    $output .= '</ul>';

   echo wp_kses_post( $output );

  } else {
    $output = '';
    $output .= '<div class="htb-row">';
    foreach($image_list as $item){
      $output .= '<div class="htmegavc-single-partner">';

      $image = isset($item['attachement_id']) && $item['attachement_id'] ? wp_get_attachment_image($item['attachement_id'], 'large') : '<img src="https://placeholdit.co//i/250x150">';
      $link_arr = explode('|', $item['link_to_image']);
      if(count($link_arr) > 1){
        $link_url  =  urldecode(str_replace('url:', '', $link_arr[0]));
        $link_target  =  urldecode(str_replace('target:', '', $link_arr[2]));

        if($link_url){
          $image = '<a  href="'. esc_url($link_url) .'" target="'. esc_attr($link_target) .'" >'. $image .'</a>';
        }
      }

      $output .= $image;

      $output .= '</div>';
    }
    $output .= '</div><!-- /.row -->';

    echo wp_kses_post($output);
  }
?>

</div>

<?php
  return ob_get_clean();
}

    public function integrateWithVC() {
    
        /*
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("HT Brands", 'htmegavc'),
            "description" => __("Add brands to your page", 'htmegavc'),
            "base" => "htmegavc_brands",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegavc_countdown_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
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
                    'param_name' => 'image_list',
                    "heading" => __("Brands List", 'text_domainn'),
                    'type' => 'param_group',
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '1', '2', '3', '4' ),
                    ],
                    'params' => array(
                       array(
                           'param_name' => 'attachement_id',
                           'heading' => __( 'Brand Image', 'htmegavc' ),
                           'type' => 'attach_image',
                       ),
                       array(
                           'param_name' => 'link_to_image',
                           'heading' => __( 'Link to this image ', 'htmegavc' ),
                           'type' => 'vc_link',
                           'value' => 'url:#',
                       ),
                    )
                ),
                array(
                    'param_name' => 'attachement_id',
                    'heading' => __( 'Brand Image', 'htmegavc' ),
                    'type' => 'attach_image',
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '5', '7' ),
                    ],
                ),
                array(
                    'param_name' => 'link_to_image',
                    'heading' => __( 'Link to this image ', 'htmegavc' ),
                    'type' => 'vc_link',
                    'value' => 'url:#',
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '5', '7' ),
                    ],
                ),
                array(
                    'param_name' => 'border_color',
                    'heading' => __( 'Border color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '2', '3', '4' ),
                    ],
                ),
                array(
                    'param_name' => 'border_type',
                    'heading' => __( 'Border Style', 'htmegavc' ),
                    'type' => 'textfield',
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '2', '3', '4' ),
                    ],
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'border_width',
                    'heading' => __( 'Border Width', 'htmegavc' ),
                    'type' => 'textfield',
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '2', '3', '4' ),
                    ],
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'box_shadow',
                  'heading' => __( 'Box Shadow', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __( 'Example value: 0 0 10px rgba(0, 0, 0, 0.1) <a target="_blank" href="https://www.w3schools.com/cssref/css3_pr_box-shadow.asp">Learn More</a>', 'htmegavc' ),
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '5' ),
                  ],
                  'group'  => __( 'Styling', 'htmegavc' ),
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
new Htmega_Vc_Brands();