<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
  exit();
}

delete_option('hm_tor_options');
