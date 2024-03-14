(function ($) {
    'use strict';

    var all_currencies = cbcurrencyconverter_elementor.all_currencies;
    //console.log(all_currencies);


    //alert('hi there');

//for elementor widget render
    $( window ).on( 'elementor/frontend/init', function() {
       // console.log(elementorFrontend);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/cbcurrencyconverter.default', function($scope, $){
            //var $element = $scope.find('.cbxgooglemap_embed');

            //console.log($scope);



        });
    });//end for elementor widget render

    $(document).ready(function ($) {



    });//end dom ready
})(jQuery);