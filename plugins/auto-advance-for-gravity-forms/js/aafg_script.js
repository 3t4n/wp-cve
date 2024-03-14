jQuery(document).bind('gform_post_render', function(){ 
	perform_hiding_operations();
});

function perform_hiding_operations() {
	
	if( jQuery('.gform_page').length > 0 ) {		
		jQuery('.gform_page').each(function() {						
			if( jQuery(this).find('.hide-next-button').length > 0 ) {
				jQuery(this).find('.gform_next_button').removeClass('make_visible');
				jQuery(this).find('.gform_next_button').addClass('keep_hidden');				
			}
			else {
				jQuery(this).find('.gform_next_button').removeClass('keep_hidden');	
			}
			
			if( jQuery(this).find('.hide-previous-button').length > 0 ) {				
				jQuery(this).find('.gform_previous_button').removeClass('make_visible');				
				jQuery(this).find('.gform_previous_button').addClass('keep_hidden');				
			}
			else {
				jQuery(this).find('.gform_previous_button').removeClass('keep_hidden');	
				jQuery(this).find('.gform_previous_button').addClass('make_visible');	
			}
			
			if( jQuery(this).find('.hide-next-button').length > 0 ) {				
				jQuery(this).find('.gform_button').removeClass('make_visible');				
				jQuery(this).find('.gform_button').addClass('keep_hidden');				
			}
			else {
				jQuery(this).find('.gform_button').removeClass('keep_hidden');	
				jQuery(this).find('.gform_button').addClass('make_visible');	
			}
		});
	}
	
	if( jQuery('.gform_wrapper').length > 0 && ! jQuery('.gform_page').length > 0 ){	
	
		jQuery('.gform_wrapper').each(function() {									
			if( jQuery(this).find('.hide-submit-button').length > 0 ) {				
				jQuery(this).find('.gform_button').removeClass('make_visible');				
				jQuery(this).find('.gform_button').addClass('keep_hidden');				
			}
			else {
				jQuery(this).find('.gform_button').removeClass('keep_hidden');	
				jQuery(this).find('.gform_button').addClass('make_visible');	
			}
		});
		
	}

}


jQuery( document ).ready( function($) {
	
	var click_perform = true;
	
	jQuery(document).on('click', ".trigger-next-zzd input[type='radio']", function() {
	   var $this = jQuery(this);
	   setTimeout(function() {
		   
			if(click_perform) {
				$this.trigger('change');
			}
			click_perform = true;
	   }, 100);
	   
   });


	jQuery(document).on ('change', ".trigger-next-zzd input[type='radio'], .trigger-next-zzd select", function() {
		
		var $this = jQuery(this);
		click_perform = false;
		
		setTimeout( function() {				
			$this.parents('form').trigger('submit', [true]);
		}, 200 );
   });
	
	
});











