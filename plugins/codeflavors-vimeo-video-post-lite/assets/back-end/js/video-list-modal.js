/**
 * Modal window for videos list functionality
 */
;(function($){

	var categories_select = function(){

		var select = $('#cvm_video_categories'),
			categoriesContainer = window.parent.jQuery('#cvm-categories-list'),
			m = window.parent.CVM_SHORTCODE_MODAL,
			inputField = window.parent.jQuery('input[name=cvm_selected_categories]'),
			in_list	= $.grep( $(inputField).val().split('|'), function(val){ return '' != val });

		$('#cvm_add_category').on( 'click', function(event){
			event.preventDefault();
			var cat_id = parseInt( select.val() );
			in_list	= $.grep( $(inputField).val().split('|'), function(val){ return '' != val });
			//if( 0 != cat_id ){
				if( -1 == $.inArray( cat_id, in_list ) ){				
					if( in_list.length == 0 ){
						$(categoriesContainer).empty();
					}	
					
					in_list = $.merge( in_list, [cat_id] );	

					var text = $("#cvm_video_categories option:selected").text(),
						container = $('<div />',{
							'class' : 'cvm-category-item',
							'id' : 'category-item-' + cat_id,
							'html' : text
						}).appendTo( categoriesContainer );

					$('<a />', {
						'id' 	: 'cvm-del-cat-' + cat_id,
						'data-post_id' : cat_id,
						'data-post_type' : $('#cvm_post_type').val(),
						'class' : 'cvm-del-cat-item',
						'html' 	: m.deleteCategory,
						'href' 	: '#',
						'on': {
							'click': function(e){
								e.preventDefault();
								$(container).remove();

								in_list = $.grep( in_list, function(value, i){
									return cat_id != value;
								});
								if( in_list.length == 0 ){
									$(categoriesContainer).empty().html( '<em>'+m.no_categories+'</em>' );
								}
								$(inputField).val( in_list.join('|') );
							}
						}

					}).prependTo(container);

					$(inputField).val( in_list.join('|') );	
				}		
			//}
		})
	}

	$(document).ready(function(){
		
		categories_select();

		// check all functionality
		var chkbxs = $('#cb-select-all-1, #cb-select-all-2, .cvm-video-list-select-all');		
		$(chkbxs).on( 'click', function(){
			if( $(this).is(':checked') ){
				$('.cvm-video-checkboxes').attr('checked', 'checked').trigger('change');
				$(chkbxs).attr('checked', 'checked');
			}else{
				$('.cvm-video-checkboxes').removeAttr('checked').trigger('change');
				$(chkbxs).removeAttr('checked');
			}
		});
		
		// some elements
		var playlistItemsContainer 	= window.parent.jQuery('#cvm-list-items'),
			m						= window.parent.CVM_SHORTCODE_MODAL,
			inputField				= $( window.parent.jQuery('#cvm-playlist-items') ).find('input[name=cvm_selected_items]'),
			in_playlist				= $.grep( $(inputField).val().split('|'), function(val){ return '' != val });
			
		
		// check boxes on load
		if(in_playlist.length > 0){
			$.each( in_playlist, function(i, post_id){
				$('#cvm-video-'+post_id).attr('checked', 'checked');
			});
		}
		
		window.parent.jQuery('a.cvm-del-item').on( 'click', function(e){
			e.preventDefault();
			var post_id = $(this).data('post_id');
			
			$('#cvm-video-'+post_id).removeAttr('checked');
			$( window.parent.jQuery('#playlist_item_'+post_id) ).remove();
			
			in_playlist = $.grep( in_playlist, function(value, i){
				return post_id != value;
			});							
			if( in_playlist.length == 0 ){
				$(playlistItemsContainer).empty().html( '<em>'+m.no_videos+'</em>' );				
			}							
			$(inputField).val( in_playlist.join('|') );
		});
		
		
		// checkboxes functionality
		$('.cvm-video-checkboxes').on( 'change', function(){
			var post_id = $(this).val();			
			if( $(this).is(':checked') ){				
				if( in_playlist.length == 0 ){
					$(playlistItemsContainer).empty();
				}				
				if( -1 == $.inArray( post_id, in_playlist ) ){				
					in_playlist = $.merge(in_playlist, [post_id]);				
					var c = $('<div />', {
						'class'	: 'playlist_item',
						'id' 	: 'playlist_item_'+post_id,
						'html' 	: $('#title'+post_id).html() + ' <span class="duration">[' + $('#duration'+post_id).html() + ']</span>'
					}).appendTo( playlistItemsContainer );
					
					$('<a />', {
						'id' 	: 'cvm-del-'+post_id,
						'data-post_id' : post_id,
						'class' : 'cvm-del-item',
						'html' 	: m.deleteItem,
						'href' 	: '#',
						'click' : function(e){
							e.preventDefault();
							$('#cvm-video-'+post_id).removeAttr('checked');
							$(c).remove();
							
							in_playlist = $.grep( in_playlist, function(value, i){
								return post_id != value;
							});							
							if( in_playlist.length == 0 ){
								$(playlistItemsContainer).empty().html( '<em>'+m.no_videos+'</em>' );				
							}							
							$(inputField).val( in_playlist.join('|') );
						}
					}).prependTo(c);					
				}				
			}else{
				in_playlist = $.grep( in_playlist, function(value, i){
					if( post_id == value ){
						$(playlistItemsContainer).find('div#playlist_item_'+post_id).remove();
					}					
					return post_id != value;
				})
			}			
			if( in_playlist.length == 0 ){
				$(playlistItemsContainer).empty().html( '<em>'+m.no_videos+'</em>' );				
			}
			
			$(playlistItemsContainer).removeClass('error');
			
			$(inputField).val( in_playlist.join('|') );
		});
		
		
		// single shortcode
		var form = $('#cvm-video-list-form'),
			attsContainer = $('#cvm-shortcode-atts'),
			divId = false;
		
		$('.cvm-show-form').on( 'click', function(e){
			e.preventDefault();
			var post_id = $(this).attr('id').replace('cvm-embed-', '');
			divId = 'single-video-settings-'+post_id;
			
			$(form).hide();
			$(attsContainer).html( $('#'+divId).html() );
			$('#'+divId).empty();
		})
		
		$(document).on( 'click', '.cvm-cancel-shortcode', function(e){
			e.preventDefault();
			
			var post_id = $(this).attr('id').replace('cancel', ''),
			divId = 'single-video-settings-'+post_id;;
			
			$('#'+divId).html( $(attsContainer).html() );
			$(attsContainer).empty();
			$(form).show();
		})
		
		$(document).on('click', '.cvm-insert-shortcode', function(e){
			e.preventDefault();
			var post_id = $(this).attr('id').replace('shortcode', ''),
				divId	= 'single-video-settings-'+post_id,
				fields 	= $('#'+divId).find('input, select');
			
			var volume = $('#cvm_volume'+post_id).val(),
				width = $('#cvm_width'+post_id).val(),
				aspect = $('#cvm_aspect_ratio'+post_id).val(),
				autoplay = $('#cvm_autoplay'+post_id).is(':checked') ? 1 : 0,
				loop = $('#cvm_loop'+post_id).is(':checked') ? 1 : 0,		
				controls = $('#cvm_controls'+post_id).is(':checked') ? 1 : 0;
			
			var shortcode = '[cvm_video id="'+post_id+'" volume="'+volume+'" width="'+width+'" aspect_ratio="'+aspect+'" loop="'+loop+'" autoplay="'+autoplay+'"]';
			
			var divId = 'single-video-settings-'+post_id;;			
			$('#'+divId).html( $(attsContainer).html() );
			$(attsContainer).empty();
			$(form).show();
			
			window.parent.send_to_editor(shortcode);
			
			window.parent.jQuery(window.parent.window.CVMVideo_DIALOG_WIN).dialog('close');
			
		})
		
	});	
})(jQuery);
