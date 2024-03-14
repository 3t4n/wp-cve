/*============= Video Player ==============*/
(function($){
"use strict";
	
    var target_elem = $('.htmegavc-video-player');
	target_elem.each(function () {
		var videotype =  jQuery(this).data('videotype');
		
		// activation js code
		if( videotype.videocontainer == 'self' ){
		    var videoplayer_elem = jQuery(this).find('.htmegavc-video-player-inner').eq(0);
		    videoplayer_elem.YTPlayer();
		}else{
		    var videopopup_elem = jQuery(this).find('.magnify-video-active').eq(0);
		    videopopup_elem.magnificPopup({
		        type: 'iframe'
		    });
		}
	});

})(jQuery);