if (typeof TrustindexJsLoaded === 'undefined') {
	var TrustindexJsLoaded = {};
}

TrustindexJsLoaded.connect = true;

// autocomplete config
var TrustindexConnect = null;
jQuery(document).ready(function($) {
	/*************************************************************************/
	/* NO REG MODE */
	TrustindexConnect = {
		button: $('.ti-connect-platform .ti-btn'),
		form: $('#ti-connect-platform-form'),
		asyncRequest: function(callback, btn) {
			// get url params
			let params = new URLSearchParams({
				type: 'facebook',
				page_id: $('#ti-noreg-page-id').val().trim(),
				access_token: $('#ti-noreg-access-token').length ? $('#ti-noreg-access-token').val() : "",
				webhook_url: $('#ti-noreg-webhook-url').val(),
				email: $('#ti-noreg-email').val(),
				token: $('#ti-noreg-connect-token').val(),
				version: $('#ti-noreg-version').val()
			});

			// show popup info
			$('#ti-connect-info').removeClass('ti-d-none');

			// open window
			let tiWindow = window.open('https://admin.trustindex.io/source/wordpressPageRequest?' + params.toString(), 'trustindex', 'width=850,height=850,menubar=0' + popupCenter(850, 850));

			// popup close interval
			let noChangeBtnLoading = false;
			let timer = setInterval(function() {
				if (tiWindow.closed) {
					$('#ti-connect-info').addClass('ti-d-none');

					if (btn && !noChangeBtnLoading) {
						btn.removeClass('btn-loading');
					}

					clearInterval(timer);
				}
			}, 1000);

			// wait for process complete
			jQuery(window).one('message', function(event) {
				if (tiWindow == event.originalEvent.source && event.originalEvent.origin.startsWith('https://admin.trustindex.io/'.replace(/\/$/,''))) {
					tiWindow.close();

					let data = event.originalEvent.data || {};

					if (data.success) {
						noChangeBtnLoading = true;
						callback($('#ti-noreg-connect-token').val(), data.request_id, typeof data.manual_download != 'undefined' && data.manual_download ? 1 : 0, data.place || null);
					}
					else {
						$('#ti-connect-info').addClass();

						if (btn) {
							btn.removeClass('btn-loading');
						}

						// reset connect form, with invalid input message
						TrustindexConnect.form.find('.ti-selected-source').hide();
						TrustindexConnect.button.removeClass('btn-disabled');
					}
				}
			});
		}
	};

			$('.btn-connect-public').click(function(event) {
			event.preventDefault();

			let button = $(this);
			let token = $('#ti-noreg-connect-token').val();

			button.addClass('ti-btn-loading').blur();

			let dontRemoveLoading = false;

			// get url params
			let params = new URLSearchParams({
				type: 'Facebook',
				referrer: 'public',
				webhook_url: $('#ti-noreg-webhook-url').val(),
				token: token,
				version: $('#ti-noreg-version').val()
			});

			let tiWindow = window.open('https://admin.trustindex.io/source/edit2?' + params.toString(), 'trustindex', 'width=850,height=850,menubar=0' + popupCenter(850, 850));

			window.addEventListener('message', function(event) {
				if (event.origin.startsWith('https://admin.trustindex.io/'.replace(/\/$/,'')) && event.data.id) {
					dontRemoveLoading = true;

					tiWindow.close();
					$('#ti-connect-info').removeClass('ti-d-none');

					$('#ti-noreg-page-details').val(JSON.stringify(event.data));

					button.closest('form').submit();
				}
			});

			$('#ti-connect-info').removeClass('ti-d-none');
			let timer = setInterval(function() {
				if (tiWindow.closed) {
					$('#ti-connect-info').addClass('ti-d-none');

					if (!dontRemoveLoading) {
						button.removeClass('ti-btn-loading');
					}

					clearInterval(timer);
				}
			}, 1000);
		});

			// hide reply box on saving
		jQuery(document).on('click', '.ti-reply-box .state-replied .btn-hide-ai-reply', function(event) {
			event.preventDefault();

			let btn = jQuery(this);
			let td = btn.closest('td');

			td.find('.ti-reply-box, .btn-show-ai-reply').remove();
		});

	// make async request on review download
	$('.btn-download-reviews').on('click', function(event) {
		event.preventDefault();

		let btn = jQuery(this);

		TrustindexConnect.asyncRequest(function(token, request_id, manual_download, place) {
			if (place) {
				$.ajax({
					type: 'POST',
					data: {
						_wpnonce: btn.data('nonce'),
						page_details: JSON.stringify(place),
						review_download_timestamp: place.timestamp
					}
				}).always(() => location.reload());
			}
			else {
				$.ajax({
					type: 'POST',
					data: {
						_wpnonce: btn.data('nonce'),
						review_download_request: token,
						review_download_request_id: request_id,
						manual_download: manual_download
					}
				}).always(() => location.reload());
			}
		}, btn);
	});

	// manual download
	$('#ti-review-manual-download').on('click', function(event) {
		event.preventDefault();

		let btn = $(this);
		btn.addClass('ti-btn-loading').blur();

		$.ajax({
			url: location.search.replace(/&tab=[^&]+/, '&tab=free-widget-configurator'),
			type: 'POST',
			data: {
				command: 'review-manual-download',
				_wpnonce: btn.data('nonce')
			},
			success: () => location.reload(),
			error: function() {
				btn.removeClass('ti-btn-loading');

				btn.removeClass('ti-toggle-tooltip').addClass('ti-show-tooltip');
				setTimeout(() => btn.removeClass('ti-show-tooltip').addClass('ti-toggle-tooltip'), 3000);
			}
		});
	});
});