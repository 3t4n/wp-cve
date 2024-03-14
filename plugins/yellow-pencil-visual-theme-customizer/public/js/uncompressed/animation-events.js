/*
    A Helper javascript function for YellowPencil Editor;
    CSS Animation Event Trigger
    Visit the plugin website to the details: https://yellowpencil.waspthemes.com

    By WaspThemes / All Rights Reserved.
*/
"use strict";

// jQuery Visible Plugin
function wyp_visible_function(element, t, e, o) {
    var i = window;
    if (!(element.length < 1)) {
        var r = element.length > 1 ? element[0] : element,
            n = r,
            f = window.innerWidth,
            h = window.innerHeight,
            o = o ? o : "both",
            l = e === !0 ? n.offsetWidth * n.offsetHeight : !0;
        if ("function" == typeof n.getBoundingClientRect) {
            var g = n.getBoundingClientRect(),
                u = g.top >= 0 && g.top < h,
                s = g.bottom > 0 && g.bottom <= h,
                c = g.left >= 0 && g.left < f,
                a = g.right > 0 && g.right <= f,
                v = t ? u || s : u && s,
                b = t ? c || a : c && a;
            if ("both" === o) return l && v && b;
            if ("vertical" === o) return l && v;
            if ("horizontal" === o) return l && b
        } else {
            var d = i.scrollTop,
                p = d + h,
                w = i.scrollLeft,
                m = w + f,
                z = r.offsetTop,
                B = z + parseFloat(getComputedStyle(r, null).height.replace("px", "")),
                C = r.offsetLeft,
                R = C + parseFloat(getComputedStyle(r, null).width.replace("px", "")),
                j = t === !0 ? B : z,
                q = t === !0 ? z : B,
                H = t === !0 ? R : C,
                L = t === !0 ? C : R;
            if ("both" === o) return !!l && p >= q && j >= d && m >= L && H >= w;
            if ("vertical" === o) return !!l && p >= q && j >= d;
            if ("horizontal" === o) return !!l && m >= L && H >= w
        }
    }
}


// Getting All Selectors from CSS Output
function get_all_selectors(source) {

    // if no source, stop.
    if (source == '') {
        return false;
    }

    // Delete media close
    source = source.replace(/\}\}/g, "}");

    // if have a problem in source, stop.
    if (source.split('{').length != source.split('}').length) {
        return false;
    }

    // Replaces
    source = source.toString().replace(/\}\,/g, "}");

    // Getting All CSS Selectors.
    var allSelectors = array_cleaner(source.replace(/\{(.*?)\}/g, '|BREAK|').split("|BREAK|"));

    return allSelectors;

}


// Minify Output CSS
function get_minimized_css(data, media) {

    // Checks
    if (data == false || data == true) {
        return '';
    }

    // Clean.
    data = data.replace(/(\r\n|\n|\r)/g, "").replace(/\t/g, '');

    // Don't care rules in comment.
    data = data.replace(/\/\*(.*?)\*\//g, "");

    // clean.
    data = data.replace(/\}\s+\}/g, '}}').replace(/\s+\{/g, '{');

    // clean.
    data = data.replace(/\s+\}/g, '}').replace(/\{\s+/g, '{');
    data = filter_bad_queries(data);

    // Don't care rules in media query
    if (media === true) {
        data = data.replace(/@media(.*?)\}\}/g, '').replace(/@?([a-zA-Z0-9_-]+)?keyframes(.*?)\}\}/g, '').replace(/@font-face(.*?)\}\}/g, '').replace(/@import(.*?)\;/g, '').replace(/@charset(.*?)\;/g, '');
    }

    // data
    return data;

}


// Filtering bad queries
function filter_bad_queries(data) {
    return data.replace(/[\u2018\u2019\u201A\u201B\u2032\u2035\u201C\u201D]/g, '');
}


// Delete the empty array items
function array_cleaner(actual) {

    var uniqueArray = [],
        i, el;

    for (i = 0; i < actual.length; i++) {

        el = actual[i];

        if (uniqueArray.indexOf(el) === -1) {
            uniqueArray.push(el);
        }

    }

    return uniqueArray;

}



// Getting CSS Data from Live Preview, external CSS etc.
function get_css_data() {

    // Stop if not CSS Output.
    if (document.querySelectorAll("style#yellow-pencil,style#wyp-live-preview,link#wyp-custom-css") == null) {
        return false;
    }

    // CSS Data
    var data = '';

    // Check if not external CSS
    if (document.querySelector("link#wyp-custom-css") == null) {

        // Adds Default CSS
        if (document.querySelector("style#yellow-pencil") != null) {
            data = data + document.querySelector("style#yellow-pencil").innerHTML;
        }

        // Adds live preivew CSS
        if (document.querySelector("style#wyp-live-preview") != null) {
            data = data + document.querySelector("style#wyp-live-preview").innerHTML;
        }

    } else { // else external

        // Adds Default CSS
        data = get_custom_CSS();

        // Adds live preivew CSS
        if (document.querySelector("style#wyp-live-preview") != null) {
            data = data + document.querySelector("style#wyp-live-preview").innerHTML;
        }

    }

    return data;

}


// Search and finds All selectors by filter
window.cachedNormalSelectors = false;
window.cachedMediaSelectors == false;

function get_matches_selectors(filter) {

    var data = "";
    if (window.cachedNormalSelectors == false || window.cachedMediaSelectors == false) {
        data = get_css_data();
    }

    // make it first time only
    if (window.cachedNormalSelectors == false) {

        // Getting all selectors by data
        window.cachedNormalSelectors = get_all_selectors(get_minimized_css(data, true));

    }

    // get selectors
    var selectors = window.cachedNormalSelectors;

    // Array
    var array = [],
        a, v;

    // Each all selectors
    for (a = 0; a < selectors.length; a++) {

        // This value
        v = selectors[a];

        // Skip if not valid
        if (v.match(/\}|\{|\;/)) {
            continue;
        }

        // if filter has and selector valid empty
        if (v.indexOf(filter) >= 0 && v != '') {

            // Replace filter and push the selector to array.
            array.push(v.replace(filter, ""));

        }

    }


    if (window.cachedMediaSelectors == false) {

        // Getting all media contents
        window.cachedMediaSelectors = get_minimized_css(data, false).match(/@media(.*?){(.*?)}}/g);

    }

    // Get media selectors
    var mediaAll = window.cachedMediaSelectors;

    // If available
    if (mediaAll != null) {

        // Variables
        var content = '';
        var condition = '';
        var mediaSelectors = '';

        // Each all media Queries
        for (var b = 0; b < mediaAll.length; b++) {

            // This Media
            var media = mediaAll[b];

            // Media condition
            condition = media.match(/@media(.*?){/g).toString().replace(/\{/g, '').replace(/@media /g, '').replace(/@media/g, '');

            // Media Content
            content = media.toString().replace(/@media(.*?){/g, '');

            // All selectors of media
            mediaSelectors = get_all_selectors(content);

            // Eaching all selectors of media
            for (var c = 0; c < mediaSelectors.length; c++) {

                // This Value
                var v = mediaSelectors[c];

                // Skip if not valid
                if (v.match(/\}|\{|\;/)) {
                    continue;
                }

                // if media works current screen size and selector has the filter
                if (window.matchMedia(condition).matches && v.indexOf(filter) >= 0 && v != '') {

                    // Replace filter and push the selector to array.
                    array.push(v.replace(filter, ""));

                }

            }

        }

    }

    // Return
    return array.toString();

}


// Click event support for animations
function click_detect() {

    // this
    var el, thisElements;

    // stop if empty
    if (get_matches_selectors(".yp_click") == "") {
        return false;
    }

    // Get all
    thisElements = document.querySelectorAll(get_matches_selectors(".yp_click"));

    // Each all
    for (var i = 0; i < thisElements.length; i++) {

        // Cache
        el = thisElements[i];

        var limitRepeat = getComputedStyle(el).getPropertyValue("--animation-trigger-repeat"),
        webkitConter = 0,
        workingCounter = 0,
		conter = 0;

        if(limitRepeat == "infinite"){
        	limitRepeat = 9000;
        }

        limitRepeat = parseInt(limitRepeat);

        if(isNaN(limitRepeat)){
        	limitRepeat = 1;
        }

		// Adds event
		el.addEventListener("click", function() {

			workingCounter = conter;

			if(webkitConter > conter){
				workingCounter = webkitConter;
			}

			if(workingCounter != limitRepeat){

            	// yp_click class will trigger the defined animation.
            	this.classList.add("yp_click");

        	}

        });

		// remove yp_click when animationEnd
		el.addEventListener("webkitAnimationEnd", function() {
			if(this.classList.contains('yp_click')){
				webkitConter++;
				this.classList.remove("yp_click");
			}
		});

		// remove yp_click when animationEnd
		el.addEventListener("animationEnd", function() {
			if(this.classList.contains('yp_click')){
				conter++;
				this.classList.remove("yp_click");
			}
		});

    }

}



// Hover event support for animations
function hover_detect() {

    // this
    var el, thisElements;

    // stop if empty
    if (get_matches_selectors(".yp_hover") == "") {
        return false;
    }

    // Get all
    thisElements = document.querySelectorAll(get_matches_selectors(".yp_hover"));

    // Each all
    for (var i = 0; i < thisElements.length; i++) {

        // Cache
        el = thisElements[i];

        var limitRepeat = getComputedStyle(el).getPropertyValue("--animation-trigger-repeat"),
        webkitConter = 0,
        workingCounter = 0,
		conter = 0;

        if(limitRepeat == "infinite"){
        	limitRepeat = 9000;
        }

        limitRepeat = parseInt(limitRepeat);

        if(isNaN(limitRepeat)){
        	limitRepeat = 1;
        }

		// Adds event
		el.addEventListener("mouseenter", function() {

			workingCounter = conter;

			if(webkitConter > conter){
				workingCounter = webkitConter;
			}

			if(workingCounter != limitRepeat){

            	// yp_hover class will trigger the defined animation.
            	this.classList.add("yp_hover");

        	}

        });

		// remove yp_hover when animationEnd
		el.addEventListener("webkitAnimationEnd", function() {
			if(this.classList.contains('yp_hover')){
				webkitConter++;
				this.classList.remove("yp_hover");
			}
		});

		// remove yp_hover when animationEnd
		el.addEventListener("animationEnd", function() {
			if(this.classList.contains('yp_hover')){
				conter++;
				this.classList.remove("yp_hover");
			}
		});

    }

}


// Focus event support for animations
function focus_detect() {

    // this
    var el, thisElements;

    // stop if empty
    if (get_matches_selectors(".yp_focus") == "") {
        return false;
    }

    // Get all
    thisElements = document.querySelectorAll(get_matches_selectors(".yp_focus"));

    // Each all
    for (var i = 0; i < thisElements.length; i++) {

        // Cache
        el = thisElements[i];

        var limitRepeat = getComputedStyle(el).getPropertyValue("--animation-trigger-repeat"),
        webkitConter = 0,
        workingCounter = 0,
		conter = 0;

        if(limitRepeat == "infinite"){
        	limitRepeat = 9000;
        }

        limitRepeat = parseInt(limitRepeat);

        if(isNaN(limitRepeat)){
        	limitRepeat = 1;
        }

		// Adds event
		el.addEventListener("focus", function() {

			workingCounter = conter;

			if(webkitConter > conter){
				workingCounter = webkitConter;
			}

			if(workingCounter != limitRepeat){

            	// yp_focus class will trigger the defined animation.
            	this.classList.add("yp_focus");

        	}

        });

		// remove yp_focus when animationEnd
		el.addEventListener("webkitAnimationEnd", function() {
			if(this.classList.contains('yp_focus')){
				webkitConter++;
				this.classList.remove("yp_focus");
			}
		});

		// remove yp_focus when animationEnd
		el.addEventListener("animationEnd", function() {
			if(this.classList.contains('yp_focus')){
				conter++;
				this.classList.remove("yp_focus");
			}
		});

    }

}


// Visible event support for animations
function onscreen_detect() {

    // this
    var el, thisElements;

    // stop if empty
    if (get_matches_selectors(".yp_onscreen") == "") {
        return false;
    }

    // Get all
    thisElements = document.querySelectorAll(get_matches_selectors(".yp_onscreen"));

    // Each all
    for (var i = 0; i < thisElements.length; i++) {

        // Cache
        el = thisElements[i];

        // Add visible event
        if (wyp_visible_function(el, true)) {

            // yp_onscreen will trigger the defined animation.
            el.classList.add("yp_onscreen");

        }

    }

}



// Setup YP Animation functions
function setAnimTriggers() {

    // Check firsts
    document.addEventListener("DOMContentLoaded", function() {
        onscreen_detect();
        click_detect();
        hover_detect();
        focus_detect();
    });

    // Check after resize
    window.onresize = function() {
        onscreen_detect()
    };

    // Check while scroll for onScreen event
    window.onscroll = function() {
        onscreen_detect()
    };

}



/* ---------------------------------------------------- */
/* Getting custom-xx.css content                        */
/* ---------------------------------------------------- */
function get_custom_CSS() {

    // vars
    var sheet, t, i, c, code = "";

    // For
    for (i = 0; i < document.styleSheets.length; i++) {

        // Get sheet
        sheet = document.styleSheets[i];

        // if is link
        if (sheet.href != null) {

            // get node
            t = sheet.ownerNode.outerHTML;

            // if yp custom css
            if (t.indexOf("wyp-custom-css") != -1) {

                // get all css text
                for (c = 0; c < sheet.cssRules.length; c++) {
                    code += sheet.cssRules[c].cssText;
                }

            }

        }

    }

    // return the code if have
    return code;

}



/* ---------------------------------------------------- */
/* Setup                                                */
/* ---------------------------------------------------- */

// Calls CSS Engine
setAnimTriggers();
