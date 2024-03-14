/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * Internal dependencies
 */
import Icon from './icon';
import ImageSelector from '../../../gutenberg/components/image-selector';
import iconLayouts from './icon-layouts';

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

const {
	Component,
	Fragment,
} = wp.element;

const {
	BaseControl,
	PanelBody,
	Placeholder,
	RangeControl,
	SelectControl,
	ToggleControl,
	Notice,
} = wp.components;

const {
	InspectorControls,
	InnerBlocks,
} = wp.blockEditor;

const { BlockControls, BlockAlignmentToolbar } = wp.blockEditor;






/**
 * Component
 */
export default class SectionBlockEdit extends Component {
	constructor() {
		super( ...arguments );

		this.getLayoutTemplate = this.getLayoutTemplate.bind( this );
		this.getLayoutSelector = this.getLayoutSelector.bind( this );
	}

	/**
	 * Returns the template configuration for a given section layout.
	 *
	 * @return {Object[]} Layout configuration.
	 */
	getLayoutTemplate() {
		const {
			attributes,
		} = this.props;

		let {
			layout,
		} = attributes;

		const result = [];

		switch (layout) {
			case 'full':
				result.push( [ 'canvas/section-content' ] );
				break;
			case 'with-sidebar':
				result.push( [ 'canvas/section-content' ] );
				result.push( [ 'canvas/section-sidebar' ] );
				break;
		}

		return result;
	}

	/**
	 * Returns layout selector.
	 *
	 * @return {JSX} ImageSelector.
	 */
	getLayoutSelector() {
		const {
			setAttributes,
			attributes,
		} = this.props;

		const {
			layout,
			sidebarPosition,
		} = attributes;

		let val = '';
		switch( layout ) {
			case 'full':
				val = 'full';
				break;
			case 'with-sidebar':
				val = `with-sidebar${ 'left' === sidebarPosition ? '-left' : '' }`;
				break;
		}

		return (
			<ImageSelector
				value={ val }
				onChange={ ( val ) => {
					// confirmation to remove sidebar.
					if ( val === 'full' && ( 'with-sidebar' === layout || 'with-sidebar-left' === layout ) ) {
						if ( ! window.confirm( __( 'When switching from a Sidebar layout to the Fullwidth layout all sidebar content will be removed. Are you sure you would like to switch the layout?' ) ) ) {
							return;
						}
					}

					switch( val ) {
						case 'full':
							setAttributes( {
								layout: 'full',
							} );
							break;
						case 'with-sidebar':
							setAttributes( {
								layout: 'with-sidebar',
								sidebarPosition: 'right',
							} );
							break;
						case 'with-sidebar-left':
							setAttributes( {
								layout: 'with-sidebar',
								sidebarPosition: 'left',
							} );
							break;
					}
				} }
				items={
					[
						{
							content: iconLayouts.full,
							value: 'full',
							label: __( 'Fullwidth' ),
						}, {
							content: iconLayouts['with-sidebar'],
							value: 'with-sidebar',
							label: __( 'Right Sidebar' ),
						}, {
							content: iconLayouts['with-sidebar-left'],
							value: 'with-sidebar-left',
							label: __( 'Left Sidebar' ),
						},
					]
				}
			/>
		);
	}

	render() {
		const {
			setAttributes,
			attributes,
			location,
		} = this.props;

		let {
			className,
		} = this.props;

		const {
			layout,
			layoutAlign,
			contentWidth,
			sidebarSticky,
			sidebarStickyMethod,
			sidebarPosition,
			textColor,
			backgroundColor,
			canvasClassName,
		} = attributes;

		const pageTemplate = wp.data.select( 'core/editor' ).getEditedPostAttribute( 'template' );

		className = classnames(
			'cnvs-block-section',
			{
				[ `cnvs-block-section-fullwidth` ]: 'full' === layout,
				[ `cnvs-block-section-layout-align-${ layoutAlign }` ]: 'full' === layout && layoutAlign,
				[ `cnvs-block-section-sidebar-sticky-${ sidebarStickyMethod }` ]: 'with-sidebar' === layout && sidebarSticky,
				[ `cnvs-block-section-sidebar-position-${ sidebarPosition }` ]: 'with-sidebar' === layout && sidebarPosition,
				'cnvs-block-section-with-text-color': textColor,
				'cnvs-block-section-with-background-color': backgroundColor,
			},
			canvasClassName,
		);

		// Page template is Canvas Full Width.
		if ( 'template-canvas-fullwidth.php' !== pageTemplate ) {
			return (
				<Placeholder
					icon={ Icon }
					label={ __( 'Section' ) }
					className="cnvs-block-section-notice"
				>
					<Notice status="warning" isDismissible={ false }>
						{ __( 'To use this block, please select the page template - "Canvas Full Width".' ) }
					</Notice>
				</Placeholder>
			);
		}

		// Block is not in root.
		if ( 'root' !== location ) {
			return (
				<Placeholder
					icon={ Icon }
					label={ __( 'Section' ) }
					className="cnvs-block-section-notice"
				>
					<Notice status="warning" isDismissible={ false }>
						{ __( 'Sections are supported on root level only. You’ve added the section inside another block and layout will most likely break. Please add the section block as a parent block instead.' ) }
					</Notice>
				</Placeholder>
			);
		}

		// Layout selector.
		if ( ! layout ) {
			return (
				<Placeholder
					className="canvas-component-custom-layouts-placeholder"
					icon={ Icon }
					label={ __( 'Section' ) }
					instructions={ __( 'Select the section layout type.' ) }
				>
					{ this.getLayoutSelector() }
				</Placeholder>
			);
		}

		var sectionStyle = {};

		if ( ! canvasBSLocalize.disableSectionResponsive ) {
			sectionStyle = {
				maxWidth: ( contentWidth || canvasBSLocalize.sectionResponsiveMaxWidth ) + 'px',
			};
		}

		return (
			<Fragment>
				{ 'full' === layout ? (
				<BlockControls key="controls">
					<BlockAlignmentToolbar
						value={ layoutAlign }
						onChange={ ( val ) => {
								setAttributes( { layoutAlign: val } );

								window.dispatchEvent(new Event( 'resize' ) );
							}
						}
						controls={ [ 'full' ] }
					/>
				</BlockControls>
				) : '' }

				<InspectorControls>
					<PanelBody
						title={ __( 'Layout' ) }
					>
						<BaseControl>
							{ this.getLayoutSelector() }
						</BaseControl>

						{ ( ! canvasBSLocalize.disableSectionResponsive ) && ( ( 'full' === layout && ! layoutAlign ) || ( 'full' !== layout ) ) ? (
						<Fragment>
							<RangeControl
								label={ __( 'Content Width (px.)' ) }
								value={ contentWidth || canvasBSLocalize.sectionResponsiveMaxWidth }
								min={ 320 }
								max={ 2560 }
								step={ 1 }
								onChange={ ( val ) => {
										setAttributes( { contentWidth: val } );

										window.dispatchEvent(new Event( 'resize' ) );
									}
								}
							/>
						</Fragment>
						) : '' }

						{ 'with-sidebar' === layout ? (
							<Fragment>
								<ToggleControl
									label={ __( 'Sticky Sidebar' ) }
									checked={ !! sidebarSticky }
									onChange={ () => { setAttributes( { sidebarSticky: ! sidebarSticky } ) } }
								/>
								{ sidebarSticky ? (
									<SelectControl
										label={ __( 'Sticky Method' ) }
										value={ sidebarStickyMethod }
										onChange={ ( val ) => { setAttributes( { sidebarStickyMethod: val } ) } }
										options={ [
											{
												label: __( 'Top Edge' ),
												value: 'top',
											}, {
												label: __( 'Bottom Edge' ),
												value: 'bottom',
											}, {
												label: __( 'Top Edge of Last Block' ),
												value: 'top-last-block',
											}
										] }
									/>
								) : '' }
							</Fragment>
						) : '' }
					</PanelBody>
				</InspectorControls>
				<div className={ className }>
					<div className="cnvs-block-section-inner" style={ sectionStyle }>
						<InnerBlocks
							template={ this.getLayoutTemplate() }
							templateLock="all"
							allowedBlocks={ [ 'canvas/section-content', 'canvas/section-sidebar' ] }
						/>
					</div>
				</div>
			</Fragment>
		);
	}
}
