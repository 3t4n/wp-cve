(function( $ ) {

	"use strict";

	/* Colorpicker */
	if( $('.apwpultimate-color-box').length > 0 ) {
		$('.apwpultimate-color-box').wpColorPicker();
	}

	/* Media Uploader */
	$( document ).on( 'click', '.apwpultimate-audio-file-uploader', function() {

			var file_frame;

			/* new media uploader */
			var button = jQuery(this);

			/* If the media frame already exists, reopen it. */
			if ( file_frame ) {
				file_frame.open();
				return;
			}

			/* Create the media frame. */
			file_frame = wp.media.frames.file_frame = wp.media({
				frame: 'post',
				state: 'insert',
				title: button.data( 'uploader-title' ),
				button: {
					text: button.data( 'uploader-button-text' ),
				},
				multiple: false  /* Set to true to allow multiple files to be selected */
			});

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
			file_frame.on( 'insert', function() {

				/* Get selected size from media uploader */
				var selected_size = $('.attachment-display-settings .size').val();

				var selection = file_frame.state().get('selection');
				selection.each( function( attachment, index ) {
					attachment = attachment.toJSON();

					/* Selected attachment url from media uploader */
					var artist_name = attachment.meta.artist;
					if(artist_name == false){ artist_name = ''; }
					var attachment_url = attachment.url;
					$("#apwpultimate-audio-file").val(attachment_url);
					$("#apwpultimate-duration").val(attachment.fileLength);
					$("#apwpultimate-artist-name").val(artist_name);
				});
			});

			/* Finally, open the modal */
			file_frame.open();

	});
	/* Clear Media */
	$( document ).on( 'click', '.audio-file-clear', function() {
		$(this).parent().find('.apwpultimate-audio-file').val('');
		$('.apwpultimate-post-sett-tbl').find('.apwpultimate-duration').val('');
		$('.apwpultimate-post-sett-tbl').find('.apwpultimate-artist-name').val('');
	});

	/* WP Code Editor */
	if( ApwpultimateAdmin.code_editor == 1 && ApwpultimateAdmin.syntax_highlighting == 1 ) {
		jQuery('.apwpultimate-code-editor').each( function() {
			
			var cur_ele		= jQuery(this);
			var data_mode	= cur_ele.attr('data-mode');
			data_mode		= data_mode ? data_mode : 'css';

			if( cur_ele.hasClass('apwpultimate-code-editor-initialized') ) {
				return;
			}

			var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
			editorSettings.codemirror = _.extend(
				{},
				editorSettings.codemirror,
				{
					indentUnit: 2,
					tabSize: 2,
					mode: data_mode,
				}
			);
			var editor = wp.codeEditor.initialize( cur_ele, editorSettings );

			cur_ele.addClass('apwpultimate-code-editor-initialized');

			editor.codemirror.on( 'change', function( codemirror ) {
				cur_ele.val( codemirror.getValue() ).trigger( 'change' );
			});

			/* When post metabox is toggle */
			$(document).on('postbox-toggled', function( event, ele ) {
				if( $(ele).hasClass('closed') ) {
					return;
				}

				if( $(ele).find('.apwpultimate-code-editor').length > 0 ) {
					editor.codemirror.refresh();
				}
			});
		});
	}

	/* Reset Settings Button */
	$( document ).on( 'click', '.apwpultimate-confirm', function() {

		var msg	= $(this).attr('data-msg');
		msg 	= msg ? msg : ApwpultimateAdmin.reset_msg;
		var ans = confirm(msg);

		if(ans) {
			return true;
		} else {
			return false;
		}
	});

})(jQuery);