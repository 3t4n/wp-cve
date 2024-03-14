<?php

// If this file is called directly, abort.
if (!defined("WPINC")){
	die;
}


function easyfonts_check_incompatible_plugins() {
  // Check if the 'Local Google Fonts' plugin is active
  if(is_plugin_active('local-google-fonts/local-google-fonts.php')) {
    // Display a notice to deactivate the plugin
    ?>
    <div class="notice notice-error is-dismissible">
      <p>The 'Local Google Fonts' plugin is active and may cause conflicts with the Easy Fonts plugin. Please deactivate the 'Local Google Fonts' plugin to avoid any issues.</p>
    </div>
    <?php
  }
  
  // Check if the 'Host Webfonts Local' plugin is active
  if(is_plugin_active('host-webfonts-local/host-webfonts-local.php')) {
    // Display a notice to deactivate the plugin
    ?>
    <div class="notice notice-error is-dismissible">
      <p>The 'OMGF' plugin is active and may cause conflicts with the Easy Fonts plugin. Please deactivate the 'OMGF' plugin to avoid any issues.</p>
    </div>
    <?php
  }
}

// Display the notices on the options page
add_action('admin_notices', 'easyfonts_check_incompatible_plugins');