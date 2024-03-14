(function ($) {
    'use strict';

    $('input#enable_advanced_capabilities')
        .on('change', function (event) {
            let checked = this.checked;

            if (checked)
                $('div.capability-editor').slideDown();
            else
                $('div.capability-editor').slideUp();

            $('div.capability-selection input').prop('disabled', !checked);
        })
        .trigger('change');

    $('input[data-toggle]')
        .on('change', function (event) {
            let $checkbox = $(this),
                state = $checkbox.prop('checked'),
                toggle_elements = $checkbox.data('toggle'),
                $toggle_elements = $(toggle_elements);

            if (state)
                $toggle_elements.slideDown();
            else
                $toggle_elements.slideUp();
        })
        .trigger('change');

}(jQuery));
