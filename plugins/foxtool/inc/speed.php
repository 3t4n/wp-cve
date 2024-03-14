<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options;
# Remove jquery-migrate
if(isset($foxtool_options['speed-off1'])){
function foxtool_remove_jquery_migrate( $scripts ) {
   if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $script = $scripts->registered['jquery'];
   if ( $script->deps ) { 
        $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
 }
 }
 }
add_action( 'wp_default_scripts', 'foxtool_remove_jquery_migrate' );
}
# tắt Gutenberg CSS o home
if(isset($foxtool_options['speed-off2'])){
function foxtool_remove_wp_block_library_css() {
    if ( is_front_page() ) {
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'wc-blocks-style' );
    }
}
add_action( 'wp_enqueue_scripts', 'foxtool_remove_wp_block_library_css', 100 );
}
# tắt Classic CSS o home
if(isset($foxtool_options['speed-off3'])){
function foxtool_classic_styles_off() {
	if ( is_front_page()) {
    wp_dequeue_style( 'classic-theme-styles' );
	}
}
add_action( 'wp_enqueue_scripts', 'foxtool_classic_styles_off', 20 );
}
# tắt emoji 
if(isset($foxtool_options['speed-off4'])){
function foxtool_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );	
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );		
}
add_action( 'init', 'foxtool_disable_emojis' );
}
# gioi han so ban ghi trong csdl 
if(isset($foxtool_options['speed-data1'])){
function foxtool_limit_post_revisions($num, $post) {
	global $foxtool_options;
	if(!empty($foxtool_options['speed-data11'])){
    $limit = $foxtool_options['speed-data11'];
	} else {
	$limit = 3;	
	}
    return $limit;
}
add_filter('wp_revisions_to_keep', 'foxtool_limit_post_revisions', 10, 2);	
}
# gioi han thoi gian luu bai viet tu dong pút
if(isset($foxtool_options['speed-data2'])){
	if (!defined('AUTOSAVE_INTERVAL')) {
		$secon = !empty($foxtool_options['speed-data21']) ? $foxtool_options['speed-data21'] : 1;
		define('AUTOSAVE_INTERVAL', $secon * MINUTE_IN_SECONDS);
	}
}
# nhan nut xoa het ban ghi tam trong csdl làm sach csdl
function foxtool_delete_post_revisions() {
	check_ajax_referer('foxtool_post_revisions', 'security');
    if (!current_user_can('manage_options')){
        wp_die(__('Insufficient permissions', 'foxtool'));
    }
    global $wpdb;
    $sql = 'DELETE FROM `' . $wpdb->prefix . 'posts` WHERE `post_type` = %s;';
    try {
        $wpdb->query($wpdb->prepare($sql, array('revision')));
		return true;
    } catch (Exception $e) {
        return 'Error! ' . $wpdb->last_error;
    }
}
add_action('wp_ajax_foxtool_delete_revisions', 'foxtool_delete_post_revisions');
add_action('wp_ajax_nopriv_foxtool_delete_revisions', 'foxtool_delete_post_revisions'); 
# nhan nut xoa ban luu tu dong trong csdl
function foxtool_delete_auto_drafts() {
	check_ajax_referer('foxtool_post_drafts', 'security');
    if (!current_user_can('manage_options')){
        wp_die(__('Insufficient permissions', 'foxtool'));
    }
    global $wpdb;
    $sql = "DELETE FROM {$wpdb->posts} WHERE `post_status` = 'auto-draft'";
    try {
        $wpdb->query($sql);
        return true;
    } catch (Exception $e) {
        return 'Lỗi! ' . $wpdb->last_error;
    }
}
add_action('wp_ajax_foxtool_delete_auto_drafts', 'foxtool_delete_auto_drafts');
add_action('wp_ajax_nopriv_foxtool_delete_auto_drafts', 'foxtool_delete_auto_drafts');
# xoa tat ca bai trong thung rac
function foxtool_delete_all_trashed_posts() {
	check_ajax_referer('foxtool_post_trashed', 'security');
    if (!current_user_can('manage_options')){
        wp_die(__('Insufficient permissions', 'foxtool'));
    }
    global $wpdb;
    $sql = "DELETE FROM {$wpdb->posts} WHERE `post_status` = 'trash'";
    try {
        $wpdb->query($sql);
        return true;
    } catch (Exception $e) {
        return 'Lỗi! ' . $wpdb->last_error;
    }
}
add_action('wp_ajax_foxtool_delete_all_trashed_posts', 'foxtool_delete_all_trashed_posts');
add_action('wp_ajax_nopriv_foxtool_delete_all_trashed_posts', 'foxtool_delete_all_trashed_posts');
# thu vien instant-page.js tai truoc link khi di chuot
if(isset($foxtool_options['speed-link1'])){
function foxtool_instantpage_scripts() {
  wp_enqueue_script( 'instantpage', FOXTOOL_URL . 'link/instantpage.js', array(), '5.7.0', true );
}
add_action( 'wp_enqueue_scripts', 'foxtool_instantpage_scripts' );
function foxtool_instantpage_loader_tag( $tag, $handle ) {
  if ( 'instantpage' === $handle ) {
    if ( strpos( $tag, 'text/javascript' ) !== false ) {
      $tag = str_replace( 'text/javascript', 'module', $tag );
    }
    else {
      $tag = str_replace( '<script ', "<script type='module' ", $tag );
    }
  }
  return $tag;
}
add_filter( 'script_loader_tag', 'foxtool_instantpage_loader_tag', 10, 2 );
}
# cuon trang muot ma
if(isset($foxtool_options['speed-link2'])){
function foxtool_smooth_scripts() {
	wp_enqueue_script( 'smooth-scroll', FOXTOOL_URL . 'link/smooth-scroll.min.js', array(), true );
}
add_action( 'wp_enqueue_scripts', 'foxtool_smooth_scripts' );
}
# lazyload hinh anh
if(isset($foxtool_options['speed-lazy1'])){
function foxtool_lazyload_to_images_with_jquery() {
    if (!is_admin()) {
        wp_add_inline_script('jquery', '
            jQuery(document).ready(function($) {
                $("img").addClass("lazyload").each(function() {
                    var dataSrc = $(this).attr("src");
                    $(this).attr("data-src", dataSrc).removeAttr("src");
                });
            });
        ');
		wp_enqueue_script( 'lazyload', FOXTOOL_URL . 'link/lazysizes.min.js', array('jquery'), '5.3.2', true);
    }
}
add_action('wp_enqueue_scripts', 'foxtool_lazyload_to_images_with_jquery');
}
# tuy chon nen html
function foxtool_minify_html_output($buffer) {
	global $foxtool_options;
	if ( substr( ltrim( $buffer ), 0, 5) == '<?xml' )
		return ( $buffer );
	if ( isset($foxtool_options['speed-zip16']) && mb_detect_encoding($buffer, 'UTF-8', true) )
		$mod = '/u';
	else
		$mod = '/s';
	$buffer = str_replace(array (chr(13) . chr(10), chr(9)), array (chr(10), ''), $buffer);
	$buffer = str_ireplace(array ('<script', '/script>', '<pre', '/pre>', '<textarea', '/textarea>', '<style', '/style>'), array ('M1N1FY-ST4RT<script', '/script>M1N1FY-3ND', 'M1N1FY-ST4RT<pre', '/pre>M1N1FY-3ND', 'M1N1FY-ST4RT<textarea', '/textarea>M1N1FY-3ND', 'M1N1FY-ST4RT<style', '/style>M1N1FY-3ND'), $buffer);
	$split = explode('M1N1FY-3ND', $buffer);
	$buffer = ''; 
	for ( $i=0; $i<count($split); $i++ ) {
		$ii = strpos($split[$i], 'M1N1FY-ST4RT');
		if ( $ii !== false ) {
			$process = substr($split[$i], 0, $ii);
			$asis = substr($split[$i], $ii + 12);
			if ( substr($asis, 0, 7) == '<script' ) {
				$split2 = explode(chr(10), $asis);
				$asis = '';
				for ( $iii = 0; $iii < count($split2); $iii ++ ) {
					if ( $split2[$iii] )
						$asis .= trim($split2[$iii]) . chr(10);
					if ( isset($foxtool_options['speed-zip11']) ) {
						$last = substr(trim($split2[$iii]), -1);
						if ( strpos($split2[$iii], '//') !== false && ($last == ';' || $last == '>' || $last == '{' || $last == '}' || $last == ',') )
							$asis .= chr(10);
					}
				}
				if ( $asis )
					$asis = substr($asis, 0, -1);
				if ( isset($foxtool_options['speed-zip12']) )
					$asis = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $asis);
				if ( isset($foxtool_options['speed-zip11']) )
					$asis = str_replace(array (';' . chr(10), '>' . chr(10), '{' . chr(10), '}' . chr(10), ',' . chr(10)), array(';', '>', '{', '}', ','), $asis);
			} else if ( substr($asis, 0, 6) == '<style' ) {
				$asis = preg_replace(array ('/\>[^\S ]+' . $mod, '/[^\S ]+\<' . $mod, '/(\s)+' . $mod), array('>', '<', '\\1'), $asis);
				if ( isset($foxtool_options['speed-zip12']) )
					$asis = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $asis);
				$asis = str_replace(array (chr(10), ' {', '{ ', ' }', '} ', '( ', ' )', ' :', ': ', ' ;', '; ', ' ,', ', ', ';}'), array('', '{', '{', '}', '}', '(', ')', ':', ':', ';', ';', ',', ',', '}'), $asis);
			}
		} else {
			$process = $split[$i];
			$asis = '';
		}
		$process = preg_replace(array ('/\>[^\S ]+' . $mod, '/[^\S ]+\<' . $mod, '/(\s)+' . $mod), array('>', '<', '\\1'), $process);
		if ( isset($foxtool_options['speed-zip12']) )
			$process = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->' . $mod, '', $process);
		$buffer .= $process.$asis;
	}
	$buffer = str_replace(array (chr(10) . '<script', chr(10) . '<style', '*/' . chr(10), 'M1N1FY-ST4RT'), array('<script', '<style', '*/', ''), $buffer);
	if ( isset($foxtool_options['speed-zip13']) && strtolower( substr( ltrim( $buffer ), 0, 15 ) ) == '<!doctype html>' )
		$buffer = str_replace( ' />', '>', $buffer );
	if ( isset($foxtool_options['speed-zip14']) )
		$buffer = str_replace( array ( 'https://' . $_SERVER['HTTP_HOST'] . '/', 'http://' . $_SERVER['HTTP_HOST'] . '/', '//' . $_SERVER['HTTP_HOST'] . '/' ), array( '/', '/', '/' ), $buffer );
	if (isset($foxtool_options['speed-zip15']))
		$buffer = str_replace( array( 'http://', 'https://' ), '//', $buffer );
	return ( $buffer );
}
function foxtool_init_minify_html(){
	global $foxtool_options;
	if(isset($foxtool_options['speed-zip1']) && !current_user_can('administrator')){
		ob_start('foxtool_minify_html_output');
	}
}
add_action( 'init', 'foxtool_init_minify_html', 1 );