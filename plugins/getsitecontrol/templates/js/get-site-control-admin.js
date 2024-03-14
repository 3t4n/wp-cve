'use strict';

jQuery(document).ready(function() {
    /**
     *
     * Sign Up and Sign In pages
     *
     */
    if (jQuery('[data-auth]').length) {
        var formSender = {
            api_key: null,
            api_domain: null,
            errorMessages: {
                name: {
                    required: 'Specify your name',
                    valid: 'Enter a valid name'
                },
                email: {
                    required: 'Specify your email address',
                    valid: 'Enter a valid email address'
                },
                password: {
                    required: 'Enter your password',
                    valid: 'Your password must have a minimum of 4 characters'
                },
                site: {
                    required: 'Specify your site URL',
                    valid: 'Enter a valid URL'
                }
            },
            errors: {
                count: 0,
                stack: {}
            },
            form: null,
            fields: {},
            findFields: findFieldsForm,
            validate: validateForm,
            send: sendForm,
            markSending: markSendingForm,
            renderErrors: renderErrorsForm,
            saveApiInfo: saveApiInfo
        };

        jQuery('[data-form-validate]').submit(function() {
            formSender.form = jQuery(this);
            formSender.findFields();
            formSender.validate() ;
            formSender.send();
            return false;
        });


        jQuery('[form]').click(function() {
            var target = '.' + jQuery(this).data('close');
            jQuery(target).removeClass('show');
        });

        jQuery('.form-control').on('input', function() {
            jQuery(this).parent().removeClass('has-error');
            jQuery('.form-contents').removeClass('has-error');
            jQuery('.form-contents').find('.form-validation-message').removeClass('show');
        });

        jQuery('.social-signin__btn, .social-login-button').click(function(e) {
            window.open( jQuery(this).attr('href'), '_blank', 'location=yes,scrollbars=yes,status=yes');
            e.preventDefault();
            return false;
        });
    }

    window.addEventListener('message', onWindowMessage, false);

    function onWindowMessage(e) {
        if (
            typeof formSender != 'undefined' &&
            e.data.event == 'signupComplete' &&
            e.data.user &&
            e.data.user.api_key
        ) {
            formSender.api_key = e.data.user.api_key;
            formSender.api_domain = extract_domain(e.data.user.login_url);
            formSender.saveApiInfo();
        }
    }

    function findFieldsForm() {
        // this.fields.name = this.form.find('[name="name"]');
        this.fields.email = this.form.find('[name="email"]');
        this.fields.password = this.form.find('[name="password"]');
        // this.fields.site = this.form.find('[name="site"]');
    }

    function validateForm() {
        this.errors.stack = {};
        this.errors.count = 0;

        // if (this.fields.name.length) {
        //     if ( this.fields.name.val().length == 0 ) {
        //         this.errors.stack.name = [
        //             this.errorMessages.name.required
        //         ];
        //         this.errors.count++;
        //     } else if ( !validateName(this.fields.name.val()) ) {
        //         this.errors.stack.name = [
        //             this.errorMessages.name.valid
        //         ];
        //         this.errors.count++;
        //     }
        // }

        if (this.fields.email.length) {
            if ( this.fields.email.val().length == 0 ) {
                this.errors.stack.email = [
                    this.errorMessages.email.required
                ];
                this.errors.count++;
            } else if ( !validateEmail(this.fields.email.val()) ) {
                this.errors.stack.email = [
                    this.errorMessages.email.valid
                ];
                this.errors.count++;
            }
        }

        if (this.fields.password.length) {
            if ( this.fields.password.val().length == 0 ) {
                this.errors.stack.password = [
                    this.errorMessages.password.required
                ];
                this.errors.count++;
            } else if ( this.fields.password.val().length < 4 ) {
                this.errors.stack.password = [
                    this.errorMessages.password.valid
                ];
                this.errors.count++;
            }
        }

        // if (this.fields.site.length) {
        //     var site = this.fields.site.val();

        //     if (!/^((http|https):\/\/)/.test(site)) {
        //         this.fields.site.val( window.location.protocol + '//' + site );
        //     }

        //     if ( this.fields.site.val().length == 0 ) {
        //         this.errors.stack.site = [
        //             this.errorMessages.site.required
        //         ];
        //         this.errors.count++;
        //     } else if ( !validateURL(this.fields.site.val()) ) {
        //         this.errors.stack.site = [
        //             this.errorMessages.site.valid
        //         ];
        //         this.errors.count++;
        //     }
        // }

        this.renderErrors();
    }

    function sendForm() {
        var self = this;

        if (this.errors.count) {
            return false;
        }

        var formData ={};
        var sData = self.form.serializeArray();
        for (var i in sData) {
            if (sData.hasOwnProperty(i)) {
                formData[ sData[i].name ] = sData[i].value;
            }
        }

        if (typeof GSC_OPTIONS !== 'undefined' && GSC_OPTIONS.form_type == 'sign-up') {
            formData['tracking'] = {
                context: 'utm_campaign=WordpressPlugin&utm_medium=plugin'
            };
            formData['timezone'] =  GSC_OPTIONS.timezone;
            formData['timezone_name'] =  GSC_OPTIONS.timezone_name;
        }

        self.markSending(true);

        jQuery.ajax({
            type: "POST",
            url: typeof GSC_OPTIONS !== 'undefined' ? GSC_OPTIONS.api_url : self.form.attr('action'),
            data: JSON.stringify(formData),
            headers: {
                "Content-Type":"application/json"
            },
            success: function(data) {
                if (data.api_key) {
                    self.api_key = data.api_key;
                    self.api_domain = extract_domain(data.login_url);
                    self.saveApiInfo();
                } else {
                    self.errors.stack['__all__'] = 'Oops! Something wrong. Api key cannot be empty.';
                    self.errors.count++;
                    self.markSending();
                }
            },
            error: function(data) {
                if (data.responseText) {
                    var responseArray = JSON.parse( data.responseText );
                    if (responseArray.error) {
                        for (var key in responseArray.error.field_errors) {
                            if (responseArray.error.field_errors.hasOwnProperty(key)) {
                                self.errors.stack[key] = responseArray.error.field_errors[key];
                                self.errors.count += responseArray.error.field_errors[key].length;
                            }
                        }
                    }
                }
                self.renderErrors();
                self.markSending();
            }
        });
    }

    function markSendingForm(isSending) {
        var button = this.form.find('button[type="submit"]');
        if (isSending) {
            button.text( button.data('sending-text') ).attr('disabled', true);
        } else {
            button.attr('disabled', false).text( button.data('text') );
        }
    }

    function renderErrorsForm() {
        var generalNotification = jQuery('.form-contents');

        generalNotification.removeClass('has-error');
        this.form.find('.form-validation-message').removeClass('show');

        for (var field in this.fields) {
            if (this.fields.hasOwnProperty(field)) {
                if (this.errors.stack.hasOwnProperty(field) && this.errors.stack[field].length) {
                    this.fields[field].closest('.form-wrapper')
                        .addClass('has-error')
                        .find('.form-validation-message')
                        .text(this.errors.stack[field][0])
                        .addClass('show')
                } else {
                    this.fields[field].closest('.form-wrapper')
                        .removeClass('has-error')
                        .addClass('has-success')
                        .find('.form-validation-message')
                        .text('');
                }
            }
        }

        if (this.errors.stack['__all__']) {
            console.log("all");
            generalNotification
                .addClass('has-error')
                .find('.form-validation-general').addClass('show')
                .html( this.errors.stack['__all__'][0]);
        }
    }

    /**
     * Save API key
     */
    function saveApiInfo() {
        var self = this;

        if (self.api_key) {
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                dataType: 'json',
                data: {
                    action: typeof GSC_OPTIONS !== 'undefined' ? GSC_OPTIONS.success_action : '',
                    gsc_api_key: self.api_key,
                    gsc_api_domain: self.api_domain,
                },
                success: function(data) {
                    if (data.redirect_link) {
                        window.location.href = data.redirect_link;
                    } else {
                        self.markSending();
                        alert('Oops! Something wrong. API key was not found!');
                    }
                },
                error: function() {
                    self.markSending();
                    alert('Oops! Something wrong. It is impossible to save an API key!');
                }
            });
        } else {
            self.markSending();
            alert('Oops! Something wrong. API key was not found!');
        }
    }


    /**
     *
     * Manage Widgets page
     *
     */
    var manageListView = jQuery('[data-manage]');
    if (manageListView.length) {
        var manageWidgets = {
            site_selected_action: null,
            api_url: null,
            api_key: null,
            site_id: null,
            url_exists: false,
            sites: [],
            select: null,
            manage_link: null,
            init: initList,
            setSites: setSitesList,
            render: renderSitesList,
            changeManageLink: changeManageLinkList,
            saveWidget: saveWidgetList,
            clearApiKey: clearApiKey,
        };

        manageWidgets.init();

        manageWidgets.select.change(function() {
            manageWidgets.changeManageLink();
            manageWidgets.saveWidget();
        });

        manageWidgets.manage_link.click(function() {
            return !jQuery(this).hasClass('disabled');
        });
    }

    function initList() {
        this.select = jQuery('#widget');
        this.selected_block = jQuery('.selected-toggled-block');
        this.manage_link = jQuery('.getsitecontrol .manage-widget-link');
        this.site_selected_action = typeof GSC_OPTIONS !== 'undefined' ? GSC_OPTIONS.site_selected_action : null;
        this.api_url = typeof GSC_OPTIONS !== 'undefined' ? GSC_OPTIONS.api_url : null;
        this.api_key = typeof GSC_OPTIONS !== 'undefined' ? GSC_OPTIONS.api_key : null;
        this.site_id = typeof GSC_OPTIONS !== 'undefined' ? GSC_OPTIONS.site_id : null;
        this.script = typeof GSC_OPTIONS !== 'undefined' ? GSC_OPTIONS.script : null;
        this.setSites();
    }
    /**
     * Get list of sites via API
     */
    function setSitesList() {
        var self = this;

        jQuery.ajax({
            type: "GET",
            url: self.api_url,
            data: {
                api_key: self.api_key
            },
            complete: function () {
                manageListView.removeClass('getsitecontrol-view-loading')
            },
            success: function(data) {
                if (data.objects && data.objects.length) {
                    for (var i=0; i<data.objects.length; i++ ) {
                        var site = data.objects[i];

                        if (!self.url_exists && compare_urls(site.url,  window.location.origin)) {
                            self.url_exists = true;
                        }

                        self.sites.push({
                            id: site.id,
                            script_rendered_url: site.script_rendered_url,
                            url: site.url,
                            manage_link: typeof GSC_OPTIONS !== 'undefined' ?
                                GSC_OPTIONS['manage_site_link'].replace('<SITE_ID>', site.id) : 'javascript:void(0);'
                        });
                    }
                }

                self.render();
            },
            error: function(data) {
                console.error('response', data);
                alert("Oops! Something wrong. It's impossible to get list of sites!");
            }
        });
    }
    /**
     * Render list of sites
     */
    function renderSitesList() {
        var options = '';

        if (!this.sites.length || !this.url_exists) {
            options += '<option value="">Select a site...</option>';
        }

        if (this.sites.length) {
            for (var i=0; i<this.sites.length; i++) {
                options +=
                    '<option' +
                        (this.script == this.sites[i].script_rendered_url ? ' selected' : '') + ' value="' + this.sites[i].id + '">' +
                        this.sites[i].url +
                    '</option>';
            }

            if (this.sites.length == 1 && this.url_exists) {
               this.selected_block.hide();
            } else {
                // Change text for multiple site case
                jQuery('.manage__title').text('Select website to manage widgets');
                jQuery('.manage__text').html('Select website and open your Getsitecontrol dashboard to create and edit widgets.');
            }
        }

        this.select.html(options);
        this.select.attr('disabled', false);
        this.changeManageLink(true);

        if (GSC_OPTIONS.script != this.script) {
            this.saveWidget();
        }
    }
    /**
     * Change link for manage button
     */
    function changeManageLinkList() {
        if (this.manage_link && this.manage_link.length && this.select && this.select.length) {
            var currentSite = this.select.val();
            var manage_link_exists = false;

            for (var i=0; i<this.sites.length; i++) {
                if (this.sites[i].id == currentSite) {
                    manage_link_exists = true;
                    this.site_id = this.sites[i].id;
                    this.script = this.sites[i].script_rendered_url;
                    this.manage_link.attr('href', this.sites[i].manage_link).removeClass('disabled');
                    break;
                }
            }

            if (!manage_link_exists) {
                if (this.site_id && this.script) {
                    this.site_id = null;
                    this.script = null;
                }
                this.manage_link.attr('href', 'javascript:void(0);').addClass('disabled');
            }
        }
    }
    /**
     * Save widget's settings
     */
    function saveWidgetList() {
        var self = this;

        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            dataType: 'json',
            data: {
                action: self.site_selected_action,
                gsc_script: self.script,
                gsc_site_id: self.site_id,
            },
            success: function(data) {
                if (!!data.error) {
                    alert('Oops! Something wrong. It is impossible to save the data!');
                    window.location.reload();
                }
            },
            error: function() {
                alert('Oops! Something wrong. It is impossible to save the data! Something happens on backend part!');
            }
        });
    }


    /**
     * Clear API key request
     */
    function clearApiKey() {
        var self = this;
        self.api_key = null;

        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            dataType: 'json',
            data: {
                action: typeof GSC_OPTIONS !== 'undefined' ? GSC_OPTIONS.clear_api_key_action : '',
                gsc_clear_api_key: true
            },
            success: function(data) {
                if (!data.error) {
                    if (data.redirect_link) {
                        window.location.href = data.redirect_link;
                    } else {
                        window.location.reload();
                    }
                } else {
                    alert('Oops! Something wrong. Your API key is expired!');
                }
            },
            error: function() {
                alert('Oops! Something wrong. It is impossible to save an API key!');
            }
        });
    }


    if (jQuery('[data-logout]').length) {
        var logoutPage = {
            api_key: typeof GSC_OPTIONS !== 'undefined' ? GSC_OPTIONS.api_key : null,
            clearApiKey: clearApiKey
        };

        logoutPage.clearApiKey();
    }


    /**
     * Name validator
     *
     * @param name
     * @returns {boolean}
     */
    function validateName(name) {
        var regex = /^[a-zA-Zа-яёА-ЯЁ0-9_\.\s\-]{3,}$/i;
        return regex.test(name);
    }


    /**
     * E-mail validator
     *
     * @param email
     * @returns {boolean}
     */
    function validateEmail(email) {
        var regex = /^[a-z0-9!#$%&'*+\/=?^_`{|}~.-]+@[a-z0-9]([a-z0-9-]*[a-z0-9])?(\.[a-z0-9]([a-z0-9-]*[a-z0-9])?)*$/i;
        return regex.test(email);
    }


    /**
     * URL validator
     *
     * @param url
     * @returns {boolean}
     */
    function validateURL(url) {
        var regex = /^[a-z][a-z\d.+-]*:\/*(?:[^:@]+(?::[^@]+)?@)?(?:[^\s:/?#]+|\[[a-f\d:]+\])(?::\d+)?(?:\/[^?#]*)?(?:\?[^#]*)?(?:#.*)?$/i;
        return regex.test(url);
    }


    /**
    * URL comparator
    *
    * @param url1
    * @param url2
    * @returns {boolean}
    */
    function compare_urls(url1, url2) {
        if (!url1 || !url2) {
            return false
        }

        url1 = url1.replace(/https?:\/\//, '').replace(/\/$/,'');
        url2 = url2.replace(/https?:\/\//, '').replace(/\/$/,'');
        return url1 == url2;
    }

    function extract_domain(url){
        return url.split('//')[1].split('/')[0]
    }
});
