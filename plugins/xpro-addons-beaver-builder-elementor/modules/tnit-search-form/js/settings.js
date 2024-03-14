(function($){
	"use strict";

	FLBuilder.registerModuleHelper(
		'tnit-search-form',
		{

			init: function()
		{
				var form = $( '.fl-builder-settings' ),
				a        = form.find( '.fl-builder-settings-tabs a' );

				a.on( 'click', this._openSeachPopup );
				$( '.fl-builder-content' ).on( 'fl-builder.layout-rendered', this._openSearchPopupRender );
			},

			_openSeachPopup: function() {
				var form   = $( '.fl-builder-settings' ),
				id         = form.data( 'node' ),
				anchorHref = $( this ).attr( 'href' );

				if ( anchorHref === '#fl-builder-settings-tab-style' || anchorHref === '#fl-builder-settings-tab-button' || anchorHref === '#fl-builder-settings-tab-typography' ) {
					jQuery( '.fl-node-' + id + ' .tnit-search-outer' ).addClass( 'open' );
				} else {
					jQuery( '.fl-node-' + id + ' .tnit-search-outer' ).removeClass( "open" );
				}
			},

			_openSearchPopupRender: function() {
				var form   = $( '.fl-builder-settings' ),
				id         = form.data( 'node' ),
				anchorHref = jQuery( '.fl-builder-settings-tabs' ).children( '.fl-active' ).attr( 'href' );

				if (anchorHref === '#fl-builder-settings-tab-style' || anchorHref === '#fl-builder-settings-tab-button' || anchorHref === '#fl-builder-settings-tab-typography' ) {
					jQuery( '.fl-node-' + id + ' .tnit-search-outer' ).addClass( 'open' );
				} else {
					jQuery( '.fl-node-' + id + ' .tnit-search-outer' ).removeClass( "open" );
				}
			},

		}
	);

})( jQuery );
