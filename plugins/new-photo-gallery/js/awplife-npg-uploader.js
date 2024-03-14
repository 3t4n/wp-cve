jQuery(function(jQuery) {
	var file_frame,
	awplife_photo_gallery = {
		ul: '',
		init: function() {
			this.ul = jQuery('.photo-box');
			this.ul.sortable({
				placeholder: '',
				revert: true,
			});
			
			/**
			 * Add Slide Callback Function
			 */
			jQuery('#add-new-photos').on('click', function(event) {
				event.preventDefault();
				if (file_frame) {
					file_frame.open();
					return;
				}
				file_frame = wp.media.frames.file_frame = wp.media({
					multiple: true
				});

				file_frame.on('select', function() {
					var images = file_frame.state().get('selection').toJSON(),
							length = images.length;
					for (var i = 0; i < length; i++) {
						awplife_photo_gallery.get_thumbnail(images[i]['id']);
					}
				});
				file_frame.open();
			});
			
			/**
			 * Delete Slide Callback Function
			 */
			this.ul.on('click', '#remove-photo', function() {
				if (confirm('Are sure to delete this photo?')) {
					jQuery(this).parent().fadeOut(700, function() {
						jQuery(this).remove();
					});
				}
				return false;
			});
			
			/**
			 * Delete All Slides Callback Function
			 */
			jQuery('#remove-all-photos').on('click', function() {
				if (confirm('Are sure to delete all photos?')) {
					awplife_photo_gallery.ul.empty();
				}
				return false;
			});
		   
		},
		get_thumbnail: function(id, cb) {
			cb = cb || function() {
			};
			var data = {
				action: 'photo_gallery_js',
				slideId: id
			};
			jQuery.post(ajaxurl, data, function(response) {
				awplife_photo_gallery.ul.append(response);
				cb();
			});
		}
	};
	awplife_photo_gallery.init();
});