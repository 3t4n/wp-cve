(function() {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

    jQuery(document).ready(function($) {


        if($('.pp-portfolio-img').length) {
            $(".pp-portfolio-img").lazyload();
        }

        if ( $( '#lgx-portfolio').length ) {

            var dataAttr = $('#lgx-grid-wrapper').data();

            var $grid = $('#lgx-grid-wrapper').isotope({
                // options
                itemSelector: '.lgx-grid-item',
                layoutMode: dataAttr.layout //fitRows, cellsByRow,vertical,packery, masonryHorizontal,, fitColumns, cellsByColumn, horiz
            });

            $(window).load(function () {
                    // show all items
                    $grid.isotope({filter: '*'});
                }
            );

            // filter items on button click
            $('#pp-filter').on('click', 'a.pp-filter-item', function () {
                var filterValue = $(this).attr('data-filter');
                $grid.isotope({filter: filterValue});
            });

            $('#pp-filter').on('click', 'a.pp-filter-item', function (e) {
                e.preventDefault();
                var link = $(this);

                $("#pp-filter").find(".active").removeClass("active");
                link.parent().addClass("active");
                return false;
            });
        }//if

    });


})();
