<?php
/**
 * @author William Sergio Minozzi
 * @copyright 2021
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly 
?>
<div id="wptools-notifications-page">
    <div class="wptools-block-title">
        <?php esc_attr_e("More Tools","wptools");?>
    </div>
    <div id="notifications-tab">
        <div id="freebies-tab">
        <?php
        // https://boatplugin.com/wp-admin/admin.php?page=wptools_new_more_plugins
        // https://boatplugin.com/wp-admin/admin.php?page=wptools_options39
        if(is_multisite())
           $url = esc_url(WPTOOLSHOMEURL)  . "plugin-install.php?s=sminozzi&tab=search&type=author";
        else
           $url = esc_url(WPTOOLSHOMEURL).'admin.php?page=wptools_options39';
        echo '<script>';
        echo 'window.location.replace("'.esc_url($url).'");';
        echo '</script>';
       ?>
            <br />
        </div>
    </div>
</div>