/*global wffnfunnelVars */
/*global fbq */
/*global pintrk */
(function ($) {
    var wffnOptin = {
        init: function () {

            let self = this;
            $(document).ready(function () {
                self.renderForm();
                self.handleSubmit();
                self.renderPBPopUps();
                self.attachCloseBtn();
                self.initPhoneFlag();
            });
            $(document).on('wffn_reload_popups', function () {
                self.renderPBPopUps();
            });
            $(document).on('wffn_reload_phone_field', function () {
                self.initPhoneFlag();
            });

        },
        initPhoneFlag: function () {
            let intlconfig = {
                initialCountry: window.wffnfunnelVars.op_flag_country,
                separateDialCode: true,
                geoIpLookup: function (callback) {
                    $.get('https://ipinfo.io', function () {
                    }, "jsonp").always(function (resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "us";
                        callback(countryCode);
                    });
                },
            };

            if (typeof window.wffnfunnelVars.onlyCountries !== "undefined" && window.wffnfunnelVars.onlyCountries.length > 0) {
                intlconfig.onlyCountries = window.wffnfunnelVars.onlyCountries;
            }
            var elems = document.querySelectorAll(".phone_flag_code input[type='tel']");
            for (var i in elems) {
                if (typeof elems[i] === 'object' && undefined !== window.intlTelInput) {
                    window.intlTelInput(elems[i], intlconfig);
                }

            }
        },
        attachCloseBtn: function () {
            jQuery(document).on('click', '.bwf_pp_close', function (e) {
                e.preventDefault();
                jQuery('.bwf_pp_overlay').removeClass('show_popup_form');
                jQuery('body').css('overflow', '');
            });
        },
        renderPBPopUps: function () {
            jQuery('.wfop_pb_widget_wrap').each(function () {
                let elem = this;
                jQuery(this).find(".bwf-custom-button a").click(function (e) {
                    e.preventDefault();
                    jQuery(elem).find('.bwf_pp_overlay').addClass('show_popup_form');
                    jQuery('body').css('overflow', 'hidden');
                });

            });
        },
        renderForm: function () {

            if (jQuery('.bwf_pp_overlay').length > 0) {
                jQuery('a[href*="wfop-popup=yes"]').on('click', function (e) {
                    e.preventDefault();
                    jQuery('.bwf_pp_overlay').addClass('show_popup_form');
                });

            }
        },

        DoValidation: function (formElem) {
            var valid = true;

            jQuery(formElem).find('.wfop_required').each(function () {
                console.log( window.wffnfunnelVars );
                var self = jQuery(this);
                var message = null;
                var error_msg = window.wffnfunnelVars.op_valid_text;
                var error_email = window.wffnfunnelVars.op_valid_email;
                if (jQuery.trim(self.val()) === '') {
                    message = error_msg;
                } else if ('checkbox' === self.attr('type')) {
                    if (!self.prop('checked')) {
                        message = error_msg;
                    }
                } else if ('radio' === self.attr('type')) {
                    var radioName = self.attr("name");
                    if (jQuery(formElem).find("input:radio[name=" + radioName + "]:checked").length === 0) {
                        message = error_msg;
                    }
                }

                var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
                if (jQuery.trim(self.val()) !== '' && 'wfop_optin_email' === self.attr('name')) {
                    if (!jQuery.trim(self.val()).match(pattern)) {
                        message = error_email;
                    }
                }
                if (message !== null) {
                    self.parents('.bwfac_form_sec').addClass('bwfac_error');
                    if (self.parents('.bwfac_form_sec').find('.error').length === 0) {
                        self.parents('.bwfac_form_sec').append('<span class="error">' + message + '</span>');
                    }
                    valid = false;
                }
            });

            jQuery(formElem).find('.wfop_phone_validation .wffn-optin-input').each(function () {
                var inst = jQuery(this);
                var error_message = null;
                var error_phone = window.wffnfunnelVars.op_valid_phone;
                if (jQuery.trim(inst.val()) !== '' && 'wfop_optin_phone' === inst.attr('name')) {
                    if ("undefined" !== typeof window.intlTelInputGlobals) {
                        var itis = window.intlTelInputGlobals.getInstance(inst.get(0));
                        if (!itis.isValidNumber()) {
                            if (Array.isArray(error_phone)) {
                                var errorCode = itis.getValidationError();
                                error_message = error_phone[errorCode];
                            } else {
                                error_message = error_phone;
                            }
                        }
                    }
                }
                if (error_message !== null) {
                    inst.parents('.bwfac_form_sec').addClass('bwfac_error');
                    if (inst.parents('.bwfac_form_sec').find('.error').length === 0) {
                        inst.parents('.bwfac_form_sec').append('<span class="error">' + error_message + '</span>');
                    }
                    valid = false;
                }
            });

            return valid;
        },
        setUpClick: function (FormElem) {
            let inst = this;
            jQuery(FormElem).find('#wffn_custom_optin_submit').on('click', function (e) {
                var valid = true;

                jQuery(this).removeAttr('disabled');
                var $this = jQuery(this);

                var bwf_form = FormElem;
                jQuery(bwf_form).find('.bwfac_form_sec').removeClass('bwfac_error');
                jQuery(bwf_form).find('.bwfac_form_sec .error').remove();
                let is_admin = jQuery(bwf_form).find('input[name=optin_is_admin]').val();
                let is_ajax = jQuery(bwf_form).find('input[name=optin_is_ajax]').val();
                let is_preview = jQuery(bwf_form).find('input[name=optin_is_preview]').val();

                if (is_admin || is_ajax || is_preview) {

                    valid = false;
                }


                valid = inst.DoValidation(FormElem);
                e.preventDefault();
                if (valid) {
                    jQuery(this).attr('disabled', 'disabled');
                    let submitting_text = jQuery(this).attr('data-subitting-text');
                    jQuery(FormElem).find("button.wfop_submit_btn .bwf_heading").html(submitting_text);


                    if ("undefined" !== typeof window.intlTelInputGlobals && undefined !== jQuery(FormElem).find('input[name="wfop_optin_phone"]').get(0)) {
                        var iti = window.intlTelInputGlobals.getInstance(jQuery(FormElem).find('input[name="wfop_optin_phone"]').get(0));
                        var getCountryData = iti.getSelectedCountryData();
                        jQuery(FormElem).find('input[name="wfop_optin_phone_dialcode"]').eq(0).val('+' + getCountryData.dialCode);
                        jQuery(FormElem).find('input[name="wfop_optin_phone_countrycode"]').eq(0).val(getCountryData.iso2);

                    }

                    /* Add overlay Class when clicked on the button after validation */
                    $this.parents('.wffn-custom-optin-from').addClass("wffn-form-overlay");

                    inst.handleLeadEvent();
                    /**
                     * XHR synchronous requests on the main threads are deprecated. We need to make it async, and after that trigger the form submission
                     */
                    jQuery.ajax({
                        url: window.wffnfunnelVars.ajaxUrl + '?action=wffn_submit_custom_optin_form&lead_event_id=' + wffnfunnelVars.op_lead_tracking.fb.event_ID,
                        data: jQuery(FormElem).serialize(),
                        dataType: 'json',
                        type: 'post',
                    }).always(function (resp) {
                        /* Remove overlay Class after succuss  */
                        $this.parents('.wffn-custom-optin-from').addClass("wffn-form-overlay");
                        /* When there is no action for the form we reload the page manually so we won't mess up the redirects from WP */
                        if (Object.prototype.hasOwnProperty.call(resp, 'mapped')) {
                            for (var k in resp.mapped) {
                                jQuery(".wfop_integration_form input[name='" + k + "']").val(resp.mapped[k]);
                            }
                            jQuery(".wfop_integration_form").trigger('submit');
                            return;
                        }

                        if (Object.prototype.hasOwnProperty.call(resp, 'next_url') && '' !== resp.next_url) {
                            window.location.href = resp.next_url;
                            return;
                        }
                    });
                } else {
                    console.log('form validation failed');
                }
            });
        },
        handleSubmit: function () {
            let inst = this;
            jQuery("form.wffn-custom-optin-from").each(function () {
                inst.setUpClick(this);
            });

        },
        handleLeadEvent: function () {
            if( 1 != wffnfunnelVars.op_should_render ){
                return;
            }
            if ( 'object' === typeof wffnfunnelVars.op_lead_tracking.fb.enable && 'yes' === wffnfunnelVars.op_lead_tracking.fb.enable[0] && false !== wffnfunnelVars.op_lead_tracking.fb.fb_pixels) {
                if (typeof fbq === 'undefined') {
                    (function (f, b, e, v, n, t, s) {
                        if (f.fbq) return;
                        n = f.fbq = function () {
                            let pl = n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
                        };
                        if (!f._fbq) f._fbq = n;
                        n.push = n;
                        n.loaded = !0;
                        n.version = '2.0';
                        n.queue = [];
                        t = b.createElement(e);
                        t.async = !0;
                        t.src = v;
                        s = b.getElementsByTagName(e)[0];
                        s.parentNode.insertBefore(t, s);
                    })(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');


                    /** iterate loop **/
                    const pixelIds = wffnfunnelVars.op_lead_tracking.fb.fb_pixels.split(',');
                    $(pixelIds).each(function (k, v) {

                        fbq('init', v);
                    });
                }

                var data = (typeof wffnAddTrafficParamsToEvent !== "undefined") ? wffnAddTrafficParamsToEvent({}) : {};
                fbq('track', 'Lead', data, {'eventID': wffnfunnelVars.op_lead_tracking.fb.event_ID});
            }

            if ( 'object' === typeof wffnfunnelVars.op_lead_tracking.pint.enable && 'yes' === wffnfunnelVars.op_lead_tracking.pint.enable[0] && false !== wffnfunnelVars.op_lead_tracking.pint.pixels) {
                !function (e) {
                    if (!window.pintrk) {
                        window.pintrk = function () {
                            window.pintrk.queue.push(Array.prototype.slice.call(arguments))
                        };
                        var n = window.pintrk;
                        n.queue = [], n.version = "3.0";
                        var t = document.createElement("script");
                        t.async = !0, t.src = e;
                        var r = document.getElementsByTagName("script")[0];
                        r.parentNode.insertBefore(t, r)
                    }
                }("https://s.pinimg.com/ct/core.js");


                /** iterate loop **/
                const pixelIds = wffnfunnelVars.op_lead_tracking.pint.pixels.split(',');
                $(pixelIds).each(function (k, v) {
                    pintrk('load', v, {np: 'woofunnels'});
                });
                var data = (typeof wffnAddTrafficParamsToEvent !== "undefined") ? wffnAddTrafficParamsToEvent({}) : {};
                pintrk('track', 'Lead', data);
            }


            if ( 'object' === typeof wffnfunnelVars.op_lead_tracking.ga.enable && 'yes' === wffnfunnelVars.op_lead_tracking.ga.enable[0] && false !== wffnfunnelVars.op_lead_tracking.ga.ids) {
                const pixelIds = wffnfunnelVars.op_lead_tracking.ga.ids.split(',');
                var data = (typeof wffnAddTrafficParamsToEvent !== "undefined") ? wffnAddTrafficParamsToEvent({}) : {};
                data.send_to = pixelIds[0];

                gtag('event', 'Lead', data);
            }

            if ( 'object' === typeof wffnfunnelVars.op_lead_tracking.gad.enable && 'yes' === wffnfunnelVars.op_lead_tracking.gad.enable[0] && false !== wffnfunnelVars.op_lead_tracking.gad.ids) {
                const pixelIds = wffnfunnelVars.op_lead_tracking.gad.ids.split(',');
                var data = (typeof wffnAddTrafficParamsToEvent !== "undefined") ? wffnAddTrafficParamsToEvent({}) : {};
                data.send_to = pixelIds[0];
                gtag('event', 'Lead', data);
            }
        }
    };
    wffnOptin.init();
})(jQuery);
