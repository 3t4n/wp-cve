/**
 * WordPress dependencies
 */
const {
	addFilter,
} = wp.hooks;

/**
 * Internal dependencies
 */
import CollapsibleBlockEdit from './edit.jsx';
import CollapsibleBlockSave from './save.jsx';

/**
 * Custom block Edit output for Collapsible block.
 *
 * @param {JSX} edit Original block edit.
 * @param {Object} blockProps Block data.
 *
 * @return {JSX} Block edit.
 */
function editRender( edit, blockProps ) {
	if ( 'canvas/collapsible' === blockProps.name ) {
		return (
			<CollapsibleBlockEdit { ...blockProps } />
		);
	}

	return edit;
}

/**
 * Custom block register data for Collapsible block.
 *
 * @param {Object} blockData Block data.
 *
 * @return {Object} Block data.
 */
function registerData( blockData ) {
	if ( 'canvas/collapsible' === blockData.name ) {
		blockData.save = CollapsibleBlockSave;
	}

	return blockData;
}

addFilter( 'canvas.customBlock.editRender', 'canvas/collapsible/editRender', editRender );
addFilter( 'canvas.customBlock.registerData', 'canvas/collapsible/registerData', registerData );
