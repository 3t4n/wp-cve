(function($){

$('.showtime').each(function(){
    var slider = this;
    var transition = $(this).attr('data-transition');
    $(this).nivoSlider({
        effect: transition,    // Specify sets like: 'fold,fade,sliceDown'
        slices: 15,                                 // For slice animations
        boxCols: 8,                                 // For box animations
        boxRows: 4,                                 // For box animations
        animSpeed: 1300,                             // Slide transition speed
        pauseTime: 5000,                            // How long each slide will show
        startSlide: 0,                              // Set starting Slide (0 index)
        directionNav: true,                         // Next & Prev navigation
        controlNav: false,                           // 1,2,3... navigation
        controlNavThumbs: false,                    // Use thumbnails for Control Nav
        pauseOnHover: true,                         // Stop animation while hovering
        manualAdvance: false,                       // Force manual transitions
        prevText: 'Prev',                           // Prev directionNav text
        nextText: 'Next',                           // Next directionNav text
        randomStart: false,                         // Start on a random slide
        beforeChange: function(){
            $('.nivo-caption').fadeOut(500);
        },                 // Triggers before a slide transition
        afterChange: function() {
            showCaption(slider);
            $('.nivo-caption').fadeIn(500);
        },
        slideshowEnd: function(){},                 // Triggers after all slides have been shown
        lastSlide: function(){},                    // Triggers when last slide is shown
        afterLoad: function() {
            showCaption(slider);
        }
    });

    function showCaption(slide) {
        var current = $('.nivo-imageLink', slide).filter(function(){
            if ($(this)[0].style.display == 'block') {
                return true;
            }
        });

        var title = $('img:first', current).attr('data-title');
        var content = $('img:first', current).attr('data-content');

        $('.nivo-caption:first', slide).html('<h2>'+title+'</h2><div class="content">'+content+'</div>');
    }
    
	//mql
	function arrowposi() {
		if ($(window).width() > 600) {
			 var slidearaW = $('.slider-box').width()+70;
			 var slideW = $('.showtime').width();
			 var l = slidearaW-slideW-30;
			 var cl = slidearaW-slideW-70;
			 var cw = slidearaW-slideW-100;
			 $('.left-photo-right .nivo-prevNav').css({'left': -l});
			 $('.right-photo-left .nivo-nextNav').css({'right': -l});
			 $('.left-photo-right .nivo-caption').css({'left': -cl, 'width': cw});
			 $('.right-photo-left .nivo-caption').css({'right': -cl, 'width': cw});
		} else {
			 $('.left-photo-right .nivo-prevNav').css({'left': -30});
			 $('.right-photo-left .nivo-nextNav').css({'right': -30});
			 $('.left-photo-right .nivo-caption').css({'left': 0, 'width': '100%'});
			 $('.right-photo-left .nivo-caption').css({'left': 0,'right': 0, 'width': '100%'});
		}
     }
     

 	$(window).load(function() {
	 	arrowposi();
	});

	var timer = false;
	$(window).resize(function() {
	    if (timer !== false) {
	        clearTimeout(timer);
	    }
	    timer = setTimeout(function() {
			arrowposi();
	    }, 200);
	});

});


})(jQuery);
