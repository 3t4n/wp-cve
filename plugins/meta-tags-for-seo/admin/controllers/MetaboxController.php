<?php

namespace Pagup\MetaTags\Controllers;

use  Pagup\MetaTags\Core\Option ;
use  Pagup\MetaTags\Core\Plugin ;
use  Pagup\MetaTags\Core\Request ;
class MetaboxController
{
    public function add_metabox()
    {
    }
    
    public function metabox( $post )
    {
        $data = [
            'pmt_custom_tags' => get_post_meta( $post->ID, 'pmt_custom_tags', true ),
            'pmt_meta_tags'   => get_post_meta( $post->ID, 'pmt_meta_tags', true ),
            'text_domain'     => Plugin::domain(),
            'site_title'      => get_bloginfo( 'name' ),
            'title'           => get_the_title(),
        ];
        $meta = [];
        $meta['disable_tags'] = get_post_meta( $post->ID, 'pmt_disable_tags', true );
        $meta['custom_tags'] = get_post_meta( $post->ID, 'pmt_custom_tags', true );
        $meta['meta_tags'] = get_post_meta( $post->ID, 'pmt_meta_tags', true );
        // var_dump($meta);
        wp_add_inline_script( 'pmt__script', 'const meta = ' . json_encode( $meta ), 'before' );
        // wp_localize_script( 'pmt__script', 'meta', $meta);
        return Plugin::view( 'metabox', $data );
    }
    
    public function metadata( $postid )
    {
    }

}
$metabox = new MetaboxController();