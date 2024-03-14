( function( $ ) {
	'use strict';

	elementor.hooks.addFilter( 'editor/style/styleText', function( css, context ) {
		if ( ! context ) {
			return;
		}
		const model = context.model,
			customCSS = model.get( 'settings' ).get( 'custom_css' );
		let selector = '.elementor-element.elementor-element-' + model.get( 'id' );

		if ( 'document' === model.get( 'elType' ) ) {
			selector = elementor.config.document.settings.cssWrapperSelector;
		}

		if ( customCSS ) {
			css += customCSS.replace( /selector/g, selector );
		}

		return css;
	} );

	elementor.on( 'preview:loaded', function() {
		let customCSS = elementor.settings.page.model.get( 'custom_css' );
		if ( customCSS ) {
			customCSS = customCSS.replace( /selector/g, elementor.config.document.settings.cssWrapperSelector );
			elementor.settings.page.getControlsCSS().elements.$stylesheetElement.append( customCSS );
		}
	} );
}( jQuery ) );
