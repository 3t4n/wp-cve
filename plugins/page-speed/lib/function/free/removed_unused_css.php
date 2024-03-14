<?php
//Remove Gutenberg Block Library CSS from loading on the frontend
add_filter( 'wp_enqueue_scripts', 'ps_pagespeed_remove_unused_css', 100  );  
function ps_pagespeed_remove_unused_css(  ) {
	
    if ( !is_user_logged_in() ) { 
    	  
    	  $ps_pagespeed = get_option('ps_pagespeed');
    	  
    	   if ($ps_pagespeed['removed_unused_css']['gutenburg'] == "on") {
    	   
    	     wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
    wp_dequeue_style( 'storefront-gutenberg-blocks' ); // Storefront theme
    	   
    	   }
    	  
    	
    
    }

}




?>