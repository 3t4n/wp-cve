<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Animated_Heading extends WPBakeryShortCode{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_animated_heading', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_register_style( 'htmegavc_animated_heading', plugins_url('css/animated-heading.css', __FILE__) );
      wp_enqueue_style( 'htmegavc_animated_heading' );

      wp_register_script( 'htmegavc_animated_heading', plugins_url('js/animated-heading.js', __FILE__) );
      wp_enqueue_script( 'htmegavc_animated_heading' );
    }
 
    public function integrateWithVC() {
 
        /*
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("HT Animated Heading", 'htmegavc'),
            "description" => __("Add Animated heading to your page", 'htmegavc'),
            "base" => "htmegavc_animated_heading",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegvc_animated_heading_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
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
                    "param_name" => "animation_type",
                    "heading" => __("Animation Type", 'htmegavc'),
                    "type" => "dropdown",
                    "default_set" => 'type',
                    'value' => [
                        __( 'Type', 'htmegavc' )  =>  'type',
                        __( 'Loading bar', 'htmegavc' )  =>  'loading_bar',
                        __( 'Slide', 'htmegavc' )  =>  'slide',
                        __( 'Zoom', 'htmegavc' )  =>  'zoom',
                        __( 'Push', 'htmegavc' )  =>  'push',
                        __( 'Rotate 1', 'htmegavc' )  =>  'rotate_1',
                        __( 'Rotate 2', 'htmegavc' )  =>  'rotate_2',
                        __( 'Rotate 3', 'htmegavc' )  =>  'rotate_3',
                        __( 'BG Image', 'htmegavc' )  =>  'bg_immage',
                    ],
                ),
                array(
                    'param_name' => 'animated_before_text',
                    'heading' => __( 'Heading Before Text', 'htmegavc' ),
                    'type' => 'textfield',
                ),
                array(
                    'param_name' => 'animated_text_list',
                    'heading' => __( 'Animated Texts', 'htmegavc' ),
                    'type' => 'textarea',
                    'description' =>  __( 'Put each animated title separated by comma', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'animated_after_text',
                    'heading' => __( 'Heading After Text', 'htmegavc' ),
                    'type' => 'textfield',
                ),

                // customizations
                array(
                    'param_name' => 'heading_bg_image',
                    'heading' => __( 'Heading Bg Image', 'htmegavc' ),
                    'type' => 'attach_image',
                    'description' =>  __( 'Background image of the heading', 'htmegavc' ),
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'heading_text_color',
                    'heading' => __( 'Heading Text Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'animated_heading_text_color',
                    'heading' => __( 'Animated Heading Text Color', 'htmegavc' ),
                    'type' => 'colorpicker',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),


                // typography

                // title
                array(
                    "type" => "htmegavc_param_heading",
                    "text" => __("Heading Typography","htmegavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Typography', 'htmegavc' ),
                ),
                array(
                  'type' => 'checkbox',
                  'heading' => __( 'Use google font?', 'htmegavc' ),
                  'param_name' => 'heading_text_use_google_font',
                  'description' => __( 'Use font family from google font.', 'htmegavc' ),
                  'group'  => __( 'Typography', 'htmegavc' ),
                ),
                array(
                  'type' => 'google_fonts',
                  'param_name' => 'heading_text_google_font',
                  'group'  => __( 'Typography', 'htmegavc' ),
                  'settings' => array(
                    'fields' => array(
                      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
                      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
                    ),
                  ),
                  'dependency' =>[
                      'element' => 'heading_text_use_google_font',
                      'value' => array( 'true' ),
                  ],
                ),
                array(
                  'param_name' => 'heading_text_typography',
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
                    "text" => __("Animated Text Typography","htmegavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Typography', 'htmegavc' ),
                ),
                array(
                  'type' => 'checkbox',
                  'heading' => __( 'Use google font?', 'htmegavc' ),
                  'param_name' => 'animated_text_use_google_font',
                  'description' => __( 'Use font family from google font.', 'htmegavc' ),
                  'group'  => __( 'Typography', 'htmegavc' ),
                ),
                array(
                  'type' => 'google_fonts',
                  'param_name' => 'animated_text_google_font',
                  'group'  => __( 'Typography', 'htmegavc' ),
                  'settings' => array(
                    'fields' => array(
                      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
                      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
                    ),
                  ),
                  'dependency' =>[
                      'element' => 'animated_text_use_google_font',
                      'value' => array( 'true' ),
                  ],
                ),
                array(
                  'param_name' => 'animated_text_typography',
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

    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
            'style' => '1', // 1-7
            'animation_type' => 'type',
            'animated_before_text' => '',
            'animated_text' => '',
            'animated_text_list' => '',
            'animated_after_text' => '',
            'animation_class' => '',

            'heading_bg_image' => '',
            'heading_text_color' => '',
            'animated_heading_text_color' => '',

            'heading_text_use_google_font' => '',
            'heading_text_google_font' => '',
            'heading_text_typography' => '',

            'animated_text_use_google_font' => '',
            'animated_text_google_font' => '',
            'animated_text_typography' => '',
            
            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));

        // wrapper class
        $wrapper_class_arr = array();
        
        $unique_class = uniqid('htmegavc_animated_heading_');
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_animated_heading__wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        // add wrapper class
        $wrapper_class_arr[] =  'htmegavc-animated-heading htmegavc-style-'. $style;

        // join all wrapper class
        $wrapper_class = implode(' ', $wrapper_class_arr);

        // animation class
        if($animation_type == 'type'){
            $animation_class = ' cd-headline letters type';
        } else {
            $animation_class = str_replace('_', '-', $animation_type) . ' cd-headline';
        }

        $animated_text_list = explode(',', $animated_text_list);


        // customization
        $heading_bg_image = wp_get_attachment_image_src($heading_bg_image, 'full');
        $heading_bg_image = isset($heading_bg_image[0]) ? $heading_bg_image[0] : '';


        // load google font
        $google_font_data1 = htmegavc_build_google_font_data($heading_text_google_font);
        if ( 'true' == $heading_text_use_google_font && isset( $google_font_data1['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data1['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data1['values']['font_family'] );
        }
        // concate google font properties and other properties
        $heading_text_google_font = htmegavc_build_google_font_style($google_font_data1);
        $heading_text_typography = htmegavc_combine_font_container($heading_text_typography.';'.$heading_text_google_font);

        $google_font_data2 = htmegavc_build_google_font_data($animated_text_google_font);
        if ( 'true' == $heading_text_use_google_font && isset( $google_font_data2['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data2['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data2['values']['font_family'] );
        }
        // concate google font properties and other properties
        $animated_text_google_font = htmegavc_build_google_font_style($google_font_data2);
        $animated_text_typography = htmegavc_combine_font_container($animated_text_typography.';'.$animated_text_google_font);


        $output = '';
        $output .= '<style>';
        if($heading_bg_image){
            $output .= ".$unique_class.htmegavc-animated-heading .cd-headline{background-image:url($heading_bg_image)}";
        }
        $output .= ".$unique_class.htmegavc-animated-heading .cd-headline span{color:$heading_text_color;}";
        $output .= ".$unique_class.htmegavc-animated-heading .cd-headline span b.is-visible{color:$animated_heading_text_color;}";
        $output .= "</style>";

        ob_start(); ?>

        <div class="<?php echo esc_attr( $wrapper_class ); ?>">
            <h4 class="text-center <?php echo esc_attr( $animation_class );  ?>" style="<?php echo esc_attr($heading_text_typography); ?>">

                <?php
                    if( !empty( $animated_before_text ) ){
                        echo '<span class="beforetext">'. esc_html( $animated_before_text ).'</span>';
                    }

                    if( is_array( $animated_text_list ) && count( $animated_text_list ) > 0 ):
                ?>

                <span class="cd-words-wrapper" style="<?php echo esc_attr($animated_text_typography); ?>">
                    <?php foreach ($animated_text_list as $key => $value): ?>
                        <b class="<?php echo esc_attr( $key == 0 ? 'is-visible': '' ); ?>"><?php echo esc_html( $value ); ?></b>
                    <?php endforeach ?>
                </span>

                <?php

                    endif; // list

                    if( !empty( $animated_after_text ) ){
                        echo '<span class="aftertext">'. esc_html( $animated_after_text ).'</span>';
                    }
                ?>
            </h4>
        </div>

        <?php 
        $output .= ob_get_clean();
        return $output;
  }

}

// Finally initialize code
new Htmegavc_Animated_Heading();