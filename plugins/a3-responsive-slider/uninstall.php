<?php
/**
 * A3 Responsive Slider Uninstall
 *
 * Uninstalling deletes options, tables, and pages.
 *
 */
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit();

$plugin_key = 'a3_responsive_slider';

// Delete Google Font
delete_option( $plugin_key . '_google_api_key' . '_enable' );
delete_transient( $plugin_key . '_google_api_key' . '_status' );
delete_option( $plugin_key . '_google_font_list' );

if ( get_option( $plugin_key . '_clean_on_deletion' ) == 1 ) {
	delete_option( $plugin_key . '_google_api_key' );
	delete_option( $plugin_key . '_toggle_box_open' );
	delete_option( $plugin_key . '-custom-boxes' );

	delete_metadata( 'user', 0,  $plugin_key . '-' . 'plugin_framework_global_box' . '-' . 'opened', '', true );


	delete_option( $plugin_key . '_clean_on_deletion');

	global $wpdb;

	$templates_list = array(
		'template_1'		=> 'template1',
		'template_2'		=> 'template2',
		'template_card'		=> 'template_card',
		'template_widget'	=> 'template-widget',
		'template_mobile'	=> 'template-mobile',
	);

	// Delete all settings from wp_options
	foreach ( $templates_list as $template_key => $template_value ) {
		delete_option( 'a3_rslider_'.$template_value.'_global_settings' );
		delete_option( 'a3_rslider_'.$template_key );
		delete_option( 'a3_rslider_'.$template_value.'_dimensions_settings' );
		delete_option( 'a3_rslider_'.$template_value.'_slider_styles_settings' );
		delete_option( 'a3_rslider_'.$template_value.'_control_settings' );
		delete_option( 'a3_rslider_'.$template_value.'_pager_settings' );
		delete_option( 'a3_rslider_'.$template_value.'_title_settings' );
		delete_option( 'a3_rslider_'.$template_value.'_caption_settings' );
		delete_option( 'a3_rslider_'.$template_value.'_readmore_settings' );
		delete_option( 'a3_rslider_'.$template_value.'_shortcode_settings' );
	}

	// Remove all shortcode from all post embed the shortcode into the content
	$all_posts_used_sliders = $wpdb->get_results("SELECT * FROM ".$wpdb->postmeta." WHERE meta_key='_a3_slider_is_used' ");
	if ( is_array( $all_posts_used_sliders ) && count( $all_posts_used_sliders ) > 0 ) {
		foreach ( $all_posts_used_sliders as $post_meta ) {
			$my_post = get_post( $post_meta->post_id );
			$have_shortcode = false;
			
			$post_type = get_post_type( $post_meta->post_id );
			$our_shortcode = 'a3_responsive_slider';
			
			// Remove old data for this post ID
			delete_post_meta( $post_meta->post_id, '_a3_slider_is_used_' . $post_type );
			
			// Remove shortcode from the content
			$content = $my_post->post_content;
			preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
			if ( ! empty( $matches ) && is_array( $matches ) && count( $matches ) > 0 ) {
				foreach ( $matches as $shortcode ) {
					if ( $our_shortcode === $shortcode[2] ) {
						$have_shortcode = true;
						$content = str_replace( $shortcode[0], '', $content );
					}
				}
			}
			
			if ( $have_shortcode ) {
				wp_update_post( array(
					'ID'			=> $post_meta->post_id,
					'post_content'	=> $content,
				) );	
			}
		}
	}
		
	// Remove all meta key assign to all post type
	delete_post_meta_by_key( '_a3_slider_is_used' );
		
	// Drop Tables
	$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'a3_rslider_images');

	// Delete posts + data
	$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type = 'a3_slider' ;" );
	$wpdb->query( "DELETE FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE wp.ID IS NULL;" );

}
