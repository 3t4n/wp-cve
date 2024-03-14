/**
 * WordPress dependencies
 */
const {
    addFilter,
} = wp.hooks;

/**
 * Internal dependencies
 */
import SectionSidebarBlockEdit from './edit.jsx';
import SectionSidebarBlockSave from './save.jsx';

/**
 * Custom block Edit output for Section block.
 *
 * @param {JSX} edit Original block edit.
 * @param {Object} blockProps Block data.
 *
 * @return {JSX} Block edit.
 */
function editRender( edit, blockProps ) {
	if ( 'canvas/section-sidebar' === blockProps.name ) {
		return (
			<SectionSidebarBlockEdit { ...blockProps } />
		);
	}

    return edit;
}

/**
 * Custom block register data for Section block.
 *
 * @param {Object} blockData Block data.
 *
 * @return {Object} Block data.
 */
function registerData( blockData ) {
	if ( 'canvas/section-sidebar' === blockData.name ) {
		blockData.save = SectionSidebarBlockSave;
	}

    return blockData;
}

addFilter( 'canvas.customBlock.editRender', 'canvas/section-sidebar/editRender', editRender );
addFilter( 'canvas.customBlock.registerData', 'canvas/section-sidebar/registerData', registerData );
