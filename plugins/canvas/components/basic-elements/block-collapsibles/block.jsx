/**
 * WordPress dependencies
 */
const {
	addFilter,
} = wp.hooks;

/**
 * Internal dependencies
 */
import CollapsiblesBlockEdit from './edit.jsx';
import CollapsiblesBlockSave from './save.jsx';

/**
 * Custom block Edit output for Collapsibles block.
 *
 * @param {JSX} edit Original block edit.
 * @param {Object} blockProps Block data.
 *
 * @return {JSX} Block edit.
 */
function editRender( edit, blockProps ) {
	if ( 'canvas/collapsibles' === blockProps.name ) {
		return (
			<CollapsiblesBlockEdit { ...blockProps } />
		);
	}

	return edit;
}

/**
 * Custom block register data for Collapsibles block.
 *
 * @param {Object} blockData Block data.
 *
 * @return {Object} Block data.
 */
function registerData( blockData ) {
	if ( 'canvas/collapsibles' === blockData.name ) {
		blockData.save = CollapsiblesBlockSave;
	}

	return blockData;
}

addFilter( 'canvas.customBlock.editRender', 'canvas/collapsibles/editRender', editRender );
addFilter( 'canvas.customBlock.registerData', 'canvas/collapsibles/registerData', registerData );
