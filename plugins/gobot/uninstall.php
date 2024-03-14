<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
  die;
}


// cleanup API key setting
delete_option('gobot_apikey');