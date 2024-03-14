<?php 
use Adminz\Helper\ADMINZ_Helper_Language;
if(!class_exists( 'WooCommerce' ) ) return;
add_shortcode('adminz_flatsome_product_portfolio_info',function($atts){ 
    extract(shortcode_atts(array(
        'title'=> 'Your title',
        'show_producs_sync_portfolio'=> '',
        'title_small'=> 'Same Portfolio',
        'columns' => 2,
        'depth' => 1,
        'depth_hover'=> 2,
        'image_width'=>25,
        'text_align'=> 'left',
        'style'=> 'vertical',
        'type'=>'row'
    ), $atts));
    ob_start();
    global $product;
    $taxonomy_sync = ADMINZ_Helper_Language::get_pll_string('adminz_flatsome_portfolio_product_tax');
    $terms = get_the_terms(get_the_ID(),$taxonomy_sync);
    

    $ids = [];

    if(!empty($terms) and is_array($terms)){        
        foreach ($terms as $key => $value) {            
            $args = [
                'post_type'=> 'featured_item',
                'name'=>$value->slug,
                'fields'=>'ids'
            ];          
            foreach (get_posts($args) as $key => $value) {
                $ids[] = $value;
            }
        }
        
    }
    if(!empty($ids) and is_array($ids)){
        foreach ($ids as $key => $id) {
            $shortcode = '[row][col][title text="'.$title.'"]';
            $shortcode .= '<a href="'.get_permalink($id).'">'.get_the_title($id).'</a>';
            $shortcode.= '[/col][/row]';
            echo do_shortcode($shortcode);


            // products same portfolio 
            if($show_producs_sync_portfolio){
                echo '<div class="row"><div class="col large-12"><div class="col-inner">';
                
                $featured_item_slug = get_post_field( 'post_name', $id );
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
                        if($value !==get_the_ID()){
                            $ids[] = $value;
                        }                        
                    }
                }
                $ids = implode(",", $ids);
                if($ids){   
                    echo do_shortcode('[title text="'.$title_small.'"]');   
                    echo do_shortcode('[ux_products style="'.$style.'" type="'.$type.'" columns="'.$columns.'" depth="'.$depth.'" depth_hover="'.$depth_hover.'" image_width="'.$image_width.'" text_align="'.$text_align.'" ids="'.$ids.'"]');
                }
                echo '</div></div></div>';
            }
        }
        
    }



    return ob_get_clean();
});