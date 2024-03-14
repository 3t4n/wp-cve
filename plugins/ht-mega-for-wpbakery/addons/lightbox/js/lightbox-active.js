/*============= LightBox ==============*/
(function($){
"use strict";
	$('.htmegavc-lightbox').each(function () {
	    var lightbox_elem = jQuery(this).find('.image-popup-vertical-fit').eq(0);

	    if( lightbox_elem.length > 0 ){
	        var popupoption = lightbox_elem.data('popupoption');
	        lightbox_elem.magnificPopup({
	            type: popupoption.datatype,
	            closeOnContentClick: true,
	            mainClass: 'mfp-img-mobile',
	            image: {
	                verticalFit: true,
	                titleSrc:"This is title",
	            },
	        });
	    }

	});
})(jQuery);