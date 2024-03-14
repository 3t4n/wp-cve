jQuery(function($) {

	// Color picker
	$('.colorpick').wpColorPicker();

	$(".tips, .help_tip").tipTip({
    	'attribute' : 'data-tip',
    	'fadeIn' : 50,
    	'fadeOut' : 50,
    	'delay' : 200
    });

    var wphr_Image_Uploader = {

        init: function() {
            $('a.wphr-image-upload').on('click', this.imageUpload);
            $('a.wphr-remove-image').on('click', this.removeBanner);
        },

        imageUpload: function(e) {
            e.preventDefault();

            var file_frame,
                self = $(this);

            if ( file_frame ) {
                file_frame.open();
                return;
            }

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: jQuery( this ).data( 'uploader_title' ),
                button: {
                    text: jQuery( this ).data( 'uploader_button_text' )
                },
                multiple: false
            });

            file_frame.on( 'select', function() {
                var attachment = file_frame.state().get('selection').first().toJSON();

                var wrap = self.closest('td');

                wrap.find('input.wphr-file-field').val(attachment.id);

                if ( typeof attachment.sizes.thumbnail !== 'undefined' ) {
                    wrap.find('img.wphr-option-image').attr('src', attachment.sizes.thumbnail.url);
                } else {
                    wrap.find('img.wphr-option-image').attr('src', attachment.url);
                }

                $('.image-wrap', wrap).removeClass('wphr-hide');

                $('.button-area', wrap).addClass('wphr-hide');
            });

            file_frame.open();

        },

        removeBanner: function(e) {
            e.preventDefault();

            var self = $(this);
            var wrap = self.closest('.image-wrap');
            var instruction = wrap.siblings('.button-area');

            wrap.find('input.wphr-file-field').val('0');
            wrap.addClass('wphr-hide');
            instruction.removeClass('wphr-hide');
        },
    };

    wphr_Image_Uploader.init();
});
