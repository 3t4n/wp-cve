( function( $ ) {
	/**
 	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */ 
	var WidgetTxSlider = function( $scope, $ ) {
		//console.log( $scope );
		
		var tx_slider = $scope.find('.tx-slider');
		
		if (tx_slider.length > 0) {
			
			var _this = tx_slider;
			var slider_delay = _this.data('delay');
			var slider_transition = _this.data('transition');
			
			if( slider_transition == 'slide' )
			{
				tx_slider.owlCarousel({
					autoPlay : slider_delay,
					stopOnHover : true,
					navigation: true,
					paginationSpeed : 1000,
					goToFirstSpeed : 2000,
					singleItem : true,
					autoHeight : true,
					navigationText: ['<span class="genericon genericon-rightarrow"></span>','<span class="genericon genericon-leftarrow"></span>'],
					addClassActive: true,
					theme : "tx-owl-theme",
					pagination : true	
				});
			} else
			{
				tx_slider.owlCarousel({
					autoPlay : slider_delay,
					stopOnHover : true,
					navigation: true,
					paginationSpeed : 1000,
					goToFirstSpeed : 2000,
					singleItem : true,
					autoHeight : true,
					navigationText: ['<span class="genericon genericon-rightarrow"></span>','<span class="genericon genericon-leftarrow"></span>'],
					addClassActive: true,
					theme : "tx-owl-theme",
					transitionStyle : slider_transition,
					pagination : true	
				});			
			}
		}
		

		//data-parallax="yes" 
		//data-fullscreen="yes" 
		
		var slider_fullscreen = _this.data('fullscreen');
		var slider_parallax = _this.data('parallax');
		var slider_height = _this.data('height');		
		
		if( slider_fullscreen == 'yes' )
		{
			slider_height = $( window ).height();
		}

		_this.find('.tx-slider-img').css( "height", slider_height );
		_this.find('.owl-wrapper-outer').css( "height", slider_height );
		
		_this.find('.tx-slider-img').css('background-position', 'center center');
		var backgroundPos = _this.find('.tx-slider-img').css('backgroundPosition').split(" ");
		var yPos = backgroundPos[1];		
		
		if (slider_parallax == 'yes')
		{		
			var slidetop = parseInt(_this.offset().top);
			
			if( $( window ).width() > 999 )
			{	
				$(window).scroll(function(){
					var newvalue = parseInt($(this).scrollTop()*0.70)-60;
				
					if ($(this).scrollTop() > slidetop)
					{
						_this.find('.tx-slider-img').css('background-position', 'center calc( 50% + '+newvalue+'px');	
					}
					
					if ($(this).scrollTop() <= slidetop)
					{
						var slideheight = $('.active .da-img').height();

						_this.find('.tx-slider-img').css('background-position', 'center center');
						_this.find('.owl-wrapper-outer').css('max-height', slideheight+'px');
					}		
				});
			}
		}		
		
		
			
	};
	
	// Make sure you run this code under Elementor.
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/tx-slider.default', WidgetTxSlider );
	} );
} )( jQuery );
