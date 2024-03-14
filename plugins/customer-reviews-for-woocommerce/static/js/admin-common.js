if(typeof Trustindex_JS_loaded == 'undefined')
{
	var Trustindex_JS_loaded = {};
}

Trustindex_JS_loaded.common = true;

String.prototype.ucfirst = function() {
	return this.charAt(0).toUpperCase() + this.slice(1)
}

jQuery(document).ready(function() {
	/*************************************************************************/
	/* PASSWORD TOGGLE */
	jQuery(".ti-toggle-password").on('click', function(event) {
		event.preventDefault();

		let icon = jQuery(this);
		let parent = icon.closest('.form-group, .ti-input-field');

		if(icon.hasClass('dashicons-visibility'))
		{
			parent.find('input').attr('type', 'text');
			icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
		}
		else
		{
			parent.find('input').attr('type', 'password');
			icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
		}
	});

	// nav padding-right
	let nav = jQuery('#trustindex-plugin-settings-page .nav-tab-wrapper');
	if(nav.length)
	{
		let width = nav.find('.nav-tab-right').outerWidth();
		nav.css('padding-right', parseInt(width + 5) + 'px');
	}

	// toggle opacity
	jQuery('.ti-toggle-opacity').css('opacity', 1);

	/*************************************************************************/
	/* TOGGLE */
	jQuery("#trustindex-plugin-settings-page .btn-toggle").on('click', function(e) {
		e.preventDefault();

		jQuery(jQuery(this).attr("href")).toggle();

		return false;
	});

	/*************************************************************************/
	/* COPY 2 CLIPBOARD */
	jQuery(".btn-copy2clipboard").click(function(event) {
		event.preventDefault();

		let button = jQuery(this);
		let obj = jQuery(button.attr("href"));
		let text = obj.html() ? obj.html() : obj.val();

		TI_copyTextToClipboard(text, () => {
			button.addClass('show-tooltip');

			if(typeof this.timeout != 'undefined')
			{
				clearTimeout(this.timeout);
			}

			this.timeout = setTimeout(() => button.removeClass('show-tooltip'), 3000);
		});
	});

	/*************************************************************************/
	/* STYLE */
	var apply_style = function() {
		let style_id = jQuery('#ti-style-id').val();
		let box = jQuery('#ti-review-list').closest('.ti-preview-box');

		if(['8', '9', '10', '11', '12', '20', '22'].indexOf(style_id) != -1 && !is_no_reviews_with_filter)
		{
			box.css('width', '30%');
		}
		else if(['6', '7', '24', '25', '26', '27', '28', '29', '35'].indexOf(style_id) != -1 && !is_no_reviews_with_filter)
		{
			box.css('width', '50%');
		}
		else
		{
			box.css('width', 'auto');
		}

		//This is necessary to round up x.5 px width
		box.css('width', box.width());
	};

	/*************************************************************************/
	/* FILTER */
	//Checkbox
	jQuery('.ti-checkbox:not(.disabled)').on('click', function() {
		let checkbox = jQuery(this).find('input[type=checkbox], input[type=radio]');
		checkbox.prop('checked', !checkbox.prop('checked')).trigger("change");

		return false;
	});

	//Custom select - init
	jQuery('.ti-select').each(function() {
		let el = jQuery(this);
		let selected = el.find('ul li.selected');

		if(selected.length == 0)
		{
			selected = el.find('ul li:first');
		}

		el.data('value', selected.data('value')).find('font').html(selected.html());
	});

	//Custom select - toggle click
	jQuery(document).on('click', '.ti-select', function() {
		let el = jQuery(this);
		el.toggleClass('active');

		if(el.hasClass('active'))
		{
			jQuery(window).unbind().on('click', function(e) {
				if(!jQuery(e.target).is(el) && jQuery(e.target).closest('.ti-select').length == 0)
				{
					el.removeClass('active');
					jQuery(window).unbind();
				}
			});
		}
	});

	//Custom select - select item
	jQuery(document).on('click', '.ti-select li', function() {
		let el = jQuery(this);
		el.parent().parent().data('value', el.data('value')).trigger('change').find('font').html(el.html());

		el.parent().find('li').removeClass('selected');
		el.addClass('selected');
	});

	var is_no_reviews_with_filter = false;

	//Get reviews to memory
	var reviews_el = jQuery('#ti-review-list .ti-widget').clone();

	//Set reviews' rating and empty to attributes
	reviews_el.find('.ti-review-item').each(function() {
		let el = jQuery(this);
		let rating = el.find('.ti-stars .ti-star.f, .stars .ti-star.f').length;

		//Facebook recommendations
		if(el.find('.ti-recommendation-icon.positive').length)
		{
			rating = 5;
		}
		else if(el.find('.ti-recommendation-icon.negative').length)
		{
			rating = 1;
		}

		if(el.find('.ti-polarity-icon.positive').length)
		{
			rating = 5;
		}
		else if(el.find('.ti-polarity-icon.neutral').length)
		{
			rating = 3;
		}
		else if(el.find('.ti-polarity-icon.negative').length)
		{
			rating = 1;
		}

		//Ten scale
		if(el.find('.ti-rating-box').length)
		{
			rating = Math.round(parseFloat(el.find('.ti-rating-box').text()) / 2);
		}

		let selector = '.ti-review-content';
		if(el.find('.ti-review-content .ti-inner').length)
		{
			selector = '.ti-review-content .ti-inner';
		}
		else if(el.find('.ti-review-text').length)
		{
			selector = '.ti-review-text';
		}

		el.attr('data-rating', rating);

		if(typeof el.attr('data-empty') == 'undefined')
		{
			el.attr('data-empty', el.find(selector).text().trim() == "" ? 1 : 0);
		}
	});

	//Set the stars background in the filter for the corresponding platform
	var set_star_background = function() {
		let platform = (jQuery('#ti-filter #show-star').data('platform') || 'google').ucfirst();

		let el = jQuery('<div class="ti-widget" style="display: none"><div class="source-'+ platform +'"><span class="ti-star f"></span><span class="ti-star e"></span></div></div>');
		el.append('body');

		jQuery('body').append(el);
		jQuery('#ti-filter .ti-star.e').css('background', el.find('.ti-star.e').css('background'));
		jQuery('#ti-filter .ti-star.f').css('background', el.find('.ti-star.f').css('background'));

		el.remove();
	};
	set_star_background();

	//Check badge type
	var is_badge_widget = function() {
		let layout_id = jQuery('#ti-review-list .ti-widget').data('layout-id');

		return [11, 12, 20, 22, 24, 25, 26, 27, 28, 29, 35, 55, 56, 57, 58, 59, 60, 61, 62].indexOf(layout_id) != -1;
	};

	//Apply filter when change or init
	var apply_filter = function(init) {
		let style_id = jQuery('#ti-style-id').val();

		//get stars
		let stars = (jQuery('#ti-filter #show-star').data('value') + "").split(',').map(function(i) { return parseInt(i); });

		//only ratings
		let show_only_ratings = jQuery('#ti-filter-only-ratings').prop('checked');

		//filter removed
		if(!jQuery('#ti-filter').length)
		{
			stars = [ 1, 2, 3, 4, 5 ];
			show_only_ratings = false;
		}

		//remove current review elements
		jQuery('.ti-widget .ti-reviews-container-wrapper .ti-review-item').remove();

		//remove all event listeners on the widget
		let widget = document.querySelector(".ti-widget");
		widget.replaceWith(widget.cloneNode(true));

		//iterate through stored reviews
		let results = 0;
		reviews_el.find('.ti-review-item').each(function() {
			let el = jQuery(this);

			//check rating
			if(stars.indexOf(el.data('rating')) !== -1)
			{
				//check only ratings
				if(show_only_ratings && el.data('empty'))
				{
					return;
				}

				//return after 5 results (vertical widgets)
				if(['8', '9', '10', '18', '33'].indexOf(style_id) != -1 && results > 4)
				{
					return;
				}

				//clone and append element
				let clone = el.clone();
				jQuery('#ti-review-list .ti-widget .ti-reviews-container-wrapper').append(clone);
				clone.hide();
				clone.fadeIn();

				//increase count
				results++;
			}
		});

		//clear pager interval
		if(typeof Trustindex != "undefined" && Trustindex.intervalPointer)
		{
			clearInterval(Trustindex.intervalPointer);
		}

		//show empty text
		if(results == 0 && !is_badge_widget())
		{
			jQuery('#ti-review-list').hide().next().fadeIn();
			is_no_reviews_with_filter = true;
		}
		else
		{
			jQuery('#ti-review-list').fadeIn().next().hide();
			is_no_reviews_with_filter = false;

			//start pager
			if(init === undefined)
			{
				let dot_container = jQuery('#ti-review-list .ti-widget .ti-controls-dots');
				if(dot_container.length)
				{
					let dot = dot_container.children(":first").clone();
					if(dot.length)
					{
						dot_container.html(" " + dot.removeAttr('data-pager-state')[0].outerHTML + " ");
					}
				}
			}

			if(typeof Trustindex != "undefined")
			{
				Trustindex.pager_inited = true;
				Trustindex.init_pager(document.querySelectorAll(".ti-widget"));
				Trustindex.resize_widgets();
			}
		}

		//ajax save
		if(init !== true)
		{
			jQuery.post('', {
				command: 'save-filter',
				filter: JSON.stringify({
					'stars': stars,
					'only-ratings': show_only_ratings
				})
			});
		}

		apply_style();
	}

	//hooks
	jQuery('#ti-filter #show-star').on('change', apply_filter);
	jQuery('#ti-filter-only-ratings').on('change', function(e) {
		e.preventDefault();
		apply_filter();
		return false;
	});

	//init
	if(reviews_el.length)
	{
		apply_filter(true);
		apply_style();
	}

	//Background post save - style and set change
	jQuery("#ti-style-id, #ti-set-id, #ti-lang-id, #ti-dateformat-id, #ti-widget-options input[type=checkbox]:not(.no-form-update), #ti-align-id, #ti-review-text-mode-id").on('change', function() {
		let form = jQuery(this).closest('form');

		let data = form.serializeArray();

		// include unchecked checkboxes
		form.find('input[type=checkbox]:not(.no-form-update)').each(function() {
			let checkbox = jQuery(this);

			if(!checkbox.prop('checked') && checkbox.attr('name'))
			{
				data.push({
					name: checkbox.attr('name'),
					value: 0
				});
			}
		});

		// show loading
		jQuery('#ti-loading').addClass('active');

		jQuery('li.ti-preview-box').addClass('disabled');
		jQuery.ajax({
			url: form.attr('action'),
			type: 'post',
			dataType: 'application/json',
			data: data
		}).always(function() {
			location.reload(true);
		});

		return false;
	});

	//Layout select filter
	jQuery('input[name=layout-select]').on('change', function(e) {
		e.preventDefault();

		let ids = (jQuery('input[name=layout-select]:checked').data('ids') + "").split(',');

		if(ids == "")
		{
			jQuery('.ti-preview-boxes-container').find('.ti-full-width, .ti-half-width').fadeIn();
		}
		else
		{
			jQuery('.ti-preview-boxes-container').find('.ti-full-width, .ti-half-width').hide();
			ids.forEach(function(id) {
				jQuery('.ti-preview-boxes-container').find('.ti-preview-boxes[data-layout-id="'+ id + '"]').parent().fadeIn();
			});
		}

		return false;
	});

	//Free step stepping
	let is_stepping = false;
	jQuery('.ti-free-steps li.done, .ti-free-steps li.active').on('click', function(e) {
		e.preventDefault();

		if(is_stepping)
		{
			return false;
		}

		is_stepping = true;
		window.location.href = jQuery(this).attr('href');

		return false;
	});

	//Set auto active if not present
	if(jQuery('.ti-free-steps:not(.ti-setup-guide-steps) li.current').length == 0)
	{
		jQuery('.ti-free-steps:not(.ti-setup-guide-steps) li.active:last').addClass('current');
	}

	/*************************************************************************/
	/* MODAL */
	jQuery(document).on('click', '.btn-modal-close', function(event) {
		event.preventDefault();

		jQuery(this).closest('.ti-modal').fadeOut();
	});

	jQuery(document).on('click', '.ti-modal', function(event) {
		if(event.target.nodeName != 'A')
		{
			event.preventDefault();

			if(!jQuery(event.target).closest('.ti-modal-dialog').length)
			{
				jQuery(this).fadeOut();
			}
		}
	});

	/*************************************************************************/
	/* HIGHLIGHT */
	let highlight_modal = jQuery('#ti-highlight-modal');
	if(highlight_modal.length)
	{
		let appendHiddenInputs = function(obj) {
			highlight_modal.find('input[type=hidden]').each(function() {
				let input = jQuery(this);

				obj[ input.attr('name') ] = input.val();
			});

			return obj;
		};
		//show highlight modal
		jQuery(document).on('click', '.btn-highlight', function(event) {
			event.preventDefault();

			let btn = jQuery(this);
			let review_box = btn.closest('tr').find('.ti-review-content');
			let raw_content = review_box.html();
			let content = raw_content.replace(/<mark class="ti-highlight">/g, '').replace(/<\/mark>/, ''); // remove current highlight tags

			highlight_modal.fadeIn();
			highlight_modal.find('.ti-highlight-content').html("<div class='raw-content'>"+ raw_content + "</div><div class='selection-content'>"+ content + "</div>");
			highlight_modal.find('.btn-highlight-confirm, .btn-highlight-remove').attr('href', btn.attr('href'));

			// toggle highlight remove button
			if(btn.hasClass('has-highlight'))
			{
				highlight_modal.find('.btn-highlight-remove').show();
			}
			else
			{
				highlight_modal.find('.btn-highlight-remove').hide();
			}
		});

		// highlight save
		jQuery(document).on('click', '.btn-highlight-confirm', function(event) {
			event.preventDefault();

			let btn = jQuery(this);
			let highlight_content = btn.closest('.ti-modal-content').find('.ti-highlight-content .selection-content');
			let data = TI_highlight_getSelection(highlight_content.get(0));

			if(data.start !== null) {
				data.id = btn.attr('href');
				data['save-highlight'] = 1;

				btn.css('pointer-events', 'none');
				btn.blur();
				btn.addClass('btn-disabled');
				TI_manage_dots(btn);
				btn.closest('.ti-modal').find('.btn-text').css('pointer-events', 'none');

				jQuery.ajax({
					method: "POST",
					url: window.location.href,
					data: appendHiddenInputs(data)
				}).always(function() {
					location.reload(true);
				});
			}
		});

		// highlight remove
		jQuery(document).on('click', '.btn-highlight-remove', function(event) {
			event.preventDefault();

			let btn = jQuery(this);
			let highlight_content = btn.closest('.ti-modal-content').find('.ti-highlight-content');
			let data = TI_highlight_getSelection(highlight_content.get(0));

			btn.css('pointer-events', 'none');
			btn.blur();
			btn.addClass('btn-disabled');
			TI_manage_dots(btn);
			btn.closest('.ti-modal').find('.btn-text').css('pointer-events', 'none');

			jQuery.ajax({
				method: "POST",
				url: window.location.href,
				data: appendHiddenInputs({
					id: btn.attr('href'),
					"save-highlight": 1
				})
			}).always(function() {
				location.reload(true);
			});
		});
	}

	/*************************************************************************/
	/* NOTICE HIDE */
	jQuery(document).on('click', '.ti-notice.is-dismissible .notice-dismiss', function() {
		let button = jQuery(this);
		let container = button.closest('.ti-notice');

		container.fadeOut(200);

		if(button.data('command') && !button.data('ajax-run'))
		{
			button.data('ajax-run', 1); // prevent multiple triggers

			jQuery.post('', { command: button.data('command') });
		}
	});

	jQuery('.ti-checkbox input[type=checkbox][onchange]').on('change', function() {
		jQuery('#ti-loading').addClass('active');
	});

	/*************************************************************************/
	/* RATE US */
	// remember on hover
	jQuery('.ti-rate-us-box .ti-quick-rating').on('mouseenter', function(event) {
		let container = jQuery(this);
		let selected = container.find('.ti-star-check.active');

		if(selected.length)
		{
			// add index to data & remove all active stars
			container.data('selected', selected.index()).find('.ti-star-check').removeClass('active');

			// give back active star on mouse enter
			container.one('mouseleave', () => container.find('.ti-star-check').eq(container.data('selected')).addClass('active'));
		}
	});

	// star click
	jQuery(document).on('click', '.ti-rate-us-box .ti-quick-rating .ti-star-check', function(event) {
		event.preventDefault();

		let star = jQuery(this);
		let container = star.parent();

		// add index to data & remove all active stars
		container.data('selected', star.index()).find('.ti-star-check').removeClass('active');

		// select current star
		star.addClass('active');

		// show modals
		if(parseInt(star.data('value')) >= 4)
		{
			// open new window
			window.open(location.href + '&command=rate-us-feedback&star=' + star.data('value'), '_blank');

			jQuery('.ti-rate-us-box').fadeOut();
		}
		else
		{
			let feedback_modal = jQuery('#ti-rateus-modal-feedback');

			feedback_modal.fadeIn();
			feedback_modal.find('.ti-quick-rating .ti-star-check').removeClass('active').eq(star.index()).addClass('active');
			feedback_modal.find('textarea').focus();
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
		container.find('input[type=text], textarea').removeClass('has-error');

		// check email
		if(email == "" || !/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(email))
		{
			container.find('input[type=text]').addClass('has-error');
		}

		// check text
		if(text == "")
		{
			container.find('textarea').addClass('has-error');
		}

		// there is error
		if(container.find('.has-error').length)
		{
			return false;
		}

		// show loading animation
		btn.css('pointer-events', 'none').addClass('btn-disabled');
		TI_manage_dots(btn);
		btn.closest('.ti-modal').find('.btn-text').css('pointer-events', 'none');

		// ajax request
		jQuery.ajax({
			type: 'post',
			dataType: 'application/json',
			data: {
				command: 'rate-us-feedback',
				email: email,
				text: text,
				star: container.find('.ti-quick-rating .ti-star-check.active').data('value')
			}
		}).always(function() {
			location.reload(true);
		});
	});
});

//btn: JQuery Element
function TI_manage_dots(btn)
{
	let old_text = btn.html();
	let loading_text = btn.data('loading-text');
	let dots = [ '.', '..', '...' ];
	let index = dots.length - 1;

	btn.data('old', old_text);
	btn.html(loading_text + dots[index]);

	btn.animationInterval = setInterval(function() {
		index++;

		if(index >= dots.length)
		{
			index = 0;
		}

		btn.html(loading_text + dots[index]);
	}, 1000);

	btn.restore = function() {
		btn.html(old_text);

		clearInterval(btn.animationInterval);
	};
}

function decodeHTMLEntities(text)
{
	let textArea = document.createElement('textarea');

	textArea.innerHTML = text;

	return textArea.value;
}

function TI_copyTextToClipboard(text, callback)
{
	text = decodeHTMLEntities(text);

	if (!navigator.clipboard)
	{
		//fallback
		var textArea = document.createElement("textarea");
		textArea.value = text;
		textArea.style.position="fixed";  //avoid scrolling to bottom
		document.body.appendChild(textArea);
		textArea.focus();
		textArea.select();

		try {
			var successful = document.execCommand('copy');

			if(callback)
			{
				callback();
			}
			//console.log('Fallback: Copying text command was ' + msg);
		} catch (err) {
			//console.error('Fallback: Oops, unable to copy', err);
		}

		document.body.removeChild(textArea);
		return;
	}
	navigator.clipboard.writeText(text).then(
		function() {
			if(callback)
			{
				callback();
			}
		},
		function(err) {/*console.error('Async: Could not copy text: ', err);*/}
	);
}