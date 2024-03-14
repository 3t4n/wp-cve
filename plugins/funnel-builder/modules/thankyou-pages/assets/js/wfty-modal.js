/*
 * Thickbox 3.1 - One Box To Rule Them All.
 * By Cody Lindley (http://www.codylindley.com)
 * Copyright (c) 2007 cody lindley
 * Licensed under the MIT License: http://www.opensource.org/licenses/mit-license.php
*/

if (typeof wfty_modal_pathToImage != 'string') {
    var wfty_modal_pathToImage = (typeof wftymodal10n !== "undefined") ? wftymodal10n.loadingAnimation : "";
}

/*!!!!!!!!!!!!!!!!! edit below this line at your own risk !!!!!!!!!!!!!!!!!!!!!!!*/

//on page load call wfty_modal_init
jQuery(document).ready(function () {
    wfty_modal_init('a.wftymodal, area.wftymodal, input.wftymodal');//pass where to apply wftymodal
    imgLoader = new Image();// preload image
    imgLoader.src = wfty_modal_pathToImage;
});

/*
 * Add wftymodal to href & area elements that have a class of .wftymodal.
 * Remove the loading indicator when content in an iframe has loaded.
 */
function wfty_modal_init(domChunk) {
    jQuery('body')
        .on('click', domChunk, wfty_modal_click)
        .on('wftymodal:iframe:loaded', function () {
            jQuery('#WFTY_MB_window').removeClass('wftymodal-loading');
        });
}

function wfty_modal_click() {
    var t = this.title || this.name || null;
    var a = this.href || this.alt;
    var g = this.rel || false;
    wfty_modal_show(t, a, g);
    this.blur();
    return false;
}

function wfty_show_tb(title, id) {
    wfty_modal_show(title, "#WFTY_MB_inline?height=500&amp;width=1000&amp;inlineId=" + id + "");
}

function wfty_modal_show(caption, url, imageGroup) {//function called when the user clicks on a wftymodal link

    var $closeBtn;

    try {
        if (typeof document.body.style.maxHeight === "undefined") {//if IE 6
            jQuery("body", "html").css({height: "100%", width: "100%"});
            jQuery("html").css("overflow", "hidden");
            if (document.getElementById("WFTY_MB_HideSelect") === null) {//iframe to hide select elements in ie6
                jQuery("body").append("<iframe id='WFTY_MB_HideSelect'>" + wftymodal10n.noiframes + "</iframe><div id='WFTY_MB_overlay'></div><div id='WFTY_MB_window' class='wftymodal-loading'></div>");
                jQuery("#WFTY_MB_overlay").click(wfty_modal_remove);
            }
        } else {//all others
            if (document.getElementById("WFTY_MB_overlay") === null) {
                jQuery("body").append("<div id='WFTY_MB_overlay'></div><div id='WFTY_MB_window' class='wftymodal-loading'></div>");
                jQuery("#WFTY_MB_overlay").click(wfty_modal_remove);
                jQuery('body').addClass('modal-open');
            }
        }

        if (wfty_modal_detectMacXFF()) {
            jQuery("#WFTY_MB_overlay").addClass("WFTY_MB_overlayMacFFBGHack");//use png overlay so hide flash
        } else {
            jQuery("#WFTY_MB_overlay").addClass("WFTY_MB_overlayBG");//use background and opacity
        }

        if (caption === null) {
            caption = "";
        }
        jQuery("body").append("<div id='WFTY_MB_load'><img src='" + imgLoader.src + "' width='208' /></div>");//add loader to the page
        jQuery('#WFTY_MB_load').show();//show loader

        var baseURL;
        if (url.indexOf("?") !== -1) { //ff there is a query string involved
            baseURL = url.substr(0, url.indexOf("?"));
        } else {
            baseURL = url;
        }

        var urlString = /\.jpg$|\.jpeg$|\.png$|\.gif$|\.bmp$/;
        var urlType = baseURL.toLowerCase().match(urlString);

        if (urlType == '.jpg' || urlType == '.jpeg' || urlType == '.png' || urlType == '.gif' || urlType == '.bmp') {//code to show images

            WFTY_MB_PrevCaption = "";
            WFTY_MB_PrevURL = "";
            WFTY_MB_PrevHTML = "";
            WFTY_MB_NextCaption = "";
            WFTY_MB_NextURL = "";
            WFTY_MB_NextHTML = "";
            WFTY_MB_imageCount = "";
            WFTY_MB_FoundURL = false;
            if (imageGroup) {
                WFTY_MB_TempArray = jQuery("a[rel=" + imageGroup + "]").get();
                for (WFTY_MB_Counter = 0; ((WFTY_MB_Counter < WFTY_MB_TempArray.length) && (WFTY_MB_NextHTML === "")); WFTY_MB_Counter++) {
                    var urlTypeTemp = WFTY_MB_TempArray[WFTY_MB_Counter].href.toLowerCase().match(urlString);
                    if (!(WFTY_MB_TempArray[WFTY_MB_Counter].href == url)) {
                        if (WFTY_MB_FoundURL) {
                            WFTY_MB_NextCaption = WFTY_MB_TempArray[WFTY_MB_Counter].title;
                            WFTY_MB_NextURL = WFTY_MB_TempArray[WFTY_MB_Counter].href;
                            WFTY_MB_NextHTML = "<span id='WFTY_MB_next'>&nbsp;&nbsp;<a href='#'>" + wftymodal10n.next + "</a></span>";
                        } else {
                            WFTY_MB_PrevCaption = WFTY_MB_TempArray[WFTY_MB_Counter].title;
                            WFTY_MB_PrevURL = WFTY_MB_TempArray[WFTY_MB_Counter].href;
                            WFTY_MB_PrevHTML = "<span id='WFTY_MB_prev'>&nbsp;&nbsp;<a href='#'>" + wftymodal10n.prev + "</a></span>";
                        }
                    } else {
                        WFTY_MB_FoundURL = true;
                        WFTY_MB_imageCount = wftymodal10n.image + ' ' + (WFTY_MB_Counter + 1) + ' ' + wftymodal10n.of + ' ' + (WFTY_MB_TempArray.length);
                    }
                }
            }

            imgPreloader = new Image();
            imgPreloader.onload = function () {
                imgPreloader.onload = null;

                // Resizing large images - original by Christian Montoya edited by me.
                var pagesize = wfty_modal_getPageSize();
                var x = pagesize[0] - 150;
                var y = pagesize[1] - 150;
                var imageWidth = imgPreloader.width;
                var imageHeight = imgPreloader.height;
                if (imageWidth > x) {
                    imageHeight = imageHeight * (x / imageWidth);
                    imageWidth = x;
                    if (imageHeight > y) {
                        imageWidth = imageWidth * (y / imageHeight);
                        imageHeight = y;
                    }
                } else if (imageHeight > y) {
                    imageWidth = imageWidth * (y / imageHeight);
                    imageHeight = y;
                    if (imageWidth > x) {
                        imageHeight = imageHeight * (x / imageWidth);
                        imageWidth = x;
                    }
                }
                // End Resizing

                WFTY_MB_WIDTH = imageWidth + 30;
                WFTY_MB_HEIGHT = imageHeight + 60;
                jQuery("#WFTY_MB_window").append("<a href='' id='WFTY_MB_ImageOff'><span class='screen-reader-text'>" + wftymodal10n.close + "</span><img id='WFTY_MB_Image' src='" + url + "' width='" + imageWidth + "' height='" + imageHeight + "' alt='" + caption + "'/></a>" + "<div id='WFTY_MB_caption'>" + caption + "<div id='WFTY_MB_secondLine'>" + WFTY_MB_imageCount + WFTY_MB_PrevHTML + WFTY_MB_NextHTML + "</div></div><div id='WFTY_MB_closeWindow'><button type='button' id='WFTY_MB_closeWindowButton'><span class='screen-reader-text'>" + wftymodal10n.close + "</span><span class='wfty_modal_close_btn'></span></button></div>");

                jQuery("#WFTY_MB_closeWindowButton").click(wfty_modal_remove);

                if (!(WFTY_MB_PrevHTML === "")) {
                    function goPrev() {
                        if (jQuery(document).unbind("click", goPrev)) {
                            jQuery(document).unbind("click", goPrev);
                        }
                        jQuery("#WFTY_MB_window").remove();
                        jQuery("body").append("<div id='WFTY_MB_window'></div>");
                        wfty_modal_show(WFTY_MB_PrevCaption, WFTY_MB_PrevURL, imageGroup);
                        return false;
                    }

                    jQuery("#WFTY_MB_prev").click(goPrev);
                }

                if (!(WFTY_MB_NextHTML === "")) {
                    function goNext() {
                        jQuery("#WFTY_MB_window").remove();
                        jQuery("body").append("<div id='WFTY_MB_window'></div>");
                        wfty_modal_show(WFTY_MB_NextCaption, WFTY_MB_NextURL, imageGroup);
                        return false;
                    }

                    jQuery("#WFTY_MB_next").click(goNext);

                }

                jQuery(document).bind('keydown.wftymodal', function (e) {
                    if (e.which == 27) { // close
                        wfty_modal_remove();

                    } else if (e.which == 190) { // display previous image
                        if (!(WFTY_MB_NextHTML == "")) {
                            jQuery(document).unbind('wftymodal');
                            goNext();
                        }
                    } else if (e.which == 188) { // display next image
                        if (!(WFTY_MB_PrevHTML == "")) {
                            jQuery(document).unbind('wftymodal');
                            goPrev();
                        }
                    }
                    return false;
                });

                wfty_modal_position();
                jQuery("#WFTY_MB_load").remove();
                jQuery("#WFTY_MB_ImageOff").click(wfty_modal_remove);
                jQuery("#WFTY_MB_window").css({'visibility': 'visible'}); //for safari using css instead of show
            };

            imgPreloader.src = url;
        } else {
            var queryString = url.replace(/^[^\?]+\??/, '');
            var params = wfty_modal_parseQuery(queryString);

            WFTY_MB_WIDTH = (params['width'] * 1) + 30 || 630; //defaults to 630 if no parameters were added to URL
            WFTY_MB_HEIGHT = (params['height'] * 1) + 40 || 440; //defaults to 440 if no parameters were added to URL
            ajaxContentW = WFTY_MB_WIDTH - 30;
            ajaxContentH = WFTY_MB_HEIGHT - 45;

            if (url.indexOf('WFTY_MB_iframe') != -1) {// either iframe or ajax window
                urlNoQuery = url.split('WFTY_MB_');
                jQuery("#WFTY_MB_iframeContent").remove();
                if (params['modal'] != "true") {//iframe no modal
                    jQuery("#WFTY_MB_window").append("<div id='WFTY_MB_title'><div id='WFTY_MB_ajaxWindowTitle'>" + caption + "</div><div id='WFTY_MB_closeAjaxWindow'><button type='button' id='WFTY_MB_closeWindowButton'><span class='screen-reader-text'>" + wftymodal10n.close + "</span><span class='wfty_modal_close_btn'></span></button></div></div><iframe frameborder='0' hspace='0' allowtransparency='true' src='" + urlNoQuery[0] + "' id='WFTY_MB_iframeContent' name='WFTY_MB_iframeContent" + Math.round(Math.random() * 1000) + "' onload='wfty_modal_showIframe()' style='width:" + (ajaxContentW + 29) + "px;height:" + (ajaxContentH + 17) + "px;' >" + wftymodal10n.noiframes + "</iframe>");
                } else {//iframe modal
                    jQuery("#WFTY_MB_overlay").unbind();
                    jQuery("#WFTY_MB_window").append("<iframe frameborder='0' hspace='0' allowtransparency='true' src='" + urlNoQuery[0] + "' id='WFTY_MB_iframeContent' name='WFTY_MB_iframeContent" + Math.round(Math.random() * 1000) + "' onload='wfty_modal_showIframe()' style='width:" + (ajaxContentW + 29) + "px;height:" + (ajaxContentH + 17) + "px;'>" + wftymodal10n.noiframes + "</iframe>");
                }
            } else {// not an iframe, ajax
                if (jQuery("#WFTY_MB_window").css("visibility") != "visible") {
                    if (params['modal'] != "true") {//ajax no modal
                        jQuery("#WFTY_MB_window").append("<div id='WFTY_MB_title'><div id='WFTY_MB_ajaxWindowTitle'>" + caption + "</div><div id='WFTY_MB_closeAjaxWindow'><a href='#' id='WFTY_MB_closeWindowButton'><div class='wfty_modal_close_btn'></div></a></div></div><div id='WFTY_MB_ajaxContent' style='width:" + ajaxContentW + "px;height:" + ajaxContentH + "px'></div>");
                    } else {//ajax modal
                        jQuery("#WFTY_MB_overlay").unbind();
                        jQuery("#WFTY_MB_window").append("<div id='WFTY_MB_ajaxContent' class='WFTY_MB_modal' style='width:" + ajaxContentW + "px;height:" + ajaxContentH + "px;'></div>");
                    }
                } else {//this means the window is already up, we are just loading new content via ajax
                    jQuery("#WFTY_MB_ajaxContent")[0].style.width = ajaxContentW + "px";
                    jQuery("#WFTY_MB_ajaxContent")[0].style.height = ajaxContentH + "px";
                    jQuery("#WFTY_MB_ajaxContent")[0].scrollTop = 0;
                    jQuery("#WFTY_MB_ajaxWindowTitle").html(caption);
                }
            }

            jQuery("#WFTY_MB_closeWindowButton").click(wfty_modal_remove);

            if (url.indexOf('WFTY_MB_inline') != -1) {
                jQuery("#WFTY_MB_ajaxContent").append(jQuery('#' + params['inlineId']).children());
                jQuery("#WFTY_MB_window").bind('wfty_modal_unload', function () {
                    jQuery('#' + params['inlineId']).append(jQuery("#WFTY_MB_ajaxContent").children()); // move elements back when you're finished
                });
                wfty_modal_position();
                jQuery("#WFTY_MB_load").remove();
                jQuery("#WFTY_MB_window").css({'visibility': 'visible'});
            } else if (url.indexOf('WFTY_MB_iframe') != -1) {
                wfty_modal_position();
                jQuery("#WFTY_MB_load").remove();
                jQuery("#WFTY_MB_window").css({'visibility': 'visible'});
            } else {
                var load_url = url;
                load_url += -1 === url.indexOf('?') ? '?' : '&';
                jQuery("#WFTY_MB_ajaxContent").load(load_url += "random=" + (new Date().getTime()), function () {//to do a post change this load method
                    wfty_modal_position();
                    jQuery("#WFTY_MB_load").remove();
                    wfty_modal_init("#WFTY_MB_ajaxContent a.wftymodal");
                    jQuery("#WFTY_MB_window").css({'visibility': 'visible'});
                });
            }

        }

        if (!params['modal']) {
            jQuery(document).bind('keydown.wftymodal', function (e) {
                if (e.which == 27) { // close
                    wfty_modal_remove();
                    return false;
                }
            });
        }

        $closeBtn = jQuery('#WFTY_MB_closeWindowButton');
        /*
         * If the native Close button icon is visible, move focus on the button
         * (e.g. in the Network Admin Themes screen).
         * In other admin screens is hidden and replaced by a different icon.
         */
        if ($closeBtn.find('.wfty_modal_close_btn').is(':visible')) {
            $closeBtn.focus();
        }


        if (jQuery("#WFTY_MB_ajaxContent").innerHeight() > window.innerHeight) {
            jQuery("#WFTY_MB_ajaxContent").height((window.innerHeight * 90) / 100);
        }

    } catch (e) {
        //nothing here
    }
}

//helper functions below
function wfty_modal_showIframe() {
    jQuery("#WFTY_MB_load").remove();
    jQuery("#WFTY_MB_window").css({'visibility': 'visible'}).trigger('wftymodal:iframe:loaded');
}

function wfty_modal_remove() {
    jQuery("#WFTY_MB_imageOff").unbind("click");
    jQuery("#WFTY_MB_closeWindowButton").unbind("click");
    jQuery('#WFTY_MB_window').fadeOut('fast', function () {
        jQuery('#WFTY_MB_window, #WFTY_MB_overlay, #WFTY_MB_HideSelect').trigger('wfty_modal_unload').unbind().remove();
        jQuery('body').trigger('wftymodal:removed');
    });
    jQuery('body').removeClass('modal-open');
    jQuery("#WFTY_MB_load").remove();
    if (typeof document.body.style.maxHeight == "undefined") {//if IE 6
        jQuery("body", "html").css({height: "auto", width: "auto"});
        jQuery("html").css("overflow", "");
    }
    jQuery(document).unbind('.wftymodal');
    return false;
}

function wfty_modal_position() {
    var isIE6 = typeof document.body.style.maxHeight === "undefined";
    jQuery("#WFTY_MB_window").css({marginLeft: '-' + parseInt((WFTY_MB_WIDTH / 2), 10) + 'px', width: WFTY_MB_WIDTH + 'px'});
    if (!isIE6) { // take away IE6
        jQuery("#WFTY_MB_window").css({marginTop: '-' + parseInt((WFTY_MB_HEIGHT / 2), 10) + 'px'});
    }
}

function wfty_modal_parseQuery(query) {
    var Params = {};
    if (!query) {
        return Params;
    }// return empty object
    var Pairs = query.split(/[;&]/);
    for (var i = 0; i < Pairs.length; i++) {
        var KeyVal = Pairs[i].split('=');
        if (!KeyVal || KeyVal.length != 2) {
            continue;
        }
        var key = unescape(KeyVal[0]);
        var val = unescape(KeyVal[1]);
        val = val.replace(/\+/g, ' ');
        Params[key] = val;
    }
    return Params;
}

function wfty_modal_getPageSize() {
    var de = document.documentElement;
    var w = window.innerWidth || self.innerWidth || (de && de.clientWidth) || document.body.clientWidth;
    var h = window.innerHeight || self.innerHeight || (de && de.clientHeight) || document.body.clientHeight;
    arrayPageSize = [w, h];
    return arrayPageSize;
}

function wfty_modal_detectMacXFF() {
    var userAgent = navigator.userAgent.toLowerCase();
    if (userAgent.indexOf('mac') != -1 && userAgent.indexOf('firefox') != -1) {
        return true;
    }
}

