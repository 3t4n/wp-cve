/**
 * Styles
 */
import './style.scss';


/**
 * Internal dependencies
 */
import isCoreBlockWithExt from '../../utils/is-core-block-with-ext';
import dynamicStylesBYkey from '../../utils/dynamic-styles-by-key';
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

const {
	BaseControl,
	PanelBody,
	RangeControl,
	SelectControl,
	DropZone,
	Button,
} = wp.components;

const {
	hasBlockSupport,
} = wp.blocks;

const {
	InspectorControls,
	MediaPlaceholder,
	MediaUpload,
	mediaUpload,
} = wp.blockEditor;


/**
 * Extend block attributes with background image.
 *
 * @param {Object} blockSettings Original block settings.
 * @param {String} name Original block name.
 *
 * @return {Object} Filtered block settings.
 */
function addAttribute( blockSettings, name ) {
	let supports = hasBlockSupport( name, 'canvasBackgroundImage', false );

	if ( isCoreBlockWithExt( name ) ) {
		blockSettings.supports = {
			...blockSettings.supports,
			canvasBackgroundImage: ['core/group'].includes(name) ? true : false,
		};
		supports = ['core/group'].includes(name) ? true : false;
	}

	if ( supports ) {
		if ( ! blockSettings.attributes ) {
			blockSettings.attributes = {};
		}

		// Responsive attributes.
		Object.keys( canvasBreakpoints ).forEach( ( breakpoint ) => {
			let suffix = '';

			if ( breakpoint && 'desktop' !== breakpoint ) {
				suffix = `_${ breakpoint }`;
			}

			// Background Image.
			if ( ! blockSettings.attributes[ `backgroundImage${ suffix }` ] ) {
				blockSettings.attributes[ `backgroundImage${ suffix }` ] = {
					type: 'object',
				};
			}

			// Background Position.
			if ( ! blockSettings.attributes[ `backgroundPosition${ suffix }` ] ) {
				blockSettings.attributes[ `backgroundPosition${ suffix }` ] = {
					type: 'string',
				};
			}

			// Background Position X Unit.
			if ( ! blockSettings.attributes[ `backgroundPositionXUnit${ suffix }` ] ) {
				blockSettings.attributes[ `backgroundPositionXUnit${ suffix }` ] = {
					type: 'string',
				};
			}

			// Background Position X Val.
			if ( ! blockSettings.attributes[ `backgroundPositionXVal${ suffix }` ] ) {
				blockSettings.attributes[ `backgroundPositionXVal${ suffix }` ] = {
					type: 'number',
				};
			}

			// Background Position Y Unit.
			if ( ! blockSettings.attributes[ `backgroundPositionYUnit${ suffix }` ] ) {
				blockSettings.attributes[ `backgroundPositionYUnit${ suffix }` ] = {
					type: 'string',
				};
			}

			// Background Position Y Val.
			if ( ! blockSettings.attributes[ `backgroundPositionYVal${ suffix }` ] ) {
				blockSettings.attributes[ `backgroundPositionYVal${ suffix }` ] = {
					type: 'number',
				};
			}

			// Background Attachment.
			if ( ! blockSettings.attributes[ `backgroundAttachment${ suffix }` ] ) {
				blockSettings.attributes[ `backgroundAttachment${ suffix }` ] = {
					type: 'string',
				};
			}

			// Background Repeat.
			if ( ! blockSettings.attributes[ `backgroundRepeat${ suffix }` ] ) {
				blockSettings.attributes[ `backgroundRepeat${ suffix }` ] = {
					type: 'string',
				};
			}

			// Background Size Unit.
			if ( ! blockSettings.attributes[ `backgroundSizeUnit${ suffix }` ] ) {
				blockSettings.attributes[ `backgroundSizeUnit${ suffix }` ] = {
					type: 'string',
				};
			}

			// Background Size Val.
			if ( ! blockSettings.attributes[ `backgroundSizeVal${ suffix }` ] ) {
				blockSettings.attributes[ `backgroundSizeVal${ suffix }` ] = {
					type: 'number',
				};
			}
		} );
	}

	return blockSettings;
}

/**
 * Override the default edit UI to include a new block inspector control for
 * assigning the custom background image if needed.
 *
 * @param {function|Component} BlockEdit Original component.
 *
 * @return {string} Wrapped component.
 */
const withInspectorControl = createHigherOrderComponent( ( OriginalComponent ) => {
	return class CanvasBackgroundImageWrapper extends Component {
		constructor() {
			super( ...arguments );

			this.getCurrentBackgroundImageStyles = this.getCurrentBackgroundImageStyles.bind( this );
			this.getCurrentValue = this.getCurrentValue.bind( this );
		}

		/**
		 * Get current background image for preview purposes.
		 *
		 * @returns {String} background image.
		 */
		getCurrentBackgroundImageStyles() {
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
					let backgroundImageStyles = '';

					if ( name && name !== 'desktop' ) {
						suffix = '_' + name;
					}


					if ( typeof attributes[ 'backgroundImage' + suffix ] !== 'undefined' && attributes[ 'backgroundImage' + suffix ].id ) {
						backgroundImageStyles += `background-image: url("${ attributes[ 'backgroundImage' + suffix ].url }"); `;
					}

					if ( typeof attributes[ 'backgroundImage'] !== 'undefined' && attributes[ 'backgroundImage'].id ) {
						if ( typeof attributes[ 'backgroundPosition' + suffix ] !== 'undefined' ) {
							if ( 'custom' === attributes[ 'backgroundPosition' + suffix ] ) {
								let unitX = typeof attributes[ 'backgroundPositionXUnit' + suffix ] !== 'undefined' ? attributes[ 'backgroundPositionXUnit' + suffix ] : 'px';
								let valX  = typeof attributes[ 'backgroundPositionXVal' + suffix ] !== 'undefined' ? attributes[ 'backgroundPositionXVal' + suffix ] : '0';
								let unitY = typeof attributes[ 'backgroundPositionYUnit' + suffix ] !== 'undefined' ? attributes[ 'backgroundPositionYUnit' + suffix ] : 'px';
								let valY  = typeof attributes[ 'backgroundPositionYVal' + suffix ] !== 'undefined' ? attributes[ 'backgroundPositionYVal' + suffix ] : '0';

								backgroundImageStyles += `background-position: ${ valX }${ unitX } ${ valY }${ unitY }; `;
							} else {
								backgroundImageStyles += `background-position: ${ attributes[ 'backgroundPosition' + suffix ] }; `;
							}
						}

						if ( typeof attributes[ 'backgroundAttachment' + suffix ] !== 'undefined' ) {
							backgroundImageStyles += `background-attachment: ${ attributes[ 'backgroundAttachment' + suffix ] }; `;
						}

						if ( typeof attributes[ 'backgroundRepeat' + suffix ] !== 'undefined' ) {
							backgroundImageStyles += `background-repeat: ${ attributes[ 'backgroundRepeat' + suffix ] }; `;
						}

						if ( typeof attributes[ 'backgroundSize' + suffix ] !== 'undefined' ) {
							backgroundImageStyles += `background-size: ${ attributes[ 'backgroundSize' + suffix ] }; `;

							if ( 'custom' === attributes[ 'backgroundSize' + suffix ] ) {
								if ( typeof attributes[ 'backgroundSizeVal' + suffix ] !== 'undefined' ) {
									let unit = typeof attributes[ 'backgroundSizeUnit' + suffix ] !== 'undefined' ? attributes[ 'backgroundSizeUnit' + suffix ] : '%';

									backgroundImageStyles += `background-size: ${ attributes[ 'backgroundSizeVal' + suffix ] }${ unit } auto; `;
								}
							} else {
								backgroundImageStyles += `background-size: ${ attributes[ 'backgroundSize' + suffix ] }; `;
							}
						}
					}

					if ( backgroundImageStyles ) {
						backgroundImageStyles = `.${ canvasClassName } { ${ backgroundImageStyles } }`;

						if ( suffix ) {
							backgroundImageStyles = `@media (max-width: ${ canvasBreakpoints[ name ].width }px) { ${ backgroundImageStyles } } `;
						}

						customStyles += backgroundImageStyles;
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
			if ( ! hasBlockSupport( this.props.name, 'canvasBackgroundImage', false ) ) {
				return <OriginalComponent { ...this.props } />;
			}

			const {
				attributes,
				setAttributes,
			} = this.props;

			const {
				canvasClassName,
			} = attributes;

			dynamicStylesBYkey( 'background-image', canvasClassName, this.getCurrentBackgroundImageStyles() );

			return (
				<Fragment>
					<OriginalComponent
						{ ...this.props }
						{ ...this.state }
						setState={ this.setState }
					/>
					<InspectorControls>
						<PanelBody
							title={ __( 'Background Image' ) }
							initialOpen={ false }
						>
							<ComponentResponsiveWrapper>
								{ ( { responsiveSuffix, ComponentResponsiveDropdown } ) => {

									var controlName = 'backgroundImage' + responsiveSuffix;

									// Get values of control.
									var id  = controlName in attributes ? attributes[controlName].id : 0;
									var url = controlName in attributes ? attributes[controlName].url : '';

									return (
										<Fragment>
											<h2>
												{ __( 'Image' ) }
												<ComponentResponsiveDropdown />
											</h2>

											<Fragment>
												<BaseControl
												>
													{ ! id ? (
														<MediaPlaceholder
															icon="format-image"
															labels={ {
																title: __( 'Image' ),
																name: __( 'image' ),
															} }
															onSelect={ ( image ) => {
																setAttributes( { [ controlName ] : {
																	id: image.id,
																	url: image.url,
																} } );
															} }
															accept="image/*"
															allowedTypes={ [ 'image' ] }
															disableMaxUploadErrorMessages
															onError={ ( e ) => {
																console.log( e );
															} }
														/>
													) : '' }
													{ url ? (
														<Fragment>
															<DropZone
																onFilesDrop={ ( files ) => {
																	mediaUpload( {
																		allowedTypes: [ 'image' ],
																		filesList: files,
																		onFileChange: ( image ) => {
																			setAttributes( { [ controlName ]: {
																				id: image.id,
																				url: image.url,
																			} } );
																		},
																		onError( e ) {
																			console.log( e );
																		},
																	} );
																} }
															/>
															{ url ? (
																<img src={ url } />
															) : '' }
															<div>
																<Button
																	isDefault={ true }
																	onClick={ () => {
																		setAttributes( { [ controlName ]: {
																			id: 0,
																			url: '',
																		} } );
																	} }
																>
																	{ __( 'Remove Image' ) }
																</Button>
															</div>
														</Fragment>
													) : '' }
												</BaseControl>
											</Fragment>
										</Fragment>
									);
								} }
							</ComponentResponsiveWrapper>


							{/* Additional Controls */}
							{ 'backgroundImage' in attributes && typeof attributes['backgroundImage'] === 'object' && attributes['backgroundImage'].id ? (
								<div>
									<ComponentResponsiveWrapper>
										{ ( { responsiveSuffix, ComponentResponsiveDropdown } ) => {

											var controlName = 'backgroundPosition' + responsiveSuffix;

											return (
												<Fragment>
													<h2>
														{ __( 'Position' ) }
														<ComponentResponsiveDropdown />
													</h2>

													<SelectControl
														label={ false }
														help={ false }
														multiple={ false }
														value={ controlName in attributes ? attributes[controlName] : '' }
														options={ [
															{ label: 'Default', value: '' },
															{ label: 'Center Center', value: 'center center' },
															{ label: 'Center Left', value: 'center left' },
															{ label: 'Center Right', value: 'center right' },
															{ label: 'Top Center', value: 'top center' },
															{ label: 'Top Left', value: 'center left' },
															{ label: 'Top Right', value: 'top right' },
															{ label: 'Bottom Center', value: 'bottom center' },
															{ label: 'Bottom Left', value: 'bottom left' },
															{ label: 'Bottom Left', value: 'bottom right' },
															{ label: 'Custom', value: 'custom' },
														] }
														onChange={ ( val ) => {
															setAttributes( { [ controlName ]: val });
														} }
													/>
												</Fragment>
											);
										} }
									</ComponentResponsiveWrapper>

									<ComponentResponsiveWrapper>
										{ ( { responsiveSuffix, ComponentResponsiveDropdown } ) => {

											var controlName     = 'backgroundPosition' + responsiveSuffix;
											var unitControlName = 'backgroundPositionXUnit' + responsiveSuffix;
											var valControlName  = 'backgroundPositionXVal' + responsiveSuffix;

											var units = [ 'px', 'em', '%' ];
											if ( 'custom' === attributes[controlName] ) {
												return (
													<Fragment>
														<h2 class="cnvs-component-background-image-units">
															{ __( 'X Position' ) }
															<ComponentResponsiveDropdown />

															<div className="cnvs-component-background-image-units-controls">
																{ units.map( ( unit ) => {
																	var unitVal = unitControlName in attributes && attributes[unitControlName] ? attributes[unitControlName] : 'px';
																	return (
																		<Button
																			isPrimary={ ( unitVal === unit ) ? true : false }
																			onClick={ () => {
																				setAttributes( { [ unitControlName ]: unit });
																			} }
																		>
																			{ unit }
																		</Button>
																	);
																} ) }
															</div>
														</h2>

														<RangeControl
															label={ false }
															help={ false }
															min={ -2000 }
															max={ 2000 }
															step={ 1 }
															value={ valControlName in attributes ? attributes[valControlName] : 0 }
															onChange={ ( val ) => {
																setAttributes( { [ valControlName ]: val });
															} }
														/>
													</Fragment>
												);
											}
										} }
									</ComponentResponsiveWrapper>

									<ComponentResponsiveWrapper>
										{ ( { responsiveSuffix, ComponentResponsiveDropdown } ) => {

											var controlName     = 'backgroundPosition' + responsiveSuffix;
											var unitControlName = 'backgroundPositionYUnit' + responsiveSuffix;
											var valControlName  = 'backgroundPositionYVal' + responsiveSuffix;

											var units = [ 'px', 'em', '%' ];

											if ( 'custom' === attributes[controlName] ) {
												return (
													<Fragment>
														<h2 class="cnvs-component-background-image-units">
															{ __( 'Y Position' ) }
															<ComponentResponsiveDropdown />

															<div className="cnvs-component-background-image-units-controls">
																{ units.map( ( unit ) => {
																	var unitVal = unitControlName in attributes && attributes[unitControlName] ? attributes[unitControlName] : 'px';
																	return (
																		<Button
																			isPrimary={ ( unitVal === unit ) ? true : false }
																			onClick={ () => {
																				setAttributes( { [ unitControlName ]: unit });
																			} }
																		>
																			{ unit }
																		</Button>
																	);
																} ) }
															</div>
														</h2>

														<RangeControl
															label={ false }
															help={ false }
															min={ -2000 }
															max={ 2000 }
															step={ 1 }
															value={ valControlName in attributes ? attributes[valControlName] : 0 }
															onChange={ ( val ) => {
																setAttributes( { [ valControlName ]: val });
															} }
														/>
													</Fragment>
												);
											}
										} }
									</ComponentResponsiveWrapper>

									<ComponentResponsiveWrapper>
										{ ( { responsiveSuffix, ComponentResponsiveDropdown } ) => {

											var controlName = 'backgroundAttachment' + responsiveSuffix;

											return (
												<Fragment>
													<h2>
														{ __( 'Attachment' ) }
														<ComponentResponsiveDropdown />
													</h2>

													<SelectControl
														label={ false }
														help={ false }
														multiple={ false }
														value={ controlName in attributes ? attributes[controlName] : '' }
														options={ [
															{ label: 'Default', value: '' },
															{ label: 'Scroll', value: 'scroll' },
															{ label: 'Fixed', value: 'fixed' },
														] }
														onChange={ ( val ) => {
															setAttributes( { [ controlName ]: val });
														} }
													/>
												</Fragment>
											);
										} }
									</ComponentResponsiveWrapper>

									<ComponentResponsiveWrapper>
										{ ( { responsiveSuffix, ComponentResponsiveDropdown } ) => {

											var controlName = 'backgroundRepeat' + responsiveSuffix;

											return (
												<Fragment>
													<h2>
														{ __( 'Repeat' ) }
														<ComponentResponsiveDropdown />
													</h2>

													<SelectControl
														label={ false }
														help={ false }
														multiple={ false }
														value={ controlName in attributes ? attributes[controlName] : '' }
														options={ [
															{ label: 'Default', value: '' },
															{ label: 'No-repeat', value: 'no-repeat' },
															{ label: 'Repeat', value: 'repeat' },
															{ label: 'Repeat-x', value: 'repeat-x' },
															{ label: 'Repeat-y', value: 'repeat-y' },
														] }
														onChange={ ( val ) => {
															setAttributes( { [ controlName ]: val });
														} }
													/>
												</Fragment>
											);
										} }
									</ComponentResponsiveWrapper>

									<ComponentResponsiveWrapper>
										{ ( { responsiveSuffix, ComponentResponsiveDropdown } ) => {

											var controlName = 'backgroundSize' + responsiveSuffix;

											return (
												<Fragment>
													<h2>
														{ __( 'Size' ) }
														<ComponentResponsiveDropdown />
													</h2>

													<SelectControl
														label={ false }
														help={ false }
														multiple={ false }
														value={ controlName in attributes ? attributes[controlName] : '' }
														options={ [
															{ label: 'Default', value: '' },
															{ label: 'Auto', value: 'auto' },
															{ label: 'Cover', value: 'cover' },
															{ label: 'Contain', value: 'contain' },
															{ label: 'Custom', value: 'custom' },
														] }
														onChange={ ( val ) => {
															setAttributes( { [ controlName ]: val });
														} }
													/>
												</Fragment>
											);
										} }
									</ComponentResponsiveWrapper>

									<ComponentResponsiveWrapper>
										{ ( { responsiveSuffix, ComponentResponsiveDropdown } ) => {

											var controlName     = 'backgroundSize' + responsiveSuffix;
											var unitControlName = 'backgroundSizeUnit' + responsiveSuffix;
											var valControlName  = 'backgroundSizeVal' + responsiveSuffix;

											var units = [ 'px', 'em', '%' ];

											if ( 'custom' === attributes[controlName] ) {
												return (
													<Fragment>
														<h2 class="cnvs-component-background-image-units">
															{ __( 'Width' ) }
															<ComponentResponsiveDropdown />

															<div className="cnvs-component-background-image-units-controls">
																{ units.map( ( unit ) => {
																	var unitVal = unitControlName in attributes && attributes[unitControlName] ? attributes[unitControlName] : '%';
																	return (
																		<Button
																			isPrimary={ ( unitVal === unit ) ? true : false }
																			onClick={ () => {
																				setAttributes( { [ unitControlName ]: unit });
																			} }
																		>
																			{ unit }
																		</Button>
																	);
																} ) }
															</div>
														</h2>

														<RangeControl
															label={ false }
															help={ false }
															min={ 0 }
															max={ 2000 }
															step={ 1 }
															value={ valControlName in attributes ? attributes[valControlName] : '' }
															onChange={ ( val ) => {
																setAttributes( { [ valControlName ]: val });
															} }
														/>
													</Fragment>
												);
											}
										} }
									</ComponentResponsiveWrapper>
								</div>
							) : '' }
						</PanelBody>
					</InspectorControls>
				</Fragment>
			);
		}
	};
}, 'withInspectorControl' );

// Init filters.
addFilter( 'blocks.registerBlockType', 'canvas/background-image/additional-attributes', addAttribute );
addFilter( 'editor.BlockEdit', 'canvas/background-image/additional-attributes', withInspectorControl );
