/**
 * WordPress dependencies
 */
import { register } from '@wordpress/data';

/**
 * Internal dependencies
 */
import store from './store';

export const registerSettingsStore = () => {
	register( store );
};
