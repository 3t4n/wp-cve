;(function ($) {
    function _ready() {
        $('#extra_settings input').each(function (index, element) {
            var isDisabled = $(element).is(':disabled');
            if (isDisabled) {
                $(element).prop('disabled', false);
            } else {
                // Handle input is not disabled
            }
        });
        $('#extra_settings select').each(function (index, element) {
            var isDisabled = $(element).is(':disabled');
            if (isDisabled) {
                $(element).prop('disabled', false);
            } else {
                // Handle input is not disabled
            }
        });
    }

    $(document).ready(_ready);
})(jQuery);