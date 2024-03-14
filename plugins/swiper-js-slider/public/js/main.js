
var swiper_slider;

(function($) {
"use strict";
 	
 	$('[data-slider]').each(function(){
 		var t 			= $(this),
			config 		= {},
			general		= t.data('general'),
			autoplay	= t.data('autoplay'),
			pagination 	= t.data('pagination'),
			navigation 	= t.data('navigation'),
			breakpoints	= t.data('breakpoints');

		if(general){
			config = general;
		}

 		if(autoplay){
 			config.autoplay = autoplay;
 		}
		 
		if(pagination){
			config.pagination = pagination;
		}

		if(navigation){
			config.navigation = navigation;
		}

		if(breakpoints){
			config.breakpoints = breakpoints;
		}
 		
 		//console.log(config);
		swiper_slider = new Swiper(t, config);
		
		t.removeAttr('data-general'); 
		t.removeAttr('data-autoplay'); 
		t.removeAttr('data-pagination'); 
		t.removeAttr('data-navigation'); 
		t.removeAttr('data-breakpoints'); 
	 });
	 
})(jQuery);



  
