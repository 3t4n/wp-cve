<?php

require_once TAYORI_PLUGIN_DIR . '/includes/functions.php';

if ( is_admin() ) {
  require_once TAYORI_PLUGIN_DIR . '/admin/admin.php';
} else {
  wp_enqueue_script( 'button-js', TAYORI_PLUGIN_URL . '/js/tayori_button.js', array(), false, true );
  wp_localize_script('button-js', 'myScript', array(
    'plugins_Url' => plugins_Url()
  ));
  wp_enqueue_script( 'tayori-setting-js', TAYORI_PLUGIN_URL . '/js/tayori-setting.js', array(), false, true );
}
