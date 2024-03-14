jQuery(document).ready(function() {

	jQuery('.mo-option').each( function() {
		if ( jQuery(this).find('select').val() == 2 ) {
			jQuery(this).parents('.mo-option-fields').find('.mo-option-roles').show();
		} else {
			jQuery(this).parents('.mo-option-fields').find('.mo-option-roles').hide();
		}

		if ( jQuery(this).find('select').val() == 3 ) {
			jQuery(this).parents('.mo-option-fields').find('.mo-option-redirect').show();
		}else{
			jQuery(this).parents('.mo-option-fields').find('.mo-option-redirect').hide();
		}
	});


	jQuery( document.body ).on('change', '.mo-option select', function(){
		if ( jQuery(this).val() == 2 ) {
			jQuery(this).parents('.mo-option-fields').find('.mo-option-roles').show();
		}else{
			jQuery(this).parents('.mo-option-fields').find('.mo-option-roles').hide();
		}

		if ( jQuery(this).val() == 3 ) {
			jQuery(this).parents('.mo-option-fields').find('.mo-option-redirect').show();
		}else{
			jQuery(this).parents('.mo-option-fields').find('.mo-option-redirect').hide();
		}
	});
});