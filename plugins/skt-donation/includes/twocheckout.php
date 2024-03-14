<?php
function skt_donation_checkout_form(){
  $donation_amount = esc_attr( get_option('skt_donation_amount_in_usd'));
  include_once( SKT_DONATIONS_DIR .'/payment-method/twocheckout/lib/Twocheckout.php');
  if(esc_attr(get_option('skt_donation_twocheck_mode_zero_one') == 'true' )){
    $production_sandbox = "sandbox";
    $tc_sellerid = esc_attr(get_option('skt_donation_twocheck_sellerid_one'));
    $tc_username = esc_attr(get_option('skt_donation_twocheck_username'));
    $tc_password = esc_attr(get_option('skt_donation_twocheck_password'));
    $tc_publish_key = esc_attr(get_option('skt_donation_twocheck_test_publish_key'));
    $tc_private_key = esc_attr(get_option('skt_donation_twocheck_test_private_key'));
  }else{
    $production_sandbox = "production";
    $tc_sellerid = esc_attr(get_option('skt_donation_twocheck_sellerid_one'));
    $tc_username = esc_attr(get_option('skt_donation_twocheck_username'));
    $tc_password = esc_attr(get_option('skt_donation_twocheck_password'));
    $tc_publish_key = esc_attr(get_option('skt_donation_twocheck_live_publish_key'));
    $tc_private_key = esc_attr(get_option('skt_donation_twocheck_live_private_key')); 
  }
  global $post;
  $page_id = $post->ID;
  global $wpdb;
  $file = "";
  $curl = plugin_dir_url( $file ); 
  $plugin_directory = basename(dirname(__DIR__)); 
  $plugin_url = $curl.''.$plugin_directory;
  $wp_skt_choose_currency_tc = $wpdb->prefix . "skt_choose_currency_twocheckout";
  $wp_skt_choose_currency_twocheck = $wpdb->get_row("SELECT * FROM $wp_skt_choose_currency_tc WHERE id='1'");
  $get_choose_stripe_count = $wpdb->num_rows;
  if ($get_choose_stripe_count <= 0) {
    $for_twocheck_payment ="USD";
    $for_twocheck_sign ="&#36;";
  }else{
    $type_currency_id_twocheck = $wp_skt_choose_currency_twocheck->type_currency_id;
    $currency_symbol_id_twocheck = $wp_skt_choose_currency_twocheck->currency_symbol_id;
    $skt_country_type_currency = $wpdb->prefix . "skt_country_type_currency";
    $select_type_currency_twocheck = $wpdb->get_row("SELECT * FROM $skt_country_type_currency WHERE id='$type_currency_id_twocheck'");
    $for_twocheck_payment =  $select_type_currency_twocheck->currency_stripe;
    $for_twocheck_sign =  $select_type_currency_twocheck->currency_sign;
  }
?>
<div class="twocheckout_radio skt_donation_box skt_donation_twocheckout_box skt_donation_form">
<div class="twocheckout_form">
  <form id="skt_myCCFormcheck"  action="" method="post">
    <input name="token" type="hidden" value="" />
    <div class="input-wrapper amount-wrapper">
      <label><?php echo esc_attr( get_option('skt_donation_stripe_first_name_lable') ); ?></label>
      <input id="twocheckout_first_name" name="twocheckout_first_name" type="text" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_first_name') ); ?>" value="" required>
    </div>
    <div class="input-wrapper amount-wrapper">
      <label><?php echo esc_attr( get_option('skt_donation_stripe_last_name_lable') ); ?></label>
      <input id="twocheckout_last_name" name="twocheckout_last_name" type="text" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_last_name') ); ?>" value="" required>
    </div>
    <div class="input-wrapper amount-wrapper">
      <label><?php echo esc_attr( get_option('skt_donation_stripe_email_lable') ); ?></label>
      <input id="twocheckout_email" name="twocheckout_email" type="text" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_email') ); ?>" value="" required>
    </div>
    <div class="input-wrapper amount-wrapper">
      <label><?php echo esc_attr( get_option('skt_donation_stripe_phone_name_lable') ); ?></label>
      <input id="twocheckout_phone" name="twocheckout_phone" type="text" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_phone_name') ); ?>" value="" required>
    </div>
    <div class="input-wrapper amount-wrapper">
      <label><?php echo esc_attr( get_option('skt_donation_stripe_amount_lable') ); ?></label>
      <input type="text" name="donation_amount" id="skt_donation_amount3" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_amount') ); ?>" value="<?php echo $donation_amount;?>" required></br></br>
      <input type="hidden" name="payment_in_currency" value="<?php echo $for_twocheck_payment;?>">
    <input type="text" name="currency_sign" value="<?php echo $for_twocheck_sign;?>" readonly>
    </div>
     <div class="input-wrapper amount-wrapper">
      <label><?php echo esc_attr( get_option('skt_donation_twocheck_address_label') ); ?></label>
      <input id="twocheckout_address" name="twocheckout_address" type="text" placeholder="<?php echo esc_attr( get_option('skt_donation_twocheck_address') ); ?>" value="" required>
    </div>
    <div class="input-wrapper amount-wrapper">
      <label><?php echo esc_attr( get_option('skt_donation_twocheck_city_label') ); ?></label>
      <input id="twocheckout_city" name="twocheckout_city" type="text" placeholder="<?php echo esc_attr( get_option('skt_donation_twocheck_city') ); ?>" value=""required>
    </div>
    <div class="input-wrapper amount-wrapper">
      <label><?php echo esc_attr( get_option('skt_donation_twocheck_state_label') ); ?></label>
      <input id="twocheckout_state" name="twocheckout_state" type="text" placeholder="<?php echo esc_attr( get_option('skt_donation_twocheck_state') ); ?>" value="" required>
    </div>
    <div class="input-wrapper amount-wrapper">
      <label><?php echo esc_attr( get_option('skt_donation_twocheck_zipcode_label') ); ?></label>
      <input id="twocheckout_zipCode" name="twocheckout_zipCode" type="text" placeholder="<?php echo esc_attr( get_option('skt_donation_twocheck_zipcode') ); ?>" value="" required>
    </div>
     <div class="input-wrapper amount-wrapper">
      <label><?php echo esc_attr( get_option('skt_donation_twocheck_country_label') ); ?></label>
      <input id="twocheckout_country" name="twocheckout_country" type="text" placeholder="<?php echo esc_attr( get_option('skt_donation_twocheck_country') ); ?>" value="" required>
    </div>
    <label><?php echo esc_attr( get_option('skt_donation_stripe_type_of_payment_label') ); ?></label>
    <select name="twocheckout_normal_subscription" id="twocheckout_normal_subscription">
      <option value="normal"><?php echo esc_attr( get_option('skt_donation_stripe_normal_payment') ); ?></option>
      <option value="subcribe"><?php echo esc_attr( get_option('skt_donation_stripe_subscription_payment') ); ?></option>
    </select>
    <div id="div_subcription_twochekout">
      <select name="twocheckout_recurring" required>      
        <?php 
            if(get_option('skt_donation_week_show')=="true"){?>
              <option value="1 Week"><?php esc_attr_e('Weekly','skt-donation');?></option>
           <?php }
            if(get_option('skt_donation_month_show')=="true"){?>
              <option value="1 Month"><?php esc_attr_e('Monthly','skt-donation');?></option>
           <?php }
           if(get_option('skt_donation_quaterly_show')=="true"){?>
              <option value="6 Month"><?php esc_attr_e('Semi-Annually','skt-donation');?></option>
           <?php }
            if(get_option('skt_donation_annual_show')=="true"){?>
              <option value="1 Year"><?php esc_attr_e('Annually','skt-donation');?></option>
            <?php } 
        ?>
      </select>
    </div>
    <div>
        <label><?php echo esc_attr( get_option('skt_donation_stripe_card_no_lable') ); ?></label>
        <input id="skt_ccNocheck" type="text" value="" placeholder="<?php echo esc_attr( get_option('skt_donation_stripe_card_no') ); ?>" autocomplete="off" required />
    </div> 
    <div class="card_details">
        <span><input id="skt_expMonthcheck" type="text" placeholder="<?php esc_attr_e('Expiration Month (MM)','skt-donation');?>" size="2" required /></span>
        <span><input id="skt_expYearcheck" type="text" placeholder="<?php esc_attr_e('Expiration Year (YYYY)','skt-donation');?>" size="4" required /></span>
        <span><input id="skt_cvvcheck" type="text" value="" placeholder="<?php esc_attr_e('CVV','skt-donation');?>" autocomplete="off" required /></span>
        <?php wp_nonce_field( 'twocheckout_nonce', 'add_checkoutnonce' ); ?>
        <input type="hidden" name="mode" value="twocheckout"  />
        <input type="hidden" name="mode_checkout" value="mode_twocheckout"/>
        <input type="hidden" name="page_id" value="<?php echo esc_attr($page_id);?>"/>
    </div>
    <input type="submit" value="<?php esc_attr_e('Submit','skt-donation');?>" id="sktbtnsubmit" />
  </form>
  </div>
</div>
<script type="text/javascript">
  jQuery(document).ready(function() {
    jQuery("#div_subcription_twochekout").hide();
    jQuery("#twocheckout_normal_subscription").click(function(){
    var twocheckout_normal_subscription = jQuery('#twocheckout_normal_subscription').val();
      if(twocheckout_normal_subscription =="normal"){
          jQuery("#div_subcription_twochekout").hide(); 
        }else{
          jQuery("#div_subcription_twochekout").show();
        }
      });
  });
</script> 
<?php
  if($tc_sellerid !="" || $tc_username !="" || $tc_password !="" || $tc_publish_key !="" || $tc_private_key !="") {
?>
<script src="<?php echo esc_url('https://www.2checkout.com/checkout/api/2co.min.js');?>"></script>
<script>
    // Called when token created successfully.
    var successCallback = function(data) {
      var myForm = document.getElementById('skt_myCCFormcheck')
      // Set the token as the value for the token input
      myForm.token.value = data.response.token.token;
      // IMPORTANT: Here we call `submit()` on the form element directly instead of using jQuery to prevent and infinite token request loop.
      myForm.submit();
    };
    // Called when token creation fails.
    var errorCallback = function(data) {
      if (data.errorCode === 200) {
        tokenRequest();
      } else {
        alert(data.errorMsg);
      }
    };
    var tokenRequest = function() {
      // Setup token request arguments
      var args = {
          sellerId: "<?php echo esc_attr($tc_sellerid);?>",
          publishableKey: "<?php echo esc_attr($tc_publish_key);?>",
          ccNo: jQuery("#skt_ccNocheck").val(),
          cvv: jQuery("#skt_cvvcheck").val(),
          expMonth: jQuery("#skt_expMonthcheck").val(),
          expYear: jQuery("#skt_expYearcheck").val()
      };
      // Make the token request
      TCO.requestToken(successCallback, errorCallback, args);
    };
    jQuery(function() {
      TCO.loadPubKey('sandbox');
      jQuery("#skt_myCCFormcheck").submit(function(e) {
        // Call our token request function
        tokenRequest();
        // Prevent form from submitting
        return false;
      });
    });
</script>
<?php }
} 
skt_donation_checkout_form();
?>