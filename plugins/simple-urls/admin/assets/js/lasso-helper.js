var lasso_lite_helper = {
	is_onboarding_page: lassoLiteOptionsData.is_onboard_page === '1',
	next_step_btn_in_onboarding_page: function() {
		return this.is_onboarding_page ? `<br/><button class="btn next-step">Continue &rarr;</button>` : '';
	},

	pagination_includes_post_id: ['url-links', 'url-opportunities', 'content-links', 'field-urls', 'url-details'],
	pagination_cache_key: 'lasso_lite_pagination',
	default_empty_data: `
		<div class="container p-5">
			<div class="row p-4 align-items-center border rounded">
				<div class="col-lg-4 purple-bg p-4 rounded">
					<img src="https://getlasso.co/wp-content/uploads/dashboard.svg" class="img-fluid">
				</div>
		
				<div class="col-lg p-4">
					<h3 class="mb-3">Get Started by Adding a Link</h3>
					<p class="mb-3">Manage all of your affiliate links here, and see where they're located across your site. Get alerts when a link has new opportunities, is broken, or out of stock.</p>
					<strong><a data-toggle="modal" data-target="#url-add" class="purple"><i class="far fa-plus"></i> Add a Link</a></strong>
				</div>
			</div>
		</div>
	`,
	empty_html: function() {
		return `
			<div class="row align-items-center" id="not-found-wrapper">
				<div class="col text-center p-5 m-5">
					<i class="far fa-stars fa-7x mb-3"></i>
					<h3>We love a fresh start!</h3>
				</div>
			</div>
		`;
	},
	get_loading_image() {
		return '<div class="py-5"><div class="loader"></div></div>';
	},

	/**
	 * Get page number from current url and localStorage
	 * Example: http://affiliate.local/wp-admin/edit.php?post_type=lasso-urls&page=dashboard&search=iphone#page-2
	 * Result: 2
	 *
	 * @param key
	 * @returns {int}
	 */
	get_page_from_current_url() {
		let current_page = 1;
		let result = window.location.href.match(/.*#page-(\d*)/i);

		if (result && result.length) {
			current_page = result[1];
		} else {
			let lasso_current_page = this.get_pagination_cache( this.get_page_name() );

			// set current page as the page from local storage
			if ( lasso_current_page > 0 ) {
				current_page = lasso_current_page;
			}
		}

		return current_page;
	},
	/**
	 * Get url parameter by key
	 *
	 * @param key
	 * @returns {string}
	 */
	get_url_parameter( key ) {
		let url = new URL( window.location.href );
		return url.searchParams.get( key );
	},

	/**
	 * Update url parameter by key and value, delete parameter if value is empty
	 *
	 * @param key
	 * @param value
	 */
	update_url_parameter( key, value ) {
		let url = new URL( window.location.href );

		if ( value ) {
			url.searchParams.set( key, value );
		} else {
			url.searchParams.delete( key );
		}

		window.history.replaceState( null, null, url );
	},
	/**
	 * Get page name
	 *
	 * @returns {string}
	 */
	get_page_name() {
		let url = new URL(location.href);
		let searchParams = new URLSearchParams(url.search);
		return searchParams.get('page');
	},
	generate_paging: function ( paging_el, set_page, total_items, click_page_number_callback, items_on_page = 10 ) {
		let pagination_helper = jQuery(paging_el);
		let data_helper = {
			items: total_items,
			displayedPages: 3,
			itemsOnPage: items_on_page,
			cssStyle: 'light-theme',
			prevText: '<i class="far fa-angle-double-left"></i> Previous',
			nextText: 'Next <i class="far fa-angle-double-right"></i>',
			onPageClick: function(page_number) {
				if ( typeof click_page_number_callback === 'function' ) {
					return click_page_number_callback(page_number)
				}
			}
		};

		if(set_page > 0) {
			data_helper.currentPage = set_page;
		}
		pagination_helper.pagination(data_helper);
	},
	build_pagination_cache_key( key, suffix = '' ) {
		if ( ! suffix && this.pagination_includes_post_id.includes(key) ) {
			suffix = this.get_url_parameter('post_id');
		}

		let cache_key = key + ( suffix ? '_' + suffix : '' );
		cache_key = cache_key.replace(/-/g, '_');

		return cache_key;
	},
	set_local_storage: function(key, value) {
		try {
			if ( key === undefined || ! key || value === undefined ) {
				return;
			}
			localStorage.setItem(key, value);
		} catch (error) {
			console.error('Local storage error.');
		}
	},
	set_pagination_cache( key, page_number, suffix = '' ) {
		if ( ! key || ! page_number) {
			return;
		}

		let cache_key = this.build_pagination_cache_key( key, suffix );
		let pagination_cache = localStorage.getItem(this.pagination_cache_key);
		pagination_cache = pagination_cache ? JSON.parse( pagination_cache ) : {};
		pagination_cache[cache_key] = page_number;
		pagination_cache = JSON.stringify(pagination_cache);

		this.set_local_storage(this.pagination_cache_key, pagination_cache);
	},
	get_pagination_cache( key, suffix = '' ) {
		if ( ! key ) {
			return 1;
		}

		let cache_key = this.build_pagination_cache_key( key, suffix );
		let pagination_cache = localStorage.getItem(this.pagination_cache_key);
		pagination_cache = pagination_cache ? JSON.parse( pagination_cache ) : {};

		if ( cache_key in pagination_cache ) {
			return parseInt(pagination_cache[cache_key]);
		}

		return 1;
	},

	/**
	 *
	 * @param el_container
	 * @param jsrender_template_id
	 * @param data
	 */
	inject_to_template( el_container, jsrender_template_id, data ) {
		if ( data.length > 0 || typeof data === 'object' ) {
			let template = jQuery.templates("#"+jsrender_template_id);
			let html_output = template.render(data);
			jQuery(el_container).html(html_output);
		} else {
			jQuery(el_container).html(this.default_empty_data);
		}
	},

	/**
	 * Remove #page-xx out of current url
	 *
	 * @param set_timeout Use setTimeout to make sure apply successful after click pagination button.
	 */
	remove_page_number_out_of_url(set_timeout = 100) {
		setTimeout( function () {
			let url_without_page_hash = window.location.href.replace(/#page-\d+/g, '');
			window.history.replaceState( null, null, new URL( url_without_page_hash ) );
		}, set_timeout );
	},
	is_empty(input) {
		return typeof (input) === 'undefined';
	},
	scrollTop: function() {
		document.body.scrollTop = 0;
		document.documentElement.scrollTop = 0;
	},
	setProgressZero: function ( progress_bar_element = '#url-save' ) {
		jQuery(progress_bar_element).find(".progress-bar").css({width: '0%'});
	},
	setProgress: function (progessPercentage, progress_bar_element = '#url-save' ) {
		jQuery(progress_bar_element).find(".progress-bar").css({width: progessPercentage + '%'});
	},
	set_progress_bar( maximum, speed, progress_bar_element = '#url-save' ) {
		let progress = 0;
		window.process_bar_interval = setInterval( function () {
			if ( progress >= maximum ) {
				clearInterval(window.process_bar_interval);
			}
			progress++;
			lasso_lite_helper.setProgress( progress, progress_bar_element );
		}, speed );
	},
	set_progress_bar_complete( progress_bar_element = '#url-save' ) {
		lasso_lite_helper.setProgress( 100, progress_bar_element );
		clearInterval(window.process_bar_interval);
	},

	add_loading_button: function ( el, label = '', is_loading = true ) {
		var html = label;
		if ( is_loading ) {
			html = '<span style="opacity: 1">' + label + '</span>&nbsp;<i class="far fa-circle-notch fa-spin"></i>';
		}
		jQuery(el).html(html);
	},

	do_notification(message = '', color = 'green', template_id = "default-template-notification") {
		let alert_id  = '_' + Math.random().toString(36).substr(2, 9);
		let alert_bg  = color + '-bg';
		let json_data = [
			{
				alert_id : alert_id,
				alert_bg : alert_bg,
				message  : message
			}
		];
		lasso_lite_helper.inject_to_template(jQuery("#lasso_lite_notifications"), template_id, json_data);
		jQuery('#' + alert_id).collapse('show');
	},

	clear_notifications: function() {
		jQuery(".alert.red-bg.collapse").collapse('hide');
		jQuery(".alert.orange-bg.collapse").collapse('hide');
		jQuery(".alert.green-bg.collapse").collapse('hide');
	},

	lasso_generate_modal: function () {
		var template = null;
		var modal_id = null;
		var is_rendered = false;
		var modal_object = null;
		var btn_ok = null;
		var btn_cancel = null;
		var on_show_callback = null;
		var on_hide_callback = null;
		var on_submit_callback = null;
		var on_cancel_callback = null;
		var heading = null;
		var description = null;
		var pagination_container = null;
		var _generate_id = function () {
			return "lasso-modal-" + Math.random().toString(16).slice(2);
		};
		var _replace_text = function( text, key, value ) {
			key = '{{' + key + '}}';
			let re = new RegExp( key, "g" );
			return text.replace( re, value );
		};
		var _render_template = function ( optional_data = {} ) {
			let modal_size = '';
			if ( optional_data.hasOwnProperty('use_modal_large') && optional_data.use_modal_large === true ) {
				modal_size = 'modal-lg';
			}
			let backdrop = '';
			if ( optional_data.hasOwnProperty('backdrop') && optional_data.backdrop === true ) {
				backdrop = 'data-backdrop="static"';
			}
			let template_temp = [
				'<div class="modal fade modal_confirm" id="{{modal_id}}" tabindex="-1" role="dialog" ' + backdrop + '>',
				'<div class="modal-dialog ' + modal_size + '" role="document">',
				'<div class="modal-content text-center shadow p-5 rounded">',
				'<h2>Remove \"This Product\"</h2>',
				'<p>If removed, you won\'t be able to get its back.</p>',
				'<div class="pagination-container"></div>',
				'<div>' +
				'<button type="button" class="btn cancel-btn mx-1" data-dismiss="modal">Cancel</button>',
				'<button type="button" class="btn red-bg mx-1 btn-ok" data-lasso-id="">OK</button>',
				'</div>',
				'</div>',
				'</div>' +
				'</div>'
			];
			template = _replace_text( template_temp.join( "\n"), "modal_id", modal_id );
		};
		var _inject_to_template = function ( optional_data = {} ) {
			if ( !is_rendered ) {
				jQuery("#wpbody-content").append(template);
				modal_object = jQuery("#"+modal_id);
				heading = modal_object.find('h2');
				btn_ok = modal_object.find('.btn-ok');
				btn_cancel = modal_object.find('.cancel-btn');
				description = modal_object.find('p');
				pagination_container = modal_object.find('.pagination-container');
				is_rendered = true;
				if ( optional_data.hasOwnProperty('hide_btn_cancel') && optional_data.hide_btn_cancel === true ) {
					jQuery(btn_cancel).addClass('d-none');
				}
				if ( optional_data.hasOwnProperty('hide_btn_ok') && optional_data.hide_btn_ok === true ) {
					jQuery(btn_ok).addClass('d-none');
				}
			}
		}
		this.set_heading = function ( heading_text ) {
			heading.text( heading_text );
			return this;
		}
		this.set_description = function ( msg, is_text = true ) {
			if ( is_text ) {
				description.text( msg );
			}
			else {
				description.html( msg );
			}

			return this;
		}
		this.init = function ( optional_data = {}) {
			modal_id = _generate_id();
			_render_template( optional_data );
			_inject_to_template( optional_data );
			return this;
		}
		this.set_lasso_id = function ( lasso_id ) {
			btn_ok.data('lasso-id', lasso_id);
		}
		this.show = function () {
			modal_object.on('shown.bs.modal', on_show_callback);
			modal_object.on('hide.bs.modal', on_hide_callback);
			btn_ok.unbind().on('click', on_submit_callback);
			btn_cancel.unbind().on('click', on_cancel_callback);
			modal_object.modal("show");

			this.set_btn_ok_el( btn_ok );
			return this;
		}
		this.hide = function () {
			modal_object.modal("hide");
		}
		this.on_show = function ( callback ) {
			if ( typeof callback === "function" ) {
				on_show_callback = callback;
			}
			return this;
		}
		this.on_submit = function ( callback ) {
			if ( typeof callback === "function" ) {
				on_submit_callback = callback;
			}
			return this;
		}
		this.on_cancel = function ( callback ) {
			if ( typeof callback === "function" ) {
				on_cancel_callback = callback;
			}
			return this;
		}
		this.on_hide = function ( callback ) {
			if ( typeof callback === "function" ) {
				on_hide_callback = callback;
			}
			return this;
		}
		this.set_btn_ok_el = function ( el ) {
			this.btn_ok_el = el;
		}
		this.set_btn_ok = function ( optional_data ) {
			if ( optional_data.hasOwnProperty('class') && optional_data.class != '' ) {
				btn_ok.removeClass('red-bg');
				btn_ok.addClass(optional_data.class);
			}
			if ( optional_data.hasOwnProperty('label') && optional_data.label != '' ) {
				btn_ok.removeClass('red-bg');
				btn_ok.text(optional_data.label);
			}

			return this;
		}
		this.get_modal_id = function () {
			return modal_id;
		}
		this.set_pagination = function ( pagination_class = '' ) {
			pagination_container.html(`<div class="pagination row align-items-center no-gutters ${pagination_class}"></div>`);
			return this;
		}
	},
	throttle: function(fn, timeout, callback, delayed, trailing, debounce) {
		timeout || (timeout = 100);
		var timer = false,
			lastCall = false,
			hasCallback = (typeof callback === "function"),
			startTimer = function(wrapper, args) {
				timer = setTimeout(function(){
					timer = false;
					if (delayed || trailing) {
						fn.apply(wrapper, args);
						if (trailing) { lastCall = +new Date(); }
					}
					if (hasCallback) { callback.apply(wrapper, args); }
				}, timeout);
			},
			wrapper = function(){
				if (timer && !debounce) { return; }
				if (!timer && !delayed) {
					if (!trailing || (+new Date()-lastCall) > timeout) {
						fn.apply(this, arguments);
						if (trailing) { lastCall = +new Date(); }
					}
				}
				if (debounce || !trailing) { clearTimeout(timer); }
				startTimer(this, arguments);
			}
		if (jQuery.guid) { wrapper.guid = fn.guid = fn.guid || jQuery.guid++; }
		return wrapper;
	},
	debounce: function(fn, timeout, callback, delayed, trailing) {
        return this.throttle(fn, timeout, callback, delayed, trailing, true);
    },

	// Extract all input fields in the form
	fetchAllOptions: function() {
		// Create an object
		var values = {};

		// Loop through all the inputs
		jQuery('form.lasso-lite-admin-settings-form input, form.lasso-lite-admin-settings-form select, form.lasso-lite-admin-settings-form textarea').each(function () {
			var $field = jQuery(this);

			var name = $field.attr('name');

			var value;

			if ('checkbox' === $field.attr('type')) {
				value = $field.prop('checked');
			} else {
				value = $field.val();
			}

			values[name] = value;
		});

		return values;
	},
	get_msg_ajax_error( xhr ) {
		let msg = "An unexpected error has occurred please try again later.";
		if ( xhr.hasOwnProperty('responseJSON') && typeof xhr.responseJSON.data === 'string' ) {
			msg = xhr.responseJSON.data;
		}
		return msg;
	},
	get_loading_image_small() {
		return '<div class="loader-small"></div>';
	}
};
