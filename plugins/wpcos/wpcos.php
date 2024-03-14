<?php
/**
Plugin Name: WPCOS(腾讯云对象存储)
Plugin URI: https://www.lezaiyun.com/wpcos.html
Description: WordPress同步附件内容远程至腾讯云COS云存储中，实现网站数据与静态资源分离，提高网站加载速度。公众号：乐在云。
Version: 4.7.2
Author: 老蒋和他的小伙伴
Author URI: https://www.lezaiyun.com/
*/

require_once 'wpcos_actions.php';
$current_wp_version = get_bloginfo('version');
register_activation_hook(__FILE__, 'wpcos_set_options');
register_deactivation_hook(__FILE__, 'wpcos_restore_options');
add_action('upgrader_process_complete', 'wpcos_upgrade_options');
add_filter( 'sanitize_file_name', 'wpcos_sanitize_file_name', 10, 1 );
if (substr_count($_SERVER['REQUEST_URI'], '/update.php') <= 0) {
    add_filter('wp_handle_upload', 'wpcos_upload_attachments');
	if ( (float)$current_wp_version < 5.3 ){
		add_filter( 'wp_update_attachment_metadata', 'wpcos_upload_and_thumbs' );
	} else {
		add_filter( 'wp_generate_attachment_metadata', 'wpcos_upload_and_thumbs' );
		add_filter( 'wp_save_image_editor_file', 'wpcos_save_image_editor_file' );  
	}
}
add_filter('wp_unique_filename', 'wpcos_unique_filename');
add_action('delete_attachment', 'wpcos_delete_remote_attachment');
add_filter('the_content', 'wpcos_image_processing');
add_action('admin_menu', 'wpcos_add_setting_page');
add_filter('plugin_action_links', 'wpcos_plugin_action_links', 10, 2);
//add_filter('wp_calculate_image_srcset', 'wpcos_disable_srcset');


function wpcos_save_image_editor_file($override){
	add_filter( 'wp_update_attachment_metadata', 'wpcos_image_editor_file_save' );
	return $override;
}

function wpcos_image_editor_file_save( $metadata ){
	$wpcos_options = get_option('wpcos_options');
	$wp_uploads = wp_upload_dir();
	
	if (isset( $metadata['file'] )) {
		$attachment_key = '/' . $metadata['file'];
		$attachment_local_path = $wp_uploads['basedir'] . $attachment_key;
		$opt = array('Content-Type' => $metadata['type']);
		wpcos_file_upload($attachment_key, $attachment_local_path, $opt, $wpcos_options['no_local_file']);
	}
	if (isset($metadata['sizes']) && count($metadata['sizes']) > 0) {
		foreach ($metadata['sizes'] as $val) {
			$attachment_thumbs_key = '/' . dirname($metadata['file']) . '/' . $val['file'];
			$attachment_thumbs_local_path = $wp_uploads['basedir'] . $attachment_thumbs_key;
			$opt = array('Content-Type' => $val['mime-type']);
			wpcos_file_upload($attachment_thumbs_key, $attachment_thumbs_local_path, $opt, $wpcos_options['no_local_file']);
		}
	}
	remove_filter( 'wp_update_attachment_metadata', 'wpcos_image_editor_file_save' );
	return $metadata;
}
