jQuery(document).ready(function() {
    jQuery(document).on('click', function() {
	    if (jQuery('.ewd-ulb-image-selector').length == 0) {EWD_ULB_Add_Image_Select_HTML();}

	    jQuery('.ewd-ulb-paired-image-select').on('click', function() {	

	    	var Paired_Button = jQuery(this);

	    	jQuery('.ewd-ulb-image-selector').removeClass('ewd-ulb-hidden');
	
	    	jQuery('.ewd-ulb-image-selector img').on('click', function() {
	    		Paired_Button.parent().find('input').first().val(jQuery(this).data('id')).trigger('change');
	    		jQuery('.ewd-ulb-image-selector img').off('click');
	    		jQuery('.ewd-ulb-image-selector').addClass('ewd-ulb-hidden');
	    	});

	    	jQuery('.media-modal-backdrop').on('click', function() {
	    		jQuery('.ewd-ulb-image-selector').addClass('ewd-ulb-hidden');
	    	});
	    });

    	jQuery('.ewd-ulb-remove-paired-image').on('click', function() {
    		jQuery(this).parent().find('input').first().val('').trigger('change');
    	});
    });
});

function EWD_ULB_Add_Image_Select_HTML() {
	var HTML = '<div class="ewd-ulb-image-selector ewd-ulb-hidden">';
	jQuery('.attachment-preview.type-image').each(function(index, el) {
		var IMG_SRC = jQuery(this).find('img').first().attr('src'); 
		HTML += '<img src="' + IMG_SRC + '" data-id="' + jQuery(this).parent().data('id') + '" />';
	});
	HTML += '</div>';

	jQuery('body').append(HTML);
}