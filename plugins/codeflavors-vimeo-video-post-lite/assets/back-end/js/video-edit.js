/**
 * 
 */
;(function($){
	$(document).ready(function(){
		
		$(document).on('change', '.cvm_aspect_ratio', function(){
			var aspect_ratio_input 	= this,
				parent				= $(this).parents('.cvm-player-settings-options'),
				width_input			= $(parent).find('.cvm_width'),
				height_output		= $(parent).find('.cvm_height');		
			
			var val = $(this).val(),
				w 	= Math.round( parseInt($(width_input).val()) ),
				h 	= 0;
			switch( val ){
				case '4x3':
					h = Math.floor((w*3)/4);
				break;
				case '16x9':
					h = Math.floor((w*9)/16);
				break;
				case '2.35x1':
					h = Math.floor(w/2.35);
				break;	
			}
			$(height_output).html(h);						
		});
		
		$(document).on('change', '.cvm_widget_post_type', function(e){

			var self = this;
				tax_select = $(this).parent('p').next().find('.cvm_widget_taxonomy');
				data = {
					post_type: $(this).val(),
					name: $(tax_select).attr('name'),
					id: $(tax_select).attr('id'),
					action: 'cvm_post_type_categories'	
				};

			var s = $('<span>',{
				'class':'cvm_loading'
			}).insertAfter( $(tax_select) );

			$(tax_select).prop( 'disabled', true );
			
			$.ajax({
				type 	: 'post',
				url 	: ajaxurl,
				data	: data,
				success	: function(response){
					if( response.success ){
						$(tax_select).after( response.data.message ).remove();
						$(tax_select).prop( 'disabled', false );
						$(s).remove();
					}
					if( response.error ){
						
					}
				}
			});	

		});

		$(document).on( 'keyup', '.cvm_width', function(){
			var parent				= $(this).parents('.cvm-player-settings-options'),
				aspect_ratio_input	= $(parent).find('.cvm_aspect_ratio');		
						
			if( '' == $(this).val() ){
				return;				
			}
			var val = Math.round( parseInt( $(this).val() ) );
			$(this).val( val );	
			$(aspect_ratio_input).trigger('change');
		});
				
		
		// hide options dependant on controls visibility
		$('.cvm_controls').on( 'click', function(e){
			if( $(this).is(':checked') ){
				$('.controls_dependant').show();
			}else{
				$('.controls_dependant').hide();
			}
		})
		
		// in widgets, show/hide player options if latest videos isn't displayed as playlist
		$(document).on('click', '.cvm-show-as-playlist-widget', function(){
			var parent 		= $(this).parents('.cvm-player-settings-options'),
				player_opt 	= $(parent).find('.cvm-recent-videos-playlist-options'),
				list_thumbs = $(parent).find('.cvm-widget-show-vim-thumbs');
			if( $(this).is(':checked') ){
				$(player_opt).show();
				$(list_thumbs).hide();
			}else{
				$(player_opt).hide();
				$(list_thumbs).show();
			}
			
		})

		$(document).on( 'change', '.cvm_playlist_theme', function(){
			$('.cvm-theme-customize').hide();
			$('.cvm-theme-customize.' + $(this).val() ).show();
		});

		if( $('#cvm_color').length > 0 ) {
			// hack for values from version < 2.0
			$('#cvm_color').val('#' + $('#cvm_color').val().replace('#', ''));
			$('#cvm_color, [data-colorPicker="true"]').wpColorPicker({
				change: function () {
				},
				clear: function () {
				}
			});
		}
	});
})(jQuery);