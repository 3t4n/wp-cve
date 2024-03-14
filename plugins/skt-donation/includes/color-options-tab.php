<?php
  function sktdonation_color_optiontab(){
?>
<div id="skt-donations-tab-2" class="skt-donations-tab-content <?php if ( esc_attr(get_option('skt_donation_active_tab') == 'tab2' ) ) { ?> skt-donations-current <?php } ?>">
  <table class="skt-donations-form">
    <!--PayPal Form Start Here-->
    <tr>
      <td><h3><?php esc_attr_e('Default Gateway:','skt-donation');?></h3></td>
      <td> 
        <select name="skt_donation_default_gateway" id="skt_payment_gateway_id">
          <?php if ( esc_attr(get_option('skt_donation_paypal_active_show') == 'false' ) ) { ?>
            <option value="payPal" <?php if ( esc_attr(get_option('skt_donation_default_gateway') == 'payPal' ) ) { ?> selected <?php } ?>><?php esc_attr_e('PayPal','skt-donation');?></option>
          <?php }?>
          <?php if ( esc_attr(get_option('skt_donation_paypalexp_active_show') == 'false' ) ) { ?>
            <option value="paypalexp" <?php if ( esc_attr(get_option('skt_donation_default_gateway') == 'paypalexp' ) ) { ?> selected <?php } ?>><?php esc_attr_e('PayPal Chekout Express','skt-donation');?></option>
          <?php }?>
        </select>
      </td>
    </tr>
    <tr>
      <td><h3><?php esc_attr_e('Donation Amount :','skt-donation');?></h3></td>
      <td> 
        <input type="text" name="skt_donation_amount_in_usd" value="<?php echo esc_attr( get_option('skt_donation_amount_in_usd') ); ?>">
      </td>
    </tr>
    </table>
    <div class="skt-donation-accordion">
    <div class="skt-donation-accordion-tab">
      <input type="checkbox" class="skt_activate" id="accordion-1" name="accordion-1">
      <label for="accordion-1" class="skt-donation-accordion-title"><?php esc_attr_e('PayPal','skt-donation');?></label>
      <div class="skt-donation-accordion-content">
          <div class="skt_donation_payment_gateway">
            <span> <?php esc_attr_e('PayPal Activate/Deactivate :','skt-donation');?> </span>
            <span> 
              <input id="radio_paypal" type="radio" name="skt_donation_paypal_active_show" value="true" <?php if ( esc_attr(get_option('skt_donation_paypal_active_show') == 'true' ) || esc_attr(get_option('skt_donation_paypal_active_show') == '' ) ) { ?> checked <?php } ?>><?php esc_attr_e('Deactivate','skt-donation');?>
              <input type="radio" name="skt_donation_paypal_active_show" value="false"  <?php if ( esc_attr(get_option('skt_donation_paypal_active_show') == 'false' ) ) { ?> checked <?php } ?>><?php esc_attr_e('Activate','skt-donation');?>
            </span>
          </div>
          <div class="skt_donation_payment_gateway">
            <span><?php esc_attr_e('Mode:','skt-donation');?></span>
            <span>
              <select id="paypay_event_radio_paypal" name="skt_donation_paypal_mode_zero_one">
                <option <?php if ( esc_attr(get_option('skt_donation_paypal_mode_zero_one') == 'true' ) ) { ?> selected <?php } ?> value="true"><?php esc_attr_e('Sandbox','skt-donation');?></option>

                <option <?php if ( esc_attr(get_option('skt_donation_paypal_mode_zero_one') == 'false' ) ) { ?> selected <?php } ?> value="false"><?php esc_attr_e('Live','skt-donation');?></option>

              </select>
            </span>
          </div>
          <div id="skt_change_paypal_one">
            <div class="skt_donation_payment_gateway">
              <span><?php esc_attr_e('Sandbox API:','skt-donation');?> </span>
              <span><input type="text" name="skt_donation_paypal_test_api" placeholder="<?php esc_attr_e('Enter Sandbox API','skt-donation');?>" value="<?php echo esc_attr( get_option('skt_donation_paypal_test_api') ); ?>" /> </span>
            </div>
            <div>
              <span><?php esc_attr_e('Sandbox PayPal Business Email:','skt-donation');?></span>
              <span><input type="text" name="skt_donation_test_paypal_business_email" placeholder="<?php esc_attr_e('Enter Sandbox PayPal Business Email','skt-donation');?>" value="<?php echo esc_attr( get_option('skt_donation_test_paypal_business_email') ); ?>" /> </span>
            </div>
             <a href="<?php echo esc_url('https://www.sandbox.paypal.com/us/signin');?>" class="button" target="blank"><?php esc_attr_e('Click Here To Generate Paypal Sandbox API Key','skt-donation');?></a>
          </div>
          <div id="skt_change_paypal_two">
            <div class="skt_donation_payment_gateway">
              <span class="skt_donation_payment_gateway_passage"><?php esc_attr_e('Live API:','skt-donation');?></span>
              <span class="skt_donation_payment_gateway_input">
                <input type="text" name="skt_donation_paypal_live_api" placeholder="<?php esc_attr_e('Enter Live API','skt-donation');?>" value="<?php echo esc_attr( get_option('skt_donation_paypal_live_api') ); ?>" /> </span>
            </div>
            <div class="skt_donation_payment_gateway">
              <span class="skt_donation_payment_gateway_passage"><?php esc_attr_e('Live PayPal Business Email:','skt-donation');?></span>
              <span class="skt_donation_payment_gateway_input"><input type="text" name="skt_donation_live_paypal_business_email" placeholder="<?php esc_attr_e('Enter Live PayPal Business Email','skt-donation');?>" value="<?php echo esc_attr( get_option('skt_donation_live_paypal_business_email') ); ?>" /> </span>
            </div>
            <a href="<?php echo esc_url('https://www.paypal.com/signin?returnUri=https%3A%2F%2Fdeveloper.paypal.com%2Fdeveloper%2Fapplications');?>" class="button" target="blank"><?php esc_attr_e('Click Here To Generate Paypal Live API Key','skt-donation');?></a>
          </div>
      </div>
    </div>
    </div>
    <!--PayPal Form End Here-->

    <!------PAYPAL CHECKOUT EXPRESS------>

    <div class="skt-donation-accordion-tab">
      <input type="checkbox" class="skt_activate" id="accordion-2" name="accordion-2">
      <label for="accordion-2" class="skt-donation-accordion-title"><?php esc_attr_e('PayPal Checkout Express','skt-donation');?></label>
      <div class="skt-donation-accordion-content">
          <div class="skt_donation_payment_gateway">
            <span> <?php esc_attr_e('PayPal Activate/Deactivate :','skt-donation');?> </span>
            <span> 
              <input id="radio_paypalexp" type="radio" name="skt_donation_paypalexp_active_show" value="true" <?php if ( esc_attr(get_option('skt_donation_paypalexp_active_show') == 'true' ) || esc_attr(get_option('skt_donation_paypalexp_active_show') == '' ) ) { ?> checked <?php } ?>><?php esc_attr_e('Deactivate','skt-donation');?>
              <input type="radio" name="skt_donation_paypalexp_active_show" value="false"  <?php if ( esc_attr(get_option('skt_donation_paypalexp_active_show') == 'false' ) ) { ?> checked <?php } ?>><?php esc_attr_e('Activate','skt-donation');?>
            </span>
          </div>

          <div class="skt_donation_payment_gateway">
            <span><?php esc_attr_e('Price is per:','skt-donation');?></span>
            <span>
              <select name="skt_donation_priceper">
                <option <?php if ( esc_attr(get_option('skt_donation_priceper') == 'day' ) ) { ?> selected <?php } ?> value="day"><?php esc_attr_e('Day','skt-donation');?></option>
                <option <?php if ( esc_attr(get_option('skt_donation_priceper') == 'week' ) ) { ?> selected <?php } ?> value="week"><?php esc_attr_e('Week','skt-donation');?></option>
                <option <?php if ( esc_attr(get_option('skt_donation_priceper') == 'month' ) ) { ?> selected <?php } ?> value="month"><?php esc_attr_e('Month','skt-donation');?></option>
                <option <?php if ( esc_attr(get_option('skt_donation_priceper') == 'year' ) ) { ?> selected <?php } ?> value="year"><?php esc_attr_e('Year','skt-donation');?></option>
              </select>
            </span>
          </div>


          <div class="skt_donation_payment_gateway">
            <span><?php esc_attr_e('Mode:','skt-donation');?></span>
            <span>
              <select id="paypalexp_event_radio_paypal" name="skt_donation_paypalexp_mode_zero_one">
                <option <?php if ( esc_attr(get_option('skt_donation_paypalexp_mode_zero_one') == 'true' ) ) { ?> selected <?php } ?> value="true"><?php esc_attr_e('Sandbox','skt-donation');?></option>
                <option <?php if ( esc_attr(get_option('skt_donation_paypalexp_mode_zero_one') == 'false' ) ) { ?> selected <?php } ?> value="false"><?php esc_attr_e('Live','skt-donation');?></option>

              </select>
            </span>
          </div>
          <div id="skt_change_paypalexp_one">
            <div class="skt_donation_payment_gateway">
              <span><?php esc_attr_e('Sandbox API(Client Id):','skt-donation');?> </span>
              <span><input type="text" name="skt_donation_paypalexp_test_api" placeholder="<?php esc_attr_e('Enter Sandbox API','skt-donation');?>" value="<?php echo esc_attr( get_option('skt_donation_paypalexp_test_api') ); ?>" /> </span>
            </div>

            <div class="skt_donation_payment_gateway">
              <span><?php esc_attr_e('Sandbox Secret Key:','skt-donation');?> </span>
              <span><input type="text" name="skt_donation_paypalexp_secretkey" placeholder="<?php esc_attr_e('Enter Sandbox Secret Key','skt-donation');?>" value="<?php echo esc_attr( get_option('skt_donation_paypalexp_secretkey') ); ?>" /> </span>
            </div>

            <div>
              <span><?php esc_attr_e('Sandbox PayPal Business Email:','skt-donation');?></span>
              <span><input type="text" name="skt_donation_test_paypalexp_business_email" placeholder="<?php esc_attr_e('Enter Sandbox PayPal Business Email','skt-donation');?>" value="<?php echo esc_attr( get_option('skt_donation_test_paypalexp_business_email') ); ?>" /> </span>
            </div>
             <a href="<?php echo esc_url('https://www.sandbox.paypal.com/us/signin');?>" class="button" target="blank"><?php esc_attr_e('Click Here To Generate Paypal Sandbox API Key','skt-donation');?></a>
          </div>
          <div id="skt_change_paypalexp_two">
            <div class="skt_donation_payment_gateway">
              <span class="skt_donation_payment_gateway_passage"><?php esc_attr_e('Live API:','skt-donation');?></span>
              <span class="skt_donation_payment_gateway_input">
                <input type="text" name="skt_donation_paypalexp_live_api" placeholder="<?php esc_attr_e('Enter Live API(Client Id)','skt-donation');?>" value="<?php echo esc_attr( get_option('skt_donation_paypalexp_live_api') ); ?>" /> </span>
            </div>

            <div class="skt_donation_payment_gateway">
              <span><?php esc_attr_e('Live Secret Key:','skt-donation');?> </span>
              <span><input type="text" name="skt_donation_paypalexpIlive_secretkey" placeholder="<?php esc_attr_e('Enter Live Secret Key','skt-donation');?>" value="<?php echo esc_attr( get_option('skt_donation_paypalexpIlive_secretkey') ); ?>" /> </span>
            </div>

            <div class="skt_donation_payment_gateway">
              <span class="skt_donation_payment_gateway_passage"><?php esc_attr_e('Live PayPal Business Email:','skt-donation');?></span>
              <span class="skt_donation_payment_gateway_input"><input type="text" name="skt_donation_live_paypalexp_business_email" placeholder="<?php esc_attr_e('Enter Live PayPal Business Email','skt-donation');?>" value="<?php echo esc_attr( get_option('skt_donation_live_paypalexp_business_email') ); ?>" /> </span>
            </div>
            <a href="<?php echo esc_url('https://www.paypal.com/signin?returnUri=https%3A%2F%2Fdeveloper.paypal.com%2Fdeveloper%2Fapplications');?>" class="button" target="blank"><?php esc_attr_e('Click Here To Generate Paypal Live API Key','skt-donation');?></a>
          </div>
      </div>
    </div>
</div>
<script type="text/javascript">
  jQuery(document).ready(function() {
    var get_paypal_selected = "<?php echo esc_attr(get_option('skt_donation_paypal_mode_zero_one'));?>";
    if(get_paypal_selected=='true'){
      jQuery("#skt_change_paypal_two").hide();
      jQuery("#skt_change_paypal_one").show();
    }else{
      jQuery("#skt_change_paypal_one").hide();
      jQuery("#skt_change_paypal_two").show();
    }
    jQuery('#paypay_event_radio_paypal').on('change', function() {
      if (this.value === 'true') {
        jQuery("#skt_change_paypal_two").hide();
        jQuery("#skt_change_paypal_one").show();
      } else if (this.value === 'false') {
        jQuery("#skt_change_paypal_one").hide();
        jQuery("#skt_change_paypal_two").show();
      }
    });
  

  var get_paypalexp_selected = "<?php echo esc_attr(get_option('skt_donation_paypalexp_mode_zero_one'));?>";
  if(get_paypalexp_selected=='true' || get_paypalexp_selected==''){
      jQuery("#skt_change_paypalexp_two").hide();
      jQuery("#skt_change_paypalexp_one").show();
    }else{
      jQuery("#skt_change_paypalexp_one").hide();
      jQuery("#skt_change_paypalexp_two").show();
    }
    jQuery('#paypalexp_event_radio_paypal').on('change', function() {
      if (this.value === 'true') {
        jQuery("#skt_change_paypalexp_two").hide();
        jQuery("#skt_change_paypalexp_one").show();
      } else if (this.value === 'false') {
        jQuery("#skt_change_paypalexp_one").hide();
        jQuery("#skt_change_paypalexp_two").show();
      }
    });
  });

  jQuery(document).ready(function(){
    var payment_gateway_id = document.getElementById("skt_payment_gateway_id");
    var payment_gateway_id_value = payment_gateway_id.options[payment_gateway_id.selectedIndex].value;
    jQuery("#radio_paypal").click(function(){
      var check_paypal = "payPal";
      if(payment_gateway_id_value==check_paypal){
        alert("Please Change the default payment gateway");
      }
    });
  });
</script>
<?php } 
  $sktdonation_color_optiontab =sktdonation_color_optiontab();
?>