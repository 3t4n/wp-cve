<?php

/*
  Plugin Name: Simple Code Highlighter
  Plugin URI: http://www.comoprogramar.org
  Description: Highlight your code on Wordpress with simple code syntax button !
  Version: 2.0
  Author: Kedinn Turpo
  Author URI: http://www.comoprogramar.org
*/

add_action( 'wp_enqueue_scripts', 'wpsites_add_js' );

function wpsites_add_js() {
  if ( ! is_admin() ) {
  
      wp_enqueue_script( 'pretty', plugins_url( 'js/pretty.js', __FILE__ ), '', '', true );
      wp_enqueue_style( 'estilo', plugins_url( 'estilo.css', __FILE__ ) );
  }
}

add_action( 'after_setup_theme', 'deel_setup' );
function deel_setup(){
  add_editor_style(plugins_url( 'icono.css', __FILE__ ));
}

add_action( 'admin_enqueue_scripts', 'icono_add_js' );

function icono_add_js() {
      wp_enqueue_style( 'estilo', plugins_url( 'icono.css', __FILE__ ) );
}


  
add_action( 'admin_head', 'kedinn_add_mce_button' );

function kedinn_add_mce_button() {
    
     global $typenow;
  
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
    return;
    }
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
    return;
    
  if ( get_user_option('rich_editing') == 'true') {
    add_filter('mce_external_plugins', 'kedinn_add_tinymce_plugin');
    add_filter('mce_buttons', 'kedinn_register_mce_button');
  }
}
    
    
  
function kedinn_register_mce_button($buttons) {
    
    array_push($buttons, 'kedinn');
    return $buttons;
}
  
function kedinn_add_tinymce_plugin($plugin_array) {
    
    $plugin_array['kedinn'] = plugins_url( '/simple-syntax-highlighter-plugin.js', __FILE__ ); 
    
  return $plugin_array;

}