<?php

if (!defined('WPINC')) die('No access outside of wordpress.');

function pzat_activate() { pzat_notify('activate'); }

function pzat_deactivate() { pzat_notify('deactivate'); }

function pzat_uninstall() {
  pzat_notify('uninstall');

  $option_name = 'zoom_options';

  delete_option($option_name);
  delete_site_option($option_name);
}

?>
