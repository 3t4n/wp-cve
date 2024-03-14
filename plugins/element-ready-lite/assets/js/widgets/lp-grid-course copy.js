(function($) {

    var LP_Grid_Course = function($scope, $) {


        var layout = $scope.find('.widget-layout').data('layout');
        var widget_id = '.shafull-container-' + $scope.data('id');
        var shaf_item = '.shaf-item-' + $scope.data('id');
        var shaf_filter = '.shaf-filter-' + $scope.data('id') + ' li';

        if (layout != 'undefined' && layout == 'tabs') {

            $(window).on('load', function() {

                if ($(widget_id).length > 0) {
                    let $suffle_grid;

                    $suffle_grid = $(widget_id);

                    $suffle_grid.shuffle({
                        itemSelector: shaf_item,
                        sizer: '.shaf-sizer'
                    });

                    /* reshuffle when user clicks a filter item */
                    $(shaf_filter).on('click', function() {
                        // set active class
                        $(shaf_filter).removeClass('active');
                        $(this).addClass('active');
                        // get group name from clicked item
                        var groupName = $(this).attr('data-group');
                        // reshuffle grid
                        $suffle_grid.shuffle('shuffle', groupName);
                    });
                }

            });
        }


    };

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/element-ready-grid-course.default', LP_Grid_Course);
    });
})(jQuery);