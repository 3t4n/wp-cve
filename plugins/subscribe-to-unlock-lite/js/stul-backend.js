jQuery(document).ready(function ($) {
    "use strict";
    /**
     *
     * @type object
     */
    var notice_timeout;

    /**
     * @type object
     */
    var translation_strings = stul_backend_obj.translation_strings;

    /**
     * Generates required notice
     *
     * @param {string} info_text
     * @param {string} info_type
     *
     */
    function stul_generate_info(info_text, info_type) {
        clearTimeout(notice_timeout);
        switch (info_type) {
            case 'error':
                var info_html = '<p class="stul-error"><i class="fas fa-times"></i>' + info_text + '</p>';
                break;
            case 'info':
                var info_html = '<p class="stul-info"><i class="fas fa-check"></i>' + info_text + '</p>';
                break;
            case 'ajax':
                var info_html = '<p class="stul-ajax"><img src="' + stul_backend_obj.plugin_url + 'images/ajax-loader.gif" class="stul-ajax-loader"/>' + info_text + '</p>';
            default:
                break;

        }
        $('.stul-form-message').html(info_html).show();
        if (info_type != 'ajax') {
            notice_timeout = setTimeout(function () {
                $('.stul-form-message').slideUp(1000);
            }, 5000);
        }

    }

    /**
     * Performs clipboard copy action
     * 
     * @param {object} element
     * @returns null
     */
    function stul_copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
    }

    function stul_title_to_alias(str) {
        str = str.replace(/^\s+|\s+$/g, ''); // trim
        str = str.toLowerCase();

        // remove accents, swap ñ for n, etc
        var from = "àáäâèéëêìíïîòóöôùúüûñç·/,:;";
        var to = "aaaaeeeeiiiioooouuuunc------";
        for (var i = 0, l = from.length; i < l; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }

        str = str.replace(/[^a-z0-9 _]/g, '') // remove invalid chars
                .replace(/\s+/g, '_') // collapse whitespace and replace by _
                .replace(/_+/g, '_'); // collapse dashes

        return str;
    }

    /**
     * Settings section show hide
     */
    $('body').on('click', '.stul-nav-item', function () {
        var tab = $(this).data('tab');
        $('.stul-nav-item').removeClass('stul-active-nav');
        $(this).addClass('stul-active-nav');
        $('.stul-settings-each-section').hide();
        $('.stul-settings-each-section[data-tab="' + tab + '"]').show();

    });

    /**
     * Form components slide toggle
     */

    $('body').on('click', '.stul-component-head h4', function () {
        $(this).closest('.stul-form-each-component').find('.stul-component-body').slideToggle();
        if ($(this).next('.dashicons').hasClass('dashicons-arrow-down')) {
            $(this).next('.dashicons').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');
        } else {
            $(this).next('.dashicons').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');
        }
    });

    /**
     * Open Media Uploader
     */
    $('body').on('click', '.stul-file-uploader', function () {
        var selector = $(this);

        var image = wp.media({
            title: translation_strings.upload_button_text,
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
                .on('select', function (e) {
                    // This will return the selected image from the Media Uploader, the result is an object
                    var uploaded_image = image.state().get('selection').first();
                    console.log(uploaded_image.toJSON());
                    // We convert uploaded_image to a JSON object to make accessing it easier
                    // Output to the console uploaded_image
                    var image_url = uploaded_image.toJSON().url;
                    var image_id = uploaded_image.toJSON().id;
                    // Let's assign the url value to the input field
                    selector.parent().find('input[type="text"]').val(image_url);
                    selector.parent().find('input[type="hidden"]').val(image_id);
                    if (selector.parent().find('.stul-image-preview').length > 0) {
                        selector.parent().find('.stul-image-preview').html('<img src="' + uploaded_image.toJSON().sizes.thumbnail.url + '"/>');
                    }
                });
    });

    /**
     * Shortcode generation through alias
     */
    $('body').on('keyup', '.stul-alias-field', function () {
        var alias = $(this).val();
        var shortcode = '[stu alias="' + alias + '"]';
        $('.stul-shortcode-preview').html(shortcode);
    });



    /**
     * Subscription Form Settings submission
     * 
     */
    $('body').on('submit', '.stul-subscription-form', function (e) {
        e.preventDefault();
        var selector = $(this);
        if ($('#stul_lock_content').length > 0) {
            tinyMCE.triggerSave();
        }
        var form_data = $(this).serialize();
        var form_action = ($(this).data('form-action')) ? $(this).data('form-action') : 'stul_form_save_action';
        $.ajax({
            type: 'post',
            url: stul_backend_obj.ajax_url,
            data: {
                action: form_action,
                _wpnonce: stul_backend_obj.ajax_nonce,
                form_data: form_data
            },
            beforeSend: function (xhr) {
                stul_generate_info(translation_strings.ajax_message, 'ajax');
            },
            success: function (res) {
                res = $.parseJSON(res);
                if (res.status == 200) {
                    stul_generate_info(res.message, 'info');
                    if (res.redirect_url) {
                        window.location = res.redirect_url;
                        exit;
                    }
                } else {
                    stul_generate_info(res.message, 'error');
                }
            }
        });
    });

    /**
     * Subscription Form Delete
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.stul-form-delete', function () {
        if (confirm(translation_strings.delete_form_confirm)) {
            var selector = $(this);
            var form_id = $(this).data('form-id');
            $.ajax({
                type: 'post',
                url: stul_backend_obj.ajax_url,
                data: {
                    action: 'stul_form_delete_action',
                    form_id: form_id,
                    _wpnonce: stul_backend_obj.ajax_nonce,
                },
                beforeSend: function (xhr) {
                    stul_generate_info(translation_strings.ajax_message, 'ajax');
                },
                success: function (res) {
                    res = $.parseJSON(res);
                    if (res.status == 200) {
                        stul_generate_info(res.message, 'info');
                        selector.closest('tr').fadeOut(500, function () {
                            $(this).remove();
                        });
                    } else {
                        stul_generate_info(res.message, 'error');
                    }
                }
            });
        }
    });
    /**
     * Subscription Form Copy
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.stul-form-copy', function () {
        if (confirm(translation_strings.copy_form_confirm)) {
            var selector = $(this);
            var form_id = $(this).data('form-id');
            $.ajax({
                type: 'post',
                url: stul_backend_obj.ajax_url,
                data: {
                    action: 'stul_form_copy_action',
                    form_id: form_id,
                    _wpnonce: stul_backend_obj.ajax_nonce,
                },
                beforeSend: function (xhr) {
                    stul_generate_info(translation_strings.ajax_message, 'ajax');
                },
                success: function (res) {
                    res = $.parseJSON(res);
                    if (res.status == 200) {
                        stul_generate_info(res.message, 'info');
                        stul_generate_info(res.message, 'info');
                        if (res.redirect_url) {
                            window.location = res.redirect_url;
                            exit;
                        }
                    } else {
                        stul_generate_info(res.message, 'error');
                    }
                }
            });
        }
    });

    /**
     * Subscriber alias filter
     * 
     * @since 1.0.0
     */
    $('body').on('change', '.stul-export-alias-trigger', function () {
        $(this).closest('form').submit();
    });

    /**
     * Shortcode clipboard copy
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.stul-clipboard-copy', function () {
        var copy_element = $(this).parent().find('.stul-shortcode-preview').select();
        stul_copyToClipboard(copy_element);
        stul_generate_info(translation_strings.clipboad_copy_message, 'info');
    });

    /**
     * Show hide toggle
     * 
     * @since 1.0.0
     */
    $('body').on('change', '.stul-toggle-trigger', function () {
        var toggle_ref = $(this).val();
        var toggle_class = $(this).data('toggle-class');
        $('.' + toggle_class).hide();
        $('.' + toggle_class + '[data-toggle-ref="' + toggle_ref + '"]').show();

    });

    /**
     * Color Picker Initialization
     * 
     * @since 1.0.0
     */
    $('.stul-color-picker').wpColorPicker();

    $('.stul-field input[type="checkbox"]').each(function () {
        if (!$(this).parent().hasClass('stul-checkbox-toggle') && !$(this).hasClass('stul-disable-checkbox-toggle')) {
            var input_name = $(this).attr('name');
            $(this).parent().addClass('stul-checkbox-toggle');
            $('<label></label>').insertAfter($(this));
        }
    });


    /**
     * Form save trigger 
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.stul-form-save-trigger', function () {
        $('.stul-subscription-form').submit();
    });

    /**
     * Mailchimp connect trigger
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.stul-mailchimp-connect', function () {
        $('.stul-mailchimp-api-response').slideUp(500);
        var mailchimp_api_key = $('.stul-mailchimp-api-key').val();
        var api_pattern = /^[0-9a-zA-Z*]{32}-[a-z]{2}[0-9]{1,2}$/;
        if (api_pattern.test(mailchimp_api_key)) {
            $.ajax({
                type: 'post',
                url: stul_backend_obj.ajax_url,
                data: {
                    action: 'stul_mailchimp_connect_action',
                    api_key: mailchimp_api_key,
                    _wpnonce: stul_backend_obj.ajax_nonce,
                },
                beforeSend: function (xhr) {
                    stul_generate_info(translation_strings.ajax_message, 'ajax');
                },
                success: function (res) {
                    res = $.parseJSON(res);
                    if (res.status == 200) {
                        stul_generate_info(res.message, 'info');
                        stul_generate_info(res.message, 'info');
                        var list_template = wp.template('mc-lists');
                        $('.stul-mailchimp-lists-wrap').html(list_template(res.api_response));
                        $('.stul-mailchimp-lists-input').val(res.api_raw_response);
                        $('.stul-mailchimp-status-flag').val(1);
                        $('.stul-mailchimp-status').html(translation_strings.mc_connect);
                        $('.stul-mailchimp-status').removeClass('stul-mc-disconnected').addClass('stul-mc-connected');
                    } else {
                        stul_generate_info(res.message, 'error');
                        $('.stul-mailchimp-lists-wrap').html('');
                        $('.stul-mailchimp-lists-input').val('');
                        $('.stul-mailchimp-status-flag').val(0);
                        $('.stul-mailchimp-status').html(translation_strings.mc_disconnect);
                        $('.stul-mailchimp-status').removeClass('stul-mc-connected').addClass('stul-mc-disconnected');
                        if (res.api_response) {
                            $('.stul-mailchimp-api-response').html(JSON.stringify(res.api_response)).show();
                        }
                    }
                }
            });
        } else {
            stul_generate_info(translation_strings.invalid_api_key, 'error');
        }
    });

    /**
     * Resets mailchimp connection trigger
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.stul-mailchimp-reset', function () {
        if (confirm(translation_strings.mc_reset)) {
            $('.stul-mailchimp-lists-wrap').html('');
            $('.stul-mailchimp-lists-input').val('');
            $('.stul-mailchimp-status-flag').val(0);
            $('.stul-mailchimp-status').html(translation_strings.mc_disconnect);
            $('.stul-mailchimp-status').removeClass('stul-mc-connected').addClass('stul-mc-disconnected');
        }
    });

    $('body').on('click', '.stul-checkbox-toggle-trigger', function () {
        var toggle_class = $(this).data('toggle-class');
        var toggle_type = ($(this).data('toggle-type')) ? $(this).data('toggle-type') : 'on';
        switch (toggle_type) {
            case 'on':
                if ($(this).is(':checked')) {
                    $('.' + toggle_class).show();
                } else {
                    $('.' + toggle_class).hide();
                }
                break;
            case 'off':
                if ($(this).is(':checked')) {
                    $('.' + toggle_class).hide();
                } else {
                    $('.' + toggle_class).show();

                }
                break;
        }

    });

    /**
     * Log clear trigger
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.stul-clear-log-trigger', function () {
        $(this).parent().find('textarea').val('');
    });

    /**
     * Constant Contact connect trigger
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.stul-constant_contact-connect', function () {
        $('.stul-constant_contact-api-response').slideUp(500);
        var cc_api_key = $('.stul-constant_contact-api-key').val();
        var cc_access_token = $('.stul-constant_contact-access-token').val();
        $.ajax({
            type: 'post',
            url: stul_backend_obj.ajax_url,
            data: {
                action: 'stul_constant_contact_connect_action',
                api_key: cc_api_key,
                access_token: cc_access_token,
                _wpnonce: stul_backend_obj.ajax_nonce,
            },
            beforeSend: function (xhr) {
                stul_generate_info(translation_strings.ajax_message, 'ajax');
            },
            success: function (res) {
                res = $.parseJSON(res);
                if (res.status == 200) {
                    stul_generate_info(res.message, 'info');
                    stul_generate_info(res.message, 'info');
                    var list_template = wp.template('mc-lists');
                    $('.stul-constant_contact-lists-wrap').html(list_template(res.api_response));
                    $('.stul-constant_contact-lists-input').val(res.api_raw_response);
                    $('.stul-constant_contact-status-flag').val(1);
                    $('.stul-constant_contact-status').html(translation_strings.mc_connect);
                    $('.stul-constant_contact-status').removeClass('stul-cc-disconnected').addClass('stul-cc-connected');
                } else {
                    stul_generate_info(res.message, 'error');
                    $('.stul-constant_contact-lists-wrap').html('');
                    $('.stul-constant_contact-lists-input').val('');
                    $('.stul-constant_contact-status-flag').val(0);
                    $('.stul-constant_contact-status').html(translation_strings.mc_disconnect);
                    $('.stul-constant_contact-status').removeClass('stul-cc-connected').addClass('stul-cc-disconnected');
                    if (res.api_response) {
                        $('.stul-constant_contact-api-response').html(JSON.stringify(res.api_response)).show();
                    }
                }
            }
        });

    });

    /**
     * Resets constant contact connection trigger
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.stul-constant_contact-reset', function () {
        if (confirm(translation_strings.cc_reset)) {
            $('.stul-constant_contact-lists-wrap').html('');
            $('.stul-constant_contact-lists-input').val('');
            $('.stul-constant_contact-status-flag').val(0);
            $('.stul-constant_contact-status').html(translation_strings.mc_disconnect);
            $('.stul-constant_contact-status').removeClass('stul-cc-connected').addClass('stul-cc-disconnected');
        }
    });

    /**
     * Deletes subscriber
     * 
     * @since 1.0.0
     */
    /**
     * Subscription Form Delete
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.stul-subscriber-delete', function () {
        if (confirm(translation_strings.delete_subscriber_confirm)) {
            var selector = $(this);
            var subscriber_id = $(this).data('subscriber-id');
            $.ajax({
                type: 'post',
                url: stul_backend_obj.ajax_url,
                data: {
                    action: 'stul_subscriber_delete_action',
                    subscriber_id: subscriber_id,
                    _wpnonce: stul_backend_obj.ajax_nonce,
                },
                beforeSend: function (xhr) {
                    stul_generate_info(translation_strings.ajax_message, 'ajax');
                },
                success: function (res) {
                    res = $.parseJSON(res);
                    if (res.status == 200) {
                        stul_generate_info(res.message, 'info');
                        selector.closest('tr').fadeOut(500, function () {
                            $(this).remove();
                        });
                    } else {
                        stul_generate_info(res.message, 'error');
                    }
                }
            });
        }
    });

    $('body').on('keyup', 'input[name="form_title"]', function () {
        var form_title = $(this).val();
        var form_alias = stul_title_to_alias(form_title);
        if ($('input[name="form_alias"]').attr('readonly') != 'readonly') {
            $('input[name="form_alias"]').val(form_alias);

        }
    });

    $('body').on('keyup', 'input[name="form_alias"]', function () {
        var form_alias = $(this).val();
        form_alias = stul_title_to_alias(form_alias);

        if ($(this).attr('readonly') != 'readonly') {
            $(this).val(form_alias);

        }
    });

    $('body').on('click', '.stul-alias-force-edit', function () {
        $(this).parent().find('input[type="text"]').removeAttr('readonly');
    });


});


