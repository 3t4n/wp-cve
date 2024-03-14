/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import edit from './edit';

import metadata from './block.json';

const { name, icon } = metadata;

export { metadata, name };

export const settings = {
	title: __( 'User Profile', 'user-profile-block' ),
	icon,
	edit,
	save: () => null,
};
