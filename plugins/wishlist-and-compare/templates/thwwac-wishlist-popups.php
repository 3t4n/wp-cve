<?php
/**
* Template for compare popup in shop and product page
*
* @package Wishlist-and-compare
* @link    https://themehigh.com
*/

use \THWWC\base\THWWC_Utils;

$options = THWWC_Utils::thwwc_get_general_settings();
$redirect_wishlist = isset($options['redirect_wishlist']) ? $options['redirect_wishlist'] : 'false';
$page_id = isset($options['wishlist_page']) ? $options['wishlist_page'] : false;
$permalink = $page_id ? get_permalink($page_id) : '#';
?>
<div class="thwwac-modal thwwac-modal-open" id="thwwc_modal">
    <div class="thwwac-overlay"></div>
    <div class="thwwac-table">
        <div class="thwwac-cell">
            <div class="thwwac-modal-inner thwwac-success-popup">
                <div class="thwwc-confirm-close"><span onclick="closepopup()"></span></div>
                <?php $img_url = THWWC_URL.'assets/libs/icons/added-to-wishlist-01.svg'; ?>
                <img src="<?php echo esc_url($img_url) ?>" height="45" width="45">
                <div class="thwwac-txt"><p id="thwwc_product_name"></p></div>
                <div class="thwwacwl-buttons-group thwwac-wishlist-clear">

                    <?php if ($redirect_wishlist == 'true') { ?>
                    <a href="<?php echo esc_url($permalink) ?>" class="button thwwacwl_button_view thwwacwl-btn-onclick"><i class="thwwac-icon thwwac-heart"></i><span> <?php 
                        if (isset($options['view_button_text'])) {
                            $button_text = stripcslashes($options['view_button_text']); 
                            echo esc_html($button_text);
                        } ?></span>
                    </a>
                    <?php } ?>
                </div>
                <div class="thwwac-wishlist-clear"></div>
            </div>
        </div>
    </div>
</div>