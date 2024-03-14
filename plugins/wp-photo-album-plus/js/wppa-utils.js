// wppa-utils.js
//
// conatins common vars and functions
//
wppaJsUtilsVersion = '8.6.02.002';

// Handle animation dependant of setting for mobile
function wppaAnimate( selector, properties, duration, easing, complete ) {

	// If no animation on mobile, do not animate
	if ( wppaIsMobile && wppaNoAnimateOnMobile ) {
		jQuery( selector ).css( properties );
		if ( complete ) {
			setTimeout( complete, 10 );
		}
	}

	// Just do it
	else {
		jQuery( selector ).stop().animate( properties, duration, easing, complete );
	}
}

// Handle fading dependant of setting for mobile
function wppaFadeIn( selector, duration, complete ) {
	if ( wppaIsMobile && wppaNoAnimateOnMobile ) {
		jQuery( selector ).css( {display:''} );
		if ( complete ) {
			setTimeout( complete, 10 );
		}
	}
	else {
		jQuery( selector ).stop().fadeIn( duration, complete );
	}
}
function wppaFadeOut( selector, duration, complete ) {
	if ( wppaIsMobile && wppaNoAnimateOnMobile ) {
		jQuery( selector ).css( {display:'none'} );
		if ( complete ) {
			setTimeout( complete, 10 );
		}
	}
	else {
		jQuery( selector ).stop().fadeOut( duration, complete );
	}
}
function wppaFadeTo( selector, duration, opacity, complete ) {
	if ( wppaIsMobile && wppaNoAnimateOnMobile ) {
		jQuery( selector ).css( {display:'', opacity: opacity} );
		if ( complete ) {
			setTimeout( complete, 10 );
		}
	}
	else {
		jQuery( selector ).stop().fadeTo( duration, opacity, complete );
	}
}

// Trim
// @1 string to be trimmed
// @2 character, string, or array of characters or strings to trim off,
//    default: trim spaces, tabs and newlines
function wppaTrim( str, arg ) {

	var result;

	result = wppaTrimLeft( str, arg );
	result = wppaTrimRight( result, arg );

	return result;
}

// Trim left
// @1 string to be trimmed
// @2 character, string, or array of characters or strings to trim off,
//    default: trim spaces, tabs and newlines
function wppaTrimLeft( str, arg ) {

	var result;
	var strlen;
	var arglen;
	var argcount;
	var i;
	var done;
	var oldStr, newStr;

	switch ( typeof ( arg ) ) {
		case 'string':
			result = str;
			strlen = str.length;
			arglen = arg.length;
			while ( strlen >= arglen && result.substr( 0, arglen ) == arg ) {
				result = result.substr( arglen );
				strlen = result.length;
			}
			break;
		case 'object':
			done = false;
			newStr = str;
			while ( ! done ) {
				i = 0;
				oldStr = newStr;
				while ( i < arg.length ) {
					newStr = wppaTrimLeft( newStr, arg[i] );
					i++;
				}
				done = ( oldStr == newStr );
			}
			result = newStr;
			break;
		default:
			return str.replace( /^\s\s*/, '' );
	}

	return result;
}

// Trim right
// @1 string to be trimmed
// @2 character, string, or array of characters or strings to trim off,
//    default: trim spaces, tabs and newlines
function wppaTrimRight( str, arg ) {

	var result;
	var strlen;
	var arglen;
	var argcount;
	var i;
	var done;
	var oldStr, newStr;

	switch ( typeof ( arg ) ) {
		case 'string':
			result = str;
			strlen = str.length;
			arglen = arg.length;
			while ( strlen >= arglen && result.substr( strlen - arglen ) == arg ) {
				result = result.substr( 0, strlen - arglen );
				strlen = result.length;
			}
			break;
		case 'object':
			done = false;
			newStr = str;
			while ( ! done ) {
				i = 0;
				oldStr = newStr;
				while ( i < arg.length ) {
					newStr = wppaTrimRight( newStr, arg[i] );
					i++;
				}
				done = ( oldStr == newStr );
			}
			result = newStr;
			break;
		default:
			return str.replace( /\s\s*$/, '' );
	}

	return result;
}

// Cookie handling
function wppa_setCookie(c_name,value,exdays) {
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}

function wppa_getCookie(c_name) {
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++) {
		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
		x=x.replace(/^\s+|\s+$/g,"");
		if (x==c_name) {
			return unescape(y);
		}
	}
	return "";
}

// Change stereotype cookie
function wppaStereoTypeChange( newval ) {
	wppa_setCookie( 'stereotype', newval, 365 );
}

// Change stereoglass cookie
function wppaStereoGlassChange( newval ) {
	wppa_setCookie( 'stereoglass', newval, 365 );
}

// Console logging
function wppaConsoleLog( arg ) {

	if ( typeof( console ) != 'undefined' ) {
		var d = new Date();
		var n = d.getTime();
		var t = n % (24*60*60*1000); 				// msec this day
		var h = Math.floor( t / ( 60*60*1000 ) ); 	// Hours this day
		t -= h * 60*60*1000;						// msec this hour
		var m = Math.floor( t / ( 60*1000 ) );		// Minutes this hour
		t -= m * 60*1000;							// msec this minute
		var s = Math.floor( t / 1000 );				// Sec this minute
		t -= s * 1000;								// msec this sec
		console.log( 'At: ' + h + ':' + m + ':' + s + '.' + t + ' message: ' + arg );
	}
}

// Get an svg image html
// @1: string: Name of the .svg file without extension
// @2: string: CSS height or empty, no ; required
// @3: bool: True if for lightbox. Use lightbox colors
// @4: bool: if true: add border
// @5: radius in % type none
// @6: radius in % type light
// @7: radius in % type medium
// @8: radius in % type heavy
function wppaSvgHtml( image, height, isLightbox, border, none, light, medium, heavy ) {

	var fc; 	// Foreground (fill) color
	var bc; 	// Background color

	if ( ! none ) none = '0';
	if ( ! light ) light = '10';
	if ( ! medium ) medium = '20';
	if ( ! heavy ) heavy = '50';

	border = false; // debug

	// Find Radius
	switch ( wppaSvgCornerStyle ) {
		case 'gif':
		case 'none':
			radius = none;
			break;
		case 'light':
			radius = light;
			break;
		case 'medium':
			radius = medium;
			break;
		case 'heavy':
			radius = heavy;
			break;
	}

	// Init Height
	if ( ! height ) {
		height = '32px';
	}

	// Get Colors
	if ( image == 'Full-Screen' || image == 'Exit-Full-Screen' ) {
		fc = wppaFsFillcolor;
		bc = wppaFsBgcolor;
	}
	else {
		if ( isLightbox ) {
			fc = wppaOvlSvgFillcolor;
			bc = wppaOvlSvgBgcolor;
		}
		else {
			fc = wppaSvgFillcolor;
			bc = wppaSvgBgcolor;
		}
	}

	// Replace empty color attribute by transparent
	if ( fc == '' ) fc = 'transparent';
	if ( bc == '' ) bc = 'transparent';

	// Make the html. Native svg html
	var result = 	'<svg' +
							' version="1.1"' +
							' xmlns="http://www.w3.org/2000/svg"' +
							' xmlns:xlink="http://www.w3.org/1999/xlink"' +
							' x="0px"' +
							' y="0px"' +
							' viewBox="0 0 30 30"' +
							' style="' +
								( height ? 'height:' + height + ';' : '' ) +
								'fill:' + fc + ';' +
								'background-color:' + bc + ';' +
								'text-decoration:none !important;' +
								'vertical-align:middle;' +
								( radius ? 'border-radius:' + radius + '%;' : '' ) +
								( border ? 'border:2px solid ' + bc + ';box-sizing:content-box;' : '' ) +
								'"' +
							' xml:space="preserve"' +
							' >' +
							'<g>';
		switch ( image ) {
			case 'Next-Button':
				result += 	'<path' +
								' d="M30,0H0V30H30V0z M20,20.5' +
									'c0,0.3-0.2,0.5-0.5,0.5S19,20.8,19,20.5v-4.2l-8.3,4.6c-0.1,0-0.2,0.1-0.2,0.1c-0.1,0-0.2,0-0.3-0.1c-0.2-0.1-0.2-0.3-0.2-0.4v-11' +
									'c0-0.2,0.1-0.4,0.3-0.4c0.2-0.1,0.4-0.1,0.5,0l8.2,5.5V9.5C19,9.2,19.2,9,19.5,9S20,9.2,20,9.5V20.5z"' +
							' />';
				break;
			case 'Next-Button-Big':
				result += 	'<line x1="8" y1="2" x2="21.75" y2="15.75" stroke="'+fc+'" stroke-width="2.14" />' +
							'<line x1="21.75" y1="14.25" x2="8" y2="28" stroke="'+fc+'" stroke-width="2.14" />';
							// '<path' +
							//	' d="M8,29.5c-0.1,0-0.3,0-0.4-0.1c-0.2-0.2-0.2-0.5,0-0.7L21.3,15L7.6,1.4c-0.2-0.2-0.2-0.5,0-0.7s0.5-0.2,0.7,0l14,14' +
							//		'c0.2,0.2,0.2,0.5,0,0.7l-14,14C8.3,29.5,8.1,29.5,8,29.5z"/>';
				break;
			case 'Prev-Button':
				result += 	'<path' +
								' d="M30,0H0V30H30V0z M20,20.5' +
									'c0,0.2-0.1,0.4-0.3,0.4c-0.1,0-0.2,0.1-0.2,0.1c-0.1,0-0.2,0-0.3-0.1L11,15.4v5.1c0,0.3-0.2,0.5-0.5,0.5S10,20.8,10,20.5v-11' +
									'C10,9.2,10.2,9,10.5,9S11,9.2,11,9.5v4.2l8.3-4.6c0.2-0.1,0.3-0.1,0.5,0S20,9.3,20,9.5V20.5z"' +
							' />';
				break;
			case 'Prev-Button-Big':
				result += 	'<line x1="22" y1="2" x2="8.25" y2="15.75" stroke="'+fc+'" stroke-width="2.14" />' +
							'<line x1="8.25" y1="14.25" x2="22" y2="28" stroke="'+fc+'" stroke-width="2.14" />';

				//'<path' +
							//	' d="M22,29.5c-0.1,0-0.3,0-0.4-0.1l-14-14c-0.2-0.2-0.2-0.5,0-0.7l14-14c0.2-0.2,0.5-0.2,0.7,0s0.2,0.5,0,0.7L8.7,15l13.6,13.6' +
							//	' c0.2,0.2,0.2,0.5,0,0.7C22.3,29.5,22.1,29.5,22,29.5z"/>';
				break;
			case 'Pause-Button':
				result += 	'<path' +
								' d="M30,0H0V30H30V0z M14,20.5' +
									'c0,0.3-0.2,0.5-0.5,0.5h-4C9.2,21,9,20.8,9,20.5v-11C9,9.2,9.2,9,9.5,9h4C13.8,9,14,9.2,14,9.5V20.5z M21,20.5' +
									'c0,0.3-0.2,0.5-0.5,0.5h-4c-0.3,0-0.5-0.2-0.5-0.5v-11C16,9.2,16.2,9,16.5,9h4C20.8,9,21,9.2,21,9.5V20.5z"' +
							' />';
				break;
			case 'Play-Button':
				result += 	'<path' +
								' d="M30,0H0V30H30V0z' +
									'M19.8,14.9l-8,5C11.7,20,11.6,20,11.5,20c-0.1,0-0.2,0-0.2-0.1c-0.2-0.1-0.3-0.3-0.3-0.4v-9c0-0.2,0.1-0.3,0.2-0.4' +
									'c0.1-0.1,0.3-0.1,0.5,0l8,4c0.2,0.1,0.3,0.2,0.3,0.4C20,14.7,19.9,14.8,19.8,14.9z"' +
							' />';
				break;
			case 'Stop-Button':
				result += 	'<path' +
								' d="M30,0H0V30H30V0z M21,20.5' +
									'c0,0.3-0.2,0.5-0.5,0.5h-11C9.2,21,9,20.8,9,20.5v-11C9,9.2,9.2,9,9.5,9h11C20.8,9,21,9.2,21,9.5V20.5z"' +
							'/>';
				break;
			case 'Exit':
				result += 	'<path d="M30 24.398l-8.406-8.398 8.406-8.398-5.602-5.602-8.398 8.402-8.402-8.402-5.598 5.602 8.398 8.398-8.398 8.398 5.598 5.602 8.402-8.402 8.398 8.402z"></path>';
				break;
			case 'Exit-2':
				result +=	'<path d="M30,0H0V30H30V0z M9 4 L15 10 L21 4 L26 9 L20 15 L26 21 L21 26 L15 20 L9 26 L4 21 L10 15 L4 9Z" />';
				break;
			case 'Exit-Big':
				result +=	'<line x1="4" y1="4" x2="26" y2="26" stroke="'+fc+'" stroke-width="2.14" />'+
							'<line x1="4" y1="26" x2="26" y2="4" stroke="'+fc+'" stroke-width="2.14" />';
				break;
			case 'Full-Screen':
				result += 	'<path d="M27.414 24.586l-4.586-4.586-2.828 2.828 4.586 4.586-4.586 4.586h12v-12zM12 0h-12v12l4.586-4.586 4.543 4.539 2.828-2.828-4.543-4.539zM12 22.828l-2.828-2.828-4.586 4.586-4.586-4.586v12h12l-4.586-4.586zM32 0h-12l4.586 4.586-4.543 4.539 2.828 2.828 4.543-4.539 4.586 4.586z"></path>';
				break;
			case 'Full-Screen-2':
				result +=	'<path d="M30,0H0V30H30V0z M4 4 L12 4 L10 6 L14 10 L10 14 L6 10 L4 12Z M18 4 L26 4 L26 12 L24 10 L20 14 L16 10 L20 6Z M26 26 L18 26 L20 24 L16 20 L20 16 L24 20 L26 18Z M4 26 L4 18 L6 20 L10 16 L14 20 L10 24 L12 26Z" />';
				break;
			case 'Exit-Full-Screen':
				result += 	'<path d="M24.586 27.414l4.586 4.586 2.828-2.828-4.586-4.586 4.586-4.586h-12v12zM0 12h12v-12l-4.586 4.586-4.539-4.543-2.828 2.828 4.539 4.543zM0 29.172l2.828 2.828 4.586-4.586 4.586 4.586v-12h-12l4.586 4.586zM20 12h12l-4.586-4.586 4.547-4.543-2.828-2.828-4.547 4.543-4.586-4.586z"></path>';
				break;
			case 'Exit-Full-Screen-2':
				result +=	'<path d="M30,0H0V30H30V0z M17 17 L25 17 L23 19 L27 23 L23 27 L19 23 L17 25Z M5 17 L13 17 L13 25 L11 23 L7 27 L3 23 L7 19Z M13 13 L5 13 L7 11 L3 7 L7 3 L11 7 L13 5Z M17 13 L17 5 L19 7 L23 3 L27 7 L23 11 L25 13Z" />';
				break;
			default:
				result +=	'<path d="M30,0H0V30H30V0z" />';
				break;
		}
		result += 			'</g>' +
						'</svg>';


	return result;
}

var wppaLazyDone = false;
// Make lazy load images visible
function wppaMakeLazyVisible(e) {

	var start = Date.now();
	var count = 0;

 	// Feature enabled?
	if ( ! wppaLazyLoad ) return; // No, quit

	// Init masonryplus
	wppaInitMasonryPlus();

	var src;

	// Do the first img only
	var potential = jQuery( "*[data-src]" );
	if ( potential.length > 0 ) {

		jQuery( potential ).each( function() {
			src = jQuery(this).attr('data-src');
			if ( wppaIsElementInViewport(this) ) {
				jQuery(this).attr('src', src);
				jQuery(this).removeAttr('data-src');
				count++;
				wppaLazyDone = true;
			}
		});
	}

	// If anything done...
	if ( wppaLazyDone ) {

		// Init masonryplus
		wppaInitMasonryPlus();

		// Resize nicescroller
		if ( jQuery("div").getNiceScroll ) {
			setTimeout( function(){
				jQuery( "div" ).getNiceScroll().resize();
			},500);
			setTimeout( function(){
				jQuery( "div" ).getNiceScroll().resize();
			},1500);
		}

		// Fake a scroll
		setTimeout( function(){
			jQuery(".wppa-box").trigger("scroll");
			jQuery("body").trigger("scroll");
		}, 250);
	}

	wppaLazyDone = false;

	if ( count ) console.log( 'wppaMakeLazyVisible processed ' + count + ' items in ' + ( Date.now() - start ) + ' milliseconds' );

}

// Determines whether (a part of) element elm (an image) is inside browser window
function wppaIsElementInViewport( elm ) {

	if ( typeof elm === "undefined" ) return false;
	if ( ! elm ) return false;
	if ( elm.length == 0 ) return false;
	if ( window.closed ) return false;
	if ( document.hidden ) return false;

	// Check if elm or its (grand)parent has display none
	var e = jQuery(elm);
	while ( e[0] && e[0].nodeName != "BODY" ) {

		if ( jQuery( e[0] ).css("display") == "none" ) {
			return false;
		}
		var vis = jQuery( e[0] ).css("visibility");
		if ( vis == "hidden" || vis == "collapse" ) {
			return false;
		}
		e = jQuery( e[0] ).parent();
	}

	// If elm is a jQuery result, convert to single js item
    if ( elm instanceof jQuery ) {
        elm = elm[0];
    }

	var result;
    var rect = elm.getBoundingClientRect();

	if ( rect ) {
		if ( wppaIsMobile ) {
			result = rect.bottom > 0 && rect.right > 0 && rect.left < 3 * screen.width && rect.top < 3 * screen.height;
		}
		else {
			result = rect.bottom > 0 && rect.right > 0 && rect.left < wppaWindowWidth() && rect.top < wppaWindowHeight();
		}
	}
	else {
		result = true;
	}

    return result;
}

// Size scrollable areas
function wppaSizeArea() {

	// Thumb and album lists
	if ( wppaAreaMaxFrac > 0 && wppaAreaMaxFrac < 1 ) {
		jQuery( '.wppa-thumb-area' ).css( 'max-height', ( wppaWindowHeight() * wppaAreaMaxFrac ) );
		jQuery( '.albumlist' ).css( 'max-height', ( wppaWindowHeight() * wppaAreaMaxFrac ) );

	}

	// Slideshow
	if ( wppaAreaMaxFracSlide > 0 && wppaAreaMaxFracSlide < 1 ) {
		jQuery( '.slidelist' ).css( 'max-height', ( wppaWindowHeight() * wppaAreaMaxFracSlide ) );
	}

	// Audio only box for albums
	if ( wppaAreaMaxFracAudio > 0 && wppaAreaMaxFracAudio < 1 ) {
		jQuery( '.audiolist' ).css( 'max-height', ( wppaWindowHeight() * wppaAreaMaxFracAudio ) );
	}

	// Modal
	jQuery( '.wppa-modal' ).css({maxHeight:wppaWindowHeight()*0.8});
	jQuery( '.wppa-modal' ).each(function(){
		var t = ( wppaWindowHeight() - jQuery(this).height() ) / 2 - 24;
		if ( wppaIsMobile ) {
			t -= 24;
		}
		jQuery(this).parent().parent().css({top:t});
	});

}

// Get the icon size
function wppaIconSize( mocc, dflt, large ) {

	var opt = large ? wppaIconSizeSlide : wppaIconSizeNormal;
	if ( opt == 'default' ) {
		return dflt;
	}

	var result = ( wppaIsMini[mocc] ? opt / 2 : opt ) + 'px;';

	return result;
}

// Revert php htmlentities()
function wppaEntityDecode( txt ) {
	var result;

	result = txt;
	result = result.split('&amp;').join('&');
	result = result.split('&gt;').join('>');
	result = result.split('&lt;').join('<');
	result = result.split('&quot;').join('"');
	result = result.split('&#39;').join("'");

	return result;
}

// For tye fe upload select button
function wppaSetMaxWidthToParentWidth(elm) {
	var w = elm.parentNode.clientWidth;
	jQuery(elm).css({maxWidth:w});
}

// Even $ fails sometimes on jQuery(window).height()
function wppaWindowHeight() {

	return window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
}

//
function wppaWindowWidth() {

	return window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

}

// In fullscreen mode, place the controlbar at the bottom of the screen, 100% wide
// Check max iconsizes
function wppaAdjustControlbar(mocc) {


	var barWidth;

	// Is lightbox active?
	if ( wppaOvlOpen ) {

		// and fullscreen?
		if ( wppaIsFs() ) {

			// Adjust ctrlbar
			jQuery( ".wppa-pctl-div-lb" ).css({
				position:"fixed",
				left:0,
				right:0,
				bottom:0,
			});

			barWidth = screen.width;
		}
		else {

			// Adjust ctrlbar
			jQuery( ".wppa-pctl-div-lb" ).css({
				position:"initial",
			});

			barWidth = jQuery("#wppa-ovl-pan-container").width();
		}
		if ( wppaIsMobile && wppaIsFs() ) {
			barWidth -= 20;
		}

		// Check iconsizes
		var newHeight;
		if ( barWidth < 12 * ( parseInt( wppaOvlIconSize ) + 4 ) ) {
			newHeight = parseInt( barWidth / 12 - 4 );

			if ( newHeight > 0 ) {
				jQuery( ".wppa-pctl-div-lb" ).find( "svg" ).css({height:newHeight,width:newHeight});
				jQuery( ".wppa-pctl-div-lb" ).find( "span" ).css({height:newHeight,width:newHeight});
			}
		}
		else {
			newHeight = wppaOvlIconSize;
			jQuery( ".wppa-pctl-div-lb" ).find( "svg" ).css({height:newHeight,width:newHeight});
			jQuery( ".wppa-pctl-div-lb" ).find( "span" ).css({height:newHeight,width:newHeight});
		}
		jQuery( ".sixty" ).css({height:60,width:60});
		jQuery( ".sixty" ).find( "svg" ).css({height:60,width:60});

		// Adjust start/stop button display
		if ( wppaOvlIsSingle ) {
			jQuery( '#wppa-ovl-start-btn' ).hide();
			jQuery( '#wppa-ovl-stop-btn' ).hide();
		}
		else {
			if ( wppaOvlRunning ) {
				jQuery( '#wppa-ovl-stop-btn' ).show();
				jQuery( '#wppa-ovl-start-btn' ).hide();
			}
			else {
				jQuery( '#wppa-ovl-start-btn' ).show();
				jQuery( '#wppa-ovl-stop-btn' ).hide();
			}
		}

		// Mobile lanscape fullscreen requires space at the ends for round corners
		if ( wppaIsMobile && wppaIsFs() ) {
			jQuery( ".wppa-pctl-div-lb" ).css({paddingLeft:10,paddingRight:10});
		}
		else {
			jQuery( ".wppa-pctl-div-lb" ).css({paddingLeft:0,paddingRight:0});
		}

		// Make it visible
		jQuery( ".wppa-pctl-div-lb" ).css({visibility:"visible"});
	}

	if ( typeof( mocc ) == 'number' ) {
		if ( wppaHasControlbar ) {
			jQuery('#wppa-pctl-div-'+mocc).show();
		}
		else {
			jQuery('#wppa-pctl-div-'+mocc).hide();
		}
	}

}

// Kill event
function wppaKillEvent(e) {

	e.preventDefault();
	e.stopPropagation();
	return false;
}

// Log on the server
function wppaServerLog( message ) {

	if ( ! message ) return;

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa' +
								'&wppa-action=log' +
								'&message=' + message,
					async: 		true,
					type: 		'GET',
					timeout: 	60000,
					success: 	function( result, status, xhr ) {

								},
					error: 		function( xhr, status, error ) {
									wppaConsoleLog( 'wppaServerLog failed. Error = ' + error + ', status = ' + status );
								},
				} );
}

function wppaTimNow() {
	var d = new Date();
	var t = d.getTime();
	return t;
}

function wppaShowCoords(elm) {

	var rect = elm.getBoundingClientRect();

	alert('Top = '+parseInt(rect.top)+', Left = '+parseInt(rect.left)+', WinH = '+jQuery(window).height()+', WinW = '+jQuery(window).width()+', Scrolltop = '+jQuery(document).scrollTop());
}

// Are we on a widget activation screen?
function wppaOnWidgets() {

	var url = document.location.href;

	return url.search("widgets.php") != -1;
}
function wppaOnPost() {
	return ! wppaOnWidgets();
}

// Get Tinymce editor content by id
function wppaGetTinyMceContent( longId ) {

	if ( jQuery('#'+longId).css('display') != 'none' ) {
		var result = jQuery('#'+longId).val();
	}
	else {
		var result = tinymce.get(longId).getContent();
	}

	return result;
}