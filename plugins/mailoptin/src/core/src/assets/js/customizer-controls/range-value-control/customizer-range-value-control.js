/**
 * Script run inside a Customizer control sidebar
 */
(function ($) {

    wp.customize.bind('ready', function () {
        rangeSlider();
    });

    var valueFormatter = function () {

        window.crvc_init = false;

        if (window.crvc_init === true) return;

        window.crvc_init = true;

        var slider = $('.range-slider'),
            value = $('.range-slider__value');

        slider.each(function () {

            value.each(function () {
                var value = $(this).prev().attr('value');
                var suffix = ($(this).prev().attr('suffix')) ? $(this).prev().attr('suffix') : '';
                $(this).html(value + suffix);
            });
        });
    };

    var rangeSlider = function () {

        valueFormatter();
        $(document).on('input', '.range-slider__range', function () {
            valueFormatter();
            var suffix = ($(this).attr('suffix')) ? $(this).attr('suffix') : '';
            $(this).next('.range-slider__value').html(this.value + suffix);
        });
    };

})(jQuery);
