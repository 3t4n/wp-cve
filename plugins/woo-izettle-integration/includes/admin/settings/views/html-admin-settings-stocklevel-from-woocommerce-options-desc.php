<?php
/**
 * Admin view: Help text for purchase sync description
 *
 * @package IZI/Admin/Settings
 */

defined('ABSPATH') || exit;

?>

<div id="izettle-help-text-purchase-sync" class="izettle-help-text">
    <p>
    <?php _e('In order to configure how stocklevel should be synced you need to decide if WooCommerce or Zettle should be the master of holding stocklevels.', 'woo-izettle-integration');?>
    <?php _e('The safest and recommended setup is to use WooCommerce as the master and let Zettle change stocklevel in WooCommerce via purchases. Follow these instructions to do this:', 'woo-izettle-integration');?>
    </p>
    <ol class="izettle-help-text-list">
    <li>
    <?php _e('Select <b>Change from WooCommerce</b> as <b>Change method.</b>', 'woo-izettle-integration');?>
    </li>
    <li>
    <?php _e('Save the changes.', 'woo-izettle-integration');?>
    </li>
    </ol>
    <p>
    <i>
    <?php _e('There are a number of other configurations that can be used depending on your needs. Be careful when experimenting with the setup of stocklevels, some of the settings can cause your stocklevel in Zettle to be overwritten.', 'woo-izettle-integration');?>
    </i>
    </p>
    <p>
    <i><b>
    <?php _e('You can always contact our <a href="mailto:hello@bjorntech.com">helpdesk</a> if you need help', 'woo-izettle-integration');?>
    </b></i>
    </p>
</div">