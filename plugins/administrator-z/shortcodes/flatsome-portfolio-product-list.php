<?php 
use Adminz\Helper\ADMINZ_Helper_Language;
if(!class_exists( 'WooCommerce' ) ) return;
add_shortcode('adminz_flatsome_portfolio_product_list',function($atts){
    extract(shortcode_atts(array(
        'title'=>'your title',
        'columns' => 2,
        'depth' => 1,
        'depth_hover'=> 2,
        'image_width'=>25,
        'text_align'=> 'left',
        'style'=> 'vertical',
        'type'=>'row'
    ), $atts));
    ob_start();
    global $post;
    $featured_item_slug = $post->post_name;
    $args = [
        'post_type' => 'product',
        'fields'=> 'ids',
        'posts_per_page' => -1,
        'tax_query'=> [
            'relation'=> 'AND',
            [
                'taxonomy' => ADMINZ_Helper_Language::get_pll_string('adminz_flatsome_portfolio_product_tax'),
                'field' => 'slug',
                'terms' => [$featured_item_slug],
                'include_children' => true,
                'operator' => 'IN'
            ]
        ]
    ];    
    $posts = get_posts($args);
    $ids = [];
    if(!empty($posts) and is_array($posts)){
        foreach ($posts as $key => $value) {
            $ids[] = $value;
        }
    }
    $ids = implode(",", $ids);
    if($ids){
        if($title){
            echo do_shortcode('[gap] [title text="'.$title.'" tag_name="h4"]');
        }        
        echo do_shortcode('[ux_products style="'.$style.'" type="'.$type.'" columns="'.$columns.'" depth="'.$depth.'" depth_hover="'.$depth_hover.'" image_width="'.$image_width.'" text_align="'.$text_align.'" ids="'.$ids.'"]');
    }
    return ob_get_clean();
});