( function( $, elementor ) {

    "use strict";

    $( window ).on( 'elementor/frontend/init', function (){

        function lakit_tabs( $scope ){

            var $target = $('.lakit-tabs', $scope).first(),
                $controlWrapper = $('>.lakit-tabs__control-wrapper', $target).first(),
                $contentWrapper = $('>.lakit-tabs__content-wrapper', $target).first(),
                $controlList = $('.lakit-tabs__control', $controlWrapper),
                $contentList = $('>.lakit-tabs__content', $contentWrapper),
                settings = $target.data('settings') || {},
                toggleEvents = 'mouseenter mouseleave',
                scrollOffset,
                autoSwitchInterval = null,
                curentHash = window.location.hash || false,
                tabsArray = curentHash ? curentHash.replace('#', '').split('&') : false,
                _clickState = false;

            let isInPopup = $($scope).closest('.elementor-location-popup').length;

            var $ddControls = $('.lakit-tabs__controls', $controlWrapper),
                $ddControlsTmp = $('.lakit-tabs__controls__tmp', $controlWrapper);

            if ('click' === settings['event']) {
                addClickEvent();
            }
            else {
                addMouseEvent();
            }
            if($ddControls.length){
                switchTab(settings['activeIndex']);
            }

            if (settings['autoSwitch']) {

                var startIndex = settings['activeIndex'],
                    currentIndex = startIndex,
                    controlListLength = $controlList.length;

                autoSwitchInterval = setInterval(function () {

                    if (currentIndex < controlListLength - 1) {
                        currentIndex++;
                    } else {
                        currentIndex = 0;
                    }

                    switchTab(currentIndex);

                }, +settings['autoSwitchDelay']);
            }

            $controlList.each(function () {
                $(this).attr('data-tab_name', $(this).text().toString().toLowerCase()
                    .replace(/\s+/g, '-')           // Replace spaces with -
                    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                    .replace(/^-+/, '')             // Trim - from start of text
                    .replace(/-+$/, '') + '_' + $scope.attr('data-id'));
            });

            $(window).on('resize.lakitTabs orientationchange.lakitTabs', function () {
                $contentWrapper.css({'height': 'auto'});
            });

            $(window).on('hashchange', function () {
                var c_hash = window.location.hash.replace('#', '').toLowerCase()
                    .replace(/\s+/g, '-')           // Replace spaces with -
                    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                    .replace(/^-+/, '')             // Trim - from start of text
                    .replace(/-+$/, '');
                if(c_hash !== ''){
                    var $c_item = $('.lakit-tabs__control[data-tab_name="'+c_hash+'"]');
                    if($c_item.length){
                        var _href = window.location.href.replace(window.location.hash, '');
                        history.pushState(null,null,_href);
                        switchTab($c_item.data('tab') - 1);
                    }
                }
            });

            function addClickEvent() {
                $controlList.on('click.lakitTabs', function () {
                    var $this = $(this),
                        tabId = +$this.data('tab') - 1;

                    clearInterval(autoSwitchInterval);
                    switchTab(tabId);
                });
            }

            function addMouseEvent() {
                if ('ontouchend' in window || 'ontouchstart' in window) {
                    $controlList.on('touchstart', function (event) {
                        scrollOffset = $(window).scrollTop();
                    });

                    $controlList.on('touchend', function (event) {
                        var $this = $(this),
                            tabId = +$this.data('tab') - 1;

                        if (scrollOffset !== $(window).scrollTop()) {
                            return false;
                        }

                        clearInterval(autoSwitchInterval);
                        switchTab(tabId);
                    });

                } else {
                    $controlList.on('mouseenter', function (event) {
                        var $this = $(this),
                            tabId = +$this.data('tab') - 1;

                        clearInterval(autoSwitchInterval);
                        switchTab(tabId);
                    });
                }
            }

            function switchTab(curentIndex) {
                var $activeControl = $controlList.eq(curentIndex),
                    $activeContent = $contentList.eq(curentIndex),
                    activeContentHeight = 'auto',
                    timer;

                $contentWrapper.css({'height': $contentWrapper.outerHeight(true)});

                $controlList.removeClass('active-tab');
                $activeControl.addClass('active-tab');

                if($ddControlsTmp.length){
                    $ddControlsTmp.find('.lakit-tabs__controls__text').html($activeControl.find('.lakit-tabs__label-text').text());
                }

                if ('click' === settings['event'] && !isInPopup) {
                    if(_clickState){
                        $('html,body').animate({
                            scrollTop: $target.offset().top - 200
                        }, 300);
                    }
                }

                $controlWrapper.removeClass('open');

                $contentList.removeClass('active-content');
                $activeContent.addClass('active-content');
                activeContentHeight = $activeContent.outerHeight(true);
                activeContentHeight += parseInt($contentWrapper.css('border-top-width')) + parseInt($contentWrapper.css('border-bottom-width'));

                $(document).trigger('lastudio-kit/active-tabs', [$activeContent]);

                $contentWrapper.css({'height': activeContentHeight});

                if($('.slick-slider', $activeContent).length > 0){
                    try{
                        $('.slick-slider', $activeContent).slick('setPosition');
                    }
                    catch (e) { }
                }

                if($('.swiper-container', $activeContent).length > 0){
                    try{
                        var _swiper = $('.swiper-container', $activeContent).data('swiper');
                        _swiper.resize.resizeHandler();
                    }
                    catch (e) {  }
                }

                $('.lakit-masonry-wrapper', $activeContent).trigger('resize');

                _clickState = true;

                if (timer) {
                    clearTimeout(timer);
                }
                timer = setTimeout(function () {
                    $contentWrapper.css({'height': 'auto'});
                }, 300);
            }

            // Hash Watch Handler
            if (tabsArray) {

                $controlList.each(function (index) {
                    var $this = $(this),
                        id = $this.attr('id'),
                        tabIndex = index;

                    tabsArray.forEach(function (itemHash, i) {
                        if (itemHash === id) {
                            switchTab(tabIndex);
                        }
                    });

                });
            }

            $ddControlsTmp.on('click', function (e){
                e.preventDefault();
                if ($controlWrapper.hasClass('open')) {
                    $controlWrapper.removeClass('open');
                }
                else {
                    $controlWrapper.addClass('open');
                }
            });

            $(document).on('click', function (e){
                if( $(e.target).hasClass('lakit-tabs__controls__tmp') || $(e.target).closest('.lakit-tabs__controls__tmp').length ) {
                    return;
                }
                else{
                    if($ddControlsTmp.length){
                        $controlWrapper.removeClass('open');
                    }
                }
            });

            $('.lakit-tabs__control-wrapper-mobile a', $target).on('click.lakitTabs', function (e) {
                e.preventDefault();
                if ($controlWrapper.hasClass('open')) {
                    $controlWrapper.removeClass('open');
                }
                else {
                    $controlWrapper.addClass('open');
                }
            })
        }

        window.elementorFrontend.hooks.addAction( 'frontend/element_ready/lakit-tabs.default', lakit_tabs );
    } );

}( jQuery, window.elementorFrontend ) );