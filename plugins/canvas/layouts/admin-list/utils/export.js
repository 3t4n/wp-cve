/**
 * External dependencies
 */
const {
	kebabCase,
} = window.lodash;

/**
 * WordPress dependencies
 */
const {
	apiFetch,
} = wp;

/**
 * Internal dependencies
 */
import { download } from './file';

/**
 * Export a layout block as a JSON file.
 *
 * @param {number} id
 */
async function exportLayoutBlock( id ) {
	const postType = await apiFetch( { path: `/wp/v2/types/canvas_layout` } );
	const post = await apiFetch( { path: `/wp/v2/${ postType.rest_base }/${ id }?context=edit` } );
	const title = post.title.raw;
	const content = post.content.raw;
	const fileContent = JSON.stringify( {
		__file: 'canvas_layout',
		title,
		content,
	}, null, 2 );
	const fileName = kebabCase( title ) + '.json';

	download( fileName, fileContent, 'application/json' );
}

export default exportLayoutBlock;
