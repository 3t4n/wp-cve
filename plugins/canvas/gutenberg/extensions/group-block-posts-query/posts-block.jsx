/**
 * Internal dependencies
 */
import getParentBlock from '../../../gutenberg/utils/get-parent-block';

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

const {
	Component,
	Fragment,
} = wp.element;

const {
	addFilter,
} = wp.hooks;

const {
	Notice,
	Button,
} = wp.components;

const {
	createHigherOrderComponent,
	compose,
} = wp.compose;

const {
	withSelect,
} = wp.data;

/**
 * Hide Query Settings if Group block override it.
 *
 * @param {JSX} sectionFields - section fields.
 * @param {Object} props - block props.
 *
 * @returns {JSX} - section fields.
 */
function hidePostsQuery( sectionFields, { sectionName, props } ) {
	const {
		blockProps,
	} = props;

	if ( ! blockProps || ! blockProps.name || 'canvas/posts' !== blockProps.name || sectionName !== 'query' ) {
		return sectionFields;
	}

	const {
		getBlockHierarchyRootClientId,
		getBlockParents,
		getBlock,
	} = wp.data.select('core/block-editor');

	const {
		selectBlock,
	} = wp.data.dispatch('core/block-editor');

	const rootBlock = getBlock( getBlockHierarchyRootClientId( blockProps.clientId ) );

	const parentBlocks = getBlockParents(blockProps.clientId);

	let groupQueryBlock = false;

	// Trying to find parent Posts Group block.
	if ( rootBlock && (undefined === typeof rootBlock.attributes.ref || ! rootBlock.attributes.ref) && parentBlocks ) {
		parentBlocks.forEach((parentID) => {
			let parentBlock = getBlock( parentID );
			if ( ! groupQueryBlock ) {
				groupQueryBlock = 'core/group' === parentBlock.name && parentBlock.attributes.canvasPostsQuery ? parentBlock : false;
			}
		});
	}

	if ( groupQueryBlock ) {
		return (
			<Fragment>
				<Notice
					status="warning"
					isDismissible={ false }
					className="cnvs-extension-group-posts-query-notice"
				>
					{ __( 'This post block is located inside the "Group" block with enabled global Query settings. Please, change these settings in the parent "Group" block.', 'canvas' ) }
				</Notice>
				<Button
					isPrimary
					onClick={ () => {
						selectBlock( groupQueryBlock.clientId );
					} }
				>
					{ __( 'Select "Group" Block', 'canvas' ) }
				</Button>
			</Fragment>
		);
	}

	return sectionFields;
}

/**
 * Control the `group_query` attribute in Posts blocks.
 *
 * @param {function|Component} BlockEdit Original component.
 *
 * @return {string} Wrapped component.
 */
const withGroupAttribute = createHigherOrderComponent( ( OriginalComponent ) => {
	class CanvasPostsQueryGroupController extends Component {
		constructor() {
			super( ...arguments );

			this.update = this.update.bind( this );
		}

		componentDidMount() {
			this.update();
		}

		componentDidUpdate() {
			this.update();
		}

		update() {
			const {
				name,
				groupQueryBlock,
				attributes,
				setAttributes,
			} = this.props;

			if ( 'canvas/posts' !== name ) {
				return;
			}

			if ( groupQueryBlock ) {
				if ( groupQueryBlock.attributes.canvasClassName && groupQueryBlock.attributes.canvasClassName !== attributes.queryGroup ) {
					setAttributes( {
						queryGroup: groupQueryBlock.attributes.canvasClassName,
					} );
				}
			} else if ( attributes.queryGroup ) {
				setAttributes( {
					queryGroup: '',
				} );
			}
		}

		render() {
			return <OriginalComponent { ...this.props } />;
		}
	}

	return compose(
		withSelect((select, ownProps) => {
			if ( 'canvas/posts' !== ownProps.name ) {
				return {};
			}

			const {
				getBlockHierarchyRootClientId,
				getBlockParents,
				getBlock,
			} = select('core/block-editor');

			const rootBlock = getBlock( getBlockHierarchyRootClientId( ownProps.clientId ) );

			const parentBlocks = getBlockParents(ownProps.clientId);

			let groupQueryBlock = false;

			// Trying to find parent Posts Group block.
			if ( rootBlock && (undefined === typeof rootBlock.attributes.ref || ! rootBlock.attributes.ref) && parentBlocks ) {
				parentBlocks.forEach((parentID) => {
					let parentBlock = getBlock( parentID );
					if ( ! groupQueryBlock ) {
						groupQueryBlock = 'core/group' === parentBlock.name && parentBlock.attributes.canvasPostsQuery ? parentBlock : false;
					}
				});
			}

			return {
				groupQueryBlock,
			};
		})
	)(CanvasPostsQueryGroupController);
}, 'withGroupAttribute' );

addFilter( 'canvas.component.fieldsRender', 'canvas/group-block-posts-query/hide-posts-query', hidePostsQuery );
addFilter( 'editor.BlockEdit', 'canvas/group-block-posts-query/additional-attributes', withGroupAttribute );
