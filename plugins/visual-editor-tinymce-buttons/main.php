<?php
/**
 * @package Extra Visual Editor Buttons
 * @version 0.3
 */
/*
Plugin Name: WordPress Tinymce visual editor buttons
Plugin URI: http://rocketplugins.com
Description: Add Extra buttons to your WordPress visual editor.
Author: Muneeb ur Rehman
Version: 0.3
Author URI: http://twitter.com/cloudplugins

Thanks to css tricks  http://css-tricks.com/snippets/wordpress/turn-on-more-buttons-in-the-wordpress-visual-editor/

*/
function muneeb_add_more_buttons($buttons) {
	 $buttons[] = 'hr';
	 $buttons[] = 'del';
	 $buttons[] = 'sub';
	 $buttons[] = 'sup';
	 $buttons[] = 'fontselect';
	 $buttons[] = 'fontsizeselect';
	 $buttons[] = 'cleanup';
	 $buttons[] = 'styleselect';
	 return $buttons;
}
add_filter("mce_buttons_3", "muneeb_add_more_buttons");
?>