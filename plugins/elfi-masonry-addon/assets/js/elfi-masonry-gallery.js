;
(function($) {
    'use strict';

    $(document).ready(function() {
		
		elfi_masonry_gallery_layout();

    });

    /*-------------------------------------------
         Masonry Filter
   --------------------------------------------*/
    function elfi_masonry_gallery_layout() {
        var grid_layout = $('.grid-init');

        $.each(grid_layout, function(index, value) {
            var el = $(this);
            var parentClass = $(this).parent().attr('class');
            var $selector = $('#' + el.attr('id'));

            $($selector).imagesLoaded(function() {

                var elfiMasonry = $($selector).isotope({
                    itemSelector: '.grid-item',
                    percentPosition: true,
                    masonry: {
                        columnWidth: 0,
                        gutter: 0
                    }
                });
            });
        });


    }

})(jQuery);