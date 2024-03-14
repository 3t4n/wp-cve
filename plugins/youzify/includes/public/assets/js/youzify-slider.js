( function( $ ) {

    'use strict';

	$( document ).ready( function() {

	    $.youzify_sliders_init = function() {

			// Set Up Variables.
			var $progressBar, $bar, $elem, isPause, tick, percentTime, time = Youzify.slideshow_speed;

			var youzify_auto_slideshow = ( Youzify.slideshow_auto == '1' ) ? true : false;

		    // Init progressBar where elem is $(".youzify-slider")
		    function progressBar( elem ) {

		    	if ( ! youzify_auto_slideshow ) {
		    		return;
		    	}

			    $elem = elem;
			    // build progress bar elements
			    buildProgressBar();
			    // start counting
			    start();
		    }

		    // Create div#progressBar and div#bar then prepend to the slider.
		    function buildProgressBar() {
				$progressBar = $( '<div>', { id: 'youzify-progressBar' } );
				$bar 		 = $( '<div>', { id: 'youzify-bar' } );
				$progressBar.append( $bar ).prependTo( $elem );
		    }

		    function start() {
		    	// Reset timer
		    	percentTime = 0;
		    	isPause 	= false;
		    	// Run interval every 0.01 second
		    	tick = setInterval( interval, 10 );
		    };

		    function interval() {
		      	if ( isPause === false ) {
			        percentTime += 1 / time;
			        $bar.css( {
			           width: percentTime+"%"
			        } );

		        //if percentTime is equal or greater than 100
		        if ( percentTime >= 100 ) {
					//slide to next item
					$elem.trigger( 'owl.next' )
		        }
		      }
		    }

		    // Pause while dragging
		    function pauseOnDragging() {
		   		isPause = true;
		    }

		    // Moved callback
		    function moved(){

		    	if ( ! youzify_auto_slideshow ) {
		    		return;
		    	}

				clearTimeout( tick );
				start();
		    }

			/**
			 * SlideShow
			 */
			var youzify_slides_height = ( Youzify.slides_height_type == 'auto' ) ? true : false;
			var slideshow_attr = {
					paginationSpeed : 1000,
					singleItem 		: true,
					navigation 		: true,
					afterMove 		: moved,
					transitionStyle : 'fade',
					afterInit 		: progressBar,
					startDragging 	: pauseOnDragging,
			    	autoHeight		: youzify_slides_height
			   };

	    	if ( $( '.youzify-slider' )[0] && $( '.youzify-slider li' ).length > 1 ) {

			    // Init the carousel
			    $( '.youzify-slider' ).youzify_owlCarousel( slideshow_attr );
			}

		    $.youzify_wall_slider = function() {

			    // Init the carousel
			    $( '.youzify-wall-slider' ).youzify_owlCarousel( slideshow_attr );
		    }

		    $( '.youzify-wall-slider' ).each( function( i, obj ) {
		    	if ( ! $( obj ).hasClass( 'owl-carousel' ) ) {
		    		$( obj ).youzify_owlCarousel( slideshow_attr );
		    	}
			});

		}

		$.youzify_sliders_init();

		// Init Effect On the appended elements also.
		$( '#activity-stream' ).on( 'append', function( e ) {
			$.youzify_sliders_init();
			alert('test');
	    });

	});

})( jQuery );