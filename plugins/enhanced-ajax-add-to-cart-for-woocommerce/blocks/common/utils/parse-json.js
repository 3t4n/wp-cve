/**
 * External dependencies
 */
// import { translate as __ } from 'i18n-calypso';

/**
 * Parses a server response into a JSON object, providing a human-readable error if the
 * JSON is invalid.
 *
 * @param {Response} response Response object, as obtained from a fetch call.
 * @returns {Promise} Resolves with the parsed JSON object,
 * or rejects with a human-readable error if the payload is not valid JSON
 */
export default ( response ) => {
	if ( ! response instanceof Response ) {
		console.error( 'Invalid Response object' ); // eslint-disable-line no-console
		return Promise.reject( 'Unexpected server error.' );
	}
	return response.text().then( ( text ) => {
		try {
			return JSON.parse( text );
		} catch ( error ) {
			if ( global.EAA2C && global.EAA2C.debug && 'false' !== global.EAA2C.debug ) {
				console.error( "Error parsing the JSON passed from the response." ); // eslint-disable-line no-console
				console.error( error ); // eslint-disable-line no-console
				console.error( text ); // eslint-disable-line no-console
			}
			throw  'Unexpected server error.' ;
		}
	} );
};