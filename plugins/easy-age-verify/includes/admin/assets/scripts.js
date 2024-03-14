jQuery(document).ready(function ($) {
	var eavbtn = document.getElementById("evav-clear-cookie");
	if ( typeof eavbtn !== 'undefined' && eavbtn !== null ) {
		if ( Cookies.get('eav-age-verified') === undefined ) {
			eavbtn.innerHTML = "No Cookie Set";
			eavbtn.disabled = true;
		} else {
			eavbtn.innerHTML = "Clear Your Cookie";
			eavbtn.disabled = false;
		}
	}

    /* Testing the Logo Image onLoad */
    var csl_logo_url_val = $("#evav_logo_field_id").val();
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

	$('#_evav_adult_type_radio input').click(function () {
            if (confirm('Are you sure you want to change this? You will lose any text edits.')) {
            var evavatr = $("input[name='_evav_adult_type']:checked").val();
            if (evavatr == "adult") {
                $('#_evav_heading').val('Please verify you are 18 years or older to enter.');
                $('#_evav_disclaimer').val('WARNING ADULT CONTENT!\nThis website is intended for adults only and may contain content of an adult nature or age restricted, explicit material, which some viewers may find offensive. By entering you confirm that you are 18+ years and are not offended by viewing such material. If you are under the age of 18, if such material offends you or it is illegal to view in your location please exit now.');
                $('#_evav_agree_btn_text').val('I am 18 or older [Enter Site]');
                $('#_evav_disagree_btn_text').val('I am under 18');
            } else if (evavatr == "alcohol") {
                $('#_evav_heading').val('Are you of legal drinking age 21 or older?');
                $('#_evav_disclaimer').val('THE ALCOHOL PRODUCTS ON THIS WEBSITE ARE INTENDED FOR ADULTS ONLY.\nBy entering this website, you certify that you are of legal drinking age in the location in which you reside (age 21+ in the United States).');
                $('#_evav_agree_btn_text').val('Yes I am of legal age');
                $('#_evav_disagree_btn_text').val('No I am under age');
            } else if (evavatr == "vape") {
                $('#_evav_heading').val('Are you of legal smoking age?');
                $('#_evav_disclaimer').val('THE PRODUCTS ON THIS WEBSITE ARE INTENDED FOR ADULTS OF LEGAL SMOKING AGE.\nBy entering this website, you certify that you are of legal smoking age in the location in which you reside (age 18+, 19+ and 21+ in some areas).');
                $('#_evav_agree_btn_text').val('Yes I am of legal age');
                $('#_evav_disagree_btn_text').val('No I am under age');
            }
        } else {
    		return false;
		}
	});

    $('#evav_logo_button').click(function (e) {
        e.preventDefault();
        var csl_CustomSiteLogo_uploader = wp.media({
            title: 'Select or upload a logo',
            button: {text: 'Select Logo'},
            multiple: false
        }).on('select', function () {
            var attachment = csl_CustomSiteLogo_uploader.state().get('selection').first().toJSON();
            $('#evav_logo_field_id').val(attachment.url);
            $('.evav_logo_container').html("<IMG SRC='" + attachment.url + "'><BR>Save Changes to Set Logo");
        }).open();
    });

    $('#evav_logo_delete_button').click(function (e) {
        e.preventDefault();
        $('#evav_logo_field_id').val('');
        $('.evav_logo_container').html("Save Changes to Remove Logo");
    });

    $('#_evav_overlay_color').wpColorPicker();
    $('#_evav_agree_btn_bgcolor').wpColorPicker();
    $('#_evav_disAgree_btn_bgcolor').wpColorPicker();
    // show character count
    $.fn.maxLen = function (maxLen) {
        var elm = $(this);
        var textSelector = Math.random().toString(10).substr(2);
        if (maxLen == null)
            var maxLen = $(elm).attr('maxlength');

        $(elm).after('<div id="txt-length-left' + textSelector + '"></div>');
        elm.keypress(function (event) {
            var Length = elm.val().length;
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
    $body.find('#_evav_headline').maxLen();
    $body.find('#_evav_description').maxLen();
    $body.find('#_evav_heading').maxLen();
    $body.find('#_evav_disclaimer').maxLen();
    $body.find('#_evav_agree_btn_text').maxLen();
    $body.find('#_evav_disagree_btn_text').maxLen();
    $body.find('#_evav_disagree_error_text').maxLen();


    $('.evavpremhovertip').hover(function(e){ // Hover event
        var titleText = $(this).attr('title');
        $(this).data('tiptext', titleText).removeAttr('title');
        $('<p class="evavpremtooltip" style="display: none; z-index:999; position: absolute; padding: 10px; color: #555; background-color: #fff; border: 1px solid #777;	box-shadow: 0 1px 3px 1px rgba(0,0,0,0.5); border-radius: 3px;"></p>').text(titleText).appendTo('body').css('top', (e.pageY - 10) + 'px').css('left', (e.pageX + 20) + 'px').fadeIn('slow');
    }, function(){ // Hover off event
        $(this).attr('title', $(this).data('tiptext'));
        $('.evavpremtooltip').remove();
    }).mousemove(function(e){ // Mouse move event
        $('.evavpremtooltip').css('top', (e.pageY - 10) + 'px').css('left', (e.pageX + 20) + 'px');
    });

    $('.evavoptionshovertip').hover(function(e){ // Hover event
        var titleText = $(this).attr('title');
        $(this).data('tiptext', titleText).removeAttr('title');
        $('<p class="evavoptiontooltip"></p>').text(titleText).appendTo('body').css('top', ($(this).offset().top  - 25) + 'px').css('left', ($(this).offset().left + 5 ) + 'px').fadeIn('slow');
    }, function(){ // Hover off event
        $(this).attr('title', $(this).data('tiptext'));
        $('.evavoptiontooltip').remove();
    });

});

function evav_clear_cookie() {
	var eavbtn = document.getElementById("evav-clear-cookie");
	console.debug('Clearing cookie!');
	document.cookie='eav-age-verified=;Path=/;Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
	console.debug('Cleared!');
	eavbtn.innerHTML = "Cookie Cleared";
	eavbtn.disabled = true;
	setTimeout(function(){
		eavbtn.innerHTML = "No Cookie Set";
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
