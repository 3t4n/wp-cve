var wooctrl_media_frame;
jQuery(function($) {
	
	$('.wooctrl_delete_image').click(function(e) {
		var mediaid = $(e.target).data('mediaid');
		$('#'+mediaid).val('');
		$('#'+mediaid+'_image').attr('src','').attr('style','width:60px; height:60px; border:solid 1px #ddd');
		$(e.target).hide();
	});
	
	
	
	if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
		
		$('.wrap').on('click', '.wooctrl_add_media', function(e) {
			
			e.preventDefault();
			
			var title = $(e.target).data('title') || 'Choose Image';
			var button = $(this);
			var mediaid = $(button).data('mediaid');
			
			wp.media.editor.send.attachment = function(props, attachment) {
				var selected_size_url = attachment.sizes[props.size].url;
				$('#'+mediaid+'_image').attr('src', selected_size_url).attr('style','width:160px; height:auto');
				$('#'+mediaid).val(selected_size_url);
				$(button).parent().find('.wooctrl_delete_image').show();
				return false;
			};

        	wp.media.editor.open();
			
			return;
			
		});
	}

});