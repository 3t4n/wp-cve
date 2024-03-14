/**
 * WPPA Upload block
 *
 * Version: 8.5.01.001
 */

jQuery(document).ready(function(){
( function ( blocks, editor, i18n, element, components, _, blockEditor ) {

	var el = element.createElement;
	var __ = i18n.__;
	var RichText = blockEditor.RichText;
	var useBlockProps = blockEditor.useBlockProps;
	var wppaUploadAlbumList = wppaAlbumList.slice();
	wppaUploadAlbumList[0] = { label: __( '--- A selectionbox with all albums the user may upload to ---', 'wp-photo-album-plus' ), value: "0" };

	blocks.registerBlockType( 'wp-photo-album-plus/upload', {
		title: __( 'WPPA+ upload', 'wp-photo-album-plus' ),
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
			wppaAlbum: {
				type: 'number',
				default: 0,
			},
			wppaLoginOnly: {
				type: 'boolean',
				default: false,
			},
			wppaAdminOnly: {
				type: 'boolean',
				default: false,
			},
			wppaShortcode: {
				type: 'string',
				default: '[wppa type="upload"]',
			}
		},

		edit: function( props ) {
			var blockProps = wp.blockEditor.useBlockProps();
			var attributes = props.attributes;

			return el(
				'div',
				blockProps,
				el('h2',null,__( 'WPPA Upload form', 'wp-photo-album-plus' )),
				wppaOnWidgets() ?
				el( RichText, {
					tagName: 'h3',
					placeholder: __( 'Enter widget caption', 'wp-photo-album-plus' ),
					value: attributes.widgetTitle,
					onChange: function ( value ) {
						props.setAttributes( { widgetTitle: value } );
					},
				}) : null,
				el(
					wp.components.SelectControl,
					{
						help: __( 'If you want to limit uploads to a specific album, select it here', 'wp-photo-album-plus' ),
						value: props.attributes.wppaAlbum,
						options: wppaUploadAlbumList,
						onChange: function( val ) {
							props.setAttributes( { wppaAlbum: parseInt(val) } );
							props.setAttributes( { wppaShortcode: evaluate(props) } );
						},
					}
				),
				( ! attributes.wppaAdminOnly ?
					el( wp.components.CheckboxControl, {
						label: __( 'Show to logged in users only?', 'wp-photo-album-plus' ),
						checked: attributes.wppaLoginOnly,
						onChange: function ( value ) {
							props.setAttributes( { wppaLoginOnly: value } );
						},
					}) : null
				),
				el( wp.components.CheckboxControl, {
					label: __( 'Show to admin only?', 'wp-photo-album-plus' ),
					checked: attributes.wppaAdminOnly,
					onChange: function ( value ) {
						props.setAttributes( { wppaAdminOnly: value } );
						if ( value ) {
							props.setAttributes( { wppaLoginOnly: false } );
						}
					},
				}),
				el('small',null,__( 'Corresponding shortcode', 'wp-photo-album-plus' )+': '+ evaluate(props)),
			);
		},

		save: function( props ) {
			var attributes = props.attributes;

			return el(
				'div',
				{},
				el('h3',{},attributes.widgetTitle),
				el('div',{},evaluate(props)),
			);
		}
	} );

	function evaluate( props ) {
		var attributes = props.attributes;

		var shortcode = '[wppa type="upload"';
		if ( attributes.wppaAlbum ) {
			shortcode += ' album="'+attributes.wppaAlbum+'"';
		}
		if ( attributes.wppaAdminOnly ) {
			shortcode += ' login="admin"';
		}
		else if ( attributes.wppaLoginOnly ) {
			shortcode += ' login="yes"';
		}
		if ( wppaOnWidgets() ) {
			shortcode += ' widget="upload"';
		}
		shortcode += ']';

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



