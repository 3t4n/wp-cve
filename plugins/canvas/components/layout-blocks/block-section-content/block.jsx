/**
 * WordPress dependencies
 */
const {
    addFilter,
} = wp.hooks;

/**
 * Internal dependencies
 */
import SectionContentBlockEdit from './edit.jsx';
import SectionContentBlockSave from './save.jsx';

/**
 * Custom block Edit output for Section block.
 *
 * @param {JSX} edit Original block edit.
 * @param {Object} blockProps Block data.
 *
 * @return {JSX} Block edit.
 */
function editRender( edit, blockProps ) {
	if ( 'canvas/section-content' === blockProps.name ) {
		return (
			<SectionContentBlockEdit { ...blockProps } />
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
	if ( 'canvas/section-content' === blockData.name ) {
		blockData.save = SectionContentBlockSave;
	}

    return blockData;
}

addFilter( 'canvas.customBlock.editRender', 'canvas/section-content/editRender', editRender );
addFilter( 'canvas.customBlock.registerData', 'canvas/section-content/registerData', registerData );
