
/*----------------------------------------------------------------------------*\
	prime_SLIDER PARAM
\*----------------------------------------------------------------------------*/
( function( $ ) {
	"use strict";

	var $sliders = $( '.prime-vc-slider' );

	$sliders.each( function() {
		var $slider_wrap = $( this ),
			$slider      = $slider_wrap.children( '.prime-slider' ),
			$input       = $slider_wrap.siblings( '.prime-value' );

		$slider.slider( {
			animate: 'fast',
			min:     parseFloat( $slider.attr( 'data-min' ) ),
			max:     parseFloat( $slider.attr( 'data-max' ) ),
			step:    parseFloat( $slider.attr( 'data-step' ) ),
			value:   parseFloat( $slider.attr( 'data-value' ) ),
			range:   $slider_wrap.is( '.prime-fill' ) ? 'min' : false
		} );

		if ( $input.length ) {
			$slider.on( 'slide', function( event, ui ) {
				if ( ui.value === +ui.value && ui.value !== ( ui.value|0 )  ) {
					ui.value = ui.value.toFixed( 2 );
				}

				$input.val( ui.value );
			} );

			$input.on( 'change', function() {
				$slider.slider( 'value', $input.val() );

				var _value = $slider.slider( 'value' );
				if ( _value === +_value && _value !== ( _value|0 )  ) {
					_value = _value.toFixed( 2 );
				}

				$input.val( _value );
			} )
		}
	} );

} )( jQuery );

