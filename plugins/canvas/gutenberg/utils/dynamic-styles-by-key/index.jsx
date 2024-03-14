/**
 * Generating dynamic styles by key.
 *
 * @param {string} suffix suffix
 * @param {string} key    key
 * @param {string} styles styles
 */
export default function dynamicStylesBYkey( suffix, key, styles ) {
	if ( ! key || ! styles || 'undefined' === typeof( key ) ) {
		return;
	}

	var styleID = `dynamic-css-${suffix}-${key}`;

	if ( document.getElementById( styleID ) ) {

		document.getElementById( styleID ).innerHTML = styles;
	} else {
		// Get the head element.
		var head = document.head || document.getElementsByTagName('head')[0];

		// Create a new style node.
		var style = document.createElement( 'style' );

		style.id = styleID;

		// Set type attribute for the style node.
		style.type = 'text/css';

		// Append the css rules to the style node.
		style.appendChild( document.createTextNode( styles ) );

		// Append the style node to the head of the page.
		head.appendChild(style);
	}
}
