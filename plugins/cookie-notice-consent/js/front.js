( function ( window, document ) {

	var cookieNoticeConsent = new function () {
		
		this.showCookieNotice = function () {
			this.noticeContainer.classList.remove( 'cookie-notice-consent--hidden' );
			this.noticeContainer.classList.add( 'cookie-notice-consent--visible' );
		};
		
		this.hideCookieNotice = function () {
			this.noticeContainer.classList.remove( 'cookie-notice-consent--visible' );
			this.noticeContainer.classList.add( 'cookie-notice-consent--hidden' );
		};
		
		this.setBodyClasses = function( classes ) {
			document.body.classList.remove( 'cookie-consent-set' );
			document.body.classList.remove( 'cookie-consent-not-set' );
			for( var i = 0; i < classes.length; i++ ) {
				document.body.classList.add( classes[i] );
			}
		};
		
		this.generateUUIDv4 = function() {
			return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace( /[xy]/g, function( c ) {
				var r = Math.random() * 16 | 0, v = c == 'x' ? r : ( r & 0x3 | 0x8 );
				return v.toString( 16 );
			});
		}
		
		this.maybeReloadPage = function( reload, cache_buster = 1 ) {
			if( reload !== 1 )
				return;
			var url = window.location.protocol + '//';
			var hostname = window.location.host + '/' + window.location.pathname;
			if( 1 == cncArgs.cache && 1 == cache_buster && ! window.location.search.includes( 'cookie-consent-set=true' ) ) {
				url = url + hostname.replace( '//', '/' ) + ( window.location.search === '' ? '?' : window.location.search + '&' ) + 'cookie-consent-set=true' + window.location.hash;
				window.location.href = url;
			} else {
				url = url + hostname.replace( '//', '/' ) + window.location.search + window.location.hash;
				window.location.reload( true );
			}
			return;
		}
		
		this.setCookie = function( name, value, days ) {
			var expires;
			if( days ) {
				var date = new Date();
				date.setTime( parseInt( date.getTime() ) + ( days * 24 * 60 * 60 * 1000 ) );
				expires = "; expires=" + date.toUTCString();
			} else {
				expires = "";
			}
			var cookieString = name + "=" + JSON.stringify( value ) + expires + "; path=/; SameSite=Lax; " + ( 1 == cncArgs.secure ? 'Secure;' : '' );
			document.cookie = cookieString;
		}
		
		this.readCookie = function( name ) {
			var nameEQ = name + "=";
			var ca = document.cookie.split( ";" );
			for( var i = 0; i < ca.length; i++ ) {
				var c = ca[i];
				while( c.charAt( 0 ) == ' ' )
					c = c.substring( 1, c.length );
				if( c.indexOf( nameEQ ) == 0 )
					return c.substring( nameEQ.length, c.length );
			}
			return null;
		}
		
		this.eraseCookie = function( name ) {
			this.setCookie( name, "", -1 );
		}
		
		this.eraseAllCookies = function() {
			var cookies = document.cookie.split( ";" );
			for( var i = 0; i < cookies.length; i++ ) {
				var name = cookies[i].split( "=" )[0].trim();
				if( ! ( name.lastIndexOf( "wordpress", 0 ) === 0 || name.lastIndexOf( "wp-settings", 0 ) === 0 ) )
					this.eraseCookie( name );
			}
		}
		
		this.init = function() {
			var _this = this;
			this.noticeContainer = document.getElementById( 'cookie-notice-consent' );
			this.cookieName = 'cookie_consent';
			
			if( null === this.noticeContainer )
				return;
			
			if( null === this.readCookie( this.cookieName ) ) {
				this.showCookieNotice();
				this.setBodyClasses( [ 'cookie-consent-not-set' ] );
			} else if( null !== this.readCookie( this.cookieName ) ) {
				this.hideCookieNotice();
				this.setBodyClasses( [ 'cookie-consent-set' ] );
			}
			
			var acceptButton = document.getElementById( 'cookie-notice-consent__accept-button' );
			var confirmButton = document.getElementById( 'cookie-notice-consent__confirm-choice-button' );
			var rejectButton = document.getElementById( 'cookie-notice-consent__reject-button' );
			var revokeButton = document.getElementById( 'cookie-notice-consent__revoke-button' );
			
			var actionButtons = [];
			if( acceptButton !== null ) actionButtons.push( acceptButton );
			if( confirmButton !== null ) actionButtons.push( confirmButton );
			if( rejectButton !== null ) actionButtons.push( rejectButton );
			var ajaxRunning = false;
			for( var i = 0; i < actionButtons.length; i++ ) {
				actionButtons[i].addEventListener( 'click', function( e ) {
					if( ajaxRunning )
						return;
					e.preventDefault();
					e.stopPropagation();
					
					var acceptedCategoriesElements = [];
					if( 'cookie-notice-consent__accept-button' == e.target.id ) {
						acceptedCategoriesElements = document.querySelectorAll( '.cookie-notice-consent__categories input[type=checkbox]' );
					} else if( 'cookie-notice-consent__confirm-choice-button' == e.target.id ) {
						acceptedCategoriesElements = document.querySelectorAll( '.cookie-notice-consent__categories input[type=checkbox]:checked' );
					}
					var acceptedCategories = [];
					for( var i = 0; i < acceptedCategoriesElements.length; i++ ) {
						acceptedCategories.push( acceptedCategoriesElements[i].getAttribute( 'data-cookie-category' ) );
					}
					var uuidv4 = ( 0 == cncArgs.uuid ? _this.generateUUIDv4() : cncArgs.uuid );
					var cookieData = {
						uuid: uuidv4,
						cookie_categories: acceptedCategories,
						timestamp: new Date / 1E3 | 0
					};
					_this.setCookie( _this.cookieName, cookieData, cncArgs.cookieExpiration );
					
					if( 1 == cncArgs.reload || 1 == cncArgs.log )
						_this.noticeContainer.classList.add( 'cookie-notice-consent--loading' );
					
					if( 1 == cncArgs.log ) {
						ajaxRunning = true;
						
						var xhr = new XMLHttpRequest();
						var params = JSON.stringify( {
							uuid: uuidv4,
							categories: acceptedCategories,
							remote_addr: cncArgs.remote_addr,
							http_user_agent: cncArgs.http_user_agent
						} );
						xhr.open( 'POST', cncArgs.ajax_url, true );
						xhr.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded; charset=UTF-8' );
						xhr.send( 'action=save_cookie_consent&_ajax_nonce=' + cncArgs.ajax_nonce + '&data=' + params );
						xhr.onreadystatechange = function () {
							if( xhr.readyState == 4 && xhr.status == 200 ) {
								try {
									ajaxRunning = false;
									_this.maybeReloadPage( cncArgs.reload );
								} catch( error ) {
									throw Error;
								}
							}
						}
					} else {
						_this.maybeReloadPage( cncArgs.reload );
					}
					
					if( 0 == cncArgs.reload) {
						_this.hideCookieNotice();
					}
					_this.setBodyClasses( [ 'cookie-consent-set' ] );
				} );
			}
			
			if( revokeButton !== null ) {
				revokeButton.addEventListener( 'click', function( e ) {
					e.preventDefault();
					e.stopPropagation();
					if( 1 == cncArgs.revokeAll ) {
						_this.eraseAllCookies();
					} else {
						_this.eraseCookie( _this.cookieName );
					}
					alert( cncArgs.revokeNotice );
					_this.maybeReloadPage( 1, 0 );
				} );
			}
			
		};
	}

	window.addEventListener( 'load', function () {
		cookieNoticeConsent.init();
	}, false );

} )( window, document );


function decodeHTML( html ) {
	let txt = document.createElement( 'textarea' );
	txt.innerHTML = html;
	return txt.value;
}
function createElementFromHTML( htmlString ) {
	let div = document.createElement( 'div' );
	div.innerHTML = htmlString.trim();
	return div.firstChild; 
}
let items = document.getElementsByClassName( 'cookie-notice-consent__embed-unblock' );
for( let i = 0; i < items.length; i++ ) {
	items[i].onclick = function() {
		let embed_code = decodeHTML( this.getAttribute( 'data-embed-content' ) );
		this.parentNode.replaceWith( createElementFromHTML( embed_code ) );
		if( 'twitter.com' == this.getAttribute( 'data-embed-provider' ) ) {
			let script = document.createElement( 'script' );
			let head = document.head || document.getElementsByTagName( 'head' )[0];
			script.src = '//platform.twitter.com/widgets.js';
			script.async = false;
			head.insertBefore( script, head.firstChild );
		}
	}
}
