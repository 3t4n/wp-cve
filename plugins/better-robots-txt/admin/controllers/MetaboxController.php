<?php

namespace Pagup\BetterRobots\Controllers;

use  Pagup\BetterRobots\Core\Plugin ;
use  Pagup\BetterRobots\Core\Request ;
class MetaboxController
{
    public function add_metabox()
    {
    }
    
    public function metabox( $post )
    {
        $data = [
            'rt_disallow' => get_post_meta( $post->ID, 'rt_disallow', true ),
        ];
        // $meta = get_post_meta($post->ID);
        // var_dump($meta);
        return Plugin::view( 'metabox', $data );
    }
    
    public function metadata( $postid )
    {
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