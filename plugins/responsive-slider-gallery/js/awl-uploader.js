jQuery(function(jQuery) {
    
    var file_frame,
    awlslider = {
        ul: '',
        init: function() {
            this.ul = jQuery('.sbox');
            this.ul.sortable({
                placeholder: '',
				revert: true,
            });			
			
            /**
			 * Add Slide Callback Funtion
			 */
            jQuery('#add-new-slider').on('click', function(event) {
				var rsg_add_images_nonce = jQuery("#rsg_add_images_nonce").val();
				console.log(rsg_add_images_nonce);
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
                        awlslider.get_thumbnail(images[i]['id'], '', rsg_add_images_nonce);
                    }
                });
                file_frame.open();
            });
			
			/**
			 * Delete Slide Callback Function
			 */
            this.ul.on('click', '#remove-slide', function() {
                if (confirm('Do you wnat to delete this slide?')) {
                    jQuery(this).parent().fadeOut(700, function() {
                        jQuery(this).remove();
                    });
                }
                return false;
            });
			
			/**
			 * Delete All Slides Callback Function
			 */
			jQuery('#remove-all-slides').on('click', function() {
                if (confirm('Do you want to delete all slides?')) {
                    awlslider.ul.empty();
                }
                return false;
            });
           
        },
        get_thumbnail: function(id, cb, rsg_add_images_nonce) {
            cb = cb || function() {
            };
            var data = {
                action: 'slide',
                slideId: id,
				rsg_add_images_nonce: rsg_add_images_nonce,
            };
            jQuery.post(ajaxurl, data, function(response) {
                awlslider.ul.append(response);
                cb();
            });
        }
    };
    awlslider.init();
});