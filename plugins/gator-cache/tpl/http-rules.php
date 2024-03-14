<?php if(!defined('ABSPATH')){exit;}?>
# BEGIN Gator Cache
<IfModule mod_mime.c>
  <FilesMatch "\.gz$">
    ForceType text/html
  </FilesMatch>
  FileETag None
  AddEncoding gzip .gz
  AddType text/html .gz
  <filesMatch "\.(html|gz)$">
    Header set Vary "Accept-Encoding, Cookie"
    Header set Cache-Control "max-age=5, must-revalidate"
  </filesMatch>
</IfModule>
Header unset Last-Modified
# These browsers may be extinct
BrowserMatch ^Mozilla/4 gzip-only-text/html
BrowserMatch ^Mozilla/4\.0[678] no-gzip
BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
# Assume mod_rewrite
RewriteEngine On
#Note: If behind a reverse proxy like nginx change %{HTTPS} to %{HTTP:X-Forwarded-Proto}
#Clients that support gzip
RewriteCond %{REQUEST_METHOD} !POST
RewriteCond %{QUERY_STRING} ^$
RewriteCond %{HTTPS} !=on
RewriteCond %{HTTP_HOST} ^<?php echo($host = self::getHostString($config));?>$ 
RewriteCond %{HTTP:Cookie} !^.*(wordpress_logged_in|comment_author).*$
RewriteCond %{HTTP:Accept-Encoding} gzip
RewriteCond %{ENV:no-gzip} !1
RewriteCond <?php echo $cacheDir;?>/%{HTTP_HOST}/%{REQUEST_URI} -d
RewriteCond <?php echo $cacheDir;?>/%{HTTP_HOST}/%{REQUEST_URI}index.gz -f
RewriteRule ^/?(.*)$ /gator_cache/%{HTTP_HOST}/$1index.gz [L,E=no-gzip:1,E=gc_green:1]
#Clients without gzip
RewriteCond %{REQUEST_METHOD} !POST
RewriteCond %{QUERY_STRING} ^$
RewriteCond %{HTTPS} !=on
RewriteCond %{HTTP_HOST} ^<?php echo($host);?>$ 
RewriteCond %{HTTP:Cookie} !^.*(wordpress_logged_in|comment_author).*$
RewriteCond %{HTTP:Accept-Encoding} !gzip [OR]
RewriteCond %{ENV:no-gzip} 1
RewriteCond <?php echo $cacheDir;?>/%{HTTP_HOST}/%{REQUEST_URI}index.html -f
RewriteRule ^/?(.*)$ /gator_cache/%{HTTP_HOST}/$1index.html [L,E=gc_green:1]
<?php if(!$options['skip_ssl']){?>
#Ibid for SSL
#Clients that support gzip
RewriteCond %{REQUEST_METHOD} !POST
RewriteCond %{QUERY_STRING} ^$
RewriteCond %{HTTPS} =on
RewriteCond %{HTTP_HOST} ^<?php echo($host = str_replace('.', '\.', $config->has('secure_host') ? $config->get('secure_host') : $config->get('host')));?>$ 
RewriteCond %{HTTP:Cookie} !^.*(wordpress_logged_in|comment_author).*$
RewriteCond %{HTTP:Accept-Encoding} gzip
RewriteCond %{ENV:no-gzip} !1
RewriteCond <?php echo($cacheDir = ($cacheDir . '/ssl@'));?>%{HTTP_HOST}/%{REQUEST_URI} -d
RewriteCond <?php echo $cacheDir;?>%{HTTP_HOST}/%{REQUEST_URI}index.gz -f
RewriteRule ^/?(.*)$ /gator_cache/ssl@%{HTTP_HOST}/$1index.gz [L,E=no-gzip:1,E=gc_green:1]
#Clients without gzip
RewriteCond %{REQUEST_METHOD} !POST
RewriteCond %{QUERY_STRING} ^$
RewriteCond %{HTTPS} =on
RewriteCond %{HTTP_HOST} ^<?php echo($host);?>$ 
RewriteCond %{HTTP:Cookie} !^.*(wordpress_logged_in|comment_author).*$
RewriteCond %{HTTP:Accept-Encoding} !gzip [OR]
RewriteCond %{ENV:no-gzip} 1
RewriteCond <?php echo $cacheDir;?>%{HTTP_HOST}/%{REQUEST_URI}index.html -f
RewriteRule ^/?(.*)$ /gator_cache/ssl@%{HTTP_HOST}/$1index.html [L,E=gc_green:1]
<?php } echo '# END Gator Cache';
