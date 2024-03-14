(function ($) {
    'use strict';

    $(window).load(function () {
        Banner();
    });

    $(window).ready(function () {
        var $banner_enabled = dima_ta_banner_name.banner_enabled;
        var $float_btn_enabled = dima_ta_banner_name.float_btn_enabled;

        if ($banner_enabled === '1') {
            markup_banner();
        }

        if ($float_btn_enabled === '1') {
            markup_float_button();
        }
    });

    function markup_float_button() {
        var $float_btn_url = dima_ta_banner_name.float_btn_url;
        var $img_url = dima_ta_banner_name.img_url;
        var $float_btn_txt = dima_ta_banner_name.float_btn_txt;
        var $btn_target = dima_ta_banner_name.btn_float_target;
        var $img = '';
        if ($img_url !== '') {
            $img = ' <span class="dima-float-button-envato">' +
                ' <img src="' + $img_url + '" alt="">' +
                '</span>';
        }
        var _target = "";
        if ($btn_target === '1') {
            _target = "target=\"_blank\""
        }
        $('<a href="' + $float_btn_url + '" ' + _target + '  id="dima-btn-fixed-button" class="dima-buy-button active" title="Purchase native">' +
            '<span class="dima-buy-button-content-wrapper dima-buy-button-top">' +
            '' + $img +
            '<span class="dima-float-buy-text"><span>' + $float_btn_txt + '</span></span>' +
            '</span>' +
            '</a>'
        ).prependTo('body');
    }

    function markup_banner() {
        var $dataCacheKey = dima_ta_banner_name.dataCacheKey;
        var $class = dima_ta_banner_name.class;
        var $banner_txt = dima_ta_banner_name.banner_txt;
        var $close = '';
        var $btn = '';
        var $banner_on_mobile = dima_ta_banner_name.banner_on_mobile;
        var $close_active = dima_ta_banner_name.close_active;
        var $btn_active = dima_ta_banner_name.btn_active;
        var $btn_url = dima_ta_banner_name.btn_url;
        var $btn_txt = dima_ta_banner_name.btn_txt;
        var $mobile_txt = dima_ta_banner_name.mobile_txt;
        var $mobile_url = dima_ta_banner_name.mobile_url;

        if ($close_active === '1') {
            $close = '<a href="#" class="js-banner__dismiss banner__dismiss">' +
                '<svg class="dismiss_icon" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">' +
                '<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>' +
                '</svg>' +
                ' </a>';
        }

        if ($btn_active === '1') {
            var _target = "";
            var $btn_target = dima_ta_banner_name.btn_target;

            if ($btn_target === '1') {
                _target = "target=\"_blank\""
            }
            $btn = '<div class="headerstrip-cta-container">' +
                ' <a class="js-banner__link headerstrip-cta" ' + _target + ' href="' + $btn_url + '">' + $btn_txt + '</a>' +
                '</div>';
        }

        if ($banner_on_mobile === '1') {
            $banner_on_mobile = '<div class="headerstrip-canvas is-hidden-desktop">' +
                '<div class="headerstrip-content">' +
                '<div class="headerstrip-text">' +
                '<a class="headerstrip-cta-mobile" href="' + $mobile_url + '">' + $mobile_txt + '</a>' +
                '</div>' +
                '<a href="#" class="js-banner__dismiss banner__dismiss">' +
                '<svg class="dismiss_icon" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">' +
                '<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>' +
                '</svg>' +
                '</a>' +
                '</div>' +
                '</div>';
        } else {
            $banner_on_mobile = '';
        }

        $('<div id="take-action-banner" class="' + $class + '" data-view="banner" data-cache-key="' + $dataCacheKey + '">' +
            '<div class="headerstrip">' +
            '<div class="headerstrip-content-background"></div>' +
            '<div class="headerstrip-canvas is-hidden-tablet-and-below">' +
            '<div class="headerstrip-content">' +
            '<div class="headerstrip-text">' + $banner_txt + '</div>' +
            $btn +
            $close +
            '</div>' +
            '</div>' +
            $banner_on_mobile +
            '</div>' +
            '</div>' +
            '</div>').prependTo('body');
    }

    function Banner() {
        init();

        function init() {

            var $banner = $('#take-action-banner');
            if ($banner.length) {
                var $close = $banner.find(".js-banner__dismiss");
                var bannerData = $banner.data();
                getLocalStorage(bannerData.cacheKey);
                $close.on("click", handleClose);
            }

            function handleClose() {
                $banner.slideUp(250);
                setLocalStorage(bannerData.cacheKey);
            }

            function setLocalStorage(t) {
                return window.localStorage.setItem(t, "hidden");
            }

            function getLocalStorage(e) {
                if (!window.localStorage.getItem(e))
                    $banner.slideDown(250);
            }

        }
    }
})(jQuery);
