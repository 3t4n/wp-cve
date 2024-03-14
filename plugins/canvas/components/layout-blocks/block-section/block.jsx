/**
 * WordPress dependencies
 */
const {
	addFilter,
} = wp.hooks;

/**
 * Internal dependencies
 */
import SectionBlockEdit from './edit.jsx';
import SectionBlockSave from './save.jsx';

/**
 * Custom block Edit output for Section block.
 *
 * @param {JSX} edit Original block edit.
 * @param {Object} blockProps Block data.
 *
 * @return {JSX} Block edit.
 */
function editRender(edit, blockProps) {
	if ('canvas/section' === blockProps.name) {
		return (
			<SectionBlockEdit {...blockProps} />
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
function registerData(blockData) {
	if ('canvas/section' === blockData.name) {
		blockData.save = SectionBlockSave;
		blockData.getEditWrapperProps = (attributes) => {
			const result = {
				'data-align': 'full',
			};

			// additional attribute for last block sticky
			if (attributes.sidebarSticky) {
				result['data-canvas-section-sticky'] = attributes.sidebarStickyMethod;
			}

			return result;
		};
	}

	return blockData;
}

addFilter('canvas.customBlock.editRender', 'canvas/section/editRender', editRender);
addFilter('canvas.customBlock.registerData', 'canvas/section/registerData', registerData);
