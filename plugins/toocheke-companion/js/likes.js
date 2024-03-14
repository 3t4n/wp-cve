(function( $ ) {
	'use strict';
	$(document).on('click', '.toocheke-likes-button', function() {
		var button = $(this);
		var post_id = button.attr('data-post-id');
		var security = button.attr('data-nonce');
		var iscomment = button.attr('data-iscomment');
		var allbuttons;
		if ( iscomment === '1' ) { /* Comments can have same id */
			allbuttons = $('.toocheke-likes-comment-button-'+post_id);
		} else {
			allbuttons = $('.toocheke-likes-button-'+post_id);
		}
		var loader = allbuttons.next('#toocheke-likes-loader');
		if (post_id !== '') {
			$.ajax({
				type: 'POST',
				url: toochekeLikes.ajaxurl,
				data : {
					action : 'toocheke_process_like',
					post_id : post_id,
					nonce : security,
					is_comment : iscomment,
				},
				beforeSend:function(){
					loader.html('&nbsp;<div class="likes-loader">Loading...</div>');
				},	
				success: function(response){
					var icon = response.icon;
					var count = response.count;
					allbuttons.html(icon+count);
					if(response.status === 'unliked') {
						var like_text = toochekeLikes.like;
						allbuttons.prop('title', like_text);
						allbuttons.removeClass('liked');
					} else {
						var unlike_text = toochekeLikes.unlike;
						allbuttons.prop('title', unlike_text);
						allbuttons.addClass('liked');
					}
					loader.empty();					
				}
			});
			
		}
		return false;
	});
})( jQuery );
