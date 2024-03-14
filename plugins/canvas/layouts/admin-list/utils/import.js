/**
 * External dependencies
 */
const {
	isString,
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
import { readTextFile } from './file';

/**
 * Import a layout block from a JSON file.
 *
 * @param {File}     file File.
 * @return {Promise} Promise returning the imported layout block.
 */
async function importLayoutBlock( file ) {
	const fileContent = await readTextFile( file );
	let parsedContent;
	try {
		parsedContent = JSON.parse( fileContent );
	} catch ( e ) {
		throw new Error( 'Invalid JSON file' );
	}
	if (
		parsedContent.__file !== 'canvas_layout' ||
		! parsedContent.title ||
		! parsedContent.content ||
		! isString( parsedContent.title ) ||
		! isString( parsedContent.content )
	) {
		throw new Error( 'Invalid Canvas Layout JSON file' );
	}
	const postType = await apiFetch( { path: `/wp/v2/types/canvas_layout` } );
	const layoutBlock = await apiFetch( {
		path: `/wp/v2/${ postType.rest_base }`,
		data: {
			title: parsedContent.title,
			content: parsedContent.content,
			status: 'publish',
		},
		method: 'POST',
	} );

	return layoutBlock;
}

export default importLayoutBlock;
