<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Call_To_Action extends WPBakeryShortCode{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_cta', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
    	wp_register_style( 'htmegavc-cta', plugins_url('css/call-to-action.css', __FILE__));
    	wp_enqueue_style( 'htmegavc-cta' );
    }


    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
           // Content
           'callto_action_style' => '1',
           'callto_action_title' => '',
           'callto_action_description' => '',
           'callto_action_buttontxt' => '',
           'callto_action_button_link' => '',
           'callto_action_title_tag' => 'h1',
           'callto_action_description_tag' => 'p',

           // Styling
           'callto_section_box_shadow' => '',
           'callto_section_align' => 'center',

           // Title Styling
           'callto_action_title_color' => '',
           'callto_action_title_padding' => '',

           // Description Styling
           'callto_action_description_color' => '',
           'callto_action_description_padding' => '',

           // Button Styling
           'button_alignment' => 'center',
           'button_color' => '',
           'button_background' => '',
           'button_border_width' => '',
           'button_border_style' => '',
           'button_border_radius' => '',
           'button_border_color' => '',
           'button_margin' => '',
           'button_padding' => '',

           // Button Hover Styling
           'button_hover_color' => '',
           'button_hover_background' => '',
           'button_hover_border_color' => '',

           // Typography
           // Title typography
           'callto_action_title_use_google_font' => '',
           'callto_action_title_google_font' => '',
           'callto_action_title_typography' => '',

           // Description typography
           'callto_action_description_use_google_font' => '',
           'callto_action_description_google_font' => '',
           'callto_action_description_typography' => '',

           // Button Typography
           'button_use_google_font' => '',
           'button_google_font' => '',
           'button_typography' => '',


            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));


        // wrapper class
        $wrapper_class_arr = array();
        
        $unique_class = uniqid('htmegavc_cta_');
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_cta_wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        // add wrapper class
        $wrapper_class_arr[] =  'htmegavc-call-to-action callto-action-style-'.$callto_action_style;
        $wrapper_class_arr[] =  'text-'.$callto_section_align;

        // join all wrapper class
        $wrapper_class = implode(' ', $wrapper_class_arr);

        // Styling
        // wrapper inline style
        $wrapper_inline_style = "box-shadow:$callto_section_box_shadow;";

        // title inline style
        $title_inline_style = "color:$callto_action_title_color;";
        $title_inline_style .= "padding:$callto_action_title_padding;";

        // description inline style
        $description_inline_style = "color:$callto_action_description_color;";
        $description_inline_style .= "padding:$callto_action_description_padding;";

        // button inline style
        $button_inline_style = "color:$button_color;";
        $button_inline_style .= "background-color:$button_background;";
        $button_inline_style .= "border-width:$button_border_width;";
        $button_inline_style .= "border-style:$button_border_style;";
        $button_inline_style .= "border-color:$button_border_color;";
        $button_inline_style .= "border-radius:$button_border_radius;";
        $button_inline_style .= "margin:$callto_action_description_padding;";
        $button_inline_style .= "padding:$callto_action_description_padding;";

        // button hover inline style
        $button_hover_inline_style = "color:$button_hover_color !important;";
        $button_hover_inline_style .= "background-color:$button_hover_background !important;";
        $button_hover_inline_style .= "border-color:$button_hover_border_color !important;";

        // Typography
        // Title typography
        $google_font_data1 = htmegavc_build_google_font_data($callto_action_title_google_font);
        if ( 'true' == $callto_action_title_use_google_font && isset( $google_font_data1['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data1['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data1['values']['font_family'] );
        }

        // concate google font properties and other properties
        $callto_action_title_google_font = htmegavc_build_google_font_style($google_font_data1);
        $title_inline_style .= htmegavc_combine_font_container($callto_action_title_typography.';'.$callto_action_title_google_font);

        //Description typography
        $google_font_data2 = htmegavc_build_google_font_data($callto_action_description_google_font);
        if ( 'true' == $callto_action_description_use_google_font && isset( $google_font_data2['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data2['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data2['values']['font_family'] );
        }

        // concate google font properties and other properties
        $callto_action_description_google_font = htmegavc_build_google_font_style($google_font_data2);
        $description_inline_style .= htmegavc_combine_font_container($callto_action_description_typography.';'.$callto_action_description_google_font);

        // Popover content Typography
        $google_font_data2 = htmegavc_build_google_font_data($button_google_font);
        if ( 'true' == $button_use_google_font && isset( $google_font_data2['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data2['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data2['values']['font_family'] );
        }

        // concate google font properties and other properties
        $button_google_font = htmegavc_build_google_font_style($google_font_data2);
        $button_inline_style .= htmegavc_combine_font_container($button_typography.';'.$button_google_font);


        $output = '';
        $output .= '<style>';
        $output .= "
			.$unique_class.htmegavc-call-to-action .htmegavc_call_btn:hover{ $button_hover_inline_style }
        ";
        $output .= '</style>';

        ob_start(); ?>

         <div class="<?php echo esc_attr($wrapper_class); ?>" style="<?php echo esc_attr($wrapper_inline_style); ?>">
         	<div class="htmegavc-content">

         	<?php
         		// button generate
         		$link_arr = explode('|', $callto_action_button_link);
         		if(count($link_arr) > 1){
         		  $link_url  =  urldecode(str_replace('url:', '', $link_arr[0]));
         		  $link_target  =  urldecode(str_replace('target:', '', $link_arr[2]));

         		  if($link_url){
         		    $callto_action_buttontxt = sprintf( '<a href="%1$s" target="%3$s" class="htmegavc_call_btn text-%4$s" style="%5$s">%2$s</a>', $link_url, $callto_action_buttontxt,$link_target, $button_alignment, $button_inline_style );
         		  }
         		}
         	?>

         	<?php if( $callto_action_style == 2 ): ?>
         	    <div class="row align-items-center">
         	        <div class="col-lg-9">
         	            <div class="ht-call-to-action">
         	                <div class="content">
         	                    <?php
         	                        if( !empty( $callto_action_title ) ){
         	                            echo sprintf( '<%1$s class="%2$s" style="%4$s">%3$s</%1$s>', $callto_action_title_tag, 'htmegavc-callto-action-title', $callto_action_title, $title_inline_style );
         	                        }
         	                        if( !empty( $callto_action_description ) ){
         	                            echo sprintf( '<%1$s class="%2$s" style="%4$s">%3$s</%1$s>', $callto_action_description_tag, 'htmegavc-callto-action-description', $callto_action_description, $description_inline_style );
         	                        }
         	                    ?>
         	                </div>
         	            </div>
         	        </div>
         	        <div class="col-lg-3">
         	            <div class="text-right">
         	                <?php
         	                    if( !empty( $callto_action_buttontxt ) ){
         	                        echo wp_kses_post($callto_action_buttontxt);
         	                    }
         	                ?>
         	            </div>
         	        </div>
         	    </div>

         	<?php elseif( $callto_action_style == 3 ): ?>
         	    <div class="content">
         	        <?php
         	            if( !empty( $callto_action_description ) ){
         	                echo sprintf( '<%1$s class="%2$s" style="%4$s">%3$s</%1$s>', $callto_action_description_tag, 'htmegavc-callto-action-description', $callto_action_description, $description_inline_style );
         	            }
         	            if( !empty( $callto_action_title ) ){
         	                echo sprintf( '<%1$s class="%2$s" style="%4$s">%3$s</%1$s>', $callto_action_title_tag, 'htmegavc-callto-action-title', $callto_action_title, $title_inline_style );
         	            }
         	        ?>
         	    </div>
         	    <div class="action-btn">
         	        <?php
         	            if( !empty( $callto_action_buttontxt ) ){
         	                echo wp_kses_post($callto_action_buttontxt);
         	            }
         	        ?>
         	    </div>

         	<?php elseif( $callto_action_style == 4 || $callto_action_style == 5 || $callto_action_style == 6 ): ?>
         	    <div class="content">
         	        <?php
         	            if( !empty( $callto_action_title ) ){
         	                echo sprintf( '<%1$s class="%2$s" style="%4$s">%3$s</%1$s>', $callto_action_title_tag, 'htmegavc-callto-action-title', $callto_action_title, $title_inline_style );
         	            }
         	            if( !empty( $callto_action_description ) ){
         	                echo sprintf( '<%1$s class="%2$s" style="%4$s">%3$s</%1$s>', $callto_action_description_tag, 'htmegavc-callto-action-description', $callto_action_description, $description_inline_style );
         	            }
         	        ?>
         	    </div>
         	    <div class="action-btn">
         	        <?php
         	            if( !empty( $callto_action_buttontxt ) ){
         	                echo wp_kses_post($callto_action_buttontxt);
         	            }
         	        ?>
         	    </div>
         	    
         	<?php elseif( $callto_action_style == 7 ):?>
         	    <div class="call-to-action-inner">
         	        <div class="content">
         	            <?php
         	                if( !empty( $callto_action_title ) ){
         	                    echo sprintf( '<%1$s class="%2$s" style="%4$s">%3$s</%1$s>', $callto_action_title_tag, 'htmegavc-callto-action-title', $callto_action_title, $title_inline_style );
         	                }
         	                if( !empty( $callto_action_description ) ){
         	                    echo sprintf( '<%1$s class="%2$s" style="%4$s">%3$s</%1$s>', $callto_action_description_tag, 'htmegavc-callto-action-description', $callto_action_description, $description_inline_style );
         	                }
         	            ?>
         	        </div>
         	        <div class="action-btn">
         	            <?php
         	                if( !empty( $callto_action_buttontxt ) ){
         	                    echo wp_kses_post($callto_action_buttontxt);
         	                }
         	            ?>
         	        </div>
         	    </div>

         	<?php else:?>
         	    <?php
         	        if( !empty( $callto_action_description ) ){
         	            echo sprintf( '<%1$s class="%2$s" style="%4$s">%3$s</%1$s>', $callto_action_description_tag, 'htmegavc-callto-action-description', $callto_action_description, $description_inline_style );
         	        }
         	        if( !empty( $callto_action_title ) ){
         	            echo sprintf( '<%1$s class="%2$s" style="%4$s">%3$s</%1$s>', $callto_action_title_tag, 'htmegavc-callto-action-title', $callto_action_title, $title_inline_style );
         	        }
         	        if( !empty( $callto_action_buttontxt ) ){
         	            echo wp_kses_post($callto_action_buttontxt);
         	        }
         	    ?>
         	<?php endif;?>
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
            "name" => __("HT Call To Action", 'htmegavc'),
            "description" => __("Add Call To Action to your page", 'htmegavc'),
            "base" => "htmegavc_cta",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegvc_cta_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('HT Mega Addons', 'htmegavc'),
            "params" => array(

            	// cotnent
            	array(
            	  "param_name" => "callto_action_style",
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
            	    'param_name' => 'callto_action_title',
            	    'heading' => __( 'Title', 'htmegavc' ),
            	    'type' => 'textarea',
            	    'value' => 'Title  Text',
            	),
            	array(
            	    'param_name' => 'callto_action_description',
            	    'heading' => __( 'Description', 'htmegavc' ),
            	    'type' => 'textarea',
            	    'value' => 'Description Text',
            	),
            	array(
            	    'param_name' => 'callto_action_buttontxt',
            	    'heading' => __( 'Button Text ', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'value' => 'Button',
            	),
            	array(
            	    'param_name' => 'callto_action_button_link',
            	    'heading' => __( 'Button Link ', 'htmegavc' ),
            	    'type' => 'vc_link',
            	    'value' => 'url:#',
            	),
            	array(
            	  "param_name" => "callto_action_title_tag",
            	  "heading" => __("Title Tag", 'htmegavc'),
            	  "type" => "dropdown",
            	  "default_set" => 'h2',
            	  'value' => [
            	      __( 'H1', 'htmegavc' )  =>  'h1',
            	      __( 'H2', 'htmegavc' )  =>  'h2',
            	      __( 'H3', 'htmegavc' )  =>  'h3',
            	      __( 'H4', 'htmegavc' )  =>  'h4',
            	      __( 'H5', 'htmegavc' )  =>  'h5',
            	      __( 'H6', 'htmegavc' )  =>  'h6',
            	      __( 'P', 'htmegavc' )  =>  'p',
            	      __( 'Div', 'htmegavc' )  =>  'div',
            	  ],
            	),
            	array(
            	  "param_name" => "callto_action_description_tag",
            	  "heading" => __("Description Tag", 'htmegavc'),
            	  "type" => "dropdown",
            	  "default_set" => 'p',
            	  'value' => [
            	      __( 'H1', 'htmegavc' )  =>  'h1',
            	      __( 'H2', 'htmegavc' )  =>  'h2',
            	      __( 'H3', 'htmegavc' )  =>  'h3',
            	      __( 'H4', 'htmegavc' )  =>  'h4',
            	      __( 'H5', 'htmegavc' )  =>  'h5',
            	      __( 'H6', 'htmegavc' )  =>  'h6',
            	      __( 'P', 'htmegavc' )  =>  'p',
            	      __( 'Div', 'htmegavc' )  =>  'div',
            	  ],
            	),
            	array(
            	    'param_name' => 'callto_section_align',
            	    'heading' => __( 'Section Content Alignment', 'htmegavc' ),
            	    'type' => 'dropdown',
            	    "default_set" => 'center',
            	    'value' => [
            	        __( 'Center', 'htmegavc' )  =>  'center',
            	        __( 'Left', 'htmegavc' )  =>  'left',
            	        __( 'Right', 'htmegavc' )  =>  'right',
            	        __( 'Justify', 'htmegavc' )  =>  'justify',
            	    ],
            	),


            	// Styling
            	array(
            	  'param_name' => 'callto_section_box_shadow',
            	  'heading' => __( 'Section Box Shadow', 'htmegavc' ),
            	  'type' => 'textfield',
            	  'description' => __( 'Example value: 0 0 10px rgba(0, 0, 0, 0.1) <a target="_blank" href="https://www.w3schools.com/cssref/css3_pr_box-shadow.asp">Learn More</a>', 'htmegavc' ),
            	  'group'  => __( 'Styling', 'htmegavc' ),
            	),

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
            	    'param_name' => 'callto_action_title_color',
            	    'heading' => __( 'Title Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'callto_action_title_padding',
            	    'heading' => __( 'Title Padding', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS padding. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),


            	// Description Styling
            	array(
            	    "param_name" => "custom_heading",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Description Styling","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'callto_action_description_color',
            	    'heading' => __( 'Description Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'callto_action_description_padding',
            	    'heading' => __( 'Description Padding', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS padding. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
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
            	),
            	array(
            	    'param_name' => 'button_color',
            	    'heading' => __( 'Text Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'button_background',
            	    'heading' => __( 'Button BG Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'button_border_width',
            	    'heading' => __( 'Button Border Width', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS Border width of blockquote. Example: 2px, which stand for border-width:2px;', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'button_border_style',
            	    'heading' => __( 'Button Border Style', 'htmegavc' ),
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
            	    'param_name' => 'button_border_radius',
            	    'heading' => __( 'Button Border Radius', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS Border Radius of button. Example: 5px, which stand for border-radius:5px;', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'button_border_color',
            	    'heading' => __( 'Button Border Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'description' => __( 'The CSS Border color of blockquote.', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'button_margin',
            	    'heading' => __( 'Button Margin', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS margin of Button. Example: 18px 0, which stand for margin-top and margin-bottom is 18px', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'button_padding',
            	    'heading' => __( 'Button Padding', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'The CSS padding of Button. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),

            	// Button Hover Styling
            	array(
            	    "param_name" => "custom_heading",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Button Hover Styling","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'button_hover_color',
            	    'heading' => __( 'Button Hover Text Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'button_hover_background',
            	    'heading' => __( 'Button Hover BG Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'button_hover_border',
            	    'heading' => __( 'Button Hover Border Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'description' => __( 'The CSS Border color of Button.', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),


            	// Typography
            	// Title Typography
            	array(
            	    "param_name" => "package_typograpy",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Title Typography","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'checkbox',
            	  'heading' => __( 'Use google Font?', 'htmegavc' ),
            	  'param_name' => 'callto_action_title_use_google_font',
            	  'description' => __( 'Use font family from google font.', 'htmegavc' ),
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'google_fonts',
            	  'param_name' => 'callto_action_title_google_font',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
            	      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
            	    ),
            	  ),
            	  'dependency' =>[
            	      'element' => 'callto_action_title_use_google_font',
            	      'value' => array( 'true' ),
            	  ],
            	),
            	array(
            	  'param_name' => 'callto_action_title_typography',
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
            	  'heading' => __( 'Use google font?', 'htmegavc' ),
            	  'param_name' => 'callto_action_description_use_google_font',
            	  'description' => __( 'Use font family from google font.', 'htmegavc' ),
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'google_fonts',
            	  'param_name' => 'callto_action_description_google_font',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
            	      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
            	    ),
            	  ),
            	  'dependency' =>[
            	      'element' => 'callto_action_description_use_google_font',
            	      'value' => array( 'true' ),
            	  ],
            	),
            	array(
            	  'param_name' => 'callto_action_description_typography',
            	  'type' => 'font_container',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_size',
            	      'line_height',
            	      'text-align',
            	      'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
            	      'color_description' => __( 'Select heading color.', 'htmegavc' ),
            	    ),
            	  ),
            	),

            	// Button Typography
            	array(
            	    "param_name" => "package_typograpy",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Button Typography","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Typography', 'htmegavc' ),
            	),

            	array(
            	  'type' => 'checkbox',
            	  'heading' => __( 'Use google font?', 'htmegavc' ),
            	  'param_name' => 'button_use_google_font',
            	  'description' => __( 'Use font family from google font.', 'htmegavc' ),
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'google_fonts',
            	  'param_name' => 'button_google_font',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
            	      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
            	    ),
            	  ),
            	  'dependency' =>[
            	      'element' => 'button_use_google_font',
            	      'value' => array( 'true' ),
            	  ],
            	),
            	array(
            	  'param_name' => 'button_typography',
            	  'type' => 'font_container',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_size',
            	      'line_height',
            	      'text-align',
            	      'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
            	      'color_description' => __( 'Select heading color.', 'htmegavc' ),
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
new Htmegavc_Call_To_Action();