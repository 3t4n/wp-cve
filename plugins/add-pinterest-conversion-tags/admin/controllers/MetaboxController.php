<?php

namespace Pagup\Pctag\Controllers;

use  Pagup\Pctag\Core\Plugin ;
use  Pagup\Pctag\Core\Request ;
class MetaboxController
{
    protected  $enables = array(
        "enable_signup",
        "enable_watchVideo",
        "enable_lead",
        "enable_custom"
    ) ;
    protected  $events = array(
        'signup_events',
        'watchVideo_events',
        'lead_events',
        'custom_events'
    ) ;
    public function add_metabox()
    {
    }
    
    public function metabox( $post )
    {
        $fields = array_merge( $this->enables, $this->events );
        $data = [];
        foreach ( $fields as $field ) {
            $data[$field] = get_post_meta( $post->ID, $field, true );
        }
        wp_localize_script( 'pctag_script', 'data', $data );
        // var_dump($data);
        return Plugin::view( 'metabox', $data );
    }
    
    public function save_meta( $postid )
    {
    }
    
    public function addEvents( string $name, int $postid )
    {
        if ( !Request::check( $name . '-type' ) || !Request::check( $name . '-value' ) ) {
            return;
        }
        $type = $_POST[$name . '-type'];
        $value = $_POST[$name . '-value'];
        
        if ( isset( $type ) && !empty($type) ) {
            $count = count( $type );
            $existing_event = get_post_meta( $postid, $name . '_events', true );
            $event = [];
            $pattern = '/^[a-zA-Z0-9_]+$/';
            for ( $i = 0 ;  $i < $count ;  $i++ ) {
                
                if ( !empty($type[$i]) && !empty($value[$i]) && preg_match( $pattern, $type[$i] ) ) {
                    $event[$i]['type'] = sanitize_text_field( $type[$i] );
                    $event[$i]['value'] = sanitize_text_field( $value[$i] );
                }
            
            }
            
            if ( !empty($event) && $event != $existing_event ) {
                update_post_meta( $postid, $name . '_events', $event );
            } elseif ( empty($event) && $existing_event ) {
                delete_post_meta( $postid, $name . '_events', $existing_event );
            }
        
        } else {
            delete_post_meta( $postid, $name . '_events' );
        }
    
    }
    
    public function cpts( $excludes )
    {
        // All CPTs.
        $post_types = get_post_types( array(
            'public' => true,
        ), 'objects' );
        // remove Excluded CPTs from All CPTs.
        foreach ( $excludes as $exclude ) {
            unset( $post_types[$exclude] );
        }
        return $post_types;
    }

}
$metabox = new MetaboxController();