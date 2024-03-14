'use strict';

( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		let currentComponent;

		doc.on( 'click', '.fsp-tab[data-component]', function () {
			let _this = $( this );
			let filter_by = FSPObject.filter_by;

			currentComponent = _this.data( 'component' );

			if ( currentComponent )
			{
				$( '.fsp-tab.fsp-is-active' ).removeClass( 'fsp-is-active' );
				_this.addClass( 'fsp-is-active' );

				FSPoster.ajax( 'get_accounts', { name: currentComponent }, function ( res ) {
					$( '#fspComponent' ).html( FSPoster.htmlspecialchars_decode( res[ 'html' ] ) );

					$( '.fsp-accounts-add-button > span' ).text( res[ 'extra' ][ 'button_text' ] );

					let fspAccountsCount = FSPObject.accountsCount;

					_this.find( '.fsp-tab-all' ).text( fspAccountsCount );

					$( '.fsp-accounts-add-button' ).data( 'sn', currentComponent );

					if ( $( '.fsp-account-checkbox > .fsp-is-checked, .fsp-account-checkbox > .fsp-is-checked-conditionally' ).length > 0 )
					{
						$( '.fsp-tab.fsp-is-active > .fsp-tab-badges' ).addClass( 'fsp-has-active-accounts' );
					}
					else
					{
						$( '.fsp-tab.fsp-is-active > .fsp-tab-badges' ).removeClass( 'fsp-has-active-accounts' );
					}

					filterAccounts( filter_by );
				} );

				window.history.pushState( {}, '', `?page=fs-poster-accounts&tab=${ currentComponent }&filter_by=${ filter_by }` );
			}
		} ).on( 'click', function ( e ) {
			if ( ! $( e.target ).is( '.fsp-account-checkbox, .fsp-account-checkbox > i' ) )
			{
				$( '#fspActivateMenu' ).hide();
			}

			if ( ! $( e.target ).is( '.fsp-account-more, .fsp-account-more > i' ) )
			{
				$( '#fspMoreMenu' ).hide();
			}
		} ).on( 'click', '.fsp-modal-option', function () {
			let _this = $( this );
			let step = _this.data( 'step' );

			$( '.fsp-modal-option.fsp-is-selected' ).removeClass( 'fsp-is-selected' );
			_this.addClass( 'fsp-is-selected' );

			if ( step )
			{
				if ( $( `#fspModalStep_${ step }` ).length )
				{
					$( '.fsp-modal-step' ).addClass( 'fsp-hide' );
					$( `#fspModalStep_${ step }` ).removeClass( 'fsp-hide' );
				}
			}
		} ).on( 'click', '.fspjs-refetch-account', function () {
			let accountID = $( this ).data( 'id' );

			FSPoster.ajax( 'refetch_account', { 'account_id': accountID }, function () {
				$( '.fsp-tab.fsp-is-active' ).click();
			} );
		} ).on( 'change', '#fspUseProxy', function () {
			let checked = ! ( $( this ).is( ':checked' ) );

			$( '#fspProxy' ).val( '' );
			$( '#fspProxyContainer' ).toggleClass( 'fsp-hide', checked );
		} ).on( 'click', function () {
			let checkedAccounts = $( '.fsp-account-selectbox:checked' );
			let checkedAccountsLength = checkedAccounts.length;

			$( '#fspSelectedAccountsAction option:first' ).text( `Select an action (${ checkedAccountsLength })` );

			if ( checkedAccountsLength > 0 )
			{
				$( '#fspSelectedAccountsAction' ).prop( 'disabled', false );
				$( '#fspToggleSelectboxes' ).text( fsp__( 'UNSELECT ALL' ) );
			}
			else
			{
				$( '#fspSelectedAccountsAction' ).prop( 'disabled', true );
				$( '#fspToggleSelectboxes' ).text( fsp__( 'SELECT ALL' ) );
			}
		} ).on( 'click', '.fspjs-hide-account', function () {
			let _this = $( this );
			let menuDiv = _this.parent();
			let id = menuDiv.data( 'id' );
			let type = menuDiv.data( 'type' ) === 'account' ? 'account' : 'node';
			let hidden = menuDiv.data( 'hidden' ) ? 0 : 1;

			FSPoster.ajax( `hide_unhide_${ type }`, { id, hidden }, function () {
				$( '.fsp-tab.fsp-is-active' ).click();
			} );
		} ).on( 'keyup', '#fsp-node-search-input', function () {
			filterNodesByName( $( this ).val() );

			$( this ).siblings( 'i' ).removeClass( 'fa-search' ).addClass( 'fa-times' );
		} ).on( 'click', '.fsp-node-search > i', function () {
			$( '#fsp-node-search-input' ).val( '' ).trigger( 'keyup' );

			$( this ).removeClass( 'fa-times' ).addClass( 'fa-search' );
		} ).on( 'click', '.fsp-account-group-btns a:last-child', function ( e ) {
			e.preventDefault();
			FSPoster.upgrade( 'Purchase premium version to access all features.', true );
		} );

		$( '.fsp-tab.fsp-is-active' ).click();

		let component = $( '#fspComponent' );

		component.on( 'click', '.fsp-account-more', function () {
			let _this = $( this );
			let accountDiv = _this.parent().parent();
			let id = accountDiv.data( 'id' );
			let type = accountDiv.data( 'type' ) ? accountDiv.data( 'type' ) : 'account';
			let hidden = accountDiv.data( 'hidden' ) ? 1 : 0;

			if ( hidden )
			{
				$( '#fspMoreMenu > [data-type="hide"]' ).addClass( 'fsp-hide' );
				$( '#fspMoreMenu > [data-type="unhide"]' ).removeClass( 'fsp-hide' );
			}
			else
			{
				$( '#fspMoreMenu > [data-type="hide"]' ).removeClass( 'fsp-hide' );
				$( '#fspMoreMenu > [data-type="unhide"]' ).addClass( 'fsp-hide' );
			}

			if ( accountDiv.find( '.fsp-account-is-public' ).hasClass( 'fsp-hide' ) )
			{
				$( '#fspMoreMenu > [data-type="public"]' ).removeClass( 'fsp-hide' );
				$( '#fspMoreMenu > [data-type="private"]' ).addClass( 'fsp-hide' );
			}
			else
			{
				$( '#fspMoreMenu > [data-type="public"]' ).addClass( 'fsp-hide' );
				$( '#fspMoreMenu > [data-type="private"]' ).removeClass( 'fsp-hide' );
			}

			let top = _this.offset().top + 25 - $( window ).scrollTop();
			let left = _this.offset().left - ( $( '#fspMoreMenu' ).width() ) + 10;

			$( '#fspMoreMenu' ).data( 'hidden', hidden ).data( 'id', id ).data( 'type', type ).css( {
				top: top, left: left
			} ).show();
		} ).on( 'click', '.fsp-account-checkbox', function () {
			let _this = $( this );
			let accountDiv = _this.parent().parent();
			let id = accountDiv.data( 'id' );
			let type = accountDiv.data( 'type' ) ? _this.parent().parent().data( 'type' ) : 'account';

			if ( accountDiv.data( 'active' ) )
			{
				$( '#fspActivatesDiv' ).hide();
				$( '#fspDeactivatesDiv' ).show();
			}
			else
			{
				$( '#fspActivatesDiv' ).show();
				$( '#fspDeactivatesDiv' ).hide();
			}

			let top = _this.offset().top + 25 - $( window ).scrollTop();
			let left = _this.offset().left - ( $( '#fspActivateMenu' ).width() ) + 10;

			$( '#fspActivateMenu' ).data( 'id', id ).data( 'type', type ).css( { top: top, left: left } ).show();
		} ).on( 'click', '.fsp-account-caret', function () {
			let _this = $( this );
			let nodesDiv = _this.parent().parent().parent().find( '.fsp-account-nodes-container' );

			if ( nodesDiv.css( 'display' ) === 'none' )
			{
				nodesDiv.slideDown();
				_this.addClass( 'fsp-is-rotated' );
			}
			else
			{
				nodesDiv.slideUp();
				_this.removeClass( 'fsp-is-rotated' );
			}
		} );

		$( '#fspActivateMenu #fspActivateForAll' ).on( 'click', function () {
			let _this = $( this );
			let menuDiv = _this.parent().parent();
			let id = menuDiv.data( 'id' );
			let type = menuDiv.data( 'type' );
			let ajaxType = type === 'community' ? 'settings_node_activity_change' : 'account_activity_change';

			FSPoster.ajax( ajaxType, { id, checked: 1, for_all: 1 }, function ( result ) {
				if ( result.status === 'ok' )
				{
					$( '.fsp-tab.fsp-is-active' ).click();
				}
			} );
		} );

		$( '#fspActivateMenu #fspDeactivateForAll' ).on( 'click', function () {
			let _this = $( this );
			let menuDiv = _this.parent().parent();
			let id = menuDiv.data( 'id' );
			let type = menuDiv.data( 'type' );
			let ajaxAction = type === 'community' ? 'settings_node_activity_change' : 'account_activity_change';

			FSPoster.ajax( ajaxAction, { id, checked: 0, for_all: 1 }, function ( result ) {
				if ( result.status === 'ok' )
				{
					$( '.fsp-tab.fsp-is-active' ).click();
				}
			} );
		} );

		$('#fspActivateMenu #fspActivateConditionally').on('click', function (){
			FSPoster.upgrade( 'Purchase premium version to access all features.', true );
		});

		$( '#fspMoreMenu > #fspDelete' ).on( 'click', function () {
			let _this = $( this );
			let menuDiv = _this.parent();
			let id = menuDiv.data( 'id' );
			let type = menuDiv.data( 'type' );
			let accountDiv = $( `.fsp-account-item[data-id=${ id }][data-type="${ type }"]` );

			FSPoster.confirm( fsp__( 'Are you sure you want to delete?' ), function () {
				let ajaxAction = type === 'community' ? 'settings_node_delete' : 'delete_account';

				FSPoster.ajax( ajaxAction, { id }, function () {
					if ( type === 'community' )
					{
						$( '.fsp-tab.fsp-is-active' ).click();
					}
					else
					{
						$( '.fsp-tab.fsp-is-active' ).click();
					}
				} );
			} );
		} );

		$( '#fspAccountsFilterSelector' ).on( 'change', function () {
			let filter_by = $( this ).val();
			let url = window.location.href;

			filterAccounts( filter_by );

			if ( url.indexOf( 'filter_by' ) > -1 )
			{
				url = url.replace( /filter_by=([a-zA-Z]+)/, `filter_by=${ filter_by }` );
			}
			else
			{
				url += `${ ( url.indexOf( '?' ) > -1 ? '&' : '?' ) }filter_by=${ filter_by }`;
			}

			window.history.pushState( '', '', url );

			$.get( url );

			FSPObject.filter_by = filter_by;
		} );

		$( '#fspCollapseAccounts' ).on( 'click', function () {
			$( '.fsp-account-caret' ).click();

			const btnText = $( this ).find( 'span' ).text() === fsp__( 'COLLAPSE ALL' ) ? fsp__( 'EXPAND ALL' ) : fsp__( 'COLLAPSE ALL' );
			$( this ).find( 'span' ).text( btnText );
		} );

		doc.on( 'click', '.fsp-accounts-add-button', function () {
			let sn = $( this ).data( 'sn' );

			if ( ! ( sn.length > 0 ) )
			{
				return;
			}

			if ( ['vk', 'plurk', 'telegram'].includes(sn) )
			{
				FSPoster.loadModal( 'add_' + sn + '_account', {} );
			}
			else
			{
				window.open( `${ fspConfig.siteURL }/?fsp_add_account=${ sn.toLowerCase() }`, 'fs-app', 'width=750, height=550' );
			}
		} );
	} );
} )( jQuery );

function accountAdded (message = '', updateModal = false)
{
	if ( typeof jQuery !== 'undefined' )
	{
		$ = jQuery;
	}

	if(updateModal){
		let modalBody = $( '.fsp-modal-body' );

		if ( modalBody.length )
		{
			$( '.fsp-modal-footer' ).remove();

			modalBody.html( `<div class="fsp-modal-succeed"><div class="fsp-modal-succeed-image"><img src="${ FSPoster.asset( 'Base', 'img/success.svg' ) }"></div><div class="fsp-modal-succeed-text">${ fsp__( 'Account has been added successfully!' ) }</div><div class="fsp-modal-succeed-button"><button class="fsp-button" data-modal-close="true">${ fsp__( 'CLOSE' ) }</button></div></div>` );

			$( '.fsp-tab.fsp-is-active' ).click();
		}
	}
	else{
		if ( message !== '' ){
			FSPoster.alert(message);
		}
		else{
			$( '.fsp-tab.fsp-is-active' ).click();
		}
	}
}

function filterAccounts ( filter_by = 'all' )
{
	if ( typeof jQuery !== 'undefined' )
	{
		$ = jQuery;
	}

	if ( filter_by === 'all' )
	{
		$( '.fsp-account-item' ).show();
	}
	else if ( filter_by === 'active' )
	{
		$( '.fsp-account-item[data-active=1]' ).show();
		$( '.fsp-account-item[data-active=0]' ).slideUp( 100 );
	}
	else if ( filter_by === 'inactive' )
	{
		$( '.fsp-account-item[data-active=0]' ).show();
		$( '.fsp-account-item[data-active=1]' ).hide();
	}
	else if ( filter_by === 'visible' )
	{
		$( '.fsp-account-item[data-hidden=0]' ).show();
		$( '.fsp-account-item[data-hidden=1]' ).hide();
	}
	else if ( filter_by === 'hidden' )
	{
		$( '.fsp-account-item[data-hidden=1]' ).show();
		$( '.fsp-account-item[data-hidden=0]' ).hide();
	}
	else if ( filter_by === 'failed' )
	{
		$( '.fsp-account-item[data-failed=1]' ).show();
		$( '.fsp-account-item[data-failed=0]' ).hide();
	}

	setTimeout( function () {
		if ( $( '.fsp-account-item:visible' ).length === 0 )
		{
			$( '.fsp-emptiness' ).removeClass( 'fsp-hide' );
		}
		else
		{
			$( '.fsp-emptiness' ).addClass( 'fsp-hide' );
		}
	}, 200 );
}

function filterNodesByName ( query )
{
	if ( typeof jQuery !== 'undefined' )
	{
		$ = jQuery;
	}

	if ( query !== '' )
	{
		$( '.fsp-account-nodes .fsp-account-item' ).filter( function () {
			let _this = $( this );

			if ( _this.text().toLowerCase().indexOf( query ) > -1 )
			{
				_this.slideDown( 200 );
			}
			else
			{
				_this.slideUp( 200 );
			}
		} );
	}
	else
	{
		$( '.fsp-account-nodes .fsp-account-item' ).slideDown( 200 );
	}
}