/* Contact Form X */

(function($) {
	
	$(document).ready(function() {
		
		$('.cfx-reset').hide();
		$('.cfx-powered-by').css({ 'background-image' : 'url('+ contactFormX.cfxurl +'img/email-icon.png)' });
		
		contactFormXSetCookie();
		contactFormXGetCookie();
		
		$(document.body).on('click', '.cfx-reset', function(e) {
			e.preventDefault();
			contactFormXReset();
			$('.cfx-reset').hide();
			$('.cfx-form').slideToggle(100);
			$('.cfx-before-form, .cfx-after-form').show();
			$('.cfx-response').empty();
			return false;
		});
		
		$(document.body).on('click', '.cfx-submit', function(e) {
			e.preventDefault();
			if (!$('.cfx-submit').hasClass('cfx-disabled')) {
				
				var name      = $('#cfx-name').val();
				var website   = $('#cfx-website').val();
				var email     = $('#cfx-email').val();
				var subject   = $('#cfx-subject').val();
				var custom    = $('#cfx-custom').val();
				var challenge = $('#cfx-challenge').val();
				var message   = $('#cfx-message').val();
				var agree     = ($('#cfx-agree').is(':checked')) ? true : false;
				var url       = window.location.href;
				var recaptcha = null;
				
				if (contactFormX.carbon === 'alt') {
					var carbon = true;
				} else {
					var carbon = ($('#cfx-carbon').is(':checked')) ? true : false;
				}
				
				if (contactFormX.renable == 1) {
					if (contactFormX.rversion == 2) { // v2
						recaptcha = contactFormXreCaptcha();
						contactFormXAjax(name, website, email, subject, custom, challenge, message, recaptcha, carbon, agree, url);
					} else { // v3
						grecaptcha.ready(function() {
							grecaptcha.execute(contactFormX.rpublic, { action: 'contact' }).then(function(token) {
								recaptcha = token;
								contactFormXAjax(name, website, email, subject, custom, challenge, message, recaptcha, carbon, agree, url);
							});
						});
					}
				} else {
					contactFormXAjax(name, website, email, subject, custom, challenge, message, recaptcha, carbon, agree, url);
				}
			}
			return false;
		}); 
		
		function contactFormXAjax(name, website, email, subject, custom, challenge, message, recaptcha, carbon, agree, url) {
			if (contactFormX.xhr != null) {
				contactFormX.xhr.abort();
				contactFormX.xhr = null;
			}
			contactFormX.xhr = $.ajax({
				type: 'POST',
				url:   contactFormX.ajaxurl,
				data: {
					action:    'contactformx',
					nonce:     contactFormX.nonce,
					name:      name,
					website:   website,
					email:     email,
					subject:   subject,
					custom:    custom,
					challenge: challenge,
					message:   message,
					recaptcha: recaptcha,
					carbon:    carbon,
					agree:     agree, 
					url:       url
				},
				dataType: 'json',
				beforeSend: function() {
					contactFormXSending();
				},
				success: function(data) {
					contactFormXResults(data);
				}
			});
		}
		
		function contactFormXResults(data) {
			contactFormXCheckErrors();
			contactFormXSetValue(data);
			contactFormXDisplayResults(data);
			contactFormXSendingComplete();
		}
		
		function contactFormXCheckErrors() {
			$('#cfx input, #cfx textarea').each(function() {
				var id = $(this).attr('id');
				if (id == 'cfx-carbon' || id == 'cfx-agree') {
					if ($(this).prop('required') && !$(this).is(':checked')) {
						$(this).parent().addClass('cfx-required');
					} else {
						$(this).parent().removeClass('cfx-required');
					}
				} else if (id == 'cfx-challenge') {
					var challenge = contactFormX.challenge;
					var casing    = contactFormX.casing;
					var answer    = $(this).val();
					if (casing == 0) {
						if (challenge.toLowerCase() === answer.toLowerCase()) {
							$(this).parent().removeClass('cfx-required');
						} else {
							$(this).parent().addClass('cfx-required');
						}
					} else {
						if (challenge === answer) {
							$(this).parent().removeClass('cfx-required');
						} else {
							$(this).parent().addClass('cfx-required');
						}
					}
				} else if (id == 'cfx-email') {
					var email = $(this).val();
					var display = contactFormX.email;
					if (display === 'show') {
						if (contactFormXValidateEmail(email) === false) {
							$(this).parent().addClass('cfx-required');
						} else {
							$(this).parent().removeClass('cfx-required');
						}
					} else if (display === 'optn') {
						if (email) {
							if (contactFormXValidateEmail(email) === false) {
								$(this).parent().addClass('cfx-required');
							} else {
								$(this).parent().removeClass('cfx-required');
							}
						} else {
							$(this).parent().removeClass('cfx-required');
						}
					}
				} else if (id == 'g-recaptcha-response') {
					if (contactFormXreCaptcha()) {
						$('.cfx-recaptcha').removeClass('cfx-required');
					} else {
						$('.cfx-recaptcha').addClass('cfx-required');
					}
				} else {
					if ($(this).prop('required') && !this.value) {
						$(this).parent().addClass('cfx-required');
					} else {
						$(this).parent().removeClass('cfx-required');
					}
				}
			});
		}
		
		function contactFormXSetValue(data) {
			$('#cfx-name').val(data['name']);
			$('#cfx-website').val(data['website']);
			$('#cfx-email').val(data['email']);
			$('#cfx-subject').val(data['subject']);
			$('#cfx-custom').val(data['custom']);
			$('#cfx-challenge').val(data['challenge']);
			$('#cfx-message').val(data['message']);
			
			if ((contactFormX.renable == 1) && (contactFormX.rversion == 2)) {
				if (typeof grecaptcha !== 'undefined') grecaptcha.reset();
			}
			if (data['carbon'] == 'true') $('#cfx-carbon').prop('checked', true);
			else $('#cfx-carbon').prop('checked', false);
			
			if (data['agree'] == 'true') $('#cfx-agree').prop('checked', true);
			else $('#cfx-agree').prop('checked', false);
		}
		
		function contactFormXDisplayResults(data) {
			if (data['errors'] !== '') {
				$('.cfx-response').html(data['errors']);
			} else if (data['success'] != '') {
				contactFormXReset();
				$('.cfx-response').html(data['success']);
				if (data['display'] !== 'form') {
					$('.cfx-before-form, .cfx-after-form').hide();
					$('.cfx-form').hide(0, function () {
						$('.cfx-reset').css('display', 'inline-block');
					});
					
				}
			}
		}
		
		function contactFormXReset() {
			$('.cfx-form input[type=text]').val('');
			$('.cfx-form input[type=email]').val('');
			$('.cfx-form input[type=checkbox]').prop('checked', false);
			$('.cfx-form textarea').val('');
			contactFormXForgetCookie();
		}
		
		function contactFormXValidateEmail(email) {
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(String(email).toLowerCase());
		}
		
		function contactFormXreCaptcha() {
			var recaptcha = null;
			if (typeof grecaptcha !== 'undefined') recaptcha = grecaptcha.getResponse();
			return recaptcha;
		}
		
		function contactFormXSending() {
			$('.cfx-submit').attr('disabled', true).addClass('cfx-disabled').html(contactFormX.sending);
		}
		
		function contactFormXSendingComplete() {
			$('.cfx-submit').attr('disabled', false).removeClass('cfx-disabled').html(contactFormX.submit);
		}
		
		function contactFormXSetCookie() {
			$('#cfx input, #cfx textarea').each(function() {
				$(this).on('change', function() {
					var id = $(this).attr('id');
					if (id == 'cfx-carbon' || id == 'cfx-agree') {
						var val = ($(this).is(':checked')) ? true : false;
					} else {
						var val = $(this).val();
					}
					Cookies.set(id, val, { expires: 7, path: '/', secure: true, sameSite: 'strict' });
				});
			});
		}
		
		function contactFormXGetCookie() {
			$('#cfx input, #cfx textarea').each(function() {
				var id = $(this).attr('id');
				var cookie = Cookies.get(id);
				if (cookie) {
					if (id == 'cfx-carbon' || id == 'cfx-agree') {
						if (cookie == 'true') $('#'+ id).prop('checked', true);
						else $('#'+ id).prop('checked', false);
					} else {
						$('#'+ id).val(cookie);
					}
				}
			});
		}
		
		function contactFormXForgetCookie() {
			Cookies.remove('cfx-name',      { path: '/' });
			Cookies.remove('cfx-website',   { path: '/' });
			Cookies.remove('cfx-email',     { path: '/' });
			Cookies.remove('cfx-subject',   { path: '/' });
			Cookies.remove('cfx-custom',    { path: '/' });
			Cookies.remove('cfx-challenge', { path: '/' });
			Cookies.remove('cfx-message',   { path: '/' });
			Cookies.remove('cfx-carbon',    { path: '/' });
			Cookies.remove('cfx-agree',     { path: '/' });
		}
		
	});
	
})(jQuery);