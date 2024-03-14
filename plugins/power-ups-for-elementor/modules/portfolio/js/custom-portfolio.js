//Use Strict Mode
(function ($) {
    "use strict";

    //Begin - Window Load
    $(window).load(function () {



    	//Project Filter
        var $container = $('.elpt-portfolio-content');
       	var $grid = $('.elpt-portfolio-content').isotope({
            itemSelector: '.portfolio-item-wrapper'
        });

       	$('.elpt-portfolio-filter').on('click', 'button', function () {   
            $('.elpt-portfolio-filter button').removeClass('item-active');
            $(this).addClass('item-active');
            var filterValue = $(this).attr('data-filter');
            $grid.isotope({
                filter: filterValue
            });
        });

        //Lightbox
        $('.elpt-portfolio-lightbox').simpleLightbox({
            captions: true,
        });       


    });

    //End - Use Strict mode
})(jQuery);