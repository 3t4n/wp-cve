'use strict';

/**
 * Send event data using GA
 *
 * @param {string} category
 * @param {string} action
 * @param {string} label
 *
 * @see https://developers.google.com/analytics/devguides/collection/analyticsjs/events
 */
function trackGaEvent( category, action, label ) {
	ga( 'send', {
		hitType: 'event',
		eventCategory: category,
		eventAction: action,
		eventLabel: label,
	} );
}

/**
 * Send event data using dataLayer
 *
 * @param {string} category
 * @param {string} action
 * @param {string} label
 */
function trackDatalayerEvent( category, action, label ) {
	window.dataLayer.push( {
		event: 'gaEvent',
		eventCategory: category,
		eventAction: action,
		eventLabel: label,
	} );
}

/**
 * Track event using Google DataLayer or Google Analytics (ga) script
 *
 * @param {string} label
 */
function trackEvent( label ) {
	var ga = window[ window[ 'GoogleAnalyticsObject' ] || 'ga' ];
	var datalayer = window.dataLayer;
	var category = 'RockConvertCTA';
	var action = 'Click';
	var fn = function ( _a, _b, _c ) {
		console.debug(
			'RockConvert: Google Analytics not configured -- Skipping event tracking'
		);
	};

	if ( datalayer ) {
		fn = trackDatalayerEvent;
	} else if ( ga && typeof ga === 'function' ) {
		fn = trackGaEvent;
	}

	fn( category, action, label );
}

function addEvent( obj, evt, fn ) {
	if ( obj.addEventListener ) {
		obj.addEventListener( evt, fn, false );
	} else if ( obj.attachEvent ) {
		obj.attachEvent( 'on' + evt, fn );
	}
}

function setCookie() {
	const date = new Date();
	date.setDate( date.getDate() + 1 );
	var expires = 'expires=' + date.toUTCString();
	document.cookie =
		'rock-popupcookie' + '=' + '1' + ';' + expires + ';path=/';
}

function getCookie( username ) {
	let name = username + '=';
	let spli = document.cookie.split( ';' );
	for ( var j = 0; j < spli.length; j++ ) {
		let char = spli[ j ];
		while ( char.charAt( 0 ) == ' ' ) {
			char = char.substring( 1 );
		}
		if ( char.indexOf( name ) == 0 ) {
			return char.substring( name.length, char.length );
		}
	}
	return '';
}

( function ( $ ) {
	/**
	 * Send POST request to track view endpoint
	 *
	 * @param {number} id
	 */
	function trackRockConvertCtaView( id ) {
		var tracking_view_url = rconvert_params.track_cta_view_path;
		jQuery.post( tracking_view_url + id );
	}

	/**
	 * @param {object} settings
	 */
	function showBar( settings ) {
		var $btn = '';

		if ( settings.link && settings.btn ) {
			var btn_styles =
				'background-color: ' +
				settings.btn_color +
				' ; color: ' +
				settings.btn_text_color +
				' ;';
			$btn =
				'<a href="' +
				settings.link +
				'" class="rconvert_announcement_bar__container__cta" style="' +
				btn_styles +
				'">' +
				settings.btn +
				'</a>';
		}

		var $text =
			'<span style="color: ' +
			settings.text_color +
			'">' +
			settings.text +
			'</span>';

		var $annBar =
			'<div class="rconvert_announcement_bar rconvert_announcement_bar--' +
			settings.position +
			'" style="background-color: ' +
			settings.bg_color +
			';">' +
			'<div class="rconvert_announcement_bar__container">' +
			$text +
			$btn +
			'</div>' +
			'</div>';

		jQuery( 'body' )
			.addClass( 'rc_announcement_bar--' + settings.position )
			.prepend( $annBar );
	}

	/**
	 * Track clicks on CTAs
	 */
	$( document ).on( 'click', '.rock-convert-cta-link', function () {
		var tracking_url = rconvert_params.track_cta_click_path;
		var ctaId = $( this ).data( 'cta-id' );
		var title = $( this ).data( 'cta-title' );
		jQuery.post( tracking_url + ctaId );
		trackEvent( title );
	} );

	/**
	 * Send google analytics events on announcements bar click
	 */
	$( document ).on(
		'click',
		'.rconvert_announcement_bar__container__cta',
		function () {
			trackEvent( 'Barra anuncio' );
		}
	);

	/**
	 * Send google analytics events on sidebar widget
	 */
	$( document ).on( 'click', '.rock-convert-widget-cta a', function () {
		trackEvent( 'Banner barra lateral' );
	} );

	$( document ).ready( function () {
		var settings = JSON.parse( rconvert_params.announcements_bar_settings );

		if ( settings.activated === '1' ) {
			if (
				settings.visibility === 'all' ||
				( settings.visibility === 'post' &&
					settings.isSingle === true &&
					settings.postType === 'post' )
			) {
				showBar( settings );
			}

			if ( settings.visibility === 'exclude' ) {
				var urls = settings.urls;

				if ( ! urls.includes( window.location.href ) ) {
					showBar( settings );
				}
			}
		}

		var analytics_enabled =
			rconvert_params && rconvert_params.analytics_enabled === '1';
		if ( analytics_enabled ) {
			$( '.rock-convert-cta-link' ).each( function ( index, value ) {
				var ctaId = $( this ).data( 'cta-id' );
				trackRockConvertCtaView( ctaId );
			} );
		}
	} );

	$( document ).on( 'click', '#btnClose', function () {
		$( '.convert-popup' ).slideUp();
	} );

	$( document ).on( 'click', '#btnEmail', function () {
		$( '.convert-popup' ).slideUp();
	} );

	addEvent( document, 'mouseout', function ( evt ) {
		if (
			evt.toElement == null &&
			evt.relatedTarget == null &&
			getCookie( 'rock-popupcookie' ) == ''
		) {
			$( '.convert-popup' ).slideDown();
			setCookie();
		}
	} );

	document.onkeydown = function ( evt ) {
		evt = evt || window.event;
		var isEscape = false;
		if ( 'key' in evt ) {
			isEscape = evt.key === 'Escape' || evt.key === 'Esc';
		} else {
			isEscape = evt.keyCode === 27;
		}
		if ( isEscape ) {
			$( '.convert-popup' ).slideUp();
		}
	};
} )( jQuery );
