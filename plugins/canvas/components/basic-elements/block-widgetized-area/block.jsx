/**
 * WordPress dependencies
 */
const {
	addFilter,
} = wp.hooks;

/**
 * Internal dependencies
 */
import WidgetizedAreaBlockEdit from './edit.jsx';

/**
 * Custom block Edit output for Widgetized Area block.
 *
 * @param {JSX} edit Original block edit.
 * @param {Object} blockProps Block data.
 *
 * @return {JSX} Block edit.
 */
function editRender( edit, blockProps ) {
	if ( 'canvas/widgetized-area' === blockProps.name ) {
		return (
			<WidgetizedAreaBlockEdit { ...blockProps } />
		);
	}

	return edit;
}

addFilter( 'canvas.customBlock.editRender', 'canvas/widgetized-area/editRender', editRender );
