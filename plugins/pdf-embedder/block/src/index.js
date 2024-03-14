import { registerBlockType } from '@wordpress/blocks';

import './style.scss';

/**
 * Internal dependencies.
 */
import Edit from './edit';
import metadata from './block.json';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType(
	metadata,
	{
		"attributes": {
			"pdfID": {
				"type": "number"
			},
			"url": {
				"type": "string"
			},
			"width": {
				"type": "string",
				"default": pdfembPluginOptions.width
			},
			"height": {
				"type": "string",
				"default": pdfembPluginOptions.height
			},
			"toolbar": {
				"type": "string",
				"enum": ["top", "bottom", "both", "none"],
				"default": pdfembPluginOptions.toolbar
			},
			"toolbarfixed": {
				"type": "string",
				"enum": ["off", "on"],
				"default": pdfembPluginOptions.toolbarfixed
			}
		},
		edit: Edit,
	}
);
