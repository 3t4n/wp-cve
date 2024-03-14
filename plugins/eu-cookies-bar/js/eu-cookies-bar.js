jQuery(document).ready(function ($) {
    'use strict';
    var cookies_bar_on_close = eu_cookies_bar_params.cookies_bar_on_close,
        cookies_bar_on_scroll = eu_cookies_bar_params.cookies_bar_on_scroll,
        cookies_bar_on_page_redirect = eu_cookies_bar_params.cookies_bar_on_page_redirect,
        strictly_necessary = eu_cookies_bar_params.strictly_necessary,
        strictly_necessary_family = eu_cookies_bar_params.strictly_necessary_family,
        expire_time = eu_cookies_bar_params.expire_time,
        cookiePath = eu_cookies_bar_params.cookiepath,
        block_until_accept = eu_cookies_bar_params.block_until_accept,
        user_cookies_settings_enable = eu_cookies_bar_params.user_cookies_settings_enable,
        blockCookies;

    function setCookie(cname, cvalue, expire, path, domain) {
        domain = domain || "";
        var d = new Date(expire * 1000);
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=" + path + ";domain=" + domain;
    }

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    handleExplicitBehaviors();

    function handleExplicitBehaviors() {
        $('.eu-cookies-bar-cookies-bar-button-close').on('click', function () {
            if (!getCookie('eu_cookies_bar')) {
                switch (cookies_bar_on_close) {
                    case 'yes':
                        handleCookies();
                        acceptCookiesHandle();
                        break;
                    case "no":
                        declineCookiesHandle();
                        break;
                    default:
                }
            }
            $('.eu-cookies-bar-cookies-bar-wrap').fadeOut(500);

        });
        $(window).on('mousewheel', function () {
            if (!getCookie('eu_cookies_bar')) {
                switch (cookies_bar_on_scroll) {
                    case 'yes':
                        handleCookies();
                        acceptCookiesHandle();
                        $('.eu-cookies-bar-cookies-bar-wrap').fadeOut(500);
                        break;
                    case 'no':
                        declineCookiesHandle();
                        $('.eu-cookies-bar-cookies-bar-wrap').fadeOut(500);
                        break;
                    default:
                }
            }
        });
        $(window).on('unload', function () {
            if (!getCookie('eu_cookies_bar')) {
                switch (cookies_bar_on_page_redirect) {
                    case 'yes':
                        handleCookies();
                        acceptCookiesHandle();
                        $('.eu-cookies-bar-cookies-bar-wrap').fadeOut(500);
                        break;
                    case 'no':
                        declineCookiesHandle();
                        $('.eu-cookies-bar-cookies-bar-wrap').fadeOut(500);
                        break;
                    default:
                }
            }
        })


    }

    function cookiesBarPopup() {
        if (!getCookie('eu_cookies_bar')) {
            $('.eu-cookies-bar-cookies-bar-wrap').fadeIn(500);
            if (block_until_accept) {
                var cookies = document.cookie.split(";");
                var keyFlag;
                var j, k;
                for (j = 0; j < cookies.length; j++) {
                    var cookie_name = cookies[j].split("=")[0].trim();
                    var cookie_value = cookies[j].split("=")[1];
                    keyFlag = false;
                    for (k = 0; k < strictly_necessary_family.length; k++) {
                        if (cookie_name.match('^' + strictly_necessary_family[k] + '(|.+?)')) {
                            keyFlag = true;
                            break;
                        }
                    }
                    if (!keyFlag && strictly_necessary.indexOf(cookie_name) === -1 && cookie_name !== 'eu_cookies_bar' && cookie_name !== 'eu_cookies_bar_block') {
                        setCookie(cookie_name, cookie_value, -1, '/', window.location.hostname);
                        setCookie(cookie_name, cookie_value, -1, cookiePath, window.location.hostname);
                    }
                }
            }
        } else if (getCookie('eu_cookies_bar_block') !== '') {
            var myBlockCookies = getCookie('eu_cookies_bar_block').split(',');
            var blockCookieName;
            if (myBlockCookies.length > 0) {
                // deleteSpecificCookies(myBlockCookies);
                for (var i in myBlockCookies) {
                    blockCookieName = myBlockCookies[i].trim();
                    if (getCookie(blockCookieName) && strictly_necessary.indexOf(blockCookieName) === -1) {
                        setCookie(blockCookieName, '', -1, '/', window.location.hostname);
                        setCookie(blockCookieName, '', -1, cookiePath, window.location.hostname);

                    }
                }
            }

        }
    }

    function saveSettings() {
        $('.eu-cookies-bar-cookies-bar-settings-save-button').on('click', function () {
            handleCookies();
            acceptCookiesHandle();
            $('.eu-cookies-bar-cookies-bar-settings-wrap').fadeOut(500);
            vi_ecb_enable_scroll();
        })
    }

    saveSettings();

    function handleCookies() {
        blockCookies = [];
        $('.eu-cookies-bar-cookie-checkbox').map(function () {
            if ($(this).prop('checked') === false && $(this).val() !== 'eu_cookies_bar' && $(this).val() !== 'eu_cookies_bar_block') {
                blockCookies.push($(this).val());
            }
        });
        if (blockCookies.length > 0) {
            for (var index in blockCookies) {
                if (strictly_necessary.indexOf(blockCookies[index]) > -1) {
                    blockCookies.splice(index, 1);
                }
            }
        }
    }

    function declineCookiesHandle() {
        blockCookies = [];
        $('.eu-cookies-bar-cookie-checkbox').map(function () {
            if ($(this).val() !== 'eu_cookies_bar' && $(this).val() !== 'eu_cookies_bar_block') {
                blockCookies.push($(this).val());
            }
        });
        if (blockCookies.length > 0) {
            for (var index in blockCookies) {
                if (strictly_necessary.indexOf(blockCookies[index]) > -1) {
                    blockCookies.splice(index, 1);
                }
            }
        }
        setCookie('eu_cookies_bar', expire_time, expire_time, cookiePath);
        setCookie('eu_cookies_bar_block', blockCookies.toString(), expire_time, cookiePath);
        setCookie('eu_cookies_bar_decline', 1, expire_time, cookiePath);
    }

    function acceptCookiesHandle() {
        var expire;
        if (!getCookie('eu_cookies_bar')) {
            expire = expire_time;
            setCookie('eu_cookies_bar', expire, expire, cookiePath);
            $('.eu-cookies-bar-cookies-bar-wrap').fadeOut(500);
            setCookie('eu_cookies_bar_block', '', expire, cookiePath);
        } else {
            expire = parseInt(getCookie('eu_cookies_bar'));
            setCookie('eu_cookies_bar_block', blockCookies.toString(), expire, cookiePath);
        }
        if (getCookie('eu_cookies_bar_decline')) {
            setCookie('eu_cookies_bar_decline', '', -1, cookiePath);
        }
    }

    function handleButton() {
        $('.eu-cookies-bar-cookies-bar-button-accept').on('click', function () {
            handleCookies();
            acceptCookiesHandle();
            $('.eu-cookies-bar-cookies-bar-wrap').fadeOut(500);
        });

    }

    function overLay() {
        $('.eu-cookies-bar-cookies-bar-settings-overlay').unbind();
        $('.eu-cookies-bar-cookies-bar-settings-overlay').on('click', function () {
            $('.eu-cookies-bar-cookies-bar-settings-wrap').fadeOut(500);
            vi_ecb_enable_scroll();
        })
    }

    function showSettings() {
        if (user_cookies_settings_enable) {
            $('.eu-cookies-bar-cookies-bar-button-settings').unbind();
            $('.eu-cookies-bar-cookies-bar-button-settings').on('click', function () {
                $('.eu-cookies-bar-cookies-bar-settings-wrap').fadeIn(500);
                vi_ecb_disable_scroll();
            })
        }
    }

    cookiesBarPopup();
    handleButton();
    showSettings();
    overLay();

    function userCookiesSettingsTab() {
        $('.eu-cookies-bar-cookies-bar-settings-nav div').on('click', function () {
            $('.eu-cookies-bar-cookies-bar-settings-nav div').toggleClass('eu-cookies-bar-cookies-bar-settings-nav-active');
            $('.eu-cookies-bar-cookies-bar-settings-content-child').toggleClass('eu-cookies-bar-cookies-bar-settings-content-child-inactive');
        })
    }

    userCookiesSettingsTab();
    $(document).on('keyup', function (e) {
        if (e.keyCode === 27) {
            $('.eu-cookies-bar-cookies-bar-settings-overlay').click();
        }
    });
    $('.eu-cookies-bar-cookies-bar-settings-close').on('click', function () {
        $('.eu-cookies-bar-cookies-bar-settings-overlay').click();
    });
    $('.eu-cookies-bar-cookies-bar-button-decline').on('click', function () {
        declineCookiesHandle();
        $('.eu-cookies-bar-cookies-bar-wrap').fadeOut(500);
    });
    function vi_ecb_enable_scroll() {
        let scrollTop = parseInt($('html').css('top'));
        $('html').removeClass('vi_ecb-noscroll');
        $('html,body').scrollTop(-scrollTop);
    }

    function vi_ecb_disable_scroll() {
        if ($(document).height() > $(window).height()) {
            let scrollTop = ($('html').scrollTop()) ? $('html').scrollTop() : $('body').scrollTop(); // Works for Chrome, Firefox, IE...
            $('html').addClass('vi_ecb-noscroll').css('top', -scrollTop);
        }
    }
});