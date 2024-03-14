/**
 * External dependencies
 */
import ReactSelect from 'react-select';

/**
 * Internal dependencies
 */
import './style.scss';
import SchemeWrapper from '../scheme-wrapper';
import ResponsiveWrapper from '../responsive-wrapper';
import DimensionControl from '../dimension-control';
import CategoriesSelectorControl from '../categories-selector-control';
import TagsSelectorControl from '../tags-selector-control';
import PostsSelectorControl from '../posts-selector-control';
import QueryControl from '../query-control';
import isFieldVisible from '../../utils/is-field-visible';

/**
 * WordPress dependencies
 */
const {
	__,
	sprintf,
} = wp.i18n;

const {
	Component,
	Fragment,
	RawHTML,
} = wp.element;

const {
	BaseControl,
	ToggleControl,
	TextControl,
	TextareaControl,
	RangeControl,
	SelectControl,
	PanelBody,
	Notice,
	DropZone,
	Button,
	Toolbar,
} = wp.components;

const {
	ColorPalette,
	MediaPlaceholder,
	MediaUpload,
	mediaUpload,
} = wp.blockEditor;

const {
	applyFilters,
} = wp.hooks;

/**
 * Component
 */
export default class ComponentFieldsRender extends Component {
	constructor() {
		super( ...arguments );

		this.getAllFieldsSections = this.getAllFieldsSections.bind( this );
		this.getFieldValue = this.getFieldValue.bind( this );
		this.updateFieldValue = this.updateFieldValue.bind( this );
		this.renderControl = this.renderControl.bind( this );
	}

	/**
	 * Get all available sections.
	 *
	 * @returns {Object} sections.
	 */
	getAllFieldsSections() {
		const {
			fields = [],
		} = this.props;

		const sections = {
			...{ '': '' },
			...this.props.sections,
		};

		// check all fields and add section if not defined.
		fields.forEach( ( field ) => {
			if ( field.section && typeof sections[ field.section ] === 'undefined' ) {
				sections[ field.section ] = field.section;
			}
		} );

		return sections;
	}

	/**
	 * Get current field value. If value doesn't exist, use default value.
	 *
	 * @param {Object} fieldData field data.
	 * @param {String} suffix attribute name suffix.
	 *
	 * @returns {Mixed} field value.
	 */
	getFieldValue( fieldData, suffix = '' ) {
		const {
			attributes = {},
		} = this.props;

		if ( typeof attributes[ fieldData.key + suffix ] !== 'undefined' ) {
			return attributes[ fieldData.key + suffix ];
		} else if ( typeof fieldData[ 'default' + suffix ] !== 'undefined' ) {
			return fieldData[ 'default' + suffix ];
		}

		return null;
	}

	/**
	 * Update current field value.
	 *
	 * @param {Object} fieldData field data.
	 * @param {Mixed} val field value.
	 * @param {String} suffix attribute name suffix.
	 */
	updateFieldValue( fieldData, val, suffix = '' ) {
		const {
			onChange,
		} = this.props;

		onChange( fieldData.key + suffix, val );
	}

	/**
	 * Render control
	 *
	 * @param {Object} fieldData field data.
	 *
	 * @returns {JSX}
	 */
	renderControl( fieldData ) {
		const renderName = `renderControl${ fieldData.type.replace( /(\b\w)|(-.)/g, ( x ) => ( x[1] || x[0] ).toUpperCase() ) }`;

		// check if render method exist.
		if (this[renderName]) {

			return (
				<SchemeWrapper>
					{({ schemeSuffix, ComponentSchemeDropdown }) => {
						return (
							<ResponsiveWrapper>
								{({ responsiveSuffix, ComponentResponsiveDropdown }) => {
									let fieldSuffix = '';
									let newFieldData = { ...fieldData };

									// Scheme dropdown.
									if (canvasSchemes && ('color' === newFieldData.type)) {
										fieldSuffix += schemeSuffix;
										newFieldData = {
											...newFieldData,
											label: (
												<Fragment>
													{newFieldData.label || ''}
													<ComponentSchemeDropdown />
												</Fragment>
											)
										};
									}

									// Responsive dropdown.
									if (newFieldData.responsive) {
										fieldSuffix += responsiveSuffix;
										newFieldData = {
											...newFieldData,
											label: (
												<Fragment>
													{newFieldData.label || ''}
													<ComponentResponsiveDropdown />
												</Fragment>
											)
										};
									}

									return this[renderName](
										newFieldData,
										this.getFieldValue(newFieldData, fieldSuffix),
										(val) => {
											this.updateFieldValue(newFieldData, val, fieldSuffix);
										}
									);
								}}
							</ResponsiveWrapper>
						)
					}}
				</SchemeWrapper>
			);
		}

		// render method does not exist.
		return (
			<Notice status="warning" isDismissible={ false }>
				{ sprintf( __( 'Unfortunately, `%s` method doesn\'t exist.'  ), renderName ) }
			</Notice>
		);
	}

	/**
	 * Render Separator control
	 *
	 * @param {Object} fieldData field data.
	 * @param {String} val field value.
	 * @param {Object} onChange field on change callback.
	 *
	 * @returns {JSX}
	 */
	renderControlSeparator( fieldData, val, onChange ) {
		return (
			<hr />
		);
	}

	/**
	 * Render Text control
	 *
	 * @param {Object} fieldData field data.
	 * @param {String} val field value.
	 * @param {Object} onChange field on change callback.
	 *
	 * @returns {JSX}
	 */
	renderControlText( fieldData, val, onChange ) {
		return (
			<TextControl
				label={ fieldData.label || false }
				help={ <RawHTML>{ fieldData.help || '' }</RawHTML> }
				value={ val }
				onChange={ onChange }
			/>
		);
	}

	/**
	 * Render Textarea control
	 *
	 * @param {Object} fieldData field data.
	 * @param {String} val field value.
	 * @param {Object} onChange field on change callback.
	 *
	 * @returns {JSX}
	 */
	renderControlTextarea( fieldData, val, onChange ) {
		return (
			<TextareaControl
				label={ fieldData.label || false }
				help={ fieldData.help || false }
				value={ val }
				onChange={ onChange }
			/>
		);
	}

	/**
	 * Render Toggle control
	 *
	 * @param {Object} fieldData field data.
	 * @param {Boolean} val field value.
	 * @param {Object} onChange field on change callback.
	 *
	 * @returns {JSX}
	 */
	renderControlToggle( fieldData, val, onChange ) {
		return (
			<ToggleControl
				label={ fieldData.label || false }
				help={ fieldData.help || false }
				checked={ !! val }
				onChange={ onChange }
			/>
		);
	}

	/**
	 * Render Toggle List control
	 *
	 * @param {Object} fieldData field data.
	 * @param {Object} val field value.
	 * @param {Object} onChange field on change callback.
	 *
	 * @returns {JSX}
	 */
	renderControlToggleList( fieldData, val, onChange ) {
		return (
			<BaseControl
				label={ fieldData.label || false }
				help={ fieldData.help || false }
			>
				<p />
				{ Object.keys( val ).map( ( valName ) => {
					return (
						<ToggleControl
							key={ `toggle-list-control-${ fieldData.key }-${ valName }` }
							label={ fieldData.choices[ valName ] || false }
							checked={ !! val[ valName ] }
							onChange={ () => {
								const result = Object.assign( {}, val );
								result[ valName ] = ! result[ valName ];
								onChange( result );
							} }
						/>
					);
				} ) }
			</BaseControl>
		);
	}

	/**
	 * Render Dimension control
	 *
	 * @param {Object} fieldData field data.
	 * @param {String} val field value.
	 * @param {Object} onChange field on change callback.
	 *
	 * @returns {JSX}
	 */
	renderControlDimension( fieldData, val, onChange ) {
		return (
			<DimensionControl
				label={ fieldData.label || false }
				help={ fieldData.help || false }
				value={ val }
				onChange={ onChange }
			/>
		);
	}

	/**
	 * Render Number control
	 *
	 * @param {Object} fieldData field data.
	 * @param {Number} val field value.
	 * @param {Object} onChange field on change callback.
	 *
	 * @returns {JSX}
	 */
	renderControlNumber( fieldData, val, onChange ) {
		return (
			<RangeControl
				label={ fieldData.label || false }
				help={ fieldData.help || false }
				min={ fieldData.min || false }
				max={ fieldData.max || false }
				step={ fieldData.step || 1 }
				value={ val }
				onChange={ onChange }
			/>
		);
	}

	/**
	 * Render Icon Buttons control
	 *
	 * @param {Object} fieldData field data.
	 * @param {String} val field value.
	 * @param {Object} onChange field on change callback.
	 *
	 * @returns {JSX}
	 */
	renderControlIconButtons( fieldData, val, onChange ) {
		return (
			<BaseControl
				label={ fieldData.label || false }
				help={ fieldData.help || false }
			>
				<Toolbar
					className="cnvs-control-icon-buttons"
					controls={ Object.keys( fieldData.choices ).map( ( option ) => {
						return {
							icon: <RawHTML className="cnvs-control-icon-buttons-svg">{ fieldData.choices[ option ] }</RawHTML>,
							isActive: val === option,
							onClick() {
								onChange( val === option ? '' : option );
							},
						};
					} ) }
				/>
			</BaseControl>
		);
	}

	/**
	 * Render Select control
	 *
	 * @param {Object} fieldData field data.
	 * @param {String} val field value.
	 * @param {Object} onChange field on change callback.
	 *
	 * @returns {JSX}
	 */
	renderControlSelect( fieldData, val, onChange ) {
		const options = Object.keys( fieldData.choices ).map( ( option ) => {
			return {
				label: fieldData.choices[ option ],
				value: option,
			};
		} );

		return (
			<SelectControl
				label={ fieldData.label || false }
				help={ fieldData.help || false }
				multiple={ fieldData.multiple || false }
				value={ val }
				options={ options }
				onChange={ ( val ) => {
					onChange( val );
				} }
			/>
		);
	}

	/**
	 * Render React Select control
	 *
	 * @param {Object} fieldData field data.
	 * @param {String} val field value.
	 * @param {Object} onChange field on change callback.
	 *
	 * @returns {JSX}
	 */
	renderControlReactSelect( fieldData, val, onChange ) {
		const options = Object.keys( fieldData.choices ).map( ( option ) => {
			return {
				label: fieldData.choices[ option ],
				value: option,
			};
		} );

		return (
			<BaseControl
				label={ fieldData.label || false }
				help={ fieldData.help || false }
			>
				<ReactSelect
					isMulti={ fieldData.multiple || false }
					name="colors"
					options={ options }
					value={ ( () => {
						if ( fieldData.multiple ) {
							if ( ! Array.isArray( val ) ) {
								val = [];
							}

							// options
							const result = val.map( ( val ) => {
								return {
									value: val,
									label: fieldData.choices[ val ] || val,
								};
							} );

							return result;
						}
						return val;
					} )() }
					onChange={ ( val ) => {
						if ( fieldData.multiple ) {
							if ( val ) {
								const result = val.map( ( opt ) => {
									return opt.value;
								} );

								onChange( result );
							} else {
								onChange( [] );
							}
						} else {
							onChange( val );
						}
					} }
				/>
			</BaseControl>
		);
	}

	/**
	 * Render Color Picker control
	 *
	 * @param {Object} fieldData field data.
	 * @param {String} val field value.
	 * @param {Object} onChange field on change callback.
	 *
	 * @returns {JSX}
	 */
	renderControlColor( fieldData, val, onChange ) {
		let result = (
			<ColorPalette
				value={ val || '' }
				onChange={ onChange }
			/>
		);

		if ( fieldData.label || fieldsData.help ) {
			return (
				<BaseControl
					label={ fieldData.label || false }
					help={ fieldData.help || false }
				>
					{ result }
				</BaseControl>
			);
		}

		return result;
	}

	/**
	 * Render Image control
	 *
	 * @param {Object} fieldData field data.
	 * @param {Number} val field value.
	 * @param {Object} onChange field on change callback.
	 *
	 * @returns {JSX}
	 */
	renderControlImage( fieldData, val = {}, onChange ) {
		const {
			id = 0,
			url = '',
		} = val;

		return (
			<Fragment>
				<BaseControl
					label={ fieldData.label || false }
					help={ fieldData.help || false }
				>
					{ ! id ? (
						<MediaPlaceholder
							icon="format-image"
							labels={ {
								title: __( 'Image' ),
								name: __( 'image' ),
							} }
							onSelect={ ( image ) => {
								onChange( {
									id: image.id,
									url: image.url,
								} );
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
											onChange( {
												id: image.id,
												url: image.url,
											} );
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
										onChange( {
											id: 0,
											url: '',
										} );
									} }
								>
									{ __( 'Remove Image' ) }
								</Button>
							</div>
						</Fragment>
					) : '' }
				</BaseControl>
			</Fragment>
		);
	}

	/**
	 * Render Gallery control
	 *
	 * @param {Object} fieldData field data.
	 * @param {Number} val field value.
	 * @param {Object} onChange field on change callback.
	 *
	 * @returns {JSX}
	 */
	renderControlGallery( fieldData, val = [], onChange ) {
		const ALLOWED_MEDIA_TYPES = [ 'image' ];

		return (
			<BaseControl
				label={ fieldData.label || false }
				help={ fieldData.help || false }
			>
				{ ! val || ! val.length ? (
					<MediaPlaceholder
						icon="format-gallery"
						labels={ {
							title: fieldData.label,
							name: __( 'images' ),
						} }
						onSelect={ ( images ) => {
							const result = images.map( ( image ) => {
								return image.id;
							} );

							onChange( result );
						} }
						accept="image/*"
						allowedTypes={ ALLOWED_MEDIA_TYPES }
						disableMaxUploadErrorMessages
						multiple
						onError={ ( e ) => {
							// eslint-disable-next-line no-console
							console.log( e );
						} }
					/>
				) : '' }
				{ val && val.length ? (
					<MediaUpload
						onSelect={ ( images ) => {
							const result = images.map( ( image ) => {
								return image.id;
							} );

							onChange( result );
						} }
						allowedTypes={ ALLOWED_MEDIA_TYPES }
						multiple
						gallery
						value={ val }
						render={ ( { open } ) => (
							<div
								className="cnvs-gutenberg-component-gallery"
								onClick={ open }
								role="presentation"
							>
								<DropZone
									onFilesDrop={ ( files ) => {
										const currentImages = val || [];
										mediaUpload( {
											allowedTypes: ALLOWED_MEDIA_TYPES,
											filesList: files,
											onFileChange: ( images ) => {
												const result = images.map( ( image ) => {
													return image.id;
												} );

												onChange( currentImages.concat( result ) );
											},
											onError( e ) {
												// eslint-disable-next-line no-console
												console.log( e );
											},
										} );
									} }
								/>

								{ val ? (
								<div className="cnvs-gutenberg-component-gallery-list">
									{val.map(imageId => {
										return (
											<img src={ canvasLocalize.ajaxURL + '?action=cnvs_render_thumbnail&image_id=' + imageId } />
										)
									})}
								</div>
								) : '' }

								<div className="cnvs-gutenberg-component-gallery-button">
									<Button isDefault={ true }>{ __( 'Edit Gallery' ) }</Button>
								</div>
							</div>
						) }
					/>
				) : '' }
			</BaseControl>
		);
	}

	/**
	 * Render Categories Selector control
	 *
	 * @param {Object} fieldData field data.
	 * @param {String} val field value.
	 * @param {Object} onChange field on change callback.
	 *
	 * @returns {JSX}
	 */
	renderControlCategoriesSelector( fieldData, val, onChange ) {
		return (
			<CategoriesSelectorControl
				label={ fieldData.label || false }
				help={ fieldData.help || false }
				value={ val }
				onChange={ onChange }
			/>
		);
	}

	/**
	 * Render Tags Selector control
	 *
	 * @param {Object} fieldData field data.
	 * @param {String} val field value.
	 * @param {Object} onChange field on change callback.
	 *
	 * @returns {JSX}
	 */
	renderControlTagsSelector( fieldData, val, onChange ) {
		return (
			<TagsSelectorControl
				label={ fieldData.label || false }
				help={ fieldData.help || false }
				value={ val }
				onChange={ onChange }
			/>
		);
	}

	/**
	 * Render Posts Selector control
	 *
	 * @param {Object} fieldData field data.
	 * @param {String} val field value.
	 * @param {Object} onChange field on change callback.
	 *
	 * @returns {JSX}
	 */
	renderControlPostsSelector( fieldData, val, onChange ) {
		return (
			<PostsSelectorControl
				label={ fieldData.label || false }
				help={ fieldData.help || false }
				value={ val }
				onChange={ onChange }
			/>
		);
	}

	/**
	 * Render Query control
	 *
	 * @param {Object} fieldData field data.
	 * @param {String} val field value.
	 * @param {Object} onChange field on change callback.
	 *
	 * @returns {JSX}
	 */
	renderControlQuery( fieldData, val, onChange ) {
		return (
			<QueryControl
				label={ fieldData.label || false }
				help={ fieldData.help || false }
				value={ val }
				onChange={ onChange }
			/>
		);
	}

	render() {
		const {
			fields = [],
			attributes,
		} = this.props;

		const sections = this.getAllFieldsSections();

		return (
			<Fragment>
				{ Object.keys( sections ).map( ( sectionName ) => {

					const sectionTitle = sections[ sectionName ].title;
					const initialOpen  = sections[ sectionName ].open || ( sectionTitle ? false : true );

					if ( ! fields || ! fields.length ) {
						return '';
					}

					const sectionFields = fields
						.filter( ( fieldData ) => {
							if ( ! fieldData || ! fieldData.type ) {
								return false;
							}

							// prevent invisible fields, that used only for registering block attributes.
							if ( 'type-string' === fieldData.type || 'type-number' === fieldData.type || 'type-boolean' === fieldData.type || 'type-array' === fieldData.type ) {
								return false;
							}

							// limit fields for current section only.
							if ( sectionName && fieldData.section !== sectionName ) {
								return false;
							} else if ( ! sectionName && fieldData.section ) {
								return false;
							}

							// check active_callback
							return isFieldVisible( fieldData, attributes, fields );
						})
						.map( ( fieldData, i ) => {
							let fieldKey = `field-${ fieldData.type }-${ i }`;

							return (
								applyFilters( 'canvas.component.fieldsRender.singleField', (
									<Fragment key={ fieldKey }>
										{ this.renderControl( fieldData ) }
									</Fragment>
								), {
									fieldData,
									props: this.props,
								} )
							);
						} );

					if ( ! sectionFields || ! sectionFields.length ) {
						return '';
					}

					return (
						<PanelBody
							key={ `section-${ sectionName }` }
							title={ sectionTitle }
							initialOpen={ initialOpen }
						>
							{ applyFilters( 'canvas.component.fieldsRender', sectionFields, {
								sectionName,
								sectionTitle,
								props: this.props,
							} ) }
						</PanelBody>
					);
				} ) }
			</Fragment>
		);
	}
}
