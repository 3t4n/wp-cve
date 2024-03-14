<?php

function wfd_add_display_swap($html){
//If font-display is exist remove it 1st
	$html = str_replace("&display=swap", "", $html);
// Add font-display property in google fonts
	$html = str_replace("fonts.googleapis.com/css2?family", "fonts.googleapis.com/css2?display=swap&family", $html);
// Fix for webloader fonts
	$html = preg_replace("/(WebFontConfig\['google'\])(.+[\w])(.+};)/", "$1$2&display=swap$3", $html);
	return $html;	
}
add_action('init','wfd_add_display_swap', 1);
// Add html in action 
/*function wfd_font_action(){
	ob_start("wfd_add_display_swap");
}
add_action('init','wfd_font_action', 1);*/
// add font display swap in @font face
function wfd_for_font_face($content, $file_type , $urls){
	if($file_type === 'css')
		$content = str_replace('@font-face{','@font-face{font-display:swap;', $content);
		return $content;
}
add_action('litespeed_optm_cssjs','wfd_for_font_face', 10,3);
//add property font-display into custom css files

//
add_action( 'wp_print_styles', 'wfd_dequeue_font_awesome_style' );
function wfd_dequeue_font_awesome_style() {
if(wp_style_is('font-awesome_min' , 'enqueued')){
	wp_deregister_style( 'font-awesome_min'); 
}elseif(wp_style_is('font-awesome' , 'enqueued')){
	wp_deregister_style( 'font-awesome'); 
}
}
function wfd_load_css() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'font-display', $plugin_url . '/webfonts.min.css' );
}
add_action( 'wp_enqueue_scripts', 'wfd_load_css' );

?>