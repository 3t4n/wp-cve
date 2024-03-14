/* eslint-disable no-var */
/* eslint-disable @typescript-eslint/no-unsafe-call */
/* eslint-disable @typescript-eslint/no-unsafe-return */
/* eslint-disable @typescript-eslint/no-unsafe-member-access */
/* eslint-disable @typescript-eslint/no-unsafe-assignment */
/* eslint-disable @typescript-eslint/restrict-plus-operands */
/* eslint-disable @typescript-eslint/prefer-regexp-exec */
( function ( $ ) {
	'use strict';

	function addTabNameToUrl( url, tabName ) {
		if ( ! tabName ) {
			return url.replace( /.?\btab=[^&]+/, '' );
		} //end if

		tabName = tabName.replace( 'nelio-content-', '' );
		if ( /\btab=/.test( url ) ) {
			url = url.replace( /\btab=[^&]+/, 'tab=' + tabName );
		} else if ( url.indexOf( '?' ) > 0 ) {
			url += '&tab=' + tabName;
		} else {
			url = '?tab=' + tabName;
		} //end if

		return url;
	} //end addTabNameToUrl()

	// Fix help buttons.
	$( 'img.nelio-content-help' ).click( function ( ev ) {
		ev.preventDefault();
		$( this ).closest( 'tr' ).find( '.setting-help' ).toggle();
	} );

	// Tab management.
	var $tabs = $( '.nav-tab' );
	$tabs.removeClass( 'nav-tab-active' );

	var $tabContents = $( '.tab-content' );
	$tabContents.hide();

	// Get the current tab.
	var $currentTab;
	var currentTabName = '';

	var matches = window.location.href.match( /\btab=([^&]+)/ );
	if ( matches && matches.length > 1 ) {
		currentTabName = 'nelio-content-' + matches[ 1 ];
		$currentTab = $( '#' + currentTabName );
		if ( ! $currentTab || 0 === $currentTab.length ) {
			currentTabName = '';
		} //end if
	} //end if

	if ( '' === currentTabName ) {
		$currentTab = $tabs.eq( 0 );
		currentTabName = $currentTab.attr( 'id' );
	} //end if

	// Set the current tab name, no matter what.
	var url = addTabNameToUrl( window.location.href, currentTabName );
	window.history.replaceState( {}, '', url );

	// Select the current tab.
	$currentTab.addClass( 'nav-tab-active' );
	$( '#' + currentTabName + '-tab-content' ).show();

	// Fix all links.
	$( 'h2.nav-tab-wrapper .nav-tab' ).each( function () {
		var $tab = $( this );
		$tab.attr( 'href', addTabNameToUrl( url, $tab.attr( 'id' ) ) );
	} );
} )( window.jQuery );
