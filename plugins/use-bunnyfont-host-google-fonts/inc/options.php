<?php
// Create the plugin options page
function bunnyfonts_create_options_page() {
  add_options_page(
    'Replace or Remove Google Fonts', // Page title
    'Remove google fonts', // Menu title
    'manage_options', // Capability
    'remove-replace-gf', // Menu slug
    'bunnyfonts_render_options_page' // Callback function
  );
}
add_action('admin_menu', 'bunnyfonts_create_options_page');

// Render the plugin options page
function bunnyfonts_render_options_page() {
  // Check user capabilities
  if (!current_user_can('manage_options')) {
    return;
  }
  $easyurl = site_url( '', 'https' );
  $siteurl      = preg_replace( '(^https?://)', '', $easyurl );
  // Display the plugin options form
  ?><div class="bndisp">
	

  <div class="bunwrap"><style>.bunwrap{background:#fff;padding:20px;color:#000;box-shadow:2px 2px 10px #d3d3d3;margin-top:30px;margin-right:30px}</style>
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">
      <?php
      // Output nonce, action, and option_page fields for a settings page
      settings_fields('bunnyfonts_options');

      // Output settings sections and fields
      do_settings_sections('bunnyfonts');

      // Output save settings button
      submit_button('Save Changes');
      ?>
    </form><script>jQuery(document).ready(function(e){var o=e("#replace_google_fonts"),t=e('td label[for="block_google_fonts"]'),n="This option will remove the remaining Google Fonts that are not compatible with the Bunnyfonts replacement.",l="This option will remove all the Google fonts from HTML.";o.prop("checked")?t.text(n):t.text(l),o.change(function(){o.prop("checked")?t.text(n):t.text(l)})});</script>
  </div><div class="easy"><p>
	
	The Plugin can't replace google fonts inside inline style with bunnyfonts.<br> <br>Instead of removing the fonts completely, you can use <a target="_blank" href="https://wordpress.org/plugins/easyfonts/">EasyFonts</a> which allows downloading google fonts and loading it from your domain (<?php echo $siteurl; ?>) for better efficiency, faster loading, and privacy (100% GDPR & DSGVO compliant) with a single click.</p> <a class="downeasy" href="https://<?php echo $siteurl; ?>/wp-admin/plugin-install.php?s=google fonts local by easyfonts&tab=search&type=term">Install and Activate EasyFonts</a></div></div><style>.easy,.easy a{color:#fff}.bndisp{display:inline-flex}.easy{text-align:center;background:#6c2eb9;padding:20px;box-shadow:2px 2px 10px #d3d3d3;margin-top:30px;margin-right:30px;width:40%}.easy p{text-transform:capitalize;text-align:left}a.downeasy{background:#00c3aa;padding:10px 20px;text-transform:uppercase;text-decoration:none;font-weight:600;white-space:nowrap;top:30px;position:relative;border-radius:5px}.easy p{font-size:15px;font-weight:500;text-transform:capitalize}.bunwrap{margin-right:10px!important}@media screen and (max-width:700px){.bndisp{display:block}}</style>
  <?php
}

// Register plugin options
function bunnyfonts_register_options() {
  // Register a new setting
  register_setting(
    'bunnyfonts_options', // Option group
    'bunnyfonts_options', // Option name
    'bunnyfonts_sanitize_options' // Sanitize callback
  );
  
  // Register the bunnyfonts_notice_closed_time option
  register_setting(
    'bunnyfonts_options', // Option group
    'bunnyfonts_notice_closed_time', // Option name
    'intval' // Sanitize callback
  );

  // Add a new section to the options page
  add_settings_section(
    'bunnyfonts_section_general', // ID
    '', // Title
    'bunnyfonts_section_general_cb', // Callback
    'bunnyfonts' // Page
  );

  // Add a new field to the section
  add_settings_field(
    'replace_google_fonts', // ID
    'Replace Google fonts', // Title
    'bunnyfonts_field_replace_google_fonts_cb', // Callback
    'bunnyfonts', // Page
    'bunnyfonts_section_general', // Section
    ['label_for' => 'replace_google_fonts'] // Args
  );

  // Add a new field to the section
  add_settings_field(
    'block_google_fonts', // ID
    'Remove Google fonts', // Title
    'bunnyfonts_field_block_google_fonts_cb', // Callback
    'bunnyfonts', // Page
    'bunnyfonts_section_general', // Section
    ['label_for' => 'block_google_fonts'] // Args
  );
	
	add_settings_field(
    'remove_google_fonts_jquery', // ID
    'Remove Google fonts with Javascript', // Title
    'bunnyfonts_field_remove_google_fonts_cb', // Callback
    'bunnyfonts', // Page
    'bunnyfonts_section_general', // Section
    ['label_for' => 'remove_google_fonts_jquery'] // Args
  );
}
add_action('admin_init','bunnyfonts_register_options');

// Display the section description
function bunnyfonts_section_general_cb() {
  echo '<p>Configure the plugin settings below:</p><p>Enabling the \'Replace Google Fonts\' option will replace the Google fonts with Bunnyfonts, which <span style="color:red">they claim to be GDPR compliant</span>. The \'Remove Google Fonts\' option will remove Google fonts from the HTML. Both options can be used at same time.</p>';
}

// Display the checkbox field
function bunnyfonts_field_replace_google_fonts_cb() {
  $options = get_option('bunnyfonts_options');
  $replace_google_fonts = isset($options['replace_google_fonts']) ? $options['replace_google_fonts'] : 0;
  ?>
  <input type="checkbox" id="replace_google_fonts" name="bunnyfonts_options[replace_google_fonts]" value="1" <?php checked($replace_google_fonts, 1); ?>>
  <label for="replace_google_fonts">Replace Google fonts with BunnyFonts. (<a href="https://bunny.net/gdpr/?ref=hzzl7cco4c">Read More About their GDPR</a>)</label>
  <?php
}

function bunnyfonts_field_block_google_fonts_cb() {
  $options = get_option('bunnyfonts_options');
  $block_google_fonts = isset($options['block_google_fonts']) ? $options['block_google_fonts'] : 0;
  ?>
  <input type="checkbox" id="block_google_fonts" name="bunnyfonts_options[block_google_fonts]" value="1" <?php checked($block_google_fonts, 1); ?>>
  <label for="block_google_fonts">This option will remove all the google fonts from html</label>
  <?php
}

function bunnyfonts_field_remove_google_fonts_cb() {
  $options = get_option('bunnyfonts_options');
  $remove_google_fonts_jquery = isset($options['remove_google_fonts_jquery']) ? $options['remove_google_fonts_jquery'] : 0;
  ?>
  <input type="checkbox" id="remove_google_fonts_jquery" name="bunnyfonts_options[remove_google_fonts_jquery]" value="1" <?php checked($remove_google_fonts_jquery, 1); ?>>
  <label for="remove_google_fonts_jquery">This option will remove the google fonts on page load <span style="color:red">(Use this option only if the above option doesn't work)</span></label>
  <?php
}

// Sanitize plugin options
function bunnyfonts_sanitize_options($input) {
  $output = [];

  // Sanitize the "replace_google_fonts" option
  if (isset($input['replace_google_fonts'])) {
    $output['replace_google_fonts'] = absint($input['replace_google_fonts']);
  }

  // Sanitize the "block_google_fonts" option
  if (isset($input['block_google_fonts'])) {
    $output['block_google_fonts'] = absint($input['block_google_fonts']);
  }
	
  // Sanitize the "remove_google_fonts_jquery" option
  if (isset($input['remove_google_fonts_jquery'])) {
    $output['remove_google_fonts_jquery'] = absint($input['remove_google_fonts_jquery']);
  }

  return $output;
}

