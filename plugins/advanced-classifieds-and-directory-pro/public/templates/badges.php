<?php

/**
 * Listing Badges.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

$badges_settings = get_option( 'acadp_badges_settings' );
$featured_listing_settings = get_option( 'acadp_featured_listing_settings' );

$badges = array();

if ( ! empty( $badges_settings['show_new_tag'] ) ) {		
    $each_hours = 60 * 60 * 24; // Seconds in a day
    $s_date1 = strtotime( current_time( 'mysql' ) ); // Seconds for date 1
    $s_date2 = strtotime( $post->post_date ); // Seconds for date 2
    $s_date_diff = abs( $s_date1 - $s_date2 ); // Different of the two dates in seconds
    $days = round( $s_date_diff / $each_hours ); // Divided the different with second in a day

    if ( $days <= (int) $badges_settings['new_listing_threshold'] ) {
        $badges[] = sprintf(
            '<span class="acadp-badge acadp-badge-new">%s</span>',
            esc_html( $badges_settings['new_listing_label'] )
        );
    }		
}

if ( ! empty( $badges_settings['show_popular_tag'] ) ) {	
    if ( isset( $post_meta['views'] ) && (int) $post_meta['views'][0] >= (int) $badges_settings['popular_listing_threshold'] ) {
        $badges[] = sprintf(
            '<span class="acadp-badge acadp-badge-popular">%s</span>',
            esc_html( $badges_settings['popular_listing_label'] )
        );
    }		
}

if ( ! empty( $featured_listing_settings['show_featured_tag'] ) ) {	
    if ( isset( $post_meta['featured'] ) && 1 == (int) $post_meta['featured'][0] ) {
        $badges[] = sprintf(
            '<span class="acadp-badge acadp-badge-featured">%s</span>',
            esc_html( $featured_listing_settings['label'] )
        );
    }		
}

if ( ! empty( $badges_settings['mark_as_sold'] ) ) {	
    if ( isset( $post_meta['sold'] ) && 1 == (int) $post_meta['sold'][0] ) {
        $badges[] = sprintf(
            '<span class="acadp-badge acadp-badge-sold">%s</span>',
            esc_html( $badges_settings['sold_listing_label'] )
        );
    }		
}

if ( empty( $badges ) ) {
    return false;
}
?>

<div class="acadp-badges acadp-flex acadp-flex-wrap acadp-gap-1">
    <?php echo implode( '', $badges ); ?>
</div>