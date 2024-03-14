/**
 * WordPress dependencies
 */
const {
	addFilter,
} = wp.hooks;

/**
 * Internal dependencies
 */
import AlertBlockEdit from './edit.jsx';
import AlertBlockSave from './save.jsx';

/**
 * Custom block Edit output for Alert block.
 *
 * @param {JSX} edit Original block edit.
 * @param {Object} blockProps Block data.
 *
 * @return {JSX} Block edit.
 */
function editRender( edit, blockProps ) {
	if ( 'canvas/alert' === blockProps.name ) {
		return (
			<AlertBlockEdit { ...blockProps } />
		);
	}

	return edit;
}

/**
 * Custom block register data for Alert block.
 *
 * @param {Object} blockData Block data.
 *
 * @return {Object} Block data.
 */
function registerData( blockData ) {
	if ( 'canvas/alert' === blockData.name ) {
		blockData.save = AlertBlockSave;
	}

	return blockData;
}

addFilter( 'canvas.customBlock.editRender', 'canvas/alert/editRender', editRender );
addFilter( 'canvas.customBlock.registerData', 'canvas/alert/registerData', registerData );
