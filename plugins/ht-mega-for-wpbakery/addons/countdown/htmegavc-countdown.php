<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Countdown{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_countdown', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_register_style( 'htmegavc-countdown', plugins_url('css/countdown.css', __FILE__) );
      wp_register_script( 'jquery-countdown', HTMEGAVC_LIBS_URI. '/countdown/jquery-countdown.min.js' );
      wp_register_script( 'jquery-countdown-active', plugins_url('js/countdown-active.js', __FILE__), '', '', true );

      wp_enqueue_style( 'htmegavc-countdown' );
      wp_enqueue_script( 'jquery-countdown' );
      wp_enqueue_script( 'jquery-countdown-active' );
    }

    public function render_shortcode( $atts, $content = null ) {

    	extract(shortcode_atts(array(
            'style' => '1',
            'target_date' => '2010/03/01',
            'hide_day' => '',
            'hide_hour' => '',
            'hide_minute' => '',
            'hide_second' => '',
            'label_days' => '',
            'label_hours' => '',
            'label_minutes' => '',
            'label_seconds' => '',

            //style 1
            'border_color' => '',
            'timer_typography' => '',
            'label_typography' => '',
            'colon_color' => '',
            'column_width' => '',
            'column_height' => '',
            'column_gap' => '',

            // style 2
            'timer_bg_color' => '',
            'label_bg_color' => '',
            'box_shadow' => '',

            'custom_class' => '', 
            'wrapper_css' => '', 
    	),$atts));

      $wrapper_css_class = ' '. apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_countdown', $atts );
      $unique_class =  uniqid('htmega_countdown_');
      $custom_class = ' ' . $custom_class;


      $data_options = [];
      $data_options['htmegadate'] = $target_date;

      // Hide Countdownload item
      $data_options['hide_day']      = $hide_day;
      $data_options['hide_hour']    = $hide_hour;
      $data_options['hide_minute']  = $hide_minute;
      $data_options['hide_second']   = $hide_second;

      // translate Label
      $data_options['label_days'] = ! empty( $label_days ) ? $label_days : 'Days';
      $data_options['label_hours'] = ! empty( $label_hours ) ? $label_hours : 'Hours';
      $data_options['label_minutes'] = ! empty( $label_minutes ) ? $label_minutes : 'Minutes';
      $data_options['label_seconds'] = ! empty( $label_seconds ) ? $label_seconds : 'Seconds';

      // styling
      $timer_typography = htmegavc_combine_font_container($timer_typography);
      $label_typography = htmegavc_combine_font_container($label_typography);

    	ob_start();
?>

<div class="htmegavc-countdown-wrapper htmegavc-countdown-style-<?php echo esc_attr($style); ?> <?php echo esc_attr($unique_class.$wrapper_css_class.$custom_class ); ?>" >

  <div class="htmegavc-box-timer">
      <div class="htmegavc-countbox">
          <?php
              echo '<div data-countdown=\'' . wp_json_encode( $data_options ) . '\'></div>';
          ?>
      </div>
  </div>

  <style type="text/css">
    <?php if($style == '1'): ?>
  
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-1 span.ht-count{border-color: <?php echo esc_attr($border_color); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-1 span.time-count{<?php echo esc_attr($timer_typography); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-1 span.count-inner p{<?php echo esc_attr($label_typography); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-1 .ht-count::before{color:<?php echo esc_attr($colon_color); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-1 span.ht-count{width: <?php echo esc_attr($column_width); ?>;height: <?php echo esc_attr($column_height); ?>;}

    <?php elseif($style == '2'): ?>

    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-2 span.ht-count{border-color: <?php echo esc_attr($border_color); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-2 span.time-count{background-color:<?php echo esc_attr($timer_bg_color); ?>;<?php echo esc_attr($timer_typography); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-2 span.count-inner p{background-color:<?php echo esc_attr($label_bg_color); ?>;<?php echo esc_attr($label_typography); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-2 span.ht-count{width: <?php echo esc_attr($column_width); ?>;<?php echo esc_attr($column_height); ?>;}

    <?php elseif($style == '3'): ?>

    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-3 span.time-count{<?php echo esc_attr($timer_typography); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-3 span.count-inner p{<?php echo esc_attr($label_typography); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-3 .ht-count::before{color:<?php echo esc_attr($colon_color); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-3 span.ht-count{width: <?php echo esc_attr($column_width); ?>;<?php echo esc_attr($column_height); ?>; box-shadow: <?php echo esc_attr($box_shadow); ?>;}

    <?php elseif($style == '4'): ?>
    
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-4 span.time-count{<?php echo esc_attr($timer_typography); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-4 span.count-inner p{<?php echo esc_attr($label_typography); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-4 span.ht-count{width: <?php echo esc_attr($column_width); ?>;<?php echo esc_attr($column_height); ?>;}

    <?php elseif($style == '5'): ?>

    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-5 span.time-count{<?php echo esc_attr($timer_typography); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-5 span.count-inner p{<?php echo esc_attr($label_typography); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-5 .ht-count::before{color:<?php echo esc_attr($colon_color); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-5 span.ht-count{width: <?php echo esc_attr($column_width); ?>;<?php echo esc_attr($column_height); ?>;}

    <?php elseif($style == '6'): ?>

    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-6 span.time-count{<?php echo esc_attr($timer_typography); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-6 .ht-count::before{color:<?php echo esc_attr($colon_color); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-6 span.ht-count{width: <?php echo esc_attr($column_width); ?>;<?php echo esc_attr($column_height); ?>;}

    <?php elseif($style == '7'): ?>
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-7 span.time-count{<?php echo esc_attr($timer_typography); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-7 span.count-inner p{<?php echo esc_attr($label_typography); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-7 .ht-count::before{color:<?php echo esc_attr($colon_color); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-7 count-inner .time-count::before{background-color:<?php echo esc_attr($timer_bg_color); ?>;}
    .<?php echo esc_attr($unique_class)?>.htmegavc-countdown-style-7 span.ht-count{background-color:<?php echo esc_attr($label_bg_color); ?>;width: <?php echo esc_attr($column_width); ?>;<?php echo esc_attr($column_height); ?>;}

    <?php endif; ?>
  </style>

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
            "name" => __("HT Count Down", 'htmegavc'),
            "description" => __("Add Countdown section to your page", 'htmegavc'),
            "base" => "htmegavc_countdown",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegvc_countdown_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
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
                  "param_name" => "target_date",
                  "heading" => __( "Due Date", "htmegavc" ),
                  "type" => "textfield",
                  'value' => '2010/03/01',
                  'description' => __( 'Add your desired date like this example formate: YYYY/MM/DD', 'htmegavc' ),
              ),
                array(
                  'param_name' => 'hide_day',
                  'heading' => __( 'Hide Day?', 'htmegavc' ),
                  'type' => 'checkbox',
                  'edit_field_class' => 'vc_column vc_col-sm-3',
                ),
                array(
                  'param_name' => 'hide_hour',
                  'heading' => __( 'Hide Hours?', 'htmegavc' ),
                  'type' => 'checkbox',
                  'edit_field_class' => 'vc_column vc_col-sm-3',
                ),
                array(
                  'param_name' => 'hide_minute',
                  'heading' => __( 'Hide Minutes?', 'htmegavc' ),
                  'type' => 'checkbox',
                  'edit_field_class' => 'vc_column vc_col-sm-3',
                ),
                array(
                  'param_name' => 'hide_second',
                  'heading' => __( 'Hide Seconds?', 'htmegavc' ),
                  'type' => 'checkbox',
                  'edit_field_class' => 'vc_column vc_col-sm-3',
                ),
                array(
                    "type" => "htmegavc_param_heading",
                    "text" => __("Translate Labels","htmegavc"),
                    "param_name" => "package_label",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                ),
                array(
                  'param_name' => 'label_days',
                  'heading' => __( 'Label Days', 'htmegavc' ),
                  'type' => 'textfield',
                  'edit_field_class' => 'vc_column vc_col-sm-6',
                ),
                array(
                  'param_name' => 'label_hours',
                  'heading' => __( 'Label Hours', 'htmegavc' ),
                  'type' => 'textfield',
                  'edit_field_class' => 'vc_column vc_col-sm-6',
                ),
                array(
                  'param_name' => 'label_minutes',
                  'heading' => __( 'Label Minutes', 'htmegavc' ),
                  'type' => 'textfield',
                  'edit_field_class' => 'vc_column vc_col-sm-6',
                ),
                array(
                  'param_name' => 'label_seconds',
                  'heading' => __( 'Label Seconds', 'htmegavc' ),
                  'type' => 'textfield',
                  'edit_field_class' => 'vc_column vc_col-sm-6',
                ),


                // styling
                array(
                  'param_name' => 'border_color',
                  'heading' => __( 'Border Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '1', '2' ),
                  ],
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                    "type" => "htmegavc_param_heading",
                    "text" => __("Timer Typography","htmegavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Typography', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'timer_typography',
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
                    'dependency' =>[
                        'element' => 'style',
                        'value' => array( '1', '2', '3', '4', '5', '7' ),
                    ],
                    'group'  => __( 'Typography', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'label_typography',
                  'type' => 'font_container',
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '1', '2', '3', '4', '5', '7' ),
                  ],
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
                  'param_name' => 'colon_color',
                  'heading' => __( 'Colon Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '1',  '3', '5', '6', '7' ),
                  ],
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'column_width',
                  'heading' => __( 'Column Width', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __('Width for each item. (day,hour,min,sec). Eg: 150px', 'htmegavc'),
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'column_height',
                  'heading' => __( 'Column Height', 'htmegavc' ),
                  'type' => 'textfield',
                  'description' => __('Height for each item. (day,hour,min,sec). Eg: 150px', 'htmegavc'),
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'timer_bg_color',
                  'heading' => __( 'Timer Bg Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '2', '7' ),
                  ],
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'label_bg_color',
                  'heading' => __( 'Label Bg Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'dependency' =>[
                      'element' => 'style',
                      'value' => array( '2', '7' ),
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
                      'value' => array( '3' ),
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
new Htmegavc_Countdown();