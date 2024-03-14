/**
 * @sf uploader v1.0.0 - MIT License
 */
jQuery(
	function(jQuery) {
		var file_frame,
		SF = {
			ul: '',
			init: function() {
				/* this.admin_thumb_ul = jQuery('#uris-slides-container');
				this.admin_thumb_ul.sortable({
				placeholder: '',
				revert: true,
				}); */

				/* this.admin_thumb_ul.on('click', '.sf-delete-slide', function() {
				//if (confirm('Are you sure you want to delete this slide?')) {
					jQuery(this).parent().fadeOut(700, function() {
						jQuery(this).remove();
					});
				//}
				return false;
				}); */

				/**
				 * Add Image Callback Function
				 */
				jQuery( '#sf-upload-slides' ).on(
					'click',
					function(event) {
						event.preventDefault();
						if (file_frame) {
							file_frame.open();
							return;
						}
						file_frame = wp.media.frames.file_frame = wp.media(
							{
								multiple: true
							}
						);

						file_frame.on(
							'select',
							function() {
								var images = file_frame.state().get( 'selection' ).toJSON(),
									length = images.length;
								for (var i = 0; i < length; i++) {
									SF.get_thumbnail( images[i]['id'] );
								}
							}
						);
						file_frame.open();
					}
				);

				/**
				 * Remove Image Slide Callback Function
				 */
				/* this.ul.on('click', '#sf-remove-image', function() {
				if (confirm('Are sure to delete this images?')) {
					jQuery(this).parent().fadeOut(700, function() {
						jQuery(this).remove();
					});
				}
				return false;
				}); */

				/**
				 * Remove All Image Slides Callback Function
				 */
				/* jQuery('.sf-delete-all-slide').on('click', function() {
				//if (confirm('Are you sure you want to delete all the slides?')) {
					//SF.admin_thumb_ul.fadeOut(700);
					jQuery(function() {
						setTimeout(function() {
							SF.admin_thumb_ul.empty();
						}, 700);
					});
				//}
				return false;
				}); */

			},
			get_thumbnail: function(id, cb) {
				cb = cb || function() {
				};

				var sf_slider_id = jQuery( "#sf_slider_id" ).val();
				var sf_upload_nonce = jQuery( "#sf_upload_nonce" ).val();
				console.log( sf_slider_id );
				var data = {
					action: 'sf_image_id',
					sf_attachment_id: id,
					sf_slider_id: sf_slider_id,
					sf_upload_nonce: sf_upload_nonce,
				};

				jQuery.ajax(
					{
						type: 'POST',
						url: ajaxurl,
						async: false,
						dataType: 'html',
						data: data,
						complete: function() { },
						success: function(response) {
							jQuery( ".sf-slides" ).append( response );
							// SF.admin_thumb_ul.prepend(response);
							cb();
							// BindMultiSelect();
						}
					}
				);
			}
		};
		SF.init();
	}
);
