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
			default: acadp_blocks.listings.view
		},		
		location: {
			type: 'number',
			default: acadp_blocks.listings.location
		},	
		category: {
			type: 'number',
			default: acadp_blocks.listings.category
		},	
		columns: {
			type: 'number',
			default: acadp_blocks.listings.columns
		},
		listings_per_page: {
			type: 'number',
			default: acadp_blocks.listings.listings_per_page
		},
		filterby: {
			type: 'string',
			default: acadp_blocks.listings.filterby
		},
		orderby: {
			type: 'string',
			default: acadp_blocks.listings.orderby
		},
		order: {
			type: 'string',
			default: acadp_blocks.listings.order
		},		
		featured: {
			type: 'boolean',
			default: acadp_blocks.listings.featured
		},		
		header: {
			type: 'boolean',
			default: acadp_blocks.listings.header
		},
		show_excerpt: {
			type: 'boolean',
			default: acadp_blocks.listings.show_excerpt
		},
		show_category: {
			type: 'boolean',
			default: acadp_blocks.listings.show_category
		},
		show_location: {
			type: 'boolean',
			default: acadp_blocks.listings.show_location
		},
		show_price: {
			type: 'boolean',
			default: acadp_blocks.listings.show_price
		},
		show_date: {
			type: 'boolean',
			default: acadp_blocks.listings.show_date
		},
		show_user: {
			type: 'boolean',
			default: acadp_blocks.listings.show_user
		},
		show_views: {
			type: 'boolean',
			default: acadp_blocks.listings.show_views
		},
		show_custom_fields: {
			type: 'boolean',
			default: acadp_blocks.listings.show_custom_fields
		},
		pagination: {
			type: 'boolean',
			default: acadp_blocks.listings.pagination
		},
	},
	
	edit: Edit
} );
