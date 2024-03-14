/* admin-scripts.js */
/* Package: wp-photo-album-plus
/*
/* Version 8.6.01.001
/* Various js routines used in admin pages
*/

// Init at dom ready
jQuery(document).ready(function() {

	// Make Lazy load images visible
	jQuery(window).on('DOMContentLoaded load resize scroll', function(){wppaMakeLazyVisible('windowon')});
	wppaMakeLazyVisible('docready');
});

function wppaReUpload( event, photo, expectedName, reload ) {

	var form = document.getElementById('wppa-re-up-form-'+photo);
	var fileSelect = document.getElementById('wppa-re-up-file-'+photo);
	var button = document.getElementById('wppa-re-up-butn-'+photo);

	// Remove default action
	event.preventDefault();

	// Get the selected file from the input.
	var file = fileSelect.files[0];

	// Check the file type.
	if ( !file.type.match( 'image.*' ) ) {
		alert( 'File is not an image file!' );
		return;
	}

	// Check the file name
	if ( expectedName.length == 0 ) {
		alert( 'Filename will be set to '+file.name );
	}
	else if ( file.name != expectedName ) {
		if ( ! confirm( 'Filename is different.\nIf you continue, the filename will not be updated!.\n\nContinue?' ) ) {
			jQuery( '#re-up-'+photo ).css( 'display', 'none' );
			return;
		}
	}

	// Update button text
	button.value = 'Uploading...';
	button.style.color = 'black';

	// Create a new FormData object.
	var formData = new FormData();

	// Add the file to the request.
	formData.append('photo', file, file.name);

	// Set up the request.
	var xhr = new XMLHttpRequest();

	// Open the connection.
	var queryString = 	'?action=wppa' +
						'&wppa-action=update-photo' +
						'&photo-id=' + photo +
						'&item=file' +
						'&wppa-nonce=' + document.getElementById('photo-nonce-'+photo).value;

	xhr.open( 'POST', wppaAjaxUrl + queryString, true );

	// Set up a handler for when the request finishes.
	xhr.onload = function () {

		if ( xhr.status === 200 ) {

			var str = wppaTrim( xhr.responseText );
			var ArrValues = str.split( "||" );

				if ( ArrValues[0] != '' ) {
					alert( 'The server returned unexpected output:\n' + ArrValues[0] );
				}
				switch ( ArrValues[1] ) {
					case '0':		// No error

						// Extract update felds
						var updates = JSON.parse( ArrValues[2] );
						var fieldName;
						var fieldValue;

						for ( fieldName in updates ) {
							fieldValue = updates[fieldName];

							switch ( fieldName ) {

								case 'remark':
									var text;
									fieldValue = fieldValue.replace(/&lt;/g,'<');
									fieldValue = fieldValue.replace(/&gt;/g,'>');
									fieldValue = fieldValue.replace(/\\/g,'');

									if ( ArrValues[1] != "0" ) { 	// error
										text = '<span style="color:red;" >' + fieldValue + '</span>';
									}
									else { 							// no error
										text = '<span style="color:green;" >' + fieldValue + '</span>';
									}
									if ( reload ) {
										text += ' <span style="color:blue;" >Reloading...</span>';
									}
									jQuery( "#remark-" + photo ).html( text );
									break;

								case 'photourl':
									if ( wppaCropper[photo] ) {
										var c = wppaCropper[photo];
										c.replace(fieldValue);
									}
									else {
										jQuery( "#photourl-" + photo ).attr('src', fieldValue);
									}
									jQuery( "#thumba-" + photo ).attr('href', fieldValue);
									break;

								case 'thumburl':
									jQuery( "#thumburl-" + photo ).attr('src', fieldValue);
									break;

								case 'magickstack':
									jQuery( "#magickstack-" + photo ).html( fieldValue );
									if ( fieldValue.length > 0 ) {
										jQuery( '#imstackbutton-' + photo ).css( 'display', 'inline' );
									}
									else {
										jQuery( '#imstackbutton-' + photo ).css( 'display', 'none' );
									}
									break;

								default:
									jQuery( "#" + fieldName + "-" + photo ).html( fieldValue );
									break;
							}
						}

						button.value = 'Upload';
						jQuery( '#re-up-'+photo ).css( 'display', 'none' );
						break;
					case '99':	// Photo is gone
						document.getElementById('photoitem-'+photo).innerHTML = '<span style="color:red">'+ArrValues[2]+'</span>';
						break;
					default:	// Any error
						document.getElementById('remark-'+photo).innerHTML = '<span style="color:red">'+ArrValues[2]+' ('+ArrValues[1]+')</span>';
						button.value = 'Error occured';
						button.style.color = 'red';
						break;
				}
		}
		else {
			alert('An error occurred!');
		}
	};

	// Send the Data.
	xhr.send( formData );
}


var _wppaRefreshAfter = false;
var _wppaPlanPotdUpdate = false;
var _wppaPlanUpdateWatermarkPreview = false;
function wppaRefreshAfter() {
	_wppaRefreshAfter = true;
}

/* Adjust visibility of selection radiobutton if fixed photo is chosen or not */
/* Also: hide/show order# stuff */
function wppaCheckWidgetMethod() {
	var ph;
	var i;
	if (document.getElementById('wppa-wm').value=='4') {
		document.getElementById('wppa-wp').style.visibility='visible';
		var per = jQuery('#wppa-wp').val();

		if ( per == 'day-of-week' || per == 'day-of-month' || per == 'day-of-year' ) {
			jQuery('.wppa-order').css('visibility', '');
		}
		else {
			jQuery('.wppa-order').css('visibility', 'hidden');
		}

	}
	else {
		document.getElementById('wppa-wp').style.visibility='hidden';
		jQuery('.wppa-order').css('visibility', 'hidden');


	}
	if (document.getElementById('wppa-wm').value=='1') {
		ph=document.getElementsByName('wppa-widget-photo');
		i=0;
		while (i<ph.length) {
			ph[i].style.visibility='visible';
			i++;
		}
	}
	else {
		ph=document.getElementsByName('wppa-widget-photo');
		i=0;
		while (i<ph.length) {
			ph[i].style.visibility='hidden';
			i++;
		}
	}
}



function wppa_tablecookieon(i) {
	wppa_setCookie('table_'+i, 'on', '365');
}

function wppa_tablecookieoff(i) {
	wppa_setCookie('table_'+i, 'off', '365');
}

function wppaCookieCheckbox(elm, id) {
	if ( elm.checked ) wppa_setCookie(id, 'on', '365');
	else wppa_setCookie(id, 'off', '365');
}

function wppa_move_up(who) {
	document.location = "#"+who+"&wppa-nonce="+document.getElementById('wppa-nonce').value;
}

function checkColor(xslug) {
	var slug = xslug.substr(5);
	var color = jQuery('#'+slug).val();
	jQuery('#colorbox-'+slug).css('background-color', color);
}

function checkAll(name, clas) {
	var elm = document.getElementById(name);
	if (elm) {
		if ( elm.checked ) {
			jQuery(clas).prop('checked', true);
		}
		else {
			jQuery(clas).prop('checked', false);
		}
	}
}

function impUpd(elm, id) {


	if ( elm.checked ) {
		jQuery(id).val( __( 'Update', 'wp-photo-album-plus') );
		jQuery('.hideifupdate').css('display', 'none');
	}
	else {
		jQuery(id).val( __( 'Import', 'wp-photo-album-plus') );
		jQuery('.hideifupdate').css('display', '');
	}
}

function wppaAjaxDeletePhoto(photo, bef, aft, immediate) {

	var before = '';
	var after = '';
	if ( bef ) {
		before = bef;
	}
	else {
		before = '<div style="padding-left:5px;" >';
	}
	if ( aft ) {
		after = aft;
	}
	else {
		aftrer = '</div>';
	}

	wppaFeAjaxLog('in');

	var xmlhttp = wppaGetXmlHttp();

	// Make the Ajax url
	var url = wppaAjaxUrl+'?action=wppa&wppa-action=delete-photo&photo-id='+photo;
	url += '&wppa-nonce='+document.getElementById('photo-nonce-'+photo).value;
	if ( immediate ) url += '&wppa-immediate=1';

	// Do the Ajax action
	xmlhttp.open('GET',url,true);
	xmlhttp.send();

	// Process the result
	xmlhttp.onreadystatechange=function() {
		switch (xmlhttp.readyState) {
		case 1:
			document.getElementById('remark-'+photo).innerHTML = 'server connection established';
			break;
		case 2:
			document.getElementById('remark-'+photo).innerHTML = 'request received';
			break;
		case 3:
			document.getElementById('remark-'+photo).innerHTML = 'processing request';
			break;
		case 4:
			if ( xmlhttp.status == 200 ) {

				var str = wppaTrim(xmlhttp.responseText);
				var ArrValues = str.split("||");
				if ( ArrValues[0] == 'ER' ) {
					if ( ArrValues[3] ) {
						alert(ArrValues[3] );
					}
					jQuery('#wppa-delete-'+photo).css('text-decoration','line-through');
				}
				else if (ArrValues[0] != '') {
					alert('The server returned unexpected output:\n'+ArrValues[0]);
				}

				if ( ArrValues[1] == 0 ) document.getElementById('remark-'+photo).innerHTML = ArrValues[2];	// Error
				else {
					document.getElementById('photoitem-'+photo).innerHTML = before+ArrValues[2]+after;	// OK
					wppaProcessFull(ArrValues[3], ArrValues[4]);
				}
				jQuery(window).trigger('scroll');
				wppaFeAjaxLog('out');
			}
			else {	// status != 200
				document.getElementById('photoitem-'+photo).innerHTML = before+'<span style="color:red;" >Comm error '+xmlhttp.status+': '+xmlhttp.statusText+'</span>'+after;
			}
		}
	}
}

function wppaAjaxUndeletePhoto(photo) {

	wppaFeAjaxLog('in');

	var xmlhttp = wppaGetXmlHttp();

	// Make the Ajax url
	var url = wppaAjaxUrl+'?action=wppa&wppa-action=undelete-photo&photo-id='+photo;
	url += '&wppa-nonce='+document.getElementById('photo-nonce-'+photo).value;

	// Do the Ajax action
	xmlhttp.open('GET',url,true);
	xmlhttp.send();

	// Process the result
	xmlhttp.onreadystatechange=function() {
		switch (xmlhttp.readyState) {
		case 1:
			document.getElementById('remark-'+photo).innerHTML = 'server connection established';
			break;
		case 2:
			document.getElementById('remark-'+photo).innerHTML = 'request received';
			break;
		case 3:
			document.getElementById('remark-'+photo).innerHTML = 'processing request';
			break;
		case 4:
			if ( xmlhttp.status == 200 ) {

				var str = wppaTrim(xmlhttp.responseText);
				var ArrValues = str.split("||");
				if ( ArrValues[0] == 'ER' ) {
					if ( ArrValues[3] ) {
						alert( ArrValues[3] );
					}
					jQuery('#wppa-delete-'+photo).css('text-decoration','line-through');
				}
				else if (ArrValues[0] != '') {
					alert('The server returned unexpected output:\n'+ArrValues[0]);
				}

				if ( ArrValues[1] == 0 ) document.getElementById('remark-'+photo).innerHTML = ArrValues[2];	// Error
				else {
					document.getElementById('photoitem-'+photo).innerHTML = '<div style="padding-left:5px;" >' + ArrValues[2] + '</div>';	// OK
				}
				wppaFeAjaxLog('out');
			}
			else {	// status != 200
				document.getElementById('photoitem-'+photo).innerHTML = before+'<span style="color:red;" >Comm error '+xmlhttp.status+': '+xmlhttp.statusText+'</span>'+after;
			}
		}
	}
}

function wppaAjaxDeleteExportZips() {

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa' +
								'&wppa-action=delexportzips',
					async: 		false,
					type: 		'GET',
					timeout: 	60000,
					success: 	function( result, status, xhr ) {
									document.location.reload(true);
								},
					error: 		function( xhr, status, error ) {
									wppaConsoleLog( 'wppaAjaxDeleteExportZips failed. Error = ' + error + ', status = ' + status );
								},
				} );
}

function wppaAjaxApplyWatermark(photo, file, pos) {

	wppaFeAjaxLog('in');

	var xmlhttp = wppaGetXmlHttp();

	// Show spinner
	jQuery('#wppa-water-spin-'+photo).css({visibility:'visible'});

	// Make the Ajax send data
	var data = 'action=wppa&wppa-action=watermark-photo&photo-id='+photo;
	data += '&wppa-nonce='+document.getElementById('photo-nonce-'+photo).value;
	if (file) data += '&wppa-watermark-file='+file;
	if (pos) data += '&wppa-watermark-pos='+pos;

	// Do the Ajax action
	xmlhttp.open('POST',wppaAjaxUrl,true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send(data);

	// Process the result
	xmlhttp.onreadystatechange=function() {
		if ( xmlhttp.readyState == 4 ) {
			if ( xmlhttp.status == 200 ) {
				var str = wppaTrim(xmlhttp.responseText);
				var ArrValues = str.split("||");

				if (ArrValues[0] != '') {
					alert('The server returned unexpected output:\n'+ArrValues[0]);
				}
				switch (ArrValues[1]) {
					case '0':		// No error
						document.getElementById('remark-'+photo).innerHTML = ArrValues[2];
						setTimeout(function(){document.location.reload(true)},500);
						break;
					default:
						document.getElementById('remark-'+photo).innerHTML = '<span style="color:red">'+ArrValues[2]+'</span>';
				}
				// Hide spinner
				jQuery('#wppa-water-spin-'+photo).css({visibility:'hidden'});

				wppaFeAjaxLog('out');
			}
			else {	// status != 200
				document.getElementById('remark-'+photo).innerHTML = '<span style="color:red;" >Comm error '+xmlhttp.status+': '+xmlhttp.statusText+'</span>';
			}
		}
	}
}

// Update an iptc tag
function wppaAjaxUpdateIptc( photo, tagid, value, tagname ) {

	// Open ajax object
	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa&wppa-action=update-iptc' +
								'&photo-id=' + photo +
								'&item=' + tagid +
								'&wppa-nonce=' + document.getElementById( 'photo-nonce-' + photo ).value +
								'&value=' + wppaEncode( value ) +
								'&tagname=' + tagname,
					async: 		true,
					type: 		'POST',
					timeout: 	60000,
					beforeSend: function( xhr ) {

									// Update status
									jQuery( '#remark-' + photo ).html( 'Working, please wait...' );
								},
					success: 	function( result, status, xhr ) {

									// Format result
									var str = wppaTrim( result );
									var ArrValues = str.split("||");

									// Any strange results returned?
									if ( ArrValues[0] != '' ) {
										alert( 'The server returned unexpected output:\n' + ArrValues[0] );
									}

									// Switch on error code
									switch ( ArrValues[1] ) {

										case '99':	// Photo is gone
											jQuery( '#photoitem-' + photo ).html( bef+'<span style="color:red">' + ArrValues[2] + '</span>'+aft );
											break;

										default:	// No or recoverable error

											// Extract update felds
											var updates = JSON.parse( ArrValues[2] );
											var fieldName;
											var fieldValue;

											for ( fieldName in updates ) {
												fieldValue = updates[fieldName];

												switch ( fieldName ) {

													case 'remark':
														var text;
														fieldValue = fieldValue.replace(/&lt;/g,'<');
														fieldValue = fieldValue.replace(/&gt;/g,'>');
														fieldValue = fieldValue.replace(/\\/g,'');

														if ( ArrValues[1] != "0" ) { 	// error
															text = '<span style="color:red;" >' + fieldValue + '</span>';
														}
														else { 							// no error
															text = '<span style="color:green;" >' + fieldValue + '</span>';
														}
														jQuery( "#remark-" + photo ).html( text );
														break;

													case 'photourl':
														if ( wppaCropper[photo] ) {
															var c = wppaCropper[photo];
															c.replace(fieldValue);
														}
														else {
															jQuery( "#photourl-" + photo ).attr('src', fieldValue);
														}
														jQuery( "#thumba-" + photo ).attr('href', fieldValue);
														break;

													case 'thumburl':
														jQuery( "#thumburl-" + photo ).attr('src', fieldValue);
														break;

													case 'magickstack':
														jQuery( "#magickstack-" + photo ).html( fieldValue );
														if ( fieldValue.length > 0 ) {
															jQuery( '#imstackbutton-' + photo ).css( 'display', 'inline' );
														}
														else {
															jQuery( '#imstackbutton-' + photo ).css( 'display', 'none' );
														}
														break;

													default:
														//alert( fieldName + ' not supported');
														//jQuery( "#" + fieldName + "-" + photo ).html( fieldValue );
														break;
												}
											}

											break;

									}

									// Front-end button
									wppaFeAjaxLog('out');

								},
					error: 		function( xhr, status, error ) {

									// Update status
									jQuery( '#remark-' + photo ).html( '<span style="color:red;" >Comm error ' + status + '</span>' );

									// Log error
									wppaConsoleLog( 'wppaAjaxUpdatePhoto failed. Error = ' + error + ', status = ' + status );
								},
					complete: 	function( xhr, status, newurl ) {
									jQuery( '#wppa-admin-spinner' ).css( 'display', 'none' );
									jQuery(window).trigger('scroll');
								}
	} )
}

// Do the ajax update photo request
function wppaAjaxUpdatePhoto( photo, actionslug, value, reload, bef, aft ) {

	if ( ! bef ) bef = '';
	if ( ! aft ) aft = '';

	// On Front-end edit photo classic style, there is a button: Update and exit.
	// Set it to the desired state
	wppaFeAjaxLog('in');

	// Open ajax object
	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa&wppa-action=update-photo' +
								'&photo-id=' + photo +
								'&item=' + actionslug +
								'&wppa-nonce=' + document.getElementById( 'photo-nonce-' + photo ).value +
								'&value=' + wppaEncode( value ),
					async: 		true,
					type: 		'POST',
					timeout: 	60000,
					beforeSend: function( xhr ) {

									// Show spinner
									if ( actionslug == 'description' ) {
										jQuery('#wppa-photo-spin-'+photo).css( { visibility: 'visible' } );
									}

									// Update status
									jQuery( '#remark-' + photo ).html( 'Working, please wait...' );

								},
					success: 	function( result, status, xhr ) {

									// Format result
									var str = wppaTrim( result );
									var ArrValues = str.split("||");

									// Any strange results returned?
									if ( ArrValues[0] != '' ) {
										alert( 'The server returned unexpected output:\n' + ArrValues[0] );
									}

									// Switch on error code
									switch ( ArrValues[1] ) {

										case '99':	// Photo is gone
											jQuery( '#photoitem-' + photo ).html( bef+'<span style="color:red">' + ArrValues[2] + '</span>'+aft );
											break;

										default:	// No or recoverable error

											// Extract update felds
											var updates = JSON.parse( ArrValues[2] );
											var fieldName;
											var fieldValue;

											for ( fieldName in updates ) {
												fieldValue = updates[fieldName];

												switch ( fieldName ) {

													case 'remark':
														var text;
														fieldValue = fieldValue.replace(/&lt;/g,'<');
														fieldValue = fieldValue.replace(/&gt;/g,'>');
														fieldValue = fieldValue.replace(/\\/g,'');

														if ( ArrValues[1] != "0" ) { 	// error
															text = '<span style="color:red;" >' + fieldValue + '</span>';
														}
														else { 							// no error
															text = '<span style="color:green;" >' + fieldValue + '</span>';
														}
														if ( reload ) {
															text += ' <span style="color:blue;" >Reloading...</span>';
														}
														jQuery( "#remark-" + photo ).html( text );
														break;

													case 'photourl':
														if ( wppaCropper[photo] ) {
															var c = wppaCropper[photo];
															c.replace(fieldValue);
														}
														else {
															jQuery( "#photourl-" + photo ).attr('src', fieldValue);
														}
														jQuery( "#thumba-" + photo ).attr('href', fieldValue);
														break;

													case 'thumburl':
														jQuery( "#thumburl-" + photo ).attr('src', fieldValue);
														break;

													case 'magickstack':
														jQuery( "#magickstack-" + photo ).html( fieldValue );
														if ( fieldValue.length > 0 ) {
															jQuery( '#imstackbutton-' + photo ).css( 'display', 'inline' );
														}
														else {
															jQuery( '#imstackbutton-' + photo ).css( 'display', 'none' );
														}
														break;

													case 'tags':
														jQuery( '#tags-' + photo ).val( fieldValue );
														break;

													default:
														jQuery( "#" + fieldName + "-" + photo ).html( fieldValue );
														break;
												}
											}

											break;

									}

									// Hide spinner
									if ( actionslug == 'description' ) {
										jQuery('#wppa-photo-spin-'+photo).css( { visibility:'hidden' } );
									}

									// Front-end button
									wppaFeAjaxLog('out');

								},
					error: 		function( xhr, status, error ) {

									// Update status
									jQuery( '#remark-' + photo ).html( '<span style="color:red;" >Comm error ' + status + '</span>' );

									// Log error
									wppaConsoleLog( 'wppaAjaxUpdatePhoto failed. Error = ' + error + ', status = ' + status );
								},
					complete: 	function( xhr, status, newurl ) {
									var href = document.location.href;
									href = href.replace(/&pano-val=./,'');

									if ( reload ) {
										setTimeout( function(){document.location.href=href;}, 300 );
										return;
									}

									jQuery( '#wppa-admin-spinner' ).css( 'display', 'none' );
									jQuery(window).trigger('scroll');

								}
				} );
}


function wppaChangeScheduleAlbum(album, elem) {
	var onoff = jQuery(elem).prop('checked');
	if ( onoff ) {
		jQuery('.wppa-datetime-'+album).css('display', 'inline');
	}
	else {
		jQuery('.wppa-datetime-'+album).css('display', 'none');
		wppaAjaxUpdateAlbum(album, 'scheduledtm', Math.rand() );
	}
}

function wppaChangeScheduleDelAlbum(album, elem) {
	var onoff = jQuery(elem).prop('checked');
	if ( onoff ) {
		jQuery('.wppa-datetimedel-'+album).css('display', 'inline');
	}
	else {
		jQuery('.wppa-datetimedel-'+album).css('display', 'none');
		wppaAjaxUpdateAlbum(album, 'scheduledel', Math.rand() );
	}
}

var _wppaRefreshAfter = false;

var wppaAjaxAlbumCount = 0;
var wppaAlbumUpdateMatrix = new Array();

// Update album
//
// @1: integer album id
// @2: string action slug
// @3: elem ( this or getElementById() from caller ) OR a random number.
//     The number must be different in successive calls to trigger subsequent actions.
//     If the number is the same as before, no change is assumed.
// @4: bool: indicating if page reload needed after action.
function wppaAjaxUpdateAlbum(album, actionslug, elemOrValue, refresh) {

	// Indexes in udate matrix
	var albidx = 0;
	var slgidx = 1;
	var ovlidx = 2;
	var nvlidx = 3;
	var bsyidx = 4;
	var refidx = 5;

	// Are we using TynyMce?
	var isTmce = jQuery( "#wppaalbumdesc:visible" ).length == 0;
	jQuery( "#wppaalbumdesc-html" ).click();

	// Init
	var count = wppaAlbumUpdateMatrix.length;
	var i = 0;
	var found = false;
	var index = -1;

	// See if we did this slug for this album already
	while ( i < count ) {
		if ( wppaAlbumUpdateMatrix[i][albidx] == album && wppaAlbumUpdateMatrix[i][slgidx] == actionslug ) {
			found = true;
			index = i;
		}
		i++;
	}

	// Not done this yet, create new entry in array
	if ( ! found ) {
		var oldval = 'undefined';
		var newval = false;
		var busy = false;
		wppaAlbumUpdateMatrix[count] = [album, actionslug, oldval, newval, busy, refresh];
		index = count;
	}

	// Update array
	var value;
	if ( typeof( elemOrValue ) == 'object' ) {
		value = elemOrValue.value;
	}
	else {
		value = elemOrValue;
	}
	wppaAlbumUpdateMatrix[index][nvlidx] = value;
	wppaAlbumUpdateMatrix[index][refidx] = refresh;

	// Run the monitor
	wppaAjaxUpdateAlbumMonitor( isTmce );
}

// This monitor keeps track of running ajax requests
// If many chars are typed quickly ( busy flag true ) updating will be skipped
// until the running ajax request ends. A new request will catch up the rest of the data mods.
function wppaAjaxUpdateAlbumMonitor( isTmce ) {

	var albidx = 0;	// album id
	var slgidx = 1; // action slug
	var ovlidx = 2; // old value
	var nvlidx = 3; // new value
	var bsyidx = 4; // busy flag
	var refidx = 5; // page refresh after completion

	var count = wppaAlbumUpdateMatrix.length;
	var i = 0;

	// Find the entries in the matrix for the album/slug cominations where the new value is unequal to the old value,
	// where this combi is not busy ( operation in progress ).
	// For such matrix entries: set busy flag and start an ajax action
	while ( i < count ) {

		if ( ( wppaAlbumUpdateMatrix[i][ovlidx] != wppaAlbumUpdateMatrix[i][nvlidx] ) && ! wppaAlbumUpdateMatrix[i][bsyidx] ) {

			// Set busy
			wppaAlbumUpdateMatrix[i][bsyidx] = true;

			// Start ajax
			_wppaAjaxUpdateAlbum( wppaAlbumUpdateMatrix[i][albidx], wppaAlbumUpdateMatrix[i][slgidx], wppaAlbumUpdateMatrix[i][nvlidx], isTmce, wppaAlbumUpdateMatrix[i][refidx] );
		}
		i++;
	}
	if ( isTmce ) jQuery( "#wppaalbumdesc-tmce" ).click();
}

// Do the actual ajax update request
function _wppaAjaxUpdateAlbum( album, actionslug, value, isTmce, refresh ) {

	// Increment total number of ajax actions pending
	wppaAjaxAlbumCount++;

	var albidx = 0;	// album id
	var slgidx = 1; // action slug
	var ovlidx = 2; // old value
	var nvlidx = 3; // new value
	var bsyidx = 4; // busy flag
	var refidx = 5; // page refresh after completion

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa&wppa-action=update-album' +
								'&album-id=' + album +
								'&item=' + actionslug +
								'&wppa-nonce=' + document.getElementById( 'album-nonce-' + album ).value +
								'&value=' + wppaEncode( value ),
					async: 		true,
					type: 		'POST',
					timeout: 	60000,
					beforeSend: function( xhr ) {

									// Show spinner
									if ( actionslug == 'description' ) {
										jQuery( '#wppa-album-spin' ).css( { visibility: 'visible' } );
									}

									// Update status
									jQuery( '#albumstatus-' + album ).html( 'Working, please wait... (' + wppaAjaxAlbumCount + ')' );
								},
					success: 	function( result, status, xhr ) {

									// Format result
									var str = wppaTrim( result );
									var ArrValues = str.split( "||" );

									// One pending less
									wppaAjaxAlbumCount--;

									// Any strange results returned?
									if ( ArrValues[0] != '' ) {
										alert( 'The server returned unexpected output:\n' + ArrValues[0] );
									}

									// Update status field
									switch ( ArrValues[1] ) {
										case '0':		// No error

											// Update status
											// Done?
											if ( wppaAjaxAlbumCount == 0 ) {
												jQuery( '#albumstatus-' + album ).html( ArrValues[2] );
												if ( wppaGetCoverPreview ) {
													wppaGetCoverPreview( album, 'cover-preview-'+album );
												}
											}
											// Not done yet
											else {
												jQuery( '#albumstatus-' + album ).html( 'Working, please wait... (' + wppaAjaxAlbumCount + ')' );
											}
											break;

										case '1': 	// Nothing updated
											jQuery( '#albumstatus-' + album ).html( '<span style="color:blue">' + ArrValues[2] + ' (' + ArrValues[1] + ')</span>' );
											break;

										default:		// Any error
											jQuery( '#albumstatus-' + album ).html( '<span style="color:red">' + ArrValues[2] + ' (' + ArrValues[1] + ')</span>' );
											break;
									}

									// Process full/notfull. The last action may have caused changing the status of 'album full'
									if ( typeof( ArrValues[3] ) != 'undefined' ) {
										wppaProcessFull( ArrValues[3], ArrValues[4] );
									}

									// Need refresh? Refresh only if no error
									if ( refresh && ArrValues[1] == '0' ) {

										// Indicate reloading
										jQuery( '#albumstatus-' + album ).after( '<span style="color:blue;font-weight:bold;"> Reloading...</span>' );

										// Show spinner
										jQuery( '#wppa-admin-spinner' ).fadeIn();

										// Plan reloading
										setTimeout( function() { wppaReload() }, 100 );
										return;
									}

									// Cover link
									if ( actionslug == 'cover_linktype' ) {
										if ( value == 'manual' ) {
											jQuery( '#link-url-tr' ).show();
											jQuery( '#-link-url-tr' ).hide();
										}
										else {
											jQuery( '#link-url-tr' ).hide();
											jQuery( '#-link-url-tr' ).show();
										}
									}

									// No reload: Hide spinner
									if ( actionslug == 'description' ) {
										jQuery( '#wppa-album-spin' ).css( { visibility: 'hidden' } );
									}

									// Init Update Matrix
									var i = 0;
									var index = -1;
									var count = wppaAlbumUpdateMatrix.length;

									// Find this actioslugs matrix index
									while ( i < count ) {
										if ( wppaAlbumUpdateMatrix[i][albidx] == album && wppaAlbumUpdateMatrix[i][slgidx] == actionslug ) {
											index = i;
										}
										i++;
									}

									// Do update matrix
									wppaAlbumUpdateMatrix[index][2] = ( value ? value : 0 );
									wppaAlbumUpdateMatrix[index][4] = false; 	// No more busy
									wppaAlbumUpdateMatrix[index][5] = false;	// reset refresh

									// Update monitor
									wppaAjaxUpdateAlbumMonitor( isTmce );	// Check for more to do


								},
					error: 		function( xhr, status, error ) {

									// One pending less
									wppaAjaxAlbumCount--;

									// Update status
									jQuery( '#albumstatus-' + album ).html( '<span style="color:red;" >Comm error ' + status + '</span>' );

									// Log error
									wppaConsoleLog( '_wppaAjaxUpdateAlbum failed. Error = ' + error + ', status = ' + status );
								},
					complete: 	function( xhr, status, newurl ) {

								}
				} );
}

function wppaProcessFull( arg, n ) {

	if ( arg == 'full' ) {
		jQuery('#full').css('display', '');
		jQuery('#notfull').css('display', 'none');
	}
	if ( arg == 'notfull' ) {
		jQuery('#full').css('display', 'none');
		if ( n > 0 ) jQuery('#notfull').attr('value', __( 'Upload to this album', 'wp-photo-album-plus' )+' (max '+n+')');
		else jQuery('#notfull').attr('value', __( 'Upload to this album', 'wp-photo-album-plus' ));
		jQuery('#notfull').css('display', '');
	}
}

function wppaAjaxUpdateCommentStatus( photo, id, value ) {

	var xmlhttp = wppaGetXmlHttp();

	// Make the Ajax url
	var url = wppaAjaxUrl+	'?action=wppa&wppa-action=update-comment-status'+
							'&wppa-photo-id='+photo+
							'&wppa-comment-id='+id+
							'&wppa-comment-status='+value+
							'&wppa-nonce='+document.getElementById('photo-nonce-'+photo).value;

	xmlhttp.onreadystatechange=function() {
		if ( xmlhttp.readyState == 4 ) {
			if ( xmlhttp.status == 200 ) {
				var str = wppaTrim(xmlhttp.responseText);
				var ArrValues = str.split("||");

				if (ArrValues[0] != '') {
					alert('The server returned unexpected output:\n'+ArrValues[0]);
				}
				switch (ArrValues[1]) {
					case '0':		// No error
						jQuery('#remark-'+photo).html(ArrValues[2]);
						break;
					default:	// Error
						jQuery('#remark-'+photo).html('<span style="color:red">'+ArrValues[2]+'</span>');
						break;
				}
				jQuery('#wppa-comment-spin-'+id).css('visibility', 'hidden');
			}
			else {	// status != 200
				jQuery('#remark-'+photo).html('<span style="color:red;" >Comm error '+xmlhttp.status+': '+xmlhttp.statusText+'</span>');
			}
		}
	}

	// Do the Ajax action
	xmlhttp.open('GET',url,true);
	xmlhttp.send();
}

function wppaAjaxUpdateOptionCheckBox(slug, elm) {

	var myData = 	'action=wppa' +
					'&wppa-action=update-option&wppa-option='+slug+
					'&wppa-nonce='+document.getElementById('wppa-nonce').value;
					if (elm.checked) myData += '&value=yes';
					else myData += '&value=no';

			//		jQuery('#img_'+slug).attr('src',wppaImageDirectory+'spinner.gif');

	jQuery.ajax( {	url: 		wppaAjaxUrl,
					data: 		myData,
					async: 		true,
					type: 		'POST',
					timeout: 	10000,
					beforeSend:	function( xhr, settings ) {
									jQuery('#img_'+slug).attr('src',wppaImageDirectory+'spinner.gif');
								},
					success: 	function( result, status, xhr ) {
									var str = wppaTrim(result);
									var ArrValues = str.split("||");

									if (ArrValues[0] != '') {
										alert('The server returned unexpected output:\n'+ArrValues[0]);
									}

									else {
										switch (ArrValues[1]) {
											case '0':	// No error
												jQuery('#img_'+slug).attr('src',wppaImageDirectory+'tick.png');
												jQuery('#img_'+slug).attr('title',ArrValues[2]);
												if ( ArrValues[3] ) alert(ArrValues[3]);
												if ( _wppaRefreshAfter ) {
													_wppaRefreshAfter = false;
													setTimeout(function(){document.location.reload(true);}, 200);
												}
												if ( _wppaPlanPotdUpdate ) {
													_wppaPlanPotdUpdate = false;
													setTimeout(function(){wppaUpdatePotdInfo();}, 200);
												}
												if ( _wppaPlanUpdateWatermarkPreview ) {
													_wppaPlanUpdateWatermarkPreview = false;
													setTimeout(function(){wppaUpdateWatermarkPreview();}, 200);
												}
												break;
											default:
												jQuery('#img_'+slug).attr('src',wppaImageDirectory+'cross.png');
												jQuery('#img_'+slug).attr('title','Error #'+ArrValues[1]+', message: '+ArrValues[2]+', status: '+status);
												if ( ArrValues[3] ) alert(ArrValues[3]);
												if ( _wppaRefreshAfter ) {
													_wppaRefreshAfter = false;
													setTimeout(function(){document.location.reload(true);}, 200);
												}
												if ( _wppaPlanPotdUpdate ) {
													_wppaPlanPotdUpdate = false;
													setTimeout(function(){wppaUpdatePotdInfo();}, 200);
												}
												if ( _wppaPlanUpdateWatermarkPreview ) {
													_wppaPlanUpdateWatermarkPreview = false;
													setTimeout(function(){wppaUpdateWatermarkPreview();}, 200);
												}
										}
									}
								},
					error: 		function( xhr, status, error ) {
									jQuery('#img_'+slug).attr('src',wppaImageDirectory+'cross.png');
									jQuery('#img_'+slug).attr('title','Communication error, status = '+xmlhttp.status);
								},
					complete: 	function() {
									wppaCheckInconsistencies();
								}
				} );



/*
	var xmlhttp = wppaGetXmlHttp();

	// Make the Ajax url
	var url = wppaAjaxUrl+'?action=wppa&wppa-action=update-option&wppa-option='+slug;
	url += '&wppa-nonce='+document.getElementById('wppa-nonce').value;
	if (elem.checked) url += '&value=yes';
	else url += '&value=no';

	// Process the result
	xmlhttp.onreadystatechange=function() {
		switch (xmlhttp.readyState) {
		case 1:
		case 2:
		case 3:
			jQuery('#img_'+slug).attr('src',wppaImageDirectory+'spinner.gif');
			break;
		case 4:
			var str = wppaTrim(xmlhttp.responseText);
			var ArrValues = str.split("||");

			if (ArrValues[0] != '') {
				alert('The server returned unexpected output:\n'+ArrValues[0]);
			}
			if (xmlhttp.status!=404) {
				switch (ArrValues[1]) {
					case '0':	// No error
						jQuery('#img_'+slug).attr('src',wppaImageDirectory+'tick.png');
						jQuery('#img_'+slug).attr('title',ArrValues[2]);
						if ( ArrValues[3] ) alert(ArrValues[3]);
						if ( _wppaRefreshAfter ) {
							_wppaRefreshAfter = false;
							document.location.reload(true);
						}
						break;
					default:
						jQuery('#img_'+slug).attr('src',wppaImageDirectory+'cross.png');
						jQuery('#img_'+slug).attr('title','Error #'+ArrValues[1]+', message: '+ArrValues[2]+', status: '+xmlhttp.status);
						if ( ArrValues[3] ) alert(ArrValues[3]);
						if ( _wppaRefreshAfter ) {
							_wppaRefreshAfter = false;
							document.location.reload(true);
						}
				}

			}
			else {
				jQuery('#img_'+slug).attr('src',wppaImageDirectory+'cross.png');
				jQuery('#img_'+slug).attr('title','Communication error, status = '+xmlhttp.status);
			}
			wppaCheckInconsistencies();
		}
	}

	// Do the Ajax action
	xmlhttp.open('GET',url,true);
	xmlhttp.send();
*/
}

var wppaAlwaysContinue = 100;

function wppaMaintenanceProc(slug, intern, asCronJob ) {

	// If running: stop
	if ( asCronJob ) {
	}
	else if ( ! intern && document.getElementById(slug+"_continue").value == 'yes' ) {
		document.getElementById(slug+"_continue").value = 'no';
		document.getElementById(slug+"_button").value = 'Start!';
		if ( jQuery("#"+slug+"_togo").html() > 0 ) {
			jQuery("#"+slug+"_status").html('Pausing...');
			jQuery("#"+slug+"_button").css('display', 'none');
		}
		return;
	}

	// Start
	if ( asCronJob ) {
	}
	else {
		document.getElementById(slug+"_continue").value = 'yes';
		document.getElementById(slug+"_button").value = 'Stop!';
		if ( jQuery("#"+slug+"_status").html() == '' ) {
			jQuery("#"+slug+"_status").html('Wait...');
		}
	}

	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa'+
								'&wppa-action=maintenance'+
								'&slug='+slug+
								'&wppa-nonce='+jQuery('#wppa-nonce').val()+
								( asCronJob ? '&wppa-cron=1' : '' ),
					async: 		true,
					type: 		'POST',
					timeout: 	300000,
					beforeSend: function( xhr ) {

								},
					success: 	function( result, status, xhr ) {

									// sample: '<error>||<slug>||<status>||<togo>'
									var resparr = result.split("||");
									var slug 	= resparr[1];
									var error 	= false;

									// Check for unrecoverable error
									if ( ! slug ) {
										alert('The server returned unexpected output:\n'+result+'\nIf the current procedure has a Skip One button, press it before retrying. Reloading page...');
										wppaReload();
										return;	// give up;
									}

									// Check for recoverable error
									if ( resparr[0].length > 10 ) {
										var errtxt = resparr[0];
										errtxt = errtxt.replace(/&gt;/g,'>');
										alert('An error occurred:\n'+errtxt);
										error = true;
									}

									// Update status and togo
									jQuery("#"+slug+"_status").html(resparr[2]);
									jQuery("#"+slug+"_togo").html(resparr[3]);
									jQuery("#"+slug+"_button").css('display', '');

									// Stop on error or on ready
									if ( error || resparr[3] == '0' ) {
										if ( resparr[4] == 'reload' ) {
											alert('This page will now be reloaded to finish the operation. Please stay tuned...');
											wppaReload();
											return;
										}
										else {
											setTimeout('wppaMaintenanceProc(\''+slug+'\', false)', 20);	// fake extern to stop it
										}
										return;
									}

									// Continue if not stopped by user
									if ( document.getElementById(slug+"_continue") ) {
										if ( document.getElementById(slug+"_continue").value == 'yes' ) {
											setTimeout('wppaMaintenanceProc(\''+slug+'\', true)', 20);
											return;
										}
									}

									// Stopped but not ready yet
									if ( ! asCronJob ) {
										jQuery("#"+slug+"_status").html('Pending');
									}

									// Start update togo
									setTimeout( function() {wppaAjaxUpdateTogo(slug);}, 1000 );
								},

					error: 		function( xhr, status, error ) {
									wppaConsoleLog( 'wppaMaintenanceProc failed. Slug = ' + slug + ', Error = ' + error + ', status = ' + status );
									jQuery("#"+slug+"_status").html('Server error #'+(11-wppaAlwaysContinue));
									var wppaContinue = false;
									wppaAlwaysContinue--;
									if ( wppaAlwaysContinue < 1 ) {
										wppaContinue = confirm( '10 Server errors happened.\nDo you want to continue?' );
										if ( wppaContinue ) {
											wppaAlwaysContinue = 100;
										}
									}
									if ( wppaContinue || wppaAlwaysContinue > 0 ) {
										if ( slug == 'wppa_remake' ) {
											wppaAjaxUpdateOptionValue( 'wppa_remake_skip_one', 0 );
										}
										if ( slug == 'wppa_regen_thumbs' ) {
											wppaAjaxUpdateOptionValue( 'wppa_regen_thumbs_skip_one', 0 );
										}
										if ( slug == 'wppa_create_o1_files' ) {
											wppaAjaxUpdateOptionValue( 'wppa_create_o1_files_skip_one', 0 );
										}
										setTimeout('wppaMaintenanceProc(\''+slug+'\', true)', 2000);
									}
								},

					complete: 	function( xhr, status, newurl ) {

								}
	} );
}

function wppaAjaxPopupWindow( slug ) {


	var name;
	switch ( slug ) {
		case 'wppa_list_index':
			name = 'Search index table';
			break;
		case 'wppa_list_errorlog':
			name = 'WPPA+ Error log';
			break;
		case 'wppa_list_rating':
			name = 'Recent ratings';
			break;
		case 'wppa_list_session':
			name = 'Active sessions';
			break;
		case 'wppa_list_comments':
			name = 'Recent comments';
			break;
	}
	var desc = '';
	var width = wppaWindowWidth() * 0.9;
	var height = 512;

	var xmlhttp = wppaGetXmlHttp();

	// Make the Ajax send data
	var url = wppaAjaxUrl;
	var data = 'action=wppa&wppa-action=maintenancepopup&slug='+slug;
	data += '&wppa-nonce='+document.getElementById('wppa-nonce').value;

	// Do the Ajax action
	xmlhttp.open('POST', url, false);	// Synchronously !!
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send(data);

	// Process result
	if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		var temp = wppaEntityDecode( xmlhttp.responseText ).split('|');
		var dialogtitle = temp[0];
		temp[0] = '';
		var result = temp.join('|').substring(1);

		var opt = {
			modal:		true,
			resizable: 	true,
			width:		width,
			show: 		{
							effect: 	"fadeIn",
							duration: 	800
						},
			closeText: 	__( 'Close!', 'wp-photo-album-plus' ),
		};
		try {
			jQuery( '#wppa-modal-container' ).dialog('destroy');
		}
		catch {
			jQuery( '#wppa-modal-container' ).html('');
		}
		jQuery( '#wppa-modal-container' ).html(result);
		jQuery( '#wppa-modal-container' ).dialog(opt);
		jQuery( '#wppa-modal-container' ).css({width:'100%'});

		jQuery( '.ui-dialog' ).css({
										boxShadow: 			'0px 0px 5px 5px #aaaaaa',
										padding: 			'8px',
										backgroundColor: 	'#cccccc',
										boxSizing: 			'content-box',
										zIndex: 			'200200',
									});
		jQuery( '.ui-dialog-titlebar' ).css({
												lineHeight: '0px',
												height: 	'50px',
											});
		jQuery( '.ui-dialog-title' ).html('<h2>'+dialogtitle+'</h2>');
		jQuery( '.ui-button' ).css({
										position: 	'absolute',
										top: 		'12px',
										right: 		'12px',
									});
		jQuery( '.ui-button' ).attr( 'title', __( 'Close!', 'wp-photo-album-plus' ) );
		jQuery( '.ui-widget-overlay' ).css({
										backgroundColor: 	'transparent',
									});

		setTimeout( function() {
			if (jQuery("#wppa-maintenance-list").niceScroll) {
				jQuery("#wppa-maintenance-list").niceScroll(".wppa-nicewrap",{});
			};
		}, 1000 );

		/* ' . wppa_opt( 'nicescroll_opts' ) . ' */
	}
}

function wppaAjaxUpdateOptionValue(slug, elem, multisel) {

	var data = 	'action=wppa&wppa-action=update-option&wppa-option='+wppaEncode(slug)+
				'&wppa-nonce='+document.getElementById('wppa-nonce').value;
	if ( elem != 0 ) {
		if ( typeof( elem ) == 'number' ) {
			data += '&value='+elem;
		}
		else if ( multisel ) {
			data += '&value='+wppaGetSelectionEnumByClass('.'+slug, ',');
		}
		else {
			data += '&value='+wppaEncode(jQuery(elem).val());
		}
	}

	jQuery.ajax( { 	url:		wppaAjaxUrl,
					data: 		data,
					async: 		true,
					type: 		'POST',
					timeout: 	100000,
					beforeSend: function( xhr ) {
									jQuery( '#img_'+slug.replace('#','H') ).attr( 'src', wppaImageDirectory+'spinner.gif' );
								},
					success: 	function( result, status, xhr ) {
									var str = wppaTrim(result);
									var ArrValues = str.split("||");
									if (ArrValues[0] != '') {
										alert('The server returned unexpected output:\n'+ArrValues[0]);
									}
									else {
										// Process result
										switch (ArrValues[1]) {
											case '0':	// No error
												jQuery( '#img_'+slug.replace('#','H') ).attr( 'src', wppaImageDirectory+'tick.png' );
												if ( ArrValues[3] ) alert(ArrValues[3]);
												if ( _wppaRefreshAfter ) {
													_wppaRefreshAfter = false;
													setTimeout(function(){document.location.reload(true);}, 200);
												}
												if ( _wppaPlanPotdUpdate ) {
													_wppaPlanPotdUpdate = false;
													setTimeout(function(){wppaUpdatePotdInfo();}, 200);
												}
												if ( _wppaPlanUpdateWatermarkPreview ) {
													_wppaPlanUpdateWatermarkPreview = false;
													setTimeout(function(){wppaUpdateWatermarkPreview();}, 200);
												}
												break;
											default:
												jQuery( '#img_'+slug.replace('#','H') ).attr( 'src', wppaImageDirectory+'cross.png' );
												if ( ArrValues[3] ) alert(ArrValues[3]);
												if ( _wppaRefreshAfter ) {
													_wppaRefreshAfter = false;
													setTimeout(function(){document.location.reload(true);}, 200);
												}
												if ( _wppaPlanPotdUpdate ) {
													_wppaPlanPotdUpdate = false;
													setTimeout(function(){wppaUpdatePotdInfo();}, 200);
												}
												if ( _wppaPlanUpdateWatermarkPreview ) {
													_wppaPlanUpdateWatermarkPreview = false;
													setTimeout(function(){wppaUpdateWatermarkPreview();}, 200);
												}
										}
										jQuery( '#img_'+slug.replace('#','H') ).attr( 'title', ArrValues[2] );

										// Update cron statusses
										if (  ArrValues[4] ) {
											var tokens = ArrValues[4].split( ';' );
											var i = 0;
											var temp;
											var Old, New;
											while ( i < tokens.length ) {
												temp = tokens[i].split( ':' );
												Old = jQuery( '#'+ temp[0] ).html();
												New = temp[1];
												if ( Old != '' && New == '' ) {
													New = '<input type="button" class="button-secundary" style="border-radius:3px;font-size:11px;height:18px;margin: 0 4px;padding:0px;color:red;background-color:pink;" onclick="document.location.reload(true)" value="Reload" />';
												}
												jQuery( '#'+ temp[0] ).html( New );
												i++;
											}
										}
									}
								},
					error: 		function( xhr ) {
									jQuery('#img_'+slug.replace('#','H')).attr('src', wppaImageDirectory+'cross.png');
									jQuery('#img_'+slug.replace('#','H')).attr('title', 'Communication error');
								},
					complete: 	function( xhr ) {
									wppaCheckInconsistencies();
									if ( slug == 'spinner_shape' || slug == 'icon_corner_style' ) {
										wppaAjaxGetSpinnerHtml( 'normal', 'wppa-spin-pre-1' );
										wppaAjaxGetSpinnerHtml( 'lightbox', 'wppa-spin-pre-2' );
									}
									if ( slug == 'svg_color' || slug == 'svg_bg_color' ) {
										wppaAjaxGetSpinnerHtml( 'normal', 'wppa-spin-pre-1' );
									}
									if ( slug == 'ovl_svg_color' || slug == 'ovl_svg_bg_color' ) {
										wppaAjaxGetSpinnerHtml( 'lightbox', 'wppa-spin-pre-2' );
									}
								}
				} );
}

function wppaEncode(xtext) {

	if ( typeof(xtext) != 'string' ) return xtext;

	var text, result;

	text = xtext;
	result = text.replace(/#/g, '%23');
	text = result;
	result = text.replace(/&/g, '%26');
	text = result;
//	result = text.replace(/+/g, '%2B');
	var temp = text.split('+');
	var idx = 0;
	result = '';
	while (idx < temp.length) {
		result += temp[idx];
		idx++;
		if (idx < temp.length) result += '%2B';
	}

	return result;
}

// Check conflicting settings, Autosave version only
function wppaCheckInconsistencies() {

	// Uses thumb popup and thumb lightbox?
	if ( jQuery('#use_thumb_popup').prop('checked') && jQuery('#thumb_linktype').val() == 'lightbox' ) {
		jQuery('.popup-lightbox-err').css('display', '');
	}
	else {
		jQuery('.popup-lightbox-err').css('display', 'none');
	}
}

// Get the http request object
function wppaGetXmlHttp() {
	if (window.XMLHttpRequest) {		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else {								// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	return xmlhttp;
}

function wppaPhotoStatusChange(id) {

	// Init
	jQuery('#psdesc-'+id).css({display: 'none'});

	elm = document.getElementById('status-'+id);
	if ( ! elm ) return; // From frontend?

	if ( elm.value == 'pending' || elm.value == 'scheduled' ) {
		jQuery('#photoitem-'+id).css({backgroundColor: '#ffebe8', borderColor: '#cc0000'});
	}
	if (elm.value=='publish') {
		jQuery('#photoitem-'+id).css({backgroundColor:'#ffffe0', borderColor:'#e6db55'});
	}
	if (elm.value=='featured') {
		jQuery('#photoitem-'+id).css({backgroundColor: '#e0ffe0', borderColor: '#55ee55'});
		var temp = document.getElementById('pname-'+id).value;
		var name = temp.split('.')
		if (name.length > 1) {
			var i = 0;
			while ( i< name.length ) {
				if (name[i] == 'jpg' || name[i] == 'JPG' ) {
					jQuery('#psdesc-'+id).css({display: ''});
				}
				i++;
			}
		}
	}
	if (elm.value=='gold') {
		jQuery('#photoitem-'+id).css({backgroundColor:'#eeeecc', borderColor:'#ddddbb'});
	}
	if (elm.value=='silver') {
		jQuery('#photoitem-'+id).css({backgroundColor:'#ffffff', borderColor:'#eeeeee'});
	}
	if (elm.value=='bronze') {
		jQuery('#photoitem-'+id).css({backgroundColor:'#ddddbb', borderColor:'#ccccaa'});
	}
	if (elm.value=='private') {
		jQuery('#photoitem-'+id).css({backgroundColor:'transparent', borderColor:'#cccccc'});
	}

	if ( elm.value == 'scheduled' ) {
		jQuery( '.wppa-datetime-'+id ).css('display', ''); //prop( 'disabled', false );
	}
	else {
		jQuery( '.wppa-datetime-'+id ).css('display', 'none'); //prop( 'disabled', true );
	}

	// schedule delete
	if ( jQuery( '#scheduledel-' + id ).prop( 'checked' ) ) {
		jQuery( '.wppa-del-datetime-' + id ).css( 'display', '' );
	}
	else {
		jQuery( '.wppa-del-datetime-' + id ).css( 'display', 'none' );
	}
}

function wppaSetComBgCol( id ) {
	var status = jQuery( '#com-stat-' + id ).val();

	if ( status == 'approved' ) {
		jQuery( '#com-stat-' + id ).css({backgroundColor:'#ffffe0'});
	}
	else {
		jQuery( '#com-stat-' + id ).css({backgroundColor:'#ffebe8'});
	}
}

function wppaAddCat(val, id) {
	wppaAddTag(val, id);
}

function wppaAddTag(val, id) {
	var elm = document.getElementById(id);
	if ( val ) {
		if ( elm.value ) {
			elm.value += ','+val;
		}
		else {
			elm.value = val;
		}
		if ( val == '-clear-' ) {
			elm.value = '';
		}
	}
}

function wppaRefresh(label) {
	var oldurl 	= new String(document.location);
	var temp 	= oldurl.split("#");
	var newurl 	= temp[0]+'#'+label;

	document.location = newurl;
}
function wppaReload(arg) {
	if ( arg ) {
		url = document.location.href.split('#');

		document.location.href = url[0] + arg;
		setTimeout( function(){document.location.reload( true );}, 10 );
	}
	else {
		document.location.reload( true );
	}
}

var wppaFeCount = 0;
function wppaFeAjaxLog(key) {

	if ( key == 'in' ) {
		if ( wppaFeCount == 0 ) {
			jQuery('#wppa-fe-exit').css('display', 'none');
		}
		wppaFeCount++;
		jQuery('#wppa-fe-count').html(wppaFeCount);
	}
	if ( key == 'out' ) {
		if ( wppaFeCount == 1 ) {
			jQuery('#wppa-fe-count').html('');
			jQuery('#wppa-fe-exit').css('display', 'inline');
			wppaFeCount--;
		}
		if ( wppaFeCount > 1 ) {
			wppaFeCount--;
			jQuery('#wppa-fe-count').html(wppaFeCount);
		}
	}
}

function wppaArrayToEnum( arr, sep ) {

	// Step 1. Sort Ascending Numeric
	temp = arr.sort(function(a, b){return a-b});

	// Init
	var result = '';
	var lastitem = -1;
	var previtemp = -2;
	var lastitemp = 0;
	var isrange = false;
	var i = 0;
	var item;
	while ( i < arr.length ) {
		item = arr[i].valueOf();
		if ( item != 0 ) {
			lastitemp = lastitem;
			lastitemp++;
			if ( item == lastitemp ) {
				isrange = true;
			}
			else {
				if ( isrange ) {	// Close range
					if ( lastitem == previtemp ) {	// Range is x . (x+1)
						result += sep + lastitem + sep + item;
					}
					else {
						result += sep + sep + lastitem + sep + item;
					}
					isrange = false;
				}
				else {				// Add single item
					result += sep + item;
				}
			}
			if ( ! isrange ) {
				previtemp = item;
				previtemp++;
			}
			lastitem = item;
		}
		i++;
	}
	if ( isrange ) {	// Don't forget the last if it ends in a range
		result += '..' + lastitem;
	}

	// ltrim .
	while ( result.substr(0,1) == '.' ) result = result.substr(1);

	// ltrim sep
	while ( result.substr(0,1) == sep ) result = result.substr(1);

	return result;
}

function wppaGetSelEnumToId( cls, id ) {
	p = jQuery( '.'+cls );
	var pararr = [];
	i = 0;
	j = 0;
	while ( i < p.length ) {
		if ( p[i].selected ) {
			pararr[j] = p[i].value;
			j++;
		}
		i++;
	}
	jQuery( '#'+id ).val( wppaArrayToEnum( pararr, '.' ) );
}

function wppaGetSelectionEnumByClass( clas, sep ) {
var p;
var parr = [];
var i = 0;
var j = 0;
var result = '';

	if ( ! sep ) {
		sep = '.';
	}
	p = jQuery( clas );
	i = 0;
	j = 0;
	while ( i < p.length ) {
		if ( p[i].selected ) {
			parr[j] = p[i].value;
			j++;
		}
		i++;
	}
	result = wppaArrayToEnum( parr, sep );

	return result;
}

function wppaEditSearch( url, id ) {

	var ss = jQuery( '#'+id ).val();
	if ( ss.length == 0 ) {
		alert('Please enter searchstring');
	}
	else {
		document.location.href = url + '&wppa-searchstring=' + ss;
	}
}

function wppaEditTrash( url ) {
	document.location.href = url;
}

function wppaExportDbTable( table ) {
	jQuery.ajax( { 	url: 		wppaAjaxUrl,
					data: 		'action=wppa' +
								'&wppa-action=export-table' +
								'&table=' + table,
					async: 		true,
					type: 		'GET',
					timeout: 	100000,
					beforeSend: function( xhr ) {
									jQuery( '#' + table + '-spin' ).css( 'display', 'inline' );
								},
					success: 	function( result, status, xhr ) {
									var ArrValues = result.split( "||" );
									if ( ArrValues[1] == '0' ) {	// Ok, no error

										// Publish result
										document.location = ArrValues[2];
									}
									else {

										// Show error
										alert( 'Error: '+ArrValues[1]+'\n\n'+ArrValues[2] );
									}
								},
					error: 		function( xhr, status, error ) {
									alert( 'Export Db Table ' + table + ' failed. Error = ' + error + ', status = ' + status );
								},
					complete: 	function( xhr, status, error ) {
									jQuery( '#' + table + '-spin' ).css( 'display', 'none' );
								}
				} );

}

function wppaDismissAdminNotice(notice, elm) {

	wppaAjaxUpdateOptionCheckBox(notice, elm);
	jQuery('#wppa-wr-').css('display','none');

}

function wppaAjaxUpdateTogo(slug) {

	jQuery.ajax( { 	url:		wppaAjaxUrl,
					data: 		'action=wppa' +
								'&wppa-action=gettogo' +
								'&slug=' + slug,
					async: 		true,
					type: 		'GET',
					timeout: 	100000,
					beforeSend: function( xhr ) {
								},
					success: 	function( result, status, xhr ) {

									// Split status and togo
									var data = result.split('|');

									// Update togo
									jQuery( '#' + slug + '_togo' ).html( data[0] );

									// Update status when not changing to empty, else request user to reload page
									var Old = jQuery( '#' + slug + '_status' ).html();
									var New = data[1];
									if ( Old != '' && New == '' ) {
								//		New = '<span style="color:red;font-weight:bold;" onclick="document.location.reload(true)" >Reload page</span>';
										New = '<input type="button" class="button-secundary" style="border-radius:3px;font-size:11px;height:18px;margin: 0 4px;padding:0px;color:red;background-color:pink;" onclick="document.location.reload(true)" value="Reload" />';
									}
									jQuery( '#' + slug + '_status' ).html( New );

									setTimeout( function() {wppaAjaxUpdateTogo(slug);}, 1000 );
								},
					error: 		function( xhr ) {
								},
					complete: 	function( xhr ) {
								}
				} );
}

// The js equivalence of php's NOT (!)
function wppaIsEmpty( str ) {

	if ( str == null ) return true;
	if ( typeof( str ) == 'undefined' ) return true;
	if ( str == '' ) return true;
	if ( str == false ) return true;
	if ( str == 0 ) return true;

	return false;
}

// Timed confirmationbox
function wppaTimedConfirm( text ) {


	var opt = {
		modal:		true,
		resizable: 	false,
		width:		400,
		show: 		{
						effect: 	"fadeIn",
						duration: 	800
					},
		closeText: 	'X',

		buttons: [
					{
						text: 	"NO",
					//	icon: 	"ui-icon-heart",
						click: 	function() {
									jQuery( this ).dialog( "close" );
								}


					},
					{
						text: 	"YES",
					//	icon: 	"ui-icon-heart",
						click: 	function() {
									jQuery( this ).dialog( "close" );
								}


					},
				]
	};
	jQuery( '#wppa-modal-container' ).html( text ).dialog( opt ).dialog( "open" );
	jQuery( '.ui-dialog' ).css( {
									boxShadow: 			'0px 0px 5px 5px #aaaaaa',
								//	borderRadius: 		wppaBoxRadius+'px',
									padding: 			'8px',
									backgroundColor: 	'#cccccc',
									boxSizing: 			'content-box',
									zIndex: 			'200200',
								});
	jQuery( '.ui-dialog-titlebar' ).css(
											{
												lineHeight: '0px',
												height: 	'32px',
											}
										)
	jQuery( '.ui-button' ).css(
								{
									float: 		'right',
									position: 	'relative',
									bottom: 	'40px',
								});

	jQuery( '.ui-dialog-titlebar-close' ).css(
								{
									display: 	'none',
								});

	jQuery( '.ui-widget-overlay' ).css(
								{
									backgroundColor: 	'transparent',
								});

	jQuery( '.ui-button' ).attr( 'title', __( 'Close!', 'wp-photo-album-plus' ) );
	setTimeout( function(){jQuery('.ui-button').trigger('click');}, 60000 );
}

function wppaAjaxGetSpinnerHtml( type, target ) {

	jQuery.ajax( { 	url:		wppaAjaxUrl,
					data: 		'action=wppa' +
								'&wppa-action=update-option' +
								'&wppa-option=getspinnerpreview' +
								'&type=' + type +
								'&wppa-nonce=' + document.getElementById( 'wppa-nonce' ).value,
					async: 		true,
					type: 		'GET',
					timeout: 	100000,
					beforeSend: function( xhr ) {
								},
					success: 	function( result, status, xhr ) {

									// Split status and data
									var data = result.split('|');

									// Update html
									jQuery( '#' + target ).html( data[0] );

								},
					error: 		function( xhr ) {

								},
					complete: 	function( xhr ) {

								}
					} );
}


// Movable wppa-horizon on photo admin page
function wppaDragHorizon(elmnt) {

	var pos2 = 0, pos4 = 0;

	elmnt.onmousedown = dragMouseDown;

	function dragMouseDown(e) {
		e = e || window.event;
		e.preventDefault();

		// get the mouse cursor position at startup:
		pos3 = e.clientX;
		pos4 = e.clientY;
		document.onmouseup = closeDragElement;

		// call a function whenever the cursor moves:
		document.onmousemove = elementDrag;
	}

	function elementDrag(e) {
		e = e || window.event;
		e.preventDefault();

		// calculate the new cursor position:
//		pos1 = pos3 - e.clientX;
		pos2 = pos4 - e.clientY;
//		pos3 = e.clientX;
		pos4 = e.clientY;

		// set the element's new position:
		elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
//   	elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
	}

	function closeDragElement() {

		// stop moving when mouse button is released:
		document.onmouseup = null;
		document.onmousemove = null;
	}
}

// Photo admin page specific functions
function wppaBulkActionChange( elm, id ) {
	wppa_setCookie( 'wppa_bulk_action',elm.value,365 );
	if ( elm.value == 'wppa-bulk-move-to' || elm.value == 'wppa-bulk-copy-to' ) jQuery( '#wppa-bulk-album' ).css( 'display', 'inline' );
	else jQuery( '#wppa-bulk-album' ).css( 'display', 'none' );
	if ( elm.value == 'wppa-bulk-status' ) jQuery( '#wppa-bulk-status' ).css( 'display', 'inline' );
	else jQuery( '#wppa-bulk-status' ).css( 'display', 'none' );
	if ( elm.value == 'wppa-bulk-owner' ) jQuery( '#wppa-bulk-owner' ).css( 'display', 'inline' );
	else jQuery( '#wppa-bulk-owner' ).css( 'display', 'none' );
}
function wppaBulkDoitOnClick() {
	var photos = jQuery( '.wppa-bulk-photo' );
	var count=0;
	for ( i=0; i< photos.length; i++ ) {
		var photo = photos[i];
		if ( photo.checked ) count++;
	}
	if ( count == 0 ) {
		alert( 'No photos selected' );
		return false;
	}
	var action = document.getElementById( 'wppa-bulk-action' ).value;
	switch ( action ) {
		case '':
			alert( 'No action selected' );
			return false;
			break;
		case 'wppa-bulk-delete':
		case 'wppa-bulk-delete-immediate':
		case 'wppa-bulk-undelete':
			break;
		case 'wppa-bulk-move-to':
		case 'wppa-bulk-copy-to':
			var album = document.getElementById( 'wppa-bulk-album' ).value;
			if ( album == 0 ) {
				alert( 'No album selected' );
				return false;
			}
			break;
		case 'wppa-bulk-status':
			var status = document.getElementById( 'wppa-bulk-status' ).value;
			if ( status == 0 ) {
				alert( 'No status selected' );
				return false;
			}
			break;
		case 'wppa-bulk-owner':
			var owner = documnet.getElementById( 'wppa-bulk-owner' ).value;
			if ( owner == 0 ) {
				alert( 'No new owner selected' );
				return false;
			}
			break;
		default:
			alert( 'Unimplemented action requested: '+action );
			return false;
			break;

	}
	return true;
}
function wppaSetThumbsize( elm ) {
	var thumbsize = elm.value;
	wppa_setCookie( 'wppa_bulk_thumbsize',thumbsize,365 );
	jQuery( '.wppa-bulk-thumb' ).css( 'max-width', thumbsize+'px' );
	jQuery( '.wppa-bulk-thumb' ).css( 'max-height', ( thumbsize/2 )+'px' );
	jQuery( '.wppa-bulk-dec' ).css( 'height', ( thumbsize/2 )+'px' );
}
jQuery(document).ready( function() {
	if ( !document.getElementById( 'wppa-bulk-action' ) ) return;
	var action = wppa_getCookie( 'wppa_bulk_action' );
	if ( action != '' ) {
		document.getElementById( 'wppa-bulk-action' ).value = action;
	}
	if ( action == 'wppa-bulk-move-to' || action == 'wppa-bulk-copy-to' ) {
		jQuery( '#wppa-bulk-album' ).css( 'display','inline' );
		document.getElementById( 'wppa-bulk-album' ).value = wppa_getCookie( 'wppa_bulk_album' );
	}
	if ( action == 'wppa-bulk-status' ) {
		jQuery( '#wppa-bulk-status' ).css( 'display','inline' );
		document.getElementById( 'wppa-bulk-status' ).value = wppa_getCookie( 'wppa_bulk_status' );
	}
	if ( action == 'wppa-bulk-owner' ) {
		jQuery( '#wppa-bulk-owner' ).css( 'display','inline' );
		document.getElementById( 'wppa-bulk-owner' ).value = wppa_getCookie( 'wppa_bulk_owner' );
	}
} );

function wppaToggleConfirmDelete( elm ) {
	var status = jQuery( elm ).prop( 'checked' );
	if ( status ) {
		wppa_setCookie( 'wppaConfirmDelete', 'checked', 365 );
	}
	else {
		wppa_setCookie( 'wppaConfirmDelete', 'unchecked', 365 );
	}
}
function wppaToggleConfirmMove( elm ) {
	var status = jQuery( elm ).prop( 'checked' );
	if ( status ) {
		wppa_setCookie( 'wppaConfirmMove', 'checked', 365 );
	}
	else {
		wppa_setCookie( 'wppaConfirmMove', 'unchecked', 365 );
	}
}
function wppaSetConfirmDelete( id ) {
	var status = wppa_getCookie( 'wppaConfirmDelete' );
	if ( status == 'checked' ) {
		jQuery( '#' + id ).prop( 'checked', true );
	}
	else {
		jQuery( '#' + id ).prop( 'checked', false );
	}
}
function wppaSetConfirmMove( id ) {
	var status = wppa_getCookie( 'wppaConfirmMove' );
	if ( status == 'checked' ) {
		jQuery( '#' + id ).prop( 'checked', true );
	}
	else {
		jQuery( '#' + id ).prop( 'checked', false );
	}
}

// Replace log
function wppaAjaxReplaceLog() {

	jQuery.ajax( { 	url:		wppaAjaxUrl,
					data: 		'action=wppa' +
								'&wppa-action=maintenancepopup' +
								'&slug=wppa_list_errorlog' +
								'&raw=1' +
								'&wppa-nonce=' + jQuery("#wppa-nonce").val(),

					async: 		true,
					type: 		'GET',
					timeout: 	100000,
					beforeSend: function( xhr ) {
									jQuery( "#wppa-spinner" ).show();
								},
					success: 	function( result, status, xhr ) {
									result = wppaEntityDecode(result);
									jQuery( "#wppa-logbody" ).html( result.substr( 1 ));
									jQuery( "#wppa-spinner" ).hide();
								}
	});
}

// Hide admin spinner
jQuery(document).ready( function() {
	setTimeout( "wppaTestAdminReady()", 200 );
});
function wppaTestAdminReady() {
	if ( document.readyState === "complete" ) {
		jQuery( "#wppa-admin-spinner" ).fadeOut();
	}
	else {
		setTimeout( "wppaTestAdminReady()", 200 );
	}
}

// Util for comment admin
function wppaCommentAdminUpdateHref( id ) {

	var val 	= encodeURIComponent(jQuery("#commenttext-"+id).val());
	var href 	= jQuery("#href-"+id).attr("href");
	var arr 	= href.split("commenttext=");
	arr[1] 		= val;
	href 		= arr[0] + "commenttext=" + arr[1];
	jQuery("#href-"+id).attr("href", href);
	jQuery("#href-"+id).css("display","inline");
}

// Init Imagick on photo admin page
function wppaInitMagick(id) {

	// Been here before for this id?
	if ( wppaCropper[id] ) return;

	var image = document.querySelector("#fs-img-"+id);
	var button = document.getElementById("button-"+id);
	var ratio;

	if ( wppaImageMagickDefaultAspect == 'ratio' ) {
		ratio = image.naturalWidth / image.naturalHeight;
	}
	else if ( wppaImageMagickDefaultAspect == 'NaN' ) {
		ratio = '';
	}
	else {
		ratio = wppaImageMagickDefaultAspect;
	}

	wppaCropper[id] = new Cropper(image, {
		zoomable: false,
		viewMode: 2,
		background: false,
		dragMode: "move",
		responsive: true,
		movable: false,
		aspectRatio: ratio,
	});

	button.onclick = function () {
		var data = wppaCropper[id].getData(true);
		var value=data.width+"x"+data.height+(data.x<0?"-":"+")+data.x+(data.y<0?"-":"+")+data.y;
		wppaTryMagick( id, "crop", value );
	};
};