/*
 * Thickbox 3.1 - One Box To Rule Them All.
 * By Cody Lindley (http://www.codylindley.com)
 * Copyright (c) 2007 cody lindley
 * Licensed under the MIT License: http://www.opensource.org/licenses/mit-license.php
 */

if (typeof xlwcty_modal_pathToImage != 'string') {
    var xlwcty_modal_pathToImage = (typeof xlwctymodal10n !== "undefined") ? xlwctymodal10n.loadingAnimation : "";
}

/*!!!!!!!!!!!!!!!!! edit below this line at your own risk !!!!!!!!!!!!!!!!!!!!!!!*/

//on page load call xlwcty_modal_init
jQuery(document).ready(function () {
    xlwcty_modal_init('a.xlwctymodal, area.xlwctymodal, input.xlwctymodal');//pass where to apply xlwctymodal
    imgLoader = new Image();// preload image
    imgLoader.src = xlwcty_modal_pathToImage;
});

/*
 * Add xlwctymodal to href & area elements that have a class of .xlwctymodal.
 * Remove the loading indicator when content in an iframe has loaded.
 */
function xlwcty_modal_init(domChunk) {
    jQuery('body')
            .on('click', domChunk, xlwcty_modal_click)
            .on('xlwctymodal:iframe:loaded', function () {
                jQuery('#xlwcty_MB_window').removeClass('xlwctymodal-loading');
            });
}

function xlwcty_modal_click() {
    var t = this.title || this.name || null;
    var a = this.href || this.alt;
    var g = this.rel || false;
    xlwcty_modal_show(t, a, g);
    this.blur();
    return false;
}

function xlwcty_modal_show(caption, url, imageGroup) {//function called when the user clicks on a xlwctymodal link

    var $closeBtn;

    try {
        if (typeof document.body.style.maxHeight === "undefined") {//if IE 6
            jQuery("body", "html").css({height: "100%", width: "100%"});
            jQuery("html").css("overflow", "hidden");
            if (document.getElementById("xlwcty_MB_HideSelect") === null) {//iframe to hide select elements in ie6
                jQuery("body").append("<iframe id='xlwcty_MB_HideSelect'>" + xlwctymodal10n.noiframes + "</iframe><div id='xlwcty_MB_overlay'></div><div id='xlwcty_MB_window' class='xlwctymodal-loading'></div>");
                jQuery("#xlwcty_MB_overlay").click(xlwcty_modal_remove);
            }
        } else {//all others
            if (document.getElementById("xlwcty_MB_overlay") === null) {
                jQuery("body").append("<div id='xlwcty_MB_overlay'></div><div id='xlwcty_MB_window' class='xlwctymodal-loading'></div>");
                jQuery("#xlwcty_MB_overlay").click(xlwcty_modal_remove);
                jQuery('body').addClass('modal-open');
            }
        }

        if (xlwcty_modal_detectMacXFF()) {
            jQuery("#xlwcty_MB_overlay").addClass("xlwcty_MB_overlayMacFFBGHack");//use png overlay so hide flash
        } else {
            jQuery("#xlwcty_MB_overlay").addClass("xlwcty_MB_overlayBG");//use background and opacity
        }

        if (caption === null) {
            caption = "";
        }
        jQuery("body").append("<div id='xlwcty_MB_load'><img src='" + imgLoader.src + "' width='208' /></div>");//add loader to the page
        jQuery('#xlwcty_MB_load').show();//show loader

        var baseURL;
        if (url.indexOf("?") !== -1) { //ff there is a query string involved
            baseURL = url.substr(0, url.indexOf("?"));
        } else {
            baseURL = url;
        }

        var urlString = /\.jpg$|\.jpeg$|\.png$|\.gif$|\.bmp$/;
        var urlType = baseURL.toLowerCase().match(urlString);

        if (urlType == '.jpg' || urlType == '.jpeg' || urlType == '.png' || urlType == '.gif' || urlType == '.bmp') {//code to show images

            xlwcty_MB_PrevCaption = "";
            xlwcty_MB_PrevURL = "";
            xlwcty_MB_PrevHTML = "";
            xlwcty_MB_NextCaption = "";
            xlwcty_MB_NextURL = "";
            xlwcty_MB_NextHTML = "";
            xlwcty_MB_imageCount = "";
            xlwcty_MB_FoundURL = false;
            if (imageGroup) {
                xlwcty_MB_TempArray = jQuery("a[rel=" + imageGroup + "]").get();
                for (xlwcty_MB_Counter = 0; ((xlwcty_MB_Counter < xlwcty_MB_TempArray.length) && (xlwcty_MB_NextHTML === "")); xlwcty_MB_Counter++) {
                    var urlTypeTemp = xlwcty_MB_TempArray[xlwcty_MB_Counter].href.toLowerCase().match(urlString);
                    if (!(xlwcty_MB_TempArray[xlwcty_MB_Counter].href == url)) {
                        if (xlwcty_MB_FoundURL) {
                            xlwcty_MB_NextCaption = xlwcty_MB_TempArray[xlwcty_MB_Counter].title;
                            xlwcty_MB_NextURL = xlwcty_MB_TempArray[xlwcty_MB_Counter].href;
                            xlwcty_MB_NextHTML = "<span id='xlwcty_MB_next'>&nbsp;&nbsp;<a href='#'>" + xlwctymodal10n.next + "</a></span>";
                        } else {
                            xlwcty_MB_PrevCaption = xlwcty_MB_TempArray[xlwcty_MB_Counter].title;
                            xlwcty_MB_PrevURL = xlwcty_MB_TempArray[xlwcty_MB_Counter].href;
                            xlwcty_MB_PrevHTML = "<span id='xlwcty_MB_prev'>&nbsp;&nbsp;<a href='#'>" + xlwctymodal10n.prev + "</a></span>";
                        }
                    } else {
                        xlwcty_MB_FoundURL = true;
                        xlwcty_MB_imageCount = xlwctymodal10n.image + ' ' + (xlwcty_MB_Counter + 1) + ' ' + xlwctymodal10n.of + ' ' + (xlwcty_MB_TempArray.length);
                    }
                }
            }

            imgPreloader = new Image();
            imgPreloader.onload = function () {
                imgPreloader.onload = null;

                // Resizing large images - original by Christian Montoya edited by me.
                var pagesize = xlwcty_modal_getPageSize();
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

                xlwcty_MB_WIDTH = imageWidth + 30;
                xlwcty_MB_HEIGHT = imageHeight + 60;
                jQuery("#xlwcty_MB_window").append("<a href='' id='xlwcty_MB_ImageOff'><span class='screen-reader-text'>" + xlwctymodal10n.close + "</span><img id='xlwcty_MB_Image' src='" + url + "' width='" + imageWidth + "' height='" + imageHeight + "' alt='" + caption + "'/></a>" + "<div id='xlwcty_MB_caption'>" + caption + "<div id='xlwcty_MB_secondLine'>" + xlwcty_MB_imageCount + xlwcty_MB_PrevHTML + xlwcty_MB_NextHTML + "</div></div><div id='xlwcty_MB_closeWindow'><button type='button' id='xlwcty_MB_closeWindowButton'><span class='screen-reader-text'>" + xlwctymodal10n.close + "</span><span class='xlwcty_modal_close_btn'></span></button></div>");

                jQuery("#xlwcty_MB_closeWindowButton").click(xlwcty_modal_remove);

                if (!(xlwcty_MB_PrevHTML === "")) {
                    function goPrev() {
                        if (jQuery(document).unbind("click", goPrev)) {
                            jQuery(document).unbind("click", goPrev);
                        }
                        jQuery("#xlwcty_MB_window").remove();
                        jQuery("body").append("<div id='xlwcty_MB_window'></div>");
                        xlwcty_modal_show(xlwcty_MB_PrevCaption, xlwcty_MB_PrevURL, imageGroup);
                        return false;
                    }
                    jQuery("#xlwcty_MB_prev").click(goPrev);
                }

                if (!(xlwcty_MB_NextHTML === "")) {
                    function goNext() {
                        jQuery("#xlwcty_MB_window").remove();
                        jQuery("body").append("<div id='xlwcty_MB_window'></div>");
                        xlwcty_modal_show(xlwcty_MB_NextCaption, xlwcty_MB_NextURL, imageGroup);
                        return false;
                    }
                    jQuery("#xlwcty_MB_next").click(goNext);

                }

                jQuery(document).bind('keydown.xlwctymodal', function (e) {
                    if (e.which == 27) { // close
                        xlwcty_modal_remove();

                    } else if (e.which == 190) { // display previous image
                        if (!(xlwcty_MB_NextHTML == "")) {
                            jQuery(document).unbind('xlwctymodal');
                            goNext();
                        }
                    } else if (e.which == 188) { // display next image
                        if (!(xlwcty_MB_PrevHTML == "")) {
                            jQuery(document).unbind('xlwctymodal');
                            goPrev();
                        }
                    }
                    return false;
                });

                xlwcty_modal_position();
                jQuery("#xlwcty_MB_load").remove();
                jQuery("#xlwcty_MB_ImageOff").click(xlwcty_modal_remove);
                jQuery("#xlwcty_MB_window").css({'visibility': 'visible'}); //for safari using css instead of show
            };

            imgPreloader.src = url;
        } else {//code to show html

            var queryString = url.replace(/^[^\?]+\??/, '');
            var params = xlwcty_modal_parseQuery(queryString);

            xlwcty_MB_WIDTH = (params['width'] * 1) + 30 || 630; //defaults to 630 if no parameters were added to URL
            xlwcty_MB_HEIGHT = (params['height'] * 1) + 40 || 440; //defaults to 440 if no parameters were added to URL
            ajaxContentW = xlwcty_MB_WIDTH - 30;
            ajaxContentH = xlwcty_MB_HEIGHT - 45;

            if (url.indexOf('xlwcty_MB_iframe') != -1) {// either iframe or ajax window
                urlNoQuery = url.split('xlwcty_MB_');
                jQuery("#xlwcty_MB_iframeContent").remove();
                if (params['modal'] != "true") {//iframe no modal
                    jQuery("#xlwcty_MB_window").append("<div id='xlwcty_MB_title'><div id='xlwcty_MB_ajaxWindowTitle'>" + caption + "</div><div id='xlwcty_MB_closeAjaxWindow'><button type='button' id='xlwcty_MB_closeWindowButton'><span class='screen-reader-text'>" + xlwctymodal10n.close + "</span><span class='xlwcty_modal_close_btn'></span></button></div></div><iframe frameborder='0' hspace='0' allowtransparency='true' src='" + urlNoQuery[0] + "' id='xlwcty_MB_iframeContent' name='xlwcty_MB_iframeContent" + Math.round(Math.random() * 1000) + "' onload='xlwcty_modal_showIframe()' style='width:" + (ajaxContentW + 29) + "px;height:" + (ajaxContentH + 17) + "px;' >" + xlwctymodal10n.noiframes + "</iframe>");
                } else {//iframe modal
                    jQuery("#xlwcty_MB_overlay").unbind();
                    jQuery("#xlwcty_MB_window").append("<iframe frameborder='0' hspace='0' allowtransparency='true' src='" + urlNoQuery[0] + "' id='xlwcty_MB_iframeContent' name='xlwcty_MB_iframeContent" + Math.round(Math.random() * 1000) + "' onload='xlwcty_modal_showIframe()' style='width:" + (ajaxContentW + 29) + "px;height:" + (ajaxContentH + 17) + "px;'>" + xlwctymodal10n.noiframes + "</iframe>");
                }
            } else {// not an iframe, ajax
                if (jQuery("#xlwcty_MB_window").css("visibility") != "visible") {
                    if (params['modal'] != "true") {//ajax no modal
                        jQuery("#xlwcty_MB_window").append("<div id='xlwcty_MB_title'><div id='xlwcty_MB_ajaxWindowTitle'>" + caption + "</div><div id='xlwcty_MB_closeAjaxWindow'><a href='#' id='xlwcty_MB_closeWindowButton'><div class='xlwcty_modal_close_btn'></div></a></div></div><div id='xlwcty_MB_ajaxContent' style='width:" + ajaxContentW + "px;height:" + ajaxContentH + "px'></div>");
                    } else {//ajax modal
                        jQuery("#xlwcty_MB_overlay").unbind();
                        jQuery("#xlwcty_MB_window").append("<div id='xlwcty_MB_ajaxContent' class='xlwcty_MB_modal' style='width:" + ajaxContentW + "px;height:" + ajaxContentH + "px;'></div>");
                    }
                } else {//this means the window is already up, we are just loading new content via ajax
                    jQuery("#xlwcty_MB_ajaxContent")[0].style.width = ajaxContentW + "px";
                    jQuery("#xlwcty_MB_ajaxContent")[0].style.height = ajaxContentH + "px";
                    jQuery("#xlwcty_MB_ajaxContent")[0].scrollTop = 0;
                    jQuery("#xlwcty_MB_ajaxWindowTitle").html(caption);
                }
            }

            jQuery("#xlwcty_MB_closeWindowButton").click(xlwcty_modal_remove);

            if (url.indexOf('xlwcty_MB_inline') != -1) {
                jQuery("#xlwcty_MB_ajaxContent").append(jQuery('#' + params['inlineId']).children());
                jQuery("#xlwcty_MB_window").bind('xlwcty_modal_unload', function () {
                    jQuery('#' + params['inlineId']).append(jQuery("#xlwcty_MB_ajaxContent").children()); // move elements back when you're finished
                });
                xlwcty_modal_position();
                jQuery("#xlwcty_MB_load").remove();
                jQuery("#xlwcty_MB_window").css({'visibility': 'visible'});
            } else if (url.indexOf('xlwcty_MB_iframe') != -1) {
                xlwcty_modal_position();
                jQuery("#xlwcty_MB_load").remove();
                jQuery("#xlwcty_MB_window").css({'visibility': 'visible'});
            } else {
                var load_url = url;
                load_url += -1 === url.indexOf('?') ? '?' : '&';
                jQuery("#xlwcty_MB_ajaxContent").load(load_url += "random=" + (new Date().getTime()), function () {//to do a post change this load method
                    xlwcty_modal_position();
                    jQuery("#xlwcty_MB_load").remove();
                    xlwcty_modal_init("#xlwcty_MB_ajaxContent a.xlwctymodal");
                    jQuery("#xlwcty_MB_window").css({'visibility': 'visible'});
                });
            }

        }

        if (!params['modal']) {
            jQuery(document).bind('keydown.xlwctymodal', function (e) {
                if (e.which == 27) { // close
                    xlwcty_modal_remove();
                    return false;
                }
            });
        }

        $closeBtn = jQuery('#xlwcty_MB_closeWindowButton');
        /*
         * If the native Close button icon is visible, move focus on the button
         * (e.g. in the Network Admin Themes screen).
         * In other admin screens is hidden and replaced by a different icon.
         */
        if ($closeBtn.find('.xlwcty_modal_close_btn').is(':visible')) {
            $closeBtn.focus();
        }


        if (jQuery("#xlwcty_MB_ajaxContent").innerHeight() > window.innerHeight) {
            jQuery("#xlwcty_MB_ajaxContent").height((window.innerHeight * 90) / 100);
        }

    } catch (e) {
        //nothing here
    }
}

//helper functions below
function xlwcty_modal_showIframe() {
    jQuery("#xlwcty_MB_load").remove();
    jQuery("#xlwcty_MB_window").css({'visibility': 'visible'}).trigger('xlwctymodal:iframe:loaded');
}

function xlwcty_modal_remove() {
    jQuery("#xlwcty_MB_imageOff").unbind("click");
    jQuery("#xlwcty_MB_closeWindowButton").unbind("click");
    jQuery('#xlwcty_MB_window').fadeOut('fast', function () {
        jQuery('#xlwcty_MB_window, #xlwcty_MB_overlay, #xlwcty_MB_HideSelect').trigger('xlwcty_modal_unload').unbind().remove();
        jQuery('body').trigger('xlwctymodal:removed');
    });
    jQuery('body').removeClass('modal-open');
    jQuery("#xlwcty_MB_load").remove();
    if (typeof document.body.style.maxHeight == "undefined") {//if IE 6
        jQuery("body", "html").css({height: "auto", width: "auto"});
        jQuery("html").css("overflow", "");
    }
    jQuery(document).unbind('.xlwctymodal');
    return false;
}

function xlwcty_modal_position() {
    var isIE6 = typeof document.body.style.maxHeight === "undefined";
    jQuery("#xlwcty_MB_window").css({marginLeft: '-' + parseInt((xlwcty_MB_WIDTH / 2), 10) + 'px', width: xlwcty_MB_WIDTH + 'px'});
    if (!isIE6) { // take away IE6
        jQuery("#xlwcty_MB_window").css({marginTop: '-' + parseInt((xlwcty_MB_HEIGHT / 2), 10) + 'px'});
    }
}

function xlwcty_modal_parseQuery(query) {
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

function xlwcty_modal_getPageSize() {
    var de = document.documentElement;
    var w = window.innerWidth || self.innerWidth || (de && de.clientWidth) || document.body.clientWidth;
    var h = window.innerHeight || self.innerHeight || (de && de.clientHeight) || document.body.clientHeight;
    arrayPageSize = [w, h];
    return arrayPageSize;
}

function xlwcty_modal_detectMacXFF() {
    var userAgent = navigator.userAgent.toLowerCase();
    if (userAgent.indexOf('mac') != -1 && userAgent.indexOf('firefox') != -1) {
        return true;
    }
}
