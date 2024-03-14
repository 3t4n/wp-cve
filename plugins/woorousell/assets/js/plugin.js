/**
* Plugin JS file
*
*/

var WRSL = new function() {

	var t = this;

	t.moveSlides = function( carousel ) {

		jQuery(document).on( "click" , "button.wrsl-carousel-to-prev" , function(e) {
			e.preventDefault();
			carousel.goToPrevSlide();
		});

		jQuery(document).on( "click" , "button.wrsl-carousel-to-next" , function(e) {
			e.preventDefault();
			carousel.goToNextSlide();
		});
	}

	var getMaxSlides = function( width , maxSlides ) {

		if ( maxSlides > 2 ) {
			if ( width <= WRSLL.MobileWidth ) {
				maxSlides = 1;
			} else if ( width <= WRSLL.tablet1Width ) {
				maxSlides = 2;
			}
		}

		if ( maxSlides > 3 ) {
			if ( width <= WRSLL.MobileWidth ) {
				maxSlides = 1;
			} else if ( width <= WRSLL.tablet1Width ) {
				maxSlides = 2;
			} else if ( width <= WRSLL.tablet2Width ) {
				maxSlides = 3;
			}
		}

		if ( maxSlides > 4 ) {
			if ( width <= WRSLL.MobileWidth ) {
				maxSlides = 1;
			} else if ( width <= WRSLL.tablet1Width ) {
				maxSlides = 2;
			} else if ( width <= WRSLL.tablet2Width ) {
				maxSlides = 3;
			} else if ( width <= WRSLL.LaptopWidth ) {
				maxSlides = 4;
			}
		}

		return maxSlides;
	}

	var getCarouselProps = function( container ) {

		var	maxSlides = ( container.attr("data-maxslides") !== undefined ? parseInt( container.attr("data-maxslides") ) : 3 ),
			slideMargin = ( container.attr("data-slidemargin") !== undefined ? parseInt( container.attr("data-slidemargin") ) : 0 ),
			calWidth = parseInt( container.width() ),
			maxSlides = getMaxSlides( calWidth , maxSlides );

		var props = {
			slideSelector: "div.wrsl-carousel-item",
			mode: ( container.attr("data-mode") !== undefined ? container.attr("data-mode") : "horizontal" ),
			speed: ( container.attr("data-speed") !== undefined ? parseInt( container.attr("data-speed") ) : 500 ),
		    slideWidth: Math.round( ( calWidth - ( slideMargin * ( maxSlides - 1 ) ) ) / maxSlides ),
		    minSlides: ( container.attr("data-minslides") !== undefined ? parseInt( container.attr("data-minslides") ) : 1 ),
		    maxSlides: maxSlides,
		    moveSlides: ( container.attr("data-moveslides") !== undefined ? parseInt( container.attr("data-moveslides") ) : 0 ),
		    slideMargin: slideMargin,
		    randomStart: ( container.attr("data-randomstart") !== undefined && container.attr("data-randomstart") === "true" ? true : false ),
		    adaptiveHeight: ( container.attr("data-adaptiveheight") !== undefined && container.attr("data-adaptiveheight") === "true" ? true : false ), // only for vertical mode
		    adaptiveHeightSpeed: ( container.attr("data-adaptiveheightspeed") !== undefined ? parseInt( container.attr("data-adaptiveheightspeed") ) : 500 ), // only for vertical mode
			//touchEnabled: ( container.attr("data-touchenabled") !== undefined && container.attr("data-touchenabled") === "true" ? true : false ),
			touchEnabled: false,
		    swipeThreshold: ( container.attr("data-swipethreshold") !== undefined ? parseInt( container.attr("data-swipethreshold") ) : 50 ),
		    auto: ( container.attr("data-auto") !== undefined && container.attr("data-auto") === "true" ? true : false ),
		    pause: ( container.attr("data-pause") !== undefined ? parseInt( container.attr("data-pause") ) : 4000 ),
		    autoHover: ( container.attr("data-autohover") !== undefined && container.attr("data-autohover") === "true" ? true : false ),
		    autoDelay: ( container.attr("data-autodelay") !== undefined ? parseInt( container.attr("data-autodelay") ) : 0 ),
		    ticker: ( container.attr("data-ticker") !== undefined && container.attr("data-ticker") === "true" ? true : false ),
		    tickerHover: ( container.attr("data-tickerhover") !== undefined && container.attr("data-tickerhover") === "true" ? true : false ),
		    pager: false,
		    controls: false,
		    onSliderLoad: function( currentIndex ) {
		    	jQuery(this).closest(".wrsl-carousel-container").addClass("carousel-loaded");
		    }
		};

		return props;
	}


	/* Run the init function
	---------------------------------------------------------- */
	jQuery(document).ready(function(){

		var WoorouSell = new Array();

		jQuery(".wrsl-carousel-container").each( function( i ) {

			var container = jQuery(this),
				section = container.closest(".wrsl-widget"),
				sectionID = section.attr("id"),
				carousel = container.find( "div.wrsl-carousel" ),
				carouselProps = getCarouselProps( container );

			WoorouSell[i] = carousel.bxSlider( carouselProps );

			container.on( "click" , "button.wrsl-carousel-to-prev" , function(e) {
				e.preventDefault();
				WoorouSell[i].goToPrevSlide();
			});

			container.on( "click" , "button.wrsl-carousel-to-next" , function(e) {
				e.preventDefault();
				WoorouSell[i].goToNextSlide();
			});

			jQuery(document).on("added_to_cart",function() {
				var newProps = getCarouselProps( container );
				newProps.startSlide = WoorouSell[i].getCurrentSlide();
				WoorouSell[i].reloadSlider( newProps );
			});

			jQuery(window).resize(function() {
				var newProps = getCarouselProps( container );
				WoorouSell[i].reloadSlider( newProps );
			});

		});

	});


} // end - WRSL