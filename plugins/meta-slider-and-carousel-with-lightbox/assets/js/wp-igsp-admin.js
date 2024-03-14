(function($) {

	"use strict";

	/* Media Uploader */
	$( document ).on( 'click', '.wp-igsp-img-uploader', function() {

		var imgfield, showfield, multiple_img, file_frame;
		imgfield		= jQuery(this).prev('input').attr('id');
		showfield		= jQuery(this).parents('td').find('.wp-igsp-imgs-preview');
		multiple_img	= jQuery(this).attr('data-multiple');
		multiple_img	= (typeof(multiple_img) != 'undefined' && multiple_img == 'true') ? true : false;

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
			var selected_size	= $('.attachment-display-settings .size').val();
			var selection		= file_frame.state().get('selection');

			selection.each( function( attachment, index ) {

				attachment = attachment.toJSON();

				/* Selected attachment url from media uploader */
				var attachment_id = attachment.id ? attachment.id : '';
				if( attachment_id && attachment.sizes && multiple_img == true ) {

					var attachment_url			= attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
					var attachment_edit_link	= attachment.editLink ? attachment.editLink : '';

					showfield.append('\
						<div class="wp-igsp-img-wrp">\
							<div class="wp-igsp-img-tools wp-igsp-hide">\
								<span class="wp-igsp-tool-icon wp-igsp-edit-img dashicons dashicons-edit" title="'+WpIgspAdmin.img_edit_popup_text+'"></span>\
								<a href="'+attachment_edit_link+'" target="_blank" title="'+WpIgspAdmin.attachment_edit_text+'"><span class="wp-igsp-tool-icon wp-igsp-edit-attachment dashicons dashicons-visibility"></span></a>\
								<span class="wp-igsp-tool-icon wp-igsp-del-tool wp-igsp-del-img dashicons dashicons-no" title="'+WpIgspAdmin.img_delete_text+'"></span>\
							</div>\
							<img class="wp-igsp-img" src="'+attachment_url+'" alt="" />\
							<input type="hidden" class="wp-igsp-attachment-no" name="wp_igsp_img[]" value="'+attachment_id+'" />\
						</div>\
							');
					showfield.find('.wp-igsp-img-placeholder').hide();
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
				showfield.html('<img src="'+attachment_url+'" alt="" />');
			});
		});

		/* Finally, open the modal */
		file_frame.open();
	});

	/* Remove Single Gallery Image */
	$(document).on('click', '.wp-igsp-del-img', function(){

		$(this).closest('.wp-igsp-img-wrp').fadeOut(300, function(){ 
			$(this).remove();

			if( $('.wp-igsp-img-wrp').length == 0 ){
				$('.wp-igsp-img-placeholder').show();
			}
		});
	});

	/* Remove All Gallery Image */
	$(document).on('click', '.wp-igsp-del-gallery-imgs', function() {

		var ans = confirm(WpIgspAdmin.all_img_delete_text);

		if( ans ) {
			$('.wp-igsp-gallery-imgs-wrp .wp-igsp-img-wrp').remove();
			$('.wp-igsp-img-placeholder').fadeIn();
		}
	});

	/* Image ordering (Drag and Drop) */
	if( $('.wp-igsp-gallery-imgs-wrp').length > 0 ) {
		$('.wp-igsp-gallery-imgs-wrp').sortable({
			items: '.wp-igsp-img-wrp',
			cursor: 'move',
			scrollSensitivity:40,
			forcePlaceholderSize: true,
			forceHelperSize: false,
			helper: 'clone',
			opacity: 0.8,
			placeholder: 'wp-igsp-gallery-placeholder',
			containment: '.wp-igsp-post-sett-table',
			start:function(event,ui){
				ui.item.css('background-color','#f6f6f6');
			},
			stop:function(event,ui){
				ui.item.removeAttr('style');
			}
		});
	}

	/* Open Attachment Data Popup */
	$(document).on('click', '.wp-igsp-img-wrp .wp-igsp-edit-img', function(){

		$('.wp-igsp-img-data-wrp').show();
		$('.wp-igsp-popup-overlay').show();
		$('body').addClass('wp-igsp-no-overflow');
		$('.wp-igsp-img-loader').show();

		var current_obj		= $(this);
		var attachment_id	= current_obj.closest('.wp-igsp-img-wrp').find('.wp-igsp-attachment-no').val();
		var nonce			= current_obj.closest('.wp-igsp-imgs-preview').attr('data-nonce');

		var data = {
					action			: 'wp_igsp_get_attachment_edit_form',
					attachment_id	: attachment_id,
					nonce			: nonce,
				};

		$.post(ajaxurl, data, function(result) {

			if( result.success == 1 ) {
				$('.wp-igsp-img-data-wrp  .wp-igsp-popup-body-wrp').html( result.data );
				$('.wp-igsp-img-loader').hide();
			}
		});
	});

	/* Close Popup */
	$(document).on('click', '.wp-igsp-popup-close', function(){
		wp_igsp_hide_popup();
	});

	/* `Esc` key is pressed */
	$(document).on('keyup', function(e) {
		if (e.keyCode == 27) {
			wp_igsp_hide_popup();
		}
	});

	/* Save Attachment Data */
	$(document).on('click', '.wp-igsp-save-attachment-data', function() {

		var current_obj = $(this);
		current_obj.attr('disabled','disabled');
		current_obj.parent().find('.spinner').css('visibility', 'visible');

		var data = {
						action			: 'wp_igsp_save_attachment_data',
						attachment_id	: current_obj.attr('data-id'),
						nonce			: current_obj.attr('data-nonce'),
						form_data		: current_obj.closest('form.wp-igsp-attachment-form').serialize()
					};

		$.post(ajaxurl, data, function(result) {

			if( result.success == 1 ) {
				current_obj.closest('form').find('.wp-igsp-success').html(result.msg).fadeIn().delay(3000).fadeOut();
			} else if( result.success == 0 ) {
				current_obj.closest('form').find('.wp-igsp-error').html(result.msg).fadeIn().delay(3000).fadeOut();
			}
			current_obj.prop('disabled', false);
			current_obj.parent().find('.spinner').css('visibility', '');
		});
	});

	/* Vertical Tab */
	$( document ).on( "click", ".igsp-vtab-nav a", function() {

		$(".igsp-vtab-nav").removeClass('igsp-active-vtab');
		$(this).parent('.igsp-vtab-nav').addClass("igsp-active-vtab");

		var selected_tab = $(this).attr("href");
		$('.igsp-vtab-cnt').hide();

		/* Show the selected tab content */
		$(selected_tab).show();

		/* Pass selected tab */
		$('.igsp-selected-tab').val(selected_tab);
		return false;
	});

	/* Remain selected tab for user */
	if( $('.igsp-selected-tab').length > 0 ) {

		var sel_tab = $('.igsp-selected-tab').val();

		if( typeof(sel_tab) !== 'undefined' && sel_tab != '' && $(sel_tab).length > 0 ) {
			$('.igsp-vtab-nav [href="'+sel_tab+'"]').click();
		} else {
			$('.igsp-vtab-nav:first-child a').click();
		}
	}

	/* Click to Copy the Text */
	$(document).on('click', '.wpos-copy-clipboard', function() {
		var copyText = $(this);
		copyText.select();
		document.execCommand("copy");
	});

	/* Drag widget event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.preview-rendered', wp_igsp_fl_render_preview );

	/* Save widget event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.layout-rendered', wp_igsp_fl_render_preview );

	/* Publish button event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.didSaveNodeSettings', wp_igsp_fl_render_preview );

})( jQuery );

/* Function to render shortcode preview for Beaver Builder */
function wp_igsp_fl_render_preview() {
	wp_igsp_slider_init();
	wp_igsp_carousel_init();
	wp_igsp_popup_init();
}

/* Function to hide popup */
function wp_igsp_hide_popup() {
	jQuery('.wp-igsp-img-data-wrp').hide();
	jQuery('.wp-igsp-popup-overlay').hide();
	jQuery('body').removeClass('wp-igsp-no-overflow');
	jQuery('.wp-igsp-img-data-wrp  .wp-igsp-popup-body-wrp').html('');
}