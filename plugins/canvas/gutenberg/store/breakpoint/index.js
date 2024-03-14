/**
 * WordPress dependencies
 */
const {
	registerStore,
} = wp.data;

/**
 * Internal dependencies
 */
import reducer from './reducer';
import * as selectors from './selectors';
import * as actions from './actions';

registerStore( 'canvas/breakpoint', { reducer, selectors, actions } );
