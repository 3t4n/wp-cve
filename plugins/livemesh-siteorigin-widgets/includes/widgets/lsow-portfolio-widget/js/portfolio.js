jQuery(function ($) {

    if ($().isotope === undefined) {
        return;
    }

    $('.lsow-portfolio-wrap').each(function () {

        var container = $(this).find('.lsow-portfolio');
        if (container.length === 0) {
            return; // no items to filter or load and hence don't continue
        }

        // layout Isotope after all images have loaded
        var html_content = $(this).find('.js-isotope');

        var options = html_content.data('isotope-options');

        html_content.imagesLoaded(function () {

            html_content.isotope({
                itemSelector: options.itemSelector,
                layoutMode: options.layoutMode,
                transitionDuration: '0.8s'
            });
        });

        /* -------------- Taxonomy Filter --------------- */

        $(this).find('.lsow-taxonomy-filter .lsow-filter-item a').on('click', function (e) {
            e.preventDefault();

            var selector = $(this).attr('data-value');
            container.isotope({filter: selector});
            $(this).closest('.lsow-taxonomy-filter').children().removeClass('lsow-active');
            $(this).closest('.lsow-filter-item').addClass('lsow-active');
            return false;
        });
    });

});