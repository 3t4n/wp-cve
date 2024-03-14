<?php
/**
 * Plugin Name: Redirect Post to URL
 * Description: Redirects a post (or any other any post-type) with a custom field <code>'redirect'</code> to another URL
 * Version: 1.2
 * Author: wp-hotline.com ~ Stefan
 * Author URI:  https://www.wp-hotline.com
 */

defined( 'ABSPATH' ) or exit;

function bhrdr2p_redirect_post_to_url() {
    if( !is_singular() ) return;

    global $post;
    $redirect = esc_url( get_post_meta( $post->ID, 'redirect', true ) );
    if( $redirect ) {
        wp_redirect( $redirect, 301 );
        exit;
    }
}
add_action( 'template_redirect', 'bhrdr2p_redirect_post_to_url' );


//redirect any drafted posts
add_action('template_redirect', 'bhrdr2p_rtrash_redirect');
function bhrdr2p_rtrash_redirect(){
    if ( !current_user_can( 'edit_pages' ) ) {
	    if (is_404()){
	        global $wp_query, $wpdb;
	        $page_id = $wpdb->get_var( $wp_query->request );
	        $post_status = get_post_status( $page_id );
          $redirect = esc_url( get_post_meta( $page_id, 'redirect', true ) );

	        if($post_status == 'draft' && $redirect){
	            wp_redirect( $redirect , 301);
	            die();
	        }
	    }
	}
}
