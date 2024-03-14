<?php
/*
   Plugin Name: WHWS Display User Meta Shortcode
   Version: 0.6
   Author: David G
   Author URI: https://skenniweb.com
   Donate link: https://www.buymeacoffee.com/Ll7myjoqA
   Description: Use this plugin to display various user meta information for the currently logged in user with a simple shortcode.
   Text Domain: whws-display-user-meta-shortcode
   License: GPLv3
*/
add_action("admin_menu", "whws_user_meta_add_theme_menu_item");
function whws_user_meta_add_theme_menu_item() {
	add_menu_page("User Meta", "User Meta", "manage_options", "whws-user-meta-panel", "whws_user_meta_settings_page", null, 98);
}

add_shortcode('whwsmeta', 'whws_user_meta_shortcode_handler');
function whws_user_meta_shortcode_handler( $atts, $content = NULL ) {
    $meta = get_user_meta( get_current_user_id(), $atts['meta'], true );
    if(!is_array($meta)) {
        return esc_html($meta);
    }
    else {
        $comma_list = implode(', ', $meta);
        return $comma_list;
    }
}

function whws_user_meta_settings_page() {
  ?>
  <script>
  function copyText(element) {
    var range, selection, worked;

    if (document.body.createTextRange) {
      range = document.body.createTextRange();
      range.moveToElementText(element);
      range.select();
    } else if (window.getSelection) {
      selection = window.getSelection();
      range = document.createRange();
      range.selectNodeContents(element);
      selection.removeAllRanges();
      selection.addRange(range);
    }

    try {
      document.execCommand('copy');
      alert('Shortcode copied to Clipboard.');
    }
    catch (err) {
      alert('Unable to copy shortcode to Clipboard.');
    }
  }
  </script>
    <div class="wrap">
    <h1>WHWS Display User Meta Shortcode</h1>
    <p>Use the following shortcodes to display specific user meta tags (for the currently logged in user) in content pages and posts. Please note, not every user has all type of user meta tags it depends on your specific WordPress environment. Array data in user meta are displayed as comma-separated list.</p>
    <p><i>Click on the selected shortcode to copy to Clipboard.</i></p>
    <table class="wp-list-table widefat fixed striped table-view-list pages">
        <thead>
            <tr valign="top">
                <th><strong>User Meta</strong></th>
                <th><strong>Shortcode to Use</strong></th>
            </tr>
        </thead>
        <tbody>
      <?php
      global $wpdb;
      $whws_meta = $wpdb->get_results("SELECT meta_key FROM $wpdb->usermeta GROUP BY meta_key ORDER BY meta_key ASC");
      foreach ( $whws_meta as $meta ) {
      ?>
      <tr valign="top">
          <td scope="row"><?php echo $meta->meta_key ?></td>
          <td><span onClick="copyText(this)">[whwsmeta meta="<?php echo $meta->meta_key ?>"]</span></td>
      </tr>
      <?php } ?>
        </tbody>
    </table>
    <br /><strong>Do you like this plugin?</strong><br /><br />
    <a href="https://www.buymeacoffee.com/Ll7myjoqA" target="_blank"><img src="https://cdn.buymeacoffee.com/buttons/v2/default-yellow.png" alt="Buy Me A Coffee :)" style="height: 60px !important;width: 217px !important;" ></a>

  </div>
<?php
}

function whws_add_plugin_page_settings_link( $links ) {
	$links[] = '<a target="_self" href="admin.php?page=whws-user-meta-panel">' . __('Settings') . '</a>';
	return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'whws_add_plugin_page_settings_link');

function whws_add_plugin_page_donate_link( $links ) {
	$links[] = '<a target="_blank" href="https://www.buymeacoffee.com/Ll7myjoqA">' . __('Donate to this plugin') . '</a>';
	return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'whws_add_plugin_page_donate_link');
