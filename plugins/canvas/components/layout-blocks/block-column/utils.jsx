const { findIndex } = window.lodash;

/**
 * Returns the considered adjacent to that of the specified `clientId` for
 * resizing consideration. Adjacent blocks are those occurring after, except
 * when the given block is the last block in the set. For the last block, the
 * behavior is reversed.
 *
 * @param {WPBlock[]} blocks   Block objects.
 * @param {string}    clientId Client ID to consider for adjacent blocks.
 *
 * @return {WPBlock[]} Adjacent block objects.
 */
export function getAdjacentBlocks( blocks, clientId ) {
	const index = findIndex( blocks, { clientId } );
	const isLastBlock = index === blocks.length - 1;

	return isLastBlock ? blocks.slice( 0, index ) : blocks.slice( index + 1 );
}
