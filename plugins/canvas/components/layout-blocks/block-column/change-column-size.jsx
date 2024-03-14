/**
 * WordPress dependencies.
 */
const {
	getBlockRootClientId,
	getBlocks,
	getBlockAttributes,
} = wp.data.select( 'core/block-editor' );

const {
	jQuery: $,
} = window;

/**
 * Internal dependencies
 */
import { getAdjacentBlocks } from './utils';

const MIN_COL_SIZE = 1;

/**
 * Update columns size.
 *
 * First, we update the column size by changing style directly, then we update the block attribute.
 * This hack is needed to prevent columns jumping as `updateBlockAttributes` don't apply changes immediately.
 *
 * @param {*} clientId
 * @param {*} size
 */
function updateAttribute( clientId, size ) {
	$( `#block-${ clientId }[data-type="canvas/column"]` ).css( {
		flexBasis: `${ 100 * size / 12 }%`,
		width: `${ 100 * size / 12 }%`,
	} );

	wp.data.dispatch( 'core/block-editor' ).updateBlockAttributes(clientId, {
		size: size,
	} );
}

/**
 * Update columns size and compensate adjacent columns if needed.
 *
 * @param {String} clientId - block client id.
 * @param {Int} size - new column size.
 * @param {Boolean} compensate - compensate adjacent columns also.
 */
export default function( clientId, size, compensate = false ) {
	if ( ! compensate ) {
		updateAttribute( clientId, size );
		return;
	}

	// Compensate
	if ( size < MIN_COL_SIZE || size > 12 ) {
		return;
	}

	// Constrain or expand siblings to account for gain or loss of
	// total columns area.
	const columns = getBlocks( getBlockRootClientId( clientId ) );
	const adjacentColumns = getAdjacentBlocks( columns, clientId );
	const thisColumnAttrs = getBlockAttributes( clientId );

	let neededSize = size - thisColumnAttrs.size;

	/*
	 * Make col smaller.
	 */
	if ( neededSize < 0 ) {
		// set new size to current column.
		updateAttribute( clientId, size );

		// set new size to next column.
		updateAttribute( adjacentColumns[0].clientId, adjacentColumns[0].attributes.size - neededSize );
	} else if ( neededSize > 0) {
		/*
		 * Make col larger.
		 */
		let availableSize = 0;
		adjacentColumns.map( ( colData ) => {
			if ( colData.attributes.size > MIN_COL_SIZE ) {
				availableSize += colData.attributes.size - MIN_COL_SIZE;
			}
		} );

		// we can't change size, because no space available on the right.
		if ( ! availableSize || neededSize > availableSize ) {
			return;
		}

		// set new size to current column.
		updateAttribute( clientId, size );

		// set new size to adjacent columns.
		adjacentColumns.forEach( ( colData ) => {
			if ( neededSize > 0 && colData.attributes.size > MIN_COL_SIZE ) {
				const newColSize = Math.max( colData.attributes.size - neededSize, MIN_COL_SIZE );
				neededSize -= colData.attributes.size - newColSize;

				updateAttribute( colData.clientId, newColSize );
			}
		} );
	}
}
