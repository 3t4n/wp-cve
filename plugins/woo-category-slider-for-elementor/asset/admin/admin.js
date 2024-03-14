 var foodMenuFilter = function ($scope, $) {

       function slider_active(){
            var oc = $('.owl-carousel');
          // var ocOptions = oc.data('carousel-options');
          oc.each(function(index){
            var ocOptions = $(this).data('carousel-options');
                $(this).owlCarousel( ocOptions );
          });

        }

    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/BlueLion_Food_Menus.default', foodMenuFilter);
    });