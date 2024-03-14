(function ($) {

    "use strict";

    $(window).on('elementor/frontend/init', function () {

        window.elementorFrontend.hooks.addAction('frontend/element_ready/lakit-timeline-horizontal.default', function ($scope) {

            var $timeline = $scope.find('.lakit-htimeline'),
                $timelineTrack = $scope.find('.lakit-htimeline-track'),
                $items = $scope.find('.lakit-htimeline-item'),
                $arrows = $scope.find('.lakit-arrow'),
                $nextArrow = $scope.find('.next-arrow'),
                $prevArrow = $scope.find('.prev-arrow'),
                columns = $timeline.data('columns') || {},
                desktopColumns = columns.desktop || 3,
                tabletColumns = columns.tablet || desktopColumns,
                mobileColumns = columns.mobile || tabletColumns,
                firstMouseEvent = true,
                currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
                prevDeviceMode = currentDeviceMode,
                itemsCount = $scope.find('.lakit-htimeline-list--middle .lakit-htimeline-item').length,
                currentTransform = 0,
                currentPosition = 0,
                transform = {
                    desktop: 100 / desktopColumns,
                    tablet: 100 / tabletColumns,
                    mobile: 100 / mobileColumns
                },
                maxPosition = {
                    desktop: Math.max(0, (itemsCount - desktopColumns)),
                    tablet: Math.max(0, (itemsCount - tabletColumns)),
                    mobile: Math.max(0, (itemsCount - mobileColumns))
                };

            if ('ontouchstart' in window || 'ontouchend' in window) {
                $items.on('touchend.LaStudioKitsTimeLineHorizontal', function (event) {
                    var itemId = $(this).data('item-id');

                    $scope.find('.elementor-repeater-item-' + itemId).toggleClass('is-hover');
                });
            }
            else {
                $items.on('mouseenter.LaStudioKitsTimeLineHorizontal mouseleave.LaStudioKitsTimeLineHorizontal', function (event) {
                    if (firstMouseEvent && 'mouseleave' === event.type) {
                        return;
                    }
                    if (firstMouseEvent && 'mouseenter' === event.type) {
                        firstMouseEvent = false;
                    }
                    var itemId = $(this).data('item-id');
                    $scope.find('.elementor-repeater-item-' + itemId).toggleClass('is-hover');
                });
            }

            // Set Line Position
            setLinePosition();
            $(window).on('resize.LaStudioKitsTimeLineHorizontal orientationchange.LaStudioKitsTimeLineHorizontal', setLinePosition);

            function setLinePosition() {
                var $line = $scope.find('.lakit-htimeline__line'),
                    $firstPoint = $scope.find('.lakit-htimeline-item__point-content:first'),
                    $lastPoint = $scope.find('.lakit-htimeline-item__point-content:last'),
                    firstPointLeftPos = $firstPoint.position().left + parseInt($firstPoint.css('marginLeft')),
                    lastPointLeftPos = $lastPoint.position().left + parseInt($lastPoint.css('marginLeft')),
                    pointWidth = $firstPoint.outerWidth();

                $line.css({
                    'left': firstPointLeftPos + pointWidth / 2,
                    'width': lastPointLeftPos - firstPointLeftPos
                });

                $timeline.css({
                    '--lakit-htimeline-line-offset': ($line.offset().top - $timeline.offset().top) + 'px'
                });

                var $progressLine   = $scope.find( '.lakit-htimeline__line-progress' ),
                    $lastActiveItem = $scope.find( '.lakit-htimeline-list--middle .lakit-htimeline-item.is-active:last' );

                if ( $lastActiveItem[0] ) {
                    var $lastActiveItemPointWrap = $lastActiveItem.find( '.lakit-htimeline-item__point' ),
                        progressLineWidth        = $lastActiveItemPointWrap.position().left + $lastActiveItemPointWrap.outerWidth() - firstPointLeftPos - pointWidth / 2;

                    $progressLine.css( {
                        'width': progressLineWidth
                    } );
                }
            }

            // Arrows Navigation Type
            if ($nextArrow[0] && maxPosition[currentDeviceMode] === 0) {
                $nextArrow.addClass('arrow-disabled');
            }

            if ($arrows[0]) {
                $arrows.on('click.LaStudioKitsTimeLineHorizontal', function (event) {
                    var $this = $(this),
                        direction = $this.hasClass('next-arrow') ? 'next' : 'prev',
                        currentDeviceMode = elementorFrontend.getCurrentDeviceMode();

                    if ('next' === direction && currentPosition < maxPosition[currentDeviceMode]) {
                        currentTransform -= transform[currentDeviceMode];
                        currentPosition += 1;
                    }

                    if ('prev' === direction && currentPosition > 0) {
                        currentTransform += transform[currentDeviceMode];
                        currentPosition -= 1;
                    }

                    if (currentPosition > 0) {
                        $prevArrow.removeClass('arrow-disabled');
                    } else {
                        $prevArrow.addClass('arrow-disabled');
                    }

                    if (currentPosition === maxPosition[currentDeviceMode]) {
                        $nextArrow.addClass('arrow-disabled');
                    } else {
                        $nextArrow.removeClass('arrow-disabled');
                    }

                    if (currentPosition === 0) {
                        currentTransform = 0;
                    }

                    $timelineTrack.css({
                        'transform': 'translateX(' + currentTransform + '%)'
                    });

                });
            }

            setArrowPosition();
            $(window).on('resize.LaStudioKitsTimeLineHorizontal orientationchange.LaStudioKitsTimeLineHorizontal', setArrowPosition);
            $(window).on('resize.LaStudioKitsTimeLineHorizontal orientationchange.LaStudioKitsTimeLineHorizontal', timelineSliderResizeHandler);

            function setArrowPosition() {
                if (!$arrows[0]) {
                    return;
                }

                var $middleList = $scope.find('.lakit-htimeline-list--middle'),
                    middleListTopPosition = $middleList.position().top,
                    middleListHeight = $middleList.outerHeight();

                $arrows.css({
                    'top': middleListTopPosition + middleListHeight / 2
                });
            }

            function timelineSliderResizeHandler(event) {
                if (!$timeline.hasClass('lastudio-hor-timeline--arrows-nav')) {
                    return;
                }

                var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
                    resetSlider = function () {
                        $prevArrow.addClass('arrow-disabled');

                        if ($nextArrow.hasClass('arrow-disabled')) {
                            $nextArrow.removeClass('arrow-disabled');
                        }

                        if (maxPosition[currentDeviceMode] === 0) {
                            $nextArrow.addClass('arrow-disabled');
                        }

                        currentTransform = 0;
                        currentPosition = 0;

                        $timelineTrack.css({
                            'transform': 'translateX(0%)'
                        });
                    };

                switch (currentDeviceMode) {
                    case 'desktop':
                        if ('desktop' !== prevDeviceMode) {
                            resetSlider();
                            prevDeviceMode = 'desktop';
                        }
                        break;

                    case 'tablet':
                        if ('tablet' !== prevDeviceMode) {
                            resetSlider();
                            prevDeviceMode = 'tablet';
                        }
                        break;

                    case 'mobile':
                        if ('mobile' !== prevDeviceMode) {
                            resetSlider();
                            prevDeviceMode = 'mobile';
                        }
                        break;
                }
            }

        });
    });

}(jQuery));