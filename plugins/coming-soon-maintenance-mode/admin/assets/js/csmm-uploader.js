/**
 * @csmm logo uploader v1.0.0 - MIT License
 */
jQuery(
	function(jQuery) {
		var file_frame,
		CSMM = {
			ul: '',
			init: function() {
				this.ul = jQuery( '#csmm-logo' );
				this.ul.sortable(
					{
						placeholder: '',
						revert: true,
					}
				);

				/**
				 * Add Image Callback Function
				 */
				jQuery( '#csmm-upload-logo' ).on(
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
									CSMM.get_thumbnail( images[i]['id'] );
								}
							}
						);
						file_frame.open();
					}
				);

				/**
				 * Remove Image Callback Function
				 */
				/* this.ul.on('click', '#csmm-remove-image', function() {
				if (confirm('Are sure to delete this images?')) {
					jQuery(this).parent().fadeOut(700, function() {
						jQuery(this).remove();
					});
				}
				return false;
				}); */

				/**
				 * Remove All Images Callback Function
				 */
				jQuery( '#csmm-remove-all-image' ).on(
					'click',
					function() {
						if (confirm( 'Are sure to delete all images?' )) {
							CSMM.ul.empty();
						}
						return false;
					}
				);

			},
			get_thumbnail: function(id, cb) {
				cb = cb || function() {
				};
				var data = {
					action: 'csmm_logo',
					attachment_id: id,
				};
				jQuery.post(
					ajaxurl,
					data,
					function(response) {
						CSMM.ul.empty();
						CSMM.ul.append( response );
						cb();
						// BindMultiSelect();
					}
				);
			}
		};
		CSMM.init();
	}
);

/**
 * @csmm slides v1.0.0 - MIT License
 */
jQuery(
	function(jQuery) {
		var file_frame,
		CSMM_Slides = {
			ul: '',
			init: function() {
				this.ul = jQuery( '#csmm-slides' );
				this.ul.sortable(
					{
						placeholder: '',
						revert: true,
					}
				);

				/**
				 * Add Image Callback Function
				 */
				jQuery( '#csmm-upload-slide' ).on(
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
									CSMM_Slides.get_thumbnail( images[i]['id'] );
								}
							}
						);
						file_frame.open();
					}
				);

				/**
				 * Remove Image Callback Function
				 */
				/* this.ul.on('click', '#csmm-remove-slides', function() {
				if (confirm('Are sure to delete this images?')) {
					jQuery(this).parent().fadeOut(700, function() {
						jQuery(this).remove();
					});
				}
				return false;
				}); */

				/**
				 * Remove All Images Callback Function
				 */
				jQuery( '#csmm-remove-all-slides' ).on(
					'click',
					function() {
						if (confirm( 'Are sure to delete all slides?' )) {
							CSMM_Slides.ul.empty();
						}
						return false;
					}
				);

			},
			get_thumbnail: function(id, cb) {
				cb = cb || function() {
				};
				var data = {
					action: 'csmm_slide',
					attachment_id: id,
				};
				jQuery.post(
					ajaxurl,
					data,
					function(response) {
						CSMM_Slides.ul.append( response );
						cb();
						// BindMultiSelect();
					}
				);
			}
		};
		CSMM_Slides.init();
	}
);

