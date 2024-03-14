/**
 * WPPA slideshow block
 *
 * Version: 8.5.01.001
 */

jQuery(document).ready(function(){
( function ( blocks, editor, i18n, element, components, _, blockEditor ) {

	var el = element.createElement;
	var __ = i18n.__;
	var RichText = blockEditor.RichText;
	var CheckboxControl = blockEditor.CheckboxControl;
	var useBlockProps = blockEditor.useBlockProps;
	var wppaSlideshowAlbumList = wppaAlbumList.slice();
		wppaSlideshowAlbumList[0] = { label: __( '--- The last added album ---', 'wp-photo-album-plus' ), value: "0" };

	blocks.registerBlockType( 'wp-photo-album-plus/slideshow', {
		title: __( 'WPPA+ Slideshow', 'wp-photo-album-plus' ),
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
			wppaShortcode: {
				type: 'string',
				default: '[wppa type="slideonly" album="#last"]',
			},
			timeout: {
				type: 'string',
				default: wppaSlideshowDefaultTimeout,
			},
			filmstrip: {
				type: 'boolean',
				default: false,
			},
			cacheIt: {
				type: 'boolean',
				default: false,
			},
			delayIt: {
				type: 'boolean',
				default: false,
			}
		},

		example: {
			attributes: {
				widgetTitle: __('Slideshow', 'wp-photo-album-plus'),
				wppaAlbum: 0,
				wppaShortcode: '[wppa type="slideonly" album="#last"]',
				filmstrip: false,
				timeout: '2.5',
				cacheIt: false,
			},
		},

		edit: function( props ) {
			var blockProps = wp.blockEditor.useBlockProps();
			var attributes = props.attributes;
			var shortcode  = evaluate(props);
			var result;
			var previewId  = 'wppaslide-'+Math.floor(Math.random() * 100);
			var needPreview = true;

				result = el(
					'div',
					blockProps,
					el('h2',null,__( 'WPPA+ Simple slideshow', 'wp-photo-album-plus' )),
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
							label: __( 'Select an album', 'wp-photo-album-plus' ),
							value: attributes.wppaAlbum,
							options: wppaSlideshowAlbumList,
							onChange: function( val ) {
								props.setAttributes( { wppaAlbum: parseInt(val) } );
								props.setAttributes( { wppaShortcode: evaluate(props) } );
								needPreview = true;
							},
						}
					),
					el( wp.components.TextControl, {
						label: __( 'Timeout in seconds ( 0 means use default )', 'wp-photo-album-plus' ),
						value: attributes.timeout,
						onChange: function( value ) {
							props.setAttributes( { timeout: value } );
							props.setAttributes( { wppaShortcode: evaluate(props) } );
							needPreview = true;
						},
					}),
					el( wp.components.CheckboxControl, {
						label: __( 'Add filmstrip', 'wp-photo-album-plus' ),
						checked: attributes.filmstrip,
						onChange: function( value ) {
							props.setAttributes( { filmstrip: value } );
							needPreview = true;
						},
					}),
					el( wp.components.CheckboxControl, {
						label: __( 'Cache this block', 'wp-photo-album-plus' )+'. '+__( 'Cache uses WPPA embedded smart cache', 'wp-photo-album-plus' ),
			//			help: __( 'Cache uses WPPA embedded smart cache', 'wp-photo-album-plus' ),
						checked: attributes.cacheIt,
						onChange: function ( value ) {
							props.setAttributes( { cacheIt: value } );
						},
					}),
					el( wp.components.CheckboxControl, {
						label: __( 'Delay this block', 'wp-photo-album-plus' ),
						checked: attributes.delayIt,
						onChange: function ( value ) {
							props.setAttributes( { delayIt: value } );
						},
					}),
					el('small',null,__( 'Corresponding shortcode', 'wp-photo-album-plus' )+': '+evaluate(props)),
					el('div', {id: previewId}),
				);

			setTimeout(function(){if(needPreview)wppaGutenbergGetWppaShorcodeRendered(shortcode.replace('#','%23'), previewId);needPreview=false;},100);
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
	} );

	function evaluate( props ) {
		var attributes = props.attributes;
		var shortcode;
		var timeout = parseInt( attributes.timeout );

		if ( attributes.filmstrip ) {
			shortcode = '[wppa type="slideonlyf"';
		}
		else {
			shortcode = '[wppa type="slideonly"';
		}

		if ( parseInt(attributes.wppaAlbum) > 0 ) {
			shortcode += ' album="'+attributes.wppaAlbum+'"';
		}
		else {
			shortcode += ' album="#last"';
		}

		if ( attributes.cacheIt ) {
			shortcode += ' cache="inf"';
		}

		if ( attributes.delayIt ) {
			shortcode += ' delay="yes"';
		}

		if ( timeout ) {
			shortcode += ' timeout="'+timeout+'"';
		}

		if ( wppaOnWidgets() ) {
			shortcode += ' widget="slide"';
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
