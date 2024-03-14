/**
 * WPPA potd block
 *
 * Version: 8.5.01.001
 */

jQuery(document).ready(function(){
( function ( blocks, editor, i18n, element, components, _, blockEditor ) {

	var el = element.createElement;
	var __ = i18n.__;
	var RichText = blockEditor.RichText;
	var useBlockProps = blockEditor.useBlockProps;

	blocks.registerBlockType( 'wp-photo-album-plus/potd', {
		title: __( 'WPPA+ Potd', 'wp-photo-album-plus' ),
		icon: el( 'img', {
							src: 	wppaImageDirectory+'camera32.png',
							style: 	{ width: '24px', height: '24px' },
						} ),
		category: 'layout',

		attributes: {
			widgetTitle: {
				type: 'string',
				selector: 'h2',
				default: '',
			},
			wppaShortcode: {
				type: 'string',
				default: '[wppa type="photo" photo="#potd"]',
			},
		},

		example: {
			attributes: {
				widgetTitle: 'Photo of the day',
				wppaShortcode: '[wppa type="photo" photo="#potd"]',
			},
		},


		edit: function( props ) {
			var blockProps = wp.blockEditor.useBlockProps();
			var attributes = props.attributes;
			var shortcode  = evaluate(props);
			var result;
			var previewId  = 'wppapotd-'+Math.floor(Math.random() * 100);

			result = el(
				'div',
				blockProps,
				el('h2',null,__( 'WPPA Photo of the day','wp-photo-album-plus' )),
				wppaOnWidgets() ?
				el( RichText, {
					tagName: 'h3',
					placeholder: __( 'Enter widget caption', 'wp-photo-album-plus' ),
					value: attributes.widgetTitle,
					onChange: function ( value ) {
						props.setAttributes( { 	widgetTitle: value,
												wppaShortcode: evaluate(),
												});
					},
				}) : null,
				el('small',null,__( 'Corresponding shortcode', 'wp-photo-album-plus' )+': '+evaluate(props)),
				el('div', {id: previewId}),
			);

			setTimeout(function(){if(!jQuery('#'+previewId).html() || jQuery('#'+previewId).html().length == 0)wppaGutenbergGetWppaShorcodeRendered(shortcode.replace('#','%23'), previewId);},100);
			return result;
		},

		save: function( props ) {
			var attributes = props.attributes;
			var result;

			if ( wppaOnWidgets() ) {
				result = el(
					'div',
					{},
					el('h3',{},attributes.widgetTitle),
					el('div',{},evaluate(props)),
				);
			}
			else {
				result = el(
					'div',
					{},
					evaluate(props),
				);
			}
			return result;
		},
	});

	function evaluate( props ) {
		var shortcode = '[wppa type="photo" photo="#potd"'+(wppaOnWidgets() ? ' widget="potd"' : '')+']';

		return shortcode;
	}

} )(
	window.wp.blocks,
	window.wp.editor,
	window.wp.i18n,
	window.wp.element,
	window.wp.components,
	window._,
	window.wp.blockEditor
)});
