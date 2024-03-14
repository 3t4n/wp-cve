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
    <?php _e('Purchase processing can be used to create WooCommerce orders from purchases made in Zettle or to change the stocklevel in WooCommerce based on purchases made in Zettle.', 'woo-izettle-integration');?>
    <?php _e('When enabled a new menu item "Zettle purchases" where the status of downloaded purchases will be shown to the admin user.', 'woo-izettle-integration');?>
    <?php _e('The difference in changing the stocklevel in this section compared to selecting the stocklevel to be updated in the "Products from Zettle" settings section is that this one will change the stocklevel rather than overwriting it.', 'woo-izettle-integration');?>
    <?php _e('If you are not sure on what method do use it is normally best to use the one in "Products from Zettle".', 'woo-izettle-integration');?>
    </p>
    <p>
    <i>
    <?php _e('Be careful when experimenting with the setup of stocklevels, some of the settings can cause your stocklevel in WooCommerce to be overwritten.', 'woo-izettle-integration');?>
    </i>
    </p>
    <p>
    <i><b>
    <?php _e('You can always contact our <a href="mailto:hello@bjorntech.com">helpdesk</a> if you need help', 'woo-izettle-integration');?>
    </b></i>
    </p>
</div">