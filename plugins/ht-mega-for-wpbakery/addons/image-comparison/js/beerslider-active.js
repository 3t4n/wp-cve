(function($){
"use strict";

	$.fn.BeerSlider = function (options) {
	    options = options || {};
	    return this.each(function () {
	        new BeerSlider(this, options);
	    });

	};

    var elem = $('.beer-slider');
	elem.each(function (index, el) {
		 $(el).BeerSlider({
		     start: $(el).data("start")
		 })
	});




})(jQuery);
