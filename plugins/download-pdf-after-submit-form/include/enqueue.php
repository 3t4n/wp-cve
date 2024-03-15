<?php
//--------------------------- If Add Shortcode use any single page with design ------------- //
function add_css_js_DPBSF(){        
    wp_enqueue_style( 'bootstrap.cs', plugin_dir_url(__FILE__) . '../css/formstyle.css', array(), '1.0.0', 'all' );
    wp_enqueue_script('axiosjs', plugin_dir_url(__FILE__) . '../js/axios.min.js' , array('jquery'),'1.0.0',true);   
}add_action('wp_enqueue_scripts','add_css_js_DPBSF');
//---------------------------only admin side css and js-----------------------------------//
function admin_enqueue_DPBSF($hook) {
   // if ( 'edit.php' == $hook ) {
      //wp_enqueue_script('mbgm-admin-js', plugin_dir_url( __DIR__ ). 'js/gallery-metabox.js', array('jquery'),'1.0.0',true);
      wp_enqueue_style('dpbsf-admin-css', plugin_dir_url( __FILE__ ). '../css/admin.css', array(), '1.0.0', 'all');
  //  }
  }
  add_action('admin_enqueue_scripts', 'admin_enqueue_DPBSF');
?>
