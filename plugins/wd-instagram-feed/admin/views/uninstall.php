<?php

class Uninstall_view_wdi {

  public function display() {
    global $wpdb;
    ?>
    <span class="uninstall-icon"></span>
    <h2 class="wdi_page_title"><?php _e('Uninstalling Instagram Feed', "wd-instagram-feed"); ?></h2>
    <p style="color:red;font-size:15px">
      <?php _e('Deactivating Instagram Feed plugin does not remove any data that may have been created. To completely remove this plugin, you can uninstall it here.', 'wd-instagram-feed') ?>
      <br>
      <?php _e('WARNING: Once uninstalled, this can\'t be undone. You should use a Database Backup plugin of WordPress to back up all the data first.', 'wd-instagram-feed') ?>
    </p>
    <p style="color:red;margin-top:10px;font-size:13px;"><?php _e('The following Database Tables will be deleted:', 'wd-instagram-feed') ?></p>
    <div style="background-color:white;border:1px solid #888888">
      <ul style="background-color:white;margin:0">
        <p style="background-color:#F3EFEF;margin: 0;border-bottom: 1px solid #888888;padding:2px;font-size:20px;"><?php _e('Database Tables', 'wd-instagram-feed') ?></p>
        <li style="padding-bottom:5px;padding-left:5px;font-weight: bold;margin:0;">
          1) <?php echo esc_attr($wpdb->prefix . WDI_FEED_TABLE) ?></li>
        <li style="padding-bottom:5px;padding-left:5px;font-weight: bold;margin:0;">
          2) <?php echo esc_attr($wpdb->prefix . WDI_THEME_TABLE) ?></li>
        <p style="background-color:#F3EFEF;margin: 0;border-top: 1px solid #888888;border-bottom: 1px solid #888888;padding:2px;font-size:20px;">
          <?php _e('Options From', 'wd-instagram-feed') ?> <?php echo esc_html($wpdb->prefix), 'options' ?></p>
        <li style="padding-bottom:5px;padding-left:5px;font-weight: bold;margin:0;">3) wdi_version</li>
        <li style="padding-bottom:5px;padding-left:5px;font-weight: bold;margin:0;">4) wdi_custom_js</li>
        <li style="padding-bottom:5px;padding-left:5px;font-weight: bold;margin:0;">5) wdi_custom_css</li>
        <li style="padding-bottom:5px;padding-left:5px;font-weight: bold;margin:0;">6) wdi_instagram_options</li>
        <li style="padding-bottom:5px;padding-left:5px;font-weight: bold;margin:0;">7) wdi_feeds_min_capability</li>
        <li style="padding-bottom:5px;padding-left:5px;font-weight: bold;margin:0;">8) wdi_sample_feed_id</li>
        <li style="padding-bottom:5px;padding-left:5px;font-weight: bold;margin:0;">9) wdi_sample_feed_post_id</li>
        <li style="padding-bottom:5px;padding-left:5px;font-weight: bold;margin:0;">10) wdi_sample_feed_post_url</li>
        <li style="padding-bottom:5px;padding-left:5px;font-weight: bold;margin:0;">11) wdi_first_user_username</li>
        <li style="padding-bottom:5px;padding-left:5px;font-weight: bold;margin:0;">12) wdi_theme_keys</li>
        <li style="padding-bottom:5px;padding-left:5px;font-weight: bold;margin:0;">13) wdi_admin_notice</li>
        <li style="padding-bottom:5px;padding-left:5px;font-weight: bold;margin:0;">14) wdi_subscribe_done</li>
        <li style="padding-bottom:5px;padding-left:5px;font-weight: bold;margin:0;">15) wdi_token_error_flag</li>
        <li style="padding-bottom:5px;padding-left:5px;font-weight: bold;margin:0;">16) widget_wdi_instagram_widget</li>
      </ul>
    </div>
    <form action="admin.php?page=wdi_uninstall" id="wdi_uninstall_form" method="post">
      <div style="margin-top:10px; text-align:center">
        <p style="font-size:15px;"><?php _e('Are you sure you want to uninstall plugin?', 'wd-instagram-feed') ?></p>
        <input type="checkbox" id="wdi_verify" name="wdi_verify" value="1">
        <label for="wdi_verify" style="vertical-align:top"><?php _e('Yes', 'wd-instagram-feed') ?></label>
        <br>
        <div style="margin-top:10px; text-align:center;">
          <input type="hidden" name="task" value="uninstall">
          <?php wp_nonce_field('wdi_nonce', 'wdi_nonce'); ?>
          <input type="submit" name="wdi_submit" id="wdi_submit" class="button button-primary" disabled="disabled" value="<?php _e('Uninstall', 'wd-instagram-feed') ?>">
        </div>
      </div>
    </form>
    <?php
  }
}