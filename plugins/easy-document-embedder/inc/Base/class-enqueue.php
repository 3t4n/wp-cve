<?php

/**
 * @package easy-document-embedder
 */
namespace EDE\Inc\Base;

require_once \dirname(__FILE__) . '/class-basecontroller.php';

class Enqueue extends BaseController
{
    public function ede_register()
     {
        add_action('admin_enqueue_scripts',array($this,'enqueue_style'));
     }

     public function enqueue_style()
     {
         if ( ! did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
         }
         wp_enqueue_style( 'wp-color-picker' ); 
         wp_enqueue_style('pdfdoc_css',$this->plugin_url.'assets/css/main.css');
         wp_enqueue_style( 'thickbox' );

         wp_enqueue_script('media-upload');
         wp_enqueue_script('thickbox');
         
         wp_enqueue_script('pdfdoc_js',$this->plugin_url.'assets/js/main.js');
         
     }
}