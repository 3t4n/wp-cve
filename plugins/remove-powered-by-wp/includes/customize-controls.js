/*
 * Version: 1.0.1
*/



(function($) {

    $(document).ready(function() {
        $('.customize-control-webd-checkbox-multiple input[type="checkbox"]').on('change', function() {
            checkbox_values = jQuery(this).parents('.customize-control').find('input[type="checkbox"]:checked').map(
                function() {
                    return this.value;
                }
            ).get().join(',');
            $(this).parents('.customize-control').find('input[type="hidden"]').val(checkbox_values).trigger('change');
        });
    });

})(jQuery);
