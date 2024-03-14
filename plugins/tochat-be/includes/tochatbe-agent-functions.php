<?php
defined( 'ABSPATH' ) || exit;

function tochatbe_get_agents() {
    $agents = array();

    $query = new WP_Query( array(
        'post_type'      => 'tochatbe_agent',
        'posts_per_page' => -1,
    ) );

    if ( $query->have_posts() ) {
        while( $query->have_posts() ) { $query->the_post();
            $agents[] = new TOCHATBE_Agent( get_the_ID() );
        }

        wp_reset_postdata();
    }

    return $agents;
}
