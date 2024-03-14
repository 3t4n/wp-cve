<?php
namespace LevelTen\Intel\Realtime;
/**
 * @file
 * @author  Tom McCracken <tomm@getlevelten.com>
 * @version 1.0
 * @copyright 2013 LevelTen Ventures
 * 
 * @section LICENSE
 * All rights reserved. Do not use without permission.
 * 
 */
 
 function cron_run() {
  file_include('functions/track.php');
  
  track_log_prune();
  
  $response = array(
    'message' => "OK",
  );
  return $response;
}