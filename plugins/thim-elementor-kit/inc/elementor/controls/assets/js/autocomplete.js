jQuery( window ).on( 'elementor:init', function() {
	'use strict';

	const el = elementor.modules.controls.BaseData.extend( {
		onReady() {
			const el = this,
				t = el.ui.select,
				url = t.attr( 'data-ajax-url' ),
				none = window.wpApiSettings.nonce;

			t.select2( {
				ajax: {
					url,
					dataType: 'json',
					headers: { 'X-WP-Nonce': none },
					data( e ) {
						return { s: e.term };
					},
				},
				cache: false,
			} );

			let r = void 0 !== el.getControlValue() ? el.getControlValue() : '';

			r.isArray && ( r = el.getControlValue().join() );

			jQuery
				.ajax( {
					url,
					dataType: 'json',
					headers: { 'X-WP-Nonce': none },
					data: { ids: String( r ) },
				} )
				.then( function( res ) {
					if ( null !== res && res.results.length > 0 ) {
						jQuery.each( res.results, function( e, a ) {
							const n = new Option( a.text, a.id, ! 0, ! 0 );
							t.append( n ).trigger( 'change' );
						} ),

						t.trigger( { type: 'select2:select', params: { data: res } } );
					}
				} );
		},

		onBeforeDestroy() {
			this.ui.select.data( 'select2' ) && this.ui.select.select2( 'destroy' ),
			this.el.remove();
		},
	} );

	elementor.channels.editor.on( 'thimELKitsPreview', function( e ) {
		$e.internal( 'document/save/save', {
			force: true,
			onSuccess: function onSuccess() {
				elementor.dynamicTags.cleanCache();
				elementor.reloadPreview();
			},
		} );
	} );

	elementor.addControlView( 'thim-ekit-autocomplete', el );
} );
