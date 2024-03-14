// JavaScript Document
jQuery(window).load(function(){
	jQuery(".preloader").fadeOut('slow');
});
jQuery(document).ready(function(){
	jQuery('.skt-donations-tabs li').click(function(){
		var tab_id = jQuery(this).attr('data-tab');

		jQuery('.skt-donations-tabs li').removeClass('skt-donations-current');
		jQuery('.skt-donations-tab-content').removeClass('skt-donations-current');

		jQuery(this).addClass('skt-donations-current');
		jQuery("#"+tab_id).addClass('skt-donations-current');
	});
});
// For PayPal Change event
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
  // For 2Checkout Change event
  var get_twocheck_selected = "<?php echo esc_attr(get_option('skt_donation_twocheck_mode_zero_one'));?>"
  if(get_twocheck_selected=='true'){
    jQuery("#skt_change_twocheck_two").hide();
    jQuery("#skt_change_twocheck_one").show();
  }else{
    jQuery("#skt_change_twocheck_one").hide();
    jQuery("#skt_change_twocheck_two").show();
  }
  jQuery('#skt_donation_twocheckout_change').on('change', function() {
    if (this.value === 'true') {
      jQuery("#skt_change_twocheck_two").hide();
      jQuery("#skt_change_twocheck_one").show();
    } else if (this.value === 'false') {
      jQuery("#skt_change_twocheck_one").hide();
      jQuery("#skt_change_twocheck_two").show();
    }
  });
});