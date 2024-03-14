<?php
/**
* Template for compare popup in shop and product page
*
* @package Wishlist-and-compare
* @link    https://themehigh.com
*/

use \THWWC\base\THWWC_Utils;

$table_options = THWWC_Utils::thwwc_get_compare_table_settings();
$table_title = $table_options && isset($table_options['lightbox_title']) ? $table_options['lightbox_title'] : 'Compare products';
$hide_attribute_btn = $table_options && isset($table_options['hide_show']) ? $table_options['hide_show']: '';

?>
<div class="thwwac-modal thwwac-modal-open" id="comparemodal">
    <div class="thwwac-overlay"></div>
    <div class="thwwac-modal-inner-compare">
        <div class="thwwac-fixed-head">
            <div class="thwwac-compare-title"><h3><?php echo esc_html(stripcslashes($table_title)) ?></h3></div>
            <div class="compare-close"><span onclick="close_comparemodal()"></span></div>
        </div>
        <input type="hidden" id="thwwac_is_page" value="0">
        <input type="hidden" name="check_added_to_cart" value="no" id="check_added_to_cart">
        <div class="thwwc-compare-top-scn">
            <?php if ($hide_attribute_btn == 'true') {?>
            <div class="thwwac_hide_show"><input type="checkbox" onclick="hide_show()" name="differences" value="hide"> <?php esc_html_e(' Show only differences', 'wishlist-and-compare'); ?></div>
            <?php } ?>
        </div>
        <div id="compare-popup">
            <div id="added-msg"></div>
                
            <div class="thwwac-wishlist-clear"></div>
        </div>
    </div>
</div>