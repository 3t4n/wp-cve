<?php 
use Adminz\Admin\Adminz as Adminz;
add_action('ux_builder_setup', 'adminz_icon');
add_shortcode('adminz_icon', 'adminz_icon_function');
function adminz_icon(){
    $options = [];
    $options[]  = '--Select--';
    foreach (Adminz::get_support_icons() as $icon) {
        $options[str_replace(".svg", "", $icon)] = $icon;
    }    
    add_ux_builder_shortcode('adminz_icon', array(
        'name'      => 'Custom Icon',
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'icon_box' . '.svg',
        'options' => array(
            'icon' => array(
                'type'       => 'select',
                'heading'    => 'Select Icon',
                'default' => '',
                'options' => $options
            ),
            'image' => array(
                'type'       => 'image',
                'heading'    => 'Or Upload SVG',
                'default' => '',
                'conditions' => 'icon == ""',
            ),
            'color' =>array(
                'type' => 'colorpicker',
                'heading' => __('Icon Color'),
                'alpha' => true,
                'format' => 'hex',
            ),
            'max_width' => array(
                'type' => 'scrubfield',
                'heading' => __( 'Width' ),
                'default' => '100%',
                'responsive' => true,
                'min' => 0,
                'max' => 100,
                'description'=> 'Type unit: px or %'
            ),
            'link' =>array(
                'type' => 'textfield',
                'heading' => __('Link'),
            ),
            'text_before'=>array(
                'type' => 'textfield',
                'heading' => __('Text before'),
            ),
            'text_after'=>array(
                'type' => 'textfield',
                'heading' => __('Text after'),
            ),
            'line_break'=>array(
                'type' => 'checkbox',
                'heading' => __('Line break'),
            ),
            'class' =>array(
                'type' => 'textfield',
                'heading' => __('SVG Class'),
                'description'=> 'separated by commas ","'
            ),
        ),
    ));
}
function adminz_icon_function($atts){    
    extract(shortcode_atts(array(
        '_id'=> 'adminz_svg'.rand(),
        'icon'    => '',
        'image' => '',
        'color'=> '',
        'link' => '',
        'class'=>'',
        'max_width'=>'',
        'text_before'=>'',
        'text_after'=>'',
        'line_break'=>'',
        'width'=> "100%",
        'height'=>'',
        'image_size'=>'full',
        'style'=>[]
    ), $atts));
    ob_start();    
    $attr = [];
    $attr['style'] = $style;
    if($color) {
        $attr['style']['color']= $color;
    }
    if($class) {
        $attr['class']= $class;
    }
    if($_id){
        $attr['id'] = $_id;
    }
    if($width){
        $attr['width']= $width;
    }
    if($height){
        $attr['height']= $height;
    }

    if($image){
        $a = get_post($image);
        if(isset($a->post_mime_type) and $a->post_mime_type == 'image/svg+xml'){    
            $icon = get_attached_file($image);
            $icon_html =Adminz::get_icon_html($icon,$attr);     
        }else{
            ob_start();
            echo wp_get_attachment_image( $image, $image_size, false, $attr );
            $icon_html= ob_get_clean();
        }
    }else{
        $icon_html = Adminz::get_icon_html($icon,$attr);
    }
    
    $before = ""; $after = "";
    if($link) {
        $before = "<a href='".$link."'>";
        $after = "</a>";
    }
    if($line_break) echo '<p>';
    echo esc_attr($before);
    echo $text_before ? "<span>".esc_attr($text_before) . " </span>" : "";

    echo apply_filters('the_title',$icon_html);
    if(Adminz::is_flatsome()){ 
        $unit = 'px';
        if(isset($atts['max_width']) and strpos($atts['max_width'],"%")){
            $unit = "%";
        }
        if(isset($atts['max_width']) and strpos($atts['max_width'],"em")){
            $unit = "em";
        }
        if(isset($atts['max_width']) and strpos($atts['max_width'],"rem")){
            $unit = "rem";
        }
        $args = array(
            'max_width' => array(
                'selector' => '',
                'property' => 'max-width',
                'unit'     => $unit,
            ),
        );
        echo ux_builder_element_style_tag( $_id, $args, $atts );
    }
    echo $text_after ? "<span> ".esc_attr($text_after)."</span>" : "" ;
    echo esc_attr($after);
    if($line_break) echo '</p>';
    return apply_filters('adminz_output_debug',ob_get_clean());
}