<?php

namespace Pagup\Twitter\Controllers;

use  Pagup\Twitter\Core\Plugin ;
use  Pagup\Twitter\Core\Request ;
class MetaboxController
{
    public function add_metabox()
    {
    }
    
    public function metabox( $post )
    {
        $data = [
            'atp_custom_pixel'      => get_post_meta( $post->ID, 'atp_custom_pixel', true ),
            'atp_custom_pixel_code' => get_post_meta( $post->ID, 'atp_custom_pixel_code', true ),
            'text_domain'           => Plugin::domain(),
        ];
        //$meta = get_post_meta($post->ID);
        //var_dump($meta);
        return Plugin::view( 'metabox', $data );
    }
    
    public function metadata( $postid )
    {
    }

}
$metabox = new MetaboxController();