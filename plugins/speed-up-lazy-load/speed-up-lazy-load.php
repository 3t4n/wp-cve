<?php
/*
 Plugin Name: Speed Up - Lazy Load
 Plugin URI: http://wordpress.org/plugins/speed-up-lazy-load/
 Description: This plugin, implementing "Lazy Load" technique, avoids download of the pictures that are not displayed on the screen (for example: images in the bottom of the page) until the user will display them. This improves load speed of page and save the bandwidth.
 Version: 1.0.25
 Author: Simone Nigro
 Text Domain: speed-up-lazy-load
 Domain Path: /languages
 Author URI: https://profiles.wordpress.org/nigrosimone
 License: GPLv2 or later
 License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( !defined('ABSPATH') ) exit;


class SpeedUp_LazyLoad {

    const VERSION = '1.0.25';
    const DATASRC = 'data-lazy-src';
    const HANDLE  = 'speed-up-lazyload';
    const IMGPLH  = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
    const REGEX   = '#<%s(?<prepend>[^>]+?)%s=([\'"])?(?<src>(?:(?!\2)[^>])+)\2?(?<append>[^>]*)>#';
    
    /**
     * Instance of the object.
     * 
     * @since  1.0.0
     * @static
     * @access public
     * @var null|object
     */
    public static $instance = null;

    /**
     * Access the single instance of this class.
     *
     * @since  1.0.0
     * @return SpeedUp_LazyLoad
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     * 
     * @since  1.0.0
     * @return SpeedUp_LazyLoad
     */
    private function __construct(){
        
        if( !is_admin() ){
            add_filter('the_content',         array($this, 'add_lazy'), PHP_INT_MAX);
            add_filter('get_avatar',          array($this, 'add_lazy'), PHP_INT_MAX);
            add_filter('widget_text',         array($this, 'add_lazy'), PHP_INT_MAX);
            add_filter('post_thumbnail_html', array($this, 'add_lazy'), PHP_INT_MAX);
            add_action('wp_enqueue_scripts',  array($this, 'enqueue_scripts'));
            add_filter('script_loader_tag',   array($this, 'add_async_attribute'), 10, 2);
        }
    }
    
    
    /**
     * Enqueue scripts.
     * 
     * @since 1.0.11
     * @return void
     */
    public function enqueue_scripts(){
        wp_enqueue_script(self::HANDLE, plugins_url('js/lazy-load.min.js', __FILE__), array('jquery'), self::VERSION, true);
    }

    
    /**
     * Replace all image in the $content with lazy placeholder.
     * 
     * @since  1.0.0
     * @param  string $content
     * @return string
     */
    public function add_lazy( $content ) {

        // check if the content is empty
        if( empty($content) ){
            return $content;
        }
        
        // check if the content has already been lazy
        if ( false !== strpos($content, self::DATASRC) ){
            return $content;
        }
        
        // check if $content have "no-lazy-area" string
        if( false !== strpos($content, 'no-lazy-area') ){
            return $content;
        }

        // Don't lazy if is in admin, feed or is a post preview
        if( is_admin() || is_feed() || is_preview() ){
            return $content;
        }
        
        // check if $content have images
        if( false !== strpos($content, '<img ') ){
            $content = preg_replace_callback(
                sprintf(self::REGEX, 'img', 'src'),
                array($this, 'add_lazy_to_image_preg_replace_callback'),
                $content
            );
        }

        // check if $content have iframe
        if( false !== strpos($content, '<iframe ') ){
            $content = preg_replace_callback(
            	sprintf(self::REGEX, 'iframe', 'src'),
                array($this, 'add_lazy_to_iframe_preg_replace_callback'),
                $content
            );
        }
        
        // check if $content have div
        if( false !== strpos($content, '<div ') ){
            $content = preg_replace_callback(
            	sprintf(self::REGEX, 'div', 'style'),
                array($this, 'add_lazy_to_div_preg_replace_callback'),
                $content
            );
        }
        
        return $content;
    }

    /**
     * Repace image with "lazy" placeholder.
     * 
     * @since  1.0.7
     * @param  array $matches
     * @return string
     */
    public function add_lazy_to_image_preg_replace_callback($matches){
    	
    	if( !isset($matches['prepend'], $matches['src'], $matches['append']) ){
    		return $matches[0];
    	}

        $string  = $matches[0];
        $prepend = $matches['prepend'];
        $src     = $matches['src'];
        $append  = $matches['append'];

        // check if tag contains 'no-lazy' string
        if( false !== strpos($string, 'no-lazy') ){
            return $string;
        }
        
        // check if tag have "skip-lazy" string
        if( false !== strpos($string, 'skip-lazy') ){
            return $string;
        }

        // check if $src is already base64 image
        if( 0 === strpos($src, 'data:image') ){
            return $string;
        }

        return '<img '.$prepend.' src="'.self::IMGPLH.'" '.self::DATASRC.'="'.$src.'" '.$append.'><noscript><img '.$prepend.' src="'.$src.'" '.$append.'></noscript>';
    }
    
    /**
     * Repace iframe with "lazy" placeholder.
     *
     * @since  1.0.7
     * @param  array $matches
     * @return string
     */
    public function add_lazy_to_iframe_preg_replace_callback($matches){
    	
    	if( !isset($matches['prepend'], $matches['src'], $matches['append']) ){
    		return $matches[0];
    	}
    
        $string  = $matches[0];
        $prepend = $matches['prepend'];
        $src     = $matches['src'];
        $append  = $matches['append'];
    
        // check if tag contains 'no-lazy' string
        if( false !== strpos($string, 'no-lazy') ){
            return $string;
        }
        
        // check if tag have "skip-lazy" string
        if( false !== strpos($string, 'skip-lazy') ){
            return $string;
        }
    
        // check if $src is already about:blank page
        if( 0 === strpos($src, 'about:blank') ){
            return $string;
        }
    
        return '<iframe '.$prepend.' src="about:blank" '.self::DATASRC.'="'.$src.'" '.$append.'>';
    }
    
    /**
     * Repace div with "lazy" placeholder.
     *
     * @since  1.0.18
     * @param  array $matches
     * @return string
     */
    public function add_lazy_to_div_preg_replace_callback($matches){
        
    	if( !isset($matches['prepend'], $matches['src'], $matches['append']) ){
    		return $matches[0];
    	}
    	
        $string  = $matches[0];
        $prepend = $matches['prepend'];
        $style   = $matches['src'];
        $append  = $matches['append'];
        
        // check if tag contains 'no-lazy' string
        if( false !== strpos($string, 'no-lazy') ){
            return $string;
        }
        
        // check if tag have "skip-lazy" string
        if( false !== strpos($string, 'skip-lazy') ){
            return $string;
        }
        
        // check if $style contains 'background' string
        if( false === strpos($style, 'background') ){
            return $string;
        }
        
        // find the url image
        if( preg_match('/url\(\s*([\'"]?)(?<src>.*?)\1\s*\)/i', $style, $urlMatches) ){
            if( isset($urlMatches['src']) ){
            	$src = $urlMatches['src'];
            	
            	$newStyle = str_replace($src, self::IMGPLH, $style);
            	$newSrc   = $src;
            	
            	// remove html entities
            	$newSrc = str_replace(array('&apos;', '&#39;'), '', $newSrc);
            	$newSrc = str_replace(array('&quot;', '&#34;'), '', $newSrc);
            	
            	return '<div '.$prepend.' style="'.$newStyle.'" '.self::DATASRC.'="'.$newSrc.'" '.$append.'>';
            }
        }
        
        return $string;
    }
    
    /**
     * Add async attribute to script.
     * 
     * @since  1.0.7
     * @param string  $tag
     * @param string  $handle
     * @return string
     */
    public function add_async_attribute($tag, $handle) {
        
        if ( self::HANDLE === $handle ){
            return str_replace(' src', ' async="async" src', $tag);
        }
        
        return $tag;
    }
}

// Init
SpeedUp_LazyLoad::get_instance();