/**
 * Internal dependencies
 */
import isCoreBlockWithExt from '../../utils/is-core-block-with-ext';
import dynamicStylesBYkey from '../../utils/dynamic-styles-by-key';
import ComponentColors from '../../components/colors';
import ComponentSpacings from '../../components/spacings';
import ComponentRadius from '../../components/radius';
import ComponentSchemeWrapper from '../../components/scheme-wrapper';
import ComponentResponsiveWrapper from '../../components/responsive-wrapper';

const {
	canvasSchemes,
	canvasBreakpoints,
} = window;

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

const {
	addFilter,
} = wp.hooks;

const {
	Component,
	Fragment,
} = wp.element;

const {
	createHigherOrderComponent,
} = wp.compose;

const { InspectorControls } = wp.blockEditor;

const {
	PanelBody,
	SelectControl,
} = wp.components;

const {
	hasBlockSupport,
} = wp.blocks;

const allBorders = {
	'borderWidthTop': 'border-top-width',
	'borderWidthBottom': 'border-bottom-width',
	'borderWidthLeft': 'border-left-width',
	'borderWidthRight': 'border-right-width',
};

const allBordersRadius = {
	'borderRadiusTopLeft': 'border-top-left-radius',
	'borderRadiusTopRight': 'border-top-right-radius',
	'borderRadiusBottomLeft': 'border-bottom-left-radius',
	'borderRadiusBottomRight': 'border-bottom-right-radius',
};

/**
 * Extend block attributes with borders.
 *
 * @param {Object} blockSettings Original block settings.
 * @param {String} name Original block name.
 *
 * @return {Object} Filtered block settings.
 */
function addAttribute( blockSettings, name ) {
	let supports = hasBlockSupport( name, 'canvasBorder', false );

	// add support to core blocks
	if ( isCoreBlockWithExt( name ) ) {
		blockSettings.supports = {
			...blockSettings.supports,
			canvasBorder: true,
		};
		supports = true;
	}

	if ( supports ) {
		if ( ! blockSettings.attributes ) {
			blockSettings.attributes = {};
		}

		// style.
		if ( ! blockSettings.attributes.borderStyle ) {
			blockSettings.attributes.borderStyle = {
				type: 'string',
			};
		}

		// color.
		Object.keys( canvasSchemes ).forEach( ( scheme ) => {
			let suffix = '';

			if ( scheme && 'default' !== scheme ) {
				suffix = `_${ scheme }`;
			}

			if ( ! blockSettings.attributes[ `borderColor${ suffix }` ] ) {
				blockSettings.attributes[ `borderColor${ suffix }` ] = {
					type: 'string',
				};
			}
		} );

		// responsive attributes.
		Object.keys( canvasBreakpoints ).forEach( ( breakpoint ) => {
			let suffix = '';

			if ( breakpoint && 'desktop' !== breakpoint ) {
				suffix = `_${ breakpoint }`;
			}

			// width.
			Object.keys( allBorders ).forEach( ( spacing ) => {
				if ( ! blockSettings.attributes[ spacing + suffix ] ) {
					blockSettings.attributes[ spacing + suffix ] = {
						type: 'number',
					};
				}
			} );

			// link.
			if ( ! blockSettings.attributes[ `borderWidthLink${ suffix }` ] ) {
				blockSettings.attributes[ `borderWidthLink${ suffix }` ] = {
					type: 'boolean',
				};
			}

			// unit.
			if ( ! blockSettings.attributes[ `borderWidthUnit${ suffix }` ] ) {
				blockSettings.attributes[ `borderWidthUnit${ suffix }` ] = {
					type: 'string',
				};
			}

			// radius.
			Object.keys( allBordersRadius ).forEach( ( radius ) => {
				if ( ! blockSettings.attributes[ radius + suffix ] ) {
					blockSettings.attributes[ radius + suffix ] = {
						type: 'number',
					};
				}
			} );

			// link.
			if ( ! blockSettings.attributes[ `borderRadiusLink${ suffix }` ] ) {
				blockSettings.attributes[ `borderRadiusLink${ suffix }` ] = {
					type: 'boolean',
				};
			}

			// unit.
			if ( ! blockSettings.attributes[ `borderRadiusUnit${ suffix }` ] ) {
				blockSettings.attributes[ `borderRadiusUnit${ suffix }` ] = {
					type: 'string',
				};
			}
		} );
	}

	return blockSettings;
}

/**
 * Override the default edit UI to include a new block inspector control for
 * assigning the custom borders if needed.
 *
 * @param {function|Component} BlockEdit Original component.
 *
 * @return {string} Wrapped component.
 */
const withInspectorControl = createHigherOrderComponent( ( OriginalComponent ) => {
	return class CanvasBordersWrapper extends Component {
		constructor() {
			super( ...arguments );

			this.getCurrentBorderStyles = this.getCurrentBorderStyles.bind( this );
			this.getCurrentValue = this.getCurrentValue.bind( this );
		}

		/**
		 * Get current border styles for preview purposes.
		 *
		 * @returns {String} border styles.
		 */
		getCurrentBorderStyles() {
			const {
				attributes,
			} = this.props;

			const {
				canvasClassName,
				borderStyle,
			} = attributes;

			let customStyles = '';

			if ( canvasClassName ) {
				Object.keys( canvasBreakpoints ).forEach( ( name ) => {
					let suffix = '';
					let borderRadiusUnit = 'px';
					let breakpointStyles = '';

					if ( name && name !== 'desktop' ) {
						suffix = '_' + name;
					}

					if ( attributes[ 'borderRadiusUnit' + suffix ] ) {
						borderRadiusUnit = attributes[ 'borderRadiusUnit' + suffix ];
					}

					Object.keys( allBordersRadius ).forEach( ( radius ) => {
						if ( typeof attributes[ radius + suffix ] !== 'undefined' ) {
							breakpointStyles += `${ allBordersRadius[ radius ] }: ${ attributes[ radius + suffix ] }${ borderRadiusUnit }; `;
						}
					} );

					if ( breakpointStyles ) {
						breakpointStyles = `.${ canvasClassName } { ${ breakpointStyles } }`;

						if ( suffix ) {
							breakpointStyles = `@media (max-width: ${ canvasBreakpoints[ name ].width }px) { ${ breakpointStyles } } `;
						}

						customStyles += breakpointStyles;
					}
				} );
			}

			if ( canvasClassName && borderStyle ) {
				let mainStyles = '';

				mainStyles  = `border-style: ${ borderStyle };`;
				mainStyles += `border-width: 0;`;

				customStyles += `.${ canvasClassName } { ${ mainStyles } }`;

				Object.keys( canvasSchemes ).forEach( ( name ) => {
					let rule   = '';
					let suffix = '';

					if ( name && 'default' !== name ) {
						suffix = `_${ name }`;

						rule = `[data-scheme="${ name }"]`;
					}

					if ( attributes[ 'borderColor' + suffix ] ) {
						mainStyles = `border-color: ${ attributes[ 'borderColor' + suffix ] };`;

						customStyles += `${rule} .${ canvasClassName } { ${ mainStyles } }`;
					}
				} );

				Object.keys( canvasBreakpoints ).forEach( ( name ) => {
					let suffix = '';
					let borderWidthUnit  = 'px';
					let breakpointStyles = '';

					if ( name && name !== 'desktop' ) {
						suffix = '_' + name;
					}

					if ( attributes[ 'borderWidthUnit' + suffix ] ) {
						borderWidthUnit = attributes[ 'borderWidthUnit' + suffix ];
					}

					Object.keys( allBorders ).forEach( ( spacing ) => {
						if ( typeof attributes[ spacing + suffix ] !== 'undefined' ) {
							breakpointStyles += `${ allBorders[ spacing ] }: ${ attributes[ spacing + suffix ] }${ borderWidthUnit }; `;
						}
					} );

					if ( breakpointStyles ) {
						breakpointStyles = `.${ canvasClassName } { ${ breakpointStyles } }`;

						if ( suffix ) {
							breakpointStyles = `@media (max-width: ${ canvasBreakpoints[ name ].width }px) { ${ breakpointStyles } } `;
						}

						customStyles += breakpointStyles;
					}
				} );
			}

			return customStyles;
		}

		/**
		 * Get current value.
		 *
		 * @param {String} name - name.
		 * @param {String} suffix - suffix.
		 *
		 * @return {Int}
		 */
		getCurrentValue( name, suffix = '' ) {
			const {
				attributes,
			} = this.props;

			return attributes[ `${ name }${ suffix }` ];
		}

		render() {
			if ( ! hasBlockSupport( this.props.name, 'canvasBorder', false ) ) {
				return <OriginalComponent { ...this.props } />;
			}

			const {
				attributes,
				setAttributes,
			} = this.props;

			const {
				canvasClassName,
			} = attributes;

			dynamicStylesBYkey( 'borders', canvasClassName, this.getCurrentBorderStyles() );

			// add new borders controls.
			return (
				<Fragment>
					<OriginalComponent
						{ ...this.props }
						{ ...this.state }
						setState={ this.setState }
					/>
					<InspectorControls>
						<PanelBody
							title={ __( 'Borders' ) }
							initialOpen={ false }
						>
							<ComponentResponsiveWrapper>
								{ ( { responsiveSuffix, ComponentResponsiveDropdown } ) => {
									return (
										<Fragment>
											<h2>
												{ __( 'Radius' ) }
												<ComponentResponsiveDropdown />
											</h2>
											<ComponentRadius
												prefix="borderRadius"
												suffix={ responsiveSuffix }
												units={ [ 'px', '%' ] }
												topLeft={ this.getCurrentValue( 'borderRadiusTopLeft', responsiveSuffix ) }
												topRight={ this.getCurrentValue( 'borderRadiusTopRight', responsiveSuffix ) }
												bottomLeft={ this.getCurrentValue( 'borderRadiusBottomLeft', responsiveSuffix ) }
												bottomRight={ this.getCurrentValue( 'borderRadiusBottomRight', responsiveSuffix ) }
												link={ this.getCurrentValue( 'borderRadiusLink', responsiveSuffix ) }
												unit={ this.getCurrentValue( 'borderRadiusUnit', responsiveSuffix ) }
												onChange={ ( attributes ) => {
													setAttributes( attributes );
												} }
											/>
										</Fragment>
									);
								} }
							</ComponentResponsiveWrapper>
							<SelectControl
								label={ __( 'Border' ) }
								value={ attributes.borderStyle }
								options={ [
									{
										label: __( 'None' ),
										value: '',
									}, {
										label: __( 'Solid' ),
										value: 'solid',
									}, {
										label: __( 'Dashed' ),
										value: 'dashed',
									}, {
										label: __( 'Dotted' ),
										value: 'dotted',
									}, {
										label: __( 'Double' ),
										value: 'double',
									},
								] }
								onChange={ ( val ) => {
									setAttributes( {
										borderStyle: val,
									} );
								} }
							/>
							{ attributes.borderStyle ? (
								<ComponentResponsiveWrapper>
									{ ( { responsiveSuffix, ComponentResponsiveDropdown } ) => {
										return (
											<Fragment>
												<h2>
													{ __( 'Width' ) }
													<ComponentResponsiveDropdown />
												</h2>
												<ComponentSpacings
													prefix="borderWidth"
													suffix={ responsiveSuffix }
													units={ [ 'px' ] }
													top={ this.getCurrentValue( 'borderWidthTop', responsiveSuffix ) }
													bottom={ this.getCurrentValue( 'borderWidthBottom', responsiveSuffix ) }
													left={ this.getCurrentValue( 'borderWidthLeft', responsiveSuffix ) }
													right={ this.getCurrentValue( 'borderWidthRight', responsiveSuffix ) }
													link={ this.getCurrentValue( 'borderWidthLink', responsiveSuffix ) }
													unit={ this.getCurrentValue( 'borderWidthUnit', responsiveSuffix ) }
													onChange={ ( attributes ) => {
														setAttributes( attributes );
													} }
												/>
											</Fragment>
										);
									} }
								</ComponentResponsiveWrapper>
							) : '' }
							{ attributes.borderStyle ? (
								<ComponentSchemeWrapper>
									{({ schemeSuffix, ComponentSchemeDropdown }) => {
									return (
										<Fragment>
											<h2>
												{ __( 'Color' ) }
												<ComponentSchemeDropdown />
											</h2>

											<ComponentColors
												slug='borderColor'
												suffix={ schemeSuffix }
												val={ this.getCurrentValue( 'borderColor', schemeSuffix ) }
												onChange={ ( attributes ) => {
													setAttributes( attributes );
												} }
											/>
										</Fragment>
										)
									}}
								</ComponentSchemeWrapper>
							) : '' }
						</PanelBody>
					</InspectorControls>
				</Fragment>
			);
		}
	};
}, 'withInspectorControl' );

// Init filters.
addFilter( 'blocks.registerBlockType', 'canvas/borders/additional-attributes', addAttribute );
addFilter( 'editor.BlockEdit', 'canvas/borders/additional-attributes', withInspectorControl );
