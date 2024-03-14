(function ($) {

    "use strict";

    /**
     * LaStudio Timeline Class
     *
     * @return {void}
     */
    window.LaStudioKitsTimeLineVertical = function ($element) {
        var $viewport = $(window),
            self = this,
            $line = $element.find('.lakit-vtimeline__line'),
            $progress = $line.find('.lakit-vtimeline__line-progress'),
            $cards = $element.find('.lakit-vtimeline-item'),
            $points = $element.find('.lakit-vtimeline-item__point'),

            currentScrollTop = $viewport.scrollTop(),
            lastScrollTop = -1,
            currentWindowHeight = $(window).height(),
            currentViewportHeight = $viewport.outerHeight(),
            lastWindowHeight = -1,
            requestAnimationId = null,
            flag = false;

        self.debounce = function (threshold, callback) {
            var timeout;

            return function debounced($event) {
                function delayed() {
                    callback.call(this, $event);
                    timeout = null;
                }

                if (timeout) {
                    clearTimeout(timeout);
                }

                timeout = setTimeout(delayed, threshold);
            };
        };

        self.onScroll = function () {
            currentScrollTop = $viewport.scrollTop();

            self.updateFrame();
            self.animateCards();
        };

        self.onResize = function () {
            currentScrollTop = $viewport.scrollTop();
            currentWindowHeight = $viewport.height();

            self.updateFrame();
        };

        self.updateWindow = function () {
            flag = false;

            $line.css({
                'top': $cards.first().find($points).offset().top - $cards.first().offset().top,
                'bottom': ($element.offset().top + $element.outerHeight()) - $cards.last().find($points).offset().top
            });

            if ((lastScrollTop !== currentScrollTop)) {
                lastScrollTop = currentScrollTop;
                lastWindowHeight = currentWindowHeight;

                self.updateProgress();
            }
        };

        self.updateProgress = function () {
            var progressFinishPosition = $cards.last().find($points).offset().top,
                progressHeight = (currentScrollTop - $progress.offset().top) + (currentViewportHeight / 2);

            if (progressFinishPosition <= (currentScrollTop + currentViewportHeight / 2)) {
                progressHeight = progressFinishPosition - $progress.offset().top;
            }

            $progress.css({
                'height': progressHeight + 'px'
            });

            $cards.each(function () {
                if ($(this).find($points).offset().top < (currentScrollTop + currentViewportHeight * 0.5)) {
                    $(this).addClass('is--active');
                } else {
                    $(this).removeClass('is--active');
                }
            });
        };

        self.updateFrame = function () {
            if (!flag) {
                requestAnimationId = requestAnimationFrame(self.updateWindow);
            }
            flag = true;
        };

        self.animateCards = function () {
            $cards.each(function () {
                if ($(this).offset().top <= currentScrollTop + currentViewportHeight * 0.9 && $(this).hasClass('lakit-vtimeline-item--animated')) {
                    $(this).addClass('is--show');
                }
            });
        };

        self.init = function () {
            $(document).ready(self.onScroll);
            $(window).on('scroll.LaStudioKitsTimeLineVertical', self.onScroll);
            $(window).on('resize.LaStudioKitsTimeLineVertical orientationchange.LaStudioKitsTimeLineVertical', self.debounce(50, self.onResize));
        };
    }

    $(window).on('elementor/frontend/init', function () {

        window.elementorFrontend.hooks.addAction('frontend/element_ready/lakit-timeline-vertical.default', function ($scope) {

            var $target = $scope.find('.lakit-vtimeline'),
                instance = null;

            if (!$target.length) {
                return;
            }

            instance = new LaStudioKitsTimeLineVertical($target);
            instance.init();

        });
    });

}(jQuery));