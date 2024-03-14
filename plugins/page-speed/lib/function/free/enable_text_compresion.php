<?php

add_filter('mod_rewrite_rules', 'ps_pagespeed_enable_text_compression');
function ps_pagespeed_enable_text_compression( $rules ) {
     
$ps_pagespeed = get_option("ps_pagespeed");

if ($ps_pagespeed['enable_text_compression']=='on') {
    return $rules . "
#StartPagespeedGzip
<ifmodule mod_deflate.c>
  AddOutputFilterByType DEFLATE application/javascript
  AddOutputFilterByType DEFLATE application/rss+xml
  AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
  AddOutputFilterByType DEFLATE application/x-font
  AddOutputFilterByType DEFLATE application/x-font-opentype
  AddOutputFilterByType DEFLATE application/x-font-otf
  AddOutputFilterByType DEFLATE application/x-font-truetype
  AddOutputFilterByType DEFLATE application/x-font-ttf
  AddOutputFilterByType DEFLATE application/x-javascript
  AddOutputFilterByType DEFLATE application/xhtml+xml
  AddOutputFilterByType DEFLATE application/xml
  AddOutputFilterByType DEFLATE font/opentype
  AddOutputFilterByType DEFLATE font/otf
  AddOutputFilterByType DEFLATE font/ttf
  AddOutputFilterByType DEFLATE font/woff
  AddOutputFilterByType DEFLATE font/woff2
  AddOutputFilterByType DEFLATE image/svg+xml
  AddOutputFilterByType DEFLATE image/x-icon
  AddOutputFilterByType DEFLATE text/css
  AddOutputFilterByType DEFLATE text/html
  AddOutputFilterByType DEFLATE text/javascript
  AddOutputFilterByType DEFLATE text/plain
  AddOutputFilterByType DEFLATE text/xml

  # Remove browser bugs (only needed for really old browsers)
  BrowserMatch ^Mozilla/4 gzip-only-text/html
  BrowserMatch ^Mozilla/4\.0[678] no-gzip
  BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
  Header append Vary User-Agent
</ifmodule>
<filesMatch '.(ico|pdf|flv|jpg|jpeg|png|gif|svg|js|css|swf|woff|woff2)$'>
Header set Cache-Control 'max-age=31557600, public'
</filesMatch>
<ifModule mod_headers.c>
Header set Connection keep-alive
Header set no-cache '1'
Header set Set-Cookie 'NO_CACHE=1; path=/;'
Header set Cache-Control 'max-age=31536000, public'
</ifModule>
#ENdPagespeedGzip";
	}
	
	else {
	// do nothing 

	return $rules;
	
	
	}
	

}


?>