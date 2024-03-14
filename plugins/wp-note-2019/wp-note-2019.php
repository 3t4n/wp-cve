<?php
/*
Plugin Name: WP-Note 2019
Plugin URI:  https://flammlin.com/blog/2019/02/05/wp-note/
Description: Красивое оформление заметок в постах. Плагин полностью поддерживает старую версию плагина WP-Note от Luke.
Version: 1.2
Author: alado
Author URI: https://flammlin.com/
*/

function wpnote(){
    $wpnote = get_option('wpnote');
}

function stylesheet_wpnote() {
    wp_register_style( 'stylesheet_wpnote', plugins_url('/style.css', __FILE__) );
    wp_enqueue_style( 'stylesheet_wpnote' );
}
add_action( 'wp_enqueue_scripts', 'stylesheet_wpnote' );

function active_wpnote(){
    add_option('wpnote','1','active the plugin');
}

function deactive_wpnote(){
    delete_option('wpnote');
}

function rendernotes($text) {

	$text = preg_replace('/\[note\]/', '<div class="note"><div class="noteclassic">', $text);
	$text = preg_replace('/\[\/note\]/', '</div></div>', $text);
	
	$text = preg_replace('/\[important\]/', '<div class="note"><div class="noteimportant">', $text);
	$text = preg_replace('/\[\/important\]/', '</div></div>', $text);
	
	$text = preg_replace('/\[warning\]/', '<div class="note"><div class="notewarning">', $text);
	$text = preg_replace('/\[\/warning\]/', '</div></div>', $text);
	
	$text = preg_replace('/\[tip\]/', '<div class="note"><div class="notetip">', $text);
	$text = preg_replace('/\[\/tip\]/', '</div></div>', $text);
	
	$text = preg_replace('/\[help\]/', '<div class="note"><div class="notehelp">', $text);
	$text = preg_replace('/\[\/help\]/', '</div></div>', $text);

   return $text;
}

function wpnote_tinymce_button()
{
if ( current_user_can('edit_posts') && current_user_can('edit_pages') )
{
add_filter('mce_external_plugins', 'wpnote_add_tinymce_button');
add_filter('mce_buttons_2', 'wpnote_register_tinymce_button');
}
}
add_action('init', 'wpnote_tinymce_button');
function wpnote_add_tinymce_button($plugin_array){
$plugin_array['my_button_script'] = plugins_url('/js/wpnotebuttons.js', __FILE__);
return $plugin_array;
}
function wpnote_register_tinymce_button($buttons){
    array_push($buttons, "nelp");
	array_push($buttons, "important");
	array_push($buttons, "note");
	array_push($buttons, "tip");
	array_push($buttons, "warning");
    return $buttons;
    }

add_filter('the_content', 'rendernotes', 10);
add_filter('the_excerpt', 'rendernotes', 10);
add_action('wp_head', 'wpnote');

register_activation_hook(__FILE__,'active_wpnote');
register_deactivation_hook(__FILE__,'deactive_wpnote');
?>
