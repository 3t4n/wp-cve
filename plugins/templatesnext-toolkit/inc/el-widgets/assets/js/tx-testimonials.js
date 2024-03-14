( function( $ ) {
	/**
 	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */ 
	var WidgetTxTestimonials = function( $scope, $ ) {

		var tx_testimonials = $scope.find('.tx-testiin2');
		
		if ( tx_testimonials.length > 0 ) {
			
			console.log("Testing Testi");
			
			var _this = tx_testimonials;
			var testi_delay = _this.data('delay');
			
			tx_testimonials.owlCarousel({
				autoPlay : testi_delay,
				stopOnHover : true,
				navigation: true,
				paginationSpeed : 1000,
				goToFirstSpeed : 2000,
				singleItem : true,
				autoHeight : true,
				navigationText: ['<span class="genericon genericon-leftarrow"></span>','<span class="genericon genericon-rightarrow"></span>'],
				addClassActive: true,
				theme : "tx-owl-theme",
				pagination : true	
			});
			
		}
		
	};
	
	// Make sure you run this code under Elementor.
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/tx-testimonials.default', WidgetTxTestimonials );
	} );
} )( jQuery );
