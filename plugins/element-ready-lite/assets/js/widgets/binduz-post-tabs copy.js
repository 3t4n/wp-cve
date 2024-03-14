;(function ($) {
    "use strict";
  
    var Element_Ready_Binduz_Tab__Widget = function($scope, $){ 

        var $element          = $scope.find( '.er-binduz-nav li a' );
        var $element_navs     = $scope.find( '.er-binduz-nav li a' );
        var $element_tab_pane = $scope.find( '.er-binduz-news-tab-content .er-b-tab-pane' );
        
        $element.on('click',function(e){
            e.preventDefault()
            var tab_id = $(this).attr('id').replace('#','');
            $element_navs.each(function( index ) {
                $(this).removeClass('er-b-active');
            });

            $(this).addClass('er-b-active');

            $element_tab_pane.each(function( index ) {
                
                if($(this).attr('id') == tab_id){
                    $(this).addClass('er-b-show er-b-active').fadeIn(3000);
                }else{
                    $(this).removeClass('er-b-active').fadeOut(3000);
                    $(this).removeClass('er-b-show');
                }
            });

           
        });

        var videoModal  = $scope.find(".binduz-er-video-popup");
        videoModal.modalVideo();
    }

	$(window).on('elementor/frontend/init', function () {
       
        elementorFrontend.hooks.addAction( 'frontend/element_ready/element-ready-binduz-grid-post-tabs.default', Element_Ready_Binduz_Tab__Widget );
    });
})(jQuery);