(function($) {

    var Element_Ready_Pdf_View_Widget = function($scope, $) {

        var el_pdf_btn = $scope.find('.element__ready__pdf_btn');
        var pdf__viewer = $scope.find('.element__ready__pdf__viewer__wrap');
        var url         = pdf__viewer.data('url');
        var width       = pdf__viewer.data('width');
        var width_unit  = pdf__viewer.data('width_unit');
        var height      = pdf__viewer.data('height');
        var height_unit = pdf__viewer.data('height_unit');
        var page        = parseInt( pdf__viewer.data('page') );
        
        var options = {
            height: height+height_unit,
            width : width+width_unit,
            page  : page + 1,
           
        };

        if(el_pdf_btn.length){
           
            el_pdf_btn.css({'cursor':'pointer'})
            pdf__viewer.hide();
            $(el_pdf_btn).on('click', function(){
                pdf__viewer.fadeToggle('slow','swing');
            });

        } 
        
        if(PDFObject.supportsPDFs){
            PDFObject.embed(url, pdf__viewer, options); 
        }
        
    }

    $(window).on('elementor/frontend/init', function() {
        
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Pdf_View_Widget.default', Element_Ready_Pdf_View_Widget);
       
    });
})(jQuery);