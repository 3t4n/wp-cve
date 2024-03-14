
    (function ($) {

        "use strict";
    
        jQuery(document).on('ready', function () {

            var product_vertical_menu = function($scope,$){
            
                var currentTab = $scope.find('.woo-ready-product-vertical-menu'),
                currentLayout = currentTab.data('layout');
        
                if ( currentLayout == "always-open") {
                    return;
                }
        
                $scope.find( '.element-ready-widget-title').on('click',function () {
                  
                    $(this).toggleClass('show');
                    $scope.find( '.wooready-vertical-menu').slideToggle();
                });
            };

            $(window).on('elementor/frontend/init', function() {
                 elementorFrontend.hooks.addAction('frontend/element_ready/element-ready-vartical-mega-menu.default', product_vertical_menu);
            });
        });

    })(jQuery);