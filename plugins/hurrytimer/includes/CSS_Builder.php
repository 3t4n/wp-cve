<?php
namespace Hurrytimer;

use Hurrytimer\Traits\Singleton;

class CSS_Builder{

    use Singleton;

    const  OPTION_NAME = 'hurrytimer_css_build_hash';

    public function generate_css(){
        ob_start();
        include HURRYT_DIR . '/assets/css/main.css';
        include HURRYT_DIR . '/includes/css_template.php';
        $css = ob_get_clean();
        $css = wp_strip_all_tags($css);

        $uploads = wp_upload_dir();

        $uploads_dir = $uploads['basedir'];
        
        $path = $uploads_dir . '/hurrytimer/css/';

        if( ! file_exists( $path ) ){
            mkdir( $path, 0777, true );
        }
        
        if(!file_exists($uploads_dir . '/hurrytimer/index.html')){
            file_put_contents($uploads_dir . '/hurrytimer/index.html', '');
        }
        if(!file_exists($uploads_dir . '/hurrytimer/css/index.html')){
            file_put_contents($uploads_dir . '/hurrytimer/css/index.html', '');
        }
        array_map('unlink', glob($path . "*.css"));
        $build_version = substr( md5( time() ), 0, 16 );
        $css_path = $uploads_dir . '/hurrytimer/css/' . $build_version . '.css';
        file_put_contents($css_path, $css);

        update_option(self::OPTION_NAME, $build_version);

        return $css_path;
    }

    public function get_css_url(){
        $uploads = wp_upload_dir();
        $uploads_dir = $uploads['basedir'];

        $version = get_option(self::OPTION_NAME);
        if(empty($version)){
           $css_path = $this->generate_css();
        }
        $subpath = '/hurrytimer/css/' . $version . '.css';
        $css_path = $uploads_dir . $subpath;

        // Ensure the file exists, otherwise generate it.
        if( ! file_exists( $css_path ) ){
            $css_path = $this->generate_css();
        }

        return set_url_scheme( $uploads['baseurl'] . $subpath);
    }

}