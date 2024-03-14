// Base64 script - helps encoding unicode characters
(function (global, factory) { typeof exports === "object" && typeof module !== "undefined" ? module.exports = factory(global) : typeof define === "function" && define.amd ? define(factory) : factory(global) })(typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : typeof global !== "undefined" ? global : this, function (global) { "use strict"; global = global || {}; var _Base64 = global.Base64; var version = "2.5.2"; var buffer; if (typeof module !== "undefined" && module.exports) { try { buffer = eval("require('buffer').Buffer") } catch (err) { buffer = undefined } } var b64chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/"; var b64tab = function (bin) { var t = {}; for (var i = 0, l = bin.length; i < l; i++)t[bin.charAt(i)] = i; return t }(b64chars); var fromCharCode = String.fromCharCode; var cb_utob = function (c) { if (c.length < 2) { var cc = c.charCodeAt(0); return cc < 128 ? c : cc < 2048 ? fromCharCode(192 | cc >>> 6) + fromCharCode(128 | cc & 63) : fromCharCode(224 | cc >>> 12 & 15) + fromCharCode(128 | cc >>> 6 & 63) + fromCharCode(128 | cc & 63) } else { var cc = 65536 + (c.charCodeAt(0) - 55296) * 1024 + (c.charCodeAt(1) - 56320); return fromCharCode(240 | cc >>> 18 & 7) + fromCharCode(128 | cc >>> 12 & 63) + fromCharCode(128 | cc >>> 6 & 63) + fromCharCode(128 | cc & 63) } }; var re_utob = /[\uD800-\uDBFF][\uDC00-\uDFFFF]|[^\x00-\x7F]/g; var utob = function (u) { return u.replace(re_utob, cb_utob) }; var cb_encode = function (ccc) { var padlen = [0, 2, 1][ccc.length % 3], ord = ccc.charCodeAt(0) << 16 | (ccc.length > 1 ? ccc.charCodeAt(1) : 0) << 8 | (ccc.length > 2 ? ccc.charCodeAt(2) : 0), chars = [b64chars.charAt(ord >>> 18), b64chars.charAt(ord >>> 12 & 63), padlen >= 2 ? "=" : b64chars.charAt(ord >>> 6 & 63), padlen >= 1 ? "=" : b64chars.charAt(ord & 63)]; return chars.join("") }; var btoa = global.btoa ? function (b) { return global.btoa(b) } : function (b) { return b.replace(/[\s\S]{1,3}/g, cb_encode) }; var _encode = function (u) { var isUint8Array = Object.prototype.toString.call(u) === "[object Uint8Array]"; return isUint8Array ? u.toString("base64") : btoa(utob(String(u))) }; var encode = function (u, urisafe) { return !urisafe ? _encode(u) : _encode(String(u)).replace(/[+\/]/g, function (m0) { return m0 == "+" ? "-" : "_" }).replace(/=/g, "") }; var encodeURI = function (u) { return encode(u, true) }; var re_btou = /[\xC0-\xDF][\x80-\xBF]|[\xE0-\xEF][\x80-\xBF]{2}|[\xF0-\xF7][\x80-\xBF]{3}/g; var cb_btou = function (cccc) { switch (cccc.length) { case 4: var cp = (7 & cccc.charCodeAt(0)) << 18 | (63 & cccc.charCodeAt(1)) << 12 | (63 & cccc.charCodeAt(2)) << 6 | 63 & cccc.charCodeAt(3), offset = cp - 65536; return fromCharCode((offset >>> 10) + 55296) + fromCharCode((offset & 1023) + 56320); case 3: return fromCharCode((15 & cccc.charCodeAt(0)) << 12 | (63 & cccc.charCodeAt(1)) << 6 | 63 & cccc.charCodeAt(2)); default: return fromCharCode((31 & cccc.charCodeAt(0)) << 6 | 63 & cccc.charCodeAt(1)) } }; var btou = function (b) { return b.replace(re_btou, cb_btou) }; var cb_decode = function (cccc) { var len = cccc.length, padlen = len % 4, n = (len > 0 ? b64tab[cccc.charAt(0)] << 18 : 0) | (len > 1 ? b64tab[cccc.charAt(1)] << 12 : 0) | (len > 2 ? b64tab[cccc.charAt(2)] << 6 : 0) | (len > 3 ? b64tab[cccc.charAt(3)] : 0), chars = [fromCharCode(n >>> 16), fromCharCode(n >>> 8 & 255), fromCharCode(n & 255)]; chars.length -= [0, 0, 2, 1][padlen]; return chars.join("") }; var _atob = global.atob ? function (a) { return global.atob(a) } : function (a) { return a.replace(/\S{1,4}/g, cb_decode) }; var atob = function (a) { return _atob(String(a).replace(/[^A-Za-z0-9\+\/]/g, "")) }; var _decode = buffer ? buffer.from && Uint8Array && buffer.from !== Uint8Array.from ? function (a) { return (a.constructor === buffer.constructor ? a : buffer.from(a, "base64")).toString() } : function (a) { return (a.constructor === buffer.constructor ? a : new buffer(a, "base64")).toString() } : function (a) { return btou(_atob(a)) }; var decode = function (a) { return _decode(String(a).replace(/[-_]/g, function (m0) { return m0 == "-" ? "+" : "/" }).replace(/[^A-Za-z0-9\+\/]/g, "")) }; var noConflict = function () { var Base64 = global.Base64; global.Base64 = _Base64; return Base64 }; global.Base64 = { VERSION: version, atob: atob, btoa: btoa, fromBase64: decode, toBase64: encode, utob: utob, encode: encode, encodeURI: encodeURI, btou: btou, decode: decode, noConflict: noConflict, __buffer__: buffer }; if (typeof Object.defineProperty === "function") { var noEnum = function (v) { return { value: v, enumerable: false, writable: true, configurable: true } }; global.Base64.extendString = function () { Object.defineProperty(String.prototype, "fromBase64", noEnum(function () { return decode(this) })); Object.defineProperty(String.prototype, "toBase64", noEnum(function (urisafe) { return encode(this, urisafe) })); Object.defineProperty(String.prototype, "toBase64URI", noEnum(function () { return encode(this, true) })) } } if (global["Meteor"]) { Base64 = global.Base64 } if (typeof module !== "undefined" && module.exports) { module.exports.Base64 = global.Base64 } else if (typeof define === "function" && define.amd) { define([], function () { return global.Base64 }) } return { Base64: global.Base64 } });


var itemsTotal = []
var elementsTotal = []
var elementsComment = []
var customMath = []
var Sliders = []
var itemsArray = [] //for detail
var scc_rate_label_convertion = null
var mandatoryElements = []

async function initializeScc() {

	jQuery('.calc-wrapper').each((index, element) => {
		let formId = element.getAttribute('id').replace('scc_form_', '');
		if (typeof (window.sccData) == "undefined") {
			window.sccData = []
		}
		if (typeof (sccData[formId]) == "undefined") {
			sccData[formId] = {}
		}

		// parse and store config to data object
		sccData[formId].config = JSON.parse(document.getElementById('scc-config-' + formId).textContent)
		if (sccData[formId].config?.enableStripe) {
			var stripeScript = document.createElement("script");
			stripeScript.src = 'https://js.stripe.com/v3/';
			document.body.appendChild(stripeScript);
			stripeScript.onload = function () {
				window.stripe = Stripe(sccData[formId].config?.stripePubKey);
				console.log('loaded');
			}
		}


		/**
		 * *Handles currency conversion rate
		 */
		//regresar conversion a total agregar en total script
		//if scc_rate_label_convertion is != null show label convertion in total script else hide
		jQuery(function () {
			getCurrency(Object.keys(sccData)[0])
		})
		/**
		 * *handle minimum total 
		 * !it needs to check total script
		 */
		jQuery(function () {
			Object.keys(sccData).forEach(function (calcId, i) {
				getMinimumTotal(calcId)
			})
		})
		/**
		 * *Translates what is in the tanslate column in database
		 * !needs to have charged translate.js library
		 */
		jQuery(function () {
			Object.keys(sccData).forEach(function (calcId) {
				getTranslation_(calcId)
			})
		})

		/**
		 * *Handles mandatory feature of elements
		 */
		jQuery(function () {
			mandatoryElements = mandatory_elements()
		})
	})

	Object.keys(sccData).forEach(function (calcId) {
		let {
			form_id
		} = sccData[calcId].config
		itemsTotal[form_id] = []
		elementsTotal[form_id] = []
		elementsComment[form_id] = []
		customMath[form_id] = []
		Sliders[form_id] = []
		itemsArray[form_id] = []
	})
	return 0;
}

function sccWhenAvailable(name, callback) {
	var interval = 10; // ms
	window.setTimeout(function () {
		if (window[name]) {
			eval(callback)();
		} else {
			sccWhenAvailable(name, callback);
		}
	}, interval)
};

async function initiateQuoteForm(calcId, callbackFn = null) {
	if (!verifiedMandatoryItems(calcId)) {
		return
	}
	let config = sccData[calcId].config;
	var { objectColor } = config;
	var { isCaptchaEnabled, recaptchaSiteKey } = config.captcha;
	var { disableUnitColumn } = config.pdf;

	/**
	 * Apply css
	 */
	var fontName = config.fontConfig.serviceFont;
	let titleColor = config.title.color
	var showFormInDetail = config.showFormInDetail
	let serviceColor = config.service.color
	let objectColors = config.objectColor
	var css = `.df-scc-euiModalHeader__title,
	.df-scc-euiFormLabel.df-scc-euiFormRow__label,
	.modal .df-scc-euiText,
	.modal .df-scc-euiModal,
	.df-scc-euiButtonContent.df-scc-euiButtonEmpty__content,
	.df-scc-euiButton.df-scc-euiButton--primary.df-scc-euiButton--fill {
		font-family : ${fontName};
	}
	.df-scc-euiModalHeader__title{
		color:${titleColor};
	}
	.df-scc-euiFormRow__label{
		color:${serviceColor};
	}
	.df-scc-euiButtonEmpty__text{
		color:${objectColors};
	}
	.df-scc-euiButtonContent.df-scc-euiButton__content{
		background-color:${objectColors};
		border-color:${objectColors};
		border-radius:3px;
	}
	svg{
		transition:fill 120ms ease-in-out;
		fill:currentColor;
	}
	input.df-scc-euiFieldText:focus{
		background-image: linear-gradient(to top, ${objectColor}, ${objectColor} 2px, transparent 2px, transparent 100%);
	}`

	head = document.head || document.getElementsByTagName('head')[0],
		style = document.createElement('style');
	style.setAttribute("id", "scc-quotemodal");

	head.appendChild(style);

	style.type = 'text/css';
	if (style.styleSheet) {
		// This is required for IE8 and below.
		style.styleSheet.cssText = css;
	} else {
		style.appendChild(document.createTextNode(css));
	}
	jQuery('#scc-quotemodal').remove();
	document.head.appendChild(style);

	if (verifiedMandatoryItems(calcId)) {
		document.querySelectorAll( '.scc-mandatory-msg' ).forEach( ( e ) => {
			e.classList.add( 'scc-hidden' );
		} );

		const quoteFormPlaceholder = document.querySelector( '#quote-form-placeholder' );

		quoteFormPlaceholder.addEventListener( 'hidden.bs.modal', function() {
			this.classList.remove( 'show' );
		} );
		let quoteForm = await wp.template("scc-quote-text-field")({ title: sccGetTranslationByKey(calcId, 'Email Quote Form'), calcId, objectColor, isCaptchaEnabled, disableUnitColumn, recaptchaSiteKey, showFormInDetail });
		if (quoteForm) {
			quoteFormPlaceholder.html(quoteForm);
			jQuery('input.comment_box_text', `#scc_form_${calcId}`).filter((i, e) => {
				var checkIfEmail = jQuery(e);
				var emailEvaluation = !(!checkIfEmail || !checkIfEmail.val() || !checkIfEmail.val().includes('@') || !checkIfEmail.val().includes('.') || checkIfEmail.val().includes('#') || checkIfEmail.val().length < 4)
				emailEvaluation && quoteFormPlaceholder.find('input[type="email"]').val(checkIfEmail.val());
			})
			if (callbackFn) {
				quoteFormPlaceholder.find('form').data('callback', { cb: callbackFn });
			}
			quoteFormPlaceholder.addClass('show')
			quoteFormPlaceholder.show()
		}
		quoteFormPlaceholder.find('.df-scc-euiModal__closeIcon').on('click', function () {
			quoteFormPlaceholder.hide()
			quoteFormPlaceholder.hide()

		})
		quoteFormPlaceholder.find('.df-scc-euiButtonEmpty.df-scc-euiButtonEmpty--primary').on('click', function () {
			quoteFormPlaceholder.hide()
			quoteFormPlaceholder.hide()
		})
	}
}

function showQuoteDetailView(calcId) {
	if (!verifiedMandatoryItems(calcId)) {
		return
	}
	let { webhookConfig, pdf: pdfConfig, objectColor } = sccData[calcId].config;
	let isDetailViewWebhookEnabled = webhookConfig.filter(e => Object.keys(e) == "scc_set_webhook_detail_view")[0]?.scc_set_webhook_detail_view?.enabled;
	var fontName = sccData[calcId].config.fontConfig.serviceFont;
	var css = `.hover_bkgr_fricctableprice-1 .span2,
	.hover_bkgr_fricctableprice-1 .users-price-summary-scc,
	.hover_bkgr_fricctableprice-1 .Title-Descriptions-Summary-Window,
	.hover_bkgr_fricctableprice-1 .Section-Title-Summary-Window,
	.hover_bkgr_fricctableprice-1 .span-title,
	.hover_bkgr_fricctableprice-1 .Total_Price,
	.scc-col-md-10.scc-col-xs-12.main-title,
	.detailview-date span,
	.scc_email_template_view > div {
		 font-family : ${fontName};
	} `;
	css += `
	#sccTale_price-1::-webkit-scrollbar-thumb
	{
		border-radius: 10px;
		background-color: #FFF;
		background-image: -webkit-gradient(linear, 40% 0%,75% 84%,from(${objectColor}),to(${objectColor}),color-stop(.6,${objectColor}))
	}`;
	if (pdfConfig.disableUnitColumn) {
		css += `
		.scc-col-md-2.scc-col-xs-2.sscfull-height:not(:nth-child(2)),
			span.Title-Descriptions-Summary-Window.trn.Unit.Price {
				opacity: 0;
			}

		`;
	}
	head = document.head || document.getElementsByTagName('head')[0],
		style = document.createElement('style');
	style.setAttribute("id", "scc-detail-view");

	head.appendChild(style);

	style.type = 'text/css';
	if (style.styleSheet) {
		// This is required for IE8 and below.
		style.styleSheet.cssText = css;
	} else {
		style.appendChild(document.createTextNode(css));
	}
	jQuery('#scc-detail-view').remove();
	document.head.appendChild(style);
	//added to avoid error js when no element selected
	if ((!sccData[calcId].pdf)) return;
	jQuery('#detail-view-placeholder').html(wp.template('scc-quote-items-list-modal')({ pdf: sccData[calcId].pdf, calcId, pdfConfig, objectColor }));
	if (isDetailViewWebhookEnabled) {
		jQuery.ajax({
			url: wp.ajax.settings.url + '?action=scc_do_webhooks' + '&calcId=' + calcId + '&type=detailed_view',
			type: 'POST',
			contentType: 'json',
			data: JSON.stringify(sccData[calcId].webhook)
		});
	}
}

function sendPDF(enable, calcId) {
	var sanitizedPDFJson = sccData[calcId].pdf;
	sanitizedPDFJson.rows = sanitizedPDFJson.rows.filter(e => Object.keys(e).length);
	var tableTitle = sanitizedPDFJson.pdf_title;
	var pdfConfig = sccData[calcId].config.pdf;
	if (enable == 0) {
		jQuery('.scc-alert').remove();
		return;
	}
	$ajaxQuery = {
		url: wp.ajax.settings.url,
		type: 'POST',
		tableTitle,
		xhrFields: {
			responseType: ''
		},
		data: {
			action: 'sccSendPDF',
			payload: Base64.encode(JSON.stringify(sanitizedPDFJson)),
			disableUnitColumn: pdfConfig.disableUnitColumn,
			tableTitle,
		},
		success: function (b64) {
			const linkSource = `data:application/pdf;base64,${b64.file}`;
			const downloadLink = document.createElement("a");
			const fileName = this.tableTitle + "-" + (new Date()).toLocaleDateString() + ".pdf";
			downloadLink.href = linkSource;
			downloadLink.download = fileName;
			downloadLink.click();
		},
		error: function (err) {
			console.log('err', err)
			console.log('err', err.responseText)
			var disposition = err.getResponseHeader('content-disposition');
			var matches = /"([^"]*)"/.exec(disposition);
			var filename = (matches != null && matches[1] ? matches[1] : 'file.pdf');
			// The actual download
			var blob = new Blob([err.responseText], { type: 'application/pdf' });
			var link = document.createElement('a');
			link.href = window.URL.createObjectURL(blob);
			link.download = filename;
			document.body.appendChild(link);
			link.click();
			document.body.removeChild(link);
		}
	}
	jQuery.ajax($ajaxQuery);
}

PrintDoc = function (enable, calcId) {
	if (enable === 0) {
		jQuery('.scc-alert').remove();
		jQuery('body').find('.scc-detailed-list-head').each(function (index, ob) {
			jQuery(ob).html(`<div class='alert alert-info scc-alert' role='alert'><button class='close' type='button' data-dismiss='alert'>×</button><p>This feature is only for Premium users. You can purchase the premium version at <a href='https://stylishcostcalculator.com/' class='alert-link'><strong>https://stylishcostcalculator.com</strong></a></p></div>`);
		})
		return;
	}
	var pdfConfig = sccData[calcId].config.pdf;
	// https://stackoverflow.com/questions/40469412/trigger-print-preview-of-base64-encoded-pdf-from-javascript
	printPreview = (data, type = 'application/pdf') => {
		let blob = null;
		blob = this.b64toBlob(data.file, type);
		const blobURL = URL.createObjectURL(blob);
		const theWindow = window.open(blobURL);
		const theDoc = theWindow.document;
		const theScript = document.createElement('script');
		function injectThis() {
			window.print();
		}
		theScript.innerHTML = `window.onload = ${injectThis.toString()};`;
		theDoc.body.appendChild(theScript);
	};
	b64toBlob = (content, contentType) => {
		contentType = contentType || '';
		const sliceSize = 512;
		// method which converts base64 to binary
		const byteCharacters = window.atob(content);
		const byteArrays = [];
		for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
			const slice = byteCharacters.slice(offset, offset + sliceSize);
			const byteNumbers = new Array(slice.length);
			for (let i = 0; i < slice.length; i++) {
				byteNumbers[i] = slice.charCodeAt(i);
			}
			const byteArray = new Uint8Array(byteNumbers);
			byteArrays.push(byteArray);
		}
		const blob = new Blob(byteArrays, {
			type: contentType
		}); // statement which creates the blob
		return blob;
	};
	// sanitize PDFJson, so it doesn't trigger errors
	var sanitizedPDFJson = sccData[calcId].pdf;
	var tableTitle = sanitizedPDFJson.pdf_title;
	sanitizedPDFJson.rows = sanitizedPDFJson.rows.filter(e => Object.keys(e).length);
	$ajaxQuery = {
		url: wp.ajax.settings.url,
		type: 'POST',
		xhrFields: {
			responseType: ''
		},
		data: {
			action: 'sccSendPDF',
			payload: Base64.encode(JSON.stringify(sanitizedPDFJson)),
			disableUnitColumn: pdfConfig.disableUnitColumn,
			tableTitle,
		},
		success: function (b64) {
			printPreview(b64);
		},
		error: function (err) {
			console.log('err', err)
			console.log('err', err.responseText)
		}
	}
	jQuery.ajax($ajaxQuery);
}

function handleQuoteSubmission(calcId, $this, color = 'cyan', isCaptchaEnabled = false, disableUnitColumn = false, showFormInDetail = false) {
	event.preventDefault();
	let { config } = sccData[calcId];
	isCaptchaEnabled = Boolean(config.captcha.enabled);
	var captchaResponse = jQuery($this).data('recaptcha-key');
	var recaptchaSiteKey = config.captcha.siteKey;
	if (isCaptchaEnabled && !captchaResponse) {
		sccProcessRecaptcha(calcId, recaptchaSiteKey, $this);
		return;
	}
	if ((!sccData[calcId].pdf)) {
		console.warn("Nothing is selected in calculator")
		return
	}
	// sanitize PDFJson, so it doesn't trigger errors
	var sanitizedPDFJson = sccData[calcId].pdf;
	sanitizedPDFJson.rows = sanitizedPDFJson.rows.filter(e => Object.keys(e).length);
	let userSubmittedData = new FormData($this);
	let toSubmitData = new FormData();
	let pageReferer = document.referrer;
	let encodedQuoteData = Base64.encode(JSON.stringify({ referer: pageReferer, pdf: sanitizedPDFJson }));
	toSubmitData.append('action', 'sccQuoteSubmission');
	toSubmitData.append('data', JSON.stringify([...userSubmittedData.entries()]));
	toSubmitData.append('quote_data', encodedQuoteData);
	toSubmitData.append('calcId', calcId);
	toSubmitData.append('captchaResponse', captchaResponse);
	toSubmitData.append('disableUnitColumn', disableUnitColumn);
	toSubmitData.append('showFormInDetail', showFormInDetail)
	jQuery(`.scc_wrapper.calc-id-${calcId}`).find('.scc-file-upload input[type="file"]').each(function (i, e) {
		toSubmitData.append(`file-${i}`, e.files[0]);
	})
	jQuery.ajax({
		url: wp.ajax.settings.url,
		type: 'POST',
		context: $this,
		processData: false,
		contentType: false,
		data: toSubmitData,
		beforeSend: function () {
			let pleaseWaitText = sccGetTranslationByKey(calcId, 'Please wait...')
			jQuery(this).find('.df-scc-euiText.df-scc-euiText--medium').html(jQuery(`<div class="df-scc-progress df-scc-progress-striped active">
				<div class="df-scc-progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="background-color: ${color}; width: 100%">
				</div>
				</div>`));
			jQuery(this).find('.df-scc-euiModalHeader__title').text(pleaseWaitText);
			jQuery(this).find('.df-scc-euiModalFooter').hide();
		},
		success: async function (data) {
			console.log(data)
			let { webhookConfig } = sccData[calcId].config;
			let isQuoteWebhookEnabled = webhookConfig.filter(e => Object.keys(e) == "scc_set_webhook_quote")[0]?.scc_set_webhook_quote?.enabled;
			let failedMessage = jQuery(`
				<p><span class="trn">Email quote sending failed. Please review your recaptcha/email provider settings. And verify you have the PHP GD library installed on your server.</span></p>
				<p><a href="https://designful.freshdesk.com/a/solutions/categories/48000446985/folders/48000658562?view=all">Learn More</a></p>
				`);
			let successMessage = jQuery(`
				<p><span class="trn">${sccGetTranslationByKey(calcId, 'Thank you! We sent your quote to')}</span> ${userSubmittedData.get('email')}.</p>
				<p class="trn" style="font-size:12px;margin-top:-10px;">${sccGetTranslationByKey(calcId, 'Remember to check your spam folder.')}</p>
				`);
			let message = successMessage;
			if (data?.error) {
				message = failedMessage;
				console.log(data.error);
			}
			if (!data?.error && isQuoteWebhookEnabled) {
				jQuery.ajax({
					url: wp.ajax.settings.url + '?action=scc_do_webhooks' + '&calcId=' + calcId + '&type=quote_send',
					type: "POST",
					contentType: 'json',
					data: JSON.stringify({
						quoteItems: JSON.stringify(sccData[calcId].webhook),
						emailQuoteForm: toSubmitData.get('data')
					}),
					success: async function () {
						if (jQuery($this).data('callback')) {
							let cb = jQuery($this).data('callback').cb;
							cb();
							jQuery('#quote-form-placeholder').modal('hide');
						}
					}
				});
			}
			if (jQuery($this).data('callback') && !isQuoteWebhookEnabled && !data?.error) {
				let cb = jQuery($this).data('callback').cb;
				cb();
				jQuery('#quote-form-placeholder').modal('hide');
			}
			if (!jQuery($this).data('callback')) {
				jQuery(this).find('.df-scc-euiText.df-scc-euiText--medium').html(message);
				jQuery(this).find('.df-scc-euiModalHeader__title').text('');
			}
			// reset captcha response
			jQuery($this).data('recaptcha-key', '');
			if (window.hasOwnProperty("translatorJson")) {
				initTranslationScriptSCC($this, { lang: "en", t: translatorJson });
			}
		}
	})
}

function sccProcessRecaptcha(calcId, recaptchaSiteKey, element) {
	var robotCheckNoticeText = sccGetTranslationByKey(calcId, 'Prove that you are not a robot');
	var htmlData = `<div class="df-scc-euiModalBody__overflow recaptcha">
	<div class="df-scc-euiText df-scc-euiText--medium">
	<p class="trn">${robotCheckNoticeText}</p>
	<div class="scc-g-recaptcha form-field"></div>
	</div>
	</div>`;
	jQuery.getScript('https://www.google.com/recaptcha/api.js')
		.done(function (script, textStatus) {
			var modalBody = jQuery(element).closest('.df-scc-euiModal').find('.df-scc-euiModalBody');
			modalBody.children().hide();
			modalBody.children(':first').after(htmlData)
			return grecaptcha.ready(function () {
				grecaptcha.render(modalBody.find('.scc-g-recaptcha')[0], {
					'sitekey': recaptchaSiteKey,
					'callback': function (e) {
						jQuery(element).data('recaptcha-key', e);
						jQuery(element).find('button[type=submit]').trigger('click');
					},
				});
			});
		})
		.fail(function (jqxhr, settings, exception) {
		});
	return;
}

function sccGetTranslationByKey(calcId, key) {
	let translation = sccData[calcId].config.translation.filter((e, i) => e.key == key)[0]?.translation;
	if (translation) {
		return translation;
	}
	return key;
}

function sccMandatoryValidationCheck($this, submitEvent, calculator_id) {
	if (submitEvent) submitEvent.preventDefault();
	if (!calculator_id && $this) {
		calculator_id = jQuery($this).closest('.scc_wrapper').attr('id').split('scc_form_')[1];
	}

	let scc_mandatory = '';

	const mandatoryElements = document.querySelectorAll( '.mandatory_yes_' + calculator_id );
	mandatoryElements.forEach( ( el ) => {
		if ( el.value === '' && el.style.display !== 'none' ) {
			el.addEventListener( 'change', function() {
				if ( this.value !== '' ) {
					this.parentElement.parentElement.querySelector( '.scc-mandatory-msg' ).classList.remove( 'scc-d-flex' );
					this.parentElement.parentElement.querySelector( '.scc-mandatory-msg' ).classList.add( 'scc-hidden' );
				}
			}, { once: true } );

			const alertDanger = el.parentElement.parentElement.querySelector( '.scc-mandatory-msg' );
			const alertDangerText = alertDanger.querySelector( '.scc-mandatory-translated-text' );
			alertDangerText.innerText = 'Please choose an option☝️';
			alertDanger.classList.remove( 'scc-hidden' );
			alertDanger.classList.add( 'scc-d-flex' );

			window.scroll( {
				top: el.offsetTop,
				behavior: 'smooth',
			} );

			scc_mandatory = 'scc_remains';

			if ( window.hasOwnProperty( 'translatorJson' ) ) {
				initTranslationScriptSCC( 'body', { lang: 'en', t: translatorJson } );
			}

			checkIfParentIsAccordionAndOpenIt( element );
		}
	} );
	// check if mandatory checkbox/button/switches are checked
	jQuery('#scc_form_' + calculator_id + ' ' + '[data-mandatory=yes]').each((i, e) => {
		scc_mandatory += checkBoxMandatoryCheck(e);
	})
	// check if mandatory sliders are utilized
	jQuery('#scc_form_' + calculator_id + ' ' + '[data-slider-mandatory=yes]').each((i, e) => {
		scc_mandatory += sliderMandatoryCheck(e);
	})
	if (scc_mandatory == '') {
		if (submitEvent) submitEvent.target.submit();
		if (!submitEvent) return true;
	}
};

function addItemsToPayPalForm(items, tax, comments, calculator_id, isTaxInclusionEnabled = false) {
	total = 0;
	var itemsToAddPaypalForm = "";
	var itemsAdded = 0;
	for (var i = 0; i < items.length; i++) {
		var item_value = items[i].qtn === null || isNaN(items[i].qtn || items[i].qtn === 0)
			? items[i].price
			: items[i].calculatedValue
				? items[i].calculatedValue
				: items[i].price * items[i].qtn;
		var item_name = items[i].qtn === null || items[i].qtn === 1 || isNaN(items[i].qtn) || items[i].qtn === 0
			? items[i].name
			: items[i].qtn + " units: " + items[i].name;
		if (item_name) {
			itemsToAddPaypalForm += '  <input type="hidden" name="item_name_' + (
				itemsAdded + 1) + '" value="' + item_name + '" class="btPayPalItem">';
			itemsToAddPaypalForm += '  <input type="hidden" name="amount_' + (
				itemsAdded + 1) + '" value="' + parseFloat(item_value).toFixed(2) + '" class="btPayPalItem">';
			itemsAdded++;
		}
		total += items[i].qtn === null || isNaN(items[i].qtn)
			? items[i].price
			: items[i].price * items[i].qtn;
	}
	if (tax && isTaxInclusionEnabled) {
		itemsToAddPaypalForm += '  <input type="hidden" name="item_name_' + (
			itemsAdded + 1) + '" value="tax" class="btPayPalItem">';
		itemsToAddPaypalForm += '  <input type="hidden" name="amount_' + (
			itemsAdded + 1) + '" value="' + tax.toFixed(2) + '" class="btPayPalItem">';
	}
	jQuery(`#scc_form_${calculator_id}`).find("form.paypal_form .paypal_form_add_items").html(itemsToAddPaypalForm);
}


function sccProcessCheckout(calcId) {
	$fragment_refresh = {
		url: wp.ajax.settings.url,
		type: 'POST',
		data: {
			action: 'scc_stripe_action',
			data: Base64.encode(JSON.stringify(sccData[calcId].stripeCart)),
			currency: sccData[calcId].config.currencyCode
		},
		success: function (session) {
			if (!session.error) {
				return stripe.redirectToCheckout({ sessionId: session.id });
			} else {
				console.log(session.error);
			}
		}
	};
	var validationCheckResult = sccMandatoryValidationCheck(null, null, calcId);
	if (validationCheckResult) jQuery.ajax($fragment_refresh);
}

function preCheckout(calcId, callback) {
	let { config } = sccData[calcId];
	if (config.preCheckoutQuoteForm && verifiedMandatoryItems(calcId)) {
		initiateQuoteForm(calcId, callback);
		return;
	}
	if (verifiedMandatoryItems(calcId)) {
		callback();
	}
}
function showWooCommerceLoadingModal(buttonBackground) {
	var code = `
	<div
		id="showWooCommerceModal"
		class="scc-modal-2020 fade"
		role="dialog"
		style="opacity:1"
  	>
		<div class="scc-modal-dialog-2020" style="width:560px;top:250px;">
		<div class="scc-modal-content-2020">
			<div class="scc-modal-header-2020">
			Please Wait
			<button type="button" class="close" data-dismiss="modal">
				&times;
			</button>
			</div>
			<div class="scc-modal-body-2020">
			<div class="df-scc-progress df-scc-progress-striped active">
				<div
				class="df-scc-progress-bar"
				role="progressbar"
				aria-valuenow="100"
				aria-valuemin="0"
				aria-valuemax="100"
				style="background-color: ${buttonBackground}; width: 100%"
				></div>
			</div>
			</div>
		</div>
		</div>
  	</div>`;
	jQuery('#woocommerce-loading-placeholder').html(code)
	jQuery('#showWooCommerceModal').modal('show')
}
function sccWoocommerceAction(calcId) {
	return
}
// TODO remove it before next release
function sccApplyColors() {
	if (typeof sccData == "undefined") return
	Object.keys(sccData).forEach((calcId, i) => {
		let { objectColor, title, service } = sccData[calcId].config;
		var styleEl = document.createElement('style');
		styleEl.setAttribute("id", "scc-js-style");
		let existingStyleTag = document.getElementById('scc-js-style');
		if (existingStyleTag) {
			existingStyleTag.remove();
		}
		document.head.appendChild(styleEl);
		var styleSheet = styleEl.sheet;
		let targets = {
			'a.scc-usr-act-btns:not(.scc-btn-style-2)': `background: ${objectColor}`,
			'.btPayPalButtonCustom, .btnStripe': `border: 2px solid ${objectColor}`,
			'.calc-wrapper .description_section_preview': `color: ${objectColor}`,
			'.itemCreated input[type="checkbox"]+label::after': `box-shadow: inset 0 0 0 1px #666565, inset 0 0 0 16px #FFFFFF, inset 0 0 0 16px ${objectColor} !important`,
			'.itemCreated input[type="checkbox"]+label:hover': `color: ${objectColor} !important`,
			'.itemCreated input[type="checkbox"]+label:hover::after': `box-shadow: inset 0 0 0 1px ${objectColor}, inset 0 0 0 16px #FFFFFF, inset 0 0 0 16px ${objectColor} !important`,
			'.itemCreated input[type="checkbox"]:checked+label::after': `box-shadow: inset 0 0 0 1px ${objectColor}, inset 0 0 0 3px #FFFFFF, inset 0 0 0 16px ${objectColor} !important`,
			'.scc_customradio-45 input[type="checkbox"]+label:after': `box-shadow: inset 0 0 0 1px ${objectColor}, inset 0 0 0 16px #f6f6f6, inset 0 0 0 16px ${objectColor} !important`,
			'.scc_customradio-45 input[type="checkbox"]+label:hover': `color: ${objectColor} !important`,
			'.scc_customradio-45 input[type="checkbox"]:checked+label::after': `box-shadow: inset 0 0 0 1px ${objectColor}, inset 0 0 0 3px #FFFFFF, inset 0 0 0 16px ${objectColor} !important`,
			'.scc_customradio-45 input[type="checkbox"]+label:hover::after': `box-shadow: inset 0 0 0 1px ${objectColor}, inset 0 0 0 16px #FFFFFF, inset 0 0 0 16px ${objectColor} !important`,
			'.scc-btn-style-2:after': `background-color: ${objectColor} !important;`,
			'a.scc-trigger.scc-btn-style-2': `color: ${objectColor} !important;`,
			'.btn-radio-45 svg path': `stroke: ${objectColor}`,
			'.label-cbx-45 input:checked+.checkbox': `border-color: ${objectColor}`,
			'.label-cbx-45 input:checked+.checkbox svg path': `fill: ${objectColor}`,
			'.label-cbx-45 .checkbox svg path': `stroke: ${objectColor}`,
			'.dd-scc-45': `border-left: 4px solid ${objectColor}`,
			'\[id$="_msdd"\]': `border-left: 4px solid ${objectColor}`,
			'.slider-handle': `
					background-image: -moz-linear-gradient(top, ${objectColor}, ${objectColor});
					background-image: -webkit-gradient(linear, 0 0, 0 100%, from(${objectColor}), to(${objectColor}));
					background-image: -webkit-linear-gradient(top, ${objectColor}, ${objectColor});
					background-image: -o-linear-gradient(top, ${objectColor}, ${objectColor});
					background-image: linear-gradient(to bottom, ${objectColor}, ${objectColor});
					background-color: ${objectColor}`,
			'.slider-track .slider-selection': `background-color: ${objectColor}`,
			'.scc-custom-math-element': `border-left: 3px solid ${objectColor}`,
			'span.input-number-increment,span.input-number-decrement': `color: ${objectColor} !important`,
			'.clicked-buttom-checkbox': `background-color: ${objectColor} !important`,
			'.scc-accordion, .scc-accordion_active': `background-color: ${objectColor}`,
			//fonts styling
			'.scc-accordion_active':
				`font-family:${title.font_family};
				font-size:22px;
				background-color: ${objectColor}`,
			'.scc-accordion':
				`font-family:${title.font_family};
				font-size:22px;
				background-color: ${objectColor}`,
			'.scc-title-s':
				`color: ${title.color};
					font-family:${title.font_family};
					font-size:${title.size};
					font-weight:${title.font_weight};`,
			".ts-dropdown-content::-webkit-scrollbar-thumb": `border-radius: 10px;
					background-color: #FFF;
					background-image: -webkit-gradient(linear, 40% 0%,75% 84%,from(${objectColor}),to(${objectColor}),color-stop(.6,${objectColor}))`,
			'.scc-minimum-msg':
				`color: ${service.color};
					font-size: ${service.size};
					font-family: ${service.font_familly};`,
			'[id*=ssc-elmt-]':
				`color: ${service.color};
					font-size: ${service.size};
					font-family: ${service.font_familly};
					margin-top: 20px;`,
			'.scc-form-field-item-label':
				`color: ${service.color};
					font-size: ${service.size};
					font-family: ${service.font_familly};`,
			'.description_section_preview':
				`color: ${service.color};
					font-size: ${service.size};
					font-family: ${service.font_familly};`,
			'.scc-multiple-total':
				`color: ${service.color};
					font-family: ${service.font_familly};`,
			'.scc-usr-act-btns':
				`font-family: ${service.font_familly};`,
			'p.scc-tax-container':
				`font-size: ${title.size.replace('px', '') - 7 + 'px'};
				margin-bottom: -2px;
					color: ${title.color};`,
			'.totalPrice':
				`font-family:${title.font_family}`,
			'.df-scc-image-buttons :checked + label:before':
				`background-color:${objectColor};
				 border: 1px solid ${objectColor};`,
			'.df-scc-image-buttons label.img-bordered':
				`border: 2px solid ${objectColor};`,
			'.df-scc-image-buttons .item-name':
				`color:${service.color};
				font-family:${service.font_familly};
				font-size:${service.size} !important;`,
			'.total1 .conversion,.total12 .conversion':
				`color:${title.color}`,
			'.scc-total-section .total1, .scc-total-section .total12':
				`background-color:${title.color};
				font-family:${title.font_family};`,
			'.df-scc-slider-connect, .df-scc-slider-handle': `background:${objectColor};`,
			'.scc-total-section .total2, .scc-total-section .total22':
				`font-family:${title.font_family};`
		}
		Object.keys(targets).forEach(e => styleSheet.insertRule(`${'#scc_form_' + calcId + ' ' + e} {${targets[e]}}`))
	});
}

function sccLoadGFonts() {
	Object.keys(sccData).forEach((calcId, i) => {
		let { fontConfig } = sccData[calcId].config;
		fontConfig.googleFontLinks.forEach((e, index) => {
			var cssId = 'scc_gfont_' + calcId + '_' + index;  // you could encode the css path itself to generate id..
			if (!document.getElementById(cssId)) {
				var head = document.getElementsByTagName('head')[0];
				var link = document.createElement('link');
				link.id = cssId;
				link.rel = 'stylesheet';
				link.type = 'text/css';
				link.href = e;
				link.media = 'all';
				head.appendChild(link);
			}
		})
	})
}

window.addEventListener('DOMContentLoaded', (event) => {
	// apply colors


	// START of Multiple Total shortcode with combine attribute's frontend javascript
	jQuery('.scc-multiple-total-wrapper[data-combination]').on('valueChange', function (event) {
		/* showCurrencySymbol : if the currency symbol be showed or not it finds the value from the data attribute
		values : gets the total values from the calculator IDs
		*/
		let showCurrencySymbol = jQuery(this).data('currSym');
		let values = jQuery(this)
			.data("combination")
			.map((e) => (sccData[e]?.total ? sccData[e]?.total : 0));
		// the combined value is a variable that goes through a loop and adds the values in the previously defined 'values' variable
		let combinedValue = 0;
		for (let index = 0; index < values.length; index++) {
			combinedValue += parseFloat(values[index]);
		}
		// finally we apply the currency symbol and the value in the html elements
		showCurrencySymbol ? jQuery(this).find('.multi-total-currency-prefix').text(currencySymbol(currencyLabel)) : jQuery(this).find('.multi-total-currency-suffix').text(currencyLabel);
		jQuery(this).find('.scc-total').text(combinedValue).trigger('doMath');
	});
	// End of Multiple Total shortcode with combine attribute's frontend javascript
	// START of Multiple Total shortcode with apply-math attribute's frontend javascript
	jQuery('.scc-multiple-total-wrapper[data-math] .scc-total').on('doMath', function (event) {
		let $this = jQuery(this);
		// mathOperator: gets the data-math attribute's value
		let mathOperator = $this.parent().data('math');
		let elementTotal = parseFloat($this.text());
		let calcId = 0;
		if ($this.parent()[0]?.classList) {
			calcId = [...$this.parent()[0]?.classList]
				.find((e) => e.match("calcid"))
				?.replace("calcid-", "");
		}

		// if there are math operators, we apply the math here
		if (elementTotal && mathOperator.length == 2) {
			let opKey = mathOperator[0];
			let opVal = parseFloat(mathOperator[1]);
			switch (opKey) {
				case 'divide':
					if (opVal > 0) elementTotal = elementTotal / parseFloat(opVal);
					break;
				case 'multiply':
					elementTotal = elementTotal * parseFloat(opVal);
					break;
				case 'add':
					elementTotal = elementTotal + parseFloat(opVal);
					break;
				case 'subtract':
					elementTotal = elementTotal - parseFloat(opVal);
					break;
				default:
					break;
			}
			if (calcId) {
				$this.text(getCurrencyText(calcId, elementTotal));
			} else {
				$this.text(elementTotal);
			}
		}
	})
	// END of Multiple Total shortcode with apply-math attribute's frontend javascript

	// Start of accordion handling codes
	function initSCCAccordion() {
		var acc = document.getElementsByClassName("scc-accordion");
		var i;
		for (i = 0; i < acc.length; i++) {
			acc[i].addEventListener("click", function () {
				if (jQuery(this).hasClass("scc-accordion_active")) {
					jQuery(this).removeClass("scc-accordion_active");
					jQuery(this).addClass("scc-accordion");
				} else {
					jQuery(this).removeClass("scc-accordion");
					jQuery(this).addClass("scc-accordion_active");
				}
				jQuery("." + jQuery(this).attr("id")).each(function (index, ob) {
					if (jQuery(ob).css("maxHeight") === "0px") {
						if (jQuery(ob).hasClass("simple_button")) {
							jQuery(ob).css("display", "inline-block");
							jQuery(ob).css("paddingLeft", 10);
						} else {
							if (!jQuery(ob).hasClass("scc-set-hidden"))
								var origDisplayState = jQuery(ob).data('origDisplayState')
							jQuery(ob).css("display", origDisplayState);
						}
						setTimeout(function () {
							jQuery(ob).css("maxHeight", 'max-content');
							jQuery(ob).removeClass("scc-accordion-panel-hidden");
							jQuery(ob).css("overflow", "visible");
						}, 200);
					} else {
						jQuery(ob).css("maxHeight", 0);
						jQuery(ob).css("overflow", "hidden");
						setTimeout(function () {
							jQuery(ob).addClass("scc-accordion-panel-hidden");
							jQuery(ob).css("display", "none");
						}, 600);
					}
				});
			});
		}
		for (i = 0; i < acc.length; i++) {
			jQuery("." + jQuery(jQuery(acc[i])).attr("id")).each(function (index, ob) {
				// get display property of current element
				let currentDisplayProperty = jQuery(ob).css('display');
				// store the display propery value for later use
				jQuery(ob).data('origDisplayState', currentDisplayProperty);
				jQuery(ob).css("display", "none");
			});
		}
	}
	// END of accordion handling codes
	// detect if in the editing page or the frontend, apply strategies accordingly
	if (typeof (isInsideEditingPage) !== 'undefined' && isInsideEditingPage()) {
		jQuery('.preview_form_right_side').on('previewLoaded', async function () {
			await initializeScc()
			let param = new URLSearchParams(window.location.search)
			checkConditions(param.get('id_form'))
			dfsccLoaded()
			initSCCAccordion()
			sccApplyColors()
			sccLoadGFonts()
			// scc_font_styles()
		})
	} else {
		initializeScc()
		sccUslStats()
		dfsccLoaded()
		initSCCAccordion()
		// scc_font_styles()

		sccApplyColors()
		sccLoadGFonts()
	}
})

function addCouponCodeModal(calcId) {
	var { objectColor, fontConfig, title, service, objectColor } = sccData[calcId].config
	var css = `.df-scc-euiModalHeader__title,
	.df-scc-euiFormLabel.df-scc-euiFormRow__label,
	.modal .df-scc-euiText,
	.modal .df-scc-euiModal,
	.df-scc-euiButtonContent.df-scc-euiButtonEmpty__content,
	.df-scc-euiButton.df-scc-euiButton--primary.df-scc-euiButton--fill {
		font-family : ${fontConfig.serviceFont};
	}
	.df-scc-euiModalHeader__title{
		color:${title.color};
	}
	.df-scc-euiFormRow__label{
		color:${service.color};
	}
	.df-scc-euiButtonEmpty__text{
		color:${objectColor};
	}
	.df-scc-euiButtonContent.df-scc-euiButton__content{
		background-color:${objectColor};
		border-color:${objectColor};
		border-radius:3px;
	}
	svg{
		transition:fill 120ms ease-in-out;
		fill:currentColor;
	}
	input.df-scc-euiFieldText:focus {
		background-image: linear-gradient(to top, ${objectColor}, ${objectColor} 2px, transparent 2px, transparent 100%);
	}`
	head = document.head || document.getElementsByTagName('head')[0],
		style = document.createElement('style');
	style.setAttribute("id", "scc-quotemodal");

	head.appendChild(style);

	style.type = 'text/css';
	if (style.styleSheet) {
		// This is required for IE8 and below.
		style.styleSheet.cssText = css;
	} else {
		style.appendChild(document.createTextNode(css));
	}
	jQuery('#scc-quotemodal').remove();
	document.head.appendChild(style);
	let template = wp.template('scc-coupon-modal')({ calcId });
	jQuery('#scc-coupon-modal-placeholder').html(template).modal('show');
}


function showDiscountNotice(calcId, discountInfo) {
	let targetCalculator = jQuery('#scc_form_' + calcId)
	let discountNotice = wp.template('scc-discount-notice')(discountInfo)
	targetCalculator.find('.row.scc-total-section').prev('.coupon_info_container').remove()
	targetCalculator.find('.row.scc-total-section').before(discountNotice)
}

function getCurrencyText(calcId, amount) {
	let {
		useCurrencyLetters,
		currencyCode,
		removeCurrency,
		tseparator
	} = sccData[calcId].config;
	amount = parseFloat(amount).toFixed(2)
	if (tseparator === "comma") {
		amount = priceCommaStyler(amount);
	} else {
		const hasFractions = amount - parseInt( amount ) > 0;
		if ( hasFractions ) {
			amount = parseFloat(amount).toLocaleString(navigator.languages[0], { minimumFractionDigits: 2 });
		} else {
			amount = parseFloat(amount).toLocaleString(navigator.languages[0]);
		}
	}
	if (removeCurrency) {
		return amount;
	}
	let _currencySymbol = currencySymbol(currencyCode);
	return useCurrencyLetters ? amount + ' ' + currencyCode : _currencySymbol + amount;
}

function outputDate(format) {
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth() + 1;
	var yyyy = today.getFullYear();
	if (dd < 10) {
		dd = '0' + dd;
	}
	if (mm < 10) {
		mm = '0' + mm;
	}
	if (format == "dd-mm-yyyy") {
		var today = dd + '/' + mm + '/' + yyyy;
	} else {
		var today = mm + '/' + dd + '/' + yyyy;
	}
	return today;
}
function sccHandleSliderInputValueChange(sliderValue, elementId) {
	jQuery("#itemcreateds_0_0-" + elementId).data('bootstrapSlider').setValue(sliderValue);
}

function dfSccInputBoxAddCommas(inputField) {
	let val = inputField.value.replace(/\D/g, ""); // remove any non-digit characters
	val = parseInt(val, 10); // convert to integer
	if (isNaN(val)) {
		// if the input value is not a valid number, empty the input field
		inputField.value = "";
	} else {
		// if the input value is a valid number, format it with commas
		inputField.value = val.toLocaleString('en-US');
	}
}

//default value in form
function dfsccLoaded() {
	jQuery('[id^=ssc-elmt-]').each(function () {
		let type = jQuery(this).data('typeelement')
		switch (type) {
			case "checkbox":
				triggerCheck(this)
				break
			case 'Dropdown Menu':
				triggerTomSelect(this)
				break
			case 'slider':
				triggerSlider(this)
				break
			case 'custom math':
				triggerCustom(this)
				break
			case 'file upload':
				triggerFile(this)
				break
		}

		function triggerCheck(cntx) {
			jQuery(cntx).find('input').each(function () {
				let formId = jQuery(this).data('formid')
				let elementId = jQuery(this).data('elementid')
				let itemId = jQuery(this).data('itemid')
				let optdef = jQuery(this).data('optdefault')
				let checkboxtype = jQuery(this).data('checkboxtype')
				if (optdef == 1) {
					jQuery(this).prop("checked", "true")
					if (checkboxtype == 6) changeButtonToogle(this)
					triggerSubmit(1, this, elementId, itemId, formId)
				}
			})
		}

		function triggerTomSelect(cntx) {
			var el = jQuery(cntx).find("[id^=byjson]");
			let formId = jQuery(el).data("formid");
			let elementId = jQuery(el).data("elementid");
			let plugins = [];
			let shouldEnableSearchInput = (el[0].options.length - 1) > 7;
			if ( shouldEnableSearchInput ) {
				plugins.push('dropdown_input');
			}
			var tomSelectInstance = new TomSelect("#byjson" + elementId, {
				/*create: true,*/
				plugins,
				searchField: [{ field: 'text', weight: 2 }, { field: 'text2', weight: 0.5 }],
				hidePlaceholder: true,
				hideSelected: true,
				placeholder: sccGetTranslationByKey(formId, 'Search for an option...'),
				onInitialize: function () {
					if (!shouldEnableSearchInput) {
						this.control_input.setAttribute('disabled', '');
					}
				},
				render: {
					option: function (data, escape) {
						var contentResult = data.text.split('scc-dropdown-opt-content');
						const isChooseAnOption = data.$option.classList.contains('scc-pick-an-option');
						if (isChooseAnOption) {
							return `<div class="firstchild1">${sccGetTranslationByKey(formId, data.text.trim())}</div>`;
						}
						else {
							if (data.src) {
								return `<div> <img loading="lazy" class="me-2 ${data.class}" src="${data.src}"><div class="scc-option-content"><div class="scc-dropdown-opt-title">${contentResult[0]}</div><div class="scc-dropdown-opt-description">${contentResult[1]}</div></div></div>`;
							}
							else {
								return `<div class="scc-option-content"><div class="scc-dropdown-opt-title">${contentResult[0]}</div><div class="scc-dropdown-opt-description">${contentResult[1]}</div></div>`;
							}
						}

					},
					item: function (item, escape) {
						var contentResult = item.text.split('scc-dropdown-opt-content');
						const isChooseAnOption = item.$option.classList.contains('scc-pick-an-option')
						if (isChooseAnOption) {
							return `<div class="scc-pick-an-option-bkp firstchild2">
										<div class="scc-pick-an-option-text-bkp firstchild2">${sccGetTranslationByKey(formId, item.text.trim())}</div>
									</div>`;
						}
						else {
							if (item.src) {
								return `<div><img loading="lazy" class="me-2 ${item.class}" src="${item.src}"><div class="scc-option-content"><div class="scc-dropdown-opt-title">${contentResult[0]}</div><div class="scc-dropdown-opt-description">${contentResult[1]}</div></div></div>`;
							}
							else {
								return `<div class="scc-option-content"><div class="scc-dropdown-opt-title">${contentResult[0]}</div><div class="scc-dropdown-opt-description">${contentResult[1]}</div></div>`;
							}
						}

					}
				},
			});
			
			tomSelectInstance.on('change', function (currentValue) {
				let previusValue = this.input.getAttribute('data-prev-choice') || 0;
				if (previusValue == 'null') {
					previusValue = 0;
				}
				var selected = currentValue;
				triggerSubmit(2, this.input, 0, previusValue + "/" + selected, formId);
				this.input.setAttribute('data-prev-choice', selected);
			})
		}

		function triggerSlider(cntx) {
			try {
				var sliderNode = cntx.querySelector('.slider-styled');
				if (sliderNode && (!sliderNode.hasOwnProperty('noUiSlider'))) {
					let startValue = parseInt(sliderNode.getAttribute('data-start-value'));
					let minValue = parseInt(sliderNode.getAttribute('data-slider-min'));
					let maxValue = parseInt(sliderNode.getAttribute('data-slider-max'));
					let slideStep = parseInt(sliderNode.getAttribute('data-slider-step'));
					let rangeMatrix = sliderNode.getAttribute('data_range').split(',');
					let sliderCalculationType = sliderNode.getAttribute('data-calculation-type');
					let isPriceHintEnabled = sliderNode.getAttribute('data-show-pricehint') == 'true' && sliderCalculationType !== 'quantity_mod';
					let elementId = sliderNode.getAttribute('data-elementid');
					let sliderInputNode = cntx.querySelector('.scc-slider-input');
					var cur = sliderNode.getAttribute("data-currency");
					var sym = sliderNode.getAttribute("data-symbol");
					let handleTooltips = (value) => {
						for (k = 0; k < rangeMatrix.length; k += 3) {
							if (isPriceHintEnabled) {
								var sliderPrice = 0;
								switch (sliderCalculationType) {
									case "default":
									case "bulk":
										sliderPrice = parseFloat(rangeMatrix[k + 2]) * value;
										break;
									case "sliding":
										sliderPrice = parseFloat(rangeMatrix[k + 2]);
										break;
									default:
										break;
								}
								roundedPrice = (sliderPrice).toFixed(2);
							};
							if ((parseInt(value) <= parseInt(rangeMatrix[k + 1]))) {
								return (sym == 0) ? currencySymbol(cur) + priceCommaStyler(roundedPrice) : priceCommaStyler(roundedPrice) + ' ' + cur;
							}
						}
					}
					let sliderInstance = noUiSlider.create(sliderNode, {
						start: [startValue],
						connect: 'lower',
						tooltips: isPriceHintEnabled ? { from: handleTooltips, to: handleTooltips } : null,
						range: {
							'min': [minValue, slideStep],
							'max': maxValue
						},
						cssPrefix: 'df-scc-slider-',
					});
					let sliderHandleTouchArea = sliderNode.querySelector('.df-scc-slider-touch-area');
					let sliderHandleNode = sliderNode.querySelector('.df-scc-slider-handle');
					sliderHandleTouchArea.innerHTML = (`<span class="df-scc-slider-handle-text">${startValue}</span>`);
					isPriceHintEnabled ? sliderNode.noUiSlider.getTooltips()[0].style.display = 'none' : 0;
					let sliderHandleText = sliderHandleTouchArea.querySelector('.df-scc-slider-handle-text');
					sliderNode.noUiSlider.on('update', (value) => {
						let handleValue = parseInt(value[0]).toFixed();
						sliderHandleText.innerText = Number(handleValue).toLocaleString('en-US', { maximumFractionDigits: 2 });
						let defaultHeightWeight = '40px';
						let defaultHandleRadius = '20px';
						if (handleValue.length > 3) {
							let charExtraLength = handleValue.length - 4;
							let heightWeight = 55 + (charExtraLength * 5) + 'px';
							let handleRadius = 35 + (charExtraLength * 5) + 'px';
							sliderHandleNode.style.top = -20 - (charExtraLength * 5) + 'px'
							sliderHandleNode.style.width = heightWeight;
							sliderHandleNode.style.height = heightWeight;
							sliderHandleNode.style.borderRadius = handleRadius;
						} else {
							let currentStyle = getComputedStyle(sliderHandleNode);
							if (currentStyle.borderRadius !== '20px') {
								sliderHandleNode.style.top = '-16px'
								sliderHandleNode.style.width = defaultHeightWeight;
								sliderHandleNode.style.height = defaultHeightWeight;
								sliderHandleNode.style.borderRadius = defaultHandleRadius;
							}
						}
						if (sliderInputNode) sliderInputNode.value = parseInt(value[0]).toFixed();
					});
					let postChangeAction = (sliderValue) => {
						var ww = jQuery("#itemcreateds_0_0_" + elementId);
						let showOnDetailedList = sliderNode.getAttribute('data-show-on-detailed-list') == 'true';

						let formId = sliderNode.getAttribute('data-formid');
						let subid = sliderNode.getAttribute('data-subid');
						let o = sliderNode.getAttribute('data-slider-id');
						for (k = 0; k < rangeMatrix.length; k += 3) {
							if ((parseInt(sliderValue) <= parseInt(rangeMatrix[k + 1]))) {
								const switched = {
									name: 'title',
									unit: sliderValue,
									value: parseFloat(rangeMatrix[k + 2]),
									sliderInsubsection: true,
									sliderCalculationType,
									showOnDetailedList,
									paypal_name: jQuery(ww).closest('.scc-form-field-item').find('label').text(),
									calculator_id: 2
								};
								/**
								 * *Sends data to totalScript
								 * !needs to send id of slider, quantity, price, sliderCalculationType
								 */
								triggerSubmit(5, 0, o + "/" + sliderValue + "/" + switched.value + "/" + switched.sliderCalculationType + "/" + subid, 0, formId)
								return 'done';
							}
						}
					}
					postChangeAction(startValue);
					let actionPromise = (value) => new Promise(function (resolve, reject) {
						setTimeout(() => {
							resolve(postChangeAction(value));
						}, 10);
					});
					sliderNode.noUiSlider.on('set', actionPromise);
					sliderInputNode && sliderInputNode.addEventListener('change', function () {
						sliderNode.noUiSlider.set([this.value]);
					});
					sliderNode.noUiSlider.on('start', function () {
						isPriceHintEnabled ? this.getTooltips()[0].style.display = 'block' : 0;
					});
					sliderNode.noUiSlider.on('end', function () {
						isPriceHintEnabled ? this.getTooltips()[0].style.display = 'none' : 0;
					});
				}
			} catch (e) {
			}
		}

		function triggerCustom(cntx) {
			let elementId = jQuery(cntx).data('elementid')
			let formId = jQuery(cntx).data('formid')
			triggerSubmit(4, this, elementId, 0, formId)
		}

		function triggerFile(cntx) {
			jQuery.fn.customFile = function () {
				$file = this.find('input[type="file"]');
				$uploadBtn = this.find('.scc-ui.scc-fu-button');
				$uploadFileNamePlaceholder = this.find('.scc-ui.scc-fu-action.scc-fu-input input[type="text"]');
				$uploadBtn.unbind().click(function () {
					jQuery(this).parent().find("input:file").click();
				});
				$uploadFileNamePlaceholder.unbind().click(function () {
					jQuery(this).parent().find("input:file").click();
				});
				$file.on('change', function (e) {
					var name = function () {
						try {
							return e.target.files[0].name;
						} catch (error) {
							return '';
						}
					}
					try {
						if (e.target.files[0].size / 1024 > $(e.target).data('maxSize')) {
							var warnText = $(this).closest('.scc-form-field-item').find('.alert.alert-danger');
							warnText.html('<span class="trn">file size excheeds the limit</span>').show() && jQuery(this).val('').trigger('change');
							initTranslationScriptSCC(warnText[0], {
								lang: "en",
								t: translatorJson
							});
							setTimeout(() => {
								warnText.hide(300);
							}, 5000);
						}
					} catch (error) { }
					jQuery('input:text', jQuery(e.target).parent()).val(name);
				});
			}
			jQuery('.scc-file-upload').customFile();
		}

	})
}

//total script
//total script
/**
 * *totalScript 
 * todo: on calculator select, check, slide adds the id of the element to calcule the total
 * ?type attr takes 1 for checkboxes, 2 for dropdown, 3 for cuantityBox, 4 for customMath 5 for slider
 * ?1 needs to evaluete if checked is the id of the element is added, itemsTotal else is remove 
 * ?2 needs to have the previus value, the previus id is replaced with the new id 
 * ?3 needs to have qnt if id doenst exist is added to elementsTotal else is replace with the new qnt
 * ?4 on load the id is added to customMath
 * ?5 the id,qnt,price,scaling is added if doesnt exist else qnt, price, isScaling is replace
 */
function triggerSubmit(type, dom, element, item, calcId) {
	// console.log(calcId)
	var container = document.querySelector("#scc_form_" + calcId)
	// 1 checkboxes, 2 dropdown, 3 quantity box, 4 custom math, 6 radiobutton
	var total = jQuery(".scc-total-" + calcId)
	var numberDom
	var check = false
	switch (type) {
		case 1:
			var p = jQuery(dom).is(":checked")
			if (p) {
				itemsTotal[calcId].push(item)
			} else {
				var index = itemsTotal[calcId].indexOf(item)
				if (index >= 0) {
					itemsTotal[calcId].splice(index, 1)
				}
			}
			break
		case 2:
			var splited = item.split("/")
			var prev = splited[0]
			var now = splited[1]
			if (prev) {
				itemsTotal[calcId]?.splice(itemsTotal[calcId].indexOf(prev), 1)
			}
			if (now != 0) {
				itemsTotal[calcId].push(now)
			}
			break
		case 3:
			if (parseFloat(jQuery(dom).val()) > parseFloat(jQuery(dom).attr('max'))) jQuery(dom).val(jQuery(dom).attr('max'))
			if (parseFloat(jQuery(dom).val()) < parseFloat(jQuery(dom).attr('min'))) jQuery(dom).val(jQuery(dom).attr('min'))
			numberDom = jQuery(dom).val()
			var o = [element, numberDom]
			var ss = elementsTotal[calcId].find(e => e[0] == element)
			if (ss) {
				ss[1] = numberDom
			} else {
				elementsTotal[calcId].push(o)
			}
			if (numberDom == 0) {
				var index = elementsTotal[calcId].indexOf(ss)
				elementsTotal[calcId].splice(index, 1)
			}
			break
		case 4:
			let m = customMath[calcId].includes(element)
			if (!m) {
				customMath[calcId].push(element)
			}
			break
		case 5:
			var elementComposed = element.split("/") //brings id,qnt,price,isScaling  
			var idComposed = elementComposed[0].split("-")
			var id = idComposed[1]
			var qnt = elementComposed[1]
			var price = elementComposed[2]
			var calculationType = elementComposed[3]
			var subcId = elementComposed[4]
			var f = Sliders[calcId].find(s => s[0] == id)
			var o = [id, qnt, price, calculationType, subcId]
			if (f) {
				f[1] = qnt
				f[2] = price
				f[3] = calculationType
			} else {
				Sliders[calcId].push(o)
			}
			break
		case 6:
			var i = item
			var o = document.querySelector("#ssc-elmt-" + element).querySelectorAll("input").forEach(function (e) {
				var popo = e.getAttribute("radio-item-number")
				var index = itemsTotal[calcId].indexOf('chkbx-' + parseInt(popo))
				if (index >= 0) {
					itemsTotal[calcId].splice(index, 1)
				}
			})
			itemsTotal[calcId].push('chkbx-' + i)
			break
		case 7:
			var numberDom2 = jQuery(dom).val()
			var o = [element, numberDom2]
			var ss = elementsComment[calcId].find(e => e[0] == element)
			if (ss) {
				ss[1] = numberDom2
			} else {
				elementsComment[calcId].push(o)
			}
			if (numberDom2 == 0) {
				var index = elementsComment[calcId].indexOf(ss)
				elementsComment[calcId].splice(index, 1)
			}
			break
	}
	checkConditions(calcId)
	/**
	 * *Remove from itemsTotal and elements total if the element is not visible
	 * todo: evaluate acording to types of element, if its hidden remove from elements
	 */
	var as = container.querySelectorAll("[id*=ssc-elmt-]").forEach(function (e) {
		if (e.style.display != 'none') return
		// traer los tipos y de acuerto a los tipos buscar si es select buscar selected value para sacalo de los arrays
		var sa = e.getAttribute("id")
		/**
		 * *For dropdown
		 */
		var drop = e.classList.contains("scc_dropdown_")
		if (drop) {
			var select = e.querySelector("select")
			var o = select ? select.options[select.selectedIndex].value : 0
			var index = itemsTotal[calcId].indexOf(o)
			if (index >= 0) {
				itemsTotal[calcId].splice(index, 1)
			}
		}
		/**
		 * *For Checkboxes
		 */
		var chck = e.classList.contains("scc-chkbx-container")
		if (chck) {
			var ch = e.querySelectorAll("input").forEach(function (e) {
				var s = e.checked
				if (s) {
					var i = e.getAttribute("id").split("_")
					var index = itemsTotal[calcId].indexOf('chkbx-' + parseInt(i[i.length - 1]))
					if (index >= 0) {
						// TODO: handle variable math
						itemsTotal[calcId].splice(index, 1)
					}
				}
			})
		}
		/**
		 * *Quantity box
		 */
		/**
		 * *For Custom math
		 */
		var customM = e.classList.contains("ssc_custom_math__")
		if (customM) {
			var i = e.getAttribute("id").split("-")
			var index = customMath[calcId].indexOf(parseInt(i[i.length - 1]))
			console.log(i)
			if (index >= 0) {
				customMath[calcId].splice(index, 1)
			}
		}
		/**
		 * *Slider
		 */
		var slider = e.classList.contains('scc_sliderr__')
		if (slider) {
			var i = e.getAttribute("id").split("-")
			var index = Sliders[calcId].find(s => s[0] == i[i.length - 1])
			if (index) {
				Sliders[calcId].splice(index, 1)
			}
		}
	})
	/** 
	 * *Add to items total if the elements become visible
	 * todo: evaluate acording to types of element
	 */
	var as = container.querySelectorAll("[id*=ssc-elmt-]").forEach(function (e) {
		if (e.style.display == 'none') return
		// traer los tipos y de acuerto a los tipos buscar si es select buscar selected value para sacalo de los arrays
		/**
		 * *For dropdown
		 */
		var sa = e.getAttribute("id")
		var drop = e.classList.contains("scc_dropdown_")
		if (drop) {
			var select = e.querySelector("select")
			var o = select ? select.options[select.selectedIndex].value : 0
			var index = itemsTotal[calcId].indexOf(o)
			if (index < 0) {
				itemsTotal[calcId].push(o)
			}
		}
		/**
		 * *For Checkboxes
		 */
		var chck = e.classList.contains("scc-chkbx-container")
		if (chck) {
			var ch = e.querySelectorAll("input").forEach(function (e) {
				var s = e.checked
				if (s) {
					var i = e.getAttribute("id").split("_")
					var index = itemsTotal[calcId].indexOf('chkbx-' + parseInt(i[i.length - 1]))
					if (index < 0) {
						itemsTotal[calcId].push('chkbx-' + parseInt(i[i.length - 1]))
					}
				}
			})
		}
		/**
		 * *For Custom math
		 */
		var customM = e.classList.contains("ssc_custom_math__")
		if (customM) {
			var i = e.getAttribute("id").split("-")
			var index = customMath[calcId].indexOf(parseInt(i[i.length - 1]))
			if (index < 0) {
				customMath[calcId].push(parseInt(i[i.length - 1]))
			}
		}

		/**
		* *Slider
		*/
		var slider = e.classList.contains('scc_sliderr__')
		if (slider) {
			var i = e.getAttribute("id").split("-")
			var index = Sliders[calcId].find(s => s[0] == i[i.length - 1])
			if (!index) {
				let sliderNode = e.querySelector('.slider-styled');
				let sliderHandleNode = sliderNode.querySelector('.df-scc-slider-handle');
				let qtn = sliderHandleNode ? parseInt(sliderHandleNode.getAttribute('aria-valuetext')) : sliderNode.getAttribute('data-start-value');
				let scale = sliderNode.getAttribute('data-calculation-type') == "sliding" ? "true" : "false";
				let range = sliderNode.getAttribute('data_range').split(",");
				let subId = sliderNode.getAttribute('data-subId');
				//groups each 3 elements 0 range min 1 range max 2 price
				let grouped = []
				for (let i = 0; i < range.length; i += 3) {
					grouped.push(range.slice(i, i + 3))
				}
				//get price acording to range 
				function getPriceRange(group, qnt2) {
					let o = null
					group.forEach(g => {
						if (parseInt(qnt2) >= parseInt(g[0]) && parseInt(qnt2) <= parseInt(g[1])) {
							o = g[2]
						}
					});
					return o
				}
				//sends again to total script 
				let o = []
				id = i[i.length - 1]
				qnt = qtn
				price = getPriceRange(grouped, qtn)
				isScale = scale
				subcId = subId
				o = [id, qnt, price, isScale, subcId]
				if (qnt != 0) {
					Sliders[calcId].push(o)
				}
			}
		}
	})
	/**
	 * *Gets the prices of elements, except for slider
	 * ?the prices are found with the element value received in the method in the calculator data, if esxists the amount is added to
	 * ?sections total, the total is calculated for sections to be shown in section subtotal if enabled
	 * ?elments /items are added to itemsArray to be used in detail, id, name, qnt, price, description !for slider if description is true means slidingScale is set true
	 * ?maths (custom math modifies the total)
	 */
	let {
		sections
	} = sccData[calcId].config
	// elementitem
	var mathArray = [] //for detail
	var arraySectItems = []
	itemsArray[calcId] = []
	mathArray[calcId] = []
	arraySectItems[calcId] = []
	sections.forEach(secs => {
		var tot = 0
		var secOne = []
		var maths = []
		var sub = secs.subsection
		if (sub.length) {
			itemsArray[calcId].push({
				section: secs.name,
				sectionId: secs.id,
				showSectionTotalOnPdf: secs.showSectionTotalOnPdf
			});
		}
		sub.forEach(subs => {
			var elmts = subs.element
			elementsTotal[calcId].forEach(et => {
				var itemOne = []
				var f = elmts.find(el1 => el1.id == et[0])
				// Multiplies the quantity box with the slider
				var qt = 1
				Sliders[calcId].forEach(scs => {
					var g = elmts.find(itt => itt.subsection_id == scs[4])
					if (g) {
						qt = scs[1]
					}
				})
				if (f) { //quantity box
					tot = parseFloat(tot) + parseFloat(f.value2) * (parseFloat(et[1]) * qt)
					itemOne["id_element"] = f.id
					itemOne["qtn"] = et[1] * qt
					itemOne["name"] = f.titleElement
					itemOne["description"] = ""
					itemOne["price"] = f.value2
					itemOne["woo_commerce"] = {
						product_id: parseInt(f.element_woocomerce_product_id) || 0,
						quantity: parseFloat(itemOne["qtn"])
					}
					itemsArray[calcId].push(itemOne)
				}
			});
			elementsComment[calcId].forEach(et1 => {
				var itemOne = []
				var f = elmts.find(el1 => el1.id == et1[0])
				if (f) { //quantity box
					tot = parseFloat(tot) + 0
					itemOne["id_element"] = f.id
					itemOne["qtn"] = 0
					itemOne["name"] = f.titleElement
					itemOne["type"] = "comment",
						itemOne["description"] = et1[1]
					itemOne["price"] = 0
					itemsArray[calcId].push(itemOne)
				}
			});
			customMath[calcId].forEach(cm => {
				var itemOne = []
				var f = elmts.find(mm => mm.id == cm)
				if (f) {
					var m = {
						action: f.value1,
						value: f.value2,
						showInDetail: f.displayFrontend,
						customMathId: f.id
					}
					mathArray[calcId].push(m)
					maths.push(m)
					itemOne["id_element"] = f.id
					itemOne["qtn"] = 0
					itemOne["name"] = f.titleElement
					itemOne["description"] = f.value1
					itemOne["type"] = "customMath",
						itemOne["price"] = f.value2
					if (f.displayDetailList == 1) {
						itemOne["displayDetailList"] = true
					} else {
						itemOne["displayDetailList"] = false
					}
					itemsArray[calcId].push(itemOne)
				}
			});
			Sliders[calcId].forEach(Ss => {
				// match against currently available elements in this subsection
				var f = elmts.find(s => s.id == Ss[0])
				// converting possible null value to number
				Ss[2] = Number(Ss[2])
				var itemOne = []
				if (f) {
					itemOne["id_element"] = f.id
					itemOne["qtn"] = Ss[1]
					itemOne["name"] = f.titleElement
					itemOne["description"] = Ss[3]
					itemOne["calculationType"] = Ss[3]
					itemOne["price"] = Ss[2]
					itemOne["type"] = "slider"
					itemOne["displayDetailList"] = f.displayDetailList == 3 ? false : true
					itemOne["woo_commerce"] = {
						product_id: parseInt(f.elementitems[0].woocomerce_product_id) || 0,
						quantity: parseFloat(itemOne["qtn"])
					}
					// Here, Ss[3] is to determine if it is a sliding scale
					switch (Ss[3]) {
						case "sliding":
							tot = parseFloat(tot) + parseFloat(Ss[2])
							itemsArray[calcId].push(itemOne)
							break

						case "quantity_mod":
							itemsArray[calcId].push(itemOne)
							break

						case "default":
							tot = parseFloat(tot) + parseFloat(Ss[1]) * parseFloat(Ss[2])
							itemsArray[calcId].push(itemOne)
							break

						default:
							tot = parseFloat(tot) + parseFloat(Ss[1]) * parseFloat(Ss[2])
							itemsArray[calcId].push(itemOne)
							break
					}
					itemsArray[calcId].push(itemOne)
				}
			});
			elmts.forEach(itms => {
				/** if the current element is a dropdown, add dropdownTitle property to it's items
				 * to reference it in webhook as a title of the dropdown and the choice items name
				 */
				if (itms.type == "Dropdown Menu") {
					itms.elementitems = itms.elementitems.map(elementitem => {
						elementitem.dropdownTitle = itms.titleElement
						elementitem.showTitlePdf = itms.showTitlePdf
						return elementitem
					})
				}
				var num = 0
				/**
				 * *Multiplies the number of items in the same subsection
				 */
				var q = 1
				Sliders[calcId].forEach(scs => {
					var g = elmts.find(itt => itt.subsection_id == scs[4])
					if (g) {
						q = scs[1]
					}
				})
				var items = itms.elementitems
				itemsTotal[calcId].forEach(it => {
					var itemOne = []
					var f = items.find(el2 => itms.type == 'checkbox' ? 'chkbx-' + el2.id == it : el2.id == it)
					// console.log(f)
					if (f) {
						num = f.price
						tot = parseFloat(tot) + parseFloat(num) * q
						itemOne["id_elementItem"] = f.id
						itemOne["element_id"] = itms.id
						itemOne["qtn"] = q
						itemOne["name"] = f.name.replace(/\\/g, "")
						itemOne["description"] = f.description.replace(/\\/g, "")
						itemOne["price"] = f.price
						if (f.dropdownTitle) {
							itemOne["dropdownTitle"] = f.dropdownTitle
							itemOne["showTitlePdf"] = f.showTitlePdf
						}
						itemOne["woo_commerce"] = {
							product_id: parseInt(f.woocomerce_product_id) || 0,
							quantity: parseFloat(itemOne["qtn"])
						}
						itemsArray[calcId].push(itemOne)
					}
				});
			});
		});
		// totalSec = totalNew            
		secOne["id"] = secs.id
		secOne["total"] = tot
		secOne["math"] = maths
		arraySectItems[calcId].push(secOne)
	});
	var currency = sccData[calcId].config.currencyCode
	var no_currency = sccData[calcId].config.removeCurrency
	var symbolType = sccData[calcId].config.useCurrencyLetters
	var cur_symbol = currencySymbol(currency)
	var totalTotal = []
	totalTotal[calcId] = 0
	arraySectItems[calcId].forEach(element => {
		var totalOld = element.total
		var totalNew = 0
		var adsdas = totalOld % 10
		if (element.math.length > 0) {
			element.math.forEach(m => {
				if (m.value == "") {
					m.value = 0
					console.warn("You must set a value in custom math")
				}
				if (m.action == "x") m.action = "*"
				if (m.action == "%") {
					totalNew = totalOld + (totalOld * (parseFloat(m.value) / 100))
					itemsArray[calcId].find(e => e.id_element == m.customMathId).calculatedValue = totalOld * (parseFloat(m.value) / 100)
					if (totalNew > 0) {
						totalOld = totalNew
					}
				} else {
					totalNew = eval(totalOld + m.action + m.value)
					itemsArray[calcId].find(e => e.id_element == m.customMathId).calculatedValue = m.value
					if (totalNew > 0) {
						totalOld = totalNew
					}
				}
			});
		}
		/**
		 * *changes symbol type
		 */
		jQuery(".scc-subtotal-amout-" + element.id).html(getCurrencyText(calcId, totalOld))
		totalTotal[calcId] = totalTotal[calcId] + totalOld
	});
	/**
	 * *process cupon discount if applied
	 */
	let {
		coupon
	} = sccData[calcId]
	let couponData = {}
	if (coupon && coupon?.isActive) {
		let {
			useCurrencyLetters,
			currencyCode
		} = sccData[calcId].config
		let _currencySymbol = currencySymbol(currencyCode)
		let getCurrencyText = (amount) => useCurrencyLetters ? _currencySymbol + ' ' + amount : amount + currencyCode
		if (Number(coupon.discountpercentage) != -1) {
			let discountAmount = totalTotal[calcId] * (parseFloat(coupon.discountpercentage) / 100)
			totalTotal[calcId] = totalTotal[calcId] - discountAmount
			let discountAmountText = getCurrencyText(discountAmount.toFixed(2))
			couponData = {
				type: "coupon",
				attr: {
					title: `${sccGetTranslationByKey(calcId, 'Coupon Discount')} ${coupon.discountpercentage}%`,
					discount_price: `-${discountAmountText}`
				}
			}
			showDiscountNotice(calcId, {
				discountAmountText,
				percent: coupon.discountpercentage
			})
		}
		if (Number(coupon.discountvalue) != -1) {
			let discountAmount = coupon.discountvalue
			totalTotal[calcId] = totalTotal[calcId] - discountAmount
			let discountAmountText = getCurrencyText(discountAmount.toFixed(2))
			couponData = {
				type: "coupon",
				attr: {
					title: sccGetTranslationByKey('Applied discount'),
					discount_price: `-${discountAmountText}`
				}
			}
			showDiscountNotice(calcId, {
				discountAmountText,
				discountvalue: coupon.discountvalue
			})
		}
	}
	/**
	 * *Added tax from calculator settings to total script
	 */
	let subTotalBeforeTax = totalTotal[calcId]
	let tax_vat = parseFloat(sccData[calcId].config.taxVat)
	if (tax_vat && tax_vat > 0) {
		tax_vat = tax_vat / 100
		tax_vat = totalTotal[calcId] * tax_vat
		if (container.querySelector(".tax-placeholder"))
			container.querySelector(".tax-placeholder").textContent = getCurrencyText(calcId, tax_vat)
		subTotalBeforeTax = totalTotal[calcId]
		totalTotal[calcId] = totalTotal[calcId] + tax_vat
	}
	if (scc_rate_label_convertion != null) {
		var rate_label = scc_rate_label_convertion.split("/")
		var rate = rate_label[0]
		var label = rate_label[1]
		var total_converted = parseFloat(rate) * totalTotal[calcId]
		if (container.querySelector(".df_scc_cc_span"))
			container.querySelector(".df_scc_cc_span").innerHTML = "(" + getCurrencyText(calcId, total_converted) + " " + label + ")"
	}
	let minOpt = []
	minOpt[calcId] = sccData[calcId].config.minimumTotalChoose
	/**
	 * *handle min total from calculator settings
	 */
	var minNumber = []
	minNumber[calcId] = parseFloat(sccData[calcId].config.minimumTotal)
	if (minNumber[calcId] > 0) {
		if (totalTotal[calcId] >= minNumber[calcId]) {
			container.querySelector("span.scc-minimum-msg").style.display = "none"
			container.querySelector(".scc-btn-hndle").style.display = ""
			if (container.find(".totalPrice"))
				container.find(".totalPrice").style.display = ""
		} else {
			if (minOpt[calcId] == "show-notice") {
				container.querySelector("span.scc-minimum-msg").style.cssText
					`display: inline-block,
					margin-top: 20px,
					margin-bottom: 20px`
			} else {
				container.querySelector(".totalPrice").style.display = "none"
			}
			container.querySelector(".scc-btn-hndle").style.display = "none"
		}
	}
	//?set total to preview and supports comma and browser separator
	total.text(getCurrencyText(calcId, parseFloat(totalTotal[calcId])))
	// handle multiple total
	sccData[calcId].total = totalTotal[calcId];
	jQuery('.scc-multiple-total-wrapper.calcid-' + calcId).find('.scc-total').text(parseFloat(totalTotal[calcId]).toFixed(2)).trigger('doMath');
	jQuery('.scc-multiple-total-wrapper.scc-combination').trigger('valueChange');
	var jsonDetail = [itemsArray, mathArray, {
		total: totalTotal[calcId]
	}, tax_vat]
	var taxParcent = (sccData[calcId].config.taxVat) ? sccData[calcId].config.taxVat : 0
	// we remove the section from the itemsArray
	let rawItems = itemsArray[calcId].filter(e => typeof (e.section) == "undefined")
	addItemsToPayPalForm(rawItems, null, null, calcId, null)
	sccData[calcId].webhook = buildWebhookData([rawItems, {
		total: totalTotal[calcId]
	}])
	console.log(rawItems)
	let checkboxToItemsMapping = [];
	let sortedItems = [];
	let itemsOrdered = [];
	vv = sections
		.map((q) => q.subsection.map((e) => ({ [q.id]: e.element })))
		.map((eq) => { return eq.flat() }).flat();
	[... new Set(vv.map(e => Object.keys(e)[0]))].forEach(eb => {
		console.log(itemsArray[calcId].find(e => e?.sectionId == eb))
		itemsOrdered.push(itemsArray[calcId].find(e => e?.sectionId == eb))
		vv.filter(e => Object.keys(e)[0] == eb).map(e => e[eb]).flat().forEach(item => {
			if (item.type == "checkbox") {
				item.elementitems.forEach(checkboxItem => {
					checkboxItem.type = "checkbox";
					itemsOrdered.push(checkboxItem);
					return;
				})
			}
			itemsOrdered.push(item)
		})
	});
	itemsOrdered.forEach((e) => {
		let res = itemsArray[calcId].find((eq) => {
			let resp = false;
			if (e.type == "Dropdown Menu" || e.type == "checkbox") {
				if (e.type == "checkbox") {
					resp = e.id == eq.id_elementItem;
				} else {
					resp = e.elementitems.find((ez) => ez.id == eq.id_elementItem);
				}
			} else if (e?.sectionId && eq?.sectionId) {
				resp = e.sectionId == eq.sectionId;
			} else if (e.id) {
				resp = eq.id_element == e.id;
			}
			return resp;
		});
		if (res) {
			sortedItems.push(res);
		}
	});
	for (let index = 0; index < itemsOrdered.length; index++) {
		const element = itemsOrdered[index];
		if (element.type == "checkbox") {
			checkboxToItemsMapping.push({
				targetIndex: index,
				items: element.elementitems
			})
		}
	}
	if (checkboxToItemsMapping.length) {
	}
	sccData[calcId].stripeCart = buildStripeData(rawItems)
	sccData[calcId].pdf = buildDetailViewData(sortedItems, sccData[calcId].config.formname, totalTotal[calcId], calcId, tax_vat, taxParcent, subTotalBeforeTax, couponData, total_converted)
}
//handles mandatory items
function verifiedMandatoryItems(calcId) {
	var container = document.querySelector("#scc_form_" + calcId)
	container.querySelectorAll( '.scc-mandatory-msg' ).forEach( function( e ) {
		e.classList.remove( 'scc-d-flex' );
		e.classList.add( 'scc-hidden' );
	} );
	var o = elementsId4Mandatory(calcId)
	var oppo = getNeededIds(calcId)
	function elementsId4Mandatory(ca) {
		let ee_i = []
		ee_i[calcId] = []
		itemsArray[calcId].forEach(iar => {
			if (iar.id_element) {
				ee_i[ca].push(iar.id_element)
			}
			if (iar.element_id) {
				ee_i[ca].push(iar.element_id)
			}
		});
		return ee_i
	}
	//returns mandatory ids that arent in array
	function getNeededIds(ca) {
		var ids = []
		ids[ca] = []
		mandatoryElements[ca].forEach(m1 => {
			//check if element is visible, if not visible is not mandatory 
			let e = document.querySelector('#ssc-elmt-' + m1).style.display == 'none'
			if (jQuery.inArray(m1, o[ca]) == -1 && !e) {
				ids[ca].push(m1)
			}
		});
		return ids
	}
	//mandatories ids not selected
	for (let i = 0; i < oppo[calcId].length; i++) {
		const currentElement = document.querySelector( '#ssc-elmt-' + oppo[ calcId ][ i ] );

		const alertDangerElement = currentElement.querySelector( '.scc-mandatory-msg' );
		if ( alertDangerElement ) {
			alertDangerElement.classList.remove( 'scc-hidden' );
			alertDangerElement.classList.add( 'scc-d-flex' );
		}

		const nextSiblingElement = currentElement.nextElementSibling;
		if ( nextSiblingElement && nextSiblingElement.classList.contains( 'scc-mandatory-msg' ) ) {
			nextSiblingElement.classList.remove( 'scc-hidden' );
			nextSiblingElement.classList.add( 'scc-d-flex' );
		}


		if (i == 0) {
			jQuery([document.documentElement, document.body]).animate({
				scrollTop: jQuery("#ssc-elmt-" + oppo[calcId][i]).offset().top - 50
			}, 1000);
		}
	}
	if (oppo[calcId].length > 0) {
		return false
	} else {
		return true
	}
}

function mandatory_elements() {
	let mandatories = []
	Object.keys(sccData).forEach(function (calcId) {
		mandatories[calcId] = []
		let { sections } = sccData[calcId].config
		sections.forEach(s => {
			s.subsection.forEach(su => {
				su.element.forEach(e => {
					if (e.mandatory == "1") {
						mandatories[calcId].push(e.id);
					}
				});
			});
		});
	})
	return mandatories
}

/**
 * *CurrencySymbol is used in slider
 * !currently in slider its called statictly and needs to be loaded from wpoptions
 * !should be implemented in total script, currently is static
 */
function currencySymbol(currency) {
	var symbol = scc_currencies.find(x => x.code == currency).symbol
	return symbol ? symbol : currency
}

function buildWebhookData(rawItemsData) {
	return rawItemsData[0].map(e => {
		return {
			[e.dropdownTitle ? e.dropdownTitle : e.name]: {
				name: e.name,
				unit: e.qtn,
				value: e.price,
				unit_price: e.price,
				woo_commerce: {},
				element_price: e.price
			}
		}
	});
}
function buildStripeData(cartItems) {
	return cartItems.map(e => {
		return {
			name: e.name,
			valuePerUnit: e?.calculatedValue ? e.calculatedValue : e.price,
			units: e.qtn ? e.qtn : 1
		}
	})
}
function buildDetailViewData(rawData, formname, total, calcId, taxAmount = 0, taxParcent = 0, subTotalBeforeTax = 0, coupon, total_converted = null) {
	let { pdf: pdfConfig } = sccData[calcId].config
	// remove empty section title from PDF
	for (let index = 0; index < rawData.length; index++) {
		// get the row by index
		const row = rawData[index];
		// if quantity is 0, we remove it
		if (row?.qtn <= 0 && !Boolean(["customMath", "comment"].findIndex(e => e == row?.type))) {
			delete rawData[index];
		}
		// exclude the items with unit value 0.001 from detailed view
		if (row?.price == 0.001) {
			delete rawData[index];
		}
		// if last row and a section, we remove it
		if ((index + 1) == rawData.length && row?.section) {
			delete rawData[index];
		}
		// if the section is followed by another section, remove the section
		const nextRow = rawData[index + 1];
		if (row?.section && nextRow?.section) {
			delete rawData[index]
		}
	}
	// remove empty undefined values from array
	rawData = rawData.filter(e => typeof (e) !== 'undefined')
	let hasTax = taxAmount > 0
	if (hasTax) {
		subTotalBeforeTax = getCurrencyText(calcId, subTotalBeforeTax)
		taxAmount = getCurrencyText(calcId, taxAmount)
	}
	total = getCurrencyText(calcId, total)
	let rows = rawData.map(e => {
		let total_price = getCurrencyText(calcId, parseInt(e.qtn) * parseFloat(e.price))
		let unit_price = getCurrencyText(calcId, e.price)
		if ((e?.displayDetailList) !== undefined && !e.displayDetailList) {
			return
		}
		if ((e?.displayDetailList) !== undefined && e.displayDetailList && e?.calculationType == undefined) {
			return {
				type: "custom_math",
				attr: {
					name: e.name,
					total_price: getCurrencyText(calcId, e.calculatedValue),
					unit: parseInt(e.qtn),
					show_detailed_list: true,
					unit_price,
					element_type: "custom_math",
					math_type: ['%', '/'].includes(e.description) ? '' : e.description,
					value: parseFloat(e.price),
					woo_commerce: e.woo_commerce
				}
			}
		}
		if (e?.type == "comment") {
			return {
				type: "comment",
				attr: {
					title: e.name,
					comment: e.description
				}
			}
		}
		if (e?.section !== undefined) {
			return {
				section_title: e.section,
				type: 'section_title',
				showSectionTotalOnPdf: e?.showSectionTotalOnPdf == "true" ? true : false
			}
		}
		if (e?.calculationType == "sliding") {
			total_price = getCurrencyText(calcId, parseFloat(e.price))
			return {
				type: "element",
				attr: {
					name: e.name,
					total_price,
					unit: parseInt(e.qtn),
					unit_price,
					isSlidingScale: true,
					value: parseFloat(e.price),
					woo_commerce: e.woo_commerce,
					numeric_value: parseFloat(e.price)
				}
			}
		}
		if (e?.calculationType == "quantity_mod") {
			return {
				type: "element",
				attr: {
					name: e.name,
					total_price: 0,
					unit: parseInt(e.qtn),
					unit_price: 0,
					isSlidingScale: false,
					isQuantityModifier: true,
					value: 0,
					numeric_value: 0
				}
			}
		}
		if (e.dropdownTitle && e.showTitlePdf == "1") {
			return {
				elementTitle: e.dropdownTitle,
				type: "element_dropdown",
				attr: {
					name: e.name + ": " + e.dropdownTitle,
					description: e.description,
					total_price,
					unit: parseInt(e.qtn),
					unit_price,
					value: parseFloat(e.price),
					woo_commerce: e.woo_commerce
				}
			}
		} else if (e.dropdownTitle && e.showTitlePdf == "0") {
			return {
				elementTitle: e.dropdownTitle,
				type: "element_dropdown",
				attr: {
					name: e.name,
					description: e.description,
					total_price,
					unit: parseInt(e.qtn),
					unit_price,
					value: parseFloat(e.price),
					woo_commerce: e.woo_commerce
				}
			}
		}
		return {
			type: "element",
			attr: {
				name: e.name,
				total_price,
				unit: parseInt(e.qtn),
				unit_price,
				value: parseFloat(e.price),
				woo_commerce: e.woo_commerce
			}
		}
	});

	// remove empty undefined values from array
	rows = rows.filter(e => typeof (e) !== 'undefined')
	// filtering out empty section titles
	for (let index = 0; index < rows.length; index++) {
		// get the row by index
		const row = rows[index];
		// if last row and a section, we remove it
		if ((index + 1) == rows.length && row?.section_title) {
			delete rows[index];
		}
		// if the section is followed by another section, remove the section
		const nextRow = rows[index + 1];
		if (row?.section_title && nextRow?.section_title) {
			delete rows[index];
		}
	}

	/**
	 * if coupon is not empty, add it as a row
	 * */
	if (Object.keys(coupon).length) {
		rows.push(coupon)
	}
	if (hasTax && !pdfConfig.turnoffTax) {
		rows.push({
			type: "subtotal_tax",
			attr: {
				price: subTotalBeforeTax,
				title: sccGetTranslationByKey(calcId, 'Subtotal')
			}
		}, {
			type: "tax",
			attr: {
				price: taxAmount,
				title: sccGetTranslationByKey(calcId, 'TAX') + ' ' + taxParcent + '%'
			}
		})
	}
	// remove empty undefined values from array
	rows = rows.filter(e => typeof (e) !== 'undefined')
	/**
	 * define total text to send to the pdfjson, detect convertion to so we can include it on the total text, 
	 * compatibility with old format
	 */
	let totalText = total_converted ? total + `<span class="df-scc-detailview-cc" style="font-size:70%;display:block"> (${total_converted}) </span>` : total
	rows.push({
		type: "total",
		attr: {
			price: totalText,
			title: sccGetTranslationByKey(calcId, 'Total Price')
		}
	})
	/**
	 * To add the values of the items inside a section, we take the index value of the sections 
	 * and push the items to that index for later calculations against the items
	 */
	let itemsTosectionIndexMapping = [];
	for (let index = 0; index < rows.length; index++) {
		const row = rows[index];
		if (row) {
			if (row.type == "section_title" && !itemsTosectionIndexMapping[index] && row.showSectionTotalOnPdf) {
				itemsTosectionIndexMapping[index] = [];
			}
			if ((row.type == "element" || row.type == "element_dropdown") && itemsTosectionIndexMapping.length) {
				itemsTosectionIndexMapping[Object.keys(itemsTosectionIndexMapping).slice(-1)[0]].push(row);
			}
		}
	}
	/**
	 * Here, the items are calculated and the subtotal value is returned against each index
	 */
	let sectionSubTotals = itemsTosectionIndexMapping.map(e => {
		let subtotal = 0;
		e.forEach(ee => {
			subtotal += ee.attr.isSlidingScale ? ee.attr.value : ee.attr.value * ee.attr?.unit;
		});
		return subtotal;
	})
	let sectionSubTotalsKeys = Object.keys(sectionSubTotals);
	for (let index = 0; index < sectionSubTotalsKeys.length; index++) {
		const element = sectionSubTotals[index];
		if (sectionSubTotalsKeys[index + 1]) {
			let targetIndex = sectionSubTotalsKeys.slice(-1)[0];
			let sectionSubTotal = sectionSubTotals[sectionSubTotalsKeys[index]]
			rows = [...rows.slice(0, targetIndex), {
				type: "section_subtotal",
				attr: {
					name: sccGetTranslationByKey(calcId, 'SubTotal'),
					total_price: getCurrencyText(calcId, sectionSubTotal.toFixed(2)),
					unit: '',
					unit_price: ''
				}
			}, ...rows.slice(targetIndex)];
		} else {
			let totalBeforeTaxIndex = rows.findIndex(e => e && e.type == "subtotal_tax");
			let totalIndex = rows.findIndex(e => e && e.type == "total");
			let targetIndex = totalBeforeTaxIndex > 0 ? totalBeforeTaxIndex : totalIndex;
			let sectionSubTotal = sectionSubTotals[sectionSubTotalsKeys[index]];
			rows = [...rows.slice(0, targetIndex), {
				type: "section_subtotal",
				attr: {
					name: sccGetTranslationByKey(calcId, 'SubTotal'),
					total_price: getCurrencyText(calcId, sectionSubTotal.toFixed(2)),
					unit: '',
					unit_price: ''
				}
			}, ...rows.slice(targetIndex)];
		}
	}
	return {
		banner_image: pdfConfig.bannerImage,
		date: outputDate(pdfConfig.dateFormat),
		description: sccGetTranslationByKey(calcId, "Description"),
		footer: pdfConfig.footer,
		logo_image: pdfConfig.logo,
		pdf_title: pdfConfig.removeTitle ? '' : formname,
		price: sccGetTranslationByKey(calcId, "Price"),
		quantity: sccGetTranslationByKey(calcId, "Quantity"),
		unit: sccGetTranslationByKey(calcId, "Unit Price"),
		hasTax,
		taxAmount,
		taxParcent,
		subTotalBeforeTax,
		rows,
		total
	}
}

/**
	 * *Handles the conditional logic for elements
	 * todo: two types of conditionals, conditionals elements and conditional elemenitms
	 * ?if that conditional doesnt match hides the element
	 * !needs to load in document load
	 */
function checkConditions(calcId) {
	return
}

/**
	 * *Price hint
	 */
function pricehint(calcId, element, value) {
	let { currencyCode, useCurrencyLetters } = sccData[calcId].config
	var o = jQuery(element).is(":checked")
	let symbole = currencySymbol(currencyCode)
	let plus = '+ '
	if (o && value == 1) {
		var p = jQuery(element).parent().parent().find('.price-hint-text').html()
		var label = jQuery(element).parent().parent().find('.price-hint-text')
		if (!label.is(":visible")) {
			symbole = (!label.text().includes(symbole)) ? symbole : ''
			currencyCode = (!label.text().includes(currencyCode)) ? currencyCode : ''
			plus = (!label.text().includes(plus)) ? plus : ''
			if (!useCurrencyLetters) label.text(plus + symbole + p).css('display', 'inline-block').delay(3000).fadeOut('slow');
			if (useCurrencyLetters) label.text(plus + p + currencyCode).css('display', 'inline-block').delay(3000).fadeOut('slow');
		}
	}
}


/**
	 * *Change qnt of quantity input box when the type is set to compact
	 * !sends data to totalScript, id and element
	 */
function changeNumberQuantity(dom, type, idElement, calcId) {
	switch (type) {
		case "-":
			var input = jQuery(dom).next("input")
			var val = input.val()
			if (!val) {
				val = 0
				input.val(val)
			}
			if (val > 0) {
				val--
				input.val(val)
			}
			triggerSubmit(3, input, idElement, 0, calcId)
			break
		case "+":
			var input = jQuery(dom).prev("input")
			var val = input.val()
			if (!val) {
				val = 0
				input.val(val)
			}
			if (val >= 0) {
				val++
				input.val(val)
			}
			triggerSubmit(3, input, idElement, 0, calcId)
			break
	}
}

function getCurrency(dataScc) {
	let {
		currencyCode,
		currency_conversion_mode,
		currency_conversion_selection
	} = sccData[dataScc].config
	if (currency_conversion_mode != "off") {
		if (currency_conversion_mode == 'manual_selection') {
			const exchangeRatesApiKeys = ['6MGLLRKYSJ12H4FB', 'Y3WQDA71TDUO57XB', 'LYIDFE0YTNPYCBDT', 'W9IZARQX9UIXEUOM'];
			const hour = new Date().getUTCHours();
			const selectKey = (hour) => {
				return (hour % 6 < 5) ? hour % 6 : 1;
			};
			const currentKey = exchangeRatesApiKeys[selectKey(hour)];
			jQuery.ajax({
				type: 'GET',
				url: `https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency=${currencyCode}&to_currency=${currency_conversion_selection}&apikey=${currentKey}`,
				cache: false,
				success: function (res) {
					let o = res["Realtime Currency Exchange Rate"]["9. Ask Price"] + '/' + currency_conversion_selection
					get_rate_label_convertion(o)
				}
			})
		} else if (currency_conversion_mode == 'auto_detect') {
			jQuery.ajax({
				type: 'GET',
				url: 'https://httpbin.org/ip',
				cache: false
			}).then(function (res) {
				return jQuery.ajax({
					type: 'GET',
					url: `https://ipapi.co/${res.origin}/currency/`,
					cache: false,
				})
			}).then(function (res) {
				const exchangeRatesApiKeys = ['6MGLLRKYSJ12H4FB', 'Y3WQDA71TDUO57XB', 'LYIDFE0YTNPYCBDT', 'W9IZARQX9UIXEUOM'];
				const hour = new Date().getUTCHours();
				const selectKey = (hour) => {
					return (hour % 6 < 5) ? hour % 6 : 1;
				};
				const currentKey = exchangeRatesApiKeys[selectKey(hour)];
				return jQuery.ajax({
					type: 'GET',
					url: `https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency=${currencyCode}&to_currency=${res}&apikey=${currentKey}`,
					cache: false,
				})
			}).done(function (res) {
				console.log(res)
				let o = res["Realtime Currency Exchange Rate"]["9. Ask Price"] + '/' + res["Realtime Currency Exchange Rate"]["3. To_Currency Code"]
				get_rate_label_convertion(o)
			}).fail(function (err) {
				console.error(err)
			})
		}
		function get_rate_label_convertion(ss) {
			scc_rate_label_convertion = ss
		}
	}
}

/**
 * *Display sign in amounts in slider
 * !needs to be used in total value, currently static
 */
function priceCommaStyler(price) {
	let o = Object.keys(sccData)[0]
	let { tseparator } = sccData[o].config
	let isCommaBasedStyling = false
	if (tseparator == 'comma') {
		isCommaBasedStyling = true
	}
	price = price + '';
	if (isCommaBasedStyling) {
		var dot = price.indexOf('.') === -1 ? false : true
		var newPrice = ''
		for (var i = (price.split('').length - 1); i >= 0; i--) {
			if (price.indexOf('-') === -1) {
				if (!dot && (i === ((price.split('').length - 1) - 3) || i === ((price.split('').length - 1) - 6) || i === ((price.split('').length - 1) - 9))) {
					newPrice = ',' + newPrice
				} else if (dot && (i === ((price.split('').length - 1) - 6) || i === ((price.split('').length - 1) - 9) || i === ((price.split('').length - 1) - 12))) {
					newPrice = ',' + newPrice
				}
				newPrice = price.split('')[i] + newPrice
			} else {
				if (!dot && (i === ((price.split('').length - 1) - 4) || i === ((price.split('').length - 1) - 6) || i === ((price.split('').length - 1) - 9))) {
					if (price.split('').length > 4) {
						newPrice = ',' + newPrice
					}
				} else if (dot && (i === ((price.split('').length - 1) - 6) || i === ((price.split('').length - 1) - 9) || i === ((price.split('').length - 1) - 12))) {
					if (price.split('').length > 7) {
						newPrice = ',' + newPrice
					}
				}
				newPrice = price.split('')[i] + newPrice
			}
		}
		if (newPrice.length >= 3 && (newPrice.substring(newPrice.length - 3, newPrice.length - 1) === '.00' || newPrice.substring(newPrice.length - 3, newPrice.length) === '.00')) {
			newPrice = newPrice.split('.')[0]
		}
		return newPrice;
	}
	roundedPrice = parseFloat(parseFloat(price).toFixed(2));
	return roundedPrice.toLocaleString(navigator.languages[0]);
}

function getMinimumTotal(calculatorId) {
	let {
		minimumTotal,
		minimumTotalChoose
	} = sccData[calculatorId].config
	var container = jQuery("#scc_form_" + calculatorId)
	if (minimumTotal && parseInt(minimumTotal) > 0) {
		//si hay numero min total
		if (minimumTotalChoose == "show-notice") {
			container.find("span.scc-minimum-msg").css({
				"display": "inline-block",
				"margin-top": "20px",
				"margin-bottom": "50px"
			})
			container.find(".scc-btn-hndle").css("display", "none")
		} else {
			container.find("span.scc-minimum-msg").css("display", "none")
			container.find(".scc-btn-hndle").css("display", "none")
			container.find(".totalPrice").css("display", "none")
		}
	} else {
		container.find("span.scc-minimum-msg").css("display", "none")
		container.find(".scc-btn-hndle").css("display", "")
	}
}

function getTranslation_(calculatorID) {
	let {
		translation
	} = sccData[calculatorID].config
	let container = jQuery("#scc_form_" + calculatorID)
	var dict = {}
	for (let i = 0; i < translation.length; i++) {
		var o = {
			en: translation[i].translation
		}
		dict[translation[i].key] = o
	}
	jQuery(document).ready(function () {
		container.translate({
			lang: "en",
			t: dict
		});
		translateToogle()
		function translateToogle() {
			translation.forEach(t => {
				if (t.key == 'Yes') translateYes(t.translation)
				if (t.key == 'No') translateNo(t.translation)
			});
			function translateYes(value) {
				container.find('.can-toggle__switch').each(function () {
					jQuery(this).attr('data-checked', value)
				})
			}
			function translateNo(value) {
				container.find('.can-toggle__switch').each(function () {
					jQuery(this).attr('data-unchecked', value)
				})
			}
		}
	})
}

// register urlstats
function sccUslStats() {
	if (typeof sccData == "undefined") return
	Object.keys(sccData).forEach(function (calcId) {
		checkConditions(calcId)
		// detect if calculator form is rendered in a not editing page then trigger the dashboard url stats records
		// if (!window.location.href.match('&id_form')) {
		jQuery.ajax({
			url: wp.ajax.settings.url,
			method: 'POST',
			data: {
				action: 'sccUpdateUrlStats',
				url: window.location.href,
				calcId,
				nonce: eval('pageCalcFront' + calcId).nonce
			}
		});
		// }
	})
}


/**
 * *Changes the apearence of checkboxbuttons 
 * !currently the color is static, needs to be taken from calculator settings
 */
function changeButtonToogle(element) {
	var s = jQuery(element).is(":checked")
	if (s) {
		jQuery(element).parent().addClass("clicked-buttom-checkbox")
	} else {
		jQuery(element).parent().removeClass("clicked-buttom-checkbox")
	}
}
