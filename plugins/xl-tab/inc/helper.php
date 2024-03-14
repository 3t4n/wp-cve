<?php

namespace XLTab;
if ( ! defined( 'ABSPATH' ) ) { exit; }

class xltab_helper {
 	
   static function xltab_drop_posts($post_type){ 
        $args = array(
          'numberposts' => -1,
          'post_type'   => $post_type
        );

        $posts = get_posts( $args );        
        $list = array();
        foreach ($posts as $cpost){

            $list[$cpost->ID] = $cpost->post_title;
        }
        return $list;
    }

    static function xltab_filter_faq( $portfolio ) {
        $category_in = [];

        foreach ( $portfolio as $item ) {
            if ( $item['category'] ) {
                $cat_explode = explode( ',', $item['category'] );

                foreach ( $cat_explode as $name ) {
                    $category = strtolower( str_replace( ' ', '_', $name ) );

                    if ( !in_array($category, $category_in) ) {
                        $category_in[] = $category;
                    }
                }
            }
        }
        return $category_in;
    }
    

}

