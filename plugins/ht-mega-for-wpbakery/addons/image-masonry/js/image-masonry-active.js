(function($){
"use strict";

	$('.htmegavc-masonry-activation').each(function () {
	    var masonry_elem = jQuery(this);
	    masonry_elem.imagesLoaded(function () {
	        // init Isotope
	        var $grid = $('.masonry-wrap').isotope({
	            itemSelector: '.masonary-item',
	            percentPosition: true,
	            transitionDuration: '0.7s',
	            masonry: {
	                columnWidth: '.masonary-item',
	            }
	        });

	    });
	});

})(jQuery);