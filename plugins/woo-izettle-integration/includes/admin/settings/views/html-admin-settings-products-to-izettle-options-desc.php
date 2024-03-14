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
    <?php _e('The first product export creates a new set of products based on the product selection filters and data selected to be exported. Later exports will update the products already created. A product can be excluded from Zettle by checking the "exclude from Zettle" found in the product metadata.');?>
    <p>
    </p>
    <?php _e('Automatic product updates can be configured to update when the change in WooCommerce is done or later.', 'woo-izettle-integration');?>
    </p>
    <p>
    <i><b>
    <?php _e('You can always contact our <a href="mailto:hello@bjorntech.com">helpdesk</a> if you need help', 'woo-izettle-integration');?>
    </b></i>
    </p>
</div">