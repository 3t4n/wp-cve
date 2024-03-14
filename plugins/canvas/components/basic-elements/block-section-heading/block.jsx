/**
 * WordPress dependencies
 */
const {
	addFilter,
} = wp.hooks;

/**
 * Internal dependencies
 */
import SectionHeadingBlockEdit from './edit.jsx';

/**
 * Custom block Edit output for Styled Block.
 *
 * @param {JSX} edit Original block edit.
 * @param {Object} blockProps Block data.
 *
 * @return {JSX} Block edit.
 */
function editRender(edit, blockProps) {
	if ('canvas/section-heading' === blockProps.name) {
		return (
			<SectionHeadingBlockEdit {...blockProps} />
		);
	}

	return edit;
}

addFilter('canvas.customBlock.editRender', 'canvas/section-heading/editRender', editRender);
