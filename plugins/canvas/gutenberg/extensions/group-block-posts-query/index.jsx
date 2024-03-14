/**
 * Internal dependencies
 */
import './style.scss';

import QueryControl from '../../components/query-control';
import getParentBlock from '../../../gutenberg/utils/get-parent-block';

import './posts-block';

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

const { InspectorControls } = wp.blockEditor;

const {
	PanelBody,
	ToggleControl,
} = wp.components;

const {
	createHigherOrderComponent,
	compose,
} = wp.compose;

const {
	withSelect,
	withDispatch,
} = wp.data;

/**
 * Extend block attributes with posts block query attributes.
 *
 * @param {Object} blockSettings Original block settings.
 * @param {String} name Original block name.
 *
 * @return {Object} Filtered block settings.
 */
function addAttribute( blockSettings, name ) {
	// add support to core blocks
	if ( 'core/group' === name ) {
		if ( ! blockSettings.attributes ) {
			blockSettings.attributes = {};
		}

		if ( ! blockSettings.attributes.canvasPostsQuery ) {
			blockSettings.attributes.canvasPostsQuery = {
				type: 'boolean',
			};
		}
	}

	return blockSettings;
}

/**
 * Get all posts blocks inside Posts Group block.
 *
 * @param {Object} block - block data.
 *
 * @returns {Array} - list of collected blocks.
 */
function getAllPostsBlocks( block ) {
	let result = [];

	if ( ! block ) {
		return result;
	}

	// Collect posts block data.
	if ( block.name && 'canvas/posts' === block.name ) {
		result.push( block );
	}

	// Check inner blocks.
	if ( block.innerBlocks && block.innerBlocks.length ) {
		block.innerBlocks.forEach( ( innerBlock ) => {
			result = [
				...result,
				...getAllPostsBlocks( innerBlock ),
			];
		} );
	}

	return result;
}

/**
 * Override the default edit UI to include a new block inspector control for
 * assigning the Query settings to Group block.
 *
 * @param {function|Component} BlockEdit Original component.
 *
 * @return {string} Wrapped component.
 */
const withInspectorControl = createHigherOrderComponent( ( OriginalComponent ) => {
	class CanvasGroupPostsQueryWrapper extends Component {
		constructor() {
			super( ...arguments );

			this.update = this.update.bind( this );
			this.getQueryAttrs = this.getQueryAttrs.bind( this );
			this.updateQueryAttrs = this.updateQueryAttrs.bind( this );
			this.maybeUpdatePostsBlockAttrs = this.maybeUpdatePostsBlockAttrs.bind( this );
		}

		componentDidMount() {
			this.update();
		}

		componentDidUpdate() {
			this.update();
		}

		update() {
			const {
				attributes,
				canvasPostsBlocks,
				allowCanvasPostsQuery,
			} = this.props;

			if ( ! allowCanvasPostsQuery || ! attributes.canvasPostsQuery ) {
				return;
			}

			// Update inner Posts blocks attributes.
			this.maybeUpdatePostsBlockAttrs( canvasPostsBlocks );
		}

		/**
		 * Get group query attrs.
		 * On first run get attrs from the first found Posts block.
		 *
		 * @returns {Object} - grouped posts attrs.
		 */
		getQueryAttrs() {
			const {
				canvasPostsBlocks,
			} = this.props;

			if ( ! this.canvasPostsGroupAttrs ) {
				// default attributes.
				this.canvasPostsGroupAttrs = {
					avoidDuplicatePosts: false,
					query: {
						posts_type: 'post',
						categories: '',
						tags: '',
						exclude_categories: '',
						exclude_tags: '',
						formats: '',
						posts: '',
						offset: '',
						orderby: 'date',
						order: 'DESC',
						time_frame: '',
					},
				};

				// attributes from the first inner Posts block.
				if ( canvasPostsBlocks && canvasPostsBlocks.length ) {
					if ( canvasPostsBlocks[ 0 ].attributes && canvasPostsBlocks[ 0 ].attributes.query ) {
						this.canvasPostsGroupAttrs.query = {
							...this.canvasPostsGroupAttrs.query,
							...canvasPostsBlocks[ 0 ].attributes.query,
						};
					}
					if ( canvasPostsBlocks[ 0 ].attributes && canvasPostsBlocks[ 0 ].attributes.avoidDuplicatePosts ) {
						this.canvasPostsGroupAttrs.avoidDuplicatePosts = true;
					}
				}
			}

			return this.canvasPostsGroupAttrs;
		}

		/**
		 * Update query attrs.
		 */
		updateQueryAttrs( newAttrs ) {
			const currentAttrs = this.getQueryAttrs();

			this.canvasPostsGroupAttrs = {
				...currentAttrs,
				...newAttrs,
				query: {
					...currentAttrs.query,
					...newAttrs.query || {},
				},
			};

			this.update();
		}

		/**
		 * Update posts blocks Query attributes to work properly inside Posts Group block.
		 *
		 * @param {Array} blocks block list.
		 */
		maybeUpdatePostsBlockAttrs( blocks ) {
			if ( this.groupBlockAttrsBusy ) {
				return;
			}

			this.groupBlockAttrsBusy = true;

			const {
				updateBlockAttributes,
			} = this.props;

			const newAttrs = this.getQueryAttrs();
			const queryString = JSON.stringify( newAttrs );

			blocks.forEach( ( { clientId, attributes } ) => {
				const postsQueryString = JSON.stringify( {
					avoidDuplicatePosts: attributes.avoidDuplicatePosts,
					query: attributes.query,
				} );

				if (
					attributes.relatedPosts ||
					postsQueryString !== queryString
				) {
					updateBlockAttributes( clientId, {
						relatedPosts: false,
						avoidDuplicatePosts: newAttrs.avoidDuplicatePosts,
						query: {
							...newAttrs.query,
						},
					} );
				}
			} );

			setTimeout( () => {
				this.groupBlockAttrsBusy = false;
			}, 100 );
		}

		render() {
			const {
				setAttributes,
				attributes,
				allowCanvasPostsQuery,
			} = this.props;

			if ( ! attributes.canvasPostsQuery && ! allowCanvasPostsQuery ) {
				return <OriginalComponent { ...this.props } />;
			}

			const postsGroupAttrs = attributes.canvasPostsQuery ? this.getQueryAttrs() : {};

			// add new spacings controls.
			return (
				<Fragment>
					<OriginalComponent { ...this.props } />
					<InspectorControls>
						<PanelBody
							title={ __( 'Query Settings', 'canvas' ) }
							initialOpen={ false }
						>
							<ToggleControl
								label={ __( 'Enable global Query settings for child Posts blocks', 'canvas' ) }
								checked={ attributes.canvasPostsQuery }
								onChange={ () => {
									setAttributes( {
										canvasPostsQuery: ! attributes.canvasPostsQuery,
									} );
								} }
							/>
							{ attributes.canvasPostsQuery ? (
								<Fragment>
									<QueryControl
										value={ postsGroupAttrs.query }
										onChange={ ( val ) => {
											this.updateQueryAttrs( {
												query: val,
											} );
										} }
									/>
									<ToggleControl
										label={ __( 'Avoid Duplicate Posts', 'canvas' ) }
										help={ __( 'Changes will be visible on frontend only.', 'canvas' ) }
										checked={ postsGroupAttrs.avoidDuplicatePosts }
										onChange={ ( val ) => {
											this.updateQueryAttrs( {
												avoidDuplicatePosts: val,
											} );
										} }
									/>
								</Fragment>
							) : '' }
						</PanelBody>
					</InspectorControls>
				</Fragment>
			);
		}
	}

	return compose(
		withSelect((select, ownProps) => {
			if ( 'core/group' !== ownProps.name ) {
				return {};
			}

			const {
				getBlockHierarchyRootClientId,
				getBlockParents,
				getBlock,
			} = select('core/block-editor');

			const currentBlock = getBlock( ownProps.clientId );
			const rootBlock = getBlock( getBlockHierarchyRootClientId( ownProps.clientId ) );
			const parentBlocks = getBlockParents(ownProps.clientId);
			const canvasPostsBlocks = getAllPostsBlocks( currentBlock );

			let isInsideAnotherGroupQuery = false;

			// Trying to find parent Posts Group block.
			if ( rootBlock && (undefined === typeof rootBlock.attributes.ref || ! rootBlock.attributes.ref) && parentBlocks ) {
				parentBlocks.forEach((parentID) => {
					let parentBlock = getBlock( parentID );
					if ( ! isInsideAnotherGroupQuery ) {
						isInsideAnotherGroupQuery = 'core/group' === parentBlock.name && parentBlock.attributes.canvasPostsQuery ? parentBlock : false;
					}
				});
			}

			return {
				allowCanvasPostsQuery: canvasPostsBlocks.length && ! isInsideAnotherGroupQuery,
				canvasPostsBlocks,
			};
		}),
		withDispatch((dispatch) => {
			const {
				updateBlockAttributes,
			} = dispatch('core/block-editor');

			return {
				updateBlockAttributes,
			};
		})
	)(CanvasGroupPostsQueryWrapper);
}, 'withInspectorControl' );

// Init filters.
addFilter( 'blocks.registerBlockType', 'canvas/group-block-posts-query/additional-attributes', addAttribute );
addFilter( 'editor.BlockEdit', 'canvas/group-block-posts-query/additional-attributes', withInspectorControl );
