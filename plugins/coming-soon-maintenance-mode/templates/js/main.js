/* ===================================================================
 * Count - Main JS
 *
 * ------------------------------------------------------------------- */
//jQuery.noConflict();

jQuery( document ).ready(function() {

    "use strict";
    
    var cfg = {
        scrollDuration : 800, // smoothscroll duration
        mailChimpURL   : 'https://facebook.us8.list-manage.com/subscribe/post?u=cdb7b577e41181934ed6a6a44&amp;id=e6957d85dc'   // mailchimp url
    },

    $WIN = jQuery(window);

    // Add the User Agent to the <html>
    // will be used for IE10 detection (Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0))
    var doc = document.documentElement;
    doc.setAttribute('data-useragent', navigator.userAgent);

    // svg fallback
    if (!Modernizr.svg) {
        jQuery(".home-logo img").attr("src", "images/logo.png");
    }

   /* Preloader
    * -------------------------------------------------- */
    var CmssPreloader = function() {
        jQuery("html").addClass('ss-preload');
        $WIN.on('load', function() {
            // will first fade out the loading animation 
            jQuery("#loader").fadeOut("slow", function() {
                // will fade out the whole DIV that covers the website.
                jQuery("#preloader").delay(300).fadeOut("slow");
            }); 
            // for hero content animations 
            jQuery("html").removeClass('ss-preload');
            jQuery("html").addClass('ss-loaded');
        });
    };

   /* final countdown
    * ------------------------------------------------------ */
    var CmssFinalCountdown = function() {
        var finalDate =  new Date("March 25, 2024 15:37:25").getTime();
        //-date: "Mar 25 2021",
        jQuery('.home-content__clock').countdown(finalDate)
        .on('update.countdown finish.countdown', function(event) {
            var str = '<div class=\"time days\">' +
                      '%D <span>D</span>' + 
                      '</div></div>' +
                      '<div class=\"time hours\">' +
                      '%H <span>H</span></div>' +
                      '<div class=\"time minutes\">' +
                      '%M <span>M</span></div>' +
                      '<div class=\"time seconds\">' +
                      '%S <span>S</span>';
            jQuery(this)
            .html(event.strftime(str));
        });
    };

   /* initialize
    * ------------------------------------------------------ */
    (function ssInit() {
        CmssPreloader();
        CmssFinalCountdown();
    })();

});