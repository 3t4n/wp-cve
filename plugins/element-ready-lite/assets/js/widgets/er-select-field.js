(function($) {
    
    var Elements_Ready_Drop_Down_Select_Box = function($scope, $) {
    
        var element = $scope.find('.eready-dropdown-wrapper.er-open-link select');
        var new_tab = element.attr('data-open_tab');
        
        element.on('change', function(){

          if(new_tab == 'yes'){
            window.open($(this).val(), '_blank');
          }else{
            window.open($(this).val(), '_self');
          }  
       
        });
    }
    
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/Elements_Ready_Drop_Down_Select_Box.default', Elements_Ready_Drop_Down_Select_Box);
    });

})(jQuery);