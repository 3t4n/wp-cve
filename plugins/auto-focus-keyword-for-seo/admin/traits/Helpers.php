<?php

namespace Pagup\AutoFocusKeyword\Traits;

use  Pagup\AutoFocusKeyword\Core\Option ;
trait Helpers
{
    public function post_types() : string
    {
        global  $wpdb ;
        $allowed_post_types = ( Option::check( 'post_types' ) ? Option::get( 'post_types' ) : [] );
        if ( in_array( 'product', $allowed_post_types ) ) {
            unset( $allowed_post_types[array_search( 'product', $allowed_post_types )] );
        }
        // Create a string of placeholders and prepare the whole list of post types
        $placeholders = implode( ', ', array_fill( 0, count( $allowed_post_types ), '%s' ) );
        $post_types = $wpdb->prepare( $placeholders, $allowed_post_types );
        // $post_types is now a string ready to use in an IN clause
        return $post_types;
    }
    
    public function cpts( $excludes = array() )
    {
        // All CPTs.
        $post_types = get_post_types( array(
            'public' => true,
        ), 'objects' );
        // remove Excluded CPTs from All CPTs.
        foreach ( $excludes as $exclude ) {
            unset( $post_types[$exclude] );
        }
        $types = [];
        foreach ( $post_types as $post_type ) {
            $label = get_post_type_labels( $post_type );
            $types[$label->name] = $post_type->name;
        }
        return $types;
    }
    
    public function meta_key() : string
    {
        
        if ( class_exists( 'WPSEO_Meta' ) ) {
            $meta_key = '_yoast_wpseo_focuskw';
        } elseif ( class_exists( 'RankMath' ) ) {
            $meta_key = 'rank_math_focus_keyword';
        } else {
            $meta_key = '';
        }
        
        return $meta_key;
    }
    
    /**
     * Get the list of blacklist URL's string from Options, converts it to an array, and use the array map function to convert each URL to ID.
     * 
     * @return array
     */
    public function blacklist() : array
    {
        $blacklist = ( Option::check( 'blacklist' ) ? Option::get( 'blacklist' ) : [] );
        if ( empty($blacklist) ) {
            return $blacklist;
        }
        $blacklist = array_map( 'intval', explode( ',', $blacklist ) );
        return $blacklist;
    }
    
    /**
     * Get list of items with id, title, url. set $keyword to true to get yoast focus keyword
     * 
     * @param array $ids
     * @param boolean $type
     * @return array $list
     */
    public function get_items( $ids, $type = false )
    {
        $list = [];
        $i = 0;
        foreach ( $ids as $id ) {
            // Create Array of Objects
            $post_type = ( $type === true ? " (" . $this->post_type( $id ) . ")" : "" );
            $title = get_the_title( $id );
            $title = html_entity_decode( $title, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
            
            if ( !empty($title) ) {
                $post = [
                    'value' => $id,
                    'label' => $title . $post_type,
                ];
                array_push( $list, $post );
            }
            
            $i++;
        }
        return $list;
    }
    
    /**
     * Get post type label from post type object
     * 
     * @param int $post_id
     * @return string
     */
    public function post_type( $post_id )
    {
        $post_type_obj = get_post_type_object( get_post_type( $post_id ) );
        return $post_type_obj->labels->singular_name;
    }

}