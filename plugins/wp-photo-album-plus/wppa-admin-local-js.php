<?php
/* wppa-admin-local-js.php
* Package: wp-photo-album-plus
*
* local js code for admin pages
* Version 8.5.03.003
*
*/

function wppa_add_local_js( $slug, $arg1 = '', $arg2 = '' ) {

	switch( $slug ) {

		case 'wppa_album_photos':
			{
				$the_js = "
				function wppaTryMove( id, video ) {

					var query;

					if ( ! jQuery( '#target-' + id ).val() ) {
						alert( '" . esc_js( __( 'Please select an album to move to first.', 'wp-photo-album-plus' ) ) . "' );
						return false;
					}

					if ( video ) {
						query = '" . esc_js( __( 'Are you sure you want to move this video?', 'wp-photo-album-plus' ) ) . "';
					}
					else {
						query = '" . esc_js( __( 'Are you sure you want to move this photo?', 'wp-photo-album-plus' ) ) . "';
					}

					if ( confirm( query ) ) {
						wppaAjaxUpdatePhoto( id, 'moveto', document.getElementById( 'target-' + id ).value );
					}
				}

				function wppaTryCopy( id, video ) {

					var query;

					if ( ! jQuery( '#target-' + id ).val() ) {
						alert( '" . esc_js( __( 'Please select an album to copy to first.', 'wp-photo-album-plus' ) ) . "' );
						return false;
					}

					if ( video ) {
						query = '" . esc_js( __( 'Are you sure you want to copy this video?', 'wp-photo-album-plus' ) ) . "';
					}
					else {
						query = '" . esc_js( __( 'Are you sure you want to copy this photo?', 'wp-photo-album-plus' ) ) . "';
					}

					if ( confirm( query ) ) {
						wppaAjaxUpdatePhoto( id, 'copyto', document.getElementById( 'target-' + id ).value );
					}
				}

				function wppaTryDelete( id, video, perm ) {

					var query;

					if ( perm ) {
						if ( video ) {
							query = '" . esc_js( __( 'Are you sure you want to remove this video permanently?', 'wp-photo-album-plus' ) ) . "';
						}
						else {
							query = '" . esc_js( __( 'Are you sure you want to remove this photo permanently?', 'wp-photo-album-plus' ) ) . "';
						}
					}
					else {
						if ( video ) {
							query = '" . esc_js( __( 'Are you sure you want to delete this video?', 'wp-photo-album-plus' ) ) . "';
						}
						else {
							query = '" . esc_js( __( 'Are you sure you want to delete this photo?', 'wp-photo-album-plus' ) ) . "';
						}
					}

					if ( confirm( query ) ) {
						if ( perm ) {
							wppaAjaxDeletePhoto( id, '', '', true );
						}
						else {
							wppaAjaxDeletePhoto( id );
						}
						jQuery( window ).trigger( 'scroll' );
					}
				}

				function wppaTryUndelete ( id ) {
					wppaAjaxUndeletePhoto( id );
				}

				function wppaTryRotLeft( id ) {

					var query = '" . esc_js( __( 'Are you sure you want to rotate this photo left?', 'wp-photo-album-plus' ) ) . "';

					if ( confirm( query ) ) {
						wppaAjaxUpdatePhoto( id, 'rotleft', 0 );
					}
				}

				function wppaTryRot180( id ) {

					var query = '" . esc_js( __( 'Are you sure you want to rotate this photo 180&deg;?', 'wp-photo-album-plus' ) ) . "';

					if ( confirm( query ) ) {
						wppaAjaxUpdatePhoto( id, 'rot180', 0 );
					}
				}

				function wppaTryRotRight( id ) {

					var query = '" . esc_js( __( 'Are you sure you want to rotate this photo right?', 'wp-photo-album-plus' ) ) .  "';

					if ( confirm( query ) ) {
						wppaAjaxUpdatePhoto( id, 'rotright', 0 );
					}
				}

				function wppaTryFlip( id ) {

					var query = '" . esc_js( __( 'Are you sure you want to flip this photo?', 'wp-photo-album-plus' ) ) . "';

					if ( confirm( query ) ) {
						wppaAjaxUpdatePhoto( id, 'flip', 0 );
					}
				}

				function wppaTryFlop( id ) {

					var query = '" . esc_js( __( 'Are you sure you want to flip this photo?', 'wp-photo-album-plus' ) ) . "';

					if ( confirm( query ) ) {
						wppaAjaxUpdatePhoto( id, 'flop', 0 );
					}
				}

				function wppaTryWatermark( id, hasSource, canRemove ) {

					var query;
					var wmFile = jQuery( '#wmfsel_' + id ).val();
					if ( wmFile == '--- none ---' ) {
						alert( '" . esc_js( __( 'No watermark selected', 'wp-photo-album-plus' ) ) . "' );
						return;
					}
					if ( hasSource ) {
						query = '" . esc_js( __( 'Are you sure?', 'wp-photo-album-plus' ) ) . "';
						query += '. ';
						query += '" . esc_js( __( 'To revert to the default watermark setting afterwards: select Watermark: --- default --- and press the Remake files button', 'wp-photo-album-plus' ) ) . "';
						if ( canRemove ) {
							query += '. ';
							query += '" . esc_js( __( 'To remove: select Watermark: --- none --- and press the Remake files button', 'wp-photo-album-plus' ) ) . "';
						}
					}
					else {
						query = '" . esc_js( __( 'Are you sure? Once applied it can not be removed!', 'wp-photo-album-plus' ) ) . "';
						query += '. ';
						query += '" . esc_js( __( 'And I do not know if there is already a watermark on this photo', 'wp-photo-album-plus' ) ) . "';
					}

					if ( confirm( query ) ) {
						wppaAjaxApplyWatermark( id, document.getElementById( 'wmfsel_' + id ).value, document.getElementById( 'wmpsel_' + id ).value );
					}
				}

				function wppaTryMagick( id, slug, value ) {

					if ( ! value ) {
						value = 0;
					}

					var query = '" . esc_js( __( 'Are you sure you want to magically process this photo?', 'wp-photo-album-plus' ) ) .  "';

					if ( true || confirm( query ) ) {
						jQuery( '#wppa-admin-spinner' ).css( 'display', 'inline' );
						wppaAjaxUpdatePhoto( id, slug, value );
					}
				}

				wppaHor = false;
				function wppaToggleHorizon() {
					if ( wppaHor ) {
						jQuery( '#wppa-horizon' ).css( 'display', 'none' );
						wppaHor = false;
					}
					else {
						jQuery( '#wppa-horizon' ).css( 'display', 'inline' );
						wppaHor = true;
					}
				}

				function wppaTryScheduledel( id ) {
					wppaPhotoStatusChange( id );
					if ( ! jQuery( '#scheduledel-' + id ).prop( 'checked' ) ) {
						wppaAjaxUpdatePhoto( id, 'removescheduledel', 0 );
					}
				}
				";

				// The script for the tabs to operate
				$the_js .= '
				function wppaChangePhotoAdminTab(elm,tabId,itemId) {
					jQuery(".wppa-photoadmin-tab-"+itemId).removeClass(\'active\');
					jQuery(elm).addClass(\'active\');
					jQuery(".wppa-tabcontent-"+itemId).hide();
					jQuery(tabId).show();
					setTimeout(function(){jQuery(window).trigger("resize");},200);
				}';

				// The script for pdf to album conversion
				$the_js .= '
				function wppaConvertToAlbum(id,paging,contin,stop) {
					var ajaxurl = wppaAjaxUrl + "?action=wppa&wppa-action=pdftoalbum&photo-id=" + id + "&wppa-nonce=" + jQuery( "#photo-nonce-" + id ).val();
						if (contin) ajaxurl += "&continue=true";
						if (stop) ajaxurl += "&stop=true";
					jQuery.ajax({ 	url: 		ajaxurl,
									async: 		true,
									type: 		"GET",
									timeout: 	600000,
									beforeSend: function( xhr ) {
										jQuery("#remark-"+id).html("'.__('Converting, please wait', 'wp-photo-album-plus').'");
									},
									success: 	function( xresult, status, xhr ) {
										console.log("succes "+xresult);
										jQuery("#remark-"+id).html(xresult);
									},
									error:		function( xhr, status, error ) {
										console.log("error "+status+" "+error);
										jQuery("#remark-"+id).html(error);
									},
									complete: 	function( xhr, status, newurl ) {
										console.log("complete "+status);
										console.log(status);
									}
					});
				}';
			}
			break;

		case 'wppa_album_photos_bulk':
			{
				$the_js = "
				function wppaTryMove( id, video ) {

					var query;

					if ( ! jQuery( '#target-' + id ).val() ) {
						alert( '" . esc_js( __( 'Please select an album to move to first.', 'wp-photo-album-plus' ) ) . "' );
						return false;
					}

					if ( video ) {
						query = '" . esc_js( __( 'Are you sure you want to move this video?', 'wp-photo-album-plus' ) ) . "';
					}
					else {
						query = '" . esc_js( __( 'Are you sure you want to move this photo?', 'wp-photo-album-plus' ) ) . "';
					}

					if ( ! jQuery('#confirm-move').prop('checked') || confirm( query ) ) {
						jQuery( '#moving-' + id ).html( '". __( 'Moving...', 'wp-photo-album-plus' ) . "' );
						wppaAjaxUpdatePhoto( id, 'moveto', jQuery( '#target-' + id ).val(), false, '<td colspan=\'8\' >', '</td>' );
					}
				}" . '
				jQuery(document).ready( function() {
					wppaSetConfirmDelete( "confirm-delete" );
					wppaSetConfirmMove( "confirm-move" );
				});
				function wppaConfirmAndDelete(id, immediate) {
					if ( ! jQuery("#confirm-delete").prop("checked") ||
						 confirm( "' . esc_js( __( 'Are you sure you want to delete this photo?', 'wp-photo-album-plus' ) ) . '" ) ) {
							jQuery("#wppa-delete-"+id).html( "' . esc_js( __('Deleting...', 'wp-photo-album-plus' ) ) . '" );
							wppaAjaxDeletePhoto(id, "<td colspan=8 >", "</td>", immediate);
					}
				}';
			}
			break;

		case 'wppa_album_photos_sequence':
			{
				$the_js = '
				jQuery( function() {
					jQuery( "#sortable" ).sortable( {
						cursor: "move",
						placeholder: "sortable-placeholder-photos",
						stop: function( e, ui ) {
							var ids = jQuery( ".wppa-sort-item" );
							var seq = jQuery( ".wppa-sort-seqn" );
							var idx = 0;
							while ( idx < ids.length ) {
								var newvalue;
								if ( wppaSeqnoDesc ) {
									newvalue = ids.length - idx;
								}
								else newvalue = idx + 1;
								var oldvalue = seq[idx].value;
								var photo = ids[idx].value;
								if ( newvalue != oldvalue ) {
									wppaDoSeqUpdate( photo, newvalue );
								}
								idx++;
							}
						},
						delay: 		100,
						opacity: 	0.5,
						scroll: 	false,
						cursorAt: 	{ left: 90, top: 90 }
					});
					jQuery( "#sortable" ).disableSelection();
				});
				var wppaAjaxRequests = 0;
				function wppaDoSeqUpdate( photo, seqno ) {
					if ( wppaAjaxRequests >=10 ) {
						setTimeout(function(){wppaDoSeqUpdate( photo, seqno );},500);
						return;
					}
					var data = "action=wppa&wppa-action=update-photo&photo-id="+photo+"&item=p_order&wppa-nonce="+document.getElementById(\'photo-nonce-\'+photo).value+"&value="+seqno;
					var xmlhttp = new XMLHttpRequest();

					xmlhttp.onreadystatechange = function() {
						if ( xmlhttp.readyState == 4 && xmlhttp.status != 404 ) {
							var ArrValues = xmlhttp.responseText.split( "||" );
							if ( ArrValues[0] != "" ) {
								alert("The server returned unexpected output:\n"+ArrValues[0]);
							}
							switch ( ArrValues[1] ) {
								case "0":	// No error
									jQuery("#wppa-seqno-"+photo).html(seqno);
									break;
								case "99":	// Photo is gone
									jQuery("#wppa-seqno-"+photo).html(\'<span style="color:red">deleted</span>\');
									break;
								default:	// Any error
									jQuery("#wppa-seqno-"+photo).html(\'<span style="color:red">Err:"+ArrValues[1]+"</span>\');
									break;
							}
							wppaAjaxRequests--;
							wppaConsoleLog("Pending ajax requests = "+wppaAjaxRequests);
						}
					}
					xmlhttp.open("POST",wppaAjaxUrl,true);
					xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
					xmlhttp.send( data );
					wppaAjaxRequests++;
					wppaConsoleLog("Pending ajax requests = "+wppaAjaxRequests);
					jQuery("#wppa-sort-seqn-"+photo).val(seqno);	// set hidden value to new value to prevent duplicate action
					var spinnerhtml = \'<img src="\'+wppaImageDirectory+\'spinner.gif" />\';
					jQuery("#wppa-seqno-"+photo).html(spinnerhtml);
				}';
			}
			break;

		case '_wppa_admin':
			{
				$the_js = '
				function wppaChangeAlbumAdminTab(elm,tabid) {
					jQuery(".wppa-albumadmin-tab").removeClass(\'active\');
					jQuery(elm).addClass(\'active\');
					jQuery(".wppa-tabcontent").hide();
					jQuery(tabid).show();
				}
				function wppaGoEditAlbNo() {
					var id = parseInt(document.getElementById("wppa-edit-albid").value);
					if (id > 0) {
						var nonce = "' . wp_create_nonce( 'wppa-nonce' ) .'";
						var href = "' . get_admin_url() . 'admin.php?page=wppa_admin_menu&wppa-nonce="+nonce+"&tab=edit&edit-id="+id;
						document.location.href=href;
					}
					else {
						alert("' . __( 'Please enter a valid album id', 'wp-photo-album-plus' ) . '");
					}
				}
				function wppaGoApplyFilter() {
					var filter = document.getElementById("wppa-edit-filter").value;
					if(filter) {
						document.location.href="' . get_admin_url() . 'admin.php?page=wppa_admin_menu&switchto=flat&filter="+filter;
					}
					else {
						alert("' . __( 'Please select a filter token', 'wp-photo-album-plus' ) . '");
					}
				}
				function wppaTryInheritCats( id ) {

					var query = "' . esc_js( __( 'Are you sure you want to inherit categories to all (sub-)sub albums of this album?', 'wp-photo-album-plus' ) ) . '";
					if ( confirm( query ) ) {
						wppaAjaxUpdateAlbum( id, \'inherit_cats\', Math.random() );
					}
				}
				function wppaTryAddCats( id ) {

					var query = "' . esc_js( __( 'Are you sure you want to add the categories to all (sub-)sub albums of this album?', 'wp-photo-album-plus' ) ) . '";
					if ( confirm( query ) ) {
						wppaAjaxUpdateAlbum( id, \'inhadd_cats\', Math.random() );
					}
				}
				function wppaTryApplyDeftags( id ) {

					var query = "' . esc_js( __( 'Are you sure you want to set the default tags to all photos in this album?', 'wp-photo-album-plus' ) ) . '";
					if ( confirm( query ) ) {
						wppaAjaxUpdateAlbum( id, \'set_deftags\', Math.random(), true );
					}
				}
				function wppaTryAddDeftags( id ) {

					var query = "' . esc_js( __( 'Are you sure you want to add the default tags to all photos in this album?', 'wp-photo-album-plus' ) ) . '";
					if ( confirm( query ) ) {
						wppaAjaxUpdateAlbum( id, \'add_deftags\', Math.random(), true );
					}
				}
				function wppaTryScheduleAll( id ) {

					var query;
					if ( ! jQuery( "#schedule-box" ).prop( "checked" ) ) {
						query = "' . esc_js( __( 'Please switch feature on and set date/time to schedule first', 'wp-photo-album-plus' ) ) . '";
						alert( query );
						return;
					}
					query = "' . esc_js( __( 'Are you sure you want to schedule all photos in this album?', 'wp-photo-album-plus' ) ) . '";
					if ( confirm( query ) ) {
						wppaAjaxUpdateAlbum( id, \'setallscheduled\', Math.random(), true );
					}
				}
				function wppaTryScheduledelAlb( id ) {

					wppaAjaxUpdateAlbum( id, "scheduledel", Math.random() );
					if ( ! jQuery( "#scheduledel" ).prop( "checked" ) ) {
						wppaAjaxUpdateAlbum( id, "removescheduledel", 0 );
					}
				}
				function wppaTrySetAllPanorama( id ) {
					var panoval = jQuery( "#pano-opt" ).val();
					if ( panoval == 0 || panoval == 1 || panoval == 2 ) {
						var url = "' . get_admin_url() . 'admin.php?page=wppa_admin_menu&tab=edit&edit-id="+id+"&wppa-nonce=' . wp_create_nonce( 'wppa-nonce' ) . '&pano-val="+panoval;
						document.location = url;
					}
					else {
						alert("' . __( 'Please select a valid panorama mode', 'wp-photo-album-plus' ) . '");
					}
				}
				function wppaGetCoverPreview( albumId, divId ) {

					jQuery.ajax( {

						url: 		wppaAjaxUrl,
						data: 		"action=wppa" +
									"&wppa-action=getshortcodedrendered" +
									"&shortcode=[wppa type=\"cover\" album=\""+albumId+"\"]",
						async: 		true,
						type: 		"GET",
						timeout: 	10000,
						beforeSend: function( xhr ) {
									},
						success: 	function( result, status, xhr ) {
										result = result.replace(/\[script/g, "<script");
										result = result.replace(/\[\/script/g, "</script");
										result = result.replace(/&gt;/g, ">");
										jQuery( "#" + divId ).html( result );
									},
						error: 		function( xhr, status, error ) {
										wppaConsoleLog( "wppaGetCoverPreview failed. Error = " + error + ", status = " + status );
									},
						complete: 	function( xhr, status, newurl ) {

									}
					} );
				}';
			}
			break;

		case 'wppa_album_sequence':
			{
				$the_js = '
				jQuery( function() {
					jQuery( "#sortable-albums" ).sortable( {
						cursor: 		"move",
						placeholder: 	"sortable-placeholder-albums",
						stop: 			function( e, ui ) { wppaDoRenumber(); },
						axis: 			"y",
						delay: 			100,
						opacity:		0.5,
						scroll: 		false,
						cursorAt: 		{ top: 30 }
					} );
				} );
				var wppaRenumberPending = false;
				var wppaAjaxInProgress 	= 0;

				function wppaDoRenumber() {
					if ( wppaAjaxInProgress > 0 ) {
						wppaRenumberPending = true;
					}
					else {
						_wppaDoRenumber();
					}
				}

				function _wppaDoRenumber() {

					// Init
					var ids = jQuery( ".wppa-sort-item-albums" );
					var seq = jQuery( ".wppa-sort-seqn-albums" );
					var descend = wppaAlbSeqnoDesc;

					// Mark needs update
					var idx = 0;
					while ( idx < ids.length ) {
						var newvalue;
						if ( descend ) newvalue = ids.length - idx;
						else newvalue = idx + 1;
						var oldvalue = seq[idx].value;
						var album = ids[idx].value;
						if ( newvalue != oldvalue ) {
							jQuery( "#wppa-pb-"+idx ).css({backgroundColor:"orange"});
						}
						idx++;
					}

					// Process
					var idx = 0;
					while ( idx < ids.length ) {
						var newvalue;
						if ( descend ) newvalue = ids.length - idx;
						else newvalue = idx + 1;
						var oldvalue = seq[idx].value;
						var album = ids[idx].value;
						if ( newvalue != oldvalue ) {
							wppaDoSeqUpdateAlbum( album, newvalue );
							jQuery( "#wppa-pb-"+idx ).css({backgroundColor:"yellow"});
							wppaLastAlbum = album;
						}
						idx++;
					}
				}

				var wppaAjaxRequests = 0;
				function wppaDoSeqUpdateAlbum( album, seqno ) {
					if ( wppaAjaxRequests >=10 ) {
						setTimeout(function(){wppaDoSeqUpdateAlbum( album, seqno );},500);
						return;
					}
					var data = 	"action=wppa" +
								"&wppa-action=update-album" +
								"&album-id=" + album +
								"&item=a_order" +
								"&wppa-nonce=" + document.getElementById( "album-nonce-" + album ).value +
								"&value=" + seqno;
					var xmlhttp = new XMLHttpRequest();

					xmlhttp.onreadystatechange = function() {
						if ( xmlhttp.readyState == 4 && xmlhttp.status != 404 ) {
							var ArrValues = xmlhttp.responseText.split( "||" );
							if ( ArrValues[0] != "" ) {
								alert( "The server returned unexpected output:\n" + ArrValues[0] );
							}
							switch ( ArrValues[1] ) {
								case "0":	// No error
									var i = seqno - 1;
									var descend = wppaAlbSeqnoDesc;
									if ( descend ) {
										i = wppaAlbumCount - seqno;
									}
									jQuery( "#wppa-album-seqno-" + album ).html( seqno );
									if ( wppaRenumberPending ) {
										jQuery( "#wppa-pb-"+i ).css({backgroundColor:"orange"});
									}
									else {
										jQuery( "#wppa-pb-"+i ).css({backgroundColor:"green"});
									}
									if ( wppaLastAlbum = album ) {
										wppaRenumberBusy = false;
									}
									break;
								default:	// Any error
									jQuery( "#wppa-album-seqno-" + album ).html( "<span style=\'color:red\' >Err:" + ArrValues[1] + "</span>" );
									break;
							}
							wppaAjaxRequests--;
							wppaConsoleLog("Pending ajax requests = "+wppaAjaxRequests);

							wppaAjaxInProgress--;

							// No longer busy?
							if ( wppaAjaxInProgress == 0 ) {

								if ( wppaRenumberPending ) {

									// Redo
									wppaRenumberPending = false;
									wppaDoRenumber();
								}
							}
						}
					};
					xmlhttp.open( "POST",wppaAjaxUrl,true );
					xmlhttp.setRequestHeader( "Content-type","application/x-www-form-urlencoded" );
					xmlhttp.send( data );
					wppaAjaxRequests++;
					wppaConsoleLog("Pending ajax requests = "+wppaAjaxRequests);
					wppaAjaxInProgress++;

					jQuery( "#wppa-sort-seqn-albums-" + album ).prop( "value", seqno );
					var spinnerhtml = "<img src=\'" + wppaImageDirectory + "spinner.gif\' />";
					jQuery( "#wppa-album-seqno-" + album ).html( spinnerhtml );
				}';
			}
			break;

		case 'wppa_ajax_import_upload':
			{
				$the_js = '
				function wppaGetUploadOptions() {

					var options = {
						beforeSend: function() {
							jQuery("#progress").show();
							jQuery("#bar").width("0%");
							jQuery("#message").html("");
							jQuery("#percent").html("");
						},
						uploadProgress: function(event, position, total, percentComplete) {
							jQuery("#bar").css("backgroundColor","#7F7");
							jQuery("#bar").width(percentComplete+"%");
							jQuery("#percent").html(percentComplete+"%");
						},
						success: function() {
							jQuery("#bar").width("100%");
							jQuery("#percent").html("' . __( 'Done!', 'wp-photo-album-plus' ) . '");
							jQuery("#wppa-upload-display").val("' . __( 'Browse...', 'wp-photo-album-plus' ) . '");
						},
						complete: function(response) {
							var success = response.responseText.substr(0,1);
							if ( success == "0" ) {
								jQuery("#message").html( "<span style=\"color: red;\" >' . __( 'Server error', 'wp-photo-album-plus' ) . '</span>" );
								jQuery("#bar").css("backgroundColor","#F77");
								jQuery("#percent").html(wppaUploadFailed);
							}
							jQuery("#message").html( "<span>"+response.responseText.substr(1)+"</span>" );
						},
						error: function() {
							jQuery("#message").html( "<span style=\"color: red;\" >' . __( 'Server error', 'wp-photo-album-plus' ) . '</span>" );
							jQuery("#bar").css("backgroundColor","#F77");
							jQuery("#percent").html(wppaUploadFailed);
						}
					};

					return options;
				}
				function wppaDisplaySelectedFilesForImport(id) {
					var theFiles = jQuery("#"+id);
					var i = 0;
					var result = "";
					var ok = true;
					var maxlen = parseInt("' . ini_get( 'upload_max_filesize' ) . '") * 1024 * 1024;
					var maxfiles = ' . ini_get( 'max_file_uploads' ) . ';

					if ( theFiles[0].files.length > maxfiles ) {
						result = "' . sprintf( __( 'Too many files selected, only %d allowed', 'wp-photo-album-plus' ), ini_get( 'max_file_uploads' ) ) . '";
						ok = false;
					}

					else while ( i<theFiles[0].files.length ) {
						if ( theFiles[0].files[i].size > maxlen ) {
							result += theFiles[0].files[i].name+" <span style=\'color:red\'>' . __( 'is too big', 'wp-photo-album-plus' ) . '</span><br>";
							ok = false;
						}
						else {
							result += theFiles[0].files[i].name+"<br>";
						}
						i++;
					}

					jQuery("#"+id+"-display").val(result);
					jQuery("#"+id+"-display").html(result);
					if ( ! ok ) {
						jQuery( "#wppa-submit" ).css( "display", "none" );
					}

					return ok;
				}';
			}
			break;

		case '_wppa_page_import':
			{
				global $wppa_endtime;
				$the_js = '
				// Init session
				var wppaBusy = false;
				var wppaImportRuns = false;
				var wppaTimer;
				var wppaReloadRequested = false;
				var wppaDidZip = false;
				var wppaReloadPending = false;

				function wppaDoAjaxImport(from) {

					var $ = jQuery;
					jQuery( "#wppa-spinner" ).css( "display", "none" );

					var elm;
					var type = "nil";

					console.log("do import requested from loc "+from);
					if ( wppaBusy ) return;
					wppaBusy = true;

					// Find the first item check
					var itemsToDo = jQuery( ".wppa-import-item:checked" );

					// Anything to do?
					if ( itemsToDo.length > 0 ) {
						elm  = itemsToDo[0];
						type = jQuery(elm).attr("data-type");
					}

					// If a pending reload request for new files from zip, and this elm is not a zip, do the reload
					if ( wppaReloadRequested && type != "zip" ) {

						// Must reload after last zip
						wppaStopAjaxImport(1);
						wppaConsoleLog("Rel 3","force");
						wppaIssueReload( "zip-err", 2 );
						return;
					}

					// If nothing found, type is still "nil", than we can stop
					if ( type == "nil" ) {
						wppaStopAjaxImport(2);
						jQuery( "#wppa-start-ajax" ).prop( "disabled", false );
						return;
					}

					// Prepare for ajax call
					var data = "wppa-import-submit=ajax&import-ajax-file="+elm.title+"&type="+type+"&wppa-update-check="+jQuery("#wppa-update-check").val();
					var tmp;
					switch ( type ) {

						case "zip":
							if (jQuery("#del-after-z").length)
								data += "&del-after-z="+(jQuery("#del-after-z").prop("checked")?"1":"0");

							if (jQuery("#del-after-fz").length)
								data += "&del-after-fz="+(jQuery("#del-after-fz").prop("checked")?"1":"0");
							break;

						case "pho":
							tmp = jQuery("#wppa-photo-album").find(":selected").val();
							if (tmp) data += "&wppa-photo-album="+tmp;

							tmp = jQuery("#cre-album").find(":selected").val();
							if (tmp) data += "&cre-album="+tmp;

							tmp = jQuery("#wppa-watermark-file").find(":selected").val();
							if (tmp) data += "&wppa-watermark-file="+tmp;

							tmp = jQuery("#wppa-watermark-pos").find(":selected").val();
							if (tmp) data += "&wppa-watermark-pos="+tmp;

							if (jQuery("#del-after-p").length)
								data += "&del-after-p="+(jQuery("#del-after-p").prop("checked")?"1":"0");

							if (jQuery("#del-after-fp").length)
								data += "&del-after-fp="+(jQuery("#del-after-fp").prop("checked")?"1":"0");

							if (jQuery("#use-backup").length)
								data += "&use-backup="+(jQuery("#use-backup").prop("checked")?"1":"0");

							if (jQuery("#wppa-update").length)
								data += "&wppa-update="+(jQuery("#wppa-update").prop("checked")?"1":"0");

							if (jQuery("#wppa-nodups").length)
								data += "&wppa-nodups="+(jQuery("#wppa-nodups").prop("checked")?"1":"0");

							if (jQuery("#wppa-zoom").length)
								data += "&wppa-zoom="+(jQuery("#wppa-zoom").prop("checked")?"1":"0");

							break;

						case "video":
							tmp = jQuery("#wppa-video-album").find(":selected").val();
							if (tmp) data += "&wppa-video-album="+tmp;

							if (jQuery("#del-after-v").length)
								data += "&del-after-v="+(jQuery("#del-after-v").prop("checked")?"1":"0");

							if (jQuery("#del-after-fv").length)
								data += "&del-after-fv="+(jQuery("#del-after-fv").prop("checked")?"1":"0");

							break;

						case "audio":

							tmp = jQuery("#wppa-audio-album").find(":selected").val();
							if (tmp) data += "&wppa-audio-album="+tmp;

							if (jQuery("#del-after-u").length)
								data += "&del-after-u="+(jQuery("#del-after-u").prop("checked")?"1":"0");

							if (jQuery("#del-after-fu").length)
								data += "&del-after-fu="+(jQuery("#del-after-fu").prop("checked")?"1":"0");

							break;

						case "pdf":
							tmp = jQuery("#wppa-document-album").find(":selected").val();
							if (tmp) data += "&wppa-document-album="+tmp;

							if (jQuery("#del-after-d").length)
								data += "&del-after-d="+(jQuery("#del-after-d").prop("checked")?"1":"0");

							if (jQuery("#del-after-fd").length)
								data += "&del-after-fd="+(jQuery("#del-after-fd").prop("checked")?"1":"0");

							break;

						case "amf":

							if (jQuery("#del-after-a").length)
								data += "&del-after-a="+(jQuery("#del-after-a").prop("checked")?"1":"0");

							if (jQuery("#del-after-fa").length)
								data += "&del-after-fa="+(jQuery("#del-after-fa").prop("checked")?"1":"0");

							break;

						case "csv":

							if (jQuery("#del-after-c").length)
								data += "&del-after-c="+(jQuery("#del-after-c").prop("checked")?"1":"0");

							if (jQuery("#del-after-fc").length)
								data += "&del-after-fc="+(jQuery("#del-after-fc").prop("checked")?"1":"0");

							break;

						case "dir":

							if (jQuery("#del-dir").length)
								data += "&del-dir="+(jQuery("#del-dir").prop("checked")?"1":"0");

							if (jQuery("#del-dir-cont").length)
								data += "&del-dir-cont="+(jQuery("#del-dir-cont").prop("checked")?"1":"0");

							break;

						default:
							wppaConsoleLog("Unimplemented type in set up data", "force");
							break;
					}

					// Log the data in the querystring
					wppaConsoleLog(data,"force");

					// Now do the ajax call
					jQuery.ajax( {

						url: 		wppaAjaxUrl+"?action=wppa&wppa-action=import",
						data: 		data,
						async: 		true,
						type: 		"POST",
						timeout: 	(3600*1000),

						beforeSend: function( xhr ) {
							jQuery( "#label-"+elm.id ).html( "<b style=\'color:blue\' >" + "' . __( "Working...", "wp-photo-album-plus" ) . '" + "</b>" );
						},

						success: 	function( resp, status, xhr ) {

										// Log response
										console.log(resp);

										// See if valid json reply
										try { 	// Looks valid json reply
											var result 	= JSON.parse( resp );
											contin 		= parseInt(result.continue);
											deleted 	= parseInt(result.deleted);
											code 		= parseInt(result.code);
											if ( parseInt(result.reload) ) wppaReloadRequested = true;
											done 		= parseInt(result.done);
											skip 		= parseInt(result.skip);

											// Asked to continue?
											if ( contin ) {
												console.log("contin requested. import runs = "+(wppaImportRuns?"true":"false"));
												jQuery( "#"+type+"-err" ).html( ( code ==23 ? jQuery( "#err-"+code ).html() + " " :  "" ) + ( done ? done+" processed." : "" ) + ( skip ? " "+skip+" skipped." : "" ) );

												if ( wppaImportRuns ) {
													jQuery( "#label-"+elm.id ).html( "<span style=\'color:blue;\'>' . esc_html( __( 'Continuing...', 'wp-photo-album-plus' ) ) . '</span>" );
													jQuery(elm).prop("checked", true);
													if ( ! wppaReloadPending ) {
														setTimeout(function(){wppaDoAjaxImport(1);}, 2000);
													}
												}
												else {
													jQuery( "#label-"+elm.id ).html( "<span style=\'color:blue;\'>' . esc_html( __( 'Paused', 'wp-photo-album-plus' ) ) . '</span>" );
													jQuery(elm).prop("checked", true);
												}
												if ( wppaReloadPending ) {
													wppaStopAjaxImport("contin but reload pending");
												}
												return;
											}

											// Has item been deleted? disable and remove url in title
											if ( deleted ) {
												console.log("deleted");

												jQuery(elm).prop("disabled", true);
												jQuery(elm).attr("title", "");
											}

											// This one processed, remove checkmark
											jQuery(elm).prop("checked", false);

											// error
											if ( code > 0 ) {
												console.log("error "+code);
												if ( code == 1 || code == 9 || code == 14 || code == 15 || code == 33 ) {
													jQuery( "#label-"+elm.id ).html( "<span style=\'font-weight:bold;color:darkorange;\'>' . esc_html( __( 'Warning', 'wp-photo-album-plus' ) ) . ' "+code+"</span>" );
													jQuery( "#"+type+"-err" ).html( "<span style=\'color:darkorange;\'>' . esc_html( __( 'Warning', 'wp-photo-album-plus' ) ) . ' "+code+":&nbsp;"+jQuery( "#err-"+code ).html()+"</span>" );
												}
												else {
													jQuery( "#label-"+elm.id ).html( "<span style=\'font-weight:bold;color:red;\'>' . esc_html( __( 'Error', 'wp-photo-album-plus' ) ) . ' "+code+"</span>" );
													jQuery( "#"+type+"-err" ).html( "<span style=\'color:red;\'>' . esc_html( __( 'Error', 'wp-photo-album-plus' ) ) . ' "+code+":&nbsp;"+jQuery( "#err-"+code ).html()+"</span>" );
													wppaStopAjaxImport(3);
												}
											}

											// no error
											if ( code == 0 ) {
												jQuery( "#label-"+elm.id ).html( "<span style=\'font-weight:bold;color:green;\'>' . esc_html( __( 'Done!', 'wp-photo-album-plus' ) ) . '</span>" );
												jQuery( "#"+type+"-err" ).html(( done ? " "+done+" processed." : "" ) + ( skip ? " "+skip+" skipped." : "" ));
												if ( type == "zip" ) wppaDidZip = true;
											}

											// just poster msg
											if ( code < 0 ) {
												var txt;
												switch(code) {
													case -10:
														txt = "' . esc_html( __( 'Poster ok', 'wp-photo-album-plus' ) ) . '";
														break;
													default:
														txt = "????";
												}
												jQuery( "#label-"+elm.id ).html( "<span style=\'color:blue;\'>"+txt+"</span>" );
												jQuery( "#"+type+"-err" ).html("");
											}

											// do next
											if ( wppaImportRuns ) {
												setTimeout( function(){wppaDoAjaxImport(2)}, 1000 );
											}
										}

										// Invalid json response
										catch(error) {
											console.error(error);
											wppaStopAjaxImport(4);
										}

									},

						error: 		function( xhr, status, error ) {
										jQuery( "#label-"+elm.id ).html("<span style=\'color:red\'>Comm error</span>" );
										wppaImportAbort();
										console.error(status+" "+error);
									},

						complete: 	function( xhr, status, newurl ) {
										wppaBusy = false;
										jQuery( "#wppa-start-ajax" ).prop( "disabled", false );
										if ( wppaReloadPending ) {
											wppaReloadPending = false;
											wppaImportRuns = false;
											wppaImportReload("reload pending");
										}
									}
					});
				}

				function wppaStopAjaxImport(from) {
					console.log("stop requested from "+from);

					var $ = jQuery;
					wppaImportRuns = false;
					jQuery( "#wppa-start-ajax" ).css( "display", "inline" );
					jQuery( "#wppa-start-ajax" ).prop( "disabled", true );
					jQuery( "#wppa-stop-ajax" ).css( "display", "none" );
					jQuery( "#wppa-spinner" ).css( "display", "none" );
					wppaBusy = false;
				}
				function wppaIssueReload(elmId) {
					var $ = jQuery;
					jQuery( "#"+elmId ).html("' . __( 'Reloading to include the unzipped items, please stand by...', 'wp-photo-album-plus' ) . '");
					wppaImportReload(1);
					return;
				}';

				if ( wppa_switch( 'import_all' ) ) {
					$the_js .= '
					jQuery(document).ready(function(){
						var $ = jQuery;
						jQuery(".wppa-import-item").prop("checked",true);
						jQuery(".wppa-del").prop("checked",true);
						jQuery(".wppa-all").prop("checked",true);
						' . ( wppa_switch( 'import_auto' ) && wppa_get( 'autorun' ) ? 'setTimeout(function(){jQuery("#wppa-start-ajax").trigger("click");},200);' : '' ) . '
					});';
				}

				if ( wppa_switch( 'import_auto' ) ) {
					$url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
					$url = add_query_arg( 'wppa-autorun', 1, $url );
					$the_js .= '
					function wppaImportReload(from) {
						console.log("reload requested from "+from);
						if (! wppaImportRuns) setTimeout(function(){document.location.href="' . $url . '"},200);
						else wppaReloadPending = true;
					}';
				}
				else {
					$the_js .= '
					function wppaImportReload(from) {
						console.log("reload requested from "+from);
						if (! wppaImportRuns) setTimeout(function(){document.location.reload(true)},2000);
						else wppaReloadPending = true;
					}';
				}

				$the_js .= '
				function wppaImportAbort() {
					wppaReloadRequested = false;
					wppaDidZip = false;
					wppaStopAjaxImport(5);
				}';

				wppa_add_inline_script( 'wppa-admin', $the_js, true );

			}
			break;

		case '_wppa_page_options':
			{
				$the_js = '
				function wppaSettingTab(tab) {
					jQuery(".wppa-setting-tabs li").removeClass("active");
					jQuery("#wppa-setting-tab-"+tab).addClass("active");
					if ( tab == 99 ) {
						jQuery( "#wppa-admin-spinner" ).show();
						document.location.href = document.location.href + "&oldstyle";
					}
					else {
						jQuery("#wppa-setting-content").hide();
						jQuery(".wppa-tabdesc").hide();
						jQuery( "#wppa-admin-spinner" ).show();
						document.location.href = wppaReturnUrl() + "&wppa-tab=" + tab;
					}
				}
				function wppaSlaveChecked(elm,clas) {
					if ( jQuery( elm ).prop( "checked" ) ) {
						jQuery( "."+clas ).show();
					}
					else {
						jQuery( "."+clas ).hide();
					}
				}
				function wppaUnSlaveChecked(elm,clas) {
					if ( jQuery( elm ).prop( "checked" ) ) {
						jQuery( "."+clas ).hide();
					}
					else {
						jQuery( "."+clas ).show();
					}
				}
				function wppaSlaveNotNone(elm,clas) {
					if ( jQuery( elm ).val() == "none" ) {
						jQuery( "."+clas ).hide();
					}
					else {
						jQuery( "."+clas ).show();
					}
				}
				function wppaSlaveSelected(elmOrId,clas) {
					if (typeof(elmOrId)=="object") {
						elm = elmOrId;
					}
					else {
						elm = jQuery("#"+elmOrId);
					}
					if (jQuery(elm).prop("selected")) {
						jQuery( "."+clas ).show();
					}
					else {
						jQuery( "."+clas ).hide();
					}
				}
				function wppaUnSlaveSelected(elmOrId,clas) {
					if (typeof(elmOrId)=="object") {
						elm = elmOrId;
					}
					else {
						elm = jQuery("#"+elmOrId);
					}
					if (jQuery(elm).prop("selected")) {
						jQuery( "."+clas ).hide();
					}
					else {
						jQuery( "."+clas ).show();
					}
				}
				function wppaSlaveSelectedOr(Id1,Id2,clas) {
					elm1 = jQuery("#"+Id1);
					elm2 = jQuery("#"+Id2);

					if (jQuery(elm1).prop("selected")||jQuery(elm2).prop("selected")) {
						jQuery( "."+clas ).show();
					}
					else {
						jQuery( "."+clas ).hide();
					}
				}
				function wppaSlaveSelectedAndSwitch(SelId,SwitchId,clas) {
					elm1 = jQuery("#"+SelId);
					elm2 = jQuery("#"+SwitchId);

					if (jQuery(elm1).prop("selected") && jQuery(elm2).prop("checked")) {
						jQuery( "."+clas ).show();
					}
					else {
						jQuery( "."+clas ).hide();
					}
				}
				function wppaSlaveNeedPage(elm,id) {
					var value=jQuery(elm).val();
					var needs=["none","file","widget","custom","same","fullpopup","lightbox"];
					if (needs.indexOf(value)!==-1) {
						jQuery("#"+id).hide();
					}
					else {
						jQuery("#"+id).show();
					}
				}
				function wppaReturnUrl(actionSlug) {
					var url = document.location.href;
					var qpos = url.indexOf("?");
					url = url.substr(0,qpos) + "?page=wppa_options&wppa-tab=' . $arg1 . '";
					if ( actionSlug ) {
						url += "&wppa-nonce=' . wp_create_nonce( 'wppa-nonce' ) . '";
						url += "&wppa-settings-submit=Doit&wppa-key=" + actionSlug;
					}
					return url;
				}
				function wppaRefreshPage() {
					document.location.href = wppaReturnUrl();
				}
				function wppaToggleSubtab(clas) {
					if ( jQuery( "#"+clas ).css("display") == "none" ) {
						jQuery(".wppa-setting-content").hide();
						jQuery(".wppa-setting-content").each(function(){
							wppa_setCookie(jQuery(this).attr("id"),"off",30);
						});
						jQuery(".wppa-tabdesc").each(function(){
							jQuery(this).css("background-color", "#eeeeee");
							jQuery(this).attr("data-inactive","1");
						});
						jQuery( "#"+clas ).show();
						jQuery( ".wppa-tabdesc-"+clas ).css("background-color", "#ffffff");
						wppa_setCookie(clas,"on",30);
					}
					else {
						jQuery( "#"+clas ).hide();
						jQuery( ".wppa-tabdesc-"+clas ).css("background-color", "#eeeeee");
						jQuery(this).attr("data-inactive","0");
						wppa_setCookie(clas,"off",30);
					}
					jQuery( "#"+clas+"-cm" ).hide();
				}
				function wppaCheckFontPreview() {
					var font = document.getElementById("textual_watermark_font").value,
						type = document.getElementById("textual_watermark_type").value,
						date = new Date(),
						time = date.getTime(),
						fsrc = wppaFontDirectory+"wmf"+font+"-"+type+".png?ver="+time,
						tsrc = wppaFontDirectory+"wmf"+type+"-"+font+".png?ver="+time;
					jQuery("#wm-font-preview").attr("src", fsrc);
					jQuery("#wm-type-preview").attr("src", tsrc);
				}
				function wppaSetFixed(id) {
					if ( jQuery("#wppa-widget-photo-" + id).prop("checked") ) {
						_wppaRefreshAfter = true;
						wppaAjaxUpdateOptionValue("potd_photo", id);
					}
				};
				function wppaPotdCheckPom4andDof() {
					var pom4 = jQuery("#wppa_potd_method-4").prop("selected");
					var per  = jQuery("#potd_period").val();

					if (pom4 && per.substr(0,6)=="day-of") {
						jQuery(".wppa_potd_offset").show();
					}
					else {
						jQuery(".wppa_potd_offset").hide();
					}
				}
				function wppaUpdatePotdInfo() {
					jQuery.ajax( {
						url: 		wppaAjaxUrl,
						data: 		"action=wppa&wppa-action=updatepotddata",
						async: 		false,
						type: 		"GET",
						timeout: 	60000,
						success: 	function( result, status, xhr ) {
										try {
											var potddata = JSON.parse(result);
											var offset   = potddata.offset;
												jQuery("#potd_offset").val(offset);
											var seqno = potddata.seqno;
												jQuery("#potdseqno").html(seqno);
											var preview  = potddata.preview;
												jQuery("#potdpreview").html(preview);
											var pool     = potddata.pool;
												jQuery("#potd-pool").html(pool);
										}
										catch {
											wppaConsoleLog( "wppaUpdatePotdInfo failed. JSON.parse failed" );
										}
									},
						error: 		function( xhr, status, error ) {
										wppaConsoleLog( "wppaUpdatePotdInfo failed. Error = " + error + ", status = " + status );
									},
					} );
				}
				function wppaUpdateWatermarkPreview() {
					jQuery.ajax( {
						url: 		wppaAjaxUrl,
						data: 		"action=wppa&wppa-action=updatewatermarkpreview",
						async: 		false,
						type: 		"GET",
						timeout: 	60000,
						success: 	function( result, status, xhr ) {
							wppaConsoleLog("Received:"+result);
										try {
											var data = JSON.parse(result);
											var url = data.url;
								wppaConsoleLog( "url="+url );
												jQuery("#wppa-watermark-preview").attr("src",url);
												wppaCheckFontPreview();
										}
										catch {
											wppaConsoleLog( "wppaUpdateWatermarkPreview failed. JSON.parse failed" );
											wppaConsoleLog( result );
										}
									},
						error: 		function( xhr, status, error ) {
										wppaConsoleLog( "wppaUpdateWatermarkPreview failed. Error = " + error + ", status = " + status );
									},
					} );
				}
				function wppaUpdateIcon(source,target) {
					var icon = jQuery("#"+source).val();
					var iconurl="' . esc_attr( WPPA_UPLOAD_URL ) . '/icons/"+icon;
					jQuery("#"+target).attr("src",iconurl);
				}
				function wppaPlanPotdUpdate() {
					jQuery("#potdseqno").html("<img src=\'' . wppa_get_imgdir() . 'spinner.gif\'>");
					jQuery("#potdpreview").html("<img src=\'' . wppa_get_imgdir() . 'spinner.gif\'>");
					_wppaPlanPotdUpdate = true;
				}
				function wppaPlanUpdateWatermarkPreview() {
					jQuery("#wppa-watermark-preview").attr("src",\'' . wppa_get_imgdir() . 'spinner.gif\');
					_wppaPlanUpdateWatermarkPreview = true;
				}
				var heartbeat = 0;
				setInterval( function() {
					heartbeat++;
					wppaAjaxUpdateOptionValue( "heartbeat", heartbeat );
				}, 10000 );
				jQuery(document).ready(function(){setTimeout(function(){
					jQuery("#wppa-setting-content").show();
				},10)}); ' .
				( $arg2 ? '
				jQuery(document).ready(function(){setTimeout(function(){
					if (jQuery(".' . $arg2 . '").attr("data-inactive") == "1") jQuery(".' . $arg2 . '").trigger("click");
				},100)});' : ''
				) .
				( $arg1 == 'watermark' ? 'jQuery(document).ready(function(){wppaCheckFontPreview();});' : '' ) .
				( $arg1 == 'photos' ? 'jQuery(document).ready(function(){setTimeout(function(){wppaUpdatePotdInfo()},2000);});' : '' );
			}
			break;
		default:

			wppa_log( 'err', "No local js for $slug in wppa_add_local_js()" );
			return;
			break;
	}

	wppa_add_inline_script( 'wppa-admin', $the_js, true );
}