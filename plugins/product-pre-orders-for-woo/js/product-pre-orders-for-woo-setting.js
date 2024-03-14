/*
Author: Truong
Author URI: http://villatheme.com
Copyright 2020-2021 villatheme.com. All rights reserved.
*/

jQuery(document).ready(function ($) {
    "use strict";
    $('.tabular.menu .item').vi_tab();

    $('.dropdown').dropdown();

    /*
    * Check box pre order variable product
    */
    jQuery(function ($) {
        if ($('#product-type').val() == 'simple') {
            $('input#simple_preorder').on('change', function () {
                show_and_hide_panel();
            });

            function show_and_hide_panel() {
                var is_pre_order = $('input#simple_preorder:checked').length;

                var hide_classes = '.hide_if_downloadable, .hide_if_pre_order';
                var show_classes = '.show_if_downloadable, .show_if_pre_order';

                $(hide_classes).show();
                $(show_classes).hide();
                if (is_pre_order) {
                    $('.show_if_pre_order').show();
                } else {
                    $("li.general_tab").find('a').trigger("click");
                }
                if (is_pre_order) {
                    $('.hide_if_pre_order').hide();
                }
            }

            show_and_hide_panel()
        }
    });

    $('#_wpro_manage_price').on('change', function () {
        let checkbox = $(this).prop('checked');
        if (checkbox == true) {
            $('#_wpro_price').prop('required', true);
        } else {
            $('#_wpro_price').prop('required', false);
        }
    });

    jQuery(this).find('._wpro_manage_price').map(function () {
        let checkbox = $(this).prop('checked');
        let $container = $(this).closest('div.woocommerce_options_panel');
        if (checkbox == true) {
            $container.find('input#_wpro_price').prop('required', true);
            $container.find('div.wpro-form-manage-price').removeClass('hidden');
        } else {
            $container.find('input#_wpro_price').prop('required', false);
            $container.find('div.wpro-form-manage-price').addClass('hidden');
        }
    });

    $('#_wpro_manage_price').on('click', function () {
        let checkbox = $(this).prop('checked');
        let $container = $(this).closest('div.woocommerce_options_panel');
        if (checkbox == true) {
            $container.find('div.wpro-form-manage-price').removeClass('hidden');
        } else {
            $container.find('div.wpro-form-manage-price').addClass('hidden');
        }
    });

    jQuery(this).find('._wpro_discount_price').map(function () {
        let radio = $(this).prop('checked');
        let $container = $(this).closest('div.woocommerce_options_panel');
        if (radio == true) {
            $container.find('._wpro_price_adjustment_type_simple').removeClass('hidden');
        }
    });

    $('#_wpro_discount_price').on('click', function () {
        $('._wpro_price_adjustment_type_simple').removeClass('hidden');
    });

    jQuery(this).find('._wpro_price_type').map(function () {
        let radio = $(this).prop('checked');
        let $container = $(this).closest('div.woocommerce_options_panel');
        if (radio == true) {
            $container.find('._wpro_price_adjustment_type_simple').addClass('hidden');
        }
    });

    $('#_wpro_price_manually').on('click', function () {
        $('._wpro_price_adjustment_type_simple').addClass('hidden');
    });

    jQuery(this).find('._wpro_mark_up').map(function () {
        let radio = $(this).prop('checked');
        let $container = $(this).closest('div.woocommerce_options_panel');
        if (radio == true) {
            $container.find('._wpro_price_adjustment_type_simple').removeClass('hidden');
        }
    });

    $('#_wpro_mark_up').on('click', function () {
        $('._wpro_price_adjustment_type_simple').removeClass('hidden');
    });

    $('#product-type').on('change', function () {
        if ($('#product-type').val() !== 'simple') {
            $('.pre-order-tab_options').hide();
        }
        if ($('#product-type').val() === 'simple') {
            if ($('input#simple_preorder:checked').val() == 'on') {
                $('.pre-order-tab_options').show();
            } else {
                $('.pre-order-tab_options').hide();
            }
        }
        if ($('#product-type').val() === 'variable') {
            $('#simple_preorder').removeAttr('checked');
        }
    });

    /*Color picker*/
    if ($('.color-picker').length !== 0) {
        $('.color-picker').iris({
            change: function (event, ui) {
                $(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
            },
            hide: true,
            border: true
        }).click(function () {
            $('.iris-picker').hide();
            $(this).closest('td').find('.iris-picker').show();
        });

        $('body').click(function () {
            $('.iris-picker').hide();
        });
        $('.color-picker').click(function (event) {
            event.stopPropagation();
        });
    }

    /*
    * table date simple
     */
    jQuery('._wpro_dates_field').each(function () {
        var dateToday = new Date();
        jQuery(this).find('input[type="text"]').datepicker({
            defaultDate: '',
            dateFormat: 'yy-mm-dd',
            numberOfMonths: 1,
            showButtonPanel: true,
            minDate: dateToday,
            onSelect: function () {
                date_picker_select($(this));
            }
        });
    });

    /**
     * Jquery Variable product
     */
    jQuery('#woocommerce-product-data').on('woocommerce_variations_loaded', function (event) {
        // $('._wpro_manage_price_variable').on('change', function () {
        //     let checkbox = $(this).prop('checked');
        //     if (checkbox == true) {
        //         $('._wpro_price_variable').prop('required', true);
        //     } else {
        //         $('._wpro_price_variable').prop('required', false);
        //     }
        // });

        //fix
        // $('.woocommerce_variation').each(function () {
        //     let manage_price = $(this).find('._wpro_manage_price_variable')[0];
        //     console.log(manage_price.value)
        //     if (manage_price.value == 'yes') {
        //         $('button.save-variation-changes').on('click', function () {
        //                 alert(price.status);
        //                 return false;
        //             });
        //     }
        // });
        // $('.woocommerce_variation').each(function () {
        //     // let manage_price = $(this).find('._wpro_manage_price_variable')[0];
        //     let val_price = $(this).find('._wpro_price_variable')[0];
        //         $('._wpro_manage_price_variable').on('change', function () {
        //             let checkbox = $(this).prop('checked');
        //             console.log(checkbox)
        //             console.log(val_price.value)
        //             $('button.save-variation-changes').on('click', function () {
        //             if (checkbox == true && val_price.value == '') {
        //                     alert(price.status);
        //                     return false;
        //             }
        //         });
        //     });
        // });

        /**
         * Pre Order variation checkbox
         */
        let $product_data = jQuery(this);
        $product_data.find('._wpro_variable_is_preorder').map(function () {
            let checkbox = $(this).prop('checked');
            let $container = $(this).closest('div.woocommerce_variable_attributes');
            if (checkbox == true) {
                $container.find('div.wpro-pre-order-variable').removeClass('hidden');
            } else {
                $container.find('div.wpro-pre-order-variable').addClass('hidden');
            }
        });

        $(document).on('click', '._wpro_variable_is_preorder', function () {
            let checkbox = $(this).prop('checked');
            let $container = $(this).closest('div.woocommerce_variable_attributes');
            if (checkbox == true) {
                $container.find('div.wpro-pre-order-variable').removeClass('hidden');
            } else {
                $container.find('div.wpro-pre-order-variable').addClass('hidden');
            }
        });

        $product_data.find('._wpro_manage_price_variable').map(function () {
            let checkbox = $(this).prop('checked');
            let $container = $(this).closest('div.wpro-pre-order-variation');
            if (checkbox == true) {
                $container.find('input._wpro_price_variable').prop('required', true);
                $container.find('div.wpro-manage-price').removeClass('hidden');
            } else {
                $container.find('input._wpro_price_variable').prop('required', false);
                $container.find('div.wpro-manage-price').addClass('hidden');
            }
        });

        $(document).on('click', '._wpro_manage_price_variable', function () {
            let checkbox = $(this).prop('checked');
            let $container = $(this).closest('div.wpro-pre-order-variation');
            if (checkbox == true) {
                $container.find('div.wpro-manage-price').removeClass('hidden');
            } else {
                $container.find('div.wpro-manage-price').addClass('hidden');
            }
        });

        /**
         * input date
         */
        jQuery('._wpro_date_variable').each(function () {
            var dateToday = new Date();
            jQuery(this).datepicker({
                defaultDate: '',
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                showButtonPanel: true,
                minDate: dateToday,
                onSelect: function () {
                    date_picker_select($(this));
                }
            });
        });

        /**
         *  Price adjustment radio
         */
        $("input:radio").on('click', function () {
            var $box = $(this);
            if ($box.is(":checked")) {
                var group = "input:radio[name='" + $box.attr("name") + "']";
                $(group).prop("checked", false);
                $box.prop("checked", true);
            } else {
                $box.prop("checked", false);
            }
        });

        $product_data.find('._wpro_price_manually_variable').map(function () {
            let radio = $(this).prop('checked');
            let $container = $(this).closest('div.wpro-pre-order-variation');
            if (radio == true) {
                $container.find('._wpro_price_adjustment_type_variable').addClass('hidden');
            }
        });

        $(document).on('click', '._wpro_price_manually_variable', function () {
            let $radio = $(this);
            let $container = $radio.closest('.wpro-pre-order-variation');
            $container.find('._wpro_price_adjustment_type_variable').addClass('hidden');
        });

        $product_data.find('._wpro_discount_price_variable').map(function () {
            let radio = $(this).prop('checked');
            let $container = $(this).closest('div.wpro-pre-order-variation');
            if (radio == true) {
                $container.find('._wpro_price_adjustment_type_variable').removeClass('hidden');
            }
        });

        $(document).on('click', '._wpro_discount_price_variable', function () {
            let $radio = $(this);
            let $container = $radio.closest('.wpro-pre-order-variation');
            $container.find('._wpro_price_adjustment_type_variable').removeClass('hidden');
        });

        $product_data.find('._wpro_markup_variable').map(function () {
            let radio = $(this).prop('checked');
            let $container = $(this).closest('div.wpro-pre-order-variation');
            if (radio == true) {
                $container.find('._wpro_price_adjustment_type_variable').removeClass('hidden');
            }
        });

        $(document).on('click', '._wpro_markup_variable', function () {
            let $radio = $(this);
            let $container = $radio.closest('.wpro-pre-order-variation');
            $container.find('._wpro_price_adjustment_type_variable').removeClass('hidden');
        });
    });

    /*
    * Adjustment type price
     */
    $("input:radio").on('click', function () {
        var $box = $(this);
        if ($box.is(":checked")) {
            var group = "input:radio[name='" + $box.attr("name") + "']";
            $(group).prop("checked", false);
            $box.prop("checked", true);
        } else {
            $box.prop("checked", false);
        }
    });

    // Date picker fields.
    function date_picker_select(datepicker) {
        var option = $(datepicker).next().is('.hasDatepicker') ? 'minDate' : 'maxDate',
            otherDateField = 'minDate' === option ? $(datepicker).next() : $(datepicker).prev(),
            date = $(datepicker).datepicker('getDate');

        $(otherDateField).datepicker('option', option, date);
        $(datepicker).change();
    }
});