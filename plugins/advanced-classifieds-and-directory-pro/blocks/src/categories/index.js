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
		view: {
			type: 'string',
			default: acadp_blocks.categories.view
		},
		parent: {
			type: 'number',
			default: acadp_blocks.categories.parent
		},			
		columns: {
			type: 'number',
			default: acadp_blocks.categories.columns
		},
		depth: {
			type: 'number',
			default: acadp_blocks.categories.depth
		},
		orderby: {
			type: 'string',
			default: acadp_blocks.categories.orderby
		},
		order: {
			type: 'string',
			default: acadp_blocks.categories.order
		},
		show_count: {
			type: 'boolean',
			default: acadp_blocks.categories.show_count
		},
		hide_empty: {
			type: 'boolean',
			default: acadp_blocks.categories.hide_empty
		},
	},

	edit: Edit
} );
