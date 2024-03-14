/*
 * 	laza.util - miscellaneous utility functions and prototype extensions
 *	(c) Laszlo Molnar, 2020
 *	Licensed under Creative Commons Attribution-NonCommercial-ShareAlike 
 *	<http://creativecommons.org/licenses/by-nc-sa/3.0/>
 *
 *	requires: jQuery
 */
;

// Ensuring no 'console is undefined' errors happen

window.console = window.console || (function(){
		return {
			log: function(msg) {}
		};
	})();


/*
 *	Extending prototypes
 */
if (!String.prototype.hasOwnProperty('trim')) {
	
	String.wsp = [];
	String.wsp[0x0009] = true;
	String.wsp[0x000a] = true;
	String.wsp[0x000b] = true;
	String.wsp[0x000c] = true;
	String.wsp[0x000d] = true;
	String.wsp[0x0020] = true;
	String.wsp[0x0085] = true;
	String.wsp[0x00a0] = true;
	String.wsp[0x1680] = true;
	String.wsp[0x180e] = true;
	String.wsp[0x2000] = true;
	String.wsp[0x2001] = true;
	String.wsp[0x2002] = true;
	String.wsp[0x2003] = true;
	String.wsp[0x2004] = true;
	String.wsp[0x2005] = true;
	String.wsp[0x2006] = true;
	String.wsp[0x2007] = true;
	String.wsp[0x2008] = true;
	String.wsp[0x2009] = true;
	String.wsp[0x200a] = true;
	String.wsp[0x200b] = true;
	String.wsp[0x2028] = true;
	String.wsp[0x2029] = true;
	String.wsp[0x202f] = true;
	String.wsp[0x205f] = true;
	String.wsp[0x3000] = true;
	
	String.prototype.trim = function() { 
			var str = this + '', 
				j = str.length;
			if (j) {
				var ws = String.wsp, 
					i = 0;
				--j;
				while (j >= 0 && ws[str.charCodeAt(j)]) {
					--j;
				}
				++j;
				while (i < j && ws[str.charCodeAt(i)]) { 
					++i; 
				}
				str = str.substring(i, j);
			}
			return str;
		};
}

if (!String.prototype.hasOwnProperty('trunc')) {

	String.prototype.trunc = function( n ) {
			var t = this + '';
			
			if (t.length <= n) {
				return t.toString();
			}
			
			var s = t.substring(0, n - 1), 
				i = s.lastIndexOf(' ');
				
			return ((i > 6 && (s.length - i) < 20)? s.substring(0, i) : s) + '...';
		};
}

if (!String.prototype.hasOwnProperty('startsWith')) {

	String.prototype.startsWith = function( s ) {
			return (this + '').indexOf( s ) === 0;
		};
}

if (!String.prototype.hasOwnProperty('endsWith')) {

	String.prototype.endsWith = function( s ) {
			return (this + '').substring(this.length - s.length) === s;
		};
}

	String.prototype.capitalize = function() {
			return this.charAt(0).toUpperCase() + this.slice(1);
		};
	
	String.prototype.unCamelCase = function() {
			return this.replace(/([a-z])([A-Z])/g, "$1-$2").toLowerCase();
		};
	
	String.prototype.getExt = function() {
			var t = this + '', 
				i = t.lastIndexOf('.');
			return (i <= 0 || i >= t.length - 1)? '' : t.substring(i + 1);
		};
		
	String.prototype.stripExt = function() {
			var t = this + '',
				i = t.lastIndexOf('.');
			return (i <= 0 || i > t.length - 1)? t : t.substring(0, i);
		};
	
	String.prototype.hasExt = function(x) {
			var t = (this + ''), 
				i = t.lastIndexOf('.');
			if (i >= 0) {
				t = t.substring(i + 1).toLowerCase();
				return (x + ',').indexOf(t + ',') >= 0;
			}
			return !1;
		};
	
	String.prototype.replaceExt = function( s ) {
			var t = this + '', 
				i = t.lastIndexOf('.');
			return (i <= 0)? t : (t.substring(0, i + 1) + s);  
		};
	
	String.prototype.fixExtension = function() {
			return (this + '').replace(/.gif$/gi, '.png').replace(/.tif+$/gi, '.jpg');
		};
	
	String.prototype.getDir = function() {
			var u = (this + '').split('#')[0];
			return u.substring(0, u.lastIndexOf('/') + 1);
		};
	
	String.prototype.getFile = function() {
			var u = (this + '').split('#')[0];
			return u.substring(u.lastIndexOf('/') + 1);
		};
	
	String.prototype.getRelpath = function(level) {
			var t = (this + ''), 
				i = t.lastIndexOf('#');
			if (i === -1) {
				i = t.length - 1;
			} else {
				i--;
			}
			for (; i >= 0; i--) {
				if (t[i] === '/' && (level--) === 0)
					break;
			}
			return t.substring(i + 1);
		};
	
	String.prototype.fixUrl = function() {
			var i, 
				j, 
				t = this + '';
			while ((i = t.indexOf('../')) > 0) {
				if (i === 1 || (j = t.lastIndexOf('/', i - 2)) === -1) {
					return t.substring(i + 3);
				}
				t = t.substring(0, j) + t.substring(i + 2);
			}
			return t;
		};
	
	String.prototype.fullUrl = function() {
			var t = this + '';
			if (!t.match(/^(http|ftp|file)/)) {
				t = window.location.href.getDir() + t;
			}
			return t.fixUrl();
		};
	
	String.prototype.cleanupHTML = function() {
			var htmlregex = [
					[ /<(b|h)r\/?>/gi, '\n' ],
					[ /\&amp;/g, '&' ],
					[ /\&nbsp;/g, ' ' ],
					[ /\&lt;/g, '<' ],
					[ /\&gt;/g, '>' ],
					[ /\&(m|n)dash;/g , '-' ],
					[ /\&apos;/g, '\'' ],
					[ /\&quot;/g, '"' ]
				],
				t = this + '';
				
			for (var i = htmlregex.length - 1; i >= 0; i--) {
				t = t.replace(htmlregex[i][0], htmlregex[i][1]);
			}
			
			return t.replace; 
		};
	
	String.prototype.stripHTML = function(format) { 
			var s = (this + '');
			
			if (format) {
				s = s.cleanupHTML();
			}
			
			return s.replace(/<\/?[^>]+>/g, ' '); 
		};
	
	String.prototype.stripQuote = function() {
			return (this + '').replace(/\"/gi, '&quot;');
		};
	
	String.prototype.appendSep = function(s, sep) {
			return (this.length? (this + (sep || ' &middot; ')) : '') + s; 
		};
	
	String.prototype.rgb2hex = function() {
			var t = this + '';
			if (t.charAt(0) === '#' || t === 'transparent') {
				return t;
			}
			var n, r = t.match(/\d+/g), h = '';
			if ( r ) {
				for (var i = 0; i < r.length && i < 3; ++i) {
					n = parseInt( r[i], 10 ).toString(16);
					h += ((n.length < 2)? '0' : '') + n;
				}
				return '#' + h;
			}
			return 'transparent';
		};
	
	String.prototype.template = function(s) {
			if (typeof s === 'undefined' || !this) {
				return this;
			}
			if (!isNaN(parseFloat(s)) && isFinite(s)) {
				s = s + '';
			}
			var t = this + '';
			if (s.constructor === Array) {
				for (var i = 0; i < s.length; ++i) {
					t = t.replace(new RegExp('\\{' + i + '\\}', 'gi'), s[i]);
				}
			} else {
				t = t.replace( /\{0\}/gi, s );
			}
			return t;
		};
	
	String.prototype.getSearchTerms = function() {
			var t = this + '';
			
			if (t.indexOf('"') === -1) {
				return t.split(' ');
			} else {
				var a = [],
					i;
			
				do {
					if ((i = t.indexOf('"')) > 0) {
						a.push.apply(a, t.substring(0, i).split(' '));
					}
					t = t.substring(i + 1);
					i = t.indexOf('"');
					if (i < 0) {
						a.push(t);
						break;
					}
					a.push(t.substring(0, i));
					t = t.substring(i + 1);
					
				} while (t.length);
			
				return a;
			}
		};
	
	// Shorten
	String.prototype.shorten = function(max, forceStrip) {
			if (!this || !this.length) {
				return this;
			}
			var t = this + '',
				i,
				max = max || 160;
			
			if (forceStrip) {
				t = stripHTML(t);
			}
			
			if (t.length > max) {
				if (t.indexOf("<") >= 0) {
					t = t.stripHTML();
					if (t.length <= max) {
						return t;
					}
				}
				
				if ((i = t.lastIndexOf(' ', max)) < (max / 2)) {
					i = max;
				}
				
				return t.substring(0, i) + '&hellip;';
			}
			
			return t;
		};
		
	// Creating hash code
	String.prototype.hashCode = function() {
			for (var h = 0, i = 0, l = this.length; i < l; ++i) {
				h = (h << 5) - h + this.charCodeAt(i);
				h &= h;
			}
			return h;
		};
	
	// > Min && < Max
	Math.minMax = function(a, b, c) {
			b = (isNaN(b))? parseFloat(b) : b;
			return  (b < a)? a : ((b > c)? c : b); 
		};
		
	// Gets range
	Math.getRange = function(a, r) {
			if (r.constructor !== Array) {
				return (a >= r)? 1 : 0;
			}
			if (r.length === 1) {
				return (a >= r[0])? 1 : 0;
			}
			if (a < r[0]) {
				return 0;
			}
			for (var i = 1; i < r.length; i++) {
				if (a >= r[i - 1] && a < r[i]) {
					break;
				}
			}
			return i;
		};

/*
 *	New functions and variables for the global context - no jQuery dependency
 */

var encodeJ = [];
	encodeJ[33] 	= '%21'; 	// !
	encodeJ[35] 	= '%23';	// #
	encodeJ[36] 	= '%24';	// $
	encodeJ[38] 	= '%26';	// &
	encodeJ[39] 	= '%27';	// '
	encodeJ[40] 	= '%28';	// (
	encodeJ[41] 	= '%29';	// )
	encodeJ[43] 	= '%2B';	// +
	encodeJ[44] 	= '%2C';	// ,
	encodeJ[59] 	= '%3B';	// ;
	encodeJ[60] 	= '%3C';	// <
	encodeJ[61] 	= '%3D';	// =
	encodeJ[62] 	= '%3E';	// >
	encodeJ[63] 	= '%3F';	// ?
	encodeJ[64] 	= '%40';	// @
	encodeJ[123] 	= '%7B';	// {
	encodeJ[124]	= '%7C';	// |
	encodeJ[125]	= '%7D';	// }
	
	transCodeJ = [];
	transCodeJ[33] 	= '%21'; 	// !
	transCodeJ[39] 	= '%27';	// '
	transCodeJ[40] 	= '%28';	// (
	transCodeJ[41] 	= '%29';	// )
	

var	UNDEF 	= 'undefined',
	LOCAL 	= document.location.protocol === 'file:',
	
	ONEDAY_S	= 60 * 60 * 24,
	ONEDAY_MS	= 60 * 60 * 24 * 1000,
	
	STARS		= '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80" height="16" viewBox="0 0 80 16" xml:space="preserve"><path d="M12,10.094l0.938,5.5L8,13l-4.938,2.594L4,10.094L0,6.219l5.531-0.813l2.469-5l2.469,5L16,6.219L12,10.094z"/><path d="M28,10.094l0.938,5.5L24,13l-4.938,2.594l0.938-5.5l-4-3.875l5.531-0.813l2.469-5l2.469,5L32,6.219L28,10.094z"/><path d="M44,10.094l0.938,5.5L40,13l-4.938,2.594l0.938-5.5l-4-3.875l5.531-0.813l2.469-5l2.469,5L48,6.219L44,10.094z"/><path d="M60,10.094l0.938,5.5L56,13l-4.938,2.594l0.938-5.5l-4-3.875l5.531-0.813l2.469-5l2.469,5L64,6.219L60,10.094z"/><path d="M76,10.094l0.938,5.5L72,13l-4.938,2.594l0.938-5.5l-4-3.875l5.531-0.813l2.469-5l2.469,5L80,6.219L76,10.094z"/></svg>';
	
	DIR_PATH = (function() {
			var p = window.location.pathname,
				level = document.getElementsByTagName('html')[0].getAttribute('data-level') || 0;
			
			do {
				p = p.substring(0, p.lastIndexOf('/'));
			} while (level--) 
			
			return p + '/';
		})(),
	
	/*
	 *	Translate function
	 *	returns a translated key, or the default value (if exists), or the key itself de-camelcased
	 */
	
	translate = function(key, def) {
		
			key = key.trim();
			
			if (typeof Texts !== 'undefined') {
				if (Texts.hasOwnProperty(key)) {
					return Texts[key];
				}
			}
			
			if (typeof def !== 'undefined') {
				// Using the default
				if (DEBUG && console) {
					console.log('Using default translation: '+key+'='+def);
				}
				return def;
			}
			
			if (DEBUG && console) {
				console.log('Missing translation: '+key);
			}
			
			var s = key.replace(/([A-Z])/g, ' $1').toLowerCase();
			
			s[0] = s.charAt(0).toUpperCase();
			return s;
		},
	
	/* 
	 *	Simpler method 
	 *	text = getKeys('key1,key2,key3', [defaults])
	 */
	
	getKeys = function(keys, def) {
		
			var t = {}, i, k = keys.split(','), l = k.length;
			
			for (i = 0; i < l; i++) {
				t[k[i]] = translate(k[i], def[k]);
			}
			
			return t;
		},
	
	/*
	 *	Finds translation for each key in def Object
	 *	def should contain only elements that need translation
	 */
	
	getTranslations = function(def) {
		
			var t = {}, k;
			
			for (k in def) {
				if (typeof def[k] === 'object') {
					t[k] = getTranslations(def[k]);
				} else {
					t[k] = translate(k, def[k]);
				}
			}
			
			return t;
		},
	
	// Check if object is empty
	
	isEmpty = function(o) {
	
			if (o == null) {
				return true;
			};
			return (Object.getOwnPropertyNames(o)).length === 0;
		},
	
	// String value of an object
	
	stringVal = function(o) {
		
			switch (typeof o) {
				case 'string':
					return o;
				case 'number':
					return o + '';
				case 'array':
					return o.toString();
				case 'object':
					return JSON.stringify(o);
			}
			return '';
		},
			
	// Pure JS extend function
	
	extend = function() {
		
			if (arguments.length < 2) {
				return arguments[0] || {};
			}
			
			var r = arguments[0];
			
			for (var i = 1; i < arguments.length; i++) {
				for (var key in arguments[i]) {
					if (arguments[i].hasOwnProperty(key)) {
						r[key] = arguments[i][key];
					}
				}
			}
			
			return r;
		},

	// Push all elements of an array into another array
	
	pushAll = function(a, b) {
			if (a instanceof Array) {
				if (b instanceof Array) {
					for (var i = 0, l = b.length; i < l; i++) {
						a.push(b[i]);
					}
				} else {
					a.push(b);
				}
			}
		},
		
	// Push only elements of an array into another array which are absent
	
	pushNew = function(a, b) {
			if (a instanceof Array) {
				if (b instanceof Array) {
					for (var i = 0, l = b.length; i < l; i++) {
						if (!a.includes(b[i])) {
							a.push(b[i]);
						}
					}
				} else {
					if (!a.includes(b)) {
						a.push(b);
					}
				}
			}
		},
		
	// Getting computed style
	
	getStyle = function(el, style) {
		
			if (el instanceof Element) {
				if (document.defaultView && document.defaultView.getComputedStyle) {
					return document.defaultView.getComputedStyle(el, '').getPropertyValue(style.unCamelCase());
				} else if (el.currentStyle) {
					return e.currentStyle[style];
				}
			}
			
			return null;
		},
		
	// Testing scrollbar width for mobile detection
	
	scrollbarWidth = function() {
		
			var div = document.createElement("div"),
				sw = 0;
				
			div.style.cssText = 'width:100px;height:100px;overflow:scroll !important;position:absolute;top:-9999px';
			if (document.body) {
				document.body.appendChild(div);
				sw = div.offsetWidth - div.clientWidth;
				document.body.removeChild(div);
			}
			
			return sw;
		},
		
	// Test for touch support
	
	isTouchEnabled = function() {
		
			if (/Trident/.test(navigator.userAgent)) {
				return (typeof navigator['maxTouchPoints'] !== 'undefined' && navigator.maxTouchPoints); // || /IEMobile/.test(navigator.userAgent);
			} else if (/Edge/.test(navigator.userAgent)) {
				return (scrollbarWidth() == 0);
			} else if (/(Chrome|CriOS)/.test(navigator.userAgent)) {
				return /Mobile/.test(navigator.userAgent) || 'ontouchstart' in window; 
			}
			return 'ontouchstart' in window;
		},
	
	// Touch event naming
	
	getTouch = function() {
	
			if (/Trident|Edge/.test(navigator.userAgent)) {
				// Setting MS events
				if (window.navigator.pointerEnabled) {
					return {
						'START': 	'pointerdown',
						'MOVE':		'pointermove',
						'END':		'pointerup',
						'CANCEL':	'pointercancel'
					};
				}
				return {
					'START': 	'MSPointerDown',
					'MOVE':		'MSPointerMove',
					'END':		'MSPointerUp',
					'CANCEL':	'MSPointerCancel'
				};
			}
		
			return {
				'START': 	'touchstart',
				'MOVE':		'touchmove',
				'END':		'touchend',
				'CANCEL':	'touchcancel'
			};
		},
	
	// Test for localStorage
	
	hasLocalStorage = function() {
			try {
				localStorage.setItem('_t', 'undefined');
				localStorage.removeItem('_t');
				return true;
			} catch(e) {
				return false;
			}
		},
	
	// Test for history
	
	hasHistory = function() {
			// Taken from Modernizr 3.1
			var ua = navigator.userAgent;
	
			if ((ua.indexOf('Android 2.') !== -1 ||
				(ua.indexOf('Android 4.0') !== -1)) &&
				ua.indexOf('Mobile Safari') !== -1 &&
				ua.indexOf('Chrome') === -1 &&
				ua.indexOf('Windows Phone') === -1) {
			  return false;
			}
	
			return (window.history && 'pushState' in window.history);
		},
	
	// Test for PDF viewer
	// from: https://github.com/featurist/browser-pdf-support/blob/master/index.js
	
	hasPDFViewer = function() {
			var hasAcrobatInstalled = function() {
						var getActiveXObject = function(name) {
								try { return new ActiveXObject(name); } catch(e) {}
							};
		
						return getActiveXObject('AcroPDF.PDF') || getActiveXObject('PDF.PdfCtrl');
					},

				isIos = function() {
						return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream
					};
 
			return navigator.mimeTypes['application/pdf'] || hasAcrobatInstalled() || isIos();
		},

	
	// Adding class without jQuery (can be used anytime)
	
	addClass = function(el, className) {
			if (el.classList) {
				el.classList.add(className);
			} else {
				el.className += ' ' + className;
			}
		},

	// Returns browser vendor
	
	getVendor = function() {
			var ua = navigator.userAgent;
			/*
				PC:
					IE 8: 		"Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0)"
					IE 9: 		"Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0; .NET CLR 2.0.50727; SLCC2; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; Tablet PC 2.0; .NET4.0C)"
					IE 10: 		"Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; WOW64; Trident/6.0; .NET4.0E; .NET4.0C)"
					Edge:		"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299"
					Opera 12: 	"Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.15"
					Firefox 21: "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20100101 Firefox/21.0"
					Chrome 27: 	"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.94 Safari/537.36"
				Mac:
					Chrome 27: 	"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.93 Safari/537.36"
					Firefox 21: "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:21.0) Gecko/20100101 Firefox/21.0"
					Safari 6: 	"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/536.26.14 (KHTML, like Gecko) Version/6.0.1 Safari/536.26.14"
			 */
			if (ua.indexOf('Trident') > 0 || ua.indexOf('Edge') > 0) {
				return 'ms';
			} else if (ua.indexOf('AppleWebKit') > 0) {
				return 'webkit';
			} else if (ua.indexOf('Gecko') > 0) {
				return 'moz';
			} else if (ua.indexOf('Presto') > 0) {
				return 'o';
			} else if (ua.indexOf('Blink') > 0) {
				return 'webkit';
			}
			return '';		
		},
	
	/*
	 *	New constants
	 */

	NOLINK 			= 'javascript:void(0)',
	LOCALSTORAGE 	= hasLocalStorage(),
	HISTORY			= hasHistory(),
	VEND 			= getVendor(),
	BACKFACEBUG		= (navigator.userAgent.indexOf('Edge') > 0) && (parseInt(navigator.userAgent.match(/Edge\/(\d+\.\d+)/)[1]) <= 16),
	FITCONTENT		= (function() {
							if (/Trident/.test(navigator.userAgent) || /Edge/.test(navigator.userAgent)) {
								document.getElementsByTagName('html')[0].classList.add('no-fitcontent');
								return false;
							}
							document.getElementsByTagName('html')[0].classList.add('fitcontent');
							return true;
						})(),
	ISIOSDEVICE		= (navigator.userAgent.match(/ipad|iphone|ipod/i)),
	TOUCH			= getTouch(),
	TOUCHENABLED	= isTouchEnabled(),
	HIDPI			= matchMedia("(-webkit-min-device-pixel-ratio: 2), (min-device-pixel-ratio: 2), (min-resolution: 192dpi)").matches;
 	HASPDFVIEWER	= hasPDFViewer(),
 	LANGUAGE		= (function() {
							if (navigator.hasOwnProperty('languages')) { 
								return navigator.languages[0]; 
							}
							return navigator.language;
						})(),
	DEBUG 			= (typeof DEBUG === 'undefined')? false : DEBUG;
	
	// Adding 'touch' : 'no-touch' classes
	addClass(document.getElementsByTagName('html')[0], (TOUCHENABLED? '' : 'no-') + 'touch');
	// Adding 'hidpi' : 'no-hdpi' classes
	addClass(document.getElementsByTagName('html')[0], (HIDPI? '' : 'no-') + 'hidpi');
	

/*
 *	Enhancing jQuery
 */
 
(function($, undefined) {
	'use strict';
	
	// Making $.when working with arrays
	
	if ($.when.all === undefined) {
		$.when.all = function(deferreds) {
				var deferred = new $.Deferred();
				$.when.apply($, deferreds).then(
					function() {
						deferred.resolve(Array.prototype.slice.call(arguments));
					},
					function() {
						deferred.fail(Array.prototype.slice.call(arguments));
					}
				);
				return deferred;
			};
	}
	
	$.fn.getRotate = function(el) {
			if (el && el.length) {
				var st = window.getComputedStyle(el[0], null),
					mx = st.getPropertyValue('transform') || st.getPropertyValue('-ms-transform') || st.getPropertyValue('-webkit-transform'),
					a;
					
				if (mx && (a = mx.match(/matrix3d\(([^\)]+)\)/))) {
					a = a.split(/\s*,/);
					return Math.round(Math.atan2(a[1], a[0]) * (180 / Math.PI));
				}
			}
			return 0;
		};
	
	$.fn.waitAllImg = function(doneFn, successFn, failFn) {
		
			if (!this.length) {
				doneFn.call(self);
				return;
			}
			
			var self = $(this),
				deferreds = [],
				
				loadImage = function(image) {
					var deferred = new $.Deferred(),
						el = new Image();
						
					el.onload = function() {
							deferred.resolve(image);
						};
					
					el.onerror = function() {
							deferred.reject(new Error('Image not found: ' + image.src));
						};
					
					el.src = image.src;
					
					return deferred;
				},
				
				loadVideo = function(video) {
					var deferred = new $.Deferred(),
						el = document.createElement("VIDEO");
						
					el.addEventListener('loadedmetadata', function() {
							deferred.resolve(video);
						});
					
					el.addEventListener('error', function() {
							deferred.reject(new Error('Video not found: ' + video.src));
						});
					
					el.src = video.src;
					
					return deferred;
				},
				
				loadImages = function(items) {
					
					items.filter('img[src!=""]').each(function() {
							deferreds.push(loadImage(this));
						});
					
					items.filter('video[src!=""]').each(function() {
							deferreds.push(loadVideo(this));
						});
					
					return $.when.all(deferreds);
				};
			
			loadImages(self).then(
				
				function(self) {
					if ($.isFunction(successFn) && successFn !== doneFn) {
						successFn.call(self);
					}
				},
				
				function(err) {
					if ($.isFunction(failFn)) {
						failFn.call(err);
					}
				}
				
			).then(function() {
				if ($.isFunction(doneFn)) {
					doneFn.call(self);
				}
			});
			
			return this;
		};
	
	
	var isFullscreen = function() {
				return document.fullscreenElement ||
					document.webkitFullscreenElement ||
					document.mozFullScreenElement ||
					document.msFullscreenElement;
			},
	
		requestFullscreen = function( e ) {
				if (!isFullscreen()) {
					if (e.requestFullscreen) {
						e.requestFullscreen();
					} else if (e.webkitRequestFullscreen) {
						e.webkitRequestFullscreen();
					} else if (e.mozRequestFullScreen) {
						e.mozRequestFullScreen();
					} else if (e.msRequestFullscreen) {
						document.body.msRequestFullscreen();
						// Works only on body :(
						// e.msRequestFullscreen();
					}
				}
			},
		
		exitFullscreen = function() {
				if (isFullscreen()) {
					if (document.exitFullscreen) {
						document.exitFullscreen();
					} else if (document.webkitExitFullscreen) {
						document.webkitExitFullscreen();
					} else if (document.mozCancelFullScreen) {
						document.mozCancelFullScreen();
					} else if (document.msExitFullscreen) {
						document.msExitFullscreen();
					}
				}
			};
	
	$.fn.fullScreen = function(v) {
		
			if (document.fullscreenEnabled || 
				document.webkitFullscreenEnabled || 
				document.mozFullScreenEnabled ||
				document.msFullscreenEnabled
			) {
				// no state supplied :: returning the fullscreen status
				if (typeof v === 'undefined') {
					return isFullscreen();
				} else if (v) {
					requestFullscreen(this[0]);
				} else {
					exitFullscreen();
				}
				
			} else {
				return false;
			}
			
		};
	
})(jQuery);
