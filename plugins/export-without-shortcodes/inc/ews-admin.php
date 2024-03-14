<?php
defined( 'EOS_EWS_PLUGIN_DIR' ) || exit; // Exit not accessed by this plugin

add_filter( 'the_content_export','eos_ews_do_shortcode_during_export' );
//It filters the content during the exporting process to run the shortcodes
function eos_ews_do_shortcode_during_export( $content ){
  $opts = eos_ews_get_option( 'eos_ews_opts' );
  if( isset( $opts['global'] ) && 'convert' !== $opts['global'] ) return $content;
  $shortcodesA = isset( $opts['keep'] ) ? $opts['keep'] : false;
  if( class_exists( 'WPBMap' ) ) WPBMap::addAllMappedShortcodes();
  if( class_exists( 'EosbMap' ) ) EosbMap::addAllMappedShortcodes();
  $start = uniqid();
  $start_slash = uniqid();
  $end = uniqid();
  $space = uniqid();
  if( $shortcodesA && is_array( $shortcodesA ) ){
    if( $shortcodesA && !empty( $shortcodesA ) ){
      $shortcodesA = array_filter( $shortcodesA );
      foreach( $shortcodesA as $shortcode_name ){
        if( '' !== $shortcode_name ){
          $content = str_replace( '['.$shortcode_name.']',$start.$shortcode_name.$end,$content );
          $content = str_replace( '['.$shortcode_name.' ',$start.$shortcode_name.$space,$content );
          $content = str_replace( '[/'.$shortcode_name.']',$start_slash.$shortcode_name.$end,$content );
        }
      }
    }
  }
  $content = do_shortcode( $content );
  if( $shortcodesA && is_array( $shortcodesA ) ){
    foreach( $shortcodesA as $shortcode_name ){
      if( '' !== $shortcode_name ){
        $content = str_replace( $start.$shortcode_name.$end,'['.$shortcode_name.']',$content );
        $content = str_replace( $start.$shortcode_name.$space,'['.$shortcode_name.' ',$content );
        $content = str_replace( $start_slash.$shortcode_name.$end,'[/'.$shortcode_name.']',$content );
      }
    }
  }
  return $content;
}

add_action( 'export_filters','eos_ews_plugin_settings' );
//Output plugin settings in the exporting page_end_date
function eos_ews_plugin_settings(){
  require_once EOS_EWS_PLUGIN_DIR.'/inc/ews-export.php';
}

//Get options in case of single or multisite installation.
function eos_ews_get_option( $option ){
  if( !is_multisite() ){
    return get_option( $option );
  }
  else{
    return get_blog_option( get_current_blog_id(),$option );
  }
}

//Update options in case of single or multisite installation.
function eos_ews_update_option( $option,$newvalue,$autoload = false ){
	if( !is_multisite() ){
		return update_option( $option,$newvalue,$autoload );
	}
	else{
		return update_blog_option( get_current_blog_id(),$option,$newvalue );
	}
}

if( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'],array( 'eos_ews_save_options' ) ) ){
  require_once EOS_EWS_PLUGIN_DIR.'/inc/ews-ajax.php';
}
