<?php

function pin_generator_create_post_meta() {
    if ( is_admin()) {
        
        /* do stuff once right after activation */
        $args = array(
            'post_type' => 'post', // Only get the posts
            'posts_per_page'   => -1 // Get every post
        );

        $posts = get_posts($args);

        // Loop through all posts and add PG metadata
        foreach ( $posts as $post ) {
            if(!metadata_exists('post', $post->ID, 'pingen_pin_text') ){
                add_post_meta( $post->ID, 'pingen_pin_text', $post->post_title, true );
            }
            
            if(!metadata_exists('post', $post->ID, 'pingen_show_pin') ){
                add_post_meta( $post->ID, 'pingen_show_pin', true, true );
            }
            
            if(!metadata_exists('post', $post->ID, 'pingen_pin_image_url') ){
                add_post_meta( $post->ID, 'pingen_pin_image_url', "", true );
            }
        }
    }
}
add_action( 'admin_init', 'pin_generator_create_post_meta' );

function pin_generator_activate(){
    pin_generator_create_post_meta();
    
    //Define paths
    define("PIN_GENERATOR_PLUGIN_URL", plugin_dir_url(__FILE__));
    define("PIN_GENERATOR_PLUGIN_DIR", plugin_dir_path(__FILE__));
}
register_activation_hook(__FILE__, 'pin_generator_activate');

function pin_generator_uninstall() {
    // Delete options
    delete_option('pin_generator_access_key');
    delete_option('pin_generator_design_settings');
}
register_uninstall_hook( __FILE__, 'pin_generator_uninstall' );