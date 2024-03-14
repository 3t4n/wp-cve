(function($) {

    var wtotem_add_captcha_token = function() {
        if (typeof grecaptcha === 'object') {
            grecaptcha.ready(function () {
                grecaptcha.execute(wtotem_login_vars.recaptcha_site_key, {action: 'login'}).then(function (token) {
                    var tokenField = $('#wtotem-recaptcha-token');
                    if (tokenField.length) {
                        tokenField.val(token);
                    } else {
                        tokenField = $('<input type="hidden" name="wtotem-recaptcha-token" id="wtotem-recaptcha-token" />');
                        tokenField.val(token);
                        $('#loginform').append(tokenField);
                    }
                });
            });
        } else {
            var tokenField = $('#wtotem-recaptcha-token');
            if (tokenField.length) {
                tokenField.val('grecaptcha-missing');
            } else {
                tokenField = $('<input type="hidden" name="wtotem-recaptcha-token" id="wtotem-recaptcha-token" />');
                tokenField.val('grecaptcha-missing');
                $('#loginform').append(tokenField);
            }
        }
    }

    if (parseInt(wtotem_login_vars.recaptcha_is_enabled)) {
        wtotem_add_captcha_token();
    }

    var wtotem_login_ajax = function() {

        var form =  $("#loginform");
        var data = form.serialize();
        data += '&action=wtotem_ajax&ajax_action=authenticate';

        $.ajax({
            type: 'POST',
            url: wtotem_login_vars.ajaxurl,
            dataType: 'json',
            data: data,
            success: function(response) {
                form.data('wtotemLoggingIn', 0);
                if (response.hasOwnProperty('error')) {
                    showLoginMessage(response.error, 'error');
                    $('#wtotem-token').val('');

                    if (parseInt(wtotem_login_vars.recaptcha_is_enabled)) {
                        wtotem_add_captcha_token();
                    }
                }
                else if (response.hasOwnProperty('login')) {
                    if (parseInt(wtotem_login_vars.recaptcha_is_enabled)) {
                        wtotem_add_captcha_token();
                    }
                    if (response.hasOwnProperty('2fa')) {

                        if (!$('#wtotem-token').length) {

                            form.css('position', 'relative').append(response.content);

                            $('#wtotem-token-submit').on( 'click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();

                                form.data('wtotemLoggingIn', 1);
                                $('#wp-submit').trigger('click');
                            });

                            $('#wtotem-token').focus();
                        }

                    } else {
                        form.data('wtotemLoggingIn', 1);
                        $('#wp-submit').trigger('click');
                    }

                }
            }
        });
    };


    jQuery(document).ready(function ($) {
        $("#loginform").on('submit', function(e) {

            var wtotemloggingIn = !!parseInt($(this).data('wtotemLoggingIn'));
            $(this).data('wtotemLoggingIn', 0);

            if (wtotemloggingIn) {  return; }

            if (parseInt(wtotem_login_vars.recaptcha_is_enabled)) {
                wtotem_add_captcha_token();
            }

            if (parseInt(wtotem_login_vars.two_factor_is_enabled)){
                e.preventDefault();
                e.stopPropagation();

                wtotem_login_ajax();
            }

        });
    });

    function showLoginMessage(messageHtml, type) {
        var heading = $('#login > h1');
        if (heading.length > 0) {
            var dom = (type === 'error' ? $('<div id="login_error">') : $('<p class="message">'));
            dom.addClass('wtotem-login-message');
            dom.html(messageHtml);
            heading.after(dom);
            dom.get(0).scrollIntoView();
            return;
        }

        var messageModal = $('<div>')
            .attr('id', 'wtotem-login-modal')
            .css({
                position: 'fixed',
                top: 0,
                right: 0,
                bottom: 0,
                left: 0,
                'background-color': 'rgba(0,0,0,0.5)',
                'z-index': 99999,
                display: 'flex',
                'align-items': 'center',
                'justify-content': 'center',
                padding: '16px'
            })
            .appendTo($('body'));
        messageModal.append(
            $('<div>')
                .css({
                    'background-color': '#FFF',
                    'border-radius': '4px',
                    padding: '16px',
                    'text-align': 'center'
                })
                .append(
                    $('<p>').html(messageHtml)
                )
                .append(
                    $('<button>').text('Dismiss')
                        .on('click', function() {
                            messageModal.remove();
                        })
                )
        );
    }

})(jQuery);