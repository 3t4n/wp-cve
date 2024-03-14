(function($) {

	window.onLoadFLReCaptcha = function() {
		var reCaptchaFields = $( '.fl-grecaptcha' ),
			widgetID;

		if ( reCaptchaFields.length > 0 ) {
			reCaptchaFields.each(
				function( i ){
					var self   = $( this ),
					attrWidget = self.attr( 'data-widgetid' ),
					newID      = $( this ).attr( 'id' ) + '-' + i;

					// Avoid re-rendering as it's throwing API error.
					if ( (typeof attrWidget !== typeof undefined && attrWidget !== false) ) {
						return;
					} else {
						// Increment ID to avoid conflict with the same form.
						self.attr( 'id', newID );

						widgetID = grecaptcha.render(
							newID,
							{
								sitekey : self.data( 'sitekey' ),
								theme	: self.data( 'theme' ),
								size    : self.data( 'validate' ),
								callback: function( response ){
									if ( response !== '' ) {
										self.attr( 'data-fl-grecaptcha-response', response );

										// Re-submitting the form after a successful invisible validation.
										if ( 'invisible' == self.data( 'validate' ) ) {
											self.closest( '.fl-contact-form' ).find( 'a.fl-button' ).trigger( 'click' );
										}
									}
								}
							}
						);

						self.attr( 'data-widgetid', widgetID );
					}
				}
			);
		}
	};

	TNITContactForm = function( settings )
	{
		this.settings  = settings;
		this.nodeClass = '.fl-node-' + settings.id;
		this._init();
	};

	TNITContactForm.prototype = {

		settings	: {},
		nodeClass	: '',

		_init: function()
		{
			$( this.nodeClass + ' .tnit-button' ).click( $.proxy( this._submit, this ) );
		},

		_submit: function( e )
		{
			var theForm        = $( this.nodeClass + ' .tnit-contact-form' ),
				submit         = $( this.nodeClass + ' .tnit-button' ),
				name           = $( this.nodeClass + ' .tnit-contact-form .tnit-contact-name' ),
				email          = $( this.nodeClass + ' .tnit-contact-form .tnit-contact-email' ),
				phone          = $( this.nodeClass + ' .tnit-contact-form .tnit-contact-phone' ),
				subject        = $( this.nodeClass + ' .tnit-contact-form .tnit-contact-subject' ),
				message        = $( this.nodeClass + ' .tnit-contact-form .tnit-contact-message' ),
				reCaptchaField = $( this.nodeClass + ' .fl-grecaptcha' ),
				reCaptchaValue = reCaptchaField.data( 'fl-grecaptcha-response' ),
				ajaxData       = null,
				ajaxurl        = FLBuilderLayoutConfig.paths.wpAjaxUrl,
				email_regex    = /\S+@\S+\.\S+/,
				isValid        = true,
				postId         = theForm.closest( '.fl-builder-content' ).data( 'post-id' ),
				layoutId       = theForm.find( 'input[name=tnit-layout-id]' ).val(),
				templateId     = theForm.data( 'template-id' ),
				templateNodeId = theForm.data( 'template-node-id' ),
				nodeId         = theForm.closest( '.fl-module' ).data( 'node' );

			e.preventDefault();

			// End if button is disabled (sent already).
			if (submit.hasClass( 'tnit-disabled' )) {
				return;
			}

			// validate the name.
			if (name.length) {
				if (name.val() === '') {
					isValid = false;
					name.parent().addClass( 'tnit-error' );
				} else if (name.parent().hasClass( 'tnit-error' )) {
					name.parent().removeClass( 'tnit-error' );
				}
			}

			// validate the email.
			if (email.length) {
				if (email.val() === '' || ! email_regex.test( email.val() )) {
					isValid = false;
					email.parent().addClass( 'tnit-error' );
				} else if (email.parent().hasClass( 'tnit-error' )) {
					email.parent().removeClass( 'tnit-error' );
				}
			}

			// validate the subject..just make sure it's there.
			if (subject.length) {
				if (subject.val() === '') {
					isValid = false;
					subject.parent().addClass( 'tnit-error' );
				} else if (subject.parent().hasClass( 'tnit-error' )) {
					subject.parent().removeClass( 'tnit-error' );
				}
			}

			// validate the phone..just make sure it's there.
			if (phone.length) {
				if (phone.val() === '') {
					isValid = false;
					phone.parent().addClass( 'tnit-error' );
				} else if (phone.parent().hasClass( 'tnit-error' )) {
					phone.parent().removeClass( 'tnit-error' );
				}
			}

			// validate the message..just make sure it's there.
			if (message.val() === '') {
				isValid = false;
				message.parent().addClass( 'tnit-error' );
			} else if (message.parent().hasClass( 'tnit-error' )) {
				message.parent().removeClass( 'tnit-error' );
			}

			// validate if reCAPTCHA is enabled and checked.
			if ( reCaptchaField.length > 0 && isValid ) {
				if ( 'undefined' === typeof reCaptchaValue || reCaptchaValue === false ) {
					if ( 'normal' == reCaptchaField.data( 'validate' ) ) {
						reCaptchaField.parent().addClass( 'tnit-error' );
					}

					isValid = false;
				} else {
					reCaptchaField.parent().removeClass( 'tnit-error' );
				}
			}

			// end if we're invalid, otherwise go on..
			if ( ! isValid) {
				return false;
			} else {

				// disable send button.
				submit.addClass( 'tnit-disabled' );

				ajaxData = {
					action				: 'tnit_builder_email',
					name				: name.val(),
					subject				: subject.val(),
					email				: email.val(),
					phone				: phone.val(),
					message				: message.val(),
					post_id 			: postId,
					layout_id			: layoutId,
					template_id 		: templateId,
					template_node_id 	: templateNodeId,
					node_id 			: nodeId
				};

				if ( reCaptchaValue ) {
					ajaxData.recaptcha_response	= reCaptchaValue;
				}

				// post the form data.
				$.post( ajaxurl, ajaxData, $.proxy( this._submitComplete, this ) );
			}
		},

		_submitComplete: function( response )
		{
			var urlField  = $( this.nodeClass + ' .tnit-success-url' ),
				noMessage = $( this.nodeClass + ' .tnit-success-none' );

			console.log( response );

			// On success show the success message.
			if (typeof response.error !== 'undefined' && response.error === false) {

				$( this.nodeClass + ' .tnit-send-error' ).fadeOut();

				if ( urlField.length > 0 ) {
					window.location.href = urlField.val();
				} else if ( noMessage.length > 0 ) {
					noMessage.fadeIn();
				} else {
					$( this.nodeClass + ' .tnit-contact-form' ).hide();
					$( this.nodeClass + ' .tnit-success-msg' ).fadeIn();
				}
			}
			// On failure show fail message and re-enable the send button.
			else {
				$( this.nodeClass + ' .tnit-button' ).removeClass( 'tnit-disabled' );
				if ( typeof response.message !== 'undefined' ) {
					$( this.nodeClass + ' .tnit-send-error' ).html( response.message );
				}
				$( this.nodeClass + ' .tnit-send-error' ).fadeIn();
				return false;
			}
		}
	};

})( jQuery );
