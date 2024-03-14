<?php

// Render Blocking Resources Stylesheet

// css defered 
add_filter( 'style_loader_tag',  'ps_pagespeed_render_blocking_resources_stylesheet_defered', 10, 4 );
function ps_pagespeed_render_blocking_resources_stylesheet_defered( $html, $handle, $href, $media ){
    
    if ( !is_user_logged_in() ) { 
    
     global $wp_styles;
     $ps_pagespeed = get_option("ps_pagespeed");
     $onload = 'this.onload=null;this.rel="stylesheet"';
       if( in_array( $handle, $wp_styles->queue ) ){
           
           if ($ps_pagespeed['render_blocking_resources']['stylesheet'] == "defer") {
           $html = str_replace("rel='stylesheet'", " rel='stylesheet' rel='preload'  as='style' onload='$onload'  ", $html);
            }
       
        }
    
    
    }
    
    return $html;
}


  add_action( 'wp_head', 'ps_pagespeed_render_blocking_resources_stylesheet_critical_css', 1, 1 );  
function ps_pagespeed_render_blocking_resources_stylesheet_critical_css(  ) {
    
    
    if ( !is_user_logged_in() ) { 
        
     $ps_pagespeed = get_option("ps_pagespeed");
     
     ?>
     <style> 
     <?php 
     if ($ps_pagespeed['render_blocking_resources']['critical_css'] ) {
     echo $ps_pagespeed['render_blocking_resources']['critical_css'];
     
     }
     ?> </style>
     
     
     <?php
     
    }
     
}

 

?>