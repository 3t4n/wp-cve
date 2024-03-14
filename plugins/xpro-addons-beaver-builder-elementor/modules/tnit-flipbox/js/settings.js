(function($){
	"use strict";

	FLBuilder.registerModuleHelper(
		'tnit-flipbox',
		{

			init: function()
		{
				var form = $( '.fl-builder-settings' ),
				a        = form.find( '.fl-builder-settings-tabs a' );

				a.on( 'click', this._openFlipboxBack );
				$( '.fl-builder-content' ).on( 'fl-builder.layout-rendered', this._openFlipboxBackAfterRender );
			},

			_openFlipboxBack: function() {
				var form   = $( '.fl-builder-settings' ),
				id         = form.data( 'node' ),
				anchorHref = $( this ).attr( 'href' );

				if ( anchorHref === '#fl-builder-settings-tab-flipbox_back' ) {
					jQuery( '.fl-node-' + id + ' .tnit-flipbox.tnit-flip-horizontal .flip-box-inner' ).css( { 'transform': 'rotateY(180deg)' } );
					jQuery( '.fl-node-' + id + ' .tnit-flipbox.tnit-flip-vertical .flip-box-inner' ).css( { 'transform': 'rotateX(180deg)' } );
					jQuery( '.fl-node-' + id + ' .tnit-flipbox.tnit-flip-zoomIn .flip-box-front' ).css( { 'transform': 'scale(0.5)', 'opacity': '0', 'visibility': 'hidden' } );
					jQuery( '.fl-node-' + id + ' .tnit-flipbox.tnit-flip-zoomIn .flip-box-back' ).css( { 'transform': 'scale(1)', 'opacity': '1', 'visibility': 'visible', 'transition-delay': '0.3s' } );
					jQuery( '.fl-node-' + id + ' .tnit-flip-skewUp .flip-box-front' ).css( { 'transform': 'skew(-20deg, -20deg) scaleX(0.3) scale(1.1)', 'opacity': '0', 'visibility': 'hidden' } );
					jQuery( '.fl-node-' + id + ' .tnit-flip-skewUp .flip-box-back' ).css( { 'transform': 'skew(0deg, 0deg)', 'opacity': '1', 'visibility': 'visible', 'transition-delay': '0.3s' } );
				} else {
					jQuery( '.fl-node-' + id + ' .tnit-flipbox.tnit-flip-horizontal .flip-box-inner' ).css( { 'transform': 'rotateY(0deg)' } );
					jQuery( '.fl-node-' + id + ' .tnit-flipbox.tnit-flip-vertical .flip-box-inner' ).css( { 'transform': 'rotateX(0deg)' } );
					jQuery( '.fl-node-' + id + ' .tnit-flipbox.tnit-flip-zoomIn .flip-box-front' ).css( { 'transform': 'scale(1)', 'opacity': '1', 'visibility': 'visible' } );
					jQuery( '.fl-node-' + id + ' .tnit-flipbox.tnit-flip-zoomIn .flip-box-back' ).css( { 'transform': 'scale(0.5)', 'opacity': '0', 'visibility': 'hidden', 'transition-delay': 'unset' } );
					jQuery( '.fl-node-' + id + ' .tnit-flip-skewUp .flip-box-front' ).css( { 'transform': 'skew(0deg, 0deg)', 'opacity': '1', 'visibility': 'visible' } );
					jQuery( '.fl-node-' + id + ' .tnit-flip-skewUp .flip-box-back' ).css( { 'transform': 'skew(-10deg, -10deg) scaleX(0.6)', 'opacity': '0', 'visibility': 'hidden', 'transition-delay': 'unset' } );
				}
			},

			_openFlipboxBackAfterRender: function() {
				var form   = $( '.fl-builder-settings' ),
				id         = form.data( 'node' ),
				anchorHref = jQuery( '.fl-builder-settings-tabs' ).children( '.fl-active' ).attr( 'href' );

				if ( anchorHref === '#fl-builder-settings-tab-flipbox_back' ) {
					jQuery( '.fl-node-' + id + ' .tnit-flipbox.tnit-flip-horizontal .flip-box-inner' ).css( { 'transform': 'rotateY(180deg)' } );
					jQuery( '.fl-node-' + id + ' .tnit-flipbox.tnit-flip-vertical .flip-box-inner' ).css( { 'transform': 'rotateX(180deg)' } );
					jQuery( '.fl-node-' + id + ' .tnit-flipbox.tnit-flip-zoomIn .flip-box-front' ).css( { 'transform': 'scale(0.5)', 'opacity': '0', 'visibility': 'hidden' } );
					jQuery( '.fl-node-' + id + ' .tnit-flipbox.tnit-flip-zoomIn .flip-box-back' ).css( { 'transform': 'scale(1)', 'opacity': '1', 'visibility': 'visible', 'transition-delay': '0.3s' } );
					jQuery( '.fl-node-' + id + ' .tnit-flip-skewUp .flip-box-front' ).css( { 'transform': 'skew(-20deg, -20deg) scaleX(0.3) scale(1.1)', 'opacity': '0', 'visibility': 'hidden' } );
					jQuery( '.fl-node-' + id + ' .tnit-flip-skewUp .flip-box-back' ).css( { 'transform': 'skew(0deg, 0deg)', 'opacity': '1', 'visibility': 'visible', 'transition-delay': '0.3s' } );
				} else {
					jQuery( '.fl-node-' + id + ' .tnit-flipbox.tnit-flip-horizontal .flip-box-inner' ).css( { 'transform': 'rotateY(0deg)' } );
					jQuery( '.fl-node-' + id + ' .tnit-flipbox.tnit-flip-vertical .flip-box-inner' ).css( { 'transform': 'rotateX(0deg)' } );
					jQuery( '.fl-node-' + id + ' .tnit-flipbox.tnit-flip-zoomIn .flip-box-front' ).css( { 'transform': 'scale(1)', 'opacity': '1', 'visibility': 'visible' } );
					jQuery( '.fl-node-' + id + ' .tnit-flipbox.tnit-flip-zoomIn .flip-box-back' ).css( { 'transform': 'scale(0.5)', 'opacity': '0', 'visibility': 'hidden', 'transition-delay': 'unset' } );
					jQuery( '.fl-node-' + id + ' .tnit-flip-skewUp .flip-box-front' ).css( { 'transform': 'skew(0deg, 0deg)', 'opacity': '1', 'visibility': 'visible' } );
					jQuery( '.fl-node-' + id + ' .tnit-flip-skewUp .flip-box-back' ).css( { 'transform': 'skew(-10deg, -10deg) scaleX(0.6)', 'opacity': '0', 'visibility': 'hidden', 'transition-delay': 'unset' } );
				}
			},

		}
	);

})( jQuery );
