(function ($) {
    'use strict';

    var all_currencies = cbcurrencyconverter_widget.all_currencies;

    var $select_two_options = {
        placeholder: cbcurrencyconverter_widget.please_select,
        allowClear: false
    };

    $(document).ready(function ($) {
        $(document).on('widget-added', function (e, widget) {
            widget.find('.cbcurrencyconverter-admin-widget-wrap').each(function (index, element){
                var $element = $(element);
                var $old = parseInt($element.data('old'));

                if($old === 0){
                    $element.data('old', 1);
                    $element.find('.selecttwo-select').select2($select_two_options);

                    $element.find('.calc_from_currencies').on('change', function (e){
                        var $values = $(this).select2('val');
                        var $data = [];
                        $values.forEach(function (value, index){
                            $data.push({
                                id: value,
                                text: all_currencies[value]+' - '+value
                            });
                        });

                        $element.find('.calc_from_currency').select2('destroy').empty().select2({data : $data});
                    });

                    $element.find('.calc_to_currencies').on('change', function (e){
                        var $values = $(this).select2('val');
                        var $data = [];
                        $values.forEach(function (value, index){
                            $data.push({
                                id: value,
                                text: all_currencies[value]+' - '+value
                            });
                        });

                        $element.find('.calc_to_currency').select2('destroy').empty().select2({data : $data});
                    });
                }
            });


        });

        $(document).on('widget-updated', function (e, widget) {
            widget.find('.cbcurrencyconverter-admin-widget-wrap').each(function (index, element){
                var $element = $(element);
                var $old = parseInt($element.data('old'));

                if($old === 0){
                    $element.data('old', 1);
                    $element.find('.selecttwo-select').select2($select_two_options);

                    $element.find('.calc_from_currencies').on('change', function (e){
                        var $values = $(this).select2('val');
                        var $data = [];
                        $values.forEach(function (value, index){
                            $data.push({
                                id: value,
                                text: all_currencies[value]+' - '+value
                            });
                        });

                        $element.find('.calc_from_currency').select2('destroy').empty().select2({data : $data});
                    });

                    $element.find('.calc_to_currencies').on('change', function (e){
                        var $values = $(this).select2('val');
                        var $data = [];
                        $values.forEach(function (value, index){
                            $data.push({
                                id: value,
                                text: all_currencies[value]+' - '+value
                            });
                        });

                        $element.find('.calc_to_currency').select2('destroy').empty().select2({data : $data});
                    });
                }
            });

        });


        $('#widgets-right .cbcurrencyconverter-admin-widget-wrap').each(function (index, element){

           var $element = $(element);
           var $old = parseInt($element.data('old'));

            if($old === 0){
               $element.data('old', 1);
               $element.find('.selecttwo-select').select2($select_two_options);

                $element.find('.calc_from_currencies').on('change', function (e){
                    var $values = $(this).select2('val');
                    var $data = [];
                    $values.forEach(function (value, index){
                        $data.push({
                            id: value,
                            text: all_currencies[value]+' - '+value
                        });
                    });

                    $element.find('.calc_from_currency').select2('destroy').empty().select2({data : $data});
                });

                $element.find('.calc_to_currencies').on('change', function (e){
                    var $values = $(this).select2('val');
                    var $data = [];
                    $values.forEach(function (value, index){
                        $data.push({
                            id: value,
                            text: all_currencies[value]+' - '+value
                        });
                    });

                    $element.find('.calc_to_currency').select2('destroy').empty().select2({data : $data});
                });
           }

        });
    });//end dom ready
})(jQuery);