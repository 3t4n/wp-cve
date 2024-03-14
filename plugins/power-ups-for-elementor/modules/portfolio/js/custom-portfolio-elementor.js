//Use Strict Mode
(function ($) {
    "use strict";

    var $container = $('.elpt-portfolio-content');
    var $grid = $('.elpt-portfolio-content').isotope({
        itemSelector: '.portfolio-item-wrapper'
    });

    function startElemenfolio(){
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
    }

    //Begin - Window Load
    $(window).load(function () {    
        setTimeout(startElemenfolio, 800);   
    });

    $(document).on('mouseup', function(){
       setTimeout(startElemenfolio, 1200);   
    });

    //End - Use Strict mode
})(jQuery);