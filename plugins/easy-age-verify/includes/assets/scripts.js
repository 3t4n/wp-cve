jQuery(document).ready(function ($) {

    // Check for global variable settings
    let $body = $('body'),
        $evavoverlay = $('#evav-overlay-wrap');

	// Set tabindex for better one-tab ADA compliance
	$('#evav_confirm_age').attr("tabindex", 1);
	$('#evav_not_confirm_age').attr("tabindex", 1);
	$('#evav_verify_remember').attr("tabindex", 1);

	if ( evav_ajax_object !== undefined && Cookies.get('eav-age-verified') === undefined ) {

		// Add ajax check option body class check, if exists
		if ( $body.hasClass('evav-ajax-check') == true ) {
        // set evav_status
        $.get( evav_ajax_object.ajax_url, { action: 'evav_get_status' })
            .success( function(resp) {
				let evav_status = resp;
				evav_eval_overlay_display(evav_status);
            });
		} else {
			if ( $body.hasClass('evav-disabled') == true ) evav_status = 'disabled';
			if ( $body.hasClass('evav-admin-only') == true ) evav_status = 'admin-only';
			if ( $body.hasClass('evav-guests') == true ) evav_status = 'guests';
			if ( $body.hasClass('evav-all') == true ) evav_status = 'all';
			evav_eval_overlay_display(evav_status);
		}
	} else if ( $body.hasClass('evav-admin-only') == true ) { // If cookie set and in testing mode, still display popup
		evav_status = 'admin-only';
		evav_eval_overlay_display(evav_status);
	}

	function evav_eval_overlay_display(evav_status) {
		if ( $.inArray( evav_status , [ 'all', 'guests', 'admin-only' ]) > -1 ){
			if ( evav_status === 'all' ) {
				$body.css('position', 'fixed');
				$body.css('width', '100%');
				$evavoverlay.fadeIn();
			}
			else if ( $body.hasClass('logged-in') !== true && evav_status === 'guests') {
				$body.css('position', 'fixed');
				$body.css('width', '100%');
				$evavoverlay.fadeIn();
			}
			else if ( $body.hasClass('logged-in') == true && $body.hasClass('administrator') == true && evav_status === 'admin-only') {
				$body.css('position', 'fixed');
				$body.css('width', '100%');
				$evavoverlay.fadeIn();
			}
			else {
				$evavoverlay.fadeOut();
			}
		}

    	$("#evav_confirm_age").on('click touch', function () {
			// Setting cookie options
			let options = {path: '/'};

			if ( $body.hasClass('logged-in') == true && $body.hasClass('administrator') == true && evav_status == 'admin-only') {
				// skip setting cookie
			} else {
				Cookies.set('eav-age-verified', 1, options);
			}

			$body.css('position', '');
			$body.css('width', '');
			$evavoverlay.fadeOut();
    	});

    	$("#evav_not_confirm_age").on('click touch', function () {
        	$(".evav-error").fadeIn();
    	});
	}
});
// Embed jQuery js-cookies file to remove remote call
/*!
 * JavaScript Cookie v2.2.1
 * Minified by jsDelivr using Terser v3.14.1.
 * Original file: /npm/js-cookie@2.2.1/src/js.cookie.js
 */
!function(e){var n;if("function"==typeof define&&define.amd&&(define(e),n=!0),"object"==typeof exports&&(module.exports=e(),n=!0),!n){var t=window.Cookies,o=window.Cookies=e();o.noConflict=function(){return window.Cookies=t,o}}}(function(){function e(){for(var e=0,n={};e<arguments.length;e++){var t=arguments[e];for(var o in t)n[o]=t[o]}return n}function n(e){return e.replace(/(%[0-9A-Z]{2})+/g,decodeURIComponent)}return function t(o){function r(){}function i(n,t,i){if("undefined"!=typeof document){"number"==typeof(i=e({path:"/"},r.defaults,i)).expires&&(i.expires=new Date(1*new Date+864e5*i.expires)),i.expires=i.expires?i.expires.toUTCString():"";try{var c=JSON.stringify(t);/^[\{\[]/.test(c)&&(t=c)}catch(e){}t=o.write?o.write(t,n):encodeURIComponent(String(t)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g,decodeURIComponent),n=encodeURIComponent(String(n)).replace(/%(23|24|26|2B|5E|60|7C)/g,decodeURIComponent).replace(/[\(\)]/g,escape);var f="";for(var u in i)i[u]&&(f+="; "+u,!0!==i[u]&&(f+="="+i[u].split(";")[0]));return document.cookie=n+"="+t+f}}function c(e,t){if("undefined"!=typeof document){for(var r={},i=document.cookie?document.cookie.split("; "):[],c=0;c<i.length;c++){var f=i[c].split("="),u=f.slice(1).join("=");t||'"'!==u.charAt(0)||(u=u.slice(1,-1));try{var a=n(f[0]);if(u=(o.read||o)(u,a)||n(u),t)try{u=JSON.parse(u)}catch(e){}if(r[a]=u,e===a)break}catch(e){}}return e?r[e]:r}}return r.set=i,r.get=function(e){return c(e,!1)},r.getJSON=function(e){return c(e,!0)},r.remove=function(n,t){i(n,"",e(t,{expires:-1}))},r.defaults={},r.withConverter=t,r}(function(){})});
