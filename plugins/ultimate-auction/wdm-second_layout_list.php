<div class="container-auw">
        <div class="auction-table-design">
            <div class="auction-table-header">
                <ul>
                    <li class="prod_img"><?php _e("Image", "wdm-ultimate-auction"); ?></li>
                    <li class="prod_summary"><?php _e("Product Summary", "wdm-ultimate-auction"); ?></li>
                    <li class="cur-price"><?php _e("Current Price", "wdm-ultimate-auction"); ?></li>
                    <li class="bid_place"><?php _e("Bids Placed", "wdm-ultimate-auction"); ?></li>
                    <li class="end-time"><?php _e("Ending", "wdm-ultimate-auction"); ?></li>
                    <li class="bids_now-btn"><?php _e("Bids Now", "wdm-ultimate-auction"); ?></li>
                </ul>
            </div>
            
        </div>

     <div class="product-details-content">
        <?php
        //auction listing page container
        foreach ($wdm_auction_array as $wdm_single_auction) {
            global $wpdb;

            /*$query = "SELECT MAX(bid) FROM " . $wpdb->prefix . "wdm_bidders WHERE auction_id =" . $wdm_single_auction->ID;*/

            $table = $wpdb->prefix . "wdm_bidders";
            $auctionid = $wdm_single_auction->ID;

            $query = $wpdb->prepare("SELECT MAX(bid) FROM {$table} WHERE auction_id = %d", 
                $auctionid);

            $curr_price = $wpdb->get_var($query);
            ?>
             
                
                    <ul>
                        <li class="product_single_auction_thumb prod_img">
                            <div class="show-in-mobile hide-in-desktop">
                                <label>Product Image</label>
                            </div>
                             <?php
                                $vid_arr = array('mpg', 'mpeg', 'avi', 'mov', 'wmv', 'wma', 'mp4', '3gp', 'ogm', 'mkv', 'flv');
                                $auc_thumb = get_post_meta($wdm_single_auction->ID, 'wdm_auction_thumb', true);
                                //$imgid  = attachment_url_to_postid( $auc_thumb );
                                $imgMime = wdm_get_mime_type($auc_thumb);
                                $img_ext = explode(".", $auc_thumb);
                                $img_ext = end($img_ext);

                                if (strpos($img_ext, '?') !== false)
                                $img_ext = strtolower(strstr($img_ext, '?', true));

                                if ( (!is_null( $auc_thumb ) && strstr($auc_thumb, "youtube.com")) || (!is_null( $auc_thumb ) && strstr($auc_thumb, "youtu.be")) ) {

                                    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $auc_thumb, $match);

                                    $youtube_id = '';

                                    if (isset($match[1])) {
                                        $youtube_id = $match[1];
                                    }


                                    if ($youtube_id) {

                                        $youtube_url = 'https://img.youtube.com/vi/'.$youtube_id.'/maxresdefault.jpg';

                                    } else {

                                        $youtube_url = plugins_url('img/video-banner.png', __FILE__);

                                    }


                                    $auc_thumb = $youtube_url;
                                }

                                elseif (!is_null( $auc_thumb ) && strstr($auc_thumb, "vimeo.com")) {

                                    $auc_thumb = plugins_url('img/video-banner.png', __FILE__);
                                }

                                elseif ((!is_null( $imgMime ) && strstr($imgMime, "video/")) || in_array($img_ext, $vid_arr)) {
                                    $auc_thumb = plugins_url('img/video-banner.png', __FILE__);
                                }

                                elseif (!is_null( $imgMime ) && strstr($imgMime, "image/")){

                                    $imgid  = attachment_url_to_postid( $auc_thumb );
                                    $Image_URL = wp_get_attachment_image_url($imgid, 'thumbnail');
                                    $auc_thumb = $Image_URL;

                                } 

                                elseif (empty($auc_thumb)) {
                                    $auc_thumb = plugins_url('img/no-pic.jpg', __FILE__);
                                }

                                else {

                                    $auc_thumb = plugins_url('img/no-pic.jpg', __FILE__);

                                }


                                ?>
                                
                                <img src="<?php echo $auc_thumb; ?>" alt="<?php echo $wdm_single_auction->post_title; ?>" />
                            
                        </li>
                        <li class="prod_summary">
                            <div class="product-name"><?php echo $wdm_single_auction->post_title; ?></div>
                            <div class="product-description"><?php 
                            if($wdm_single_auction->post_excerpt) {

                                echo substr($wdm_single_auction->post_excerpt, 0, 100) . ' ..'; 

                            }
                            ?> </div>
                        </li>
                        <li class="cur-price">
                            <strong class="show-in-mobile hide-in-desktop"><?php _e("Current Price", "wdm-ultimate-auction"); ?>: </strong>
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
                        <li class="bid_place">
                            <strong class="show-in-mobile hide-in-desktop"><?php _e("Bids Placed", "wdm-ultimate-auction"); ?>: </strong>
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
                        <li class="end-time">
                            <strong class="show-in-mobile hide-in-desktop"><?php _e("Ending", "wdm-ultimate-auction"); ?>: </strong>
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
                        <li class="bids_now-btn">
							<a href="<?php echo get_permalink() . $set_char . "ult_auc_id=" . $wdm_single_auction->ID; ?>">
								<input type="hidden" name="ult_auc_id" value="<?php echo $wdm_single_auction->ID; ?>">
								<input type="submit" value="<?php _e("Bid Now", "wdm-ultimate-auction"); ?>">
							</a>
                        </li>
                    </ul>
                
            
            <?php
        }


        if (!empty($count_pages)) {
            echo '<input type="hidden" id="wdm_ua_auc_avail" value="' . 
                $count_pages . '" />';

            $c = ceil($count_pages / $page_num);
            auction_pagination($c, 1, $paged);
        }

        ?>
    </div>
</div>


