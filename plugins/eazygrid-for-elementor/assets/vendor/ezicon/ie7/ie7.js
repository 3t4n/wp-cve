/* To avoid CSS expressions while still supporting IE 7 and IE 6, use this script */
/* The script tag referencing this file must be placed before the ending body tag. */

/* Use conditional comments in order to target IE 7 and older:
	<!--[if lt IE 8]><!-->
	<script src="ie7/ie7.js"></script>
	<!--<![endif]-->
*/

(function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'ezicon\'">' + entity + '</span>' + html;
	}
	var icons = {
		'ezicon-eazyplugins': '&#xe941;',
		'ezicon-alphabetic': '&#xe937;',
		'ezicon-check-range': '&#xe938;',
		'ezicon-date-range': '&#xe939;',
		'ezicon-date': '&#xe93a;',
		'ezicon-search-Input': '&#xe93b;',
		'ezicon-range-slider': '&#xe93c;',
		'ezicon-radio': '&#xe93d;',
		'ezicon-min-max': '&#xe93e;',
		'ezicon-image-select': '&#xe93f;',
		'ezicon-hierarchy': '&#xe940;',
		'ezicon-button-choose': '&#xe92f;',
		'ezicon-checkbox': '&#xe930;',
		'ezicon-text-choose': '&#xe931;',
		'ezicon-sorting': '&#xe932;',
		'ezicon-select': '&#xe933;',
		'ezicon-rating': '&#xe934;',
		'ezicon-grid': '&#xe935;',
		'ezicon-color-swatches': '&#xe936;',
		'ezicon-post-even': '&#xe91e;',
		'ezicon-image-gallery': '&#xe91f;',
		'ezicon-image-carousel': '&#xe920;',
		'ezicon-post-carousel': '&#xe921;',
		'ezicon-post-creative': '&#xe922;',
		'ezicon-post-smart': '&#xe923;',
		'ezicon-post-masonry': '&#xe924;',
		'ezicon-post-metro': '&#xe925;',
		'ezicon-product-creative': '&#xe926;',
		'ezicon-product-even': '&#xe927;',
		'ezicon-product-carousel': '&#xe928;',
		'ezicon-product-metro': '&#xe929;',
		'ezicon-image-creative': '&#xe92a;',
		'ezicon-image-even': '&#xe92b;',
		'ezicon-image-masonry': '&#xe92c;',
		'ezicon-image-justified': '&#xe92d;',
		'ezicon-image-metro': '&#xe92e;',
		'ezicon-play-1': '&#xe915;',
		'ezicon-play-1-alt': '&#xe916;',
		'ezicon-play-2': '&#xe917;',
		'ezicon-play-3': '&#xe918;',
		'ezicon-play-4': '&#xe919;',
		'ezicon-play-5': '&#xe91a;',
		'ezicon-play-6': '&#xe91b;',
		'ezicon-play-7': '&#xe91c;',
		'ezicon-play-8': '&#xe91d;',
		'ezicon-handler': '&#xe914;',
		'ezicon-arrow-right': '&#xe900;',
		'ezicon-arrow-down': '&#xe901;',
		'ezicon-arrow-left': '&#xe902;',
		'ezicon-arrow-up': '&#xe903;',
		'ezicon-camera': '&#xe904;',
		'ezicon-copy': '&#xe905;',
		'ezicon-cross': '&#xe906;',
		'ezicon-desktop-fill': '&#xe907;',
		'ezicon-desktop-outline': '&#xe908;',
		'ezicon-eazygrid': '&#xe909;',
		'ezicon-edit-pen': '&#xe90a;',
		'ezicon-hamburger': '&#xe90b;',
		'ezicon-link-broken': '&#xe90c;',
		'ezicon-link': '&#xe90d;',
		'ezicon-mobile-fill': '&#xe90e;',
		'ezicon-mobile-outline': '&#xe90f;',
		'ezicon-shortcode': '&#xe910;',
		'ezicon-tablet-fill': '&#xe911;',
		'ezicon-tablet-outline': '&#xe912;',
		'ezicon-template': '&#xe913;',
		'0': 0
		},
		els = document.getElementsByTagName('*'),
		i, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		c = el.className;
		c = c.match(/ezicon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
}());
