<?php
if ( ! function_exists ( 'sktdonation_shortcode_tab' ) ) {
  function sktdonation_shortcode_tab(){
?>
<div id="skt-donations-tab-4" class="skt-donations-tab-content <?php if ( esc_attr(get_option('skt_donation_active_tab') == 'tab4' ) ) { ?> skt-donations-current <?php } ?>">
	<div class="skt_donation_stripe_form_setting">
		<div class="stripe_card_detail">
			<h3><?php esc_attr_e('Placeholder Settings / Manage Form Field Placeholder [For PayPal]','skt-donation');?></h3>
		</div>
		<label><?php esc_attr_e('First Name','skt-donation');?></label>
		<input type="text" name="skt_donation_stripe_first_name" value="<?php echo esc_attr( get_option('skt_donation_stripe_first_name') ); ?>">
		<label><?php esc_attr_e('Last Name','skt-donation');?></label>
		<input type="text" name="skt_donation_stripe_last_name" value="<?php echo esc_attr( get_option('skt_donation_stripe_last_name') ); ?>">
		<label><?php esc_attr_e('Email','skt-donation');?></label>
		<input type="text" name="skt_donation_stripe_email" value="<?php echo esc_attr( get_option('skt_donation_stripe_email') ); ?>">
		<label><?php esc_attr_e('Phone','skt-donation');?></label>
		<input type="text" name="skt_donation_stripe_phone_name" value="<?php echo esc_attr( get_option('skt_donation_stripe_phone_name') ); ?>">
		<label><?php esc_attr_e('Amount','skt-donation');?></label>
		<input type="text" name="skt_donation_stripe_amount" value="<?php echo esc_attr( get_option('skt_donation_stripe_amount') ); ?>">
		<label><?php esc_attr_e('Type Payment','skt-donation');?></label>
		<input type="text" name="skt_donation_stripe_normal_payment" value="<?php echo esc_attr( get_option('skt_donation_stripe_normal_payment') ); ?>">
		<input type="text" name="skt_donation_stripe_subscription_payment" value="<?php echo esc_attr( get_option('skt_donation_stripe_subscription_payment') ); ?>">
		<label><?php esc_attr_e('Card Number','skt-donation');?></label>
		<input type="text" name="skt_donation_stripe_card_no" value="<?php echo esc_attr( get_option('skt_donation_stripe_card_no') ); ?>">
	</div>
	<div class="skt_donation_stripe_form_setting">
		<div class="stripe_card_detail">
			<h3><?php esc_attr_e('Label Settings / Manage Form Field Label [For PayPal]','skt-donation');?></h3>
		</div>
		<label><?php esc_attr_e('First Name','skt-donation');?></label>
		<input type="text" name="skt_donation_stripe_first_name_lable" value="<?php echo esc_attr( get_option('skt_donation_stripe_first_name_lable') ); ?>">
		<label><?php esc_attr_e('Last Name','skt-donation');?></label>
		<input type="text" name="skt_donation_stripe_last_name_lable" value="<?php echo esc_attr( get_option('skt_donation_stripe_last_name_lable') ); ?>">
		<label><?php esc_attr_e('Email','skt-donation');?></label>
		<input type="text" name="skt_donation_stripe_email_lable" value="<?php echo esc_attr( get_option('skt_donation_stripe_email_lable') ); ?>">
		<label><?php esc_attr_e('Phone','skt-donation');?></label>
		<input type="text" name="skt_donation_stripe_phone_name_lable" value="<?php echo esc_attr( get_option('skt_donation_stripe_phone_name_lable') ); ?>">

		<label><?php esc_attr_e('Amount','skt-donation');?></label>
		<input type="text" name="skt_donation_stripe_amount_lable" value="<?php echo esc_attr( get_option('skt_donation_stripe_amount_lable') ); ?>">
		<label><?php esc_attr_e('Type Payment','skt-donation');?></label>
		<input type="text" name="skt_donation_stripe_type_of_payment_label" value="<?php echo esc_attr( get_option('skt_donation_stripe_type_of_payment_label') ); ?>">
		<label><?php esc_attr_e('Card Number','skt-donation');?></label>
		<input type="text" name="skt_donation_stripe_card_no_lable" value="<?php echo esc_attr( get_option('skt_donation_stripe_card_no_lable') ); ?>">
	</div>
	<div class="skt-donations-radio">
	    <span><?php esc_attr_e('Daily :','skt-donation');?></span>
	  	<label class="skt_radio_inline">
	      <input type="radio" name="skt_donation_day_show" value="false" <?php if ( esc_attr(get_option('skt_donation_day_show') == 'false' ) ) { ?> checked <?php } ?>><?php esc_attr_e('No','skt-donation');?>
	    </label>
	    <label class="skt_radio_inline">
	      <input type="radio" name="skt_donation_day_show" value="true" <?php if ( esc_attr(get_option('skt_donation_day_show') == 'true' ) || esc_attr(get_option('skt_donation_day_show') == '') ) { ?> checked <?php } ?>><?php esc_attr_e('Yes','skt-donation');?>
	    </label></br></br>
	     <span><?php esc_attr_e('Weekly :','skt-donation');?></span>
	  	<label class="skt_radio_inline">
	      <input type="radio" name="skt_donation_week_show" value="false" <?php if ( esc_attr(get_option('skt_donation_week_show') == 'false' ) ) { ?> checked <?php } ?>><?php esc_attr_e('No','skt-donation');?>
	    </label>
	    <label class="skt_radio_inline">
	      <input type="radio" name="skt_donation_week_show" value="true" <?php if ( esc_attr(get_option('skt_donation_week_show') == 'true' ) || esc_attr(get_option('skt_donation_week_show') == '')) { ?> checked <?php } ?>><?php esc_attr_e('Yes','skt-donation');?>
	    </label></br><br>
	   <span><?php esc_attr_e('Monthly :','skt-donation');?></span>
	  	<label class="skt_radio_inline">
	      <input type="radio" name="skt_donation_month_show" value="false" <?php if ( esc_attr(get_option('skt_donation_month_show') == 'false' ) ) { ?> checked <?php } ?>><?php esc_attr_e('No','skt-donation');?>
	    </label>
	    <label class="skt_radio_inline">
	      <input type="radio" name="skt_donation_month_show" value="true" <?php if ( esc_attr(get_option('skt_donation_month_show') == 'true' ) || esc_attr(get_option('skt_donation_month_show') == '') ) { ?> checked <?php } ?>><?php esc_attr_e('Yes','skt-donation');?>
	    </label></br><br>
	   <span> <?php esc_attr_e('Quarterly :','skt-donation');?> </span>
	  	<label class="skt_radio_inline">
	      <input type="radio" name="skt_donation_quaterly_show" value="false" <?php if ( esc_attr(get_option('skt_donation_quaterly_show') == 'false' ) ) { ?> checked <?php } ?>><?php esc_attr_e('No','skt-donation');?>
	    </label>
	    <label class="skt_radio_inline">
	      <input type="radio" name="skt_donation_quaterly_show" value="true" <?php if ( esc_attr(get_option('skt_donation_quaterly_show') == 'true' ) || esc_attr(get_option('skt_donation_quaterly_show') == '') ) { ?> checked <?php } ?>><?php esc_attr_e('Yes');?>
	    </label></br></br>
	    <span><?php esc_attr_e('Semi-Annually :','skt-donation');?></span>
	  	<label class="skt_radio_inline">
	      <input type="radio" name="skt_donation_semiquaterly_show" value="false" <?php if ( esc_attr(get_option('skt_donation_semiquaterly_show') == 'false' ) ) { ?> checked <?php } ?>><?php esc_attr_e('No','skt-donation');?>
	    </label>
	    <label class="skt_radio_inline">
	      <input type="radio" name="skt_donation_semiquaterly_show" value="true" <?php if ( esc_attr(get_option('skt_donation_semiquaterly_show') == 'true' ) || esc_attr(get_option('skt_donation_semiquaterly_show') == '') ) { ?> checked <?php } ?>><?php esc_attr_e('Yes','skt-donation');?>
	    </label></br></br>
	    <span><?php esc_attr_e('Annually :','skt-donation');?></span>
	  	<label class="skt_radio_inline">
	      <input type="radio" name="skt_donation_annual_show" value="false" <?php if ( esc_attr(get_option('skt_donation_annual_show') == 'false' ) ) { ?> checked <?php } ?>><?php esc_attr_e('No','skt-donation');?>
	    </label>
	    <label class="skt_radio_inline">
	      <input type="radio" name="skt_donation_annual_show" value="true" <?php if ( esc_attr(get_option('skt_donation_annual_show') == 'true' || esc_attr(get_option('skt_donation_semiquaterly_show') == '') ) ) { ?> checked <?php } ?>><?php esc_attr_e('Yes','skt-donation');?>
	    </label></br></br>
	</div>
</div>
<?php } 
	$sktdonation_shortcode_tab = sktdonation_shortcode_tab();
}
?>