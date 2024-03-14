<?php
/**
 * Admin view: Help text for purchase sync description
 *
 * @package IZI/Admin/Settings
 */

defined('ABSPATH') || exit;

?>

<div id="izettle-help-text-purchase-sync" class="izettle-help-text">
    <i><b><?php _e('New to the plugin? Check out our <a href="https://bjorntech.com/kb/getting-started-with-the-woo-zettle-integration/">guide</a> on how to get started!','woo-izettle-integration')?></b></i>
    <p>
    <?php _e('<b>Automatic import</b> can be configured in a number of different ways:', 'woo-izettle-integration');?>
    </p>
    <ul class="izettle-help-text-list">
    <li>
    <?php _e('<b>Create products in WooCommerce</b> - A new product is created in WooCommerce when a new Zettle product is created. ', 'woo-izettle-integration');?>
    </li>
    <li>
    <?php _e('<b>Update products in WooCommerce</b> - If a matching product to the updated Zettle product is found in WooCommerce it will be updated.', 'woo-izettle-integration');?>
    </li>
    <li>
    <?php _e('<b>Create and update products in WooCommerce</b> - Both the Create and Update above will happen.', 'woo-izettle-integration');?>
    </li>
    <li>
    <?php _e('<b>Create, update and delete products in WooCommerce</b> - Both the Create and Update above will happen. Furthermore, products deleted in Zettle will also be deleted in WooCommerce.', 'woo-izettle-integration');?>
    </li>
    </ul>
    <p>
    <i><b>
    <?php _e('You can always contact our <a href="mailto:hello@bjorntech.com">helpdesk</a> if you need help', 'woo-izettle-integration');?>
    </b></i>
    </p>
</div">