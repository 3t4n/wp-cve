/**
 * External dependencies
 */
// import { omit } from 'lodash';
import { findIndex } from 'lodash';

/**
 * Internal dependencies
 */
import { 
    FETCH_PRODUCTS,
	FETCH_VARIATIONS,
	SET_SELECTED,
	SET_LIST,
	GET_PRODUCTS,
	SET_PRODUCTS,
	GET_VARIATIONS,
	SET_VARIATIONS,
	REMOVE_SELECTED,
	SWITCH_TO_PROD,
	SWITCH_TO_VAR,
	REMOVE_ALL_SELECTED,
	CLEAR_LISTS,
} from './actions';


export const DEFAULT_STATE = {
	isLoading: false,
	list: [],
	products: [],
	variations: {},
	// selected: [],
	currentProduct: {},
};

export default ( state = DEFAULT_STATE, action ) => {
	var { selected, products, variations } = state;
	switch ( action.type ) {
		case SWITCH_TO_PROD:
			// console.log( "switching to prod" );
			return {
				...state,
				list: products,
				isLoading: false,
			};
		case SWITCH_TO_VAR:
			// console.log( "switching to var: " + action.parent.id );
			// console.log( action.parent );
			// console.log( "that should have been the parent product." );
			return {
				...state,
				list: variations[ action.parent.id ],
				isLoading: false,
			};
		case SET_PRODUCTS:
			// console.log( "in set products and isloading should be false now" );
			return {
				...state,
				products: action.products,
				isLoading: false,
				list: action.products,
			};
		case SET_VARIATIONS:
			// console.log( "in set variations and isloading should be false now" );
			return {
				...state,
				variations: {
					...state.variations,
					[ action.parent.id ]: action.variations,
				},
				list: action.variations,
				currentProduct: action.parent,
				isLoading: false,
			};
		case SET_SELECTED:
			var newSel = ( state.selected && ! action.single ) ? state.selected.concat( action.product ) : [ action.product ];
			// console.log( "setting selected:" );
			// console.log( newSel );
			return {
				...state,
				selected: [ ...newSel ]
				// [
				// 	...state.selected,
				// 	action.product,
				// ]
			};
		
		case REMOVE_SELECTED:
			const id = action.product.id;
			var i = findIndex( selected, { id } );
			// console.log( "index of remove " + i );
			// console.log( "selected item: " + selected[i] );
			return {
				...state,
				selected: [
					...selected.slice( 0, i ),
					...selected.slice( i + 1 ),
				],
			};
		case SET_LIST:
			return {
				...state,
				list: action.list,
			};
		case GET_PRODUCTS:
			// console.log( "in get products?" );
			return {
				...state,
				// list: products,
				isLoading: true,
			};
		case GET_VARIATIONS:
			// return state;
			// console.log( "in get variations?" );
			return {
				...state,
				isLoading: true,
			};
		case REMOVE_ALL_SELECTED:
			return {
				...state,
				selected: [],
			}
		case CLEAR_LISTS:
			return {
				...state,
				list: [],
				products: [],
				variations: {},
			}
		default:
			return state;
	}
}
