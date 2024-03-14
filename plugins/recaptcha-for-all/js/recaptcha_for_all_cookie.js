jQuery(document).ready(function ($) {

    // console.log('works !!!');

    var getTouchSupport = function () {
        var maxTouchPoints = 0
        var touchEvent
        if (typeof navigator.maxTouchPoints !== 'undefined') {
            maxTouchPoints = navigator.maxTouchPoints
        } else if (typeof navigator.msMaxTouchPoints !== 'undefined') {
            maxTouchPoints = navigator.msMaxTouchPoints
        }
        try {
            document.createEvent('TouchEvent')
            touchEvent = true
        } catch (_) {
            touchEvent = false
        }
        var touchStart = 'ontouchstart' in window
        return [maxTouchPoints, touchEvent, touchStart]
    }

    var touchSupportKey = getTouchSupport();


    var userAgent = navigator.userAgent.toLowerCase();
    var platform = navigator.platform.toLowerCase();
    var os;
    if (userAgent.indexOf('windows phone') >= 0) {
        os = 'Windows Phone'
    } else if (userAgent.indexOf('windows') >= 0 || userAgent.indexOf('win16') >= 0 || userAgent.indexOf('win32') >= 0 || userAgent.indexOf('win64') >= 0 || userAgent.indexOf('win95') >= 0 || userAgent.indexOf('win98') >= 0 || userAgent.indexOf('winnt') >= 0 || userAgent.indexOf('wow64') >= 0) {
        os = 'Windows'
    } else if (userAgent.indexOf('android') >= 0) {
        os = 'Android'
    } else if (userAgent.indexOf('linux') >= 0 || userAgent.indexOf('cros') >= 0 || userAgent.indexOf('x11') >= 0) {
        os = 'Linux'
    } else if (userAgent.indexOf('iphone') >= 0 || userAgent.indexOf('ipad') >= 0 || userAgent.indexOf('ipod') >= 0 || userAgent.indexOf('crios') >= 0 || userAgent.indexOf('fxios') >= 0) {
        os = 'iOS'
    } else if (userAgent.indexOf('macintosh') >= 0 || userAgent.indexOf('mac_powerpc)') >= 0) {
        os = 'Mac'
    } else {
        os = 'Other'
    }
    try {
        if (window.Intl && window.Intl.DateTimeFormat) {
            var x = new window.Intl.DateTimeFormat().resolvedOptions().timeZone;
        }
        else {
            var x = 'undef';
        }
    }
    catch (err) {
        //console.log(err.message);
        var x = 'error';
    }
    try {
        var d = new Date();
        var t = d.getTimezoneOffset();
    }
    catch (err) {
        // console.log(err.message);
        var t = 'error';
    }


    var loseWebglContext = function (context) {
        var loseContextExtension = context.getExtension('WEBGL_lose_context')
        if (loseContextExtension != null) {
            loseContextExtension.loseContext()
        }
    }

    var getWebglVendorAndRenderer = function () {
        /* This a subset of the WebGL fingerprint with a lot of entropy, while being reasonably browser-independent */
        try {
            var glContext = getWebglCanvas()
            var extensionDebugRendererInfo = glContext.getExtension('WEBGL_debug_renderer_info')
            var params = glContext.getParameter(extensionDebugRendererInfo.UNMASKED_VENDOR_WEBGL) + '~' + glContext.getParameter(extensionDebugRendererInfo.UNMASKED_RENDERER_WEBGL)
            loseWebglContext(glContext)
            return params;
        } catch (e) {
            return null;
        }
    }

    var getWebglCanvas = function () {
        var canvas = document.createElement('canvas')
        var gl = null
        try {
            gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl')
        } catch (e) { /* squelch */ }
        if (!gl) { gl = null }
        return gl
    }


    var webglVendorAndRendererKey = '?';
    var elem = document.createElement('canvas')
    // webglVendorAndRendererKey = getWebglVendorAndRenderer();
    try {
        var w = (!!(elem.getContext && elem.getContext('2d')));
        webglVendorAndRendererKey = getWebglVendorAndRenderer();
    }
    catch (err) {
        webglVendorAndRendererKey = 'Undef';
    }

    x = jQuery.trim(x);
    t = jQuery.trim(t);
    p = jQuery.trim(platform);
    o = jQuery.trim(os);
    ts = jQuery.trim(touchSupportKey);
    v = webglVendorAndRendererKey;

    var $fingerprint = '';
    if (x.lenght != 0) {
        $fingerprint = $fingerprint + '#' + x;
    }
    if (t.lenght != 0) {
        $fingerprint = $fingerprint + '#' + t;
    }
    if (p.lenght != 0) {
        $fingerprint = $fingerprint + '#' + p;
    }
    if (o.lenght != 0) {
        $fingerprint = $fingerprint + '#' + o;
    }
    if (ts.lenght != 0) {
        $fingerprint = $fingerprint + '#' + ts;
    }

    if (v !== null) {
        if (v.lenght != 0) {
            $fingerprint = $fingerprint + '#' + v;
        }
    }

    eraseCookie('recaptcha_cookie');
    if (readCookie('recaptcha_cookie') == null) {
        createCookie('recaptcha_cookie', $fingerprint, 30);
    }


    function createCookie(name, value, days) {
        var expires;
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        } else {
            expires = "";
        }
        document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";

        // console.log('Linha 155');
    }
    
    function readCookie(name) {
        var nameEQ = escape(name) + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return unescape(c.substring(nameEQ.length, c.length));
        }
        return null;
    }
    function eraseCookie(name) {
        createCookie(name, "", -1);
    }



});
