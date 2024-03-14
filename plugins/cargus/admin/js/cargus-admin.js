(function ($) {
    'use strict';

    jQuery(document).ready(function () {
        // initialize the select 2 fields.
        $('.cargus-select2').select2();
    });

    /**
     * Chose what happens when pressing the "Adauga colet" button.
     */
    $(document).on('click', '#fb_button_add_cargus_colete_comanda', function (e) {
        // check if on the right page.
        if ($('#cargus_woocommerce_detalii_awb').length) {
            if ($('#cargus_colete_comanda').attr('max') == '' || parseInt($('#cargus_colete_comanda').val()) < parseInt($('#cargus_colete_comanda').attr('max'))) {
                // disable select 2 field before cloning block.
                $('.repeatable_block').last().find('select.cargus-select2:not(.pudo-point-select)').select2('destroy');
                // clone the repeatable block.
                $('.repeatable_block').first().before(createRepeatableBlock($('.repeatable_block').last().clone(), $('#cargus_colete_comanda')));
                // trigger the select reactivation event.
                $(document).trigger('wc-renhanced-select-init');
                // decrement the colete number.
                $('#cargus_colete_comanda').val(parseInt($('#cargus_colete_comanda').val()) + 1);
                $('#fb_cargus_colete_comanda b').text(parseInt(($('#fb_cargus_colete_comanda b').text())) + 1);
            } else {
                alert("Ati ajuns la limita numarului de colete pentru Seriviciul Cargus selectat.");
            }
        }
    });

    /**
     * Reinitialize the select 2 fields.
     */
    $(document).on('wc-renhanced-select-init', function () {
        $('.cargus-select2:not(.pudo-point-select)').select2();
    });

    /**
     * Chose what happens when pressing the "Elimina colet" button.
     */
    $(document).on('click', '.button.remove', function (e) {
        if ($('#cargus_woocommerce_detalii_awb').length) {
            // get deleted item index.
            let index = $(this).attr('data-index');
            // check if there is more than one repetable block.
            if ($('#cargus_colete_comanda').val() > 1) {
                // remove the block.
                $('.repeatable_block_' + index).remove();
                // decrement the blocks count.
                $('#cargus_colete_comanda').val(parseInt($('#cargus_colete_comanda').val()) - 1);

                $('#fb_cargus_colete_comanda b').text(parseInt(($('#fb_cargus_colete_comanda b').text())) - 1);

                // decrement the index for the remailng repeatable bloks.
                $('.repeatable_block').each(function () {
                    if (getRepeatableBlockIndex($(this)) > index) {
                        decremendtRepeatableBlockIndex($(this));
                    }
                });
            } else {
                alert("Nu puteți șterge singurul colet existent.");
            }
        }
    });

    /**
     * Create the repeatable block html that's about to be cloned.
     *
     * @param {*} repeatableBlock repeatable block html
     * @param {*} index           the block index
     * @returns
     */
    const createRepeatableBlock = (repeatableBlock, index) => {

        let oldIndex = repeatableBlock.attr('class').split(' ').pop();
        repeatableBlock.removeClass(oldIndex);
        repeatableBlock.addClass('repeatable_block_' + (parseInt(index.val()) + 1));
        repeatableBlock.find('.button.remove').attr('data-index', parseInt(index.val()) + 1);
        repeatableBlock.find('.cargus-numar-colet').text(parseInt(index.val()) + 1);

        repeatableBlock.find('input').each(function () {
            $(this).val('');
        })

        repeatableBlock.find('select option').each(function () {
            $(this).removeAttr('selected');
        })

        return repeatableBlock;

    }

    /**
     * Decrement the repeatable block index.
     *
     * @param {*obj} repeatableBlock repeatable block html
     */
    const decremendtRepeatableBlockIndex = (repeatableBlock) => {

        let currentindexClass = repeatableBlock.attr('class').split(' ').pop();
        let indexArray = currentindexClass.split("_");
        indexArray[indexArray.length - 1] = parseInt(getRepeatableBlockIndex(repeatableBlock)) - 1;
        repeatableBlock.find('.button.remove').attr('data-index', parseInt(getRepeatableBlockIndex(repeatableBlock)) - 1);
        repeatableBlock.find('.cargus-numar-colet').text(parseInt(getRepeatableBlockIndex(repeatableBlock)) - 1);
        repeatableBlock.removeClass(currentindexClass);
        currentindexClass = indexArray.join("_");
        repeatableBlock.addClass(currentindexClass);

    }

    /**
     * Get the repeatable block index.
     *
     * @param {obj} repeatableBlock repeatableBlock html.
     * @returns array
     */
    const getRepeatableBlockIndex = (repeatableBlock) => {
        let currentindexClass = repeatableBlock.attr('class').split(' ').pop();
        let indexArray = currentindexClass.split("_");

        return indexArray[indexArray.length - 1];
    }

    /**
     * Open the print link in a new window.
     */
    $(document).on('click', '.note a.print_awb', function (event) {
        event.preventDefault();

        let link = $(this).attr('href');
        window.open(link, '_blank', 'location=yes,height=800,width=800,scrollbars=yes,status=yes');
    });

    /**
     * Condition the tva field availability
     */
    jQuery(document).ready(function () {

        conditionNotField('input#woocommerce_cargus_fixed', 'input#woocommerce_cargus_shipping_cost_tax');
        $(document).on('focusout', 'input#woocommerce_cargus_fixed', function () {
            conditionNotField('input#woocommerce_cargus_fixed', 'input#woocommerce_cargus_shipping_cost_tax');
        });

        const validityRes = ['1', '2'];
        const onlyPrintRes = ['2'];
        conditionDisableField('select#woocommerce_cargus_return-awb', validityRes, 'input#woocommerce_cargus_awb-validity');
        conditionDisableField('select#woocommerce_cargus_return-awb', onlyPrintRes, 'select#woocommerce_cargus_return-awb-print');
        $(document).on('change', 'select#woocommerce_cargus_return-awb', function () {
            conditionDisableField('select#woocommerce_cargus_return-awb', validityRes, 'input#woocommerce_cargus_awb-validity');
            conditionDisableField('select#woocommerce_cargus_return-awb', onlyPrintRes, 'select#woocommerce_cargus_return-awb-print');
        });
    });

    /**
     * Get the repeatable block index.
     *
     * @param {obj} repeatableBlock repeatableBlock html.
     * @returns array
     */
    const conditionDisableField = (conditioningField, conditioningFieldValues, conditionedField) => {
        if (conditioningFieldValues.includes($(conditioningField).val())) {
            $(conditionedField).prop("disabled", false);
        } else {
            $(conditionedField).prop("disabled", true);
        }
    }

    /**
     * Get the repeatable block index.
     *
     * @param {obj} repeatableBlock repeatableBlock html.
     * @returns array
     */
    const conditionNotField = (conditioningField, conditionedField) => {
        if ('' !== $(conditioningField).val()) {
            $(conditionedField).parents().eq(3).hide();
        } else {
            $(conditionedField).parents().eq(3).show();
        }
    }

})(jQuery);
