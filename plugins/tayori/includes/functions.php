<?php

function tayori_plugin_path( $path = '' ) {
  return path_join( TAYORI_PLUGIN_DIR, trim( $path, '/' ) );
}

function tayori_plugin_url( $path = '' ) {
  $url = plugins_url( $path, TAYORI_PLUGIN );
  
  if ( is_ssl() && 'http:' == substr( $url, 0, 5 ) ) {
  	$url = 'https:' . substr( $url, 5 );
  }
  
  return $url;
}
