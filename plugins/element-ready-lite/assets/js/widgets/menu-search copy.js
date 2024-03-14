(function($) {

    var Element_Ready_Menu_Search = function($scope, $) {
        //===== Search 
     
        let $search_opened = $scope.find(".element-ready-search-close");
        let $search_       = $scope.find(".element-ready-search-open");
        let $search_box    = $scope.find(".element-ready-search-box");

        $search_.on('click', function() {
            $search_box.addClass('open')
        });
        
        $search_opened.on('click', function() {
            $search_box.removeClass('open')
        });

    };

    $(window).on('elementor/frontend/init', function() {
        
        elementorFrontend.hooks.addAction('frontend/element_ready/element-ready-popup-search.default', Element_Ready_Menu_Search);
       
    });
})(jQuery);