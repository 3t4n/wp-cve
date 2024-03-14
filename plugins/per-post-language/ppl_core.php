<?php
/*
* Plugin Name: Per Post Language
* Plugin URI:  https://wordpress.org/plugins/per-post-language/
* Description: This plugin allows the user to set the blog language per post or page while having a default blog language.
* Domain Path: /languages
* Text Domain: per-post-language
* License:     GPLv3
* Version:     1.3
* Author:      Fahad Alduraibi
* Author URI:  http://www.fadvisor.net/blog/

Copyright (C) 2016 Fahad Alduraibi

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require 'ppl_settings_page.php';

// Add settings link on plugin page
function ppl_add_settings_links($links) { 
	$settingsLink = '<a href="options-general.php?page=ppl_settings_page">' . __('Settings') . '</a>'; 
	array_unshift($links, $settingsLink); 
	return $links; 
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'ppl_add_settings_links' );

// Run this function once when the plugin is activated to create an entry in the database to store the settings
function ppl_add_options_entry(){
	add_option( "ppl_options");	// This is not actually needed since 'update_option" also creates the entry if it doesn't exist.
}
register_activation_hook( __FILE__, "ppl_add_options_entry" );

// Set the post language when loading up the page based on the store meta
function ppl_set_post_language() {
	$postID = url_to_postid( $_SERVER["REQUEST_URI"] );
	if ($postID > 0) {
		$postLanguage = esc_attr( get_post_meta( $postID, '_ppl_post_language', true ) );
		if ( ! empty( $postLanguage ) ) {
			global $locale;
			$locale = $postLanguage;
		}
	}
}
// Any call to 'url_to_postid' earlier then 'setup_theme' will generate a fatal error.
add_action('setup_theme', 'ppl_set_post_language');

// Update the post language if the user has selected one when saving the post
function ppl_save_post_meta( $post_id ) {
	global $post;
	if( isset( $post ) && ($post->post_type == "post" || $post->post_type == "page") ) {
		if (isset( $_POST ) && isset($_POST['pplPostLang']) ) {
			update_post_meta( $post_id, '_ppl_post_language', strip_tags( $_POST['pplPostLang'] ) );
		}
	}
}
add_action( 'save_post', 'ppl_save_post_meta' );

// List of languages that are shown in the Post/Page Language box
function ppl_get_language_list( $post ) {
	$postID = $post->ID;
	if ($postID > 0) {
		$postLanguage = esc_attr( get_post_meta( $postID, '_ppl_post_language', true) );
		$pplLanguages = get_option("ppl_options");
		if ( $pplLanguages == false ) {
			esc_html_e('You need to add languages from the plugin settings page.', 'per-post-language');
			?> <a href="options-general.php?page=ppl_settings_page"><?php esc_html_e('Go to settings', 'per-post-language');?></a><?php
		} else {
			?>
			<script>
				function pplSetDir(pplDir) {
					var pplTitle  = document.getElementById('titlewrap');
					var pplBody = document.getElementById('content_ifr').contentWindow.document.getElementById('tinymce');
					if (pplDir == 'rtl') {
						pplTitle.style.direction = 'rtl';
						pplBody.style.direction = 'rtl';
					} else {
						pplTitle.style.direction = '';
						pplBody.style.direction = '';
					}
				}
			</script>
			<?php
			foreach ($pplLanguages as $key => $value) {
				// This 'if' is for people upgrading from old version with only one dimensional array
				if (empty($value['name'])) {
					$value_array['name'] = $value;
				} else {
					$value_array = $value;
				}
				
				if (isset($value_array['dir']) and $value_array['dir'] == 'on') {
					$pplDir = 'rtl';
					
					// Run the function to set the direction onload if the selected language is RTL
					if ($postLanguage == $key) {
						?>
						<script>
							// Wait until the page is fully loaded then set the direction
							window.onload = function() {
								pplSetDir('rtl');
							}
						</script>
						<?php
					}
				} else {
					$pplDir = '';
				}
				?>
				<input type="radio" name="pplPostLang" value="<?php echo $key; ?>" onclick="pplSetDir('<?php echo $pplDir; ?>')" <?php if ($postLanguage == $key){ echo "checked=\"checked\"";} ?>>
					<?php echo $value_array['name']; ?>
				</input><br />
				<?php
			}
		}
	}
}

// Post Language box is shown when editing a post, on the side ('high' makes it show on the top)
function ppl_register_meta_boxes( $post ) {
	if(current_user_can( 'edit_posts' ) ){
		add_meta_box( 'ppl_meta_box', esc_html__( 'Post Language', 'per-post-language' ), 'ppl_get_language_list', 'post', 'side', 'high', null );
	}
}
add_action( 'add_meta_boxes_post', 'ppl_register_meta_boxes' );

// Page Language box is shown when editing a page, on the side ('high' makes it show on the top)
function ppl_register_meta_boxes_page( $post ) {
	if(current_user_can( 'edit_pages' ) ){
		add_meta_box( 'ppl_meta_box', esc_html__( 'Page Language', 'per-post-language' ), 'ppl_get_language_list', 'page', 'side', 'high', null );
	}
}
add_action( 'add_meta_boxes_page', 'ppl_register_meta_boxes_page' );

// Load plugin textdomain (translation file).
function ppl_load_textdomain() {
	load_plugin_textdomain( 'per-post-language', false, dirname(plugin_basename(__FILE__)) . '/languages' ); 
}
add_action( 'init', 'ppl_load_textdomain' );

?>
