//Use Strict Mode
(function ($) {
    "use strict";

    //Begin - Window Load
     function testimonialsCarousel(){

        $(".elpug-testimonials-carousel").owlCarousel({
            items: 1,
            nav: false,        	
        });

    }

    testimonialsCarousel();

    //Begin - Window Load
    $(window).load(function () {   
        setTimeout(testimonialsCarousel, 500);      
    });

    //On MouseUp
    $(document).on('mouseup', function(){
       setTimeout(testimonialsCarousel, 1200);   
    });

    //End - Use Strict mode
})(jQuery);