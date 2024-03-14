(function($) {

    var Element_Ready_Blog_Module_Grid_Post_Script = function($scope, $) {

        var tabs_area      = $scope.find('.er-post-grid-tabs');
        var container      = $scope.find('.trending-news-item .element-ready-post-meta');
        var filter_content = '';
        var filterby       = '';

        if(tabs_area.length){

            tabs_area.find('a').on('click', function(){
                
                $.each( tabs_area.find('a'), function( key, value ) {
                    $(this).removeClass('active');
                }); 
         
                filter_content = $(this).addClass('active').data('filter').toString();
                $.each( container, function( key, value ) {
                   filterby = $(this).data('filterby').toString().split(',');
                   if(filterby.includes(filter_content) || filter_content =='all'){
                     $(this).parents('.trending-news-item').parent().fadeIn('slow');
                   }else{
                    $(this).parents('.trending-news-item').parent().fadeOut(1);
                   }
                });

            });
        }
    }

    $(window).on('elementor/frontend/init', function() {
        
        elementorFrontend.hooks.addAction('frontend/element_ready/element-ready-grid-post.default', Element_Ready_Blog_Module_Grid_Post_Script);
       
    });
})(jQuery);