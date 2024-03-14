<?php
namespace LevelTen\Intel\Realtime;
/**
 * @file
 * @author  Tom McCracken <tomm@levelten.net>
 * @version 1.0
 * @copyright 2013 LevelTen Ventures
 * 
 * @section LICENSE
 * All rights reserved. Do not use without permission.
 * 
 */

function render_response($data, $vars = array()) {
  global $GET, $REQUEST;
  if (!empty($REQUEST['return'])) {
    $data['return'] = $REQUEST['return'];
  }
  render_response_json($data, $vars);
}

function render_response_json($data, $vars = array()) {
  global $GET;
  // Prevent caching.
  if (empty($GET['noheaders'])) {
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 01 Jan 1996 00:00:00 GMT');
    // tell robots to ignore this url
    header('X-Robots-Tag: noindex');
  }
  $post_replace = array();
  if (!empty($data['_post_replace']) && is_array($data['_post_replace'])) {
    $post_replace = $data['_post_replace'];
    unset($data['_post_replace']);
  }
  $output = json_encode($data); 
  if (count($post_replace)) {
    foreach ($post_replace AS $needle => $haystack) {
      $output = str_replace($needle, $haystack, $output);
    }
  }
  // Send the data.
  if (!empty($GET['callback'])) {
    if (empty($GET['noheaders'])) {
      header('Content-type: application/javascript; charset=utf-8');
    }
    print sprintf('%s(%s);', $GET['callback'], $output);
  }
  else {
    if (empty($GET['noheaders'])) {
      header('Content-type: application/json');
    }
    print $output; 
  }
}