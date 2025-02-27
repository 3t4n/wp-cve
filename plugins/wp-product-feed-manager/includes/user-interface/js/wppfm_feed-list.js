/*global wppfm_feed_list_form_vars */
function wppfm_fillFeedList() {
	var listHtml         = '';
	var feedListSelector = jQuery( '#wppfm-feed-list' );

	wppfm_getFeedList(
		function( result ) {
			var feedList = JSON.parse( result );
			var list     = feedList[ 'list' ];

			if ( '0' !== list ) {
				// convert the data to html code
				listHtml = wppfm_feedListTable( list );
			} else {
				listHtml = wppfm_emptyListTable();
			}

			feedListSelector.empty(); // first clear the feed list

			feedListSelector.append( listHtml );

			console.log( 'Feed List refreshed' );
		}
	);
}

function appendCategoryLists( channelId, language, isNew ) {
	var levelZeroSelector     = jQuery( '#lvl_0' );
	var categoryLevelSelector = jQuery( '#category-selector-lvl' );

	if ( isNew ) {
		wppfm_getCategoryListsFromString(
			channelId,
			'',
			language,
			function( categories ) {

				var list = JSON.parse( categories )[ 0 ];

				if ( list && list.length > 0 ) {
					levelZeroSelector.html( wppfm_categorySelectCntrl( list ) );
					levelZeroSelector.prop( 'disabled', false );
				} else {
					// as the user selected a free format, just show a text input control
					categoryLevelSelector.html( wppfm_freeCategoryInputCntrl( 'default', '0', false ) );
					categoryLevelSelector.prop( 'disabled', false );
				}
			}
		);
	}
}

function wppfm_resetFeedList() {
	wppfm_fillFeedList();
}

function wppfm_resetFeedStatus( feedData ) {
	wppfm_checkNextFeedInQueue(
		function() {
			wppfm_updateFeedRowStatus( feedData[ 'product_feed_id' ], parseInt( feedData[ 'status_id' ] ) );
			wppfm_updateFeedRowData( feedData );
		}
	);
}

function wppfm_feedListTable( list ) {
	var htmlCode = '';

	if ( ! list ) {
		return htmlCode;
	}

	for ( var i = 0; i < list.length; i ++ ) {
		var status       = list [ i ] [ 'status' ];
		var feedId       = list [ i ] [ 'product_feed_id' ];
		var feedUrl      = list [ i ] [ 'url' ];
		var feedReady    = 'on_hold' === status || 'ok' === status;
		var nrProducts   = '';
		var statusString = wppfm_list_status_text( status );

		if ( feedReady ) {
			nrProducts = list [ i ] [ 'products' ];
		} else if ( 'processing' === status ) {
			nrProducts = wppfm_feed_list_form_vars.processing_the_feed;
		} else if ( 'failed_processing' === status || 'in_processing_queue' === status ) {
			nrProducts = wppfm_feed_list_form_vars.unknown_text;
		}

		htmlCode += '<tr id="feed-row">';
		htmlCode += '<td id="title-' + feedId + '">' + list [ i ] [ 'title' ] + '</td>';
		htmlCode += '<td id="url">' + feedUrl + '</td>';
		htmlCode += '<td id="updated-' + feedId + '">' + list [ i ] [ 'updated' ] + '</td>';
		htmlCode += '<td id="products-' + feedId + '">' + nrProducts + '</td>';
		htmlCode += '<td id="type-' + feedId + '">' + list [ i ] [ 'feed_type_name' ] + '</td>';
		htmlCode += '<td id="feed-status-' + feedId + '" value="' + status + '" style="color: ' + list [ i ] [ 'color' ] + '"><strong>';
		htmlCode += statusString;
		htmlCode += '</strong></td>';
		htmlCode += '<td id="wppfm-feed-list-actions-for-feed-' + feedId + '">';

		if ( feedReady ) {
			htmlCode += feedReadyActions( feedId, feedUrl, status, list [ i ] [ 'title' ], list [ i ] [ 'feed_type_name' ], list [ i ] [ 'feed_type' ] );
		} else {
			htmlCode += feedNotReadyActions( feedId, feedUrl, list [ i ] [ 'title' ], list [ i ] [ 'feed_type' ] );
		}

		htmlCode += '</td>';
	}


	return htmlCode;
}

function feedReadyActions( feedId, feedUrl, status, title, feedTypeName, feedType ) {
	var fileExists   = 'No feed generated' !== feedUrl;
	var fileName     = feedUrl.lastIndexOf( '/' ) > 0 ? feedUrl.slice( feedUrl.lastIndexOf( '/' ) - feedUrl.length + 1 ) : title;
	var tabTitle     = feedType.replace( / /g, '-' ).toLowerCase();
	var actionId     = title.replace( / /g, '-' ).toLowerCase();
	var changeStatus = 'ok' === status ? wppfm_feed_list_form_vars.list_deactivate : wppfm_feed_list_form_vars.list_activate;

	var htmlCode = '<strong><a href="javascript:void(0);" id="wppfm-edit-' + actionId + '-action" onclick="parent.location=\'admin.php?page=wppfm-feed-editor-page&feed-type=' + tabTitle + '&id=' + feedId + '\'">' + wppfm_feed_list_form_vars.list_edit + ' </a>';
	htmlCode    += fileExists ? ' | <a href="javascript:void(0);" id="wppfm-view-' + actionId + '-action" onclick="wppfm_viewFeed(\'' + feedUrl + '\')">' + wppfm_feed_list_form_vars.list_view + '</a>' : '';
	htmlCode    += ' | <a href="javascript:void(0);" id="wppfm-delete-' + actionId + '-action" onclick="wppfm_deleteSpecificFeed(' + feedId + ', \'' + fileName + '\')">' + wppfm_feed_list_form_vars.list_delete + '</a>';
	htmlCode    += fileExists ? '<a href="javascript:void(0);" id="wppfm-deactivate-' + actionId + '-action" onclick="wppfm_deactivateFeed(' + feedId + ')" id="feed-status-switch-' + feedId + '"> | ' + changeStatus + '</a>' : '';
	htmlCode    += wppfmEndOfActionsCode( feedId, actionId, feedTypeName, title, feedType );
	return htmlCode;
}

function feedNotReadyActions( feedId, feedUrl, title, feedType ) {
	var fileName     = feedUrl.lastIndexOf( '/' ) > 0 ? feedUrl.slice( feedUrl.lastIndexOf( '/' ) - feedUrl.length + 1 ) : title;
	var tabTitle     = feedType.replace( / /g, '-' ).toLowerCase();
	var actionId     = title.replace( / /g, '-' ).toLowerCase();

	var htmlCode = '<strong>';
	htmlCode    += '<a href="javascript:void(0);" id="wppfm-edit-' + actionId + '-action" onclick="parent.location=\'admin.php?page=wppfm-feed-editor-page&feed-type=' + feedType + '&id=' + feedId + '\'">' + wppfm_feed_list_form_vars.list_edit + '</a>';
	htmlCode    += ' | <a href="javascript:void(0);" id="wppfm-delete-' + actionId + '-action" onclick="wppfm_deleteSpecificFeed(' + feedId + ', \'' + fileName + '\')"> ' + wppfm_feed_list_form_vars.list_delete + '</a>';
	htmlCode    += wppfmEndOfActionsCode( feedId, actionId, feedType, title );
	htmlCode    += wppfm_addFeedStatusChecker( feedId );
	return htmlCode;
}

function wppfmEndOfActionsCode( feedId, actionId, feedTypeName, title, feedType ) {
	var htmlCode = ' | <a href="javascript:void(0);" id="wppfm-duplicate-' + actionId + '-action" onclick="wppfm_duplicateFeed(' + feedId + ', \'' + title + '\')">' + wppfm_feed_list_form_vars.list_duplicate + '</a>';
	htmlCode += 'Product Feed' === feedType ? ' | <a href="javascript:void(0);" id="wppfm-regenerate-' + actionId + '-action" onclick="wppfm_regenerateFeed(' + feedId + ')">' + wppfm_feed_list_form_vars.list_regenerate + '</a>' : '';
	htmlCode += '</strong>';

	return htmlCode;
}

function wppfm_emptyListTable() {
	var htmlCode = '';

	htmlCode += '<tr>';
	htmlCode += '<td colspan = 4>' + wppfm_feed_list_form_vars.no_data_found + '</td>';
	htmlCode += '</tr>';

	return htmlCode;
}

function wppfm_updateFeedRowData( rowData ) {
	if ( rowData[ 'status_id' ] === '1' || rowData[ 'status_id' ] === '2' ) {
		var feedId = rowData[ 'product_feed_id' ];
		var status = rowData[ 'status_id' ] === '1' ? wppfm_feed_list_form_vars.ok : wppfm_feed_list_form_vars.other;

		jQuery( '#updated-' + feedId ).html( rowData[ 'updated' ] );
		jQuery( '#products-' + feedId ).html( rowData[ 'products' ] );
		jQuery( '#wppfm-feed-list-actions-for-feed-' + feedId ).html( feedReadyActions( feedId, rowData[ 'url' ], status, rowData[ 'title' ], rowData[ 'feed_type_name' ], rowData[ 'feed_type' ] ) );
	}
}

/**
 * Sorts a selected column.
 *
 * @since 2.38.0.
 * @since 3.2.0. reformatted to the new layout.
 * @param columnId Column id of the selected column.
 */
function wppfm_sortOnColumn( columnId ) {
	var tbody = jQuery( 'tbody#wppfm-feed-list' );
	var dataStorageElement = jQuery( '#wppfm-feed-list-page-data-storage' );
	var sortedColumn = dataStorageElement.data('wppfmSortColumn');
	var sortDirection = dataStorageElement.data('wppfmSortDirection');
	var sortedColumns = dataStorageElement.data('wppfmSortableColumns').split('-')

	// Reset the not selected columns to the starting position
	for ( var i=0; i<sortedColumns.length; i++) {
		if ( columnId.toString() !== sortedColumns[ i ] ) {
			wppfm_resetSortableColumns( sortedColumns[ i ] );
		}
	}

	// Sort the table rows
	tbody.find('tr').sort( function( a, b ) {
		var aElement = jQuery('td:nth-child(' + columnId + ')', a)
		var bElement = jQuery('td:nth-child(' + columnId + ')', b)
		dataStorageElement.data('wppfmSortColumn', columnId);

		if ( sortedColumn === columnId && ( 'asc' === sortDirection || 'none' === sortDirection ) ) {
			// Sort descending
			dataStorageElement.data('wppfmSortDirection', 'desc');
			return bElement.text().localeCompare( aElement.text(),false, { numeric: true } );
		} else {
			// Sort ascending
			dataStorageElement.data('wppfmSortDirection', 'asc');
			return aElement.text().localeCompare( bElement.text(), false, { numeric: true } );
		}

	}).appendTo( tbody );

	wppfm_setSortedColumn( columnId );

	wppfm_redrawAlternateRowBackground();
}

/**
 * Redraws the alternating light and dark table rows after sorting.
 */
function wppfm_redrawAlternateRowBackground() {
	jQuery( 'tbody#wppfm-feed-list tr:odd' ).removeClass('alternate');
	jQuery( 'tbody#wppfm-feed-list tr:even' ).addClass('alternate');
}

/**
 * Sets the correct classes for the column that has been selected to be sorted.
 *
 * @param columnId
 * @param selectedColumnId
 */
function wppfm_setSortedColumn( columnId ) {
	var sortColumnHeader = jQuery( 'table thead tr th:nth-child(' + columnId + ')' );

	if ( sortColumnHeader.hasClass( 'asc' ) ) {
		sortColumnHeader.removeClass( 'asc').addClass( 'desc' );
	} else {
		sortColumnHeader.removeClass( 'desc').addClass( 'asc' );
	}
	sortColumnHeader.removeClass( 'sortable' ).addClass( 'sorted' );
}

function wppfm_resetSortableColumns( columnId ) {
	var columnHeader = jQuery( 'table thead tr th:nth-child(' + columnId + ')' );
	columnHeader.removeClass('sorted' ).addClass( 'sortable' );
	columnHeader.removeClass('asc' ).addClass( 'desc' );
}

function wppfm_switchStatusAction( feedId, status ) {
	var feedName = jQuery( '#title-' + feedId ).html();
	var actionText = '';

	feedName = feedName.replace(/\s+/g, '-').toLowerCase(); // Replace spaces in the feed name by a dash.

	switch ( status ) {
		case '1':
			actionText = ' | Auto-off ';
			break;

		case '2':
			actionText = ' | Auto-on ';
			break;
	}

	jQuery( '#wppfm-deactivate-' + feedName + '-action' ).html( actionText );
}

function wppfm_list_status_text( status ) {
	switch ( status ) {
		case 'unknown':
			return wppfm_feed_list_form_vars.unknown;

		case 'ok':
			return wppfm_feed_list_form_vars.status_ok;

		case 'on_hold':
			return wppfm_feed_list_form_vars.on_hold;

		case 'processing':
			return wppfm_feed_list_form_vars.processing;

		case 'in_processing_queue':
			return wppfm_feed_list_form_vars.processing_queue;

		case 'has_errors':
			return wppfm_feed_list_form_vars.has_errors;

		case 'failed_processing':
			return wppfm_feed_list_form_vars.failed_processing;
	}
}

function wppfm_updateFeedRowStatus( feedId, status ) {
	var feedStatusSelector       = jQuery( '#feed-status-' + feedId );
	var feedStatusSwitchSelector = jQuery( '#feed-status-switch-' + feedId );
	var productsSelector         = jQuery( '#products-' + feedId );

	switch ( status ) {
		case 0: // unknown
			feedStatusSelector.html( '<strong>' + wppfm_feed_list_form_vars.unknown + '</strong>' );
			feedStatusSelector.css( 'color', '#6549F7' );
			feedStatusSwitchSelector.html( '' );
			break;

		case 1: // OK
			feedStatusSelector.html( '<strong>' + wppfm_feed_list_form_vars.ok + '</strong>' );
			feedStatusSelector.css( 'color', '#0073AA' );
			feedStatusSwitchSelector.html( ' | ' + wppfm_feed_list_form_vars.list_deactivate + ' ' );
			break;

		case 2: // On hold
			feedStatusSelector.html( '<strong>' + wppfm_feed_list_form_vars.on_hold + '</strong>' );
			feedStatusSelector.css( 'color', '#0173AA' );
			feedStatusSwitchSelector.html( ' | ' + wppfm_feed_list_form_vars.list_activate + ' ' );
			break;

		case 3: // Processing
			feedStatusSelector.html( '<strong>' + wppfm_feed_list_form_vars.processing + '</strong>' );
			feedStatusSelector.css( 'color', '#0000FF' );
			feedStatusSwitchSelector.html( '' );
			productsSelector.html( wppfm_feed_list_form_vars.processing_the_feed );
			break;

		case 4: // In queue
			feedStatusSelector.html( '<strong>' + wppfm_feed_list_form_vars.processing_queue + '</strong>' );
			feedStatusSelector.css( 'color', '#00CCFF' );
			feedStatusSwitchSelector.html( wppfm_feed_list_form_vars.list_activate + ' ' );
			break;

		case 5: // Has errors
			feedStatusSelector.html( '<strong>' + wppfm_feed_list_form_vars.has_errors + '</strong>' );
			feedStatusSelector.css( 'color', '#FF0000' );
			productsSelector.html( wppfm_feed_list_form_vars.unknown_text );
			feedStatusSwitchSelector.html( wppfm_feed_list_form_vars.list_activate + ' ' );
			break;

		case 6: // Failed processing
			feedStatusSelector.html( '<strong>' + wppfm_feed_list_form_vars.processing_failed + '</strong>' );
			feedStatusSelector.css( 'color', '#FF3300' );
			productsSelector.html( wppfm_feed_list_form_vars.unknown_text );
			feedStatusSwitchSelector.html( '' );
			break;

		case 7: // Channel not installed
			feedStatusSelector.html( '<strong>' + wppfm_feed_list_form_vars.no_channel + '</strong>' );
			feedStatusSelector.css( 'color', '#FF3300' );
			productsSelector.html( wppfm_feed_list_form_vars.unknown_text );
			feedStatusSwitchSelector.html( '' );
			break;
	}
}

/**
 * Document ready actions
 */
jQuery(
	function() {
		// No actions required at the moment.
	}
);
