<?php

function venipak_fetch_pickups($country = null) {
  $upload_dir = wp_upload_dir();
  if (!file_exists($upload_dir['basedir'] . '/venipak')) {
    mkdir($upload_dir['basedir'] . '/venipak', 0777, true);
  }
  $file_path = $upload_dir['basedir'] . '/venipak/pickups.json';
  if (!file_exists($file_path) || (time() - filemtime($file_path) > 86400)) {
    _venipak_fetch_pickups_request($file_path);
  }

  return json_decode(file_get_contents($file_path), true);
}

function _venipak_fetch_pickups_request($file_path) {
  $response = wp_remote_get( "https://go.venipak.lt/ws/get_pickup_points" );

  if (is_wp_error( $response ) ) {
      error_log("VENIPAK https://go.venipak.lt/ws/get_pickup_points SERVICE ERROR");
  }
  $body = wp_remote_retrieve_body( $response );
  $collection = json_decode($body);

  if ($collection && sizeof($collection) > 0) {
    file_put_contents($file_path, $body);
  } else {
    error_log("VENIPAK https://go.venipak.lt/ws/get_pickup_points RESPONSE READ ERROR");
  }
}