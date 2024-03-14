<?php
            
vc_map( array(
    "name"        => __( "Flip Box", 'asvc' ),
    "base"        => "favc_flipbox_two",
    "icon"        => "asvc_flipbox_icon",
    "category" => __('Flip Box', 'js_composer'),
    //'description' => __('Info text box', 'js_composer'),
    "params"      => array(
                            
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => __("Direction:", "asvc"),
            "param_name" => "direction",
            "value" => array(
                __("Verticle","asvc") => "flip-vertical",
                __("Horizontal","asvc") => "flip-horizontal",
            ),
            "description" => __( "Choose animation direction", "asvc" ),
            "group" => "General",
            "std" => "",
        ), 
        array(
            'type' => 'dropdown',
            'heading' => __( 'Show Icon:', 'asvc' ),
            'param_name' => 'show_icon',
            "value" => array(
                "Yes" => "yes",
                "No" => "no",
            ),
            "std" => "no",
            "group" => "Front"
        ),                    
                   
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_fontawesome',
            'value' => '', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false, // default true, display an "EMPTY" icon?
                'iconsPerPage' => 100, // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
            ),
            'dependency' => array(
                'element' => 'show_icon',
                'value' => 'yes',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
            "group" => "Front"
        ),
                           
        array(
            "type" => "prime_slider",
            "class" => "",
            "heading" => __("Icon Size", "asvc"),
            "param_name" => "icon_size",
            "value" => 20,
            "min" => 16,
            "max" => 100,
            "step" => 1,
            "unit" => "px",
            "description" => __("Provide icon size", "asvc"),
            'dependency' => array(
                'element' => 'show_icon',
                'value' => 'yes',
            ),
            "group" => "Front"
        ),
        
        array(
            "type"        => "colorpicker",
            "class"       => "",
            "heading"     => __( "Icon Color", "asvc" ),
            "param_name"  => "icon_color",
            "value"       => "#343434",
            "description" => __( "Choose icon color", "asvc" ),
            'dependency' => array(
                'element' => 'show_icon',
                'value' => 'yes',
            ),
            "group" => "Front"
        ),                    
                         
        array(
            "type"        => "textfield",
            "class"       => "",
            "heading"     => __( "Title", 'asvc' ),
            "param_name"  => "title",
            "admin_label" => true,
            "value"       => "",
            "group" => "Front"
            
        ),
        array(
            "type" => "textarea",
            "class" => "",
            "heading" => __("Description", "asvc"),
            "param_name" => "front_desc",
            "value" => "",
            "description" => __("Provide the description for the front.", "asvc"),
            "group" => "Front"
        ),                  
        array(
            "type"        => "colorpicker",
            "class"       => "",
            "heading"     => __( "Box color", "asvc" ),
            "param_name"  => "front_box_color",
            "description" => __( "Choose flipbox color", "asvc" ),
            "group" => "Front"
        ),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", "asvc"),
            "param_name" => "extraclass",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "asvc"),
            "group" => "General"
        ),
        
        array(
            'type'             => 'prime_slider',
            'heading'          => __( 'Title Font Size', 'asvc' ),
            'param_name'       => 'title_f_size',
            "value" => 18,
            "min" => 10,
            "max" => 50,
            "step" => 1,
            "unit" => "px",
            "description" => __("Chose Title Font Size as Pixel. Default is 18px", "asvc"),
            "group" => "Front"
        ),
        // Description Font Size Field
        array(
            'type'             => 'prime_slider',
            'heading'          => __( 'Description Font Size', 'asvc' ),
            'param_name'       => 'desc_f_size',
            "value" => 14,
            "min" => 10,
            "max" => 50,
            "step" => 1,
            "unit" => "px",
            "description" => __("Chose Description Font Size as Pixel. Default is 14px", "asvc"),
            "group" => "Front"
        ),

        array(
            "type"        => "colorpicker",
            "class"       => "",
            "heading"     => __( "Title color", "asvc" ),
            "param_name"  => "title_color",
            "description" => __( "Choose text color", "asvc" ),
            "group" => "Front"
        ),
        array(
            "type"        => "colorpicker",
            "class"       => "",
            "heading"     => __( "Description color", "asvc" ),
            "param_name"  => "descr_color",
            "description" => __( "Choose text color", "asvc" ),
            "group" => "Front"
        ),
        
        array(
            'type' => 'dropdown',
            'heading' => __( 'Show Icon:', 'asvc' ),
            'param_name' => 'back_show_icon',
            "value" => array(
                "Yes" => "yes",
                "No" => "no",
            ),
            "std" => "no",
            "group" => "Back"
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'back_icon_fontawesome',
            'value' => '', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => false, // default true, display an "EMPTY" icon?
                'iconsPerPage' => 100, // default 100, how many icons per/page to display, we use (big prime_slider) to display all icons in single page
            ),
            'dependency' => array(
                'element' => 'back_show_icon',
                'value' => 'yes',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
            "group" => "Back"
        ),
                           
        array(
            "type" => "prime_slider",
            "class" => "",
            "heading" => __("Icon Size", "asvc"),
            "param_name" => "back_icon_size",
            "value" => 18,
            "min" => 10,
            "max" => 50,
            "step" => 1,
            "unit" => "px",
            "description" => __("Provide icon size", "asvc"),
            'dependency' => array(
                'element' => 'back_show_icon',
                'value' => 'yes',
            ),
            "group" => "Back"
        ),
        
        array(
            "type"        => "colorpicker",
            "class"       => "",
            "heading"     => __( "Icon Color", "asvc" ),
            "param_name"  => "back_icon_color",
            "value"       => "#343434",
            "description" => __( "Choose icon color", "asvc" ),
            'dependency' => array(
                'element' => 'back_show_icon',
                'value' => 'yes',
            ),
            "group" => "Back"
        ),                    
        array(
            "type"        => "colorpicker",
            "class"       => "",
            "heading"     => __( "Box color", "asvc" ),
            "param_name"  => "back_box_color",
            "description" => __( "Choose flipbox color", "asvc" ),
            "group" => "Back"
        ),
  
        array(
            "type"        => "textfield",
            "class"       => "",
            "heading"     => __( "Title", 'asvc' ),
            "param_name"  => "back_title",
            "admin_label" => true,
            "value"       => "",
            "description" => __("leave empty if you don't want.", "asvc"),
            "group" => "Back"
        ),
        array(
            "type" => "textarea",
            "class" => "",
            "heading" => __("Description", "asvc"),
            "param_name" => "back_desc",
            "value" => "",
            "description" => __("Provide the description for the back.", "asvc"),
            "group" => "Back"
        ),                   
        
        array(
            'type'             => 'prime_slider',
            'heading'          => __( 'Title Font Size', 'asvc' ),
            'param_name'       => 'back_title_f_size',
            "value" => 18,
            "min" => 10,
            "max" => 50,
            "step" => 1,
            "unit" => "px",
            "description" => __("Chose Title Font Size as Pixel. Default is 18px", "asvc"),
            "group" => "Back"
        ),
        // Description Font Size Field
        array(
            'type'             => 'prime_slider',
            'heading'          => __( 'Description Font Size', 'asvc' ),
            'param_name'       => 'back_desc_f_size',
            "value" => 14,
            "min" => 10,
            "max" => 50,
            "step" => 1,
            "unit" => "px",
            "description" => __("Chose Description Font Size as Pixel. Default is 14px", "asvc"),
            "group" => "Back"
        ),

        array(
            "type"        => "colorpicker",
            "class"       => "",
            "heading"     => __( "Title color", "asvc" ),
            "param_name"  => "back_title_color",
            "description" => __( "Choose text color", "asvc" ),
            "group" => "Back"
        ),
        array(
            "type"        => "colorpicker",
            "class"       => "",
            "heading"     => __( "Description color", "asvc" ),
            "param_name"  => "back_descr_color",
            "description" => __( "Choose text color", "asvc" ),
            "group" => "Back"
        ),                    
            
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => __("Show Button:", "asvc"),
            "param_name" => "show_button",
            "value" => array(
                __("Yes","asvc") => "yes",
                __("No","asvc") => "no",
            ),
            "group" => "Back",
            "std" => "no",
        ),
        array(
            "type"        => "textfield",
            "class"       => "",
            "heading"     => __( "Text on Button", 'asvc' ),
            "param_name"  => "btn_text",
            "value"       => "Read More",
            "group" => "Back",
            'dependency' => array(
                'element' => 'show_button',
                'value' => 'yes',
            ),
            
        ),
        array(
            "type" => "vc_link",
            "class" => "",
            "heading" => __("Add Link", "asvc"),
            "param_name" => "link",
            "value" => "",
            "description" => __("Add a custom link or select existing page. You can remove existing link as well.", "asvc"),
            'dependency' => array(
                'element' => 'show_button',
                'value' => 'yes',
            ),
            "group" => "Back"
        ),                                        
        
        array(
            'type' => 'css_editor',
            'heading' => __( 'Css', 'asvc' ),
            'param_name' => 'css_flip_box',
            'group' => __( 'Design ', 'asvc' ),
            'edit_field_class' => 'asvc-flipbox flipper',
        ),                    
       
        
        
    )
) );

function asvc_flipbox_two_shortcode_function( $atts, $content = null, $tag ) {
    extract( shortcode_atts( array(
        'direction'      => '',
        'show_icon'      => '',
        'icon_fontawesome'   => '',
        'front_box_color'   => '#f7f7f7',
        'back_box_color'     => '#9bcc18',
        'icon_size'            => '40',
        'icon_color'      => '#42bce2',
        'title'         => '',
        'front_desc'      => '',
        'title_f_size'      => '',
        'desc_f_size'      => '',
        'title_color'      => '',
        'descr_color'      => '',
        'back_show_icon'  => '',
        'back_icon_fontawesome'  => '',
        'back_title'      => '',
        'back_desc'      => '',
        'back_title_f_size'  => '',
        'back_icon_size'  => '40',
        'back_icon_color'  => '',
        'back_desc_f_size'  => '',
        'back_title_color'   => '',
        'back_descr_color'   => '',
        'show_button'      => '',
        'btn_text'      => 'Read More',
        'link'      => '',
        'css_flip_box'      => '',
        'extraclass' => '',
        
    ), $atts ) );
    
    wp_register_style( 'flipbox-font-awesome', plugins_url( '../info-box/css/font-awesome.min.css',  __FILE__) );
    wp_enqueue_style( 'flipbox-font-awesome' );
    
    wp_register_style( 'flipbox-css', plugins_url( '/css/flipbox-box.css',  __FILE__) );
    wp_enqueue_style( 'flipbox-css' );
    
    
    $content = wpb_js_remove_wpautop( $content ); // fix unclosed/unwanted paragraph tags in $content
    $link   = vc_build_link( $link );
    
    $output ='';
    $front_data ='';
    $back_data ='';
    
    
    if( $show_icon == 'yes' && !empty( $icon_fontawesome ) )
         $front_data .= '<div class="wrap-icon"><i style="font-size:'.$icon_size.'px; color:'.$icon_color.';" class="'.$icon_fontawesome.'"></i></div>';
    
    if( !empty( $title ) )
        $front_data .= '<h3 style="font-size:'.$title_f_size.'px; color:'.$title_color.'; ">'. $title .'</h3>';

    if(!empty($front_desc))
        $front_data .= '<p style="font-size:'.$desc_f_size.'px; color:'.$descr_color.'; ">'. $front_desc .'</p>';
    
    //back side
    if( $back_show_icon == 'yes' && !empty( $back_icon_fontawesome ) )
         $back_data .= '<div class="wrap-icon"><i style="font-size:'.$back_icon_size.'px; color:'.$back_icon_color.';" class="'.$back_icon_fontawesome.'"></i></div>';
    
    if( !empty( $back_title ) )
        $back_data .= '<h3 style="font-size:'.$back_title_f_size.'px; color:'.$back_title_color.';">'. $back_title .'</h3>';

    if(!empty($back_desc))
        $back_data .= '<p style="font-size:'.$back_desc_f_size.'px; color:'.$back_descr_color.'; ">'. $back_desc .'</p>'; 
        
    
    
    if( $show_button == 'yes' ){
        
    $back_data .= '<a style="background-color:'.$icon_color.'; margin-top: -10px;" class="button" href="'.$link['url'].'" title="'.$link['title'].'" target="'.$link['target'].'">'. $btn_text .'</a>';    
    
    }    
                       


    $output .='<div class="asvc-flipbox asvc-flip-container '.$direction.' '.$extraclass.'">
                <div class="flipper">
                <div class="front" style="background:'.$front_box_color.';">
                <div class="front-content">';

    $output .= $front_data;    

    $output .= '</div>
                 </div>';


    $output .='<div class="back" style="background:'.$back_box_color.';">
                <div class="des">';
    
    $output .= $back_data;
    
    $output .= '</div>
                </div>';

    $output .= '</div>
                </div>';
    



    return $output;
}


add_shortcode( 'favc_flipbox_two', 'asvc_flipbox_two_shortcode_function' );