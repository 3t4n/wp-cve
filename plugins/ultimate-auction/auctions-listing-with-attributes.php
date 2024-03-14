<?php

//auction listing page - pagination
$page_num = get_option('wdm_auc_num_per_page');
$page_num = (!empty($page_num) && $page_num > 0) ? $page_num : 20;

function auction_pagination($pages = '', $range = 2, $paged) {
    $showitems = ($range * 2) + 1;

    if (empty($paged))
        $paged = 1;

    if ($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;

        if (!$pages) {
            $pages = 1;
        }
    }

    if (1 != $pages) {
        echo "<div class='pagination'>";
        printf('<span>' . __("Page %1\$s of %2\$s", "wdm-ultimate-auction") . '</span>', $paged, $pages);
        if ($paged > 2 && $paged > $range + 1 && $showitems < $pages)
            echo "<a href='" . get_pagenum_link(1) . "'>&laquo;</a>";
        if ($paged > 1 && $showitems < $pages)
            echo "<a href='" . get_pagenum_link($paged - 1) . "'>&lsaquo;</a>";

        for ($i = 1; $i <= $pages; $i++) {
            if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems )) {
                echo ($paged == $i) ? "<span class='current'>" . $i . "</span>" : "<a href='" . get_pagenum_link($i) . "' class='inactive' >" . $i . "</a>";
            }
        }

        if ($paged < $pages && $showitems < $pages)
            echo "<a href='" . get_pagenum_link($paged + 1) . "'>&rsaquo;</a>";
        if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages)
            echo "<a href='" . get_pagenum_link($pages) . "'>&raquo;</a>";
        echo "</div>\n";
    }
}

$wdm_auction_array = array();

if (get_query_var('paged')) {
    $paged = get_query_var('paged');
} elseif (get_query_var('page')) {
    $paged = get_query_var('page');
} else {
    $paged = 1;
}

$args = array(
    'posts_per_page' => $page_num,
    'post_type' => 'ultimate-auction',
    //'auction-status' => 'live',
    'auction-status' => $auction_status_val,
    'post_status' => 'publish',
    'paged' => $paged,
    'suppress_filters' => false,
    'orderby' => $sortby_val, 
    'order' => $order_val
);

$arg_data_c = array(
    'posts_per_page' => -1,
    'post_type' => 'ultimate-auction',
    //'auction-status' => 'live',
    'auction-status' => $auction_status_val,
    'post_status' => 'publish',
    'paged' => $paged,
    'suppress_filters' => false,
    'orderby' => $sortby_val, 
    'order' => $order_val
);

do_action('wdm_ua_before_get_auctions');

$wdm_auction_array = get_posts($args);

$count_pages = count(get_posts($arg_data_c));

do_action('wdm_ua_after_get_auctions');

$show_content = '';
$show_content = apply_filters('wdm_ua_before_auctions_listing', $show_content);
echo $show_content;
?>

<div class="wdm-auction-listing-container">
    <ul class="wdm_auctions_list">
        <li class="auction-list-menus">
            <ul>
                <li class="wdm-apn auc_single_list wdm-ua-desktop"><strong><?php _e("Product", "wdm-ultimate-auction"); ?></strong></li>
                <li class="auc_single_list wdm-ua-af-list-wrap-alt">
                    <ul class="clearfix wdm-ua-af-list-inner-alt">
                        <li class="wdm-apt auc_single_list wdm-ua-desktop"><strong></strong></li>
                        <li class="wdm-app auc_single_list wdm-ua-desktop"><strong><?php _e("Current Price", "wdm-ultimate-auction"); ?></strong></li>
                        <li class="wdm-apb auc_single_list wdm-ua-desktop"><strong><?php _e("Bids Placed", "wdm-ultimate-auction"); ?></strong></li>
                        <li class="wdm-ape auc_single_list wdm-ua-desktop"><strong><?php _e("Ending", "wdm-ultimate-auction"); ?></strong></li>
                    </ul>
                </li>
            </ul>
        </li>

        <?php
        //auction listing page container
        foreach ($wdm_auction_array as $wdm_single_auction) {
            global $wpdb;

            /* $query = "SELECT MAX(bid) FROM " . $wpdb->prefix . "wdm_bidders WHERE auction_id =" . $wdm_single_auction->ID; */

            $table = $wpdb->prefix . "wdm_bidders";
            $auctionid = $wdm_single_auction->ID;

            $query = $wpdb->prepare("SELECT MAX(bid) FROM {$table} WHERE auction_id = %d", 
                $auctionid);
            
            $curr_price = $wpdb->get_var($query);

            ?>
            <li class="wdm-auction-single-item">
                <a href="<?php echo get_permalink() . $set_char . "ult_auc_id=" . $wdm_single_auction->ID; ?>" class="wdm-auction-list-link">
                    <ul class="clearfix">
                        <li class="wdm-apn auc_single_list wdm-ua-single-list">
                            <div  class="wdm_single_auction_thumb">
                                <?php
                                $vid_arr = array('mpg', 'mpeg', 'avi', 'mov', 'wmv', 'wma', 'mp4', '3gp', 'ogm', 'mkv', 'flv');
                                $auc_thumb = get_post_meta($wdm_single_auction->ID, 'wdm_auction_thumb', true);
                                $imgMime = wdm_get_mime_type($auc_thumb);
                                $img_ext = explode(".", $auc_thumb);
                                $img_ext = end($img_ext);

                                if (strpos($img_ext, '?') !== false)
                                    $img_ext = strtolower(strstr($img_ext, '?', true));

                                if ((!is_null( $imgMime ) && strstr($imgMime, "video/")) || in_array($img_ext, $vid_arr) || (!is_null( $auc_thumb ) && strstr($auc_thumb, "youtube.com")) || (!is_null( $auc_thumb ) && strstr($auc_thumb, "vimeo.com"))) {
                                    $auc_thumb = plugins_url('img/film.png', __FILE__);
                                }
                                if (empty($auc_thumb)) {
                                    $auc_thumb = plugins_url('img/no-pic.jpg', __FILE__);
                                }
                                ?>
                                <img src="<?php echo $auc_thumb; ?>" alt="<?php echo $wdm_single_auction->post_title; ?>" />
                            </div>
                            <?php if($auction_status_val == 'live'){ ?>
                            <div class="wdm-ua-bid-now-btn-wrap wdm-ua-responsive">
                                <input class="wdm_bid_now_btn" type="button" value="<?php _e("Bid Now", "wdm-ultimate-auction"); ?>" />
                            </div>
                            <?php } ?>
                        </li>

                        <li class="auc_single_list wdm-ua-af-list-wrap-alt">
                            <ul class="clearfix wdm-ua-af-list-inner-alt">
                                <li class="wdm-apt auc_single_list wdm-ua-single-list wdm-ua-af-listing-alt">
                                    <div class="wdm-auction-title"><?php echo $wdm_single_auction->post_title; ?></div>
                                </li>

                                <li class="wdm-app auc_single_list auc_list_center wdm-ua-single-list wdm-ua-af-listing-alt">
                                    <strong class="wdm-ua-responsive wdmua-feeder-label"><?php _e("Current Price", "wdm-ultimate-auction"); ?>: </strong>
                                    <span class="wdm-auction-price wdm-mark-green">
                                        <?php
                                        $cc = substr(get_option('wdm_currency'), -3);
                                        $ob = get_post_meta($wdm_single_auction->ID, 'wdm_opening_bid', true);
                                        $bnp = get_post_meta($wdm_single_auction->ID, 'wdm_buy_it_now', true);

                                        if ((!empty($curr_price) || $curr_price > 0) && !empty($ob))
                                            echo $currency_symbol . number_format($curr_price, 2, '.', ',') . " " . $currency_code_display;
                                        elseif (!empty($ob))
                                            echo $currency_symbol . number_format($ob, 2, '.', ',') . " " . $currency_code_display;
                                        elseif (empty($ob) && !empty($bnp))
                                            printf(__("Buy at %s%s %s", "wdm-ultimate-auction"), $currency_symbol, number_format($bnp, 2, '.', ','), $currency_code_display);
                                        ?>
                                    </span>
                                </li>

                                <li class="wdm-apb auc_single_list auc_list_center wdm-ua-af-listing-alt">
                                    <strong class="wdm-ua-responsive wdmua-feeder-label"><?php _e("Bids Placed", "wdm-ultimate-auction"); ?>: </strong>
                                    <?php

                                    /*$get_bids = "SELECT COUNT(bid) FROM " . $wpdb->prefix . "wdm_bidders WHERE auction_id =" . $wdm_single_auction->ID;*/

                                    $get_bids = $wpdb->prepare("SELECT COUNT(bid) FROM 
                                        {$table} WHERE auction_id = %d", $auctionid);

                                    $bids_placed = $wpdb->get_var($get_bids);
                                    
                                    if (!empty($bids_placed) || $bids_placed > 0)
                                        echo "<span class='wdm-bids-avail wdm-mark-normal'>" . $bids_placed . "</span>";
                                    else
                                        echo "<span class='wdm-no-bids-avail wdm-mark-red'>" . __("No bids placed", "wdm-ultimate-auction") . "</span>";
                                    ?>
                                </li>

                                <li class="wdm-ape auc_single_list auc_list_center wdm-ua-af-listing-alt wdm-ua-af-listing-alt-last">
                                    <strong class="wdm-ua-responsive wdmua-feeder-label"><?php _e("Ending", "wdm-ultimate-auction"); ?>: </strong>
                                    <?php
                                    $now = current_time( 'timestamp' );
                                    $ending_date = strtotime(get_post_meta($wdm_single_auction->ID, 'wdm_listing_ends', true));
                                    $act_trm = wp_get_post_terms($wdm_single_auction->ID, 'auction-status', array("fields" => "names"));

                                    $seconds = $ending_date - $now;

                                    if (in_array('expired', $act_trm)) {
                                        echo "<span class='wdm-mark-red'>" . __("Expired", "wdm-ultimate-auction") . "</span>";
                                    } elseif ($seconds > 0 && !in_array('expired', $act_trm)) {
                                        $days = floor($seconds / 86400);
                                        $seconds %= 86400;

                                        $hours = floor($seconds / 3600);
                                        $seconds %= 3600;

                                        $minutes = floor($seconds / 60);
                                        $seconds %= 60;

                                        if ($days > 1)
                                            echo "<span class='wdm-mark-normal'>" . $days . " " . __("days", "wdm-ultimate-auction") . "</span>";
                                        elseif ($days == 1)
                                            echo "<span class='wdm-mark-normal'>" . $days . " " . __("day", "wdm-ultimate-auction") . "</span>";
                                        elseif ($days < 1) {
                                            if ($hours > 1)
                                                echo "<span class='wdm-mark-normal'>" . $hours . " " . __("hours", "wdm-ultimate-auction") . "</span>";
                                            elseif ($hours == 1)
                                                echo "<span class='wdm-mark-normal'>" . $hours . " " . __("hour", "wdm-ultimate-auction") . "</span>";
                                            elseif ($hours < 1) {
                                                if ($minutes > 1)
                                                    echo "<span class='wdm-mark-normal'>" . $minutes . " " . __("minutes", "wdm-ultimate-auction") . "</span>";
                                                elseif ($minutes == 1)
                                                    echo "<span class='wdm-mark-normal'>" . $minutes . " " . __("minute", "wdm-ultimate-auction") . "</span>";
                                                elseif ($minutes < 1) {
                                                    if ($seconds > 1)
                                                        echo "<span class='wdm-mark-normal'>" . $seconds . " " . __("seconds", "wdm-ultimate-auction") . "</span>";
                                                    elseif ($seconds == 1)
                                                        echo "<span class='wdm-mark-normal'>" . $seconds . " " . __("second", "wdm-ultimate-auction") . "</span>";
                                                    else
                                                        echo "<span class='wdm-mark-red'>" . __("Expired", "wdm-ultimate-auction") . "</span>";
                                                }
                                            }
                                        }
                                    }
                                    else {
                                        echo "<span class='wdm-mark-red'>" . __("Expired", "wdm-ultimate-auction") . "</span>";
                                    }
                                    ?>
                                    <br/>
                                </li>
                            </ul>
                        </li>

                        <?php if($auction_status_val == 'live'){ ?>
                            <li class="wdm-apbid auc_single_list auc_list_center wdm-ua-bid-now-wrap wdm-ua-desktop">
                                <input class="wdm_bid_now_btn" type="button" value="<?php _e("Bid Now", "wdm-ultimate-auction"); ?>" />
                            </li>
                        <?php } ?>
                           
                        <li class="wdm-ua-desktop"><div class="wdm-apd"><?php echo $wdm_single_auction->post_excerpt; ?> </div></li>
                    </ul>
                </a>
            </li>
            <?php
        }

        if (!empty($count_pages)) {
            echo '<input type="hidden" id="wdm_ua_auc_avail" value="' . 
                $count_pages . '" />';

            $c = ceil($count_pages / $page_num);
            auction_pagination($c, 1, $paged);
        }

        ?>
    </ul>
</div>