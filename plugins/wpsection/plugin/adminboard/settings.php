<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  if (isset($_POST['wpsection_nonce']) && wp_verify_nonce($_POST['wpsection_nonce'], 'wpsection_nonce_action')) {
    // Nonce is valid, proceed with updating options
    update_option('show_shop', isset($_POST['show_shop']) ? 1 : 0);
    update_option('show_block', isset($_POST['show_block']) ? 1 : 0);
    update_option('show_footer', isset($_POST['show_footer']) ? 1 : 0);
    update_option('show_header', isset($_POST['show_header']) ? 1 : 0);
    update_option('show_banner', isset($_POST['show_banner']) ? 1 : 0);
    add_settings_error('wpsection_success', 'wpsection_success', 'Settings saved successfully', 'updated');
  }
}

// Retrieve the current option values for checkboxes
$show_shop = get_option('show_shop', 0);
$show_block = get_option('show_block', 0);
$show_footer = get_option('show_footer', 0);
$show_header = get_option('show_header', 0);
$show_banner = get_option('show_banner', 0);
?>

<form action="<?php echo menu_page_url('admin.php?page=wpsection-settings'); ?>" class="page-wrapper wp_sectionform" method="post" enctype="multipart/form-data">
  <?php settings_errors('wpsection_success'); ?>
  <div class="wps_level">
    <label for="show_block">Hide Shop:</label>
    <input type="checkbox" id="show_shop" name="show_shop" value="1" <?php checked($show_shop, 1); ?>><br>
  </div>

  <div class="wps_level">
    <label for="show_block">Hide Block:</label>
    <input type="checkbox" id="show_block" name="show_block" value="1" <?php checked($show_block, 1); ?>><br>
  </div>

  <div class="wps_level">
    <label for="show_footer">Hide Footer:</label>
    <input type="checkbox" id="show_footer" name="show_footer" value="1" <?php checked($show_footer, 1); ?>><br>
  </div>

  <div class="wps_level">
    <label for="show_header">Hide Header:</label>
    <input type="checkbox" id="show_header" name="show_header" value="1" <?php checked($show_header, 1); ?>><br>
  </div>

  <div class="wps_level">
    <label for="show_banner">Hide Banner:</label>
    <input type="checkbox" id="show_banner" name="show_banner" value="1" <?php checked($show_banner, 1); ?>><br>
  </div>
  <br>
  <div class="wps_level">
    <input class="wps_input" type="submit" name="submit" value="Save/Update Settings">
  </div>
  <br>
  <p>Update after Save/Update Settings</p>
  <?php wp_nonce_field('wpsection_nonce_action', 'wpsection_nonce'); ?>
</form>
