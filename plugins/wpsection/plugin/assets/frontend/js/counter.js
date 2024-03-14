;(function ($, elementor) {

    'use strict';

    let widgetCounter = function ($scope, $) {

        let $countNumber = $scope.find('.wpsection-counter .counter-number'),
            $counter_id = '#' + $countNumber.attr('id');

        if (!$countNumber.length) {
            return;
        }

        $($counter_id).counterUp({
            delay: 10,
            time: 2000
        });

    };

    jQuery(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/wpsection-counter.default', widgetCounter);
    });

}(jQuery, window.elementorFrontend));