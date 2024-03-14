(function($){
"use strict";
	
	var tooptip_elem = $('[data-toggle="tooltip"]');
	tooptip_elem.each(function () {
		jQuery(this).htbtooltip({
		    animation: false,
		});
	});
	

})(jQuery);