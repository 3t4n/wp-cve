const isLodash = () => {
	let isLodashLib = false;

	// If _ is defined and the function _.forEach exists then we know underscore OR lodash are in place
	if ( 'undefined' != typeof _ && 'function' == typeof _.forEach ) {

		// A small sample of some of the functions that exist in lodash but not underscore
		const funcs = [ 'get', 'set', 'at', 'cloneDeep' ];

		// Simplest if assume exists to start
		isLodashLib = true;

		funcs.forEach( function( func ) {

			// If just one of the functions do not exist, then not lodash
			isLodashLib = 'function' != typeof _[func] ? false : isLodashLib;
		});
	}

	if ( isLodashLib ) {

		// We know that lodash is loaded in the _ variable
		return true;
	} else {

		// We know that lodash is NOT loaded
		return false;
	}
};

wp.domReady( () => {
	if ( isLodash() ) {
		_.noConflict();
	}
});
