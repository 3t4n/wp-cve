/**
 * Javascript for ajax like request to update the weather location
 * and corresponding data on the fly
 *
 * @package wp-forecast
 */

/* load widget for the first time on this page */
function wpf_load() {
	// get cookie.
	if (document.cookie) {
		a          = document.cookie;
		cookiename = a.substring( 0, a.indexOf( '=' ) );
		if (a.indexOf( ';' ) != -1) {
			cookiewert = a.substring( a.indexOf( '=' ) + 1, a.indexOf( ';' ) );
		} else {
			cookiewert = a.substr( a.indexOf( '=' ) + 1, a.length );
		}
		// set selected location.
		if (cookiename == 'location') {
			document.getElementById( "wpf_selector" ).value = cookiewert;
		}
	}
	// update widget.
	wpf_update();
}
/* get the data for the new location */
function wpf_update() {
	var newloc   = document.getElementById( "wpf_selector" ).value;
	var siteuri  = document.getElementById( "wpf_selector_site" ).value;
	var language = document.getElementById( "wpf_language" ).value;

	// set cookie with newloc.
	var expire      = new Date();
	expire          = new Date( expire.getTime() + 1000 * 60 * 60 * 24 * 365 );
	document.cookie = escape( "location=" + newloc ) + '; expires=' + expire.toGMTString() + ';';

	jQuery.get(
		siteuri + "/wp-forecast-direct",
		{
			wpfcid: newloc,
			header: "0",
			selector: "1",
			language_override: language
		},
		function(data) {
			jQuery( "div#wp-forecastA" ).html( data );
		}
	);
}

/* javascript to rebuild the onLoad event for triggering
   the first wpf_update call */

// create onDomReady Event.
window.onDomReady = initReady;

// Initialize event depending on browser.
function initReady(fn) {
	// W3C-compliant browser.
	if (document.addEventListener) {
		document.addEventListener( "DOMContentLoaded", fn, false );
	} else {
		// IE.
		document.onreadystatechange = function() {
			readyState( fn );
		};
	}
}

// IE execute function.
function readyState(func) {
	// DOM is ready.
	if (document.readyState == "interactive" || document.readyState == "complete") {
		func();
	}
}
