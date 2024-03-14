jQuery(function ($) {

    var setup = function (widgetForm) {

        if (typeof widgetForm.data('initialised') == 'undefined') {

            $(widgetForm).find('.lsow-widget-input-datepicker').each(function (index, element) {
                $(element).datepicker();
            });

            $(widgetForm).data('initialised', true);
        }
        else {
            setTimeout(function () {
                setup(widgetForm);
            }, 500);
        }
    };

    $(document).on('sowsetupform', function (e) {
        var $form = $(e.target);

        if ($form.is('.siteorigin-widget-field-repeater-item-form')) {
            if ($form.is(':visible')) {
                setup($form);
            }
            else {
                $form.on('slideToggleOpenComplete', function onSlideToggleComplete() {
                    if ($form.is(':visible')) {
                        setup($form);
                        $form.off('slideToggleOpenComplete');
                    }
                })
            }
        }
        else {
            setup($form);
        }
    });

});