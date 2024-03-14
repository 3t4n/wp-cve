<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Progress_Bar{
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );
 
        // creating a shortcode addon
        add_shortcode( 'htmegavc_progress_bar', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_enqueue_script( 'jquery-easy-pie-chart', HTMEGAVC_LIBS_URI. '/easy-pie-chart/jquery-easy-pie-chart.js', array('jquery', 'waypoints'), '', true );

      wp_register_style( 'animate', HTMEGAVC_LIBS_URI. '/animate-css/animate.css' );
      wp_enqueue_style( 'animation' );

      wp_register_style( 'htmegavc_progress_bar', plugins_url('/css/progress-bar.css', __FILE__ ));
      wp_enqueue_style( 'htmegavc_progress_bar' );

    }


    
    /*
    Shortcode logic how it should be rendered
    */
    public function render_shortcode( $atts, $content = null ) {

    	extract(shortcode_atts(array(
            'htmega_progress_bar_style' => 'horizontal', 
            'unit' => '%', 
            'bar_bg_color' => '', 
            'bar_text_color' => '', 
            'bar_color' => '', 
            'bar_weight' => '',
            'bar_width' => '',
            'value_style' => 'value_top_1',
            'bar_border_radious' => '',
            'show_indicator' => '',
            'show_stripes' => '',
            'indicator_style' => 'style_1',
            'css' => '',
            'lg_item' => '',
            'line_width' => '10',
            'circle_style_theme' => '1',
            'rotate' => '0',
            'track_color' => '',
    	),$atts));

        $htmega_progressbar_list = isset($atts['htmega_progressbar_list']) ? vc_param_group_parse_atts($atts['htmega_progressbar_list']) : array();
        $wrapper_class =  uniqid('progress_bar_');
        $indicator_style = $show_indicator ? $indicator_style = 'progress-indicator-'. $indicator_style : '';


    	ob_start();
?>

<?php

$output = '';	
if($htmega_progress_bar_style == 'circle'):
	$output .= '<style>';
	$output .= ".$wrapper_class .radial-progress-single .radial-progress span{color:$bar_text_color;}";
	$output .= ".$wrapper_class .radial-progress-single h5.radial-progress-title{color:$bar_text_color;}";
	$output .= "</style>";

    $bar_width = $bar_width ? $bar_width : 130;
	$lg_item    = $lg_item == '5' ? 'five' : floor(12 / $lg_item);

	 $column_classes = array();
	 $column_classes[] = 'htb-col-lg-'. $lg_item;
?>

    <div class="htmegavc-progress_wrapper <?php echo esc_attr($wrapper_class); ?>">
        <div class="htb-row">
                <?php foreach($htmega_progressbar_list as $key => $item):
                    $value = $item['value'] ? $item['value'] : '';
                    $bar_bg_color = isset($item['bar_bg_color_2']) ? $bar_bg_color = $item['bar_bg_color_2'] : $bar_color;
                ?>

                <div class="<?php echo esc_attr( implode(' ', $column_classes) ); ?> <?php echo esc_attr('item_'. $key); ?>">
                <!-- Single Radial Progress -->
                    <div class="radial-progress-single theme_<?php echo esc_attr($circle_style_theme) ?>">
                        <div class="radial-progress" data-percent="<?php echo esc_attr($value) ?>" data-bar-color="<?php echo esc_attr($bar_bg_color) ?>" data-track-color="<?php echo esc_attr( $track_color); ?>">
                            <span><?php echo esc_html($value) ?><?php echo esc_html($unit) ?></span>
                        </div>
                        <h5 class="radial-progress-title"><?php echo esc_html($item['title']); ?></h5>
                    </div>
                <!--// Single Radial Progress -->
                </div>

                <style type="text/css">
                    .<?php echo esc_attr($wrapper_class); ?> .item_<?php echo esc_attr($key); ?>  .radial-progress-single.theme_2 .radial-progress::before {
                        background:<?php echo $bar_bg_color; ?>;
                    }
                    .<?php echo esc_attr($wrapper_class); ?> .item_<?php echo esc_attr($key); ?> .radial-progress-single.theme_3 .radial-progress{
                        border-color: <?php echo $bar_bg_color; ?>;
                    }
                </style>
                <?php endforeach; ?>

        </div>
    </div>

    <script type="text/javascript">
        jQuery( document ).ready(function() {
        	'use strict';
            jQuery('.<?php echo esc_attr($wrapper_class); ?> .radial-progress').waypoint(function(){
                jQuery('.<?php echo esc_attr($wrapper_class); ?> .radial-progress').easyPieChart({
                    lineWidth: '<?php echo $line_width; ?>',
                    trackColor: false,
                    scaleLength: 0,
                    rotate: <?php echo esc_js( $rotate ); ?>,
                    barColor: '#1cb9da',
                    trackColor: '#dcd9d9',
                    lineCap: 'square',
                    size: 150
                });

            }, {
                triggerOnce: true,
                offset: 'bottom-in-view'
            });
        });
    </script>

<?php elseif($htmega_progress_bar_style == 'vertical'):
?>
    <div class="htmegavc-progress_wrapper <?php echo esc_attr($wrapper_class); ?>">
        <div class="htb-row">


            <?php foreach($htmega_progressbar_list as $key => $item):
                $value = $item['value'] ? $item['value'] : '';
                $stripes_class = $show_stripes ? 'htb-progress-bar-striped' : '';
                $bar_bg_color = isset($item['bar_bg_color_2']) ? $bar_bg_color = $item['bar_bg_color_2'] : '';
            ?>
            <div class="<?php echo esc_attr('item_'. $key); ?>" style="width: <?php echo esc_attr($bar_width); ?>">
                <!-- Start Single Progress Bar -->
                <div class="htmegavc-single-skill htmegavc-progress-bar-vertical">
                    <div class="htmegavc-progress">
                        <div class="htb-progress-bar htb-progress-bar-danger <?php echo esc_attr($stripes_class) ?> wow fadeInUp" data-wow-duration="0.4s" data-wow-delay=".4s" role="progressbar" aria-valuenow="<?php echo esc_attr($value) ?>" aria-valuemin="0" aria-valuemax="100" style="height: <?php echo esc_attr($value) ?>%; ">
                            <span class="lable"><?php echo esc_html($value) ?><?php echo esc_html($unit) ?></span>
                        </div>
                    </div>
					
					<?php if($item['title']){ echo '<p>'. esc_html($item['title']) .'</p>'; } ?>
                </div>
                 <!-- End Single Progress Bar -->
            </div>
            <style>
                .<?php echo esc_html($wrapper_class)?> .item_<?php echo $key; ?> .progress-bar{background-color:<?php echo $bar_bg_color; ?>;}
            </style>
            <?php endforeach; ?>

        </div>
    </div> <!-- End htmegavc-progress_wrapper -->

<?php elseif($htmega_progress_bar_style == 'horizontal'):?>
    <div class="htmegavc-progress_wrapper <?php echo esc_attr($wrapper_class); ?>">

        <?php foreach($htmega_progressbar_list as $key => $item):
            $value = $item['value'] ? $item['value'] : '';

            $lable_class = '';
            $value_style == 'value_top_1' ? $lable_class =  'progress-label-top' : '';
            $value_style == 'value_top_2' ? $lable_class =  'progress-label-top progress-label-2' : '';
            $value_style == 'value_top_3' ? $lable_class = 'progress-label-top progress-label-3' : '';
            $value_style == 'value_inside' ? $lable_class = '' : '';
        ?>

        <div class="htmegavc-single-skill <?php echo esc_attr('item_'. $key); ?>">
            <p><?php echo esc_html($item['title']); ?></p>
            <div style="background-color: <?php echo esc_attr($bar_bg_color) ?>; height: <?php echo esc_attr($bar_weight) ?>" class="htmegavc-progress  <?php echo esc_attr($lable_class. ' '. $indicator_style); ?> ">
                <div class="htb-progress-bar wow fadeInLeft htmegavc-progress-bar-bg--1" data-wow-duration="0.5s" data-wow-delay=".3s" role="progressbar" style="width: <?php echo esc_attr($value); ?>%; border-radius: <?php echo esc_attr($bar_border_radious); ?>; background: <?php echo esc_attr($bar_color) ?>; height: <?php echo esc_attr($bar_weight) ?>" aria-valuen ow="<?php echo esc_attr($item['value']); ?>" aria-valuemin="0" aria-valuemax="100">
                    <span class="htmegavc-percent-label"><?php echo esc_html($item['value']); ?><?php echo esc_html($unit) ?></span>
                </div>
            </div>
        </div>
        
        <?php endforeach; ?>

    </div>
<?php endif; ?>



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
            "name" => __("HT Progress Bar", 'text_domainn'),
            "description" => __("Add progress Bar to your page", 'text_domainn'),
            "base" => "htmegavc_progress_bar",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegvc_progress_bar_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('HT Mega Addons', 'text_domainn'),
            "params" => array(
                    array(
                      "param_name" => "htmega_progress_bar_style",
                      "heading" => __("Style", 'text_domainn'),
                      "type" => "dropdown",
                      "default_set" => 'horizontal',
                      "value"       => array(
                        __( 'Horizontal', 'htmegavc' )     => 'horizontal' ,
                        __( 'Vertical', 'htmegavc' )       => 'vertical'   ,
                        __( 'Circle', 'htmegavc' )         => 'circle'     ,
                      ),
                    ),

                    // Progress bar repeater 1
                    array(
                        'param_name' => 'htmega_progressbar_list',
                        "heading" => __("Progress Bar", 'text_domainn'),
                        'type' => 'param_group',
                        "dependency"  => Array( 
                            "element" => "htmega_progress_bar_style",
                            "value" => Array('horizontal' , 'vertical', 'circle')
                        ),
                        'value' => urlencode( json_encode (array(
                            array(
                                'title'         => __('Photoshop','htmegavc'),
                                'value'         => '70',
                            ),
                            array(
                                'title'         => __('Joomla','htmegavc'),
                                'value'         => '80',
                            ),
                            array(
                                'title'         => __('WordPress','htmegavc'),
                                'value'         => '90',
                            ),
                         ))),
                        'params' => array(
                           array(
                               'param_name' => 'title',
                               'heading' => __( 'Title', 'htmegavc' ),
                               'type' => 'textfield',
                               "default_set" => __( 'WordPress' , 'htmegavc' ),
                           ),
                           array(
                               'param_name' => 'value',
                               'heading' => __( 'Progress Bar Value', 'htmegavc' ),
                               'type' => 'textfield',
                               "default_set" => __( '50' , 'htmegavc' ),
                           ),
                           array(
                               'param_name' => 'bar_bg_color_2',
                               'heading' => __( 'Bar Bg Color', 'htmegavc' ),
                               'type' => 'colorpicker',
                               "dependency"  => Array( 
                                   "element" => "htmega_progress_bar_style",
                                   "value" => Array('vertical')
                               ),
                           ),
                        )
                    ),
                     array(
                      "param_name" => "lg_item",
                      "heading" => __("Columns on desktop", 'text_domainn'),
                      "type" => "dropdown",
                      "default_set" => 'horizontal',
                      "value"       => array(
                        esc_html__( 'Column One', 'htmegavc' ) => '1',
                        esc_html__( 'Column Two', 'htmegavc' ) => '2',
                        esc_html__( 'Column Three', 'htmegavc' ) => '3',
                        esc_html__( 'Column Four', 'htmegavc' ) => '4',
                        esc_html__( 'Column Five', 'htmegavc' ) => '5',
                        esc_html__( 'Column Six', 'htmegavc' ) => '6',
                      ),
                    ),


                    array(
                        'param_name' => 'unit',
                        'heading' => __( 'Progress Bar Unit', 'htmegavc' ),
                        'type' => 'textfield',
                        'description' => __('Measurement units. Eg: %, px etc', 'htmegavc'),
                        "default_set" => __( '%' , 'htmegavc' ),
                    ),
                    array(
                      "param_name" => "value_style",
                      "heading" => __("Value Style", 'text_domainn'),
                      "type" => "dropdown",
                      "default_set" => 'value_top_1',
                      "value"       => array(
                        __( 'Value Top 1', 'htmegavc' )     => 'value_top_1' ,
                        __( 'Value Top 2', 'htmegavc' )       => 'value_top_2'   ,
                        __( 'Value Top 3', 'htmegavc' )         => 'value_top_3'     ,
                        __( 'Value Inside', 'htmegavc' )         => 'value_inside'     ,
                      ),
                      "dependency"  => Array( 
                          "element" => "htmega_progress_bar_style",
                          "value" => Array('horizontal')
                      ),
                    ),
                    array(
                        'param_name' => 'bar_weight',
                        'heading' => __( 'Bar height', 'htmegavc' ),
                        'type' => 'textfield',
                    ),
                    array(
                        'param_name' => 'bar_width',
                        'heading' => __( 'Bar width', 'htmegavc' ),
                        'type' => 'textfield',
                        "dependency"  => Array( 
                            "element" => "htmega_progress_bar_style",
                            "value" => Array('vertical', 'circle')
                        ),
                    ),
                    array(
                        'param_name' => 'bar_text_color',
                        'heading' => __( 'Bar Text Color', 'htmegavc' ),
                        'type' => 'colorpicker',
                    ),
                    array(
                        'param_name' => 'bar_bg_color',
                        'heading' => __( 'Bar Bg Color', 'htmegavc' ),
                        'type' => 'colorpicker',
                    ),
                    array(
                        'param_name' => 'bar_color',
                        'heading' => __( 'Bar Color', 'htmegavc' ),
                        'type' => 'colorpicker',
                    ),
                    array(
                        "param_name" => "show_indicator",
                        "heading" => __("Show Bar Indicator", 'text_domainn'),
                        "type" => "checkbox",
                    ),
                    array(
                      "param_name" => "indicator_style",
                      "heading" => __("Indicator Style", 'text_domainn'),
                      "type" => "dropdown",
                      "value"       => array(
                        __( 'Style 1', 'htmegavc' )     => 'style_1',
                        __( 'Style 2', 'htmegavc' )     => 'style_2',
                      ),
                      "dependency"  => Array( 
                          "element" => "show_indicator",
                          "value" => Array('true')
                      ),
                    ),
                    array(
                        "param_name" => "show_stripes",
                        "heading" => __("Show Stripes", 'text_domainn'),
                        "type" => "checkbox",
                    ),
                    array(
                        'param_name' => 'line_width',
                        'heading' => __( 'Line Width', 'htmegavc' ),
                        'type' => 'textfield',
                        "dependency"  => Array( 
                            "element" => "htmega_progress_bar_style",
                            "value" => Array('circle')
                        ),
                    ),
                    array(
                        'param_name' => 'track_color',
                        'heading' => __( 'Track Color', 'htmegavc' ),
                        'type' => 'colorpicker',
                        "dependency"  => Array( 
                            "element" => "htmega_progress_bar_style",
                            "value" => Array('circle')
                        ),
                    ),
                    array(
                      "param_name" => "circle_style_theme",
                      "heading" => __("Theme", 'text_domainn'),
                      "type" => "dropdown",
                      "value"       => array(
                        __( 'Theme 1', 'htmegavc' )     => '1',
                        __( 'Theme 2', 'htmegavc' )     => '2',
                        __( 'Theme 3', 'htmegavc' )     => '3',
                      ),
                      "dependency"  => Array( 
                          "element" => "htmega_progress_bar_style",
                          "value" => Array('circle')
                      ),
                    ),
            )
        ) );
    }
}

// Finally initialize code
new Htmegavc_Progress_Bar();