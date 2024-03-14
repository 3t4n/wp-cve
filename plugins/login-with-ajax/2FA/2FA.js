jQuery(document).ready( function($){
	let modal = $('#lwa-2FA-modal');
	let container = modal.find('.lwa-2FA');
	let timeouts = {};


	const LWA_TwoFA = {
		isWPLogin : false,
		current : {
			method : {},
			methods : [],
			nonce : '',
			user : '',
			id : '',
			verifier : null,
		},
		loginResult : function( event ){
			let response = event.detail.response;
			let form = event.detail.form;
			let statusElement = event.detail.statusElement;
			LWA_TwoFA.isWPLogin = form.matches('body.login.wp-core-ui #loginform');
			if( response.result && 'TwoFA' in response && form.classList.contains('lwa-form') ){
				LWA_TwoFA.current = response.TwoFA;
				modal.hide(); // hide until processed and cleaned up
				container.find('.lwa-status').remove();
				container.find('.lwa-2FA-method input[name="2FA_code"]').val('');
				// add generic values
				if( !form.getAttribute('id') ){
					// compat for forms without IDs
					form.setAttribute('id', 'lwa-login-form-' + Math.floor(Math.random() * 10000) );
				}
				container.attr('data-bound-form', '#'+form.getAttribute('id'));
				container.find('.lwa-2FA-method, form.lwa-2FA-method-selection').attr('data-bound-form', '#'+form.getAttribute('id'));
				container.find('.lwa-2FA-data-id').val( response.TwoFA.id );
				// load methods or show user intro/setup page
				if( response.TwoFA.methods ) {
					// populate the methods text
					let method_types = Object.keys(response.TwoFA.methods);
					if ( method_types.length > 1 ) {
						method_types.forEach(function (type) {
							let method = response.TwoFA.methods[type];
							container.find('form.lwa-2FA-method-selection .lwa-2FA-method-' + type).addClass('available').find('.lwa-2FA-method-desc').html(method.text.select);
						});
					} else if ( method_types.length === 1 ) {
						// hide selection link and selector formws via CSS
						container.addClass('single-method');
					}
					// get specific and detect trigger
					if ( 'method' in response.TwoFA && response.TwoFA.method ) {
						// one method or predefined method, just trigger the selection of that
						LWA_TwoFA.current.method = response.TwoFA.method;
						LWA_TwoFA.selectMethod( response.TwoFA.method );
					} else {
						// trigger the selection of a method
						container.find('.lwa-2FA-select-method').click();
					}
					// set general timer for timeout
					if( 'timeout_time' in response.TwoFA && response.TwoFA.timeout_time > 0 ) {
						if( response.TwoFA.method in timeouts ) {
							clearInterval( timeouts[response.TwoFA.method] );
						}
						timeouts[response.TwoFA.method] = LWA_TwoFA.setTimeout( container, response.TwoFA.timeout_time, response.TwoFA.timeout_error );
					}
					LoginWithAJAX.handleStatus(response, statusElement);
					modal.addClass('active').find('.lwa-modal-popup').addClass('active');
				} else {
					// get url for loading 2FA form and load it into modal
					container.addClass('hidden');
					container.siblings('.setup-loader').removeClass('hidden');
					container.load( response.TwoFA.setup_url, function(){
						// show modal and hide loader
						container.removeClass('hidden');
						container.siblings('.setup-loader').addClass('hidden');
						// apply login form data in this case, since we're intending to log in aferwards
						let setup = container.find('.lwa-2FA-setup').attr('data-bound-form', '#'+form.getAttribute('id'));
						// get login data if a LWA login form
						let loginForm = LWA_TwoFA.getLoginForm( setup );
						let formData = setup.find('.lwa-2FA-formdata').empty().hide();
						loginForm.find('*[name]').filter(':not([name="login-with-ajax"])').filter(':not(input[type="submit"])').clone().appendTo(formData);
						// set up settings form
						lwa_init_2FA_setup( container );
						LoginWithAJAX.handleStatus(response, statusElement);
						modal.addClass('active').find('.lwa-modal-popup').addClass('active');
					} );
				}
			}
		},
		selectMethod : async function( method_type ){
			// load method into current
			let method = this.current.methods[method_type];
			this.current.method = method_type;

			// show the selected method form
			let methodSelectForm = container.find('form.lwa-2FA-method-selection');
			methodSelectForm.find('.lwa-status').remove();
			methodSelectForm.find('input[name="2FA"]').prop('checked', false);
			container.find('.lwa-2FA-method-forms .lwa-2FA-method').removeClass('active');
			let methodForm = container.find('.lwa-2FA-method-forms .lwa-2FA-method.lwa-2FA-method-'+ method_type).addClass('active');
			methodForm.find('.lwa-status').remove()

			// add message if there is one
			if ( method.text.form ) {
				methodForm.find('.lwa-2FA-message').html(method.text.form);
			}

			// hide selector if open and show actual method
			methodSelectForm.hide();
			container.find('.lwa-2FA-method-forms').show();
			container.find('.lwa-2FA-select-method').show();

			// unset radios
			methodSelectForm.find('input[name="2FA"]').prop('checked', false);

			// get login data if a LWA login form
			let loginForm = this.getLoginForm(methodForm);
			let formData = methodForm.find('.lwa-2FA-formdata').empty().hide();
			loginForm.find('*[name]').filter(':not([name="login-with-ajax"])').filter(':not(input[type="submit"])').clone().appendTo(formData);

			// load type, and possibly re-request to handle timeouts
			if ( !this.isMethodValid(method) ) {
				method = await this.requestMethodForm( methodForm );
				if( method.result ) {
					methodSelectForm.find('.lwa-status').remove();
				} else {
					// don't proceed, no valid method secured
					var statusElement = LoginWithAJAX.addStatusElement(methodForm);
					if( 'restart' in method && method.restart ) {
						LWA_TwoFA.handleVerifyResponse(response, methodForm[0]); // in case we needed a restart
					}
					return LoginWithAJAX.handleStatus( method, statusElement );
				}
			} else if ( 'resend' in method && method.resend ) {
				// make sure resend timer is good
				let countdown = methodForm.find('.lwa-2FA-resend-timer');
				LWA_TwoFA.setCountdown( countdown, method.resend );
			}

			// add a timeout
			if( method.timeout ) {
				let countdown = methodForm.find('.method-countdown');
				if( countdown.length === 0 ) {
					countdown = null;
				}
				LWA_TwoFA.setTimeout( methodForm, method.timeout, method.text.timeout, countdown );
			}

			// add auto-verifier if this is an authorization method rather than code
			if( method.verification === 'authorize' ) {
				LWA_TwoFA.verifier = function () {
					LoginWithAJAX.submit( methodForm, {socket: true}, null, {spinner: false}).then(function (response) {
						if( methodForm.attr('data-method') !== LWA_TwoFA.current.method ) return; // check we're still on the same method
						if (!response.result) {
							if ('skip' in response && response.skip && typeof LWA_TwoFA.verifier === 'function' ) {
								setTimeout(LWA_TwoFA.verifier, 2500);
							}
						}
						LWA_TwoFA.handleVerifyResponse( response, methodForm[0] );
					});
				};
				LWA_TwoFA.verifier();
			}

			// trigger a hook to allow extra values etc.
			jQuery(document).triggerHandler('lwa_2FA_method_'+method_type, [this.current, methodForm]);
		},
		isMethodValid : function( method ){
			let result = true;
			if( !method ) return false;
			if ( !('result' in method && method.result) ) {
				result = false;
			} else if ( !('requested' in method && method.requested) ) {
				result = false;
			} else if ( 'verification' in method && method.verification === 'direct' ) {
				result = true;
			} else if ( 'resend' in method && method.resend && method.resend < (Date.now() / 1000) ) {
				result = false;
			}
			return result;
		},
		requestMethodForm : async function( methodForm ){
			form = $(methodForm);
			let method_type = form.attr('data-method');
			// disable reset and verify button whilst we wait for response
			form.find('button, .button').prop('disabled', true).addClass('disabled inactive');
			let method = await this.request( method_type );
			if( method.result ) {
				this.current.methods[method_type] = method;
			}
			// unblock buttons regrdless of result
			form.find('button, .button').prop('disabled', false).removeClass('disabled inactive');
			// generic resend timeout notice
			if( method.resend ){
				let countdown = form.find('.lwa-2FA-resend-timer');
				if( countdown.length > 0 ){
					LWA_TwoFA.setCountdown( countdown, method.resend );
				}
			}
			return method;
		},
		request : async function( method ){
			// fetch a request for specific 2FA, which may involve initiating sending of a code, pushing a notification etc.
			let data = {
				lwa : 1,
				'login-with-ajax'  : '2FA',
				'2FA_request' : 'request',
				'2FA_id' : this.current.id,
				'2FA' : method,
				nonce : this.current.nonce,
				log : this.current.user,
			};
			return fetch( LWA.ajaxurl, {
				method: "POST",
				headers : {
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
				},
				body: new URLSearchParams( data ).toString(),
			}).then( function( response ){
				if( response.ok ) {
					return response.json();
				}
				return Promise.reject( response );
			}).catch( function( err ){
				console.log('Error with AJAX Request - %o', err);
				return  { result: false, message : 'There was an error, see message console for more info.'};
			});
		},
		getLoginForm : function( form ){
			let $form = jQuery(form);
			return jQuery( $form.attr('data-bound-form') );
		},
		setCountdown : function( countdown, timeout ){
			// Set the date we're counting down to
			countdown.closest('.lwa-2FA-resend').prop('style', 'background:#fefefe !important; border-color: #ccc !important; text-shadow:none !important; color:#aaa !important;').addClass('inactive');
			clearInterval(countdown.data('countdown')); // clear other timers
			let timer = timeout - (Date.now() / 1000);
			// Update the count down every 1 second
			let interval = function() {
				timer -= 1;
				// Time calculations for days, hours, minutes and seconds
				let minutes = Math.floor((timer % (60 * 60)) / (60));
				let seconds = String( Math.floor((timer % 60)) ).padStart(2, '0');
				if( minutes < 0 ) minutes = 0;
				if( seconds < 0 ) seconds = '00';
				countdown.html('(' + minutes + ": " + seconds + ')');
				// If the count down is finished, write some text
				if (timer < 0) {
					clearInterval(x);
					countdown.empty();
					countdown.closest('.lwa-2FA-resend').prop('style', '').removeClass('inactive');
				}
			}
			interval();
			let x = setInterval(interval, 1000);
			countdown.data('countdown', x);
		},
		setTimeout : function( form, timeout, error_txt, countdown = null ){
			let timer = timeout - (Date.now() / 1000);
			let timer_interval = countdown ? 1000 : timer * 1000;
			let interval = function() {
				timer -= timer_interval / 1000;
				// Time calculations for days, hours, minutes and seconds
				let minutes = Math.floor((timer % (60 * 60)) / (60));
				let seconds = String( Math.floor((timer % 60)) ).padStart(2, '0');
				if( minutes < 0 ) minutes = 0;
				if( seconds < 0 ) seconds = '00';
				if( countdown ) {
					countdown.html(minutes + ":" + seconds);
				}
				// If the count down is finished, close login window
				if (timer <= 0) {
					clearInterval(x);
					// if form is main authentication form, then exit to login form, if a method, exit to main authentication form
					if( form.hasClass('lwa-2FA-method') ) {
						if( form.hasClass('active') ) {
							// find method selection form and show that
							form.find('.lwa-status').remove();
							form.prepend($('<span class="lwa-status lwa-status-invalid" role="alert">'+ error_txt +'</span>'));
						}
					} else {
						form.prepend($('<span class="lwa-status lwa-status-invalid" role="alert">'+ error_txt +'</span>'));
						LWA_TwoFA.handleVerifyResponse( { result : false, restart : true }, form[0] );
					}
				}
			}
			if ( countdown ) {
				// start timer to show countdown
				interval();
			}
			let x = setInterval(interval, timer_interval);
			return x;
		},
		handleVerifyResponse : function( response, methodForm ){
			if ( !response.result && 'restart' in response && response.restart ) {
				// close this modal and send error message to main form
				let form = LWA_TwoFA.getLoginForm( methodForm )[0];
				let wrapper = form.closest('.lwa');
				let status = methodForm.querySelector('.lwa-status');
				wrapper.querySelectorAll('.lwa-status').forEach( el => el.remove() );
				form.querySelectorAll('.lwa-status').forEach( el => el.remove() );
				if ( LWA_TwoFA.isWPLogin ) {
					// replace same status with a div instead, copy it all over and remove original span
					status.classList.add('message', 'login', 'error');
					let newStatus = document.createElement('div');
					wrapper.prepend( newStatus );
					newStatus.outerHTML = status.outerHTML.replace('<span', '<div').replace('</span', '</div');
					status.remove();
				} else {
					form.prepend( status );
				}
				modal.removeClass('active').find('.lwa-modal-popup').removeClass('active');
			}
		}
	};

	// setup 2FA for user, enabling and disabling methods along with setup UI
	var lwa_init_2FA_setup = function ( container ) {
		// get success page if it exists
		let intro = container.find('.lwa-2FA-setup-intro');
		let success = container.find('.lwa-2FA-setup-success');
		let setup = container.find('.lwa-2FA-setup-form');
		// setup enable
		container.on('change', '.lwa-2FA-method-enable', function( e ){
			// get closest section
			e.preventDefault();
			let method = jQuery(this).closest('.lwa-2FA-method');
			let content = method.find('.lwa-2FA-method-content');
			if( this.checked ) {
				// find lwa-2FA-method-setup and slide down
				content.slideDown(400);
				method.addClass('enabled');
			} else {
				content.slideUp( 400, function() {
					method.removeClass('enabled');
				});
			}
		});
		container.on('click', '.lwa-2FA-method:not(.enabled)', function( e ){
			if( e.target.matches('label, button, input') || e.target.parentElement.matches('label') ) return true;
			e.preventDefault();
			e.stopPropagation();
			this.querySelector('.lwa-2FA-method-title label').click();
		});

		// reset setup of a method that requires it (e.g. 2FA) and hide appropriate buttons
		container.on('click', '.lwa-2FA-method-setup-reset', function( e ){
			// get closest section
			e.preventDefault();
			let method = jQuery(this).closest('.lwa-2FA-method');
			let setup = method.find('.lwa-2FA-method-setup');
			let button = this;
			if( this.innerHTML === this.dataset.modifyTxt ) {
				// find lwa-2FA-method-setup and slide down
				method.attr('data-previous-status', method.attr('data-status'));
				method.removeAttr('data-status');
				method.find('.lwa-2FA-method-setup-reset').html( button.dataset.cancelTxt );
			} else {
				method.attr('data-status', method.attr('data-previous-status'));
				let status = method.find('.lwa-2FA-method-status');
				status.find('.lwa-2FA-method-setup-reset').html( button.dataset.modifyTxt );
			}
		});
		container.find('.lwa-2FA-method-content.hidden, .lwa-2FA-method-setup.hidden').hide();
		container.on('click', '.lwa-2FA-method .setup-verify-button', function( e ){
			e.preventDefault();
			// get closest section
			let $button = jQuery(this);
			let method_select = $button.closest('.lwa-2FA-method');
			let method = method_select.attr('data-method');
			let verify_url = method_select.closest('[data-verify-url]').attr('data-verify-url');
			let data = { 'method' : method, 'action' : 'lwa_2FA_setup_verify' };
			$button.closest('.setup-verify-form').find('[data-name]').each( function() {
				data[this.getAttribute('data-name')] = this.value;
			});
			let status_el = method_select.find('.lwa-2FA-method-status');
			let status_msg = status_el.find('mark');
			let button = this;
			let current_text = button.innerText;
			button.innerText = button.dataset.txt;
			let error = jQuery(this).closest('.lwa-2FA-method-verification').find('mark.error').html('');
			jQuery.ajax( {
				url : verify_url,
				method : 'post',
				data : data,
				dataType : 'json',
				success : function ( response ) {
					if( response.result ) {
						status_msg.html( response.message );
						method_select.attr('data-status', response.status );
						// hide the setup form
						if( response.status === 'complete' ) {
							let setup = method_select.find('.lwa-2FA-method-setup');
							let button = status_el.find('button');
							button.html(button.attr('data-modify-txt'));
							method_select.find('input[data-name][type="text"]').val('');
						}
						// create vanilla js custom event and trigger at document level
						let event = new CustomEvent('lwa_2FA_setup_verified', { detail : { response : response, method_select : method_select.first()[0], method : method, data: data } });
						document.dispatchEvent(event);
					} else {
						error.html( response.error );
					}
				},
				error : function( response, status ){
					error.html('Network Error, try again or contact support.') ;
					console.log('Error in AJAX 2FA setup with status %s for %o', status, response);
				},
				complete : function(){
					button.innerText = current_text;
				}
			}, data, function( response, status ){
				console.log('Error in ajax load for response %o with status %s', status);
			});
		});
		container.on('submit', async function( e ){
			e.preventDefault();
			let form = this;
			if( !this.matches('form.lwa-2FA-setup') ) {
				form = this.querySelector('form.lwa-2FA-setup');
			}
			await LoginWithAJAX.submit( form, $(form).serializeArray(), true ).then( (response) => {
				if( response.result ) {
					// show the confirmation message
					if( success.length > 0 ) {
						setup.addClass('hidden');
						// add redirect url to button
						success.removeClass('hidden');
						if (response.redirect) {
							success.find('.lwa-2FA-setup-confirm').attr('href', response.redirect);
						}
					} else {
						LWA_TwoFA.handleVerifyResponse( response, form );
						form.parentElement.scrollIntoView({behavior: "smooth", block: "start", inline: "nearest"});
					}
				} else {
					LWA_TwoFA.handleVerifyResponse( response, form );
					form.parentElement.scrollIntoView({behavior: "smooth", block: "start", inline: "nearest"});
				}
				return response;
			}).catch( function( err ){
				console.log('Error with AJAX Request - %o', err);
				return  { result: false, message : 'There was an error, see message console for more info.'};
			});
		});

		container.on('click', '.lwa-2FA-setup-start', function( e ){
			intro.addClass('hidden');
			setup.removeClass('hidden');
		});

		// create a new event called lwa_2FA_setup_init and trigger at document level, containing detail of container
		let event = new CustomEvent('lwa_2FA_setup_init', { detail : { container : container[0] } });
		document.dispatchEvent(event);
	}

	// add listeners
	document.addEventListener('lwa_submit_login', LWA_TwoFA.loginResult);
	container.find('.lwa-2FA-select-method').on('click', function( event ){
		container.find('.lwa-2FA-method-forms').hide();
		container.find('.lwa-2FA-method-selection').show();
		$(this).hide();
		LWA_TwoFA.current.method = null; // reset
	});
	container.find('.lwa-2FA-method-selection input[name="2FA"]').on('change', async function( event ){
		this.checked = true;
		// send a request if not already loaded, otherwise just show
		let method = this.value;
		// add loader
		LWA_TwoFA.current.method = null; // reset
		LWA_TwoFA.selectMethod( method );
		event.preventDefault();
	});
	$(document).on('submit', 'form.lwa-2FA-method', function( event ){
		// copy all the fields from the original form over to this one to submit the right credentials
		event.preventDefault();
		let methodForm = this;
		LoginWithAJAX.submit(methodForm).then( response => LWA_TwoFA.handleVerifyResponse( response, methodForm ) );
	});
	// Resend
	$(document).on('lwa_2FA_resend', function( event, response, form ){
		if( 'resend' in response ){
			let countdown = form.find('.lwa-2FA-resend-timer');
			if( countdown.length > 0 ){
				LWA_TwoFA.setCountdown( countdown, response.resend );
			}
			// load updated method into current
			LWA_TwoFA.current.methods[response.type] = response;
		}
	});
	$(document).on('click', '.lwa-2FA-resend', async function( event ){
		event.preventDefault();
		if( !this.classList.contains('inactive') ) {
			let methodForm = this.closest('.lwa-2FA-method');
			let method = await LWA_TwoFA.requestMethodForm( methodForm );
			let statusElement = LoginWithAJAX.addStatusElement(methodForm);
			return LoginWithAJAX.handleStatus( method, statusElement );
		}
	});

	// handle non-ajax logins in wp-login.php
	if( modal.attr('data-is-wp-login') === '1' ) {
		let login_response = JSON.parse( document.getElementById('lwa-login-json-response').innerHTML );
		$(document).triggerHandler('lwa_login', [login_response, $('#loginform'), $('<div>')]);
	}

	$('#lwa-2FA .lwa-2FA-setup').each( function() {
		lwa_init_2FA_setup( $(this) );
	});

	document.dispatchEvent( new CustomEvent('lwa_2FA_loaded') );

});