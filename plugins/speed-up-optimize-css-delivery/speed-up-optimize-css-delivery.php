<?php
/*
 Plugin Name: Speed Up - Optimize CSS Delivery
 Plugin URI: http://wordpress.org/plugins/speed-up-optimize-css-delivery/
 Description: This plugin load the stylesheets asynchronously and improve page load times.
 Version: 1.0.11
 Author: Simone Nigro
 Author URI: https://profiles.wordpress.org/nigrosimone
 License: GPLv2 or later
 License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( !defined('ABSPATH') ) exit;

class SpeedUp_OptimizeCSSDelivery {
    
    const HANDLE  = 'speed-up-optimize-css-delivery';
    
    // @see /wp-content/plugins/speed-up-optimize-css-delivery/js/loadCSS.js for unminified version
    const LOADCSS = '/* loadCSS. [c]2018 Filament Group, Inc. MIT License */ (function(w){"use strict";if(!w.loadCSS){w.loadCSS=function(){}}
var rp=loadCSS.relpreload={};rp.support=(function(){var ret;try{ret=w.document.createElement("link").relList.supports("preload")}catch(e){ret=!1}
return function(){return ret}})();rp.bindMediaToggle=function(link){var finalMedia=link.media||"all";function enableStylesheet(){link.media=finalMedia}
if(link.addEventListener){link.addEventListener("load",enableStylesheet)}else if(link.attachEvent){link.attachEvent("onload",enableStylesheet)}
setTimeout(function(){link.rel="stylesheet";link.media="only x"});setTimeout(enableStylesheet,3000)};rp.poly=function(){if(rp.support()){return}
var links=w.document.getElementsByTagName("link");for(var i=0;i<links.length;i++){var link=links[i];if(link.rel==="preload"&&link.getAttribute("as")==="style"&&!link.getAttribute("data-loadcss")){link.setAttribute("data-loadcss",!0);rp.bindMediaToggle(link)}}};if(!rp.support()){rp.poly();var run=w.setInterval(rp.poly,500);if(w.addEventListener){w.addEventListener("load",function(){rp.poly();w.clearInterval(run)})}else if(w.attachEvent){w.attachEvent("onload",function(){rp.poly();w.clearInterval(run)})}}
if(typeof exports!=="undefined"){exports.loadCSS=loadCSS}
else{w.loadCSS=loadCSS}}(typeof global!=="undefined"?global:this))';
    
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
     * @return SpeedUp_OptimizeCSSDelivery
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
     * @return SpeedUp_OptimizeCSSDelivery
     */
    private function __construct(){

        if( !is_admin() ){

        	add_filter('style_loader_tag', array($this, 'style_loader_tag'), PHP_INT_MAX, 3);
            add_action('wp_head', array($this, 'print_inline_script'));
        }
    }
    
    /**
     * Wordpress style loader tag.
     * 
     * @since 1.0.0
     * @param string $html
     * @param string $handle
     * @param string $href
     * @return string
     */
    public function style_loader_tag($html, $handle, $href){
    	
        // check if current handle is excluded
        if( apply_filters(self::HANDLE, $handle) === true ){
            return $html;
        }
        
        // default media-attribute is "all"
        $media = 'all';
        
        // try to catch media-attribute in the html tag
        if( preg_match('/media=\'(.*)\'/', $html, $match) ){
        
            // extract media-attribute
            if( isset($match[1]) && !empty($match[1]) ){
                $media = $match[1];
            }
        }
        
        return '<link id="'.$handle.'" rel="preload" href="'.$href.'" as="style" media="'.$media.'" onload="this.onload=null;this.rel=\'stylesheet\'" type="text/css"><noscript><link id="'.$handle.'" rel="stylesheet" href="'.$href.'" media="'.$media.'" type="text/css"></noscript>'."\n";
    }
    
    /**
     * Print inline loadCSS script.
     * 
     * @since 1.0.0
     * @return void
     */
    public function print_inline_script(){
        echo '<script id="'.self::HANDLE.'" type="text/javascript">'.self::LOADCSS.'</script>';
    }
}

// Init
SpeedUp_OptimizeCSSDelivery::get_instance();