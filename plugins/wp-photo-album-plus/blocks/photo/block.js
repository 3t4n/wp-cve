/**
 * WPPA photo block
 *
 * Version: 8.5.01.001
 */

jQuery(document).ready(function(){
( function ( blocks, editor, i18n, element, components, _, blockEditor ) {

	var el = element.createElement;
	var __ = i18n.__;
	var RichText = blockEditor.RichText;
	var useBlockProps = blockEditor.useBlockProps;

	blocks.registerBlockType( 'wp-photo-album-plus/photo', {
		title: __( 'WPPA+ Photo', 'wp-photo-album-plus' ),
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
			wppaPhoto: {
				type: 'number',
				default: 0,
			},
			wppaShortcode: {
				type: 'string',
				default: '[photo]',
			},
		},

		example: {
			attributes: {
				widgetTitle: 'Photo',
				wppaShortcode: '[photo 3699]',
			},
		},

		edit: function( props ) {
			var blockProps = wp.blockEditor.useBlockProps();
			var attributes = props.attributes;
			var shortcode  = evaluate(props);
			var result;
			var previewId  = 'wppaphoto-'+Math.floor(Math.random() * 100);
			var needPreview = true;

			result = el(
				'div',
				blockProps,
				el('h2',null,__( 'WPPA+ Photo', 'wp-photo-album-plus' )),
				wppaOnWidgets() ?
				el( RichText, {
					tagName: 'h3',
					placeholder: __( 'Enter widget caption', 'wp-photo-album-plus' ),
					value: attributes.widgetTitle,
					onChange: function ( value ) {
						props.setAttributes( { 	widgetTitle: value,
												wppaShortcode: evaluate(props),
												});
						needPreview = true;
					},
				}) : null,

				el(wp.components.SelectControl,
					{
						label: __( 'Select the photo to show', 'wp-photo-album-plus' ),
						value: attributes.wppaPhoto,
						options: wppaPhotoList,
						onChange: function( value ) {
							props.setAttributes( { wppaPhoto: parseInt(value) } );
							props.setAttributes( { wppaShortcode: evaluate(props) } );
							needPreview = true;
						},
				}),

				el('small',null,__( 'Corresponding shortcode', 'wp-photo-album-plus' )+': '+evaluate(props)),
				el('div', {id: previewId}),
			);

			setTimeout(function(){if(needPreview)wppaGutenbergGetWppaShorcodeRendered(shortcode, previewId);needPreview=false;},100);
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
		var attributes = props.attributes;
		var shortcode;
		var photo = attributes.wppaPhoto;

		if ( photo > 0 ) {
			shortcode = '[photo '+photo+(wppaOnWidgets() ? ' widget="photo"' : '')+']';
		}
		else {
			shortcode = '[photo]';
		}

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
