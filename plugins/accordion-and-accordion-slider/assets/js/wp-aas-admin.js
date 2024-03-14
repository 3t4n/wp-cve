(function($) {

	"use strict";

	/* Click to Copy the Text */
	$(document).on('click', '.wpos-copy-clipboard', function() {
		var copyText = $(this);
		copyText.select();
		document.execCommand("copy");
	});

	// Media Uploader
	$( document ).on( 'click', '.wp-aas-img-uploader', function() {
		
		var imgfield, showfield;
		imgfield			= jQuery(this).prev('input').attr('id');
		showfield 			= jQuery(this).parents('td').find('.wp-aas-imgs-preview');
		var multiple_img	= jQuery(this).attr('data-multiple');
		multiple_img 		= (typeof(multiple_img) != 'undefined' && multiple_img == 'true') ? true : false;
		
		if( typeof wp == "undefined" || WpAasAdmin.new_ui != '1' ) { // check for media uploader
			
			tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	    	
			window.original_send_to_editor = window.send_to_editor;
			window.send_to_editor = function(html) {
				
				if(imgfield)  {
					
					var mediaurl = $('img',html).attr('src');
					$('#'+imgfield).val(mediaurl);
					showfield.html('<img src="'+mediaurl+'" />');
					tb_remove();
					imgfield = '';
					
				} else {
					window.original_send_to_editor(html);
				}
			};
	    		return false;
		      
		} else {
			
			var file_frame;
			//window.formfield = '';
			
			/* new media uploader */
			var button = jQuery(this);
		
			/* If the media frame already exists, reopen it. */
			if ( file_frame ) {
				file_frame.open();
			  return;
			}

			if( multiple_img == true ) {
				
				/* Create the media frame. */
				file_frame = wp.media.frames.file_frame = wp.media({
					title: button.data( 'title' ),
					button: {
						text: button.data( 'button-text' ),
					},
					multiple: true  /* Set to true to allow multiple files to be selected */
				});
				
			} else {
				
				/* Create the media frame. */
				file_frame = wp.media.frames.file_frame = wp.media({
					frame: 'post',
					state: 'insert',
					title: button.data( 'title' ),
					button: {
						text: button.data( 'button-text' ),
					},
					multiple: false  /* Set to true to allow multiple files to be selected */
				});
			}
			
			file_frame.on( 'menu:render:default', function(view) {
		        /* Store our views in an object. */
		        var views = {};
				
		        /* Unset default menu items */
		        view.unset('library-separator');
		        view.unset('gallery');
		        view.unset('featured-image');
		        view.unset('embed');
				
		        /* Initialize the views in our view object. */
		        view.set(views);
		    });

			/* When an image is selected, run a callback. */
			file_frame.on( 'select', function() {
				
				/* Get selected size from media uploader */
				var selected_size = $('.attachment-display-settings .size').val();
				var selection = file_frame.state().get('selection');
				
				selection.each( function( attachment, index ) {
					
					attachment = attachment.toJSON();

					/* Selected attachment url from media uploader */
					var attachment_id = attachment.id ? attachment.id : '';
					if( attachment_id && attachment.sizes && multiple_img == true ) {
						
						var attachment_url 			= attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
						var attachment_edit_link	= attachment.editLink ? attachment.editLink : '';

						showfield.append('\
							<div class="wp-aas-img-wrp">\
								<div class="wp-aas-img-tools wp-aas-hide">\
									<span class="wp-aas-tool-icon wp-aas-edit-img dashicons dashicons-edit" title="'+WpAasAdmin.img_edit_popup_text+'"></span>\
									<a href="'+attachment_edit_link+'" target="_blank" title="'+WpAasAdmin.attachment_edit_text+'"><span class="wp-aas-tool-icon wp-aas-edit-attachment dashicons dashicons-visibility"></span></a>\
									<span class="wp-aas-tool-icon wp-aas-del-tool wp-aas-del-img dashicons dashicons-no" title="'+WpAasAdmin.img_delete_text+'"></span>\
								</div>\
								<img class="wp-aas-img" src="'+attachment_url+'" alt="" />\
								<input type="hidden" class="wp-aas-attachment-no" name="wp_aas_img[]" value="'+attachment_id+'" />\
							</div>\
								');
						showfield.find('.wp-aas-img-placeholder').hide();
					}
				});
			});
			
			/* When an image is selected, run a callback. */
			file_frame.on( 'insert', function() {
				
				/* Get selected size from media uploader */
				var selected_size = $('.attachment-display-settings .size').val();
				
				var selection = file_frame.state().get('selection');
				selection.each( function( attachment, index ) {
					attachment = attachment.toJSON();
					
					/* Selected attachment url from media uploader */
					var attachment_url = attachment.sizes[selected_size].url;
					
					/* place first attachment in field */
					$('#'+imgfield).val(attachment_url);
					showfield.html('<img src="'+attachment_url+'" />');
				});
			});
			
			/* Finally, open the modal */
			file_frame.open();
		}
	});
	
	
	/* Remove Single Gallery Image */
	$(document).on('click', '.wp-aas-del-img', function(){
		
		$(this).closest('.wp-aas-img-wrp').fadeOut(300, function(){ 
			$(this).remove();
			
			if( $('.wp-aas-img-wrp').length == 0 ){
				$('.wp-aas-img-placeholder').show();
			}
		});
	});

	/* Remove All Gallery Image */
	$(document).on('click', '.wp-aas-del-gallery-imgs', function() {

		var ans = confirm('Are you sure to remove all images from this gallery!');

		if(ans){
			$('.wp-aas-gallery-imgs-wrp .wp-aas-img-wrp').remove();
			$('.wp-aas-img-placeholder').fadeIn();
		}
	});

	/* Image ordering (Drag and Drop) */
	$('.wp-aas-gallery-imgs-wrp').sortable({
		items: '.wp-aas-img-wrp',
		cursor: 'move',
		scrollSensitivity:40,
		forcePlaceholderSize: true,
		forceHelperSize: false,
		helper: 'clone',
		opacity: 0.8,
		placeholder: 'wp-aas-gallery-placeholder',
		containment: '.wp-aas-post-sett-table',
		start:function(event,ui){
			ui.item.css('background-color','#f6f6f6');
		},
		stop:function(event,ui){
			ui.item.removeAttr('style');
		}
	});

	/* Open Attachment Data Popup */
	$(document).on('click', '.wp-aas-img-wrp .wp-aas-edit-img', function(){
		
		$('.wp-aas-img-data-wrp').show();
		$('.wp-aas-popup-overlay').show();
		$('body').addClass('wp-aas-no-overflow');
		$('.wp-aas-img-loader').show();

		var current_obj 	= $(this);
		var nonce			= current_obj.closest('.wp-aas-imgs-preview').attr('data-nonce');
		var attachment_id 	= current_obj.closest('.wp-aas-img-wrp').find('.wp-aas-attachment-no').val();

		var data = {
                        action      	: 'wp_aas_get_attachment_edit_form',
                        attachment_id   : attachment_id,
                        nonce			: nonce,
                    };
        $.post(ajaxurl,data,function(response) {
			var result = $.parseJSON(response);
			
			if( result.success == 1 ) {
				$('.wp-aas-img-data-wrp  .wp-aas-popup-body-wrp').html( result.data );
				$('.wp-aas-img-loader').hide();
			}
        });
	});

	/* Close Popup */
	$(document).on('click', '.wp-aas-popup-close', function(){
		wp_aas_hide_popup();
	});

	/* `Esc` key is pressed */
	$(document).keyup(function(e) {
		if (e.keyCode == 27) {
			wp_aas_hide_popup();
		}
	});

	/* Save Attachment Data */
	$(document).on('click', '.wp-aas-save-attachment-data', function(){
		
		var current_obj = $(this);
		var nonce		= current_obj.attr('data-nonce');

		current_obj.attr('disabled','disabled');
		current_obj.parent().find('.spinner').css('visibility', 'visible');
		

		var data = {
                        action      	: 'wp_aas_save_attachment_data',
                        attachment_id   : current_obj.attr('data-id'),
                        form_data		: current_obj.closest('form.wp-aas-attachment-form').serialize(),
                        nonce			: nonce,
                    };
        $.post(ajaxurl,data,function(response) {
			var result = $.parseJSON(response);
			
			if( result.success == 1 ) {
				current_obj.closest('form').find('.wp-aas-success').html(result.msg).fadeIn().delay(3000).fadeOut();
			} else if( result.success == 0 ) {
				current_obj.closest('form').find('.wp-aas-error').html(result.msg).fadeIn().delay(3000).fadeOut();
			}
			current_obj.removeAttr('disabled','disabled');
			current_obj.parent().find('.spinner').css('visibility', '');
        });
	});
})(jQuery);

/* Function to hide popup */
function wp_aas_hide_popup() {
	jQuery('.wp-aas-img-data-wrp').hide();
	jQuery('.wp-aas-popup-overlay').hide();
	jQuery('body').removeClass('wp-aas-no-overflow');
	jQuery('.wp-aas-img-data-wrp  .wp-aas-popup-body-wrp').html('');
}