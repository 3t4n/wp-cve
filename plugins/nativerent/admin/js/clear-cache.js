//TODO: rewrite to ECMAScript 5

const AJAX_URL = (window.NTRNTData && NTRNTData.ajax_url) || '/wp-admin/admin-ajax.php'

const NTRNT_CC_ACTION = (window.NTRNTData && NTRNTData.action) || 'ntrnt_clear_cache'
const SUCCESS_MESSAGE = (window.NTRNTData && NTRNTData.success_message) || ''
const ERROR_MESSAGE = (window.NTRNTData && NTRNTData.error_message) || ''
const WAIT_MESSAGE = (window.NTRNTData && NTRNTData.wait_message) || ''
const NONCE = (window.NTRNTData && NTRNTData._wpnonce) || ''

const NTRNT_CONTAINER = document.querySelector('#ntrnt-clear-cache')
const NTRNT_CC_BUTTON = NTRNT_CONTAINER?.querySelector('a')

const NTRNT_DN_CONTAINER = document.querySelector('.ntrnt_dismiss_cache_notice')
const NTRNT_DN_ACTION = (window.NTRNTData && NTRNTData.dismiss_action) || 'ntrnt_dismiss_cache_notice'

let ntrnt_cc_start = 0

const encodeParams = (params) => {
	return Object.entries(params).map(key => key.map(encodeURIComponent).join('=')).join('&')
}

const ajaxCCQuery = {
	action: NTRNT_CC_ACTION,
	_wpnonce: NONCE,
}

const ajaxDNQuery = {
	action: NTRNT_DN_ACTION,
	_wpnonce: NONCE,
}

const ntrntRequestOptions = {
	method: 'GET',
	headers: {
		'Content-Type': 'application/x-www-form-urlencoded charset=utf-8',
	},
}

const ntrnt_time_interval = 5 * 60 * 1000
let ntrnt_alert_message = ''

const ntrntCheckJson = (result) => {
	try {
		JSON.parse(result)
	} catch (e) {
		return false
	}
	return true
}

const ntrntMakeCCRequest = async () => {
	if (ntrnt_cc_start !== 0) {
		return
	}

	ntrnt_cc_start = Date.now()
	NTRNT_CONTAINER.classList.add('progress')

	const ntrntResponce = await fetch(`${AJAX_URL}?${encodeParams(ajaxCCQuery)}`, ntrntRequestOptions)
	const ntrntResult = await ntrntResponce.text()

	if (ntrntResult == 1) {
		NTRNT_CC_BUTTON.title = SUCCESS_MESSAGE
		alert(SUCCESS_MESSAGE)
		NTRNT_CONTAINER.closest('.notice').remove()
	} else {
		if (ntrntResult == 0 || !ntrntCheckJson(ntrntResult)) {
			NTRNT_CC_BUTTON.title = ERROR_MESSAGE
			alert(ERROR_MESSAGE)
		} else {
			const ntrntErrorJson = JSON.parse(ntrntResult)
			const ntrntErrorMessage = ntrntErrorJson?.data[0].message
			NTRNT_CC_BUTTON.title = ntrntErrorMessage
			alert(ntrntErrorMessage)
		}

		setTimeout(() => {
			NTRNT_CONTAINER.classList.remove('done')
			ntrnt_cc_start = 0
		}, ntrnt_time_interval)
	}

	ntrnt_cc_start = 0
}

const ntrntCCButtonListener = async () => {
	NTRNT_CC_BUTTON.addEventListener('click', function (event) {
		event.preventDefault()
		if (ntrnt_cc_start > 0) {
			alert(WAIT_MESSAGE)
		} else {
			ntrntMakeCCRequest()
		}
	})
}

if (NTRNT_CC_BUTTON && NONCE) {
	ntrntCCButtonListener()
}

const ntrntMakeDNRequest = async () => {
	await fetch(`${AJAX_URL}?${encodeParams(ajaxDNQuery)}`, ntrntRequestOptions)
}

const ntrntDNButtonListener = async () => {
	NTRNT_DN_CONTAINER.addEventListener('click', function (event) {
		if (event.target.classList.contains('notice-dismiss')) {
			ntrntMakeDNRequest()
		}
	})
}

if (NTRNT_DN_CONTAINER && NONCE) {
	ntrntDNButtonListener()
}

// Deactivation plugin alert
(function ($) {
	$(document).on('click', 'a[id*=deactivate-nativerent]', function (e) {
		alert('Если на сайте настроено кэширование, то после деактивации сбросьте кэш.')
	})
})(window.jQuery)
