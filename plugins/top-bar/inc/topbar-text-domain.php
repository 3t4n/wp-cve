<?php

/* Loads plugin's text domain. */
add_action('plugins_loaded', 'tpbr_load_plugin_textdomain');
function tpbr_load_plugin_textdomain() {
  load_plugin_textdomain(TPBR_TXTDM, false, basename(dirname(__FILE__)).'/lang/');
}
