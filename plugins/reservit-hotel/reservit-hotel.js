/*!
 *      Reservit Hotel JS
 *      Version: 1.4
 *      By Reservit
 *
 *      Contact: http://www.reservit.com/hebergement
 *      Created: 2017
 *      Modified: 15/05/2019
 *
 *      Copyright (c) 2017, Reservit. All rights reserved.
 *
 *      Licensed under the GPLv2 license - https://www.gnu.org/licenses/gpl-2.0.html
 *
 */

console.log('reservit-hotel.js loaded');
var divWidth;
var divHeight;
//var clickUrl = rsvitHotelScript.reservitClickUrl;
//console.log(clickUrl);

function creerCookie(nom, valeur, jours) {
// Le nombre de jours est spécifié
    if (jours) {
        var date = new Date();
        // Converti le nombre de jour en millisecondes
        date.setTime(date.getTime() + (jours * 24 * 60 * 60 * 1000));
        var expire = "; expires=" + date.toGMTString();
    }
    // Aucune valeur de jours spécifiée
    else
        var expire = "";
    document.cookie = nom + "=" + valeur + expire + "; path=/";
}

function  getCookie(name) {
    if (document.cookie.length == 0)
        return null;

    var regSepCookie = new RegExp('(; )', 'g');
    var cookies = document.cookie.split(regSepCookie);

    for (var i = 0; i < cookies.length; i++) {
        var regInfo = new RegExp('=', 'g');
        var infos = cookies[i].split(regInfo);
        if (infos[0] == name) {
            return unescape(infos[1]);
        }
    }
    return null;
}

//Form window content
function fill_the_box($HotelId, $CustdId, paramsWidget) {
    /* BESTPRICE WIDGET CONFIGURATION - ProxIT v1.7
     Copyright (c) 2014-2016, Interface Technologies. All rights reserved. */
    var reservitHotelId = $HotelId; // Votre Hotelid chez Interface Technologies
    var reservitCustdId = $CustdId; // Votre Custid chez Interface Technologies
    
    
    
    // Core - DO NOT MODIFY
    function buildWidgetUrl() {
        var urlToCall = 'https://secure.reservit.com/front' + reservitHotelId + '/front.do?m=widget&mode=init&custid=' + reservitCustdId + '&hotelid=' + reservitHotelId;
        for (key in paramsWidget) {
            urlToCall += '&' + key + '=' + paramsWidget[key];
        }
        if (typeof _gaq != 'undefined' && typeof _gat != 'undefined') {
            var pageTracker = _gat._getTrackerByName();
            var linkerUrl = pageTracker._getLinkerUrl(urlToCall);
            urlToCall = linkerUrl;
        }
        return urlToCall;
    }
    function getWidgetInIframe(frameid) {
        document.getElementById(frameid).src = buildWidgetUrl();
    }

    getWidgetInIframe('ReservitBestPriceWidget');
}

//Showing the button
function show_the_btn($closedalready) {
    setTimeout(function () {
        //If the user use a mobile device or he already has closed the widget window
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || $closedalready === "yes") {
            //show the button

            jQuery('#rsvit_btn').fadeIn('swing');
        } else {
            //show the widget window
            jQuery('#ReservitBestPriceWidgetbox1').fadeIn('swing');
        }
    }, 2000);
}

//on the pop-up window button click
jQuery('#box_btn').click(function () {
    jQuery('#ReservitBestPriceWidgetbox1').fadeOut('swing');
    jQuery('#rsvit_btn').fadeIn('swing');

    //When the user close the widget window
    //We set the cookie to "yes" value for one year
    creerCookie("rsvit_box_closed", "yes", 365);
    //--NOT used-- We send "yes" value for the session variable RsvitWidgetboxClosed with ajax
    //jQuery.post(clickUrl+"/reservit-hotel-click.php", { RsvitWidgetboxClosed: "yes", });
});

//on the reservit button click	
jQuery('#rsvit_btn').click(function () {
    jQuery('#rsvit_btn').fadeOut('swing');
    jQuery('#ReservitBestPriceWidgetbox1').fadeIn('swing');
});

