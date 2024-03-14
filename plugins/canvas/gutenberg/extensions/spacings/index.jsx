/**
 * Internal dependencies
 */
import isCoreBlockWithExt from '../../utils/is-core-block-with-ext';
import dynamicStylesBYkey from '../../utils/dynamic-styles-by-key';
import ComponentSpacings from '../../components/spacings';
import ComponentResponsiveWrapper from '../../components/responsive-wrapper';

const {
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
} = wp.components;

const {
	hasBlockSupport,
} = wp.blocks;

const allSpacings = {
	'marginTop': 'margin-top',
	'marginBottom': 'margin-bottom',
	'marginLeft': 'margin-left',
	'marginRight': 'margin-right',
	'paddingTop': 'padding-top',
	'paddingBottom': 'padding-bottom',
	'paddingLeft': 'padding-left',
	'paddingRight': 'padding-right',
};

/**
 * Extend block attributes with spacings.
 *
 * @param {Object} blockSettings Original block settings.
 * @param {String} name Original block name.
 *
 * @return {Object} Filtered block settings.
 */
function addAttribute( blockSettings, name ) {
	let supports = hasBlockSupport( name, 'canvasSpacings', false );

	// add support to core blocks
	if ( isCoreBlockWithExt( name ) ) {
		blockSettings.supports = {
			...blockSettings.supports,
			canvasSpacings: true,
		};
		supports = true;
	}

	if ( supports ) {
		if ( ! blockSettings.attributes ) {
			blockSettings.attributes = {};
		}

		// responsive attributes.
		Object.keys( canvasBreakpoints ).forEach( ( breakpoint ) => {
			let suffix = '';

			if ( breakpoint && 'desktop' !== breakpoint ) {
				suffix = `_${ breakpoint }`;
			}

			// spacings.
			Object.keys( allSpacings ).forEach( ( spacing ) => {
				if ( ! blockSettings.attributes[ spacing + suffix ] ) {
					blockSettings.attributes[ spacing + suffix ] = {
						type: 'number',
					};
				}
			} );

			// link.
			if ( ! blockSettings.attributes[ `marginLink${ suffix }` ] ) {
				blockSettings.attributes[ `marginLink${ suffix }` ] = {
					type: 'boolean',
				};
			}
			if ( ! blockSettings.attributes[ `paddingLink${ suffix }` ] ) {
				blockSettings.attributes[ `paddingLink${ suffix }` ] = {
					type: 'boolean',
				};
			}

			// unit.
			if ( ! blockSettings.attributes[ `marginUnit${ suffix }` ] ) {
				blockSettings.attributes[ `marginUnit${ suffix }` ] = {
					type: 'string',
				};
			}
			if ( ! blockSettings.attributes[ `paddingUnit${ suffix }` ] ) {
				blockSettings.attributes[ `paddingUnit${ suffix }` ] = {
					type: 'string',
				};
			}
		} );
	}

	return blockSettings;
}

/**
 * Override the default edit UI to include a new block inspector control for
 * assigning the custom spacings if needed.
 *
 * @param {function|Component} BlockEdit Original component.
 *
 * @return {string} Wrapped component.
 */
const withInspectorControl = createHigherOrderComponent( ( OriginalComponent ) => {
	return class CanvasSpacingsWrapper extends Component {
		constructor() {
			super( ...arguments );

			this.getCurrentSpacingStyles = this.getCurrentSpacingStyles.bind( this );
			this.getSpacing = this.getSpacing.bind( this );
		}

		/**
		 * Get current spacing styles for preview purposes.
		 *
		 * @returns {String} spacing styles.
		 */
		getCurrentSpacingStyles() {
			const {
				attributes,
			} = this.props;

			const {
				canvasClassName,
			} = attributes;

			let customStyles = '';

			if ( canvasClassName ) {
				Object.keys( canvasBreakpoints ).forEach( ( name ) => {
					let suffix = '';
					let marginUnit = 'px';
					let paddingUnit = 'px';
					let breakpointStyles = '';

					if ( name && name !== 'desktop' ) {
						suffix = '_' + name;
					}

					if ( attributes[ 'marginUnit' + suffix ] ) {
						marginUnit = attributes[ 'marginUnit' + suffix ];
					}
					if ( attributes[ 'paddingUnit' + suffix ] ) {
						paddingUnit = attributes[ 'paddingUnit' + suffix ];
					}

					Object.keys( allSpacings ).forEach( ( spacing ) => {
						if ( typeof attributes[ spacing + suffix ] !== 'undefined' ) {
							const currentUnit = /^margin/g.test( spacing ) ? marginUnit : paddingUnit;
							breakpointStyles += `${ allSpacings[ spacing ] }: ${ attributes[ spacing + suffix ] }${ currentUnit }; `;
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
		 * Get current spacing value.
		 *
		 * @param {String} name - spacing name.
		 * @param {String} suffix - responsive suffix.
		 *
		 * @return {Int}
		 */
		getSpacing( name, suffix = '' ) {
			const {
				attributes,
			} = this.props;

			return attributes[ `${ name }${ suffix }` ];
		}

		render() {
			if ( ! hasBlockSupport( this.props.name, 'canvasSpacings', false ) ) {
				return <OriginalComponent { ...this.props } />;
			}

			const {
				attributes,
				setAttributes,
			} = this.props;

			const {
				canvasClassName,
			} = attributes;

			dynamicStylesBYkey( 'spacings', canvasClassName, this.getCurrentSpacingStyles() );

			// add new spacings controls.
			return (
				<Fragment>
					<OriginalComponent
						{ ...this.props }
						{ ...this.state }
						setState={ this.setState }
					/>
					<InspectorControls>
						<PanelBody
							title={ __( 'Spacings' ) }
							initialOpen={ false }
						>
							<ComponentResponsiveWrapper>
								{ ( { responsiveSuffix, ComponentResponsiveDropdown } ) => {
									return (
										<Fragment>
											{ /* Margins */ }
											<h2>
												{ __( 'Margins' ) }
												<ComponentResponsiveDropdown />
											</h2>
											<ComponentSpacings
												prefix="margin"
												suffix={ responsiveSuffix }
												units={ [ 'px', '%' ] }
												top={ this.getSpacing( 'marginTop', responsiveSuffix ) }
												bottom={ this.getSpacing( 'marginBottom', responsiveSuffix ) }
												left={ this.getSpacing( 'marginLeft', responsiveSuffix ) }
												right={ this.getSpacing( 'marginRight', responsiveSuffix ) }
												link={ this.getSpacing( 'marginLink', responsiveSuffix ) }
												unit={ this.getSpacing( 'marginUnit', responsiveSuffix ) }
												onChange={ ( attributes ) => {
													setAttributes( attributes );
												} }
											/>

											{ /* Paddings */ }
											<h2>
												{ __( 'Paddings' ) }
												<ComponentResponsiveDropdown />
											</h2>
											<ComponentSpacings
												prefix="padding"
												suffix={ responsiveSuffix }
												units={ [ 'px', 'em', '%' ] }
												top={ this.getSpacing( 'paddingTop', responsiveSuffix ) }
												bottom={ this.getSpacing( 'paddingBottom', responsiveSuffix ) }
												left={ this.getSpacing( 'paddingLeft', responsiveSuffix ) }
												right={ this.getSpacing( 'paddingRight', responsiveSuffix ) }
												link={ this.getSpacing( 'paddingLink', responsiveSuffix ) }
												unit={ this.getSpacing( 'paddingUnit', responsiveSuffix ) }
												onChange={ ( attributes ) => {
													setAttributes( attributes );
												} }
											/>
										</Fragment>
									);
								} }
							</ComponentResponsiveWrapper>
						</PanelBody>
					</InspectorControls>
				</Fragment>
			);
		}
	};
}, 'withInspectorControl' );

// Init filters.
addFilter( 'blocks.registerBlockType', 'canvas/spacings/additional-attributes', addAttribute );
addFilter( 'editor.BlockEdit', 'canvas/spacings/additional-attributes', withInspectorControl );
