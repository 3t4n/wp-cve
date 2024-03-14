/**
 * Import block dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

import Edit from './edit';
import metadata from './block.json';

/**
 * Register the block.
 */
registerBlockType( metadata.name, {
	attributes: {
		parent: {
			type: 'number',
			default: acadp_blocks.locations.parent
		},	
		columns: {
			type: 'number',
			default: acadp_blocks.locations.columns
		},
		depth: {
			type: 'number',
			default: acadp_blocks.locations.depth
		},
		orderby: {
			type: 'string',
			default: acadp_blocks.locations.orderby
		},
		order: {
			type: 'string',
			default: acadp_blocks.locations.order
		},
		show_count: {
			type: 'boolean',
			default: acadp_blocks.locations.show_count
		},
		hide_empty: {
			type: 'boolean',
			default: acadp_blocks.locations.hide_empty
		},
	},

	edit: Edit
} );
