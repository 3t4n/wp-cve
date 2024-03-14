(function ($) {
"use strict";

    var Tab1 = function($scope, $) {
        $scope.find('.xl-tab').each(function() {
            var tabArea = $(this).find("ul.tab-area li"),
                tabContent = $(this).find('.tab-content');
            $(tabArea).add(tabContent).each(function() {
                $(this).siblings(':first-child').addClass('active');
            });
 
            $(tabArea).on('click', function() {

                $(this).each(function() {
                    var tabIndex = $(this).index();
                    $(this).siblings().removeClass('active');
                    $(this).parent('ul').next(".tab-wrap").find(tabContent).removeClass('active');
                    $(this).addClass('active');
                    $(this).parent('ul').next(".tab-wrap").find(tabContent).eq(tabIndex).addClass('active');
                    var to_bottom =  $(this).parent('ul').next(".tab-wrap").find('.tab-content.active');
                    if ($(this).parents('.xl-tab').hasClass('has-scroll')) {
                        $('html, body').animate({
                            scrollTop: to_bottom.offset().top
                        }, 500); 
                    }                   
                })
            });
        });
    };

    var AccorDl = function ($scope, $) {

        $scope.find('.xldacdn').each(function () {

            var settings = $(this).data('xld');
            var faction = $('.accordion.'+ settings['id']+ ' ' +'li:eq(0) .xltbhd');
            var saction = $('.accordion.'+ settings['id']+ ' ' +'.xltbhd');

            faction.addClass('active').next().slideDown();

            saction.click(function(j) {
                var dropDown = $(this).closest('li').find('.xltbc');

                $(this).closest('.accordion').find('.xltbc').not(dropDown).slideUp();

                if ($(this).hasClass('active')) {
                    $(this).removeClass('active');
                } else {
                    $(this).closest('.accordion').find('.xltbhd.active').removeClass('active');
                    $(this).addClass('active');
                }

                dropDown.stop(false, true).slideToggle();

                j.preventDefault();
            });


        });

    };

    var TabVrtkl = function ($scope, $) {

        $scope.find('.xlvtab1').each(function() {
            var tabArea = "ul.tabs li",
                tabContent = '.tabs_item';
            $(tabArea).add(tabContent).each(function() {
                $(this).siblings(':first-child').addClass('current');
            });
            $(tabArea).on('click', function() {
                $(this).each(function() {
                    
                    var index = $(this).closest('li').index();
                    $(this).siblings().removeClass('current');
                    $(this).parent('ul').next(".tab_content").find(tabContent).not('div.tabs_item:eq(' + index + ')').slideUp();
                    $(this).addClass('current');
                    $(this).parent('ul').next(".tab_content").find('div.tabs_item:eq(' + index + ')').slideDown();
                })
            });
        });

    };

    var TabSwitcH = function ($scope, $) {

        $scope.find('.xldswitcher').each(function () {

            var settings = $(this).data('xld');
            var toggleSwitch = $('.d-' + settings['id']+ ' ' +'label.switch');
            var monthTabTitle = $('.d-' + settings['id']+ ' ' +'li.month');
            var yearTabTitle = $('.d-' + settings['id']+ ' ' +'li.year');
            var monthTabContent = $('.d-' + settings['id']+ ' ' +'#month');
            var yearTabContent = $('.d-' + settings['id']+ ' ' +'#year');
            
            // hidden show deafult;
            monthTabContent.slideUp();
            yearTabContent.slideDown();
            function toggleHandle() {
                if(toggleSwitch.hasClass('off')) {
                    yearTabContent.slideDown();
                    monthTabContent.slideUp();
                    monthTabTitle.addClass('active');
                    yearTabTitle.removeClass('active');
                }else {
                    monthTabContent.slideDown();
                    yearTabContent.slideUp();
                    yearTabTitle.addClass('active');
                    monthTabTitle.removeClass('active');
                }
            };
            monthTabTitle.on('click', function () {
                toggleSwitch.addClass('on').removeClass('off');
                toggleHandle();
                return false;
            });
            yearTabTitle.on('click', function () {
                toggleSwitch.addClass('off').removeClass('on');
                toggleHandle();
                return false;
            });
            toggleSwitch.on('click', function () {
               // toggleSwitch.toggleClass('on off');
                toggleHandle();
            });


        });

    };

    $(window).on('elementor/frontend/init', function () {

        if (elementorFrontend.isEditMode()) {

            elementorFrontend.hooks.addAction('frontend/element_ready/xltab1.default', Tab1);
            elementorFrontend.hooks.addAction('frontend/element_ready/aetabswitch.default', TabSwitcH);
            elementorFrontend.hooks.addAction('frontend/element_ready/xlvtab1.default', TabVrtkl);
            elementorFrontend.hooks.addAction('frontend/element_ready/xlacrdn1.default', AccorDl);
        }
        else {

            elementorFrontend.hooks.addAction('frontend/element_ready/xltab1.default', Tab1);
            elementorFrontend.hooks.addAction('frontend/element_ready/aetabswitch.default', TabSwitcH);
            elementorFrontend.hooks.addAction('frontend/element_ready/xlvtab1.default', TabVrtkl);
            elementorFrontend.hooks.addAction('frontend/element_ready/xlacrdn1.default', AccorDl);
        }
    });

})(jQuery);
