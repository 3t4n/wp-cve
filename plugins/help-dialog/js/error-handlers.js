/** This file will show notice about js errors */
window.ephdErrorsList = [];
var previousErrorHandler = window.onerror;
var jqueryChecked = 0;

// create new errors handler - fires when an error occurs during object loading
window.onerror = function( errorMsg, url, lineNumber, columnNumber, errorObject ) {

	/* Firefox bug */
	if ( errorObject.name != 'NS_ERROR_FAILURE' ) {
		ephdErrorsList.push({ 'msg' : errorMsg, 'url' : url });
	}

	if ( typeof jQuery !== 'undefined' ) {
		jQuery(document).trigger( 'ephd_js_error', [errorMsg, url, lineNumber, columnNumber, errorObject] );
	}

	// run previous Window errors handler possibly used by other plugins if it exists
	if ( previousErrorHandler ) {
		return previousErrorHandler( errorMsg, url, lineNumber, columnNumber, errorObject );
	}

	// run default handler 
	return false;
};