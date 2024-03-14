(function($) {

	"use strict";

	/* Color Picker */
	if( $('.paoc-colorpicker').length > 0 ) {
		$('.paoc-colorpicker').wpColorPicker({
			width : 260,
		}).closest('td').addClass('paoc-colorpicker-wrap');
	}

	/* Vertical Tab */
	$( document ).on( "click", ".paoc-vtab-nav a", function() {

		$(".paoc-vtab-nav").removeClass('paoc-active-vtab');
		$(this).parent('.paoc-vtab-nav').addClass("paoc-active-vtab");

		var selected_tab = $(this).attr("href");
		$('.paoc-vtab-cnt').hide();

		/* Show the selected tab content */
		$(selected_tab).show();

		/* Pass selected tab */
		$('.paoc-selected-tab').val(selected_tab);
		return false;
	});

	/* Remain selected tab for user */
	if( $('.paoc-selected-tab').length > 0 ) {
		
		var sel_tab = $('.paoc-selected-tab').val();
		
		if( typeof(sel_tab) !== 'undefined' && sel_tab != '' && $(sel_tab).length > 0 ) {
			$('.paoc-vtab-nav [href="'+sel_tab+'"]').click();
		} else {
			$('.paoc-vtab-nav:first-child a').click();
		}
	}

	/* Show/Hide JS */
	$( document ).on( 'change', '.paoc-show-hide', function() {

		var prefix		= $(this).attr('data-prefix');
		var inp_type	= $(this).attr('type');
		var showlabel	= $(this).attr('data-label');

		if(typeof(showlabel) == 'undefined' || showlabel == '' ) {
			showlabel = $(this).val();
		}

		if( prefix ) {
			showlabel = prefix +'-'+ showlabel;
			$('.paoc-show-hide-row-'+prefix).hide();
			$('.paoc-show-for-all-'+prefix).show();
		} else {
			$('.paoc-show-hide-row').hide();
			$('.paoc-show-for-all').show();
		}

		$('.paoc-show-if-'+showlabel).hide();
		$('.paoc-hide-if-'+showlabel).hide();

		if( inp_type == 'checkbox' || inp_type == 'radio' ) {
			if( $(this).is(":checked") ) {
				$('.paoc-show-if-'+showlabel).show();
			} else {
				$('.paoc-hide-if-'+showlabel).show();
			}
		} else {
			$('.paoc-show-if-'+showlabel).show();
		}
	});

	/* Show Preview Popup Modal - Start */
	$(document).on('click', '.paoc-show-popup-modal', function() {

		var curr_ele	= $(this);
		var preview		= $(this).attr('data-preview');
		var popup_id	= $(this).data('popup-id');
		var main_ele	= $('.paoc-popup-modal');

		main_ele.find('.paoc-popup-modal-loader').show();

		$('.paoc-popup-modal').show();
		$('.paoc-popup-modal-overlay').show();
		$('body').addClass('paoc-no-overflow');

		if( preview == 1 ) {

			if( typeof(tinyMCE) != 'undefined' ) {
				tinyMCE.triggerSave();
			}

			var form_data 				= $('form#post').serialize();
			var preview_frame_data_src	= main_ele.find('.paoc-preview-frame').attr('data-src');

			$('body').append('<form action="'+preview_frame_data_src+'" method="post" class="paoc-preview-form" id="paoc-preview-form" target="paoc_preview_frame"></form>');
			$('#paoc-preview-form').append('<input type="hidden" name="paoc_preview_form_data" value="'+form_data+'" /> <input type="hidden" name="paoc_preview_popup_id" value="'+popup_id+'" />');
			$('#paoc-preview-form').trigger('submit');
			$('#paoc-preview-form').remove();

		} else {
			main_ele.find('.paoc-popup-modal-loader').fadeOut();
		}

		return false;
	});

	/* Tweak - To display preview link in admin bar menu */
	if( $('.paoc-popup-preview-btn').length > 0 ) {

		var curr_ele	= $('.paoc-popup-preview-btn');
		var preview		= curr_ele.attr('data-preview');
		var popup_id	= curr_ele.attr('data-popup-id');
		var btn_text	= curr_ele.text();

		if( preview == 1 ) {
			$('#wpadminbar #wp-admin-bar-top-secondary').append('<li id="wp-admin-bar-paoc-preview-menu" class="paoc-show-popup-modal" data-preview="1" data-popup-id="'+popup_id+'"><div class="ab-item ab-empty-item">'+btn_text+'</div></li>');
		}
	}

	/* Show Popup Tags */
	$(document).on('click', '.paoc-show-popup-tags', function() {

		var curr_ele	= $(this);
		var data_tags	= $(this).attr('data-tags');
		var main_ele	= $('.paoc-popup-tags');

		main_ele.find('.paoc-popup-modal-loader').show();

		/* If Notification tags are there */
		if( data_tags == 'notification' ) {

			$('.paoc-notification-tags').show();

		} else { // Else General tags are there

			$('.paoc-general-tags').show();
		}

		$('.paoc-popup-modal-overlay').show();
		$('body').addClass('paoc-no-overflow');

		main_ele.find('.paoc-popup-modal-loader').fadeOut();

		return false;
	});

	/* On load iframe */
	$('.paoc-popup-modal').find('.paoc-preview-frame').on('load', function () {
		$('.paoc-popup-modal').find('.paoc-popup-modal-loader').fadeOut();
	});
	/* Show Preview Popup Modal - End */

    /* Close Popup Preview */
	$(document).on('click', '.paoc-popup-close', function() {
		popupaoc_hide_popup();
		popupaoc_hide_popup_modal();
	});

	/* Initialize Select 2 */
	if( $('.paoc-select2').length > 0 ) {

		$('.paoc-select2').select2({
		});
	}

	/* Initialize Select 2 with Ajax */
	if( $('.paoc-post-title-sugg').length > 0 ) {

		/* Ajax suggest post title based on post type */
		$('.paoc-post-title-sugg').each(function() {

			var cls_ele			= $(this).closest('.paoc-report-form-wrp');
			var meta_data		= $(this).attr('data-meta');
			var nonce			= $(this).attr('data-nonce');
			var post_type_attr	= $(this).attr('data-post-type');
			var post_status		= $(this).attr('data-post-status');
			var predefined		= $(this).attr('data-predefined');

			$(this).select2({
				ajax: {
					url				: ajaxurl,
					dataType		: 'json',
					delay			: 500,
					data			: function ( params ) {
										var search_term = params.term.trim();
										var post_type	= post_type_attr ? post_type_attr : cls_ele.find('.paoc-post-type-sugg').val();

										delay: 0;

										return {
											action		: 'popupaoc_post_title_sugg',
											search		: search_term,
											post_type	: post_type,
											meta_data	: meta_data,
											post_status	: post_status,
											nonce		: nonce,
										};
									},
					processResults	: function( data ) {
										var options = [];

										if( predefined ) {
											options = JSON.parse( predefined );
											options = $.makeArray(options);
										}

										if ( data ) {
											$.each( data, function( index, text ) {
												options.push( { id: text[0], text: text[1]  } );
											});
										}
										return {
											results: options
										};
									},
					cache			: true
				},
				minimumInputLength	: 1,
				allowClear			: true,
				closeOnSelect		: false,
				language			: {
										inputTooShort : function() {
											return PaocAdmin.select2_input_too_short;
										},
										removeAllItems : function() {
											return PaocAdmin.select2_remove_all_items;
										},
										removeItem : function() {
											return PaocAdmin.select2_remove_item;
										},
										searching : function() {
											return PaocAdmin.select2_searching;
										}
									}

			});
		});
	}

	/* Media Uploader */
	$( document ).on( 'click', '.paoc-image-upload', function() {

		var imgfield, showfield, file_frame, parent_td, attachment_id;

		imgfield	= jQuery(this).prev('input').attr('id');
		parent_td	= jQuery(this).parents('td');
		showfield	= jQuery(this).parents('td').find('.paoc-img-view');

		/* If the media frame already exists, reopen it. */
		if ( file_frame ) {
			file_frame.open();
			return;
		}

		/* Create the media frame. */
		file_frame = wp.media.frames.file_frame = wp.media({
			frame		: 'post',
			state		: 'insert',
			title		: jQuery(this).data( 'uploader-title' ),
			button		: {
							text : jQuery(this).data( 'uploader-button-text' ),
						},
			multiple	: false  /* Set to true to allow multiple files to be selected */
		});

		file_frame.on( 'menu:render:default', function(view) {
			/* Store our views in an object. */
			var views = {};

			/* Unset default menu items */
			view.unset('library-separator');
			view.unset('gallery');
			view.unset('featured-image');
			view.unset('embed');
			view.unset('playlist');
			view.unset('video-playlist');

			/* Initialize the views in our view object. */
			view.set(views);
		});

		/* When an image is selected, run a callback. */
		file_frame.on( 'insert', function() {

			/* Get selected size from media uploader */
			var selected_size = $('.attachment-display-settings .size').val();

			var selection = file_frame.state().get('selection');
			selection.each( function( attachment, index ) {
				attachment		= attachment.toJSON();
				attachment_id	= attachment.id;

				/* Selected attachment url from media uploader */
				var attachment_url = attachment.sizes[selected_size].url;

				parent_td.find('.popup-image-id').val( attachment_id );

				if(index == 0){
					/* place first attachment in field */
					$('#'+imgfield).val(attachment_url);
					showfield.html('<img src="'+attachment_url+'" />');
					
				} else{
					$('#'+imgfield).val(attachment_url);
					showfield.html('<img src="'+attachment_url+'" />');
				}
			});
		});

		/* Finally, open the modal */
		file_frame.open();
	});

	/* Clear Media */
	$( document ).on( 'click', '.paoc-image-clear', function() {

		$(this).parent().find('.paoc-img-upload-input').val('');
		$(this).parent().find('.paoc-img-view').html('');
		$(this).parent().find('.popup-image-id').val('');
	});

	/* WP Code Editor */
	if( PaocAdmin.code_editor == 1 && PaocAdmin.syntax_highlighting == 1 ) {
		jQuery('.wpos-code-editor').each( function() {

			var cur_ele		= jQuery(this);
			var data_mode	= cur_ele.attr('data-mode');
			data_mode		= data_mode ? data_mode : 'css';

			if( cur_ele.hasClass('wpos-code-editor-initialized') ) {
				return;
			}

			var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
			editorSettings.codemirror = _.extend(
				{},
				editorSettings.codemirror,
				{
					indentUnit	: 2,
					tabSize		: 2,
					mode		: data_mode,
				}
			);
			var editor = wp.codeEditor.initialize( cur_ele, editorSettings );
			cur_ele.addClass('wpos-code-editor-initialized');

			editor.codemirror.on( 'change', function( codemirror ) {
				cur_ele.val( codemirror.getValue() ).trigger( 'change' );
			});

			/* When post metabox is toggle */
			$(document).on('postbox-toggled', function( event, ele ) {
				if( $(ele).hasClass('closed') ) {
					return;
				}

				if( $(ele).find('.wpos-code-editor').length > 0 ) {
					editor.codemirror.refresh();
				}
			});

			/* When setting tab is opened */
			$(document).on('click', '.paoc-vtab-nav a', function() {

				var selected_tab = $(this).attr("href");

				if( $(selected_tab).find('.wpos-code-editor').length > 0 ) {
					editor.codemirror.refresh();
				}
			});
		});
	}

	/* Comman cofirm alert box */
	$(document).on('click', '.paoc-confirm', function(e) {
		var confirm = popupaoc_confirm();

		return confirm;
	});

	/* Click to Copy the Text */
	$(document).on('click', '.paoc-copy-clipboard', function() {
		var copyText = $(this);
		copyText.select();
		document.execCommand("copy");
	});

	/* Save Data on Ctrl + S - Start */
	if( adminpage == 'post-php' ) {
		var paoc_save_btn = $('#publish');
	} else {
		var paoc_save_btn = $('.paoc-sett-submit');
	}

	if( paoc_save_btn.length > 0 ) {
		$(window).on('keydown', function(event) {
			if ( (event.ctrlKey || event.metaKey) && (String.fromCharCode(event.which).toLowerCase() == 's') ) {

				event.preventDefault();
				paoc_save_btn.trigger('click');
				return;
			}
		});
	}
	/* Save Data on Ctrl + S - End */
})(jQuery);

/* Function to hide popup */
function popupaoc_hide_popup() {
	jQuery('.paoc-popup-overlay').hide();
	jQuery('body').removeClass('paoc-no-overflow');
}

/* Function to hide preview popup modal */
function popupaoc_hide_popup_modal() {
	jQuery('.paoc-popup-modal').hide();
	jQuery('.paoc-popup-tags').hide();
	jQuery('.paoc-popup-modal-overlay').hide();
	jQuery('body').removeClass('paoc-no-overflow');
}

/* Common alert message function */
function popupaoc_confirm( msg ) {

	var msg	= jQuery(this).attr('data-msg');
	msg		= msg ? msg : PaocAdmin.cofirm_msg;

	var ans = confirm(msg);

	if(ans) {
		return true;
	} else {
		return false;
	}
}