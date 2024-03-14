<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Video_Player extends WPBakeryShortCode{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_video_player', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
    	wp_register_script( 'jquery-magnific-popup', HTMEGAVC_LIBS_URI .'/magnific-popup/jquery.magnific-popup.min.js', '', '', '');
    	wp_enqueue_script( 'jquery-magnific-popup' );

    	wp_register_style( 'magnific-popup', HTMEGAVC_LIBS_URI . '/magnific-popup/magnific-popup.css');
    	wp_enqueue_style( 'magnific-popup' );

    	wp_register_script( 'jquery-mb-YTPlayer', HTMEGAVC_LIBS_URI .'/ytplayer/jquery.mb.YTPlayer.min.js', '', '', '');
    	wp_enqueue_script( 'jquery-mb-YTPlayer' );

    	wp_register_style( 'jquery-mb-YTPlayer', HTMEGAVC_LIBS_URI . '/ytplayer/jquery.mb.YTPlayer.min.css');
    	wp_enqueue_style( 'jquery-mb-YTPlayer' );
    	
    	wp_register_style( 'video-player', plugins_url('css/video-player.css', __FILE__));
    	wp_enqueue_style( 'video-player' );

    	wp_register_script( 'htmegavc-video-player-active', plugins_url('js/video-player-active.js', __FILE__), '', '', true);
    	wp_enqueue_script( 'htmegavc-video-player-active' );
    }


    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
            // Content
            'videocontainer' => 'self',
            'video_url'	=> 'https://www.youtube.com/watch?v=CDilI6jcpP4',
            'video_image'	=> '',
            'video_image_size'	=> '',
            'buttonicon'	=> 'fa fa-play',
            'buttontext'	=> '',

            // Video Options
            'autoplay'	=>	'',
            'soundmute'	=>	'',
            'repeatvideo'	=>	'',
            'controlerbutton'	=>	'yes',
            'videosourselogo'	=>	'yes',
            'videostarttime'	=>	'',

            // Styling
            'video_style_align'	=>	'center',

            // Button Styling
            'video_button_color'	=>	'',
            'video_button_background'	=>	'',
            'video_button_fontsize'	=>	'',
            'video_button_margin'	=>	'',
            'video_button_padding'	=>	'',
            'video_button_border_width'	=>	'',
            'video_button_border_style'	=>	'',
            'video_button_border_radius'	=>	'',
            'video_button_border_color'	=>	'',

            // Hover Styling
            'video_button_hover_color'	=>	'',
            'video_button_hover_background'	=>	'',
            'video_button_hover_border_color'	=>	'',

            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));


        // wrapper class
        $wrapper_class_arr = array();
        
        $unique_class = uniqid('htmegavc_video_player_');
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_video_player_wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        // add wrapper class
        $wrapper_class_arr[] =  'htmegavc-video-player htmegavc-player-container';
        $wrapper_class_arr[] =  'text-'. $video_style_align;

        // join all wrapper class
        $wrapper_class = implode(' ', $wrapper_class_arr);

        // Styling
        // Button Styling
        $button_inline_style = "color:$video_button_color;";
        $button_inline_style .= "background-color:$video_button_background;";
        $button_inline_style .= "font-size:$video_button_fontsize;";
        $button_inline_style .= "margin:$video_button_margin;";
        $button_inline_style .= "padding:$video_button_padding;";
        $button_inline_style .= "border-width:$video_button_border_width;";
        $button_inline_style .= "border-style:$video_button_border_style;";
        $button_inline_style .= "border-radius:$video_button_border_radius;";
        $button_inline_style .= "border-color:$video_button_border_color;";

        // Button hover
        $button_hover_inline_style = "color:$video_button_hover_color;";
        $button_hover_inline_style .= "background-color:$video_button_hover_background;";
        $button_hover_inline_style .= "border-color:$video_button_hover_border_color;";

        $output = '';
        $output .= '<style>';
        $output .= "
			.$unique_class.htmegavc-video-player  .magnify-video-active{ $button_inline_style }
			.$unique_class.htmegavc-video-player  .magnify-video-active:hover{ $button_hover_inline_style }
        ";
        $output .= '</style>';

        ob_start();

        if(strpos($video_image_size, 'x')){
            $size_arr = explode('x', $video_image_size);
            $video_image_size = array($size_arr[0],$size_arr[1]);
        }

        $video_image = wp_get_attachment_image_src( $video_image, $video_image_size );
        if( $videocontainer == 'self' ){
            $player_options_settings = [
                'videoURL'          => !empty( $video_url ) ? $video_url : 'https://www.youtube.com/watch?v=CDilI6jcpP4',
                'coverImage'        => !empty( $video_image[0] ) ? $video_image[0] : '',
                'autoPlay'          => ( $autoplay == 'yes' ) ? true : false,
                'mute'              => ( $soundmute == 'yes' ) ? true : false,
                'loop'              => ( $repeatvideo == 'yes' ) ? true : false,
                'showControls'      => ( $controlerbutton == 'yes' ) ? true : false,
                'showYTLogo'        => ( $videosourselogo == 'yes' ) ? true : false,
                'startAt'           => $videostarttime,
                'containment'       => 'self',
                'opacity'           => 1,
                'optimizeDisplay'   => true,
                'realfullscreen'    => true,
            ];
        }
        $videocontainer_arr = [
            'videocontainer' => isset( $videocontainer ) ? $videocontainer : '',
        ];

        ?>

        <div class="<?php echo esc_attr( $wrapper_class ); ?>" data-videotype='<?php echo wp_json_encode( $videocontainer_arr ); ?>'>
		    <?php if($videocontainer == 'self'): ?>
		        <div class="htmegavc-video-player-inner" data-property='<?php echo wp_json_encode( $player_options_settings );?>' ></div>
		    <?php else:
		        if( $buttonicon != '' ){
		            echo sprintf('<a class="magnify-video-active has_icon" href="%1$s"><i class="%2$s"></i> %3$s</a>',$video_url,$buttonicon,$buttontext);
		        }else{
		            echo sprintf('<a class="magnify-video-active has_text" href="%1$s">%2$s</a>',$video_url,$buttontext);
		        }
		    ?>
		    <?php endif;?>
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
            "name" => __("HT Video Player", 'htmegavc'),
            "description" => __("Add Video Player to your page", 'htmegavc'),
            "base" => "htmegavc_video_player",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegavc_video_player_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('HT Mega Addons', 'htmegavc'),
            "params" => array(

            	// cotnent
            	array(
            	  "param_name" => "videocontainer",
            	  "heading" => __("Video Container", 'htmegavc'),
            	  "type" => "dropdown",
            	  "default_set" => '1',
            	  'value' => [
            	      __( 'Self', 'htmegavc' )  =>  'self',
            	      __( 'Pop Up', 'htmegavc' )  =>  'popup',
            	  ],
            	),
            	array(
            	    'param_name' => 'video_url',
            	    'heading' => __( 'Video URL', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'value' => __( 'https://www.youtube.com/watch?v=CDilI6jcpP4', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'video_image',
            	    'heading' => __( 'Cover Image', 'htmegavc' ),
            	    'type' => 'attach_image',
            	    'description' => '',
            	    'dependency' =>[
            	        'element' => 'videocontainer',
            	        'value' => array( 'self' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'video_image_size',
            	    'heading' => __( 'Cover Image Size', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'videocontainer',
            	        'value' => array( 'self' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'buttonicon',
            	    'heading' => __( 'Button Icon', 'htmegavc' ),
            	    'type' => 'iconpicker',
            	    'value' => 'fa fa-play',
            	),
            	array(
            	    'param_name' => 'buttontext',
            	    'heading' => __( 'Button Text', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'dependency' =>[
            	        'element' => 'videocontainer',
            	        'value' => array( 'popup' ),
            	    ],
            	),

            	// Video Options
            	array(
            	  "param_name" => "autoplay",
            	  "heading" => __("Autoplay", 'htmegavc'),
            	  "type" => "dropdown",
            	  'value' => [
            	      __( 'No', 'htmegavc' )  =>  'no',
            	      __( 'Yes', 'htmegavc' )  =>  'yes',
            	  ],
            	  'dependency' =>[
            	      'element' => 'videocontainer',
            	      'value' => array( 'self' ),
            	  ],
            	),
            	array(
            	  "param_name" => "soundmute",
            	  "heading" => __("Mute Sound", 'htmegavc'),
            	  "type" => "dropdown",
            	  'value' => [
            	      __( 'No', 'htmegavc' )  =>  'no',
            	      __( 'Yes', 'htmegavc' )  =>  'yes',
            	  ],
            	  'dependency' =>[
            	      'element' => 'videocontainer',
            	      'value' => array( 'self' ),
            	  ],
            	),
            	array(
            	  "param_name" => "repeatvideo",
            	  "heading" => __("Repeat Video", 'htmegavc'),
            	  "type" => "dropdown",
            	  'value' => [
            	      __( 'No', 'htmegavc' )  =>  'no',
            	      __( 'Yes', 'htmegavc' )  =>  'yes',
            	  ],
            	  'dependency' =>[
            	      'element' => 'videocontainer',
            	      'value' => array( 'self' ),
            	  ],
            	),
            	array(
            	  "param_name" => "controlerbutton",
            	  "heading" => __("Show Controller Button", 'htmegavc'),
            	  "type" => "dropdown",
            	  'value' => [
            	      __( 'Yes', 'htmegavc' )  =>  'yes',
            	      __( 'No', 'htmegavc' )  =>  'no',
            	  ],
            	  'dependency' =>[
            	      'element' => 'videocontainer',
            	      'value' => array( 'self' ),
            	  ],
            	),
            	array(
            	  "param_name" => "videosourselogo",
            	  "heading" => __("Show Video Sourse Logo", 'htmegavc'),
            	  "type" => "dropdown",
            	  'value' => [
            	      __( 'Yes', 'htmegavc' )  =>  'yes',
            	      __( 'No', 'htmegavc' )  =>  'no',
            	  ],
            	  'dependency' =>[
            	      'element' => 'videocontainer',
            	      'value' => array( 'self' ),
            	  ],
            	),
            	array(
            	    'param_name' => 'videostarttime',
            	    'heading' => __( 'Video Start Time', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Example: 5', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'videocontainer',
            	        'value' => array( 'self' ),
            	    ],
            	),


            	// Styling
            	array(
            	    'param_name' => 'video_style_align',
            	    'heading' => __( 'Alignment', 'htmegavc' ),
            	    'type' => 'dropdown',
            	    'value' => [
            	        __( 'Center', 'htmegavc' )  =>  'center',
            	        __( 'Left', 'htmegavc' )  =>  'left',
            	        __( 'Right', 'htmegavc' )  =>  'right',
            	        __( 'Justify', 'htmegavc' )  =>  'justify',
            	    ],
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),


            	// Button Styling
            	array(
            	    "param_name" => "custom_heading",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Button Styling","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'videocontainer',
            	        'value' => array( 'popup' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'video_button_color',
            	    'heading' => __( 'Button Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'videocontainer',
            	        'value' => array( 'popup' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'video_button_background',
            	    'heading' => __( 'Button BG Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'videocontainer',
            	        'value' => array( 'popup' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'video_button_fontsize',
            	    'heading' => __( 'Button Icon Font Size', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Example: 20px', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'videocontainer',
            	        'value' => array( 'popup' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'video_button_margin',
            	    'heading' => __( 'Button Margin', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS margin. Example: 18px 0, which stand for margin-top and margin-bottom is 18px', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'videocontainer',
            	        'value' => array( 'popup' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'video_button_padding',
            	    'heading' => __( 'Button Padding', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS padding. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'videocontainer',
            	        'value' => array( 'popup' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'video_button_border_width',
            	    'heading' => __( 'Button Border width', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS Border width. Example: 2px, which stand for border-width:2px;', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'videocontainer',
            	        'value' => array( 'popup' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'video_button_border_style',
            	    'heading' => __( 'Button Border style', 'htmegavc' ),
            	    'type' => 'dropdown',
            	    'value' => [
            	        __( 'None', 'htmegavc' )  =>  'none',
            	        __( 'Solid', 'htmegavc' )  =>  'solid',
            	        __( 'Double', 'htmegavc' )  =>  'double',
            	        __( 'Dotted', 'htmegavc' )  =>  'dotted',
            	        __( 'Dashed', 'htmegavc' )  =>  'dashed',
            	        __( 'Groove', 'htmegavc' )  =>  'groove',
            	    ],
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'videocontainer',
            	        'value' => array( 'popup' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'video_button_border_radius',
            	    'heading' => __( 'Button Border Radius', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS Border Radius. Example: 5px, which stand for border-radius:5px;', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'videocontainer',
            	        'value' => array( 'popup' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'video_button_border_color',
            	    'heading' => __( 'Button Border color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'description' => __( 'The CSS Border color.', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'videocontainer',
            	        'value' => array( 'popup' ),
            	    ],
            	),


            	// Hover Styling
            	array(
            	    "param_name" => "custom_heading",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Hover Styling","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'videocontainer',
            	        'value' => array( 'popup' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'video_button_hover_color',
            	    'heading' => __( 'Button Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'videocontainer',
            	        'value' => array( 'popup' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'video_button_hover_background',
            	    'heading' => __( 'Button BG Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'videocontainer',
            	        'value' => array( 'popup' ),
            	    ],
            	),
            	array(
            	    'param_name' => 'video_button_hover_border_color',
            	    'heading' => __( 'Button Border color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'description' => __( 'The CSS Border color.', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	    'dependency' =>[
            	        'element' => 'videocontainer',
            	        'value' => array( 'popup' ),
            	    ],
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
new Htmegavc_Video_Player();