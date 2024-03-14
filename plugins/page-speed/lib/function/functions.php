<?php
//  add admin menu
add_action('admin_menu', 'pg_pagespeed_menu');

function pg_pagespeed_menu() {

// Add a new top-level menu (ill-advised):
    add_menu_page(__('PageSpeed','pagespeed_menu'), __('PageSpeed','pagespeed_menu'), 'manage_options', 'pagespeed-settings', 'ps_pagespeed_general_settings', 'dashicons-chart-area' );

// Add a submenu to the custom top-level menu:
   //  add_submenu_page('pagespeed-settings', __('PageSpeed PRO','pagespeed_menu'), __('PageSpeed PRO','pagespeed_menu'), 'manage_options', 'pagespeed-pro-settings', 'ps_pagespeed_pro_settings');	

}

// register admin styles and scripts
add_action( 'admin_enqueue_scripts', 'pg_pagespeed_admin_scripts' );  
function pg_pagespeed_admin_scripts($hook) {
	
	wp_register_script( 'pagespeed-admin-script',  plugins_url( ) . '/page-speed/lib/js/admin-script.js' );
	wp_enqueue_script( 'pagespeed-admin-script' );
	
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'jquery-ui-accordion' );
	
}





function ps_pagespeed_default_option() {
	$option_name =  'ps_pagespeed';
    $new_value =  array(
    	       "enable_text_compression" =>  "on",
    	       "cache_policy" =>  "on",
    	       "render_blocking_resources" =>  array(
    	            "javascript" => "defer",
    	            "stylesheet" => "defer",
    	           ),
    	        "removed_unused_javascript" =>  array(
    	            "emoji" => "on",
    	           ),
    	        "removed_unused_css" =>  array(
    	            "gutenburg" => "on",
    	           ),
    	       "serve_images_in_nextgen_formats" =>  array(
    	            "upload_webp" => "off",
    	           ),
    	       
    	       "properly_size_images" =>  array(
    	            "srcset_img" => "off",
    	           ),
    	       "defer_offscreen_images" =>  array(
    	            "lazyload_img" => "off",
    	            "lazyload_bg" => "off",
    	           ),
    	       "efficiently_encode_images" =>  array(
    	            "jpg_quality" => "off",
    	           ),
    	       "reduce_the_impact_of_third_part_code" =>  array(
    	            "iframe" => "off",
    	           ),
    	    );
     
    if ( get_option( $option_name ) !== false ) {
     
        // The option already exists, so update it.
       // delete_option( $option_name, $new_value );
     
    } else {
     
        // The option hasn't been created yet, so add it with $autoload set to 'no'.
        $deprecated = null;
        $autoload = 'no';
        add_option( $option_name, $new_value, $deprecated, $autoload );
    }
}
 add_action( 'init', 'ps_pagespeed_default_option' );
 
 
// beta  testing 















?>