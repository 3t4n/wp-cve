/**
 * 
 */
;(function($){
	$(document).ready(function(){
		$(document).on('click', '#cvm-import-video-thumbnail', function(e){
			e.preventDefault();
			
			var html = $(this).html(),
				self = this;
			
			if( $(this).hasClass('loading') ){
				$(this).html( cvm_thumb_message.still_loading );
				return;
			}
			
			var data = $(this).data();
			data.action = 	'cvm_import_video_thumbnail';
			
			$(this).addClass('loading').html( cvm_thumb_message.loading );
			
			$.ajax({
				type 	: 'post',
				url 	: ajaxurl,
				data	: data,
				success	: function( response ){
					if( true == response.success ){					
						WPSetThumbnailHTML( response.data );
					}else{
						$(self).html(html).removeClass('loading');
						$('#cvm-thumb-response').addClass('cvm-error').html( response.data );
					}	
				}
			});
		});
	});
})(jQuery);