	window.scrollTo(0,0);
	var send = window.template_send_to_editor;

	// setup media uploader
	jQuery( 'a.template-media-upload' ).each(function(){
		var rel = jQuery( this ).attr( 'rel' );
		jQuery( this ).click( function(){
			window.send_to_editor = function( html ) {
				if ( html.match( /<img / ) ) {
					if ( jQuery( html ).is( 'a' ) ) {
						var img_obj = jQuery( 'img', html );							
					} else {
						var img_obj = jQuery( html );							
					}		
					id = img_obj.attr( 'class' ).match( /wp-image-\d{1,}/ );
//					id = jQuery( 'img', html ).attr( 'class' ).match( /wp-image-\d{1,}/ );
				} else {
					id = html.match( / class=\"wp-media-\d{1,}/ );
					id = id.toString().slice(8);
				}
				id = id.toString().slice(9);
				var data = {
					action: 'get_template_thumbnail_size',
					id: id
				};

				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				jQuery.post(ajaxurl, data, function(response) {
					if ( response ) {
						var img_vars = response.split( '|' );
						jQuery('#'+rel+'-image').attr({
							'src'		: img_vars[0],
							'width'		: img_vars[1],
							'height'	: img_vars[2]
						});
					}
				});
				jQuery('#'+rel).val(id);

				tb_remove();
			}
			formfield = jQuery('#'+rel).attr('name');

			var post_id = jQuery('#post_ID').val();
//			if( isNaN( post_id ) ){
//				post_id = 0 ;
//			}
			tb_show( null, 'media-upload.php?post_id=0&type=image&TB_iframe=true' );
//			tb_show( null, 'media-upload.php?post_id='+post_id+'&type=image&tab=type&TB_iframe=true' );
			return false;
		});
	});
	
	// setup visual editor
	jQuery('#post_editor_box a.thickbox').each(function(){
		jQuery(this).click(function(){
			window.send_to_editor = send;
		});
	});


// send html to the post editor
function template_send_to_editor(h) {
	var ed;

	if ( typeof tinyMCE != 'undefined' && ( ed = tinyMCE.activeEditor ) && !ed.isHidden() ) {
		ed.focus();
		if ( tinymce.isIE )
			ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark);

		if ( h.indexOf('[caption') === 0 ) {
			if ( ed.plugins.wpeditimage )
				h = ed.plugins.wpeditimage._do_shcode(h);
		} else if ( h.indexOf('[gallery') === 0 ) {
			if ( ed.plugins.wpgallery )
				h = ed.plugins.wpgallery._do_gallery(h);
		} else if ( h.indexOf('[embed') === 0 ) {
			if ( ed.plugins.wordpress )
				h = ed.plugins.wordpress._setEmbed(h);
		}

		ed.execCommand('mceInsertContent', false, h);

	} else if ( typeof edInsertContent == 'function' ) {
		edInsertContent(edCanvas, h);
	} else {
		jQuery( edCanvas ).val( jQuery( edCanvas ).val() + h );
	}

	tb_remove();
}

// thickbox settings
var tb_position;
(function($) {
	tb_position = function() {
		var tbWindow = $('#TB_window'), width = $(window).width(), H = $(window).height(), W = ( 720 < width ) ? 720 : width, adminbar_height = 0;

		if ( $('body.admin-bar').length )
			adminbar_height = 28;

		if ( tbWindow.size() ) {
			tbWindow.width( W - 50 ).height( H - 45 - adminbar_height );
			$('#TB_iframeContent').width( W - 50 ).height( H - 75 - adminbar_height );
			tbWindow.css({'margin-left': '-' + parseInt((( W - 50 ) / 2),10) + 'px'});
			if ( typeof document.body.style.maxWidth != 'undefined' )
				tbWindow.css({'top': 20 + adminbar_height + 'px','margin-top':'0'});
		};

		return $('a.thickbox').each( function() {
			var href = $(this).attr('href');
			if ( ! href ) return;
			href = href.replace(/&width=[0-9]+/g, '');
			href = href.replace(/&height=[0-9]+/g, '');
			$(this).attr( 'href', href + '&width=' + ( W - 80 ) + '&height=' + ( H - 85 - adminbar_height ) );
		});
	};

	$(window).resize(function(){ tb_position(); });

})(jQuery);
