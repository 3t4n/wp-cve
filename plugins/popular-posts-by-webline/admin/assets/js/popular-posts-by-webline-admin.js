(function( $ ) {
	'use strict';

	// Uploading files
	var file_frame;
	var $upload_btn;

	jQuery( document ).on( 'click', '.upload_img_btn', function( event ) {

		event.preventDefault();

		$upload_btn = $(this);

		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame.open();
			return;
		}

		// Create the media frame.
		file_frame = wp.media.frames.downloadable_file = wp.media({
			title: WLIPP_ScriptsData.choose_image_title,
			button: {
				text: WLIPP_ScriptsData.use_image_btn_text
			},
			multiple: false
		});

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			var attachment = file_frame.state().get( 'selection' ).first().toJSON();
			var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;
			$upload_btn.siblings( 'input.wli_popular_posts_upload_img_id' ).val( attachment.id );
			$upload_btn.siblings( '.wli_popular_posts_upload_img_preview' ).find('img').attr( 'src', attachment_thumbnail.url );
			$upload_btn.siblings( '.remove_img_btn' ).show();
		});

		// Finally, open the modal.
		file_frame.open();
	});

	jQuery( document ).on( 'click', '.remove_img_btn', function() {
		var $this = $(this);
		$this.siblings( '.wli_popular_posts_upload_img_preview' ).find( 'img' ).attr( 'src', '' );
		$this.siblings( '.wli_popular_posts_upload_img_id' ).val( '' );
		return false;
	});

	$(document).ready(function(){
		$('.wli_color_picker').wpColorPicker();
	});
})( jQuery );
