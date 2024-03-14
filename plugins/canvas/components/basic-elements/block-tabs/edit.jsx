/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

const {
	Component,
	Fragment,
} = wp.element;

const {
	PanelBody,
	RangeControl,
} = wp.components;

const {
	InspectorControls,
	InnerBlocks,
	RichText,
} = wp.blockEditor;

/**
 * Component
 */
export default class TabsBlockEdit extends Component {
	constructor() {
		super( ...arguments );

		this.state = {
			// fix for WP 5.2
			// styles control generates error
			showInnerBlocks: !! this.props.clientId,
		};

		this.getLayoutTemplate = this.getLayoutTemplate.bind( this );
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
			tabsData,
		} = attributes;

		const result = [];

		for ( let k = 0; k < tabsData.length; k++ ) {
			result.push( [
				'canvas/tab',
				{},
			] );
		}

		return result;
	}

	render() {
		const {
			setAttributes,
		} = this.props;

		let {
			className,
		} = this.props;

		const {
			tabActive,
			tabsData,
			tabsPosition,
			canvasClassName,
		} = this.props.attributes;

		className = classnames(
			'cnvs-block-tabs',
			`cnvs-block-tabs-${ tabsData.length }`,
			'vertical' === tabsPosition ? `cnvs-block-tabs-${ tabsPosition }` : '',
			canvasClassName,
			className,
		);

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody>
						<RangeControl
							label={ __( 'Tabs' ) }
							value={ tabsData.length }
							min={ 1 }
							max={ 20 }
							onChange={ ( val ) => {
								const newTabsData = [];

								for ( let k = 0; k < val; k += 1 ) {
									if ( tabsData[ k ] ) {
										newTabsData.push( tabsData[ k ] );
									} else {
										newTabsData.push( `Tab ${ k + 1 }` );
									}
								}

								setAttributes( { tabsData: newTabsData } );
							} }
						/>
					</PanelBody>
				</InspectorControls>
				<div className={ className }>
					<div className="cnvs-block-tabs-buttons">
						{
							tabsData.map( ( title, i ) => {
								const selected = tabActive === i;

								return (
									<div
										className={
											classnames(
												'cnvs-block-tabs-button',
												{
													'cnvs-block-tabs-button-active': selected,
												}
											)
										}
										key={ `tab_button_${ i }` }
										onClick={ () => setAttributes( { tabActive: i } ) }
									>
										<RichText
											tagName="span"
											placeholder={ __( 'Tab label' ) }
											onChange={ ( value ) => {
												if ( tabsData[ i ] ) {
													const newTabsData = tabsData.map( ( oldTabData, newIndex ) => {
														if ( i === newIndex ) {
															return value;
														}

														return oldTabData;
													} );

													setAttributes( {
														tabsData: newTabsData,
													} );
												}
											} }
											keepPlaceholderOnFocus
										/>
									</div>
								);
							} )
						}
					</div>
					<div className="cnvs-block-tabs-content">
						{ this.state.showInnerBlocks ? (
							<InnerBlocks
								template={ this.getLayoutTemplate() }
								templateLock="all"
								allowedBlocks={ [ 'canvas/tab' ] }
							/>
						) : __( 'Tab content' ) }
					</div>
				</div>
				<style>
					{ `
						[data-block="${ this.props.clientId }"] > .canvas-component-custom-blocks > .cnvs-block-tabs > .cnvs-block-tabs-content > .block-editor-inner-blocks > .block-editor-block-list__layout > div {
							display: none;
						}
						[data-block="${ this.props.clientId }"] > .canvas-component-custom-blocks > .cnvs-block-tabs > .cnvs-block-tabs-content > .block-editor-inner-blocks > .block-editor-block-list__layout > :nth-child(${ tabActive + 1 }) {
							display: block;
						}
					` }
				</style>
			</Fragment>
		);
	}
}
