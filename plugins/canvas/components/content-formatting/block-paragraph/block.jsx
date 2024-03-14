/**
 * Internal dependencies
 */
import {
	replaceClass,
	getActiveClass,
} from '../../../gutenberg/utils/classes-replacer';

/**
 * WordPress dependencies
 */
const {
	__,
} = wp.i18n;

const {
	registerBlockStyle,
} = wp.blocks;

const {
	Component,
	Fragment,
} = wp.element;

const {
	PanelBody,
	SelectControl,
} = wp.components;

const {
	InspectorControls,
} = wp.blockEditor;

const {
	addFilter,
} = wp.hooks;

const {
	createHigherOrderComponent,
} = wp.compose;

const {
	withDispatch,
} = wp.data;


registerBlockStyle( 'core/paragraph', {
	name: 'cnvs-paragraph-callout',
	label: __( 'Callout' ),
} );


/**
 * Override the default edit UI to include a new block inspector control for
 * assigning the custom styles if needed.
 *
 * @param {function|Component} BlockEdit Original component.
 *
 * @return {string} Wrapped component.
 */
const paragraphWithCanvasDropcap = createHigherOrderComponent( ( BlockEdit ) => {
	class NewEdit extends Component {
		constructor() {
			super( ...arguments );

			this.activeClasses = '';

			this.getDropcapStyle = this.getDropcapStyle.bind( this );
			this.updateDropcapStyle = this.updateDropcapStyle.bind( this );
		}

		/**
		 * Get dropcap style from classname on the block.
		 *
		 * @return {String}
		 */
		getDropcapStyle() {
			const {
				attributes,
			} = this.props;

			return getActiveClass( attributes.className, 'is-cnvs-dropcap' );
		}

		/**
		 * Update dropcap classname on the block.
		 *
		 * @param {String} dropcapName name of dropcap style
		 * @memberof NewEdit
		 */
		updateDropcapStyle( dropcapName ) {
			const {
				attributes,
				onChangeClassName,
			} = this.props;

			const updatedClassName = replaceClass( attributes.className, 'is-cnvs-dropcap', dropcapName );

			onChangeClassName( updatedClassName );
		}

		render() {
			if ( 'core/paragraph' !== this.props.name ) {
				return <BlockEdit { ...this.props } />;
			}

			const {
				attributes,
			} = this.props;

			if ( attributes.dropCap ) {
				let dropcapStyle = this.getDropcapStyle();

				if ( dropcapStyle ) {
					dropcapStyle = dropcapStyle.replace( /^is-cnvs-dropcap-/, '' );
				}

				return (
					<Fragment>
						<BlockEdit { ...this.props } />
						<InspectorControls>
							<PanelBody
								title={ __( 'Dropcap Style' ) }
							>
								<SelectControl
									value={ dropcapStyle }
									options={ [
										{
											label: __( 'Default' ),
											value: '',
										}, {
											label: __( 'Simple' ),
											value: 'simple',
										}, {
											label: __( 'Bordered' ),
											value: 'bordered',
										}, {
											label: __( 'Border Right' ),
											value: 'border-right',
										}, {
											label: __( 'Background Light' ),
											value: 'bg-light',
										}, {
											label: __( 'Background Dark' ),
											value: 'bg-dark',
										},
									] }
									onChange={ ( val ) => {
										this.updateDropcapStyle( val );
									} }
								/>
							</PanelBody>
						</InspectorControls>
					</Fragment>
				);
			}

			return <BlockEdit { ...this.props } />;
		}
	}

	return withDispatch( ( dispatch, { clientId } ) => {
		return {
			onChangeClassName( newClassName ) {
				dispatch( 'core/block-editor' ).updateBlockAttributes( clientId, {
					className: newClassName,
				} );
			},
		};
	} )( NewEdit );
}, 'paragraphWithCanvasDropcap' );

addFilter( 'editor.BlockEdit', 'canvas/paragraph/dropcap', paragraphWithCanvasDropcap );
