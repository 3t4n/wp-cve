/**
 * BLOCK: drop-shadow-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';

const { registerBlockType } = wp.blocks;
const { RangeControl, SelectControl, ToggleControl, PanelBody } = wp.components;
const { InspectorControls, InnerBlocks, PanelColorSettings, ColorPalette, BlockAlignmentToolbar } = wp.blockEditor;
const { Fragment } = wp.element;

const { __ } = wp.i18n;

import classnames from 'classnames';


/**
 * Register the Dropshadow Block
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'stevehenty/drop-shadow-box', {
	title: __( 'Drop Shadow Box', 'drop-shadow-boxes' ),
	icon:   () => {
		return <svg height="20" width="20">
			<filter id="f1" x="0" y="0" width="120%" height="120%">
				<feOffset result="offOut" in="SourceAlpha" dx="10" dy="10" />
				<feGaussianBlur result="blurOut" in="offOut" stdDeviation="10" />
				<feBlend in="SourceGraphic" in2="blurOut" mode="normal" />
			</filter>
					<rect width="15" height="15" stroke="gray" strokeWidth="1" fill="white" filter="url(#f1)" />
				</svg>
	},

	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'Add Drop Shadow Box', 'drop-shadow-boxes' ),
		__( 'Drop Shadow', 'drop-shadow-boxes' )
	],

	attributes: {
		align: {
			type: 'string',
		},
		effect: {
			type: 'string',
			default: 'lifted-both',
		},
		backgroundColor: {
			type: 'string',
			default: '#FFFFFF',
		},
		borderWidth: {
			type: 'number',
			default: 2,
		},
		borderColor: {
			type: 'string',
			default: '#DDDDDD',
		},
		roundedCorners: {
			type: 'bool',
			default: true,
		},
		insideShadow: {
			type: 'bool',
			default: true,
		},
		outsideShadow: {
			type: 'bool',
			default: true,
		},
		width: {
			type: 'string',
			default: '100',
		},
		widthUnits: {
			type: 'string',
			default: 'auto',
		},
		padding: {
			type: 'number',
			default: 10,
		},
	},

	description: __( 'Select the options below for your drop-shadow box.', 'drop-shadow-boxes' ),

	transforms: {
		from: [
			{
				type: 'shortcode',
				// Shortcode tag can also be an array of shortcode aliases
				tag: 'dropshadowbox',
				attributes: {
					// An attribute can be source from the shortcode attributes
					effect: {
						type: 'string',
						shortcode: ( { named: { effect } } ) => {
							return effect;
						},
					},
					backgroundColor: {
						type: 'string',
						default: '#FFFFFF',
						shortcode: ( { named: { background_color } } ) => {
							return background_color;
						},
					},
					borderWidth: {
						type: 'number',
						default: 2,
						shortcode: ( { named: { border_width } } ) => {
							return border_width;
						},
					},
					borderColor: {
						type: 'string',
						default: '#DDDDDD',
						shortcode: ( { named: { border_color } } ) => {
							return border_color;
						},
					},
					roundedCorners: {
						type: 'bool',
						default: true,
						shortcode: ( { named: { rounded_corners } } ) => {
							return rounded_corners;
						},
					},
					insideShadow: {
						type: 'bool',
						default: true,
						shortcode: ( { named: { inside_shadow } } ) => {
							return inside_shadow;
						},
					},
					outsideShadow: {
						type: 'bool',
						default: true,
						shortcode: ( { named: { outside_shadow } } ) => {
							return outside_shadow;
						},
					},
					width: {
						type: 'string',
						shortcode: ( { named: { width } } ) => {
							return width;
						},
					},
					widthUnits: {
						type: 'string',
						default: 'auto',
						shortcode: ( { named: { width } } ) => {
							return width;
						},
					},
					padding: {
						type: 'number',
						default: 10,
						shortcode: ( { named: { padding } } ) => {
							return padding;
						},
					},
				},
			},
		],
	},


	getEditWrapperProps( attributes ) {
		const { align } = attributes;
		if ( [ 'wide', 'full', 'left', 'right' ].indexOf( align ) !== -1 ) {
			return { 'data-align': align, 'data-width': 50 };
		}
	},

	edit( { attributes, setAttributes, className } ) {
		const { align, widthUnits, effect, borderWidth, borderColor, roundedCorners, insideShadow, outsideShadow, padding } = attributes;
		let { backgroundColor, width } = attributes;
		if ( ! backgroundColor ) {
			backgroundColor = '#FFFFFF'
		}

		let widthSlider = '';

		let units = widthUnits == 'percent' ? '%' : 'px';

		if (widthUnits != 'auto') {
			widthSlider = (<RangeControl
				label={ __( 'Width', 'drop-shadow-boxes' ) }
				value={ width }
				onChange={ ( nextWidth ) => {
							setAttributes( {
								width: nextWidth,
							} );
						} }
				min={ 0 }
				max={  units == '%' ? 100 : 1000 }
			/>)
		}

		let widthControls = (
			<div>
				<SelectControl
					label={ __( 'Width:', 'drop-shadow-boxes' ) }
					value={ widthUnits }
					onChange={ ( value ) => {
							setAttributes( {
												widthUnits: value,
											} );
										} }
					options={ [
										{ value: 'auto', label: __( 'auto', 'drop-shadow-boxes' ) },
										{ value: 'pixels', label: __( 'pixels', 'drop-shadow-boxes' ) },
										{ value: 'percent', label: __( '%', 'drop-shadow-boxes' ) },
									] }
				/>
				{ widthSlider }
			</div>
		)

		let widthStyle = ( widthUnits == 'auto' ? widthUnits : width + units );
		let boxWidth = '';
		if ( align == 'center' ) {
			boxWidth = ( widthUnits == 'auto' ? widthUnits : width + units );
			widthStyle = '100%';
		}

		return (
				<Fragment>
					<InspectorControls key="inspector">
						{ widthControls }
						<SelectControl
							label={ __( 'Effect:', 'drop-shadow-boxes' ) }
							value={ effect }
							onChange={ ( value ) => {
								setAttributes( {
													effect: value,
												} );
											} }
							options={ [
											{ value: 'lifted-both', label: __( 'Lifted (Both)', 'drop-shadow-boxes' ) },
											{ value: 'lifted-bottom-left', label: __( 'Lifted (Left)', 'drop-shadow-boxes' ) },
											{ value: 'lifted-bottom-right', label: __( 'Lifted (Right)', 'drop-shadow-boxes' ) },
											{ value: 'curled', label: __( 'Curled', 'drop-shadow-boxes') },
											{ value: 'perspective-left', label: __( 'Perspective (Left)', 'drop-shadow-boxes' ) },
											{ value: 'perspective-right', label: __( 'Perspective (Right)', 'drop-shadow-boxes' ) },
											{ value: 'curved', label: __( 'Vertical Curve (Left)', 'drop-shadow-boxes' ) },
											{ value: 'curved dropshadowboxes-curved-vertical-2', label: __( 'Vertical Curve (Both)', 'drop-shadow-boxes' ) },
											{ value: 'curved dropshadowboxes-curved dropshadowboxes-curved-horizontal-1', label: __( 'Horizontal Curve (Bottom)', 'drop-shadow-boxes' ) },
											{ value: 'curved dropshadowboxes-curved dropshadowboxes-curved-horizontal-2', label: __( 'Horizontal Curve (Both)', 'drop-shadow-boxes' ) },
										] }
						/>
						<ToggleControl
							label={ __( 'Inside shadow', 'drop-shadow-boxes' ) }
							checked={ insideShadow }
							onChange={ () => setAttributes( { insideShadow: ! insideShadow } ) }
						/>
						<ToggleControl
							label={ __( 'Outside shadow', 'drop-shadow-boxes' ) }
							checked={ outsideShadow }
							onChange={ () => setAttributes( { outsideShadow: ! outsideShadow } ) }
						/>
						<PanelColorSettings
							title={__('Colours', 'drop-shadow-boxes')}
							initialOpen={false}
							colorSettings={[
								{
									value: backgroundColor,
									onChange: (colorValue) => setAttributes({backgroundColor: colorValue}),
									label: __('Background', 'drop-shadow-boxes'),
								},
								{
									value: borderColor,
									onChange: (colorValue) => setAttributes({borderColor: colorValue}),
									label: __('Border', 'drop-shadow-boxes'),
								},
							]}>
						</PanelColorSettings>
						<RangeControl
							label={ __( 'Border (pixels):', 'drop-shadow-boxes' ) }
							value={ borderWidth }
							onChange={ ( nextBorderWidth ) => {
								setAttributes( {
									borderWidth: nextBorderWidth,
								} );
							} }
							min={ 0 }
							max={ 10 }
						/>
						<ToggleControl
							label={ __( 'Rounded corners', 'drop-shadow-boxes' ) }
							checked={ roundedCorners }
							onChange={ () => setAttributes( { roundedCorners: ! roundedCorners } ) }
						/>
						<PanelBody title={ __( 'Alignment:', 'drop-shadow-boxes' ) }>
							<BlockAlignmentToolbar
								value={ align }
								onChange={ ( nextAlign ) => setAttributes( { align: nextAlign } ) }
							/>
						</PanelBody>
						<RangeControl
							label={ __( 'Padding', 'drop-shadow-boxes' ) }
							value={ padding }
							onChange={ ( nextPadding ) => {
								setAttributes( {
									padding: nextPadding,
								} );
							} }
							min={ 0 }
							max={ 50 }
						/>
					</InspectorControls>

					<div key="wrap" className={ classnames( className, 'dropshadowboxes-container', { 'dropshadowboxes-left': align == 'left' }, { 'dropshadowboxes-right': align == 'right' }, { 'dropshadowboxes-center': align == 'center' } ) } style={{ width: widthStyle }}>
						<div key="inner"
							 className={ classnames( 'dropshadowboxes-drop-shadow', 'dropshadowboxes-' + effect, { 'dropshadowboxes-rounded-corners': roundedCorners }, { 'dropshadowboxes-inside-and-outside-shadow': insideShadow && outsideShadow }, { 'dropshadowboxes-inside-shadow': insideShadow && ! outsideShadow }, { 'dropshadowboxes-outside-shadow': ! insideShadow && outsideShadow } ) }
							style={{backgroundColor: backgroundColor, borderStyle: 'solid', borderWidth: borderWidth, borderColor: borderColor, width: boxWidth, padding: padding }}>
							<div key="container">
								<InnerBlocks />
							</div>
						</div>
					</div>
				</Fragment>
		)
	},

	save( { attributes } ) {
		const { align, effect, backgroundColor, borderWidth, borderColor, roundedCorners, insideShadow, outsideShadow, width, widthUnits, padding, className } = attributes;
		let units = widthUnits == 'percent' ? '%' : 'px';
		let widthStyle = ( widthUnits == 'auto' ? widthUnits : width + units );
		let boxWidth = '';
		if ( align == 'center' ) {
			boxWidth = ( widthUnits == 'auto' ? widthUnits : width + units );
			widthStyle = '100%';
		}

		return (
			<div className={ classnames( className, 'dropshadowboxes-container', { 'dropshadowboxes-left': align == 'left' }, { 'dropshadowboxes-right': align == 'right' }, { 'dropshadowboxes-center': align == 'center' } ) } style={{ width: widthStyle }}>
				<div className={ classnames( 'dropshadowboxes-drop-shadow', 'dropshadowboxes-' + effect, { 'dropshadowboxes-rounded-corners': roundedCorners }, { 'dropshadowboxes-inside-and-outside-shadow': insideShadow && outsideShadow }, { 'dropshadowboxes-inside-shadow': insideShadow && ! outsideShadow }, { 'dropshadowboxes-outside-shadow': ! insideShadow && outsideShadow } ) }
				     style={{backgroundColor: backgroundColor, borderStyle: 'solid', borderWidth: borderWidth, borderColor: borderColor, width: boxWidth, padding: padding }}>
					<div>
						<InnerBlocks.Content />
					</div>
				</div>
			</div>
		);
	},
} );
