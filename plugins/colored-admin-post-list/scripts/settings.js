jQuery(document).ready(function($) {
    $(".capl-wp-color-picker").wpColorPicker();

    $("#capl-form-reset-to-defaults").bind("submit", function() {
        var confirm_message = $("#capl-button-reset-to-defaults").data("message");
        if(confirm(confirm_message)) {
            return true;
        }
        return false;
    });   
});