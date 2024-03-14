(function ($) {
	"use strict";
    
     $(window).on("elementor/frontend/init", function () {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $scope ) {
		$('body .bsk-tabs').removeClass('no-load'); 
		} );
					
    })
	
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl, {
    html: true
  })
})

	
   


}(jQuery));	


