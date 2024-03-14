<?php

/**
 * Class Uninstall_ffwd
 */
class Uninstall_ffwd {

  /**
   *  Display.
   */
  public function display() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    ?>
    <form method="post" action="admin.php?page=uninstall_ffwd" style="width:99%;">
      <?php wp_nonce_field('uninstall_ffwd', 'ffwd_nonce'); ?>
      <h2></h2>
      <div class="wrap">
        <span class="uninstall_icon"></span>
        <h2><?php _e( 'Uninstall Facebook Feed by 10Web', 'ffwd' ); ?></h2>
        <p><?php _e( 'Deactivating Facebook Feed by 10Web plugin does not remove any data that may have been created. To completely remove this plugin, you can uninstall it here.', 'ffwd' ); ?></p>
        <p style="color: red;">
          <strong><?php _e( 'WARNING', 'ffwd' ); ?>:</strong>
          <?php _e( 'Once uninstalled, this can\'t be undone. You should use a Database Backup plugin of WordPress to back up all the data first.', 'ffwd' ); ?>
        </p>
        <p style="color: red">
          <strong><?php _e( 'The following Database Tables will be deleted:', 'ffwd' ); ?></strong>
        </p>
        <table class="widefat">
          <thead>
          <tr>
            <th><?php _e( 'Database Tables', 'ffwd' ); ?></th>
          </tr>
          </thead>
          <tr>
            <td valign="top">
              <ol>
                <li><?php echo $prefix; ?>wd_fb_info</li>
                <li><?php echo $prefix; ?>wd_fb_data</li>
                <li><?php echo $prefix; ?>wd_fb_option</li>
                <li><?php echo $prefix; ?>wd_fb_theme</li>
                <li><?php echo $prefix; ?>wd_fb_shortcode</li>
              </ol>
            </td>
          </tr>
        </table>
        <p style="text-align: center;"><?php _e( 'Do you really want to uninstall Facebook Feed by 10Web?', 'ffwd' ); ?></p>
        <p style="text-align: center;">
          <input type="checkbox" name="Facebook Feed by 10Web" id="check_yes" value="yes" onclick="if (check_yes.checked) { jQuery('#wd_fb_uninstall_submit').prop('disabled', false); } else { jQuery('#wd_fb_uninstall_submit').prop('disabled', true); }" /><label for="check_yes"><?php _e( 'Yes', 'ffwd' ); ?></label>
        </p>
        <p style="text-align: center;">
          <input type="submit" id="wd_fb_uninstall_submit" value="<?php _e( 'UNINSTALL', 'ffwd' ); ?>" class="button-primary" onclick="if (check_yes.checked) {
                                                                                    if (confirm('<?php _e( 'You are About to Uninstall Facebook Feed by 10Web from WordPress.', 'ffwd' ); ?>\n<?php _e( 'This Action Is Not Reversible.', 'ffwd' ); ?>')) {
                                                                                      spider_set_input_value('task', 'uninstall');
                                                                                    } else {
                                                                                      return false;
                                                                                    }
                                                                                  }
                                                                                  else {
                                                                                    return false;
                                                                                  }" disabled/>
        </p>
      </div>
      <input id="task" name="task" type="hidden" value=""/>
    </form>
    <?php
  }
}