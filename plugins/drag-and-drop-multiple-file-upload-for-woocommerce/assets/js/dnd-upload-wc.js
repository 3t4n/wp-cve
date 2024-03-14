jQuery(document).ready(function($){

	// Setup drag n drop function
	window.initDragDrop = function () {

		// Get text object options/settings from localize script
		var TextOJB = dnd_wc_uploader.drag_n_drop_upload;

		var dnd_options = {
			'form'				: 	$('form.cart'),
			'color'				:	'#fff',
			'ajax_url'			: 	dnd_wc_uploader.ajax_url,
			'text'				: 	TextOJB.text,
			'separator'			: 	TextOJB.or_separator,
			'button_text'		:	TextOJB.browse,
			'server_max_error'	: 	TextOJB.server_max_error,
			'err_message'		: 	{ maxNumFiles : TextOJB.maxNumFiles, maxUploadLimit : TextOJB.maxFileLimit },

			//@description: upload is in progress
			'in_progress' : function( form_handler, queue, data ) {

				// Get submit btn
				var cartBtn = $('button[type="submit"]', form_handler);

				// Disable submit button
				if( cartBtn.length > 0 ) {
					cartBtn.addClass('disable').prop( "disabled", true );
				}
			},

			// @description: single queue file upload is complete
			'on_success' : function( progressBar, response, fieldName, Record ){

				// Append hidden input field
				$('button[type="submit"]', $('form.cart') )
					.before('<input type="hidden" data-index="'+progressBar+'" name="'+ fieldName +'[]" value="'+ response.data.path + response.data.file +'">');

				// Update Counter
				$('.dnd-upload-counter span').text( Record.uploaded );

			},

			// @description: all queued files has been completed
			'completed' : function( form_handler, name, data ) {
				// If it's complete remove disabled attribute in button
				if( $('.in-progress', $('.codedropz-upload-wrapper') ).length === 0 ) {
					$('button[type="submit"]', form_handler ).removeAttr('disabled');
				}
			}

		};

		// Initialize Plugin
		$('.wc-drag-n-drop-file').CodeDropz_Uploader_WC( dnd_options );

	}

	// Initialize drag n drop plugin.
	window.initDragDrop();

	// Add to cart btn - minimum file validation
	$('button[type="submit"]', $('form.cart') ).on("click", function(){
		var $file = $('input.wc-drag-n-drop-file');
		if( $minimum_file = parseInt( $file.data('min') ) ) {
			var $total_files = $('input[name="'+ $file.data('name') +'[]"]' ).length;
			var $error_msg = dnd_wc_uploader.drag_n_drop_upload.minimum_file;
			$('.codedropz-upload-wrapper').find('span.has-error-msg').hide().remove();
			if( $total_files > 0 && $total_files < $minimum_file ) {
				$('.codedropz--results').after('<span class="has-error-msg">'+ $error_msg.replace('%s', $minimum_file ) +'</span>');
				return false;
			}
		}
	});
});