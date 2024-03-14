'use strict';

(function ($) {
	let doc = $( document );

	doc.ready( function () {
		$( window ).scroll( function () {
			if ( $( this ).scrollTop() > 300 )
			{
				$( '#fspSaveSettings' ).addClass( 'fsp-settings-jump-to-top' );
			}
			else
			{
				$( '#fspSaveSettings' ).removeClass( 'fsp-settings-jump-to-top' );
			}
		} );

		$( '.fsp-settings-collapser' ).on( 'click', function () {
			let _this = $( this );

			if ( ! _this.parent().hasClass( 'fsp-is-open' ) )
			{
				_this.parent().find( '.fsp-settings-collapse' ).slideToggle();
				_this.find( '.fsp-settings-collapse-state' ).toggleClass( 'fsp-is-rotated' );
			}
		} );

		$( '.fsp-require-premium' ).on( 'change', function () {
			$( this )[ 0 ].checked = ! $( this ).is( ':checked' );
		} );

		$( '.fsp-select-require-premium').on('change', function (e) {
			$( this ).val(1);
			FSPoster.upgrade( 'Purchase premium version to access all features.', true );
		} );
	} );
})( jQuery );
