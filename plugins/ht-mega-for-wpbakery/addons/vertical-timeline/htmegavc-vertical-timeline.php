<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Vertical_Timeline extends WPBakeryShortCode{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_vertical_timeline', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
    	wp_register_style( 'htmegavc-vertical-timeline', plugins_url('css/vertical-timeline.css', __FILE__));
    	wp_enqueue_style( 'htmegavc-vertical-timeline' );
    }


    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
           
            // Content
            'verticle_timeline_layout'	=> '1',
            'custom_content_list'	=> '',

            // Styling
            'timeline_border_color'	=>	'',
            'timeline_line_color'	=>	'',
            'content_title_color'	=> '',
            'content_date_color'	=> '',
            'content_text_color'	=> '',


            // Title Typography
            'content_title_use_google_font'	=> '',
            'content_title_google_font'	=> '',
            'content_title_typography'	=> '',

            // Date Typography
            'content_date_use_google_font'	=> '',
            'content_date_google_font'	=> '',
            'content_date_typography'	=> '',

            // Description Typography
            'content_text_use_google_font'	=> '',
            'content_text_google_font'	=> '',
            'content_text_typography'	=> '',

            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));


        // wrapper class
        $wrapper_class_arr = array();
        
        $unique_class = uniqid('htmegavc_vertical_timeline_');
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_vertical_timeline_wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        // add wrapper class
        $wrapper_class_arr[] =  'htmegavc_vertical_timeline';
        $wrapper_class_arr[] =   'htmegavc-verctimeline-wrapper htmegavc-verticletimeline-style-'.$verticle_timeline_layout;

        // join all wrapper class
        $wrapper_class = implode(' ', $wrapper_class_arr);

        // Styling
        $title_inline_style = $date_inline_style = $description_inline_style = '';

        $title_inline_style .= "color:$content_title_color;";
        $date_inline_style .= "color:$content_date_color;";
        $description_inline_style .= "color:$content_text_color;";

        // Typography
        // Title Typography
        $google_font_data1 = htmegavc_build_google_font_data($content_title_google_font);
        if ( 'true' == $content_title_use_google_font && isset( $google_font_data1['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data1['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data1['values']['font_family'] );
        }

        // concate google font properties and other properties
        $content_title_google_font = htmegavc_build_google_font_style($google_font_data1);
        $title_inline_style .= htmegavc_combine_font_container($content_title_typography.';'.$content_title_google_font);

        // Date Typography
        $google_font_data2 = htmegavc_build_google_font_data($content_date_google_font);
        if ( 'true' == $content_date_use_google_font && isset( $google_font_data2['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data2['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data2['values']['font_family'] );
        }

        // concate google font properties and other properties
        $content_date_google_font = htmegavc_build_google_font_style($google_font_data2);
        $date_inline_style .= htmegavc_combine_font_container($content_date_typography.';'.$content_date_google_font);

        // Description Typography
        $google_font_data2 = htmegavc_build_google_font_data($content_text_google_font);
        if ( 'true' == $content_text_use_google_font && isset( $google_font_data2['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data2['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data2['values']['font_family'] );
        }

        // concate google font properties and other properties
        $content_text_google_font = htmegavc_build_google_font_style($google_font_data2);
        $description_inline_style .= htmegavc_combine_font_container($content_text_typography.';'.$content_text_google_font);


        $output = '';
        $output .= '<style>';
        $output .= "
			.$unique_class.htmegavc-verctimeline-wrapper .htmegavc-ver-timeline .vertical-time .vertical-date{ border-color: $timeline_border_color; }
			.$unique_class.htmegavc-verctimeline-wrapper .htmegavc-ver-timeline .vertical-time .vertical-date::before{ border-color: transparent transparent transparent $timeline_border_color; }
			.$unique_class.htmegavc-verctimeline-wrapper .htmegavc-ver-timeline.vertical-reverse .vertical-time .vertical-date::before{ border-color: transparent $timeline_border_color transparent transparent; }
			.$unique_class.htmegavc-verctimeline-wrapper .htmegavc-ver-timeline .timeline-content::before{ border-color: $timeline_border_color; }

			.$unique_class.htmegavc-verctimeline-wrapper .htmegavc-ver-timeline::before{ background-color: $timeline_line_color; }
			.$unique_class.htmegavc-verctimeline-wrapper .htmegavc-ver-timeline .vertical-time::before{ border-color: $timeline_line_color; }

			.$unique_class.htmegavc-verctimeline-wrapper > div .timeline-content h6.time_line_title{ $title_inline_style }
			.$unique_class.htmegavc-verctimeline-wrapper > div .vertical-date span.month{ $date_inline_style }
			.$unique_class.htmegavc-verctimeline-wrapper > div .timeline-content{ $description_inline_style }

        ";
        $output .= '</style>';

        ob_start();
        $custom_content_list = isset($atts['custom_content_list']) ? vc_param_group_parse_atts($atts['custom_content_list']) : array();

        $item_class = 'htmegavc-ver-timeline';
        if( $verticle_timeline_layout> 1 ){
            $item_class = 'htmegavc-ver-timeline--'.$verticle_timeline_layout;
        }else{
            $item_class = $item_class;
        }
        
        ?>

        <div class="<?php echo esc_attr( $wrapper_class ); ?>" >
		
			<?php
			    $i = 0;
			    if(  $custom_content_list ):
			        foreach ( $custom_content_list as $items ):
			            $i++;
			?>
			   
			    <?php if( $i % 2 == 0 ): ?>
			        <div class="<?php echo esc_attr( $item_class ); ?> vertical-reverse">
			            <?php if( $items['content_date'] ): ?>
			                <div class="vertical-time">
			                    <div class="vertical-date">
			                        <span class="month"><?php echo wp_kses_post($items['content_date']); ?></span>
			                    </div>
			                </div>
			            <?php endif; if( isset($items['content_text']) || isset($items['content_title']) ):?>
			                <div class="timeline-content">
			                    <?php
			                        if( $verticle_timeline_layout == 3 ){
			                            echo '<div class="content">';
			                        }
			                        if( isset($items['content_title']) ){
			                            echo '<h6 class="time_line_title">'.esc_html( $items['content_title'] ).'</h6>'; 
			                        }
			                        echo wp_kses_post( $items['content_text'] );
			                        if( $verticle_timeline_layout == 3 ){
			                            echo '</div>';
			                        }
			                    ?>
			                </div>
			            <?php endif;?>
			        </div>

			    <?php else:?>
			        <div class="<?php echo esc_attr( $item_class ); ?>">
			            <?php if( !empty( $items['content_date'] ) ): ?>
			                <div class="vertical-time">
			                    <div class="vertical-date">
			                        <span class="month"><?php echo wp_kses_post($items['content_date']); ?></span>
			                    </div>
			                </div>
			            <?php endif; if( isset($items['content_text']) || isset($items['content_title']) ):?>
			                <div class="timeline-content">
			                    <?php
			                        if( $verticle_timeline_layout == 3 ){
			                            echo '<div class="content">';
			                        }
			                        if( isset($items['content_title']) ){
			                            echo '<h6 class="time_line_title">'.esc_html( $items['content_title'] ).'</h6>'; 
			                        }
			                        echo wp_kses_post( $items['content_text'] );
			                        if( $verticle_timeline_layout == 3 ){
			                            echo '</div>';
			                        }
			                    ?>
			                </div>
			            <?php endif;?>
			        </div>
			    <?php endif;?>

			<?php endforeach; endif; ?>

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
            "name" => __("HT Vertical Timeline", 'htmegavc'),
            "description" => __("Add Vertical Timeline to your page", 'htmegavc'),
            "base" => "htmegavc_vertical_timeline",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegavc_vertical_timeline_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('HT Mega Addons', 'htmegavc'),
            "params" => array(

            	// cotnent
            	array(
            	  "param_name" => "verticle_timeline_layout",
            	  "heading" => __("Layout", 'htmegavc'),
            	  "type" => "dropdown",
            	  "default_set" => '1',
            	  'value' => [
            	      __( 'Layout One', 'htmegavc' )  =>  '1',
            	      __( 'Layout Two', 'htmegavc' )  =>  '2',
            	      __( 'Layout Three', 'htmegavc' )  =>  '3',
            	  ],
            	),

            	array(
            	    'param_name' => 'custom_content_list',
            	    "heading" => __("Timeline List", 'text_domainn'),
            	    'type' => 'param_group',
            	    'params' => array(
            	       array(
            	           'param_name' => 'content_title',
            	           'heading' => __( 'Title', 'htmegavc' ),
            	           'type' => 'textfield',
            	       ),
            	       array(
            	           'param_name' => 'content_date',
            	           'heading' => __( 'Date', 'htmegavc' ),
            	           'type' => 'textfield',
            	       ),
            	       array(
            	           'param_name' => 'content_text',
            	           'heading' => __( 'Description', 'htmegavc' ),
            	           'type' => 'textarea',
            	       ),
            	    ),
            	    'value' => urlencode( json_encode (array(
            	        array(
            	            'content_title'         => __( 'Title', 'htmegavc' ),
            	            'content_date'         => __( 'Sep<br/>2018', 'htmegavc' ),
            	            'content_text'         => __( 'Lorem ipsum dolor sit amet, consectetur adipis icing elit, sed do eiusmod tempor incid ut labore et dolore magna aliqua Ut enim ad min.', 'htmegavc' ),
            	        ),
            	        array(
            	            'content_title'         => __( 'Title', 'htmegavc' ),
            	            'content_date'         => __( 'Oct<br/>2018', 'htmegavc' ),
            	            'content_text'         => __( 'Lorem ipsum dolor sit amet, consectetur adipis icing elit, sed do eiusmod tempor incid ut labore et dolore magna aliqua Ut enim ad min.', 'htmegavc' ),
            	        ),
            	        array(
            	            'content_title'         => __( 'Title', 'htmegavc' ),
            	            'content_date'         => __( 'Nov<br/>2018', 'htmegavc' ),
            	            'content_text'         => __( 'Lorem ipsum dolor sit amet, consectetur adipis icing elit, sed do eiusmod tempor incid ut labore et dolore magna aliqua Ut enim ad min.', 'htmegavc' ),
            	        ),
            	     ))),
            	),


            	// Styling
            	array(
            	    'param_name' => 'timeline_border_color',
            	    'heading' => __( 'Timeline Primary Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'timeline_line_color',
            	    'heading' => __( 'Timeline Line Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'content_title_color',
            	    'heading' => __( 'Title Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'content_date_color',
            	    'heading' => __( 'Date Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'content_text_color',
            	    'heading' => __( 'Description Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),


            	// Typography
            	// Title Typography
            	array(
            	    "param_name" => "package_typograpy",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Before Title Typography","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'checkbox',
            	  'heading' => __( 'Use google Font?', 'htmegavc' ),
            	  'param_name' => 'content_title_use_google_font',
            	  'description' => __( 'Use font family from google font.', 'htmegavc' ),
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'google_fonts',
            	  'param_name' => 'content_title_google_font',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
            	      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
            	    ),
            	  ),
            	  'dependency' =>[
            	      'element' => 'content_title_use_google_font',
            	      'value' => array( 'true' ),
            	  ],
            	),
            	array(
            	  'param_name' => 'content_title_typography',
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

            	// Date Typography
            	array(
            	    "param_name" => "package_typograpy",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Date Typography","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'checkbox',
            	  'heading' => __( 'Use google Font?', 'htmegavc' ),
            	  'param_name' => 'content_date_use_google_font',
            	  'description' => __( 'Use font family from google font.', 'htmegavc' ),
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'google_fonts',
            	  'param_name' => 'content_date_google_font',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
            	      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
            	    ),
            	  ),
            	  'dependency' =>[
            	      'element' => 'content_date_use_google_font',
            	      'value' => array( 'true' ),
            	  ],
            	),
            	array(
            	  'param_name' => 'content_date_typography',
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

            	// Description Typography
            	array(
            	    "param_name" => "package_typograpy",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Description Typography","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'checkbox',
            	  'heading' => __( 'Use google Font?', 'htmegavc' ),
            	  'param_name' => 'content_text_use_google_font',
            	  'description' => __( 'Use font family from google font.', 'htmegavc' ),
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'google_fonts',
            	  'param_name' => 'content_text_google_font',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
            	      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
            	    ),
            	  ),
            	  'dependency' =>[
            	      'element' => 'content_text_use_google_font',
            	      'value' => array( 'true' ),
            	  ],
            	),
            	array(
            	  'param_name' => 'content_text_typography',
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
                    'heading' => __( 'Extra class name', 'htmegavc' ),
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
new Htmegavc_Vertical_Timeline();