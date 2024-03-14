(function($){
"use strict";

    var target_elem = $('.htmegavc-counter-number');
	target_elem.each(function () {
		jQuery(this).counterUp({
		    delay: 10,
		    time: 1000,
		});
	});

})(jQuery);