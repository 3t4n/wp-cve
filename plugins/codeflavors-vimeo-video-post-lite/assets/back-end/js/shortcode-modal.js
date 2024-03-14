/**
 * TinyMce playlist shortcode insert 
 */
var CVMVideo_DIALOG_WIN = false;
;(function($){
	$(document).ready(function(){
		$(document).on('click', '#cvm-insert-playlist-shortcode', function(e){
			e.preventDefault();
			var videos 		= $('#cvm-playlist-items').find('input[name=cvm_selected_items]').val(),
				categories = $('input[name=cvm_selected_categories]').val();
			if( '' == videos && '' == categories ){
				$('#cvm-list-items').addClass('error');
				return;
			}
			
			var theme = $('#cvm_playlist_theme').val(),
				order = $('#cvm_playlist_posts_order').val(),
				aspect = $('#aspect_ratio').val(),
				width = $('#width').val(),
				volume = $('#volume').val(),
				title = $('#cvm_title[type=checkbox]').is(':checked') ? 1 : 0,
				byline = $('#cvm_byline[type=checkbox]').is(':checked') ? 1 : 0,
				portrait = $('#cvm_portrait[type=checkbox]').is(':checked') ? 1 : 0,
				playlist_loop = $('#playlist_loop[type=checkbox]').is(':checked') ? 1 : 0;

			if( typeof wp.hooks.applyFilters !== 'undefined' ){
				extra_theme_opts = wp.hooks.applyFilters( 'vimeotheque.classicEditor.playlistShortcode.params', '' );
			}else{
				extra_theme_opts = '';
			}

			
			if( 'default' == theme ){
				extra_theme_opts += 'layout="' + $('input[name=cvm_theme_default_layout]:checked').val() + '"';
				extra_theme_opts += ' show_excerpts="' + ( $('input[name=cvm_theme_default_show_excerpts]').is(':checked') ? 1 : 0 ) + '"';
				extra_theme_opts += ' use_original_thumbnails="' + ( $('input[name=cvm_theme_default_use_original_thumbnails]').is(':checked') ? 1 : 0 ) + '"';
			}

			var videos_array = $.grep( videos.split('|'), function(val){ return '' != val }),
				categories_array = $.grep( categories.split('|'), function(val){ return '' != val }),
				pt_obj = {},
				s_videos = '',
				s_categories = '',
				s_post_types = '';
			
			if( videos_array.length > 0 ){
				s_videos = ' videos="'+( videos_array.join(',') )+'"';
			}			
			if( categories_array.length > 0 ){
				s_categories = ' categories="'+( categories_array.join(',') )+'"';
				$.each( $('#cvm-categories-list .cvm-category-item a'), function(i, v){
					var pt = $(v).data('post_type');
					pt_obj[ pt ] = pt;
				});
				if( Object.keys(pt_obj).length > 0 ){
					s_post_types = ' post_type="' + Object.keys(pt_obj).join(',') + '"';
				}
			}

			var	shortcode 	= '[cvm_playlist theme="' + theme + '" '+ extra_theme_opts + ' order="' + order + '"' + ' aspect_ratio="' + aspect + '" width="' + width + '" volume="' + volume + '" title="'+title+'" byline="'+byline+'" portrait="'+portrait+'" playlist_loop="' + playlist_loop + '"' + s_videos + s_categories + s_post_types + ']';;
			
			$('#cvm-playlist-items').find('input[name=cvm_selected_items]').val('');
			$('#cvm-list-items').empty().html(CVM_SHORTCODE_MODAL.no_videos);

			$('input[name=cvm_selected_categories]').val('');
			$('#cvm-categories-list').empty().html(CVM_SHORTCODE_MODAL.no_categories);
			
			var iframe = $('#cvm-display-videos').find('iframe');
			$('input[type=checkbox]', iframe.contents()).removeAttr('checked');
			
			send_to_editor(shortcode);

			$(CVMVideo_DIALOG_WIN).dialog('close');
		});
		
		$('#cvm-shortcode-2-post').on( 'click', function(e){
			e.preventDefault();
			if( CVMVideo_DIALOG_WIN ){
				CVMVideo_DIALOG_WIN.dialog('open');
			}
		});

		var titles = $('#cvm-playlist-items .inside').children('h3');
		$(titles).on( 'click', function(){
			var n = $(this).next();
			$(n).toggle();
			if( $(n).is(':visible') ){
				$(this).removeClass('closed');
			}else{
				$(this).addClass('closed');
			}
		})
		
		var editorZIndex = $('#wp-content-wrap').css( 'z-index');
		
		var dialog = $('#CVMVideo_Modal_Window').dialog({
			'autoOpen'		: false,
			'width'			: '90%',
			'height'		: 750,
			'maxWidth'		: '90%',
			'maxHeight'		: 750,
			'minWidth'		: '90%',
			'minHeight'		: 750,
			'modal'			: true,
			'dialogClass'	: 'wp-dialog',
			'title'			: '',
			'resizable'		: true,
			'open'			:function(ui){
				$('#wp-content-wrap').css( 'z-index', 30 );
			},
			'close':function(ui){
				$('#wp-content-wrap').css( 'z-index', editorZIndex );
			}
		})		
		CVMVideo_DIALOG_WIN = dialog;	

		// theme options
		var theme = $('#cvm_playlist_theme').val();
		$( '.cvm-theme-customize.' + theme ).show();

		$('#cvm_playlist_theme').on( 'change', function(){
			$('.cvm-theme-customize').hide();
			$('.cvm-theme-customize.' + $(this).val() ).show();
		});
			
	});
})(jQuery);