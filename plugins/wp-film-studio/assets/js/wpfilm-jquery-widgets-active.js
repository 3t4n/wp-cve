(function($){
"use strict";

/*------ Related  Movie ------*/
    $('.related-trailer-active').slick({
      slidesToShow: 3,
      arrows:true,
      dots: false,
      prevArrow: '<div class="btn-prev"><i class="icofont icofont-thin-left"></i></div>',
      nextArrow: '<div><i class="icofont icofont-thin-right"></i></div>',
       responsive: [
                {
                  breakpoint: 768,
                  settings: {
                    slidesToShow: 2
                  }
                },
                {
                  breakpoint: 575,
                  settings: {
                    slidesToShow: 1
                  }
                }
              ]
    });

	/*------ Magnific Popup For Video ------*/
	$('.popup-youtube').magnificPopup({
		type: 'iframe'
	});
	


})(jQuery);