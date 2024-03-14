(function($) {
	'use strict';
	$(document).ready(function() {

		var grecaptchaV2 = $('.stls_grecaptcha_v2');
		var grecaptchaV3 = $('.stls_grecaptcha_v3');
		var cfturnstile = $('.stls_cf_turnstile');
		var captcha = $('.stlsr input[name="captcha"]:checked').val();
		if('google_recaptcha_v2' === captcha) {
			grecaptchaV2.show();
		} else if('google_recaptcha_v3' === captcha) {
			grecaptchaV3.show();
		} else if('cf_turnstile' === captcha) {
			cfturnstile.show();
		}

		$(document).on('change', '.stlsr input[name="captcha"]', function() {
			var captcha = this.value;
			var stlsCapt = $('.stls_capt');
			stlsCapt.hide();
			if('google_recaptcha_v2' === captcha) {
				grecaptchaV2.fadeIn();
			} else if('google_recaptcha_v3' === captcha) {
				grecaptchaV3.fadeIn();
			} else if('cf_turnstile' === captcha) {
				cfturnstile.fadeIn();
			}
		});

		var loginCapt = $('.stls_capt_login');
		var lostPasswordCapt = $('.stls_capt_lostpassword');
		var registerCapt = $('.stls_capt_register');
		var commentCapt = $('.stls_capt_comment');

		if($('#stls_capt_login_enable').is(':checked')) {
			loginCapt.show();
		}
		if($('#stls_capt_lostpassword_enable').is(':checked')) {
			lostPasswordCapt.show();
		}
		if($('#stls_capt_register_enable').is(':checked')) {
			registerCapt.show();
		}
		if($('#stls_capt_comment_enable').is(':checked')) {
			commentCapt.show();
		}

		$(document).on('change', '#stls_capt_login_enable', function() {
			if($(this).is(':checked')) {
				loginCapt.fadeIn();
			} else {
				loginCapt.hide();
			}
		});
		$(document).on('change', '#stls_capt_lostpassword_enable', function() {
			if($(this).is(':checked')) {
				lostPasswordCapt.fadeIn();
			} else {
				lostPasswordCapt.hide();
			}
		});
		$(document).on('change', '#stls_capt_register_enable', function() {
			if($(this).is(':checked')) {
				registerCapt.fadeIn();
			} else {
				registerCapt.hide();
			}
		});
		$(document).on('change', '#stls_capt_comment_enable', function() {
			if($(this).is(':checked')) {
				commentCapt.fadeIn();
			} else {
				commentCapt.hide();
			}
		});

		$(document).on('click', '.st-alert-box-dismiss', function() {
			$(this).parent().fadeOut(300, function() {
				$(this).remove();
			});
		});

		var loadingContainer = $('<span/>', {
			'class': 'st-loading-container'
		});
		var loadingImage = $('<img/>', {
			'src': stlsradminurl + 'images/spinner.gif',
			'class': 'st-loading-image'
		});

		function stlsrBeforeSubmit(formBtn) {
			$('.st-text-danger').remove();
			$('.st-is-invalid').removeClass("st-is-invalid");
			$('.st-alert-box').remove();
			formBtn.prop('disabled', true);
			loadingContainer.insertAfter(formBtn);
			loadingImage.appendTo(loadingContainer);
			return true;
		}

		function stlsrSuccessMessage(message, formId) {
			var alertBox = '' +
			'<div class="st-alert-box notice notice-success is-dismissible">' +
				'<p>' +
					'<strong>' + message + '</strong>' +
				'</p>' +
				'<button type="button" class="st-alert-box-dismiss notice-dismiss"></button>' +
			'</div>';
			$(alertBox).insertBefore(formId);
		}

		function stlsrErrorMessage(message, formId, statusText = '') {
			if(statusText) {
				var statusText = ' ' + statusText;
			}
			var errorSpan = '' +
			'<div class="st-alert-box notice notice-error is-dismissible">' +
				'<p>' +
					'<strong>' + message + '</strong>' + statusText +
				'</p>' +
				'<button type="button" class="st-alert-box-dismiss notice-dismiss"></button>' +
			'</div>';
			$(errorSpan).insertBefore(formId);
		}

		function stlsrFormErrors(formId, response) {
			$(formId + ' :input').each(function() {
				var input = this;
				$(input).removeClass('st-is-invalid');
				if(response.data[input.name]) {
					var errorSpan = '<div class="st-text-danger">' + response.data[input.name] + '</div>';
					$(input).addClass('st-is-invalid');
					$(errorSpan).insertAfter(input);
				}
			});
		}

		// Save captcha settings.
		var saveCaptchaFormId = '#stlsr-save-captcha-form';
		var saveCaptchaForm = $(saveCaptchaFormId);
		var saveCaptchaBtn = $('#stlsr-save-captcha-btn');
		saveCaptchaForm.ajaxForm({
			beforeSubmit: function(arr, $form, options) {
				return stlsrBeforeSubmit(saveCaptchaBtn);
			},
			success: function(response) {
				if(response.success) {
					stlsrSuccessMessage(response.data.message, saveCaptchaFormId);
				} else {
					if(response.data && $.isPlainObject(response.data)) {
						stlsrFormErrors(saveCaptchaFormId, response);
					} else {
						stlsrErrorMessage(response.data, saveCaptchaFormId);
					}
				}
			},
			error: function(response) {
				saveCaptchaBtn.prop('disabled', false);
				stlsrErrorMessage(response.status, saveCaptchaFormId, response.statusText);
			},
			complete: function(event, xhr, settings) {
				saveCaptchaBtn.prop('disabled', false);
				loadingContainer.remove();
			}
		});

		// Clear error logs.
		var clearErrorLogsFormId = '#stlsr-clear-error-logs-form';
		var clearErrorLogsForm = $(clearErrorLogsFormId);
		var clearErrorLogsBtn = $('#stlsr-clear-error-logs-btn');
		$(document).on('click', '#stlsr-clear-error-logs-btn', function(e) {
			e.preventDefault();
			if(confirm(clearErrorLogsBtn.data('message'))) {
				clearErrorLogsForm.ajaxSubmit({
					beforeSubmit: function(arr, $form, options) {
						return stlsrBeforeSubmit(clearErrorLogsBtn);
					},
					success: function(response) {
						if(response.success) {
							stlsrSuccessMessage(response.data.message, clearErrorLogsFormId);
	                		$('.stlsr-error-logs-body').load(location.href + " " + '.stlsr-error-logs-body', function () {});
						} else {
							if(response.data && $.isPlainObject(response.data)) {
								stlsrFormErrors(clearErrorLogsFormId, response);
							} else {
								stlsrErrorMessage(response.data, clearErrorLogsFormId);
							}
						}
					},
					error: function(response) {
						clearErrorLogsBtn.prop('disabled', false);
						stlsrErrorMessage(response.status, clearErrorLogsFormId, response.statusText);
					},
					complete: function(event, xhr, settings) {
						clearErrorLogsBtn.prop('disabled', false);
						loadingContainer.remove();
					}
				});
			}
		});

		// Reset plugin.
		var resetPluginFormId = '#stlsr-reset-plugin-form';
		var resetPluginForm = $(resetPluginFormId);
		var resetPluginBtn = $('#stlsr-reset-plugin-btn');
		$(document).on('click', '#stlsr-reset-plugin-btn', function(e) {
			e.preventDefault();
			if(confirm(resetPluginBtn.data('message'))) {
				resetPluginForm.ajaxSubmit({
					beforeSubmit: function(arr, $form, options) {
						return stlsrBeforeSubmit(resetPluginBtn);
					},
					success: function(response) {
						if(response.success) {
							stlsrSuccessMessage(response.data.message, resetPluginFormId);
						} else {
							if(response.data && $.isPlainObject(response.data)) {
								stlsrFormErrors(resetPluginFormId, response);
							} else {
								stlsrErrorMessage(response.data, resetPluginFormId);
							}
						}
					},
					error: function(response) {
						resetPluginBtn.prop('disabled', false);
						stlsrErrorMessage(response.status, resetPluginFormId, response.statusText);
					},
					complete: function(event, xhr, settings) {
						resetPluginBtn.prop('disabled', false);
						loadingContainer.remove();
					}
				});
			}
		});

	});
})(jQuery);
