<div id="wdm-tab-anchor-id"></div>

<div class="wdm-tabs">
    <ul id="wdm-tabs-nav">
        <li><a href="#tab1"><?php _e("Description", "wdm-ultimate-auction"); ?></a></li>
        <?php do_action('wdm_ua_add_ship_tab', $wdm_auction->ID); ?>
        <?php if (get_option('wdm_show_prvt_msg') == "Yes") { ?>
            <li><a href="#tab2"><?php _e("Send Private Message", "wdm-ultimate-auction"); ?></a></li>
        <?php } ?>
        <li><a href="#tab3"><?php _e("Total bids placed", "wdm-ultimate-auction"); ?></a></li>
    </ul>
    <!-- END tabs-nav -->
    <div id="wdm-tabs-content">
      <div id="tab1" class="wdm-tab-content">
            <?php
              $ext_desc = "";
              $ext_desc = apply_filters('wdm_single_auction_extra_desc', $ext_desc);
              echo $ext_desc;
              echo apply_filters('the_content', $wdm_auction->post_content);
              ?>
      </div>

      <?php if (get_post_meta($wdm_auction->ID, 'wdm_enable_shipping', true) == "1") { ?>
        <div id="wdm-desc-ship-tab" class="auction-tab-container" style="display: none;overflow: hidden;">
            <div class="wdm-ship-info clearfix">
                <?php do_action('ua_add_shipping_cost_view_field', $wdm_auction->ID); //SHP-ADD hook to add new product data ?>
            </div>  
        </div>
      <?php } ?>

      <div id="tab2" class="wdm-tab-content">
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
          <?php require_once('ajax-actions/send-private-msg.php'); ?>
      </div>

      <div id="tab3" class="wdm-tab-content">
        <div id="#wdm-tab-anchor-id"></div>
        <table>
        <?php

          /*$query = "SELECT * FROM " . $wpdb->prefix . "wdm_bidders WHERE auction_id =" . $wdm_auction->ID . " ORDER BY id DESC";*/

          $table = $wpdb->prefix . "wdm_bidders";
          $auctionid = $wdm_auction->ID;

          $query = $wpdb->prepare("SELECT * FROM {$table} WHERE auction_id = %d ORDER BY id DESC", $auctionid);

          $results = $wpdb->get_results($query);
          if (!empty($results)) {
              ?>
              <tr>
                  <th><?php _e("Bidder Name", "wdm-ultimate-auction"); ?></th>
                  <th><?php _e("Bid Price", "wdm-ultimate-auction"); ?></th>
                  <th><?php _e("When", "wdm-ultimate-auction"); ?></th>
              </tr>
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
                  <tr>
                      <td>
                          <?php echo $result->name; ?> 
                      </td>
                      <td>
                          <?php echo $currency_symbol . number_format($result->bid, 2, '.', ',') . " " . $currency_code_display; ?>
                      </td>
                      <td>
                          <?php printf(__("%s ago", "wdm-ultimate-auction"), $ago_time); ?>
                      </td>
                  </tr>
                  <?php
              }
          }
        ?>
        </table>
      </div>
    </div> <!-- END tabs-content -->
</div> <!-- END tabs -->
</div>


 
<script type="text/javascript">
   document.addEventListener('DOMContentLoaded', function() {
    const tabsNavItems = document.querySelectorAll('#wdm-tabs-nav li');
    const tabContents = document.querySelectorAll('.wdm-tab-content');

    tabsNavItems[0].classList.add('active');
    tabContents.forEach(content => content.style.display = 'none');
    tabContents[0].style.display = 'block';

    tabsNavItems.forEach(tabNavItem => {
        tabNavItem.addEventListener('click', function() {
            tabsNavItems.forEach(item => item.classList.remove('active'));
            this.classList.add('active');
            tabContents.forEach(content => content.style.display = 'none');
            
            const activeTab = this.querySelector('a').getAttribute('href');
            document.querySelector(activeTab).style.display = 'block';
            
            return false;
        });
    });
  
});

document.querySelector("#wdm-total-bids-link").addEventListener("click", function() {
document.querySelector("#tab3").style.display = "block";
document.querySelector("#tab2").style.display = "none";
document.querySelector("#tab1").style.display = "none";
});

document.querySelector("#wdm-total-bids-link").addEventListener("click", function() {
document.querySelector("#wdm-tabs-nav li:last-child").classList.add("active");
document.querySelector("#wdm-tabs-nav li:first-child").classList.remove("active");
document.querySelector("#wdm-tabs-nav li:nth-child(2)").classList.remove("active");
});
	
</script>



