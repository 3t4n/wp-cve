(function($) {

    
    var Element_Ready_Menu_SigninUp = function($scope, $) {

        /* TOGGLE */
        $(document).on('click', '.header-control .close', function() {
            $(this).closest('.element-ready-dropdown').removeClass('open');
        });

        $(document).on('click', function(event) {

            var _target = $(event.target).closest('.element-ready-dropdown');
            var _allparent = $('.element-ready-dropdown');

            if (_target.length > 0) {
                _allparent.not(_target).removeClass('open');
                if (
                    $(event.target).is('[data-gnash="gnash-dropdown"]') ||
                    $(event.target).closest('[data-gnash="gnash-dropdown"]').length > 0
                ) {
                    _target.toggleClass('open');
                    return false;
                }
            } else {
                $('.element-ready-dropdown').removeClass('open');
            }

        });

        $tab_nav       = $scope.find('.nav-tabs .nav-link');
        $tab_container = $scope.find('.tab-content .tab-pane');
        var active_id = '';
        $scope.find('.nav-tabs .nav-link').on('click',function(){
            // nav
            $.each($tab_nav, function () {
                $(this).removeClass('active');
            });

            active_id = $(this).attr('href').replace('#','');
            $(this).addClass('active');
            // content
            $.each($tab_container, function () {
                $(this).removeClass('active');
                $(this).removeClass('show');
                var $cur = $(this).attr('id');
             
                if(active_id == $cur){
                    $(this).addClass('show active');
                }
            });

        });

        $scope.find('.element-ready-user-interface, .modal-header .close').on('click', function(){
            $scope.find('.modal').toggle();
       });
    };

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/element-ready-user-sign-signup-popup.default', Element_Ready_Menu_SigninUp);
    });
})(jQuery);