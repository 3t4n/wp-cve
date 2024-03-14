;(function($) {

  var Element_Ready_Col_Wrapper_Modules = function( $scope, $ ){
   
      if($scope.data('link_active') == 'yes'){

          $($scope).css('cursor', 'pointer');

          $($scope).on('click', function(){

              if($scope.data('is_external') =='on'){
                  window.open($scope.data('url'), '_blank');
              }else{
                  window.location.href = $scope.data('url');
              }
          
          });

      }
  }

  $(window).on('elementor/frontend/init', function () {

      elementorFrontend.hooks.addAction( 'frontend/element_ready/column', Element_Ready_Col_Wrapper_Modules );
    
  });
  
})(jQuery);