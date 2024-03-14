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


/**
 * Override the default edit UI to include a new block inspector control for
 * assigning the custom styles if needed.
 *
 * @param {function|Component} BlockEdit Original component.
 *
 * @return {string} Wrapped component.
 */
const coverWithCanvasVertical = createHigherOrderComponent( ( BlockEdit ) => {
	class NewEdit extends Component {
		constructor() {
			super( ...arguments );

			this.getVerticalAlign    = this.getVerticalAlign.bind( this );
			this.updateVerticalAlign = this.updateVerticalAlign.bind( this );
		}

		/**
		 * Get vertical style from classname on the block.
		 *
		 * @return {String}
		 */
		getVerticalAlign() {
			const {
				attributes,
			} = this.props;

			return getActiveClass( attributes.className, 'is-cnvs-vert-align' );
		}

		/**
		 * Update vertical classname on the block.
		 *
		 * @param {String} verticalName name of vertical style
		 * @memberof NewEdit
		 */
		updateVerticalAlign( verticalName ) {
			const {
				attributes,
				onChangeClassName,
			} = this.props;

			const updatedClassName = replaceClass( attributes.className, 'is-cnvs-vert-align', verticalName );

			onChangeClassName( updatedClassName );
		}

		render() {
			if ( 'core/cover' !== this.props.name ) {
				return <BlockEdit { ...this.props } />;
			}

			const {
				attributes,
			} = this.props;

			let verticalAlign = this.getVerticalAlign();

			if ( verticalAlign ) {
				verticalAlign = verticalAlign.replace( /^is-cnvs-vert-align-/, '' );
			}

			return (
				<Fragment>
					<BlockEdit { ...this.props } />
					<InspectorControls>
						<PanelBody
							title={ __( 'Vertical Align' ) }
						>
							<SelectControl
								value={ verticalAlign }
								options={ [
									{
										label: __( 'Top' ),
										value: '',
									}, {
										label: __( 'Middle' ),
										value: 'middle',
									}, {
										label: __( 'Bottom' ),
										value: 'bottom',
									},
								] }
								onChange={ ( val ) => {
									this.updateVerticalAlign( val );
								} }
							/>
						</PanelBody>
					</InspectorControls>
				</Fragment>
			);;
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
}, 'coverWithCanvasVertical' );

addFilter( 'editor.BlockEdit', 'canvas/cover/vertical', coverWithCanvasVertical );
