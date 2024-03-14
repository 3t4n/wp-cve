<?php
/*
Plugin Name: WP-Widget Gallery
Plugin URI: http://scoopdesign.com.au
Description: This WordPress plugin allows user to create a gallery for widgets. This plugin also has the ability to display it on page of your choice. 
Version: 1.5.3
Author: eyouth { rob.panes } | scoopdesign.com.au
Author URI: http://scoodpesign.com.au

Copyright 2013  Rob Panes | scoopdesign.com.au  (email : robpane126@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit; 

require_once('wpwidgetgallery_class.php');
   
// Add Widget
add_action('widgets_init', 'wpwidget_media_gallery_init');

function wpwidget_media_gallery_init() {
    
    register_widget('wpwidget_media_gallery');	

    if( is_active_widget(  false, false, 'wpwidget_media_gallery', true )) {
        
        //Media upload
        if ( is_admin() )
            add_action('admin_enqueue_scripts', 'widget_media_gallery_upload');
        
        function widget_media_gallery_upload(){
            wp_register_script( 'wpwidget-mediaupload', plugins_url('js/mediaupload.js', __FILE__ ), array('jquery') );
            wp_enqueue_script ( 'wpwidget-mediaupload' );
            wp_register_script( 'wpwidget-masonry', plugins_url('js/jquery.masonry.min.js', __FILE__ ), array('jquery') );
            wp_enqueue_script ( 'wpwidget-masonry' );            
            wp_register_script( 'wpwidget-modernizer', plugins_url('js/modernizr-2.5.3.min.js', __FILE__ ), array('jquery') );
            wp_enqueue_script ( 'wpwidget-modernizer' );               
            
            wp_enqueue_style  ( 'wpwidget-style', plugins_url('css/admin.css', __FILE__ ));           
            
            if(function_exists( 'wp_enqueue_media' )){ 	
                    wp_enqueue_media();
            }else{
                    wp_enqueue_style('thickbox');
                    wp_enqueue_script('media-upload');
                    wp_enqueue_script('thickbox');    
            }	          
        }
                
        function wp_media_gallery_script(){
            wp_register_script( 'wpwidget-lightbox', plugins_url('js/jquery.prettyPhoto.js', __FILE__ ), array('jquery'),'',true );
            wp_enqueue_script ( 'wpwidget-lightbox' );
            wp_register_script( 'wpwidget-cycle', plugins_url('js/jquery.cycle.js', __FILE__ ), array('jquery'),'',true );
            wp_enqueue_script ( 'wpwidget-cycle' );
            wp_register_script( 'wpwidget-carousel', plugins_url('js/jquery.carousel.js', __FILE__ ), array('jquery'),'',true );
            wp_enqueue_script ( 'wpwidget-carousel' );  
        }
    
        function wpwidget_lightbox(){
            wp_register_style('wpwidget-lightbox', plugins_url('css/lightbox.css', __FILE__ ));
            wp_register_style('wpwidget-prettyPhoto', plugins_url('css/prettyPhoto.css', __FILE__ ));
            
            wp_enqueue_style ('wpwidget-lightbox');
            wp_enqueue_style ('wpwidget-prettyPhoto');
        }
        
        function wpwidget_script(){
        ?>
            <script type="text/javascript" charset="utf-8">
              jQuery(document).ready(function($){
                $("a[rel^='prettyPhoto']").prettyPhoto();
              });
            </script>
        <?php    
        }
        
        if ( !is_admin() ){
            add_action ('wp_enqueue_scripts', 'wp_media_gallery_script');
            add_action ('wp_enqueue_scripts','wpwidget_lightbox');
            add_action ('wp_footer','wpwidget_script');
        }
    }
}//end of widgets init        