(function($) {
   
    /*------------------------------------
        ISOTOPE FILTER ACTIVATION
    -------------------------------------*/
    var Element_Ready_Masonry_Filter_Script = function($scope, $) {

        var grid_elem = $scope.find('.element-ready-filter-activation').eq(0);
        var elem_id = grid_elem.attr('id');
        var grid_activation_id = $('#' + elem_id);
        var settings = grid_elem.data('settings');


        if ('slider' != settings['gallery_type'] || 'genaral' != settings['gallery_type']) {

            var gallery_id = settings['gallery_id'] ? settings['gallery_id'] : 1234;
            var item_selector = '.element__ready__grid__item__' + gallery_id;
            var filter_menu = $('#filter__menu__' + gallery_id + ' li');
            var active_menu_category = settings['active_menu_category'] ? settings['active_menu_category'] : '';


            var layout_mode = settings['layout_mode'];
            if ('masonry' === layout_mode) {
                var layoutMode = 'masonry';
                var layoutOption = {
                    columnWidth: item_selector,
                    gutter: 0,
                };
                var option_cat = layoutMode;
            } else if ('fitRows' === layout_mode) {
                var layoutMode = 'fitRows';
                var layoutOption = {
                    gutter: 0,
                };
                var option_cat = layoutMode;
            } else if ('masonryHorizontal' === layout_mode) {

                var layoutMode = 'masonryHorizontal';
                var layoutOption = {
                    rowHeight: item_selector,
                };
                var option_cat = layoutMode;
            } else if ('fitColumns' === layout_mode) {
                var layoutMode = 'fitColumns';
            } else if ('cellsByColumn' === layout_mode) {
                var layoutMode = 'cellsByColumn';
                var layoutOption = {
                    columnWidth: item_selector,
                    rowHeight: item_selector,
                };
                var option_cat = layoutMode;
            } else {
                var layoutMode = 'fitRows';
                var layoutOption = {
                    gutter: 0,
                };
                var option_cat = layoutMode;
            }

            if (active_menu_category != '') {
                var active_filter = '.' + active_menu_category.toLowerCase();
                filter_menu.removeClass('active');
                $('.filter__menu li[data-filter="' + active_filter + '"]').addClass('active');
            } else {
                var active_filter = '*';
            }

            /*--------------------------------
                ISOTOPE ACTIVE ELEMENT
            ---------------------------------*/

            $(window).on('load', function() {
                if (typeof imagesLoaded === 'function') {
                    imagesLoaded(grid_activation_id, function() {
                        setTimeout(function() {
                            grid_activation_id.isotope({
                                itemSelector: item_selector,
                                resizesContainer: true,
                                filter: active_filter,
                                layoutMode: layoutMode,
                                option_cat: layoutOption,
                            });
                        }, 500);
                    });
                };
            });

            /* --------------------------------
                FILTER MENU SET ACTIVE CLASS 
            ----------------------------------*/
            $(window).on('load', function() {
                filter_menu.on('click', function(event) {
                    $(this).siblings('.active').removeClass('active');
                    $(this).addClass('active');
                    event.preventDefault();
                });
            });

            /*------------------------------
                FILTERING ACTIVE
            -------------------------------- */
            $(window).on('load', function() {
                filter_menu.on('click', function() {
                    var filterValue = $(this).attr('data-filter');
                    grid_activation_id.isotope({
                        filter: filterValue,
                        animationOptions: {
                            duration: 750,
                            easing: 'linear',
                            queue: false,
                        }
                    });
                    return false;
                });
            });
        }
    }
    
    $(window).on('elementor/frontend/init', function() {
         
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Multi_Gallery_Widget.default', Element_Ready_Masonry_Filter_Script);
        /* Post */
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Portfolio.default', Element_Ready_Masonry_Filter_Script);

        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Edd_Products_Widget.default', Element_Ready_Masonry_Filter_Script);

    });

})(jQuery);