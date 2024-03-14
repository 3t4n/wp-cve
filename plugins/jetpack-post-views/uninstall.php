<?php

if( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

// Delete all options
delete_option( 'jetpack_post_views' );
delete_option( 'jetpack-post-views_version' );
delete_option( 'jetpack_post_views_wp_api_key' );
delete_option( 'jetpack_post_views_stats_has_run' );

// Undefine plugin version


// Delete post meta from each post
$interval = array(
    'Unlimited' =>  -1,
    'Day'       =>   1,
    'Week'      =>   7,
    'Month'     =>  30,
    'Year'      => 365
);

$post_types         = get_post_types( array( '_builtin' => false ), 'names' );
$post_types['post'] = 'post';

$args = array(
    'numberposts' => -1,
    'post_type'   => $post_types,
    'post_status' => 'publish'
);
$allposts = get_posts( $args );
foreach( $allposts as $post) {
    foreach ( $interval as $key => $value ) {
        if ( $key == 'Unlimited' ) {
            delete_post_meta( $post->ID, 'jetpack-post-views' );
        }
        else {
            delete_post_meta( $post->ID, 'jetpack-post-views-'.$key );
        }
    }
}