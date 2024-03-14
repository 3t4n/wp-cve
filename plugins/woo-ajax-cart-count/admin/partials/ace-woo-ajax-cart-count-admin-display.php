<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://profiles.wordpress.org/acewebx/#content-plugins
 * @since      1.0.0
 *
 * @package    Ace_Woo_Ajax_Cart_Count
 * @subpackage Ace_Woo_Ajax_Cart_Count/admin/partials
 */
?>
<div class="imsSetting">
<div class=".ace-app-container ui-tabs ui-corner-all ui-widget ui-widget-content"id="tab">
    <div class="ace-app-title-bar">
    <div class="ace-app-title-content">
  
    <div class="ace-app-title-menu" id="ace-app-menu1"><span>  Ace Cart Count</span></div>
</div>
</div>
<div class="ace-ims-setting">
    <h3 >Use this shortcode for Ace Ajax Cart Count </h3>
    <mark>[WooAjaxCartCount]</mark>
</div>    
    <form method="post" action="options.php">
        <?php settings_fields('imsAjaxCartCount_optionsGroup'); ?>
        <!-- <table class="cart_count"> -->
        
            <!-- <tr valign="top"> -->
            <div class="ace-ims-setting">
                <!-- <th scope="row"> -->
                <div class="ace-ims-setting-content position-left">
                    <h3>
                    <label for="imsAjaxCartCount_optionIcon">Icon</label>
</h3>
                <!-- </th> -->
                <!-- <td> -->
                </div>
                <div class="ace-ims-setting-content position-right">
                    <input required="required" placeholder="fa-shopping-cart" type="text" id="imsAjaxCartCount_optionIcon" name="imsAjaxCartCount_optionIcon" value="<?php echo get_option('imsAjaxCartCount_optionIcon'); ?>" /><a href="http://fontawesome.io/icons/" target="_blank">Help</a>
<!-- </td> -->
</div>
</div>
            <!-- </tr> -->
        
         <div class="ace-ims-setting">
            <!-- <tr valign="top">
                <th scope="row"> -->
                <div class="ace-ims-setting-content position-left">
                    <h3>
                    <label for="imsAjaxCartCount_optionColor">Color</label>
                    <h3>
                <!-- </th>
                <td> -->
        </div>
        <div class="ace-ims-setting-content position-right">
                    <input required="required" placeholder="#000000" type="text" id="imsAjaxCartCount_optionColor" name="imsAjaxCartCount_optionColor" value="<?php echo get_option('imsAjaxCartCount_optionColor'); ?>" /><a href="http://htmlcolorcodes.com" target="_blank">Help</a>
                <!-- </td>
            </tr> -->
</div>
        </div>
        <div class="ace-ims-setting">
            <!-- <tr valign="top">
                <th scope="row"> -->
                <div class="ace-ims-setting-content  position-left">
                    <h3>
                    <label for="imsAjaxCartCount_optionFontSize">Font Size</label>
                    <h3>
</div>
                <!-- </th>
                <td> -->
                <div class="ace-ims-setting-content  position-right">
                    <select id="imsAjaxCartCount_optionFontSize" name="imsAjaxCartCount_optionFontSize">
                        <?php
                        for ($i = 10; $i <= 18; $i++) {
                            if (get_option('imsAjaxCartCount_optionFontSize') == $i) {
                                $selected = 'selected';
                            } else {
                                $selected = '';
                            }
                        ?>
                            <option <?php echo $selected ?> value="<?php echo $i; ?>"><?php echo $i; ?>px</option>
                        <?php } ?>
                    </select>
                <!-- </td>
            </tr> -->
                        </div>
                        </div>
                       
            <!-- <tr>
                <td></td> -->
                <div class="ace-ims-setting-save">
                <!-- <td> --><?php submit_button(); ?> <!--</td> -->
            <!-- </tr> -->
                        
                        </div>
        </table>
    </form>
                        </div>
</div>