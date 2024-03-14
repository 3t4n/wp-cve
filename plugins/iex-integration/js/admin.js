jQuery(document).ready(function(){
	jQuery(document).on('click', 'button#iexcheckkey', function(e){
		e.preventDefault();
		jQuery('#iexspin').addClass('is-active');
		var data = {
			'action' : 'iex_check_key',
			'key': jQuery('#iex_api_key').val(),
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			result = jQuery.parseJSON(response);
			if (result.Success == "True") {
				jQuery('.api_customer.customer_info span').html(result.Returned);
			} else {
				jQuery('.api_customer.customer_info span').html(result.Message);
			}
			jQuery('#iexspin').removeClass('is-active');
		});
	});
});