(function($) {

    var Element_Ready_Toggle_Tab_Price_Handaler = function($scope, $) {

        var $element = $scope.find('#switch-toggle-tab');
        
        if ($element.length) {

            var toggleSwitch = $('#switch-toggle-tab label.switch');
            var TabTitle = $('#switch-toggle-tab li');

            var monthTabTitle = $scope.find('#switch-toggle-tab li.month');
            var yearTabTitle = $scope.find('#switch-toggle-tab li.year');
            var monthTabContent = $scope.find('#month');
            var yearTabContent = $scope.find('#year');

            // hidden show deafult;
            monthTabContent.show();
            yearTabContent.hide();

            function toggleHandle() {

                if (toggleSwitch.hasClass('on')) {
                    yearTabContent.hide();
                    monthTabContent.show();
                    monthTabTitle.addClass('active');
                    yearTabTitle.removeClass('active');
                } else {
                    monthTabContent.hide();
                    yearTabContent.show();
                    yearTabTitle.addClass('active');
                    monthTabTitle.removeClass('active');
                }
            };

            monthTabTitle.on('click', function() {
                toggleSwitch.addClass('on').removeClass('off');
                toggleHandle();
                return false;
            });

            yearTabTitle.on('click', function() {
                toggleSwitch.addClass('off').removeClass('on');
                toggleHandle();
                return false;
            });

            toggleSwitch.on('click', function() {
                toggleSwitch.toggleClass('on off');
                toggleHandle();
            });
        }

    }



    $(window).on('elementor/frontend/init', function() {
        
        elementorFrontend.hooks.addAction('frontend/element_ready/element-ready-toggle-pricing-tab.default', Element_Ready_Toggle_Tab_Price_Handaler);

       
    });
})(jQuery);