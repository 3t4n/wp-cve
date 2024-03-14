<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit;


function apeGalleryUpdateGallery( $post_id, $post, $update ) {
    $post_type = get_post_type($post_id);
    if ( WPAPE_GALLERY_POST != $post_type ) return;
    delete_transient( 'ape_gallery_static'. $post_id ) ;
}
add_action( 'save_post', 'apeGalleryUpdateGallery', 10, 3 );

