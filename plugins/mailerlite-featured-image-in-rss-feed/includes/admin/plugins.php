<?php
/**
 * Settings
 *
 * @package     MailerLiteFIRSS\Admin\Plugins
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Plugins row action links
 *
 * @param array $links already defined action links
 * @param string $file plugin file path and name being processed
 * @return array $links
 */
function mailerlite_firss_action_links( $links, $file ) {

    $settings_link = '<a href="' . admin_url( 'options-general.php?page=mailerlite_firss' ) . '">' . esc_html__( 'Settings', 'mailerlite-featured-image-in-rss-feed' ) . '</a>';

    if ( $file == 'mailerlite-featured-image-in-rss-feed/mailerlite-featured-image-in-rss-feed.php' )
        array_unshift( $links, $settings_link );

    return $links;
}
add_filter( 'plugin_action_links', 'mailerlite_firss_action_links', 10, 2 );

/**
 * Plugin row meta links
 *
 * @param array $input already defined meta links
 * @param string $file plugin file path and name being processed
 * @return array $input
 */
function mailerlite_firss_row_meta( $input, $file ) {

    if ( $file != 'mailerlite-featured-image-in-rss-feed/mailerlite-featured-image-in-rss-feed.php' )
        return $input;

    $custom_link = esc_url( add_query_arg( array(
            'utm_source'   => 'plugins-page',
            'utm_medium'   => 'plugin-row',
            'utm_campaign' => 'Featured Image in RSS Feed (WP Plugin)',
        ), 'https://help.mailerlite.com/article/show/29274-how-do-i-add-a-featured-image-in-my-rss-campaign' )
    );

    $links = array(
        '<a href="' . $custom_link . '">' . esc_html__( 'Documentation', 'mailerlite-featured-image-in-rss-feed' ) . '</a>',
    );

    $input = array_merge( $input, $links );

    return $input;
}
add_filter( 'plugin_row_meta', 'mailerlite_firss_row_meta', 10, 2 );