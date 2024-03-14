addEventListener("load", (event) => {
	(function( $ ) {
		'use strict';

		$.fn.christmasify = function(options) {
			var settings = $.extend({
	      snowflakes: 0,
	      classy_snow: false,
	      snow_speed: 'medium',
	      santa: true,
	      font: true,
	      music: false,
	      image_frame: false
	    }, options );

	 		if(settings.snowflakes){
	 			var snowflakes = '';
				for (let i = 0; i < settings.snowflakes; i++) {
				  snowflakes += '<i></i>';
				} 	

	 			$('body').append('<div id="snowflakes">' + snowflakes + '</div>'); 		
	 		}

	 		if(settings.classy_snow){
	 			$('body').addClass('snow-alternate');
	 		}

	 		if(settings.snow_speed){
	 			if(settings.snow_speed == 'fast'){
	 				$('body').addClass('fast-snow');
	 			} else if(settings.snow_speed == 'slow'){
	 				$('body').addClass('slow-snow');
	 			}
	 		}

	 		if(settings.music){
				$('body').append('<audio id="christmas-music" controls autoplay><source src="' + settings.music + '" type="audio/mpeg">Your browser does not support the audio element.</audio>');
	 			$('#christmas-music')[0].volume = .2;
	 		}

	 		if(settings.santa){
	 			$('body').append('<div id="santa" class="sleigh-1"></div>');
	 		}

	 		if(settings.font){
	 			$('body').append('<link href="https://fonts.googleapis.com/css?family=Mountains+of+Christmas:700" rel="stylesheet">');
	 			$('h1, h2, h3').each(function(i, heading){
	 				$(heading).css('font-family', "'Mountains of Christmas', cursive");
	 				$(heading).css('font-weight', "700");
	 			});
	 		}

	 		if(settings.image_frame && $('img').length){
		 		$('img').each(function(i, image){
		 			if(image.complete){
			 			if($(image).width() > 240 && (!$(image).hasClass('alignright') || !$(image).hasClass('alignleft'))){
			 				$(image).wrap('<div class="christmas-frame"></div');
			 			}
		 			} else {
			 			$(image).on('load', function(){
				 			if($(image).width() > 240 && (!$(image).hasClass('alignright') || !$(image).hasClass('alignleft'))){
				 				$(image).wrap('<div class="christmas-frame"></div');
				 			}
			 			});
		 			}
		 		});
		 	}
		};

	})( jQuery );
});