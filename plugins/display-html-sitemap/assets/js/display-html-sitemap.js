jQuery(document).ready(function(){

	"use strict";
	
	jQuery( "#dhswp-sortable" ).sortable({
		
		update: function(event, ui) {

			var fruitOrder = jQuery("#dhswp-sortable").sortable('toArray').toString();
			jQuery('#dhswp-sortorder').val(fruitOrder);
		}
	});
	
	jQuery('.dhswp_changename').click(function(){
		
		jQuery(this).next().fadeIn();
		return false;
	});
	
	jQuery('a.dhswp-save-newname').click(function(){
		
		if( jQuery(this).prev().val() == '' ){
			
			originalname = jQuery(this).parent().parent().next().next().html();
			jQuery(this).prev().val(originalname);
			jQuery(this).parent().prev().prev().html(originalname);

		} else {
			
			jQuery(this).parent().prev().prev().html( jQuery(this).prev().val() );
		}
		jQuery(this).parent().fadeOut();
		return false;
	});
	
	jQuery('a.dhswp-cancel-newname').click(function(){
		jQuery(this).prev().prev().val( jQuery(this).parent().prev().prev().html() );
		jQuery(this).parent().fadeOut();
		return false;
	});
});