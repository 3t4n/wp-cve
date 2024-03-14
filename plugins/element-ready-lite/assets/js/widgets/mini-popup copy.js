(function($) {

    
    var Element_Ready_Menu_Mini_PopUp =  function($scope, $) {

        /* TOGGLE */
        let $header_pop_content = $scope.find('.element-ready-submenu');
        let $header_pop_close = $scope.find('.er-ready-count-close-btn');
      
        let $er_pop = $scope.find('.er-ready-cart-popup');
       
        $er_pop.on('click', function(event) {
             
            if($header_pop_content.hasClass('open')){

                $header_pop_content.removeClass('open');
              
            }else{
                $header_pop_content.addClass('open')
                
            }
      
        });

        $header_pop_close.on('click',function(){
            $header_pop_content.removeClass('open');
           
        }); 
    
    
    }; 

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/element-ready-global-popup.default', Element_Ready_Menu_Mini_PopUp);
    });
})(jQuery);