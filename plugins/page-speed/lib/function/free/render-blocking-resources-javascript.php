<?php
// render blocking resources javascript
add_filter( 'script_loader_tag', 'ps_pagespeed_render_blocking_resources_javascript_attribute', 10, 2 );  
function ps_pagespeed_render_blocking_resources_javascript_attribute( $tag, $handle  ) {
	
if ( !is_user_logged_in() ) { 
	  global $wp_scripts;
	  
	  $ps_pagespeed = get_option('ps_pagespeed');
	  
	  
	if ( 'jquery-core' === $handle ) {
        return str_replace(' src',  ' async src', $tag);
    }
    if ( 'jquery-migrate' === $handle ) {
        return str_replace(' src',  ' async src', $tag);
    }
	else {
	    return str_replace(' src',  ' '.$ps_pagespeed['render_blocking_resources']['javascript'].' src', $tag); 
	}  	    
	  	

}
	return $tag;
}




?>