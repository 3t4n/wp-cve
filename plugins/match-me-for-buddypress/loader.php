<?php
/**
 * Plugin Name: BuddyPress Matchmaking
 * Plugin URI: https://meshpros.com/
 * Description: Custom Plugin for BuddyPress profile matching
 * Author: Muhammad Kashif
 * Author URI: https://kashif.io/
 * Version: 1.3
 */

  require_once('class-mp-bp-match.php');

  if(class_exists('Mp_BP_Match')){
       new Mp_BP_Match();
  }
