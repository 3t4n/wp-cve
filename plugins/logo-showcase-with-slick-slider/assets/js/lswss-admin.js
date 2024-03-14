( function($) {

	'use strict';

	/* Media Uploader */
	$( document ).on( 'click', '.lswssp-img-uploader', function() {

		var imgfield, showfield, multiple_img, file_frame;
		imgfield		= jQuery(this).prev('input').attr('id');
		showfield 		= jQuery(this).parents('td').find('.lswssp-imgs-preview');
		multiple_img	= jQuery(this).attr('data-multiple');
		multiple_img 	= (typeof(multiple_img) != 'undefined' && multiple_img == 'true') ? true : false;
		
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
				multiple: true /* Set to true to allow multiple files to be selected */
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
				multiple: false /* Set to true to allow multiple files to be selected */
			});
		}

		file_frame.on( 'menu:render:default', function(view) {
			/* Store our views in an object */
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
					
					var attachment_url			= attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
					var attachment_edit_link	= attachment.editLink ? attachment.editLink : '';

					showfield.append('\
						<div class="lswssp-img-wrp">\
							<div class="lswssp-img-tools">\
								<span class="lswssp-tool-icon lswssp-edit-img dashicons dashicons-edit" title="'+LswssAdmin.img_edit_text+'"></span>\
								<a href="'+attachment_edit_link+'" target="_blank" title="'+LswssAdmin.attachment_edit_text+'"><span class="lswssp-tool-icon lswssp-edit-attachment dashicons dashicons-visibility"></span></a>\
								<span class="lswssp-tool-icon lswssp-del-tool lswssp-del-img dashicons dashicons-no" title="'+LswssAdmin.img_del_text+'"></span>\
							</div>\
							<img class="lswssp-img" src="'+attachment_url+'" alt="" />\
							<input type="hidden" class="lswssp-attachment-no" name="lswss_img[]" value="'+attachment_id+'" />\
						</div>\
							');
					showfield.find('.lswssp-img-placeholder').hide();
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
	});

	/* Vertical Tab */
	$( document ).on( "click", ".lswssp-vtab-nav a", function() {

		$(".lswssp-vtab-nav").removeClass('lswssp-active-vtab');
		$(this).parent('.lswssp-vtab-nav').addClass("lswssp-active-vtab");

		var selected_tab = $(this).attr("href");
		$('.lswssp-vtab-cnt').hide();

		/* Show the selected tab content */
		$(selected_tab).show();

		/* Pass selected tab */
		$('.lswssp-selected-tab').val(selected_tab);
		return false;
	});

	/* Remain selected tab for user */
	if( $('.lswssp-selected-tab').length > 0 ) {

		var sel_tab = $('.lswssp-selected-tab').val();

		if( typeof(sel_tab) !== 'undefined' && $(sel_tab).closest('.lswssp-vtab-sett-wrap').is(":visible") && $(sel_tab).length > 0 ) {
			$('.lswssp-vtab-nav [href="'+sel_tab+'"]').click();
		} else {
			$('.lswssp-vtab-nav:visible:first-child a').click();
		}
	}

	/* Remove Single Gallery Image */
	$(document).on('click', '.lswssp-del-img', function(){

		$(this).closest('.lswssp-img-wrp').fadeOut(300, function(){ 
			$(this).remove();

			if( $('.lswssp-img-wrp').length == 0 ) {
				$('.lswssp-img-placeholder').show();
			}
		});
	});

	/* Remove All Gallery Image */
	$(document).on('click', '.lswssp-del-gallery-imgs', function() {

		var ans = confirm( LswssAdmin.all_img_del_text );

		if( ans ) {
			$('.lswssp-gallery-imgs-wrp .lswssp-img-wrp').remove();
			$('.lswssp-img-placeholder').fadeIn();
		}
	});

	/* Image ordering (Drag and Drop) */
	if( $('.lswssp-gallery-imgs-wrp').length > 0 ) {
		$('.lswssp-gallery-imgs-wrp').sortable({
			items: '.lswssp-img-wrp',
			cursor: 'move',
			scrollSensitivity:40,
			forcePlaceholderSize: true,
			forceHelperSize: false,
			helper: 'clone',
			opacity: 0.8,
			placeholder: 'lswssp-gallery-placeholder',
			containment: '.lswssp-post-sett-table',
		});
	}

	/* On Chage of Display Type */
	$(document).on('change', '.lswssp-display-type', function() {

		var cls_ele			= $(this).closest('.lswssp-sett-wrap');
		var display_type	= $(this).val();

		cls_ele.find('.lswssp-vtab-sett-wrap').hide();
		cls_ele.find('.lswssp-vtab-'+display_type+'-sett-wrap').fadeIn();

		$('.lswssp-vtab-nav:visible:first-child a').click();
	});

	/* Show / Hide JS */
	$( document ).on( 'change', '.lswssp-show-hide', function() {

		var prefix		= $(this).attr('data-prefix');
		var inp_type	= $(this).attr('type');
		var showlabel	= $(this).attr('data-label');

		if(typeof(showlabel) == 'undefined' || showlabel == '' ) {
			showlabel = $(this).val();
		}

		if( prefix ) {
			showlabel = prefix +'-'+ showlabel;
			$('.lswssp-show-hide-row-'+prefix).hide();
			$('.lswssp-show-for-all-'+prefix).show();
		} else {
			$('.lswssp-show-hide-row').hide();
			$('.lswssp-show-for-all').show();
		}

		$('.lswssp-show-if-'+showlabel).hide();
		$('.lswssp-hide-if-'+showlabel).hide();

		if( inp_type == 'checkbox' || inp_type == 'radio' ) {
			if( $(this).is(":checked") ) {
				$('.lswssp-show-if-'+showlabel).show();
			} else {
				$('.lswssp-hide-if-'+showlabel).show();
			}
		} else {
			$('.lswssp-show-if-'+showlabel).show();
		}
	});

	/* Open Attachment Popup */
	$(document).on('click', '.lswssp-img-wrp .lswssp-edit-img', function(){
		
		$('.lswssp-img-data-wrp').show();
		$('.lswssp-popup-overlay').show();
		$('body').addClass('lswssp-no-overflow');
		
		$('.lswssp-img-loader').show();
		$('.lswssp-img-data-wrp .lswssp-error').hide().html('');

		var current_obj 	= $(this);
		var cls_wrap		= current_obj.closest('.lswssp-imgs-preview');
		var attachment_id 	= current_obj.closest('.lswssp-img-wrp').find('.lswssp-attachment-no').val();

		var data = {
						action			: 'lswss_get_attachment_edit_form',
						attachment_id	: attachment_id,
						nonce			: cls_wrap.attr('data-nonce'),
					};

		$.post(ajaxurl, data, function(result) {
			
			if( result.success == 1 ) {
				
				$('.lswssp-img-data-wrp .lswssp-popup-body-wrp').html( result.data );

			} else {

				$('.lswssp-img-data-wrp .lswssp-error').html(result.msg).fadeIn();
			}

			$('.lswssp-img-loader').hide();
		});
	});

	/* Close Popup */
	$(document).on('click', '.lswssp-popup-close', function(){
		lswss_hide_popup();
	});

	/* `Esc` key is pressed */
	$(document).on('keyup', function(e) {
		if (e.keyCode == 27) {
			lswss_hide_popup();
		}
	});

	/* Save Attachment Data */
	$(document).on('click', '.lswssp-save-attachment-data', function() {
		
		var current_obj = $(this);

		current_obj.attr('disabled','disabled');
		current_obj.parent().find('.spinner').css('visibility', 'visible');

		var data = {
						action			: 'lswss_save_attachment_data',
						attachment_id	: current_obj.attr('data-id'),
						form_data		: current_obj.closest('form.lswssp-attachment-form').serialize()
					};

		$.post(ajaxurl, data, function(result) {

			if( result.success == 1 ) {
				current_obj.closest('form').find('.lswssp-success').html(result.msg).fadeIn().delay(3000).fadeOut();
			} else if( result.success == 0 ) {
				current_obj.closest('form').find('.lswssp-error').html(result.msg).fadeIn().delay(3000).fadeOut();
			}
			current_obj.removeAttr('disabled','disabled');
			current_obj.parent().find('.spinner').css('visibility', '');
		});
	});

	/* Alert Confirmation */
	$('.lswssp-confirm').on('click', function() {
		if( confirm( LswssAdmin.confirm_msg ) ) {
			return true;
		}
		return false;
	});

})( jQuery );

/* Function to hide popup */
function lswss_hide_popup() {
	jQuery('.lswssp-img-data-wrp').hide();
	jQuery('.lswssp-popup-overlay').hide();
	jQuery('body').removeClass('lswssp-no-overflow');
	jQuery('.lswssp-img-data-wrp .lswssp-popup-body-wrp').html('');
}