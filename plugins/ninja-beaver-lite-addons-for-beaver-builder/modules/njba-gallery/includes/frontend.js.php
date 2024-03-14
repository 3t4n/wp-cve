(function ($) {

	<?php if($settings->layout === 'masonary'):?>

    var $grid = $('.fl-node-<?php echo $id; ?> .njba-gallery-masonary-section').imagesLoaded(function () {
        $grid.isotope({

            itemSelector: '.njba-masonary-gallery',
            columnWidth: '.njba-grid-sizer'

        });
    });
	<?php endif;?>
	<?php if($settings->click_action === 'lightbox') : ?>

    $('.njba-gallery-wrapper').each(function () { // the containers for all your galleries
        $(this).magnificPopup({
            delegate: 'a', // the selector for gallery item
            type: 'image',
            gallery: {
                enabled: true
            },
            removalDelay: 900,
            mainClass: 'mfp-fade'
        });
    });
	<?php

	endif;
	?>

})(jQuery);
	
