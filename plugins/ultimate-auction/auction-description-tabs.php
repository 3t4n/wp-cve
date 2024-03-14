<div id="wdm-tab-anchor-id"></div>
<div id="auction-desc-tabs">
    <ul id="auction-tab-titles">
        <li id="wdm-desc-aucdesc-link"><?php _e("Description", "wdm-ultimate-auction"); ?></li>
        <?php do_action('wdm_ua_add_ship_tab', $wdm_auction->ID); ?>
            <!-- <li id="wdm-desc-cmt-link"><?php // _e('Comments', 'wdm-ultimate-auction'); ?></li> -->
        <?php if (get_option('wdm_show_prvt_msg') == "Yes") { ?>
            <li id="wdm-desc-msg-link"><?php _e("Send Private Message", "wdm-ultimate-auction"); ?></li>
        <?php } ?>
        <li id="wdm-desc-bids-link"><?php _e("Total bids placed", "wdm-ultimate-auction"); ?></li>
    </ul>

    <div id="wdm-desc-aucdesc-tab" class="auction-tab-container">
        <div class="wdm-single-auction-description">
            <?php
            $ext_desc = "";
            $ext_desc = apply_filters('wdm_single_auction_extra_desc', $ext_desc);
            echo $ext_desc;
            echo apply_filters('the_content', $wdm_auction->post_content);
            ?>
        </div>
    </div>
    <?php if (get_post_meta($wdm_auction->ID, 'wdm_enable_shipping', true) == "1") { ?>
        <div id="wdm-desc-ship-tab" class="auction-tab-container" style="display: none;overflow: hidden;">
            <div class="wdm-ship-info clearfix">
                <?php do_action('ua_add_shipping_cost_view_field', $wdm_auction->ID); //SHP-ADD hook to add new product data ?>
            </div>  
        </div>
    <?php } ?>

    <div id="wdm-desc-msg-tab" class="auction-tab-container" style="display: none;">
        <form id="wdm-auction-private-form" action="">
            <div class="clearfix wdmua-shipping-field-wrap">
                <label for="wdm-prv-bidder-name" class="wdmua-shipping-label wdm-align-left"> <?php _e("Name",
                 "wdm-ultimate-auction"); ?>: </label>
                <input type="text" id="wdm-prv-bidder-name" class="wdmua-shipping-input wdm-align-left"/>
            </div>
            <div class="clearfix wdmua-shipping-field-wrap">
                <label for="wdm-prv-bidder-email" class="wdmua-shipping-label wdm-align-left"> <?php _e("Email", "wdm-ultimate-auction"); ?>: </label>
                <input type="text" id="wdm-prv-bidder-email" class="wdmua-shipping-input wdm-align-left" />
            </div>
            <div class="clearfix wdmua-shipping-field-wrap">
                <label for="wdm-prv-bidder-msg" class="wdmua-shipping-label wdm-align-left"> <?php _e("Message", "wdm-ultimate-auction"); ?>: </label>
                <textarea id="wdm-prv-bidder-msg" class="wdmua-shipping-input wdm-align-left"></textarea> 
            </div>
            <div class="clearfix wdmua-shipping-field-wrap">
                <input class="wdm-align-right" id="ult-auc-prv-msg" name="ult-auc-prv-msg" type="submit" value="<?php _e("Send", "wdm-ultimate-auction"); ?>" />
            </div>
        </form>
    </div>
    <?php require_once('ajax-actions/send-private-msg.php'); ?>

    <div id="wdm-desc-bids-tab" class="auction-tab-container" style="display: none;">

        <?php

        /*$query = "SELECT * FROM " . $wpdb->prefix . "wdm_bidders WHERE auction_id =" . $wdm_auction->ID . " ORDER BY id DESC";*/

        $table = $wpdb->prefix . "wdm_bidders";
        $auctionid = $wdm_auction->ID;

        $query = $wpdb->prepare("SELECT * FROM {$table} WHERE auction_id = %d ORDER BY id DESC", $auctionid);

        $results = $wpdb->get_results($query);
        if (!empty($results)) {
            ?>
            <ul class="wdm-recent-bidders clearfix">
                <li class="wdmua-recent-bidders-list wdmua-recent-bidders-list-first"><h4><?php _e("Bidder Name", "wdm-ultimate-auction"); ?></h4></li>
                <li class="wdmua-recent-bidders-list"><h4><?php _e("Bid Price", "wdm-ultimate-auction"); ?></h4></li>
                <li class="wdmua-recent-bidders-list wdmua-recent-bidders-list-last"><h4><?php _e("When", "wdm-ultimate-auction"); ?></h4></li>
            </ul>
            <?php
            foreach ($results as $result) {
                $curr_time = current_time( 'timestamp' );
                $bid_time = strtotime($result->date);

                $secs = $curr_time - $bid_time;

                $dys = floor($secs / 86400);
                $secs %= 86400;

                $hrs = floor($secs / 3600);
                $secs %= 3600;

                $mins = floor($secs / 60);
                $secs %= 60;

                $ago_time = "";

                if ($dys > 1)
                    $ago_time = $dys . ' ' . __("days", "wdm-ultimate-auction");
                elseif ($dys == 1)
                    $ago_time = $dys . ' ' . __("day", "wdm-ultimate-auction");
                elseif ($dys < 1) {
                    if ($hrs > 1)
                        $ago_time = $hrs . ' ' . __("hours", "wdm-ultimate-auction");
                    elseif ($hrs == 1)
                        $ago_time = $hrs . ' ' . __("hour", "wdm-ultimate-auction");
                    elseif ($hrs < 1) {
                        if ($mins > 1)
                            $ago_time = $mins . ' ' . __("minutes", "wdm-ultimate-auction");
                        elseif ($mins == 1)
                            $ago_time = $mins . ' ' . __("minute", "wdm-ultimate-auction");
                        elseif ($mins < 1) {
                            if ($secs > 1)
                                $ago_time = $secs . ' ' . __("seconds", "wdm-ultimate-auction");
                            elseif ($secs == 1)
                                $ago_time = $secs . ' ' . __("second", "wdm-ultimate-auction");
                        }
                    }
                }
                ?>
                <ul class="wdm-recent-bidders clearfix">
                    <li class="wdmua-recent-bidders-list wdmua-recent-bidders-list-first">
                        <?php echo $result->name; ?> 
                    </li>
                    <li class="wdmua-recent-bidders-list">
                        <?php echo $currency_symbol . number_format($result->bid, 2, '.', ',') . " " . $currency_code_display; ?>
                    </li>
                    <li class="wdmua-recent-bidders-list wdmua-recent-bidders-list-last">
                        <?php printf(__("%s ago", "wdm-ultimate-auction"), $ago_time); ?>
                    </li>
                </ul>
                <?php
            }
        }
        ?>
    </div>

</div>
<script type="text/javascript">
    jQuery(document).ready(function () {

        jQuery("#wdm-desc-aucdesc-link").css("background-color", "#ffffff");
        jQuery("#wdm-desc-aucdesc-link").css("border-bottom-color", "#ffffff");
        jQuery("#wdm-desc-cmt-link").css("background-color", "#dddddd");
        jQuery("#wdm-desc-msg-link").css("background-color", "#dddddd");
        jQuery("#wdm-desc-bids-link").css("background-color", "#dddddd");
        jQuery("#wdm-desc-ship-link").css("background-color", "#dddddd");

        jQuery("#wdm-desc-cmt-link").click(function () {
            jQuery("#wdm-desc-cmt-tab").css("display", "block");
            jQuery("#wdm-desc-msg-tab").css("display", "none");
            jQuery("#wdm-desc-bids-tab").css("display", "none");
            jQuery("#wdm-desc-aucdesc-tab").css("display", "none");
            jQuery("#wdm-desc-ship-tab").css("display", "none");
            jQuery(this).css("border-bottom-color", "#ffffff");
            jQuery("#wdm-desc-msg-link").css("border-bottom-color", "#cccccc");
            jQuery("#wdm-desc-bids-link").css("border-bottom-color", "#cccccc");
            jQuery("#wdm-desc-aucdesc-link").css("border-bottom-color", "#cccccc");
            jQuery("#wdm-desc-ship-link").css("border-bottom-color", "#cccccc");
            jQuery(this).css("background-color", "#ffffff");
            jQuery("#wdm-desc-msg-link").css("background-color", "#dddddd");
            jQuery("#wdm-desc-aucdesc-link").css("background-color", "#dddddd");
            jQuery("#wdm-desc-ship-link").css("background-color", "#dddddd");
            jQuery("#wdm-desc-bids-link").css("background-color", "#dddddd");
        });

        jQuery("#wdm-desc-msg-link").click(function () {
            jQuery("#wdm-desc-msg-tab").css("display", "block");
            jQuery("#wdm-desc-cmt-tab").css("display", "none");
            jQuery("#wdm-desc-bids-tab").css("display", "none");
            jQuery("#wdm-desc-ship-tab").css("display", "none");
            jQuery("#wdm-desc-aucdesc-tab").css("display", "none");
            jQuery(this).css("border-bottom-color", "#ffffff");
            jQuery("#wdm-desc-cmt-link").css("border-bottom-color", "#cccccc");
            jQuery("#wdm-desc-bids-link").css("border-bottom-color", "#cccccc");
            jQuery("#wdm-desc-aucdesc-link").css("border-bottom-color", "#cccccc");
            jQuery("#wdm-desc-ship-link").css("border-bottom-color", "#cccccc");
            jQuery(this).css("background-color", "#ffffff");
            jQuery("#wdm-desc-cmt-link").css("background-color", "#dddddd");
            jQuery("#wdm-desc-aucdesc-link").css("background-color", "#dddddd");
            jQuery("#wdm-desc-ship-link").css("background-color", "#dddddd");
            jQuery("#wdm-desc-bids-link").css("background-color", "#dddddd");
        });

        jQuery("#wdm-desc-bids-link").click(function () {
            jQuery("#wdm-desc-bids-tab").css("display", "block");
            jQuery("#wdm-desc-cmt-tab").css("display", "none");
            jQuery("#wdm-desc-msg-tab").css("display", "none");
            jQuery("#wdm-desc-aucdesc-tab").css("display", "none");
            jQuery("#wdm-desc-ship-tab").css("display", "none");
            jQuery(this).css("border-bottom-color", "#ffffff");
            jQuery("#wdm-desc-cmt-link").css("border-bottom-color", "#cccccc");
            jQuery("#wdm-desc-msg-link").css("border-bottom-color", "#cccccc");
            jQuery("#wdm-desc-aucdesc-link").css("border-bottom-color", "#cccccc");
            jQuery("#wdm-desc-ship-link").css("border-bottom-color", "#cccccc");
            jQuery(this).css("background-color", "#ffffff");
            jQuery("#wdm-desc-msg-link").css("background-color", "#dddddd");
            jQuery("#wdm-desc-aucdesc-link").css("background-color", "#dddddd");
            jQuery("#wdm-desc-ship-link").css("background-color", "#dddddd");
            jQuery("#wdm-desc-cmt-link").css("background-color", "#dddddd");
        });

        jQuery("#wdm-desc-aucdesc-link").click(function () {
            jQuery("#wdm-desc-aucdesc-tab").css("display", "block");
            jQuery("#wdm-desc-cmt-tab").css("display", "none");
            jQuery("#wdm-desc-msg-tab").css("display", "none");
            jQuery("#wdm-desc-bids-tab").css("display", "none");
            jQuery("#wdm-desc-ship-tab").css("display", "none");
            jQuery(this).css("border-bottom-color", "#ffffff");
            jQuery("#wdm-desc-cmt-link").css("border-bottom-color", "#cccccc");
            jQuery("#wdm-desc-msg-link").css("border-bottom-color", "#cccccc");
            jQuery("#wdm-desc-bids-link").css("border-bottom-color", "#cccccc");
            jQuery("#wdm-desc-ship-link").css("border-bottom-color", "#cccccc");
            jQuery(this).css("background-color", "#ffffff");
            jQuery("#wdm-desc-msg-link").css("background-color", "#dddddd");
            jQuery("#wdm-desc-cmt-link").css("background-color", "#dddddd");
            jQuery("#wdm-desc-bids-link").css("background-color", "#dddddd");
            jQuery("#wdm-desc-ship-link").css("background-color", "#dddddd");
        });

        jQuery("#wdm-desc-ship-link").click(function () {
            jQuery("#wdm-desc-ship-tab").css("display", "block");
            jQuery("#wdm-desc-cmt-tab").css("display", "none");
            jQuery("#wdm-desc-msg-tab").css("display", "none");
            jQuery("#wdm-desc-bids-tab").css("display", "none");
            jQuery("#wdm-desc-aucdesc-tab").css("display", "none");
            jQuery(this).css("border-bottom-color", "#ffffff");
            jQuery("#wdm-desc-cmt-link").css("border-bottom-color", "#cccccc");
            jQuery("#wdm-desc-msg-link").css("border-bottom-color", "#cccccc");
            jQuery("#wdm-desc-bids-link").css("border-bottom-color", "#cccccc");
            jQuery("#wdm-desc-aucdesc-link").css("border-bottom-color", "#cccccc");
            jQuery(this).css("background-color", "#ffffff");
            jQuery("#wdm-desc-msg-link").css("background-color", "#dddddd");
            jQuery("#wdm-desc-cmt-link").css("background-color", "#dddddd");
            jQuery("#wdm-desc-bids-link").css("background-color", "#dddddd");
            jQuery("#wdm-desc-aucdesc-link").css("background-color", "#dddddd");
        });

        jQuery("#wdm-total-bids-link").click(
                function ()
                {
                    jQuery("#wdm-desc-bids-tab").css("display", "block");
                    jQuery("#wdm-desc-ship-tab").css("display", "none");
                    jQuery("#wdm-desc-aucdesc-tab").css("display", "none");
                    jQuery("#wdm-desc-cmt-tab").css("display", "none");
                    jQuery("#wdm-desc-msg-tab").css("display", "none");
                    jQuery("#wdm-desc-bids-link").css("border-bottom-color", "#ffffff");
                    jQuery("#wdm-desc-cmt-link").css("border-bottom-color", "#cccccc");
                    jQuery("#wdm-desc-msg-link").css("border-bottom-color", "#cccccc");
                    jQuery("#wdm-desc-ship-link").css("border-bottom-color", "#cccccc");
                    jQuery("#wdm-desc-aucdesc-link").css("border-bottom-color", "#cccccc");
                    jQuery("#wdm-desc-bids-link").css("background-color", "#ffffff");
                    jQuery("#wdm-desc-msg-link").css("background-color", "#dddddd");
                    jQuery("#wdm-desc-cmt-link").css("background-color", "#dddddd");
                    jQuery("#wdm-desc-ship-link").css("background-color", "#dddddd");
                    jQuery("#wdm-desc-aucdesc-link").css("background-color", "#dddddd");
                }
        );

        jQuery("#wdm-shipping-info-link").click(
                function ()
                {
                    jQuery("#wdm-desc-ship-tab").css("display", "block");
                    jQuery("#wdm-desc-bids-tab").css("display", "none");
                    jQuery("#wdm-desc-cmt-tab").css("display", "none");
                    jQuery("#wdm-desc-msg-tab").css("display", "none");
                    jQuery("#wdm-desc-aucdesc-tab").css("display", "none");
                    jQuery("#wdm-desc-ship-link").css("border-bottom-color", "#ffffff");
                    jQuery("#wdm-desc-cmt-link").css("border-bottom-color", "#cccccc");
                    jQuery("#wdm-desc-msg-link").css("border-bottom-color", "#cccccc");
                    jQuery("#wdm-desc-bids-link").css("border-bottom-color", "#cccccc");
                    jQuery("#wdm-desc-aucdesc-link").css("border-bottom-color", "#cccccc");
                    jQuery("#wdm-desc-ship-link").css("background-color", "#ffffff");
                    jQuery("#wdm-desc-msg-link").css("background-color", "#dddddd");
                    jQuery("#wdm-desc-cmt-link").css("background-color", "#dddddd");
                    jQuery("#wdm-desc-aucdesc-link").css("background-color", "#dddddd");
                    jQuery("#wdm-desc-bids-link").css("background-color", "#dddddd");
                }
        );

    });
</script>