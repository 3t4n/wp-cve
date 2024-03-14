wgxpath.install();

function staxWriteCss() {
	'use strict';

	var css = '';
	for (var key in staxResponsive) {
		if (staxResponsive.hasOwnProperty(key)) {
			var el = staxResponsive[key];

			if (el.isXpath === true) {
				css += cssify(key) + ' { opacity: 0; ' +
					'animation-duration: 0.001s;' +
					'-webkit-animation-duration: 0.001s;' +
					'animation-name: staxZoneDetected' + staxResponsive[key].zoneId + ';' +
					'-webkit-animation-name: staxZoneDetected' + staxResponsive[key].zoneId + ';' +
					'}';

			}
		}
	}

	var head = document.head || document.getElementsByTagName('head')[0],
		style = document.createElement('style');
	style.type = 'text/css';

	if (style.styleSheet) {
		// This is required for IE8 and below.
		style.styleSheet.cssText = css;
	} else {
		style.appendChild(document.createTextNode(css));
	}
	head.appendChild(style);
}

function staxListener(event) {
	for (var key in staxResponsive) {
        var el;

        if (staxResponsive[key].isXpath == true) {
            el = document.evaluate(key, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
        } else {
            el = document.querySelector(key);
        }
        if ( el === null ) {
            continue;
        }
        if (el.classList.contains("stax-loaded")) {
            continue;
        }

		if ( event.animationName == "staxZoneDetected" + staxResponsive[key].zoneId ) {
			staxReplaceZone(staxResponsive[key].zoneId);
		}
	}
}


function getBreakpoint() {
	'use strict';

	var windowWidth = window.innerWidth;

	if (windowWidth < 480) {
		return 'xxs';
	} else if (windowWidth >= 480 && windowWidth < 768) {
		return 'xs';
	} else if (windowWidth >= 768 && windowWidth < 991) {
		return 'sm';
	} else if (windowWidth >= 991 && windowWidth < 1200) {
		return 'md';
	} else if (windowWidth >= 1200) {
		return 'lg';
	}
}

var previousDisplay = '';
var parsedZones = [];

function staxReplaceZone(zoneId) {
	'use strict';

	var currentDisplay = getBreakpoint();

	if( currentDisplay === previousDisplay ) {
		if ( parsedZones.indexOf(zoneId) > -1 ) {
			return;
		}
	} else {
		parsedZones = [];
	}

	previousDisplay = currentDisplay;

	for (var key in staxResponsive) {

		if (zoneId !== undefined && staxResponsive[key].zoneId != zoneId ) {
			continue;
		}

		var el;

		if (staxResponsive[key].isXpath == true) {
			el = document.evaluate(key, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
		} else {
			el = document.querySelector(key);
		}
		if ( el === null ) {
			continue;
		}

		var newContent = '';
		var zoneClasses = 'stax-zone';

		if (currentDisplay === "xxs" || currentDisplay === "xs") {
			newContent = staxResponsive[key].mobile;
			zoneClasses += ' stax-zone-mobile';
		}
		else if (currentDisplay === "sm") {
			newContent = staxResponsive[key].tablet;
			zoneClasses += ' stax-zone-tablet';
		}
		else {
			newContent = staxResponsive[key].desktop;
			zoneClasses += ' stax-zone-desktop';
		}

		zoneClasses += ' element-'+ staxResponsive[key].zoneId;
		newContent = '<div class="'+ zoneClasses +'">'+ newContent +'</div>';

		el.innerHTML = newContent; ///it's simple replacement of whole element with contents of str var
		el.classList.add("stax-loaded");

		// Create the event.
		var event = document.createEvent('Event');
		event.initEvent('staxReplace', true, true);
		el.dispatchEvent(event);

		parsedZones.push(zoneId);
	}
}

// JavaScript function for converting simple XPath to CSS selector.
// Ported by Dither from [cssify](https://github.com/santiycr/cssify)
// Example: `cssify('//div[@id="girl"][2]/span[@class="body"]//a[contains(@class, "sexy")]//img[1]')`

var sub_regexes = {
	"tag": "([a-zA-Z][a-zA-Z0-9]{0,10}|\\*)",
	"attribute": "[.a-zA-Z_:][-\\w:.]*(\\(\\))?)",
	"value": "\\s*[\\w/:][-/\\w\\s,:;.]*"
};

var validation_re =
	"(?P<node>" +
	"(" +
	"^id\\([\"\\']?(?P<idvalue>%(value)s)[\"\\']?\\)" +// special case! `id(idValue)`
	"|" +
	"(?P<nav>//?(?:following-sibling::)?)(?P<tag>%(tag)s)" + //  `//div`
	"(\\[(" +
	"(?P<matched>(?P<mattr>@?%(attribute)s=[\"\\'](?P<mvalue>%(value)s))[\"\\']" + // `[@id="well"]` supported and `[text()="yes"]` is not
	"|" +
	"(?P<contained>contains\\((?P<cattr>@?%(attribute)s,\\s*[\"\\'](?P<cvalue>%(value)s)[\"\\']\\))" +// `[contains(@id, "bleh")]` supported and `[contains(text(), "some")]` is not
	")\\])?" +
	"(\\[\\s*(?P<nth>\\d|last\\(\\s*\\))\\s*\\])?" +
	")" +
	")";

for (var prop in sub_regexes)
	validation_re = validation_re.replace(new RegExp('%\\(' + prop + '\\)s', 'gi'), sub_regexes[prop]);
validation_re = validation_re.replace(/\?P<node>|\?P<idvalue>|\?P<nav>|\?P<tag>|\?P<matched>|\?P<mattr>|\?P<mvalue>|\?P<contained>|\?P<cattr>|\?P<cvalue>|\?P<nth>/gi, '');

function XPathException(message) {
	this.message = message;
	this.name = "[XPathException]";
}



function cssify(xpath) {
	'use strict';

	var prog, match, result, nav, tag, attr, nth, nodes, css, node_css = '', csses = [], xindex = 0, position = 0;

	// preparse xpath:
	// `contains(concat(" ", @class, " "), " classname ")` => `@class=classname` => `.classname`
	xpath = xpath.replace(/contains\s*\(\s*concat\(["']\s+["']\s*,\s*@class\s*,\s*["']\s+["']\)\s*,\s*["']\s+([a-zA-Z0-9-_]+)\s+["']\)/gi, '@class="$1"');

	if (typeof xpath == 'undefined' || (
		xpath.replace(/[\s-_=]/g, '') === '' ||
		xpath.length !== xpath.replace(/[-_\w:.]+\(\)\s*=|=\s*[-_\w:.]+\(\)|\sor\s|\sand\s|\[(?:[^\/\]]+[\/\[]\/?.+)+\]|starts-with\(|\[.*last\(\)\s*[-\+<>=].+\]|number\(\)|not\(|count\(|text\(|first\(|normalize-space|[^\/]following-sibling|concat\(|descendant::|parent::|self::|child::|/gi, '').length)) {
		//`number()=` etc or `=normalize-space()` etc, also `a or b` or `a and b` (to fix?) or other unsupported keywords
		throw new XPathException('Invalid or unsupported XPath: ' + xpath);
	}

	var xpatharr = xpath.split('|');
	while (xpatharr[xindex]) {
		prog = new RegExp(validation_re, 'gi');
		css = [];
		while (nodes = prog.exec(xpatharr[xindex])) {
			if (!nodes && position === 0) {
				throw new XPathException('Invalid or unsupported XPath: ' + xpath);
			}
			match = {
				node: nodes[5],
				idvalue: nodes[12] || nodes[3],
				nav: nodes[4],
				tag: nodes[5],
				matched: nodes[7],
				mattr: nodes[10] || nodes[14],
				mvalue: nodes[12] || nodes[16],
				contained: nodes[13],
				cattr: nodes[14],
				cvalue: nodes[16],
				nth: nodes[18]
			};

			if (position != 0 && match['nav']) {
				if (~match['nav'].indexOf('following-sibling::')) nav = ' + ';
				else nav = (match['nav'] == '//') ? ' ' : ' > ';
			} else {
				nav = '';
			}
			tag = (match['tag'] === '*') ? '' : (match['tag'] || '');

			if (match['contained']) {
				if (match['cattr'].indexOf('@') === 0) {
					attr = '[' + match['cattr'].replace(/^@/, '') + '*=' + match['cvalue'] + ']';
				} else { //if(match['cattr'] === 'text()')
					throw new XPathException('Invalid or unsupported XPath attribute: ' + match['cattr']);
				}
			} else if (match['matched']) {
				switch (match['mattr']) {
					case '@id':
						attr = '#' + match['mvalue'].replace(/^\s+|\s+$/, '').replace(/\s/g, '#');
						break;
					case '@class':
						attr = '.' + match['mvalue'].replace(/^\s+|\s+$/, '').replace(/\s/g, '.');
						break;
					case 'text()':
					case '.':
						throw new XPathException('Invalid or unsupported XPath attribute: ' + match['mattr']);
					default:
						if (match['mattr'].indexOf('@') !== 0) {
							throw new XPathException('Invalid or unsupported XPath attribute: ' + match['mattr']);
						}
						if (match['mvalue'].indexOf(' ') !== -1) {
							match['mvalue'] = '\"' + match['mvalue'].replace(/^\s+|\s+$/, '') + '\"';
						}
						attr = '[' + match['mattr'].replace('@', '') + '=' + match['mvalue'] + ']';
						break;
				}
			} else if (match['idvalue'])
				attr = '#' + match['idvalue'].replace(/\s/, '#');
			else
				attr = '';

			if (match['nth']) {
				if (match['nth'].indexOf('last') === -1) {
					if (isNaN(parseInt(match['nth'], 10))) {
						throw new XPathException('Invalid or unsupported XPath attribute: ' + match['nth']);
					}
					nth = parseInt(match['nth'], 10) !== 1 ? ':nth-of-type(' + match['nth'] + ')' : ':first-of-type';
				} else {
					nth = ':last-of-type';
				}
			} else {
				nth = '';
			}
			node_css = nav + tag + attr + nth;

			css.push(node_css);
			position++;
		} //while(nodes

		result = css.join('');
		if (result === '') {
			throw new XPathException('Invalid or unsupported XPath: ' + match['node']);
		}
		csses.push(result);
		xindex++;

	} //while(xpatharr

	return csses.join(', ');
}

