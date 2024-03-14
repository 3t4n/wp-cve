<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options;
# Tắt API REST
if (isset($foxtool_options['scuri-off1'])){
add_filter( 'rest_authentication_errors', function( $result ) {
    if ( true === $result || is_wp_error( $result ) ) {
        return $result;
    }
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'rest_not_logged_in',  __('You are not logged in', 'foxtool'), array( 'status' => 401 ) );
    }
    return $result;
});
}
# Tắt  XML RPC
if (isset($foxtool_options['scuri-off2'])){
add_filter( 'wp_xmlrpc_server_class', '__return_false' );
add_filter('xmlrpc_enabled', '__return_false');
add_filter('pre_update_option_enable_xmlrpc', '__return_false');
add_filter('pre_option_enable_xmlrpc', '__return_zero');
}
# Xóa Wp-Embed
if (isset($foxtool_options['scuri-off3'])){
function foxtool_deregister_scripts(){
	wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'foxtool_deregister_scripts' );
}
# Xóa xpingback header
if (isset($foxtool_options['scuri-off4'])){
function foxtool_adminify_remove_pingback_head($headers){
    if (isset($headers['X-Pingback'])) {
        unset($headers['X-Pingback']);
    }
    return $headers;
}
add_filter('wp_headers', 'foxtool_adminify_remove_pingback_head');
}
# xóa tiêu đề ko cần thiết
if (isset($foxtool_options['scuri-off5'])){
function foxtool_remove_header_info() {
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'start_post_rel_link');
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head',10,0); 
}
add_action('init', 'foxtool_remove_header_info');
}
# xóa nguồn cấp dữ liệu khác
if (isset($foxtool_options['scuri-off6'])){
function foxtool_disable_feed() {
wp_die('<a href="'. get_bloginfo('url') .'">Home</a>!');
}
add_action('do_feed', 'foxtool_disable_feed', 1);
add_action('do_feed_rdf', 'foxtool_disable_feed', 1);
add_action('do_feed_rss', 'foxtool_disable_feed', 1);
add_action('do_feed_atom', 'foxtool_disable_feed', 1);
add_action('do_feed_rss2_comments', 'foxtool_disable_feed', 1);
add_action('do_feed_atom_comments', 'foxtool_disable_feed', 1);
}
# Bao mat file ngan chan tai len ko phai la file anh
if (isset($foxtool_options['scuri-up1'])){
function foxtool_wp_handle_upload_prefilter($file) {
    if ($file['type'] == 'application/octet-stream' && isset($file['tmp_name'])) {
        $file_size = getimagesize($file['tmp_name']);
        if (isset($file_size[2]) && $file_size[2] != IMAGETYPE_UNKNOWN) {
            $file['type'] = image_type_to_mime_type($file_size[2]);
        } else {
            $file['error'] = __('Unable to determine image format', 'foxtool');
            return $file;
        }
    }
    list($category, $type) = explode('/', $file['type']);
    $allowed_types = array('jpg', 'jpeg', 'gif', 'png', 'webp', 'svg+xml');
    if ($category !== 'image' || !in_array($type, $allowed_types)) {
        $file['error'] = __('I am sorry, you can only upload image files in the formats .GIF, .JPG, .PNG, .WEBP, .SVG', 'foxtool');
    }
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'foxtool_wp_handle_upload_prefilter');
}
# Xóa ver của css và js
if (isset($foxtool_options['scuri-verof1'])){
function foxtool_remove_css_js_version( $src ) {
	if( strpos( $src, '?ver=' ) )
	$src = remove_query_arg( 'ver', $src );
	return $src;
	}
add_filter( 'style_loader_src', 'foxtool_remove_css_js_version', 9999 );
add_filter( 'script_loader_src', 'foxtool_remove_css_js_version', 9999 );
}
# xóa ver wordpress
if (isset($foxtool_options['scuri-verof2'])){
function foxtool_remove_wpversion() {
	return '';
	}
add_filter('the_generator', 'foxtool_remove_wpversion');
}
# bảo mật dữ liệu truy cập
if (isset($foxtool_options['scuri-sql1'])){
function foxtool_security_check() {
    global $user_ID;
    if ($user_ID) {
        if (!current_user_can('administrator')) {
            if (strlen($_SERVER['REQUEST_URI']) > 255 ||
                stripos($_SERVER['REQUEST_URI'], "eval(") ||
                stripos($_SERVER['REQUEST_URI'], "CONCAT") ||
                stripos($_SERVER['REQUEST_URI'], "UNION+SELECT") ||
                stripos($_SERVER['REQUEST_URI'], "base64")) {
                    @header("HTTP/1.1 414 Request-URI Too Long");
                    @header("Status: 414 Request-URI Too Long");
                    @header("Connection: Close");
                    @exit;
            }
        }
    }
}
add_action('init', 'foxtool_security_check');
}



