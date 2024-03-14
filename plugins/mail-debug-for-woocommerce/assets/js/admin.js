jQuery( function ( $ ) {
	var anchors = $( '.mail-debug-message__tabs-anchor' ),
		tabs    = $( '.mail-debug-message__tab' );

	anchors.on( 'click', function () {
		var tab = $( '#' + $( this ).data( 'ref' ) );
		anchors.removeClass( 'active' );
		tabs.removeClass( 'active' );

		$( this ).addClass( 'active' );
		tab.addClass( 'active' );

		if ( tab.find('.mail-debug-codemirror:not(.mail-debug-codemirror--initialized)') ) {
			$( document ).trigger( 'mail-debug-codemirror-init' );
		}
	} );

	// Codemirror.
	$( function () {
		var codemirrorInit = function () {
			if ( typeof wp !== 'undefined' && typeof wp.codeEditor !== 'undefined' ) {
				$( '.mail-debug-codemirror:not(.mail-debug-codemirror--initialized)' ).each( function () {
					var settings = $( this ).data( 'settings' ),
						editor   = wp.codeEditor.initialize( $( this ), settings );

					$( this ).addClass( 'mail-debug-codemirror--initialized' );
					$( this ).data( 'codemirrorInstance', editor );

					editor.codemirror.setSize('100%', 500);
				} );
			}
		};
		$( document ).on( 'mail-debug-codemirror-init', codemirrorInit );
	} );
} );
