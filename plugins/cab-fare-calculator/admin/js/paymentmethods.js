jQuery(document).ready(function($){
	jQuery('#admin-form').submit(function(){
		var errorsCount = 0;
		jQuery('#admin-form .form-required').each(function(){
			if(jQuery(this).find('input').val() == "")
			{
				errorsCount++;
				jQuery(this).addClass('form-invalid');
			}
			else if(jQuery(this).find('input').hasClass('numeric') && jQuery(this).find('input').val() == "0")
			{
				errorsCount++;
				jQuery(this).addClass('form-invalid');
			}
			else {
				jQuery(this).removeClass('form-invalid');
			}
		})
		
		if(errorsCount > 0){
			return false;
		}
		return true;
	});

	jQuery('.btn-group-yesno label.btn').click(function () {
		if (jQuery(this).prop("checked")) {
			// checked
			return;
		}
		jQuery(this).siblings('.btn').removeClass('active');
		jQuery(this).addClass('active');
		jQuery(this).parent('.btn-group-yesno').children('input').attr('checked', false);
		jQuery(this).prev('input').attr('checked', true);
	});	

	jQuery('.payment_element').change(function () {
		if (jQuery(this).val()=='paypal' || jQuery(this).val()=='stripe') {
			jQuery('[name="state"]').val(0);
			window.location.href = 'https://kanev.com/products/taxi-booking-for-wordpress';
		}
	});

	jQuery( "#tabs" ).tabs();
	
});