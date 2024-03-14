/**
 * WordPress dependencies
 */
const {
	addFilter,
} = wp.hooks;

/**
 * Internal dependencies
 */
import TabBlockEdit from './edit.jsx';
import TabBlockSave from './save.jsx';

/**
 * Custom block Edit output for Tab block.
 *
 * @param {JSX} edit Original block edit.
 * @param {Object} blockProps Block data.
 *
 * @return {JSX} Block edit.
 */
function editRender( edit, blockProps ) {
	if ( 'canvas/tab' === blockProps.name ) {
		return (
			<TabBlockEdit { ...blockProps } />
		);
	}

	return edit;
}

/**
 * Custom block register data for Tab block.
 *
 * @param {Object} blockData Block data.
 *
 * @return {Object} Block data.
 */
function registerData( blockData ) {
	if ( 'canvas/tab' === blockData.name ) {
		blockData.save = TabBlockSave;
	}

	return blockData;
}

addFilter( 'canvas.customBlock.editRender', 'canvas/tab/editRender', editRender );
addFilter( 'canvas.customBlock.registerData', 'canvas/tab/registerData', registerData );
