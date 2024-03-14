<?php
class WGZ_Toolbox
{
    protected $file_path;
    protected $file_content;
    protected $active;

    function __construct()
    {
        $this->file_path        = ABSPATH . '.htaccess';
        $this->file_content     = file_get_contents( $this->file_path );
        $this->active           = get_option( 'wgz_active' );
    }

    public function is_apache()
    {
        return ( strpos( strtolower( $_SERVER[ 'SERVER_SOFTWARE' ] ), 'apache' ) !== false ) ? true : false;
    }

    public function install_gzip()
    {
        $status     = true;
        if ( !$this->is_gzip() || $this->active == 'yes' ) {
            $status = $this->edit_file( $this->gzip_settings() . $this->file_content );
        } else {
            $status = $this->uninstall_gzip();
        }

		return $status;
    }

    public function is_gzip()
    {
        if ( strpos( $this->file_content, '# WORDPRESS GZIP START #' ) !== false ) {
            return ( strpos( $this->file_content, '# VERSION ' . WGZ_VERSION . ' #' ) !== false ) ? true : 'update';
        } else {
            return false;
        }
    }

    public function edit_file( $content )
    {
        $status     = true;
        $file_del   = @unlink( $this->file_path );

        $fp = fopen( $this->file_path, 'a' );
        if ( flock( $fp, LOCK_EX ) ) {
            fwrite( $fp, $content );
            fflush( $fp );
            flock( $fp, LOCK_UN );
        } else {
            $status = false;
        }
        fclose( $fp );

        return $status;
    }

    protected function uninstall_gzip()
    {
        return $this->edit_file( str_replace( $this->gzip_settings(), '', $this->file_content ) );
    }

    protected function gzip_settings()
    {
        $gzip .= '
            # WORDPRESS GZIP START #
            # VERSION ' . WGZ_VERSION . ' #
            ## EXPIRES CACHING ##
            <IfModule mod_expires.c>
            ExpiresActive On
            ExpiresByType image/jpg "access plus 1 week"
            ExpiresByType image/jpeg "access plus 1 week"
            ExpiresByType image/gif "access plus 1 week"
            ExpiresByType image/png "access plus 1 week"
            ExpiresByType text/css "access plus 1 week"
            ExpiresByType application/pdf "access plus 1 month"
            ExpiresByType text/x-javascript "access plus 1 week"
            ExpiresByType application/x-shockwave-flash "access plus 1 month"
            ExpiresByType image/x-icon "access plus 1 year"
            ExpiresByType application/vnd.ms-fontobject "access plus 1 year"
            ExpiresByType font/ttf "access plus 1 year"
            ExpiresByType font/otf "access plus 1 year"
            ExpiresByType font/x-woff "access plus 1 year"
            ExpiresByType image/svg+xml "access plus 1 year"
            ExpiresDefault "access plus 1 week"
            </IfModule>
            ## EXPIRES CACHING ##

            <IfModule mod_deflate.c>
              # Compress HTML, CSS, JavaScript, Text, XML and fonts
              AddType application/vnd.ms-fontobject .eot
              AddType font/ttf .ttf
              AddType font/otf .otf
              AddType font/x-woff .woff
              AddType image/svg+xml .svg

              AddOutputFilterByType DEFLATE application/javascript
              AddOutputFilterByType DEFLATE application/rss+xml
              AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
              AddOutputFilterByType DEFLATE application/x-font
              AddOutputFilterByType DEFLATE application/x-font-opentype
              AddOutputFilterByType DEFLATE application/x-font-otf
              AddOutputFilterByType DEFLATE application/x-font-truetype
              AddOutputFilterByType DEFLATE application/x-font-ttf
              AddOutputFilterByType DEFLATE application/x-font-woff
              AddOutputFilterByType DEFLATE application/x-javascript
              AddOutputFilterByType DEFLATE application/xhtml+xml
              AddOutputFilterByType DEFLATE application/xml
              AddOutputFilterByType DEFLATE font/opentype
              AddOutputFilterByType DEFLATE font/otf
              AddOutputFilterByType DEFLATE font/ttf
              AddOutputFilterByType DEFLATE font/woff
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
            </IfModule>
            # WORDPRESS GZIP START END #
        ';

        return $gzip;
    }
}
