jQuery(document).ready(function(){
		
	jQuery(document).on("click", ".cbr-country-widget", function(){	
		jQuery('.cbr-widget-popup').show();
	});
	jQuery(document).on("click", ".popup_close_icon, .popupclose", function(){
		jQuery('.cbr-widget-popup').hide();
		jQuery('.cbr-shortcode-popup').hide();
	});
	jQuery(document).on("click", ".cbr-country-shortcode", function(){	
		jQuery('.cbr-shortcode-popup').show();
	});
	jQuery(document).on("change", "#calc_shipping_country, #shipping_country", function(){	
		Cookies.set('country', jQuery(this).val());
		set_customer_country_on_checkout_without_reload();
	}); 

}); 

function setCountryCookie(cookieName, cookieValue, nDays) {
	var today = new Date();
	var expire = new Date();

	if (!nDays) 
		nDays=1;

	expire.setTime(today.getTime() + 3600000*24*nDays);
	document.cookie = cookieName+"="+escape(cookieValue) + ";path=/;expires="+expire.toGMTString();
	set_customer_country_on_checkout();
	
}

function set_customer_country_on_checkout(){
	"use strict";

	var country = jQuery(this).val();
	var data = {
		action: 'set_widget_country',
		country: country
	};		
	
	jQuery.ajax({
		url: cbr_ajax_object.cbr_ajax_url,
		data: data,
		type: 'POST',
		dataType:"json",	
		success: function(response) {
			jQuery("#wp-admin-bar-cbr_item a.ab-item").text("CBR Country: "+response.country);
			jQuery(".display-country-for-customer .country, .widget-country, .select-country-dropdown").val(response.countrycode);
			jQuery(".cbr_shipping_country").text(response.country);	
			location.reload();	
		},
		error: function(response) {
			console.log(response);			
		}
	});
	return false;
}

function setCookie(cookieName, cookieValue, nDays) {
	var today = new Date();
	var expire = new Date();

	if (!nDays) 
		nDays=1;

	expire.setTime(today.getTime() + 3600000*24*nDays);
	document.cookie = cookieName+"="+escape(cookieValue) + ";path=/;expires="+expire.toGMTString();
	set_customer_country_on_checkout_without_reload();
	
}

function set_customer_country_on_checkout_without_reload(){
	"use strict";

	var country = jQuery(this).val();
	var data = {
		action: 'set_widget_country',
		country: country
	};		
	
	jQuery.ajax({
		url: cbr_ajax_object.cbr_ajax_url,
		data: data,
		type: 'POST',
		dataType:"json",	
		success: function(response) {
			jQuery("#wp-admin-bar-cbr_item a.ab-item").text("CBR Country: "+response.country);
			jQuery(".display-country-for-customer .country, .widget-country, .select-country-dropdown").val(response.countrycode);
			jQuery(".cbr_shipping_country").text(response.country);		
		},
		error: function(response) {				
		}
	});
	return false;
}

jQuery(document).on("submit", ".woocommerce-shipping-calculator", function(){
	/*setTimeout(function() {
		location.reload();
	 }, 3000);*/
	var country = jQuery('#calc_shipping_country').val();
	var data = {
		action: 'set_cart_page_country',
		country: country
	};		
	
	jQuery.ajax({
		url: cbr_ajax_object.cbr_ajax_url,
		data: data,
		type: 'POST',
		dataType:"json",	
		success: function(response) {
			jQuery("#wp-admin-bar-cbr_item a.ab-item").text("CBR Country: "+response.country);
			jQuery(".display-country-for-customer .country, .widget-country, .select-country-dropdown").val(response.countrycode);
			jQuery(".cbr_shipping_country").text(response.country);
		},
		error: function(response) {	
			console.log(response);			
		}
	});
	return false;
});