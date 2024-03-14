/**
 * Get parent block data.
 *
 * @param {Object} rootBlock root block data
 * @param {Object} currentBlock current block data
 * @return {Object}
 */
export default function getParentBlock(rootBlock, currentBlock) {
	if (rootBlock && rootBlock.clientId === currentBlock.clientId) {
		return rootBlock;
	}

	let result = false;

	// Check all inner blocks to find parent block.
	if (rootBlock && rootBlock.innerBlocks && rootBlock.innerBlocks.length) {
		rootBlock.innerBlocks.forEach((innerBlockData) => {
			const innerParent = getParentBlock(innerBlockData, currentBlock);

			if (!result && innerParent) {
				result = innerParent.clientId === currentBlock.clientId ? rootBlock : innerParent;
			}
		});
	}

	return result;
}
