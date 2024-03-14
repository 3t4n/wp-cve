// Admin upload jquery
jQuery(document).ready(function($) {

	/*---------------------------------------------------------------------------------------------------------*/
	/*On page load*/

	var popupClosable = true; // Variable to set whether popup can be closed or not

	// Add option to bulk actions on list mode.
	$('#bulk-action-selector-top, #bulk-action-selector-bottom').each( function() {
		var newOption = '<option value="jabd-download">' + jabd_downloader.download_option + '</option>';
		$(this).append(newOption);
	});

	// Add bulk download button to grid mode
	var	downloadBtnHtml = '<button type="button" class="button media-button button-primary button-large jabd-download-selected-button" disabled="disabled">Download</button>',
		selectMode = false;
	$('body').on( 'click', '.select-mode-toggle-button', function() {
		var btn = $('.jabd-download-selected-button');
		if ( ! btn.length ) {
			$('.delete-selected-button').after(downloadBtnHtml);
		} else {
			// Toggle display status.
			if ( btn.hasClass('hidden') ) {
				btn.removeClass('hidden');
			} else {
				btn.addClass('hidden');
				btn.prop('disabled', true);
			}
		}
		selectMode = selectMode ? false : true;
	});

	// Enable / disable download button on toggling attachment for selection in grid mode
	if ( 'undefined' != typeof( wp.media.frame ) ) {
		wp.media.frame.on('selection:toggle', function() {
			
			if ( ! selectMode ) {
				return false;
			}

			// If any selected, activate button, otherwise deactivate
			var someSelected = false,
				btn = $('.jabd-download-selected-button');
			$('.attachment.save-ready').each( function() {
				if ( $(this).hasClass('selected') ) {
					someSelected = true;
					return false;
				}
			});
			if ( someSelected ) {
				if ( true == btn.prop('disabled') ) {
					btn.prop('disabled', false);
				}
			} else {
				btn.prop('disabled', true);
			}

		});
	}

	// Handle request for attachments data in grid mode
	$(document).on('click', '.jabd-download-selected-button', function() {
		var attmtIds = getGridAttmtIds();
		requestAttmtsData(attmtIds);
	});

	// On form submission in list mode, hijack the process if the download option has been selected
	$('#posts-filter').submit( function(e){

		// Check if Download has been selected
		if (
			$('#bulk-action-selector-top').val() != 'jabd-download' &&
			$('#bulk-action-selector-bottom').val() != 'jabd-download'
		) {
			return true;
		} else {

			// Prevent form submitting
			e.preventDefault();

			// Check if checkboxes have been checked
			var attmtIds = getAttmtIds();
			if ( attmtIds.length > 0 ) {

				// Request data
				requestAttmtsData( attmtIds );
				
			} else {
				return false;
			}

		}

    });

	// On click of download button, create the download
	$('body').on( 'click', '#jabd-create-download', function() {
		processDownloadRequest();
	});

	// Handle close of download form
	$('body').on( 'click', '.jabd-popup-overlay, .jabd-popup-container, #jabd-close-download-popup', function() {
		if (popupClosable) { closeDownloadRequestPopup(); }
	});
	$('body').on( 'click', '.jabd-popup', function(evt) {
		evt.stopPropagation();
	});
	
	// Request data
	function requestAttmtsData( attmtIds ) {
		$.ajax({
			url : ajaxurl,
			type : 'post',
			beforeSend: function() {
				displayGatheringData();
			},
			data : {
				action			: 'jabd_request_download',
				doaction		: 'getdata',
				downloadNonce	: jabd_downloader.download_nonce,
				attmtIds		: attmtIds,
			},
			xhrFields: {
				withCredentials: true
			},
			success : function( response ) {
				requestResponse( response );
			}
		});
	}
	
	// Display gathering data message
	function displayGatheringData() {
		openDownloadRequestPopup();
		var downloadLaunchedHtml = '<div class="jabd-popup-msg"><span>' +
			jabd_downloader.gathering_data_msg + '</span><div class="spinner is-active"></div></div>';
		setPopupContents( downloadLaunchedHtml );
		popupClosable = false;
	}
	

	/*---------------------------------------------------------------------------------------------------------*/
	/*Download request popup*/

	// Create popup
	function openDownloadRequestPopup() {
		popupClosable = true;
		var popupHtml = '<div class="jabd-popup-overlay"></div><div class="jabd-popup-container"><div class="jabd-popup"></div></div>';
		$('body').append(popupHtml);
	}

	// Set popup contents
	function setPopupContents( popupContents ) {
		$('.jabd-popup').html( popupContents );
	}

	// Close the popup
	function closeDownloadRequestPopup() {
		$( '.jabd-popup-overlay, .jabd-popup-container' ).remove();
	}

	/*---------------------------------------------------------------------------------------------------------*/
	/*Process download request functions*/

	// Manage the download request
	function processDownloadRequest() {
		
		var attmtIds = [],
			downloadNonce = '';
			title = $( '.jabd-popup-msg input[type="text"]' ).val(),
			pword = $( '.jabd-popup-msg #zipfile-password' ).val(),
			intsizes = $( '#jabd-int-sizes-chkbox' ).prop( 'checked' ),
			nofolders = $( '#jabd-no-folder-chkbox' ).prop( 'checked' );
		if ( 'undefined' == typeof( pword) ) {
			pword = '';
		}

		// Set the attachment IDs.
		attmtIds = 'undefined' == typeof( wp.media.frame ) ? getAttmtIds() : getGridAttmtIds();

		if ( ! attmtIds.length ) {
			return false;
		}

		jQuery.ajax({
			url : ajaxurl,
			type : 'post',
			beforeSend: function() {
				downloadLaunchedMessage();
			},
			data : {
				action			: 'jabd_request_download',
				doaction		: 'download',
				downloadNonce	: jabd_downloader.download_nonce,
				attmtIds		: attmtIds,
				title			: title,
				pword			: pword,
				intsizes		: intsizes,
				nofolders		: nofolders
			},
			xhrFields: {
				withCredentials: true
			},
			success : function( response ) {
				requestResponse( response );
			}
		});

	}

	// Get the selected attachment ids from the checked checkboxes in list mode
	function getAttmtIds() {
		var attmtIds = [];
		$('#the-list .check-column input[type="checkbox"]').each( function() {
			if( $(this).prop('checked') ) {
				attmtIds.push( $(this).val() );
			}
		});
		return attmtIds;
	}

	// Get the selected attachment ids from the selected items in grid mode
	function getGridAttmtIds() {
		var attmtIds = [];
		$('.attachment.save-ready').each( function() {
			if( $(this).hasClass('selected') ) {
				attmtIds.push( $(this).data('id') );
			}
		});
		return attmtIds;
	}

	// Show message that download request has been initiated
	function downloadLaunchedMessage() {
		var downloadLaunchedHtml = '<div class="jabd-popup-msg"><span>' +
			jabd_downloader.download_launched_msg + '</span><div class="spinner is-active"></div></div>';
		setPopupContents( downloadLaunchedHtml );
		popupClosable = false;
	}


	// Show results - either link to download or error message
	function requestResponse( response ) {
		var result = JSON.parse(response);
		popupClosable = true;
		setPopupContents( result.messages );
		// Give focus to download title field if container is displaying at full height and not scrollable
		var div = $('.jabd-popup').get(0);
		if ( div.scrollHeight <= div.clientHeight ) {
			$('.jabd-popup-msg input[type="text"]').focus();
		}
	}
	
});