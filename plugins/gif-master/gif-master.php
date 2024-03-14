<?php
/**
* Plugin Name: GIF Master - Awesome GIFs with Giphy and Tenor
* Description: Easily Search and Insert gifs within your WordPress website from world's largest gif hosts.
* Version: 1.0.1
* Author: Media Jedi
* Author URI: https://mediajedi.com/
* License: GPL+2
* Text Domain: gif-master
* Domain Path: /languages
*/

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

add_action('init', 'gifm_add_tinymce_button');
if (!function_exists('gifm_add_tinymce_button')) {
	function gifm_add_tinymce_button(){
		if (!current_user_can('edit_posts') && !current_user_can('edit_pages')){ return; }
		if ( get_user_option( 'rich_editing' ) !== 'true' ) { return; }
		add_filter('mce_external_plugins', 'gifm_add_btns');	
		add_filter('mce_buttons', 'gifm_register_btns');
	}
}
if (!function_exists('gifm_add_btns')) {
	function gifm_add_btns($plugin_array ){
		$plugin_array ['gifmaster_new'] = plugins_url( '/js/gifm_tinymce.js',__FILE__ );
		return $plugin_array ;
	}
}
if (!function_exists('gifm_register_btns')) {
	function gifm_register_btns($buttons){
		array_push($buttons, '|', 'gifmaster_new');
		return $buttons;
	}
}

add_filter('tiny_mce_version', 'gifm_check_mce_ver');
if (!function_exists('gifm_check_mce_ver')) {
	function gifm_check_mce_ver($ver) {
		$ver += 3;
		return $ver;
	}
}

add_action( 'admin_enqueue_scripts', 'gifm_reg_admin_scripts' );
if(!function_exists('gifm_reg_admin_scripts')){
	function gifm_reg_admin_scripts(){
		wp_enqueue_script(
			'gifm-tinymce-script',
			plugins_url('js/custom.js',__FILE__ ),
			array('jquery')
		);
		$gifm_tenor_api = get_option('gifm_tenor_api');
		$gifm_giphy_api = get_option('gifm_giphy_api');
		wp_localize_script(
			'gifm-tinymce-script',
			'gifm_tinymce_obj',
			array( 'tenor_key' => $gifm_tenor_api, 'giphy_key' => $gifm_giphy_api )
		);
		
	}
}


// Plugin Configuration Page
if(is_admin()){
	add_action('admin_menu', 'gifm_admin_config');
	if (!function_exists('gifm_admin_config')) {
		function gifm_admin_config() {
			add_options_page('GIF Master', 'GIF Master', 'manage_options', 'gifm', 'gifm_config_callback'); 
		}
	}
	if (!function_exists('gifm_config_callback')) {
		function gifm_config_callback(){
			if (!current_user_can('manage_options')){
				wp_die( __('You do not have sufficient permissions to access this page.') );
			}
			
			if(!empty($_POST) && check_admin_referer('gifm_config_update', 'gifm_config_nonce')){
				if(isset($_POST['gifm_config_submit'])){
					if(isset($_POST['gifm_tenor_api'])){
						$gifm_tenor_api = sanitize_text_field($_POST['gifm_tenor_api']);
						update_option('gifm_tenor_api', $gifm_tenor_api);
					}
					if(isset($_POST['gifm_giphy_api'])){
						$gifm_giphy_api = sanitize_text_field($_POST['gifm_giphy_api']);
						update_option('gifm_giphy_api', $gifm_giphy_api);
					}
					echo '<div class="notice notice-success is-dismissible"><p>Settings Saved!</p></div>';
				}
			}
			?>

			<div class="wrap">
				<div id="apfl_settings">
					<form method='POST' action="">
						<br>
						<h1>GIF Master Settings</h1>
						<table class="form-table">
							<tr>
								<th>
									<?php $gifm_tenor_api = get_option('gifm_tenor_api'); ?>
									<label for="gifm_tenor_api">Tenor API Key </label>
								</th>
								<td>
									<input type="text" name="gifm_tenor_api" id="gifm_tenor_api" style="min-width: 350px;" value="<?php echo $gifm_tenor_api; ?>">
									<a href="https://developers.google.com/tenor/guides/quickstart#setup" target="_blank"> Create one Here (v2).</a> <span>If you used v1 key in the past, please create a new one for v2</span>
								</td>
							</tr>
							<tr>
								<th>
									<?php $gifm_giphy_api = get_option('gifm_giphy_api'); ?>
									<label for="gifm_giphy_api">Giphy API Key </label>
								</th>
								<td>
									<input type="text" name="gifm_giphy_api" id="gifm_giphy_api" style="min-width: 350px;" value="<?php echo $gifm_giphy_api; ?>">
									<a href="https://developers.giphy.com/dashboard/?create=true" target="_blank"> Create one Here (select API Key)</a>
								</td>
							</tr>
						</table>
						<p class="submit">
							<input type="submit" name="gifm_config_submit" id="gifm_config_submit" class="button-primary" value="Save"/>
						</p>
						<?php wp_nonce_field( 'gifm_config_update','gifm_config_nonce' ); ?>
					</form>
				</div>
			</div>
		<?php
		}
	}
}
