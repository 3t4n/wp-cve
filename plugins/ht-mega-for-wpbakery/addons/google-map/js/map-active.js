(function($){
"use strict";

    var googlemap_elem = $('.htmegavc-google-map');
	googlemap_elem.each(function () {
		 var mapsettings = jQuery(this).data('mapmarkers');
		 var mapsoptions = jQuery(this).data('mapoptions');
		 var mapstyles   = jQuery(this).data('mapstyle');

		 var myMarkers = {
		     "markers": mapsettings,
		 };

		 jQuery(this).mapmarker({
		     zoom    : parseInt(mapsoptions.zoom),
		     center  : mapsoptions.center,
		     styles  : mapstyles,
		     markers : myMarkers,
		 });
	});


})(jQuery);