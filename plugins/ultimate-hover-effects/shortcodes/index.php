<?php

if ( ! defined( 'ABSPATH' ) ) { die; }

function uhe_circle_shortcode($atts, $content = null){
    extract( shortcode_atts( array(
    
        'id' => '',
        
    ), $atts) );
    
    
    $q = new WP_Query(
        array('posts_per_page' => -1, 'post_type' => 'u_hover_effect', 'p' => $id)
    );

    while($q->have_posts()) : $q->the_post();
    $idd = get_the_ID();

    
    $extra_class = get_post_meta( get_the_ID(), 'uhe_extra_class', true );
    
    //typography
    //$heading_font = $options['heading_font'];
    //$desc_font = $options['desc_font'];
    $heading_font_size = get_post_meta( get_the_ID(), 'uhe_heading_font_size', true );
    $heading_color = get_post_meta( get_the_ID(), 'uhe_heading_color', true );
    //$heading_text_transform = $options['heading_text_transform'];
    //$heading_italic = $options['heading_italic'];
    $desc_font_size = get_post_meta( get_the_ID(), 'uhe_desc_font_size', true );
    //$desc_color = $options['desc_color'];
    //$desc_text_transform = $options['desc_text_transform'];
    //$desc_italic = $options['desc_italic'];
    //$desc_line_height = $options['desc_line_height'];
    
    //image sizes
    $custom_image_size = get_post_meta( get_the_ID(), 'uhe_custom_image_size', true );
    $image_width = get_post_meta( get_the_ID(), 'uhe_image_width', true );
    //$image_height = $options['image_height'];
    //$remove_image_gap = $options['remove_image_gap'];

    //item column
    $column_number = get_post_meta( get_the_ID(), 'uhe_column_number', true );
    switch ($column_number) {
    case 1:
        $column = 12;
        break;
    case 2:
        $column = 6;
        break;
    case 3:
        $column = 4;
        break;
    case 4:
        $column = 3;
        break;
    case 6:
        $column = 2;
        break;                       
    default:
        $column = 4;
} 
    

$output ='';

$options = get_post_meta( get_the_ID(), 'options', true );


    $output .='<div class="hover-wrap row">';

    foreach ( (array) $options as $option ) {
        
    $image = wp_get_attachment_image( $option['uhe_image_id'], 'share-pick', null, array(
            'class' => 'full',
        ) );
    $effect = $option['uhe_effect'];
        
    
    $output .='<div class="ultimate-hover-item mg-col-md-'.$column.' mg-col-xs-12 mg-col-sm-6">';
    

    $output .= '<div class="hover-item" style="">';
    
    $output .= '<a href="" class="ultimate-link noHover">';
        
    $output .= '<figure style="width:300px; height:300px" class="ultimate-hover effect-hover '.$effect.' ratiooriginal effect-fonts ultimate-lazyload">';

    
    $output .= '<img style="width:300px; height:300px" data-src="'.$image.'" alt="'.$option['title'].'"/>';
  
    $output .= '<figcaption>
            <div class="effect-caption">
                <div class="effect-heading">
                    <h2 style="font-size:'.$heading_font_size.'px; color:'.$heading_color.';">'.$option['title'].'</h2>
                </div>

                <div class="effect-description">
                    <p style="font-size:'.$desc_font_size.'px;" class="description">'.$option['desc'].'</p>                
                </div>

            </div>
        </figcaption>
    </figure>';

    $output .= '</a>';
        
    $output .= '</div>';    
    
$output .='</div>';

    }
    
    $output .='</div>';//hover wraper close  
    
    endwhile;
    wp_reset_query();
    return $output;
    
}
add_shortcode('u_hover_effect', 'uhe_circle_shortcode');