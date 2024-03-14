/* global wppfm_setting_form_vars */
function wppfm_resetBackupsList() {
	var backupListData    = null;
	var listHtml          = '';
	var backupListElement = jQuery( '#wppfm-backups-list' );

	wppfm_getBackupsList(
		function( list ) {
			if ( '0' !== list ) {
				backupListData = JSON.parse( list );

				// convert the data to html code
				listHtml = wppfm_backupsTable( backupListData );
			} else {
				listHtml = wppfm_emptyBackupsTable();
			}

			backupListElement.empty(); // first clear the feed list.

			backupListElement.append( listHtml );
		}
	);
}

/**
 * Restores the options on the settings page
 */
function wppfm_resetOptionSettings() {
	wppfm_getSettingsOptions(
		function( optionsString ) {

			if ( optionsString ) {
				var options = JSON.parse( optionsString );

				jQuery( '#wppfm-auto-feed-fix-mode' ).prop( 'checked', options[ 0 ] === 'true' );
				jQuery( '#wppfm-background-processing-mode' ).prop( 'checked', options[ 3 ] === 'true' );

				jQuery( '#wppfm-third-party-attr-keys' ).val( options[ 1 ] );
				jQuery( '#wppfm-notice-mailaddress' ).val( options[ 2 ] );
			}
		}
	);
}

function wppfm_backupsTable( list ) {
	var htmlCode = '';

	for ( var i = 0; i < list.length; i ++ ) {

		var backup   = list[ i ].split( '&&' );
		var fileName = backup[ 0 ];
		var fileDate = backup[ 1 ];

		htmlCode += '<tr id="feed-row">';
		htmlCode += '<td id="file-name" value="' + fileName + '">' + fileName + '</td>';
		htmlCode += '<td id="file-date">' + fileDate + '</td>';
		htmlCode += '<td id="actions"><strong><a href="javascript:void(0);" id="wppfm-delete-' + fileName.replace('.', '-') + '-backup-action" onclick="wppfm_deleteBackupFile(\'' + fileName + '\')">' + wppfm_setting_form_vars.list_delete + ' </a>';
		htmlCode += '| <a href="javascript:void(0);" id="wppfm-restore-' + fileName.replace('.', '-') + '-backup-action" onclick="wppfm_restoreBackupFile(\'' + fileName + '\')">' + wppfm_setting_form_vars.list_restore + ' </a>';
		htmlCode += '| <a href="javascript:void(0);" id="wppfm-duplicate-' + fileName.replace('.', '-') + '-backup-action" onclick="wppfm_duplicateBackupFile(\'' + fileName + '\')">' + wppfm_setting_form_vars.list_duplicate + ' </a></strong></td>';
		htmlCode += '</tr>';
	}

	return htmlCode;
}

function wppfm_emptyBackupsTable() {
	var htmlCode = '';

	htmlCode += '<tr>';
	htmlCode += '<td colspan = 4>' + wppfm_setting_form_vars.no_backup + '</td>';
	htmlCode += '</tr>';

	return htmlCode;
}

/**
 * Document ready actions
 */
jQuery(
	function() {
		// fill the backups list
		wppfm_resetBackupsList();
	}
);
