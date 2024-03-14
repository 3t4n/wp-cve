// TODO build via webpack
( function() {
	// TODO share with block.js
	var defaultOptions = {
		strings: [ 'Default value', '<b>Please</b> <i>override me</i>' ],
		typeSpeed: 60,
		startDelay: 0,
		backSpeed: 40,
		backDelay: 700,
		loop: false,
		loopCount: 0,
		smartBackspace: true,
		shuffle: false,
		fadeOut: false,
		showCursor: true,
		cursorChar: '|',
	};

	var elements = document.getElementsByClassName( 'typing-animation' );

	function extend( obj, src ) {
		for ( var key in src ) {
			if ( src.hasOwnProperty( key ) ) {
				if ( typeof obj[ key ] === 'undefined' ) {
					obj[ key ] = src[ key ];
				}
			}
		}
		return obj;
	}

	for ( var i = 0; i < elements.length; i++ ) {
		var element = elements[ i ];
		var attributes = element.dataset.options;
		new Typed( element, extend( JSON.parse( attributes ), defaultOptions ) );
	}
}() );
