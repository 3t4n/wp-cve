// Uploading files

var file_frame;

jQuery(document).ready(function(){
	
	jQuery("[id$='_url']").each(function(index, element) {
	
		var prefix = jQuery(element).attr('id').split('_')[0];
		
		if (jQuery('#' + prefix + '_url').val()=='') {
		
			jQuery('#' + prefix + '_upload').show();
			jQuery('#' + prefix + '_preview').hide();
			jQuery('#' + prefix + '_remove').hide();
		
		} else {
		
			jQuery('#' + prefix + '_upload').hide();
			jQuery('#' + prefix + '_preview').show();
			jQuery('#' + prefix + '_remove').show();
		
		}
	
	});
	
	jQuery('.upload-button').on('click', function( event ){
	
	var prefix = jQuery(this).prop('name');
	
	event.preventDefault();
	
	// If the media frame already exists, reopen it.
	if ( file_frame ) {
		file_frame.open();
		return;
	}
	
	// Create the media frame.
	file_frame = wp.media.frames.file_frame = wp.media({
		title: jQuery( this ).data( 'uploader_title' ),
		button: {
			text: jQuery( this ).data( 'uploader_button_text' ),
		},
		multiple: false  // Set to true to allow multiple files to be selected
	});
	
	// When an image is selected, run a callback.
	file_frame.on( 'select', function() {
		// We set multiple to false so only get one image from the uploader
		img = file_frame.state().get('selection').first();
		
		jQuery('#' + prefix + '_url').val(img.attributes.url);
		jQuery('#' + prefix + '_upload').hide();
		jQuery('#' + prefix + '_preview').show();
		jQuery('#' + prefix + '_remove').show();
		
		if (prefix == 'video') {
		
			var data = {
			
				action: 'video_preview',
				ajax_nonce: jQuery('#ajax_nonce').val(),
				video_url: img.attributes.url,
				video_width: jQuery('#video_width').val(),
				video_height: jQuery('#video_height').val(),
				video_poster: jQuery('#poster_url').val(),
				video_loop: jQuery('#video_loop:checked').val(),
				video_autoplay: jQuery('#video_autoplay:checked').val(),
				video_preload: jQuery('#video_preload').val(),
				video_class: jQuery('#video_class').val(),
				video_id: jQuery('#video_id').val()
			
			};
		
			jQuery.post(ajaxurl, data, function(response) {
			
				jQuery('#' + prefix + '_preview').html(response);
			
			});
		
		}
		
		else {
		
			jQuery('#' + prefix + '_preview>img').attr('src', img.attributes.url);
		
		}
		
		});
		
		// Finally, open the modal
		file_frame.open();
		
		});
		
		jQuery('.remove-button').on('click', function(){
		
		prefix = jQuery(this).prop('name');
		
		if (prefix == 'video') {
		
			jQuery('#video_preview').html('');
			jQuery('#video_url').val('');
			jQuery('#video_upload').show();
			jQuery('#video_preview').hide();
			jQuery('#video_remove').hide();
			jQuery('#poster_preview>img').attr('src', '');
			jQuery('#poster_url').val('');
			jQuery('#poster_upload').show();
			jQuery('#poster_preview').hide();
			jQuery('#poster_remove').hide();
		
		}
		
		else {
		
			jQuery('#' + prefix + '_preview>img').attr('src', '');
			jQuery('#' + prefix + '_url').val('');
			jQuery('#' + prefix + '_upload').show();
			jQuery('#' + prefix + '_preview').hide();
			jQuery('#' + prefix + '_remove').hide();
		
		}
	
	});
	 
});