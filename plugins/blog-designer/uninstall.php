<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package Blog Designer
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
$bd_unintall_data = get_option( 'bd_unintall_data', 0 );
if ( 1 == $bd_unintall_data ) {
	delete_option( 'posts_per_page' );
	delete_option( 'display_sticky' );
	delete_option( 'display_category' );
	delete_option( 'social_icon_style' );
	delete_option( 'rss_use_excerpt' );
	delete_option( 'template_alternativebackground' );
	delete_option( 'display_tag' );
	delete_option( 'display_author' );
	delete_option( 'display_date' );
	delete_option( 'social_share' );
	delete_option( 'facebook_link' );
	delete_option( 'twitter_link' );
	delete_option( 'linkedin_link' );
	delete_option( 'pinterest_link' );
	delete_option( 'display_comment_count' );
	delete_option( 'excerpt_length' );
	delete_option( 'display_html_tags' );
	delete_option( 'read_more_on' );
	delete_option( 'read_more_text' );
	delete_option( 'template_titlefontsize' );
	delete_option( 'content_fontsize' );
	delete_option( 'wp_blog_designer_settings' );
	delete_option( 'blog_page_display' );
	delete_option( 'custom_css' );
	delete_option( 'is_user_subscribed_cancled' );
	delete_option( 'bd_version' );
	delete_option( 'bd_is_optin' );
	delete_option( 'bd_unintall_data' );
}
