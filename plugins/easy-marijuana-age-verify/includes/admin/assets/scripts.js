jQuery(document).ready(function ($) {
	var emavbtn = document.getElementById("emav-clear-cookie");
	if ( typeof emavbtn !== 'undefined' && emavbtn !== null ) {
		if ( Cookies.get('emav-age-verified') === undefined ) {
			emavbtn.innerHTML = "No Cookie Set";
			emavbtn.disabled = true;
		} else {
			emavbtn.innerHTML = "Clear Your Cookie";
			emavbtn.disabled = false;
		}
	}

	/* Check custom text option selection, show/hide fields */
	if ( $('input:radio[name=_emav_user_age_verify_option]:checked').val() == '6' ) {
		$('h2:contains("Free-Form Custom Text")').fadeIn();
		$('input[name=_emav_custom_age_text]').closest('tr').fadeIn();
		$('input[name=_emav_custom_agreebutton_text]').closest('tr').fadeIn();
		$('input[name=_emav_custom_disagreebutton_text]').closest('tr').fadeIn();
		$('input[name=_emav_disagree_error_text]').closest('tr').fadeIn();
	} else {
		$('h2:contains("Free-Form Custom Text")').hide();
		$('input[name=_emav_custom_age_text]').closest('tr').hide();
		$('input[name=_emav_custom_agreebutton_text]').closest('tr').hide();
		$('input[name=_emav_custom_disagreebutton_text]').closest('tr').hide();
		$('input[name=_emav_disagree_error_text]').closest('tr').hide();
	}
	$('.emav-age-header-option input').on('click', function() {
		if ($(this).val() == '6') {
			$('h2:contains("Free-Form Custom Text")').fadeIn();
			$('input[name=_emav_custom_age_text]').closest('tr').fadeIn();
			$('input[name=_emav_custom_agreebutton_text]').closest('tr').fadeIn();
			$('input[name=_emav_custom_disagreebutton_text]').closest('tr').fadeIn();
			$('input[name=_emav_disagree_error_text]').closest('tr').fadeIn();
		} else {
			$('h2:contains("Free-Form Custom Text")').fadeOut();
			$('input[name=_emav_custom_age_text]').closest('tr').fadeOut();
			$('input[name=_emav_custom_agreebutton_text]').closest('tr').fadeOut();
			$('input[name=_emav_custom_disagreebutton_text]').closest('tr').fadeOut();
			$('input[name=_emav_disagree_error_text]').closest('tr').fadeOut();
		}
	});

	/* Testing the Logo Image onLoad */
	var csl_logo_url_val = $("#emav_logo_field_id").val();
	csl_logo_testImage(csl_logo_url_val);

	function csl_logo_testImage(URL) {
		if (URL != "Select Logo" && URL) {
			var tester = new Image();
			tester.onerror = csl_logo_imageNotFound;
			tester.src = URL;
		}
	}

	function csl_logo_imageNotFound() {
		alert("That image was not found.");
	}

	$('#emav_logo_button').click(function (e) {
		e.preventDefault();
		var csl_CustomSiteLogo_uploader = wp.media({
			title: 'Select or upload a logo',
			button: {text: 'Select Logo'},
			multiple: false
		}).on('select', function () {
			var attachment = csl_CustomSiteLogo_uploader.state().get('selection').first().toJSON();
			$('#emav_logo_field_id').val(attachment.url);
			$('.emav_logo_container').html("<IMG SRC='" + attachment.url + "'><BR>Save Changes to Set Logo");
		}).open();
	});

	$('#emav_logo_delete_button').click(function (e) {
		e.preventDefault();
		$('#emav_logo_field_id').val('');
		$('.emav_logo_container').html("Save Changes to Remove Logo");
	});

	$('#_emav_overlay_color').wpColorPicker();
	$('#_emav_agree_btn_bgcolor').wpColorPicker();
	$('#_emav_disAgree_btn_bgcolor').wpColorPicker();
	// show character count
	$.fn.maxLen = function (maxLen) {
		var elm = $(this);
		var textSelector = Math.random().toString(10).substr(2);
		if (maxLen == null)
			var maxLen = $(elm).attr('maxlength');

		$(elm).after('<div id="txt-length-left' + textSelector + '"></div>');
		elm.keypress(function (event) {
			var Length = elm.val().length + 1;
			var AmountLeft = maxLen - Length;
			$('#txt-length-left' + textSelector).html(AmountLeft + " Characters left");
			if (Length - 1 >= maxLen) {
				$('#txt-length-left' + textSelector).html("0 Characters left");
				if (event.which != 8) {
					return false;
				}
			}
		});
	};

	// Disclaimer text box
	var $body = $('body');
	$body.find('#_emav_custom_age_text').maxLen();
	$body.find('#_emav_custom_agreebutton_text').maxLen();
	$body.find('#_emav_custom_disagreebutton_text').maxLen();
	$body.find('#_emav_disagree_error_text').maxLen();
	$body.find('#_emav_headline').maxLen();
	$body.find('#_emav_description').maxLen();
	$body.find('#_emav_heading').maxLen();
	$body.find('#_emav_disclaimer').maxLen();

	$('.emavpremhovertip').hover(function(e){ // Hover event
		var titleText = $(this).attr('title');
		$(this).data('tiptext', titleText).removeAttr('title');
		$('<p class="emavpremtooltip" style="display: none; z-index:999; position: absolute; padding: 10px; color: #555; background-color: #fff; border: 1px solid #777;	box-shadow: 0 1px 3px 1px rgba(0,0,0,0.5); border-radius: 3px;"></p>').text(titleText).appendTo('body').css('top', (e.pageY - 10) + 'px').css('left', (e.pageX + 20) + 'px').fadeIn('slow');
	}, function(){ // Hover off event
		$(this).attr('title', $(this).data('tiptext'));
		$('.emavpremtooltip').remove();
	}).mousemove(function(e){ // Mouse move event
		$('.emavpremtooltip').css('top', (e.pageY - 10) + 'px').css('left', (e.pageX + 20) + 'px');
	});

	$('.emavoptionshovertip').hover(function(e){ // Hover event
		var titleText = $(this).attr('title');
		$(this).data('tiptext', titleText).removeAttr('title');
		$('<p class="emavoptiontooltip"></p>').text(titleText).appendTo('body').css('top', ($(this).offset().top  - 25) + 'px').css('left', ($(this).offset().left + 5 ) + 'px').fadeIn('slow');
	}, function(){ // Hover off event
		$(this).attr('title', $(this).data('tiptext'));
		$('.emavoptiontooltip').remove();
	});


});

function emav_clear_cookie() {
	var emavbtn = document.getElementById("emav-clear-cookie");
	console.debug('Clearing cookie!');
	document.cookie='emav-age-verified=;Path=/;Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
	console.debug('Cleared!');
	emavbtn.innerHTML = "Cookie Cleared";
	emavbtn.disabled = true;
	setTimeout(function(){
		emavbtn.innerHTML = "No Cookie Set";
	}, 4000);
	return false;
}

// Embed jQuery js-cookies file to remove remote call
/*!
 * JavaScript Cookie v2.2.1
 * Minified by jsDelivr using Terser v3.14.1.
 * Original file: /npm/js-cookie@2.2.1/src/js.cookie.js
 */
!function(e){var n;if("function"==typeof define&&define.amd&&(define(e),n=!0),"object"==typeof exports&&(module.exports=e(),n=!0),!n){var t=window.Cookies,o=window.Cookies=e();o.noConflict=function(){return window.Cookies=t,o}}}(function(){function e(){for(var e=0,n={};e<arguments.length;e++){var t=arguments[e];for(var o in t)n[o]=t[o]}return n}function n(e){return e.replace(/(%[0-9A-Z]{2})+/g,decodeURIComponent)}return function t(o){function r(){}function i(n,t,i){if("undefined"!=typeof document){"number"==typeof(i=e({path:"/"},r.defaults,i)).expires&&(i.expires=new Date(1*new Date+864e5*i.expires)),i.expires=i.expires?i.expires.toUTCString():"";try{var c=JSON.stringify(t);/^[\{\[]/.test(c)&&(t=c)}catch(e){}t=o.write?o.write(t,n):encodeURIComponent(String(t)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g,decodeURIComponent),n=encodeURIComponent(String(n)).replace(/%(23|24|26|2B|5E|60|7C)/g,decodeURIComponent).replace(/[\(\)]/g,escape);var f="";for(var u in i)i[u]&&(f+="; "+u,!0!==i[u]&&(f+="="+i[u].split(";")[0]));return document.cookie=n+"="+t+f}}function c(e,t){if("undefined"!=typeof document){for(var r={},i=document.cookie?document.cookie.split("; "):[],c=0;c<i.length;c++){var f=i[c].split("="),u=f.slice(1).join("=");t||'"'!==u.charAt(0)||(u=u.slice(1,-1));try{var a=n(f[0]);if(u=(o.read||o)(u,a)||n(u),t)try{u=JSON.parse(u)}catch(e){}if(r[a]=u,e===a)break}catch(e){}}return e?r[e]:r}}return r.set=i,r.get=function(e){return c(e,!1)},r.getJSON=function(e){return c(e,!0)},r.remove=function(n,t){i(n,"",e(t,{expires:-1}))},r.defaults={},r.withConverter=t,r}(function(){})});
