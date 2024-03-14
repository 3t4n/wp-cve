/**
 * External dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';
import { getSetting } from '@woocommerce/settings';
import { flatten, uniqBy } from 'lodash';
import { createTitle } from './formatting/title';
import { createValidHtml } from './formatting/html';

const getProductsRequests = ( {
	selected = [],
	search = '',
	queryArgs = [],
} ) => {
	const defaultArgs = {
		per_page: getSetting( 'isLargeCatalog' ) ? 100 : -1,
		catalog_visibility: 'any',
		status: 'publish',
		search,
		orderby: 'title',
		order: 'asc',
	};
	const requests = [
		addQueryArgs( '/wc/v3/products', { ...defaultArgs, ...queryArgs } ),
	];

	// If we have a large catalog, we might not get all selected products in the first page.
	if ( getSetting( 'isLargeCatalog' ) && selected.length ) {
		requests.push(
			addQueryArgs( '/wc/v3/products', {
				status: 'publish',
				include: selected,
			} )
		)
	}

	return requests;
};
export const getProducts = ( {
	selected = [],
	search = '',
	queryArgs = [],
} ) => {
	const requests = getProductsRequests( { selected, search, queryArgs } );

	return Promise.all( requests.map( ( path ) => apiFetch( { path } ) ) )
		.then( ( data ) => {
			const products = uniqBy( flatten( data ), 'id' );
			var title = '';
			const list = products.map( ( product ) => ( {
				name: title = createTitle( { product: product, variation: [], titleType: 'base' } ),
				id: product.id,
				full: title,
				base: title,
				att: title,
				price: product.price,
				type: product.type,
				short_description: createValidHtml( { inputHtml: product.short_description } ),
				children: product.variations,
				images: ( product.images ? product.images.map( image => (image.id) ) : [] ),
			} ) );
			return list;
		} )
		.catch( ( e ) => {
			throw e;
		} );
};
