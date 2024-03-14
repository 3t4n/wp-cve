<?php
//Remove  emojie script from loading on the frontend
add_filter( 'init', 'ps_pagespeed_remove_unused_javascript', 100  );  
function ps_pagespeed_remove_unused_javascript(  ) {
	
    if ( !is_user_logged_in() ) { 
    	  
    	  $ps_pagespeed = get_option('ps_pagespeed');
    	  
    	   if ($ps_pagespeed['removed_unused_javascript']['emoji'] == "on") {
    	   
    	    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' ); 
    	   
    	   }
    	  
    	
    
    }

}




?>