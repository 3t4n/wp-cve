(function ($) {
    'use strict';

    //var all_currencies = cbcurrencyconverter_elementor_admin.all_currencies;
    //console.log(all_currencies);

    elementor.hooks.addAction( 'panel/open_editor/widget/cbcurrencyconverter', function( panel, model, view ) {
        ///var $element = view.$el.find( '.elementor-selector' );
        //console.log(panel.$el);

        //console.log($('#elementor-control-default-c744'));

        //console.log(view.$el.find('label'));
        //console.log(view.$el.find('select[data-setting="decimal_point"]'));

        //console.log(panel.$el.find('label'));

        //console.log(panel.$el.find('select'));
        //console.log(panel.$el.find('select[data-setting="layout"]'));
        //console.log(panel.$el.find('select[data-setting="calc_to_currencies"]'));

        //var controlModels = panel.getCurrentPageView().collection.models;
        //console.log(controlModels);

        /*const settingsModel = model.get( 'settings' );
        settingsModel.on( 'change', ( changedModel ) => {
            console.log( changedModel );
        } );*/


        /*panel.$el.find('select[data-setting="calc_from_currencies"]').on('change', function (e){
           var $values = $(this).select2('val');
           console.log($values);


           var $data = [];
           $values.forEach(function (value, index){
               $data.push({
                   id: value,
                   text: all_currencies[value]+' - '+value
               });
           });

            panel.$el.find('select[data-setting="calc_from_currency"]').select2('destroy').empty().select2({data : $data});
       });

        panel.$el.find('select[data-setting="calc_to_currencies"]').on('change', function (e){
           var $values = $(this).select2('val');
           var $data = [];
           $values.forEach(function (value, index){
               $data.push({
                   id: value,
                   text: all_currencies[value]+' - '+value
               });
           });

            panel.$el.find('select[data-setting="calc_to_currency"]').select2('destroy').empty().select2({data : $data});
       });*/

       /* if ( $element.length ) {
            $element.click( function() {
                alert( 'Some Message' );
            } );
        }*/
    } );


})(jQuery);