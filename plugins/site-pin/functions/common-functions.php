<?php

if (!defined('BANG_COMMON_FUNCTIONS')) {
  define('BANG_COMMON_FUNCTIONS', true);
  
  $folder = dirname(__FILE__)."/common/*.php";
  //do_action('log', 'Common functions in %s', $folder);
  foreach (glob($folder) as $filename) {
    $pathinfo = pathinfo($filename);
    //do_action('log', $filename);
    include($filename);
  }
}
