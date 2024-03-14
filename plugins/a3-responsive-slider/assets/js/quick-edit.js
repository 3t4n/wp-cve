jQuery(document).ready(function(){  
    jQuery('#the-list').on('click', '.editinline', function(){  
		
		inlineEditPost.revert();

		var post_id = jQuery(this).closest('tr').attr('id');
		
		post_id = post_id.replace("post-", "");
		
		var $a3_slider_skin_bulk_inline_data 	= jQuery('#a3_slider_skin_bulk_inline_' + post_id );
		
		var a3_slider_skin_value 				= $a3_slider_skin_bulk_inline_data.find('.a3_slider_skin_value').text();
		
		jQuery('#a3-slider-fields-quick select[name="_slider_skin"] option', '.inline-edit-row').prop('selected',false);
		jQuery('#a3-slider-fields-quick select[name="_slider_skin"] option[value="'+a3_slider_skin_value+'"]', '.inline-edit-row').prop('selected', true);
		
    }); 
	
	jQuery('#the-list').on('click', '.cancel', function(){  
	 
	});
    
    jQuery('#wpbody').on('click', '#doaction, #doaction2', function(){  

		jQuery('select.slider_skin option', '.inline-edit-row').prop('selected',false);
		jQuery('#a3-slider-fields-bulk select.slider_skin option').prop('selected',false);
		
	});
	
});  