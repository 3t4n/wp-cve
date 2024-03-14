<div class="container-auw">
    <div class="product-details-row">
        <div class="col-left-img">
            <div class="gallery-container">
                <div class="slider">
                      <div class="prev-btn">&#10094;</div>
                          <div id="currentSlideContainer">


                            <!-- Current slide (image or video) will be displayed here -->
                          </div>
                      <div class="next-btn" >&#10095;</div>
                </div>
                <div class="thumbnails">
                <?php


                    $images = '';
                    $image = array();
                    $totalimages = 0;

                    $mnimg = get_post_meta($wdm_auction->ID, 'wdm-main-image', true);
                    $img_arr = array('png', 'jpg', 'jpeg', 'gif', 'bmp', 'ico');
                    $vid_arr = array('mpg', 'mpeg', 'avi', 'mov', 'wmv', 'wma', 'mp4', '3gp', 'ogm', 'mkv', 'flv');

                   
                    for ($c = 0; $c <= 3; $c++) {

                        $imgURL = get_post_meta($wdm_auction->ID, 'wdm-image-' . ($c+1), true);

                        $imgMime = wdm_get_mime_type($imgURL);
                        $img_ext = explode(".", $imgURL);
                        $img_ext = end($img_ext);

                        if ((!is_null( $imgMime ) && strstr($imgMime, "image/")) || in_array($img_ext, $img_arr)){

                            $imgid  = attachment_url_to_postid( $imgURL );
                            $Image_URL = wp_get_attachment_image_url($imgid, 'medium_large');

                        } else {

                            $Image_URL = $imgURL;

                        }


                        if (strpos($img_ext, '?') !== false)
                            $img_ext = strtolower(strstr($img_ext, '?', true));

                        if (!empty($imgURL)) {


                            $totalimages++;


                            if ((!is_null( $imgMime ) && strstr($imgMime, "image/")) || in_array($img_ext, $img_arr)){
                                $images .= '<img class="thumbnail" src="' . get_post_meta($wdm_auction->ID, 'wdm-image-' . ($c+1), true) . '" alt="Thumbnail '. ($c+1) .'" />';

                                $image[$c]['type'] = 'image';
                                $image[$c]['src'] = $Image_URL;
                            }

                            elseif ((!is_null( $imgMime ) && strstr($imgMime, "video/")) || in_array($img_ext, $vid_arr)){
                                $images .= '<video class="thumbnail" src="' . get_post_meta($wdm_auction->ID, 'wdm-image-' . ($c+1), true) . '" controls></video>';

                                $image[$c]['type'] = 'video';
                                $image[$c]['src'] = $Image_URL;
                            }

                            elseif ((!is_null( $imgURL ) && strstr($imgURL, "youtube.com")) || (!is_null( $imgURL ) && strstr($imgURL, "youtu.be"))){

                        
                              /*  $htmlcode = wp_oembed_get($Image_URL);
                                $images .= $htmlcode;*/

                                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $Image_URL, $match);

                                    $youtube_id = '';

                                    if (isset($match[1])) {
                                        $youtube_id = $match[1];
                                    }

                                    if ($youtube_id) {
                                      
                                        $youtubeurl = 'https://img.youtube.com/vi/'.$youtube_id.'/default.jpg';
                                        //$youtubeurl = 'https://img.youtube.com/vi/'.$youtube_id.'/maxresdefault.jpg';

                                       
                                    } else {
                                      
                                        $youtubeurl = plugins_url('img/video-banner.png', __FILE__);

                                    }


                                    if ($youtube_id) {

                                       // $images .= '<img class="thumbnail" src="https://img.youtube.com/vi/'.$youtube_id.'/maxresdefault.jpg" alt="Thumbnail '. $c+1 .'" />';
                                        $images .= '<img class="thumbnail" src="https://img.youtube.com/vi/'.$youtube_id.'/default.jpg" alt="Thumbnail '. ($c+1) .'" />';

                                    } else {

                                        $images .= '<img class="thumbnail" src=" '.$youtubeurl.' " alt="Thumbnail '. ($c+1) .'" />';

                                    }


                                $image[$c]['type'] = 'youtube';
                                $image[$c]['src'] = $youtubeurl;
                                $image[$c]['href'] = $Image_URL;
                                
                            }

                            elseif (!is_null( $imgURL ) && strstr($imgURL, "vimeo.com")){

                                $youtubeurl = plugins_url('img/video-banner.png', __FILE__);

                                $images .= '<img class="thumbnail"  src="' . plugins_url('img/video-banner.png', __FILE__) . '" alt="Thumbnail '. ($c+1) .'" />';

                                $image[$c]['type'] = 'youtube';
                                $image[$c]['src'] = $youtubeurl;
                                $image[$c]['href'] = $Image_URL;


                            }

                            else{

                                $images .= '<img class="thumbnail"  src="' . plugins_url('img/image_not_found.png', __FILE__) . '" alt="Thumbnail '. ($c+1) .'" />';

                                $NoImgURL = plugins_url('img/image_not_found.png', __FILE__);

                                $image[$c]['type'] = 'image';
                                $image[$c]['src'] = $NoImgURL;
                            }

                        }

                    }
                   
                   
                echo $images;  


                wp_register_script('wdm-slider-js', plugins_url('js/wdm-gallery-script.js', __FILE__), array('jquery'));
                $sliderJsVars = array(
                    'Totalimages' => $totalimages,
                );
                
                if($totalimages > 0){

                    $totalcount = count($image) - 1;
                    $sliderJsVars['Auctionimages'] = $image;
                    $sliderJsVars['Totalarray'] = $totalcount;
                    // wp_localize_script('wdm-slider-js', 'Auctionimages', $image);
                    // wp_localize_script('wdm-slider-js', 'Totalarray', $totalcount);

                }
                wp_localize_script('wdm-slider-js', 'sliderJsVars', $sliderJsVars);




               /* wp_register_script('wdm-slider-js', plugins_url('js/wdm-gallery-script.js', __FILE__), array('jquery'));
                wp_localize_script('wdm-slider-js', 'Totalimages', $totalimages);

                if($totalimages > 0){

                    $totalcount = count($image) - 1;
               
                    wp_localize_script('wdm-slider-js', 'Auctionimages', $image);
                    wp_localize_script('wdm-slider-js', 'Totalarray', $totalcount);

                }
*/
                wp_enqueue_script( 'wdm-slider-js' );
        
                    ?>
                </div>
            </div>

            <div id="popup" class="popup">
                <span class="close-btn">&times;</span>
                    <div id="popupContent">
                      <!-- Popup content (image or video) will be displayed here -->
                    </div>
                <div class="prev-btn" >&#10094;</div>
                <div class="next-btn" >&#10095;</div>
            </div>
        </div>


        <div class="col-right-description">
            <div class="auction_description">
                <h1><?php echo $wdm_auction->post_title; ?></h1>

                <?php
                $ext_html = '';
                $ext_html = apply_filters('wdm_ua_text_before_bid_section', $ext_html, $wdm_auction->ID);
                echo $ext_html;

                //get auction-status taxonomy value for the current post - live/expired
                $active_terms = wp_get_post_terms($wdm_auction->ID, 'auction-status', array("fields" => "names"));

                //incremented price value

                if($no_bid == 0){
                    $wdm_inc_val = get_post_meta($wdm_auction->ID, 'wdm_incremental_val', true);
                    $inc_price = (int)$curr_price + (int)$wdm_inc_val;
                }else{
                    $inc_price = $curr_price;
                }

                //if the auction has reached it's time limit, expire it
                if ((current_time( 'timestamp' ) >= strtotime(get_post_meta($wdm_auction->ID, 'wdm_listing_ends', true)))) {
                    if (!in_array('expired', $active_terms)) {
                        $check_term = term_exists('expired', 'auction-status');
                        wp_set_post_terms($wdm_auction->ID, $check_term["term_id"], 'auction-status');
                    }
                }

               // $now = time();
                $now = current_time( 'timestamp' );
                $ending_date = strtotime(get_post_meta($wdm_auction->ID, 'wdm_listing_ends', true));

                //display message for expired auction
                if ((current_time( 'timestamp' ) >= strtotime(get_post_meta($wdm_auction->ID, 'wdm_listing_ends', true))) || in_array('expired', $active_terms)) {

                    $seconds = $now - $ending_date;

                    $rem_tm = wdm_ending_time_second_layout_calculator($seconds);

                    $auc_time = 'exp';
                    
                   
                    if (!empty($to_bid)) { ?>

                        <div class="wdm_bidding_price wdm-align-left">

                        <?php  
                                $current_bid = $currency_symbol . number_format($curr_price, 2, '.', ',') . " " . $currency_code_display;

                                if ($no_bid == 1) { ?>
                                <strong><?php printf(__("Starting Bid : %s", "wdm-ultimate-auction"), $current_bid); ?></strong>
                                <?php } 
                                else { ?>
                                <strong><?php printf(__("Current Bid : %s", "wdm-ultimate-auction"), $current_bid); ?></strong>
                                <?php } ?>
                        
                        </div>
                        <div id="wdm-auction-bids-placed" class="wdm_bids_placed wdm-align-right">
                            <a href="#wdm-tab-anchor-id" id="wdm-total-bids-link"><?php
                                echo $total_bids . " ";
                                echo ($total_bids == 1) ? __("Bid", "wdm-ultimate-auction") : __("Bids", "wdm-ultimate-auction");
                                ?></a>
                        </div>

                        <br />

                        <?php
                    }
                    ?>

                    <div class="wdm-auction-ending-time"><?php printf(__("Ended ago", "wdm-ultimate-auction") . ': ' . __("%s", "wdm-ultimate-auction"), '<span class="wdm-single-auction-ending">' . $rem_tm . '</span>'); ?></div>
                    <?php

                    $bought = get_post_meta($wdm_auction->ID, 'auction_bought_status', true);

                    if ($bought === 'bought') {
                        $buyer_id = get_post_meta($wdm_auction->ID, 'wdm_auction_buyer', true);
                        $buyer = get_user_by('id', $buyer_id);                      
                        ?>
                        <div class="wdm-mark-red">
                        <?php _e("This auction has been bought by", "wdm-ultimate-auction"); ?>
                        <?php  echo $buyer->user_login. ' [' . $currency_symbol . number_format($buy_now_price, 2, '.', ',') . ' ' . $currency_code_display . ']';?>
                        </div>
                        
                    <?php   
                    } else {

                        /*$cnt_qry = "SELECT COUNT(bid) FROM " . $wpdb->prefix . "wdm_bidders WHERE auction_id =" . $wdm_auction->ID;*/

                        $cnt_qry = $wpdb->prepare("SELECT COUNT(bid) FROM {$table} WHERE auction_id = %d", $auctionid);

                        $cnt_bid = $wpdb->get_var($cnt_qry);

                        if ($cnt_bid > 0) {

                            $res_price_met = get_post_meta($wdm_auction->ID, 
                                'wdm_lowest_bid', true);
                            $win_bid = "";

                            /*$bid_q = "SELECT MAX(bid) FROM " . $wpdb->prefix . "wdm_bidders WHERE auction_id =" . $wdm_auction->ID;*/

                            $bid_q = $wpdb->prepare("SELECT MAX(bid) FROM {$table} WHERE auction_id = %d", $auctionid);

                            $win_bid = $wpdb->get_var($bid_q);

                            if ($win_bid >= $res_price_met) {
                                $winner_name = "";

                                /*$name_qry = "SELECT name FROM " . $wpdb->prefix . "wdm_bidders WHERE bid =" . $win_bid . " AND auction_id =" . $wdm_auction->ID . " ORDER BY id DESC";*/

                                $name_qry = $wpdb->prepare("SELECT name FROM {$table}
                                    WHERE bid = %d AND auction_id = %d  ORDER BY id DESC", 
                                    $win_bid, $auctionid);

                                $winner_name = $wpdb->get_var($name_qry);
                                /*printf('<div class="wdm-mark-red">' . __("This auction has been sold to %1$s at %2$s.", "wdm-ultimate-auction") . '</div>', $winner_name, $currency_symbol . number_format($win_bid, 2, '.', ',') . " " . $currency_code_display);*/
                                printf('<div class="wdm-mark-red">' . __('This auction has been sold to %1$s at %2$s.', "wdm-ultimate-auction") . '</div>', $winner_name, $currency_symbol . number_format($win_bid, 2, '.', ',') . " " . $currency_code_display);
                            } else {                                
                                ?>
                                <div class="wdm-mark-red">
                                <?php _e("Auction has expired without reaching its reserve price.", "wdm-ultimate-auction"); ?>
                                </div>
                                <?php
                            }
                        } else {
                            if (empty($to_bid)) {
                                ?>
                                <div class="wdm-mark-red">
                                 <?php _e("Auction has expired without buying.", "wdm-ultimate-auction"); ?>
                                </div>
                                <?php                               
                            } else {
                                ?>
                                <div class="wdm-mark-red">
                                 <?php _e("Auction has expired without any bids.", "wdm-ultimate-auction"); ?>
                                </div>
                                <?php                                  
                            }
                        }
                    }
                }

                else {


                $seconds = $ending_date - $now;
                $rem_tm_live = wdm_ending_time_second_layout_calculator($seconds);
                $auc_time = "live";

                if (is_user_logged_in()) {
                    $curr_user = wp_get_current_user();
                    $auction_bidder_name = $curr_user->user_login;
                    $auction_bidder_email = $curr_user->user_email;
                }

                if (!empty($to_bid)) { 

                ?>

                <div class="bid-count">
                    <div class="bid-type">

                        <?php  
                        $current_bid = $currency_symbol . number_format($curr_price, 2, '.', ',') . " " . $currency_code_display;

                        if ($no_bid == 1) { ?>
                        <h2><?php printf(__("Starting Bid : %s", "wdm-ultimate-auction"), $current_bid); ?></h2>
                        <?php } 
                        else { ?>
                        <h2><?php printf(__("Current Bid : %s", "wdm-ultimate-auction"), $current_bid); ?></h2>
                        <?php }


                        if ($curr_price >= get_post_meta($wdm_auction->ID, 'wdm_lowest_bid', true)) {
                            ?>
                            
                            <p><?php _e("Reserve price has been met.", "wdm-ultimate-auction"); ?></p>
                            
                            <?php
                        } else {
                            ?>
                            <p><?php _e("Reserve price has not been met by any bid.", "wdm-ultimate-auction"); ?></p>
                            <?php
                        }
                        ?>

                    </div>
                    <div class="bid-complate" id="wdm-auction-bids-placed">
                        <a href="#wdm-tab-anchor-id" id="wdm-total-bids-link">
                            <h2>
                            <?php
                            echo $total_bids . " ";
                            echo ($total_bids == 1) ? __("Bid", "wdm-ultimate-auction") : __("Bids", "wdm-ultimate-auction");
                            ?>
                            </h2>
                        </a>
                    </div>
                </div>
                <div class="product-details-timer">
                    <div class="timer-code"><span class="Ending-text"><?php printf(__("Ending in: %s", "wdm-ultimate-auction"), '</span><span class="wdm-single-auction-ending">' . $rem_tm_live . '</span>'); ?></div>

                    <?php 
                    $wdm_end_time = get_post_meta($wdm_auction->ID, 'wdm_listing_ends', true);
                    $wdm_time_zone = get_option('wdm_time_zone');

                    if (get_option('wdm_show_enddate_msg') == "Yes") { ?>

                        <div class="Gen-bold-text"><strong>Ending On:</strong>

                            <?php echo  date_i18n( get_option( 'date_format' ),  strtotime( $wdm_end_time ));  ?>  
                            <?php echo  date_i18n( get_option( 'time_format' ),  strtotime( $wdm_end_time ));  ?> 

                        </div>
                    <?php
                    }

                    if (get_option('wdm_show_timezone_msg') == "Yes") {
                    ?> 

                        <div class="Gen-bold-text"><strong>Timezone:</strong><?php echo $wdm_time_zone; ?></div>

                    <?php 
                    }

                ?>

                </div>
                <div class="bid-form-code">

                    <?php

                    if (is_user_logged_in()) {
                        //$curr_user = wp_get_current_user();
                        //$auction_bidder_name = $curr_user->user_login;
                        //$auction_bidder_email = $curr_user->user_email;

                         if ($curr_user->ID != $wdm_auction->post_author) {
                            ?>
                            <?php
                            $curr_auc_id = esc_attr($_GET['ult_auc_id']);

                           /* if(isset($_SERVER['HTTP_REFERER'])){
                                parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $queries);
                            }*/

                            if(isset($_SERVER['HTTP_REFERER'])){

                                $wdm_parse_url = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);
                                if (!is_null($wdm_parse_url)) {
                                    parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $queries);
                                }
                                
                            }
                            
                            $wdm_redirect_url = (isset($queries['redirect_to'])) ? $queries['redirect_to'] : '';
                            $str = "ult_auc_id=$curr_auc_id";

                            $curr_bid_val = "";

                            $pos = strpos($wdm_redirect_url, $str);
                            if ($pos !== false) {

                                if (isset($queries['wdm-bid-val'])) {
                                    $bid_val = $queries['wdm-bid-val'];
                                    if (!empty($bid_val)) {
                                        $curr_bid_val = $bid_val;
                                    }
                                }
                               
                            } else {
                                $curr_bid_val = "";
                            }
                            ?>
                            <form action="<?php echo dirname(__FILE__); ?>">
                                <div class="form_group_row">
                                        <label for="wdm-bidder-bidval"><?php _e("Bid Value", "wdm-ultimate-auction"); ?>: </label>
                                        <div class="bid_input">
                                            <input type="text" id="wdm-bidder-bidval" placeholder="<?php printf(__("in %s", "wdm-ultimate-auction"), $currency_symbol . $currency_code_display); ?>" value="<?php echo $curr_bid_val ?>" />
                                            <input type="submit" value="<?php echo apply_filters(       'wdm_ultimate_auction_bid_button_text', __( "Place Bid", "wdm-ultimate-auction" )); ?>" id="wdm-place-bid-now" />
                                                
                                        </div>
                                        <div class="form-group-row">
                                            <small>(<?php printf(__("Enter %.2f or more", "wdm-ultimate-auction"), $inc_price); ?>)
                                                <?php
                                                $ehtml = '';
                                                $ehtml = apply_filters('wdm_ua_text_after_bid_form', $ehtml, $wdm_auction->ID);
                                                echo $ehtml;
                                                ?>
                                            </small>
                                        </div>
                                </div>
                            </form>
                            <?php
                            require_once('ajax-actions/place-bid.php'); //file to handle ajax requests related to bid placing form
                            
                            }

                        } else {

                            $check = get_option('wdm_users_login');
                            if ($check == 'without_login') {
                                $auction_bidder_name = '';
                                $auction_bidder_email = '';
                                ?>
                                 <div class="form-group-block">
                                    <div class="form-group-block-one">
                                        <label for="wdm-bidder-name"><?php _e("Name", "wdm-ultimate-auction"); ?>: </label>
                                        <input type="text" id="wdm-bidder-name" name="wdm-bidder-name" />
                                    </div>

                                    <div class="form-group-block-two">
                                        <label for="wdm-bidder-email"><?php _e("Email", "wdm-ultimate-auction"); ?>:  </label>
                                        <input type="text" id="wdm-bidder-email" name="wdm-bidder-email" />
                                    </div>
                                </div>
                                <div class="form-group-block">
                                    <label for="wdm-bidder-bidval"><?php _e("Bid Value", "wdm-ultimate-auction"); ?>: </label>
                                    <div class="form-group-block-two">
                                     <input type="text" id="wdm-bidder-bidval" placeholder="<?php printf(__("in %s", "wdm-ultimate-auction"), $currency_symbol . $currency_code_display); ?>" class="wdm-align-left wdmua-singleauc-input wdmua-singleauc-input-alt"/>
                                        <div class="Enter-more-text">
                                            <small>(<?php printf(__("Enter %.2f or more", "wdm-ultimate-auction"), $inc_price); ?>)
                                                <?php
                                $ehtml = '';
                                $ehtml = apply_filters('wdm_ua_text_after_bid_form', $ehtml, $wdm_auction->ID);
                                echo $ehtml;
                                                ?>

                                            </small>
                                        </div>
                                    </div>
                                    <div class="form-group-block-one">
                                       <input type="submit" value="<?php echo apply_filters(       'wdm_ultimate_auction_bid_button_text', __( "Place Bid", "wdm-ultimate-auction" )); ?>" id="wdm-place-bid-now" />    
                                    </div>
                                </div>
                           

                                <?php
                            } else {

                                if (!is_user_logged_in()) {

                                    $auction_bidder_name = '';
                                    $auction_bidder_email = '';

                                }
                                    
                                ?>
                               
                                <div class="form-group-block">
                                   
                                    <label for="wdm-bidder-bidval"><?php _e("Bid Value", "wdm-ultimate-auction"); ?>: </label>
                                    <div class="form-group-block-two">
                                        <input type="text" id="wdm-bidder-bidval" placeholder="<?php printf(__("in %s", "wdm-ultimate-auction"), $currency_symbol . $currency_code_display); ?>"/>
                                            <div class="Enter-more-text">
                                                <small>(<?php printf(__("Enter %.2f or more", "wdm-ultimate-auction"), $inc_price); ?>)</small>
                                            </div>
                                    </div>
                                   
                                    <div class="form-group-block-one">
                                       
                                        <input type="submit" data-login-url="<?php echo $wdm_login_url; ?>" value="<?php echo apply_filters(       'wdm_ultimate_auction_bid_button_text', __( "Place Bid", "wdm-ultimate-auction" )); ?>" class="wdm-login-to-place-bid" />
                                    </div>
                                   
                                </div>

                                <?php
                            }
                            require_once('ajax-actions/place-bid.php');
                        }
                        ?>

                </div>

                   <!--  <h3>Bid Value:</h3>
                    <div class="bid-input-and-btn">
                        <input type="number">
                        <button type="submit"></button>
                        <p>(Enter 45.00 or more)</p>
                    </div> -->
                 
                <?php } 

                if (!empty($to_buy) || $to_buy > 0) {
                        $a_key = get_post_meta($wdm_auction->ID, 'wdm-auth-key', true);

                        $acc_mode = get_option('wdm_account_mode');

                        if ($acc_mode == 'Sandbox')
                            $pp_link = "https://sandbox.paypal.com/cgi-bin/webscr";
                        else
                            $pp_link = "https://www.paypal.com/cgi-bin/webscr";
                        if (is_user_logged_in()) {

                            $check_method = get_post_meta($wdm_auction->ID, 'wdm_payment_method', true);

                            if ($check_method == 'method_paypal') {
                                ?>
                                <!--buy now button-->
                                <div id="wdm_buy_now_section">
                                    <?php if ($curr_user->ID != $wdm_auction->post_author) { ?>
                                        <div id="wdm-buy-line-above">
                                            <form action="<?php echo $pp_link; ?>" method="post" target="_top">
                                                <input type="hidden" name="cmd" value="_xclick">
                                                <input type="hidden" name="charset" value="utf-8" />
                                                <input type="hidden" name="business" value="<?php echo get_option('wdm_paypal_address'); ?>">
                                                <!--<input type="hidden" name="lc" value="US">-->
                                                <input type="hidden" name="item_name" value="<?php echo $wdm_auction->post_title; ?>">
                                                <input type="hidden" name="amount" value="<?php echo $buy_now_price; ?>">
                                                <?php
                                                $shipping_field = '';
                                                echo apply_filters('ua_product_shipping_cost_field', $shipping_field, $wdm_auction->ID);
                                                ?>
                                                <input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>">
                                                <input type="hidden" name="return" value="<?php echo get_permalink() . $set_char . "ult_auc_id=" . $wdm_auction->ID; ?>">
                                                <input type="hidden" name="button_subtype" value="services">
                                                <input type="hidden" name="no_note" value="0">
                                                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
                                                <input type="submit" value="<?php echo sprintf(__("Buy it now for %s%s %s", "wdm-ultimate-auction"), $currency_symbol, number_format($buy_now_price, 2, '.', ','), $currency_code_display); ?>" id="wdm-buy-now-button">
                                            </form>
                                        </div>
                                    <?php }
                                    ?>
                                </div> <!--wdm_buy_now_section ends here-->

                                <script type="text/javascript">
                                    jQuery(document).ready(function () {
                                        jQuery("#wdm_buy_now_section form").click(function () {
                                            var cur_val = jQuery("#wdm_buy_now_section form input[name='return']").val();
                                            jQuery("#wdm_buy_now_section form input[name='return']").val(cur_val + "&wdm=" + "<?php echo $a_key; ?>");
                                        });

                                    });
                                </script>
                                <?php
                            } else {
                                if ($check_method === 'method_wire_transfer') {
                                    $mthd = __("Wire Transfer", "wdm-ultimate-auction");
                                } elseif ($check_method === 'method_mailing') {
                                    $mthd = __("Cheque", "wdm-ultimate-auction");
                                } elseif ($check_method === 'method_cash') {
                                    $mthd = __("Cash", "wdm-ultimate-auction");
                                }

                                $bn_text = sprintf(__("Buy it now for %s%s %s", "wdm-ultimate-auction"), 
                                    $currency_symbol, number_format($buy_now_price, 2, '.', ','), 
                                    $currency_code_display);

                                $shipAmt = 0;
                                $shipAmt = apply_filters('ua_shipping_data_invoice', $shipAmt, $wdm_auction->ID, $auction_bidder_email);

                                if ($shipAmt > 0) {
                                    $bn_text = sprintf(__("Buy it now for %s%s %s + %s%s %s(shipping)", "wdm-ultimate-auction"), $currency_symbol, number_format($buy_now_price, 2, '.', ','), $currency_code_display, $currency_symbol, number_format($shipAmt, 2, '.', ','), $currency_code_display);
                                }
                                ?>
                                <div id="wdm_buy_now_section" class="clearfix">
                                    <?php if ($curr_user->ID != $wdm_auction->post_author) { ?>
                                        <div id="wdm-buy-line-above" class="clearfix">
                                            <form action="<?php echo add_query_arg(array('mt' => 'bn', 'wdm' => $a_key), get_permalink() . $set_char . "ult_auc_id=" . $wdm_auction->ID); ?>" method="post">
                                                <input type="submit" value="<?php echo $bn_text; ?>" id="wdm-buy-now-button">
                                            </form>
                                        </div>
                                    <?php } ?>
                                </div>

                                <script type="text/javascript">
                                    jQuery(document).ready(function ($) {
                                        $("#wdm-buy-now-button").click(function () {
                                            var bcnf = confirm('<?php printf(__("You need to pay %s %.2f amount via %s to %s. If you choose OK, you will receive an email with payment details and auction will expire. Choose Cancel to ignore this buy now transaction.", "wdm-ultimate-auction"), $currency_code, $buy_now_price + $shipAmt, $mthd, $auction_author->user_login); ?>');
                                            if (bcnf == true) {
                                                return true;
                                            }
                                            return false;
                                        });
                                    });
                                </script>
                               
                <?php
                            }
                        } else {
                            ?>
                            <div id="wdm_buy_now_section">
                                <div id="wdm-buy-line-above">
                                   <input type="submit" data-login-url="<?php echo $wdm_login_url; ?>" value="<?php echo sprintf(__("Buy it now for %s%s %s", "wdm-ultimate-auction"), $currency_symbol, number_format($buy_now_price, 2, '.', ','), $currency_code_display); ?>" class="wdm-login-to-buy-now">

                                </div>
                            </div>
                            <?php
                        }
                    }

                    if (is_user_logged_in() && $curr_user->ID == $wdm_auction->post_author) {
                        echo "<span class='wdmua-loggedin-error'>" . __("Sorry, you can not bid on your own item.", "wdm-ultimate-auction") . "</span>";
                    }

                    do_action('wdm_ua_ship_short_link', $wdm_auction->ID);

                }

                ?>

            </div>
            </div>

        </div>
    </div>
    
