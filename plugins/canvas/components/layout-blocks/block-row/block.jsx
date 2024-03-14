/**
 * WordPress dependencies
 */
const {
    addFilter,
} = wp.hooks;

/**
 * Internal dependencies
 */
import RowBlockEdit from './edit.jsx';
import RowBlockSave from './save.jsx';

/**
 * Custom block Edit output for Row block.
 *
 * @param {JSX} edit Original block edit.
 * @param {Object} blockProps Block data.
 *
 * @return {JSX} Block edit.
 */
function editRender( edit, blockProps ) {
	if ( 'canvas/row' === blockProps.name ) {
		return (
			<RowBlockEdit { ...blockProps } />
		);
	}

    return edit;
}

/**
 * Custom block register data for Row block.
 *
 * @param {Object} blockData Block data.
 *
 * @return {Object} Block data.
 */
function registerData( blockData ) {
	if ( 'canvas/row' === blockData.name ) {
		blockData.save = RowBlockSave;
	}

    return blockData;
}

addFilter( 'canvas.customBlock.editRender', 'canvas/row/editRender', editRender );
addFilter( 'canvas.customBlock.registerData', 'canvas/row/registerData', registerData );
