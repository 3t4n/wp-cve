<?php
/*
Plugin Name: Video PopUp
Plugin URI: https://wp-plugins.in/video-popup
Description: Display YouTube, Vimeo, SoundCloud, and MP4 Video in Popup. Pop-up Video on Page Load, Responsive video Popup, Retina ready, visual editor, unlimited Popup's, and many features! Easy to use.
Version: 1.1.4
Author: Alobaidi
Author URI: https://wp-plugins.in
License: GPLv2 or later
Text Domain: video-popup
*/

/*  Copyright 2021 Alobaidi (email: wp-plugins@outlook.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


defined( 'ABSPATH' ) or die(':)');


function video_popup_load_textdomain() {
    load_plugin_textdomain( 'video-popup', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'video_popup_load_textdomain' );


function video_popup_plugin_row_meta( $links, $file ) {
	if ( strpos( $file, 'video-popup.php' ) !== false ) {
		$new_links = array(
						'<a title="'.esc_attr__("Need help? Support? Questions? Read the Explanation of Use.", "video-popup").'" href="https://wp-plugins.in/VideoPopUp-Usage" target="_blank">'.__("Explanation of Use", "video-popup").'</a>',
						'<a href="https://wp-plugins.in" target="_blank">'.__("More Plugins", "video-popup").'</a>'
					);
		
		$links = array_merge( $links, $new_links );
	}
	
	return $links;
}
add_filter( 'plugin_row_meta', 'video_popup_plugin_row_meta', 10, 2 );


function video_popup_plugin_action_links( $actions, $plugin_file ){
	
	static $plugin;

	if ( !isset($plugin) ){
		$plugin = plugin_basename(__FILE__);
	}
		
	if ($plugin == $plugin_file) {
		
		$new_links = array(
						'<a title="'.esc_attr__("Get it at a low price! Unlock all the features. Easy to use, download it, install it, activate it, and enjoy! Get it now!", "video-popup").'" class="vp-premium-extension-link_plm" href="https://wp-plugins.in/Get-VP-Premium-Extension" target="_blank">'.__('Get The Premium Extension', 'video-popup').'</a>',
						'<a href="'.admin_url("/admin.php?page=video_popup_general_settings").'">'.__("Settings", "video-popup").'</a>'
					);
		
		$actions = array_merge($new_links, $actions);
			
	}
	
	return $actions;
	
}
add_filter( 'plugin_action_links', 'video_popup_plugin_action_links', 10, 5 );


require_once dirname( __FILE__ ). '/translation/texts.php';

require_once dirname( __FILE__ ). '/admin/admin.php';

require_once dirname( __FILE__ ). '/enqueue-scripts.php';

require_once dirname( __FILE__ ). '/features/sc_support.php';

require_once dirname( __FILE__ ). '/features/shortcode.php';

require_once dirname( __FILE__ ). '/tinymce/video_popup_tinymce.php';