/**
 * External dependencies
 */
// import { translate as __ } from 'i18n-calypso';

/**
 * Internal dependencies
 */
import * as api from '../../api';

import { getProducts } from '../../search-product-util';
import { getProductVariations } from '../../search-product-variation-util';

export const FETCH_PRODUCTS = 'FETCH_PRODUCTS';
export const FETCH_VARIATIONS = 'FETCH_VARIATIONS';
export const SET_SELECTED = 'SET_SELECTED';
export const SET_LIST = 'SET_LIST';
export const GET_PRODUCTS = 'GET_PRODUCTS';
export const SET_PRODUCTS = 'SET_PRODUCTS';
export const GET_VARIATIONS = 'GET_VARIATIONS';
export const SET_VARIATIONS = 'SET_VARIATIONS';
export const REMOVE_SELECTED = 'REMOVE_SELECTED';
export const SWITCH_TO_PROD = 'SWITCH_TO_PROD';
export const SWITCH_TO_VAR  = 'SWITCH_TO_VAR';
export const REMOVE_ALL_SELECTED = 'REMOVE_ALL_SELECTED';
export const CLEAR_LISTS = 'CLEAR_LISTS';

export function switchToProducts() {
	// console.log( "switching to products list" );
	return {
		type: SWITCH_TO_PROD,
	}
}

export function switchToVariations( parent ) {
	// console.log( "switching ot variations list" );
	return {
		type: SWITCH_TO_VAR,
		parent,
	}
}

export function fetchProducts( selected, search, args ) {
	// console.log( "fetching products" );
	return dispatch => {
		dispatch( requestProducts( selected, search, args ) )
		return getProducts( { selected, search, args } )
			// .then( response => response.json())
			.then( response => dispatch( setProducts( response ) ) );
	}
};

function requestProducts( selected, search, args ) {
	return {
		type: GET_PRODUCTS,
		selected,
		search,
		args,
	}
}

export function fetchVariations( parentProd, selected, search, args ) {
	// return {
	// 	type: FETCH_VARIATIONS,
	// 	parent,
	// 	selected,
	// 	search,
	// 	args,
	// };
	// console.log( "fetching variations of parent product:" );
	// console.log( parentProd );
	return dispatch => {
		dispatch( requestVariations( parentProd, selected, search, args ) )
		return getProductVariations( { parentProd, selected, search, args } )
			// .then( response => response.json())
			.then( response => dispatch( setVariations( parentProd, response ) ) );
	}
};

function requestVariations( parent, selected, search, args ) {
	return {
		type: GET_VARIATIONS,
		parent,
		selected,
		search,
		args,
	}
}

export function setSelected( product, single ) {
	// console.log( "called and reached setSelected." );
	return {
		type: SET_SELECTED,
		product,
		single,
	};
};

export function setList( item ) {
	const { products, variations } = state;
	if ( item ) {
		if ( variations[ item.id ] ) {
			var list = variations[ item.id ];
			return {
				type: SET_LIST,
				list
			}
		}
	}
	return {
		type: SET_LIST,
		list,
	};
};

export function setProducts( products ) {
	return {
		type: SET_PRODUCTS,
		products,
	};
};

export function setVariations( parent, variations ) {
	return {
		type: SET_VARIATIONS,
		parent,
		variations,
	};
};

export function removeSelected( product, value ) {
	return {
		type: REMOVE_SELECTED,
		product,
		value,
	};
};

export function removeAllSelected() {
	return {
		type: REMOVE_ALL_SELECTED,
	};
}

function shouldFetchProducts( state, selected, search, args ) {
	const { products, isLoading, error } = state;
	if ( ! products || ( products && products.length < 1 ) ) {
		// console.log( "no products exist, sending!" );
		return true;
	} else if ( isLoading ) {
		return false;
	} else {
		console.error( "there was an error with products fetching." );
		return false;
	}
}

export function fetchProductsIfNeeded( selected, search, args ) {
	// console.log( "in fetchProductsIfNeeded." );
	return ( dispatch, getState ) => {
		if ( shouldFetchProducts( getState(), selected, search, args ) ) {
			// console.log( "sending fetch dispatch." );
			return dispatch( fetchProducts( selected, search, args ) );
		} else if ( getState().products ) {
			// return {
			// 	type: SWITCH_TO_PROD,
				// selected,
				// search,
				// args,
			// }
			return dispatch( switchToProducts() );
		}
	}
}

function shouldFetchVariations( state, parent, selected, search, args ) {
	const { variations, isLoading, error } = state;
	if ( ! variations ) {
		// console.log( "no variations exist, sending!" );
		return true;
	} else if ( ! variations[parent.id] || ( variations[parent.id] && variations[parent.id].length < 1 ) ) {
		// console.log( "no variations for this product exist, sending!" );
		return true;
	} else if ( variations[parent.id] ) {
		return false;
	} else if ( isLoading ) {
		return false;
	} else {
		// console.log( "there was an error with variation fetching." );
		return false;
	}
}

export function fetchVariationsIfNeeded( parent, selected, search, args ) {
	// console.log( "in fetchVariationsIfNeeded." );
	// console.log( parent );
	return ( dispatch, getState ) => {
		const { variations } = getState();
		if ( shouldFetchVariations( getState(), parent, selected, search, args ) ) {
			// console.log( "sending fetch dispatch for variations." );
			return dispatch( fetchVariations( parent, selected, search, args ) );
		} else if ( variations[parent.id] ) {
			// return variations[ parent.id ];
			// return {
			// 	type: GET_VARIATIONS,
			// 	parent,
			// 	selected,
			// 	search,
			// 	args,
			// }
			// console.log( "attempting to switch" );
			return dispatch( switchToVariations( parent ) );
		}
	}
}

export function clearLists() {
	// console.log( "in clear lists." );
	return {
		type: CLEAR_LISTS,
	};
}