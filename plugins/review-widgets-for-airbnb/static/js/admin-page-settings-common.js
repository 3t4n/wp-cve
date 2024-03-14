if (typeof TrustindexJsLoaded === 'undefined') {
	var TrustindexJsLoaded = {};
}

TrustindexJsLoaded.common = true;

String.prototype.ucfirst = function() {
	return this.charAt(0).toUpperCase() + this.slice(1)
}

function popupCenter(w, h)
{
	let dleft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
	let dtop = window.screenTop !== undefined ? window.screenTop : window.screenY;

	let width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
	let height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

	let left = parseInt((width - w) / 2 + dleft);
	let top = parseInt((height - h) / 2 + dtop);

	return ',top=' + top + ',left=' + left;
}

jQuery.fn.expand = function() {
	let textarea = jQuery(this);
	let val = textarea.val();

	textarea.css('height', textarea.get(0).scrollHeight + 'px');
	textarea.val('').val(val);
};

jQuery(document).ready(function() {
	/*************************************************************************/
	/* PASSWORD TOGGLE */
	jQuery('.ti-toggle-password').on('click', function(event) {
		event.preventDefault();

		let icon = jQuery(this);
		let parent = icon.closest('.ti-form-group');

		if (icon.hasClass('dashicons-visibility')) {
			parent.find('input').attr('type', 'text');
			icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
		}
		else {
			parent.find('input').attr('type', 'password');
			icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
		}
	});

	// toggle opacity
	jQuery('.ti-toggle-opacity').css('opacity', 1);

	/*************************************************************************/
	/* TOGGLE */
	jQuery('#trustindex-plugin-settings-page .btn-toggle').on('click', function(event) {
		event.preventDefault();

		jQuery(jQuery(this).attr('href')).toggle();

		return false;
	});

	/*************************************************************************/
	/* FILTER */
	// checkbox
	jQuery('.ti-checkbox:not(.ti-disabled)').on('click', function() {
		let checkbox = jQuery(this).find('input[type=checkbox], input[type=radio]');
		checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');

		return false;
	});

	// custom select - init
	jQuery('.ti-select').each(function() {
		let el = jQuery(this);
		let selected = el.find('ul li.ti-selected');

		if (selected.length === 0) {
			selected = el.find('ul li:first');
		}

		el.data('value', selected.data('value')).find('font').html(selected.html());
	});

	// custom select - toggle click
	jQuery(document).on('click', '.ti-select', function() {
		let el = jQuery(this);
		el.toggleClass('ti-active');

		if (el.hasClass('ti-active')) {
			jQuery(window).unbind().on('click', function(event) {
				if (!jQuery(event.target).is(el) && jQuery(event.target).closest('.ti-select').length === 0) {
					el.removeClass('ti-active');
					jQuery(window).unbind();
				}
			});
		}
	});

	// custom select - select item
	jQuery(document).on('click', '.ti-select li', function() {
		let el = jQuery(this);

		el.parent().parent().data('value', el.data('value')).trigger('change').find('font').html(el.html());

		el.parent().find('li').removeClass('ti-selected');
		el.addClass('ti-selected');
	});

	var isNoReviewsWithFilter = false;

	// get reviews to memory
	var reviewsElement = jQuery('.ti-widget-editor-preview .ti-widget').clone();

	// set reviews' rating and empty to attributes
	reviewsElement.find('.ti-review-item').each(function() {
		let el = jQuery(this);
		let rating = el.find('.ti-stars .ti-star.f, .stars .ti-star.f').length;

		// facebook recommendations
		if (el.find('.ti-recommendation-icon.positive').length) {
			rating = 5;
		}
		else if (el.find('.ti-recommendation-icon.negative').length) {
			rating = 1;
		}

		if (el.find('.ti-polarity-icon.positive').length) {
			rating = 5;
		}
		else if (el.find('.ti-polarity-icon.neutral').length) {
			rating = 3;
		}
		else if (el.find('.ti-polarity-icon.negative').length) {
			rating = 1;
		}

		// ten scale
		if (el.find('.ti-rating-box').length) {
			rating = Math.round(parseFloat(el.find('.ti-rating-box').text()) / 2);
		}

		let selector = '.ti-review-content';
		if (el.find('.ti-review-content .ti-inner').length) {
			selector = '.ti-review-content .ti-inner';
		}
		else if (el.find('.ti-review-text').length) {
			selector = '.ti-review-text';
		}

		el.attr('data-rating', rating);

		if (typeof el.attr('data-empty') === 'undefined') {
			el.attr('data-empty', el.find(selector).text().trim() == "" ? 1 : 0);
		}
	});

	// check badge type
	var isBadgeWidget = function() {
		let layoutId = jQuery('.ti-widget-editor-preview .ti-widget').data('layout-id');

		return [ 11, 12, 20, 22, 24, 25, 26, 27, 28, 29, 35, 55, 56, 57, 58, 59, 60, 61, 62 ].indexOf(layoutId) != -1;
	};

	// apply filter when change or init
	var applyFilter = function(init) {
		let styleId = parseInt(jQuery('.ti-widget').data('layout-id'));

		// get stars
		let stars = (jQuery('#ti-filter-star').data('value') + "").split(',').map(function(i) { return parseInt(i); });

		// only ratings
		let showOnlyRatings = jQuery('#ti-filter-only-ratings').prop('checked');

		// filter removed
		if (!jQuery('#ti-filter-star').length) {
			stars = [ 1, 2, 3, 4, 5 ];
			showOnlyRatings = false;
		}

		// remove current review elements
		jQuery('.ti-widget .ti-reviews-container-wrapper .ti-review-item').remove();

		// remove all event listeners on the widget
		let widget = document.querySelector('.ti-widget');
		widget.replaceWith(widget.cloneNode(true));

		// iterate through stored reviews
		let results = 0;
		reviewsElement.find('.ti-review-item').each(function() {
			let el = jQuery(this);

			// check rating
			if (stars.indexOf(el.data('rating')) !== -1) {
				// check only ratings
				if (showOnlyRatings && el.data('empty')) {
					return;
				}

				// return after 5 results (vertical widgets)
				if ([ '8', '9', '10', '18', '33' ].indexOf(styleId) !== -1 && results > 4) {
					return;
				}

				// clone and append element
				let clone = el.clone();
				jQuery('.ti-widget-editor-preview .ti-widget .ti-reviews-container-wrapper').append(clone);
				clone.hide();
				clone.fadeIn();

				// increase count
				results++;
			}
		});

		// clear pager interval
		if (typeof Trustindex !== 'undefined' && Trustindex.intervalPointer) {
			clearInterval(Trustindex.intervalPointer);
		}

		// show empty text
		if (results === 0 && !isBadgeWidget()) {
			jQuery('.ti-widget-editor-preview .ti-widget').hide().next().fadeIn();
			isNoReviewsWithFilter = true;
		}
		else {
			jQuery('.ti-widget-editor-preview .ti-widget').fadeIn().next().hide();
			isNoReviewsWithFilter = false;

			if (typeof Trustindex !== 'undefined') {
				Trustindex.pager_inited = true;
				Trustindex.init_pager(document.querySelectorAll('.ti-widget'));
				Trustindex.resize_widgets();
			}
		}

		// ajax save
		if (init !== true) {
			jQuery.post('', {
				command: 'save-filter',
				_wpnonce: jQuery('#ti-filter-star').data('nonce'),
				filter: JSON.stringify({
					'stars': stars,
					'only-ratings': showOnlyRatings
				})
			});
		}
	}

	// hooks
	jQuery('#ti-filter-star').on('change', applyFilter);
	jQuery('#ti-filter-only-ratings').on('change', function(event) {
		event.preventDefault();

		applyFilter();

		return false;
	});

	// init
	if (reviewsElement.length) {
		applyFilter(true);
	}

	// background post save - style and set change
	jQuery('#ti-widget-selects select, #ti-widget-options input[type=checkbox]:not(.no-form-update)').on('change', function() {
		let form = jQuery(this).closest('form');
		let data = form.serializeArray();

		// include unchecked checkboxes
		form.find('input[type=checkbox]:not(.no-form-update)').each(function() {
			let checkbox = jQuery(this);

			if (!checkbox.prop('checked') && checkbox.attr('name')) {
				data.push({
					name: checkbox.attr('name'),
					value: 0
				});
			}
		});

		// show loading
		jQuery('#ti-loading').addClass('ti-active');

		jQuery('li.ti-preview-box').addClass('disabled');

		jQuery.ajax({
			url: form.attr('action'),
			type: 'post',
			dataType: 'application/json',
			data: data
		}).always(() => location.reload(true));

		return false;
	});

	// layout select filter
	jQuery('input[name=layout-select]').on('change', function(event) {
		event.preventDefault();

		let ids = (jQuery('input[name=layout-select]:checked').data('ids') + "").split(',');

		console.log('filter', ids);

		if (ids.length === 0 || ids[0] === "") {
			jQuery('.ti-preview-boxes-container').find('.ti-full-width, .ti-half-width').fadeIn();
		}
		else {
			jQuery('.ti-preview-boxes-container').find('.ti-full-width, .ti-half-width').hide();
			ids.forEach(id => jQuery('.ti-preview-boxes-container').find('.ti-preview-boxes[data-layout-id="'+ id + '"]').parent().fadeIn());
		}

		return false;
	});

	/*************************************************************************/
	/* MODAL */
	jQuery(document).on('click', '.btn-modal-close', function(event) {
		event.preventDefault();

		jQuery(this).closest('.ti-modal').fadeOut();
	});

	jQuery(document).on('click', '.ti-modal', function(event) {
		if (event.target.nodeName !== 'A') {
			event.preventDefault();

			if (!jQuery(event.target).closest('.ti-modal-dialog').length) {
				jQuery(this).fadeOut();
			}
		}
	});

	/*************************************************************************/
	/* NOTICE HIDE */
	jQuery(document).on('click', '.ti-notice.is-dismissible .notice-dismiss', function() {
		let button = jQuery(this);
		let container = button.closest('.ti-notice');

		container.fadeOut(200);

		if (button.data('command') && !button.data('ajax-run')) {
			button.data('ajax-run', 1); // prevent multiple triggers

			jQuery.post('', { command: button.data('command') });
		}
	});

	jQuery('.ti-checkbox input[type=checkbox][onchange]').on('change', function() {
		jQuery('#ti-loading').addClass('ti-active');
	});

	/*************************************************************************/
	/* DROPDOWN */

	// change dropdown arrow positions
	let fixDropdownArrows = function() {
		jQuery('.ti-button-dropdown-arrow').each(function() {
			let arrow = jQuery(this);
			let button = arrow.closest('td').find(arrow.data('button'));

			// add prev buttons' width
			let left = 0;
			button.prevAll('.ti-btn').each(function() {
				left += jQuery(this).outerWidth(true);
			});

			// center the arrow
			left += button.outerWidth() / 2;

			arrow.css('left', left + 'px');
		});
	};

	fixDropdownArrows();

	/*************************************************************************/
	/* AI REPLY */
	let generateAiReply = function(text, callback) {
		let tiWindow = window.open('', 'trustindex-generate-ai-reply', 'width=500,height=500,menubar=0' + popupCenter(500, 500));
		let form = document.createElement('form');
		let input = document.createElement('input');

		// create form to pass POST data
		form.target = 'trustindex-generate-ai-reply';
		form.method = 'POST';
		form.action = 'https://admin.trustindex.io/integration/generateAiReply';
		form.style.display = 'none';

		// data will be in a hidden input
		input.type = 'hidden';
		input.name = 'json';
		input.value = JSON.stringify({ text: text, language: jQuery('#ti-widget-language').val() });
		form.appendChild(input);

		// add form to body
		document.body.appendChild(form);

		if (tiWindow) {
			form.submit();
		}

		// remove added form
		form.remove();

		// popup close interval
		let timer = setInterval(function() {
			if (tiWindow.closed) {
				callback(false);
				clearInterval(timer);
			}
		}, 1000);

		// wait for response from Trustindex
		jQuery(window).one('message', function(event) {
			// event comes from the correct window
			if (tiWindow == event.originalEvent.source) {
				clearInterval(timer);
				callback(event.originalEvent.data.reply);

				tiWindow.close();
			}
		});
	};

	let postReply = function(data, reconnect, callback) {
		let tiWindow = window.open('', 'trustindex-post-reply', 'width=600,height=600,menubar=0' + popupCenter(600, 600));
		let form = document.createElement('form');
		let input = document.createElement('input');

		// create form to pass POST data
		form.target = 'trustindex-post-reply';
		form.method = 'POST';
		form.action = 'https://admin.trustindex.io/integration/postReply?type=airbnb';
		form.style.display = 'none';

		if (reconnect) {
			form.action += '&reconnect=1';
		}

		// data will be in a hidden input (JSON)
		input.type = 'hidden';
		input.name = 'json';
		input.value = JSON.stringify(data);
		form.appendChild(input);

		// add form to body
		document.body.appendChild(form);

		if (tiWindow) {
			form.submit();
		}

		// remove added form
		form.remove();

		// popup close interval
		let timer = setInterval(function() {
			if (tiWindow.closed) {
				callback(undefined);
				clearInterval(timer);
			}
		}, 1000);

		// wait for response from Trustindex
		jQuery(window).one('message', function(event) {
			// event comes from the correct window
			if (tiWindow == event.originalEvent.source) {
				clearInterval(timer);

				callback(!!event.originalEvent.data.success);

				tiWindow.close();
			}
		});
	};

	// show reply section
	//	- generate reply with AI if not edit
	jQuery(document).on('click', '.btn-show-ai-reply', function(event) {
		event.preventDefault();

		let btn = jQuery(this);
		let td = btn.closest('td');

		btn.addClass('ti-btn-loading').blur();

		let replyBox = td.find('.ti-reply-box');
		replyBox.find('.btn-post-reply').attr('data-reconnect', 0);
		replyBox.find('.ti-alert').addClass('ti-d-none');

		// generate reply with AI if not edit
		if (replyBox.attr('data-state') === 'reply' || replyBox.attr('data-state') === 'copy-reply') {
			let data = JSON.parse(replyBox.next().html());
			generateAiReply(data.review.text, function(reply) {
				btn.removeClass('ti-btn-loading');

				// popup closed
				if (reply === false) {
					return;
				}

				btn.addClass('ti-btn-default-disabled');
				replyBox.addClass('ti-active');

				td.find('.ti-highlight-box').removeClass('ti-active');
				td.find('.btn-show-highlight').removeClass('ti-btn-default-disabled');

				let textarea = replyBox.find('.state-'+ replyBox.attr('data-state') +' textarea');
				textarea.val(reply).focus().expand();

				if (!data.review.text.trim()) {
					replyBox.find('.ti-alert.ti-alert-empty-review').removeClass('d-none');
				}

				// save in DB
				jQuery.ajax({
					method: 'POST',
					url: window.location.href,
					data: { 'save-reply-generated': 1 }
				});
			});
		}
		else {
			btn.removeClass('ti-btn-loading').addClass('ti-btn-default-disabled');
			replyBox.addClass('ti-active');

			td.find('.ti-highlight-box').removeClass('ti-active');
			td.find('.btn-show-highlight').removeClass('ti-btn-default-disabled');
		}
	});

	// hide reply section
	jQuery(document).on('click', '.btn-hide-ai-reply', function(event) {
		event.preventDefault();

		let btn = jQuery(this);
		btn.blur();

		let replyBox = btn.closest('td').find('.ti-reply-box');
		replyBox.attr('data-state', replyBox.attr('data-original-state'));

		if (replyBox.attr('data-state') !== 'replied') {
			replyBox.removeClass('ti-active');
		}

		btn.closest('td').find('.btn-show-ai-reply').removeClass('ti-btn-default-disabled');
	});

	// show edit reply section
	jQuery(document).on('click', '.btn-show-edit-reply', function(event) {
		event.preventDefault();

		let btn = jQuery(this);
		let replyBox = btn.closest('td').find('.ti-reply-box');

		replyBox.attr('data-state', 'edit-reply');
		replyBox.find('.state-edit-reply textarea').focus().expand();
	});

	// hide edit reply section
	jQuery(document).on('click', '.btn-hide-edit-reply', function(event) {
		event.preventDefault();

		let btn = jQuery(this);
		let replyBox = btn.closest('td').find('.ti-reply-box');

		replyBox.find('.ti-alert').addClass('ti-d-none');
		replyBox.attr('data-state', 'replied');
	});

	// post reply
	jQuery(document).on('click', '.btn-post-reply', function(event) {
		event.preventDefault();

		let btn = jQuery(this);
		let replyBox = btn.closest('td').find('.ti-reply-box');
		let data = JSON.parse(replyBox.next().html());

		let textarea = btn.closest('.ti-reply-box-state').find('textarea');
		let reply = textarea.val().trim();

		textarea.removeClass('is-invalid');

		if (reply === "") {
			return textarea.addClass('is-invalid');
		}

		btn.addClass('ti-btn-loading').blur();

		data.reply = reply;

		postReply(data, btn.attr('data-reconnect') == 1, function(isSuccess) {
			btn.removeClass('ti-btn-loading');

			// popup closed
			if (isSuccess === undefined) {
				return;
			}

			if (isSuccess) {
				// save in DB
				jQuery.ajax({
					method: 'POST',
					url: window.location.href,
					data: {
						id: btn.attr('href'),
						_wpnonce: btn.data('nonce'),
						'save-reply': reply
					}
				});

				// show replied section
				replyBox.attr('data-state', 'replied').attr('data-original-state', 'replied');
				replyBox.find('.state-replied p').html(reply);
				replyBox.find('.state-edit-reply textarea').val(reply);
				replyBox.find('.state-replied .ti-alert').removeClass('ti-d-none');

				// change Reply with AI button text
				let replyButton = replyBox.closest('td').find('.btn-show-ai-reply:not(.btn-default)');
				if (replyButton.length) {
					replyButton.html(replyButton.data('edit-reply-text')).addClass('btn-default');
					setTimeout(fixDropdownArrows, 100);
				}
			}
			else {
				// set try again button state
				replyBox.find('.state-copy-reply .btn-try-reply-again').data('state', replyBox.attr('data-state'));

				// show copy section
				replyBox.attr('data-state', 'copy-reply');
				replyBox.find('.state-copy-reply textarea').val(reply).focus().expand();
				replyBox.find('.state-copy-reply .ti-alert').removeClass('ti-d-none');
			}
		});
	});

	/*************************************************************************/
	/* HIGHLIGHT */

	// show highlight section
	jQuery(document).on('click', '.btn-show-highlight', function(event) {
		event.preventDefault();

		let btn = jQuery(this);
		let td = btn.closest('td');
		let replyBox = td.find('.ti-reply-box');

		btn.addClass('ti-btn-default-disabled').blur();
		td.find('.ti-highlight-box').addClass('ti-active');

		replyBox.attr('data-state', replyBox.attr('data-original-state'));
		replyBox.removeClass('ti-active');

		td.find('.btn-show-ai-reply').removeClass('ti-btn-default-disabled');
	});

	// hide highlight section
	jQuery(document).on('click', '.btn-hide-highlight', function(event) {
		event.preventDefault();

		let btn = jQuery(this);
		let td = btn.closest('td');

		btn.blur();

		td.find('.ti-highlight-box').removeClass('ti-active');
		td.find('.btn-show-highlight').removeClass('ti-btn-default-disabled');
		td.find('.ti-reply-box[data-state="replied"]').addClass('ti-active');

		if (td.find('.ti-reply-box').attr('data-state') === 'replied') {
			td.find('.btn-show-ai-reply').addClass('ti-btn-default-disabled');
		}
	});

	// highlight save
	jQuery(document).on('click', '.btn-save-highlight', function(event) {
		event.preventDefault();

		let btn = jQuery(this);
		let highlightContent = btn.closest('td').find('.ti-highlight-content .ti-selection-content');
		let data = TI_highlight_getSelection(highlightContent.get(0));

		if (data.start !== null) {
			data.id = btn.attr('href');
			data._wpnonce = btn.data('nonce');
			data['save-highlight'] = 1;

			btn.addClass('ti-btn-loading').blur();
			btn.closest('td').find('.ti-btn').css('pointer-events', 'none');

			jQuery.ajax({
				method: 'POST',
				url: window.location.href,
				data: data
			}).always(() => location.reload(true));
		}
	});

	// highlight remove
	jQuery(document).on('click', '.btn-remove-highlight', function(event) {
		event.preventDefault();

		let btn = jQuery(this);

		btn.addClass('ti-btn-loading').blur();
		btn.closest('td').find('.ti-btn').css('pointer-events', 'none');

		jQuery.ajax({
			method: 'POST',
			url: window.location.href,
			data: {
				id: btn.attr('href'),
				_wpnonce: btn.data('nonce'),
				'save-highlight': 1
			}
		}).always(() => location.reload(true));
	});

	// review download notification email
	jQuery(document).on('click', '.btn-notification-email-save', function(event) {
		event.preventDefault();

		let container = jQuery(this).closest('.ti-notification-email');
		let input = container.find('input[type="text"]');
		let type = input.data('type');
		let nonce = input.data('nonce');
		let email = input.val().trim().toLowerCase();

		// hide alerts
		container.find('.ti-notice').hide();

		// check email
		if (email !== "" && !/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(email)) {
			return container.find('.ti-notice').fadeIn();
		}

		// show loading
		jQuery('#ti-loading').addClass('ti-active');

		// save email
		jQuery.post("", {
			'save-notification-email': email,
			'type': type,
			'_wpnonce': nonce
		}, () => location.reload(true));
	});
});


// - import/btn-loading.js
// loading on click
jQuery(document).on('click', '.ti-btn-loading-on-click', function() {
	let btn = jQuery(this);

	btn.addClass('ti-btn-loading').blur();
});

// - import/copy-to-clipboard.js
jQuery(document).on('click', '.btn-copy2clipboard', function(event) {
	event.preventDefault();

	let btn = jQuery(this);
	btn.blur();

	let obj = jQuery(btn.attr('href'));
	let text = obj.html() ? obj.html() : obj.val();

	// parse html
	let textArea = document.createElement('textarea');
	textArea.innerHTML = text;
	text = textArea.value;

	let feedback = () => {
		btn.removeClass('ti-toggle-tooltip').addClass('ti-show-tooltip');

		if (typeof this.timeout !== 'undefined') {
			clearTimeout(this.timeout);
		}

		this.timeout = setTimeout(() => btn.removeClass('ti-show-tooltip').addClass('ti-toggle-tooltip'), 3000);
	};

	if (!navigator.clipboard) {
		// fallback
		textArea = document.createElement('textarea');
		textArea.value = text;
		textArea.style.position = 'fixed'; // avoid scrolling to bottom
		document.body.appendChild(textArea);
		textArea.focus();
		textArea.select();

		try {
			var successful = document.execCommand('copy');

			feedback();
		}
		catch (err) { }

		document.body.removeChild(textArea);
		return;
	}

	navigator.clipboard.writeText(text).then(feedback);
});

// - import/feature-request.js
jQuery(document).on('click', '.btn-send-feature-request', function(event) {
	event.preventDefault();

	let btn = jQuery(this);
	btn.blur();

	let container = jQuery('.ti-feature-request');
	let email = container.find('input[name="email"]').val().trim();
	let text = container.find('textarea[name="description"]').val().trim();

	// hide errors
	container.find('.is-invalid').removeClass('is-invalid');

	// check email
	if (email === "" || !/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(email)) {
		container.find('input[name="email"]').addClass('is-invalid');
	}

	// check text
	if (text === "") {
		container.find('textarea[name="description"]').addClass('is-invalid');
	}

	// there is error
	if (container.find('.is-invalid').length) {
		return false;
	}

	// show loading animation
	btn.addClass('ti-btn-loading');

	let data = new FormData(jQuery('.ti-feature-request form').get(0));

	// ajax request
	jQuery.ajax({
		type: 'POST',
		data: data,
		cache: false,
		contentType: false,
		processData: false
	}).always(function() {
		btn.removeClass('ti-btn-loading');

		btn.addClass('ti-show-tooltip').removeClass('ti-toggle-tooltip');
		setTimeout(() => btn.removeClass('ti-show-tooltip').addClass('ti-toggle-tooltip'), 3000);
	});
});

// - import/rate-us.js
// remember on hover
jQuery(document).on('mouseenter', '.ti-quick-rating', function(event) {
	let container = jQuery(this);
	let selected = container.find('.ti-star-check.active');

	if (selected.length) {
		// add index to data & remove all active stars
		container.data('selected', selected.index()).find('.ti-star-check').removeClass('ti-active');

		// give back active star on mouse enter
		container.one('mouseleave', () => container.find('.ti-star-check').eq(container.data('selected')).addClass('ti-active'));
	}
});

// star click
jQuery(document).on('click', '.ti-rate-us-box .ti-quick-rating .ti-star-check', function(event) {
	event.preventDefault();

	let star = jQuery(this);
	let container = star.parent();

	// add index to data & remove all active stars
	container.data('selected', star.index()).find('.ti-star-check').removeClass('ti-active');

	// select current star
	star.addClass('ti-active');

	// show modals
	if (parseInt(star.data('value')) >= 4) {
		// open new window
		window.open(location.href + '&command=rate-us-feedback&_wpnonce='+ container.data('nonce') +'&star=' + star.data('value'), '_blank');

		jQuery('.ti-rate-us-box').fadeOut();
	}
	else {
		let feedbackModal = jQuery('#ti-rateus-modal-feedback');

		if (feedbackModal.data('bs') == '5') {
			feedbackModal.modal('show');
			setTimeout(() => feedbackModal.find('textarea').focus(), 500);
		}
		else {
			feedbackModal.fadeIn();
			feedbackModal.find('textarea').focus();
		}

		feedbackModal.find('.ti-quick-rating .ti-star-check').removeClass('ti-active').eq(star.index()).addClass('ti-active');
	}
});

// write to support
jQuery(document).on('click', '.btn-rateus-support', function(event) {
	event.preventDefault();

	let btn = jQuery(this);
	btn.blur();

	let container = jQuery('#ti-rateus-modal-feedback');
	let email = container.find('input[type=text]').val().trim();
	let text = container.find('textarea').val().trim();

	// hide errors
	container.find('input[type=text], textarea').removeClass('is-invalid');

	// check email
	if (email === "" || !/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(email)) {
		container.find('input[type=text]').addClass('is-invalid').focus();
	}

	// check text
	if (text === "") {
		container.find('textarea').addClass('is-invalid').focus();
	}

	// there is error
	if (container.find('.is-invalid').length) {
		return false;
	}

	// show loading animation
	btn.addClass('ti-btn-loading');
	container.find('a, button').css('pointer-events', 'none');

	// ajax request
	jQuery.ajax({
		type: 'post',
		dataType: 'application/json',
		data: {
			command: 'rate-us-feedback',
			_wpnonce: btn.data('nonce'),
			email: email,
			text: text,
			star: container.find('.ti-quick-rating .ti-star-check.ti-active').data('value')
		}
	}).always(() => location.reload(true));
});