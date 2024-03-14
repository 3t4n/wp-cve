(function( $ ) {
	'use strict';
//   $( "#pg-woocommerce_purchases" ).tabs(); 
//   $("#pg-woocommerce_reviews").tabs();
})( jQuery );
 
    
 function pg_view_woocommerce_order(orderid)
 {
    jQuery("#pg_woocommerce_order_details").html('<div class="pm-loader"></div>');
    var pmDomColor = pm_get_dom_color();
    jQuery(".pm-loader").css('border-top-color', pmDomColor);
    jQuery('#pm-show-woocommere-order-dialog').show();
    jQuery('.pm-popup-container').show();
    jQuery('#pm-show-woocommere-order-dialog .pm-popup-mask').toggle();
    params = {action: 'pg_woocommerce_get_order',order_id:orderid}
    jQuery.post(pm_ajax_object.ajax_url, params, function(response) {
                if(response)
                {
                    jQuery("#pg_woocommerce_order_details").html(response);
                }	
            });	
    
 }
  
 
 function pm_save_billing_address()
 {
     
        var email_val = "";
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	jQuery('#pg_billing_address_form .pg_billing_errors').html('');
	jQuery('#pg_billing_address_form .pg_billing_errors').hide();
	jQuery('#pg_billing_address_form .warning').removeClass('warning');

        jQuery('#pg_billing_address_form .validate-email input').each(function (index, element) {
		var email = jQuery(this).val();
		var isemail = regex.test(email);
		if (isemail == false && email != "") {
                        jQuery(".pg_billing_errors").addClass('warning');
			jQuery(".pg_billing_errors").html(pm_error_object.valid_email);
			jQuery(".pg_billing_errors").show();
		}
	});
        
     jQuery('#pg_billing_address_form .validate-required input').each(function (index, element) {
		var value = jQuery(this).val();
                var value = jQuery.trim(value);
		if (value == "") {
			jQuery(".pg_billing_errors").addClass('warning');
			jQuery(".pg_billing_errors").html(pm_error_object.required_comman_field);
			jQuery(".pg_billing_errors").show();
		}
	});
        
        jQuery('#pg_billing_address_form .validate-required select').each(function (index, element) {
		var value = jQuery(this).val();
                var value = jQuery.trim(value);
		if (value == "") {
			jQuery(".pg_billing_errors").addClass('warning');
			jQuery(".pg_billing_errors").html(pm_error_object.required_comman_field);
			jQuery(".pg_billing_errors").show();
		}
	});
        var error = jQuery('.pg_billing_errors').html();
	if (error == '') {
		return true;
	} else {
		return false;
	}
 }
 
 function pm_save_shipping_address()
 {
     
        var email_val = "";
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	jQuery('#pg_shipping_address_form .pg_shipping_errors').html('');
	jQuery('#pg_shipping_address_form .pg_shipping_errors').hide();
	jQuery('#pg_shipping_address_form .warning').removeClass('warning');

        jQuery('#pg_shipping_address_form .validate-email input').each(function (index, element) {
		var email = jQuery(this).val();
		var isemail = regex.test(email);
		if (isemail == false && email != "") {
                        jQuery(".pg_shipping_errors").addClass('warning');
			jQuery(".pg_shipping_errors").html(pm_error_object.valid_email);
			jQuery(".pg_shipping_errors").show();
		}
	});
        
     jQuery('#pg_shipping_address_form .validate-required input').each(function (index, element) {
		var value = jQuery(this).val();
                var value = jQuery.trim(value);
		if (value == "") {
			jQuery(".pg_shipping_errors").addClass('warning');
			jQuery(".pg_shipping_errors").html(pm_error_object.required_comman_field);
			jQuery(".pg_shipping_errors").show();
		}
	});
        
        jQuery('#pg_shipping_address_form .validate-required select').each(function (index, element) {
		var value = jQuery(this).val();
                var value = jQuery.trim(value);
		if (value == "") {
			jQuery(".pg_shipping_errors").addClass('warning');
			jQuery(".pg_shipping_errors").html(pm_error_object.required_comman_field);
			jQuery(".pg_shipping_errors").show();
		}
	});
        var error = jQuery('.pg_shipping_errors').html();
	if (error == '') {
		return true;
	} else {
		return false;
	}
 }