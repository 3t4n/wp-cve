/**
 * Top Bar Front JS
 */

;(function($){
$(document).ready(function (){

    /* Stylesheet JS handling */
    var sheet = (function() {
      var style = document.createElement("style");
      style.appendChild(document.createTextNode(""));
      document.head.appendChild(style);
      return style.sheet;
    })();
    
    function addCSSRule(sheet, selector, rules, index) {
      if("insertRule" in sheet) {
        sheet.insertRule(selector + "{" + rules + "}", index);
      }
      else if("addRule" in sheet) {
        sheet.addRule(selector, rules, index);
      }
    }


    /* Debounce function (credit: David Walsh). */ 
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };


    /* Throttle function (credit: Niall Campbell). */ 
    function throttle(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            if ( !timeout ) timeout = setTimeout( later, wait );
            if (callNow) func.apply(context, args);
        };
    };


    /* Top Bar settings. */
    var tpbrSettings = {};
    for (var property in tpbr_settings) {
        if (tpbr_settings.hasOwnProperty(property)) {
          tpbrSettings[property] = tpbr_settings[property];
        }
    }

    /* Top Bar is inactive, quit. */
    if (tpbrSettings['status'] == 'inactive') { return false; }

    /* Variable initialization. */
    var barPosition = tpbrSettings['fixed'];
    var secondaryColor = '';
    var barBackgroundColor = tpbrSettings['color'];


    /* Checks if user can see the bar. */
    function userCanSeeBar() {

        // Only guests can see the bar.
        if (tpbrSettings['user_who'] == 'notloggedin' && tpbrSettings['guests_or_users'] == 'guests') { return true; }

        // Only users can see the bar.
        if (tpbrSettings['user_who'] == 'loggedin' && tpbrSettings['guests_or_users'] == 'users'){ return true; }

        // Everyone can see the bar.
        if (tpbrSettings['guests_or_users'] == 'all') { return true }

        // Else.
        return false;
    }

    if (!userCanSeeBar()) { return; }


    /* Generates a scondary color. */
    function shadeColor1(color, percent) {
        var num = parseInt(color.slice(1),16), amt = Math.round(2.55 * percent), R = (num >> 16) + amt, G = (num >> 8 & 0x00FF) + amt, B = (num & 0x0000FF) + amt;
        return "#" + (0x1000000 + (R<255?R<1?0:R:255)*0x10000 + (G<255?G<1?0:G:255)*0x100 + (B<255?B<1?0:B:255)).toString(16).slice(1);
    }
    secondaryColor = shadeColor1(barBackgroundColor, -12);


    /* Gets the button element to add to the bar. */
    function getButtonElement() {
        var target = (tpbrSettings['button_behavior'] == 'newwindow') ? 'target="_blank"' : '';
        if (tpbrSettings['yn_button'] == 'button' && tpbrSettings['button_text']) {
            // Gets secondary color.
            return '<a id="tpbr_calltoaction" style="background: ' + secondaryColor + '; display: inline-block; padding: 0px 12px 1px; color: white; text-decoration: none; margin: 1px 14px 0px; border-radius: 3px;" href="' + tpbrSettings['button_url'] + '"' + target + '>' + tpbrSettings['button_text'] + '</a>';
        } else {
            return '';
        }
    }
    

    /* Initialize the bar. */
    function initBar() {
    
        // Gets button element.
        buttonElement = getButtonElement();
            

        /* Bar is fixed top. */
        if (barPosition == 'fixed'){

            var barPositionStyles = 'position:fixed; z-index:99998; width:100%; left:0px; top:0px;'; 
            var barElement = '<div class="pushr"></div><div id="tpbr_topbar" style="' + barPositionStyles + ' background:' + barBackgroundColor + ';"><div id="tpbr_box" style="line-height: 2em; padding: 5px 0px 6px; background:' + barBackgroundColor + '; margin:0 auto; text-align:center; width:100%; color:white; font-size:15px; font-family: Helvetica, Arial, sans-serif;  font-weight:300;"></div></div>';
          
        } else {

            var barElement = '<div id="tpbr_topbar" style="position:relative; z-index:99998; background:' + barBackgroundColor + ';"><div id="tpbr_box" style="line-height: 2em; padding: 5px 0px 6px; background:' + barBackgroundColor + '; margin:0 auto; text-align:center; width:100%; color:white; font-size:15px; font-family: Helvetica, Arial, sans-serif;  font-weight:300;"></div></div>';
            
        }


        /* Injects the bar. */
        setTimeout(function(){
                
            $(barElement).prependTo('body').show();
            $('#tpbr_box').html(tpbrSettings['message'] + buttonElement);

            initBarPosition();

        });

    }
    /* initial call. */
    initBar();
   

    /* Sets bar to initial position. */
    function initBarPosition() {
        
        // Variables.
        var newAdminBarHeight = $('#wpadminbar').outerHeight();
        var newHeight = ($('#tpbr_topbar').css('display') == 'none') ? 0 : $('#tpbr_topbar').outerHeight();
        var adminBarPosition = $("#wpadminbar").css('position');
        var bodyScroll = $(document).scrollTop();
        var pushVariation = 0;
        var barNewTop = 0;


        // Bar is fixed top.
        if (barPosition == 'fixed') {

            if(tpbrSettings['is_admin_bar']) {

                if( adminBarPosition != 'fixed' ) {

                    if (bodyScroll < newAdminBarHeight) {
                        $("#tpbr_topbar").css('top', newAdminBarHeight - bodyScroll );
                        pushVariation = newAdminBarHeight - bodyScroll;
                        barNewTop = newAdminBarHeight - bodyScroll;
                    } else {
                        $("#tpbr_topbar").css('top', 0 );
                        pushVariation = 0;
                        barNewTop = 0;
                    }

                } else {

                    $("#tpbr_topbar").css('top', newAdminBarHeight );
                    pushVariation = 0;
                    barNewTop = newAdminBarHeight;

                }

            } else {

                $("#tpbr_topbar").css('top', 0 );

            }

            /* Update the pusher. */
            $('.pushr').height(newHeight);


        // Bar is in standard position.
        } else {

            if(tpbrSettings['is_admin_bar']) {

                if( adminBarPosition == 'fixed' ) {

                    if (bodyScroll < newHeight) {
                        pushVariation = newHeight - bodyScroll;
                    } else {
                        pushVariation = 0;
                    } 

                } else {
                    
                    if (bodyScroll < newAdminBarHeight + newHeight) {
                        pushVariation = newAdminBarHeight + newHeight - bodyScroll;
                    } else {
                        pushVariation = 0;
                    } 

                }

            } else {
                if (bodyScroll < newHeight) {
                    pushVariation = newHeight - bodyScroll;
                } else {
                    pushVariation = 0;
                }
            }

            $("#tpbr_topbar").css('top', 0 );
            
        }

        // Fix bar position based on context.
        if (tpbrSettings['detect_sticky'] == 1) {
            updateBarContext(newAdminBarHeight, newHeight, adminBarPosition, pushVariation, bodyScroll);
        }

    }


    /* Blends the bar in context. */
    var initialEls = [];
    var firstTime = 0;
    function updateBarContext(newAdminBarHeight, newHeight, adminBarPosition, pushVariation, bodyScroll) {

        if (sheet.cssRules) { // all browsers, except IE before version 9
            for (var i=0; i<sheet.cssRules.length; i++) {
                sheet.deleteRule (i);
            }  
        }

        // Gets divs and header elements of the site.
        var els = $("body").find('div, header, nav').not('#wpadminbar');

        // Loops through each element
        $.each(els, function(index, el) {

            if(firstTime == 0) {
                initialEls.push({ 
                    'clean' : false,
                    'el' : $(el), 
                    'position' : $(el).css('position')
                });
            }

            if( barPosition == 'fixed' ) {

                // The element is positioned fixed.
                if( 
                    $(el).css('position') == 'fixed'
                    && $(el).offset().top - bodyScroll < 250
                ){

                    /* Extracts selector (id first, then class). */
                    var elSelector = ($(el).attr('id')) ? '#' + $(el).attr('id') : '.' + $(el).attr('class').replace(/\s/g, '.');
                    var elClasses = $(el).attr('class') || '-';

                    /* Value to assign to fixed elements' top property. */
                    var topValue = 0;

                    /* If admin bar and fixed, add to topValue. */
                    if( tpbrSettings['is_admin_bar'] ) {
                        if (adminBarPosition == 'fixed') {
                            topValue += parseInt(newAdminBarHeight);
                        } else {
                            topValue += pushVariation;
                        }
                    } 

                    topValue += parseInt(newHeight);

                    /* Checks that element is not from bar. */
                    if(
                        $(el).attr('id') != 'tpbr_topbar'
                        && $(el).attr('id') != 'tpbr_box'
                    ) {
                            
                        if (
                            (elSelector.indexOf('widget') === -1
                            && elSelector.indexOf('modal') === -1
                            && elSelector.indexOf('footer') === -1
                            && elSelector.indexOf('popup') === -1
                            && elSelector.indexOf('overlay') === -1
                            && elSelector.indexOf('loader') === -1
                            && elClasses.indexOf('widget') === -1
                            && elClasses.indexOf('modal') === -1
                            && elClasses.indexOf('footer') === -1
                            && elClasses.indexOf('popup') === -1
                            && elClasses.indexOf('overlay') === -1
                            && elClasses.indexOf('loader') === -1)
                            || elSelector.indexOf('header') !== -1
                            || elClasses.indexOf('header') !== -1
                        ){

                            // Flags for cleaning.
                            initialEls[index].clean = true;
                            $(elSelector).attr("style", "position:fixed; top: "+ topValue +"px !important;"); 
                            addCSSRule(sheet, elSelector, "top: "+ topValue +"px !important;");

                        }
                        
                    }

                }

            /* Bar is in standard position. */
            } else {

                // The element is positioned fixed.
                if( 
                    $(el).css('position') == 'fixed' 
                    && $(el).offset().top - bodyScroll < 250
                ){

                    /* Extracts selector (id first, then class). */
                    var elSelector = ($(el).attr('id')) ? '#' + $(el).attr('id') : '.' + $(el).attr('class').replace(/\s/g, '.');
                    var elClasses = $(el).attr('class') || '-';

                    /* Value to assign to fixed elements' top property. */
                    var topValue = 0;

                    /* If admin bar and fixed, add to topValue. */
                    if( tpbrSettings['is_admin_bar'] && adminBarPosition == 'fixed') {
                        topValue += parseInt(newAdminBarHeight);
                    }

                    /* If bar shown or admin bar is not fixed, we need 
                    pushVariation calculated above. */
                    if (adminBarPosition != 'fixed') {
                        topValue += pushVariation;
                    }

                    topValue += parseInt(newHeight);

                    /* Checks that element is not from bar. */
                    if(
                        $(el).attr('id') != 'tpbr_topbar'
                        && $(el).attr('id') != 'tpbr_box'
                    ) {
                            
                        if (
                            (elSelector.indexOf('widget') === -1
                            && elSelector.indexOf('modal') === -1
                            && elSelector.indexOf('footer') === -1
                            && elSelector.indexOf('popup') === -1
                            && elSelector.indexOf('overlay') === -1
                            && elSelector.indexOf('loader') === -1
                            && elClasses.indexOf('widget') === -1
                            && elClasses.indexOf('modal') === -1
                            && elClasses.indexOf('footer') === -1
                            && elClasses.indexOf('popup') === -1
                            && elClasses.indexOf('overlay') === -1
                            && elClasses.indexOf('loader') === -1)
                            || elSelector.indexOf('header') !== -1
                            || elClasses.indexOf('header') !== -1
                        ){
                            
                            initialEls[index].clean = true;
                            $(elSelector).attr("style", "position:fixed; top: "+ topValue +"px !important;");
                            addCSSRule(sheet, elSelector, "top: "+ topValue +"px !important;");

                        }
                        
                    }

                }

            }

        }); 

        /* Resets current elements. */
        if (initialEls.length > 0) {
            for (var h=0; h<initialEls.length; h++) {
                var clone = initialEls[h].el.clone();
                clone.removeAttr('style');
                if ( 
                    clone.css('position') != 'fixed'
                    && initialEls[h].clean === true
                ) {
                    
                    // Actual cleaning.
                    initialEls[h].el.removeAttr('style');
                    
                    // Element was cleaned.
                    initialEls[h].clean = false;
                }
            }
        }

        /* First time over. */
        firstTime = 1;

    }


    // Events.
    $( window ).on('resize', debounce( initBarPosition, 50 ));
    $( window ).on('scroll', throttle( initBarPosition, 10 ));


});
})(jQuery);