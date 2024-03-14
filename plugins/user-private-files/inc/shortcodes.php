<?php
/*
* Shortcode to display new post form
*/

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

// Load files
add_shortcode('upf_manager', 'upvf_display_prvt_files');
if (!function_exists('upvf_display_prvt_files')) {
	function upvf_display_prvt_files($atts){
		if( !is_admin() ){ // only run when in front-end
			
			if(is_user_logged_in()){
				
				wp_enqueue_style('upf-style');
				wp_enqueue_style('upf-font-awesome');
				wp_enqueue_style('upf-google-font');
				
				wp_enqueue_script('upf-fa-script');
				wp_enqueue_script('upf-waitforimages-script');
				wp_enqueue_script('upf-script');
				wp_enqueue_script('upvf-frnt-script');
				wp_enqueue_script('upvf-bulk-script');
				
				$html = '';
				global $upvf_template_loader;
				
				ob_start();
				$upvf_template_loader->get_template_part( 'render' );
				$html = ob_get_contents();
				ob_end_clean();
				
				return $html;
				
			} else{
				return '<p class="error">'.__("You must be logged in to upload/manage the files", "user-private-files").'</p>';
			}
			
		}
	}
}
