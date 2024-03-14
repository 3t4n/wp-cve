/**
 * The script has a few parts:
 *
 * 1) State management - state is updated on every change in the form.
 * 2) DOM manipulation - DOM is updated on every change in the state in render function.
 * 3) Event listeners - event listeners are attached to DOM elements in attachEventListeners function and values are passed to the state.
 *
 * So the flow is:
 * Event listener is triggered => Value is passed to the state => DOM is updated in render function.
 */

window.jQuery(document).ready(function ($) {

	/**
	 * Define default wpifyWooIcDic if not defined
	 *
	 * @type {*|{optionalText: string, changePlaceholder: null, requireVatFields: null, requireCompany: null}}
	 */
	window.wpifyWooIcDic = window.wpifyWooIcDic || {
		changePlaceholder: null,
		requireCompany: null,
		moveCompany: null,
		requireVatFields: null,
		optionalText: '',
		restUrl: null,
		position: '',
	};

	/**
	 * DOM element mockup
	 *
	 * @type {{style: {display: string}, placeholder: string, value: string, addEventListener: (function(): *)}}
	 */
	const blankDom = {
		addEventListener: () => null,
		style: { display: 'none' },
		value: '',
		placeholder: '',
		classList: {
			contains: () => false,
		},
		disabled: false,
	};

	/**
	 * DOM elements accessor. If element is not found, return blankDom.
	 */
	const dom = {
		companyDetails: () => document.querySelector('#company_details') || blankDom,
		company: () => document.querySelector('#billing_company') || blankDom,
		companyField: () => document.querySelector('#billing_company_field') || blankDom,
		ic: () => document.querySelector('#billing_ic') || blankDom,
		aresIn: () => document.querySelector('#ares_in') || blankDom,
		icField: () => document.querySelector('#billing_ic_field') || blankDom,
		dic: () => document.querySelector('#billing_dic') || blankDom,
		dicField: () => document.querySelector('#billing_dic_field') || blankDom,
		icDph: () => document.querySelector('#billing_dic_dph') || blankDom,
		icDphField: () => document.querySelector('#billing_dic_dph_field') || blankDom,
		autofill: () => document.querySelector('#wpify-woo-ares-autofill') || blankDom,
		aresResult: () => document.querySelector('#wpify-woo-icdic__ares-result') || blankDom,
		country: () => document.querySelector('#billing_country') || blankDom,
		submit: () => document.querySelector('#place_order') || blankDom,
	};

	/**
	 * State management
	 *
	 * @type {{set(*): void, data: {country: string, companyDetails: boolean, icDph: string, ic: string, company: string, dic: string}, subscribe(*): void, subscribers: *[], get(*): *, sync(): void}}
	 */
	const state = {
		subscribers: [],
		data: {
			companyDetails: false,
			ic: '',
			dic: '',
			icDph: '',
			country: '',
			company: '',
			aresResult: '',
			aresLoading: false,
			viesResult: '',
			viesLoading: false,
			disableSubmit: false,
		},
		get (key) {
			return this.data[key];
		},
		set (nextState) {
			Object.keys(nextState).forEach(key => {
				this.data[key] = nextState[key];
			});

			this.subscribers.forEach(callback => callback(this.data));
		},
		subscribe (callback) {
			this.subscribers.push(callback);
		},
		sync () {
			const data = {
				companyDetails: dom.companyDetails().checked,
				ic: dom.ic().value,
				dic: dom.dic().value,
				icDph: dom.icDph().value,
				country: dom.country().value,
				company: dom.company().value,
			};

			if (data.ic || data.dic || typeof data.companyDetails === 'undefined') {
				data.companyDetails = true;
			}

			if (!data.companyDetails) {
				data.ic = '';
				data.dic = '';
				data.icDph = '';
				data.company = '';
			}

			this.set(data);
		}
	};

	/**
	 * Subscribe render function to state changes.
	 */
	state.subscribe(render);

	/**
	 * Render function that updates form based on given state.
	 *
	 * @param state
	 */
	function render (state) {
		if (typeof state.companyDetails !== 'undefined') {
			if (state.companyDetails) {
				dom.companyField().style.display = 'block';
				dom.icField().style.display = 'block';
				dom.dicField().style.display = 'block';
			} else {
				if (window.wpifyWooIcDic.moveCompany) {
					dom.companyField().style.display = 'none';
				}
				dom.icField().style.display = 'none';
				dom.dicField().style.display = 'none';
			}
		}

		if (
			(window.wpifyWooIcDic.position !== 'before_customer_details' && state.companyDetails && state.country === 'CZ') ||
			(window.wpifyWooIcDic.position === 'before_customer_details' && state.country === 'CZ')
		) {
			dom.autofill().style.display = 'block';
		} else {
			dom.autofill().style.display = 'none';
		}

		if (state.companyDetails && state.country === 'SK') {
			dom.icDphField().style.display = 'block';

		} else {
			dom.icDphField().style.display = 'none';
		}

		if (window.wpifyWooIcDic.changePlaceholder) {
			dom.ic().placeholder = '123456789';

			if (state.companyDetails && state.country === 'SK') {
				dom.dic().placeholder = '123456789';
				dom.icDph().placeholder = state.country + '1234567890';
			} else if (state.companyDetails) {
				dom.dic().placeholder = state.country + '123456789';
				dom.icDph().placeholder = '';
			}
		}

		if (window.wpifyWooIcDic.requireCompany) {
			if (state.companyDetails) {
				setAsRequired(dom.companyField());
			} else {
				setAsOptional(dom.companyField());
			}
		}

		if (
			state.companyDetails &&
			(
				window.wpifyWooIcDic.requireVatFields === 'if_checkbox' ||
				(window.wpifyWooIcDic.requireVatFields === 'if_company' && state.company !== '')
			)
		) {
			setAsRequired(dom.icField());
		} else {
			setAsOptional(dom.icField());
		}

		dom.companyDetails().checked = !!state.companyDetails;

		document.querySelectorAll('.wpify-woo__ic-error').forEach(el => el.remove());

		if (state.aresLoading) {
			dom.ic().parentNode.classList.add('loading');
		} else {
			dom.ic().parentNode.classList.remove('loading');
		}

		if (state.aresResult && state.aresLoading === false) {
			const el = document.createElement('div');
			el.innerHTML = state.aresResult;
			el.classList.add('wpify-woo__ic-error');
			dom.ic().parentNode.insertBefore(el, dom.ic().nextSibling);
		}

		const viesInput = state.country === 'SK' ? dom.icDph() : dom.dic();

		if (state.viesLoading) {
			viesInput.parentNode.classList.add('loading');
		} else {
			viesInput.parentNode.classList.remove('loading');
		}

		if (state.viesResult && state.viesLoading === false) {
			const el = document.createElement('div');
			el.innerHTML = state.viesResult;
			el.classList.add('wpify-woo__ic-error');
			viesInput.parentNode.insertBefore(el, viesInput.nextSibling);
		}

		dom.submit().disabled = !!state.disableSubmit;
	}

	/**
	 * Set field as required.
	 *
	 * @param field
	 */
	function setAsRequired (field) {
		const span = field.querySelector('label span');

		field.classList.add('validate-required');

		if (span) {
			span.className = 'required';
			span.innerText = '*';
		}
	}

	/**
	 * Set field as optional.
	 *
	 * @param field
	 */
	function setAsOptional (field) {
		const span = field.querySelector('label span');

		field.classList.remove('validate-required');

		if (span) {
			span.className = 'optional';
			span.innerText = wpifyWooIcDic.optionalText;
		}
	}

	function fetchJson (url, options) {
		return new Promise((resolve, reject) => {
			fetch(url, options)
				.then(response => {
					if (response.ok) {
						response.json().then(resolve);
					} else {
						response.json().then(json => reject(json.message));
					}
				})
				.catch(reject);
		});
	}

	function autofillAres () {
		if (window.wpifyWooIcDic.restUrl && !state.get('aresLoading')) {
			const ino = normalizeIc(dom.ic().value || dom.aresIn().value);

			state.set({ aresLoading: true });

			fetchJson(window.wpifyWooIcDic.restUrl + '/icdic?in=' + ino)
				.then(({ details = {} }) => {
					Object.keys(details).forEach(function (key) {
						const element = document.getElementById(key);
						element.value = details[key];

						if (key !== 'billing_ic' && element) {
							$(element).trigger('change');
						}
					});

					state.set({ aresResult: '' });
					const evt = new CustomEvent("wpify_woo_ic_dic_ares_autofilled", {
						detail: {
							details: details,
						}
					});
					window.dispatchEvent(evt);
				})
				.catch(error => {
					state.set({ aresResult: error });
				})
				.finally(() => {
					state.set({ aresLoading: false, companyDetails: true });
				});
		}
	}

	function normalizeDic (dic) {
		dic = dic.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();

		if (!dic.match(/^[A-Z]{2}/)) {
			return dom.country().value + dic;
		}

		return dic;
	}

	function normalizeIc (ic) {
		return ic.replace(/\D/g, '');
	}

	let dicTimeout = null;

	function validateDic () {
		if (window.wpifyWooIcDic.restUrl && !state.get('viesLoading')) {
			window.clearTimeout(dicTimeout);

			const dic = state.get('country') === 'SK' ? normalizeDic(state.get('icDph')) : normalizeDic(state.get('dic'));

			if (state.get('viesLastChecked') !== dic) {
				state.set({ viesResult: '', viesLoading: true, disableSubmit: true });

				fetchJson(window.wpifyWooIcDic.restUrl + '/icdic-vies?in=' + dic)
					.then(() => {
						if (state.get('country') === 'SK') {
							dom.icDph().value = dic;
						} else {
							dom.dic().value = dic;
						}
					})
					.catch(error => {
						state.set({ viesResult: error });
					})
					.finally(() => {
						$(document.body).trigger('update_checkout');
						state.set({ viesLoading: false, disableSubmit: false, viesLastChecked: dic });
					});
			}
		}
	}

	function preventSubmitOnEnter (event) {
		if (event.key === 'Enter' && state.get('disableSubmit') === true) {
			event.preventDefault();
		}
	}

	function preventSubmitOnClick (event) {
		if (state.get('disableSubmit') === true) {
			event.preventDefault();
		}
	}

	/**
	 * Sync state with initial values.
	 */
	state.sync();

	/**
	 * Listen to changes in checkout form.
	 */
	$(document.body).on('change', 'input[name=company_details]', e => {
		const nextData = { companyDetails: e.target.checked };

		if (!nextData.companyDetails) {
			nextData.ic = '';
			nextData.dic = '';
			nextData.icDph = '';
			nextData.company = '';

			dom.ic().value = '';
			dom.dic().value = '';
			dom.icDph().value = '';
			dom.company().value = '';

			$(document.body).trigger('update_checkout');
		}

		state.set(nextData);
	});

	$(document.body).on('change', 'input[name=billing_company]', e => {
		state.set({ company: e.target.value });
	});

	$(document.body).on('change', 'select[name=billing_country]', e => {
		state.set({ country: e.target.value });
	});

	$(document.body).on('change', 'input[name=billing_ic]', e => {
		state.set({ ic: e.target.value });

		if (dom.icField().classList.contains('wpify-woo-ic--validate')) {
			if (state.get('country') === 'CZ' && e.target.value.length > 0) {
				autofillAres();
			} else {
				state.set({ aresResult: '' });
			}
		}
	});

	$(document.body).on('keyup change', 'input[name=billing_ic]', e => {
		dom.aresIn().value = normalizeIc(e.target.value);
	});

	$(document.body).on('keyup change', '#ares_in', e => {
		dom.ic().value = normalizeIc(e.target.value);
	});

	$(document.body).on('click', '#wpify-woo-icdic__ares-submit', autofillAres);

	$(document.body).on('change', 'input[name=billing_dic]', e => {
		state.set({ dic: e.target.value });

		if (dom.dicField().classList.contains('wpify-woo-vies--validate') && state.get('country') !== 'SK' && e.target.value.length > 0) {
			validateDic();
		}
	});

	$(document.body).on('change', 'input[name=billing_dic_dph]', e => {
		state.set({ icDph: e.target.value });

		if (dom.icDph().classList.contains('wpify-woo-vies--validate') && state.get('country') === 'SK' && e.target.value.length > 0) {
			validateDic();
		}
	});

	$(document.body).on('keyup', 'input[name=billing_dic]', e => {
		if (dom.dicField().classList.contains('wpify-woo-vies--validate') && state.get('country') !== 'SK' && e.target.value.length > 0) {
			window.clearTimeout(dicTimeout);
			state.set({ disableSubmit: true, dic: e.target.value });
			window.setTimeout(validateDic, 2000);
		}
	});

	$(document.body).on('keyup', 'input[name=billing_dic_dph]', e => {
		if (dom.dicField().classList.contains('wpify-woo-vies--validate') && state.get('country') === 'SK' && e.target.value.length > 0) {
			window.clearTimeout(dicTimeout);
			state.set({ disableSubmit: true, icDph: e.target.value });
			window.setTimeout(validateDic, 2000);
		}
	});

	$(document.body).on('click', '#wpify-woo-icdic__ares-autofill-button', function (e) {
		e.preventDefault();
		document.querySelector('.wpify-woo-icdic__ares-autofill').style.display = 'block';
	});

	$('form[name=checkout]')
		.on('keydown', 'input,select,button', preventSubmitOnEnter)
		.on('click', 'input[type=button],button', preventSubmitOnClick);

	$(document.body).on('updated_checkout', function () {
		render(state.data);
	});
});
