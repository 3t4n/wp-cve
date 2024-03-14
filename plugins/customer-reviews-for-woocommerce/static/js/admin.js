jQuery.fn.showButtonLoading = function() {
	jQuery(this).each(function() {
		let btn = jQuery(this);

		if(btn.hasClass('btn-disabled'))
		{
			return;
		}

		btn.css('pointer-events', 'none');
		btn.addClass('btn-disabled');
		btn.blur();
		btn.data('old-html', btn.html());

		if(btn.data('loading-text'))
		{
			TI_manage_dots(btn);
		}
	});
};

jQuery.fn.hideButtonLoading = function() {
	jQuery(this).each(function() {
		let btn = jQuery(this);

		// change btn
		let btn2 = btn.clone();
		btn2.removeClass('btn-disabled');
		btn2.css('pointer-events', '');
		btn2.html(btn.data('old-html'));

		btn.replaceWith(btn2);
	});
};

String.prototype.validateEmail = function() {
	return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(this);
};

String.prototype.htmlDecode = function() {
	var el = document.createElement('textarea');

	el.innerHTML = this;

	return el.childNodes.length === 0 ? "" : el.childNodes[0].nodeValue;
};

var Trustindex_WooCommerce_JS_loaded = true;
jQuery(document).ready(function() {
	/*************************************************************************/
	/* FORM submit button click */
	jQuery("#trustindex-woocommerce-admin a.btn-submit").on('click', function(event) {
		event.preventDefault();

		let btn = jQuery(this);

		btn.showButtonLoading();

		let form = btn.closest('form');
		if(form.length)
		{
			form.submit();
		}
		else
		{
			let href = btn.attr('href');
			if(href && href != '#')
			{
				window.location.href = href;
			}
		}

		return false;
	});

	/*************************************************************************/
	/* TEST E-MAIL */
	let test_email_modal = jQuery('#ti-test-email-modal');
	if(test_email_modal.length)
	{
		let appendHiddenInputs = function(obj) {
			test_email_modal.find('input[type=hidden]').each(function() {
				let input = jQuery(this);

				obj[ input.attr('name') ] = input.val();
			});

			return obj;
		};

		let data = null;
		let email_sent_notification = jQuery('#ti-test-email-sent');

		//show modal
		jQuery(document).on('click', '.btn-test-email', function(event) {
			event.preventDefault();

			// get data
			data = {
				subject: jQuery('.ti-campaign-setup input[name=campaign-subject]').val().trim(),
				text: ""
			};

			// get text
			if(jQuery('#wp-campaign-text-wrap').hasClass('tmce-active'))
			{
				data.text = tinyMCE.get('campaign-text').getContent();
			}
			else
			{
				data.text = jQuery('#campaign-text').val();
			}

			// add template html to data
			if(typeof test_email_modal.data('html') == "undefined")
			{
				test_email_modal.data('html', test_email_modal.html());
			}

			// create text
			let text = data.text
												.replace('{unsubscribe_url}', '#')
												.replace('{link}', jQuery('#ti-email-link-url').val());

			// apply data to modal
			let html = test_email_modal.data('html')
																		.replace('%subject%', data.subject)
																		.replace('%email_text%', text);
			test_email_modal.html(html)

			// show modal
			jQuery(this).blur();
			test_email_modal.fadeIn();

			jQuery('#ti-email-address').focus();
		});

		// send
		jQuery(document).on('click', '.btn-send-test-email', function(event) {
			event.preventDefault();

			if(!data)
			{
				return false;
			}

			let btn = jQuery(this);
			let email = jQuery('#ti-email-address').val().trim();

			if(email != "") {
				btn.showButtonLoading();

				data.email = email;

				jQuery.ajax({
					method: "POST",
					url: window.location.href,
					data: appendHiddenInputs(data)
				}).always(function() {
					// show email sent
					email_sent_notification.html(email_sent_notification.data('html').replace('%email%', data.email)).fadeIn();

					// close modal
					test_email_modal.find('.btn-modal-close').click();

					// hide notification after few seconds
					setTimeout(function() {
						email_sent_notification.fadeOut();
					}, 5000);
				});
			}
		});
	}

	/*************************************************************************/
	/* PREVIOUS ORDER INVITES */
	jQuery(document).on('click', "#trustindex-woocommerce-admin a.btn-invite-previous", function(event) {
		event.preventDefault();

		// hide notification
		jQuery('#ti-woocommerce-select-notification').addClass('hidden');

		// get selected ids
		let ids = [];
		jQuery('tr:not(.sent) .ti-order-ids').each(function() {
			let input = jQuery(this);
			if(input.prop('checked'))
			{
				ids.push(parseInt(input.val()));
			}
		});

		if(ids.length == 0)
		{
			jQuery('#ti-woocommerce-select-notification').removeClass('hidden');

			return false;
		}

		// change btn
		let btn = jQuery(this);
		btn.showButtonLoading();

		// post request
		jQuery.ajax({
			method: "POST",
			url: window.location.href,
			data: {
				command: 'previous-invite',
				_wpnonce: jQuery('#_wpnonce').val(),
				ids: ids
			}
		}).always(function() {
			// show sent notification
			jQuery('#ti-woocommerce-sent-notification').fadeIn();

			// change btn
			btn.hideButtonLoading();

			// disable orders from table
			let orderTable = jQuery('#ti-woocommerce-orders');
			ids.forEach(function(id) {
				orderTable.find('tbody tr[data-id='+ id +']').addClass('sent');
			});
		});

		return false;
	});

	// pagination
	jQuery('#ti-woocommerce-page-selector').on('change', function(event) {
		event.preventDefault();

		let input = jQuery(this);
		let page = parseInt(input.val().trim());
		let max = parseInt(input.attr('max'));

		if(isNaN(page) || page < 1 || page > max)
		{
			return false;
		}

		window.location.href = input.attr('href').replace('%d', page);
	});

	// search
	let searchOrderPage = function(event) {
		event.preventDefault();

		let input = jQuery('#ti-woocommerce-order-search');
		let query = input.val().trim();

		window.location.href = input.attr('href').replace('%s', query);
	};
	jQuery('#ti-woocommerce-order-search').on('change', searchOrderPage).next().on('click', searchOrderPage);

	// select all
	jQuery('#ti-woocommerce-select-all').on('change', function(event) {
		event.preventDefault();

		jQuery('#ti-woocommerce-orders tbody tr:not(.sent) .ti-checkbox input[type=checkbox]').prop('checked', jQuery(this).prop('checked'));
	});

	/*************************************************************************/
	/* SETUP TRUSTINDEX */
	// Switch tab function
	let switchTab = function(tab_name, show_notice) {
		let tab = jQuery(tab_name);

		// switch tab
		jQuery('.ti-connect-tab').hide();
		tab.fadeIn();

		// hide all notification
		jQuery('.ti-connect-tab .hidden').hide();

		// show notification
		if(typeof show_notice != "undefined" && show_notice)
		{
			tab.find('.ti-notice.' + show_notice).fadeIn();
		}
	};

	// Get ajax object for register AJAX
	let getRegisterData = function() {
		let box = jQuery('#ti-domain-check');

		return {
			pre_profile: {
				website: box.find('input.field-domain').val().trim().toLowerCase().replace(/^https?:\/\//, ''),
				email: box.find('input[type=email]').val().trim(),
				language: box.find('select[name=language]').val(),
				agent_id: 'sys',
				campaign_id: 'wp-woo-reg'
			},
			name: box.find('input[name=name]').val().trim(),
			description: box.find('textarea[name=description]').val().trim(),
			phone: box.find('input[name=phone]').val().trim(),
		};
	};

	// Do register AJAX
	let doRegister = function(btn, data) {
		// get data if not provided
		if(typeof data == "undefined")
		{
			data = getRegisterData();
		}

		// get box
		let box = jQuery('#ti-domain-check');

		// hide errors
		box.find('.ti-notice.hidden').hide();

		// check inputs
		if(data.name == "" || data.description == "" || data.pre_profile.website == "" || data.pre_profile.email == "")
		{
			box.find('.ti-notice.notice-empty').fadeIn();

			return false;
		}

		// change btn
		btn.showButtonLoading();

		// check email exists
		jQuery.ajax({
			method: "POST",
			url: "https://admin.trustindex.io/" + "api/userCheckEmail",
			data: {
				s: 'wp',
				email: data.pre_profile.email
			},
			dataType: "jsonp"
		}).always(function(exists) {
			// exists
			if(exists == 1)
			{
				// show error
				box.find('.ti-notice.notice-exists').fadeIn();

				// change btn
				btn.hideButtonLoading();

				return false;
			}

			// post request
			jQuery.ajax({
				method: "POST",
				url: "https://admin.trustindex.io/" + "api/userRegister",
				data: data,
				dataType: "jsonp"
			}).always(function(r) {
				// successful registration
				if(typeof r.success != "undefined" && r.success)
				{
					// save subscription_id
					jQuery('#ti-setup-trustindex input[name=subscription_id]').val(r.subscription_id);

					// save source-id
					jQuery('#ti-setup-trustindex input[name=source_id]').val(r.source_id);

					// submit form
					jQuery('#ti-setup-trustindex').submit();
				}
				else
				{
					// show error
					box.find('.ti-notice.notice-invalid-email').fadeIn();

					// change btn
					btn.hideButtonLoading();
				}
			});
		});
	};

	// Domain check
	jQuery(document).on('click', "#trustindex-woocommerce-admin a.btn-check-domain", function(event) {
		event.preventDefault();

		// get login div
		let box = jQuery('#ti-domain-check');

		// hide errors
		box.find('.ti-notice.hidden').hide();

		// get data for ajax
		let data = getRegisterData();

		// check inputs
		if(data.name == "" || data.description == "" || data.pre_profile.website == "" || data.pre_profile.email == "")
		{
			box.find('.ti-notice.notice-empty').fadeIn();

			return false;
		}

		// validate domain
		if(!/^[^\.]+\..+$/g.test(data.pre_profile.website))
		{
			box.find('.ti-notice.notice-invalid-domain').fadeIn();

			return false;
		}

		// check email
		if(!data.pre_profile.email.validateEmail())
		{
			box.find('.ti-notice.notice-invalid-email').fadeIn();

			return false;
		}

		// change btn
		let btn = jQuery(this);
		btn.showButtonLoading();

		// save domain
		jQuery('input.field-domain').val(data.pre_profile.website).prop('readonly', true);

		// change DOMAIN_NAME in domain found notice
		let domain_found_notice = jQuery('#ti-found-notification');
		domain_found_notice.html('<p>' + domain_found_notice.data('text').replace('DOMAIN_NAME', data.pre_profile.website) + '</p>');

		// post request
		jQuery.ajax({
			method: "POST",
			url: "https://admin.trustindex.io/" + "api/checkDomain",
			data: { domain: data.pre_profile.website }
		}).always(function(r) {
			if(typeof r.success != "undefined" && r.success && typeof r.exists != "undefined" && r.exists)
			{
				// change btn
				btn.hideButtonLoading();

				// show domain found part
				jQuery('.ti-domain-check-stage').hide();
				jQuery('.ti-domain-found-stage').fadeIn();
			}
			else
			{
				// do register
				doRegister(btn, data);
			}
		});

		return false;
	});

	// Switch tab button
	jQuery(document).on('click', "#trustindex-woocommerce-admin a.btn-switch-tab", function(event) {
		event.preventDefault();

		let btn = jQuery(this);
		switchTab(btn.attr('href'), btn.data('show-notice'));

		return false;
	});

	// Connect account
	jQuery(document).on('click', "#trustindex-woocommerce-admin a.btn-login", function(event) {
		event.preventDefault();

		// get login div
		let box = jQuery('#ti-user-connect');

		// hide errors
		box.find('.ti-notice.hidden').hide();

		// get data for ajax
		let data = {
			signin: {
				username: box.find('input[type=email]').val().trim(),
				password: box.find('input[name=password]').val().trim()
			},
			domain_list: 1
		};

		// check inputs
		if(!data.signin.username.validateEmail() || data.signin.password == "")
		{
			box.find('.ti-notice.notice-invalid').fadeIn();

			return false;
		}

		// change btn
		let btn = jQuery(this);
		btn.showButtonLoading();

		//post request
		jQuery.ajax({
			method: "POST",
			url: "https://admin.trustindex.io/" + "api/connectApi",
			data: data,
			dataType: "jsonp"
		}).always(function(r) {
			// error check
			if(typeof r.success == "undefined" || !r.success)
			{
				// show error
				box.find('.ti-notice.notice-wrong').fadeIn();

				// change btn
				btn.hideButtonLoading();

				return false;
			}

			// save subscription_id
			jQuery('#ti-setup-trustindex input[name=subscription_id]').val(r.subscription_id);

			// check given domain is in received domain list
			let found_domain = null;
			let domain = jQuery('#ti-setup-trustindex input[name=domain]').val();
			for(let d in r.domains)
			{
				if(d.toLowerCase().replace('www.', '') == domain.toLowerCase().replace('www.', ''))
				{
					found_domain = d;
				}
			}

			// domain found
			if(found_domain)
			{
				// save domain
				jQuery('input.field-domain').val(found_domain);

				// save source-id
				jQuery('#ti-setup-trustindex input[name=source_id]').val(r.domains[found_domain]);

				// submit form
				jQuery('#ti-setup-trustindex').submit();
			}
			// not found, show choose list
			else
			{
				// change btn
				btn.hideButtonLoading();

				// make list
				jQuery('#ti-domain-choose-list').html('');
				for(let domain in r.domains)
				{
					let template = jQuery('#ti-choose-domain-template').clone();
					template.find('span').html(domain);
					template.find('a').attr('href', r.domains[domain]);
					template.show();

					jQuery('#ti-domain-choose-list').append(template);
				}

				// change tab
				switchTab('#ti-domain-choose');
			}
		});

		return false;
	});

	// Domain choose
	jQuery(document).on('click', "#trustindex-woocommerce-admin a.btn-choose-domain", function(event) {
		event.preventDefault();

		// change btn
		let btn = jQuery(this);
		btn.showButtonLoading();

		// change other btn to disable
		jQuery('#ti-domain-choose-list .btn-choose-domain').addClass('btn-disabled').css('pointer-events', 'none');

		// save domain
		jQuery('input.field-domain').val(btn.prev().text());

		// save source-id
		jQuery('#ti-setup-trustindex input[name=source_id]').val(btn.attr('href'));

		// submit form
		jQuery('#ti-setup-trustindex').submit();
	});

	// Register account ("Someone else registered..." button)
	jQuery(document).on('click', "#trustindex-woocommerce-admin a.btn-register", function(event) {
		event.preventDefault();

		doRegister(jQuery(this));

		return false;
	});

	/*************************************************************************/
	/* SETUP CAMPAIGN */
	jQuery(document).on('click', ".ti-campaign-setup .btn-save", function(event) {
		event.preventDefault();

		// get button & container
		let btn = jQuery(this);
		let container = btn.closest('.ti-campaign-setup');

		btn.blur();

		// hide errors
		container.find('.ti-notice').addClass('hidden');

		// get text
		let text = "";
		if(jQuery('#wp-campaign-text-wrap').hasClass('tmce-active'))
		{
			text = tinyMCE.get('campaign-text').getContent();
		}
		else
		{
			text = jQuery('#campaign-text').val();
		}

		// check text has {link}
		if(!/<a.+href=['"]{link}['"].*>/i.test(text))
		{
			// scroll page to top
			jQuery(window).scrollTop(0);

			// show notification
			container.find('.notice-invalid-text').removeClass('hidden');

			// stop processing code
			return false;
		}

		// show button loading animation
		btn.showButtonLoading();

		// submit form
		btn.closest('form').submit();
	});

	// Reset subject
	jQuery(document).on('click', "#ti-reset-field-subject", function(event) {
		event.preventDefault();

		let btn = jQuery(this);
		jQuery('input[name=campaign-subject]').val(btn.data('value').htmlDecode());

		return false;
	});

	// Reset content
	jQuery(document).on('click', "#ti-reset-field-content", function(event) {
		event.preventDefault();

		let btn = jQuery(this);

		// wp editor
		if(jQuery('#wp-campaign-text-wrap').hasClass('tmce-active'))
		{
			tinyMCE.get('campaign-text').setContent(btn.data('value').htmlDecode());
		}
		else
		{
			jQuery('#campaign-text').val(btn.data('value').htmlDecode().replace(/<br\s?\/>/gm, "\n"));
		}

		return false;
	});
});