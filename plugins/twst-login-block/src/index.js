import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

import Edit from './edit';

registerBlockType( 'twst/login', {
	title: __( 'Login', 'twst-login-block' ),
	description: __( 'Easily insert a login form block into your post or page!', 'twst-login-block' ),
	category: 'widgets',
	icon: 'lock',
	supports: {
		html: false,
	},
	example: {},

	attributes: {
		labelUsername: {
			type: 'string',
		},
		defaultUsername: {
			type: 'string',
		},
		labelPassword: {
			type: 'string',
		},
		labelRememberMe: {
			type: 'string',
		},
		defaultRememberMe: {
			type: 'boolean',
			default: false,
		},
		showRememberMe: {
			type: 'boolean',
			default: true,
		},
		labelLogIn: {
			type: 'string',
		},
		loggedInBehaviour: {
			type: 'string',
			default: 'hide',
		}
	},

	edit: Edit,
} );
