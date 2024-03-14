jQuery(function($) {

	//* Track background & popup button hover color
	wp.customize( 'cc_audioalbum[bgcol]', function( value ) {
		value.bind( function( newval ) {
			$( '.audioalbum, .audioheading, .track').not('.bgcolset').css('background-color', newval );
			$( 'head').append('<style>.track .audiobutton a:hover{ color:' + newval + '!important;}</style>');
		});
	});

	//* Player background & popup button background
	wp.customize( 'cc_audioalbum[playr]', function( value ) {
		value.bind( function( newval ) {
			$( 'div .track .wp-audio-shortcode.mejs-audio .mejs-inner > .mejs-controls').css( 'background-color', newval );
			$( 'div .track .mejs-controls .mejs-time-rail .mejs-time-total').css( 'background-color', newval );
			$( '.track .mejs-time-rail .mejs-time-total .mejs-time-loaded').css( 'background-color', newval );
			$( '.track .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-total').css( 'background-color', newval );
			$( 'head').append('<style>.track .audiobutton a:not(:hover){ background-color:' + newval + '!important;}</style>');
		});
	});

	//* Text & Buttons
	wp.customize( 'cc_audioalbum[txtbt]', function( value ) {
		value.bind( function( newval ) {
			$( 'h1.audioheading, p.audioheading, h2.audioalbum, p.audioalbum, .track .songtitle, .track .songwriter, .track .mejs-currenttime, .track .mejs-duration' ).css( 'color', newval );
			$( 'head').append('<style>.track .audiobutton a:not(:hover),.track .mejs-controls > .mejs-button button:not(:hover){ color:' + newval + '!important;} .track .audiobutton a:hover{ background-color:' + newval + '!important;}</style>');
		});
	});

	//* Time & volume bar
	wp.customize( 'cc_audioalbum[tvcol]', function( value ) {
		value.bind( function( newval ) {
			$( '.track .mejs-time-rail .mejs-time-total .mejs-time-current').css( 'background-color', newval );
			$( '.track .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current' ).css( 'background-color', newval );
			$( 'head').append('<style>.track .mejs-controls > .mejs-button button:hover{ color:' + newval + '!important;}</style>');
		});
	});

});
