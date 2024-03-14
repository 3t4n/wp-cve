jQuery(function () {
    jQuery('.navbar-toggle').click(function () {
        jQuery('.navbar-nav').toggleClass('slide-in');
        jQuery('.side-body').toggleClass('body-slide-in');
        jQuery('#search').removeClass('in').addClass('collapse').slideUp(200);

        /// uncomment code for absolute positioning tweek see top comment in css
        //$('.absolute-wrapper').toggleClass('slide-in');
        
    });
   
   // Remove menu for searching
  jQuery('#search-trigger').click(function () {
        jQuery('.navbar-nav').removeClass('slide-in');
        jQuery('.side-body').removeClass('body-slide-in');

        /// uncomment code for absolute positioning tweek see top comment in css
        //$('.absolute-wrapper').removeClass('slide-in');

    });
});