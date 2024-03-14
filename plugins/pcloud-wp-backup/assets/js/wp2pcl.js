/**
 * PCloud WP backup plugin - JavaScript file.
 *
 * @package pcloud_wp_backup
 */

php_data      = (typeof php_data !== "undefined") ? php_data : {};
globalLang    = (typeof globalLang !== "undefined") ? globalLang : {};
pCloudGlobals = (typeof pCloudGlobals !== "undefined") ? pCloudGlobals : {};

let transl      = {};
let defaultLang = 'en';
let currentLang = defaultLang;

if (typeof String.prototype.contains === 'undefined') {
	String.prototype.contains = function (it) {
		return this.indexOf( it ) !== -1;
	};
}

function __(key, str, repl)
{
	if (currentLang in globalLang && key in globalLang[currentLang]) {
		return _repl( globalLang[currentLang][key] );
	} else if (defaultLang in globalLang && key in globalLang[defaultLang]) {
		return _repl( globalLang[defaultLang][key] );
	} else if (str) {
		return _repl( str );
	} else {
		return _repl( key );
	}

	function _repl(str)
	{
		for (let n in repl) {
			str = str.replace( '%' + n + '%', repl[n] );
		}

		return str;
	}
}

if (typeof jQuery !== "undefined") {
	$ = jQuery;
}

jQuery(
	function ($) {

		let pluginURL        = '';
		let wp2pcl_nonce     = '';
		let wp2pMainBlk      = $( '#wp2pcloud' );
		let wp2pcl_debugmode = false;

		if (wp2pMainBlk.length > 0) {
			let tmpLng = wp2pMainBlk.attr( 'data-lang' );
			if (tmpLng.match( /-/ )) {
				let tmpLngArr = tmpLng.split( '-' );
				currentLang   = tmpLngArr[0].toLowerCase();
			} else if (tmpLng.match( /_/ )) {
				let tmpLngArr = tmpLng.split( '_' );
				currentLang   = tmpLngArr[0].toLowerCase();
			} else {
				currentLang = tmpLng.toLowerCase();
			}
			if (currentLang.length !== 2) {
				currentLang = 'en';
			}

			pluginURL    = wp2pMainBlk.attr( 'data-pluginurl' );
			wp2pcl_nonce = wp2pMainBlk.attr( 'data-nonce' );
		}

		if (pluginURL.length > 0) {
			$.getJSON(
				pluginURL + "assets/translate.json",
				function (json) {
					if (typeof json['pcl_lang'] !== "undefined") {
						transl = json['pcl_lang'];
						setTranslations();
					}
				}
			);
		}

		const ajax_url        = 'admin-ajax.php?action=pcloudbackup';
		const api_url         = 'https://' + php_data['api_hostname'] + '/';
		let backupOffsetChunk = 0;
		let log_reader_timer  = 2000; // ms.

		/**
		 * LOGIN PROCEDURE!
		 */

		if ($( '.wp2pcloud-login-succcess' ).length > 0) {

			let newURL = top.location.href.slice( 0, top.location.href.indexOf( '&' ) );

			window.setTimeout(
				function () {
					top.location.href = newURL;
				},
				4000
			);

		}

		/**
		 * Apply translations to the elements
		 */
		function setTranslations()
		{
			$( '.pcl_transl' ).each(
				function (i, elm) {

					if (typeof transl[currentLang] === "undefined") {
						return false;
					}

					let tr_key = $( elm ).attr( 'data-i10nk' );
					if ( typeof tr_key !== "undefined" && tr_key.length > 0 ) {
						if ( typeof transl[currentLang][tr_key] !== "undefined" ) {
							if (transl[currentLang][tr_key].length > 0) {
								$( elm ).html( transl[currentLang][tr_key] );
							}
						}
					}

					if (transl[currentLang]['plugin_menu_name'].length > 0) {
						$( '#toplevel_page_b2pcloud_settings' ).find( '.wp-menu-name' ).html( transl[currentLang]['plugin_menu_name'] );
					}
				}
			);
		}

		/**
		 * SET INCLUDE DATABASE IN THE BACKUP ->
		 * SET INCLUDE DATABASE IN THE BACKUP ->
		 */
		$( '#wp2pcl_withmysql' ).on(
			'change',
			function ( e ) {
				e.preventDefault();
				$( '#setting-error-mysql-settings_updated' ).show();
				$.post(
					ajax_url + '&method=set_with_mysql',
					$( '#wp2_incl_db_form' ).serialize(),
					function () {
						$.get( 'admin.php' );
					},
					'JSON'
				);
			}
		);

		/**
		 * SET SCHEDULE INTERVAL ->
		 * SET SCHEDULE INTERVAL ->
		 */
		$( '#wp2pcloud_form' ).submit(
			function ( e ) {
				e.preventDefault();
				$( '#setting-error-settings_updated' ).show();
				$.post(
					ajax_url + '&method=set_schedule',
					$( this ).serialize(),
					function () {
						$.get( 'admin.php' );
					},
					'JSON'
				);
			}
		);

		/**
		 * RESTORE PROCEDURE ->
		 * RESTORE PROCEDURE ->
		 */
		$( 'body' ).on(
			'click',
			'.backup-file',
			(e) =>
			{
				let btn = $( e.currentTarget );

				btn.attr( 'disabled', 'disabled' );
				btn.addClass( 'disabled' );

				const fileId = parseInt( btn.attr( 'data-file-id' ) );
				const size   = parseInt( btn.attr( 'data-file-size' ) );

				if (confirm( 'Are you sure?' )) {
					$.getJSON(
						ajax_url + "&method=check_can_restore",
						(data) =>
						{
							if (data['status'] !== "undefined") {
								if (parseInt( data['status'] ) === 0) {
									$.post(
										ajax_url + '&method=restore_archive',
										{
											'file_id': fileId,
											'size': size,
											'wp2pcl_nonce': wp2pcl_nonce
										}
									);
								} else {
									$( '#message' ).html( data['msg'] );

									btn.attr( 'disabled', false );
									btn.removeClass( 'disabled' );
								}
							}
						}
					);
				}
			}
		);

		/**
		 * BACKUP PROCEDURE ->
		 * BACKUP PROCEDURE ->
		 */
		let makeBackUpBtn         = $( '#run_wp_backup_now' );
		makeBackUpBtn.on(
			'click',
			() =>
			{
				backupOffsetChunk = 0;

				backupLogWin.show();

				if (makeBackUpBtn.hasClass( 'disabled' )) {
					return false;
				}

				makeBackUpBtn.attr( 'disabled', 'disabled' );
				makeBackUpBtn.addClass( 'disabled' );

				if (makeBackUpBtn.hasClass( 'disabled' )) {
					$.post(
						ajax_url + '&method=start_backup',
						{
							'wp2pcl_nonce': wp2pcl_nonce
						},
						() =>
						{
							makeBackUpBtn.attr( 'disabled', false );
							makeBackUpBtn.removeClass( 'disabled' );
						}
					);
				}

				$( 'html, body' ).animate(
					{
						scrollTop: 0
					},
					'slow'
				);
			}
		);

		makeBackUpBtn.on(
			'dblclick',
			() =>
			{
				$.post(
					ajax_url + '&method=start_backup',
					{
						'wp2pcl_nonce': wp2pcl_nonce
					}
				);
			}
		);

		/**
		 * CHECK ACTIVITY ->
		 * CHECK ACTIVITY ->
		 */
		let backupLogWin = $( '.log_show' );

		function pclCheckActivity( recheck )
		{
			$.ajax(
				{
					url: ajax_url + "&method=get_log&dbg=" + wp2pcl_debugmode + "&wp2pcl_nonce=" + wp2pcl_nonce,
					dataType: 'json',
					method: 'GET',
					timeout: 60000 // 30 second timeout
				}
			).error(
				function ( a, b ) {
					console.warn( 'Error reading the log: ', a, b );
				}
			).done(
				function (data) {

					log_reader_timer = 2000;

					backupLogWin.html( data.log );

					if (parseInt( data['hasactivity'] ) === 0) {
						backupLogWin.hide();
					} else {
						backupLogWin.show();
					}

					if ( typeof data['operation'] !== "undefined" && typeof data['operation']['operation'] !== "undefined" ) {
						if ( data['operation']['operation'] === 'upload' || data['operation']['operation'] === 'download' ) {
							backupLogWin.show();
						}
					}

					if ( wp2pcl_debugmode === true ) {
						backupLogWin.show();
					}

					if (typeof data['perc'] !== "undefined") {

						let percHTML    = '';
						let percInt     = parseInt( data['perc'] );
						let backupBtns  = $( '.backup-file' );
						let hasActivity = false;

						if (typeof data['operation'] !== "undefined") {
							if (data['operation']['operation'] === 'upload') {
								percHTML         = '<div><span class="pcl_transl" data-i10nk="uploading2pcloud">Uploading to pCloud</span> ( ' + data['sizefancy'] + ' ): </div>'
								log_reader_timer = 500;
								hasActivity      = true;
							} else if (data['operation']['operation'] === 'download') {
								percHTML         = '<div><span class="pcl_transl" data-i10nk="downloadingFpcloud">Downloading from pCloud</span> ( ' + data['sizefancy'] + ' ): </div>';
								log_reader_timer = 500;
								hasActivity      = true;
							}
						}

						if (hasActivity) {
							makeBackUpBtn.attr( 'disabled', 'disabled' );
							makeBackUpBtn.addClass( 'disabled' );
							backupBtns.attr( 'disabled', 'disabled' );
							backupBtns.addClass( 'disabled' );
						} else {
							makeBackUpBtn.attr( 'disabled', false );
							makeBackUpBtn.removeClass( 'disabled' );
							backupBtns.attr( 'disabled', false );
							backupBtns.removeClass( 'disabled' );
						}

						percHTML += '' +
							'<div class="d-flex pclprogressbar"><span class="pclpr-l">0% [&nbsp;&nbsp;</span><span class="pclpr-c">';

						let symbols = '';
						for (let i = 0; i < 101; i++) {
							if (i < percInt) {
								symbols += '===';
							}
						}

						let percWidth = parseFloat( data['perc'] ) - 5;
						if (percInt > 80) {
							percWidth = parseFloat( data['perc'] ) - 3;
						}
						if (percInt > 90) {
							percWidth = parseFloat( data['perc'] ) - 2;
						}
						if (percInt > 96) {
							percWidth = parseFloat( data['perc'] ) - 1;
						}

						percHTML += '<strong class="perc-line" style="width: ' + percWidth + '%">' + symbols + '</strong>';

						if ( percInt < 98 ) {
							percHTML += '<strong>=>&nbsp;[ ' + data['perc'] + '% ]</strong>';
						} else {
							percHTML += '<strong>=></strong>';
						}

						percHTML += '</span><span class="pclpr-r">&nbsp;&nbsp;] 100%</span></div>';
						backupLogWin.append( percHTML );

					}

					if (typeof data['quotaperc'] !== "undefined") {

						let quotaLeftPercent = parseFloat( data['quotaperc'] );

						let noSpaceDiv = $( '.pcl-low-spaceleft' );

						if (quotaLeftPercent < 90) {
							if ($( '#wp2pcloud-login-form' ).length < 1) {
								let incrSpaceLink = 'https://www.pcloud.com/' + currentLang + '/cloud-storage-pricing-plans.html?period=lifetime';
								if (noSpaceDiv.length < 1) {
									noSpaceDiv = $( '<div />' );
									noSpaceDiv.addClass( 'pcl-low-spaceleft' );
									noSpaceDiv.addClass( 'notice' );
									noSpaceDiv.addClass( 'notice-warning' );
									noSpaceDiv.addClass( 'is-dismissible' );
									noSpaceDiv.append( '<p><span class="pcl_transl" data-i10nk="no_space_left">You are running out of space! Click on the button below to increase your storage space!</span><br/><a href="' + incrSpaceLink + '" target="_blank" class="button">pCloud plans</a></p>' );
									noSpaceDiv.insertAfter( '#wp2pcloud-error' );
								}
							}
						}

						if (parseFloat( data['quotaperc'] ) > 90) {
							if (noSpaceDiv.length > 0) {
								noSpaceDiv.remove();
							}
						}
					}

					setTranslations();
				}
			).always(
				function () {
					if ( typeof recheck !== "undefined" && recheck ) {
						window.setTimeout(
							function () {
								pclCheckActivity( true );
							},
							log_reader_timer
						);
					}
				}
			);

		}

		pclCheckActivity( true );

		/**
		 * UNLINK / LOGOUT FROM PCLOUD PROCEDURE ->
		 * UNLINK / LOGOUT FROM PCLOUD PROCEDURE ->
		 */
		$( '.wpb2pcloud_unlink_account' ).on(
			'click',
			function () {
				$.post(
					ajax_url + '&method=unlink_acc',
					{'wp2pcl_nonce': wp2pcl_nonce},
					function () {
						top.location.href = top.location.href.trim();
					}
				);
			}
		);

		/**
		 * TOGGLE DEBUG STATE ->
		 */
		$( '#pcl_dbg_tgl' ).on(
			'click',
			function () {
				let btn = $( this );
				if ( wp2pcl_debugmode === true ) {
					wp2pcl_debugmode = false;
					btn.css( 'color', '#CCC' );
					backupLogWin.hide();
				} else {
					wp2pcl_debugmode = true;
					btn.css( 'color', '#333' );
					pclCheckActivity();
					backupLogWin.show();
				}
			}
		);

		/**
		 * GET FREE SPACE ->
		 * GET FREE SPACE ->
		 */
		if ($( '#pcloud_info' ).length !== 0) {

			$.ajax(
				{
					url: ajax_url + "&method=userinfo&dbg=" + wp2pcl_debugmode + "&wp2pcl_nonce=" + wp2pcl_nonce,
					type: "GET",
					crossDomain: true,
					dataType: 'json',
					success: function (data) {
						if (typeof data['status'] === "undefined") {
							_display_error( 'No data received from the pCloud server!\n Please, try again later!' );
							window.setTimeout(
								function () {
									$( '.wpb2pcloud_unlink_account' ).trigger( 'click' );
								},
								30000
							);
						} else if (typeof data['status'] !== "undefined" && parseInt( data['status'] ) !== 0) {
							_display_error( data['error'] );
							window.setTimeout(
								function () {
									$( '.wpb2pcloud_unlink_account' ).trigger( 'click' );
								},
								30000
							);
						} else {

							let info_cnt = _humanFileSize( data['data']['quota'] - data['data']['usedquota'], 1024 );
							info_cnt    += ' <span class="pcl_transl" data-i10nk="free_space_av"> free space available</span>,';
							info_cnt    += '<span style="padding-left: 10px">Account: ' + data['data']['email'] + '</span>';
							$( '#pcloud_info' ).html( info_cnt );

							setTranslations();
						}
					}
				}
			);
		}

		/**
		 * GET BACKUP FILES ->
		 * GET BACKUP FILES ->
		 */
		let backupsArea          = $( '#pcloudListBackups' );
		let getBackupsFromPcloud = function () {

			if (backupsArea.length === 0) {
				return false;
			}

			$.getJSON(
				ajax_url + "&method=listfolder&dbg=" + wp2pcl_debugmode + "&wp2pcl_nonce=" + wp2pcl_nonce,
				{},
				(data) =>
				{
					if ( typeof data.status !== "undefined" && parseInt( data.status ) === 0 && typeof data.contents !== "undefined" ) {

						backupsArea.html( '' );

						for ( const [i, item] of Object.entries( data.contents ) ) {

							if (item['contenttype'] !== "application/zip" || typeof data['folderid'] === "undefined") {
								return true;
							}

							let myDate     	  = new Date( item['created'] );
							let dformat       = myDate.toLocaleDateString() + " " + myDate.toLocaleTimeString();
							let download_link = 'https://my.pcloud.com/#folder=' + data['folderid'] + '&page=filemanager';

							const html = '<tr>' +
								'<td><a target="blank_" href="' + download_link + '">' + dformat + '</a></td>' +
								'<td>' + _humanFileSize( item['size'], 1024 ) + '</td>' +
								'<td><button type="button" data-file-id="' + item['fileid'] + '" data-file-size="' + item['size'] + '" ' +
								'       class="button backup-file pcl_transl" data-i10nk=\'restore_backup\'>Restore backup</button></td>' +
								'<td><a href="' + download_link + '" target="_blank" class="button pcl_transl" data-i10nk=\'download\'>Download</a></td>' +
								'</tr> ';
							backupsArea.append( html );
						}
					}
				}
			);
			setTimeout( getBackupsFromPcloud, 30000 );
		};

		getBackupsFromPcloud();

		/**
		 * Display human-readable file size
		 *
		 * @param {number} bytes
		 * @param {number} si
		 *
		 * @returns {string}
		 * @private
		 */
		function _humanFileSize(bytes, si)
		{
			let thresh = si ? 1024 : 1024;
			if (bytes < thresh) {
				return bytes + ' B';
			}
			let units = si ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'] : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
			let u     = -1;
			do {
				bytes /= thresh;
				++u;
			} while (bytes >= thresh);
			return bytes.toFixed( 1 ) + ' ' + units[u];
		}

		/**
		 * Display error message.
		 *
		 * @param {string} msg
		 *
		 * @private
		 */
		let _display_error = function ( msg ) {
			let errorBlk = $( '#wp2pcloud-error' );
			errorBlk.find( 'p' ).html( msg );
			errorBlk.show();

			window.setTimeout(
				function () {
					errorBlk.hide( 'fast' );
				},
				10000
			);
		}
	}
);
