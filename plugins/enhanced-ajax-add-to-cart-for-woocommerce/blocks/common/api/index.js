/**
 * Internal dependencies
 */
import request from './request';

const namespace = 'eaa2c/v1/';
const wc_namespace = 'wc/v3/';

export const products = () => `${ wc_namespace }products`
// export const productImage = () => `${ wc_namespace }product-image/${ product.ID }?type=${ attribute.image }`
export const variations = () => `${ wc_namespace }products/${ product.ID }/variations`
export const settings = () => `${ namespace }settings`

const handleError = ( jsonError ) => {
	if ( jsonError.data.message ) {
		throw jsonError.data.message;
	}

	throw JSON.stringify( jsonError );
};

export const post = ( url, data ) => request().post( url, data ).catch( handleError );

export const get = ( url ) => request().get( url ).catch( handleError );

export const createGetUrlWithNonce = ( url, queryString ) => request().createGetUrlWithNonce( url, queryString );