<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://fresh-d.biz/wocommerce-remove-background.html
 * @since      1.0.0
 *
 * @package    wc-remove-bg
 * @subpackage wc-remove-bg/admin/partials
 */

?>
<div class="wc_remove_bg notice notice-info is-dismissible" id="status_w" style="display: none">
    <p><?php _e('Background removal is in progress.', 'wc-remove-bg') ?></p>
</div>
<div class="wc_remove_bg notice notice-success is-dismissible" id="status_d" style="display: none">
    <p><?php _e('Background removal complete.', 'wc-remove-bg') ?></p>
</div>
<div class="wc_remove_bg notice notice-success is-dismissible" id="status_s" style="display: none">
    <p><?php _e('Settings have been saved.', 'wc-remove-bg') ?></p>
</div>
<div class="wc_remove_bg notice notice-info is-dismissible" id="status_restore_w" style="display: none">
    <p><?php _e('Restore is in progress.', 'wc-remove-bg') ?></p>
</div>
<div class="wc_remove_bg notice notice-success is-dismissible" id="status_restore_d" style="display: none">
    <p><?php _e('Restore complete.', 'wc-remove-bg') ?></p>
</div>
<div class="wc_remove_bg notice notice-error is-dismissible" id="status_restore_e" style="display: none">
    <p></p>
</div>

