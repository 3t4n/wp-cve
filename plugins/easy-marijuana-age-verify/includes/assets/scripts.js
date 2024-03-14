jQuery(document).ready(function ($) {

	// Check for global variable settings
	let $body = $('body'),
		$emavoverlay = $('#emav-overlay-wrap');

	// Set tabindex for better one-tab ADA compliance
	$('#emav_confirm_age').attr("tabindex", 1);
	$('#emav_not_confirm_age').attr("tabindex", 1);
	$('#emav_verify_remember').attr("tabindex", 1);

	if ( emav_ajax_object !== undefined && Cookies.get('emav-age-verified') === undefined ) {
		// Add ajax check option body class check, if exists
		if ( $body.hasClass('emav-ajax-check') == true ) {
		// set status
		$.get( emav_ajax_object.ajax_url, { action: 'emav_get_status' })
			.success( function(resp) {
				let emavstatus = resp;
				emav_eval_overlay_display(emavstatus);
            });
		} else {
			if ( $body.hasClass('emav-disabled') == true ) emavstatus = 'disabled';
			if ( $body.hasClass('emav-admin-only') == true ) emavstatus = 'admin-only';
			if ( $body.hasClass('emav-guests') == true ) emavstatus = 'guests';
			if ( $body.hasClass('emav-all') == true ) emavstatus = 'all';
			emav_eval_overlay_display(emavstatus);
		}
	} else if ( $body.hasClass('emav-admin-only') == true ) { // If cookie set and in testing mode, still display popup
		emavstatus = 'admin-only';
		emav_eval_overlay_display(emavstatus);
	}

	function emav_eval_overlay_display(emavstatus) {
		if ( $.inArray( emavstatus , [ 'all', 'guests', 'admin-only' ]) > -1 ){
			if ( emavstatus === 'all' ) {
				$body.css('position', 'fixed');
				$body.css('width', '100%');
				$emavoverlay.fadeIn();
			}
			else if ( $body.hasClass('logged-in') !== true && emavstatus === 'guests') {
				$body.css('position', 'fixed');
				$body.css('width', '100%');
				$emavoverlay.fadeIn();
			}
			else if ( $body.hasClass('logged-in') == true && $body.hasClass('administrator') == true && emavstatus === 'admin-only') {
				$body.css('position', 'fixed');
				$body.css('width', '100%');
				$emavoverlay.fadeIn();
			}
			else {
				$emavoverlay.fadeOut();
			}
		}

		$("#emav_confirm_age").on('click touch', function () {
			// Setting cookie options
			let emavoptions = {path: '/'};

			if ( $body.hasClass('logged-in') == true && $body.hasClass('administrator') == true && emavstatus == 'admin-only') {
				// skip setting cookie for testing mode
			} else {
				Cookies.set('emav-age-verified', 1, emavoptions);
			}

			$body.css('position', '');
			$body.css('width', '');
			$(".emav-error").fadeOut();
			$emavoverlay.fadeOut();
		});

		$("#emav_not_confirm_age").on('click touch', function () {
			$(".emav-error").fadeIn();
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
