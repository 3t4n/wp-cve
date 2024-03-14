( function() {

	let localStorageKeyName = 'easy_notification_bar_is_hidden';

	if ( 'object' === typeof easyNotificationBar ) {
		localStorageKeyName = easyNotificationBar.local_storage_keyname;
	}

	const isHidden = function() {
		if ( 'undefined' !== typeof localStorage && 'yes' === localStorage.getItem( localStorageKeyName ) ) {
			return true;
		} else {
			return false;
		}
	};

	const noticeInit = function() {
		const notice = document.querySelector( '.easy-notification-bar' );
		if ( notice ) {
			if ( isHidden() ) {
				document.body.classList.remove( 'has-easy-notification-bar' );
			} else {
				notice.classList.remove( 'easy-notification-bar--hidden' );
			}
		}
	};

	const removeOldKeys = function() {
		var oldKeys = [];
		for (let i = 0; i < localStorage.length; i++){
			if ( 'easy_notification_bar_is_hidden' === localStorage.key(i).substring(0,31) ) {
				oldKeys.push(localStorage.key(i));
			}
		}
		for (let i = 0; i < oldKeys.length; i++) {
			localStorage.removeItem(oldKeys[i]);
		}
	};

	const noticeClose = function() {
		document.addEventListener( 'click', (e) => {
			const toggle = e.target.closest( '[data-easy-notification-bar-close]' );

			if ( ! toggle ) {
				return;
			}

			const isButtonLink = toggle.classList.contains( 'easy-notification-bar-button__link' );

			if ( ! isButtonLink || ( isButtonLink && '#' === toggle.getAttribute( 'href' ) ) ) {
				e.preventDefault();
			}

			const notice = document.querySelector( '.easy-notification-bar' );

			notice.classList.add( 'easy-notification-bar--hidden' );
			document.body.classList.remove( 'has-easy-notification-bar' );

			if ( 'undefined' !== typeof localStorage ) {
				removeOldKeys();
				localStorage.setItem( localStorageKeyName, 'yes' );
				toggle.dispatchEvent( new CustomEvent( 'easy-notification-bar:close', { bubbles: true } ) );
			}
		} );
	};

	document.addEventListener( 'DOMContentLoaded', function() {
		noticeInit();
		noticeClose();
	} );

} )();