(function($){
"use strict";
	
	var tooptip_elem = $('[data-toggle="popover"]');
	tooptip_elem.each(function () {
		jQuery(this).htbpopover();
		$('[data-toggle="popover"].show').htbpopover('show');
	});
	

})(jQuery);