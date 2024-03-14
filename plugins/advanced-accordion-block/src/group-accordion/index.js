import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';

/**
 * Internal dependencies
 */
import Edit from './edit';
import Save from './save';

// icon
import icon from './icon';

/**
 * Block Registration
 */

registerBlockType(metadata, {
	icon: {
		src: icon,
		foreground: '#77b5f7',
	},
	edit: Edit,
	save: Save,
});
