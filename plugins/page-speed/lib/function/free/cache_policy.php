<?php
add_filter('mod_rewrite_rules', 'ps_pagespeed_enable_cache_policy');
function ps_pagespeed_enable_cache_policy( $rules ) {
     
$ps_pagespeed = get_option("ps_pagespeed");

 if ($ps_pagespeed['cache_policy']=='on') {
    return $rules . "
#StartPagespeedCache#
<IfModule mod_expires.c>
ExpiresActive On
ExpiresByType text/css 'access 1 month'
ExpiresByType text/html 'access 1 month'
ExpiresByType text/xml 'access 1 month'
ExpiresByType text/javascript 'access 1 month'
ExpiresByType text/x-javascript 'access 1 month'
ExpiresByType image/jpg 'access 1 year'
ExpiresByType image/jpeg 'access 1 year'
ExpiresByType image/gif 'access 1 year'
ExpiresByType image/png 'access 1 year'
ExpiresByType image/webp 'access 1 year'
ExpiresByType image/svg+xml 'access plus 1 month'
ExpiresByType image/x-icon 'access 1 year'
ExpiresByType video/ogg 'access plus 1 month'
ExpiresByType audio/ogg 'access plus 1 month'
ExpiresByType video/mp4 'access plus 1 month'
ExpiresByType video/webm 'access plus 1 month'
ExpiresByType application/pdf 'access 1 month'
ExpiresByType application/x-font-woff 'access 1 month'
ExpiresByType application/x-font-woff2 'access 1 month'
ExpiresByType application/vnd.ms-fontobject 'access 1 month'
ExpiresByType application/x-shockwave-flash 'access 1 month'
ExpiresByType application/vnd.ms-fontobject 'access 1 month'
ExpiresByType application/xml 'access 1 month'
ExpiresByType application/json 'access 1 month'
ExpiresByType application/rss+xml 'access 1 month'
ExpiresByType application/rss+xml 'access 1 month'
ExpiresDefault 'access 2 month'
</IfModule>
# EndPagespeedCache#";
	}
	
	else {
	// do nothing 

	return $rules;
	
	
	}
	

}

?>