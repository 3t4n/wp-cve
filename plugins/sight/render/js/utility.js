// Create csco object.
var sight = {
	addAction: function( x, y, z ) {
		return;
	}
};

if ( 'undefined' !== typeof wp && 'undefined' !== typeof wp.hooks ) {
	sight.addAction = wp.hooks.addAction;
}

if ( 'undefined' === typeof window.load_more_query ) {
	window.load_more_query = [];
}

/**
 * Window size
 */
const $ = jQuery;
const $window = $( window );
const $doc = $( document );
const $body = $( 'body' );

/**
 * In Viewport checker
 */
$.fn.isInViewport = function() {
	var elementTop = $( this ).offset().top;
	var elementBottom = elementTop + $( this ).outerHeight();

	var viewportTop = $( window ).scrollTop();
	var viewportBottom = viewportTop + $( window ).height();

	return elementBottom > viewportTop && elementTop < viewportBottom;
};

export {
	$,
	$window,
	$doc,
	$body,
	sight
};
