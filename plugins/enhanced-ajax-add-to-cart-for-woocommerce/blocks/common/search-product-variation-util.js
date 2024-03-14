/**
 * External dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';
import { getSetting } from '@woocommerce/settings';
import { flatten, uniqBy } from 'lodash';
import { createTitle } from './formatting/title';
import { createValidHtml } from './formatting/html';

const getProductVariationsRequests = ( {
	parentProd = [],
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
		addQueryArgs( '/wc/v3/products/' + parentProd.id + '/variations', { ...defaultArgs, ...queryArgs } ),
	];

	// If we have a large catalog, we might not get all selected products in the first page.
	if ( getSetting( 'isLargeCatalog' ) && selected.length ) {
		requests.push(
				addQueryArgs( '/wc/v3/products/' + parentProd.id + '/variations', {
					status: 'publish',
					include: selected,
				} )
			)
	}

	return requests;
};

export const getProductVariations = ( {
	parentProd = [],
	selected = [],
	search = '',
	queryArgs = [],
} ) => {
	const requests = getProductVariationsRequests( { parentProd, selected, search, queryArgs } );

	return Promise.all( requests.map( ( path ) => apiFetch( { path } ) ) )
		.then( ( data ) => {
			const variations = uniqBy( flatten( data ), 'id' );
			const list = variations.map( ( variation ) => ( {
				name: createTitle( { product: parentProd, variation: variation } ),
				id: variation.id,
				full: createTitle( { product: parentProd, variation: variation, titleType: 'full' } ),
				att: createTitle( { product: parentProd, variation: variation, titleType: 'att' } ),
				base: createTitle( { product: parentProd, variation: variation, titleType: 'base' } ),
				price: variation.price,
				parent_id: parentProd.id,
				type: 'variation',
				short_description: createValidHtml( { inputHtml: parentProd.short_description } ),
				attributes: variation.attributes,
				images: (parentProd.images ? parentProd.images.map(image => (image.id) ) : []),
			} ) );
			return list;
		} )
		.catch( ( e ) => {
			throw e;
		} );
};
