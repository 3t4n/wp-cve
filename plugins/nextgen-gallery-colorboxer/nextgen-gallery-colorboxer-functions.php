<?php


/**********************************************************************
* check if post contains [nggallery id=x] shortcode
**********************************************************************/

function nggcb_check_nggallery_shortcode() {

    global $post;
    global $nggcb_options;
 	
 	if (!is_admin()) {
		
		if (have_posts()) {
			while (have_posts()) { 
				the_post();

    			$pattern = get_shortcode_regex();

    			if (preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
    			&& array_key_exists( 2, $matches )
    			&& in_array( 'nggallery', $matches[2] ) ) {
    
    
					// see scripts-and-styles.php for functions
					add_action('wp_enqueue_scripts', 'nggcb_load_jquery', 1000);
					add_action('wp_enqueue_scripts', 'nggcb_load_colorbox', 1000);
					add_action('wp_print_styles', 'nggcb_colorbox_style', 1000);
					add_action('wp_head','nggcb_colorbox_inline_js', 1000);


				}
			}
		}
	}
}
add_action( 'wp', 'nggcb_check_nggallery_shortcode' );



/**********************************************************************
* check if page contains [singlepic id=x] shortcode
* check if page contains [imagebrowser id=x] shortcode
* check if page contains [thumb id=x] shortcode
* check if page contains [random max=x] shortcode
* check if page contains [recent max=x] shortcode
**********************************************************************/

function nggcb_check_singlepic_imagebrowser_thumb_random_recent_shortcodes() {

	if (!is_admin()) { 

    	global $post;
    	global $nggcb_options;

		if (have_posts()) {
			while (have_posts()) { 
				the_post();
    
    			$pattern = get_shortcode_regex();

    			if ((preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
    			&& array_key_exists( 2, $matches )
    			&& in_array( 'singlepic', $matches[2] ) ) ||
    	
    			(preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
    			&& array_key_exists( 2, $matches )
    			&& in_array( 'imagebrowser', $matches[2] ) ) ||
    	
    			(preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
    			&& array_key_exists( 2, $matches )
    			&& in_array( 'thumb', $matches[2] ) ) ||
    	
    			(preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
    			&& array_key_exists( 2, $matches )
    			&& in_array( 'random', $matches[2] ) ) ||
    	
    			(preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
    			&& array_key_exists( 2, $matches )
    			&& in_array( 'recent', $matches[2] ) ) ) {
    
					
					// see scripts-and-styles.php for functions
					add_action('wp_enqueue_scripts', 'nggcb_load_jquery', 1000);
					add_action('wp_enqueue_scripts', 'nggcb_load_colorbox', 1000);
					add_action('wp_print_styles', 'nggcb_colorbox_style', 1000);
					add_action('wp_head','nggcb_colorbox_inline_js', 1000);


				}
    		} 
		}
	}
}
add_action( 'wp', 'nggcb_check_singlepic_imagebrowser_thumb_random_recent_shortcodes' );



/**********************************************************************
* remove colorbox scripts from [show as slideshow] text link page
**********************************************************************/

function nggcb_add_scripts_to_slideshow_link() {
	
	if (!is_admin()) {
	
		global $nggcb_options;
	
		if(isset($_GET['show']) && $_GET['show'] == 'slide') {
		
			// see scripts-and-styles.php for functions
			remove_action('wp_print_styles', 'nggcb_colorbox_style', 1000);	
			remove_action('wp_enqueue_scripts', 'nggcb_load_colorbox', 1000);
			remove_action('wp_head','nggcb_colorbox_inline_js', 1000);
		
	
		}
	}
}
add_action('get_header', 'nggcb_add_scripts_to_slideshow_link');



/**********************************************************************
* add scripts and styles to [album id=x] GALLERY page
**********************************************************************/

function nggcb_album_gallery_page() {
	
	if (!is_admin()) {
	
		global $nggcb_options;
	
		if(isset($_GET['album']) && $_GET['album'] != '') {
			if(isset($_GET['gallery']) && $_GET['gallery'] != '') {
			
						
				if (!isset($_GET['show']) || (isset($_GET['show']) && $_GET['show'] == 'gallery')) {
		
					// see scripts-and-styles.php for functions
					add_action('wp_enqueue_scripts', 'nggcb_load_jquery', 1000);
					add_action('wp_enqueue_scripts', 'nggcb_load_colorbox', 1000);
					add_action('wp_print_styles', 'nggcb_colorbox_style', 1000);
					add_action('wp_head','nggcb_colorbox_inline_js', 1000);
				}


			}	
		}
	}
}
add_action('get_header', 'nggcb_album_gallery_page');



/**********************************************************************
* add scripts and styles to [tagcloud] GALLERY page
**********************************************************************/

function nggcb_tagcloud_gallery_page() {
	
	if (!is_admin()) {
	
		global $nggcb_options;
	
		if (isset($_GET['gallerytag']) && $_GET['gallerytag'] != '') {
		
						
			// see scripts-and-styles.php for functions
			add_action('wp_enqueue_scripts', 'nggcb_load_jquery', 1000);
			add_action('wp_enqueue_scripts', 'nggcb_load_colorbox', 1000);
			add_action('wp_print_styles', 'nggcb_colorbox_style', 1000);
			add_action('wp_head','nggcb_colorbox_inline_js', 1000);


		}	
	}
}
add_action('get_header', 'nggcb_tagcloud_gallery_page');



/**********************************************************************
* check if page contains the [nggtags album=tag] shortcode
* check if page contains the [nggtags gallery=tag] shortcode
* check for [nggtags album=tag] GALLERY page
**********************************************************************/

function nggcb_check_nggtags_shortcode() {
	
	if (!is_admin()) { 
    
    	global $post;
    	global $nggcb_options;
    	
    	if (have_posts()) {
			while (have_posts()) { 
				the_post();
    	
    			$pattern = get_shortcode_regex();

    			if (preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
    			&& array_key_exists( 2, $matches )
    			&& in_array( 'nggtags', $matches[2]) ) {
    	
					if(isset($matches[0])) {
						foreach($matches[0] as $match) {

				
							// check if it's an nggtags gallery page OR an nggtags album gallery page
		
							if ((strpos($match,'nggtags gallery') !== false) ||
							(isset($_GET['gallerytag']) && $_GET['gallerytag'] != '')) {

					
								// see scripts-and-styles.php for functions
								add_action('wp_enqueue_scripts', 'nggcb_load_jquery', 1000);
								add_action('wp_enqueue_scripts', 'nggcb_load_colorbox', 1000);
								add_action('wp_print_styles', 'nggcb_colorbox_style', 1000);
								add_action('wp_head','nggcb_colorbox_inline_js', 1000);


							}
						}
    				} 
				}
			}
		}
	}
}
add_action( 'wp', 'nggcb_check_nggtags_shortcode' );