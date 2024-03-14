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
				type: 'airbnb',
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

	

			// check button clicked
		if (TrustindexConnect.button.length) {
			let isRegexValid = function(m) {
				if (!m) {
					return false;
				}

				for (let i = 0; i < m.length; i++) {
					if (m[i] === "") {
						return false;
					}
				}

				return true;
			};

			TrustindexConnect.button.click(function(event) {
				event.preventDefault();

				let input = $('.ti-connect-platform .ti-form-control');
				let regex = /airbnb\.[^\/]+\/(?:rooms|(experiences|luxury\/listing))\/(?:plus\/|listing\/)?([\d]+)/;

				TrustindexConnect.form.find('.ti-source-box').addClass('ti-d-none')
				$('#ti-connect-error').addClass('ti-d-none');

				if (!regex) {
					return false;
				}

				let m = regex.exec(input.val().trim());
				if (!isRegexValid(m)) {
					input.focus();

					return $('#ti-connect-error').removeClass('ti-d-none');
				}

				// support for 2 regexes
				let part1 = m[1] || m[3] || "";
				let part2 = m[2] || m[4] || "";

				let pageId = part1;
				if (part2) {
					if (part1) {
						pageId += '/';
					}

					pageId += part2;
				}

				let valid = true;
				if (TrustindexConnect.form.data('platform') === 'arukereso') {
					pageId = pageId.replace(/^com/, 'bg');
				}
				else if (TrustindexConnect.form.data('platform') === 'amazon') {
					valid = (
						!(
							pageId.search(/stores\/[^\/]+\/page/) > -1
							|| pageId.search(/stores\/page/) > -1
							|| pageId.search(/stores\/[^\/]+\/[^\/]+\/page/) > -1
							|| pageId.indexOf('account/') > -1
							|| (pageId.indexOf('gp/') > -1 && pageId.indexOf('gp/product/') === -1)
							|| pageId.search(/\-\/[^\/]{2}\/[^\/]{2}$/) > -1
						)
						&& pageId.indexOf('product-reviews/') === -1
						&& pageId.indexOf('/AccountInfo/') === -1
						&& pageId.indexOf('/SellerProfileView/') === -1
					);
				}
				else if (TrustindexConnect.form.data('platform') === 'tripadvisor') {
					// set source to first page
					let notFirstPage = pageId.match(/\-or[\d]+\-/);
					if (notFirstPage && notFirstPage[0]) {
						pageId = pageId.replace(notFirstPage[0], '-');
					}

					// add .html if not in pageId
					if (pageId.indexOf('.html') === -1) {
						pageId = pageId + '.html';
					}
				}

				// no pageId
				if (pageId.trim() === '' || !valid) {
					input.focus();

					return $('#ti-connect-error').removeClass('ti-d-none');
				}

				$('#ti-noreg-page-id').val(pageId);

				// show result
				let pageDetails = { id: pageId };
				let url = input.val().trim();

				let div = TrustindexConnect.form.find('.ti-source-box');
				TrustindexConnect.form.find('#ti-noreg-page-details').val(JSON.stringify(pageDetails));

				div.find('img').attr('src', 'https://cdn.trustindex.io/assets/platform/Airbnb/icon.png');
				div.find('.ti-source-info').html('<a target="_blank" href="'+ url +'">'+ url +'</a>');
				div.removeClass('ti-d-none');
			});
		}

		// show loading text on connect
		TrustindexConnect.form.find('.btn-connect').on('click', function(event) {
			event.preventDefault();

			// change button
			let btn = $(this);

			btn.addClass('btn-loading').blur();

			TrustindexConnect.button.css('pointer-events', 'none');

			// do request
			TrustindexConnect.asyncRequest(function(token, request_id, manual_download, place) {
				$('#ti-noreg-review-download').val(token);
				$('#ti-noreg-review-request-id').val(request_id);
				$('#ti-noreg-manual-download').val(manual_download);

				if (place) {
					$('#ti-noreg-page-details').val(JSON.stringify(place));
				}

				TrustindexConnect.form.submit();
			});
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