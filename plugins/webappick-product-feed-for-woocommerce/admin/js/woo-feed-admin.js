// noinspection JSUnresolvedletiable
/**!
 * WooFeed Scripts
 * @version 3.3.6
 * @package WooFeed
 * @copyright 2020 WebAppick
 *
 */
/* global ajaxurl, wpAjax, postboxes, pagenow, alert, deleteUserSetting, typenow, adminpage, thousandsSeparator, decimalPoint, isRtl */
// noinspection JSUnresolvedVariable

(function ($, window, document, wpAjax, opts) {
	"use strict";
	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 */

	/**
	 * disable element utility
	 *
	 * @since 3.1.9
	 *
	 * @param {*} status
	 * @returns {$|HTMLElement}
	 */
	$.fn.disabled = function (status) {
		$(this).each(function () {
			let self = $(this),
				prop = 'disabled';

			if (typeof self.prop(prop) !== 'undefined') {
				self.prop(prop, status === void 0 || status === true);
			} else {
				!0 === status ? self.addClass(prop) : self.removeClass(prop);
			}
		});
		return self; // method chaining
	};

	/**
	 * Check if a HTMLElement or jQuery is disabled
	 */
	$.fn.isDisabled = function () {
		let self = $(this),
			prop = 'disabled';
		return typeof self.prop(prop) !== 'undefined' ? self.prop(prop) : self.hasClass(prop);
	};

	/**
	 * Clear Tooltip for clip board js
	 * @param {Object} event
	 */
	const clearTooltip = (event) => {
		$(event.currentTarget).removeClass((index, className) => (className.match(/\btooltipped-\S+/g) || []).join(' ')).removeClass('tooltipped').removeAttr('aria-label');
	};

	const showTooltip = (elem, msg) => {
		$(elem).addClass('tooltipped tooltipped-s').attr('aria-label', msg);
	};

	const fallbackMessage = (action) => {
		let actionMsg,
			actionKey = action === 'cut' ? 'X' : 'C';

		if (/iPhone|iPad/i.test(navigator.userAgent)) {
			actionMsg = 'No support :(';
		} else if (/Mac/i.test(navigator.userAgent)) {
			actionMsg = 'Press ⌘-' + actionKey + ' to ' + action;
		} else {
			actionMsg = 'Press Ctrl-' + actionKey + ' to ' + action;
		}

		return actionMsg;
	};

	/**
	 * Alias of jQuery.extend()
	 * @param {Object} _default
	 * @param {Object} _args
	 */
	const extend = (_default, _args) => $.extend(true, {}, _default, _args);

	/*
	* Handles product attributes
	*
	* @since 4.4.18
	* */
	class WooFeedCustomFields {

		constructor(field, status, isTaxonomy) {
			this.productCustomFields(field, status, isTaxonomy);
		}

		cacheClear() {
			let data = {
				action: 'woo_feed_product_attribute_cache_remove',
				nonce: wpf_ajax_obj.ajax.nonce,
			};
			$.ajax({
				method: 'POST',
				url: wpf_ajax_obj.wpf_ajax_url,
				data
			});
		}

		productCustomFields(field, status = false, isTaxonomy) {
			let data = {
				action: 'woo_feed_custom_fields_status_change',
				nonce: wpf_ajax_obj.ajax.nonce,
				field,
				status,
				isTaxonomy
			};
			$.ajax({
				method: 'POST',
				url: wpf_ajax_obj.wpf_ajax_url,
				data,
				success: (response) => {
					if (response.success) {
						this.cacheClear();
					} else {
						console.log(response);
					}
					$('#' + field + '-switcher').closest('td').find('.switch-loader').hide();
				}
			});
		}
	}

	/*
	* Handles product categories
	*
	* @since 4.4.39
	* */
	class WooFeedMapCategories {
		wooFeed_get_google_categories() {
			wpAjax.send('get_google_categories', {
				type: 'GET',
				data: {
					_ajax_nonce: opts.nonce,
					action: "get_google_categories",
					// provider: provider
				}
			}).then(function (response) {
				$('.selectize-google-category').selectize({
					valueField: 'value',
					labelField: 'text',
					placeholder: 'Select a category',
					searchField: ['value', 'text'],
					options: response,
					render: {
						option: (data, escape) => `<div class="item wapk-selectize-item">${escape(data.value + ' - ' + data.text)}</div>`,
						item: (data, escape) => `<div class="item wapk-selectize-item">${escape(data.value + ' - ' + data.text)}</div>`
					}
				});

				//remove spinner element
				$('.woo-feed-cat-map-spinner').remove();
			}).fail(helper.ajax_fail);
		}

		wooFeed_get_facebook_categories() {
			// get facebook categories
			wpAjax.send('get_facebook_categories', {
				type: 'GET',
				data: {
					_ajax_nonce: opts.nonce,
					action: "get_facebook_categories",
					// provider: provider
				}
			}).then(function (response) {
				$('.selectize-google-category').selectize({
					valueField: 'value',
					labelField: 'text',
					placeholder: 'Select a Facebook category',
					searchField: ['value', 'text'],
					options: response,
					render: {
						option: (data, escape) => `<div class="item wapk-selectize-item">${escape(data.value + ' - ' + data.text)}</div>`,
						item: (data, escape) => `<div class="item wapk-selectize-item">${escape(data.value + ' - ' + data.text)}</div>`
					}
				});

				//remove spinner element
				$('.woo-feed-cat-map-spinner').remove();
			}).fail(helper.ajax_fail);
		}
	}

	let $copyBtn,
		clipboard,
		googleCategories,
		facebookCategories,
		helper = {
			in_array: (needle, haystack) => {
				try {
					return haystack.indexOf(needle) !== -1;
				} catch (e) {
					return false;
				}
			},
			selectize_render_item: (data, escape) => `<div class="item wapk-selectize-item">${escape(data.text)}</div>`, // phpcs:ignore WordPressVIPMinimum.JS.StringConcat.Found,
			ajax_fail: e => {
				console.warn(e);
				alert(e.hasOwnProperty('statusText') && e.hasOwnProperty('status') ? opts.ajax.error + '\n' + e.statusText + ' (' + e.status + ')' : e);
			},
			/**
			 * Initialize Sortable
			 * @param {$|HTMLElement} el
			 * @param {object} config
			 * @param {int|boolean} column
			 * @param {function} onDrop
			 * @return {$|HTMLElement}
			 */
			sortable: (el, config, column, onDrop) => {
				return (el || $('.sorted_table')).each(function () {
					let self = $(this),
						column_count = self.find('tbody > tr:eq(0) > td').length || column || 9;
					self.wf_sortable(extend({
						containerSelector: 'table',
						itemPath: '> tbody',
						itemSelector: 'tr',
						handle: 'i.wf_sortedtable',
						placeholder: `<tr class="placeholder"><td colspan="${column_count}"></td></tr>`,
						onDrop: ($item, container, _super, event) => {
							$item.removeClass(container.group.options.draggedClass).removeAttr('style');
							$("body").removeClass(container.group.options.bodyClass);
							// var numb = $item.find(".wf_mattributes").attr("name").replace(/\D/g, "");
							$item.find("input.wf_attributes").attr("name", "default[" + numb + "]");
							if (onDrop && 'function' === typeof (onDrop)) {
								onDrop($item, container, _super, event);
							}
						},
					}, config));
				});
			},
			selectize: (el, config) => {
				return (el || $('select.selectize')).not('.selectized').not('.selectize-google-category').each(function () {
					let self = $(this);
					self.selectize(extend({
						create: self.data('create') || false,
						plugins: self.data('plugins') ? self.data('plugins').split(',').map(function (s) {
							return s.trim();
						}) : [],
						//['remove_button'],
						render: {
							item: helper.selectize_render_item
						}
					}, config));
				});
			},
			fancySelect: (el, config) => {
				return (el || $('select.fancySelect')).not('.FancySelectInit').each(function () {
					let self = $(this);
					self.fancySelect(extend({
						maxItemShow: 3
					}, config));
				});
			},
			reindex_config_table: () => {
				$('#table-1').find('tbody tr').each((x, el) => {
					$(el).find('[name]').each((x1, el) => {
						$(el).attr('name', $(el).attr('name').replace(/(\[\d\])/g, `[${x}]`));
					});
				});
			},
			common: () => {
				helper.sortable($('.sorted_table'), {}, 9, helper.reindex_config_table);
				helper.selectize();
				helper.fancySelect($('.outputType'));

				// Init fancy select after getting output_types from API in new UI.
				wp.hooks.addAction('init_fancy_select', 'fancy_select', function () {
					let data = helper.fancySelect($('.ctx-outputType'));

					helper.fancySelect($('.ctx-postStatus'));

				});

				// wp.hooks.addAction('test_select_add', 'test',  function (options) {
				// 	let fancySelect = $('.my-select');
				// 	// fancySelect.fancySelect();
				// 	// fancySelect.append(options);
				// 	// fancySelect.trigger('update.fs');
				//
				// 	fancySelect.fancySelect({
				// 			optionTemplate: function(optionEl) {
				// 				return optionEl.text() + '<div class="icon-' + optionEl.data('icon') + '"></div>';
				// 			}
				// 		}
				// 	});
				//
				// });

			},
			/**
			 * Set locale storage data for CTX Feed
			 * @param dataName
			 * @param data
			 * @param shouldStringify
			 * @param addToParent
			 * @param parentName
			 */
			setLocalStorage: (dataName, data, shouldStringify = true, addToParent = true, parentName = 'ctxFeedData') => {
				if (shouldStringify) {
					data = JSON.stringify(data);
				}
				if (addToParent) {
					let prevData = window.localStorage.getItem(parentName);
					if (null !== prevData) {
						prevData = JSON.parse(window.localStorage.getItem(parentName));
						prevData[dataName] = data
						window.localStorage.setItem(parentName, JSON.stringify(prevData));
					} else {
						window.localStorage.setItem(parentName, JSON.stringify({[dataName]: data}));
					}
				} else {
					if (shouldStringify) {
						window.localStorage.setItem(dataName, JSON.stringify(data));
					} else {
						window.localStorage.setItem(dataName, data);
					}
				}
			},

			/**
			 * clear localstorage data.
			 * @param dataName
			 * @param innerChildren
			 * @param hasParent
			 * @param parentName
			 * @returns {boolean}
			 * //TODO: if innerChildren has length delete inner children through loop.
			 */

			clearLocalStorage: (dataName, innerChildren = [], hasParent = true, parentName = 'ctxFeedData') => {
				let tempData = null;
				let hasDeleted = false;
				// if dataName is innerChild of parentName
				if (hasParent) {
					tempData = window.localStorage.getItem(parentName);
					if (!tempData) return hasDeleted;

					tempData = JSON.parse(tempData);
					// dataName is child of parentName.
					if (tempData.hasOwnProperty(dataName)) {
						// dataName's has innerChildren.
						if (innerChildren.length) {
							let data = JSON.parse(tempData[dataName]);
							innerChildren.map(child => {
								if (data.hasOwnProperty(child)) {
									delete data[child]; // delete each innerChild
									hasDeleted = true;
								}
							});
							// update dataName with current data.
							helper.setLocalStorage(dataName, data);
						} else {
							delete tempData[dataName];
							hasDeleted = true;
							// update dataName with current data.
							helper.setLocalStorage(dataName, null);
						}
					}
				} else {
					window.localStorage.removeItem(dataName);
					hasDeleted = true;
				}

				return hasDeleted;
			},

		},
		// helper functions
		feedEditor = {
			/**
			 * The Editor Form Elem.
			 * @type {$|HTMLElement}
			 */
			form: null,

			/**
			 * Initialize The Feed Editor {Tabs...}
			 * @returns {void}
			 */
			init: function () {
				let self = this;
				self.form = $('.generateFeed');
				if (!self.form.length) return;
				helper.common();
				// noinspection JSUnresolvedVariable
				$(document).trigger('feed_editor_init');
				$(document).trigger(new jQuery.Event('feedEditor.init', {
					target: this.form
				}));
			},

			/**
			 * Render Merchant info ajax response and handle allowed feed type for selected merchant
			 * @param {$|HTMLElement} merchantInfo jQuery dom object
			 * @param {$|HTMLElement} feedType     jQuery dom object
			 * @param {Object} r            ajax response object
			 */
			renderMerchantInfo: function (merchantInfo, feedType, r) {
				for (let k in r) {
					if (r.hasOwnProperty(k)) {
						merchantInfo.find('.merchant-info-section.' + k + ' .data').html(r[k]); // phpcs:ignore WordPressVIPMinimum.JS.HTMLExecutingFunctions.html

						if ('feed_file_type' === k) {
							(function () {
								let types = r[k].split(",").map(function (t) {
									return t.trim().toLowerCase();
								}).filter(function (t) {
									// noinspection JSUnresolvedVariable
									return t !== '' && t !== opts.na.toLowerCase();
								});

								if (types.length) {
									feedType.find('option').removeAttr('selected').each(function () {
										let opt = $(this);
										opt.val() && !helper.in_array(opt.val(), types) ? opt.disabled(!0) : opt.disabled(!1);
									});
									if (types.length === 1) feedType.find('option[value="' + types[0] + '"]').attr('selected', 'selected');
								} else feedType.find('option').disabled(!1);
							})();
						}
					}
				}

				merchantInfo.find('.spinner').removeClass('is-active');
				feedType.disabled(!1);
				feedType.trigger('change');

				/**
				 * Triggers after merchant configuration is fetched & loaded
				 * */
				$(document).trigger('woo_feed_config_loaded');

				feedType.parent().find('.spinner').removeClass('is-active');
			},

			/**
			 * Render Feed Template Tabs and settings while creating new feed.
			 * @param {$|HTMLElement} feedForm     feed from query dom object
			 * @param {object} r            merchant template ajax response object
			 */
			renderMerchantTemplate: function (feedForm, r) {
				let _loop = function _loop(k) {
					if (r.hasOwnProperty(k)) {
						if ('tabs' === k) {
							// noinspection JSUnresolvedFunction
							feedForm.html(r[k]); // phpcs:ignore WordPressVIPMinimum.JS.HTMLExecutingFunctions.html
						} else {
							let contentSettings = $('[name="' + k + '"]');

							if (contentSettings.length) {
								contentSettings.each(function () {
									let elem = $(this);

									if (elem.is('select')) {
										elem.find('[value="' + r[k] + '"]').prop('selected', true);
									} else if ((elem.is('[type=checkbox]') || elem.is('[type=radio]')) && elem.val() === r[k]) {
										elem.prop('checked', true);
									} else {
										elem.val(r[k]); // type=text
									}
								}).trigger('change');
							}
						}
					}
				};

				for (let k in r) {
					_loop(k);
				}

				feedEditor.init();
			}
		},
		// Feed Editor Table
		merchantInfoCache = [],
		merchantTemplateCache = [],
		tooltip = () => {
			// Tooltip only Text
			$('.wfmasterTooltip')
				.hover(function () {
					// Hover over code
					let self = $(this), title = self.attr('wftitle');
					self.data('tipText', title).removeAttr('wftitle');
					$('<p class="wftooltip"></p>').text(title).appendTo('body').fadeIn('slow');
				}, function () {
					// Hover out code
					let self = $(this);
					self.attr('wftitle', self.data('tipText'));
					$('.wftooltip').remove();
				})
				.mousemove(function (e) {
					$('.wftooltip').css({
						top: e.pageY + 10,
						left: e.pageX + 20
					});
				});
		},
		clip = () => {
			$copyBtn = $('.toClipboard');
			if (!ClipboardJS.isSupported() || /iPhone|iPad/i.test(navigator.userAgent)) {
				$copyBtn.find('img').hide(0);
			} else {
				$copyBtn.each(function () {
					$(this).on('mouseleave', clearTooltip).on('blur', clearTooltip);
				});
				clipboard = new ClipboardJS('.toClipboard');
				clipboard.on('error', function (event) {
					showTooltip(event.trigger, fallbackMessage(event.action));
				}).on('success', function (event) {
					showTooltip(event.trigger, 'Copied');
				});
			}
		};

	/**
	 * Feed Generator Module
	 */
	class feedGenerator {

		/**
		 * Constructor
		 * @constructor
		 */
		constructor() {
			this._feed = opts.generator.feed; // wf_config+xxxx
			this._limit = opts.generator.limit;
			this._progress = 0;
			this._timer = null;
			this._color = false;
			// batch info
			this._total_batch = 0;
			this._current_batch = 0;
			this._product_ids = [];
			this._progress_per_batch = 0;
			this._refresh = true;
			// noinspection JSUnresolvedVariable
			this._regenerate = opts.generator.regenerate;
			window.isRegenerating = false;
			this._all_btn = $('.wpf_regenerate');
			this._current_btn = $(`#${this._feed.replace('wf_config', 'wf_feed_')}`);
			this._current_btn_label = '';
		}

		/**
		 * Init Hooks (Event)
		 * @return {feedGenerator}
		 */
		init() {
			let self = this;
			if ('' !== this._feed && this._regenerate && false === window.isRegenerating) {
				this.generate();
			}

			//thickbox body add feed plugin class to make it unique and custom style
			let current_page_query = window.location.search;
			let params = new URLSearchParams(current_page_query);
			let page_name = params.get("page");
			if ("webappick-manage-feeds" === page_name) {
				$('body').addClass('woo-import-popup-body');
			}

			$(document).on('click', '.woo-feed-campaign-close-button', function (event) {
				event.preventDefault();

				$(this).parent('.woo-feed-promotion').hide();
				let condition = $(this).data('condition');
				if (1 === condition) {
					wpAjax.post('woo_feed_hide_promotion', {
						_ajax_nonce: opts.nonce,
						condition: condition,
					}).then(response => {
						self._log(response);
					}).fail(error => {
						self._log(error);
						self._updateProgressStatus(error.message);
						self._color = 'red';
					});
				}
			});

			$(document).on('click', '.wpf_regenerate', function (event) {
				event.preventDefault();
				self._current_btn = $(this);
				if (self._current_btn.hasClass('disabled') || window.isRegenerating === true) return;
				self._feed = self._current_btn.attr('id').replace('wf_feed_', 'wf_config');
				if ('' !== self._feed) {
					self.generate();
				}
			});
			return this;
		}

		_block_button() {
			if (this._all_btn.length) {
				this._all_btn.addClass('disabled');
			}
			if (this._current_btn.length) {
				this._current_btn.find('span').addClass('wpf_spin reverse_spin');
				this._current_btn_label = this._current_btn.attr('title');
				// noinspection JSUnresolvedVariable
				this._current_btn.attr('aria-label', opts.regenerate).attr('title', opts.regenerate);
			}
		}

		_unblock_button() {
			if (this._all_btn.length) {
				this._all_btn.removeClass('disabled');
			}
			if (this._current_btn.length) {
				this._current_btn.find('span').removeClass('wpf_spin');
				this._current_btn.find('span').removeClass('reverse_spin');
				this._current_btn.attr('aria-label', this._current_btn_label).attr('title', this._current_btn_label);
			}
		}

		/**
		 * Generate Feed
		 * @return void
		 */
		generate() {
			let self = this;
			window.isRegenerating = true;
			this._block_button();
			this._resetProgressBar();
			this._progressBarActive();
			this._log('Counting Total Products');
			this._updateProgressStatus('Fetching products.');
			this._get_product_ids().then(response => {
				this._progress = 10;
				self._log({response});
				if (response.success) {
					self._log(`Total ${response.total} Products found.`);
					self._product_ids = response.product;
					self._total_batch = this._product_ids.length;
					self._current_batch = 0;
					self._progress_per_batch = (90 - this._progress) / this._total_batch;
					self._process_batch();
					self._updateProgressStatus('Processing Products...');
				} else {
					self._updateProgressStatus(response.data.message);
				}
			}).fail(error => {
				self._log(error);
				self._updateProgressStatus(error.message);
				self._color = 'red';
				setTimeout(function () {
					self._stopProgressBar();
					self._unblock_button();
				}, 1500);
			});
		}

		/**
		 * Get Product Ids
		 * @returns {$.promise}
		 * @private
		 */
		_get_product_ids() {
			this._progress = 5;
			return wpAjax.post('get_product_information', {
				_ajax_nonce: opts.nonce,
				feed: this._feed,
				limit: this._limit,
			});
		}

		/**
		 * Run the Batch
		 * @private
		 */
		_process_batch() {
			let self = this;
			let status = `Processing Batch ${this._current_batch + 1} of ${this._total_batch}`;
			this._updateProgressStatus(status);
			this._log(status);
			wpAjax.post('make_batch_feed', {
				_ajax_nonce: opts.nonce,
				feed: this._feed,
				products: this._product_ids[this._current_batch],
				loop: this._current_batch,
			}).then(response => {
				self._current_batch++;
				self._log(`Batch ${self._current_batch} Completed`);
				self._log(response);
				if (self._current_batch < self._total_batch) {
					self._process_batch();
					self._progress += self._progress_per_batch;
				}
				if (self._current_batch === self._total_batch) {
					self._save_feed_file();
				}
			}).fail(error => {
				self._log(error);
				self._updateProgressStatus(error.message);
				self._color = 'red';
				setTimeout(function () {
					self._stopProgressBar();
					self._unblock_button();
				}, 1500);
			});
		}

		/**
		 * Save Feed Data from temp to feed file
		 * @private
		 */
		_save_feed_file() {
			let self = this;
			this._log('Saving feed file');
			this._updateProgressStatus('Saving feed file');
			wpAjax.post('save_feed_file', {
				_ajax_nonce: opts.nonce,
				feed: this._feed,
			}).then(response => {
				self._log(response);
				self._progress = 100;
				if (self._refresh) {
					window.location.href = `${opts.pages.list.feed}&link=${response.url}&cat=${response.cat}`;
				}
				setTimeout(function () {
					self._stopProgressBar();
					setTimeout(function () {
						self._resetProgressBar(true);
						self._unblock_button();
					}, 3000);
				}, 2500);
			}).fail(error => {
				self._log(error);
				self._updateProgressStatus(error.message);
				self._color = 'red';
				setTimeout(function () {
					self._stopProgressBar();
					self._unblock_button();
				}, 1500);
			});
		}

		/**
		 * Console log wrapper with debug settings.
		 * @param data
		 * @returns {feedGenerator}
		 * @private
		 */
		_log(data) {
			// noinspection JSUnresolvedVariable
			if (opts.wpf_debug) {
				console.log(data);
			}
			return this;
		}

		/**
		 * Run the progressbar refresh interval
		 * @param {int} refreshInterval
		 * @returns {feedGenerator}
		 * @private
		 */
		_progressBarActive(refreshInterval = 0) {
			let self = this;
			this._toggleProgressBar(true);
			this._timer = setInterval(function () {
				self._updateProgressBar();
			}, refreshInterval || 1000);
			return this;
		}

		/**
		 * Stop Progressbar
		 * @returns {feedGenerator}
		 * @private
		 */
		_stopProgressBar() {
			clearInterval(this._timer);
			return this;
		}

		/**
		 * Reset Progressbar
		 * @returns {feedGenerator}
		 * @private
		 */
		_resetProgressBar(update) {
			this._toggleProgressBar(false);
			this._updateProgressStatus('');
			clearInterval(this._timer);
			this._color = false;
			this._timer = null;
			this._progress = 0;
			if (update) {
				this._updateProgressBar();
			}
			return this;
		}

		/**
		 * Show hide the progress bar el
		 * @param status
		 * @returns {feedGenerator}
		 * @private
		 */
		_toggleProgressBar(status) {
			let table = $('#feed_progress_table');
			if (status) {
				table.show();
			} else {
				table.hide();
			}
			return this;
		}

		/**
		 * Update Progress bar text status
		 * @param {string} status
		 * @returns {feedGenerator}
		 * @private
		 */
		_updateProgressStatus(status) {
			$('.feed-progress-status').html(status);
			return this;
		}

		_getErrorMessageByCode(error) {
			let progress_message = $('.feed-progress-status');
			progress_message.css({'color': this._color});
			$('.feed-progress-percentage').css({'color': this._color});
			$('.wpf_spin').css({'color': this._color});

			let message = error.status + ' : ' + error.statusText + '. ';
			if (500 === error.status) {
				message += "Please increase your PHP max_execution_time. Please <a target='_blank' href='https://webappick.com/docs/woo-feed/faq-for-woocommerce-product-feed/how-to-solve-processing-10-feed-generation-stuck-error/'>read this doc</a>.";
			}

			this._updateProgressStatus(message);

		}

		/**
		 * Update Progress Data
		 * hooked with setInterval
		 * @private
		 */
		_updateProgressBar() {
			let percentage = $('.feed-progress-percentage'),
				bar = $('.feed-progress-bar-fill'),
				_progress = `${Math.round(this._progress)}%`;
			bar.css({
				width: _progress,
			});
			percentage.text(_progress);
		}
	}

	// expose to the global scope
	window.wf = {
		helper: helper,
		feedEditor: feedEditor,
		generator: feedGenerator,
	};

	$(window).on('load', function () {
		// Template loading ui conflict
		if ($(location).attr("href").match(/webappick.*feed/g) !== null) {
			$('#wpbody-content').addClass('woofeed-body-content');
		}

		// ClipBoardJS
		clip();
		// postbox toggle
		postboxes.add_postbox_toggles(pagenow);
		// initialize generator
		let generator = new feedGenerator();
		generator.init();
		// noinspection JSUnresolvedVariable
		if ('' !== opts.generator.feed && opts.generator.regenerate) {

		}
		// initialize editor
		feedEditor.init();
		helper.common(); // Generate Feed Add Table Row
		tooltip();
		// validate feed editor
		$(".generateFeed").validate();

		// document events
		$(document)
			.on('blur', 'input[name="wfDAttributeName"]', function (e) {
				e.preventDefault();
				let attr_name = $(this).val();
				attr_name = attr_name.toLowerCase();
				attr_name = attr_name.split(' ').join('_');

				$('#wfDAttributeCode').val(attr_name);

			})
			.on('click', '[data-toggle_slide]', function (e) {
				e.preventDefault();
				$($(this).data('toggle_slide')).slideToggle('fast');
			})
			// XML Feed Wrapper
			.on('click', '#wf_newRow', function () {
				let tbody = $('#table-1 tbody'),
					template = $('#feed_config_template').text().trim().replace(/__idx__/g, tbody.find('tr').length);
				tbody.append(template);
				helper.fancySelect($('.outputType'));
				helper.fancySelect($('.ctx-outputType'));
			})
			// feed delete alert.
			.on('click', '.single-feed-delete', function (event) {
				event.preventDefault();
				// noinspection JSUnresolvedVariable
				if (confirm(opts.form.del_confirm)) {
					window.location.href = $(this).attr('val');
				}
			})
			// clear cache data.
			.on('click', '.wf_clean_cache_wrapper', function (event) {
				event.preventDefault();
				var nonce = $('.woo-feed-clean-cache-nonce').val();
				var loader = $('.woo-feed-cache-loader');
				var types = ["woo_feed_attributes", "woo_feed_category_mapping", "woo_feed_dynamic_attributes", "woo_feed_attribute_mapping", "woo_feed_wp_options"];

				//show loader
				loader.show();
				for (let i = 0; i < types.length; i++) {
					// passed cache nonce
					wpAjax.post('clear_cache_data', {
						_ajax_clean_nonce: nonce,
						type: types[i]
					}).then(function (response) {
						if (response.success) {
							//hader notice dismiss action
							wpAjax.post('pressmodo_dismiss_notice', {
								'id': types[i],
								'nonce': response.nonce
							});
							loader.hide(); //hide loader
							location.reload();
						}
					}).fail(function () {
						console.log('something wrong');
					});
				}

			})// Copy Status

			.on('click', '.ctx-notice-cache-clear', function (event) {
				event.preventDefault();
				var nonce = $(this).data('id');
				console.log(nonce);
				var notificationLoader = $('.loadingio-spinner-reload-5t7io14g51q');

				var clickedId_type = $(this).attr('id');
				//show loader
				notificationLoader.show();
				var cache_types = ["woo_feed_attributes", "woo_feed_category_mapping", "woo_feed_dynamic_attributes", "woo_feed_attribute_mapping", "woo_feed_wp_options"];


				helper.clearLocalStorage('options', ['product_attributes']);

				for (let i = 0; i < cache_types.length; i++) {
					// passed cache nonce
					wpAjax.post('clear_cache_data', {
						_ajax_clean_nonce: nonce,
						type: cache_types[i]
					}).then(function (response) {
						if (response.success) {
							//hader notice dismiss action
							wpAjax.post('pressmodo_dismiss_notice', {
								// 'id': 'woo_feed_attributes',
								'id': cache_types[i],
								'nonce': response.nonce
							});
							notificationLoader.hide(); //hide loader
							location.reload();
						}
					}).fail(function () {
						console.log('something wrong');
					});
				}

			})// Copy Status


			.on('click', '#woo-feed-copy-status-btn', function (event) {
				event.preventDefault();
				let button = $('#woo-feed-copy-status-btn');
				let status_area = $('#woo-feed-status-area');
				button.val('Copied');
				status_area.css('visibility', 'visible');
				status_area.select();

				document.execCommand('copy');
			})
			// feed value dropdown change.
			.on('change', '.ctx-wf_attr.ctx-wf_attributes', function (event) {
				event.preventDefault();

				// $('.fancy-picker-picked').trigger("click"); // trigger fancy select box clicked
				// TODO need to match with new UI
				// price attributes
				var price_attributes = ['price', 'current_price', 'sale_price', 'price_with_tax', 'current_price_with_tax', 'sale_price_with_tax', 'shipping_cost'];
				// current value
				var current_attribute_value = $(this).val();
				// var outputSelect = $(this).parents('tr').find('.outputType');
				var outputSelect = $(this).parents('tr').find('.ctx-outputType');
				// var fancyOption = $(this).parents('tr').find('.fancy-picker-content .fancy-picker-option');
				var fancyOption = $(this).parents('tr').find('.fancy-picker-content');

				let arr = [
					'<div class="fancy-picker-option" data-value="0">Select Attribute</div>',
					'<div class="fancy-picker-option selected" data-value="1"> Default </div>',
					'<div class="fancy-picker-option" data-value="2"> Strip Tags </div>',
					'<div class="fancy-picker-option" data-value="3"> UTF-8 Encode </div>',
					'<div class="fancy-picker-option" data-value="4"> htmlentities </div>',
					'<div class="fancy-picker-option" data-value="5"> Integer </div>',
					'<div class="fancy-picker-option" data-value="6"> Price </div>',
					'<div class="fancy-picker-option" data-value="7"> Rounded Price </div>',
					'<div class="fancy-picker-option" data-value="8"> Remove Space </div>',
					'<div class="fancy-picker-option" data-value="9"> CDATA </div>',
					'<div class="fancy-picker-option" data-value="10"> Remove Special Character </div>',
					'<div class="fancy-picker-option" data-value="11"> Remove ShortCodes </div>',
					'<div class="fancy-picker-option" data-value="12"> ucwords </div>',
					'<div class="fancy-picker-option" data-value="13"> ucfirst </div>',
					'<div class="fancy-picker-option" data-value="14"> strtoupper </div>',
					'<div class="fancy-picker-option" data-value="15"> strtolower </div>',
					'<div class="fancy-picker-option" data-value="16"> urlToSecure </div>',
					'<div class="fancy-picker-option" data-value="17"> urlToUnsecure </div>',
					'<div class="fancy-picker-option" data-value="18"> only_parent </div>',
					'<div class="fancy-picker-option" data-value="19"> parent </div>',
					'<div class="fancy-picker-option" data-value="20"> parent_if_empty </div>',
					'<div class="fancy-picker-option" data-value="21">  </div>',
					'<div class="fancy-picker-option" data-value="22">  </div>'
				];

				arr.map(item => {
					fancyOption.append(item);
				});


				var fancyDataPicker = $(this).parents('tr').find('.fancy-picker-data');
				var selectIf, selectKey;
				var currency;

				// when any attribute is selected, pick the key
				if (price_attributes.includes(current_attribute_value)) {
					// when select any price attribute
					selectIf = 'for_price';
					selectKey = "Price";
				} else if ("" !== current_attribute_value && -1 !== current_attribute_value.indexOf('wf_taxo')) {

					// when select any custom taxonomy
					selectIf = 'for_custom_taxo';
					selectKey = "parent_if_empty";
				} else {

					selectIf = 'for_all';
					selectKey = 'Default';
				}

				// remove selected class from old selected option
				$(fancyOption).find('.fancy-picker-option').removeClass('selected');


				// when value dropdown is selected as price or any custom taxonomy
				if (selectIf === 'for_custom_taxo' || selectIf === 'for_price') {
					// update "Option Type" when select key matches
					$(fancyOption).find('.fancy-picker-option').each(function () {
						if (selectKey === $(this).text().trim()) {
							fancyDataPicker.html(`<span>${selectKey}</span>`);
							outputSelect.find("option").text(selectKey);
							outputSelect.find("option").val($(this).data("value"));
							$(this).addClass('selected');

						}
					});

					if ("for_price" === selectIf) {
						var feedCurrency = $('#feedCurrency').val();
						if ("undefined" !== typeof feedCurrency && null !== feedCurrency) {
							currency = feedCurrency;
						} else {
							currency = wpf_ajax_obj.woocommerce.currency;
						}

						//for price add currency to the suffix
						$(this).closest("tr").find("td:eq(5) input").val(" " + currency);
					}

				} else {
					fancyOption.each(function () {
						if (selectKey === $(this).text()) {
							$(this).addClass('selected');
							fancyDataPicker.text(selectKey);
							outputSelect.find("option").text(selectKey);
							outputSelect.find("option").val($(this).data("value"));
						}
					});

					$(this).closest("tr").find("td:eq(5) input").val("");
				}

			})
			// bulk delete alert.
			.on('click', '#doaction, #doaction2', function () {
				// noinspection JSUnresolvedVariable
				return confirm(opts.form.del_confirm_multi);
			})
			// Generate Feed Table Row Delete
			.on('change', '.dType', function () {
				let self = $(this),
					type = self.val(),
					row = self.closest('tr');

				if (type === 'pattern') {
					row.find('.value_attribute').hide();
					row.find('.value_pattern').show();
				} else if (type === 'attribute') {
					row.find('.value_attribute').show();
					row.find('.value_pattern').hide();
				} else if (type === 'remove') {
					row.find('.value_attribute').hide();
					row.find('.value_pattern').hide();
				}
			})
			// Generate Feed Form Submit
			.on('click', '.delRow', function (e) {
				e.preventDefault();
				$(this).closest('tr').remove();
				helper.reindex_config_table();
			})
			.on('submit', '#generateFeed', function () {
				// Feed Generating form validation
				$(this).validate();

				if ($(this).valid()) {
					$(".makeFeedResponse")
						.show()
						.html(`<b style="color: darkblue;"><i class="dashicons dashicons-sos wpf_spin"></i> ${opts.form.generate}</b>`); // phpcs:ignore WordPressVIPMinimum.JS.HTMLExecutingFunctions.html, WordPressVIPMinimum.JS.StringConcat.Found
				}
			})
			// Generate Update Feed Form Submit
			.on('submit', '#updatefeed', function (e, data) {
				// Feed Generating form validation
				$(this).validate();

				if ($(this).valid()) {
					$(".makeFeedResponse")
						.show()
						.html(`<b style="color: darkblue;"><i class="dashicons dashicons-sos wpf_spin"></i> ${data && data.save ? opts.form.save : opts.form.generate}</b>`); // phpcs:ignore WordPressVIPMinimum.JS.HTMLExecutingFunctions.html, WordPressVIPMinimum.JS.StringConcat.Found
				}
			})
			.on('ready woo_feed_config_loaded', function () {

				if ($('#ftpenabled').val() === '0') {
					$('.google-merchant-message').hide('slow');
					$('.woo-feed-ftp .ftpconfig tr:not(.ftpcontroller) input, .woo-feed-ftp .ftpconfig tr:not(.ftpcontroller) select').attr('disabled', 'disabled');
				} else {
					if ($('#provider').val() === 'google') {
						$('.google-merchant-message').show('slow');
					} else {
						$('.google-merchant-message').hide('slow');
					}
					$('.woo-feed-ftp .ftpconfig tr:not(.ftpcontroller) input, .woo-feed-ftp .ftpconfig tr:not(.ftpcontroller) select').removeAttr('disabled');
				}

				$('#ftpenabled').on('change', function () {
					if ($('#ftpenabled').val() === '0') {
						$('.google-merchant-message').hide('slow');
						$('.woo-feed-ftp .ftpconfig tr:not(.ftpcontroller) input, .woo-feed-ftp .ftpconfig tr:not(.ftpcontroller) select').attr('disabled', 'disabled');
					} else {

						$('.woo-feed-ftp .ftpconfig tr:not(.ftpcontroller) input, .woo-feed-ftp .ftpconfig tr:not(.ftpcontroller) select').removeAttr('disabled');

						// Google merchant specific message
						if ($('#provider').val() === 'google') {
							$('.google-merchant-message').show('slow');
						} else {
							$('.google-merchant-message').hide('slow');
						}

					}
				});
			})
			.on('change', '.ftporsftp', function () {
				let server = $(this).val(),
					status = $('.ssh2_status');

				if (server === 'sftp') {
					// noinspection JSUnresolvedVariable
					status.show().css('color', 'dodgerblue').text(opts.form.sftp_checking);
					wpAjax.post('get_ssh2_status', {
						_ajax_nonce: opts.nonce,
						server: server
					}).then(function (response) {
						if (response === 'exists') {
							// noinspection JSUnresolvedVariable
							status.css('color', '#2CC185').text(opts.form.sftp_available);
							setTimeout(function () {
								status.hide();
							}, 1500);
						} else {
							// noinspection JSUnresolvedVariable
							status.show().css('color', 'red').text(opts.form.sftp_warning);
						}
					}).fail(function (e) {
						status.hide();
						helper.ajax_fail(e);
					});
				} else {
					status.hide();
				}
			})
			.on('click', '[name="save_feed_config"]', function (e) {
				e.preventDefault();
				$('#updatefeed').trigger('submit', {
					save: true
				});
			})
			// .on('change', '#provider', function (event) {
			//     event.preventDefault();
			//
			//     let merchant = $(this).val(),
			//         templateName = $(this).find(':selected').text(),
			//         name = $('#filename').val(),
			//         feedType = $("#feedType"),
			//         feedForm = $("#providerPage"),
			//         merchantInfo = $('#feed_merchant_info'); // set loading..
			//
			//     // noinspection JSUnresolvedVariable
			//     feedForm.html('<h3><span style="float:none;margin: -3px 0 0;" class="spinner is-active"></span> ' + opts.form.loading_tmpl + '</h3>'); // phpcs:ignore WordPressVIPMinimum.JS.HTMLExecutingFunctions.html, WordPressVIPMinimum.JS.StringConcat.Found
			//
			//     merchantInfo.find('.spinner').addClass('is-active');
			//     feedType.disabled(!0); // disable dropdown
			//
			//     feedType.parent().find('.spinner').addClass('is-active');
			//     merchantInfo.find('.merchant-info-section .data').html(''); // remove previous data
			//
			//
			// 	//:Merchant info now from rest API.
			//
			//     // Get Merchant info for selected Provider/Merchant
			//     if (merchantInfoCache.hasOwnProperty(merchant)) {
			//         feedEditor.renderMerchantInfo(merchantInfo, feedType, merchantInfoCache[merchant]);
			//
			//         /**
			//          * Triggers after merchant configuration is fetched & loaded
			//          * */
			//         $(document).trigger('woo_feed_config_loaded');
			//
			//     } else {
			//         wpAjax.send('woo_feed_get_merchant_info', {
			//             type: 'GET',
			//             data: {
			//                 nonce: opts.nonce,
			//                 provider: merchant,
			//                 templateName: templateName
			//             }
			//         }).then(function (r) {
			//             merchantInfoCache[merchant] = r;
			//             feedEditor.renderMerchantInfo(merchantInfo, feedType, r);
			//         }).then(function(){
			//
			//             /**
			//              * Triggers after merchant configuration is fetched & loaded
			//              * */
			//             $(document).trigger('woo_feed_config_loaded');
			//
			//         }).fail(helper.ajax_fail);
			//     } // Get FeedForm For Selected Provider/Merchant
			//
			// 	// Merchant info now from rest API.
			//     if (merchantTemplateCache.hasOwnProperty(merchant)) {
			//         feedEditor.renderMerchantTemplate(feedForm, merchantTemplateCache[merchant]);
			//
			//         /**
			//          * Triggers after merchant configuration is fetched & loaded
			//          * */
			//         $(document).trigger('woo_feed_config_loaded');
			//
			//     } else {
			//         wpAjax.post('get_feed_merchant', {
			//             _ajax_nonce: opts.nonce,
			//             merchant: merchant
			//         }).then(function (r) {
			//             merchantTemplateCache[merchant] = r;
			//             feedEditor.renderMerchantTemplate(feedForm, r);
			//             $('#utm_source').val(templateName);
			//             $('#utm_campaign').val(name);
			//             //when merchant is bing, change delimiter and enclosure
			//             if( 'bing' === merchant ) {
			//                 //delimiter value
			//                 $("#delimiter option").removeAttr('selected');
			//                 $("#delimiter option[value=\"tab\"]").attr('selected', 'selected');
			//
			//                 //enclosure value
			//                 $("#enclosure option").removeAttr('selected');
			//                 $("#enclosure option[value=\" \"]").attr('selected', 'selected');
			//             }
			//
			//             //reviewer options hide
			//             if( 'google_product_review' !== merchant) {
			//                 $('.wf_attributes option[value="reviewer_name"]').hide();
			//             }
			//         }).then(function(){
			//
			//             /**
			//              * Triggers after merchant configuration is fetched & loaded
			//              * */
			//             $(document).trigger('woo_feed_config_loaded');
			//
			//         }).fail(helper.ajax_fail);
			//     }
			// })
			// Feed Active and Inactive status change via ajax
			.on('change', '.woo_feed_status_input', function () {
				let self = $(this);
				wpAjax.post('update_feed_status', {
					_ajax_nonce: opts.nonce,
					feedName: self.val(),
					status: self[0].checked ? 1 : 0
				});
			});
		// event with trigger
		$(document)
			.on('change', '[name="is_outOfStock"], [name="product_visibility"]', function () {
				let outOfStockVisibilityRow = $('.out-of-stock-visibility');
				if ($('[name="is_outOfStock"]:checked').val() === 'n' && $('[name="product_visibility"]:checked').val() === '1') {
					outOfStockVisibilityRow.show();
				} else {
					outOfStockVisibilityRow.hide();
				}
			})
			.on('change', '.attr_type', function () {
				// Attribute type selection
				let self = $(this),
					type = self.val(),
					row = self.closest('tr');

				if (type === 'pattern') {
					row.find('.wf_attr').hide();
					row.find('.wf_attr').val('');
					row.find('.wf_default').show();
				} else {
					row.find('.wf_attr').show();
					row.find('.wf_default').hide();
					row.find('.wf_default').val('');
				}
			})
			.on('change', '.wf_mattributes, .attr_type', function () {
				let row = $(this).closest('tr'),
					attribute = row.find('.wf_mattributes'),
					type = row.find('.attr_type'),
					valueColumn = row.find('td:eq(4)'),
					provider = $('#provider').val();

				// noinspection JSUnresolvedVariable
				if (opts.form.google_category.hasOwnProperty(attribute.val()) && type.val() === 'pattern' && helper.in_array(provider, opts.form.google_category[attribute.val()])) {
					if (valueColumn.find('select.selectize').length === 0) {
						valueColumn.find('input.wf_default').remove();
						valueColumn.append('<span class="wf_default wf_attributes"><select name="default[]" class="selectize"></select></span>');
						// noinspection JSUnresolvedVariable
						valueColumn.append(`<span style="font-size:x-small;"><a style="color: red" href="https://webappick.com/docs/woo-feed/feed-configuration/how-to-map-store-category-with-merchant-category/" target="_blank">${opts.learn_more}</a></span>`);

						if (!googleCategories || !facebookCategories) {
							valueColumn.append('<span class="spinner is-active" style="margin: 0;"></span>');
						}

						let select = valueColumn.find('.wf_attributes select');
						// noinspection JSUnresolvedVariable
						helper.selectize(select, {
							preload: true,
							placeholder: opts.form.select_category,
							load: function load(query, cb) {
								if (!googleCategories) {
									wpAjax.send('get_google_categories', {
										type: 'GET',
										data: {
											_ajax_nonce: opts.nonce,
											action: "get_google_categories",
											provider: provider
										}
									}).then(function (r) {
										googleCategories = r;
										cb(googleCategories);
										valueColumn.find('.spinner').remove();
									}).fail(helper.ajax_fail);
								} else {
									cb(googleCategories);
								}

								//for facebook product category merchant attribute
								if (!facebookCategories && "facebook" === provider && "fb_product_category" === attribute.val()) {
									wpAjax.send('get_facebook_categories', {
										type: 'GET',
										data: {
											_ajax_nonce: opts.nonce,
											action: "get_facebook_categories",
											provider: provider
										}
									}).then(function (r) {
										facebookCategories = r;
										cb(facebookCategories);
										valueColumn.find('.spinner').remove();
									}).fail(helper.ajax_fail);
								} else {
									cb(facebookCategories);
								}
							}
						});
					}
				} else {
					if (attribute.val() !== 'current_category' && valueColumn.find('input.wf_default').length === 0) {
						valueColumn.find('span').remove();
						valueColumn.append('<input autocomplete="off" class="wf_default wf_attributes"  type="text" name="default[]" value="">');

						if (type.val() !== 'pattern') {
							valueColumn.find('input.wf_default').hide();
						}
					}
				}
			})
			.on('change', '#feedType,#provider', function (e) {
				let type = $('#feedType').val(),
					provider = $('#provider').val(),
					itemWrapper = $('.itemWrapper'),
					wf_csv_txt = $('.wf_csvtxt');

				// noinspection JSUnresolvedVariable
				if (type !== '' && helper.in_array(provider, opts.form.item_wrapper_hidden)) {
					itemWrapper.hide();
				}
				// TODO check with new UI
				//when feed type is changed
				if ('feedType' === e.target.id) {
					if (type === 'csv' || type === 'txt' || type === 'tsv') {
						wf_csv_txt.show();
						itemWrapper.hide();

						if ('tsv' === type) {
							$('#delimiter option:last').prop("selected", "selected");
						} else if ('csv' === type) {
							$('#delimiter option:first').prop("selected", "selected");
						}
					} else if ('xml' === type && "custom" === provider) {
						itemWrapper.show();
						wf_csv_txt.hide();
					} else if ('json' === type) {
						wf_csv_txt.hide();
					}
				}

				// when template is not custom template 1 hide the item wrapper
				if ("custom" !== provider) {
					itemWrapper.hide();
				}

			})
			.trigger('change');


		$(document)
			.on('click', '.woofeed-custom-fields [id*="-switcher"]', function () {
				$(this).closest('td').find('.switch-loader').show();
				let field = $(this).attr('id').split('-')[0],
					isTaxonomy = $(this).data('taxonomy'),
					status = $(this).prop('checked');
				new WooFeedCustomFields(field, status, isTaxonomy);

			})

			// Remove sticky cart on feature page
			.ready(function () {
				var s = $(".wapk-feed-buy-now-container");
				$(window).scroll(function () {
					var windowpos = $(window).scrollTop();
					if (windowpos <= 5000) {
						s.addClass("fixed");
					} else {
						s.removeClass("fixed");
					}
				});
			});
	});

	$(document)
		.on('click', '#woo-feed-debug-log-download', function (e) {
			e.preventDefault();
			$("<a />", {
				download: new Date() + ".log",
				href: URL.createObjectURL(
					new Blob([$("#woo-feed-debug-log").val()], {
						type: "text/plain"
					})),
			}).appendTo("body")[0].click();
		})
		// TODO working on new UI
		.on('keyup', '#filename', function () {
			var name = $('#filename').val();
			$('#utm_campaign').val(name);
		})

		.on('click', '.wf-tab-name', function (e) {
			$('.wf-tab-name.activate').removeClass('activate');
			$(this).addClass('activate');

			let for_attr = $(this).attr('for');
			$('.wf_tabs li.active').removeClass('active');
			$('.wf_tabs .wf-tab-content#' + for_attr).parent().addClass('active');
		})

		.on('change', '#category-mapping-form #providers', function () {
			var provider = $(this).find(':selected').val(),
				googleMap = ['google', 'facebook', 'pinterest', 'bing', 'bing_local_inventory', 'snapchat', 'tiktok'];

			//add snipper element
			$('.woo-feed-category-mapping-config-table').prepend('<div class="woo-feed-cat-map-spinner"><h3><span style="float:none;margin: -3px 0 0;" class="spinner is-active"></span> Loading Mapping...</h3></div>');

			if (googleMap.indexOf(provider) !== -1) {
				$('input[id*="cat_mapping_"]').css('display', 'none');
				$('.wf_default.wf_attributes').css('display', 'block').css('width', '100%');
				var cat_init = new WooFeedMapCategories();
				if ("facebook" === provider) {
					cat_init.wooFeed_get_facebook_categories();
				} else {
					cat_init.wooFeed_get_google_categories();
				}
			} else {
				$('input[id*="cat_mapping_"]').css('display', 'block');
				$('.wf_default.wf_attributes').css('display', 'none').css('width', '100%');
				$('.woo-feed-category-mapping-config-table .woo-feed-cat-map-spinner').css('display', 'none');
			}
		})

		// Copy parent category ids to child categories.
		.on('click', 'span[id*="cat-map-"]', function (e) {
			e.preventDefault();
			var providerName = $('#category-mapping-form #providers').val(),
				googleMap = ['google', 'facebook', 'pinterest', 'bing', 'bing_local_inventory', 'snapchat', 'tiktok'],
				catId = $(this).attr('id').replace(/[^\d.]/g, ''),
				groupId = 'group-child-' + catId;

			if (googleMap.indexOf(providerName) !== -1) {
				var catField = $(this).parents('tr').find('.selectized').val();

				if (catField) {
					$('.' + groupId).parents('tr').find('select').each(function (i, v) {
						$(v).data('selectize').setValue(catField);
					});
				}

			} else {
				var value = $('#cat_mapping_' + catId).val();
				$('.' + groupId).parents('tr').find('input').val(value);
			}

		});

	$(document).ready(function () {
		var provider = $("#providers").val(),
			cat_init = new WooFeedMapCategories(),
			url = new URL(window.location.href),
			cat_mapping_action = url.searchParams.get("action");

		if ("edit-mapping" === cat_mapping_action) {
			$('.woo-feed-category-mapping-config-table').prepend('<div class="woo-feed-cat-map-spinner"><h3><span style="float:none;margin: -3px 0 0;" class="spinner is-active"></span> Loading Mapping...</h3></div>');

			if ("facebook" === provider) {
				cat_init.wooFeed_get_facebook_categories();
			} else {
				cat_init.wooFeed_get_google_categories();
			}

		}
	});

	/*
	* Issue fix for feed edit page unsupported `Feed Type` not disabling
	* // TODO need to move react
	* @since 4.4.22
	* */
	$(document).on("feed_editor_init", function () {

		let types = $('.merchant-info-section.feed_file_type .data').html().split(",").map(function (t) {
			return t.trim().toLowerCase();
		});

		let feedType = $("#feedType");

		if (types.length) {
			feedType.find('option').each(function () {
				let opt = $(this);
				opt.val() && !helper.in_array(opt.val(), types) ? opt.disabled(!0) : opt.disabled(!1);
			});
			if (types.length === 1) feedType.find('option[value="' + types[0] + '"]').attr('selected', 'selected');
		} else feedType.find('option').disabled(!1);

	});

	/**
	 * Automatically adding postfix for corresponding attribute selection.
	 * // TODO nee to match with new UI
	 * @since 4.4.32
	 */
	$(document).on('ready', function () {

		function update_postfix() {
			var attribute_list = {
				'price': wpf_ajax_obj.woocommerce.currency,
				'current_price': wpf_ajax_obj.woocommerce.currency,
				'sale_price': wpf_ajax_obj.woocommerce.currency,
				'price_with_tax': wpf_ajax_obj.woocommerce.currency,
				'current_price_with_tax': wpf_ajax_obj.woocommerce.currency,
				'sale_price_with_tax': wpf_ajax_obj.woocommerce.currency,
				'weight': wpf_ajax_obj.woocommerce.weight,
				'weight_unit': wpf_ajax_obj.woocommerce.weight,
				'height': wpf_ajax_obj.woocommerce.dimension,
				'length': wpf_ajax_obj.woocommerce.dimension,
				'width': wpf_ajax_obj.woocommerce.dimension
			};

			if (typeof wpf_ajax_obj.feed_rules !== 'undefined' && wpf_ajax_obj.feed_rules !== null) {
				var feed_rules = wpf_ajax_obj.feed_rules;
				var current_feed_currency = feed_rules.feedCurrency;
			}

			//get current action name of the page
			var queryString = window.location.search;
			var urlParams = new URLSearchParams(queryString);
			var action = urlParams.get('action');

			$('.wf_attr.wf_attributes').each(function (key, value) {

				var attribute_value = $(value).val();
				var current_Value = $('input[name^="suffix"]').eq(parseInt(key)).val();

				if (-1 !== $.inArray(current_Value, [
					' ' + wpf_ajax_obj.woocommerce.currency,
					' ' + wpf_ajax_obj.woocommerce.weight,
					' ' + wpf_ajax_obj.woocommerce.dimension
				])) {
					$('input[name^="suffix"]').eq(parseInt(key)).val('');
				}

				$.each(attribute_list, function (_key, _value) {
					if (attribute_value === _key) {
						_value = null !== current_feed_currency && "undefined" !== typeof current_feed_currency ? current_feed_currency : _value;

						if ("undefined" !== action && "edit-feed" !== action) {
							$('input[name^="suffix"]').eq(parseInt(key)).val(' ' + _value);
						} else {
							$('input[name^="suffix"]').eq(parseInt(key)).val(current_Value);
						}

					}
				});
			});

			$(document).trigger('feedEditor.after.free.postfix.update');
		}

		$(document).on('feedEditor.init', function () {
			let provider = $(this).find(':selected').val(),
				googleMap = ['google', 'facebook', 'pinterest', 'bing', 'bing_local_inventory', 'snapchat', 'tiktok'];

			if (googleMap.indexOf(provider) !== -1) {
				update_postfix();
			}
		});

		// If file extension is not .wpf then don't select the file.
		$('#wpf_import_file').on('change', function (e) {
			let fakePath = e.target.value;
			let importFileExtension = fakePath.split("\\").pop();
			importFileExtension = importFileExtension.split('.').pop();
			if ('wpf' !== importFileExtension) {
				e.target.value = '';
				alert('Please select a .wpf extension file');
			}
		});


		// Init codemirror if template is special template.
		wp.hooks.addAction('init_codemirror', 'codemirror', function () {
			let el = document.getElementById('editor_react');
			if (el) {
				const editor = CodeMirror.fromTextArea(el, {
					lineNumbers: true,
					mode: "xml",
					matchBrackets: true
				});
				editor.setSize(null, 620);
				editor.on('change', function (cm, cObj) {
					window.localStorage.setItem('ctx_codemirror', cm.getValue());
				});
			}
		});

	});

})(jQuery, window, document, wp.ajax, wpf_ajax_obj);
