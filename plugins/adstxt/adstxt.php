<?php
/*
 * Plugin Name: adstxt
 * Description: Manage your ads.txt from within Wordpress and save as file to uploads dir.
 * Version: 1.0.0
 * Author: Vladyslav Bondarenko
 * Author URI: https://github.com/vladbondarenko/adstxt
 * License: GPLv3
 */

$request = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : false;
if ( '/ads.txt' === $request ) {
  header( 'Content-Type: text/plain' );
  $dir = wp_get_upload_dir();
  $ads = file_get_contents($dir['basedir'] . "/ads.txt");
  echo esc_textarea($ads);
  die();
}

include_once __DIR__ . '/class-adstxt.php';

global $adstxt;
$adstxt = new AdsTxt();
