/*
 * Attaches the image uploader to the input field
 */
 
jQuery(document).ready(function($){
 
	$('#clicface-trombi-images .clicface-trombi-images-upload').each(function() {
	
		var clicface_trombi_images_image_frame;
		
		var p = $(this);
		
		//Choose/upload image
		p.find('.clicface-trombi-images-upload-button').click(function(e) {
		
			e.preventDefault();
			
			if ( clicface_trombi_images_image_frame ) {
				clicface_trombi_images_image_frame.open();
				return;
			}
			
			clicface_trombi_images_image_frame = wp.media.frames.clicface_trombi_images_image_frame = wp.media({
				title: meta_image.title,
				button: { text:  meta_image.button }
			});
			
			// Runs when an image is selected.
			clicface_trombi_images_image_frame.on('select', function() {
			
				// Grabs the attachment selection and creates a JSON representation of the model.
				var media_attachment = clicface_trombi_images_image_frame.state().get('selection').first().toJSON();
				
				var media_id = media_attachment.id;
				var media_thumbnail = media_attachment.sizes.thumbnail.url;
				
				// Sends the attachment URL to our custom image input field.
				p.find('.clicface-trombi-images-upload-id').val(media_id);
				p.find('.clicface-trombi-images-upload-thumbnail').html('<img src="' + media_thumbnail + '">');
			});
			
			// Opens the media library frame.
			clicface_trombi_images_image_frame.open(); 
			
		});
		
		//Unset current image
		p.find('.clicface-trombi-images-upload-clear').click(function(e) {
			
			e.preventDefault();
			
			console.log('clear');
			
			p.find('.clicface-trombi-images-upload-id').val('');
			p.find('.clicface-trombi-images-upload-thumbnail').empty();
		
		});
		
	});
	
});