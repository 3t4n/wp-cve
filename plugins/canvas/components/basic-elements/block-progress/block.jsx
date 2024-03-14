/**
 * WordPress dependencies
 */
const {
	addFilter,
} = wp.hooks;

/**
 * Internal dependencies
 */
import ProgressBlockEdit from './edit.jsx';

/**
 * Custom block Edit output for Progress block.
 *
 * @param {JSX} edit Original block edit.
 * @param {Object} blockProps Block data.
 *
 * @return {JSX} Block edit.
 */
function editRender( edit, blockProps ) {
	if ( 'canvas/progress' === blockProps.name ) {
		return (
			<ProgressBlockEdit { ...blockProps } />
		);
	}

	return edit;
}

addFilter( 'canvas.customBlock.editRender', 'canvas/progress/editRender', editRender );
