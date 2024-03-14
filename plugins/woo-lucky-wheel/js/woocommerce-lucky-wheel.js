"use strict";
jQuery(document).ready(function ($) {
    let custom_field_name_enable = _wlwl_get_email_params.custom_field_name_enable;
    let custom_field_name_required = _wlwl_get_email_params.custom_field_name_required;

    let wlwl_hide_popup = _wlwl_get_email_params.hide_popup;
    let color = _wlwl_get_email_params.bg_color;
    let label = _wlwl_get_email_params.label;
    let piece_coupons = _wlwl_get_email_params.coupon_type;
    let wlwl_auto_close = parseInt(_wlwl_get_email_params.auto_close);
    let wlwl_notify_position = _wlwl_get_email_params.position;
    let wlwl_show_again = _wlwl_get_email_params.show_again;
    let wlwl_show_again_unit = _wlwl_get_email_params.show_again_unit;
    let time_if_close = _wlwl_get_email_params.time_if_close;
    switch (wlwl_show_again_unit) {
        case 'm':
            wlwl_show_again *= 60;
            break;
        case 'h':
            wlwl_show_again *= 60 * 60;
            break;
        case 'd':
            wlwl_show_again *= 60 * 60 * 24;
            break;
        default:
    }
    let intent_type = _wlwl_get_email_params.intent;
    let initial_time = _wlwl_get_email_params.show_wheel;
    let wlwl_center_color = _wlwl_get_email_params.wheel_center_color;
    let wlwl_border_color = '#ffffff';
    let wlwl_dot_color = '#000000';
    let gdpr_checkbox = _wlwl_get_email_params.gdpr;
    let slice_text_color = _wlwl_get_email_params.slice_text_color;
    let wlwl_spinning_time = 8;
    let wheel_speed = 5;
    let slices = piece_coupons.length;
    let sliceDeg = 360 / slices;
    let deg = -(sliceDeg / 2);
    let cv = document.getElementById('wlwl_canvas');
    let ctx = cv.getContext('2d');

    let canvas_width;
    let wd_width, wd_height;
    wd_width = window.innerWidth;
    wd_height = window.innerHeight;
    if (wd_width > wd_height) {
        canvas_width = wd_height;
    } else {
        canvas_width = wd_width;
    }
    let width = parseInt(canvas_width * 0.55 + 16);// size
    cv.width = width;
    cv.height = width;
    if (window.devicePixelRatio) {
        let hidefCanvasWidth = $(cv).attr('width');
        let hidefCanvasHeight = $(cv).attr('height');
        let hidefCanvasCssWidth = hidefCanvasWidth;
        let hidefCanvasCssHeight = hidefCanvasHeight;

        $(cv).attr('width', hidefCanvasWidth * window.devicePixelRatio);
        $(cv).attr('height', hidefCanvasHeight * window.devicePixelRatio);
        $(cv).css('width', hidefCanvasCssWidth);
        $(cv).css('height', hidefCanvasCssHeight);
        ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
    }

    let center = (width) / 2; // center
    $('.wheel_spin').css({'width': width + 'px', 'height': width + 'px'});
    if ('on' === _wlwl_get_email_params.show_full_wheel) {
        $('.wlwl_lucky_wheel_content').css({'max-width': (width + 600) + 'px'});
    } else {
        $('.wlwl_lucky_wheel_content').css({'max-width': (0.6 * width + 600) + 'px'});
    }
    let inline_css = '.wlwl_lucky_wheel_content:not(.wlwl_lucky_wheel_content_mobile) .wheel-content-wrapper .wheel_content_left{min-width:' + (width + 35) + 'px}';
    inline_css += '.wlwl_pointer:before{font-size:' + parseInt(width / 4) + 'px; }';

    $('head').append('<style type="text/css">' + inline_css + '</style>');
    let wheel_text_size;
    wheel_text_size = parseInt(width / 28);

    function deg2rad(deg) {
        return deg * Math.PI / 180;
    }

    function drawSlice(deg, color) {
        ctx.beginPath();
        ctx.fillStyle = color;
        ctx.moveTo(center, center);
        let r;
        if (width <= 480) {
            r = width / 2 - 10;
        } else {
            r = width / 2 - 14;
        }
        ctx.arc(center, center, r, deg2rad(deg), deg2rad(deg + sliceDeg));
        ctx.lineTo(center, center);
        ctx.fill();
        ctx.closePath();
        ctx.restore();
    }

    function drawPoint(deg, color) {
        ctx.save();
        ctx.beginPath();
        ctx.fillStyle = color;
        ctx.shadowBlur = 1;
        ctx.shadowOffsetX = 8;
        ctx.shadowOffsetY = 8;
        ctx.shadowColor = 'rgba(0,0,0,0.2)';
        ctx.arc(center, center, width / 8, 0, 2 * Math.PI);
        ctx.fill();

        ctx.clip();
        ctx.closePath();
        ctx.restore();
    }

    function drawBorder(borderC, dotC, lineW, dotR, des, shadColor) {
        ctx.beginPath();
        ctx.strokeStyle = borderC;
        ctx.lineWidth = lineW;
        ctx.shadowBlur = 1;
        ctx.shadowOffsetX = 8;
        ctx.shadowOffsetY = 8;
        ctx.shadowColor = shadColor;
        ctx.arc(center, center, center, 0, 2 * Math.PI);
        ctx.stroke();
        let x_val, y_val, deg;
        deg = sliceDeg / 2;
        let center1 = center - des;
        for (let i = 0; i < slices; i++) {
            ctx.beginPath();
            ctx.fillStyle = dotC;
            x_val = center + center1 * Math.cos(deg * Math.PI / 180);
            y_val = center - center1 * Math.sin(deg * Math.PI / 180);
            ctx.arc(x_val, y_val, dotR, 0, 2 * Math.PI);
            ctx.fill();
            deg += sliceDeg;
        }
        ctx.closePath();
        ctx.restore();
    }

    function drawText(deg, text, color) {
        ctx.save();
        ctx.translate(center, center);
        ctx.rotate(deg2rad(deg));
        ctx.textAlign = "right";
        ctx.fillStyle = color;
        ctx.font = '200 ' + wheel_text_size + 'px Helvetica';
        ctx.shadowOffsetX = 0;
        ctx.shadowOffsetY = 0;
        text = text.replace(/&#(\d{1,4});/g, function (fullStr, code) {
            return String.fromCharCode(code);
        });
        text = text.replace(/&nbsp;/g, ' ');
        let reText = text.split('\/n'), text1 = '', text2 = '';
        if (reText.length > 1) {
            text1 = reText[0];
            text2 = reText.splice(1, reText.length - 1);
            text2 = text2.join('');
        }
        if (text1.trim() !== "" && text2.trim() !== "") {
            ctx.fillText(text1.trim(), 7 * center / 8, -(wheel_text_size * 1 / 4));
            ctx.fillText(text2.trim(), 7 * center / 8, wheel_text_size * 3 / 4);
        } else {
            ctx.fillText(text, 7 * center / 8, wheel_text_size / 2 - 2);
        }
        ctx.restore();
    }

//cookie
    function setCookie(cname, cvalue, expire) {
        let d = new Date();
        d.setTime(d.getTime() + (expire * 1000));
        let expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        let name = cname + "=";
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function overlay_function() {
        $('.wlwl-overlay').on('click', function () {
            $('html').removeClass('wlwl-html');
            $(this).hide();
            $('.wlwl_lucky_wheel_content').removeClass('lucky_wheel_content_show');
            setCookie('wlwl_cookie', 'closed', time_if_close);
            if (wlwl_hide_popup !== 'on') {
                $('.wlwl_wheel_icon').addClass('wlwl_show');
            }
        });
    }

    function spins_wheel(stop_position, result_notification, result) {
        let canvas_1 = $('#wlwl_canvas');
        let canvas_3 = $('#wlwl_canvas2');
        let default_css = '';
        if (window.devicePixelRatio) {
            default_css = 'width:' + width + 'px;height:' + width + 'px;';
        }
        canvas_1.attr('style', default_css);
        canvas_3.attr('style', default_css);
        let stop_deg = 360 - sliceDeg * stop_position;
        let wheel_stop = wheel_speed * 360 * wlwl_spinning_time + stop_deg;
        let css = default_css + '-moz-transform: rotate(' + wheel_stop + 'deg);-webkit-transform: rotate(' + wheel_stop + 'deg);-o-transform: rotate(' + wheel_stop + 'deg);-ms-transform: rotate(' + wheel_stop + 'deg);transform: rotate(' + wheel_stop + 'deg);';
        css += '-webkit-transition: transform ' + wlwl_spinning_time + 's ease-out;-moz-transition: transform ' + wlwl_spinning_time + 's ease-out;-ms-transition: transform ' + wlwl_spinning_time + 's ease-out;-o-transition: transform ' + wlwl_spinning_time + 's ease-out;transition: transform ' + wlwl_spinning_time + 's ease-out;';
        canvas_1.attr('style', css);
        canvas_3.attr('style', css);
        setTimeout(function () {
            css = default_css + 'transform: rotate(' + stop_deg + 'deg);';
            canvas_1.attr('style', css);
            canvas_3.attr('style', css);

            $('.wlwl_lucky_wheel_content').addClass('wlwl-finish-spinning');
            $('.wlwl-overlay').unbind();
            $('.wlwl-overlay').on('click', function () {
                $('html').removeClass('wlwl-html');
                $(this).hide();

                $('.wlwl_lucky_wheel_content').removeClass('lucky_wheel_content_show');
                $('.wlwl_wheel_spin').css({'margin-left': '0', 'transition': '2s'});
            });
            $('.wlwl_user_lucky').html('<div class="wlwl-frontend-result">' + result_notification + '</div>');
            $('.wlwl_user_lucky').fadeIn(300);
            if (wlwl_auto_close > 0) {
                setTimeout(function () {
                    $('.wlwl-overlay').hide();
                    $('html').removeClass('wlwl-html');
                    $('.wlwl_lucky_wheel_content').removeClass('lucky_wheel_content_show');
                    $('.wlwl_wheel_spin').css({'margin-left': '0', 'transition': '2s'});
                }, wlwl_auto_close * 1000);
            }
        }, parseInt(wlwl_spinning_time * 1000))
    }

    function isValidEmailAddress(emailAddress) {
        let pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/i;
        return pattern.test(emailAddress);
    }

    function check_email() {
        $(document).on('keypress', function (e) {
            if ($('.wlwl_lucky_wheel_content').hasClass('lucky_wheel_content_show') && e.keyCode === 13) {
                $('#wlwl_chek_mail').click();
            }
        });
        $('#wlwl_chek_mail').on('click', function () {
            $('#wlwl_error_mail').html('');
            $('#wlwl_error_name').html('');
            $('#wlwl_error_mobile').html('');
            $('.wlwl_field_name').removeClass('wlwl-required-field');
            $('.wlwl_field_mobile').removeClass('wlwl-required-field');
            $('.wlwl_field_email').removeClass('wlwl-required-field');
            if ('on' === gdpr_checkbox && !$('.wlwl-gdpr-checkbox-wrap input[type="checkbox"]').prop('checked')) {
                alert(_wlwl_get_email_params.gdpr_warning);
                return false;
            }
            let wlwl_email = $('#wlwl_player_mail').val();
            let wlwl_name = $('#wlwl_player_name').val();
            let qualified = true;
            if (custom_field_name_enable == 'on' && custom_field_name_required == 'on' && !wlwl_name) {
                $('#wlwl_error_name').html(_wlwl_get_email_params.custom_field_name_message);
                $('.wlwl_field_name').addClass('wlwl-required-field');
                qualified = false;
            }
            if (!wlwl_email) {
                $('#wlwl_player_mail').prop('disabled', false).focus();
                $('#wlwl_error_mail').html(_wlwl_get_email_params.empty_email_warning);
                $('.wlwl_field_email').addClass('wlwl-required-field');
                qualified = false;
            }
            if (qualified == false) {
                return false;
            }
            $(this).unbind();
            $('.wlwl-overlay').unbind();
            $('#wlwl_player_mail').prop('disabled', true);
            if (getCookie('wlwl_cookie') === "") {
                if (isValidEmailAddress($('#wlwl_player_mail').val())) {
                    $('#wlwl_error_mail').html('');
                    $('#wlwl_chek_mail').addClass('wlwl-adding');

                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: _wlwl_get_email_params.ajaxurl,
                        data: {
                            user_email: wlwl_email,
                            user_name: wlwl_name,
                            _woocommerce_lucky_wheel_nonce: $('#_woocommerce_lucky_wheel_nonce').val(),
                        },
                        success: function (response) {
                            if (response.allow_spin === 'yes') {
                                $('.wlwl-show-again-option').hide();
                                $('.wlwl-close-wheel').hide();
                                $('.wlwl-hide-after-spin').show();
                                spins_wheel(response.stop_position, response.result_notification, response.result);

                                setCookie('wlwl_cookie', wlwl_email, wlwl_show_again);
                            } else {
                                $('#wlwl_chek_mail').removeClass('wlwl-adding');
                                $('#wlwl_player_mail').prop('disabled', false);
                                check_email();
                                overlay_function();
                                alert(response.allow_spin);

                            }
                        }
                    });

                } else {
                    $('#wlwl_player_mail').prop('disabled', false).focus();
                    check_email();
                    overlay_function();
                    $('#wlwl_error_mail').html(_wlwl_get_email_params.invalid_email_warning);
                    $('.wlwl_field_email').addClass('wlwl-required-field');
                }

            } else {
                alert(_wlwl_get_email_params.limit_time_warning);
                $('#wlwl_player_mail').prop('disabled', false);
                check_email();
                overlay_function();
            }
        });
    }

    overlay_function();
    check_email();
    let center1 = 32;

    if (!getCookie('wlwl_cookie') || getCookie('wlwl_cookie') == "") {
        $('.wlwl-hide-after-spin').bind('click', function () {
            $('.wlwl-overlay').hide();
            $('html').removeClass('wlwl-html');
            $('.wlwl_lucky_wheel_content').removeClass('lucky_wheel_content_show');
            $('.wheel_spin').css({'margin-left': '0', 'transition': '2s'});
        });

        $('.wlwl-reminder-later-a').unbind();
        $('.wlwl-reminder-later-a').bind('click', function () {
            setCookie('wlwl_cookie', 'reminder_later', 24 * 60 * 60);

            $('.wlwl_wheel_icon').addClass('wlwl_show');

            $('.wlwl-overlay').hide();
            $('html').removeClass('wlwl-html');
            $('.wlwl_lucky_wheel_content').removeClass('lucky_wheel_content_show');
        });
        $('.wlwl-never-again span').unbind();
        $('.wlwl-never-again span').bind('click', function () {
            setCookie('wlwl_cookie', 'never_show_again', 30 * 24 * 60 * 60);
            $('.wlwl_wheel_icon').addClass('wlwl_show');

            $('.wlwl-overlay').hide();
            $('html').removeClass('wlwl-html');
            $('.wlwl_lucky_wheel_content').removeClass('lucky_wheel_content_show');
        });
        $('.wlwl-close span').on('click', function () {
            $('.wlwl-overlay').hide();
            setCookie('wlwl_cookie', 'closed', time_if_close);
            $('html').removeClass('wlwl-html');
            if (wlwl_hide_popup !== 'on') {
                $('.wlwl_wheel_icon').addClass('wlwl_show');
            }
            $('.wlwl_lucky_wheel_content').removeClass('lucky_wheel_content_show');
        });
        $('.wlwl-close-wheel span').on('click', function () {
            $('.wlwl-overlay').hide();
            $('html').removeClass('wlwl-html');
            $('.wlwl_lucky_wheel_content').removeClass('lucky_wheel_content_show');
            setCookie('wlwl_cookie', 'closed', time_if_close);
            if (wlwl_hide_popup !== 'on') {
                $('.wlwl_wheel_icon').addClass('wlwl_show');
            }
        });


        $('.woocommerce-lucky-wheel-popup-icon').on('click', function () {
            $('.wlwl_wheel_icon').removeClass('wlwl_show');
            $('.wlwl-overlay').show();
            $('html').addClass('wlwl-html');
            $('.wlwl_lucky_wheel_content').addClass('lucky_wheel_content_show');
        });

        for (let i = 0; i < slices; i++) {
            drawSlice(deg, color[i]);
            drawText(deg + sliceDeg / 2, label[i], slice_text_color);
            deg += sliceDeg;

        }
        cv = document.getElementById('wlwl_canvas1');
        ctx = cv.getContext('2d');
        cv.width = width;
        cv.height = width;
        if (window.devicePixelRatio) {
            let hidefCanvasWidth = $(cv).attr('width');
            let hidefCanvasHeight = $(cv).attr('height');
            let hidefCanvasCssWidth = hidefCanvasWidth;
            let hidefCanvasCssHeight = hidefCanvasHeight;

            $(cv).attr('width', hidefCanvasWidth * window.devicePixelRatio);
            $(cv).attr('height', hidefCanvasHeight * window.devicePixelRatio);
            $(cv).css('width', hidefCanvasCssWidth);
            $(cv).css('height', hidefCanvasCssHeight);
            ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
        }
        drawPoint(deg, wlwl_center_color);
        if (width <= 480) {
            drawBorder(wlwl_border_color, 'rgba(0,0,0,0)', 20, 4, 5, 'rgba(0,0,0,0.2)');

        } else {
            drawBorder(wlwl_border_color, 'rgba(0,0,0,0)', 30, 6, 7, 'rgba(0,0,0,0.2)');
        }

        cv = document.getElementById('wlwl_canvas2');
        ctx = cv.getContext('2d');

        cv.width = width;
        cv.height = width;
        if (window.devicePixelRatio) {
            let hidefCanvasWidth = $(cv).attr('width');
            let hidefCanvasHeight = $(cv).attr('height');
            let hidefCanvasCssWidth = hidefCanvasWidth;
            let hidefCanvasCssHeight = hidefCanvasHeight;

            $(cv).attr('width', hidefCanvasWidth * window.devicePixelRatio);
            $(cv).attr('height', hidefCanvasHeight * window.devicePixelRatio);
            $(cv).css('width', hidefCanvasCssWidth);
            $(cv).css('height', hidefCanvasCssHeight);
            ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
        }
        if (width <= 480) {
            drawBorder('rgba(0,0,0,0)', wlwl_dot_color, 20, 4, 5, 'rgba(0,0,0,0)');

        } else {
            drawBorder('rgba(0,0,0,0)', wlwl_dot_color, 30, 6, 7, 'rgba(0,0,0,0)');
        }

        if (intent_type === 'popup_icon') {
            let notify_time_out = setTimeout(function () {
                $('.wlwl_wheel_icon').addClass('wlwl_show');
            }, initial_time * 1000);
        } else if (intent_type === 'show_wheel') {
            setTimeout(function () {
                $('.wlwl-overlay').show();
                $('html').addClass('wlwl-html');
                $('.wlwl_lucky_wheel_content').addClass('lucky_wheel_content_show');
            }, initial_time * 1000);
        }
    }

    function drawPopupIcon() {
        cv = document.getElementById('wlwl_popup_canvas');
        ctx = cv.getContext('2d');

        for (let k = 0; k < slices; k++) {
            drawSlice1(deg, color[k]);
            deg += sliceDeg;
        }
        drawPoint1(wlwl_center_color);
        drawBorder1(wlwl_border_color, wlwl_dot_color, 4, 1, 0);

    }

    drawPopupIcon();

    function drawSlice1(deg, color) {
        ctx.beginPath();
        ctx.fillStyle = color;
        ctx.moveTo(center1, center1);
        ctx.arc(center1, center1, 32, deg2rad(deg), deg2rad(deg + sliceDeg));
        ctx.lineTo(center1, center1);
        ctx.fill();
        ctx.restore();
    }

    function drawPoint1(color) {
        ctx.save();
        ctx.beginPath();
        ctx.fillStyle = color;
        ctx.arc(center1, center1, 8, 0, 2 * Math.PI);
        ctx.fill();
        ctx.restore();
    }

    function drawBorder1(borderC, dotC, lineW, dotR, des) {
        ctx.beginPath();
        ctx.strokeStyle = borderC;
        ctx.lineWidth = lineW;
        ctx.arc(center1, center1, center1, 0, 2 * Math.PI);
        ctx.stroke();
        let x_val, y_val, deg;
        deg = sliceDeg / 2;
        let center2 = center1 - des;
        for (let i = 0; i < slices; i++) {
            ctx.beginPath();
            ctx.fillStyle = dotC;
            x_val = center1 + center2 * Math.cos(deg * Math.PI / 180);
            y_val = center1 - center2 * Math.sin(deg * Math.PI / 180);
            ctx.arc(x_val, y_val, dotR, 0, 2 * Math.PI);
            ctx.fill();
            deg += sliceDeg;
        }
        ctx.restore();
    }

});
