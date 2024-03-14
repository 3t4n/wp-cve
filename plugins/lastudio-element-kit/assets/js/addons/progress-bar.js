(function ($) {

    "use strict";

    $(window).on('elementor/frontend/init', function () {

        window.elementorFrontend.hooks.addAction('frontend/element_ready/lakit-progress-bar.default', function ($scope) {
            var $target = $scope.find('.lakit-progress-bar'),
                percent = $target.data('percent'),
                type = $target.data('type'),
                deltaPercent = percent * 0.01;

            elementorFrontend.waypoint($target, function (direction) {
                var $this = $(this),
                    animeObject = {charged: 0},
                    $statusBar = $('.lakit-progress-bar__status-bar', $this),
                    $percent = $('.lakit-progress-bar__percent-value', $this),
                    animeProgress,
                    animePercent;

                if ('type-7' == type) {
                    $statusBar.css({
                        'height': percent + '%'
                    });
                } else {
                    $statusBar.css({
                        'width': percent + '%'
                    });
                }
                animePercent = anime({
                    targets: animeObject,
                    charged: percent,
                    round: 1,
                    duration: 1000,
                    easing: 'easeInOutQuad',
                    update: function () {
                        $percent.html(animeObject.charged);
                    }
                });

            });
        });
    });

}(jQuery));