/**
 * Internal dependencies
 */
import './store/scheme';
import './store/breakpoint';
import './extensions/group-block-posts-query';
import './extensions/unique-class';
import './extensions/background-image';
import './extensions/spacings';
import './extensions/border';
import './extensions/responsive-settings';
import './style.scss';

/**
 * WordPress dependencies
 */
const {
	addFilter,
} = wp.hooks;

/**
 * Prepare server render attributes.
 *
 * @param {Object} attrs Values of attributes.
 * @param {Object} data Fields data.
 * @returns {Object}
 */
function prepare_server_render_attributes( attrs, data ) {
	return attrs;
}

addFilter( 'canvas.block.prepareServerRenderAttributes', 'canvas.block.prepareServerRenderAttributes', prepare_server_render_attributes );
