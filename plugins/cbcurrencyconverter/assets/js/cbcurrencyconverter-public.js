(function ($) {
    'use strict';

  /*  (function($) {

        var Defaults = $.fn.select2.amd.require('select2/defaults');

        $.extend(Defaults.defaults, {
            searchInputPlaceholder: ''
        });

        var SearchDropdown = $.fn.select2.amd.require('select2/dropdown/search');

        var _renderSearchDropdown = SearchDropdown.prototype.render;

        SearchDropdown.prototype.render = function(decorated) {

            // invoke parent method
            var $rendered = _renderSearchDropdown.apply(this, Array.prototype.slice.apply(arguments));

            this.$search.attr('placeholder', this.options.get('searchInputPlaceholder'));

            return $rendered;
        };

    })(window.jQuery);*/

    $(document).ready(function () {
        //console.log(cbcurrencyconverter_public);


        $('.cbcurrencyconverter_cal_wrapper').each(function (index, element) {
            var $cal_wrapper = $(element);

            //hide the result window first
            //$cal_wrapper.find('.cbcurrencyconverter_result').hide();



            /*$cal_wrapper.find('.cbcurrencyconverter_cal_from').select2({
                placeholder: cbcurrencyconverter_public.please_select,
                allowClear: false,
                theme: 'default select2-container--cbxcc'
            });


            $cal_wrapper.find('.cbcurrencyconverter_cal_to').select2({
                placeholder: cbcurrencyconverter_public.please_select,
                allowClear: false,
                theme: 'default select2-container--cbxcc'
            });*/

            $cal_wrapper.find('.cbcurrencyconverter_select2').each(function (sel_index, sel_element){
                var $element = $(sel_element);

                var $allow_clear = parseInt($element.data('allow-clear'));
                var $show_plalceholder = parseInt($element.data('show-placeholder'));
                var $show_flag = $element.hasClass('cbcurrencyconverter_select2_flag');
                var $placeholder = $element.data('placeholder');
                var parent_wrapper = $element.closest('.cbcurrencyconverter_form_field');
                var $hide_search = parseInt($element.data('hide-search'));

                var $select2_options = {
                    //placeholder: cbcurrencyconverter_public.please_select,
                    placeholder: $show_plalceholder ? $placeholder : '',
                    allowClear: $allow_clear ? true : false,
                    theme: 'default select2-container--cbxcc',
                    dropdownParent: $(sel_element).closest('.cbcurrencyconverter_form_field_input'),
                    minimumResultsForSearch : $hide_search ? -1 : 1
                };

                if($show_flag){
                    $select2_options.templateResult =  (state) => {
                        if (!state.id) {
                            return state.text;
                        }

                        var $state = $(
                            //'<span><img src="' + baseUrl + '/' + state.element.value.toLowerCase() + '.png" class="img-flag" /> ' + state.text + '</span>'
                            '<span><i class="currency-flag currency-flag-'+state.element.value.toLowerCase()+'"> </i>' + state.text + '</span>'
                        );
                        return $state;
                    };
                }

                //console.log($show_flag);

                $element.select2($select2_options).on('select2:select', function (e) {
                    var data = e.params.data;
                    parent_wrapper.find('.cbcurrencyconverter_label i').text(data.id);

                    $cal_wrapper.find('.cbcurrencyconverter_calculate').trigger('click');

                }).on('select2:opening', function (e) {
                    parent_wrapper.find('input.select2-search__field').prop('placeholder', $placeholder);
                });
            });



            $cal_wrapper.on('click', '.cbcurrencyconverter_calculate', function (e) {
                e.preventDefault();

                var $this        = $(this);
                var button_ref   = $this.attr('data-ref');
                var button_busy  = Number($this.attr('data-busy'));
                var decimal_point = $this.attr('data-decimal-point');


                var data = {};

                data.nonce   = cbcurrencyconverter_public.nonce;
                data.decimal = decimal_point;
                data.ref     = button_ref;

                data.error = '';

                data.amount = $this.closest('.cbcurrencyconverter_cal_wrapper').find('.cbcurrencyconverter_cal_amount').val();
                data.from   = $this.closest('.cbcurrencyconverter_cal_wrapper').find('.cbcurrencyconverter_cal_from').val();
                data.to     = $this.closest('.cbcurrencyconverter_cal_wrapper').find('.cbcurrencyconverter_cal_to').val();


                // validation check
                if (data.to === '') {
                    data.error = cbcurrencyconverter_public.empty_selection;
                }

                if (data.from === '') {
                    data.error = cbcurrencyconverter_public.empty_selection;
                }

                if (data.error === '' && (data.to === data.from)) {
                    data.error = cbcurrencyconverter_public.same_selection;
                }


                if (data.error === '' && button_busy === 0) {
                    $this.closest('.cbcurrencyconverter_cal_wrapper').find('.cbcurrencyconverter_result').html(cbcurrencyconverter_public.please_wait);
                    $this.closest('.cbcurrencyconverter_cal_wrapper').find('.cbcurrencyconverter_result').show();

                    $this.data('busy', '1');
                    $this.addClass('cbcurrencyconverter_calculate_busy');
                    $this.attr('disabled', true);

                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: cbcurrencyconverter_public.ajaxurl,
                        data: {action: 'currrency_convert', cbcurrencyconverter_data: data},
                        success: function (result, textStatus, XMLHttpRequest) {

                            $this.closest('.cbcurrencyconverter_cal_wrapper').find('.cbcurrencyconverter_result').html(data.amount + ' ' + data.from + ' = ' + result + ' ' + data.to);
                            //$this.closest('.cbcurrencyconverter_cal_wrapper').find('.cbcurrencyconverter_result').show();

                            $this.data('busy', '0');
                            $this.removeClass('cbcurrencyconverter_calculate_busy');
                            $this.attr('disabled', false);
                        }
                    });// end of ajax
                }// end of if error msg null
                else {
                    //$this.closest('.cbcurrencyconverter_cal_wrapper').find('.cbcurrencyconverter_result').show();
                    $this.closest('.cbcurrencyconverter_cal_wrapper').find('.cbcurrencyconverter_result').html(data.error);
                }
            });
        });
    });// end of function

}(jQuery));