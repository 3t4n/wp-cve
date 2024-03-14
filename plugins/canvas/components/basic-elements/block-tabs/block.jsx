/**
 * WordPress dependencies
 */
const {
	addFilter,
} = wp.hooks;

/**
 * Internal dependencies
 */
import TabsBlockEdit from './edit.jsx';
import TabsBlockSave from './save.jsx';

/**
 * Custom block Edit output for Tabs block.
 *
 * @param {JSX} edit Original block edit.
 * @param {Object} blockProps Block data.
 *
 * @return {JSX} Block edit.
 */
function editRender( edit, blockProps ) {
	if ( 'canvas/tabs' === blockProps.name ) {
		return (
			<TabsBlockEdit { ...blockProps } />
		);
	}

	return edit;
}

/**
 * Custom block register data for Tabs block.
 *
 * @param {Object} blockData Block data.
 *
 * @return {Object} Block data.
 */
function registerData( blockData ) {
	if ( 'canvas/tabs' === blockData.name ) {
		blockData.save = TabsBlockSave;
	}

	return blockData;
}

addFilter( 'canvas.customBlock.editRender', 'canvas/tabs/editRender', editRender );
addFilter( 'canvas.customBlock.registerData', 'canvas/tabs/registerData', registerData );
