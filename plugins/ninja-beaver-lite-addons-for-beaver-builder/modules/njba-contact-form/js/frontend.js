(function ($) {
    window.onLoadFLReCaptcha = function () {
        let reCaptchaFields = $('.fl-grecaptcha'),
            widgetID;

        if (reCaptchaFields.length > 0) {
            reCaptchaFields.each(function (i) {
                const self = $(this),
                    attrWidget = self.attr('data-widgetid'),
                    newID = $(this).attr('id') + '-' + i;

                // Avoid re-rendering as it's throwing API error
                if ((typeof attrWidget != typeof undefined && attrWidget != false)) {
                    return;
                } else {
                    // Increment ID to avoid conflict with the same form.
                    self.attr('id', newID);

                    widgetID = grecaptcha.render(newID, {
                        sitekey: self.data('sitekey'),
                        theme: self.data('theme'),
                        size: self.data('validate'),
                        callback: function (response) {
                            if (response != '') {
                                self.attr('data-fl-grecaptcha-response', response);

                                // Re-submitting the form after a successful invisible validation.
                                if ('invisible' == self.data('validate')) {
                                    self.closest('.fl-contact-form').find('a.fl-button').trigger('click');
                                }
                            }
                        }
                    });

                    self.attr('data-widgetid', widgetID);
                }
            });
        }
    };
    NJBAContactForm = function (settings) {
        this.settings = settings;
        this.nodeClass = '.fl-node-' + settings.id;
        this.ajaxurl = settings.njba_ajaxurl;
        this.first_name_required = settings.first_name_required;
        this.email_required = settings.email_required;
        this.last_name_required = settings.last_name_required;
        this.subject_required = settings.subject_required;
        this.phone_required = settings.phone_required;
        this.msg_required = settings.msg_required;
        this._init();
    };
    NJBAContactForm.prototype = {

        settings: {},
        nodeClass: '',
        ajaxurl: '',
        first_name_required: 'no',
        email_required: 'no',
        last_name_required: 'yes',
        subject_required: 'no',
        phone_required: 'yes',
        msg_required: 'yes',
        _init: function () {
            const phone = $(this.nodeClass + ' .njba-phone input');
            phone.on('keyup', this._removeExtraSpaces);
            $(this.nodeClass + ' .njba-contact-form-submit').click($.proxy(this._submit, this));
        },
        _submit: function (e) {
            let theForm = $(this.nodeClass + ' .njba-contact-form'),
                submit = $(this.nodeClass + ' .njba-contact-form-submit'),
                name = $(this.nodeClass + ' .njba-first-name input'),
                last_name = $(this.nodeClass + ' .njba-last-name input'),
                email = $(this.nodeClass + ' .njba-email input'),
                phone = $(this.nodeClass + ' .njba-phone input'),
                subject = $(this.nodeClass + ' .njba-subject input'),
                message = $(this.nodeClass + ' .njba-message textarea'),
                mailto = $(this.nodeClass + ' .njba-mailto'),
                reCaptchaField = $(this.nodeClass + ' .fl-grecaptcha'),
                reCaptchaValue = reCaptchaField.data('fl-grecaptcha-response'),
                ajaxurl = FLBuilderLayoutConfig.paths.wpAjaxUrl,
                email_regex = /\S+@\S+\.\S+/,
                phone_regex = /^[ 0-9.()\[\]+-]*$/,
                isValid = true;
            let postId = theForm.closest('.fl-builder-content').data('post-id'),
                templateId = theForm.data('template-id'),
                templateNodeId = theForm.data('template-node-id'),
                nodeId = theForm.closest('.fl-module').data('node');
            e.preventDefault();
            name.on('focus', this._removeErrorClass);
            last_name.on('focus', this._removeErrorClass);
            email.on('focus', this._removeErrorClass);
            phone.on('focus', this._removeErrorClass);
            phone.on('keyup', this._removeExtraSpaces);
            subject.on('focus', this._removeErrorClass);

            message.on('focus', this._removeErrorClass);
            // End if button is disabled (sent already)
            if (submit.hasClass('njba-disabled')) {
                return;
            }
            // validate the name

            if (this.first_name_required == 'yes') {
                if (name.length) {
                    if (name.val().trim() == '') {
                        isValid = false;
                        name.parent().addClass('njba-error');
                        name.addClass('njba-form-error');
                        name.siblings('.njba-form-error-message').show();
                    } else if (name.parent().hasClass('njba-error')) {
                        name.parent().removeClass('njba-error');
                        name.siblings('.njba-form-error-message').hide();
                    }
                }
            }

            // validate the lastname..just make sure it's there

            if (this.last_name_required == 'yes') {
                if (last_name.length) {
                    if (last_name.val().trim() == '') {
                        isValid = false;
                        last_name.parent().addClass('njba-error');
                        last_name.siblings('.njba-form-error-message').show();
                    } else if (last_name.parent().hasClass('njba-error')) {
                        last_name.parent().removeClass('njba-error');
                        last_name.siblings('.njba-form-error-message').hide();
                    }
                }
            }


            // validate the email
            if (this.email_required == 'yes') {
                if (email.length) {
                    if (email.val().trim() == '') {
                        isValid = false;
                        email.parent().addClass('njba-error');
                        email.siblings('.njba-form-error-message').show();
                        //email.siblings().addClass('njba-form-error-message-required');
                    } else {
                        //email.siblings().removeClass('njba-form-error-message-required');
                        email.parent().removeClass('njba-error');
                        email.siblings('.njba-form-error-message').hide();
                    }
                }
            } else {
                email.siblings().removeClass('njba-form-error-message-required');
            }
            if (email.length) {
                if (email.val().trim() != '') {
                    if (email_regex.test(email.val().trim())) {
                        email.parent().removeClass('njba-error-msg');
                        email.siblings('.njba-form-error-message').hide();
                    } else {
                        isValid = false;
                        email.parent().addClass('njba-error-msg');
                        email.siblings('.njba-form-error-message').show();
                    }
                }
            }
            // validate the subject..just make sure it's there
            if (this.subject_required == 'yes') {
                if (subject.length) {
                    if (subject.val().trim() == '') {
                        isValid = false;
                        subject.parent().addClass('njba-error');
                        subject.siblings('.njba-form-error-message').show();
                    } else if (subject.parent().hasClass('njba-error')) {
                        subject.parent().removeClass('njba-error');
                        subject.siblings('.njba-form-error-message').hide();
                    }
                }
            }
            // validate the phone..just make sure it's there
            if (this.phone_required == 'yes') {
                if (phone.length) {
                    if (phone.val().trim() == '') {
                        isValid = false;
                        phone.parent().addClass('njba-error');
                        phone.siblings('.njba-form-error-message').show();
                        phone.siblings().addClass('njba-form-error-message-required');
                    } else {
                        phone.siblings().removeClass('njba-form-error-message-required');
                        phone.parent().removeClass('njba-error');
                        phone.siblings('.njba-form-error-message').hide();
                    }
                }
            } else {
                phone.siblings().removeClass('njba-form-error-message-required');
            }
            if (phone.length) {
                if (phone.val().trim() != '') {
                    if (phone_regex.test(phone.val().trim())) {
                        phone.parent().removeClass('njba-error');
                        phone.siblings('.njba-form-error-message').hide();
                    } else {
                        isValid = false;
                        phone.parent().addClass('njba-error');
                        phone.siblings('.njba-form-error-message').show();
                    }
                }
            }

            // validate the message..just make sure it's there
            if (this.msg_required == 'yes') {
                if (message.length) {
                    if (message.val().trim() == '') {

                        isValid = false;
                        message.parent().addClass('njba-error');
                        message.siblings('.njba-form-error-message').show();
                    } else if (message.parent().hasClass('njba-error')) {
                        message.parent().removeClass('njba-error');
                        message.siblings('.njba-form-error-message').hide();
                    }
                }
            }
            // validate if reCAPTCHA is enabled and checked
            if (reCaptchaField.length > 0 && isValid) {
                if ('undefined' == typeof reCaptchaValue || reCaptchaValue == false) {
                    if ('normal' == reCaptchaField.data('validate')) {
                        reCaptchaField.parent().addClass('fl-error');
                    } else if ('invisible' == reCaptchaField.data('validate')) {

                        // Invoke the reCAPTCHA check.
                        grecaptcha.execute(reCaptchaField.data('widgetid'));
                    }

                    isValid = false;
                } else {
                    reCaptchaField.parent().removeClass('fl-error');
                }
            }

            // end if we're invalid, otherwise go on..
            if (!isValid) {
                return false;
            } else {
                //console.log('success');
                // disable send button
                submit.addClass('njba-disabled');

                // post the form data
                $.post(ajaxurl, {
                    action: 'njba_builder_email',
                    name: name.val(),
                    subject: subject.val(),
                    last_name: last_name.val(),
                    email: email.val(),
                    phone: phone.val(),
                    mailto: mailto.val(),
                    message: message.val(),
                    post_id: postId,
                    node_id: nodeId,
                    template_id: templateId,
                    template_node_id: templateNodeId
                }, $.proxy(this._submitComplete, this));
            }
        },
        _removeExtraSpaces: function () {
            let textValue = $(this).val();
            textValue = textValue.replace(/ /g, "");
            $(this).val(textValue)
        },

        _removeErrorClass: function () {
            $(this).parent().removeClass('njba-error');
            $(this).siblings('.njba-form-error-message').hide();
        },
        _submitComplete: function (response) {
            //	console.log('donee');
            const urlField = $(this.nodeClass + ' .njba-success-url'),
                noMessage = $(this.nodeClass + ' .njba-success-none');

            // On success show the success message
            if (response == 1) {

                $(this.nodeClass + ' .njba-send-error').fadeOut();

                if (urlField.length > 0) {
                    window.location.href = urlField.val();
                } else if (noMessage.length > 0) {
                    noMessage.fadeIn();
                } else {
                    $(this.nodeClass + ' .njba-contact-form').hide();
                    $(this.nodeClass + ' .njba-success-msg').fadeIn();
                }
            }
            // On failure show fail message and re-enable the send button
            else {
                $(this.nodeClass + ' .njba-contact-form-submit').removeClass('njba-disabled');
                $(this.nodeClass + ' .njba-send-error').fadeIn();
                return false;
            }
        }
    };
})(jQuery);
