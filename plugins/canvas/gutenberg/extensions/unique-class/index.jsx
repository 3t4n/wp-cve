/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * Internal dependencies
 */
import isCoreBlockWithExt from '../../utils/is-core-block-with-ext';
import getParentBlock from '../../utils/get-parent-block';

const {
	canvasBreakpoints,
} = window;

/**
 * WordPress dependencies
 */
const {
	addFilter,
} = wp.hooks;

const {
	Component,
} = wp.element;

const {
	createHigherOrderComponent,
} = wp.compose;

const {
	hasBlockSupport,
} = wp.blocks;

const { select, subscribe } = wp.data;

/**
 * Extend block attributes with unique class name.
 *
 * @param {Object} blockSettings Original block settings.
 * @param {String} name Original block name.
 *
 * @return {Object} Filtered block settings.
 */
function addAttribute( blockSettings, name ) {

	let supports = (
		hasBlockSupport( blockSettings, 'canvasUniqueClass', false ) ||
		hasBlockSupport( blockSettings, 'canvasBackgroundImage', false ) ||
		hasBlockSupport( blockSettings, 'canvasSpacings', false ) ||
		hasBlockSupport( blockSettings, 'canvasBorder', false ) ||
		hasBlockSupport( blockSettings, 'canvasResponsive', false )
	);

	// add support to core blocks
	if ( isCoreBlockWithExt( name ) ) {
		blockSettings.supports = {
			...blockSettings.supports,
			canvasUniqueClass: true,
		};
		supports = true;
	}

	if ( supports ) {
		if ( blockSettings.attributes && ! blockSettings.attributes.canvasClassName ) {
			blockSettings.attributes.canvasClassName = {
				type: 'string',
			};
		}
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
	class CanvasUniqueClassNameWrapper extends Component {
		constructor() {
			super( ...arguments );

			this.getAllBlocks = this.getAllBlocks.bind( this );
			this.maybeCreateUniqueClassName = this.maybeCreateUniqueClassName.bind( this );
		}

		componentDidMount() {
			this.maybeCreateUniqueClassName( true );
		}
		componentDidUpdate() {
			this.maybeCreateUniqueClassName();
		}

		/**
		 * Get recursive all blocks of the current page
		 */
		getAllBlocks( blocks = false ) {
			let result = [];

			if ( ! blocks ) {
				blocks = wp.data.select( 'core/block-editor' ).getBlocks();
			}

			if ( ! blocks ) {
				return result;
			}

			blocks.forEach( ( data ) => {
				result.push( data );

				if ( data.innerBlocks && data.innerBlocks.length ) {
					result = [
						...result,
						...this.getAllBlocks( data.innerBlocks ),
					];
				}
			} );

			return result;
		}

		/**
		 * Generate unique block class name
		 */
		maybeCreateUniqueClassName( checkDuplicates ) {
			const {
				name,
				attributes,
				setAttributes,
				clientId,
			} = this.props;

			const {
				getBlockHierarchyRootClientId,
				getBlock,
			} = select('core/block-editor');

			const rootBlock = getBlock(getBlockHierarchyRootClientId(clientId));

			if ( rootBlock && rootBlock.hasOwnProperty( 'name' ) && 'core/gallery' === rootBlock.name ) {
				return;
			}

			if (
				! hasBlockSupport( name, 'canvasUniqueClass', false ) &&
				! hasBlockSupport( name, 'canvasBackgroundImage', false ) &&
				! hasBlockSupport( name, 'canvasSpacings', false ) &&
				! hasBlockSupport( name, 'canvasBorder', false ) &&
				! hasBlockSupport( name, 'canvasResponsive', false )
			) {
				return;
			}

			let {
				canvasClassName,
			} = attributes;

			// prevent unique ID duplication after block duplicated.
			if ( checkDuplicates ) {
				const allBlocks = this.getAllBlocks();

				allBlocks.forEach( ( data ) => {
					if (
						data.clientId !== clientId &&
						data.attributes &&
						data.attributes.canvasClassName &&
						data.attributes.canvasClassName === canvasClassName
					) {
						canvasClassName = '';
					}
				} );
			}

			if ( ! canvasClassName ) {
				const newId = new Date().getTime();

				// Generated HTML classes for blocks follow the `cnvs-block-{name}` nomenclature.
				// Blocks provided by Canvas drop the prefixes 'canvas/'.
				const className = 'cnvs-block-' + name.replace( /\//, '-' ).replace( /^canvas-/, '' );

				setAttributes( {
					canvasClassName: className + '-' + newId,
				} );
			}
		}

		render() {
			return <OriginalComponent { ...this.props } />;
		}
	}

	return CanvasUniqueClassNameWrapper;
}, 'withInspectorControl' );

/**
 * Override props assigned to save component to inject custom styles.
 * This is only applied if the block's save result is an
 * element and not a markup string.
 *
 * @param {Object} extraProps Additional props applied to save element.
 * @param {Object} blockType  Block type.
 * @param {Object} attributes Current block attributes.
 *
 * @return {Object} Filtered props applied to save element.
 */
function addSaveProps( extraProps, blockType, attributes ) {
	// add custom classname to non-canvas blocks.
	// we need this class only when Spacings or Responsive controls added on the block.
	if ( blockType.name && ! /^canvas/.test( name ) && attributes.canvasClassName ) {
		const extAttrs = [
			'backgroundImage',
			'backgroundPosition',
			'backgroundPositionXUnit',
			'backgroundPositionXVal',
			'backgroundPositionYUnit',
			'backgroundPositionYVal',
			'backgroundAttachment',
			'backgroundRepeat',
			'backgroundSize',
			'backgroundSizeUnit',
			'backgroundSizeVal',
			'marginTop',
			'marginRight',
			'marginBottom',
			'marginLeft',
			'paddingTop',
			'paddingRight',
			'paddingBottom',
			'paddingLeft',
			'borderRadiusTopLeft',
			'borderRadiusTopRight',
			'borderRadiusBottomLeft',
			'borderRadiusBottomRight',
			'borderStyle',
			'canvasResponsiveHide',
		];
		let isCustomClassRequired = false;

		// responsive attributes.
		extAttrs.forEach( ( attr ) => {
			if ( typeof attributes[ attr ] !== 'undefined' ) {
				isCustomClassRequired = true;
			}

			Object.keys( canvasBreakpoints ).forEach( ( breakpoint ) => {
				if ( ! isCustomClassRequired && typeof attributes[ `${ attr }_${ breakpoint }` ] !== 'undefined' ) {
					isCustomClassRequired = true;
				}
			} );
		} );

		if ( isCustomClassRequired ) {
			extraProps.className = classnames(
				extraProps.className,
				attributes.canvasClassName
			);
		}
	}

	return extraProps;
}

// Init filters.
addFilter( 'blocks.registerBlockType', 'canvas/unique-classname/additional-attributes', addAttribute );
addFilter( 'editor.BlockEdit', 'canvas/unique-classname/additional-attributes', withInspectorControl );
addFilter( 'blocks.getSaveContent.extraProps', 'canvas/unique-classname/save-props', addSaveProps );

/**
 * Used to modify the block’s wrapper component containing the block’s edit
 * component and all toolbars. It receives the original BlockListBlock
 * component and returns a new wrapped component.
 */
const withClientIdClassName = createHigherOrderComponent( ( BlockListBlock ) => {
	return ( props ) => {
		if ( props.name && ! /^canvas/.test( props.name ) && props.attributes.canvasClassName ) {
			return <BlockListBlock { ...props } className={ classnames( props.className, props.attributes.canvasClassName ) } />;
		} else {
			return <BlockListBlock { ...props } />;
		}
	};
}, 'withClientIdClassName' );

addFilter( 'editor.BlockListBlock', 'canvas/with-client-id-class-name', withClientIdClassName );
