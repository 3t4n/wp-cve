<?php

if ( !function_exists( 'json_decode' ) ) {
  function json_decode( $json )
  {
    require_once( 'JSON.php' );
    $jsonparser = new Services_JSON();

    return $jsonparser->decode( $json );
  }
}

if ( !function_exists( 'json_encode' ) ) {

  function json_encode( $variable )
  {
    require_once( 'JSON.php' );
    $jsonparser = new Services_JSON();

    return $jsonparser->encode( $variable );
  }
}
