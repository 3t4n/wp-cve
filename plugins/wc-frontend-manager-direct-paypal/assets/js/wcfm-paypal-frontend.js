;(function($, document) {
    const wcfm_paypal_connect = {
        clicked: false,
        init: function () {
			this.setup();
			this.bindEvents();
		},

        setup: function () {
			this.document = $(document);
			this.signup = '#paypal_signup';
            this.vendor_email = '#paypal_email';
            this.paypal_btn = '#paypal_connect_button';
            this.disconnect_paypal = '#disconnect_paypal';
		},

        bindEvents: function() {
            // signup button clicked
			this.document.on( "click", this.signup, this.generateSignupLink.bind(this) );
            // disconnect paypal button clicked
			this.document.on( "click", this.disconnect_paypal, this.disconnectPaypal.bind(this) );
        },

        generateSignupLink: function(e) {
            if (this.clicked) {
                return;
            }

            e.preventDefault();

            $(this.signup).addClass('disabled');

            let data = {
                action: "wcfm_paypal_marketplace_connect",
                vendor_email: $(this.vendor_email).val(),
                _nonce: wcfm_paypal_frontend_l10n.connect_nonce
            };

            let __this = this;

            $.ajax({
                type: 'POST',
                url: wcfm_paypal_frontend_l10n.ajaxurl,
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    $('#wcfm_settings_form').block({
                        message: null,
                        overlayCSS: {
                            background: '#fff',
                            opacity: 0.6
                        }
                    });
                }
            }).done(function(response) {
                if( response ) {
                    $('.wcfm-message').html('').removeClass('wcfm-success').removeClass('wcfm-error').slideUp();
					wcfm_notification_sound.play();
					if ( response.success ) {
						$('#wcfm_settings_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + response.data.message).addClass('wcfm-success').slideDown();
                        __this.loadPartnerJs();
                        __this.createPaypalButton( response.data.url );
                        if ( response.data.reload ) {
                            window.location.href = response.data.url;
                        } else {
                            $( __this.paypal_btn ).show();
                        }
					} else {
						$('#wcfm_settings_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + response.data.message).addClass('wcfm-error').slideDown();
					}
					wcfmMessageHide();
					$('#wcfm_settings_form').unblock();
				}
            });
        },
        loadPartnerJs: function() {
            (function(d, s, id) {
                var js, ref = d.getElementsByTagName(s)[0];
                if (!d.getElementById(id)) {
                    js = d.createElement(s);
                    js.id = id;
                    js.async = true;
                    js.src = "https://www.paypal.com/webapps/merchantboarding/js/lib/lightbox/partner.js";
                    ref.parentNode.insertBefore(js, ref);
                }
            }(document, "script", "paypal-js"));
        },
        createPaypalButton: function( url ) {
            /** Creates PayPal Connect Button */
            $( this.paypal_btn ).append( '<a data-paypal-button="true" class="button wcfm_ele paymode_field paymode_paypal_marketplace" href="' + url + '&displayMode=minibrowser" target="PPFrame">' + wcfm_paypal_frontend_l10n.paypal_btn_txt + '</a>' ).hide();
        },
        disconnectPaypal: function(e) {
            e.preventDefault();
            
            let data = {
                action: "wcfm_paypal_marketplace_disconnect",
                _nonce: wcfm_paypal_frontend_l10n.disconnect_nonce
            };

            $.ajax({
                type: 'POST',
                url: wcfm_paypal_frontend_l10n.ajaxurl,
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    $('#wcfm_settings_form').block({
                        message: null,
                        overlayCSS: {
                            background: '#fff',
                            opacity: 0.6
                        }
                    });
                }
            }).done(function(response) {
                if( response ) {
					$('.wcfm-message').html('').removeClass('wcfm-success').removeClass('wcfm-error').slideUp();
					wcfm_notification_sound.play();
					if ( response.success ) {
						$('#wcfm_settings_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + response.data.message).addClass('wcfm-success').slideDown();
                        
                        if ( response.data.url ) {
                            window.location.href = response.data.url;
                        }
					} else {
						$('#wcfm_settings_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + response.data.message).addClass('wcfm-error').slideDown();
					}
					wcfmMessageHide();
					$('#wcfm_settings_form').unblock();
				}
            });
        },
    };

    $(document).ready(function ($) {
		wcfm_paypal_connect.init();
	});
})(jQuery, document);