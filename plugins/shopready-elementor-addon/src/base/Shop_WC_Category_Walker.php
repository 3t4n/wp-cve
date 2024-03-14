<?php
namespace Shop_Ready\base;

include_once WC()->plugin_path() . '/includes/walkers/class-wc-product-cat-list-walker.php';

class Shop_WC_Category_Walker extends \WC_Product_Cat_List_Walker {

    public function start_el( &$output, $cat, $depth = 0, $args = array(), $current_object_id = 0 ) {

        $cat_id = intval( $cat->term_id );

        $output .= '<li class="cat-item cat-item-' . esc_html($cat_id);
    
        if ( $args['current_category'] === $cat_id ) {
          $output .= ' current-cat';
        }
    
        if ( $args['has_children'] && $args['hierarchical'] && ( empty( $args['max_depth'] ) || $args['max_depth'] > $depth + 1 ) ) {
          $output .= ' cat-parent';
        }
    
        if ( $args['current_category_ancestors'] && $args['current_category'] && in_array( $cat_id, $args['current_category_ancestors'], true ) ) {
          $output .= ' current-cat-parent';
        }

        $_sr_id   = isset( $args['_sr_id'] ) ? $args['_sr_id'].'-'.$cat_id : '';
        $checkbox = \sprintf('<input type="checkbox" value="%s" id="%s" /> ' , $cat_id, $_sr_id );
       
        if ( $args['show_count'] ) {
          $label = \sprintf('<label for="%s"> %s <span>( %s )</span> </label>' , esc_attr( $_sr_id ), esc_html($cat->name), esc_html($cat->count) );
        }else{
          $label = \sprintf('<label for="%s"> %s </label>' , esc_attr($_sr_id), esc_html($cat->name) );
        }
        
        if( isset( $args['sr_loadable'] ) && $args['sr_loadable'] == true){
          $output .= '">' .$checkbox. '<a href="' . esc_url(get_term_link( $cat_id, $this->tree_type )) . '">' . apply_filters( 'list_product_cats', esc_html($cat->name), $cat ) . '</a>';
        }else{
          $output .= '">' .  $checkbox  . $label  ;
        }
     
       
    }

}