/**
 * Stax WordPress Header Builder plugin
 * Javascript logic
 */


// requestAnimationFrame polyfill by Erik Möller. fixes from Paul Irish and Tino Zijdel
!function() {
    'use strict';
    for (var n = 0, i = ['ms', 'moz', 'webkit', 'o'], e = 0; e < i.length &&
    !window.requestAnimationFrame; ++e) window.requestAnimationFrame = window[i[e] +
    'RequestAnimationFrame'], window.cancelAnimationFrame = window[i[e] + 'CancelAnimationFrame'] ||
        window[i[e] + 'CancelRequestAnimationFrame'];
    window.requestAnimationFrame || (window.requestAnimationFrame = function(i) {
        var e = (new Date).getTime(), a = Math.max(0, 16 - (e - n)), o = window.setTimeout(function() {
            i(e + a);
        }, a);
        return n = e + a, o;
    }), window.cancelAnimationFrame || (window.cancelAnimationFrame = function(n) {
        clearTimeout(n);
    });
}();

/* Debouncedresize */
!function(e) {
    var n, t, i = e.event;
    n = i.special.debouncedresize = {
        setup: function() {
            e(this).on('resize', n.handler);
        }, teardown: function() {
            e(this).off('resize', n.handler);
        }, handler: function(e) {
            var o = this, r = arguments, s = function() {
                e.type = 'debouncedresize', i.dispatch.apply(o, r);
            };
            t && clearTimeout(t), t = setTimeout(s, n.threshold);
        }, threshold: 400,
    };
}(jQuery);

/*	jQuery.flexMenu 1.5
 https://github.com/352Media/flexMenu
 Description: If a list is too long for all items to fit on one line, display a popup menu instead.
 Dependencies: jQuery, Modernizr (optional). Without Modernizr, the menu can only be shown on click (not hover). */

(function(factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function($) {
    var windowWidth = $(window).width(); // Store the window width
    var windowHeight = $(window).height(); // Store the window height
    var flexObjects = [], // Array of all flexMenu objects
        resizeTimeout;

    // When the page is resized, adjust the flexMenus.
    function adjustFlexMenu() {
        if ($(window).width() !== windowWidth || $(window).height() !== windowHeight) {
            $(flexObjects).each(function() {
                $(this).flexMenu({
                    'undo': true,
                }).flexMenu(this.options);
            });
            windowWidth = $(window).width(); // Store the window width if it changed
            windowHeight = $(window).height(); // Store the window height if it changed
        }
    }

    function collapseAllExcept($menuToAvoid) {
        var $activeMenus,
            $menusToCollapse;
        $activeMenus = $('li.flexMenu-viewMore.active');
        $menusToCollapse = $activeMenus.not($menuToAvoid);
        $menusToCollapse.removeClass('active').find('> ul').hide();
    }

    $(window).resize(function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            adjustFlexMenu();
        }, 200);
    });
    $.fn.flexMenu = function(options) {
        var checkFlexObject,
            s = $.extend({
                'threshold': 2, // [integer] If there are this many items or fewer in the list, we will not display a "View More" link and will instead let the list break to the next line. This is useful in cases where adding a "view more" link would actually cause more things to break  to the next line.
                'cutoff': 2, // [integer] If there is space for this many or fewer items outside our "more" popup, just move everything into the more menu. In that case, also use linkTextAll and linkTitleAll instead of linkText and linkTitle. To disable this feature, just set this value to 0.
                'linkText': 'More', // [string] What text should we display on the "view more" link?
                'linkTitle': 'View More', // [string] What should the title of the "view more" button be?
                'linkTextAll': 'Menu', // [string] If we hit the cutoff, what text should we display on the "view more" link?
                'linkTitleAll': 'Open/Close Menu', // [string] If we hit the cutoff, what should the title of the "view more" button be?
                'shouldApply': function() {
                    return true;
                }, // [function] Function called before applying flexMenu. If it returns false, it will not be applied.
                'showOnHover': true, // [boolean] Should we we show the menu on hover? If not, we'll require a click. If we're on a touch device - or if Modernizr is not available - we'll ignore this setting and only show the menu on click. The reason for this is that touch devices emulate hover events in unpredictable ways, causing some taps to do nothing.
                'popupAbsolute': true, // [boolean] Should we absolutely position the popup? Usually this is a good idea. That way, the popup can appear over other content and spill outside a parent that has overflow: hidden set. If you want to do something different from this in CSS, just set this option to false.
                'popupClass': '', // [string] If this is set, this class will be added to the popup
                'undo': false, // [boolean] Move the list items back to where they were before, and remove the "View More" link.
            }, options);
        this.options = s; // Set options on object
        checkFlexObject = $.inArray(this, flexObjects); // Checks if this object is already in the flexObjects array
        if (checkFlexObject >= 0) {
            flexObjects.splice(checkFlexObject, 1); // Remove this object if found
        } else {
            flexObjects.push(this); // Add this object to the flexObjects array
        }
        return this.each(function() {
            var $this = $(this),
                $items = $this.find('> li'),
                $firstItem = $items.first(),
                $lastItem = $items.last(),
                numItems = $items.length,
                firstItemTop = $firstItem.length > 0 ? Math.floor($firstItem.offset().top) : 0,
                firstItemHeight = Math.floor($firstItem.outerHeight(true)),
                $lastChild,
                keepLooking,
                $moreItem,
                $moreLink,
                numToRemove,
                allInPopup = false,
                $menu,
                i;

            function needsMenu($itemOfInterest) {
                var result = (Math.ceil($itemOfInterest.offset().top) >= (firstItemTop + firstItemHeight))
                    ? true
                    : false;
                // Values may be calculated from em and give us something other than round numbers. Browsers may round these inconsistently. So, let's round numbers to make it easier to trigger flexMenu.
                return result;
            }

            if (needsMenu($lastItem) && numItems > s.threshold && !s.undo && $this.is(':visible')
                && (s.shouldApply())) {
                var $popup = $('<ul class="flexMenu-popup submenu"></ul>');
                // Add class if popupClass option is set
                $popup.addClass(s.popupClass);
                // Move all list items after the first to this new popup ul
                for (i = numItems; i > 1; i--) {
                    // Find all of the list items that have been pushed below the first item. Put those items into the popup menu. Put one additional item into the popup menu to cover situations where the last item is shorter than the "more" text.
                    $lastChild = $this.find('> li:last-child');
                    keepLooking = (needsMenu($lastChild));
                    // If there only a few items left in the navigation bar, move them all to the popup menu.
                    if ((i - 1) <= s.cutoff) { // We've removed the ith item, so i - 1 gives us the number of items remaining.
                        $($this.children().get().reverse()).appendTo($popup);
                        allInPopup = true;
                        break;
                    }
                    if (!keepLooking) {
                        break;
                    } else {
                        $lastChild.appendTo($popup);
                    }
                }
                if (allInPopup) {
                    $this.append(
                        '<li class="flexMenu-viewMore flexMenu-allInPopup has-submenu"><a class="item" href="#" title="' +
                        s.linkTitleAll + '">' + s.linkTextAll + '</a></li>');
                } else {
                    $this.append(
                        '<li class="flexMenu-viewMore has-submenu"><a class="item" href="#" title="' + s.linkTitle +
                        '">' + s.linkText + '</a></li>');
                }
                $moreItem = $this.find('> li.flexMenu-viewMore');
                /// Check to see whether the more link has been pushed down. This might happen if the link immediately before it is especially wide.
                if (needsMenu($moreItem)) {
                    $this.find('> li:nth-last-child(2)').appendTo($popup);
                }
                // Our popup menu is currently in reverse order. Let's fix that.
                $popup.children().each(function(i, li) {
                    $popup.prepend(li);
                });
                $moreItem.append($popup);
                $moreLink = $this.find('> li.flexMenu-viewMore > a');
                $moreLink.bind('tap', function(e) {
                    // Collapsing any other open flexMenu
                    collapseAllExcept($moreItem);
                    //Open and Set active the one being interacted with.
                    $popup.toggle();
                    $moreItem.toggleClass('active');
                    e.preventDefault();
                    return false;
                });
                if (s.showOnHover && (typeof Modernizr !== 'undefined') && !Modernizr.touch) { // If requireClick is false AND touch is unsupported, then show the menu on hover. If Modernizr is not available, assume that touch is unsupported. Through the magic of lazy evaluation, we can check for Modernizr and start using it in the same if statement. Reversing the order of these variables would produce an error.
                    $moreItem.hover(
                        function() {
                            $popup.show();
                            $(this).addClass('active');
                        },
                        function() {
                            $popup.hide();
                            $(this).removeClass('active');
                        });
                }
            } else if (s.undo && $this.find('ul.flexMenu-popup')) {
                $menu = $this.find('ul.flexMenu-popup');
                numToRemove = $menu.find('li').length;
                for (i = 1; i <= numToRemove; i++) {
                    $menu.find('> li:first-child').appendTo($this);
                }
                $menu.remove();
                $this.find('> li.flexMenu-viewMore').remove();
            }
        });
    };
}));

var SQ = SQ || {};
(function($) {

    // USE STRICT
    'use strict';

    SQ.hb = {
        /* variables for Header Builder */
        prevScroll: 0,
        headerSection: $('.header-section'),
        adminBar: '#wpadminbar',
        stickyWrapperClass: 'hb-sticky-wrapper',
        zoneClass: '.stax-zone',

        init: function() {

            setTimeout(function() {
                SQ.hb.headerSectionInit();
                SQ.hb.headerSectionCalc();
            }, 100);

            /* Register events */

            /* Search input focus */
            $(document).on('focus', '.sq-search .input-text', function(e) {
                $(e.target).parent().addClass('is-focused');
            });
            $(document).on('focusout', '.sq-search .input-text', function(e) {
                $(e.target).parent().removeClass('is-focused');
            });

            $(window).on('header-height-changed', function() {
                SQ.hb.headerSectionInit();
                SQ.hb.headerSectionCalc(true);
            });

            $(window).on('stax-logo', function() {
                SQ.hb.checkImageState();
            });

            $(window).on('stax-menu-ajax', function() {
                setTimeout(function() {
                    SQ.hb.trimLongMenu();
                }, 200);
            });

            $(window).on('after-header-init', function() {
                SQ.hb.checkHeaderOverflow();
            });

            SQ.hb.scrollTo();

            /* Elements */
            SQ.hb.initAccordion();
            SQ.hb.initTabs();
            SQ.hb.initSideMenu();
            SQ.hb.initMegaMenu();

            $(window).on('stax-accordion', function() {
                SQ.hb.initAccordion();
            });
            $(window).on('stax-tabs', function() {
                SQ.hb.initTabs();
            });

            SQ.hb.initHamburgerMenu();

            SQ.hb.initSearchSubmit();

            // Listen for the event.
            var elem = document.querySelector('.stax-loaded');
            if (elem !== null) {
                elem.addEventListener('staxReplace', function(e) {
                    SQ.hb.headerSectionInit();
                    SQ.hb.headerSectionCalc(true);
                }, false);
            }
        },

        onLoad: function() {
            window.addEventListener('el-open', function(evt) {
                if (evt.detail && evt.detail.type === 'sidemenu') {
                    const el = $('.element-' + evt.detail.uuid);

                    if (el.length > 0) {
                        el.find('.stax-sidemenu-wrapper').addClass('sidemenu-open');
                        el.closest('.hb-element').css('zIndex', 103);
                        el.closest('.sq-column').css('zIndex', 103);
                    }
                } else if (evt.detail && evt.detail.type === 'megamenu') {
                    const el = $('.element-' + evt.detail.uuid);

                    if (el.length > 0) {
                        const container = el.find('.stax-megamenu-wrapper');
                        const rowSizes = el.closest('.sq-row').get(0).getBoundingClientRect();

                        const sizes = el.get(0).getBoundingClientRect();
                        container.css('left', -(sizes.left - rowSizes.left));

                        container.css('width', rowSizes.width);
                        container.addClass('megamenu-open').addClass('megamenu-show-close');
                    }
                }
            }, false);
        },

        onResize: function() {
            SQ.hb.headerSectionInit();
            SQ.hb.headerSectionCalc(true);

        },

        onClassicResize: function() {

        },

        onScroll: function() {
            if (typeof staxResponsive === 'undefined') {
                SQ.hb.headerSectionInit();
            }

            SQ.hb.headerSectionCalc();
        },

        checkHeaderOverflow: function() {
            var header = $('.header-section');

            if (!header.length) {
                return;
            }

            var windowWidth = $(window).width();
            header.each(function() {
                var elementsWidth = 0;
                var elements = $(this).find('> .header-content > .sq-row > .header-item > .item');

                elements.each(function() {
                    elementsWidth += $(this).width();
                });

                if (elementsWidth > windowWidth) {
                    $(this).addClass('header-overflow');
                } else {
                    $(this).removeClass('header-overflow');
                }
            });
        },

        checkImageState: function(header) {

            if (header === undefined) {
                header = SQ.hb.headerSection;
            }
            var image = header.find('.sq-logo').find('img');

            image.each(function() {
                var parentHeader = $(this).closest('.header-section');

                //save original image
                if (!$(this).attr('data-image-original')) {
                    $(this).attr('data-image-original', $(this).attr('src'));
                }

                //transparent || resize
                if ($(this).attr('data-image-transparent') && parentHeader.hasClass('is-transparent')) {
                    $(this).attr('src', $(this).attr('data-image-transparent'));
                } else if ($(this).attr('data-image-resized') && parentHeader.hasClass('is-resized')) {
                    $(this).attr('src', $(this).attr('data-image-resized'));
                } else {
                    $(this).attr('src', $(this).attr('data-image-original'));
                }
            });

        },

        headerSectionInit: function() {

            SQ.hb.headerSection = $('.header-section');

            SQ.hb.trimLongMenu(); // Need to be onLoad

            SQ.hb.checkImageState();
            SQ.hb.checkHeaderOverflow();

            SQ.hb.headerSection.off('is-resized.stax');
            SQ.hb.headerSection.off('is-transparent.stax');

            SQ.hb.headerSection.on('is-resized.stax', function() {
                SQ.hb.checkImageState($(this));
            });

            SQ.hb.headerSection.on('is-transparent.stax', function() {
                SQ.hb.checkImageState($(this));
            });

            if (typeof staxResponsive !== 'undefined') {
                var zIndex = 100;
                if (!$body.hasClass('elementor-editor-active')) {
                    SQ.hb.headerSection.each(function(index) {
                        $(this).attr('data-index', zIndex);
                        $(this).css({'z-index': zIndex});
                        zIndex = zIndex - 2;
                    });
                }
            }

            SQ.hb.calcMenuOffset();

            $body.trigger('header-init-done');

        },

        scrollTo: function() {
            /* Click event */
            $('.header-section .menu-default a[href*="#"]').on('click', function(event) {

                var speed = 1000;
                var target = SQ.hb.getRelatedContent(this);

                if (target.length) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top,
                    }, speed);

                    return false;
                }
            });

        },

        /* Get section or article by href */
        getRelatedContent: function(el) {

            var href = $(el).attr('href');
            var split = href.split('#');
            if (split.length > 1) {
                href = '#' + split[1];
            }
            return $(href);
        },

        headerSectionCalc: function(forceCheckHeaders) {

            if (forceCheckHeaders === undefined) {
                forceCheckHeaders = false;
            }

            if (SQ.hb.headerSection.length < 1) {
                return false;
            }

            var scrollTop = $window.scrollTop();
            var _this = this;

            /* remember if a previous header was transparent */
            var prevTransparent = false;

            var transparentExtraHeight = 0;

            /* check if we are scrolling in reverse */
            var reverseScroll = scrollTop < _this.prevScroll;

            if (reverseScroll) {
                SQ.hb.headerSection.addClass('reverse');
            } else {
                SQ.hb.headerSection.removeClass('reverse');
            }

            SQ.hb.headerSection.each(function(index) {
                if ($body.hasClass('stax-editor-enabled')) {
                    var closestZone = $(this).closest('.hb-zone-wrap');

                    if (closestZone.length && !closestZone.hasClass('zone-active')) {
                        return false;
                    }
                }

                var resizeOffset = $(this).attr('data-resize-offset')
                    ? parseInt($(this).attr('data-resize-offset'), 10)
                    : 0;
                var slideUpOffset = $(this).attr('data-slideup-offset') ? parseInt($(this).attr('data-slideup-offset'),
                    10) : 300;
                var transparentOffset = $(this).attr('data-transparent') ? parseInt($(this).attr('data-transparent'),
                    10) : 0;
                var currentHeader = $(this);
                var currentHeaderContent = currentHeader;
                var prevHeight = ($(SQ.hb.adminBar).length && parseInt($(window).width()) > 600) ? parseInt(
                    $(SQ.hb.adminBar).height(), 10) : 0;
                var prevHeightAll = 0;
                var topValue;
                var topValueChange = false;
                var resizeDif = 0;
                var cancelTransparent = false;

                /* Sticky */
                if ($(this).hasClass('header-sticky')) {

                    /* top value change */
                    if (index > 0) {
                        var beforeHeaderSections = SQ.hb.headerSection.slice(0, index);

                        beforeHeaderSections.each(function(i) {
                            if ($(this).hasClass('header-sticky') && $(this).hasClass('is-sticky') &&
                                !$(this).hasClass('is-slide-up')) {
                                if ($(this).hasClass('is-resized') && $(this).attr('data-resize-height')) {
                                    prevHeight += parseInt($(this).attr('data-resize-height'), 10);
                                } else {
                                    prevHeight += parseInt($(this).attr('data-height'), 10);
                                }
                            }
                            prevHeightAll += parseInt($(this).attr('data-height'), 10);
                        });
                    }

                    topValue = prevHeight;

                    var afterHeaderSections = SQ.hb.headerSection.slice(index);

                    /* If we add headers that are not sticky after current one */
                    if (afterHeaderSections.length) {
                        afterHeaderSections.each(function() {
                            if (!$(this).hasClass('header-sticky')) {
                                cancelTransparent = true;
                            }
                        });
                    }

                    //Add our sticky wrapper
                    if ($(this).parent('.' + SQ.hb.stickyWrapperClass).length === 0) {
                        $(this).wrap('<div class=\'' + SQ.hb.stickyWrapperClass + '\'></div>');

                        var editModeWrap = $(this).parent().prev('.hb-header-content-wrap');
                        if (editModeWrap.length) {
                            $(this).prepend(editModeWrap);
                        }
                    }

                    var stickyWrapper = $(this).parent('.' + SQ.hb.stickyWrapperClass);

                    if (forceCheckHeaders === true || !stickyWrapper[0].style['height']) {
                        if (($(this).hasClass('header-transparent') || prevTransparent === true) &&
                            cancelTransparent === false) {
                            stickyWrapper.css({'height': ''});
                        } else {
                            stickyWrapper.css({'height': currentHeader.attr('data-height')});
                        }
                    }

                    var elementOffset = stickyWrapper.offset().top + transparentExtraHeight,
                        distance = (elementOffset - scrollTop) + 1;

                    if ($(this).hasClass('header-transparent') && cancelTransparent === false) {
                        prevTransparent = true;
                        transparentExtraHeight += parseInt($(this).attr('data-height'), 10);
                    }

                    /* On normal scroll. */
                    if (!reverseScroll) {

                        /* Skip if the header is slide up - no actions needed */
                        if ($(this).hasClass('is-slide-up')) {
                            return;
                        }

                        /* Make Sticky */
                        if ((distance - prevHeight) < 1 || $(this).hasClass('header-transparent')) {
                            if (!$(this).hasClass('is-sticky')) {
                                $(this).addClass('is-sticky');
                                $(this).trigger('is-sticky');

                                topValueChange = true;
                                forceCheckHeaders = true;
                            }
                        } else {
                            if ($(this).hasClass('is-sticky')) {
                                $(this).removeClass('is-sticky');
                                $(this).trigger('is-sticky');
                            }
                            topValue = '';
                            topValueChange = true;
                            forceCheckHeaders = true;
                        }

                        if ($(this).hasClass('is-sticky')) {

                            /* Resize */
                            if ($(this).hasClass('header-resize') && !$(this).hasClass('is-resized')) {

                                resizeDif = parseInt(currentHeader.attr('data-height'), 10) -
                                    parseInt(currentHeader.attr('data-resize-height'), 10);
                                if ((distance - prevHeight + resizeOffset + resizeDif) <= 0) {
                                    $(this).addClass('is-resized');
                                    $(this).trigger('is-resized');

                                    forceCheckHeaders = true;
                                }
                            }

                            /* Slide Up */
                            if ($(this).hasClass('header-slide-up') && !$(this).hasClass('is-slide-up')
                                && (distance - prevHeight + slideUpOffset) < 0) {

                                //make sure to display headers on page load
                                if (SQ.hb.prevScroll > 0) {

                                    $(this).addClass('is-slide-up');
                                    $(this).trigger('is-slide-up');

                                    topValue = '-' + $(this).attr('data-height') + 'px';
                                    topValueChange = true;

                                    forceCheckHeaders = true;
                                }
                            }

                            /* Transparent */

                            if ($(this).hasClass('header-transparent')) {
                                if (cancelTransparent === true || (distance - prevHeight + transparentOffset) <= 1) {
                                    if ($(this).hasClass('is-transparent')) {
                                        $(this).removeClass('is-transparent');
                                        $(this).trigger('is-transparent');
                                    }
                                } else if (!$(this).hasClass('is-transparent')) {
                                    $(this).addClass('is-transparent');
                                    $(this).trigger('is-transparent');
                                }
                            }

                        } else {
                            $(this).removeClass('is-transparent');
                            $(this).trigger('is-transparent');
                        }

                    } else if (reverseScroll && scrollTop >= 0) { /* Reverse scroll */

                        /* Resize */
                        if ($(this).hasClass('header-resize') && $(this).hasClass('is-resized')) {

                            resizeDif = parseInt(currentHeader.attr('data-height'), 10);

                            if ((distance - prevHeight + resizeOffset + resizeDif) >= 0) {
                                $(this).removeClass('is-resized');
                                $(this).trigger('is-resized');

                                forceCheckHeaders = true;
                            }
                        }

                        /* Slide Up */
                        if ($(this).hasClass('header-slide-up') && $(this).hasClass('is-slide-up')) {

                            $(this).removeClass('is-slide-up');
                            topValueChange = true;

                            forceCheckHeaders = true;
                        }

                        /* Sticky */
                        if ($(this).hasClass('is-sticky') && !$(this).hasClass('header-transparent')) {
                            if ((scrollTop + prevHeight) <= elementOffset) {
                                $(this).removeClass('is-sticky');
                                $(this).trigger('is-sticky');

                                topValue = '';
                                topValueChange = true;
                                forceCheckHeaders = true;
                            }
                        }

                        /* Transparent */
                        if ($(this).hasClass('header-transparent')) {

                            if ((distance - prevHeight + transparentOffset) < 1 || cancelTransparent === true) {
                                if ($(this).hasClass('is-transparent')) {
                                    $(this).removeClass('is-transparent');
                                    $(this).trigger('is-transparent');
                                }
                            } else {
                                if (!$(this).hasClass('is-transparent')) {
                                    $(this).addClass('is-transparent');
                                    $(this).trigger('is-transparent');
                                }
                            }

                            /* Multiple headers with transparent */
                            if (prevHeightAll > 0 && $(window).scrollTop() == 0) {
                                topValue = prevHeightAll;
                                topValueChange = true;
                            }
                        }

                    }

                    if (true === topValueChange || SQ.hb.prevScroll === 0 || forceCheckHeaders === true) {
                        currentHeaderContent.css({'top': topValue});
                    }
                } else {
                    $(this).removeClass('is-sticky');
                    $(this).trigger('is-sticky');
                    currentHeaderContent.css({'top': ''});
                    if ($(this).parent('.' + SQ.hb.stickyWrapperClass).length) {
                        $(this).parent('.' + SQ.hb.stickyWrapperClass).css({'height': ''});
                    }
                }
            });

            this.prevScroll = scrollTop;
        },

        trimLongMenu: function() {
            $('.flexMenu > ul').flexMenu({
                //'linkText': '',
                //'linkTextAll': '',
                'threshold': 2, // [integer] If there are this many items or fewer in the list, we will not display a "View More" link and will instead let the list break to the next line. This is useful in cases where adding a "view more" link would actually cause more things to break  to the next line.
                'cutoff': 1, //[integer] If there is space for this many or fewer items outside our "more" popup, just move everything into the more menu. In that case, also use linkTextAll and linkTitleAll instead of linkText and linkTitle. To disable this feature, just set this value to 0.
            });
        },

        calcMenuOffset: function() {

            var menus = $('.header-section .has-submenu').find('.submenu'),
                screenWidth = $(window).width();

            menus.show();

            menus.each(function() {
                var thisMenu = $(this),
                    thisMenuWidth = thisMenu.outerWidth(),
                    thisMenuLeft = thisMenu.offset().left;
                var p = $(this).parents('.has-submenu').last();
                if (screenWidth < (thisMenuWidth + thisMenuLeft)) {
                    p.addClass('submenu-left');
                } else {
                    p.removeClass('submenu-left');
                }
            });

            menus.css('display', '');

        },
        initAccordion: function() {
            $('.sq-accordion > .sq-toggle-acc').on('click', function(e) {
                e.preventDefault();

                var $this = $(this);
                var accordionContainer = $(this).parent().parent();

                var toggles = $(accordionContainer).find('.sq-toggle-acc');
                var contents = $(accordionContainer).find('.sq-inner-acc');

                $(toggles).each(function() {
                    if ($(this)[0] === $this[0]) {
                        $(this).addClass('active');
                        $(this).next('.sq-inner-acc').slideDown(350);
                        $(this).find('.acc-icon').addClass($(this).data('icon-minimized'));
                        $(this).find('.acc-icon').removeClass($(this).data('icon-maximized'));
                    } else {
                        $(this).removeClass('active');
                        $(this).next('.sq-inner-acc').slideUp(350);
                        $(this).find('.acc-icon').addClass($(this).data('icon-maximized'));
                        $(this).find('.acc-icon').removeClass($(this).data('icon-minimized'));
                    }
                });
            });
        },

        initTabs: function() {
            $('.sq-toggle-tab').on('click', function(e) {
                e.preventDefault();

                $(this).addClass('active');
                $(this).siblings().removeClass('active');
            });
        },

        initSideMenu: function() {
            $body.on('click', '.stax-sidemenu-trigger', function(e) {
                e.preventDefault();

                $('.stax-sidemenu-wrapper').removeClass('sidemenu-open');
                $(this).parent().find('.stax-sidemenu-wrapper').addClass('sidemenu-open');
            });

            $body.on('click', '.stax-sidemenu-close', function(e) {
                e.preventDefault();
                e.stopPropagation();

                $('.stax-sidemenu-wrapper').removeClass('sidemenu-open');
            });
        },

        initMegaMenu: function() {
            $body.on('mouseenter', '.sq-el-megamenu', function(e) {
                e.preventDefault();
                const container = $(this).find('.stax-megamenu-wrapper').first();
                const rowSizes = $(this).closest('.sq-row').get(0).getBoundingClientRect();

                if ($body.hasClass('stax-editor-enabled')) {
                    const sizes = $(this).get(0).getBoundingClientRect();
                    container.css('left', -(sizes.left - rowSizes.left));
                } else {
                    container.css('left', 0);
                }

                container.css('width', rowSizes.width);
                container.css('z-index', 1);
                container.addClass('megamenu-open');
            });

            if ($body.hasClass('stax-editor-enabled')) {
                $body.on('click', '.sq-el-megamenu', function(e) {
                    e.preventDefault();
                    const container = $(this).find('.stax-megamenu-wrapper').first();
                    const rowSizes = $(this).closest('.sq-row').get(0).getBoundingClientRect();

                    const sizes = $(this).get(0).getBoundingClientRect();

                    container.css('left', -(sizes.left - rowSizes.left));
                    container.css('width', rowSizes.width);
                    container.css('z-index', 1);
                    container.addClass('megamenu-open').addClass('megamenu-show-close');
                });
            }

            $body.on('mouseleave', '.sq-el-megamenu', function(e) {
                e.preventDefault();
                const container = $(this).find('.stax-megamenu-wrapper').first();

                if (!container.hasClass('megamenu-show-close')) {
                    container.removeClass('megamenu-open');
                }
            });

            $body.on('click', '.stax-megamenu-close', function(e) {
                e.preventDefault();
                e.stopPropagation();

                $(this).
                    closest('.stax-megamenu-wrapper').
                    removeClass('megamenu-open').
                    removeClass('megamenu-show-close');
            });
        },

        initSearchSubmit: function() {
            $(document).on('click', '.floating-placeholder .mdi', function(e) {
                e.preventDefault();
                $(this).closest('form').submit();
            });
        },

        initHamburgerMenu: function() {
            $body.on('click', '.sq-menu-modburger-toggle', function(e) {
                e.preventDefault();
                $(this).parent().find('.menu-burger').addClass('sq-menu-modburger-show');
                $('html').addClass('sq-menu-modburger-visible');
                var container = $(this).closest('.header-section');
                var column = $(this).closest('.header-item');
                if (!container) {
                    container = $(this).closest('.hb-section');
                }

                if (!container) {
                    return;
                }

                container.css({'z-index': 100000003});
                column.css({'z-index': 100000003});
            });

            $body.on('click', '.sq-menu-modburger-close', function(e) {
                e.preventDefault();
                $(this).closest('.menu-burger').removeClass('sq-menu-modburger-show');
                $('html').removeClass('sq-menu-modburger-visible');
                var container = $(this).closest('.header-section');
                var column = $(this).closest('.header-item');
                if (!container)
                    container = $(this).closest('.hb-section');

                if (!container)
                    return;

                container.css({'z-index': container.data('index')});
                column.css({'z-index': 'initial'});

            });
        },
    };

    var $window = $(window),
        $body = $('body');

    $(document).ready(SQ.hb.init);

    $window.on( 'load', SQ.hb.onLoad);

    $window.on('debouncedresize', SQ.hb.onResize);

    $window.on('resize', SQ.hb.onClassicResize);

    $(window).on('scroll', function() {
        SQ.hb.onScroll();
    });

})(jQuery);
