<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'restricted access' );
}

if ( ! function_exists( 'WMCC_add_meta_boxes' ) ) {
    add_action( 'add_meta_boxes', 'WMCC_add_meta_boxes' );
    function WMCC_add_meta_boxes() {
        
        $wmcc_post_types = get_site_option( 'wmcc_post_types' );
        $new_wmcc_post_types = 'none';
        if ( $wmcc_post_types ) {            
            foreach( $wmcc_post_types as $wmcc_post_type ) {
                if ( $wmcc_post_type == get_post_type() ) {
                    if ( $wmcc_post_type == 'post' || $wmcc_post_type == 'page' ) {
                        $new_wmcc_post_types = get_post_type();
                    }
                }
            }
            
            $current_user = wp_get_current_user();  
            if ( $current_user != null ) {
                $current_user_role = $current_user->roles[0];
            }            
            
            $wmcc_user_roles = get_site_option( 'wmcc_user_roles' );
            if ( ! $wmcc_user_roles ) {
                $wmcc_user_roles = array();
            }
            
            if ( is_super_admin() || ( in_array( $current_user_role, $wmcc_user_roles ) ) ) {
                add_meta_box( 'wmcc-content-copier', __( 'WordPress Multisite Content Copier/Updater: Copy/Update Content' ), 'WMCC_content_copier_callback', $new_wmcc_post_types );               
            }
        }
    }
}

if ( ! function_exists( 'WMCC_content_copier_callback' ) ) {
    function WMCC_content_copier_callback( $post ) {
        
        $post_status = get_post_status( get_the_ID() );
        if ( $post_status == 'publish' || $post_status == 'future' || $post_status == 'private' ) {
            ?>
                <div id="wmcc-content" item-id="<?php echo get_the_ID(); ?>" type="post_type" type-name="<?php echo get_post_type(); ?>"></div>
            <?php
        } else {
            ?><p><?php _e( 'If you want to copy/update, first published it. Once published, just refresh it.' ); ?></p><?php
        }
    }
}

/*if ( ! function_exists( 'WMCC_trash_post' ) ) {
    add_action( 'wp_trash_post', 'WMCC_trash_post' );
    function WMCC_trash_post( $post_id ) {
        
        global $wpdb;
        
        $blog_id = get_current_blog_id();
        $type = 'post_type';
        $type_name = get_post_type( $post_id );
        
        $wpdb->delete( 
            $wpdb->base_prefix.'wmcc_relationships', 
            array( 
                'source_item_id'    => $post_id,
                'source_blog_id'    => $blog_id,
                'type'              => $type,
                'type_name'         => $type_name,
            ) 
        );
        
        $wpdb->delete( 
            $wpdb->base_prefix.'wmcc_relationships', 
            array( 
                'destination_item_id'   => $post_id,
                'destination_blog_id'   => $blog_id,
                'type'                  => $type,
                'type_name'             => $type_name,
            ) 
        );
    }
}*/